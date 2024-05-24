<?php declare(strict_types=1);

namespace Shopware\Core\Checkout\Customer;

use Shopware\Core\Framework\Log\Package;

#[Package('checkout')]
class LoginAsCustomerTokenGenerator
{
    /**
     * @internal
     */
    public function __construct(
        private readonly string $appSecret
    ) {
    }

    public function generate(string $salesChannelId, string $customerId, string $userId): string
    {
        $tokenData = [
            'salesChannelId' => $salesChannelId,
            'customerId' => $customerId,
            'userId' => $userId,
        ];

        $data = json_encode($tokenData);

        if ($data === false) {
            throw CustomerException::invalidToken($salesChannelId . ':' . $customerId . ':' . $userId);
        }

        return hash_hmac('sha256', $data, $this->appSecret);
    }

    public function validate(string $givenToken, string $salesChannelId, string $customerId, string $userId): void
    {
        $expectedToken = $this->generate($salesChannelId, $customerId, $userId);

        if (!hash_equals($expectedToken, $givenToken)) {
            throw CustomerException::invalidToken($givenToken);
        }
    }
}
