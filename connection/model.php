<?php

session_start();


class Model
{
    private $server = "localhost";
    private $username = "root";
    private $password = "";
    private $db = "dtrdbs";
    private $conn;

    public function __construct()
    {
        try {
            $this->conn = new mysqli($this->server, $this->username, $this->password, $this->db);
        } catch (\Throwable $th) {
            //throw $th;
            echo "Connection error " . $th->getMessage();
        }
    }

    // Fetch Address

    public function fetch_dept()
    {
        $data = [];

        $query = "SELECT DISTINCT `dept` FROM `departments` ORDER BY `dept` ASC";
        if ($sql = $this->conn->query($query)) {
            while ($row = mysqli_fetch_assoc($sql)) {
                $data[] = $row;
            }
        }

        return $data;
    }

    // Fetch Gender

    public function fetch_position()
    {
        $data = [];

        $query = "SELECT DISTINCT `pos` FROM `positions` ORDER BY `pos` ASC";
        if ($sql = $this->conn->query($query)) {
            while ($row = mysqli_fetch_assoc($sql)) {
                $data[] = $row;
            }
        }

        return $data;
    }

    
    // Fetch Records

    public function fetch()
    {
        // $data = [];
        $data = array();
        $x = 0;

        if(empty($_SESSION['selected_dept']) && empty($_SESSION['selected_position'])){
            $query = "SELECT * FROM employees ORDER BY emp_id ASC";
        }elseif(isset($_SESSION['selected_dept']) && isset($_SESSION['selected_position'])){
            $dept = $_SESSION['selected_dept'];
            $position = $_SESSION['selected_position'];

            $dept = str_replace("'", "\'", $dept);
            $position = str_replace("'", "\'", $position);

            $query = "SELECT * FROM employees WHERE dept LIKE '$dept%' && position LIKE '$position%' ORDER BY emp_id ASC";
        }

        if ($sql = $this->conn->query($query)) {
            while ($row = mysqli_fetch_assoc($sql)) {
                $x++;

                $pix = $row['pictures'];

                // $data[] = $row;
                $sub_array = array();
                                                                                         
                if(empty($pix))
                {
                    $sub_array[] = '<a href=javascript:void(); data-id="'.$row['ID'].'" style="width:100%" class="schedbtn">
                                    <div class="float-left" style="width:30%"><img src="img/blank.jpg" alt="" width="57px" height="57px" class="rounded-circle"></div><div style="width:70%" class="float-right">'.
                                    $row['emp_id'].'<br><strong  class="text-body">'.$row['lname'].', '.$row['fname'].' '.substr($row['mname'],0,1).' '.$row['extname'].'</strong></div></a>';
                }elseif(!empty($pix) && $pix != '---')
                {
                    $sub_array[] = '<a href=javascript:void(); data-id="'.$row['ID'].'" style="width:100%" class="schedbtn">
                                    <div class="float-left" style="width:30%"><img src="data:image/jpg;charset=utf8;base64,'.base64_encode($pix).'" width="57px" height="57px" class="rounded-circle"/></div><div style="width:70%;" class="float-right">'.
                                    $row['emp_id'].'<br><strong  class="text-body">'.$row['lname'].', '.$row['fname'].' '.substr($row['mname'],0,1).' '.$row['extname'].'</strong></div></a>';
                }
                $sub_array[] = '<strong style="color: ">'.$row['position'].'</strong><br>'.$row['dept'];
                $sub_array[] = '<strong style="color: ">'.$row['address'].'</strong><br>'.$row['contact_no'];
                $data[] = $sub_array;
            }
        }

        return $data;
    }

    // Filter

