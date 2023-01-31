<?php
namespace pLinq;
class AsModel {
    public $Table;
    public $Alias;
    public function join($other, $fields, $joinAlias = null) {
        $join = new Join();
        $join->RightAs = $other;
        $join->LeftAs = $this;
        $join->JoinAlias = $joinAlias;
        $join->JoinFields = $fields;
        return $join;
    }
    public function where($conditions) {
        $where = new Where();
        $where->Other = $this;
        $where->Conditions = $conditions;
        return $where;
    }
    public function select($fields) {
        return new Select($this, $fields);
    }
}
?>