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
     * @var EmailEmployeeNotifier
     */
    private $emailEmployeeNotifier;

    public function __construct(EmployeeRepository $employeeRepository, EmailEmployeeNotifier $emailEmployeeNotifier)
    {
        $this->employeeRepository = $employeeRepository;
        $this->emailEmployeeNotifier = $emailEmployeeNotifier;
    }

    public function sendGreetings(XDate $xDate)
    {
        $employeeWithBirthdayToday = $this->employeeRepository->findEmployeeWithBirthday($xDate);

        foreach($employeeWithBirthdayToday as $employee){
            $this->emailEmployeeNotifier->notifyEmployee($employee, $this);
        }
    }

    // made protected for testing :-(
    public function doSendMessage(Swift_Message $msg)
    {
        $this->mailer->send($msg);
    }
}