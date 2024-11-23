<?php

require_once 'EmployeeRoster.php';
require_once 'CommissionEmployee.php';
require_once 'HourlyEmployee.php';
require_once 'PieceWorker.php';

class Main {
    private EmployeeRoster $roster;
    private $systemState = [
        'size' => 0,
        'isRunning' => true
    ];

    private function displayHeader($title) {
        $this->clearScreen();
        if ($title) {
            echo str_repeat("*", 5) . " $title " . str_repeat("*", 5) . "\n";
        }
    }

    private function clearScreen() {
        echo "\033[2J\033[;H";
    }

    public function start() {
        $this->displayHeader("System Initialization");
        $size = $this->validateInput("Enter roster size: ", 'int');
        if ($size < 1) {
            echo "Invalid size. Minimum size is 1.\n";
            readline("Press Enter to retry...");
            $this->start();
            return;
        }
        $this->systemState['size'] = $size;
        $this->roster = new EmployeeRoster($size);
        $this->mainLoop();
    }

    private function mainLoop() {
        while ($this->systemState['isRunning']) {
            $this->displayHeader("Main Menu");
            $this->showStats();
            $this->showMainMenu();
            
            switch ($this->validateInput("Select option: ", 'int')) {
                case 1: $this->handleEmployeeAddition(); break;
                case 2: $this->handleEmployeeDeletion(); break;
                case 3: $this->handleOtherOperations(); break;
                case 0: $this->systemState['isRunning'] = false; break;
                default: $this->showError("Invalid option");
            }
        }
        echo "System terminated.\n";
    }

    private function handleEmployeeAddition() {
        if ($this->roster->getAvailableSlots() === 0) {
            $this->showError("Roster is now full, unable to add an employee.");
            return;
        }

        $employeeData = [
            'name' => $this->validateInput("Enter name: ", 'string'),
            'address' => $this->validateInput("Enter address: ", 'string'),
            'age' => $this->validateInput("Enter age: ", 'int'),
            'company' => $this->validateInput("Enter company name: ", 'string')
        ];

        $this->displayHeader("Employee Type Selection");
        echo "[1] Commission Employee\n[2] Hourly Employee\n[3] Piece Worker\n";
        
        switch ($this->validateInput("Select type: ", 'int')) {
            case 1:
                $this->createCommissionEmployee($employeeData);
                break;
            case 2:
                $this->createHourlyEmployee($employeeData);
                break;
            case 3:
                $this->createPieceWorker($employeeData);
                break;
            default:
                $this->showError("Invalid employee type");
                $this->handleEmployeeAddition();
        }
    }

    private function createCommissionEmployee($data) {
        $employee = new CommissionEmployee(
            $data['name'],
            $data['address'],
            $data['age'],
            $data['company'],
            $this->validateInput("Enter regular salary: ", 'float'),
            $this->validateInput("Enter items sold: ", 'int'),
            $this->validateInput("Enter commission rate (%): ", 'float')
        );
        $this->roster->add($employee);
        $this->confirmAddition();
    }

    private function createHourlyEmployee($data) {
        $employee = new HourlyEmployee(
            $data['name'],
            $data['address'],
            $data['age'],
            $data['company'],
            $this->validateInput("Enter hours worked: ", 'float'),
            $this->validateInput("Enter hourly rate: ", 'float')
        );
        $this->roster->add($employee);
        $this->confirmAddition();
    }

    private function createPieceWorker($data) {
        $employee = new PieceWorker(
            $data['name'],
            $data['address'],
            $data['age'],
            $data['company'],
            $this->validateInput("Enter number of items: ", 'int'),
            $this->validateInput("Enter wage per item: ", 'float')
        );
        $this->roster->add($employee);
        $this->confirmAddition();
    }

    private function handleEmployeeDeletion() {
        if ($this->roster->count() === 0) {
            $this->showError("No employees to delete");
            return;
        }

        while (true) {
            $this->displayHeader("Delete Employee");
            $this->roster->display();
            $empNum = $this->validateInput("\nEnter employee number (0 to cancel): ", 'int');
            
            if ($empNum === 0) return;
            
            if ($this->roster->remove($empNum)) {
                $this->clearScreen();
                echo "Employee successfully removed.\n";
                if ($this->roster->count() === 0) {
                    echo "No more employees to delete.\n";
                    readline("Press Enter to continue...");
                    return;
                }
                readline("Press Enter to continue deleting...");
            } else {
                echo "Failed to delete employee. Please check the ID and try again.\n";
                readline("Press Enter to continue...");
            }
        }
    }

