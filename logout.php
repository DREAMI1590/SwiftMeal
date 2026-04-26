<?php
// logout.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// Hapus semua data sesi
$_SESSION = [];
session_unset();
session_destroy();

// Redirect ke login dengan pesan sukses
session_start();
$_SESSION['flash_message'] = 'Berhasil logout.';
$_SESSION['flash_type'] = 'success';

header("Location: login.php");
exit();
