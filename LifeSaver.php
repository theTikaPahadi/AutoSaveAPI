<?php

namespace AutoSaveAPI;

require_once("Saver.php");

class LifeSaver extends Saver{

    public function __construct($db_link,$data,$redirect){
        $this->db_link      = $db_link; //Database Object : MySQLI for Now. Maybe PDO Soon!!!
        $this->data         = $data;
        $this->redirect     = $db_link->real_escape_string($redirect);
    }

    /*
     * @action  : validates the requirement of table and redirect URL
     * @return  : boolean.
     */
    public function validate(){

        //1. Redirect Validation

        if($this->redirect){
            if(!isset($this->data["redirectURL"])){
                $this->errors[] = "Redirection set but Redirection URL not set.";
                return false;
            }
            $this->redirectURL = $this->db_link->real_escape_string($this->data["redirectURL"]);
        }


        //2. Table Validation
        if(!isset($this->data["table"])){
            $this->errors[] = "Table not set.";
            return false;
        }
        $this->table = $this->db_link->real_escape_string($this->data["table"]);


        //3. If action is set. Again, you may default this:
        if(!isset($this->data["action"])){
            $this->errors[] = "Action not set.";
            return false;
        } else {
            //Extra Layer of Validation for Data
            return $this->validateAction();
        }

        //Return true if everything is fine!!
        return true;

    }

    /*
     * @action  : validates the action and data requirement based on it
     * @return  : boolean
     */

    private function validateAction(){
        $this->action = strtolower($this->db_link->real_escape_string($this->data["action"]));

        //1. If insert, nothing is required
        if($this->action == "insert"){
            return true;
        }

        /*
         * CAUTION :
         *  You might want to remove delete for public sites
         *  Use at your OWN Risk
         */
        //2. If update, column name and column value is required
        //For Eg : WHERE `id` = 5
        if($this->action == "update" || $this->action == "delete"){
            if(!isset($this->data["__id"])){
                $this->errors[] = "For update or delete, column id(__id) is required. For eg : ... WHERE `id` = ....";
                return false;
            }
            if(!isset($this->data["__val"])){
                $this->errors[] = "For update or delete, column id value(__val) is required. For eg : ... WHERE `...` = 5....";
                return false;
            }

            $this->__id = $this->db_link->real_escape_string($this->data["__id"]);
            $this->__val = $this->db_link->real_escape_string($this->data["__val"]);

            return true;
        }

        //For any other action return False
        $this->errors[] = "The action is not available or invalid.";
        return false;


    }


    /*
     * @returns : (json/html) Errors based on type
     */

    public function getErrors($type){
        if($type == "json"){
            return $this->getErrorsAsJSON();
        } else {
            //Just Defaulting to text if not JSON
            return $this->getErrorsAsHTML();
        }

    }
    /*
     * @return : string of the errors from the private array $errors
     *
     */
    private function getErrorsAsHTML(){
        if(count($this->errors) == 0){
            $string = "No errors were found";
        } else {
            $string = "The following errors were found: ";
            foreach($this->errors as $error){
                $string .=  "<br />" . $error ;
            }
        }

        return $string;
    }
    /*
     * @return : json object of the errors from the private array $errors
     *
     */
    private function getErrorsAsJSON(){
        if(count($this->errors) == 0){
            $array = array(
                "success" => true)
            ;
        } else {
            $array = array(
                "success" => false,
                "errors" => $this->errors
            );
        }

        return json_encode($array);
    }



}