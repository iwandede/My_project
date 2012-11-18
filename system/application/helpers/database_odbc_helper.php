<?php

class database_odbc {

    private $dsn;
    private $user;
    private $pass;
    private $dbname;
    public static $link_identifier;

    const engine = "odbc";

    public function __construct($dsn = "myDSN", $user = "", $pass = "", $dbname = "project") {
        $this->dsn = $dsn;
        $this->user = $user;
        $this->pass = $pass;
        $this->dbname = $dbname;
    }

    public function connect() {
        if (empty(self::$link_identifier))
            if (!(self::$link_identifier = odbc_connect($this->dsn, $this->user, $this->pass)))
                throw new Exception(odbc_errormsg());
    }

    public function executeQuery($query) {
        if (!($result = odbc_exec(self::$link_identifier, $query)))
            throw new Exception(odbc_errormsg() . " \nThis is the query: " . $query);
        return $result;
    }

    public function executeUpdate($query) {
        if (!($result = odbc_exec(self::$link_identifier, $query)))
            throw new Exception(odbc_errormsg() . " \nThis is the query: " . $query);
        return $result;
    }

    public static function query($query) {
        try {
            $db = new database();
            $db->connect();
            $retval = array();
            $result = $db->executeQuery($query);
            $i = 0;
            while (($row = $db->fetch($result))) {
                $retval[$i] = $row;
                $i++;
            }
            $db->close();
            return $retval;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function fetch($result) {
        return odbc_fetch_array($result);
    }

    public function close() {
        //odbc_close(self::$link_identifier);
    }

}