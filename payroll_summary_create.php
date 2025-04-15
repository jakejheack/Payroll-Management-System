<?php
session_start();

include_once("connection/cons.php");
$con = conns();

date_default_timezone_set("Asia/Manila");
$y_now = date("Y");
$m_now = date("m");
$d_now = date("j");

$now = date("m/1/Y");
$now_update = date("m/d/y-h:i a");

$m_last = date('m', strtotime($now . "- 1 month"));

if(isset($_POST['create']))
{
    $pay_date = $_POST['pay_date'];
    $payroll = $_POST['cut_off'];
    $from = $_POST['froms'];
    $to = $_POST['tos'];
    $pay_uniqid = uniqid().uniqid();

    $created_by = $_SESSION['Usernames'];
    $date_time_created = date('m/d/y h:ia');

    $pay_date = date('F d, Y', strtotime($pay_date));
    //  payroll summary
    $sql_pay_summary = "SELECT * FROM payroll_summary WHERE pay_date = '$pay_date'";
    $dan_pay_summary = $con->query($sql_pay_summary) or die ($con->error);
    $row_pay_summary = $dan_pay_summary->fetch_assoc();
    $count_pay_summary = mysqli_num_rows($dan_pay_summary);

    if($count_pay_summary > 0){
        $id_pay = $row_pay_summary['ID'];
        $pay_uniqid = $row_pay_summary['payroll_id'];
    }else{
        $sql_add_pay = "INSERT INTO `payroll_summary`(`ID`, `pay_date`, `froms`, `tos`, `cut_off`, `payroll_id`, `created_by`, `date_time_created`, `updated_by`, `date_time_updated`,`status`) 
                        VALUES (NULL,'$pay_date','$from','$to','$payroll','$pay_uniqid','$created_by','$date_time_created','','','ACTIVE')";
        $_add_pay = ($con->query($sql_add_pay) or die ($con->error));
    }
}elseif(isset($_GET['payroll_uniqid'])){
    $pay_uniqid = $_GET['payroll_uniqid'];

    //  payroll summary
    $sql_pay_summary = "SELECT * FROM payroll_summary WHERE payroll_id = '$pay_uniqid'";
    $dan_pay_summary = $con->query($sql_pay_summary) or die ($con->error);
    $row_pay_summary = $dan_pay_summary->fetch_assoc();
    $count_pay_summary = mysqli_num_rows($dan_pay_summary);

    if($count_pay_summary > 0){
        $pay_date = $row_pay_summary['pay_date'];
        $payroll = $row_pay_summary['cut_off'];
        $from = $row_pay_summary['froms'];
        $to = $row_pay_summary['tos'];
        $id_pay = $row_pay_summary['ID'];

        $created_by = $_SESSION['Usernames'];
        $date_time_created = date('m/d/y h:ia');

    }else{
        header("Location: payroll_summary.php");
    }
}else{
    header("Location: payroll_summary.php");
}

// if(isset($_SESSION['dtr_From']) && isset($_SESSION['dtr_To']))
// {
//     $from = $_SESSION['dtr_From'];
//     $to = $_SESSION['dtr_To'];
// }

// departments
$sql_dept = "SELECT * FROM departments ORDER BY dept ASC";
$dan_dept = $con->query($sql_dept) or die ($con->error);
$row_dept = $dan_dept->fetch_assoc();

// payroll_history
$sql_pay_history = "SELECT * FROM payroll_history WHERE payroll_id = '$pay_uniqid' order by department asc";
$dan_pay_history = $con->query($sql_pay_history) or die ($con->error);
$row_pay_history = $dan_pay_history->fetch_assoc();
$count_pay_history = mysqli_num_rows($dan_pay_history);

// header
include("includes/header.php");
?>
<script>
function showLoad() {
  var box = document.getElementById("loading");
  box.style.display = "inline-block";
}

