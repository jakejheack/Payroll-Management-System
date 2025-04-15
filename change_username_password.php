<?php
session_start();

include_once("connection/cons.php");
$con = conns();

date_default_timezone_set("Asia/Manila");

$id = $_SESSION['Login'];

if(isset($_POST['change']))
{
    $cur_user = $_POST['user'];
    $current = $_POST['cur'];
    $new_user = $_POST['new_user'];
    $new = $_POST['new'];
    $verify = $_POST['ver'];

        // check if correct
        $check_id = "SELECT * FROM user WHERE users = '$cur_user' && pw = '$current' && ID = '$id'";
        $result = $con->query($check_id) or die ($con->error);
        $count = mysqli_num_rows($result);

        // check if existed
        $check_ids = "SELECT * FROM user WHERE users = '$new_user'";
        $results = $con->query($check_ids) or die ($con->error);
        $row = $results->fetch_assoc();
        $counts = mysqli_num_rows($results);

        $ids = $row['ID'];

        if($count > 0){
            if($new == $verify){
                if($counts > 0 && $id != $ids){
                    $_SESSION['check'] = "Username is Unavailable! Please try another Username.";
                }else{
                        $sql = "UPDATE user SET users = '$new_user', pw = '$new' WHERE ID ='$id'";
                
                            if($con->query($sql) or die ($con->error))
                                {
                                    $_SESSION['status'] = "New Username & Password Saved!";
                                }else{
                                    echo "Something went wrong";
                            }
                }
            }else{
                $_SESSION['check'] = "New Password & Verified New Password Doesn't Match.. Please try again.";
            }
        }else{
            $_SESSION['check'] = "Current Username or Password Doesn't match to your account! Please try again..";
        }
}

// user
$sql = "SELECT * FROM user WHERE ID = '$id'";
$dan = $con->query($sql) or die ($con->error);
$row = $dan->fetch_assoc();
$count = mysqli_num_rows($dan);

if($count < 1){
    header("Location: logout.php");
}

// header
include("includes/header.php");
?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
          <div class="form-group col-md">
                <a href="#!" data-id="" data-bs-toggle="modal" data-bs-target="#exampleModal" class="button btn btn-link rounded-circle position-relative">
                            <?php
                            $pictures = $row['pictures'];
                                if(empty($pictures))
                                {
                                    ?>
                                        <img id="" src="img/blank.jpg" alt="Employees Picture"  width="130px" height="130px" class="rounded-circle position-relative"/>
                                    <?php
                                }elseif(!empty($pictures))
                                {
                                    ?>
                                        <img id="" src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($pictures); ?>" alt="Employees Picture"  width="130px" height="130px" style="margin:0; padding:0" class="rounded-circle position-relative"/>
                                    <?php
                                }
                            ?>
                        <h2>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg bg-light">
                            <span class="glyphicon badge badge-light">&#x270f;</span>
                            <!-- <span class="visually-hidden">unread messages</span> -->
                        </span>
                        </h2>
                </a>
            </div>
            <div class="form-group col-md-11">
                <h4><?= $row['names']?></h4>
                <span class="text-secondary">Access: <?= $row['access']?></span>
            </div>
      </div>

        <div class="container table-responsive">

            <?php
                if(isset($_SESSION['status'])){
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>&#10004; '.$_SESSION['status'].'</strong> 
                        <a href="change_username_password.php" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></a>
                    </div>';
                    unset($_SESSION['status']);
                }
                if(isset($_SESSION['check'])){
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>&#10008 '.$_SESSION['check'].'</strong>
                        <a href="change_username_password.php" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></a>
                    </div>';
                    unset($_SESSION['check']);
                }
            ?>

            <div class="account_settings">
            <form action="" method="post" style="width: 25%;">
                <b>Current Username & Password <label>*</label></b>
                <br>
                <input type="text" name="user" id="" class="form-control form-control-sm" placeholder="Username" required>
                <br>
                <input type="password" name="cur" id="" class="form-control form-control-sm" placeholder="Password" required>
                <br>
                <b>New Username & Password <label>*</label></b>
                <br>
                <input type="text" name="new_user" id="" class="form-control form-control-sm" placeholder="New Username" required>
                <br>
                <input type="password" name="new" id="" class="form-control form-control-sm" placeholder="New Password" required>
                <br>
                <input type="password" name="ver" id="" class="form-control form-control-sm" placeholder="Verify New Password" required>
                <br>
                <input type="submit" name="change" class="btn btn-primary btn-sm"  value="Save Changes">
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
      $('#blah').attr('src', e.target.result).width(230).height(230);
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


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Change Profile Picture</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form action="update_pic_user.php" method="post" enctype='multipart/form-data' style="text-align: center;">
        <div class="modal-body">
                <input id="file" type="file" name="image" accept="image/*" capture="user" onchange="readURL(this);" style="display: none;" accept=".jpg, .jpeg, .png" required>
                <label for="file" style="cursor: pointer;">
                <?php
                $pictures = $row['pictures'];
                    if(empty($pictures))
                    {
                        ?>
                            <img id="blah" src="img/blank.jpg" alt="Employees Picture" width="250px"/>
                        <?php
                    }elseif(!empty($pictures))
                    {
                        ?>
                            <img id="blah" src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($pictures); ?>" alt="Employees Picture" width="250px"/>
                        <?php
                    }
                ?>
                </label>
                <p></p>
        </div>
        <div class="modal-footer">
            <input type="hidden" name="ID" value="<?php echo $row['ID'];?>">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <input type="submit" name="change_pic" class="btn btn-primary" value="Save Changes">
        </div>
        </form>
        </div>
    </div>
    </div>
