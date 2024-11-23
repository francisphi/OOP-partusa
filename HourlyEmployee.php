<?php

require_once 'Employee.php';

class HourlyEmployee extends Employee {
    private $hoursWorked = 0;
    private $rate = 0;
    private const OVERTIME_MULTIPLIER = 1.5;
    private const STANDARD_HOURS = 40;

    public function __construct($name, $address, $age, $companyName, $hoursWorked, $rate) {
        parent::__construct($name, $address, $age, $companyName);
        $this->hoursWorked = max(0, $hoursWorked);
        $this->rate = max(0, $rate);
    }

    public function earnings() {
        $standardPay = min($this->hoursWorked, self::STANDARD_HOURS) * $this->rate;
        $overtimeHours = max(0, $this->hoursWorked - self::STANDARD_HOURS);
        $overtimePay = $overtimeHours * ($this->rate * self::OVERTIME_MULTIPLIER);
        return $standardPay + $overtimePay;
    }

    public function __toString() {
        return parent::__toString() . sprintf(
            "\nHours: %d\nRate: %.2f",
            $this->hoursWorked,
            $this->rate
        );
    }
}
?>