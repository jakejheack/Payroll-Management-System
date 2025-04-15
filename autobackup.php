<?php
    $databases = ['dtrdbs'];
    $user = "root";
    $pass = "";
    $host = "localhost";

    date_default_timezone_set("Asia/Manila");

    if(!file_exists("D:/Backup/Databases")){
        mkdir("D:/Backup/Databases");
    }

    foreach($databases as $database) {
        if(!file_exists(("D:/Backup/Databases/$database"))) {
            mkdir("D:/Backup/Databases/$database");
        }

        $filename = $database."_".date("F_d_Y")."@".date("g_ia").uniqid("_", false);
        $folder = "D:/Backup/Databases/$database/".$filename.".sql";

        exec("C:/xampp/mysql/bin/mysqldump --user={$user} --password={$pass} --host={$host} {$database} --result-file={$folder}", $output);
    }

    print_r($output);
?>