<?php

interface EmployeeRepository
{
    /**
     * @param XDate $xDate
     * @return array
     */
    public function findEmployeeWithBirthday(XDate $xDate);
}