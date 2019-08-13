<?php


namespace Nickmel\SMSTo\Test;

use Orchestra\Testbench\TestCase;

class SMSToTest extends TestCase
{
    public function get_balance_test()
    {
        $response = \SMSTo::getBalance();
        $response->assertStatus(200);
    }

    protected function getPackageProviders($app)
    {
        return [
            'NickMel\SMSTo\SMSToServiceProvider'
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'SMSTo' => 'Nickmel\SMSTo\SMSToFacade'
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();
    }
}