    public function fetch_filter($dept, $position)
    {
        $_SESSION['selected_dept'] = $dept;
        $_SESSION['selected_position'] = $position;
        
        $dept = str_replace("'", "\'", $dept);
        $position = str_replace("'", "\'", $position);

        $query = "SELECT * FROM employees WHERE dept LIKE '$dept%' && position LIKE '$position%' ORDER BY emp_id ASC";

        // $data = [];
        $data = array();
        $x = 0;

        if ($sql = $this->conn->query($query)) {
            while ($row = mysqli_fetch_assoc($sql)) {
                $x++;

                $pix = $row['pictures'];

                $sub_array = array();
                if(empty($pix))
                {
                    $sub_array[] = '<a href=javascript:void(); data-id="'.$row['ID'].'" style="width:100%" class="schedbtn">
                                    <div class="float-left" style="width:30%"><img src="img/blank.jpg" alt="" width="57px" height="57px" class="rounded-circle"></div><div style="width:70%" class="float-right">'.
                                    $row['emp_id'].'<br><strong  class="text-body">'.$row['lname'].', '.$row['fname'].' '.substr($row['mname'],0,1).' '.$row['extname'].'</strong></div></a>';
                }elseif(!empty($pix) && $pix != '---')
                {
                    $sub_array[] = '<a href=javascript:void(); data-id="'.$row['ID'].'" style="width:100%" class="schedbtn">
                                    <div class="float-left" style="width:30%"><img src="data:image/jpg;charset=utf8;base64,'.base64_encode($pix).'" width="57px" height="57px" class="rounded-circle"/></div><div style="width:70%;" class="float-right">'.
                                    $row['emp_id'].'<br><strong  class="text-body">'.$row['lname'].', '.$row['fname'].' '.substr($row['mname'],0,1).' '.$row['extname'].'</strong></div></a>';
                }
                $sub_array[] = '<strong style="color: ">'.$row['position'].'</strong><br>'.$row['dept'];
                $sub_array[] = '<strong style="color: ">'.$row['address'].'</strong><br>'.$row['contact_no'];
                $data[] = $sub_array;
            }
        }

        return $data;
    }



    // employees dtr
        // Fetch Records

