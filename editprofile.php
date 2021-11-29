<?php    
session_start();    
require "connection/connection.php";  
date_default_timezone_set('Europe/Amsterdam');


// check if user is logged in
if (!isset($_SESSION['loggedinUser'])) {
header("location: signup.php");
exit();
}


$profileUsername = $_SESSION['loggedinUser'];
$_SESSION['nameExist'] = false;
$_SESSION['invalidEmail'] = false;
$_SESSION['emailExist'] = false;
$_SESSION['imgError'] = false;

// if Edit Account form is submitted
if (isset($_POST['submit'])) {
  $name = htmlspecialchars($_POST['username']);
  $email = htmlspecialchars($_POST['email']);
  $password = htmlspecialchars($_POST['password']);

 // Validate Messages
  $imgError = false;
  $imgChanged = false;
  $usernameChanged = false;
  $emailChanged = false;
  $passwordChanged = false;
  $_SESSION['nameExist'] = false;
  $_SESSION['invalidEmail'] = false;
  $_SESSION['emailExist'] = false;
  $_SESSION['imgError'] = false;


  require_once 'connection/connection.php';
  require_once 'functions.inc.php';
 
  if (usernameExists($conn, $name) !== false) {
   
    $_SESSION['nameExist'] = true;
   
  } else {
    $_SESSION['nameExist'] = false;
  }

  

  if (invalidEmail($email) !== false) {
    
    $_SESSION['invalidEmail'] = true;
  } else {
    $_SESSION['invalidEmail'] = false;
  }

  

  if (emailExists($conn, $email) !== false) {
    
    $_SESSION['emailExist'] = true;
  } else {
    $_SESSION['emailExist'] = false;
  }

  


   

  $filename = $_FILES["uploadfile"]["name"];
  $file = $_FILES["uploadfile"]["tmp_name"];
  $folder = "image/".$_SESSION['loggedinUser']."/".$filename;
  move_uploaded_file($file, $folder);
  
   // Username Change
   if ($_SESSION['nameExist'] == false and $_POST['username'] !== "") {
     $oldName = $_SESSION['loggedinUser'];
    $query = 'UPDATE gebruikers SET username ="'.$_POST['username'].'" WHERE username ="'.$profileUsername.'"';
     $stmt = $conn->prepare($query);
     $stmt->execute();
     
     $_SESSION['loggedinUser'] = $_POST['username'];
     $profileUsername = $_SESSION['loggedinUser'];
     $usernameChanged = true;
     $newName = $_SESSION['loggedinUser'];
     rename('image/'.$oldName, 'image/'.$newName);
  }

  // Email Change
  if ($_SESSION['emailExist'] == false and $_SESSION['invalidEmail'] == false and $_POST['email'] !== "") {
    $query = 'UPDATE gebruikers SET email ="'.$_POST['email'].'" WHERE username ="'.$profileUsername.'"';
     $stmt = $conn->prepare($query);
     $stmt->execute();
     $emailChanged = true;
     
  }

  // Password Change
  if ($_POST['password'] !== "") {
    $query = 'UPDATE gebruikers SET password ="'.$_POST['password'].'" WHERE username ="'.$profileUsername.'"';
     $stmt = $conn->prepare($query);
     $stmt->execute();
     $passwordChanged = true;
     
  }

  // Profile Picture Change
  if ($filename !== "" and isset($filename) and file_exists($folder)) {
   
    $query = 'UPDATE gebruikers SET profilepicture ="'.$filename.'" WHERE username ="'.$profileUsername.'"';
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $imgChanged = true;
  } else {
    $_SESSION['imgError'] = true;
    
  }
   


} 
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css">
  <link rel="stylesheet" href="mainpages/editprofile.css">
  
  <title>Settings</title>
</head>





<body >

<?php require "navbar.php";
require "sidebar.php";
require "postmodal.php";
require "deleteaccountmodal.php";
?>

<div class="box">
  
