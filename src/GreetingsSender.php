<?php

interface GreetingsSender
{
    /**
     * @param $employee
     * @param $service => not needed, just for the tests
     */
    public function send($employee, $service);
}