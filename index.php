<?php
/*
This file was written by Tika Pahadi http://tika.me
Happy Coding!! Love you Guys!!!
Used here with permission
You may use or modify the file with the permission at:
tika@akitech.org


*/

session_start();

/*
    Include the Following Files here: IMPORTANT!!!!
    # Session Validator (Check so that anyone cannot submit to here)
 */

require_once("config.php");
require_once("Connection.php");
require_once("LifeSaver.php");


//Supports both POST and GET:

if(isset($_POST["action"]) || isset($_GET["action"])){
    $data = (isset($_POST['action'])) ? $_POST : $_GET;

    $connection = new AutoSaveAPI\Connection();
    $LifeSaver = new AutoSaveAPI\LifeSaver($connection->db_link,$data,$redirect);

    if($LifeSaver->validate()){
        $success = $LifeSaver->save();
        if($success){
            //Successful. !!!!
            if($errorReturnType == "json"){
                $log = json_encode(
                    array('success' => true, 'affected_rows' => $LifeSaver->affected_rows)
                );
            } else {
                $log = "Entry successful.";
            }
        } else {
            $log = $LifeSaver->getErrors($errorReturnType);
        }

    } else {
        $log = $LifeSaver->getErrors($errorReturnType);
    }

} else {
    if($errorReturnType == "json"){
        $log = json_encode(
            array(
                'success' => false,
                'errors' => array('No data posted.')
            )
        );
    } else {
        $log = "No data posted.";
    }
}







//Finally, if redirect is set, we redirect to the URL else "echo" the log here....

if($redirect){
    $_SESSION[$sessionVar] = $log;
    header("Location:".$LifeSaver->getRedirection());
    //Don't forget : session_start() on the top of the page
    //Also unset($_SESSION[$sessionVar]) once you echo it. Else, it will come on every page
} else {
    echo $log;
}


/*

//Now Files


foreach($_FILES as $name=>$data){
    if($data["size"] > 0){
        $value = upload($name,"uploads/");
        if ($value != false) {
            $insert .= " `$name`,";
            $values .= "'{$value}',";
        }
    }
}

// upload function


function upload($name,$target){
    $allowedExts = array(
        "gif", "jpeg", "jpg", "png","JPG","PNG","GIF","JPEG",
        "doc","DOC","docx","DOCX","pdf","PDF",
        "rtf","RTF","txt","TXT","ODF","odf"
    );
    $temp = explode(".", $_FILES[$name]["name"]);
    $extension = end($temp);
    $filename = $name."_".rand(12345678,3456789045678)."_".rand(12345678,3456789045678).".".$extension;

    if (($_FILES[$name]["size"] < 10000000) && in_array($extension, $allowedExts)) {

        if ($_FILES[$name]["error"] > 0) {
            $message =  "File Upload Unsuccessful. Error: " . $_FILES["cv"]["error"] ;echo $message;
            exit();
            return false;
        } else {
            move_uploaded_file($_FILES[$name]["tmp_name"],"$target" . $filename);
            //$message = "File Successfully Uploaded. ";
            return $filename;

        }

    } else {
        $message = "Invalid file. File must be doc/docx/pdf/odf/rtf/txt/jpg/gif/png and below 10 MB";
        echo $message;
        exit();
        return false;
    }
}

*/
?>