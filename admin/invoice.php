<?php
/**
 * admin/invoice.php
 * Redirector to handle legacy invoice links from emails
 */
$query_string = $_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '';
header("Location: ../public/invoice.php" . $query_string);
exit;
