<?php
class Limit {
    public $Other;
    public $Rows;
    public $Offset;
    public function Select($fields) {
        return new Select($this, $fields);
    }
}
?>