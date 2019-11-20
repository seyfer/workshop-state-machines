<?php


namespace App\Message;


class MyMessageHandler
{
    public function __invoke(MyMessage $message)
    {
        dump($message);
    }
}
