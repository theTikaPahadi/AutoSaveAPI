<?php

//You might want to make a static vars or some constants.
//Upto you! YOUR WAY!
namespace AutoSaveAPI;

class Connection{

    public $db_link;

    public function __construct(){
        $this->db_link = mysqli_connect("localhost","root","","test");
        if(!$this->db_link){
            die("Database Connection Failed.");
        }
    }
}