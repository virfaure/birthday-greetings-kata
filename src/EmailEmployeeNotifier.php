<?php


class EmailEmployeeNotifier
{
    /** @var  BirthdayService : Just for test */
    private $service;

    /**
     * @var Swift_Mailer
     */
    private $mailer;
    private $smtpHost;
    private $smtpPort;

    public function __construct($smtpHost, $smtpPort)
    {
        $this->smtpHost = $smtpHost;
        $this->smtpPort = $smtpPort;
    }

    /**
     * @param $employee
     * @param $service
     * @internal param $smtpHost
     * @internal param $smtpPort
     */
    public function notifyEmployee($employee, $service)
    {
        $this->service = $service;
        $recipient = $employee->getEmail();
        $body = sprintf('Happy Birthday, dear %s!', $employee->getFirstName());
        $subject = 'Happy Birthday!';
        $this->sendMessage('sender@here.com', $subject, $body, $recipient);
    }

    private function sendMessage($sender, $subject, $body, $recipient)
    {
        // Create a mail session
        $this->mailer = Swift_Mailer::newInstance(Swift_SmtpTransport::newInstance($this->smtpHost, $this->smtpPort));

        // Construct the message
        $msg = Swift_Message::newInstance($subject);
        $msg
            ->setFrom($sender)
            ->setTo([$recipient])
            ->setBody($body)
        ;

        // Send the message
        $this->service->doSendMessage($msg);
    }

}
