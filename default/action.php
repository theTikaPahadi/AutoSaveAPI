<?php

session_start();

if(!isset($_SESSION["access"]) && $_SESSION["access"] != true){
    header("Location:../login.php");
    exit();
}

?>
<!DOCTYPE HTML>
<head>
    <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
    <style>

        *{font-family: "Open Sans", "Helvetica Neue", Helvetica, Arial, sans-serif; font-weight: 200 !important;}
        .container{margin: 0; width: 100%}
        .left{float: left}
        .right{float: right}
        #menu {margin: 0;background: #A3BECB;padding:0; min-height: 500px}
        #menu a {display: block; background: #A3BECB; padding: 7px 10px}
        #menu a:hover, #menu a:active{text-decoration: none; background: #7190A0}
        .rows{margin: 0;padding: 0}
        table{width: 100%}
        tr{background: none}
        th{background: #A3BECB; padding: 5px 10px; border: 1px solid #FFF}
        td{background: #E0ECF8; padding: 5px 10px; border: 1px solid #FFF}
        tr:nth-child(2n) td{background: #E0E6F8}
        .btn{margin:5px 5px 5px 0;}
        a:hover{text-decoration: none}
    </style>
</head>
<body>
<div class="container bg-info">
    <div class="left">
        <h2 class="text-success">Baikapik Samuha</h2>
        <h6 class="text-success">Content Management Systems</h6>
    </div>
    <div class="right">
        <a href="../logout.php">Logout</a>
    </div>
</div>
<div class="container rows">
    <div class="col-md-2" id="menu">
        <a href="#">Content Management</a>
        <a href="#">Event Management</a>
        <a href="#">Blogs/News</a>
        <a href="#">Job Management</a>
    </div>
    <div class="col-md-10">

        <?php
        require_once("../assets/prepare.php");
        require_once("../class/Row.php");
        require_once("../class/FetchFields.php");

        $row = new Row(
            $table,$action,$col,$id,$redirect_uri
        );

        if(isset($_SESSION["message"])){
            echo '<div class="alert alert-success" role="alert">'.$_SESSION["message"].'</div>';
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


    </div>
</div>


</body>



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
</html>