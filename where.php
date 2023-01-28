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
        public function Update($data) {
            $class = get_called_class();
            $db = DAL::getConnection($class);
            $sql = "UPDATE `$class` SET ";
            $fields = array();
            foreach($data as $key => $value) {
                $fields[] = "`$key` = '$value'";
            }
            $sql = $sql . implode(',', $fields);
            $sql = $sql . Select::GetSqlFragment($this);
            $db->query($sql);
        }
    }
?>