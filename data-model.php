<?php
include_once(__DIR__.'/as.php');
include_once(__DIR__.'/group.php');
include_once(__DIR__.'/having.php');
include_once(__DIR__.'/join.php');
include_once(__DIR__.'/where.php');
include_once(__DIR__.'/select.php');
include_once(__DIR__.'/limit.php');
include_once(__DIR__.'/order.php');
class DataModel {
    public static function As($alias = null) {
        $as1 = new AsModel();
        $as1->Table = get_called_class();
        $as1->Alias = $alias;
        return $as1;
    }
    public static function Join($toJoin, $joinFields, $joinAlias = null) {
        $join = new Join();
        $join->LeftAs = DataModel::As();
        $join->RightAs = $toJoin;
        $join->JoinFields = $joinFeilds;
        $join->JoinAlias = $joinAlias;
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
