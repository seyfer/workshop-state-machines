<?php


namespace App\StateMachine\State;


use App\Service\MailerService;
use App\StateMachine\StateMachineInterface;

class AddYourTwitter implements StateInterface
{

    /**
     * @return int To communicate back to the state machine if we should self::STOP running
     *             or if we should self::CONTINUE with the next state.
     */
    public function send(StateMachineInterface $stateMachine, MailerService $mailer): int
    {
        $user = $stateMachine->getUser();

        if (empty($user->getTwitter()) && !empty($user->getEmail())) {
            $mailer->sendEmail($user, $user->getId() . ' please enter your twitter ');
            return self::STOP;
        }

        $stateMachine->setState(new FinalState());

        return self::CONTINUE;
    }
}
