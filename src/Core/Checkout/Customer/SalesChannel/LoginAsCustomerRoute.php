<?php declare(strict_types=1);

namespace Shopware\Core\Checkout\Customer\SalesChannel;

use Shopware\Core\Checkout\Customer\LoginAsCustomerTokenGenerator;
use Shopware\Core\Framework\DataAbstractionLayer\Validation\EntityExists;
use Shopware\Core\Framework\Log\Package;
use Shopware\Core\Framework\Plugin\Exception\DecorationPatternException;
use Shopware\Core\Framework\Validation\BuildValidationEvent;
use Shopware\Core\Framework\Validation\Constraint\Uuid;
use Shopware\Core\Framework\Validation\DataBag\DataBag;
use Shopware\Core\Framework\Validation\DataBag\RequestDataBag;
use Shopware\Core\Framework\Validation\DataValidationDefinition;
use Shopware\Core\Framework\Validation\DataValidator;
use Shopware\Core\Framework\Validation\Exception\ConstraintViolationException;
use Shopware\Core\System\SalesChannel\ContextTokenResponse;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[Route(defaults: ['_routeScope' => ['store-api'], '_contextTokenRequired' => false])]
#[Package('checkout')]
class LoginAsCustomerRoute extends AbstractLoginAsCustomerRoute
{
    final public const TOKEN = 'token';
    final public const SALES_CHANNEL_ID = 'salesChannelId';
    final public const CUSTOMER_ID = 'customerId';
    final public const USER_ID = 'userId';

    /**
     * @internal
     */
    public function __construct(
        private readonly AbstractAccountService $accountService,
        private readonly LoginAsCustomerTokenGenerator $tokenGenerator,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly DataValidator $validator
    ) {
    }

    public function getDecorated(): AbstractLoginAsCustomerRoute
    {
        throw new DecorationPatternException(self::class);
    }

    #[Route(path: '/store-api/account/login/customer', name: 'store-api.account.login-as-customer', methods: ['POST'])]
    public function loginAsCustomer(RequestDataBag $requestDataBag, SalesChannelContext $context): ContextTokenResponse
    {
        $this->validateRequestDataFields($requestDataBag, $context);

        $token = $requestDataBag->getString(self::TOKEN);
        $salesChannelId = $requestDataBag->getString(self::SALES_CHANNEL_ID);
        $customerId = $requestDataBag->getString(self::CUSTOMER_ID);
        $userId = $requestDataBag->getString(self::USER_ID);

        $this->tokenGenerator->validate($token, $salesChannelId, $customerId, $userId);

        $newToken = $this->accountService->loginById($customerId, $context, $salesChannelId, $userId);

        return new ContextTokenResponse($newToken);
    }

    /**
     * @throws ConstraintViolationException
     */
    private function validateRequestDataFields(DataBag $data, SalesChannelContext $context): void
    {
        $definition = new DataValidationDefinition('login.impersonation');

        $frameworkContext = $context->getContext();

        $definition
            ->add(self::TOKEN, new NotBlank())
            ->add(self::SALES_CHANNEL_ID, new Uuid(), new EntityExists(['entity' => 'sales_channel', 'context' => $frameworkContext]))
            ->add(self::CUSTOMER_ID, new Uuid(), new EntityExists(['entity' => 'customer', 'context' => $frameworkContext]))
            ->add(self::USER_ID, new Uuid(), new EntityExists(['entity' => 'user', 'context' => $frameworkContext]));

        $validationEvent = new BuildValidationEvent($definition, $data, $frameworkContext);
        $this->eventDispatcher->dispatch($validationEvent, $validationEvent->getName());

        $this->validator->validate($data->all(), $definition);
    }
}
