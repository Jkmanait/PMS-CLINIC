<?php

    $database= new mysqli("localhost","root","","uhc");
    if ($database->connect_error){
        die("Connection failed:  ".$database->connect_error);
    }

?>

<?php
$host="localhost";
$dbuser="root";
$dbpass="";
$db="uhc";
$mysqli=new mysqli($host,$dbuser, $dbpass, $db);
?>