function showLoad_unpost() {
  var box = document.getElementById("loading_unpost");
  box.style.display = "inline-block";
}
</script>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
          <h4>Payroll for the Period of <?= date('M. d, Y', strtotime($from)).' - '.date('M. d, Y', strtotime($to))?></h4>
      </div>

        <div class="container table-responsive">
            <?php
            if(isset($_SESSION['check'])){
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>&#10008 '.$_SESSION['check'].'</strong>
                    <a href="payroll_summary_create.php" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></a>
                  </div>';
                unset($_SESSION['check']);
            }
            ?>
                <div class="btnAdd text-right">
                    <a href="#!" data-id="" data-bs-toggle="modal" data-bs-target="#addUserModal" class="btn btn-success btn-sm">+ Create Payroll per Department</a>
                    <button class="btn btn-info dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Print
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="payroll_summary_pdf.php?payroll_id=<?= $pay_uniqid?>" class="dropdown-item btn-sm" target="_blank">Summary of Payroll</a></li>
                        <li><a href="payroll_deductions_pdf.php?payroll_id=<?= $pay_uniqid?>" class="dropdown-item btn-sm" target="_blank">Employee's Deduction</a></li>
                    </ul>
                    <a href="payroll_summary.php" class="btn btn-secondary btn-sm">Back</a>
                </div>
                <p></p>
        <!-- Create Payroll Modal -->
        <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">New Payroll Department</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                <form action="payroll_create.php" name="addData" method="post" autocomplete="off">
                    <div class="mb-3 row">
                    <label for="cut_off" class="col-md-3 form-label">Cut-Off Period:</label>
                    <div class="col-md-9">
                        <select name=""  class="form-control" id="cut_off" onchange="Cut_off()" disabled>
                            <option value="" disabled selected>Cut-Off Period</option>
                            <option value="Start" <?= ($payroll == 'Start') ? 'selected' : '' ?> disabled>Start</option>
                            <option value="End" <?= ($payroll == 'End') ? 'selected' : '' ?> disabled>End</option>
                        </select>
                    </div>
                    </div>
                    <div id="cut_period">
                        <div class="mb-3 row">
                            <label for="" class="col-md-3 form-label">From:</label>
                            <div class="col-md-9">
                                <input type="date" name="" class="form-control" id="" value="<?= date('Y-m-d', strtotime($from))?>" disabled>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="" class="col-md-3 form-label">To:</label>
                            <div class="col-md-9">
                                <input type="date" name="" class="form-control" id="" value="<?= date('Y-m-d', strtotime($to))?>" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                    <label for="dept" class="col-md-3 form-label">Department</label>
                    <div class="col-md-9">
                        <select name="dept" class="form-control" id="dept" required>
                            <option value="" disabled selected>Select Department</option>
                            <?php
                                do{
                                    ?>
                                        <option value="<?= $row_dept['dept'] ?>"><?= $row_dept['dept'] ?></option>
                                    <?php
                                }while($row_dept = $dan_dept->fetch_assoc())
                            ?>
                        </select>
                    </div>
                    </div>
                    <div class="text-center">
                        <input type="hidden" name="cut_off" value="<?= $payroll?>">
                        <input type="hidden" name="froms" value="<?= $from?>">
                        <input type="hidden" name="tos" value="<?= $to?>">
                        <input type="hidden" name="pay_uniqid" value="<?= $pay_uniqid?>">
                        <input type="submit" class="btn btn-primary" value="Create" name="create">
                    </div>
                </form>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
            </div>
        </div>

                        <table id="records" class="table table-striped table-bordered table-hover" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Department</th>
                                    <th>Gross Amount</th>
                                    <th>Deductions</th>
                                    <th>Net Pay</th>
                                    <th>Created by</th>
                                    <th>Date & Time Created</th>
                                    <th>Updated By</th>
                                    <th>Date & Time Updated</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $no = 0;
                                    $net_pay = 0;
                                    $gross = 0;
                                    $deductions = 0;
                                    if($count_pay_history > 0){
                                        do{
                                            $no++;

                                            ?>
                                                <tr>
                                                    <td><?= $no ?></td>
                                                    <td>
                                                        <a href="payroll_view.php?payroll_uniqid=<?= $row_pay_history['payroll_id']?>
                                                            &transaction_id=<?= $row_pay_history['transaction_id']?>
                                                            &department_id=<?= $row_pay_history['dept_id']?>
                                                            &department_name=<?= $row_pay_history['department'] ?>
                                                            &froms=<?= $row_pay_history['froms'] ?>
                                                            &tos=<?= $row_pay_history['tos'] ?>
                                                            &payroll=<?= $row_pay_history['cut_off'] ?>
                                                            &status=<?= $row_pay_history['status'] ?>" class="btn text-active btn-link btn-sm">
                                                            <?= $row_pay_history['department']?>
                                                        </a>    
                                                    </td>
                                                    <td><?= $row_pay_history['gross']?></td>
                                                    <td><?= $row_pay_history['deductions']?></td>
                                                    <td><?= $row_pay_history['netpay']?></td>
                                                    <td><?= $row_pay_history['created_by']?></td>
                                                    <td><?= $row_pay_history['date_created']?></td>
                                                    <td><?= $row_pay_history['updated_by']?></td>  
                                                    <td><?= $row_pay_history['date_updated']?></td>
                                                    <td><button <?= ($row_pay_history['status'] == 'ACTIVE' ? 'class="btn btn-danger btn-sm" onclick="deleteme('.$row_pay_history['ID'].')"' : 'class="btn btn-secondary btn-sm" disabled')?>>Delete</button></td>
                                                </tr>
                                            <?php
                                            $net_pay = $net_pay + floatval(str_replace( ',', '', $row_pay_history['netpay']));
                                            $gross = $gross + floatval(str_replace( ',', '', $row_pay_history['gross']));
                                            $deductions = $deductions + floatval(str_replace( ',', '', $row_pay_history['deductions']));
                                        }while($row_pay_history = $dan_pay_history->fetch_assoc());
                                    }else{
                                        ?>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        <?php
                                    }

                                    //  payroll summary
                                    $sql_pay_summary = "SELECT * FROM payroll_summary WHERE payroll_id = '$pay_uniqid'";
                                    $dan_pay_summary = $con->query($sql_pay_summary) or die ($con->error);
                                    $row_pay_summary = $dan_pay_summary->fetch_assoc();
                                    $count_pay_summary = mysqli_num_rows($dan_pay_summary);

                                    if($count_pay_summary > 0){
                                        $id_summary = $row_pay_summary['ID'];
                                        if($net_pay != $row_pay_summary['net_pay'] || $gross != $row_pay_summary['gross_pay'] || $deductions != $row_pay_summary['deductions']){
                                            $sql_add_pay = "UPDATE `payroll_summary` SET `updated_by`='$created_by',`date_time_updated`='$date_time_created',
                                                            `gross_pay`='$gross', `deductions`='$deductions', `net_pay`='$net_pay' WHERE `ID` = '$id_summary'";
                                            $_add_pay = ($con->query($sql_add_pay) or die ($con->error));
                                        }
                                    }

                                ?>
                            </tbody>
                            <tfoot>
                                <th></th>
                                <th>Total</th>
                                <th><?= number_format($gross,2)?></th>
                                <th><?= number_format($deductions,2)?></th>
                                <th><?= number_format($net_pay,2)?></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tfoot>
                        </table>
        </div>        
