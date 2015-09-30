<?php

namespace Kim\JavaScript;

use Illuminate\Support\ServiceProvider;

class JavaScriptServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config/javascript.php' => config_path('javascript.php')
        ], 'config');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerJavascript();
    }

    private function registerJavascript()
    {
        $this->app->singleton('javascript', function ($app) {

            $view = config('javascript.view');
            $namespace = config('javascript.namespace');

            $session = new JavaScriptSession($app['session.store'], $namespace);
            $converter = new VariableConverter($namespace);

            return new VariableTransporter($app['events'], $session, $converter, $view);
        });
    }
}