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

        $loginAsCustomerTokenGenerator = new LoginAsCustomerTokenGenerator('testAppSecret');

        $token = $loginAsCustomerTokenGenerator->generate(
            TestDefaults::SALES_CHANNEL,
            $customerId,
            $userId
        );

        $route = new LoginAsCustomerRoute(
            $this->createMock(AccountService::class),
            $loginAsCustomerTokenGenerator,
            $this->createMock(EventDispatcherInterface::class),
            $this->createMock(DataValidator::class),
        );

        $dataBag = new RequestDataBag([
            LoginAsCustomerRoute::TOKEN => $token,
            LoginAsCustomerRoute::SALES_CHANNEL_ID => TestDefaults::SALES_CHANNEL,
            LoginAsCustomerRoute::CUSTOMER_ID => $customerId,
            LoginAsCustomerRoute::USER_ID => $userId,
        ]);

        $salesChannelContext = $this->createMock(SalesChannelContext::class);

        $response = $route->loginAsCustomer($dataBag, $salesChannelContext);

        static::assertInstanceOf(ContextTokenResponse::class, $response);
    }
}
