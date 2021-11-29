<?php
session_start();
require_once "connection/connection.php";
$toegang = false;

// Login form submitted
if (isset($_POST['loginsubmit'])) {
  $name = htmlspecialchars($_POST['usernameLogin']);
  $password = htmlspecialchars($_POST['passwordLogin']);
  
  $stmt = $conn->prepare('SELECT * FROM gebruikers');
  $stmt->execute();
  while($a = $stmt->fetch()) {
      if (($name == $a['username'] or $name === $a['email']) and $password === $a['password']) {
        $toegang = true;
        $_SESSION['loggedinUser'] = $a['username'];
        $_SESSION['loggedinId'] = $a['iduser'];
        header("location: mainpage.php");
        break;
      } 
      
  }
  
if ($toegang == false) {
  
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
    <title>Login</title>
  </head>
  <body>
    <header>
      <nav class="navbar">
        <h1 style="text-shadow: 1px 1px 6px lightgray;">ShareIt</h1>
        <button class="hamburger" id="hamburger">
          <i class="fas fa-bars"></i>
        </button>
        <ul class="nav-ul" id="nav-ul">
          <li>
            <a href="signup.php">Signup</a>
          </li>
          <li>
            <a href="#">Login</a>
          </li>
        </ul>
      </nav>
    </header>

    <div class="signupblok">
      
      <form class="formlogin" spellcheck="false" method="POST" action="login.php">
        <h2>Login</h2>
        <p>Don't have an account yet? <a href="signup.php">Register</a></p>
        <label for="username">Username Or Email</label>
        <input 
          name="usernameLogin"
          placeholder="ExampleName123"
          class="info"
          id="username"
          type="text"
          required 
         
        />
       
        <label for="password">Password</label>
        <div class="passwordbox">
          <input
          minlength="6"
          maxlength="20"
            name="passwordLogin"
            placeholder="Ex. Skd3Sodcj8S"
            class="info"
            type="password"
            id="password"
            required
          />
          <i class="fas fa-eye eye"></i>
        </div>
        <?php if (isset($_POST['loginsubmit']) && $toegang == false) echo "<p style='margin: -5px 30px; font-size:15px; color: red; margin: 5px 20px;'> Wrong Email or Password! </p>" ?>
        <input name="loginsubmit" value="Login" class="submitbutton" type="submit" />
        
      </form>
    </div>
   
  </body>
  <?php require "footer.html" ?>
</html>

<script src="loginpages/script.js"></script>
