<?php

interface GreetingsSender
{
    /**
     * @param Employee $employee
     * @param Greetings $greeting
     * @param $service => not needed, just for the tests
     * @return
     */
    public function send(Employee $employee, Greetings $greeting, $service);
}