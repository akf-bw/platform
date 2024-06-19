---
title: Add customer impersonation
issue: NEXT-8593
author: Ugurkan Kaya, Jan-Erik Spreng, Benjamin Wittwer
author_email: j.spreng@seidemann-web.com, benjamin.wittwer@a-k-f.de
author_github: ugurkankya, sobyte, akf-bw, M-arcus
---
# Core
* Added `Core/Checkout/Customer/CustomerException::invalidToken` exception
* Added `Core/Checkout/Customer/Exception/InvalidLoginAsCustomerTokenException`
* Added `Core/Checkout/Customer/LoginAsCustomerTokenGenerator` for generating secure token for the storefront to identify the customer
* Added `Core/Checkout/Customer/SalesChannel/AbstractLoginAsCustomerRoute`
* Added `Core/Checkout/Customer/SalesChannel/LoginAsCustomerRoute`
* Added `/api/_proxy/generate-imitate-customer-token` to `Core/Framework/Api/Controller/SalesChannelProxyController`
* Changed `Core/Framework/Api/ApiDefinition/Generator/Schema/StoreApi/paths/account.json` to include the `account/login/imitate-customer` route
* Changed `Core/System/SalesChannel/Context/SalesChannelContextFactory` to check for the `IMITATING_USER_ID`
* Changed `Core/System/SalesChannel/Context/SalesChannelContextService` to add the const `IMITATING_USER_ID` and check for it
* Changed `Core/System/SalesChannel/Context/SalesChannelContextServiceParameters` to add the `imitatingUserId` parameter
* Changed `Core/System/SalesChannel/Context/CartRestorer` to update the `imitatingUserId`
* Changed `Core/System/SalesChannel/SalesChannelContext` to include the `imitatingUserId` variable
___
# Storefront
* Added `/account/login/imitate-customer` to `Storefront/Controller/AuthController` for allowing to log in as customer
___
# Store API
* Added `/store-api/account/login/imitate-customer` for allowing to log in as customer and returning new token
___
# Administration
* Added new modal in `module/sw-customer/component/sw-customer-login-as-customer-modal/index.js`
* Added `module/sw-customer/component/sw-customer-login-as-customer-modal/sw-customer-login-as-customer-modal.html.twig`
* Added `module/sw-customer/component/sw-customer-login-as-customer-modal/sw-customer-login-as-customer-modal.scss`
* Added new block `sw_customer_login_as_customer_modal` in `module/sw-customer/page/sw-customer-detail/sw-customer-detail.html.twig`
* Added new block `sw_customer_detail_actions_storefront_customer_login` in `module/sw-customer/page/sw-customer-detail/sw-customer-detail.html.twig`
* Added new method `onClickButtonShowLoginAsCustomerModal` in `module/sw-customer/page/sw-customer-detail/index.js`
* Added new method `onClickButtonCloseLoginAsCustomerModal` in `module/sw-customer/page/sw-customer-detail/index.js`
* Added new method `generateLoginAsCustomerToken` in `core/service/api/store-context.api.service.js`
* Added `loginAsCustomerModal` in `module/sw-customer/snippet/de-DE.json`
* Added `buttonLoginAsCustomer` in `module/sw-customer/snippet/de-DE.json`
* Added `notificationLoginAsCustomerErrorMessage` in `module/sw-customer/snippet/de-DE.json`
* Added `loginAsCustomerModal` in `module/sw-customer/snippet/en-GB.json`
* Added `buttonLoginAsCustomer` in `module/sw-customer/snippet/en-GB.json`
* Added `notificationLoginAsCustomerErrorMessage` in `module/sw-customer/snippet/en-GB.json`
* Added `api_proxy_login-as-customer` permission
* Added `sw-privileges.additional_permissions.routes.api_proxy_login-as-customer` in `src/module/sw-users-permissions/snippet/de-DE.json`
* Added `sw-privileges.additional_permissions.routes.api_proxy_login-as-customer` in `src/module/sw-users-permissions/snippet/en-GB.json`
