<?php
/*
This file is created and property of Akitech Labs(akitech.org)
Used here with permission
You may not use, copy or re-produce this file without the owner permission.
mail@akitech.org

*/

require("../class/Connection.php");	//Connection File
$db_con = new Connection();
$link = $db_con->connect();


if(isset($_POST["action"])){
$data = $_POST;
} elseif(isset($_GET["action"])) {
$data = $_GET;
}


//If the form data is sent
if (isset($data["action"]) && isset($data["redirect_uri"]) && isset($data["table"])){
    
    //table is same as the MYSQLI TABLE NAME
    $table =  mysqli_real_escape_string($link,$data["table"]);
    $red_uri =  mysqli_real_escape_string($link,$data["redirect_uri"]);
    $action =  mysqli_real_escape_string($link,$data["action"]);
    
   
   
    if($action=="insert")
    {
      //This is to add new entry
      $insert = "";
      $values = "";
      
       
        foreach ($data as $index => $value) {
         
        if ($index == "submit" || $index == "action" || $index == "table" || $index == "redirect_uri" || $index == "no-entry" || $index == "col"|| $index == "id"){continue;}

        $index = mysqli_real_escape_string($link,$index);
	    $value = nl2br(mysqli_real_escape_string($link,$value));

        $insert .= " `{$index}`,";
        $values .= "'{$value}',";

        }


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
        
        //Removing the last comma by cutting the string to 1 letter from last
          $ln_insert = strlen($insert) - 1;
          $ln_values = strlen($values) - 1;
          $insert = substr($insert, 0,$ln_insert);
          $values = substr($values, 0,$ln_values);


          $sql = "INSERT INTO `".$table."` ({$insert}) VALUES ({$values})";

          $query = mysqli_query($link, $sql);
          if (mysqli_affected_rows($link)==1){
            $_SESSION["message"] = "<p class='pass'>The entry is successful.</p>";
             } else {
            $_SESSION["message"] = "<p class='fail'>Failed to make the entry. ".mysqli_error($link)."</p>";
          }
          
    }
    else if ($action == "delete")
    {
      if(isset($data["col"]) && isset($data["id"])){
          $col = mysqli_real_escape_string($link,$data["col"]);
          $id = mysqli_real_escape_string($link,$data["id"]);
          $sql = "DELETE FROM `".$table."` WHERE `{$col}` = '{$id}'";

          $query = mysqli_query($link, $sql);
          if (mysqli_affected_rows($link)==1){
            $_SESSION["message"] = "<p class='pass'>Successfuly Deleted..</p>";
             } else {
            $_SESSION["message"] = "<p class='fail'>Failed to delete the entry. ".mysqli_error($link)."</p>";
          }
      
      }
      
      else
      {      
	  $_SESSION["message"] =  "<p class='fail'>Delete failed. No Col/ID provided.</p>";
      }
  
          
    
    }
    else if ($action == "update")
    {
      // This is to update
        if(isset($data["col"]) && isset($data["id"])){
            $col = mysqli_real_escape_string($link,$data["col"]);
            $id = mysqli_real_escape_string($link,$data["id"]);
            $string = "";
      
        foreach ($data as $index => $value) {
            $index = mysqli_real_escape_string($link,$index);
	        $value = nl2br(mysqli_real_escape_string($link,$value));
            if ($index == "submit" || $index == "action" || $index == "table" || $index == "redirect_uri" || $index == "no-entry" || $index == "col" || $index == "id"){
                continue;
            } else {
                $string .= " `{$index}` = '{$value}',";
            }
        
        }

            //Now Files

            foreach($_FILES as $name=>$data){
                if($data["size"] > 0){
                    $value = upload($name,"uploads/");
                    if ($value != false) {
                        $string .= " `{$index}` = '{$value}',";
                    }
                }
            }



            //Removing the last comma by cutting the string to 1 letter from last


            $string = substr($string, 0,strlen($string)-1);
            $sql = "UPDATE `{$table}` SET {$string} WHERE `{$col}` = '{$id}'";


          $query = mysqli_query($link, $sql);
          if (mysqli_affected_rows($link)==1){
            $_SESSION["message"] =  "<p class='pass'>The update is successful.</p>";
             } else {
            $_SESSION["message"] =  "<p class='fail'>Failed to update the entry. ".mysqli_error($link)."</p>";
          }
        
      }
      else
      {      
	  $_SESSION["message"] =  "<p class='fail'>Update failed. No Col/ID provided.</p>";
      }
  


    } 
    else
    {
	  $_SESSION["message"] =  "<p class='fail'>Invalid Actions</p>";
          
    }

}
else {
	$_SESSION["message"] =  "<p class='fail'>Invalid Paramaters</p>";
}

    header("Location:".$red_uri);


/*
 upload function
 */

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

?>