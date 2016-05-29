<?php

namespace Application;

use Core\EmployeeRepository;
use Core\GreetingFactory;
use Core\GreetingsSender;
use Core\XDate;

// Should not be here, just for the tests to pass
use Swift_Mailer;
use Swift_Message;

class BirthdayService
{
    /**
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * @var EmployeeRepository
     */
    private $employeeRepository;

    /**
     * @var GreetingsSender
     */
    private $greetingsSender;

    public function __construct(EmployeeRepository $employeeRepository, GreetingsSender $greetingsSender)
    {
        $this->employeeRepository = $employeeRepository;
        $this->greetingsSender = $greetingsSender;
    }

    public function sendGreetings(XDate $xDate)
    {
        $employeeWithBirthdayToday = $this->employeeRepository->findEmployeeWithBirthday($xDate);

        foreach($employeeWithBirthdayToday as $employee){
            $greeting = new GreetingFactory($employee);
            $this->greetingsSender->send($employee, $greeting->getBirthdayGreeting(), $this);
        }
    }

    // made protected for testing :-(
    public function doSendMessage(Swift_Message $msg)
    {
        $this->mailer->send($msg);
    }
}