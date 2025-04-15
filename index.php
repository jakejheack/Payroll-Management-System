<?php
session_start();

include_once("connection/cons.php");
include('includes/UserInfo.php');
$con = conns();
    

if(isset($_SESSION['Login'])){
    $id_user_login_active_time = $_SESSION['Login'];
    // user
    $sql_user_login_active_time = "UPDATE `user` SET `active_status` = 'OFFLINE' WHERE `ID` = '$id_user_login_active_time'";
    $dan_user_login_active_time = $con->query($sql_user_login_active_time) or die ($con->error);

    unset($_SESSION['Login']);
    session_unset();
    session_destroy();
}
    
date_default_timezone_set("Asia/Manila");

if (isset($_POST["submit"])) {
    $ips = UserInfo::get_ip();
    $os = UserInfo::get_os();
    $browser = UserInfo::get_browser();
    $device = UserInfo::get_device();
    
    $user_info = 'IP:'.$ips.'_OS:'.$os.'_Browser:'.$browser.'_Device:'.$device;
    $ip = $_SERVER['REMOTE_ADDR'];

           $username = $_POST["username"];
           $password = $_POST["password"];

            $username == 'Dan' ? $username = 'admin1' : '';

            $sql = "SELECT * FROM user WHERE BINARY users = BINARY '$username'";
            $result = mysqli_query($con, $sql);
            $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $row = mysqli_fetch_assoc($result);

            if ($user) {
                if ($password == $user["pw"]) {
                    if($user['active_status'] == 'ONLINE' || $_POST['username'] == 'admin1'){

                        $username = $username.' --ONLINE';
    
                        $sql_in = "INSERT INTO `user_logs`(`ID`, `username`, `user_info`, `user_ip`) 
                                    VALUES (NULL,'$username','$user_info','$ip')";
                        $query = $con->query($sql_in) or die ($con->error);
    
                        $_SESSION['Login_Err'] = "".$user['names']." is already Online!";

                    }else{
                        $_SESSION['Login'] = $user['ID'];
                        $_SESSION['Usernames'] = $user['names'];
                        $_SESSION['Access'] = $user['access'];
                        $_SESSION['Store'] = $user['store'];
                        $_SESSION['last_timestamp'] = time();

                        $sql_in = "INSERT INTO `user_logs`(`ID`, `username`, `user_info`, `user_ip`) 
                                    VALUES (NULL,'$username','$user_info','$ip')";
                        $query = $con->query($sql_in) or die ($con->error);

                        header("Location: dashboard.php");
                        die();
                    }
                }else{
                    $username = $username.' --wrong password';

                    $sql_in = "INSERT INTO `user_logs`(`ID`, `username`, `user_info`, `user_ip`) 
                                VALUES (NULL,'$username','$user_info','$ip')";
                    $query = $con->query($sql_in) or die ($con->error);
        
                    $_SESSION['Login_Err'] = "Password does not match!";
                }
            }else{
                $username = $username.' --username & password error';

                $sql_in = "INSERT INTO `user_logs`(`ID`, `username`, `user_info`, `user_ip`) 
                            VALUES (NULL,'$username','$user_info','$ip')";
                $query = $con->query($sql_in) or die ($con->error);
    
                $_SESSION['Login_Err'] = "Username and Password does not match!";
            }
        
}

$ips = UserInfo::get_ip();
$os = UserInfo::get_os();
$browser = UserInfo::get_browser();
$device = UserInfo::get_device();

$user_info = 'IP:'.$ips.'_OS:'.$os.'_Browser:'.$browser.'_Device:'.$device;
$ip = $_SERVER['REMOTE_ADDR'];

$sql_in = "INSERT INTO `user_logs`(`ID`, `username`, `user_info`, `user_ip`) 
            VALUES (NULL,'','$user_info','$ip')";
$query = $con->query($sql_in) or die ($con->error);


$sql_online = "SELECT * FROM user WHERE active_status = 'ONLINE'";
$dan_online = $con->query($sql_online) or die ($con->error);
$row_online = $dan_online->fetch_assoc();
$count_online = mysqli_num_rows($dan_online);

if($count_online > 0){
    do{
        $id_online = $row_online['ID'];
        $last_time = floatval($row_online['last_time']);

        if((time() - $last_time) > 1800){
            $sql_user_login_active_time = "UPDATE `user` SET `active_status` = 'OFFLINE' WHERE `ID` = '$id_online'";
            $dan_user_login_active_time = $con->query($sql_user_login_active_time) or die ($con->error);
        }
    }while($row_online = $dan_online->fetch_assoc());
}
?>
<!DOCTYPE html>
<html lang="eng">

<head>
    <title>Login Form</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8" />
    <meta name="keywords" content="Login Form" />
        <link rel="icon" type="image/x-icon" href="img/logo.png">

    <link rel="stylesheet" href="css/all.css" type="text/css" media="all" />
    <link rel="stylesheet" href="css/styling.css" type="text/css" media="all" />

</head>

<body>
<?php
    if(isset($_SESSION['Login_Err'])){
        echo "<div class='alert alert-danger'>".$_SESSION['Login_Err']."</div>";
        unset($_SESSION['Login_Err']);
    }
?>
    <section class="w3l-mockup-form">
        <div class="container">
            <!-- /form -->
            <div class="workinghny-form-grid">
                <div class="main-mockup">
                    
                    <div class="w3l_form align-self">
                        <div class="left_grid_info">
                            <img src="img/COH.png" alt="">
                        </div>
                    </div>
                    <div class="content-wthree">
                        <center><h2>FAM TECHNOLOGIES SYSTEM</h2></center>
                        <div><br>
                            <br>
                        <center>LOGIN FORM</center>
                        </div>
                        <form action="" method="post">
                            <input type="text" class="username" name="username" placeholder="Username" required>
                            <input type="password" class="password" name="password" placeholder="Password" style="margin-bottom: 2px;" required>
                           <br>
                           <br>
                           <br>
                           

                            <button name="submit" name="submit" class="btn" type="submit">Login</button>
                        </form>
                        <div class="social-icons">
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <script src="js/jquery.min.js"></script>
    <script src="js/all.js"></script>

</body>

</html>