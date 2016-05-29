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
    private $employeeNotifier;

    public function __construct(EmployeeRepository $employeeRepository, GreetingsSender $employeeNotifier)
    {
        $this->employeeRepository = $employeeRepository;
        $this->employeeNotifier = $employeeNotifier;
    }

    public function sendGreetings(XDate $xDate)
    {
        $employeeWithBirthdayToday = $this->employeeRepository->findEmployeeWithBirthday($xDate);

        foreach($employeeWithBirthdayToday as $employee){
            $this->employeeNotifier->send($employee, $this);
        }
    }

    // made protected for testing :-(
    public function doSendMessage(Swift_Message $msg)
    {
        $this->mailer->send($msg);
    }
}