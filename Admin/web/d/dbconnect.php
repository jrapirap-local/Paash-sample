<?php
$serverName = "localhost\\SQLEXPRESS"; // double backslash
$dbName = "projectDB";
$username = "project";
$password = "2woWayP023r!";

try {
  $conn = new PDO("sqlsrv:Server=$serverName;Database=$dbName", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>