<?php
    class AsModel {
        public $Table;
        public $Alias;
        public function Join($other, $fields, $joinAlias) {
            $join = new Join();
            $join->RightAs = $other;
            $join->LeftAs = $this;
            $join->JoinAlias = $joinAlias;
            $join->JoinFields = $fields;
            return $join;
        }
        public function Where($conditions) {
            $where = new Where();
            $where->Other = $this;
            $where->Conditions = $conditions;
            return $where;
        }
        public function Select($fields) {
            return new Select($this, $fields);
        }
    }
?>