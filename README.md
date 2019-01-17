# Omnipay: RBK.money
RBK.money driver for the Omnipay payment processing library.

[![Latest Stable Version](https://poser.pugx.org/webtoucher/omnipay-rbkmoney/v/stable)](https://packagist.org/packages/webtoucher/omnipay-rbkmoney)
[![Total Downloads](https://poser.pugx.org/webtoucher/omnipay-rbkmoney/downloads)](https://packagist.org/packages/webtoucher/omnipay-rbkmoney)
[![Daily Downloads](https://poser.pugx.org/webtoucher/omnipay-rbkmoney/d/daily)](https://packagist.org/packages/webtoucher/omnipay-rbkmoney)
[![Latest Unstable Version](https://poser.pugx.org/webtoucher/omnipay-rbkmoney/v/unstable)](https://packagist.org/packages/webtoucher/omnipay-rbkmoney)
[![License](https://poser.pugx.org/webtoucher/omnipay-rbkmoney/license)](https://packagist.org/packages/webtoucher/omnipay-rbkmoney)

## Installation

The preferred way to install this library is through [composer](http://getcomposer.org/download/).

Either run

```
$ php composer.phar require webtoucher/omnipay-rbkmoney "*"
```

or add

```
"webtoucher/omnipay-rbkmoney": "*"
```

to the ```require``` section of your `composer.json` file.

## Usage

Configure API client:

```php
    $gateway = \Omnipay\Omnipay::create('RbkMoney');
    $gateway->setShopId('[SHOP_ID]');
    $gateway->setApiKey('[API_PRIVATE_KEY]');
    $gateway->setLogger(function ($message, $level = 'info') {
        // You can add logging for your requests
    });
```

Then you can create invoice.

```php
    $cart = new \Omnipay\RbkMoney\Cart;
    $cart->addItem(new \Omnipay\RbkMoney\CartItem('Some product', 100));
    $cart->addItem(new \Omnipay\RbkMoney\CartItem('Another product', 200, 1, 20));

    $request = $gateway->createInvoice([
        'cart' => $cart,
        'currency' => 'RUB',
        'transactionId' => 1234,
        'product' => "Заказ 1234",
    ]);

    try {
        $response = $request->send();

        if ($response->isSuccessful()) {
            // Your handler
        }
    } catch (\Omnipay\Common\Exception\OmnipayException $e) {
        // Your handler
    }
```

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you want to keep up to date with release anouncements, discuss ideas for the project,
or ask more detailed questions, there is also a [mailing list](https://groups.google.com/forum/#!forum/omnipay) which
you can subscribe to.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/webtoucher/omnipay-rbkmoney/issues).
