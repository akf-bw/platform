---
title: Add customer impersonation
issue: NEXT-8593
author: Ugurkan Kaya, Jan-Erik Spreng, Benjamin Wittwer
author_email: j.spreng@seidemann-web.com, benjamin.wittwer@a-k-f.de
author_github: ugurkankya, sobyte, akf-bw
---
# Core
* Added `Core/Checkout/Customer/CustomerException::invalidToken` exception
* Added `Core/Checkout/Customer/Exception/InvalidLoginAsCustomerTokenException`
* Added `Core/Checkout/Customer/LoginAsCustomerTokenGenerator` for generating secure token for the storefront to identify the customer
* Added `Core/Checkout/Customer/SalesChannel/AbstractLoginAsCustomerRoute`
* Added `Core/Checkout/Customer/SalesChannel/LoginAsCustomerRoute`
* Added `/api/_proxy/login-as-customer-token-generate` to `Core/Framework/Api/Controller/SalesChannelProxyController`
* Added `Core/Checkout/Customer/SalesChannel/AbstractAccountService`
* Changed `Core/Checkout/Customer/Event/CustomerBeforeLoginEvent` to add optional `userId`
* Changed `Core/Checkout/Customer/Event/CustomerLoginEvent` to add optional `userId`
* Changed `Core/Checkout/Customer/SalesChannel/AccountService` to add optional `userId` & `forcedSalesChannelId` to `loginById`
* Changed `Core/Framework/Api/ApiDefinition/Generator/Schema/StoreApi/paths/account.json` to include the `account/login/customer` route
___
# Storefront
* Added `/account/login/customer/{token}/{customerId}/{userId}` to `Storefront/Controller/AuthController` for allowing to log in as customer
___
# Store API
* Added `/store-api/account/login/customer` for allowing to log in as customer and returning new token
___
# Administration
* Added new modal in `module/sw-customer/component/sw-customer-login-as-customer-modal/index.js`
* Added `module/sw-customer/component/sw-customer-login-as-customer-modal/sw-customer-login-as-customer-modal.html.twig`
* Added `module/sw-customer/component/sw-customer-login-as-customer-modal/sw-customer-login-as-customer-modal.scss`
* Added new block `sw_customer_login_as_customer_modal` in `module/sw-customer/page/sw-customer-detail/sw-customer-detail.html.twig`
* Added new block `sw_customer_detail_actions_storefront_customer_login` in `module/sw-customer/page/sw-customer-detail/sw-customer-detail.html.twig`
* Added new method `onClickButtonShowLoginAsCustomerModal` in `module/sw-customer/page/sw-customer-detail/index.js`
* Added new method `onClickButtonCloseLoginAsCustomerModal` in `module/sw-customer/page/sw-customer-detail/index.js`
* Added new method `loginAsCustomerTokenGenerate` in `core/service/api/store-context.api.service.js`
* Added new snippet key `loginAsCustomerModal` in `module/sw-customer/snippet/de-DE.json`
* Added new snippet `buttonLoginAsCustomer` in `module/sw-customer/snippet/de-DE.json`
* Added new snippet `notificationLoginAsCustomerErrorMessage` in `module/sw-customer/snippet/de-DE.json`
* Added new snippet key `loginAsCustomerModal` in `module/sw-customer/snippet/en-GB.json`
* Added new snippet `buttonLoginAsCustomer` in `module/sw-customer/snippet/en-GB.json`
* Added new snippet `notificationLoginAsCustomerErrorMessage` in `module/sw-customer/snippet/en-GB.json`
