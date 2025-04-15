<?php
session_start();

include_once("connection/cons.php");
$con = conns();

date_default_timezone_set("Asia/Manila");
$y_now = date("Y");
$m_now = date("m");
$d_now = date("j");

if(isset($_POST['ID']) && isset($_POST['froms']) && isset($_POST['tos']))
{
    $emp_ids = $_POST['ID'];
    // $department = $_GET['Department'];
    $_SESSION['emp_id'] = $emp_ids;

    $from = date("m/d/Y", strtotime($_POST['froms']));
    $to = date("m/d/Y", strtotime($_POST['tos']));
}elseif(isset($_GET['ID']) && isset($_GET['froms']) && isset($_GET['tos'])) {
    $emp_ids = $_GET['ID'];
    // $department = $_GET['Department'];
    $_SESSION['emp_id'] = $emp_ids;

    $from = date("m/d/Y", strtotime($_GET['froms']));
    $to = date("m/d/Y", strtotime($_GET['tos']));

}else{
    $emp_ids= $_SESSION['emp_id'];

    $to = date("m/d/Y");
    $from = date("m/d/Y", strtotime($to .' - 1 month'));
}

$_SESSION['dtr_From2'] = $from;
$_SESSION['dtr_To2'] = $to;

// employees info
$sql_emp = "SELECT * FROM employees WHERE ID = '$emp_ids'";
$dan_emp = $con->query($sql_emp) or die ($con->error);
$row_emp = $dan_emp->fetch_assoc();
$count_emp = mysqli_num_rows($dan_emp);

if($count_emp > 0){
    $emp_name = $row_emp['lname'].', '.$row_emp['fname'].' '.$row_emp['mname'].' '.$row_emp['extname'];
    $position = $row_emp['position'];
}else{
    $emp_name = '-';
    $position = '-';    
}

// header
include("includes/header.php");
include("includes/menus.php");
?>

