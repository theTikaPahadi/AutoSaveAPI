<?php 
require("../class/Connection.php");
$db_conx = new Connection();
$link = $db_conx->connect();
//PART 1
/*
    1. DeterMine Database Table/Formname
    2. Determine Action
        i. If insert, nothing required
        ii. If update or delete, id name and id required

 */

if(isset($_GET["table"])){
    $table         = mysqli_real_escape_string($link, $_GET["table"]);

    //Getting Action
    if(isset($_GET["action"])){
        $action    = mysqli_real_escape_string($link, $_GET["action"]);
    } else {
        $action    = "";
    }


    if ($action == "update" || $action == "delete") {
        if(isset($_GET["col"])){
            $col   = mysqli_real_escape_string($link, $_GET["col"]);
        } else {
            new Error("Column Required to update or delete. ID_REQ");
        }
        if(isset($_GET["id"])){
            $id       = mysqli_real_escape_string($link, $_GET["id"]);
        } else {
            new Error("ID Required to update or delete. ID_REQ");
        }

    } else if($action=="" && !isset($_GET["col"]) && !isset($_GET["id"])){
        $col = "";  //Won't be used through
        $id = -1;   //But required
        $action = "insert";
        //Chill out brother
    } else if($action=="" && isset($_GET["col"]) && isset($_GET["id"])){
        $col   = mysqli_real_escape_string($link, $_GET["col"]);
        $id       = mysqli_real_escape_string($link, $_GET["id"]);
        //Considered as update
        $action = "update";

    } else {
        new Error("Invalid action. INV_ACTION");
    }

} else {
    new Error("Table Name not provided. INS_INF");
}

if(isset($_GET["redirect_uri"])){
    $redirect_uri   = mysqli_real_escape_string($link, $_GET["redirect_uri"]);
} else {
    $redirect_uri = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
}

//Closing connection
$link->close();