<?php
session_start();

include_once("connection/cons.php");
$con = conns();


// header
include("includes/header.php");
// include("includes/menus.php");

?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">List of Employees</h1>
      </div>

        <div class="container text-center table-responsive">

        <?php
            if(isset($_SESSION['status'])){
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>&#10004; '.$_SESSION['status'].'</strong> 
                    <a href="employees.php" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></a>
                  </div>';
                unset($_SESSION['status']);
            }
            if(isset($_SESSION['status_sched'])){
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>&#10004; '.$_SESSION['status_sched'].'</strong> 
                    <a href="employees.php" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></a>
                  </div>';
                unset($_SESSION['status_sched']);
            }
            if(isset($_SESSION['check'])){
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>&#10008 '.$_SESSION['check'].'</strong>
                    <a href="employees.php" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></a>
                  </div>';
                unset($_SESSION['check']);
            }
        ?>

                <?php
                if($_SESSION['Access'] == 'HRD')
                {
                ?>

                    <div class="row" >
                        <div class="" style="width:33.33%;">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="inputGroupSelect01" style="font-size: 0.7rem;">Department</label>
                                </div>
                                        <select class="custom-select"  id="select_dept" style="font-size: 0.7rem;">
                                        <option value="">Choose Department...</option>
                                        </select>                               
                            </div>
                        </div>
                        <div class="" style="width:33.33%;  padding: 0 10px ">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="inputGroupSelect01" style="font-size: 0.7rem;">Position</label>
                                </div>
                                <select class="custom-select" id="select_position" style="font-size: 0.7rem;">
                                    <option value="">Choose Position...</option>
                                </select>
                            </div>
                        </div>
                        <div style="width:33.33%;">
                            <button id="filter" class="btn btn-sm btn-info" style="font-size: 0.7rem;">Filter</button>
                            <button id="reset" class="btn btn-sm btn-warning" style="font-size: 0.7rem;">Reset Filter</button>
                        </div>
                    </div>

                <?php
                }
                ?>
                <p></p>

                <div id=""  class="table-responsive">
                    <table id="records" class="table table-striped table-bordered table-hover" style="width: 100%;">
                        <thead>
                            <th>Employee's Information</th>
                            <th>Position & Department</th>
                            <th>Address / Contact #</th>
                        </thead>
                        <tbody>
                          <!--   <tr>
                                <th colspan="4">
                                    Loading Data. . .
                                    <div class="spinner-grow spinner-grow-sm" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <div class="spinner-grow spinner-grow-sm" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <div class="spinner-grow spinner-grow-sm" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </th>
                            </tr> -->
                        </tbody>
                    </table>
                </div>
        </div>
<!--  -->
    </div>
    </div>
</main>


<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title fs-5" id="exampleModalLabel">Daily Time Record</h3>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="daily_time_record_employee.php" method="post">
            <div class="form-group row">
                <label for="dated" class="col-sm-2 col-form-label">Name</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="names" id="fullname" disabled required>
                    </div>
            </div>
            <div class="form-group row">
                <label for="dated" class="col-sm-2 col-form-label">From</label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control" name="froms" id="" required>
                    </div>
            </div>
            <div class="form-group row">
                <label for="dated" class="col-sm-2 col-form-label">To</label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control" name="tos" id="" required>
                    </div>
            </div>
                <div class="mb-3" style="text-align: center;">
                    <input type="submit" class="btn btn-primary btn-sm" value="Browse" name="browse">
                </div>
                    <input type="hidden" name="ID" id="id" value="">
        </form>
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<script>
    // Fetch Department

        function fetch_dept() {
            $.ajax({
                url: "fetch_dept.php",
                type: "post",
                dataType: "json",
                success: function(data) {
                    var deptBody = "";
                    for (var key in data) {
                        deptBody += `<option value="${data[key]['dept']}">${data[key]['dept']}</option>`;
                    }
                    $("#select_dept").append(deptBody);
                }
            });
        }
        fetch_dept();

        // Fetch Position
        function fetch_position() {
            $.ajax({
                url: "fetch_position.php",
                type: "post",
                dataType: "json",
                success: function(data) {
                    var positionBody = "";
                    for (var key in data) {
                        positionBody += `<option value="${data[key]['pos']}">${data[key]['pos']}</option>`;
                    }
                    $("#select_position").append(positionBody);
                }
            });
        }
        fetch_position();

            // Fetch Records

        function fetch(dept, position) {
        $.ajax({
            url: "recording.php",
            type: "post",
            data: {
                dept: dept,
                position: position
            },
            dataType: "json",
            success: function(data) {
                var i = 0;
                $('#records').DataTable({
                    "data": data,
                    "responsive": true,
                    "aoColumnDefs": [{
                        // "bSortable": false,
                        targets: [0], className: 'dt-left',
                        // "aTargets": [2]
                     }],
                      pageLength : 5,
                      lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],
                      scrollCollapse: true,
                      scrollX: true,
                      scrollY: 400,
                    });
                }
            });
        }
        fetch();

            // Filter

            $(document).on("click", "#filter", function(e) {
            e.preventDefault();

            var dept = $("#select_dept").val();
            var position = $("#select_position").val();


            if (dept !== "" || position !== "") {
                $('#records').DataTable().destroy();
                fetch(dept, position);
            }else{
                $('#records').DataTable().destroy();
                fetch();
            }

            });

        // Reset Filter

        $(document).on("click", "#reset", function(e) {
            e.preventDefault();

            $("#select_dept").html(`<option value="">Choose Department...</option>`);
            $("#select_position").html(`<option value="">Choose Position...</option>`);

            $('#records').DataTable().destroy();
            fetch();
            fetch_dept();
            fetch_position();
        });


    function deleteme(delid)
    {
        if(confirm("Do you want to Delete this Record?")){
            window.location.href='delete.php?del_id=' +delid+'';
            return true;
        }
    }

    // let table = new DataTable('#records');

    // Display DTR Modal
    $('#records').on('click', '.editbtns', function(event) {
        var table = $('#records').DataTable();
        var trid = $(this).closest('tr').attr('id');
            //console.log(selectedRow);
        var id = $(this).data('id');
        $('#editModal').modal('show');

        $.ajax({
            url: "get_single_employees_dtr.php",
            data: {
            id: id
            },
            type: 'post',
            success: function(data) {
            var json = JSON.parse(data);
            $('#fullname').val(json.fullname);
            $('#id').val(id);
            //   $('#trid').val(trid);
            }
        });
        });


    // Display View Modal
    $('#records').on('click', '.viewbtn', function(event) {
        var table = $('#records').DataTable();
        var trid = $(this).closest('tr').attr('id');
            //console.log(selectedRow);
        var id = $(this).data('id');
        $('#viewModal').modal('show');

        $.ajax({
            url: "get_single_employees_dtr.php",
            data: {
            id: id
            },
            type: 'post',
            success: function(data) {
            var json = JSON.parse(data);
            $('#fullnames').val(json.fullname);
            // $('#pix').attr('src', json.imagePath);
            $('#ids').val(id);
            //   $('#trid').val(trid);
            }
        });
        });
</script>




<!-- <script type="text/javascript" src="js/datatables/datatables.min.js"></script> -->

<!-- modal -->
<!-- <script src="js/bootstrap.bundle.min.js" crossorigin="anonymous"></script> -->
<!-- <script src="js/datatables/dataTables.fixedColumns.min.js"></script> -->

<?php
include('includes/footer.php');
?>
