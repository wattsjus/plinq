<?php
namespace pLinq;
class Where {
    public $Other;
    public $Conditions;
    public function select($fields) {
        return new \pLinq\Select($this, $fields);
    }
    public function groupBy($fields) {
        $group = new \pLinq\Group();
        $group->Other = $this;
        $group->Fields = $fields;
        return $group;
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
    public function update($data) {
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