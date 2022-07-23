<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Access-Control-Allow-Origin: *');
require_once "../inc/libs/Init.php";

$init= new Init("page", "dashboard");
