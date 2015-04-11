<?php
if(!isset($_SESSION)){
    session_start();
}

if($_SESSION["access"] != true){
    // Redirect to index
    $_SESSION["redirect"] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    header("Location: ../login.php");
    exit();
}

class Error {

    public function __construct($error){
        echo $error;
        exit();
    }

}