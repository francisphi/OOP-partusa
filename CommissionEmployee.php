<?php

require_once 'Employee.php';

class CommissionEmployee extends Employee {
    private $regularSalary = 0;
    private $itemSold = 0;
    private $commission_rate = 0;

    public function __construct($name, $address, $age, $companyName, $regularSalary, $itemSold, $commissionRate) {
        parent::__construct($name, $address, $age, $companyName);
        $this->regularSalary = max(0, $regularSalary);
        $this->itemSold = max(0, $itemSold);
        $this->commission_rate = max(0, min($commissionRate, 100)) / 100;
    }

    public function earnings() {
        return $this->regularSalary + ($this->itemSold * $this->commission_rate);
    }

    public function __toString() {
        return parent::__toString() . sprintf(
            "\nSalary: %.2f\nItems: %d\nCommission: %.1f%%",
            $this->regularSalary,
            $this->itemSold,
            $this->commission_rate * 100
        );
    }
}
?>