# Disqus SSO

A simple Laravel packages used to generate payload for the Disqus SSO feature.

## Installation

- Install the package via composer:

`composer require modbase/disqus-sso`

- Add the service provider to `app/config/app.php`:

`'Modbase\Disqus\DisqusServiceProvider',`

- Add the alias to `app/config/app.php`:

`'DisqusSSO'       => 'Modbase\Disqus\Facades\DisqusSSO',`

- Publish the configuration file:

`php artisan config:publish modbase/disqus-sso`

## Configuration

Open `config/packages/modbase/disqus-sso/key.php` and fill in your Disqus _secret_ and _public_ API keys. You can find those at your [Disqus applications](https://disqus.com/api/applications/) page.

## Usage

Using this package is very easy. Add the following code **before** the Disqus initialisation:

```JavaScript
var disqus_config = function () {
    // The generated payload which authenticates users with Disqus
    this.page.remote_auth_s3 = '{{ DisqusSSO::payload(Auth::user()) }}';
    this.page.api_key = '{{ DisqusSSO::publicKey() }}';
}
```

Note that I'm using the Blade syntax here, which is not required of course.

The payload function accepts two different types of input:  
a) An array with the `id`, `username` and `email` of the user you're trying to authenticate.  
b) A User model instance, for example `Auth::user()` as shown in the example.

You can use whatever you like.

## TODO

- Extend the user data with avatar etc.
