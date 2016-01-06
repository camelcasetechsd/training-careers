<?php

include_once '../lib/Controller/Main.php';
include_once '../lib/Model/Career.php';
include_once '../lib/Model/Category.php';
include_once '../lib/Model/Course.php';
include_once '../lib/Model/Outline.php';
include_once '../lib/Model/CareerOutline.php';
include_once '../lib/Include/MySQLiQuery.php';
$config = include_once '../lib/Include/Config.php';

if (!empty($_POST)) {
    $main = new Main($_POST, $config);
    if (method_exists($main, $_POST['method'])) {
        $main->$_POST['method']();
    }
}
