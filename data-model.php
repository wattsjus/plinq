<?php
include_once('as.php');
include_once('group.php');
include_once('having.php');
include_once('join.php');
include_once('where.php');
include_once('select.php');
include_once('limit.php');
include_once('order.php');
class DataModel {
    public static function As($alias = null) {
        $as1 = new AsModel();
        $as1->Table = get_called_class();
        $as1->Alias = $alias;
        return $as1;
    }
    public static function Join($toJoin, $joinFields) {
        $join = new Join();
        $join->LeftAs = DataModel::As();
        $join->RightAs = $toJoin;
        $join->JoinFields = $joinFeilds;
        return $join;
    }
    public static function Where($conditions) {
        $where = new Where();
        $as1 = new AsModel();
        $as1->Table = get_called_class();
        $where->Other = $as1;
        $where->Conditions = $conditions;
        return $where;
    }
    public static function Group($fields) {
        $group = new Group();
        $as1 = new AsModel();
        $as1->Table = get_called_class();
        $where->Other = $as1;
        $group = $fields;
        return $group;
    }
    public static function Select($fields) {
        return new Select(get_called_class(), $fields);
    }
}
?>
