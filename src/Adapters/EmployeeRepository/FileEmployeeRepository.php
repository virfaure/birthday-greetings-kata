<?php

namespace Adapters\EmployeeRepository;

use Core\Employee;
use Core\EmployeeNotFoundException;
use Core\EmployeeRepository;
use Core\XDate;

class FileEmployeeRepository implements EmployeeRepository
{
    /** @var string */
    private $fileName;

    public function __construct($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * @param XDate $xDate
     * @return array
     *
     * @throws EmployeeNotFoundException
     */
    public function findEmployeeWithBirthday(XDate $xDate)
    {
        $employeeWithBirthdayToday = [];

        try {
            $fileHandler = $this->readEmployeeFile();

            $employees = $this->getAllEmployees($fileHandler);

            foreach ($employees as $employee) {
                if ($employee->isBirthday($xDate)) {
                    $employeeWithBirthdayToday[] = $employee;
                }
            }
        }catch (\Exception $e) {
            throw new EmployeeNotFoundException($e->getMessage());
        }
        return $employeeWithBirthdayToday;
    }

    /**
     * @return resource
     *
     * @throws \Exception
     */
    private function readEmployeeFile()
    {
        $fileHandler = fopen($this->fileName, 'r');

        if (!$fileHandler) {
            throw new \Exception('Cannot Open file');
        }

        fgetcsv($fileHandler);

        return $fileHandler;
    }

    /**
     * @param $fileHandler
     * @return Employee[]
     *
     * @throws \Exception
     */
    private function getAllEmployees($fileHandler)
    {
        $employees = [];

        while ($employeeData = fgetcsv($fileHandler, null, ',')) {
            $employeeData = array_map('trim', $employeeData);

            if (empty($employeeData)) {
                throw new \Exception('Employee not found');
            }

            $employees[] = new Employee($employeeData[1], $employeeData[0], $employeeData[2], $employeeData[3]);
        }

        return $employees;
    }
}
