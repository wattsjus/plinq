<?php
class Select {
    private $other;
    private $fields;
    private $executed = false;
    function __construct($other, $fields) {
        $this->other = $other;
        $this->fields = $fields;
    }
    function FirstOrDefault() {
        $limit = new Limit();
        $limit->Other = $this->other;
        $limit->Rows = 1;
        $limit->Offset = 0;
        $this->Other = $limit;
        $result = $this->ToArray();
        if(count($result) > 0) {
            return $result[0];
        } else {
            return null;
        }
    }
    private function GetSql($select) {
        if(is_array($select->fields)) {
            $select->fields = implode(',', $select->fields);
        }
        $sql = "SELECT $select->fields FROM ";
        if(is_string($select->other)) {
            $sql = $sql . " `$select->other` ";
        } else {
            $sql = $sql . Select::GetSqlFragment($select->other);
        }
        return $sql;
    }
    function ToArray() {
        $sql = $this->GetSql($this);
        $table = $this->_GetFirstTable($this->other);
        global $dal;
        $db = $dal->getConnection($table);
        $result = $db->query($sql)->fetch_all(MYSQLI_ASSOC);
        $return = array();
        foreach($result as $key => $val) {
            $return[] = $val;
        }
        return $return;
    }
    private function _GetFirstTable($other) {
        if(is_string($other)) {
            return $other;
        } else {
            $keyword = get_class($other);
            if($keyword == 'AsModel') {
                return $other->Table;
            } else if($keyword == 'Join') {
                return $this->_GetFirstTable($other->LeftAs);
            } else if($keyword == 'Where'
                || $keyword == 'Group'
                || $keyword == 'Having'
                || $keyword == 'Limit'
                || $keyword == 'Order'
                || $keyword == 'Select') {
                return $this->_GetFirstTable($other->Other);
            }
        }
    }
    public static function GetSqlFragment($other) {
        $keyword = get_class($other);
        if($keyword == 'AsModel') {
            $sql = " `$other->Table` $other->Alias ";
            if(isset($other->Other)) {
                $sql = $sql  . Select::GetSqlFragment($other->Other);
            }
        } else if($keyword == 'Join') {
            $sql = Select::GetSqlFragment($other->LeftAs) . ' JOIN ';
            if(get_class($other->RightAs) == 'AsModel') {
                $sql = $sql . Select::GetSqlFragment($other->RightAs);
                $sql = $sql . ' ON ' . $other->JoinFields;
            } else if(get_class($other->RightAs) == 'Select') {
                $innerSql = $this->GetSql($other->RightAs);
                $sql = $sql . '(' . $innerSql . ') ';
                $sql = $sql . $other->JoinAlias . ' ';
                $sql = $sql . ' ON ' . $other->JoinFields;
            }
        } else if($keyword == 'Where') {
            $sql = '';
            if(isset($other->Other)) {
                $sql = Select::GetSqlFragment($other->Other);
            }
            return "$sql WHERE $other->Conditions ";
        } else if($keyword == 'Group') {
            $sql = '';
            if(isset($other->Other)) {
                $sql = Select::GetSqlFragment($other->Other);
            }
            return "$sql GROUP BY $other->Fields";
        } else if($keyword == 'Having') {
            $sql = '';
            if(isset($other->Other)) {
                $sql = Select::GetSqlFragment($other->Other);
            }
            return "$sql HAVING $other->Conditions ";
        } else if($keyword == 'Limit') {
            $sql = '';
            if(isset($other->Other)) {
                $sql = Select::GetSqlFragment($other->Other);
            }
            return "$sql LIMIT $other->Offset, $other->Rows";
        } else if($keyword == 'Order') {
            $sql = '';
            if(isset($other->Other)) {
                $sql = Select::GetSqlFragment($other->Other);
            }
            return "$sql ORDER BY $other->Fields";
        }
        return $sql;
    }
}
?>