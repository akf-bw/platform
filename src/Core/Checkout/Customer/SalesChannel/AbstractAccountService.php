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

    /**
     * @deprecated tag:v6.7.0 - Method will be removed, use `AccountService::loginById` or `AccountService::loginByCredentials` instead
     */
    abstract public function login(string $email, SalesChannelContext $context, bool $includeGuest = false): string;

    abstract public function loginById(string $id, SalesChannelContext $context, ?string $forcedSalesChannelId = null, ?string $userId = null): string;

    abstract public function loginByCredentials(string $email, string $password, SalesChannelContext $context): string;

    abstract public function getCustomerByLogin(string $email, string $password, SalesChannelContext $context): CustomerEntity;
}
