<?php declare(strict_types=1);

namespace Shopware\Core\Checkout\Customer\SalesChannel;

use Shopware\Core\Checkout\Customer\CustomerEntity;
use Shopware\Core\Framework\Log\Package;
use Shopware\Core\System\SalesChannel\SalesChannelContext;

#[Package('checkout')]
abstract class AbstractAccountService
{
    abstract public function getDecorated(): AbstractAccountService;

    abstract public function setDefaultBillingAddress(string $addressId, SalesChannelContext $context, CustomerEntity $customer): void;

    abstract public function setDefaultShippingAddress(string $addressId, SalesChannelContext $context, CustomerEntity $customer): void;

    abstract public function loginById(string $id, SalesChannelContext $context): string;

    abstract public function loginByCredentials(string $email, string $password, SalesChannelContext $context): string;

    abstract public function getCustomerByLogin(string $email, string $password, SalesChannelContext $context): CustomerEntity;
}
