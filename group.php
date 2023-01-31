<?php
namespace pLinq;
class Group {
    public $Other;
    public $Fields;
    public function select($fields) {
        return new \pLinq\Select($this, $fields);
    }
    public function having($conditions) {
        $having = new \pLinq\Having();
        $having->Other = $this;
        $having->Conditions = $conditions;
        return $having;
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
        $order->Other = $this;
        return $order;
    }
}
?>