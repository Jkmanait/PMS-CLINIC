<?php

    session_start();

    if(isset($_SESSION["user"])){
        if(($_SESSION["user"])=="" or $_SESSION['usertype']!='p'){
            header("location: ../login.php");
        }else{
            $useremail=$_SESSION["user"];
        }

    }else{
        header("location: ../login.php");
    }
    

    //import mysqli
    include("../connection.php");
    $userrow = $mysqli->query("select * from patient where pemail='$useremail'");
    $userfetch=$userrow->fetch_assoc();
    $userid= $userfetch["pid"];
    $username=$userfetch["pname"];

    
    if($_GET){
        //import mysqli
        include("../configuration/config.php");
        $id=$_GET["id"];
        $result001= $mysqli->query("select * from patient where pid=$id;");
        $email=($result001->fetch_assoc())["pemail"];
        $sql= $mysqli->query("delete from webuser where email='$email';");
        $sql= $mysqli->query("delete from patient where pemail='$email';");
        //print_r($email);
        header("location: ../logout.php");
    }


?>