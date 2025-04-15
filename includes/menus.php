<?php

if(!isset($_SESSION))
{
    session_start();
}

if(isset($_SESSION['Login']) && isset($_SESSION['Usernames'])){
    $username = $_SESSION['Usernames'];
    $user_access = $_SESSION['Access'];
    $store_supervisor = $_SESSION['Store'];
    $_SESSION['department'] = $_SESSION['Store'];
}else{
    echo header("Location: logout.php");
    // exit();
}

?>

<div class="left_menu">
    <div class="titled">
        <img src="img/COH.png" alt="">
        <!-- <h3>Employee Management & Payroll System</h3> -->
    </div>
    <?php
        if($user_access == 'HRD')
        {
    ?>
        <div class="menus">
            <ul>
                <li>Main Page
                    <ul>
                        <li><a href="dashboard.php"><ion-icon name="grid-sharp"></ion-icon> Dashboard</a></li>
                    </ul>
                </li>
                <li>Employees
                    <ul>
                        <li><a href="employees%copy.php"><ion-icon name="people-sharp"></ion-icon> List of Employees</a></li>
                        <li><a href="add_employee.php"><ion-icon name="person-add-sharp"></ion-icon> New Employee</a></li>
                    </ul>
                </li>
                <li>Reports
                    <ul>
                        <li><a href="payroll_summary.php"><ion-icon name="newspaper-sharp"></ion-icon> Payroll</a></li>
                        <li><a href="employees_dtr.php"><ion-icon name="alarm-sharp"></ion-icon> D.T.R.</a></li>
                    </ul>
                </li>
                <li>Manages
                    <ul>
                        <li><a href="departments.php"><ion-icon name="business-sharp"></ion-icon> Departments</a></li>
                        <li><a href="positions.php"><ion-icon name="briefcase-sharp"></ion-icon> Positions</a></li>
                        <li><a href="holidays.php"><ion-icon name="calendar-number-sharp"></ion-icon> Holidays</a></li>
                    </ul>
                </li>
                <li>Account Settings
                    <ul>
                        <li><a href="change_username_password.php"><ion-icon name="key-sharp"></ion-icon> Change Username / Password</a></li>
                        <li><a href="create_new_account.php"><ion-icon name="person-circle-sharp"></ion-icon> Create New Account</a></li>
                        <li><a href="logout.php"><ion-icon name="power-sharp"></ion-icon> Logout</a></li>
                    </ul>
                </li>
                <li><ion-icon name="person-sharp"></ion-icon> <?php echo $_SESSION['Usernames']?></li>
            </ul>
        </div>
    <?php
        }elseif($user_access == 'Supervisor')
        {
            ?>
        <div class="menus">
            <ul>
                <li>Main Page
                    <ul>
                        <li><a href="dashboard.php"><ion-icon name="grid-sharp"></ion-icon> Dashboard</a></li>
                    </ul>
                </li>
                <li>Employees
                    <ul>
                        <li><a href="employees.php"><ion-icon name="people-sharp"></ion-icon> List of Employees</a></li>
                    </ul>
                </li>
                <li>Account Settings
                    <ul>
                        <li><a href="change_username_password.php"><ion-icon name="key-sharp"></ion-icon> Change Username / Password</a></li>
                        <li><a href="logout.php"><ion-icon name="power-sharp"></ion-icon> Logout</a></li>
                    </ul>
                </li>
                <li><ion-icon name="person-sharp"></ion-icon> <?php echo $_SESSION['Usernames']?></li>
            </ul>
        </div>
            <?php
        }
    ?>
</div>