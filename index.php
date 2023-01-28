<?php
    require_once(__DIR__.'/data-model.php');
    class DAL {
        private static $connections = array();
        private static $maps = array();
        public static function addConnection($name, $server, $username, $password, $dbName) {
            DAL::$connections[$name] = new mysqli($server,$username,$password,$dbName);
        }
        public static function mapToConnection($class, $connection) {
            DAL::$maps[$class] = $connection;
        }
        public static function getConnection($class) {
            $connectionName = DAL::$maps[$class];
            $connection = DAL::$connections[$connectionName];
            return $connection;
        }
        public static function isFound($class) {
            return array_key_exists($class, DAL::$maps);
        }
    }
    spl_autoload_register(function ($class_name) {
        {
            if(DAL::isFound($class_name)) {
                $class = "class $class_name extends DataModel {}";
                eval($class);
            }
        }
    });

?>