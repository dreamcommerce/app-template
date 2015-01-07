app-template
============

This is a template for DreamCommerce Shop SDK.

## overview

Template could be used as a boilerplate for your own app used with our shops.



## how to start

### 1. obtain and install Application Tamplate

Clone this repository or [download ZIP](https://github.com/dreamcommerce/app-template/archive/master.zip) with current version and extract its content to your application directory.

### 2. obtain and install SDK

#### composer way

If you are familiar with [Composer](http://getcomposer.org), you can simply call ``composer install``.

#### manual way

[download ZIP](https://github.com/dreamcommerce/shop-appstore-lib/archive/master.zip) with our SDK and extract its contents to the application directory.

### 3. setup credentials

In ``src/Config.php`` there are several options to adjust. Specify your application ID, application secrets and database credentials.

An example database schemas are under ``sql`` directory. Feel free using them. They are suitable for stock ``BillingSystem``.

### 4. put an application on your server

Make sure it's reachable from the Web and you have enabled a SSL support (app won't work if your server has no SSL support enabled or incorrect certificate)

### 5. register yout application in the App Store

https://developers.dreamcommerce.com/developers/appstore/registration

### 6. execute your app in store

Click the adequate link in your shop and test your sample app. This boilerplate application dumps categories list from desired shop.

## components overview

An application consists of two components: the Billing System and an Application.

Billing System handles actions for install/uninstall and subscription. If your app is being installed to the shop, an App Store calls it with a request according to the manual: https://developers.dreamcommerce.com/developers/appstore/billing-system

An Application handles actions for your purposes. Feel free to extend it!

### ``billing_index.php``

An App Store should be pointed to this file as an Billing System entrypoint.

### ``index.php``

Application bootstrap. Handles your logic.