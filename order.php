<?php
class Order {
    public $fields;
    public function Select($fields) {
        return new Select($this, $fields);
    }
}
?>