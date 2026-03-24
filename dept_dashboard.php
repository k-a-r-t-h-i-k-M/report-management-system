<?php
session_start();
include 'db.php';

if (!isset($_SESSION['department'])) {
    header("Location: dept_login.php");
    exit();
}

date_default_timezone_set("Asia/Kolkata");

$dept = $_SESSION['department'];

$hour = date("H");
if ($hour < 12) $greet = "Good Morning";
elseif ($hour < 18) $greet = "Good Afternoon";
else $greet = "Good Evening";

// FORM SUBMIT
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = $_POST['title'];
    $year = $_POST['year'];
    $desc = $_POST['description'];

    $file = $_FILES['file']['name'];
    $path = "uploads/" . $file;

    move_uploaded_file($_FILES['file']['tmp_name'], $path);

    $sql = "INSERT INTO reports (department, title, year, description, file_path)
            VALUES (?, ?, ?, ?, ?)";

    $params = [$dept, $title, $year, $desc, $path];

    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt) {
        $message = "Report Submitted Successfully ✅";
    } else {
        $message = "Error submitting report ❌";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Department Dashboard</title>

<style>
body { margin:0; font-family:Arial; background:#f4f6f7; }

/* HEADER */
header {
    position: relative;
    height: 18vh;
    min-height: 140px;
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

/* CENTER */
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

.title-text { text-align:left; }

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

.nav a:hover {
    background: rgba(255,255,255,0.2);
}

.logout {
    background:red;
}

/* MAIN */
.main {
    padding:30px;
    margin-bottom:80px;
}

/* GREETING */
.welcome {
    background:linear-gradient(135deg,#3498db,#2980b9);
    color:white;
    padding:20px;
    border-radius:12px;
    margin-bottom:25px;
}

/* FORM */
.form-box {
    background:white;
    padding:25px;
    border-radius:15px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

input, textarea {
    width:100%;
    padding:12px;
    margin:10px 0;
    border-radius:10px;
    border:1px solid #ccc;
    box-sizing:border-box;
}

input:focus, textarea:focus {
    outline:none;
    border-color:#3498db;
    box-shadow:0 0 5px rgba(52,152,219,0.5);
}

input[type="file"] {
    padding:8px;
    background:white;
}

button {
    background:#27ae60;
    color:white;
    padding:12px;
    border:none;
    border-radius:10px;
    cursor:pointer;
}

button:hover {
    background:#1e8449;
}

/* SUCCESS MESSAGE */
.success {
    margin-top:15px;
    padding:12px;
    background:#2ecc71;
    color:white;
    border-radius:8px;
    text-align:center;
    font-weight:bold;
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
    <div class="title-text">
        <div class="main-title">GOVERNMENT REPORT MANAGEMENT SYSTEM</div>
        <div class="sub-title">Department Report Portal</div>
    </div>
</div>

<div class="nav">
    <a href="#"><b><?php echo $dept; ?></b></a>
    <a href="dept_logout.php" class="logout">Logout</a>
</div>

</div>
</header>

<div class="main">

<div class="welcome">
<h2><?php echo $greet . ", " . $dept; ?> 👋</h2>
<p>Submit your annual report</p>
</div>

<div class="form-box">

<form method="POST" enctype="multipart/form-data">

<input type="text" name="title" placeholder="Report Title" required>

<input type="number" name="year" placeholder="Year" required>

<textarea name="description" placeholder="Full report details" rows="5"></textarea>

<input type="file" name="file">

<button type="submit">Submit Report</button>

</form>

<?php if($message != "") { ?>
    <div class="success">
        <?php echo $message; ?>
    </div>
<?php } ?>

</div>

</div>

<footer>
© 2026 Developed by Karthik M
</footer>

</body>
</html>