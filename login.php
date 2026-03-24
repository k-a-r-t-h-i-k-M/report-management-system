<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['username'];
    $password = $_POST['password'];

    // SIMPLE LOGIN (you can upgrade later)
    if ($username == "admin" && $password == "123") {
        $_SESSION['admin'] = $username;
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error = "Invalid Login!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Login</title>

<style>
body {
    margin:0;
    font-family:Arial;
    background:#f4f6f7;
}

/* HEADER */
header {
    height:18vh;
    min-height:140px;
    display:flex;
    align-items:center;
    justify-content:center;
    color:white;
    position:relative;
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

.center-title {
    display:flex;
    gap:20px;
    align-items:center;
}

.logo { height:90px; }

.main-title {
    font-size:28px;
    font-weight:bold;
}

.sub-title {
    font-size:16px;
}

/* CARD */
.login-card {
    width:350px;
    margin:50px auto;
    background:white;
    padding:30px;
    border-radius:15px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
    text-align:center;
}

input {
    width:100%;
    padding:12px;
    margin:10px 0;
    border-radius:10px;
    border:1px solid #ccc;
    box-sizing:border-box;
}

button {
    width:100%;
    padding:12px;
    background:#3498db;
    color:white;
    border:none;
    border-radius:10px;
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
<div class="center-title">
<img src="Logo.png" class="logo">
<div>
<div class="main-title">GOVERNMENT REPORT MANAGEMENT SYSTEM</div>
<div class="sub-title">Admin Portal Login</div>
</div>
</div>
</header>

<div class="login-card">

<h2>Admin Login</h2>

<form method="POST">

<input type="text" name="username" placeholder="Username" required>

<input type="password" name="password" placeholder="Password" required>

<button type="submit">Login</button>

</form>

<?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

</div>

<footer>
© 2026 Developed by Karthik M
</footer>

</body>
</html>