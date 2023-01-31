<?php
namespace pLinq;
class Limit {
    public $Other;
    public $Rows;
    public $Offset;
    public function Select($fields) {
        return new \pLinq\Select($this, $fields);
    }
}
?>