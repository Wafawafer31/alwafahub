<?php
// admin/logout.php – Logout admin dan redirect ke login

session_start();
session_destroy();
header('Location: login.php');
exit;
?>