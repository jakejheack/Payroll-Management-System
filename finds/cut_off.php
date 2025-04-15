<?php
session_start();

date_default_timezone_set("Asia/Manila");
$y_now = date("Y");
$m_now = date("m");
$d_now = date("j");

$now = date("m/1/Y");

$m_last = date('m', strtotime($now . "- 1 month"));

if(isset($_POST['idt']))
{
    $payroll = trim($_POST['idt']);

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

?>

<div class="mb-3 row">
    <label for="addGenderField" class="col-md-3 form-label">From:</label>
    <div class="col-md-9">
        <input type="date" name="froms" class="form-control" id="" value="<?php echo date('Y-m-d', strtotime($from))?>" required>
    </div>
</div>
<div class="mb-3 row">
    <label for="addGenderField" class="col-md-3 form-label">To:</label>
    <div class="col-md-9">
        <input type="date" name="tos" class="form-control" id="" value="<?php echo date('Y-m-d', strtotime($to))?>" required>
    </div>
</div>
