<?php

$username = "advdimag";
$host = "localhost";
$password = "kesariyaji123";
mysqli_report(MYSQLI_REPORT_OFF);
$connection = mysqli_connect( $host , $username , $password);
$db = mysqli_select_db($connection,'advdimag_Resume');
mysqli_set_charset($connection,"utf8");

$home_page_url = "index.php";

?>