<div class="containers">
    <h3>Employees DTR</h3>
        <div class="contents">
            <div class="pay_dtr">
                <a href="employees.php" class="btn btn-secondary btn-sm">Back</a>
            </div>
        
            <div class="date_covered">
                    <h4>Employee's Information</h4>
                    <span>Name: <b><?php echo $emp_name ?></b></span>
                    <br>
                    <span>Position: <b><?php echo $position ?></b></span>
                    <br>

                <?php 
                if(isset($_SESSION['dtr_From2']) && isset($_SESSION['dtr_To2']) && isset($_SESSION['emp_id']) && $count_emp > 0){
                    $emp_id = $_SESSION['emp_id'];
                    $from = $_SESSION['dtr_From2'];
                    $to = $_SESSION['dtr_To2'];
                    ?>
                    <table id="dtr_table" class="dataTable table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th rowspan="2">Date</th>
                                <th rowspan="2">Day</th>
                                <th colspan="2" style="text-align: center;">AM</th>
                                <th colspan="2" style="text-align: center;">PM</th>
                                <th colspan="2" style="text-align: center;">O.T.</th>
                                <th rowspan="2">Work Hrs.</th>
                                <th colspan="2" style="text-align: center;">Overtime</th>
                                <th rowspan="2">Remarks</th>
                                <th rowspan="2">Action</th>
                            </tr>
                            <tr>
                                <th>IN</th>
                                <th>OUT</th>
                                <th>IN</th>
                                <th>OUT</th>
                                <th>IN</th>
                                <th>OUT</th>
                                <th>Hrs</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                                <?php
                                    $total_hrs = 0;
                                    $total_ot = 0;
                                    do{

                                        ?>
                                        <tr>
                                            <td><?php echo date('m/d', strtotime($to)) ?></td>
                                            <td><?php echo date('D', strtotime($to)) ?></td>
                                            <?php
                                                $ddd = date('l, F d, Y', strtotime($to));
                                                // dtr
                                                $sql_dtr = "SELECT * FROM dtrs WHERE emp_id = '$emp_id' && log_date = '$ddd'";
                                                $dan_dtr = $con->query($sql_dtr) or die ($con->error);
                                                $row_dtr = $dan_dtr->fetch_assoc();
                                                $count = mysqli_num_rows($dan_dtr);

                                                $dayy = date('l', strtotime($to));

                                                $dayys = date('D', strtotime($to));
                                                $dayys = strtolower($dayys);
    
                                                // schedules    
                                                $sql_sched = "SELECT * FROM schedules WHERE emp_id = '$emp_id'";
                                                $dan_sched = $con->query($sql_sched) or die ($con->error);
                                                $row_sched = $dan_sched->fetch_assoc();
                                                $count_sched = mysqli_num_rows($dan_sched);

                                                if($count_sched > 0)
                                                {
                                                    $mon = $row_sched['mon'];
                                                    $tue = $row_sched['tue'];
                                                    $wed = $row_sched['wed'];
                                                    $thu = $row_sched['thu'];
                                                    $fri = $row_sched['fri'];
                                                    $sat = $row_sched['sat'];
                                                    $sun = $row_sched['sun'];
                                                }else{
                                                    $mon = 'ON';
                                                    $tue = 'ON';
                                                    $wed = 'ON';
                                                    $thu = 'ON';
                                                    $fri = 'ON';
                                                    $sat = 'ON';
                                                    $sun = 'ON';
                                                }

                                                // holidays
                                                $hol_date = date('2023-m-d', strtotime($to));

                                                $sql_hol = "SELECT * FROM holidays WHERE datee = '$hol_date'";
                                                $dan_hol = $con->query($sql_hol) or die ($con->error);
                                                $row_hol = $dan_hol->fetch_assoc();
                                                $count_hol = mysqli_num_rows($dan_hol);

                                                // holiday with work
                                                if($count_hol > 0){
                                                $hol_types = $row_hol['types'];
                                                }else{
                                                $hol_types = "";
                                                }

                                                $remarks = "";

                                                    // day-off w/ Work
                                                    if($dayys == 'mon' && $mon == 'OFF' && empty($hol_types))
                                                    {
                                                        $remarks = "Day-Off";
                                                    }
                                                    elseif($dayys == 'tue' && $tue == 'OFF' && empty($hol_types))
                                                    {
                                                        $remarks = "Day-Off";
                                                    }
                                                    elseif($dayys == 'wed' && $wed == 'OFF' && empty($hol_types))
                                                    {
                                                        $remarks = "Day-Off";
                                                    }
                                                    elseif($dayys == 'thu' && $thu == 'OFF' && empty($hol_types))
                                                    {
                                                        $remarks = "Day-Off";
                                                    }
                                                    elseif($dayys == 'fri' && $fri == 'OFF' && empty($hol_types))
                                                    {
                                                        $remarks = "Day-Off";
                                                    }
                                                    elseif($dayys == 'sat' && $sat == 'OFF' && empty($hol_types))
                                                    {
                                                        $remarks = "Day-Off";
                                                    }
                                                    elseif($dayys == 'sun' && $sun == 'OFF' && empty($hol_types))
                                                    {
                                                        $remarks = "Day-Off";
                                                    }


                                            if($count < 1)
                                            {
                                                ?>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td><?php echo $remarks." ".$hol_types?></td>
                                                    <td><button class="btn btn-info dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            Actions
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <?php
                                                                    if($to >= date("m/d/Y")){
                                                                        echo '<a href="#" class="dropdown-item btn-sm disabled" role="button" aria-disabled="true">Edit Work Hrs</a>';
                                                                    }else{
                                                                        echo '<a href=javascript:void();" data-id="'.$to.'" class="dropdown-item btn-sm editbtn" >Edit Work Hrs</a>';
                                                                    }
                                                                ?>
                                                            </li>
                                                            <li><?= '<a href="#" class="dropdown-item btn-sm disabled" role="button" aria-disabled="true">O.T. Approval</a>' ?></li>
                                                            <li><?= '<a href=javascript:void();" data-id="'.$to.'" class="dropdown-item btn-sm schedbtn" >Custom Schedule</a>'?></li>
                                                        </ul>

                                                    </td>

                                                <?php                                                                                                        
                                            }else{
                                                $hrs1 = (double)$row_dtr['total_hrs'];
                                                $ot = (double)$row_dtr['total_ot'];

                                                $total_hrs = $total_hrs + $hrs1;
                                                $total_ot = $total_ot + $ot;    

                                                if($row_dtr['ot_approved'] == 'Approved'){
                                                    $total_hrs = $total_hrs + $ot;
                                                    $hrs1 = $hrs1 + $ot;
                                                }

                                            ?>
                                                <td><?php echo $row_dtr['in1']?></td>
                                                <td><?php echo $row_dtr['out1']?></td>
                                                <td><?php echo $row_dtr['in2']?></td>
                                                <td><?php echo $row_dtr['out2']?></td>
                                                <td><?php echo $row_dtr['in4']?></td>
                                                <td><?php echo $row_dtr['out4']?></td>
                                                <td><?php echo $hrs1?></td>
                                                <td><?php echo $ot?></td>
                                                <td><?php
                                                        if(empty($row_dtr['ot_approved']) && $ot <= 0 || $row_dtr['ot_approved'] == 'Cancelled' && $ot <= 0){
                                                           echo "";
                                                        }elseif($row_dtr['ot_approved'] == 'Approved'){
                                                            ?>
                                                            <span class="badge badge-success">&check; Approved</span>
                                                            <?php
                                                        }elseif(empty($row_dtr['ot_approved']) && $ot > 0 || $row_dtr['ot_approved'] == 'Pending'){
                                                            ?>
                                                            <span class="badge badge-warning">Pending...</span>
                                                            <?php
                                                        }elseif($row_dtr['ot_approved'] == 'Cancelled' && $ot > 0){
                                                            ?>
                                                            <span class="badge badge-danger">&times; Cancelled</span>
                                                            <?php
                                                        }
                                                    ?></td>
                                                <td><?php echo $remarks." ".$hol_types?></td>
                                                <?php
                                                        if($to >= date("m/d/Y")){
                                                            ?>
                                                                <td><a href="#" class="btn btn-secondary btn-sm disabled" role="button" aria-disabled="true">Edit</a></td>
                                                            <?php
                                                        }else{
                                                    ?>
                                                    <td><button class="btn btn-info dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            Actions
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><?= '<a href=javascript:void();" data-id="'.$row_dtr['ID'].'" class="dropdown-item btn-sm editbtn" >Edit Work Hrs</a>'?></li>
                                                            <li><?php
                                                                if($ot > 0){
                                                                    echo '<a href=javascript:void();" data-id="'.$row_dtr['ID'].'" class="dropdown-item btn-sm overtimebtn" >O.T. Approval</a>';
                                                                }else{
                                                                    echo '<a href="" class="dropdown-item btn-sm disabled" role="button" aria-disabled="true">O.T. Approval</a>';
                                                                }
                                                                ?></li>
                                                            <li><?= '<a href=javascript:void();" data-id="'.$to.'" class="dropdown-item btn-sm schedbtn" >Custom Schedule</a>'?></li>
                                                        </ul>
                                                    </td>
                                            <?php
                                                        }
                                            }
                                        ?>
                                        </tr>
                                        <?php
                                        $to = date('m/d/Y', strtotime($to . " -1 day"));
                                    }while(strtotime($to) >= strtotime($from));
                                ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="8" style="text-align: right;">Total Working Hours</th>
                                <th style="text-align: center;"><?php echo round($total_hrs,2)?></th>
                                <th style="text-align: center;"></th>
                                <th colspan=""></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                <?php
                // unset($_SESSION['dtr_date']);
                } 
                ?>
            </div>
        </div>
