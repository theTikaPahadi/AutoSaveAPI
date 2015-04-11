<?php

namespace AutoSaveAPI;

class Saver {

    protected $redirectURL = __DIR__;
    protected $redirect = true; //Defaults that the page will redirect
    protected $errors = array();
    protected $data;
    protected $table;
    protected $action;
    protected $fields;
    protected $vals;
    protected $__id;
    protected $__val;
    protected $db_link; //Database Object : MySQLI for Now. Maybe PDO Soon!!!
    public $affected_rows;


    /*
     * @action : gets and performs the SQL Query
     * @return : boolean
     */
    public function save(){
        $sql = $this->prepareStatement();
        $query = $this->db_link->query($sql);
        if($query){
            $this->affected_rows = $this->db_link->affected_rows;
            return true;
        } else {
            $this->errors[] = "Error at Database Query.<br />Error : ".mysqli_error($this->db_link)."<br /> SQL : " . $sql;
            return false;
        }

    }

    /*
     * @action : extracts fields and values from data
     */

    private function extract(){
        $skips = array("table","action","redirectURL","__id","__val");


        foreach($this->data as $_field => $_val){

            //Skipping the fields which are not to be in the SQL statement.
            // Eg : INSERT INTO `table` (`table`,`redirectURL`) VALUES ('test','http://tika.me/thanks/') is NOT what you want!

            if(!in_array($_field,$skips)){
                $field = $this->db_link->real_escape_string($_field);
                $val = $this->db_link->real_escape_string($_val);

                //Customise val based on your needs
                $field  = trim($field);
                $val    = trim($val);
                $val    = nl2br($val);

                $this->fields[] = $field;
                $this->vals[] = $val;
            }

        }
    }

    /*
     * @action : gets SQL Statement
     * @return : string (SQL String)
     */
    private function prepareStatement(){
        $this->extract();
        if($this->action == "insert"){
            return $this->prepareInsertStatement();
        } else if($this->action == "update"){
            return $this->prepareUpdateStatement();
        }else if($this->action == "delete"){
            return $this->prepareDeleteStatement();
        }

    }

    /*
     * @action : prepares Insert Statement
     * @return : string (SQL String)
     */
    private function prepareInsertStatement(){
        $insert = "";
        $values = "";

        for ($i = 0; $i < count($this->fields); $i++) {
            $insert .= "`{$this->fields[$i]}`,";
            $values .= "'{$this->vals[$i]}',";
        }

        //Removing the last comma by cutting the string to 1 letter from last
        $insert = substr($insert, 0,strlen($insert) - 1);
        $values = substr($values, 0,strlen($values) - 1);

        $sql =  "INSERT INTO `".$this->table."` (".$insert.") VALUES (".$values.")";
        return $sql;
    }
    /*
     * @action : prepares Update Statement
     * @return : string (SQL String)
     */
    private function prepareUpdateStatement(){
        $string = "";
        for ($i = 0; $i < count($this->fields); $i++) {
            $string .= " `{$this->fields[$i]}` = '{$this->vals[$i]}',";
        }

        //Removing the last comma by cutting the string to 1 letter from last
        $string = substr($string, 0,strlen($string) - 1);
        $sql = "UPDATE `".$this->table."` SET ".$string." WHERE `".$this->__id."` = '".$this->__val."'";
        return $sql;

    }
    /*
     * @action : prepares Delete Statement
     * @return : string (SQL String)
     */
    private function prepareDeleteStatement(){
        //You might want to limit it certain results
        $sql = "DELETE FROM `".$this->table."` WHERE `".$this->__id."` = '".$this->__val."'";
        return $sql;
    }


    /*
     * @return : string (Redirect URL)
     *
     */
    public function getRedirection(){
        return $this->redirectURL;
    }


}