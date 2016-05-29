<?php

namespace Adapters\GreetingsSender;

use Core\Employee;
use Core\Greetings;
use Core\GreetingsSender;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

// should not be here, just for the tests
use Application\BirthdayService;

class EmailGreetingsSender implements GreetingsSender
{
    /** @var  BirthdayService : Just for test */
    private $service;

    /**
     * @var Swift_Mailer
     */
    private $mailer;

    /** @var string */
    private $smtpHost;

    /** @var string */
    private $smtpPort;

    public function __construct($smtpHost, $smtpPort)
    {
        $this->smtpHost = $smtpHost;
        $this->smtpPort = $smtpPort;
    }

    /**
     * @param Employee $employee
     * @param Greetings $greeting
     * @param $service
     */
    public function send(Employee $employee, Greetings $greeting, $service)
    {
        $this->service = $service;
        $recipient = $employee->getEmail();
        $this->sendGreeting('sender@here.com', $greeting, $recipient);
    }

    /**
     * @param $sender
     * @param Greetings $greeting
     * @param $recipient
     */
    private function sendGreeting($sender, Greetings $greeting, $recipient)
    {
        // Create a mail session
        $this->mailer = Swift_Mailer::newInstance(Swift_SmtpTransport::newInstance($this->smtpHost, $this->smtpPort));

        // Construct the message
        $msg = Swift_Message::newInstance($greeting->getSubject());
        $msg
            ->setFrom($sender)
            ->setTo([$recipient])
            ->setBody($greeting->getBody())
        ;

        // Send the message
        $this->service->doSendMessage($msg);
    }
}
