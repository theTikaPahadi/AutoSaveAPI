<?php

require_once("../class/Connection.php");
require_once("../class/Field.php");
class FetchFields{

    private $fields = array();

    public function getFields(){
        return $this->fields;
    }

    public function __construct($table){
        $c = new Connection();
        $link = $c->connect();
        $query = $link->query("SELECT * FROM `$table` LIMIT 1");
        $row = mysqli_fetch_fields($query);

        foreach($row as $cell){
            if($cell->type == 7){
                continue;   //Timestamp
            }

            $field = new Field();
            $field->name = $cell->name;
            $field->type = $this->get_html_data_type($cell->type,$cell->length,$cell->flags);
            $field->length = $cell->length;
            $field->decimal = $cell->decimals;

            //Some arrangements
            if(
                $field->name == "image"
                || $field->name == "img"
                || $field->name == "cv"
            ){
                $field->type = "file";
            }

            if($field->type == "select"){
                $field->values =  $this->get_enum_values($table,$field->name);
            }

            if(
                $field->name != "ID"
                && $field->name != "id"
            ){
                array_push($this->fields, $field);
            }

        }

    }

    private function get_html_data_type($d,$length,$flags){
        $int = array(1,2,3,4,5,8,9,246,16);
        if($length > 200){
            return "textarea";
        }

        if(in_array($d,$int)){
            return "number";
        } else if($d == 10){
            return "date";
        } else if($d == 11){
            return "time";
        }else if($d == 12){
            return "datetime";
        } else if($d == 254 && ($flags == 4353 || $flags == 6145)){
            return "select";
        }

        return "text";

    }

    private function get_enum_values($table,$field)
    {

        $c = new Connection();
        $link = $c->connect();

        $sql = "SHOW COLUMNS FROM `$table` LIKE '$field'";
        $result = $link->query($sql);
        while($row = mysqli_fetch_assoc($result)){
            $type = $row['Type'];


            preg_match('/set\((.*)\)$/', $type, $matches);
            if(count($matches)<=1){
                preg_match('/enum\((.*)\)$/', $type, $matches);
                if(count($matches)<=1) {
                    return array();
                }
            }


            $vals = explode(",", $matches[1]);

            $i = 0;
            foreach($vals as $val){
                $val = substr($val,1,-1);
                $vals[$i] = $val;
                $i++;
            }

            return $vals;
            break;
        }



    }

}





?>