</div>
<!--  -->
</div>
    </div>
</main>

<?php
include("includes/footer.php");
?>

<script language="javascript">
let modalBtns = [...document.querySelectorAll(".button")];
modalBtns.forEach(function(btn) {
  btn.onclick = function() {
    let modal = btn.getAttribute('data-modal');
    document.getElementById(modal)
      .style.display = "block";
  }
});
let closeBtns = [...document.querySelectorAll(".close")];
closeBtns.forEach(function(btn) {
  btn.onclick = function() {
    let modal = btn.closest('.modal');
    modal.style.display = "none";
  }
});

let table = new DataTable('#records',{
        fixedColumns: {
        left: 5
        },
        scrollCollapse: true,
        scrollX: true,
        scrollY: 500
    });


    // Delete Data
    function deleteme(delid)
    {
        if(confirm("Do you want to Delete this Record?")){
            window.location.href='delete_payroll_summary.php?id=' +delid+'';
            return true;
        }
    }


// Display status Modal
$('#records').on('click', '.statusbtn', function(event) {
      var table = $('#records').DataTable();
      var trid = $(this).closest('tr').attr('id');
        //console.log(selectedRow);
      var id = $(this).data('id');
      $('#statusModal').modal('show');

      $.ajax({
        url: "get_single_data_payroll.php",
        data: {
          id: id
        },
        type: 'post',
        success: function(data) {
          var json = JSON.parse(data);
          $('#transaction_id').val(json.transaction_id);
          $('#department').val(json.department);
          $('#net_amount').val(json.net_amount);
          $('#period').val(json.period);
          $('#cut-offs').val(json.cut_off);
          $('#status').val(json.status);
          $('#payroll_ID').val(id);
        //   $('#trid').val(trid);
        }
      });
});

