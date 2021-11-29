<?php 
session_start();
echo $_GET['idPost'];
require 'connection/connection.php';

// Check if user has posts id
$checkTheProfileQuery = 'select * from posts where idpost ='.$_GET['idPost'];
$checkProfileStmt = $conn->prepare($checkTheProfileQuery);
$checkProfileStmt->execute();
while ($a = $checkProfileStmt->fetch()) {
  if ($a['iduser'] !== $_SESSION['loggedinId']) {
    header("location: mainpage.php");
    exit();
  }
}

// Delete Post 
$deletePostQuery = 'DELETE FROM posts WHERE idpost ='.$_GET['idPost'];
$deletePostStmt = $conn->prepare($deletePostQuery);
$deletePostStmt->execute();
header("Location: profile.php");