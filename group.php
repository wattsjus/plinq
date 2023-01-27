<?php
    class Group {
        public $Other;
        public $Fields;
        public function Select($fields) {
            return new Select($this, $fields);
        }
        public function Having($conditions) {
            $having = new Having();
            $having->Other = $this;
            $having->Conditions = $conditions;
            return $having;
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