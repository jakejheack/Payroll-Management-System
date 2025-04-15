<?php
session_start();

include_once("connection/cons.php");
$con = conns();

date_default_timezone_set("Asia/Manila");
$y_now = date("Y");
$m_now = date("m");
$d_now = date("j");

if(isset($_GET['ID']))
{
    $emp_ids = $_GET['ID'];
    $department = $_GET['Department'];
    $_SESSION['emp_id'] = $emp_ids;
}else{
    $emp_ids= $_SESSION['emp_id'];
}

if(isset($_SESSION['dtr_From']) && isset($_SESSION['dtr_To']))
{
    $from = $_SESSION['dtr_From'];
    $to = $_SESSION['dtr_To'];
}else{
    $from = $m_now.'/01'.'/'.$y_now;
    $to = date("m/t/Y", strtotime($from));

    $_SESSION['dtr_From'] = $from;
    $_SESSION['dtr_To'] = $to;
}

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
    <h3>DTR for the Period of  
        <?php echo date('M. d, Y', strtotime($from)).' - '.date('M. d, Y', strtotime($to))?>
    </h3>
        <div class="contents">
            <div class="pay_dtr">
                <a href="daily_time_records.php?
                    Department=<?= $_SESSION['departmentsss'] ?>" class="btn btn-secondary btn-sm">Back</a>
            </div>
        
            <div class="date_covered">
                    <h4>Employee's Information</h4>
                    <span>Name: <b><?php echo $emp_name ?></b></span>
                    <br>
                    <span>Position: <b><?php echo $position ?></b></span>
                    <br>

                <?php 
                if(isset($_SESSION['dtr_From']) && isset($_SESSION['dtr_To']) && isset($_SESSION['emp_id']) && $count_emp > 0){
                    $emp_id = $_SESSION['emp_id'];
                    $from = $_SESSION['dtr_From'];
                    $to = $_SESSION['dtr_To'];
                    ?>
                    <table id="dtr_table" class="dataTable table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th rowspan="2">Date</th>
                                <th rowspan="2">Day</th>
                                <th colspan="2">AM</th>
                                <th colspan="2">PM</th>
                                <th colspan="2">O.T.</th>
                                <th rowspan="2">Total Working Hrs.</th>
                                <th rowspan="2">Total O.T. Hrs.</th>
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
                            </tr>
                        </thead>
                        <tbody>
                                <?php
                                    $total_hrs = 0;
                                    $total_ot = 0;
                                    do{

                                        ?>
                                        <tr>
                                            <td><?php echo date('m/d', strtotime($from)) ?></td>
                                            <td><?php echo date('D', strtotime($from)) ?></td>
                                            <?php
                                                $ddd = date('l, F d, Y', strtotime($from));
                                                // dtr
                                                $sql_dtr = "SELECT * FROM dtrs WHERE emp_id = '$emp_id' && log_date = '$ddd'";
                                                $dan_dtr = $con->query($sql_dtr) or die ($con->error);
                                                $row_dtr = $dan_dtr->fetch_assoc();
                                                $count = mysqli_num_rows($dan_dtr);

                                                $dayy = date('l', strtotime($from));

                                                $dayys = date('D', strtotime($from));
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
                                                $hol_date = date('2023-m-d', strtotime($from));

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
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td><?php echo $remarks." ".$hol_types?></td>
                                                    <td><?= '<a href=javascript:void();" data-id="'.$from.'" class="btn btn-info btn-sm editbtn">Edit</a>'?></td>
                                                <?php
                                            }else{
                                                $hrs1 = (double)$row_dtr['total_hrs'];
                                                $ot = (double)$row_dtr['total_ot'];

                                                // $d1 = strtotime($row_dtr['in1']);
                                                // $d2 = strtotime($row_dtr['out1']);
                                                // $d3 = strtotime($row_dtr['in2']);
                                                // $d4 = strtotime($row_dtr['out2']);
                                                // $d5 = strtotime($row_dtr['in3']);
                                                // $d6 = strtotime($row_dtr['out3']);
                                                // $d7 = strtotime($row_dtr['in4']);
                                                // $d8 = strtotime($row_dtr['out4']);

                                                // $am = abs($d2 - $d1)/3600;
                                                // $pm = abs($d4 - $d3)/3600;
                                                // $hrs1 = $am + $pm;

                                                // $ot1 = abs($d6 - $d5)/3600;
                                                // $ot2 = abs($d8 - $d7)/3600;
                                                // $hrs2 = $ot1 + $ot2;

                                                $total_hrs = $total_hrs + $hrs1;
                                                $total_ot = $total_ot + $ot;

                                            ?>
                                                <td><?php echo $row_dtr['in1']?></td>
                                                <td><?php echo $row_dtr['out1']?></td>
                                                <td><?php echo $row_dtr['in2']?></td>
                                                <td><?php echo $row_dtr['out2']?></td>
                                                <!-- <td><?php echo $row_dtr['in3']?></td>
                                                <td><?php echo $row_dtr['out3']?></td> -->
                                                <td><?php echo $row_dtr['in4']?></td>
                                                <td><?php echo $row_dtr['out4']?></td>
                                                <td><?php echo $hrs1?></td>
                                                <td><?php echo $ot?></td>
                                                <td><?php echo $remarks." ".$hol_types?></td>
                                                <td><?= '<a href=javascript:void();" data-id="'.$row_dtr['ID'].'" class="btn btn-info btn-sm editbtn" >Edit</a>'?></td>
                                            <?php
                                            }
                                        ?>
                                        </tr>
                                        <?php
                                        $from = date('m/d/Y', strtotime($from . " +1 day"));
                                    }while(strtotime($from) <= strtotime($to));
                                ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th colspan="">Total Hrs</th>
                                <th><?php echo round($total_hrs,2)?></th>
                                <th><?php echo round($total_ot,2)?></th>
                                <th colspan=""></th>
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
          $('#id').val(id);
        //   $('#trid').val(trid);
        }
      });
    });

</script>


<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title fs-5" id="exampleModalLabel">Daily Time Record</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="dtr_edit.php" method="post">
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
                <div class="mb-3">
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


<?php
// include("includes/footer.php");
// include("includes/scripts.php");

