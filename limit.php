<?php
namespace pLinq;
class Limit {
    public $Other;
    public $Rows;
    public $Offset;
    public function select($fields) {
        return new \pLinq\Select($this, $fields);
    }
}
?>