        public function fetch_dtr()
        {
            // $data = [];
            $data = array();
            $x = 0;

            if(empty($_SESSION['selected_dept']) && empty($_SESSION['selected_position'])){
                $query = "SELECT * FROM employees ORDER BY emp_id ASC";
            }elseif(isset($_SESSION['selected_dept']) && isset($_SESSION['selected_position'])){
                $dept = $_SESSION['selected_dept'];
                $position = $_SESSION['selected_position'];

                $dept = str_replace("'", "\'", $dept);
                $position = str_replace("'", "\'", $position);
    
                $query = "SELECT * FROM employees WHERE dept LIKE '$dept%' && position LIKE '$position%' ORDER BY emp_id ASC";
            }
    
            if ($sql = $this->conn->query($query)) {
                while ($row = mysqli_fetch_assoc($sql)) {
                    $x++;
    
                    $pix = $row['pictures'];
    
                    $emp_id = $row['ID'];

                    // schedules    
                    $sql_sched = "SELECT * FROM schedules WHERE emp_id = '$emp_id'";
                    $dan_sched = $this->conn->query($sql_sched);
                    $row_sched = $dan_sched->fetch_assoc();
                    $count_sched = mysqli_num_rows($dan_sched);

                    $i = 0;
                    $breaks = '';

                    if($count_sched > 0){
                        $mon = $row_sched['mon'];
                        $tue = $row_sched['tue'];
                        $wed = $row_sched['wed'];
                        $thu = $row_sched['thu'];
                        $fri = $row_sched['fri'];
                        $sat = $row_sched['sat'];
                        $sun = $row_sched['sun'];
                        $breaks = $row_sched['min_break'];

                        // breaks
                        if(!empty($breaks)){
                            $breaks = $breaks.' mins. break';
                        }

                        $sched = [];

                        if($mon == 'ON'){
                            $i = $i + 1;
                            $sched[$i] = $row_sched['mon_in1'].'-'.$row_sched['mon_out1'].' / '.$row_sched['mon_in2'].'-'.$row_sched['mon_out2'];
                        }
                        if($tue == 'ON'){
                            $i = $i + 1;
                            $sched[$i] = $row_sched['tue_in1'].'-'.$row_sched['tue_out1'].' / '.$row_sched['tue_in2'].'-'.$row_sched['tue_out2'];
                        }
                        if($wed == 'ON'){
                            $i = $i + 1;
                            $sched[$i] = $row_sched['wed_in1'].'-'.$row_sched['wed_out1'].' / '.$row_sched['wed_in2'].'-'.$row_sched['wed_out2'];
                        }
                        if($thu == 'ON'){
                            $i = $i + 1;
                            $sched[$i] = $row_sched['thu_in1'].'-'.$row_sched['thu_out1'].' / '.$row_sched['thu_in2'].'-'.$row_sched['thu_out2'];
                        }
                        if($fri == 'ON'){
                            $i = $i + 1;
                            $sched[$i] = $row_sched['fri_in1'].'-'.$row_sched['fri_out1'].' / '.$row_sched['fri_in2'].'-'.$row_sched['fri_out2'];
                        }
                        if($sat == 'ON'){
                            $i = $i + 1;
                            $sched[$i] = $row_sched['sat_in1'].'-'.$row_sched['sat_out1'].' / '.$row_sched['sat_in2'].'-'.$row_sched['sat_out2'];
                        }
                        if($sun == 'ON'){
                            $i = $i + 1;
                            $sched[$i] = $row_sched['sun_in1'].'-'.$row_sched['sun_out1'].' / '.$row_sched['sun_in2'].'-'.$row_sched['sun_out2'];
                        }

                        $scheds_count = count(array_unique($sched));

                    }else{
                        $mon = 'ON';
                        $tue = 'ON';
                        $wed = 'ON';
                        $thu = 'ON';
                        $fri = 'ON';
                        $sat = 'ON';
                        $sun = 'ON';

                        $scheds_count = 0;
                        $sched = [];
                    }

                    if($scheds_count > 0){
                        $sched = array_unique($sched);
                    }

                    $day_off = "";

                    if($mon == 'OFF'){
                        $day_off = "Monday";
                    }

                    if($tue == 'OFF'){
                        (!empty($day_off) ? $day_off = $day_off.'/' : '');
                        $day_off = $day_off."Tuesday";
                    }

                    if($wed == 'OFF'){
                        (!empty($day_off) ? $day_off = $day_off.'/' : '');
                        $day_off = $day_off."Wednesday";
                    }

                    if($thu == 'OFF'){
                        (!empty($day_off) ? $day_off = $day_off.'/' : '');
                        $day_off = $day_off."Thursday";
                    }

                    if($fri == 'OFF'){
                        (!empty($day_off) ? $day_off = $day_off.'/' : '');
                        $day_off = $day_off."Friday";
                    }

                    if($sat == 'OFF'){
                        (!empty($day_off) ? $day_off = $day_off.'/' : '');
                        $day_off = $day_off."Saturday";
                    }

                    if($sun == 'OFF'){
                        (!empty($day_off) ? $day_off = $day_off.'/' : '');
                        $day_off = $day_off."Sunday";
                    }

                    // $data[] = $row;
                    $sub_array = array();

                    // $sub_array[] = '<input type="checkbox" name="update[]" value="'.$row['ID'].'" data-userid="'.$row['ID'].'" style="cursor:pointer">';
                                                                                             
                    // if(empty($pix))
                    // {
                    //     $sub_array[] = '<a href="daily_time_record_employees.php?ID='.$row['ID'].'" class="float-left">
                    //                     <img src="img/blank.jpg" alt="" width="57px" height="57px" class="rounded-circle"></a><div style="margin-left:50px; margin-top: 10px">'.
                    //                     $row['emp_id'].'<br><strong style="color:#06486D; ">'.$row['lname'].', '.$row['fname'].' '.substr($row['mname'],0,1).' '.$row['extname'].'</strong></div>';
                    // }elseif(!empty($pix) && $pix != '---')
                    // {
                    //     $sub_array[] = '<a href="daily_time_record_employees.php?ID='.$row['ID'].'" class="float-left" >
                    //                     <img src="data:image/jpg;charset=utf8;base64,'.base64_encode($pix).'" width="57px" height="57px" class="rounded-circle"/></a><div style="margin-left:50px; margin-top:10px">'.
                    //                     $row['emp_id'].'<br><strong style="color:#06486D; ">'.$row['lname'].', '.$row['fname'].' '.substr($row['mname'],0,1).' '.$row['extname'].'</strong></div>';
                    // }
                    $sub_array[] = '<a href="daily_time_record_employees.php?ID='.$row['ID'].'" class="float-left text-secondary" style="text-decoration:none">'.
                                    $row['emp_id'].'<br><strong class="text-primary" ">'.$row['lname'].', '.$row['fname'].' '.substr($row['mname'],0,1).' '.$row['extname'].'</strong></a>';
                    $sub_array[] = '<strong style="color:#8C1F8E; ">'.$row['position'].'</strong><br>'.$row['dept'];
                    $sub_array[] = '<a href=javascript:void();" data-id="'.$row['ID'].'"'.(!empty($sched) ? ' class="btn text-active btn-link schedbtn" >'.implode("<br>",$sched).'<br>'.$breaks : ' class="btn btn-link schedbtn text-danger" >*** W/out Schedule ***').'</a>';
                    $sub_array[] = (!empty($day_off) ? $day_off : '<span class="text-danger">- - - - - - -</span>');
                    // $sub_array[] = '<a href="daily_time_record_employees.php?ID='.$row['ID'].'" class="btn btn-info btn-sm">DTR</a>';
                    $data[] = $sub_array;
                }
            }
            return $data;
        }
    
