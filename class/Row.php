<?php
require_once("../class/Connection.php");
class Row {
    public $table;
    public $action;
    public $redirect_uri;


    //For Update & Delete
    public $col;
    public $id;

    //To find
    public $data_types;
    public $vals;

    public function __construct($table,$action,$col,$id,$redirect_uri){
        $this->table            = $table;
        $this->col              = $col;
        $this->id               = $id;
        $this->action           = $action;
        $this->redirect_uri     = $redirect_uri;

        $f = new FetchFields($table);
        $this->data_types = $f->getFields();

        if($action == "update"){
            $this->fetch_values();
        }


    }


    public function fetch_values(){
        $db_con = new Connection();
        $link = $db_con->connect();
        $sql = "SELECT * FROM `$this->table` WHERE `$this->col` = '$this->id' LIMIT 1";
        $query = mysqli_query($link, $sql);

        while($row = $query->fetch_assoc()){
            $this->vals = $row;
            break; //we need have one
        }


        $link->close();

    }





}