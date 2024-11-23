<?php
class Person {
    private $name = '';
    private $address = '';
    private $age = 0;

    public function __construct($name, $address, $age) {
        $this->name = $name;
        $this->address = $address;
        $this->age = $age;
    }

    // Getters and setters
    public function getName() { return $this->name; }
    public function getAddress() { return $this->address; }
    public function getAge() { return $this->age; }
    public function setName($name) { $this->name = $name; }
    public function setAddress($address) { $this->address = $address; }
    public function setAge($age) { $this->age = $age; }

    public function __toString() {
        return sprintf("Name: %s\nAddress: %s\nAge: %d",
            $this->name,
            $this->address,
            $this->age
        );
    }
}
?>