<?php

//Added Strict Typing
declare(strict_types=1);

namespace pLinq;

#include the data model
require_once(__DIR__.'/data-model.php');

/**
 * DAL class for database access
 */
class DAL {


    // Array to store database connections
    private $connections = [];

    // check the extensions needed are enabled.
    public function __construct()
    {
        // check if the php version is greater than 7
        if (version_compare(phpversion(), '7.0.0', '<')) {
        die('This script requires at least PHP version 7.0.0. Your version is '.phpversion());
        }

        // TODO:: Add more Support for additional db types.
        // Check to ensure the db extension is enabled.
        if (!extension_loaded('mysqli')) {
            die("You must have the MYSQLI Extension Enabled.");
        }
    }

    /**
     * Adds a connection to the connections array to allow for multiple stored connections.
     *
     * @param string $name - Name of the Database connection
     * @param string $server - Name of the server to connect to
     * @param string $username - User for the database connection
     * @param string $password - Password for the database connection
     * @param string $dbName - Name of the database
     * @param bool $autoGenerate - Flag to enable auto generation of classes based on table names.
     * @return void
     */
    public function addConnection(string $name, string $server, string $username, string $password, string $dbName, bool $autoGenerate = false) : void {

        // add the connection to the current instance of the connection array.
        $this->connections[$name] = $db = new \mysqli($server,$username,$password,$dbName);

        // Check if the connection was successful
        if ($db->connect_error) {
            die("Connection failed: " . $db->connect_error);
        }

        // check if autogenerate is set to true.
        if($autoGenerate) {

            // scrub the input data before we add it to the query.
            $dbName = $db->real_escape_string($dbName);

            //Loop the table names and generate classes for all the tables found.
            $sql = "SELECT `table_name` FROM `information_schema`.`tables` WHERE `table_schema` = '$dbName'";
            $tableNamesResult = $db->query($sql);
            $tableNames = $tableNamesResult->fetch_all(MYSQLI_ASSOC);
            foreach($tableNames as $tableName) {
                $this->generateClass($name, $tableName['table_name']);
            }
        }

    }

    /**
     * Retrieves the connection instance from the specified class.
     *
     * @param $className - class name for retrieving the connection
     * @return mixed
     */
    public function getConnection($className) {
        $connectionName = explode('\\',$className)[0];
        return $this->connections[$connectionName];
    }

    /**
     * Generates a class from connection info.
     * @param $connectionName - name of the connection used for the namespace
     * @param $name - Name of the table
     * @return void
     */
    public function generateClass($connectionName, $name) {
        $class = "namespace $connectionName; class $name extends \pLinq\DataModel {}";
        eval($class);
    }

    /**
     * Create a map between the table and the connection.
     * @param string $tableName - name of the database table
     * @param string $nameOfConnection - name of the database connection instance
     * @return void
     */
    public function mapToConnection(string $tableName, string $nameOfConnection)
    {
        $db = $this->connections[$nameOfConnection]; // Get the instance based off of connection name.
        if ($db instanceof \mysqli){
           
            $tableName = $db->real_escape_string($tableName);

            $qr = $db->query("SELECT `TABLE_SCHEMA` 
                                    FROM `information_schema`.`tables`  
                                    WHERE table_name = '$tableName' 
                                    LIMIT 1");
            $arr = $qr->fetch_assoc();

            if (strlen($arr['TABLE_SCHEMA']) > 0 ){
                // create a class for the table.
                $this->generateClass($nameOfConnection, $tableName);
            } else {
                die("Mapping Error: No table found in database");
            }
        } else {
            die("No other database types are currently supported.");
        }
    }
}
// specified a global instance of the DAL
$dal = new DAL();

//Autoload - to generate classes for things that are not found.
spl_autoload_register(function ($class_name) {
    {
        global $dal;
        $dal->GenerateClass($class_name);
    }
});