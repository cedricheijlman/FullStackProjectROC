<?php
session_start();
require_once "connection/connection.php";
$_SESSION['nameExist'] = false;
  $_SESSION['emailExist'] = false;
  $_SESSION['invalidEmail'] = false;

if (isset($_POST['submit'])) {
  $name = htmlspecialchars($_POST['username']);
  $email = htmlspecialchars($_POST['email']);
  $password = htmlspecialchars($_POST['password']);

  $_SESSION['guestName'] = $name;
  $_SESSION['guestEmail'] = $email;
  
  

  $invalidemail = false;
  $usernameexists = false;
  $emailexists = false;
  
  require_once 'connection/connection.php';
  require_once 'functions.inc.php';

  if (usernameExists($conn, $name) !== false) {
    $usernameexists = true;
    $_SESSION['nameExist'] = true;
   
  } else {
    $_SESSION['nameExist'] = false;
  }


  if (invalidEmail($email) !== false) {
    $invalidemail = true;
    $_SESSION['invalidEmail'] = true;
  } else {
    $_SESSION['invalidEmail'] = false;
  }

  

  if (emailExists($conn, $email) !== false) {
    $emailexists = true;
    $_SESSION['emailExist'] = true;
  } else {
    $_SESSION['emailExist'] = false;
  }

  if ($invalidemail == false && $usernameexists == false && $emailexists == false) {
  createUser($conn, $name, $email, $password);
  $_SESSION['loggedinUser'] = $name;
  
  $query = 'SELECT * FROM gebruikers where username ="'.$_SESSION['loggedinUser'].'"';
  $stmt = $conn->prepare($query);
  $stmt->execute();
  while($a = $stmt->fetch()) {
  $_SESSION['loggedinId'] = $a['iduser'];
  }
  mkdir("image/".$_SESSION['loggedinUser']);
  $nameUser = $_SESSION['loggedinUser'];
  copy('image.jpeg', 'image/'.$nameUser.'/image.jpeg');
  header("location: mainpage.php");

}
}




?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="loginpages/style.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css "
    />
    <title>Sign Up</title>
  </head>
  <body>
    <header>
      <nav class="navbar">
        <h1 style="text-shadow: 1px 1px 6px lightgray;">ThoughtsGram</h1>
        <button class="hamburger" id="hamburger">
          <i class="fas fa-bars"></i>
        </button>
        <ul class="nav-ul" id="nav-ul">
          <li>
            <a href="#">Signup</a>
          </li>
          <li>
            <a href="login.php">Login</a>
          </li>
        </ul>
      </nav>
    </header>

    

    <div class="signupblok">
      <div class="leftbox">
        <h2>ThoughtsGram</h2>
        <h3>Share your thoughts to the world without judgement!</h3>
      </div>
      <form spellcheck="false" method="POST" action="signup.php">
     
        <h2>Sign Up</h2>
        <p>Already have an account? <a href="login.php">Login</a></p>
        <label for="username">Username</label>
        <input 
          name="username"
          maxlength="20"
          <?php if (($_SESSION['invalidEmail'] == true or $_SESSION['nameExist'] == true or $_SESSION['emailExist']) == true && isset($_POST['submit'])) echo 'value='.$_SESSION['guestName'] ?>
          <?php if ($_SESSION['nameExist'] == true && (isset($_POST['submit']))) echo 'style="border: 1px solid red"'?>
          placeholder="ExampleName123"
          class="info"
          id="username"
          type="text"
          required 
         
        />
      <?php if (isset($_POST['submit']) && $_SESSION['nameExist'] == true) echo "<p style='margin: -5px 30px; font-size:13px; color: red;'> Username Exist or is Invalid </p>" ?>
        <label for="email">Email</label>
        <input
          
          name="email"
          <?php if (($_SESSION['invalidEmail'] == true or $_SESSION['nameExist'] == true or $_SESSION['emailExist']) == true && isset($_POST['submit'])) echo 'value='.$_SESSION['guestEmail']?>
          <?php if (($_SESSION['invalidEmail'] == true or $_SESSION['emailExist'])&& isset($_POST['submit'])) echo 'style="border: 1px solid red"'?>
          placeholder="Example@gmail.com"
          class="info"
          id="email"
          type="email"
          required
        />
        <?php if (isset($_POST['submit']) && $_SESSION['emailExist'] == true) echo "<p style='margin: -5px 30px; font-size:13px; color: red;'> Email Exist. </p>" ?>
          <?php if (isset($_POST['submit']) && $_SESSION['invalidEmail'] == true) echo "<p style='margin: -5px 30px; font-size:13px; color: red;'> Invalid Email. </p>" ?>
      
        <label for="password">Password</label>
        <div class="passwordbox">
          <input
          minlength="6"
          maxlength="20"
            name="password"
            placeholder="Ex. Skd3Sodcj8S"
            class="info"
            type="password"
            id="password"
            required
          />
          <i class="fas fa-eye eye"></i>
        </div>
        <div class="policy">
          <input type="checkbox" required/><span
            >I accept the <span style="color: blue;"> Terms of service</span> and <span style="color: blue;"> privacy policy.</span
          >
        </div>
        <input name="submit" value="Sign Up" class="submitbutton" type="submit" />
        
      </form>
    </div>
  

  
  </body>
  
 <?php require "footer.html" ?>
</html>

<script src="loginpages/script.js"></script>