        // Filter
    
        public function fetch_filter_dtr($dept, $position)
        {
            $_SESSION['selected_dept'] = $dept;
            $_SESSION['selected_position'] = $position;

            $dept = str_replace("'", "\'", $dept);
            $position = str_replace("'", "\'", $position);
    
            $query = "SELECT * FROM employees WHERE dept LIKE '$dept%' && position LIKE '$position%' ORDER BY emp_id ASC";
    
            // $data = [];
            $data = array();
            $x = 0;
    
            if ($sql = $this->conn->query($query)) {
                while ($row = mysqli_fetch_assoc($sql)) {
                    $x++;
    
                    $pix = $row['pictures'];
    
                    $emp_id = $row['ID'];

                    // schedules    
                    $sql_sched = "SELECT * FROM schedules WHERE emp_id = '$emp_id'";
                    $dan_sched = $this->conn->query($sql_sched);
                    $row_sched = $dan_sched->fetch_assoc();
                    $count_sched = mysqli_num_rows($dan_sched);

                    $i = 0;
                    $breaks = '';

                    if($count_sched > 0){
                        $mon = $row_sched['mon'];
                        $tue = $row_sched['tue'];
                        $wed = $row_sched['wed'];
                        $thu = $row_sched['thu'];
                        $fri = $row_sched['fri'];
                        $sat = $row_sched['sat'];
                        $sun = $row_sched['sun'];
                        $breaks = $row_sched['min_break'];

                        // breaks
                        if(!empty($breaks)){
                            $breaks = $breaks.' mins. break';
                        }

                        $sched = [];

                        if($mon == 'ON'){
                            $i = $i + 1;
                            $sched[$i] = $row_sched['mon_in1'].'-'.$row_sched['mon_out1'].' / '.$row_sched['mon_in2'].'-'.$row_sched['mon_out2'];
                        }
                        if($tue == 'ON'){
                            $i = $i + 1;
                            $sched[$i] = $row_sched['tue_in1'].'-'.$row_sched['tue_out1'].' / '.$row_sched['tue_in2'].'-'.$row_sched['tue_out2'];
                        }
                        if($wed == 'ON'){
                            $i = $i + 1;
                            $sched[$i] = $row_sched['wed_in1'].'-'.$row_sched['wed_out1'].' / '.$row_sched['wed_in2'].'-'.$row_sched['wed_out2'];
                        }
                        if($thu == 'ON'){
                            $i = $i + 1;
                            $sched[$i] = $row_sched['thu_in1'].'-'.$row_sched['thu_out1'].' / '.$row_sched['thu_in2'].'-'.$row_sched['thu_out2'];
                        }
                        if($fri == 'ON'){
                            $i = $i + 1;
                            $sched[$i] = $row_sched['fri_in1'].'-'.$row_sched['fri_out1'].' / '.$row_sched['fri_in2'].'-'.$row_sched['fri_out2'];
                        }
                        if($sat == 'ON'){
                            $i = $i + 1;
                            $sched[$i] = $row_sched['sat_in1'].'-'.$row_sched['sat_out1'].' / '.$row_sched['sat_in2'].'-'.$row_sched['sat_out2'];
                        }
                        if($sun == 'ON'){
                            $i = $i + 1;
                            $sched[$i] = $row_sched['sun_in1'].'-'.$row_sched['sun_out1'].' / '.$row_sched['sun_in2'].'-'.$row_sched['sun_out2'];
                        }

                        $scheds_count = count(array_unique($sched));

                    }else{
                        $mon = 'ON';
                        $tue = 'ON';
                        $wed = 'ON';
                        $thu = 'ON';
                        $fri = 'ON';
                        $sat = 'ON';
                        $sun = 'ON';

                        $scheds_count = 0;
                        $sched = [];
                    }

                    if($scheds_count > 0){
                        $sched = array_unique($sched);
                    }

                    $day_off = "";

                    if($mon == 'OFF'){
                        $day_off = "Monday";
                    }

                    if($tue == 'OFF'){
                        (!empty($day_off) ? $day_off = $day_off.'/' : '');
                        $day_off = $day_off."Tuesday";
                    }

                    if($wed == 'OFF'){
                        (!empty($day_off) ? $day_off = $day_off.'/' : '');
                        $day_off = $day_off."Wednesday";
                    }

                    if($thu == 'OFF'){
                        (!empty($day_off) ? $day_off = $day_off.'/' : '');
                        $day_off = $day_off."Thursday";
                    }

                    if($fri == 'OFF'){
                        (!empty($day_off) ? $day_off = $day_off.'/' : '');
                        $day_off = $day_off."Friday";
                    }

                    if($sat == 'OFF'){
                        (!empty($day_off) ? $day_off = $day_off.'/' : '');
                        $day_off = $day_off."Saturday";
                    }

                    if($sun == 'OFF'){
                        (!empty($day_off) ? $day_off = $day_off.'/' : '');
                        $day_off = $day_off."Sunday";
                    }

                    // $data[] = $row;
                    $sub_array = array();

                    // $sub_array[] = '<input type="checkbox" name="update[]" value="'.$row['ID'].'" data-userid="'.$row['ID'].'" style="cursor:pointer">';
                                                                                             
                    // if(empty($pix))
                    // {
                    //     $sub_array[] = '<a href="daily_time_record_employees.php?ID='.$row['ID'].'" class="float-left">
                    //                     <img src="img/blank.jpg" alt="" width="57px" height="57px" class="rounded-circle"></a><div style="margin-left:50px; margin-top: 10px">'.
                    //                     $row['emp_id'].'<br><strong style="color:#06486D; ">'.$row['lname'].', '.$row['fname'].' '.substr($row['mname'],0,1).' '.$row['extname'].'</strong></div>';
                    // }elseif(!empty($pix) && $pix != '---')
                    // {
                    //     $sub_array[] = '<a href="daily_time_record_employees.php?ID='.$row['ID'].'" class="float-left" >
                    //                     <img src="data:image/jpg;charset=utf8;base64,'.base64_encode($pix).'" width="57px" height="57px" class="rounded-circle"/></a><div style="margin-left:50px; margin-top:10px">'.
                    //                     $row['emp_id'].'<br><strong style="color:#06486D; ">'.$row['lname'].', '.$row['fname'].' '.substr($row['mname'],0,1).' '.$row['extname'].'</strong></div>';
                    // }
                    $sub_array[] = '<a href="daily_time_record_employees.php?ID='.$row['ID'].'" class="float-left text-secondary" style="text-decoration:none">'.
                                    $row['emp_id'].'<br><strong class="text-primary" ">'.$row['lname'].', '.$row['fname'].' '.substr($row['mname'],0,1).' '.$row['extname'].'</strong></a>';
                    $sub_array[] = '<strong style="color:#8C1F8E; ">'.$row['position'].'</strong><br>'.$row['dept'];
                    $sub_array[] = '<a href=javascript:void();" data-id="'.$row['ID'].'"'.(!empty($sched) ? ' class="btn text-active btn-link schedbtn" >'.implode("<br>",$sched).'<br>'.$breaks : ' class="btn btn-link schedbtn text-danger" >*** W/out Schedule ***').'</a>';
                    $sub_array[] = (!empty($day_off) ? $day_off : '<span class="text-danger">- - - - - - -</span>');
                    // $sub_array[] = '<a href="daily_time_record_employees.php?ID='.$row['ID'].'" class="btn btn-info btn-sm">DTR</a>';
                    $data[] = $sub_array;
                }
            }
    
            return $data;
        }



