<?php
// $servername = "localhost";
// $username = "root";
// $password = "D3f@ultAdm1n!!!";
// $db = "db_paash";

// // Create connection
// $conn = new mysqli($servername, 'u789299457_paashadmin', $password, "u789299457_db_paash");
// $conn2 = new mysqli($servername, 'u789299457_paashadminmail', $password, "u789299457_db_paash_mail");
// $conn3 = new mysqli($servername, 'u789299457_paashadminback', $password, "u789299457_db_paash_back");	


$servername = "localhost";
$username = "root";
$password = "";
$db = "db_paash";

// Create connection
$conn = new mysqli($servername, $username, $password, "db_paash");
$conn2 = new mysqli($servername, $username, $password, "db_paash_mail");
$conn3 = new mysqli($servername, $username, $password, "db_paash_back");

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
if ($conn2->connect_error) {
  die("Connection failed: " . $conn2->connect_error);
}
if ($conn3->connect_error) {
  die("Connection failed: " . $conn3->connect_error);
}
?>