    private function handleOtherOperations() {
        while (true) {
            $this->displayHeader("Other Operations");
            echo "[1] Display\n";
            echo "[2] Count\n";
            echo "[3] Payroll\n";
            echo "[0] Back\n\n";
            
            switch ($this->validateInput("Select option: ", 'int')) {
                case 1:
                    $this->handleDisplayOptions();
                    break;
                case 2:
                    $this->handleCountOperations();
                    break;
                case 3:
                    $this->handlePayroll();
                    break;
                case 0:
                    return;
                default:
                    $this->showError("Invalid option");
            }
        }
    }

    private function handleCountOperations() {
        while (true) {
            $this->displayHeader("Count Operations");
            echo "[1] Count All Employees\n";
            echo "[2] Count All Commission Employees\n";
            echo "[3] Count All Hourly Employees\n";
            echo "[4] Count All Piece Workers\n";
            echo "[0] Back\n\n";
            
            switch ($this->validateInput("Select option: ", 'int')) {
                case 1:
                    $this->clearScreen();
                    echo "Total Employees: " . $this->roster->count() . "\n";
                    break;
                case 2:
                    $this->clearScreen();
                    echo "Total Commission Employees: " . $this->roster->countCE() . "\n";
                    break;
                case 3:
                    $this->clearScreen();
                    echo "Total Hourly Employees: " . $this->roster->countHE() . "\n";
                    break;
                case 4:
                    $this->clearScreen();
                    echo "Total Piece Workers: " . $this->roster->countPE() . "\n";
                    break;
                case 0:
                    return;
                default:
                    $this->showError("Invalid option");
                    continue;
            }
            readline("Press Enter to continue...");
        }
    }

    private function handleDisplayOptions() {
        do {
            $this->displayHeader("Display Options");
            echo "[1] All Employees\n[2] Commission Employees\n";
            echo "[3] Hourly Employees\n[4] Piece Workers\n[0] Back\n";
            
            switch ($this->validateInput("Select option: ", 'int')) {
                case 0: 
                    $this->clearScreen();
                    return;
                case 1: 
                    $this->clearScreen();
                    $this->roster->display(); 
                    break;
                case 2: 
                    $this->clearScreen();
                    $this->roster->displayCE(); 
                    break;
                case 3: 
                    $this->clearScreen();
                    $this->roster->displayHE(); 
                    break;
                case 4: 
                    $this->clearScreen();
                    $this->roster->displayPE(); 
                    break;
                default: 
                    $this->showError("Invalid option"); 
                    continue;
            }
            readline("Press Enter to continue...");
        } while (true);
    }

    private function handleCountOptions() {
        $this->displayHeader("Employee Counts");
        echo "Total Employees: " . $this->roster->count() . "\n";
        echo "Commission Employees: " . $this->roster->countCE() . "\n";
        echo "Hourly Employees: " . $this->roster->countHE() . "\n";
        echo "Piece Workers: " . $this->roster->countPE() . "\n";
        readline("Press Enter to continue...");
    }

    private function handlePayroll() {
        $this->displayHeader("Payroll");
        if ($this->roster->count() === 0) {
            $this->showError("No employees in roster");
            return;
        }
        $this->roster->payroll();
        readline("Press Enter to continue...");
    }

    private function validateInput($prompt, $type) {
        do {
            if ($prompt) {
                echo $prompt;  // Use echo instead of readline's prompt
            }
            $input = rtrim(fgets(STDIN));  // Use fgets instead of readline for direct input
            switch ($type) {
                case 'string':
                    if (preg_match("/^[a-zA-Z\s.-]+$/", $input)) return $input; // Added support for dots and hyphens
                    break;
                case 'int':
                    if (filter_var($input, FILTER_VALIDATE_INT) !== false && $input >= 0) return (int)$input;
                    break;
                case 'float':
                    if (filter_var($input, FILTER_VALIDATE_FLOAT) !== false && $input >= 0) return (float)$input;
                    break;
            }
            echo "Invalid input. Please try again: ";  // Change error prompt to be inline
        } while (true);
    }

    // Helper methods
    private function showStats() {
        echo "Total slots: {$this->systemState['size']}\n";
        echo "Available slots: " . $this->roster->getAvailableSlots() . "\n";
    }

    private function showError($message) {
        $this->clearScreen();
        echo "$message\n";
        readline("Press Enter to continue...");
        $this->clearScreen();
    }

    private function confirmAddition() {
        $this->displayHeader("Employee Added");
        
        // Check if roster became full after adding
        if ($this->roster->getAvailableSlots() === 0) {
            echo "Roster is now full\n";
            readline("Press Enter to continue...");
            return;
        }

        if ($this->validateInput("Add another? (y/n): ", 'string') === 'y') {
            $this->handleEmployeeAddition();
        }
    }

    private function showMainMenu() {
        echo "\n[1] Add Employee\n";
        echo "[2] Delete Employee\n";
        echo "[3] Other Operations\n";
        echo "[0] Exit\n\n";
    }
}
?>