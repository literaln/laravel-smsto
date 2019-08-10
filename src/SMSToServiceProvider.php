<?php


namespace nickmel\SMSTo;

use Illuminate\Support\ServiceProvider;

class SMSToServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/laravel-sms-to.php' => config_path('laravel-sms-to.php')
        ], 'config');
    }

    public function provides()
    {
        return ['SMSToAPI'];
    }
}