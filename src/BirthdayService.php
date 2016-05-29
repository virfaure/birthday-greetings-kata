<?php

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