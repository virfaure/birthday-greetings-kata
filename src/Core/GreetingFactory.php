<?php

namespace Core;

class GreetingFactory
{
    /** @var Employee */
    private $employee;

    /**
     * @param Employee $employee
     */
    public function __construct($employee)
    {
        $this->employee = $employee;
    }

    public function getBirthdayGreeting()
    {
        $body = sprintf('Happy Birthday, dear %s!', $this->employee->getFirstName());
        $subject = 'Happy Birthday!';

        return new Greetings($subject, $body);
    }
}