<div class="container2">
    <div class="nameuser">
      <?php echo "<h5>Signed In as: ".$_SESSION['loggedinUser']."!</h5>"?>
    </div>
    <div class="options">
     
     <div class="optionitem"> <a <?php echo "href='mainpage.php?username=".$_SESSION['loggedinUser']."'"; ?> >Home</a></div>
     <div class="optionitem"> <a <?php echo "href='followingfeed.php?username=".$_SESSION['loggedinUser']."'"; ?> >Following Feed</a></div>
     <div class="optionitem"><a <?php echo "href='profile.php?username=".$_SESSION['loggedinUser']."'"; ?> >Profile</a></div>
     <div class="optionitem"> <a <?php echo "href='editprofile.php?username=".$_SESSION['loggedinUser']."'"; ?> >Settings</a></div>
     <div class="optionitem"><a href="logout.php">Log Out</a></div>
    
    </div>
  </div>
  <div class="container2">
    <div class="settingsbox">
     <button class="deleteAccountButton">Delete Account</button>
      <form onkeydown="return event.key != 'Enter';" method="POST" 
              action="" 
              enctype="multipart/form-data">
              <h2>Profile Settings</h2>
              
<label for="uploadfile">Change Profile Picture</label>
<div class="divbox">
  
            <input accept=".jpg, .jpeg, .png" type="file" 
            id="uploadfile"
                   name="uploadfile" 
                    value="" />
                   
                    </div>
                    <?php if (isset($_POST['submit']) && $imgChanged == true ) echo "<p style='margin: -5px 30px; font-size:13px; color: green;'> Image succesfully changed. </p>" ?>
  <?php if (isset($_POST['submit']) && $_SESSION['imgError'] == true && $filename !== "" ) echo "<p style='margin: -5px 30px; font-size:13px; color: red;'> Use Another Image. </p>" ?>
      <label for="nameaccount">Change Username</label>
      <input  maxlength="20" placeholder="Username" name="username" id="nameaccount" type="text" 
      <?php if (isset($_SESSION['nameExist']) && $_SESSION['nameExist'] == true && (isset($_POST['submit']))) echo 'style="border: 1px solid red"'?>>
      <?php if (isset($_POST['submit']) && $_SESSION['nameExist'] == true && isset($_SESSION['nameExist'])) echo "<p style='margin: -5px 30px; font-size:13px; color: red;'> Username exist or is invalid. </p>" ?>
      <?php if (isset($_POST['submit']) && $usernameChanged == true ) echo "<p style='margin: -5px 30px; font-size:13px; color: green;'> Username succesfully changed. </p>" ?>
      <label for="passwordchange">Change Password</label>
      <input minlength="6" placeholder="Password" name="password" id="passwordchange" type="password">
      <?php if (isset($_POST['submit']) && $passwordChanged == true ) echo "<p style='margin: -5px 30px; font-size:13px; color: green;'> Password succesfully changed. </p>" ?>
      <label for="emailchange">Change Email</label>
      <input maxlength="254" placeholder="Email" name="email" id="emailchange" type="email" <?php if (isset($_POST['submit']) and $_POST['email'] !== "" and ($_SESSION['invalidEmail'] == true or $_SESSION['emailExist'] == true)) echo 'style="border: 1px solid red"'?>>
      <?php if (isset($_POST['submit']) && $_POST['email'] !== "" && $_SESSION['emailExist'] == true) echo "<p style='margin: -5px 30px; font-size:13px; color: red;'> Email Exist. </p>" ?>
          <?php if (isset($_POST['submit']) && $_POST['email'] !== "" && $_SESSION['invalidEmail'] == true) echo "<p style='margin: -5px 35px; font-size:13px; color: red;'> Invalid Email. </p>" ?> 
          <?php if (isset($_POST['submit']) && $emailChanged == true ) echo "<p style='margin: -5px 30px; font-size:13px; color: green;'> Email succesfully changed. </p>" ?>
      <input class="editprofilebtn" name="submit" type="submit">

</form>
    </div>
    </div>
    
  <div id="test" class="container2 ">
  <?php require 'container3.php';
   
  ?>
 
  </div>
  </div>


</body>
</html>

<script src="mainpages/mainpage.js"></script>

<script>
/* Delete Account Button Modal */
let deleteAccountButton = document.querySelector(".deleteAccountButton");
let closeIconDeleteAccount = document.querySelector(".closeIconDeleteAccount");
let modalDeleteAccount = document.querySelector(".deleteAccountModal");

deleteAccountButton.addEventListener("click", function () {
  modalDeleteAccount.classList.toggle("noHidden");
});

closeIconDeleteAccount.addEventListener("click", function () {
  modalDeleteAccount.classList.toggle("noHidden");
});

modalDeleteAccount.addEventListener("click", (e) => {
  if (e.target !== e.currentTarget) {
    console.log("test");
  } else {
    modalDeleteAccount.classList.toggle("noHidden");
  }
});</script>