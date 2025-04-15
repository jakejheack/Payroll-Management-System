<?php

    function conns(){
        
$host = "localhost";
$username = "u614352398_payrolluser";
$password = "Jardinel@2015";
$database = "u614352398_payrolldb";

$con = new mysqli($host, $username, $password, $database);

if($con->connect_error){
    echo $con->connect_error;
}else{

    return $con;
}

    }


    // SET PASSWORD FOR 'root'@'localhost' = PASSWORD('D@nieL2023');