app-template
============

This is a template for DreamCommerce Shop SDK.

## overview

Template could be used as a boilerplate for your own app used with our shops.



## how to start

### 1. obtain SDK

#### composer way

If you are familiar with [Composer](http://getcomposer.org), you can simply call ``composer install``.

Once you have all dependencies installed, uncomment ``index.php:8`` and ``billing_index.php:8`` to enable Composer's autoloader. Well done.

#### manual way

Simply [download ZIP](https://github.com/dreamcommerce/shop-appstore-lib/archive/master.zip) with our SDK and extract its contents to this directory. That's all.

### 2. setup credentials

In ``src/Config.php`` there are several options to adjust. Specify your application ID, application secret and appstore secret.

Then adjust params within ``dbConnect`` method.

### 3. copy Billing System sample from SDK

Under most circumstances, the default version of ``BillingSystem`` shipped with SDK will be sufficient. Copy ``examples/BillingSystem/App.php`` to ``src/BillingSystem/App.php``.

### 4. put an application on your server

Make sure it's reachable from the Web and you have enabled a SSL support (app won't work if your server has no SSL support enabled or incorrect certificate)

### 5. check in to the App Store

TODO: link to the app installation

### 6. execute your app in store

Click the adequate link in your shop and test your sample app. This boilerplate dumps categories list from desired shop.