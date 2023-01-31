<?php
namespace pLinq;
class Select {
    private $other;
    private $fields;
    private $executed = false;
    function __construct($other, $fields) {
        $this->other = $other;
        $this->fields = $fields;
    }
    function firstOrDefault() {
        $limit = new \pLinq\Limit();
        $limit->Other = $this->other;
        $limit->Rows = 1;
        $limit->Offset = 0;
        $this->Other = $limit;
        $result = $this->toArray();
        if(count($result) > 0) {
            return $result[0];
        } else {
            return null;
        }
    }
    private function getSql($select) {
        if(is_array($select->fields)) {
            $select->fields = implode(',', $select->fields);
        }
        $sql = "SELECT $select->fields FROM ";
        if(is_string($select->other)) {
            $tableName = explode('\\', $select->other)[1];
            $sql = $sql . " `$tableName` ";
        } else {
            $sql = $sql . \pLinq\Select::getSqlFragment($select->other);
        }
        return $sql;
    }
    function toArray() {
        $sql = $this->getSql($this);
        $table = \pLinq\Select::getFirstTable($this->other);
        global $dal;
        $db = $dal->getConnection($table);
        $result = $db->query($sql)->fetch_all(MYSQLI_ASSOC);
        $return = array();
        foreach($result as $key => $val) {
            $return[] = $val;
        }
        return $return;
    }
    public static function getFirstTable($other) {
        if(is_string($other)) {
            return $other;
        } else {
            $keyword = get_class($other);
            if($keyword == 'pLinq\AsModel') {
                return $other->Table;
            } else if($keyword == 'pLinq\Join') {
                return \pLinq\Select::getFirstTable($other->LeftAs);
            } else if($keyword == 'pLinq\Where'
                || $keyword == 'pLinq\Group'
                || $keyword == 'pLinq\Having'
                || $keyword == 'pLinq\Limit'
                || $keyword == 'pLinq\Order'
                || $keyword == 'pLinq\Select') {
                return \pLinq\Select::getFirstTable($other->Other);
            }
        }
    }
    public static function getSqlFragment($other, $ignoreOther = false) {
        $keyword = get_class($other);
        if($keyword == 'pLinq\AsModel') {
            $tableName = explode('\\', $other->Table)[1];
            $sql = " `$tableName` $other->Alias ";
            if(isset($other->Other)) {
                $sql = $sql  . \pLinq\Select::getSqlFragment($other->Other);
            }
        } else if($keyword == 'pLinq\Join') {
            $sql = \pLinq\Select::getSqlFragment($other->LeftAs) . ' JOIN ';
            if(get_class($other->RightAs) == 'AsModel') {
                $sql = $sql . \pLinq\Select::getSqlFragment($other->RightAs);
                $sql = $sql . ' ON ' . $other->JoinFields;
            } else if(get_class($other->RightAs) == 'Select') {
                $innerSql = $this->getSql($other->RightAs);
                $sql = $sql . '(' . $innerSql . ') ';
                $sql = $sql . $other->JoinAlias . ' ';
                $sql = $sql . ' ON ' . $other->JoinFields;
            }
        } else if($keyword == 'pLinq\Where') {
            $sql = '';
            if(!$ignoreOther
                && isset($other->Other)) {
                $sql = \pLinq\Select::getSqlFragment($other->Other);
            }
            return "$sql WHERE $other->Conditions ";
        } else if($keyword == 'pLinq\Group') {
            $sql = '';
            if(isset($other->Other)) {
                $sql = \pLinq\Select::getSqlFragment($other->Other);
            }
            return "$sql GROUP BY $other->Fields";
        } else if($keyword == 'pLinq\Having') {
            $sql = '';
            if(isset($other->Other)) {
                $sql = \pLinq\Select::getSqlFragment($other->Other);
            }
            return "$sql HAVING $other->Conditions ";
        } else if($keyword == 'pLinq\Limit') {
            $sql = '';
            if(isset($other->Other)) {
                $sql = \pLinq\Select::getSqlFragment($other->Other);
            }
            return "$sql LIMIT $other->Offset, $other->Rows";
        } else if($keyword == 'pLinq\Order') {
            $sql = '';
            if(isset($other->Other)) {
                $sql = \pLinq\Select::getSqlFragment($other->Other);
            }
            return "$sql ORDER BY $other->Fields";
        }
        return $sql;
    }
}
?>