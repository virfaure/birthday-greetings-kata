<?php

namespace Core;

class EmployeeNotFoundException extends \Exception
{

    /**
     * @param string $message
     */
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
