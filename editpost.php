<?php 
require "connection/connection.php";
date_default_timezone_set('Europe/Amsterdam');
session_start();

// Check if User is set
if (!isset($_SESSION['loggedinUser'])) {
  header("Location: signup.php");
  exit();
}

// Check Post Content Before Edit
$contentCheckQuery = 'SELECT * from posts WHERE idpost ='.$_GET['idEditPost'];
$contentCheckStmt = $conn->prepare($contentCheckQuery);
$contentCheckStmt->execute();
while ($a = $contentCheckStmt->fetch()) {
  $postContent = $a['postcontent'];
}


// Validate Get URL ID Edit Post
$postNotExist = true;
$checkTheProfileQuery = 'select * from posts where idpost ='.$_GET['idEditPost'];
$checkProfileStmt = $conn->prepare($checkTheProfileQuery);
$checkProfileStmt->execute();
while ($a = $checkProfileStmt->fetch()) {
  if ($a['iduser'] !== $_SESSION['loggedinId']) {
    header("location: profile.php");
    exit();
  } else {
    $postNotExist = false;
  }
}

if ($postNotExist == true) {
  header("location: profile.php");
}

// If edit Post form is submitted
if (isset($_POST['editPostSubmit'])) {
   $postEditText = htmlspecialchars($_POST['postEditText']);
   

   $editPostQuery = 'UPDATE posts SET postcontent ="'.$postEditText.'" WHERE idpost ='.$_GET['idEditPost'];
   $editPostStmt = $conn->prepare($editPostQuery);
   $editPostStmt->execute();
   header("location: profile.php");
}





// Post Form
  require "post.php";
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css">
  <link rel="stylesheet" href="mainpages/post.css">
  <title>Edit Post</title>
  <link rel="stylesheet" href="mainpages/mainpagestyle.css">


  <style>
    
    .formEditPost {
      border-radius: 10px;
      box-shadow: rgba(50, 50, 93, 0.25) 0px 13px 27px -5px, rgba(0, 0, 0, 0.3) 0px 8px 16px -8px;
      position: relative;
      padding: 20px;
      margin: 15px;
      width: 80%;
      background-color: white;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .formEditPost a {
      margin: 10px 0;
      color: #0984e3;
    }

    

    .formEditPost textarea {
      font-family: "Lato";
      padding: 10px;
      border: 1px solid #0984e3;
      resize: none;
      margin: 20px 0;
    }



  </style>
</head>



<body>
<?php require "navbar.php";
require "sidebar.php";
require "postmodal.php";
?>

<div class="box">
<div class="container2">
    <div class="nameuser">
      <?php echo "<h5 style'font-size:19px;'>Signed In as: ".$_SESSION['loggedinUser']."!</h5>"?>
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
    <div class="allPosts">   
      <h1>Edit Post</h1>
<form class="formEditPost"  method="POST" 
    action="" 
    >

<label for="editPost">Edit Post:</label>

<textarea required  class="editPostText" name="postEditText" cols="40" rows="5"><?php echo $postContent ?></textarea>
 <a href="profile.php">Go back</a> <input class="postbtn postModalBtn" name="editPostSubmit" type="submit">

</form>


    
   
    </div>
  </div>
  <div class="container2">
    <?php require 'container3.php' ?>
  </div>
  </div>
</body>

</html>

<script src="mainpages/mainpage.js"></script>
