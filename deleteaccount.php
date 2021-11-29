<?php
require "connection/connection.php";  
session_start();

function Delete($path)
{
    if (is_dir($path) === true)
    {
        $files = array_diff(scandir($path), array('.', '..'));

        foreach ($files as $file)
        {
            Delete(realpath($path) . '/' . $file);
        }

        return rmdir($path);
    }

    else if (is_file($path) === true)
    {
        return unlink($path);
    }

    return false;
}
// Check if user is logged in
if (!isset($_SESSION['loggedinUser'])) {
  header("Location: signup.php");
} else { // Delete Account
  delete('image/'.$_SESSION['loggedinUser']);
  $queryDeleteAccount = 'DELETE FROM gebruikers WHERE iduser ="'.$_SESSION['loggedinId'].'"';
  $stmt = $conn->prepare($queryDeleteAccount);
  $stmt->execute();
  session_destroy();
  header("location: signup.php");
}

