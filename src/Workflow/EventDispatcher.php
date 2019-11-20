<?php


namespace App\Workflow;

//use App\Message\MyMessage;
//use App\Message\MyMessageHandler;
//use Symfony\Component\Messenger\Handler\HandlersLocator;
//use Symfony\Component\Messenger\MessageBus;
//use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;
use Symfony\Component\Workflow\Event\Event;
use Symfony\Component\Workflow\Event\GuardEvent;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class EventDispatcher implements EventDispatcherInterface
{
    /**
     * @param Event $event
     */
    public function dispatch($event): void
    {
        if ($event instanceof GuardEvent) {
            return;
        }

//        dump($event->getMarking());
//        die();

//        $bus = new MessageBus([
//            new HandleMessageMiddleware(new HandlersLocator([
//                MyMessage::class => [new MyMessageHandler()],
//            ])),
//        ]);
//
//        $bus->dispatch(new MyMessage(
//                'entered ' . json_encode($event->getMarking()->getPlaces()))
//        );
    }
}
