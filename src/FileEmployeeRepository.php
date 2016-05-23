<?php


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
     */
    public function findEmployeeWithBirthday(XDate $xDate)
    {
        $employeeWithBirthdayToday = [];

        $fileHandler = $this->readEmployeeFile();

        $employees = $this->getAllEmployees($fileHandler);

        foreach ($employees as $employee) {
            if ($employee->isBirthday($xDate)) {
                $employeeWithBirthdayToday[] = $employee;
            }
        }

        return $employeeWithBirthdayToday;
    }

    /**
     * @return resource
     */
    private function readEmployeeFile()
    {
        $fileHandler = fopen($this->fileName, 'r');
        fgetcsv($fileHandler);

        return $fileHandler;
    }

    /**
     * @param $fileHandler
     *
     * @return Employee[]
     */
    private function getAllEmployees($fileHandler)
    {
        $employees = [];

        while ($employeeData = fgetcsv($fileHandler, null, ',')) {
            $employeeData = array_map('trim', $employeeData);
            $employees[] = new Employee($employeeData[1], $employeeData[0], $employeeData[2], $employeeData[3]);
        }

        return $employees;
    }
}
