<?php


// Check if email is invalid
function invalidEmail($email) {
  $result = false;
  if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $result = true;
    return $result;
  } else {
    $result = false; 
  }
  return $result;
}

// Check if username exists
function usernameExists($conn, $name) {
  $result = false;
  $sql = "SELECT * from gebruikers";
  $stmt = $conn->prepare($sql);
  $stmt->execute();

  while($a = $stmt->fetch()) {
    if ($name == $a['username']) {
      $result = true;
      return $result; 
      exit();
    } else if (preg_match("[\W]",$name)){
      $result = true;
      return $result; 
      exit();
    }  else {
      $result = false;
    }
  }
  return $result;
}

// Check if emailExists
  function emailExists($conn, $email) {
    $result = false;
    $sql = "SELECT * from gebruikers";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
  
    while($a = $stmt->fetch()) {
      if ($email == $a['email']) {
        $result = true;
        return $result;
        exit();
      } else {
        $result = false;
      }

    }
    return $result;
   }

   // Create Account
   function createUser($conn, $name, $email, $password) {
    $sql = "INSERT INTO gebruikers (username,email,password) VALUES (:username, :email, :password)";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['username' => $name, 'email' => $email, 'password' => $password]);
  }