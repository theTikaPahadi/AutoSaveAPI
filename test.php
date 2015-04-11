<?php
require(__DIR__."/Connection.php");

$con = new Connection();


$q = $con->db_link->query("SELECT * FROM `practice`");

var_dump($con->db_link);

while($row = mysqli_fetch_assoc($q)){
    var_dump($row);
}