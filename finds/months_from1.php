<?php
session_start();

date_default_timezone_set("Asia/Manila");
// $y_now = date("Y");
// $m_now = date("m");
// $d_now = date("j");

// $now = date("m/1/Y");

// $m_last = date('m', strtotime($now . "- 1 month"));

if(isset($_POST['idtms']))
{
    $month_from = $_POST['idtms'];
    $year_from = trim($_POST['yrsms']);

    $month_from = $month_from.'/1'.'/'.$year_from;
    $month_to = date('m/t/Y', strtotime($month_from . "+ 5 months"));

    $m_to = date('m', strtotime($month_to));
    $y_to = date('Y', strtotime($month_to));

}

?>

<span class="input-group-text">To:</span>
<select name="" id="month_to" class="form-control" disabled>
    <option value="" disabled selected hidden>Month</option>
    <?php
        for($i = 1; $i <=12; $i++){
            $dateObj   = DateTime::createFromFormat('!m', $i);
            $monthName = $dateObj->format('F');
            ?>
                <option value="<?= $i ?>" <?= ($i == $m_to ? 'selected' : '')?>><?= $monthName ?></option>
            <?php
        }
    ?>
</select>
<input type="hidden" name="month_to" value="<?= $m_to?>">

<select name="" id="year_to" class="form-control" disabled>
    <option value="" disabled selected hidden>Year</option>
    <?php
        echo '<option value="'.$y_to.'" selected>'.$y_to.'</option>';
    ?>
</select>
<input type="hidden" name="year_to" value="<?= $y_to?>">
