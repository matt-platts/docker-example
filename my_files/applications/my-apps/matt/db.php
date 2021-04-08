<?php

$servername = $_SERVER['MATT_ENV_DB_ADDRESS'];
$username = $_SERVER['MATT_ENV_DB_USER'];
$password = $_SERVER['MATT_ENV_DB_PASSWORD'];

// Create connection
$conn = mysqli_connect($servername, $username, $password);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
echo "Connected successfully";
?>
