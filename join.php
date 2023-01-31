<?php
namespace pLinq;
class Join {
    public $LeftAs;
    public $RightAs;
    public $JoinFields;
    public $JoinAlias;
    public function Where($conditions) {
        $where = new \pLinq\Where();
        $where->Other = $this;
        $where->Conditions = $conditions;
        return $where;
    }
    public function Join($toJoin, $joinFields, $joinAlias = null) {
        $join = new \pLinq\Join();
        $join->LeftAs = $this;
        $join->RightAs = $toJoin;
        $join->JoinFields = $joinFields;
        $join->JoinAlias = $joinAlias;
        return $join;
    }
    public function GroupBy($fields) {
        $group = new \pLinq\Group();
        $group->Other = $this;
        $group->Fields = $fields;
        return $group;
    }
    public function OrderBy($fields) {
        $order = new \pLinq\Order();
        $order->Fields = $fields;
        $order->Other = $this;
        return $order;
    }
    public function Select($fields) {
        return new \pLinq\Select($this, $fields);
    }
}
?>