# Klarna Offline SDK

**Library that integrates and utilizes the Klarna offline API**

The full API specs are avaliable at apiary -> http://docs.klarnaoffline.apiary.io/

## Installing the library
Best option for install is by composer
```php
 composer require mnording/klarna-sms
 ```

## Setting up the cart and config

Firstly you create a config of the current culture, your currency, country, shared secret and store ID.
```php
$config = new MerchantConfig("Merchant_ID","Shared_SECRET","SEK","SE","sv-se",ServerMode::TEST);
```
To recieve your merchant ID and shared secret for your integration you will need to reach out to Klarna.

You then create a cart, and populate it with items
```php
$cart = new Cart();
$item = new \Klarna\Entities\CartRow("test","testref",2000,2,25);
$cart->AddProduct($item);
```
You are also able to define discounts for the cart.
```php
$discount = new \Klarna\Entities\CartRow("dicount","code222",-1000,1,25);
$cart->AddDiscount($discount);
```
*Note:* Prices are entered with amount of cents. Meaning a product that costs 10 SEK, you must enter 1000.

##  Creating the order
First you send in the cart and config and an optional push url

**Use polling method**
By only starting the order, you will receive a status url hosted by klarna that will communicate the order details once the customer has completed the purchase.

 ```php
$order = new KlarnaSMSOrder($config,$cart,"PHONE","Terminal","Reference");
 ```
**Use push url method**
If you define a status url, then order-data will be pushed to that url when customer has completed the purchase.

```php
$t = new KlarnaSMSOrder($config,$cart,"PHONE","Terminal","Reference","https://myownsite.com/createorderwithstatusurl.php");
```

**Create the order**
The create call will create the actual KCO session and send out the SMS to the consumer
```php
$order->Create();
```

If you did not define your own status URL, Klarna will create one for you that you will use for polling the result of the transaction

**Cancel ongoing order**
```php
$order->Cancel();
```
Note: Order must have been created before you can cancel it.


## Reading the customer details
**Using polling method:**
If you wanted Klarna to create a status url for you, you can use the pollUrl function to fetch the order-data.
```php
$order->PollStatus(30);
```
This url will timeout every 60 seconds and you will need to re-trigger it to check as long as the customer has not completed the purchase
```php
$orderdetails = $t->GetOrderInformation();
```

**Using status url method**
If you defined your own statusurl, Klarna will post data to that url.
