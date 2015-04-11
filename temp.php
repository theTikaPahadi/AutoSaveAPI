<?php


require_once("class/Connection.php");

$c = new Connection();
$link = $c->connect();

$query = $link->query("SELECT * FROM `test` LIMIT 1");


$row = mysqli_fetch_fields($query);
echo "<table>";
echo "<tr><td>name</td><td>orgname</td><td>table</td><td>orgtable</td><td>def</td><td>db</td><td>catalog</td>
<td>max_length</td><td>length</td><td>charsetnr</td><td>flags</td><td>type</td><td>decimals</td></tr>";
foreach($row as $field){
    echo "<tr>";
    foreach($field as $v=>$f){
        echo "<td>".$f."</td>";
    }
    echo "</tr>";


}
echo "<table>";

?>
<style>
    td{
        padding: .1em .5em;
        background: #e4e4e4;
    }
</style>
<?php
$data_type = array(
"1" => "tinyint",
"2" => "smallint",
"9" => "mediumint",
"3" => "int",
"8" => "bigint",
"246" => "decimal",
"4" => "float",
"5" => "double",
"5" => "real",
"16" => "bit",
"1" => "boolean",
"8" => "serial",
"10" => "date",
"12" => "datetime",
"7" => "timestamp",
"11" => "time",
"13" => "year",
"254" => "char",
"253" => "varchar",
"252" => "tinytext",
"252" => "text",
"252" => "mediumtext",
"252" => "longtext",
"254" => "binary",
"253" => "varbinary",
"252" => "tinyblob",
"252" => "mediumblob",
"252" => "blob",
"252" => "longblob"
);

?>