<?php

class EmployeeRoster {
    private $rosterData = [
        'size' => 0,
        'employees' => [],
        'vacantSlots' => 0
    ];

    public function __construct($size) {
        $this->rosterData['size'] = $size;
        $this->rosterData['employees'] = array_fill(0, $size, null);
        $this->rosterData['vacantSlots'] = $size;
    }

    public function add($employee) {
        if (!$employee) return false;
        if ($this->rosterData['vacantSlots'] <= 0) {
            echo "\033[2J\033[;H";  // Clear screen
            echo "Error: Roster is full, unable to add more employees.\n";
            readline("Press Enter to continue...");
            return false;
        }
        
        for ($i = 0; $i < $this->rosterData['size']; $i++) {
            if ($this->rosterData['employees'][$i] === null) {
                $employee->setEmployeeNumber($i + 1);
                $this->rosterData['employees'][$i] = $employee;
                $this->rosterData['vacantSlots']--;
                return true;
            }
        }
        return false;
    }

    private function filterEmployees($type) {
        return array_filter($this->rosterData['employees'], 
            fn($emp) => $emp !== null && $emp instanceof $type);
    }

    public function remove($empNum) {
        $index = $empNum - 1;
        if ($index < 0 || $index >= $this->rosterData['size'] || 
            $this->rosterData['employees'][$index] === null) {
            return false;
        }
        
        $this->rosterData['employees'][$index] = null;
        $this->rosterData['vacantSlots']++;
        return true;
    }

    public function count() { return $this->rosterData['size'] - $this->rosterData['vacantSlots']; }
    public function countCE() { return count($this->filterEmployees('CommissionEmployee')); }
    public function countHE() { return count($this->filterEmployees('HourlyEmployee')); }
    public function countPE() { return count($this->filterEmployees('PieceWorker')); }

    private function displayEmployees($filter = null) {
        $employees = $filter ? $this->filterEmployees($filter) : 
                             array_filter($this->rosterData['employees']);
        if (empty($employees)) {
            echo "\033[2J\033[;H";  // Clear screen
            echo "No employees found.\n";
            return;
        }
        echo "\033[2J\033[;H";  // Clear screen before displaying
        foreach ($employees as $index => $emp) {
            echo "Employee #" . ($index + 1) . "\n";
            echo $emp . "\n" . str_repeat("-", 30) . "\n";
        }
    }

    public function display() { $this->displayEmployees(); }
    public function displayCE() { $this->displayEmployees('CommissionEmployee'); }
    public function displayHE() { $this->displayEmployees('HourlyEmployee'); }
    public function displayPE() { $this->displayEmployees('PieceWorker'); }

    public function payroll() {
        foreach ($this->rosterData['employees'] as $emp) {
            if ($emp !== null) {
                echo $emp . "\nEarnings: " . number_format($emp->earnings(), 2) . "\n"
                    . str_repeat("-", 30) . "\n";
            }
        }
    }

    public function getAvailableSlots() {
        return $this->rosterData['vacantSlots'];
    }

    public function getEmployees() {
        return array_filter($this->rosterData['employees'], function($emp) {
            return $emp !== null;
        });
    }
}
?>