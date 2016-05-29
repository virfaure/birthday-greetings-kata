<?php


class EmailEmployeeNotifier implements EmployeeNotifier
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
     * @param $employee
     * @param $service
     */
    public function notifyEmployee($employee, $service)
    {
        $this->service = $service;
        $recipient = $employee->getEmail();
        $greeting = $this->getGreetingFor($employee);
        $this->sendGreeting('sender@here.com', $greeting, $recipient);
    }

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

    /**
     * @param $employee
     * @return Greetings
     */
    private function getGreetingFor($employee)
    {
        $body = sprintf('Happy Birthday, dear %s!', $employee->getFirstName());
        $subject = 'Happy Birthday!';

        return new Greetings($subject, $body);
    }
}
