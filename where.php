<?php
namespace pLinq;
class Where {
    public $Other;
    public $Conditions;
    public function Select($fields) {
        return new \pLinq\Select($this, $fields);
    }
    public function GroupBy($fields) {
        $group = new \pLinq\Group();
        $group->Other = $this;
        $group->Fields = $fields;
        return $group;
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
    public function Update($data) {
        $class = \pLinq\Select::GetFirstTable($this);
        global $dal;
        $db = $dal->getConnection($class);
        $sql = "UPDATE `$class` SET ";
        $fields = array();
        foreach($data as $key => $value) {
            $fields[] = "`$key` = '$value'";
        }
        $sql = $sql . implode(',', $fields);
        $sql = $sql . \pLinq\Select::GetSqlFragment($this, true);
        $db->query($sql);
    }
}
?>