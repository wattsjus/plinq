<?php
    require_once(__DIR__.'/data-model.php');
    class DAL {
        private $connections = array();
        private $maps = array();
        public function addConnection($name, $server, $username, $password, $dbName) {
            $this->connections[$name] = new mysqli($server,$username,$password,$dbName);
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
    }
    $dal = new DAL();
    spl_autoload_register(function ($class_name) {
        {
            global $dal;
            if($dal->isFound($class_name)) {
                $class = "class $class_name extends DataModel {}";
                eval($class);
            }
        }
    });

?>