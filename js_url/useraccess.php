<?php
session_start();

if(isset($_POST['idacc'])){
    $useraccount = $_POST['idacc'];
}

if($useraccount == 'Supervisor')
{
    ?>
        <b>Store </b>
        <br>
        <select name="store" id="store" class="form-control form-control-sm" required>
            <option value="" disabled selected>Select Store</option>
            <option value="Prime 1">Prime 1</option>
            <option value="Prime 2">Prime 2</option>
            <option value="Prime Tigaon">Prime Tigaon</option>
            <option value="School & Office Supplies">School & Office Supplies</option>
        </select>
        <p></p>
    <?php
}else{
    ?>
        <input type="hidden" name="store" value="All">
    <?php
}