<?php

class AcceptanceTest extends PHPUnit_Framework_TestCase
{
    const EMPLOYEE_FILENAME = __DIR__ . '/resources/employee_data.txt';
    /**
     * @var int
     */
    private static $SMTP_PORT = 25;

    /**
     * @var Swift_Message[]
     */
    private $messagesSent = [];

    /**
     * @var BirthdayService
     */
    private $service;

    public function setUp()
    {
        $messageHandler = function (Swift_Message $msg) {
            $this->messagesSent[] = $msg;
        };

        $this->service = new TestableBirthdayService(
            new FileEmployeeRepository(self::EMPLOYEE_FILENAME),
            new EmailEmployeeNotifier('localhost', self::$SMTP_PORT)
        );

        $this->service->setMessageHandler($messageHandler->bindTo($this));
    }

    public function tearDown()
    {
        $this->service = $this->messagesSent = null;
    }

    /**
     * @test
     */
    public function willSendGreetings_whenItsSomebodysBirthday()
    {
        $this->service->sendGreetings(new XDate('2008/10/08'));

        $this->assertCount(1, $this->messagesSent, 'message not sent?');
        $message = $this->messagesSent[0];
        $this->assertEquals('Happy Birthday, dear John!', $message->getBody());
        $this->assertEquals('Happy Birthday!', $message->getSubject());
        $this->assertCount(1, $message->getTo());
        $this->assertEquals('john.doe@foobar.com', array_keys($message->getTo())[0]);
    }

    /**
     * @test
     */
    public function willNotSendEmailsWhenNobodysBirthday()
    {
        $this->service->sendGreetings(new XDate('2008/01/01'));

        $this->assertCount(0, $this->messagesSent, 'what? messages?');
    }
}

class TestableBirthdayService extends BirthdayService
{
    /**
     * @var Closure
     */
    private $callback;

    public function setMessageHandler(Closure $callback)
    {
        $this->callback = $callback;

        return $this;
    }

    public function doSendMessage(Swift_Message $msg)
    {
        $callable = $this->callback;
        $callable($msg);
    }
}