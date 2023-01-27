<?php
    class Where {
        public $Other;
        public $Conditions;
        public function Select($fields) {
            return new Select($this, $fields);
        }
        public function GroupBy($fields) {
            $group = new Group();
            $group->Other = $this;
            $group->Fields = $fields;
            return $group;
        }
        public function Limit($numberOfRows, $offset = 0) {
            $limit = new Limit();
            $limit->Offset = $offset;
            $limit->Rows = $numberOfRows;
            $limit->Other = $this;
            return $limit;
        }
        public function OrderBy($fields) {
            $order = new Order();
            $order->Fields = $fields;
            $order->Other = $this;
            return $order;
        }
    }
?>