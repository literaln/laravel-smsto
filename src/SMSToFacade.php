<?php


namespace Nickmel\SMSTo;


use Illuminate\Support\Facades\Facade;

/**
 * Class SMSToFacade
 * @package Nickmel\SMSTo
 */
class SMSToFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return SMSTo::class;
    }
}