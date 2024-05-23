<?php declare(strict_types=1);

namespace Shopware\Tests\Unit\Core\Checkout\Customer;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Checkout\Customer\Exception\InvalidLoginAsCustomerTokenException;
use Shopware\Core\Checkout\Customer\LoginAsCustomerTokenGenerator;
use Shopware\Core\Framework\Log\Package;

/**
 * @internal
 */
#[Package('checkout')]
#[CoversClass(LoginAsCustomerTokenGenerator::class)]
class LoginAsCustomerTokenGeneratorTest extends TestCase
{
    private LoginAsCustomerTokenGenerator $tokenGenerator;

    private const SALES_CHANNEL_ID = '0146543d6a6241718da05d5ee6f6891a';

    private const CUSTOMER_ID = 'bcf76884cb764eb2b9650bb2fcf1073e';

    private const USER_ID = 'bcf76884cb764eb2b9650bb2fcf1073f';

    protected function setUp(): void
    {
        $this->tokenGenerator = new LoginAsCustomerTokenGenerator('testAppSecret');
    }

    public function testGenerate(): void
    {
        $token = $this->tokenGenerator->generate(self::SALES_CHANNEL_ID, self::CUSTOMER_ID, self::USER_ID);

        static::assertSame('851923ad26ff2a45eee0b21155506921ea6f40e942c1ee22844ef4e5917cb454', $token);
    }

    #[DoesNotPerformAssertions]
    public function testValidate(): void
    {
        $this->tokenGenerator->validate('851923ad26ff2a45eee0b21155506921ea6f40e942c1ee22844ef4e5917cb454', self::SALES_CHANNEL_ID, self::CUSTOMER_ID, self::USER_ID);
    }

    public function testValidateWithInvalidToken(): void
    {
        $this->expectException(InvalidLoginAsCustomerTokenException::class);

        $this->tokenGenerator->validate('invalidToken', self::SALES_CHANNEL_ID, self::CUSTOMER_ID, self::USER_ID);
    }
}
