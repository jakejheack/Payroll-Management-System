<?php

include 'connection/model.php';

$model = new Model();

$rows = $model->fetch_position();

echo json_encode($rows);