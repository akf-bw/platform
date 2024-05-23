<?php declare(strict_types=1);

namespace Shopware\Core\Checkout\Customer\Exception;

use Shopware\Core\Checkout\Customer\CustomerException;
use Shopware\Core\Framework\Log\Package;
use Symfony\Component\HttpFoundation\Response;

#[Package('checkout')]
class InvalidLoginAsCustomerTokenException extends CustomerException
{
    public function __construct(string $token)
    {
        parent::__construct(
            Response::HTTP_BAD_REQUEST,
            self::LOGIN_AS_CUSTOMER_INVALID_TOKEN_CODE,
            'The token "{{ token }}" is invalid.',
            ['token' => $token]
        );
    }
}
