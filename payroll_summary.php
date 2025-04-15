<?php
session_start();

include_once("connection/cons.php");

$con = conns();


date_default_timezone_set("Asia/Manila");
$y_now = date("Y");
$m_now = date("m");
$d_now = date("j");

$now = date("m/1/Y");

$m_last = date('m', strtotime($now . "- 1 month"));

if(isset($_GET['Payroll']))
{
    $payroll = $_GET['Payroll'];

    if($payroll == 'Start')
    {
        if($m_now == date('m', strtotime('01/01/2022')))
        {
            $y_last = date('Y', strtotime($now . "- 1 year"));
        }else{
            $y_last = $y_now;
        }

        $from = $m_last.'/28'.'/'.$y_last;
        $to = $m_now.'/12'.'/'.$y_now;
    }elseif($payroll == 'End')
    {
        $from = $m_now.'/13'.'/'.$y_now;
        $to = $m_now.'/27'.'/'.$y_now;
    }

    $from = date('m/d/Y', strtotime($from));
    $to = date('m/d/Y', strtotime($to));

    $_SESSION['dtr_From'] = $from;
    $_SESSION['dtr_To'] = $to;

}else{
    $payroll = 'Start';

    if($m_now == date('m', strtotime('01/01/2022')))
    {
        $y_last = date('Y', strtotime($now . "- 1 year"));
    }else{
        $y_last = $y_now;
    }

    $from = $m_last.'/28'.'/'.$y_last;
    $to = $m_now.'/12'.'/'.$y_now;
}

$_SESSION['Payroll'] = $payroll;

if(isset($_POST['details']))
{
    $from = $_POST['from'];
    $to = $_POST['to'];

    $from = date('m/d/Y', strtotime($from));
    $to = date('m/d/Y', strtotime($to));

    $_SESSION['dtr_From'] = $from;
    $_SESSION['dtr_To'] = $to;
}


if($payroll == 'Start'){
    $pay_date = date('m/15/Y', strtotime($to));
}elseif($payroll == 'End'){
    $pay_date = date('m/30/Y', strtotime($to));
}else{
    $pay_date = date('m/15/Y', strtotime($to));
}

// departments
$sql_dept = "SELECT * FROM departments ORDER BY dept ASC";
$dan_dept = $con->query($sql_dept) or die ($con->error);
$row_dept = $dan_dept->fetch_assoc();

//  payroll history
$sql_pay_h = "SELECT * FROM payroll_history ORDER BY ID DESC";
$dan_pay_h = $con->query($sql_pay_h) or die ($con->error);
$row_pay_h = $dan_pay_h->fetch_assoc();
$count = mysqli_num_rows($dan_pay_h);

