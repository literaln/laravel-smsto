<?php


namespace Nickmel\SMSTo\Test;


use Dotenv\Dotenv;
use Dotenv\Loader;
use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables;
use Nickmel\SMSTo\SMSToFacade;
use Nickmel\SMSTo\SMSToServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        $app->useEnvironmentPath(__DIR__ . '/..');
        $app->bootstrapWith([LoadEnvironmentVariables::class]);
        parent::getEnvironmentSetUp($app);
    }

    protected function getPackageProviders($app)
    {
        return [SMSToServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'SMSTo' => SMSToFacade::class
        ];
    }
}