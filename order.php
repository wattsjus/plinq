<?php
namespace pLinq;
class Order {
    public $fields;
    public function select($fields) {
        return new \pLinq\Select($this, $fields);
    }
}
?>