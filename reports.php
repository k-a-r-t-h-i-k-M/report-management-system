<?php
session_start();
include 'db.php';

$dept  = $_GET['dept']  ?? '';
$year  = $_GET['year']  ?? '';
$q     = $_GET['q']     ?? ''; // search

// Build SQL with params
$sql = "SELECT * FROM reports WHERE 1=1";
$params = [];

if ($dept !== '') {
    $sql .= " AND department = ?";
    $params[] = $dept;
}
if ($year !== '') {
    $sql .= " AND year = ?";
    $params[] = $year;
}
if ($q !== '') {
    $sql .= " AND (title LIKE ? OR description LIKE ?)";
    $params[] = "%$q%";
    $params[] = "%$q%";
}

$sql .= " ORDER BY year DESC";
$stmt = sqlsrv_query($conn, $sql, $params);
?>

<!DOCTYPE html>
<html>
<head>
<title>Reports</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body { margin:0; font-family:Arial; }

/* HEADER (same as dashboard) */
header {
    position: relative;
    height: 18vh;
    min-height: 140px;
    color: white;
    display:flex; align-items:center; justify-content:center;
}
header::before {
    content:"";
    position:absolute; width:100%; height:100%;
    background:url('header1.jpg') no-repeat center/cover;
    filter:brightness(0.6); z-index:-1;
}
.header-content { position:relative; width:100%; height:100%; }

.center-title {
    position:absolute; top:50%; left:50%;
    transform:translate(-50%,-50%);
    display:flex; gap:20px; align-items:center;
}
.logo { height:90px; }
.main-title { font-size:30px; font-weight:bold; }
.sub-title { font-size:17px; }

/* NAV */
.nav {
    position:absolute; right:30px; top:50%;
    transform:translateY(-50%);
    display:flex; gap:20px;
}
.nav a {
    color:white; text-decoration:none;
    padding:8px 14px; border-radius:6px;
    border:1px solid rgba(255,255,255,0.3);
    transition:.3s;
}
.nav a.active {
    background: linear-gradient(135deg,#1abc9c,#16a085);
    box-shadow:0 3px 10px rgba(0,0,0,.2);
    backdrop-filter: blur(10px);
    border:none; font-weight:bold;
}
.nav a:hover { background:rgba(255,255,255,.2); transform:translateY(-2px); }
.logout { background:red; }

/* MAIN */
.main { padding:30px; margin-bottom:80px; }

/* TOP BAR */
.top-bar {
    display:flex; justify-content:space-between; align-items:center;
    gap:15px; margin-bottom:20px; flex-wrap:wrap;
}

/* FILTER GROUP */
.filters {
    display:flex; gap:10px; flex-wrap:wrap;
}

.filters input, .filters select {
    padding:10px; border-radius:6px; border:1px solid #ccc;
    min-width:160px;
}

/* BUTTONS */
.btn {
    padding:10px 14px; border-radius:6px; border:none; cursor:pointer;
    text-decoration:none; color:white; display:inline-flex; gap:6px; align-items:center;
}
.btn-filter { background:#3498db; }
.btn-export { background:linear-gradient(135deg,#27ae60,#1e8449); }

/* TABLE */
table { width:100%; border-collapse:collapse; }
th { background:#3498db; color:white; padding:12px; }
td { padding:12px; border-bottom:1px solid #ddd; text-align:center; }
tr:hover { background:#f2f2f2; }

/* FOOTER */
footer {
    position:fixed; bottom:0; width:100%;
    text-align:center; padding:12px;
    background:#2c3e50; color:white;
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
        <a href="reports.php" class="active"><i class="fa fa-file"></i> Reports</a>
        <a href="logout.php" class="logout"><i class="fa fa-sign-out-alt"></i> Logout</a>
    </div>
</div>
</header>

<div class="main">

<h2>Reports</h2>

<div class="top-bar">

<form method="GET" class="filters">
    <input type="text" name="q" placeholder="Search..." value="<?php echo htmlspecialchars($q); ?>">

    <select name="dept">
        <option value="">All Departments</option>
        <?php
        $dq = sqlsrv_query($conn,"SELECT DISTINCT department FROM reports");
        while($d = sqlsrv_fetch_array($dq)){
            $sel = ($dept==$d['department'])?"selected":"";
            echo "<option value='{$d['department']}' $sel>{$d['department']}</option>";
        }
        ?>
    </select>

    <select name="year">
        <option value="">All Years</option>
        <?php
        $yq = sqlsrv_query($conn,"SELECT DISTINCT year FROM reports ORDER BY year DESC");
        while($y = sqlsrv_fetch_array($yq)){
            $sel = ($year==$y['year'])?"selected":"";
            echo "<option value='{$y['year']}' $sel>{$y['year']}</option>";
        }
        ?>
    </select>

    <button class="btn btn-filter"><i class="fa fa-filter"></i> Apply</button>
</form>

<a href="export_excel.php?dept=<?php echo urlencode($dept); ?>&year=<?php echo urlencode($year); ?>&q=<?php echo urlencode($q); ?>" class="btn btn-export">
<i class="fa fa-download"></i> Export
</a>

</div>

<?php
echo "<table>";
echo "<tr><th>Department</th><th>Title</th><th>Year</th><th>File</th></tr>";

while($row = sqlsrv_fetch_array($stmt)){
    echo "<tr>";
    echo "<td>{$row['department']}</td>";
    echo "<td>{$row['title']}</td>";
    echo "<td>{$row['year']}</td>";
    echo "<td><a href='{$row['file_path']}'>Download</a></td>";
    echo "</tr>";
}
echo "</table>";
?>

</div>

<footer>© NATIONAL INSTITUTE OF OCEAN TECHNOLOGY @ 2026</footer>

</body>
</html>