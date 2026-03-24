<?php
session_start();

// remove only department session
unset($_SESSION['department']);

// destroy session completely
session_destroy();

// redirect to department login
header("Location: dept_login.php");
exit();
?>