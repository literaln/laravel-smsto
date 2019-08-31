[![Latest Stable Version](https://poser.pugx.org/nickmel/laravel-smsto/v/stable?format=flat-square)](https://packagist.org/packages/nickmel/laravel-smsto)
[![Build Status](https://travis-ci.org/literaln/laravel-smsto.svg?branch=master)](https://travis-ci.org/literaln/laravel-smsto)
[![License](https://poser.pugx.org/nickmel/laravel-smsto/license?format=flat-square)](https://packagist.org/packages/nickmel/laravel-smsto)
[![Total Downloads](https://poser.pugx.org/nickmel/laravel-smsto/downloads?format=flat-square)](https://packagist.org/packages/nickmel/laravel-smsto)

## SMS.to library for Laravel 5.8
Laravel package to provide integration with SMS.to REST API. Visit https://sms.to/ to create an account and obtain API credentials.

### Installation
Install this package with composer:
```
composer require nickmel/laravel-smsto
```

Add the provider to the `providers` array in `config/app.php` file

```php
'Nickmel/SMSTo/SMSToServiceProvider',
```

and the facade in the `aliases` array in `config/app.php` file

```php
'SMSTo' => 'Nickmel/SMSTo/SMSToFacade',
```

Copy the config files for the API

```
php artisan vendor:publish --tag="laravel-sms-to" 
```

### Usage
To configure the API, either set the variables in the `.env` file or edit the published `config/laravel-sms-to.php` file directly.
- - -

##### Get Account Balance
```php
SMSTo::getBalance();
```

##### Send single SMS
```php
$messages = [
    [
        'to' => '+357xxxxxxxx',
        'message' => 'An SMS message'
    ]
];

$senderId = 'SENDERID';

SMSTo::sendSingle($messages, $senderId);
```

##### Send multiple SMS
```php
$recipients = ['+357xxxxxxxx', '+357yyyyyyyy'];

$message = 'An SMS message';

$senderId = 'SENDERID';

SMSTo::sendMultiple($message, $recipients, $senderId);
```

##### Using the Notification channel
```php
Notifications in Laravel can be send to custom channels.
```

- - - 