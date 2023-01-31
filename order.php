<?php
namespace pLinq;
class Order {
    public $fields;
    public function Select($fields) {
        return new \pLinq\Select($this, $fields);
    }
}
?>