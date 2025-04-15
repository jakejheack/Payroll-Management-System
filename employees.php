<?php
session_start();

include_once("connection/cons.php");
$con = conns();

// departments
$sql_dept = "SELECT * FROM departments ORDER BY dept ASC";
$dan_dept = $con->query($sql_dept) or die ($con->error);
$row_dept = $dan_dept->fetch_assoc();
$count_dept = mysqli_num_rows($dan_dept);

// save number of employees
do{
    $id_dept = $row_dept['ID'];
    $depts = $row_dept['dept'];
    $depts = str_replace("'", "\'", $depts);

    $sql_m_dept = "SELECT gender,dept FROM employees where gender = 'Male' && dept = '$depts'";
    $dan_m_dept = $con->query($sql_m_dept) or die ($con->error);
    $row_m_dept = $dan_m_dept->fetch_assoc();
    $count_m_dept = mysqli_num_rows($dan_m_dept);

    $sql_f_dept = "SELECT gender,dept FROM employees where gender = 'Female' && dept = '$depts'";
    $dan_f_dept = $con->query($sql_f_dept) or die ($con->error);
    $row_f_dept = $dan_f_dept->fetch_assoc();
    $count_f_dept = mysqli_num_rows($dan_f_dept);

    // departments
    $sql_dept_update = "UPDATE `departments` SET `males` = '$count_m_dept', `females` = '$count_f_dept' WHERE `ID` = '$id_dept'";
    $dan_dept_update = $con->query($sql_dept_update) or die ($con->error);

}while($row_dept = $dan_dept->fetch_assoc());

// header
include("includes/header.php");
?>
<script>
    function archiveFunction() {
        // Get the checkbox
        var checkBox = document.getElementById("Yes");
        var text = document.getElementById("archive_remarks");

            // If the checkbox is checked, display the output text
            if (checkBox.checked == true){
                document.getElementById('archive_member').disabled = this.checked;
                text.style.display = "block";
            } else {
                document.getElementById('archive_member').disabled = !this.checked;
                text.style.display = "none";
            }
    }
</script>


<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">List of Employees</h1>
      </div>

        <div class="container table-responsive">

        <?php
            if(isset($_SESSION['status'])){
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>&#10004; '.$_SESSION['status'].'</strong> 
                    <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
                unset($_SESSION['status']);
            }
            if(isset($_SESSION['status_sched'])){
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>&#10004; '.$_SESSION['status_sched'].'</strong> 
                    <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>';
                unset($_SESSION['status_sched']);
            }
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

                <?php
                if($_SESSION['Access'] == 'HRD')
                {
                ?>

                    <div class="row" >
                        <div class="" style="width:40%;">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="inputGroupSelect01" style="font-size: 0.7rem;">Department</label>
                                </div>
                                        <select class="custom-select"  id="select_dept" style="font-size: 0.7rem;">
                                        <option value="">Choose Department...</option>
                                        </select>                               
                            </div>
                        </div>
                        <div class="" style="width:40%;  padding: 0 10px ">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="inputGroupSelect01" style="font-size: 0.7rem;">Position</label>
                                </div>
                                <select class="custom-select" id="select_position" style="font-size: 0.7rem;">
                                    <option value="">Choose Position...</option>
                                </select>
                            </div>
                        </div>
                        <div style="width:20%; text-align:right">
                            <button id="filter" class="btn btn-sm btn-info" style="font-size: 0.7rem;">Filter</button>
                            <button id="reset" class="btn btn-sm btn-warning" style="font-size: 0.7rem;">Reset Filter</button>
                        </div>
                    </div>

                <?php
                }
                ?>
                <p></p>

                <div id="searches"  class="table-responsive">
                    <table id="records" class="table table-striped table-hover" style="width: 100%;">
                        <thead>
                            <th>Employee's Personal Information</th>
                            <th>Position & Department</th>
                            <th>Address & Contact #</th>
                        </thead>
                        <tbody>
                             <tr>
                                <th colspan="3">
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
                            </tr> 
                        </tbody>
                    </table>
                </div>
            </div>


