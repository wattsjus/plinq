<?php
namespace pLinq;
include_once(__DIR__.'/as.php');
include_once(__DIR__.'/group.php');
include_once(__DIR__.'/having.php');
include_once(__DIR__.'/join.php');
include_once(__DIR__.'/where.php');
include_once(__DIR__.'/select.php');
include_once(__DIR__.'/limit.php');
include_once(__DIR__.'/order.php');
class DataModel {
    public static function as($alias = null) {
        $as1 = new \pLinq\AsModel();
        $as1->Table = get_called_class();
        $as1->Alias = $alias;
        return $as1;
    }
    public static function join($toJoin, $joinFields, $joinAlias = null) {
        $join = new \pLinq\Join();
        $join->LeftAs = DataModel::as();
        $join->RightAs = $toJoin;
        $join->JoinFields = $joinFeilds;
        $join->JoinAlias = $joinAlias;
        return $join;
    }
    public static function where($conditions) {
        $where = new Where();
        $as1 = new AsModel();
        $as1->Table = get_called_class();
        $where->Other = $as1;
        $where->Conditions = $conditions;
        return $where;
    }
    public static function group($fields) {
        $group = new \pLinq\Group();
        $as1 = new \pLinq\AsModel();
        $as1->Table = get_called_class();
        $where->Other = $as1;
        $group = $fields;
        return $group;
    }
    public static function insert($data) {
        $class = get_called_class();
        global $dal;
        $db = $dal->getConnection($class);
        $fields = array();
        $values = array();
        foreach($data as $key => $value) {
            $fields[] = "`$key`";
            $values[] = "'$value'";
        }
        $fields = implode(',',$fields);
        $values = implode(',',$values);
        $sql = "INSERT INTO `$class` ($fields) VALUES ($values)";
        $db->query($sql);
        return $db->insert_id;
    }
    public static function update($conditions, $data) {
        $class = get_called_class();
        global $dal;
        $db = $dal->getConnection($class);
        $sql = "UPDATE `$class` SET ";
        $fields = array();
        foreach($data as $key => $value) {
            $fields[] = "`$key` = '$value'";
        }
        $sql = $sql . implode(',', $fields);
        if(is_string($conditions)) {
            $sql = $sql . " WHERE ";
            $wheres = array();
            foreach($conditions as $key=> $value) {
                $wheres[] = "`$key` = '$value'";
            }
            $sql = $sql . implode(',', $wheres);
        } else if($class == 'Where') {
            $sql = $sql . \pLinq\Select::GetSqlFragment($conditions);
        }
        $db->query($sql);
    }
    public static function select($fields) {
        return new \pLinq\Select(get_called_class(), $fields);
    }
}
?>
