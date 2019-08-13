<?php


namespace Nickmel\SMSTo;

use Illuminate\Support\ServiceProvider;

/**
 * Class SMSToServiceProvider
 * @package Nickmel\SMSTo
 */
class SMSToServiceProvider extends ServiceProvider
{
    /**
     * @var string
     */
    protected $configName = 'laravel-sms-to';

    /**
     * @var bool
     */
    protected $defer = false;

    /**
     *
     */
    public function register()
    {
        $configPath = __DIR__ . '/../config/' . $this->configName . '.php';
        $this->mergeConfigFrom($configPath, $this->configName);

        $this->app->singleton('SMSTo', function () {
            return new SMSTo();
        });

        $this->app->alias(SMSTo::class, 'smsto');
    }

    /**
     *
     */
    public function boot()
    {
        $configPath = __DIR__ . '/../config/' . $this->configName . '.php';
        $this->publishes([
            $configPath => config_path($this->configName . '.php')
        ], $this->configName);
    }

    /**
     * @return array
     */
    public function provides()
    {
        return ['smsto', SMSTo::class];
    }
}