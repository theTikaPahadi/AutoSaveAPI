<?php
    require_once("../assets/prepare.php");
    require_once("../class/Row.php");
    require_once("../class/FetchFields.php");

    $row = new Row(
        $table,$action,$col,$id,$redirect_uri
    );

    if(isset($_SESSION["message"])){
        echo $_SESSION["message"];
        unset($_SESSION["message"]);
    }
?>



<form method="POST" enctype="multipart/form-data" action="../assets/akitech-auto-save-api.php">
    <input type="hidden" name="redirect_uri" value="<?php echo $row->redirect_uri; ?>" />
    <input type="hidden" name="table" value="<?php echo $row->table; ?>" />
    <input type="hidden" name="action" value="<?php echo $row->action; ?>" />
    <input type="hidden" name="col" value="<?php echo $row->col; ?>" />
    <input type="hidden" name="id" value="<?php echo $row->id; ?>" />
<?php

foreach($row->data_types as $data_type){
    $name = $data_type->name;
    $type = $data_type->type;
    $length = $data_type->length;
    $select_vales = $data_type->values;
    if($data_type->decimal == 0){
        $step = 1;
    } else {
        $step = 1/($data_type->decimal);
    }

    if($data_type == "hidden" || $data_type == null || $data_type == "null" || $data_type == ""){
        continue;
    } else {
        $val = ($row->action == "update") ? $row->vals[$name] : "";
        if($type == "textarea"){
            echo "\n\t<label>".prepare_name($name).":</label><textarea name='$name' maxlength='$length'>$val</textarea><br />";
        } else if($type == "number") {
            echo "\n\t<label>".prepare_name($name)." :</label><input type='$type' name='$name' value='$val' maxlength='$length' step='$step'/><br />";
        } else if($type == "select") {
            echo "\n\t<label>".prepare_name($name)." :</label><select name='$name'>";

            foreach($select_vales as $opt_value){
                $selected = ($val == $opt_value) ? "selected" : "";
                echo "<option value='$opt_value' $selected>".prepare_name($opt_value)."</option>";
            }

            echo "</select><br />";


        } else {
            echo "\n\t<label>".prepare_name($name)." :</label><input type='$type' name='$name' value='$val' maxlength='$length'/><br />";
        }
    }

}


?>

    <input type="submit" value="Save" />
</form>

<?php

function prepare_name($name){
    $name = explode("_",$name);
    $name = implode(" ",$name);

    $name = explode("-",$name);
    $name = implode(" ",$name);

    $name = preg_split('/(?=[A-Z])/',$name);
    $name = implode(" ",$name);

    return ucwords($name);


}

?>