<?php


class EmployeeRepository
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

        $fileHandler = fopen($this->fileName, 'r');
        fgetcsv($fileHandler);

        while ($employeeData = fgetcsv($fileHandler, null, ',')) {
            $employeeData = array_map('trim', $employeeData);
            $employee = new Employee($employeeData[1], $employeeData[0], $employeeData[2], $employeeData[3]);
            if ($employee->isBirthday($xDate)) {
                $employeeWithBirthdayToday[] = $employee;
            }
        }

        return $employeeWithBirthdayToday;
    }
}
