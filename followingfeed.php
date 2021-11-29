<?php 
session_start();
require "connection/connection.php";
date_default_timezone_set('Europe/Amsterdam');


// Check if user is logged in
if (!isset($_SESSION['loggedinUser'])) {
  header("Location: signup.php");
  exit();
}

// check if GET is the same as loggedinUser
if (!isset($_GET['username']) OR $_GET['username'] !== $_SESSION['loggedinUser']) {
header("location: followingfeed.php?username=".$_SESSION['loggedinUser']);

}

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
  <title>Following Feed</title>
  <link rel="stylesheet" href="mainpages/mainpagestyle.css">
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
     <h2 style="margin-bottom: 20px;"> Thoughts From Your Following </h2>
     
    <?php  
    $noFollowingCheck = true;
    $following = [];
    $queryCheckFollowing = 'SELECT * from following where fromid ='.$_SESSION['loggedinId'];
    $stmtCheckFollowing = $conn->prepare($queryCheckFollowing);
    $stmtCheckFollowing->execute();
    while ($a = $stmtCheckFollowing->fetch()) {
     $following[] = $a['followuserid'];
    
    }

    
    $followingIds = join("','", $following);
    
    $queryCheckPosts = "SELECT posts.postcontent, posts.postdate, posts.idpost, gebruikers.username, gebruikers.profilepicture FROM posts INNER JOIN gebruikers ON gebruikers.iduser = posts.iduser  WHERE  gebruikers.iduser IN ('$followingIds') ORDER BY posts.postdate DESC";
    $stmtCheckPosts = $conn->prepare($queryCheckPosts);
    $stmtCheckPosts->execute();
    while($g = $stmtCheckPosts->fetch()) {

     // get post information
     $idPost = $g['idpost'];
      $profilePicturePost = $g['profilepicture'];
     
      $thePostDate = $g['postdate'];
      $thePostDate = substr($thePostDate, 0, -3);
      $theUsername = $g['username'];
      $thePostContent = htmlspecialchars($g['postcontent']);
  


     

      // check if image from post user is set
      if (!isset($profilePicturePost) or $profilePicturePost == "") {
        $profilePicturePost = "image.jpeg";
      }

      if ($theUsername == $_SESSION['loggedinUser']) {
        $postSameAsLoggedUser = '<div class="postActions"><div></div><div class="deleteOrEditBox"><button class="postBtnAction deleteBtnPost"><a href="deletepost.php?idPost='.$idPost.'">Delete</a></button><Button class="postBtnAction "><a href="">Edit</a></Button></div></div> ';
      } else {
        $postSameAsLoggedUser = ' ';
      }
  
     // Display posts on screen
      echo '<div class="postitem">
      <div class="postProfileBox"><a style="color:black;" href="profile.php?username='.$theUsername.'"><img src="image/'.$theUsername.'/'.$profilePicturePost.'" alt="">  <div class="usernameAndDate"><h4>'.$theUsername.'</h4></a><p>'.$thePostDate.'</p></div> </div>

      <p class="postContentText">'.$thePostContent.'</p>
      '.$postSameAsLoggedUser.'
     </div>';

     $noFollowingCheck = false;
    }
       // If there are no posts
    if ($noFollowingCheck == true) {
      echo "<p style='color: gray;word-break:break-word; width: 70%; text-align: center;'> You don't follow anyone Or none of your following has posted anything! </p>";
    }
    
    ?>
    </div>
  </div>
  <div class="container2">
    <?php require 'container3.php' ?>
  </div>
  </div>
</body>

</html>

<script src="mainpages/mainpage.js"></script>
