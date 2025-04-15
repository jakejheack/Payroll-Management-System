<?php

include 'connection/model.php';

$model = new Model();

if (isset($_POST['dept']) || isset($_POST['position']) ||
    !empty($_POST['dept']) || !empty($_POST['position']))
    {

    $dept = $_POST['dept'];
    $position = $_POST['position'];

    $rows = $model->fetch_filter($dept, $position);

} else {
    $rows = $model->fetch();
}

echo json_encode($rows);