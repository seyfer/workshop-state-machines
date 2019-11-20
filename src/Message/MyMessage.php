<?php


namespace App\Message;


class MyMessage
{
    private $message;

    /**
     * MyMessage constructor.
     * @param $message
     */
    public function __construct($message)
    {
        $this->message = $message;
    }
}
