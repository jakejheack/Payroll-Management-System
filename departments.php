<?php
session_start();

include_once("connection/cons.php");
$con = conns();

// departments
$dep = "SELECT * FROM departments order by dept ASC";
$d_dep = $con->query($dep) or die ($con->error);
$row_dep = $d_dep->fetch_assoc();

// header
include("includes/header.php");
?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h3 class="h2">Departments</h3>
      </div>

        <div class="container table-responsive">
        <?php
            if(isset($_SESSION['status'])){
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>&#10004; '.$_SESSION['status'].'</strong> 
                    <a href="departments.php" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></a>
                  </div>';
                unset($_SESSION['status']);
            }
            if(isset($_SESSION['check'])){
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>&#10008 '.$_SESSION['check'].'</strong>
                    <a href="departments.php" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></a>
                  </div>';
                unset($_SESSION['check']);
            }
        ?>
       
        <div class=""  style="width:80%; margin-left:10%">
            <div class="btnAdd">
                    <a href="#!" data-id="" data-bs-toggle="modal" data-bs-target="#addUserModal" class="btn btn-success btn-sm">+ New Department</a>
            </div>
            <p></p>

            <!-- New Department -->
            <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLabel">Create New Department</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        <div class="modal-body">
                            <form action="add_dept.php" name="addData" method="post" autocomplete="off">
                                <div class="mb-3 row">
                                <label for="cut_off" class="col-md-3 form-label">Name of Department:</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="dept" placeholder="" required>
                                </div>
                                </div>
                                <div class="text-right">
                                    <input type="submit" class="btn btn-primary btn-sm" value="Save" name="submit">
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

                <table id="dept_table" class="dataTable table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Departments</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $no = 1;
                                do{
                                    ?>
                                        <tr>
                                            <td><?php echo $no ?></td>
                                            <td style=""><b><?php echo $row_dep['dept'] ?></b></td>
                                            <td><?= '<a href=javascript:void(); data-id="'.$row_dep['ID'].'" class="btn-info btn-sm editbtn" >Edit</a>'?></td>
                                        </tr>
                                    <?php
                                    $no++;
                                }while($row_dep = $d_dep->fetch_assoc())
                            ?>
                        </tbody>
                    </table>
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
</script>

<script>

    let table = new DataTable('#dept_table',{
        fixedColumns: {
        left: 2
        },
        // scrollCollapse: true,
        scrollX: true
        // scrollY: 500
    });


// Display Edit Modal
$('#dept_table').on('click', '.editbtn', function(event) {
      var table = $('#dept_table').DataTable();
      var trid = $(this).closest('tr').attr('id');
        //console.log(selectedRow);
      var id = $(this).data('id');
      $('#editModal').modal('show');

      $.ajax({
        url: "get_single_data_dept.php",
        data: {
          id: id
        },
        type: 'post',
        success: function(data) {
          var json = JSON.parse(data);
          $('#dept_name').val(json.dept);
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
        <h4 class="modal-title fs-5" id="exampleModalLabel">Edit Name of Department</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="edit_dept.php" method="post">
        <div class="form-group row">
            <label for="dated" class="col-sm-2 col-form-label">Name of Department</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="dept_name" name="dept_name" placeholder="">
                </div>
        </div>

                <div class="mb-3" style="text-align: right;">
                    <input type="submit" class="btn btn-primary btn-sm" value="Save Changes" name="changes">
                </div>
                    <input type="hidden" name="deptID" id="id" value="">
        </form>
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
