<?php
// Database connection using Windows Authentication
$serverName = "localhost";

$connectionInfo = array(
    "Database" => "ReportSystem"
);

$conn = sqlsrv_connect($serverName, $connectionInfo);

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}
?>