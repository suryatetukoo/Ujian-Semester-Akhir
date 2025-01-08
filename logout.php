<?php
// Memulai session
session_start();

// Menghapus session yang ada
session_unset();
session_destroy();

// Redirect ke halaman login setelah logout
header("location:login.php");
exit();
?>
