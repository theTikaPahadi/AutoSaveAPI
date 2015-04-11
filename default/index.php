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
        #detail{overflow: scroll; height: 500px}
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
        <a href="index.php?table=content">Content Management</a>
        <a href="index.php?table=events">Event Management</a>
        <a href="index.php?table=blog">Blogs/News</a>
        <a href="index.php?table=jobs">Job Management</a>
        <a href="index.php?table=jobs_pending">Pending Job Request</a>
        <a href="index.php?table=apply">Job Applied/CV</a>
    </div>
    <div class="col-md-10" id="detail">
       <?php
            if(isset($_SESSION["message"])){
                echo '<div class="alert alert-success" role="alert">'.$_SESSION["message"].'</div>';
                unset($_SESSION["message"]);
            }

            if(isset($_GET["table"])){
                require_once("../class/Connection.php");
                $db_con = new Connection();
                $link = $db_con->connect();
                $table = mysqli_real_escape_string($link, $_GET["table"]);
                $link->close();
            } else {
                $table = "content";
            }

            table($table);


            function table($table)
            {

                require_once("../class/FetchEditTable.php");
                $t = new FetchEditTable($table,10);
                $rows = $t->getResults();

                $skip = array("id","timestamp");

                echo "<a href='action.php?table=$table' class='btn btn-primary'>+Add new</a>";
                echo "<table>";

                //Title
                echo "<tr>";
                echo "<th>Action</th>";
                foreach ($rows[0] as $cell => $val) {
                    if (in_array($cell,$skip)) {
                        continue;
                    }
                    echo "<th>" . prepare_name($cell) . "</th>";
                }
                echo "</tr>";


                //Content
                foreach ($rows as $row) {
                    echo "<tr>";
                    //INDEXING FOR EDIT
                    echo "<td><a href='' class='btn btn-primary glyphicon glyphicon-pencil'></a><a href='' class='btn btn-danger glyphicon glyphicon-ban-circle'></td>";

                    foreach ($row as $cell => $val) {
                        if (in_array($cell,$skip)) {
                            continue;
                        } else if($cell == "img" || $cell == "image"){
                            echo "<td><img src='" . $val . "'</td>";
                        } else{
                            $val = (strlen($val) <= 200) ? $val : substr($val, 0, 200) . "...";
                            echo "<td>" . $val . "</td>";
                        }



                    }
                    echo "</tr>";

                }
                echo "</table>";
            }
        ?>

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