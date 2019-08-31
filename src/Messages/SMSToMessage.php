<?php


namespace Nickmel\SMSTo\Messages;


/**
 * Class SMSToMessage
 * @package Nickmel\SMSTo\Messages
 */
class SMSToMessage
{
    /**
     * @var string
     */
    public $content;

    /**
     * @var
     */
    public $from;

    /**
     * @var string
     */
    public $type = 'text';

    /**
     * @var
     */
    public $clientReference;

    /**
     * SMSToMessage constructor.
     * @param string $content
     */
    public function __construct($content = '')
    {
        $this->content = $content;
    }

    /**
     * @param $content
     * @return $this
     */
    public function content($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @param $from
     * @return $this
     */
    public function from($from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @param $clientReference
     * @return $this
     */
    public function clientReference($clientReference)
    {
        $this->clientReference = $clientReference;

        return $this;
    }
}