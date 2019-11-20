<?php


namespace App\StateMachine\State;


use App\Service\MailerService;
use App\StateMachine\StateMachineInterface;

class AddYourTwitter implements StateInterface
{
    public function send(StateMachineInterface $stateMachine, MailerService $mailer): int
    {
        $user = $stateMachine->getUser();

        if (empty($user->getTwitter()) && !empty($user->getEmail())) {
            $mailer->sendEmail($user, $user->getId() . ' please enter your twitter');
            return self::STOP;
        }

        $stateMachine->setState(new FinalState());

        return self::CONTINUE;
    }
}
