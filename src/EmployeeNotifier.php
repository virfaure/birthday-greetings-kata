<?php

interface EmployeeNotifier
{
    /**
     * @param $employee
     * @param $service => not needed, just for the tests
     */
    public function notifyEmployee($employee, $service);
}