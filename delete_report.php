<?php
include 'db.php';

$id = $_GET['id'];

$sql = "DELETE FROM reports WHERE id=?";
$params = [$id];

sqlsrv_query($conn, $sql, $params);

header("Location: admin_dashboard.php");
exit();
?>