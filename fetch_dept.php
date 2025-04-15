<?php

include 'connection/model.php';

$model = new Model();

$rows = $model->fetch_dept();

echo json_encode($rows);