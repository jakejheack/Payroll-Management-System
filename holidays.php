<?php
session_start();

include_once("connection/cons.php");
$con = conns();

date_default_timezone_set("Asia/Manila");
$y_now = date("Y");
$m_now = date("m");
$d_now = date("j");

if(isset($_POST['refresh']))
{
    $m_now = date('m', strtotime($_POST['months']));
}

// holidays
$sql_hol = "SELECT * FROM holidays";
$dan_hol = $con->query($sql_hol) or die ($con->error);
$row_hol = $dan_hol->fetch_assoc();
$count_hol = mysqli_num_rows($dan_hol);

$cc = 0;

if($count_hol > 0){
    do{
        $hols = $row_hol['datee'];
            if(date('m', strtotime($hols)) == $m_now)
                {
                    $cc++;
                }
    }while($row_hol = $dan_hol->fetch_assoc());
}

// header
include("includes/header.php");
?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h3 class="h2">Holidays</h3>
      </div>

        <div class="container table-responsive">
        <?php
            if(isset($_SESSION['status'])){
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>&#10004; '.$_SESSION['status'].'</strong> 
                    <a href="holidays.php" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></a>
                  </div>';
                unset($_SESSION['status']);
            }
            if(isset($_SESSION['Delete_Holiday'])){
                echo '<div class="alert alert-secondary alert-dismissible fade show" role="alert">
                    <strong>'.$_SESSION['Delete_Holiday'].'</strong>
                    <a href="holidays.php" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></a>
                  </div>';
                unset($_SESSION['Delete_Holiday']);
            }
        ?>
       
        <div class="employee_list">

            <form action="" method="post">
                <div class="row" >
                    <div class="" style="width:30%; padding: 0 10px ">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <label class="input-group-text" for="inputGroupSelect01">Month</label>
                            </div>
                                    <select name="months" id="" class="custom-select">
                                        <option value="" disabled selected>Months</option>
                                        <?php 
                                        $mm = 1;
                                        do{
                                            $current_month = $m_now.'/1'.'/'.$y_now;
                                            $m = $mm.'/1'.'/'.$y_now;
                                            ?>
                                                <option value="<?php echo $m?>" 
                                                    <?php
                                                        if(date('F', strtotime($current_month)) == date('F', strtotime($m)))
                                                        {
                                                            echo 'selected';
                                                        }
                                                    ?>
                                                >
                                                    <?php echo date('F', strtotime($m))?>
                                                </option>
                                            <?php
                                            $mm++;
                                        }while($mm <= 12)
                                    ?>  
                                    </select>
                        </div>
                    </div>
                    <div style="width:70%;">
                        <div class="input-group mb-3">
                            <input type="submit" class="btn btn-sm btn-info" value="Filter" name="refresh" style="margin: 3px 10px 0 0 ">
                            <div class="btnAdd" style="width:90%; text-align: right; box-sizing:border-box">
                                <a href="#!" data-id="" data-bs-toggle="modal" data-bs-target="#addUserModal" class="btn btn-success btn-sm" style="margin: 3px 0 0 0 ">+ New Holiday</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
                        <!-- New Holiday -->
                        <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="exampleModalLabel">Create New Holiday</h4>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="add_holiday.php" name="addData" method="post" autocomplete="off">
                                            <div class="mb-3 row">
                                                <label for="datee" class="col-md-3 form-label">Date:</label>
                                                <div class="col-md-9">
                                                    <input type="date" class="form-control" name="datee" id="datee" required>
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="cut_off" class="col-md-3 form-label">Name of Holiday:</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control"  name="namee" id="" placeholder="Name of Holiday" required>
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="cut_off" class="col-md-3 form-label">Type:</label>
                                                <div class="col-md-9">
                                                    <select name="types" class="form-control" id="" required>
                                                        <option value="" disabled selected>Type of Holiday</option>
                                                        <option value="REGULAR HOLIDAY">REGULAR HOLIDAY</option>
                                                        <option value="SPECIAL NON-WORKING HOLIDAY">SPECIAL NON-WORKING HOLIDAY</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <input type="submit" class="btn btn-primary btn-sm" value="Submit" name="submit">
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

            <table id="holiday_table" class="dataTable table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Day</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        // holidays
                        $sql_hol = "SELECT * FROM holidays order by datee ASC";
                        $dan_hol = $con->query($sql_hol) or die ($con->error);
                        $row_hol = $dan_hol->fetch_assoc();
                        $count_hol = mysqli_num_rows($dan_hol);

                        if($cc < 1)
                        {
                            ?>
                                <tr>
                                </tr>
                            <?php
                        }else{
                            do{
                                $hol = $row_hol['datee'];
                                    if(date('m', strtotime($hol)) == $m_now)
                                        {
                                            $date = date('d M ', strtotime($hol));
                                            $dd = date('d M '.$y_now, strtotime($hol));
                                            $day = date('l', strtotime($dd));

                                            ?>
                                                <tr>
                                                    <td><?php echo $date?></td>
                                                    <td><?php echo $day?></td>
                                                    <td><?php echo $row_hol['names']?></td>
                                                    <td><?php echo $row_hol['types']?></td>
                                                    <td><?= '<a href=javascript:void(); data-id="'.$row_hol['ID'].'" class="btn-info btn-sm editbtn" >Edit</a>'?>
                                                        <button onclick="deleteme(<?php echo $row_hol['ID']; ?>)">Delete</button></td>
                                                </tr>
                                            <?php
                                        }
                            }while($row_hol = $dan_hol->fetch_assoc());
                        }

                        $current_month = $m_now.'/1'.'/'.$y_now;
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" style="text-align: center;">Month of <?php echo date('F Y', strtotime($current_month))?></th>
                    </tr>
                </tfoot>
            </table>
