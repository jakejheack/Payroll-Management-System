<?php
session_start();

if(isset($_POST['idpass'])){
    $password = $_POST['idpass'];
    $password = str_replace("'", "\'", $password);
    $_SESSION['pass_confirm'] = $password;
}

if(strlen($password) < 6 || strlen($password) > 20)
{
    ?>
        <span class="pass_con text-danger">Password is Minimum of 6 characters and Maximum of 20 characters</span>
        <br>
        <b>Confirm Password <label class="text-danger">*</label></b>
        <br>
        <input type="password" name="confirm" placeholder="Confirm Password" id="confirm" onkeyup="confirm_password()" class="form-control form-control-sm" required>
        <div id="con_pass">
        <b>User Access  <label class="text-danger">*</label></b>
        <br>
        <select name="access" id="access" class="form-control form-control-sm" required>
            <option value="" disabled selected>User Access</option>
            <option value="HRD">HRD</option>
            <option value="Supervisor">Store Supervisor</option>
        </select>
        <p></p>
        <div id="useraccess"></div>
            <input type="submit" id="register" value="Submit" name="submit" class="btn btn-primary btn-sm" disabled>
        </div>
    <?php
}else{
    ?>
        <b>Confirm Password <label class="text-danger">*</label></b>
        <br>
        <input type="password" name="confirm" placeholder="Confirm Password" id="confirm" class="form-control form-control-sm" onkeyup="confirm_password()" required>
        <div id="con_pass">
        <b>User Access <label class="text-danger">*</label></b>
        <br>
        <select name="access" id="access" class="form-control form-control-sm" required>
            <option value="" disabled selected>User Access</option>
            <option value="HRD">HRD</option>
            <option value="Supervisor">Store Supervisor</option>
        </select>
        <p></p>
        <div id="useraccess"></div>
            <input type="submit" id="register" value="Submit" name="submit" class="btn btn-primary btn-sm" disabled>
        </div>
    <?php
}
?>
