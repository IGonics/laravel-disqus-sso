[![Build Status](https://travis-ci.org/gggordon/laravel-disqus-sso.svg)](https://travis-ci.org/gggordon/laravel-disqus-sso)

# Laravel Disqus SSO

A simple Laravel packages used to generate payload for the Disqus SSO feature.

## Installation

- Install the package via composer:

`composer require igonics/disqus-sso`

- Add the service provider to `app/config/app.php`:

`'IGonics\Disqus\DisqusServiceProvider',`

- Add the alias to `app/config/app.php`:

`'DisqusSSO'       => 'IGonics\Disqus\Facades\DisqusSSO',`

- Publish the configuration file:

`php artisan vendor:publish --provider="IGonics\Disqus\DisqusServiceProvider" --tag="config"`

## Configuration

Open `config/disqus-sso.php` and fill in your Disqus _secret_ and _public_ API keys. You can find those at your [Disqus applications](https://disqus.com/api/applications/) page.

## Usage

Using this package is very easy. Add the following JavaScript code **before** the Disqus initialisation:

#### JavaScript using Blade Syntax
```
var disqus_config = function () {
    // The generated payload which authenticates users with Disqus
    this.page.remote_auth_s3 = '{{ DisqusSSO::payload(Auth::user()) }}';
    this.page.api_key = '{{ DisqusSSO::publicKey() }}';
}
```

#### JavaScript using vanilla PHP
```
var disqus_config = function () {
    // The generated payload which authenticates users with Disqus
    this.page.remote_auth_s3 = '<?php echo DisqusSSO::payload(Auth::user()) ?>';
    this.page.api_key = '<?php echo DisqusSSO::publicKey() ?>';
}
```



The payload function accepts two different types of input:  
a) An array with the `id`, `username`, `email`, `avatar` and `url` of the user you're trying to authenticate. See the [Disqus help](https://help.disqus.com/customer/portal/articles/236206-single-sign-on#user-data) for more information about these.  
b) A laravel Model instance, for example `Auth::user()` as shown in the example.

## Maintained By

[IGonics](http://igonics.com)

