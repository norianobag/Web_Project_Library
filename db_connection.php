<?php
function myconn(){
    $servername = "127.0.0.1:3307";
    $username = "root";
    $password = "test123";
    $db = "studentss";

    //create mysql connection
    $GLOBALS["conn"] = new mysqli($servername, $username, $password, $db);

    if ($GLOBALS["conn"]->connect_error){
        //die("Connection failed: " . $conn->connect_error);
        return false;
    }else{
        return true;
    }
}

?>