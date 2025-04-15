<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- <meta http-equiv="X-UA-Compatible" content="IE=edge"> -->
    <!-- refresh dashboard page -->
    <!-- <meta http-equiv="refresh" content="<?php echo $sec?>;URL='<?php echo $page?>'"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll & D.T.R. System</title>

    <link rel="stylesheet" href="css/styles8.css">

    <!-- placeholder -->
    <!-- <link rel="stylesheet" href="https://unpkg.com/placeholder-loading/dist/css/placeholder-loading.min.css"> -->

    <!-- ion-icons link -->
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    
    <!-- font awesome link -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> -->

    <!-- javascript -->
    <script type="text/javascript" src="js/searches.js"></script>
    <script type="text/javascript" src="js/emp_dept.js"></script>
    <script type="text/javascript" src="js/cut_off.js"></script>
    <script type="text/javascript" src="js/payroll_dept.js"></script>
    <script type="text/javascript" src="js/months.js"></script>
    <script type="text/javascript" src="js/years.js"></script>
    
    <script type="text/javascript" src="js/usernames.js"></script>
    <script type="text/javascript" src="js/con_passwords.js"></script>
    <script type="text/javascript" src="js/pass_limit.js"></script>
    <script type="text/javascript" src="js/useraccess.js"></script>
    <!-- ajax -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- datatables -->
    <link rel="stylesheet" href="css/datatables/bootstrap.css">
    <link rel="stylesheet" href="css/datatables/dataTables.bootstrap4.mins.css">

    <link rel="icon" href="img/icon.png" type="image/ico">

    <!--google material icon-->
    <!-- <link href="css/materialicon.css" rel="stylesheet"> -->

    <script>
    function exportTableToExcel(tableID, filename = ''){
        var downloadLink;
        var dataType = 'application/vnd.ms-excel';
        var tableSelect = document.getElementById(tableID);
        var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
        
        // Specify file name
        filename = filename?filename+'.xls':'excel_data.xls';
        
        // Create download link element
        downloadLink = document.createElement("a");
        
        document.body.appendChild(downloadLink);
        
        if(navigator.msSaveOrOpenBlob){
            var blob = new Blob(['\ufeff', tableHTML], {
                type: dataType
            });
            navigator.msSaveOrOpenBlob( blob, filename);
        }else{
            // Create a link to the file
            downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
        
            // Setting the file name
            downloadLink.download = filename;
            
            //triggering the function
            downloadLink.click();
        }
    }
    </script>

</head>
<body>
