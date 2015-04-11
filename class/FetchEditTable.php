<?php
require_once("../class/Connection.php");
class FetchEditTable {
    private $table;
    private $results = array();

    public function __construct($table,$limit){
        $db_con = new Connection();
        $link = $db_con->connect();
        $this->table = $table;
        $sql = "SELECT * FROM `$table` LIMIT $limit";
        $query = mysqli_query($link, $sql);

        while($row = $query->fetch_assoc()){
            array_push($this->results,$row);
        }


        $link->close();
    }

    public function getResults(){
        return $this->results;
    }
}