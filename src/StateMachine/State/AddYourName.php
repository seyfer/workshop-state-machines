<?php

declare(strict_types=1);

namespace App\StateMachine\State;

use App\Service\MailerService;
use App\StateMachine\StateMachineInterface;

class AddYourName implements StateInterface
{
    public function send(StateMachineInterface $stateMachine, MailerService $mailer): int
    {
        $user = $stateMachine->getUser();

        if (empty($user->getName())) {
            $mailer->sendEmail($user, $user->getId() . ' please enter your name ');
        }

        $stateMachine->setState(new AddYourTwitter());

        return self::CONTINUE;
    }
}
