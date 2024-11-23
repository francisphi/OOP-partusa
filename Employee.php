<?php

require_once 'Person.php';

abstract class Employee extends Person {
    private $companyName = '';
    private $employeeNumber = 0;

    public function __construct($name, $address, $age, $companyName) {
        parent::__construct($name, $address, $age);
        $this->companyName = $companyName;
    }

    abstract public function earnings();

    public function getCompanyName() { return $this->companyName; }
    public function getEmployeeNumber() { return $this->employeeNumber; }
    
    public function setCompanyName($name) { $this->companyName = $name; }
    public function setEmployeeNumber($num) { $this->employeeNumber = $num; }

    public function __toString() {
        return parent::__toString() . "\nCompany: " . $this->companyName;
    }

    public static function count($employees) {
        return count(array_filter($employees, function($emp) {
            return $emp instanceof Employee;
        }));
    }
}
?>