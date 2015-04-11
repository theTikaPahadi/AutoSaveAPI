<?php
require_once("../class/Error.php");
class Connection
{
    private $link;

    public function __construct()
    {
        $db_conx = new mysqli("localhost", "root", "", "myliveca_baikalpik");
        if (!$db_conx) {
            new Error("Database Connection Failed. CLS_CONN");
        }
        $this->link = $db_conx;
    }

    public function connect()
    {
        return $this->link;
    }


}
