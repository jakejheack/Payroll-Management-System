<?php
session_start();

include_once("connection/cons.php");
$con = conns();

if(isset($_POST['submit'])){
    $from = strtotime($_POST['froms']);
    $to = strtotime($_POST['tos']);

    $sql = "SELECT * FROM dtrs";
    $query = $con->query($sql) or die ($con->error);
    $row = $query->fetch_assoc();

    $json_data=array();//create the array  

    do{
        if(strtotime($row['log_date']) >= $from && strtotime($row['log_date']) <= $to){
            $json_array['ID']= uniqid().uniqid().uniqid().uniqid().uniqid().uniqid().uniqid();
            $json_array['num'] = 'Add';
            $json_array['ondipme']=$row['emp_id'];  
            $json_array['d_logs']= $row['log_date'];
            $json_array['in1']=$row['in1'];
            $json_array['out1'] = ($row['out1'] == '00:00' ? '' : $row['out1']);
            $json_array['in2']= ($row['in2'] == '00:00' ? '' : $row['in2']);  
            $json_array['out2'] = ($row['out2'] == '00:00' ? '' : $row['out2']);
            $json_array['in3']= ($row['in3'] == '00:00' ? '' : $row['in3']);
            $json_array['out3'] = ($row['out3'] == '00:00' ? '' : $row['out3']);
            $json_array['in4']= ($row['in4'] == '00:00' ? '' : $row['in4']);
            $json_array['out4'] = ($row['out4'] == '00:00' ? '' : $row['out4']);
            $json_array['remarks'] = $row['remarks'];

            array_push($json_data,$json_array);  
        }
    }while($row = $query->fetch_assoc());

    $breaks_sql = "SELECT * FROM dtrs_breaks";
    $query_breaks = $con->query($breaks_sql) or die ($con->error);
    $row_breaks = $query_breaks->fetch_assoc();

    do{
        if(strtotime($row_breaks['log_date']) >= $from && strtotime($row_breaks['log_date']) <= $to){
            $json_array['ID']= uniqid().uniqid().uniqid().uniqid().uniqid().uniqid().uniqid();
            $json_array['num'] = 'Minus';
            $json_array['ondipme']=$row_breaks['emp_id'];  
            $json_array['d_logs']= $row_breaks['log_date'];
            $json_array['in1']=$row_breaks['in1'];
            $json_array['out1'] = ($row_breaks['out1'] == '00:00' ? '' : $row_breaks['out1']);
            $json_array['in2']= ($row_breaks['in2'] == '00:00' ? '' : $row_breaks['in2']);  
            $json_array['out2'] = ($row_breaks['out2'] == '00:00' ? '' : $row_breaks['out2']);
            $json_array['in3']= ($row_breaks['in3'] == '00:00' ? '' : $row_breaks['in3']);
            $json_array['out3'] = ($row_breaks['out3'] == '00:00' ? '' : $row_breaks['out3']);
            $json_array['in4']= ($row_breaks['in4'] == '00:00' ? '' : $row_breaks['in4']);
            $json_array['out4'] = ($row_breaks['out4'] == '00:00' ? '' : $row_breaks['out4']);
            $json_array['remarks'] = $row_breaks['remarks'];
            $json_array['total_hrs'] = $row_breaks['total_hrs'];
            $json_array['lates'] = $row_breaks['lates'];

            array_push($json_data,$json_array);  
        }
    }while($row_breaks = $query_breaks->fetch_assoc());


    // foreach($row as $rec)//foreach loop  
    // {  
    //     $json_array['ID']='NULL';  
    //     $json_array['emp_id']=$rec['emp_id'];  
    //     $json_array['log_date']=$rec['log_date'];  
    //     $json_array['in_1']=$rec['in_1'];  
    //     $json_array['out_1'] = $rec['out_1'];
    //     $json_array['in_2']=$rec['in_2'];  
    //     $json_array['out_2'] = $rec['out_2'];

    //     //here pushing the values in to an array  
    //     array_push($json_data,$json_array);  
    
    // }  
    
    //built in PHP function to encode the data in to JSON format  
    $data = json_encode($json_data);  


    ?>


    <!-- <button onclick="onDownload()">Download</button> -->

    <div id="json" >
    </div>

    <script>

        const jsonData = {
            // <?= json_encode($json_data) ?>;
        };

        const e = document.getElementById('json');
        e.innerHTML = JSON.stringify(jsonData);

        function download(content, fileName, contentType) {
        const a = document.createElement("a");
        const file = new Blob([content], { type: contentType });
        a.href = URL.createObjectURL(file);
        a.download = fileName;
        a.click();
        }

        // function onDownload(){
            download(JSON.stringify(<?= json_encode($json_data) ?>), "PrimeTigaon_<?= date('m_d_Y', strtotime($_POST['froms'])).'-'.date('m_d_Y', strtotime($_POST['tos'])).'--'.time()?>.json", "text/plain");
        // }
        
    </script>

<?php
}

