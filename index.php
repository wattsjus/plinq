<?php
namespace pLinq;
require_once(__DIR__.'/data-model.php');
class DAL {
    private $connections = array();
    private $maps = array();
    public function addConnection($name, $server, $username, $password, $dbName, $autoGenerate = false) {
        $this->connections[$name] = $db = new \mysqli($server,$username,$password,$dbName);
        if($autoGenerate) {
            $sql = "SELECT table_name FROM information_schema.tables WHERE table_schema = '$dbName'";
            $tableNamesResult = $db->query($sql);
            $tableNames = $tableNamesResult->fetch_all(MYSQLI_ASSOC);
            foreach($tableNames as $tableName) {
                $this->GenerateClass($tableName['table_name']);
                $this->maptoConnection($tableName['table_name'], $name);
            }
        }
    }
    public function mapToConnection($class, $connection) {
        $this->maps[$class] = $connection;
    }
    public function getConnection($class) {
        $connectionName = $this->maps[$class];
        $connection = $this->connections[$connectionName];
        return $connection;
    }
    public function isFound($class) {
        return array_key_exists($class, $this->maps);
    }
    public function GenerateClass($name) {
        $class = "class $name extends \pLinq\DataModel {}";
        eval($class);
    }
}
$dal = new DAL();
spl_autoload_register(function ($class_name) {
    {
        global $dal;
        if($dal->isFound($class_name)) {
            $dal->GenerateClass($class_name);
        }
    }
});

?>