//  payroll summary
$sql_pay_summary = "SELECT * FROM payroll_summary WHERE cut_off = 'Start' || cut_off = 'End' ORDER BY ID DESC";
$dan_pay_summary = $con->query($sql_pay_summary) or die ($con->error);
$row_pay_summary = $dan_pay_summary->fetch_assoc();
$count_pay_summary = mysqli_num_rows($dan_pay_summary);

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
        <h3 class="h2">Payroll Summary</h3>
      </div>

        <div class="container table-responsive">
            <?php
            if(isset($_SESSION['check'])){
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>&#10008 '.$_SESSION['check'].'</strong>
                    <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>';
                unset($_SESSION['check']);
            }
            ?>
                <div class="btnAdd">
                    <a href="#!" data-id="" data-bs-toggle="modal" data-bs-target="#addUserModal" class="btn btn-success btn-sm">+ Create Payroll</a>
                    <a href="payroll_bonus_summary.php" class="btn btn-warning btn-sm">13th Month</a>
                </div>
                <p></p>
        <!-- Create Payroll Modal -->
        <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">Create Payroll</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                <form action="payroll_summary_create.php" name="addData" method="post" autocomplete="off">
                    <div class="mb-3 row">
                    <label for="cut_off" class="col-md-3 form-label">Pay Date:</label>
                    <div class="col-md-9">
                        <input type="date" name="pay_date" id="" class="form-control"  value="<?= date('Y-m-d', strtotime($pay_date))?>" required>
                    </div>
                    </div>
                    <div class="mb-3 row">
                    <label for="cut_off" class="col-md-3 form-label">Cut-Off Period:</label>
                    <div class="col-md-9">
                        <select name="cut_off"  class="form-control" id="cut_off" onchange="Cut_off()" required>
                            <option value="" disabled selected>Cut-Off Period</option>
                            <option value="Start" <?= ($payroll == 'Start') ? 'selected' : '' ?>>Start</option>
                            <option value="End" <?= ($payroll == 'End') ? 'selected' : '' ?>>End</option>
                        </select>
                    </div>
                    </div>
                    <div id="cut_period">
                        <div class="mb-3 row">
                            <label for="" class="col-md-3 form-label">From:</label>
                            <div class="col-md-9">
                                <input type="date" name="froms" class="form-control" id="" value="<?= date('Y-m-d', strtotime($from))?>" required>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="" class="col-md-3 form-label">To:</label>
                            <div class="col-md-9">
                                <input type="date" name="tos" class="form-control" id="" value="<?= date('Y-m-d', strtotime($to))?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <input type="submit" class="btn btn-primary" value="Create" name="create">
                    <!-- <button type="submit" class="btn btn-primary" onclick="insert();" name="button">Submit</button> -->
                    </div>
                </form>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
            </div>
        </div>


        <!-- 13th Month Pay Modal -->
        <div class="modal fade" id="thMonthModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h2 class="modal-title" id="exampleModalLabel">Create 13th Month Pay</h2>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                <form action="payroll_create_bonus.php" name="addData" method="post" autocomplete="off">
                    <div class="input-group">
                        <span class="input-group-text">From:</span>
                        <select name="month_from" id="" class="form-control" required>
                            <option value="" disabled selected hidden>Month</option>
                            <?php
                                if(isset($_SESSION['Bonus_Month_From'])){
                                    $m_from = $_SESSION['Bonus_Month_From'];
                                }else{
                                    $m_from = '';
                                }
                                for($i = 1; $i <=12; $i++){
                                    $dateObj   = DateTime::createFromFormat('!m', $i);
                                    $monthName = $dateObj->format('F');
                                    ?>
                                        <option value="<?= $i ?>" <?= ($i == $m_from ? 'selected' : '')?>><?= $monthName ?></option>
                                    <?php
                                }
                            ?>
                        </select>
                        <select name="year_from" id="" class="form-control" required>
                            <option value="" disabled selected hidden>Year</option>
                            <?php
                                    for($yr_now = date('Y'); $yr_now >= 2023; $yr_now--){
                                        echo '<option value="'.$yr_now.'" '.($yr_now == date('Y') ? 'selected' : '').'>'.$yr_now.'</option>';
                                    }
                            ?>
                        </select>
                    </div>
                    <p></p>
                    <div class="input-group">
                        <span class="input-group-text">To:</span>
                        <select name="month_to" id="" class="form-control" required>
                            <option value="" disabled selected hidden>Month</option>
                            <?php
                                if(isset($_SESSION['Bonus_Month_To'])){
                                    $m_to = $_SESSION['Bonus_Month_To'];
                                }else{
                                    $m_to = '';
                                }
                                for($i = 1; $i <=12; $i++){
                                    $dateObj   = DateTime::createFromFormat('!m', $i);
                                    $monthName = $dateObj->format('F');
                                    ?>
                                        <option value="<?= $i ?>" <?= ($i == $m_to ? 'selected' : '')?>><?= $monthName ?></option>
                                    <?php
                                }
                            ?>
                        </select>
                        <select name="year_to" id="" class="form-control" required>
                            <option value="" disabled selected hidden>Year</option>
                            <?php
                                for($yr_now = date('Y'); $yr_now >= 2023; $yr_now--){
                                    echo '<option value="'.$yr_now.'" '.($yr_now == date('Y') ? 'selected' : '').'>'.$yr_now.'</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <p></p>
                    <!-- <div class="input-group">
                        <span class="input-group-text">Department:</span>
                        <select name="dept_bonus" class="form-control" required>
                            <option value="" disabled selected>Select Department</option>
                            <?php
                            // departments
                            $sql_dept2 = "SELECT * FROM departments ORDER BY dept ASC";
                            $dan_dept2 = $con->query($sql_dept2) or die ($con->error);
                            $row_dept2 = $dan_dept2->fetch_assoc();

                                do{
                                    ?>
                                        <option value="<?= $row_dept2['dept'] ?>"><?= $row_dept2['dept'] ?></option>
                                    <?php
                                }while($row_dept2 = $dan_dept2->fetch_assoc())
                            ?>
                        </select>
                    </div>
                    <p></p> -->
                    <div class="text-right">
                        <input type="submit" class="btn btn-primary" value="Create" name="create_month">
                    <!-- <button type="submit" class="btn btn-primary" onclick="insert();" name="button">Submit</button> -->
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
                                    <th>Payroll Date</th>
                                    <th>Period Covered</th>
                                    <th>Cut-off</th>
                                    <th>Net Amount</th>
                                    <th>Status</th>
                                    <th>Created By</th>
                                    <th>Date & Time Created</th>
                                    <th>Updated By</th>
                                    <th>Date & Time Updated</th>
                                 </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $no = 0;
                                    if($count_pay_summary > 0)
                                    {
                                        do{
                                            $no++;
                                            $net_pay = 0;
                                            $gross = 0;
                                            $deductions = 0;

                                            $pay_uniqid = $row_pay_summary['payroll_id'];
                                            // payroll_history
                                            $sql_pay_history = "SELECT * FROM payroll_history WHERE payroll_id = '$pay_uniqid'";
                                            $dan_pay_history = $con->query($sql_pay_history) or die ($con->error);
                                            $row_pay_history = $dan_pay_history->fetch_assoc();
                                            $count_pay_history = mysqli_num_rows($dan_pay_history);

                                            if($count_pay_history > 0){
                                                do{
                                                    $net_pay = $net_pay + floatval(str_replace( ',', '', $row_pay_history['netpay']));
                                                    $gross = $gross + floatval(str_replace( ',', '', $row_pay_history['gross']));
                                                    $deductions = $deductions + floatval(str_replace( ',', '', $row_pay_history['deductions']));
                                                }while($row_pay_history = $dan_pay_history->fetch_assoc());
                                            }
                                            ?>
                                                <tr>
                                                    <td><?= $no ?></td>
                                                    <td>
                                                        <?php
                                                            if($row_pay_summary['cut_off'] == '13th Month'){
                                                                ?>
                                                                <a href="payroll_create_bonus.php?ID=<?= $row_pay_summary['payroll_id']?>" 
                                                                    class="btn text-active btn-link btn-sm">
                                                                    <?= $row_pay_summary['pay_date']?>
                                                                </a>
                                                            <?php
                                                                }else{
                                                        ?>
                                                        <a href="payroll_summary_create.php?payroll_uniqid=<?= $row_pay_summary['payroll_id']?>" 
                                                            class="btn text-active btn-link btn-sm">
                                                            <?= $row_pay_summary['pay_date']?>
                                                        </a>
                                                        <?php } ?>
                                                    </td>
                                                    <td><?= $row_pay_summary['cut_off'] == '13th Month' ? date('F', strtotime($row_pay_summary['froms'])).' to '.date('F', strtotime($row_pay_summary['tos'])) : date('m/d/y', strtotime($row_pay_summary['froms'])).' - '.date('m/d/y', strtotime($row_pay_summary['tos']))?></td>
                                                    <td><b <?= $row_pay_summary['cut_off'] == 'Start' ? 'class="text-success"' : 'class="text-danger"' ?>><?= $row_pay_summary['cut_off']?></b></td>
                                                    <td><?= number_format($row_pay_summary['net_pay'],2)?></td>
                                                    <td><a href=javascript:void(); data-id="<?= $row_pay_summary['ID']?>" <?= ($row_pay_summary['status'] == 'POSTED' ? 'class="badge badge-secondary unpostbtn"' : 'class="badge badge-success statusbtn"')?> ><?= ($row_pay_summary['status'] == 'POSTED' ? '&check; '.$row_pay_summary['status'] : $row_pay_summary['status'])?></a></td>
                                                    <td><?= $row_pay_summary['created_by']?></td>
                                                    <td><?= $row_pay_summary['date_time_created']?></td>
                                                    <td><?= $row_pay_summary['updated_by']?></td>
                                                    <td><?= $row_pay_summary['date_time_updated']?></td>
                                                    <!-- <td><button <?= ($row_pay_summary['status'] == 'ACTIVE' ? 'class="btn btn-danger btn-sm" onclick="deleteme('.$row_pay_summary['ID'].')"' : 'class="btn btn-secondary btn-sm" disabled')?>>Delete</button></td> -->
                                                </tr>
                                            <?php
                                        }while($row_pay_summary = $dan_pay_summary->fetch_assoc());
                                    }else{
                                        ?>
                                            <tr>
                                            </tr>
                                        <?php
                                    }
                                ?>
                            </tbody>
                        </table>
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
        left: 4
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
          $('#pay_date1').val(json.pay_date);
          $('#net_amount1').val(json.net_pay);
          $('#from1').val(json.froms);
          $('#to1').val(json.tos);
          $('#cut-off1').val(json.cut_off);
          $('#status1').val(json.status);
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
          $('#pay_date2').val(json.pay_date);
          $('#net_amount2').val(json.net_pay);
          $('#from2').val(json.froms);
          $('#to2').val(json.tos);
          $('#cut-off2').val(json.cut_off);
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
                <label for="dated" class="col-sm-2 col-form-label">Pay Date:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="pay_date1" disabled>
                    </div>
            </div>
            <div class="form-group row">
                <label for="dated_a" class="col-sm-2 col-form-label">Net Amount</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="net_amount1" placeholder="" disabled>
                    </div>
            </div>
            <div class="form-group row">
                <label for="dated_a" class="col-sm-2 col-form-label">Period From:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="from1" placeholder="" disabled>
                    </div>
            </div>
            <div class="form-group row">
                <label for="dated_a" class="col-sm-2 col-form-label">Period To:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="to1" placeholder="" disabled>
                    </div>
            </div>
            <div class="form-group row">
                <label for="dated_a" class="col-sm-2 col-form-label">Cut-off Period</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="cut-off1" placeholder="" disabled>
                    </div>
            </div>
            <div class="form-group row">
                <label for="dated_a" class="col-sm-2 col-form-label">Status</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="status1" placeholder="" disabled>
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
                <label for="dated" class="col-sm-2 col-form-label">Pay Date:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="pay_date2" disabled>
                    </div>
            </div>
            <div class="form-group row">
                <label for="dated_a" class="col-sm-2 col-form-label">Net Amount</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="net_amount2" placeholder="" disabled>
                    </div>
            </div>
            <div class="form-group row">
                <label for="dated_a" class="col-sm-2 col-form-label">Period From:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="from2" placeholder="" disabled>
                    </div>
            </div>
            <div class="form-group row">
                <label for="dated_a" class="col-sm-2 col-form-label">Period To:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="to2" placeholder="" disabled>
                    </div>
            </div>
            <div class="form-group row">
                <label for="dated_a" class="col-sm-2 col-form-label">Cut-off Period</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="cut-off2" placeholder="" disabled>
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