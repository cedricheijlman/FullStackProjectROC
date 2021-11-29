<?php 
// Logs user out
session_start();
session_destroy();
header("Location: login.php");