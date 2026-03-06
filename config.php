<?php
// Cek apakah session sudah jalan, kalau belum baru dinyalakan
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = "localhost";
$user = "root"; 
$pass = ""; 
$db   = "embun_visual"; 

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi Database Gagal: " . mysqli_connect_error());
}
?>