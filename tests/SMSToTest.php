<?php


namespace Nickmel\SMSTo\Test;

/**
 * Class SMSToTest
 * @package Nickmel\SMSTo\Test
 */
class SMSToTest extends TestCase
{
    /**
     * Test the configuration of the client
     */
    public function testConfig()
    {
        $this->assertNotNull(env('SMS_TO_CLIENT_ID'));
        $this->assertNotNull(config('laravel-sms-to.client_id'));
    }

    /**
     * Test the getBalance() method
     */
    public function testGetBalance()
    {
        $response = \SMSTo::getBalance();
        dump($response);
        $this->assertIsArray($response);
        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('balance', $response) || $this->assertArrayHasKey('message', $response);
    }
}