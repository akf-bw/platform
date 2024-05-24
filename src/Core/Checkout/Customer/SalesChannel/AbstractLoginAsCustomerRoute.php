<?php declare(strict_types=1);

namespace Shopware\Core\Checkout\Customer\SalesChannel;

use Shopware\Core\Framework\Log\Package;
use Shopware\Core\Framework\Validation\DataBag\RequestDataBag;
use Shopware\Core\System\SalesChannel\ContextTokenResponse;
use Shopware\Core\System\SalesChannel\SalesChannelContext;

#[Package('checkout')]
abstract class AbstractLoginAsCustomerRoute
{
    abstract public function getDecorated(): AbstractLoginAsCustomerRoute;

    abstract public function loginAsCustomer(RequestDataBag $data, SalesChannelContext $context): ContextTokenResponse;
}
