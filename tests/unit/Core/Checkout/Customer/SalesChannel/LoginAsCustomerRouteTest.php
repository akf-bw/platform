<?php declare(strict_types=1);

namespace Shopware\Tests\Unit\Core\Checkout\Customer\SalesChannel;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Checkout\Customer\CustomerEntity;
use Shopware\Core\Checkout\Customer\LoginAsCustomerTokenGenerator;
use Shopware\Core\Checkout\Customer\SalesChannel\AccountService;
use Shopware\Core\Checkout\Customer\SalesChannel\LoginAsCustomerRoute;
use Shopware\Core\Framework\Log\Package;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\Framework\Validation\DataBag\RequestDataBag;
use Shopware\Core\Framework\Validation\DataValidator;
use Shopware\Core\System\SalesChannel\ContextTokenResponse;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\Test\TestDefaults;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * @internal
 */
#[Package('checkout')]
#[CoversClass(LoginAsCustomerRoute::class)]
class LoginAsCustomerRouteTest extends TestCase
{
    public function testLoginAsCustomer(): void
    {
        $customerId = Uuid::randomHex();
        $userId = Uuid::randomHex();

        $customerEntity = new CustomerEntity();
        $customerEntity->setDoubleOptInRegistration(false);
        $customerEntity->setId($customerId);
        $customerEntity->setEmail('customer@example.com');
        $customerEntity->setGuest(false);

        $dispatcher = $this->createMock(EventDispatcherInterface::class);

        $loginAsCustomerTokenGenerator = $this->createMock(LoginAsCustomerTokenGenerator::class);

        $route = new LoginAsCustomerRoute(
            $this->createMock(AccountService::class),
            $loginAsCustomerTokenGenerator,
            $dispatcher,
            $this->createMock(DataValidator::class),
        );

        $salesChannelContext = $this->createMock(SalesChannelContext::class);
        $salesChannelContext->method('getSalesChannelId')->willReturn(TestDefaults::SALES_CHANNEL);

        $loginAsCustomerTokenGenerator->method('validate')->willReturn(true);

        $dataBag = new RequestDataBag([
            LoginAsCustomerRoute::TOKEN => 'token-1',
            LoginAsCustomerRoute::SALES_CHANNEL_ID => TestDefaults::SALES_CHANNEL,
            LoginAsCustomerRoute::CUSTOMER_ID => $customerId,
            LoginAsCustomerRoute::USER_ID => $userId,
        ]);

        $response = $route->loginAsCustomer($dataBag, $salesChannelContext);

        static::assertInstanceOf(ContextTokenResponse::class, $response);
    }
}
