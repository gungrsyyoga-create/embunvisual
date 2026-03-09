<?php
require_once dirname(__DIR__) . '/config/bootstrap.php';
echo "<h1>Diagnostic Paths</h1>";
echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "<br>";
echo "PHP_SELF: " . $_SERVER['PHP_SELF'] . "<br>";
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "<br>";
echo "Base Dir: " . str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']) . "<br>";
echo "base_url('public/login.php'): " . base_url('public/login.php') . "<br>";
echo "str_replace fix: " . str_replace('admin.php', 'public/login.php', $_SERVER['SCRIPT_NAME']) . "<br>";
echo "<hr>";
echo "<a href='diag.php?logout=1'>Test Logout Logic</a>";

if(isset($_GET['logout'])) {
    $redir_path = str_replace(basename($_SERVER['SCRIPT_NAME']), 'public/login.php', $_SERVER['SCRIPT_NAME']);
    echo "<h2>Would redirect to: $redir_path</h2>";
}
