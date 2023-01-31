<?php
namespace pLinq;
require_once(__DIR__.'/data-model.php');
class DAL {
    private $connections = array();
    public function addConnection($name, $server, $username, $password, $dbName, $autoGenerate = false) {
        $this->connections[$name] = $db = new \mysqli($server,$username,$password,$dbName);
        if($autoGenerate) {
            $sql = "SELECT table_name FROM information_schema.tables WHERE table_schema = '$dbName'";
            $tableNamesResult = $db->query($sql);
            $tableNames = $tableNamesResult->fetch_all(MYSQLI_ASSOC);
            foreach($tableNames as $tableName) {
                $this->GenerateClass($name, $tableName['table_name']);
            }
        }
    }
    public function getConnection($className) {
        $connectionName = explode('\\',$className)[0];
        $connection = $this->connections[$connectionName];
        return $connection;
    }
    public function GenerateClass($connectionName, $name) {
        $class = "namespace $connectionName; class $name extends \pLinq\DataModel {}";
        eval($class);
    }
}
$dal = new DAL();
spl_autoload_register(function ($class_name) {
    {
        global $dal;
        $dal->GenerateClass($class_name);
    }
});

?>