        // employees deductions
            // Fetch Records

    public function fetch_deduction()
    {
        // $data = [];
        $data = array();
        $x = 0;

        if(empty($_SESSION['selected_dept']) && empty($_SESSION['selected_position'])){
            $query = "SELECT * FROM employees ORDER BY emp_id ASC";
        }elseif(isset($_SESSION['selected_dept']) && isset($_SESSION['selected_position'])){
            $dept = $_SESSION['selected_dept'];
            $position = $_SESSION['selected_position'];

            $dept = str_replace("'", "\'", $dept);
            $position = str_replace("'", "\'", $position);

            $query = "SELECT * FROM employees WHERE dept LIKE '$dept%' && position LIKE '$position%' ORDER BY emp_id ASC";
        }

        if ($sql = $this->conn->query($query)) {
            while ($row = mysqli_fetch_assoc($sql)) {
                $x++;

                $sub_array = array();

                $emp_ID = $row['ID'];

                // deductions
                $sql_ded = "SELECT * FROM deductions where emp_id = '$emp_ID'";
                $dan_ded = $this->conn->query($sql_ded);
                $row_ded = $dan_ded->fetch_assoc();
                $count_ded = mysqli_num_rows($dan_ded);

                $total_start = 0;
                $total_end = 0;

                $sub_array[] = $x;
                $sub_array[] = '<strong>
                                <a href=javascript:void(); data-id="'.$row['ID'].'" class="btn text-active btn-link deductionsbtn" >
                                    <b>'. $row['lname'].', '.$row['fname'].' '.substr($row['mname'],0,1).' '.$row['extname'].'</b>
                                </a>
                                </strong>';
                if($count_ded > 0)
                {
                    $sss = floatval($row_ded['sss']);
                    $pagibig = floatval($row_ded['pagibig']);
                    $ph = floatval($row_ded['philhealth']);
                    $sss_l = floatval($row_ded['sss_loan']);
                    $sss_c = floatval($row_ded['sss_calamity']);
                    $hdmf_l = floatval($row_ded['hdmf_loan']);
                    $hdmf_c = floatval($row_ded['hdmf_calamity']);
                    $salary_l = floatval($row_ded['salary_loan']);
                    $esf = floatval($row_ded['esf']);
                    $ar = floatval($row_ded['ar']);
                    $shortages = floatval($row_ded['shortages']);

                    $total_start = $sss + $pagibig + $ph + $salary_l + $esf + $ar + $shortages;
                    $total_end = $sss_l + $sss_c + $hdmf_l + $hdmf_c + $salary_l + $esf + $ar + $shortages;

                    $sub_array[] = $row_ded['sss'];
                    $sub_array[] = $row_ded['pagibig'];
                    $sub_array[] = $row_ded['philhealth'];
                    $sub_array[] = $row_ded['sss_loan'];
                    $sub_array[] = $row_ded['sss_calamity'];
                    $sub_array[] = $row_ded['hdmf_loan'];
                    $sub_array[] = $row_ded['hdmf_calamity'];
                    $sub_array[] = $row_ded['salary_loan'];
                    $sub_array[] = $row_ded['esf'];
                    $sub_array[] = $row_ded['ar'];
                    $sub_array[] = $row_ded['shortages'];
                }else{
                    $sub_array[] = '-';
                    $sub_array[] = '-';
                    $sub_array[] = '-';
                    $sub_array[] = '-';
                    $sub_array[] = '-';
                    $sub_array[] = '-';
                    $sub_array[] = '-';
                    $sub_array[] = '-';
                    $sub_array[] = '-';
                    $sub_array[] = '-';
                    $sub_array[] = '-';
                }
                $sub_array[] = '<b>'.number_format($total_start,2).'</b>';
                $sub_array[] = '<b>'.number_format($total_end,2).'</b>';
                $data[] = $sub_array;
            }
        }

        return $data;
    }

