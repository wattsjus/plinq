<?php
namespace pLinq;
class Having {
    public $Other;
    public $Conditions = array();
    public function select($fields) {
        return new \pLinq\Select($this, $fields);
    }
    public function limit($numberOfRows, $offset = 0) {
        $limit = new \pLinq\Limit();
        $limit->Offset = $offset;
        $limit->Rows = $numberOfRows;
        $limit->Other = $this;
        return $limit;
    }
    public function orderBy($fields) {
        $order = new \pLinq\Order();
        $order->Fields = $fields;
        $order->Other= $this;
        return $order;
    }
}
?>