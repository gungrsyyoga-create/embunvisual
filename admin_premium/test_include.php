<?php
require_once __DIR__ . '/../config/bootstrap.php';
echo "<h1>Diagnostic Test</h1>";
echo "File: " . __FILE__ . "<br>";
echo "Conn initialized: " . (isset($conn) ? 'YES' : 'NO') . "<br>";
if (isset($conn)) {
    echo "Conn type: " . get_class($conn) . "<br>";
}
echo "Session active: " . (session_status() === PHP_SESSION_ACTIVE ? 'YES' : 'NO') . "<br>";
echo "<hr>";
echo "<h2>_SERVER Content</h2>";
echo "<pre>";
print_r($_SERVER);
echo "</pre>";