</div>

<script src="js/datatables/jquery.dataTables.min.js"></script>
<script src="js/datatables/dataTables.bootstrap4.min.js"></script>

<!-- modal -->
<script src="js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.20/r-2.2.3/datatables.min.js"></script>

<script>
let table = new DataTable('#dtr_table');

// Display Edit Modal
$('#dtr_table').on('click', '.editbtn', function(event) {
      var table = $('#dtr_table').DataTable();
      var trid = $(this).closest('tr').attr('id');
        //console.log(selectedRow);
      var id = $(this).data('id');
      $('#editModal').modal('show');

      $.ajax({
        url: "get_single_data_dtr.php",
        data: {
          id: id
        },
        type: 'post',
        success: function(data) {
          var json = JSON.parse(data);
          $('#d1').val(json.in1);
          $('#d2').val(json.out1);
          $('#d3').val(json.in2);
          $('#d4').val(json.out2);
          $('#d7').val(json.in4);
          $('#d8').val(json.out4);
          $('#dated').val(json.log_date);
          $('#updated_time').val(json.updated_time);
          $('#id').val(id);
        //   $('#trid').val(trid);
        }
      });
    });

// Display Overtime Modal
$('#dtr_table').on('click', '.overtimebtn', function(event) {
      var table = $('#dtr_table').DataTable();
      var trid = $(this).closest('tr').attr('id');
        //console.log(selectedRow);
      var id = $(this).data('id');
      $('#overtimeModal').modal('show');

      $.ajax({
        url: "get_single_data_dtr.php",
        data: {
          id: id
        },
        type: 'post',
        success: function(data) {
          var json = JSON.parse(data);
          $('#d7_a').val(json.in4);
          $('#d8_a').val(json.out4);
          $('#dated_a').val(json.log_date);
          $('#otHrs').val(json.total_ot);
          $('#ot_approved').val(json.ot_approved);
          $('#updated_by').val(json.updated_by);
          $('#id_a').val(id);
        //   $('#trid').val(trid);
        }
      });
    });