if(isset($_POST['move'])){
    $target_dir = "uploaded_dtr/";
    $target_file = $target_dir . basename($_FILES["json_files"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    // Check if image file is a actual image or fake image
    if(isset($_POST["move"])) {
      $check = getimagesize($_FILES["json_files"]["tmp_name"]);
      if (move_uploaded_file($_FILES["json_files"]["tmp_name"], $target_file)) {
        echo "File: ". htmlspecialchars( basename( $_FILES["json_files"]["name"])). ".<br>";
        $json_file = htmlspecialchars( basename( $_FILES["json_files"]["name"]));

            //read the json file contents
            $jsondata = file_get_contents($target_dir.$json_file);
            
            //convert json object to php associative array
            $data = json_decode($jsondata, true);
            
            $no = 0;
            $x = 0;
            $nob = 0;
            $xb = 0;
            // Extracting row by row 
            foreach($data as $row) { 

                if($row['num'] == 'Add'){
                    $no++;
                    $id = $row['ID'];
                    $emp_id = $row['ondipme'];
                    $log_date = $row['d_logs'];
                    $in1 = $row['in1'];
                    $out1 = $row['out1'];
                    $in2 = $row['in2'];
                    $out2 = $row['out2'];
                    $in3 = $row['in3'];
                    $out3 = $row['out3'];
                    $in4 = $row['in4'];
                    $out4 = $row['out4'];
                    $remarks = $row['remarks'];

                    $sql_search = "SELECT ID,emp_id,log_date FROM dtrs WHERE emp_id = '$emp_id' && log_date = '$log_date'";
                    $query_search = $con->query($sql_search) or die ($con->error);
                    $row_search = $query_search->fetch_assoc();
                    $count_search = mysqli_num_rows($query_search);

                    if($count_search == 0){
                        $sql = "INSERT INTO `dtrs` (`ID`, `emp_id`, `log_date`, `in1`, `out1`, `in2`, `out2`, `in3`, `out3`, `in4`, `out4`, `remarks`) 
                        VALUES (NULL, '$emp_id', '$log_date', '$in1', '$out1', '$in2', '$out2', '$in3', '$out3', '$in4', '$out4', '$remarks')"; 
                        $query = $con->query($sql) or die ($con->error);

                        $x++;
                    }
            }elseif($row['num'] == 'Minus'){
                $nob++;
                $id = $row['ID'];
                $emp_id = $row['ondipme'];
                $log_date = $row['d_logs'];
                $in1 = $row['in1'];
                $out1 = $row['out1'];
                $in2 = $row['in2'];
                $out2 = $row['out2'];
                $in3 = $row['in3'];
                $out3 = $row['out3'];
                $in4 = $row['in4'];
                $out4 = $row['out4'];
                $remarks = $row['remarks'];
                $total_hrs = $row['total_hrs'];
                $lates = $row['lates'];

                $sql_search = "SELECT ID,emp_id,log_date FROM dtrs_breaks WHERE emp_id = '$emp_id' && log_date = '$log_date'";
                $query_search = $con->query($sql_search) or die ($con->error);
                $row_search = $query_search->fetch_assoc();
                $count_search = mysqli_num_rows($query_search);

                if($count_search == 0){
                    $sql = "INSERT INTO `dtrs_breaks` (`ID`, `emp_id`, `log_date`, `in1`, `out1`, `in2`, `out2`, `in3`, `out3`, `in4`, `out4`, `remarks`, `total_hrs`, `lates`) 
                    VALUES (NULL, '$emp_id', '$log_date', '$in1', '$out1', '$in2', '$out2', '$in3', '$out3', '$in4', '$out4', '$remarks', '$total_hrs', '$lates')"; 
                    $query = $con->query($sql) or die ($con->error);

                    $xb++;
                }
            }
        }

            echo $no.' Lines '.$x.' Saved<br>';
            echo $nob.' Lines '.$xb.' Saved<br>';
      }
    }
}
?>

<form action="" method="post">
    <span>From:</span>
    <input type="date" name="froms" id="froms">
    <br>
    <span>To:</span>
    <input type="date" name="tos" id="tos">
    <br>
    <input type="submit" value="Submit" name="submit">
</form>

<!-- <form action="" method="post" enctype="multipart/form-data">
    <input type="file" name="json_files" id="json_files" accept=".json">
    <br>
    <input type="submit" value="Import" name="move">
</form> -->