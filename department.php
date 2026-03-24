<?php
session_start();
include 'db.php';

date_default_timezone_set("Asia/Kolkata");

// Greeting
$user = $_SESSION['username'] ?? 'Admin';
$hour = date("H");

if ($hour < 12) $greet = "Good Morning";
elseif ($hour < 18) $greet = "Good Afternoon";
else $greet = "Good Evening";
?>

<!DOCTYPE html>
<html>
<head>
<title>Departments</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body { margin:0; font-family:Arial; }

/* HEADER (SAME) */
header {
    position: relative;
    height: 18vh;
    min-height: 140px;
    color: white;
    display:flex;
    align-items:center;
    justify-content:center;
}

header::before {
    content:"";
    position:absolute;
    width:100%;
    height:100%;
    background:url('header1.jpg') no-repeat center;
    background-size:cover;
    filter:brightness(0.6);
    z-index:-1;
}

.header-content { position:relative; width:100%; height:100%; }

.center-title {
    position:absolute;
    top:50%;
    left:50%;
    transform:translate(-50%,-50%);
    display:flex;
    gap:20px;
    align-items:center;
}

.logo { height:90px; }

.main-title { font-size:30px; font-weight:bold; }
.sub-title { font-size:17px; }

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
    transition:0.3s;
}

.nav a.active {
    background: linear-gradient(135deg,#1abc9c,#16a085);
    font-weight:bold;
    box-shadow:0 3px 10px rgba(0,0,0,0.2);
    backdrop-filter: blur(10px);
    border:none;
}

.nav a:hover {
    background: rgba(255,255,255,0.2);
    transform: translateY(-2px);
}

.logout { background:red; }

/* MAIN */
.main {
    padding:30px;
    margin-bottom:80px;
}

/* WELCOME */
.welcome-box {
    background: linear-gradient(135deg,#9b59b6,#8e44ad);
    color:white;
    padding:20px;
    border-radius:12px;
    margin-bottom:25px;
}

/* GRID */
.dept-grid {
    display:grid;
    grid-template-columns: repeat(auto-fill, minmax(250px,1fr));
    gap:20px;
}

/* CARD */
.dept-card {
    background:white;
    border-radius:12px;
    padding:20px;
    text-align:center;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
    transition:0.3s;
}

.dept-card i {
    font-size:30px;
    color:#3498db;
    margin-bottom:10px;
}

.dept-card h3 {
    margin:10px 0;
}

.dept-card p {
    margin:5px 0;
    color:#555;
}

.dept-card:hover {
    transform: translateY(-5px);
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
<img src="logo.png" class="logo">
<div>
<div class="main-title">NATIONAL INSTITUTE OF OCEAN TECHNOLOGY</div>
<div class="sub-title">Government Report Management System</div>
</div>
</div>

<div class="nav">
<a href="admin_dashboard.php"><i class="fa fa-home"></i> Dashboard</a>
<a href="reports.php"><i class="fa fa-file"></i> Reports</a>
<a href="departments.php" class="active"><i class="fa fa-building"></i> Departments</a>
<a href="logout.php" class="logout"><i class="fa fa-sign-out-alt"></i> Logout</a>
</div>

</div>
</header>

<div class="main">

<div class="welcome-box">
<h2><?php echo $greet . ", " . $user; ?> 👋</h2>
<p>Browse all departments and their reports</p>
</div>

<div class="dept-grid">

<?php
$sql = "SELECT department,
        COUNT(*) as total,
        MAX(year) as latest_year
        FROM reports
        GROUP BY department";

$stmt = sqlsrv_query($conn, $sql);

while ($row = sqlsrv_fetch_array($stmt)) {
?>

<div class="dept-card">
<i class="fa fa-building"></i>
<h3><?php echo $row['department']; ?></h3>
<p>Total Reports: <b><?php echo $row['total']; ?></b></p>
<p>Latest Year: <b><?php echo $row['latest_year']; ?></b></p>

<a href="reports.php?dept=<?php echo urlencode($row['department']); ?>"
style="display:inline-block;margin-top:10px;
background:#3498db;color:white;padding:8px 12px;
border-radius:5px;text-decoration:none;">
View Reports
</a>
</div>

<?php } ?>

</div>

</div>

<footer>
© JustConsole @2026
</footer>

</body>
</html>