<div class="modal fade bd-example-modal-lgs" id="views" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="view_employee.php" target="new_popup" onsubmit="window.open('about:blank','new_popup','width=500,height=1000');" method="post">
            <div class="modal-header">
                <h4 class="modal-title">Employee's Personal Information</h4>
                <div class="" style="margin-left:20px">
                    <input type="hidden" name="emp_id" id="emp_id" value="">
                    <input type="submit" class="btn btn-primary btn-sm" value="Edit Info" name="edit">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Delete Employee's Info
                    </button>
                </div>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="inputName4">ID #</label>
                                <input type="text" class="form-control  form-control-sm" name="" id="employee_id" disabled required>
                        </div>
                        <div class="form-group col-md-9">
                            <label for="inputName4">Name</label>
                                <input type="text" class="form-control  form-control-sm" name="" id="fullname" disabled required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md">
                            <label for="inputName4">Position</label>
                                <input type="text" class="form-control  form-control-sm" name="" id="position" disabled required>
                        </div>
                        <div class="form-group col-md">
                            <label for="inputName4">Job Status</label>
                                <input type="text" class="form-control  form-control-sm" name="" id="job_status" disabled required>
                        </div>
                        <div class="form-group col-md">
                            <label for="inputName4">Department</label>
                                <input type="text" class="form-control  form-control-sm" name="" id="department" disabled required>
                        </div>
                        <div class="form-group col-md">
                            <label for="inputName4">Payroll Department</label>
                                <input type="text" class="form-control  form-control-sm" name="" id="payroll_dept" disabled required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="inputName4">Home Address</label>
                                <input type="text" class="form-control  form-control-sm" name="" id="address" disabled required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="inputName4">Contact #</label>
                                <input type="text" class="form-control  form-control-sm" name="" id="contact_no" disabled required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="inputName4">Birthdate</label>
                                <input type="text" class="form-control  form-control-sm" name="" id="bdate" disabled required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="inputName4">Gender</label>
                                <input type="text" class="form-control  form-control-sm" name="" id="gender" disabled required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="inputPosition4">Civil Status</label>
                                <input type="text" class="form-control  form-control-sm" name="" id="civil_status" disabled required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-5">
                            <label for="inputName4">Name of Spouse</label>
                                <input type="text" class="form-control  form-control-sm" name="" id="spouse" disabled required>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="inputPosition4">No. of Child</label>
                                <input type="text" class="form-control  form-control-sm" name="" id="child" disabled required>
                        </div>
                        <div class="form-group col-md-5">
                            <label for="inputName4">Name of Beneficiary</label>
                                <input type="text" class="form-control  form-control-sm" name="" id="beneficiary" disabled required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="inputName4">Educational Background</label>
                                <input type="text" class="form-control  form-control-sm" name="" id="educ" disabled required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="inputPosition2">PAG-IBIG no.</label>
                                <input type="text" class="form-control  form-control-sm" name="" id="pagibig" disabled required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="inputName2">PHILHEALTH no.</label>
                                <input type="text" class="form-control  form-control-sm" name="" id="philhealth" disabled required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="inputName2">SSS no.</label>
                                <input type="text" class="form-control  form-control-sm" name="" id="sss" disabled required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="inputName2">GSIS no.</label>
                                <input type="text" class="form-control  form-control-sm" name="" id="gsis" disabled required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="inputPosition2">TIN no.</label>
                                <input type="text" class="form-control  form-control-sm" name="" id="tin" disabled required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="inputName4">In Case of Emergency</label>
                                <input type="text" class="form-control  form-control-sm" name="" id="emergency_name" disabled required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="inputName4"></label>
                                <input type="text" class="form-control  form-control-sm" name="" id="emergency_contact" disabled required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="inputPosition4"></label>
                                <input type="text" class="form-control  form-control-sm" name="" id="emergency_address" disabled required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="inputName4">Date hired</label>
                                <input type="text" class="form-control  form-control-sm" name="" id="hired" disabled required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="inputName4">Rate per Hour</label>
                                <input type="text" class="form-control  form-control-sm" name="" id="rate" disabled required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="inputPosition4">Allowance</label>
                                <input type="text" class="form-control  form-control-sm" name="" id="allowance" disabled required>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

        <!-- delete modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Employee's Information</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="employees_archive.php" method="post">
                <div class="modal-body">
                        <div class="form-row">
                            <div class="">
                                <label for="">Do you want to Delete this Record? </label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="flexRadioDefault" onclick="archiveFunction()" id="Yes" style="cursor: pointer;">
                                    <label class="form-check-label" for="Yes" id="Yes" style="cursor: pointer;">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="flexRadioDefault" onclick="archiveFunction()" id="No" checked id="Yes" style="cursor: pointer;">
                                    <label class="form-check-label" for="No" id="Yes" style="cursor: pointer;">No</label>
                                </div>
                            </div>
                        </div>
                        <div id="archive_remarks" class="form-row" style="display: none;">
                            <div class="">
                                <strong class="text-danger">Remarks:  </strong>
                                <input type="text" class="form-control" name="archive_remarks" id="" required>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="archive_id" id="archive_id">
                    <input type="hidden" name="archive_employee_id_no" id="archive_employee_id_no">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <input type="submit" value="Delete" name="archive_member" id="archive_member" class="btn btn-danger" disabled>
                </div>
                </form>
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

            // Fetch records

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
                        targets: [0,1], className: 'dt-left',
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

            // $('#records').DataTable().destroy();
            // fetch();
            fetch_dept();
            fetch_position();

            var dept = "";
            var position = "";

            $('#records').DataTable().destroy();
            fetch(dept, position);
        });


    function deleteme(delid)
    {
        if(confirm("Do you want to Delete this Record?")){
            window.location.href='delete.php?del_id=' +delid+'';
            return true;
        }
    }

    // let table = new DataTable('#records');

    // Display Schedule Modal
    $('#records').on('click', '.schedbtn', function(event) {
        var table = $('#records').DataTable();
        var trid = $(this).closest('tr').attr('id');
            //console.log(selectedRow);
        var id = $(this).data('id');
        $('#views').modal('show');

        $.ajax({
            url: "get_single_employees_dtr.php",
            data: {
            id: id
            },
            type: 'post',
            success: function(data) {
            var json = JSON.parse(data);
            $('#emp_id').val(id);
            $('#archive_id').val(id);
            $('#fullname').val(json.fullnames);
            $('#position').val(json.position);
            $('#employee_id').val(json.emp_id);
            $('#archive_employee_id_no').val(json.emp_id);
            $('#job_status').val(json.job_status);
            $('#payroll_dept').val(json.payroll_dept);
            $('#department').val(json.dept);
            $('#address').val(json.address);
            $('#contact_no').val(json.contact_no);
            $('#bdate').val(json.bdate);
            $('#gender').val(json.gender);
            $('#civil_status').val(json.civil_status);
            $('#spouse').val(json.spouse);
            $('#child').val(json.no_of_child);
            $('#beneficiary').val(json.beneficiary);
            $('#educ').val(json.educ_background);
            $('#pagibig').val(json.pagibig);
            $('#philhealth').val(json.philhealth);
            $('#sss').val(json.sss);
            $('#gsis').val(json.gsis);
            $('#tin').val(json.tin);
            $('#emergency_name').val(json.emergency_name);
            $('#emergency_address').val(json.emergency_address);
            $('#emergency_contact').val(json.emergency_contact);
            $('#hired').val(json.date_hired);
            $('#rate').val(json.rates);
            $('#allowance').val(json.allowance);
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
            $('#pix').attr('src', json.imagePath);
            $('#ids').val(id);
            //   $('#trid').val(trid);
            }
        });
        });

</script>

<?php
include("includes/footer.php");
?>
