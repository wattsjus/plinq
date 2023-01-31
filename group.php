<?php
namespace pLinq;
class Group {
    public $Other;
    public $Fields;
    public function Select($fields) {
        return new \pLinq\Select($this, $fields);
    }
    public function Having($conditions) {
        $having = new \pLinq\Having();
        $having->Other = $this;
        $having->Conditions = $conditions;
        return $having;
    }
    public function Limit($numberOfRows, $offset = 0) {
        $limit = new \pLinq\Limit();
        $limit->Offset = $offset;
        $limit->Rows = $numberOfRows;
        $limit->Other = $this;
        return $limit;
    }
    public function OrderBy($fields) {
        $order = new \pLinq\Order();
        $order->Fields = $fields;
        $order->Other = $this;
        return $order;
    }
}
?>