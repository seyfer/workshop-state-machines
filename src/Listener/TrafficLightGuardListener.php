<?php


namespace App\Listener;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Workflow\Event\Event;

class TrafficLightGuardListener implements EventSubscriberInterface
{
    /**
     * @var Security
     */
    private $security;

    public function __construct(
        Security $security
    )
    {
        $this->security = $security;
    }

    public function guardYellow(Event $event)
    {
        if (!$this->security->isGranted('ROLE_ADMIN')) {
            $event->setBlocked(true);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'workflow.traffic_light.guard.to_yellow' => ['guardYellow'],
        ];
    }
}
