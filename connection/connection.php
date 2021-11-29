<?php
require_once 'credentials.php';

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $passworddb);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
?>