<?php
session_start();

include_once("connection/cons.php");
$con = conns();

date_default_timezone_set("Asia/Manila");

if(isset($_SESSION['employees_dtr_from']) && isset($_SESSION['employees_dtr_to'])){
    $from = date('Y-m-d', strtotime($_SESSION['employees_dtr_from']));
    $to = date('Y-m-d', strtotime($_SESSION['employees_dtr_to']));
}else{
    $from = date('Y-m-d');
    $to = date('Y-m-d');
}


// header
include("includes/header.php");

?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h3 class="h4">Employees DTR</h3>
      </div>

        <div class="container table-responsive">

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
                        <div style="width:20%;">
                                    <button id="filter" class="btn btn-sm btn-info" style="font-size: 0.7rem;">Filter</button>
                                    <button id="reset" class="btn btn-sm btn-warning" style="font-size: 0.7rem;">Reset Filter</button>
                        </div>
                    </div>
                    <div class="row" >
                        <div class="" style="width:40%;">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="inputGroupSelect01" style="font-size: 0.7rem;">Date from</label>
                                </div>
                                    <input class="form-control" type="date" name="" id="date-from" style="font-size: 0.7rem;" value="<?= $from?>">
                            </div>
                        </div>
                        <div class="" style="width:40%;  padding: 0 10px ">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="inputGroupSelect01" style="font-size: 0.7rem;">Date to</label>
                                </div>
                                <input class="form-control" type="date" name="" id="date-to" style="font-size: 0.7rem;" value="<?= $to?>">
                            </div>
                        </div>
                            <!-- <div>
                                <button id="set-date" class="btn btn-sm btn-primary" style="font-size: 0.7rem;">Set Date</button>
                            </div> -->
                    </div>

                <?php
                }
                ?>

                <div id="searches"  class="table-responsive">
                    <table id="dtr_records" class="table table-striped table-bordered table-hover" style="width: 100%;">
                        <thead>
                            <th>Employee's</th>
                            <th>Position & Department</th>
                            <th>Working Schedule</th>
                            <th>Day off</th>
                        </thead>
                        <tbody>
                            <tr>
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
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
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
<!--  -->
</div>
    </div>
</main>


