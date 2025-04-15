<?php
session_start();

include_once("connection/cons.php");
$con = conns();

    //read the json file contents
    $jsondata = file_get_contents('empdetails.json');
    
    //convert json object to php associative array
    $data = json_decode($jsondata, true);
    
    //get the employee details
    $id = $data['empid'];
    $name = $data['personal']['name'];
    $gender = $data['personal']['gender'];
    $age = $data['personal']['age'];
    $streetaddress = $data['personal']['address']['streetaddress'];
    $city = $data['personal']['address']['city'];
    $state = $data['personal']['address']['state'];
    $postalcode = $data['personal']['address']['postalcode'];
    $designation = $data['profile']['designation'];
    $department = $data['profile']['department'];
    
    //insert into mysql table
    $sql = "INSERT INTO tbl_emp(empid, empname, gender, age, streetaddress, city, state, postalcode, designation, department)
    VALUES('$id', '$name', '$gender', '$age', '$streetaddress', '$city', '$state', '$postalcode', '$designation', '$department')";
    $dan = $con->query($sql) or die ($con->error);
?>