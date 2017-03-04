<?php

namespace IGonics\Disqus;

use Illuminate\Support\ServiceProvider;

class DisqusServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/disqus-sso.php' => config_path('disqus-sso.php'),
        ], 'config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Register 'disqus-sso' instance container to our DisqusAuth object
        $this->app->singleton('disqus-sso',function ($app) {
            return new Disqus;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['disqus-sso'];
    }
}