// Display Unpost status Modal
$('#records').on('click', '.unpostbtn', function(event) {
      var table = $('#records').DataTable();
      var trid = $(this).closest('tr').attr('id');
        //console.log(selectedRow);
      var id = $(this).data('id');
      $('#unpostModal').modal('show');

      $.ajax({
        url: "get_single_data_payroll.php",
        data: {
          id: id
        },
        type: 'post',
        success: function(data) {
          var json = JSON.parse(data);
          $('#transaction_id2').val(json.transaction_id);
          $('#department2').val(json.department);
          $('#net_amount2').val(json.net_amount);
          $('#period2').val(json.period);
          $('#cut-offs2').val(json.cut_off);
          $('#status2').val(json.status);
          $('#payroll_ID2').val(id);
        //   $('#trid').val(trid);
        }
      });
});
</script>

<!-- Post Payroll -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title fs-5" id="exampleModalLabel">Payroll Info</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="payroll_post.php" method="post">
            <div class="form-group row">
                <label for="dated" class="col-sm-2 col-form-label">Transaction ID:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="transaction_id" disabled>
                    </div>
            </div>
            <div class="form-group row">
                <label for="dated_a" class="col-sm-2 col-form-label">Department</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="department" placeholder="" disabled>
                    </div>
            </div>
            <div class="form-group row">
                <label for="dated_a" class="col-sm-2 col-form-label">Net Amount</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="net_amount" placeholder="" disabled>
                    </div>
            </div>
            <div class="form-group row">
                <label for="dated_a" class="col-sm-2 col-form-label">Period Covered</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="period" placeholder="" disabled>
                    </div>
            </div>
            <div class="form-group row">
                <label for="dated_a" class="col-sm-2 col-form-label">Cut-off Period</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="cut-offs" placeholder="" disabled>
                    </div>
            </div>
            <div class="form-group row">
                <label for="dated_a" class="col-sm-2 col-form-label">Status</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="status" placeholder="" disabled>
                    </div>
            </div>

                <div class="mb-3" style="text-align: right;">
                    <div class="spinner-border spinner-border-sm text-secondary loading" role="status" id="loading" style="display: none;">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <input type="submit" class="btn btn-sm btn-danger" value="Post" name="post" onclick="showLoad()">
                </div>
                    <input type="hidden" name="payroll_ID" id="payroll_ID" value="">
        </form>
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Unpost Payroll -->
<div class="modal fade" id="unpostModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title fs-5" id="exampleModalLabel">Payroll Info</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="payroll_post.php" method="post">
            <div class="form-group row">
                <label for="dated" class="col-sm-2 col-form-label">Transaction ID:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="transaction_id2" disabled>
                    </div>
            </div>
            <div class="form-group row">
                <label for="dated_a" class="col-sm-2 col-form-label">Department</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="department2" placeholder="" disabled>
                    </div>
            </div>
            <div class="form-group row">
                <label for="dated_a" class="col-sm-2 col-form-label">Net Amount</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="net_amount2" placeholder="" disabled>
                    </div>
            </div>
            <div class="form-group row">
                <label for="dated_a" class="col-sm-2 col-form-label">Period Covered</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="period2" placeholder="" disabled>
                    </div>
            </div>
            <div class="form-group row">
                <label for="dated_a" class="col-sm-2 col-form-label">Cut-off Period</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="cut-offs2" placeholder="" disabled>
                    </div>
            </div>
            <div class="form-group row">
                <label for="dated_a" class="col-sm-2 col-form-label">Status</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="status2" placeholder="" disabled>
                    </div>
            </div>

                <div class="mb-3" style="text-align: right;">
                    <div class="spinner-border spinner-border-sm text-secondary loading_unpost" role="status" id="loading_unpost" style="display: none;">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <input type="submit" class="btn btn-sm btn-secondary" value="Unpost" name="unpost" onclick="showLoad_unpost()">
                </div>
                    <input type="hidden" name="payroll_ID2" id="payroll_ID2" value="">
        </form>
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