// Display Custom Sched Modal
$('#dtr_table').on('click', '.schedbtn', function(event) {
      var table = $('#dtr_table').DataTable();
      var trid = $(this).closest('tr').attr('id');
        //console.log(selectedRow);
      var id = $(this).data('id');
      
      $('#schedModal').modal('show');

      $.ajax({
        url: "get_employee_schedule.php",
        data: {
          id: id
        },
        type: 'post',
        success: function(data) {
          var json = JSON.parse(data);
            $('#d1_b').val(json.in1);
            $('#d2_b').val(json.out1);
            $('#d3_b').val(json.in2);
            $('#d4_b').val(json.out2);
            $('#dated_b').val(json.log_date);
            $('#dated_b2').val(json.log_date);
            $('#created_by').val(json.created_by);
            $('#id_b').val(id);
            //   $('#trid').val(trid);
        }
      });
    });


</script>

<!-- Edit Work Hours -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title fs-5" id="exampleModalLabel">Daily Time Record</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="dtr_edit2.php" method="post">
        <div class="form-group row">
            <label for="dated" class="col-sm-2 col-form-label">Date</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="dated" placeholder="Date" disabled>
                </div>
        </div>
                <div class="mb-3">
                    <table width="100%">
                        <thead>
                            <tr>
                                <th></th>
                                <th>In</th>
                                <th>Out</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><label for="dated" class="col-sm-2 col-form-label">AM<label></td>
                                <td><input type="time"  class="form-control" name="d1" id="d1"></td>
                                <td><input type="time"  class="form-control" name="d2" id="d2"></td>
                            </tr>
                            <tr>
                                <td><label for="dated" class="col-sm-2 col-form-label">PM<label></td>
                                <td><input type="time"  class="form-control" name="d3" id="d3"></td>
                                <td><input type="time"  class="form-control" name="d4" id="d4"></td>
                            </tr>
                            <tr>
                                <td><label for="dated" class="col-sm-2 col-form-label">OT<label></td>
                                <td><input type="time"  class="form-control" name="d7" id="d7"></td>
                                <td><input type="time"  class="form-control" name="d8" id="d8"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            <div class="form-group row">
                <label for="dated_a" class="col-sm-2 col-form-label">Updated by: </label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="updated_time" placeholder="" disabled>
                    </div>
            </div>

                <div class="mb-3" style="text-align: right;">
                    <input type="submit" class="btn btn-primary btn-sm" value="Save Changes" name="changes">
                </div>
                    <input type="hidden" name="dtrID" id="id" value="">
                    <input type="hidden" name="empIDS" value="<?php echo trim($emp_ids)?>">
        </form>
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- OT Approval -->
<div class="modal fade" id="overtimeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title fs-5" id="exampleModalLabel">Overtime Approval</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="ot_approval.php" method="post">
        <div class="form-group row">
            <label for="dated_a" class="col-sm-2 col-form-label">Date</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="dated_a" placeholder="Date" disabled>
                </div>
        </div>
        <div class="form-group row">
            <label for="ot_approved" class="col-sm-2 col-form-label">Status</label>
            <div class="col-sm-10">
                <select id="ot_approved" name="ot_approved" class="form-control" required>
                    <option value="Approved">Approved</option>
                    <option value="Cancelled">Cancelled</option>
                    <option value="Pending">Pending</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="dated_a" class="col-sm-2 col-form-label">Updated by: </label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="updated_by" placeholder="" disabled>
                </div>
        </div>
                <div class="mb-3">
                    <table width="100%">
                        <thead>
                            <tr>
                                <th></th>
                                <th>In</th>
                                <th>Out</th>
                                <th># of Hrs</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><label for="" class="col-sm-2 col-form-label">OT<label></td>
                                <td><input type="time" class="form-control" name="d7_a" id="d7_a" disabled></td>
                                <td><input type="time" class="form-control" name="d8_a" id="d8_a" disabled></td>
                                <td><input type="text" class="form-control" name="otHrs" id="otHrs" disabled></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mb-3" style="text-align: right;">
                    <input type="submit" class="btn btn-primary btn-sm" value="Save Changes" name="changes">
                </div>
                    <input type="hidden" name="dtrID_a" id="id_a" value="">
                    <input type="hidden" name="empIDS_a" value="<?php echo trim($emp_ids)?>">
        </form>
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Custom Sched -->
<div class="modal fade" id="schedModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Customize Scheduled Working Hours</h4>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <div class="modal-body">
        <form action="custom_sched.php" method="post">
        <div class="form-group row">
            <label for="dated_b" class="col-sm-2 col-form-label">Date</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="dated_b" placeholder="Date" disabled>
                    <input type="hidden" id="dated_b2" name="dated_b2">
                </div>
        </div>
        <div class="mb-3">
            <table width="100%">
                <thead>
                    <tr>
                        <th></th>
                        <th>In</th>
                        <th>Out</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><label for="" class="col-sm-2 col-form-label">AM<label></td>
                        <td><input type="time" class="form-control" name="d1_b" id="d1_b"></td>
                        <td><input type="time" class="form-control" name="d2_b" id="d2_b"></td>
                    </tr>
                    <tr>
                        <td><label for="" class="col-sm-2 col-form-label">PM<label></td>
                        <td><input type="time" class="form-control" name="d3_b" id="d3_b"></td>
                        <td><input type="time" class="form-control" name="d4_b" id="d4_b"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="form-group row">
            <label for="dated_a" class="col-sm-2 col-form-label">Created by: </label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="created_by" placeholder="" disabled>
                </div>
        </div>
                <div class="mb-3" style="text-align: right;">
                    <input type="submit" class="btn btn-primary btn-sm" value="Save Changes" name="changes">
                </div>
                    <input type="hidden" name="dtrID_b" id="id_b" value="">
                    <input type="hidden" name="empIDS_b" value="<?php echo trim($emp_ids)?>">
        </form>
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php
// include("includes/footer.php");
// include("includes/scripts.php");
