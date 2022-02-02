
<?php

class DB{

    // define a variable for the database connection
    private static $connection;

    // when an instance of 'connection' class is created a connection is made through mysqli
    private function __construct()
    {

        // the connection is stored inside the private variable
        self::$connection = new mysqli('localhost', 'root', '', 'BeachBumWeather');

        // Check connection
        if (self::$connection->connect_error) {
            die("Connection failed: " . self::$connection->connect_error);
            return false;
        } else{
            return true;
        }
    }

    public  static function getconnection()
    {
        if (!self::$connection) {
            self::$connection = new mysqli('localhost', 'root', '', 'BeachBumWeather');
        }
        return self::$connection;
    }

    // method used to send a query to database
    public function query($sql)
    {
        // here you use the connection made on __construct() and apply the method query. Basically we are using inside our method (called query) a method (call query too) from the mysqli class
        $this->conn->query($sql);
        return true;
    }
    public function prepare($sql)
    {
        // here you use the connection made on __construct() and apply the method query. Basically we are using inside our method (called query) a method (call query too) from the mysqli class
        $this->conn->prepare($sql);
        return true;
    }



}