    // Filter

    public function fetch_filter_deduction($dept, $position)
    {
        $_SESSION['selected_dept'] = $dept;
        $_SESSION['selected_position'] = $position;
        
        $dept = str_replace("'", "\'", $dept);
        $position = str_replace("'", "\'", $position);

        $query = "SELECT * FROM employees WHERE dept LIKE '$dept%' && position LIKE '$position%' ORDER BY emp_id ASC";

        // $data = [];
        $data = array();
        $x = 0;

        if ($sql = $this->conn->query($query)) {
            while ($row = mysqli_fetch_assoc($sql)) {
                $x++;

                $sub_array = array();

                $emp_ID = $row['ID'];

                // deductions
                $sql_ded = "SELECT * FROM deductions where emp_id = '$emp_ID'";
                $dan_ded = $this->conn->query($sql_ded);
                $row_ded = $dan_ded->fetch_assoc();
                $count_ded = mysqli_num_rows($dan_ded);
                                                                                         
                $sub_array[] = $x;
                $sub_array[] = '<strong>
                                <a href=javascript:void(); data-id="'.$row['ID'].'" class="btn text-active btn-link deductionsbtn" >
                                    <b>'. $row['lname'].', '.$row['fname'].' '.substr($row['mname'],0,1).' '.$row['extname'].'</b>
                                </a>
                                </strong>';
                if($count_ded > 0)
                {
                    $sss = floatval($row_ded['sss']);
                    $pagibig = floatval($row_ded['pagibig']);
                    $ph = floatval($row_ded['philhealth']);
                    $sss_l = floatval($row_ded['sss_loan']);
                    $sss_c = floatval($row_ded['sss_calamity']);
                    $hdmf_l = floatval($row_ded['hdmf_loan']);
                    $hdmf_c = floatval($row_ded['hdmf_calamity']);
                    $salary_l = floatval($row_ded['salary_loan']);
                    $esf = floatval($row_ded['esf']);
                    $ar = floatval($row_ded['ar']);
                    $shortages = floatval($row_ded['shortages']);

                    $total_start = $sss + $pagibig + $ph + $salary_l + $esf + $ar + $shortages;
                    $total_end = $sss_l + $sss_c + $hdmf_l + $hdmf_c + $salary_l + $esf + $ar + $shortages;

                $sub_array[] = $row_ded['sss'];
                $sub_array[] = $row_ded['pagibig'];
                $sub_array[] = $row_ded['philhealth'];
                $sub_array[] = $row_ded['sss_loan'];
                $sub_array[] = $row_ded['sss_calamity'];
                $sub_array[] = $row_ded['hdmf_loan'];
                $sub_array[] = $row_ded['hdmf_calamity'];
                $sub_array[] = $row_ded['salary_loan'];
                $sub_array[] = $row_ded['esf'];
                $sub_array[] = $row_ded['ar'];
                $sub_array[] = $row_ded['shortages'];
                }else{
                $sub_array[] = '-';
                $sub_array[] = '-';
                $sub_array[] = '-';
                $sub_array[] = '-';
                $sub_array[] = '-';
                $sub_array[] = '-';
                $sub_array[] = '-';
                $sub_array[] = '-';
                $sub_array[] = '-';
                $sub_array[] = '-';
                $sub_array[] = '-';
                        $total_start = '0.00';
                        $total_end = '0.00';
                }
                $sub_array[] = '<b>'. number_format($total_start,2).'</b>';
                $sub_array[] = '<b>'. number_format($total_end,2).'</b>';
                $data[] = $sub_array;
            }
        }

        return $data;
    }


}