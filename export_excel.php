<?php
include 'db.php';

header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=reports.csv");

echo "Department\tTitle\tYear\n";

$sql = "SELECT * FROM reports";
$stmt = sqlsrv_query($conn, $sql);

while ($row = sqlsrv_fetch_array($stmt)) {
    echo $row['department'] . "\t" .
         $row['title'] . "\t" .
         $row['year'] . "\n";
}
?>