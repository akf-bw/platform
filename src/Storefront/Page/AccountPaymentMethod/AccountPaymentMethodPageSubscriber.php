<?php declare(strict_types=1);

namespace Shopware\Storefront\Page\AccountPaymentMethod;

use Shopware\Storefront\Event\AccountEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AccountPaymentMethodPageSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            AccountEvents::ACCOUNT_PAYMENT_METHOD_PAGE_REQUEST => 'transformRequest',
        ];
    }

    public function transformRequest(AccountPaymentMethodPageRequestEvent $event): void
    {
        $accountPaymentMethodPageRequest = $event->getAccountPaymentMethodPageRequest();
    }
}
