<?php
session_start();

if(isset($_POST['date_from']) && isset($_POST['date_to'])){
    $_SESSION['employees_dtr_from'] = $_POST['date_from'];
    $_SESSION['employees_dtr_to'] = $_POST['date_to'];
}
?>