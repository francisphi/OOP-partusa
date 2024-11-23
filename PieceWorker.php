<?php

require_once 'Employee.php';

class PieceWorker extends Employee {
    private $numberItems = 0;
    private $wagePerItem = 0;

    public function __construct($name, $address, $age, $companyName, $numberItems, $wagePerItem) {
        parent::__construct($name, $address, $age, $companyName);
        $this->numberItems = max(0, $numberItems);
        $this->wagePerItem = max(0, $wagePerItem);
    }

    public function earnings() {
        return $this->numberItems * $this->wagePerItem;
    }

    public function __toString() {
        return parent::__toString() . sprintf(
            "\nItems Completed: %d\nWage Per Item: %.2f",
            $this->numberItems,
            $this->wagePerItem
        );
    }
}
?>