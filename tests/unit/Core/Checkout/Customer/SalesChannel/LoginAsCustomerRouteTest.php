<?php declare(strict_types=1);

namespace Shopware\Tests\Unit\Core\Checkout\Customer\SalesChannel;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Checkout\Customer\LoginAsCustomerTokenGenerator;
use Shopware\Core\Checkout\Customer\SalesChannel\AccountService;
use Shopware\Core\Checkout\Customer\SalesChannel\LoginAsCustomerRoute;
use Shopware\Core\Framework\Log\Package;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\Framework\Validation\DataBag\RequestDataBag;
use Shopware\Core\Framework\Validation\DataValidator;
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

        $loginAsCustomerTokenGenerator = new LoginAsCustomerTokenGenerator('testAppSecret');

        $token = $loginAsCustomerTokenGenerator->generate(
            TestDefaults::SALES_CHANNEL,
            $customerId,
            $userId
        );

        $accountService = $this->createMock(AccountService::class);
        $accountService->method('loginById')->willReturn('newToken');

        $route = new LoginAsCustomerRoute(
            $accountService,
            $loginAsCustomerTokenGenerator,
            $this->createMock(EventDispatcherInterface::class),
            $this->createMock(DataValidator::class),
        );

        $salesChannelContext = $this->createMock(SalesChannelContext::class);
        $salesChannelContext->method('getSalesChannelId')->willReturn(TestDefaults::SALES_CHANNEL);

        $dataBag = new RequestDataBag([
            LoginAsCustomerRoute::TOKEN => $token,
            LoginAsCustomerRoute::CUSTOMER_ID => $customerId,
            LoginAsCustomerRoute::USER_ID => $userId,
        ]);

        $response = $route->loginAsCustomer($dataBag, $salesChannelContext);

        static::assertEquals('newToken', $response->getToken());
    }
}