<!--  -->
</div>
    </div>
</main>

<?php
include("includes/footer.php");
?>

<script language="javascript">
function deleteme(delid)
{
    if(confirm("Do you want to Delete this Record?")){
        window.location.href='delete_hol.php?del_id=' +delid+'';
        return true;
    }
}
</script>

<script>

    let table = new DataTable('#holiday_table',{
        fixedColumns: {
        left: 2
        },
        // scrollCollapse: true,
        scrollX: true
        // scrollY: 500
    });


// Display Edit Modal
$('#holiday_table').on('click', '.editbtn', function(event) {
      var table = $('#holiday_table').DataTable();
      var trid = $(this).closest('tr').attr('id');
        //console.log(selectedRow);
      var id = $(this).data('id');
      $('#editModal').modal('show');

      $.ajax({
        url: "get_single_data_holiday.php",
        data: {
          id: id
        },
        type: 'post',
        success: function(data) {
          var json = JSON.parse(data);
          $('#datee_field').val(json.datee);
          $('#namee_field').val(json.names);
          $('#types_field').val(json.types);
          $('#id').val(id);
        //   $('#trid').val(trid);
        }
      });
    });

</script>

<!-- Edit -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title fs-5" id="exampleModalLabel">Edit Holiday</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="edit_holiday.php" method="post">
                <div class="mb-3 row">
                    <label for="datee_field" class="col-md-3 form-label">Date:</label>
                    <div class="col-md-9">
                        <input type="date" class="form-control" name="datee_field" id="datee_field" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="cut_off" class="col-md-3 form-label">Name of Holiday:</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control"  name="namee_field" id="namee_field" placeholder="Name of Holiday" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="cut_off" class="col-md-3 form-label">Type:</label>
                    <div class="col-md-9">
                        <select name="types_field" class="form-control" id="types_field" required>
                            <option value="" disabled selected>Type of Holiday</option>
                            <option value="REGULAR HOLIDAY">REGULAR HOLIDAY</option>
                            <option value="SPECIAL NON-WORKING HOLIDAY">SPECIAL NON-WORKING HOLIDAY</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3" style="text-align: right;">
                    <input type="submit" class="btn btn-primary btn-sm" value="Save Changes" name="changes">
                </div>
                    <input type="hidden" name="holidayID" id="id" value="">
        </form>
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
