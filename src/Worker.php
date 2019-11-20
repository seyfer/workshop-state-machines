<?php

declare(strict_types=1);

namespace App;

use App\Service\Database;
use App\Service\MailerService;
use App\StateMachine\State\AddYourName;

class Worker
{
    private $db;
    private $mailer;

    public function __construct(Database $em, MailerService $mailer)
    {
        $this->db = $em;
        $this->mailer = $mailer;
    }

    public function run(): void
    {
        $users = $this->db->getAllUsers();

        foreach ($users as $user) {
            $stateMachine = new StateMachine\StateMachine($this->mailer, $user);
            $stateMachine->start(new AddYourName());
        }

        $this->db->saveUsers($users);
    }
}