<div class="modal fade bd-example-modal-lgs" id="schedules" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h5">Working Schedule</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="employees_new_schedule.php" method="post">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <strong class="">Employee's Name</strong>
                                <input type="text" class="form-control form-control-sm" name="names" id="fullname" disabled required>
                            </div>
                            <div class="form-group col-md-4">
                                <strong class="">Position</strong>
                                <input type="text" class="form-control form-control-sm" name="positions" id="position" disabled required>                                    
                            </div>
                        </div>

                        <table class="dataTable table table-striped table-bordered" style="width:100%; margin: 0">
                            <thead style="font-size: 15px;">
                                <tr>
                                    <th colspan="" style="text-align: right"><span>Applicable Date:</span></th>
                                    <th colspan="2">
                                        <div class="input-group">
                                            <span class="input-group-text">From:</span>
                                            <input type="date" name="sched_from" id="sched_from" value="<?= $from?>" class="form-control" required>
                                        </div>
                                    </th>
                                    <th colspan="2">
                                        <div class="input-group">
                                            <span class="input-group-text">To:</span>
                                            <input type="date" name="sched_to" id="sched_to" value="<?= $to?>" class="form-control" required>
                                        </div>
                                    </th>
                                    <th>
                                        <div class="input-group">
                                            <span class="input-group-text">Mins. Break:</span>
                                            <input type="number" name="min_break" id="breaks"  class="form-control" placeholder="# of minutes" value="" style="width: 67px;" required>
                                        </div>    
                                </tr>
                            </thead>                             
                            <thead>
                                <tr>
                                    <th style="text-align: center;">Day</th>
                                    <th style="text-align: center;">IN-1</th>
                                    <th style="text-align: center;">OUT-1</th>
                                    <th style="text-align: center;">IN-2</th>
                                    <th style="text-align: center;">OUT-2</th>
                                    <th>Work Day Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>Monday</th>
                                    <td><input type="time" name="mon_in1" id="mon_in1"  class="form-control form-control-sm" value=""></td>
                                    <td><input type="time" name="mon_out1" id="mon_out1"  class="form-control form-control-sm" value=""></td>
                                    <td><input type="time" name="mon_in2" id="mon_in2"  class="form-control form-control-sm" value=""></td>
                                    <td><input type="time" name="mon_out2" id="mon_out2"  class="form-control form-control-sm" value=""></td>
                                    <td><select name="mon_on" id="mon_on" class="custom-select custom-select-sm" required>
                                        <option value="" selected hidden></option>
                                        <option value="ON">Active</option>
                                        <option value="OFF">Day off</option>
                                    </select></td>
                                </tr>
                                <tr>
                                    <th>Tuesday</th>
                                    <td><input type="time" name="tue_in1" id="tue_in1"  class="form-control form-control-sm" value=""></td>
                                    <td><input type="time" name="tue_out1" id="tue_out1"  class="form-control form-control-sm" value=""></td>
                                    <td><input type="time" name="tue_in2" id="tue_in2"  class="form-control form-control-sm" value=""></td>
                                    <td><input type="time" name="tue_out2" id="tue_out2"  class="form-control form-control-sm" value=""></td>
                                    <td><select name="tue_on" id="tue_on" class="custom-select custom-select-sm" required>
                                        <option value="" selected hidden></option>
                                        <option value="ON">Active</option>
                                        <option value="OFF">Day off</option>
                                    </select></td>
                                </tr>
                                <tr>
                                    <th>Wednesday</th>
                                    <td><input type="time" name="wed_in1" id="wed_in1"  class="form-control form-control-sm" value=""></td>
                                    <td><input type="time" name="wed_out1" id="wed_out1"  class="form-control form-control-sm" value=""></td>
                                    <td><input type="time" name="wed_in2" id="wed_in2"  class="form-control form-control-sm" value=""></td>
                                    <td><input type="time" name="wed_out2" id="wed_out2"  class="form-control form-control-sm" value=""></td>
                                    <td><select name="wed_on" id="wed_on" class="custom-select custom-select-sm" required>
                                        <option value="" selected hidden></option>
                                        <option value="ON">Active</option>
                                        <option value="OFF">Day off</option>
                                    </select></td>
                                </tr>
                                <tr>
                                    <th>Thursday</th>
                                    <td><input type="time" name="thu_in1" id="thu_in1"  class="form-control form-control-sm" value=""></td>
                                    <td><input type="time" name="thu_out1" id="thu_out1"  class="form-control form-control-sm" value=""></td>
                                    <td><input type="time" name="thu_in2" id="thu_in2"  class="form-control form-control-sm" value=""></td>
                                    <td><input type="time" name="thu_out2" id="thu_out2"  class="form-control form-control-sm" value=""></td>
                                    <td><select name="thu_on" id="thu_on" class="custom-select custom-select-sm" required>
                                        <option value="" selected hidden></option>
                                        <option value="ON">Active</option>
                                        <option value="OFF">Day off</option>
                                    </select></td>
                                </tr>
                                <tr>
                                    <th>Friday</th>
                                    <td><input type="time" name="fri_in1" id="fri_in1"  class="form-control form-control-sm" value=""></td>
                                    <td><input type="time" name="fri_out1" id="fri_out1"  class="form-control form-control-sm" value=""></td>
                                    <td><input type="time" name="fri_in2" id="fri_in2"  class="form-control form-control-sm" value=""></td>
                                    <td><input type="time" name="fri_out2" id="fri_out2"  class="form-control form-control-sm" value=""></td>
                                    <td><select name="fri_on" id="fri_on" class="custom-select custom-select-sm" required>
                                        <option value="" selected hidden></option>
                                        <option value="ON">Active</option>
                                        <option value="OFF">Day off</option>
                                    </select></td>
                                </tr>
                                <tr>
                                    <th>Saturday</th>
                                    <td><input type="time" name="sat_in1" id="sat_in1"  class="form-control form-control-sm" value=""></td>
                                    <td><input type="time" name="sat_out1" id="sat_out1"  class="form-control form-control-sm" value=""></td>
                                    <td><input type="time" name="sat_in2" id="sat_in2"  class="form-control form-control-sm" value=""></td>
                                    <td><input type="time" name="sat_out2" id="sat_out2"  class="form-control form-control-sm" value=""></td>
                                    <td><select name="sat_on" id="sat_on" class="custom-select custom-select-sm" required>
                                        <option value="" selected hidden></option>
                                        <option value="ON">Active</option>
                                        <option value="OFF">Day off</option>
                                    </select></td>
                                </tr>
                                <tr>
                                    <th>Sunday</th>
                                    <td><input type="time" name="sun_in1" id="sun_in1"  class="form-control form-control-sm" value=""></td>
                                    <td><input type="time" name="sun_out1" id="sun_out1"  class="form-control form-control-sm" value=""></td>
                                    <td><input type="time" name="sun_in2" id="sun_in2"  class="form-control form-control-sm" value=""></td>
                                    <td><input type="time" name="sun_out2" id="sun_out2"  class="form-control form-control-sm" value=""></td>
                                    <td><select name="sun_on" id="sun_on" class="custom-select custom-select-sm" required>
                                        <option value="" selected hidden></option>
                                        <option value="ON">Active</option>
                                        <option value="OFF">Day off</option>
                                    </select></td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="mb-3" style="text-align: right;">
                            <input type="hidden" name="emp_id" id="emp_id" value="">
                            <input type="hidden" name="pages" value="employees_dtr">
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary btn-sm" value="Save Changes" name="update_time">
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    $('#mon_in1').change(function() {
        $('#tue_in1').val($('#mon_in1').val())
        $('#wed_in1').val($('#mon_in1').val())
        $('#thu_in1').val($('#mon_in1').val())
        $('#fri_in1').val($('#mon_in1').val())
        $('#sat_in1').val($('#mon_in1').val())
        $('#sun_in1').val($('#mon_in1').val())
    })

    $('#mon_out1').change(function() {
        $('#tue_out1').val($('#mon_out1').val())
        $('#wed_out1').val($('#mon_out1').val())
        $('#thu_out1').val($('#mon_out1').val())
        $('#fri_out1').val($('#mon_out1').val())
        $('#sat_out1').val($('#mon_out1').val())
        $('#sun_out1').val($('#mon_out1').val())
    })

    $('#mon_in2').change(function() {
        $('#tue_in2').val($('#mon_in2').val())
        $('#wed_in2').val($('#mon_in2').val())
        $('#thu_in2').val($('#mon_in2').val())
        $('#fri_in2').val($('#mon_in2').val())
        $('#sat_in2').val($('#mon_in2').val())
        $('#sun_in2').val($('#mon_in2').val())
    })

    $('#mon_out2').change(function() {
        $('#tue_out2').val($('#mon_out2').val())
        $('#wed_out2').val($('#mon_out2').val())
        $('#thu_out2').val($('#mon_out2').val())
        $('#fri_out2').val($('#mon_out2').val())
        $('#sat_out2').val($('#mon_out2').val())
        $('#sun_out2').val($('#mon_out2').val())
    })




    $(document).ready(function(){

        // Check/Uncheck ALl
        $('#checkAll').change(function(){
        if($(this).is(':checked')){
            $('input[name="update[]"]').prop('checked',true);
        }else{
            $('input[name="update[]"]').each(function(){
            $(this).prop('checked',false);
            });
        }
        });

          // Checkbox click
        $('input[name="update[]"]').click(function(){
            var total_checkboxes = $('input[name="update[]"]').length;
            var total_checkboxes_checked = $('input[name="update[]"]:checked').length;

            if(total_checkboxes_checked == total_checkboxes){
            $('#checkAll').prop('checked',true);
            }else{
            $('#checkAll').prop('checked',false);
            }
        });
    });


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

            // Fetch dtr_Records

        function fetch_dtr(dept, position) {
        $.ajax({
            url: "recording_dtr.php",
            type: "post",
            data: {
                dept: dept,
                position: position
            },
            dataType: "json",
            success: function(data) {
                var i = 0;
                $('#dtr_records').DataTable({
                    "data": data,
                    "responsive": true,
                    "aoColumnDefs": [{
                        // "bSortable": false,
                        targets: [0,1], className: 'dt-left',
                        // "aTargets": [5]
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
        fetch_dtr();

        function fetch_date(date_from, date_to){
            $.ajax({
                url: "create_session.php",
                type: "post",
                data: {
                    date_from: date_from,
                    date_to: date_to
                }
            })
        }

         // Set Date

         $(document).on("click", "#set-date", function(e) {
            e.preventDefault();

            var date_from = $("#date-from").val();
            var date_to = $("#date-to").val();

            fetch_date(date_from, date_to);

            alert("Date has been set.");

        });


        $('#date-from').change(function() {
            var date_from = $("#date-from").val();
            var date_to = $("#date-to").val();

            fetch_date(date_from, date_to);
        });

        $('#date-to').change(function() {
            var date_from = $("#date-from").val();
            var date_to = $("#date-to").val();

            fetch_date(date_from, date_to);
        });

            // Filter

            $(document).on("click", "#filter", function(e) {
            e.preventDefault();

            var dept = $("#select_dept").val();
            var position = $("#select_position").val();

            if (dept !== "" || position !== "") {
                $('#dtr_records').DataTable().destroy();
                fetch_dtr(dept, position);
            }else{
                $('#dtr_records').DataTable().destroy();
                fetch_dtr();
            }

        });

        // Reset Filter

        $(document).on("click", "#reset", function(e) {
            e.preventDefault();

            $("#select_dept").html(`<option value="">Choose Department...</option>`);
            $("#select_position").html(`<option value="">Choose Position...</option>`);

            // $('#dtr_records').DataTable().destroy();
            // fetch_dtr();
            fetch_dept();
            fetch_position();

            var dept = "";
            var position = "";

            $('#dtr_records').DataTable().destroy();
            fetch_dtr(dept, position);
        });


    function deleteme(delid)
    {
        if(confirm("Do you want to Delete this Record?")){
            window.location.href='delete.php?del_id=' +delid+'';
            return true;
        }
    }

    // let table = new DataTable('#dtr_records');

    // Display Schedule Modal
    $('#dtr_records').on('click', '.schedbtn', function(event) {
        var table = $('#dtr_records').DataTable();
        var trid = $(this).closest('tr').attr('id');
            //console.log(selectedRow);
        var id = $(this).data('id');
        $('#schedules').modal('show');

        $.ajax({
            url: "get_single_employees_dtr.php",
            data: {
            id: id
            },
            type: 'post',
            success: function(data) {
            var json = JSON.parse(data);
            $('#emp_id').val(id);
            $('#fullname').val(json.fullname);
            $('#position').val(json.position);
            $('#mon_on').val(json.mon_on);
            $('#mon_in1').val(json.mon_in1);
            $('#mon_out1').val(json.mon_out1);
            $('#mon_in2').val(json.mon_in2);
            $('#mon_out2').val(json.mon_out2);
            $('#tue_on').val(json.tue_on);
            $('#tue_in1').val(json.tue_in1);
            $('#tue_out1').val(json.tue_out1);
            $('#tue_in2').val(json.tue_in2);
            $('#tue_out2').val(json.tue_out2);
            $('#wed_on').val(json.wed_on);
            $('#wed_in1').val(json.wed_in1);
            $('#wed_out1').val(json.wed_out1);
            $('#wed_in2').val(json.wed_in2);
            $('#wed_out2').val(json.wed_out2);
            $('#thu_on').val(json.thu_on);
            $('#thu_in1').val(json.thu_in1);
            $('#thu_out1').val(json.thu_out1);
            $('#thu_in2').val(json.thu_in2);
            $('#thu_out2').val(json.thu_out2);
            $('#fri_on').val(json.fri_on);
            $('#fri_in1').val(json.fri_in1);
            $('#fri_out1').val(json.fri_out1);
            $('#fri_in2').val(json.fri_in2);
            $('#fri_out2').val(json.fri_out2);
            $('#sat_on').val(json.sat_on);
            $('#sat_in1').val(json.sat_in1);
            $('#sat_out1').val(json.sat_out1);
            $('#sat_in2').val(json.sat_in2);
            $('#sat_out2').val(json.sat_out2);
            $('#sun_on').val(json.sun_on);
            $('#sun_in1').val(json.sun_in1);
            $('#sun_out1').val(json.sun_out1);
            $('#sun_in2').val(json.sun_in2);
            $('#sun_out2').val(json.sun_out2);
            $('#breaks').val(json.breaks);
            }
        });
        });


    // Display View Modal
    $('#dtr_records').on('click', '.viewbtn', function(event) {
        var table = $('#dtr_records').DataTable();
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
