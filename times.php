<?php
session_start();

include_once("connection/cons.php");
$con = conns();

// header
include("includes/header.php");

?>
<script>
    $(document).ready(function() {
        setInterval(function() {
            $('#time').load('time.php')
        }, 1000);
    });
</script>

<div id="time">
    00:00:00 AM
</div>

