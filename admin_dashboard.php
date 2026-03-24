<?php
session_start();

// 🔐 ADMIN CHECK
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

// 📊 ANALYTICS
$totalReports = sqlsrv_fetch_array(
    sqlsrv_query($conn, "SELECT COUNT(*) as total FROM reports")
)['total'];

$totalDepartments = sqlsrv_fetch_array(
    sqlsrv_query($conn, "SELECT COUNT(DISTINCT department) as total FROM reports")
)['total'];

$yearData = sqlsrv_fetch_array(
    sqlsrv_query($conn, "SELECT MIN(year) as minYear, MAX(year) as maxYear FROM reports")
);
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>

<style>
body {
    margin:0;
    font-family:Arial;
    background:#f4f6f7;
}

/* HEADER */
header {
    position: relative;
    height: 18vh;
    color: white;
}

header::before {
    content:"";
    position:absolute;
    width:100%;
    height:100%;
    background:url('header1.jpg') center/cover;
    filter:brightness(0.6);
    z-index:-1;
}

.header-content {
    position: relative;
    height: 100%;
}

.center-title {
    position:absolute;
    top:50%;
    left:50%;
    transform:translate(-50%, -50%);
    display:flex;
    align-items:center;
    gap:20px;
}

.logo { height:90px; }

.main-title {
    font-size:28px;
    font-weight:bold;
}

.sub-title {
    font-size:16px;
}

/* NAV */
.nav {
    position:absolute;
    right:30px;
    top:50%;
    transform:translateY(-50%);
    display:flex;
    gap:20px;
}

.nav a {
    color:white;
    text-decoration:none;
    padding:8px 14px;
    border-radius:6px;
    border:1px solid rgba(255,255,255,0.3);
}

.logout {
    background:red;
}

/* CARDS */
.cards {
    display:flex;
    gap:20px;
    margin:20px;
}

.card {
    flex:1;
    background:white;
    padding:20px;
    border-radius:12px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
    text-align:center;
}

.card p {
    font-size:26px;
    font-weight:bold;
    color:#3498db;
}

/* TABLE */
.container {
    padding:20px;
}

.table-container {
    max-height:400px;
    overflow-y:auto;
    border-radius:10px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

table {
    width:100%;
    border-collapse:collapse;
    background:white;
}

th {
    background:#3498db;
    color:white;
    padding:10px;
}

td {
    padding:10px;
    text-align:center;
    border-bottom:1px solid #ddd;
}

tr:hover {
    background:#f2f2f2;
}

/* STICKY HEADER */
.table-container th {
    position:sticky;
    top:0;
    z-index:1;
}

/* FOOTER */
footer {
    position:fixed;
    bottom:0;
    width:100%;
    text-align:center;
    padding:12px;
    background:#2c3e50;
    color:white;
}
</style>
</head>

<body>

<header>
<div class="header-content">

<div class="center-title">
    <img src="Logo.png" class="logo">
    <div>
        <div class="main-title">GOVERNMENT REPORT MANAGEMENT SYSTEM</div>
        <div class="sub-title">Admin Dashboard</div>
    </div>
</div>

<div class="nav">
    <a href="#">Admin</a>
    <a href="admin_logout.php" class="logout">Logout</a>
</div>

</div>
</header>

<!-- 📊 CARDS -->
<div class="cards">

<div class="card">
    <h3>Total Reports</h3>
    <p><?php echo $totalReports; ?></p>
</div>

<div class="card">
    <h3>Departments</h3>
    <p><?php echo $totalDepartments; ?></p>
</div>

<div class="card">
    <h3>Years</h3>
    <p><?php echo $yearData['minYear'] . " - " . $yearData['maxYear']; ?></p>
</div>

</div>

<!-- 📋 TABLE -->
<div class="container">

<h2>All Reports</h2>

<div class="table-container">

<?php
$sql = "SELECT * FROM reports ORDER BY year DESC";
$stmt = sqlsrv_query($conn, $sql);

echo "<table>";
echo "<tr>
        <th>Department</th>
        <th>Title</th>
        <th>Year</th>
        <th>File</th>
        <th>Action</th>
      </tr>";

while ($row = sqlsrv_fetch_array($stmt)) {

    echo "<tr>";
    echo "<td>" . $row['department'] . "</td>";
    echo "<td>" . $row['title'] . "</td>";
    echo "<td>" . $row['year'] . "</td>";
    echo "<td><a href='" . $row['file_path'] . "' target='_blank'>View</a></td>";

    echo "<td>
    <a href='delete_report.php?id=".$row['id']."' 
    style='color:red;' 
    onclick=\"return confirm('Delete this report?')\">
    Delete
    </a>
    </td>";

    echo "</tr>";
}

echo "</table>";
?>

</div>

</div>

<footer>
© 2026 Developed by Karthik M
</footer>

</body>
</html>