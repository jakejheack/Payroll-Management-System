<?php
session_start();

include_once("connection/cons.php");
$con = conns();

date_default_timezone_set("Asia/Manila");

// new account
if(isset($_POST['submit']))
{
    $names = trim($_POST['names']);
    $user = trim($_POST['user']);
    // $pw = md5($_POST['pw']);
    // $n_pw = md5($_POST['confirm']);
    $pw = $_POST['pw'];
    $n_pw = $_POST['confirm'];
    $access = $_POST['access'];
    $store = $_POST['store'];
    $created_by = $_SESSION['Usernames'];
    $datetime = date("m/d/y-h:i a");

        // check if existed
        $check_ids = "SELECT * FROM user WHERE users = '$user'";
        $results = $con->query($check_ids) or die ($con->error);
        $row = $results->fetch_assoc();
        $counts = mysqli_num_rows($results);    

    if($pw == $n_pw){
        if($counts > 0){
            $_SESSION['pw'] = "Username is Unavailable! Please try another Username.";
        }else{
            // picture
            if(!empty($_FILES["image"]["name"])) { 
                // Get file info 
                $fileName = basename($_FILES["image"]["name"]); 
                $fileType = pathinfo($fileName, PATHINFO_EXTENSION); 
                    
                // Allow certain file formats 
                $allowTypes = array('jpg','png','jpeg','gif'); 
                if(in_array($fileType, $allowTypes)){ 
                    $image = $_FILES['image']['tmp_name']; 
                    $imgContent = addslashes(file_get_contents($image));                    
                }
            }else{
                $imgContent = "";
            }

        $sql = "INSERT INTO `user` (`ID`, `users`, `pw`, `names`, `access`, `store`, `created_by`, `date_created`, `pictures`)
        VALUES (NULL, '$user', '$pw', '$names', '$access', '$store', '$created_by', '$datetime', '$imgContent')";

       if($con->query($sql) or die ($con->error))
       {
            $_SESSION['status'] = "New Account has been Saved!";
       }else{
           echo "Something went wrong";
       }
        }
   
    }else{
        $_SESSION['pw'] = "Password doesn't Match with Verified Password";
    }
}

$_SESSION['pass_confirm'] = "";

// header
include("includes/header.php");
?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h3 class="h4">Create New User Account</h3>
      </div>

        <div class="container table-responsive">
            <?php
                if(isset($_SESSION['status'])){
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>&#10004; '.$_SESSION['status'].'</strong> 
                        <a href="create_new_account.php" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></a>
                    </div>';
                    unset($_SESSION['status']);
                }
                if(isset($_SESSION['pw'])){
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>&#10008 '.$_SESSION['pw'].'</strong>
                        <a href="create_new_account.php" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></a>
                    </div>';
                    unset($_SESSION['pw']);
                }
            ?>

            <div class="account_settings">
                <form action="" method="post" class="register_form" style="width: 25%;" enctype='multipart/form-data'>
                            <div class="form-group col-md">
                                <input id="file" type="file" name="image" accept="image/*" capture="user" onchange="readURL(this);" accept=".jpg, .jpeg, .png" style="display: none;">
                                    <label for="file" style="cursor: pointer;">
                                        <img id="blah" src="img/blank.jpg" alt="Employees Picture" width="130px" height="130px" class="rounded"/>
                                    </label>
                            </div>
                            <b>Fullname <label class="text-danger">*</label></b>
                            <br>
                            <input type="text" name="names" id="names" class="form-control form-control-sm" placeholder="Fullname" required>
                            <b>Username <label class="text-danger">*</label></b>
                            <br>
                            <input type="text" name="user" id="user" class="form-control form-control-sm" placeholder="Username" onkeyup="Username()" required>
                            <div id="uname"></div>
                            <b>Password <label class="text-danger">*</label></b>
                            <br>
                            <input type="password" name="pw" id="pw" class="form-control form-control-sm" placeholder="Account Password" onkeyup="password_limit()" required>
                            <div id="pass_limit">
                                <b>Confirm Password <label class="text-danger">*</label></b>
                                <br>
                                <input type="password" name="confirm" class="form-control form-control-sm" placeholder="Confirm Password" id="confirm" onkeyup="confirm_password()" required>
                                <div id="con_pass">
                                <b>User Access <label class="text-danger">*</label></b>
                                <br>
                                <select name="access" id="access" onchange="userAccess()" class="form-control form-control-sm" required>
                                    <option value="" disabled selected>User Access</option>
                                    <option value="HRD">HRD</option>
                                    <option value="Supervisor">Store Supervisor</option>
                                </select>
                                <p></p>
                                <div id="useraccess"></div>
                                    <input type="submit" id="register" value="Submit" name="submit" class="btn btn-primary btn-sm" disabled>
                                </div>
                            </div>
                </form>
            </div>
            </div>
</div>

<?php
include("includes/footer.php");
?>


<script>
    function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function (e) {
      $('#blah').attr('src', e.target.result).width(120).height(100);
    };
    reader.readAsDataURL(input.files[0]);
  }
}

if (window.File && window.FileReader && window.FormData) {
	var $inputField = $('#file');

	$inputField.on('change', function (e) {
		var file = e.target.files[0];

		if (file) {
			if (/^image\//i.test(file.type)) {
				readFile(file);
			} else {
				alert('Not a valid image!');
			}
		}
	});
} else {
	alert("File upload is not supported!");
}

</script>