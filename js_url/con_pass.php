<?php
session_start();

if(isset($_POST['idcon'])){
    $confirm = $_POST['idcon'];
}

$password = $_SESSION['pass_confirm'];

if($confirm == $password)
{
    ?>
        <b>User Access  <label class="text-danger">*</label></b>
        <br>
        <select name="access" id="access" onchange="userAccess()" class="form-control form-control-sm" required>
            <option value="" disabled selected>User Access</option>
            <option value="HRD">HRD</option>
            <option value="Supervisor">Store Supervisor</option>
        </select>
        <p></p>
        <div id="useraccess"></div>
            <input type="submit" id="register" class="saves_user btn btn-primary btn-sm" value="Submit" name="submit">
    <?php
}else{
    ?>
        <label class="text-danger">Password and Confirm Password must be the same.</label>
        <br>
        <b>User Access <label class="text-danger">*</label></b>
        <br>
        <select name="access" id="access" onchange="userAccess()" class="form-control form-control-sm" required>
            <option value="" disabled selected>User Access</option>
            <option value="HRD">HRD</option>
            <option value="Supervisor">Store Supervisor</option>
        </select>
        <div id="useraccess"></div>
        <p></p>
        <input type="submit" id="register" value="Submit" name="submit" class="btn btn-primary btn-sm" disabled>
    <?php
}
?>