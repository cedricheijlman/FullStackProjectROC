<?php 
session_start();
require "connection/connection.php";
date_default_timezone_set('Europe/Amsterdam');

require "post.php";

// variable for later check if user exists
$rowsuser = 0;


// Check if user is logged in;
if (!isset($_SESSION['loggedinUser'])) {
  header("Location: signup.php");
  exit();
}

  


  // Set profile image, and profile cover from database
  $profileUsername = htmlspecialchars($_GET['username']);
  $query = 'SELECT * FROM gebruikers where username ="'.$profileUsername.'"';
  $stmt = $conn->prepare($query);
  $stmt->execute();
  while($a = $stmt->fetch()) {
    $userid = $a['iduser'];
    $profileName = $a['username'];
    $imgcoverr = $a['imgcover'];
    $profilepicture = $a['profilepicture'];
    $rowsuser++;
    if (!isset($imgcoverr) or $imgcoverr == "") {
      $imgcoverr = "image.jpeg";
      
    }

    if (!isset($profilepicture) or $profilepicture == "") {
      $profilepicture = "image.jpeg";
      
    }
  }


// check if logged in user follows profile
  $queryFollowButton = 'select count(*) from following where fromid ="'.$_SESSION['loggedinId'].'" AND followuserid="'.$userid.'"';
 $stmtFollowButton = $conn->prepare($queryFollowButton);
 $stmtFollowButton->execute();
 while($a = $stmtFollowButton->fetch()) {
 
  if ($a[0] == 1) {
    $_SESSION['followed'] = true;
  } else  {
    $_SESSION['followed'] = false;
  }

}

// follow profile / unfollow profile
  if (isset($_POST['follow'])) {
    $queryAddFollow = 'INSERT INTO following (fromid, followuserid) VALUES (?,?)';
    $stmtAddFollow = $conn->prepare($queryAddFollow);
    $stmtAddFollow->execute([$_SESSION['loggedinId'], $userid]);

    $_SESSION['followed'] = true;
    header('Location: '.$_SERVER['REQUEST_URI']);
  }
  
  if (isset($_POST['unfollow'])) {
    $queryDeleteFollow = 'DELETE FROM following WHERE fromid ="'.$_SESSION['loggedinId'].'" AND followuserid="'.$userid.'"';
 $stmtDeleteFollow = $conn->prepare($queryDeleteFollow);
 $stmtDeleteFollow->execute();
    $_SESSION['followed'] = false;
    header('Location: '.$_SERVER['REQUEST_URI']);
  }

  // Check if user exists
  if ($rowsuser == 0) {

   header('Location: profile.php?username='.$_SESSION['loggedinUser']);
  }

  // Count following
  $queryTwo ='select count(*) from following where fromid ="'.$userid.'"';
  $stmt = $conn->prepare($queryTwo);
  $stmt->execute();
  while($a = $stmt->fetch()) {
    $following = $a[0];
  }

  // Count Followers
  $queryThree ='select count(*) from following where followuserid ="'.$userid.'"';
  $stmt = $conn->prepare($queryThree);
  $stmt->execute();
  while($a = $stmt->fetch()) {
    $followers = $a[0];
  }

// Photo cover Change Form Submitted
  if (isset($_POST['submit'])) {
   
   
    $imgError = false;
    $_SESSION['imgError'] = false;
    $user = $_SESSION['loggedinUser'];
  
    require_once 'connection/connection.php';
  
    $filename = $_FILES["uploadfile"]["name"];
    $file = $_FILES["uploadfile"]["tmp_name"];
    $folder = "image/".$_SESSION['loggedinUser'].'/'.$filename;
    move_uploaded_file($file, $folder);
  
    
    if ($filename !== "" and isset($filename) and file_exists($folder)) {
     
      $query = 'UPDATE gebruikers SET imgcover ="'.$filename.'" WHERE username ="'.$user.'"';
      $stmt = $conn->prepare($query);
      $stmt->execute();
      $imgChanged = true;
      header('Location: '.$_SERVER['REQUEST_URI']);
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
  <link rel="stylesheet" href="mainpages/profilepage.css">
  <link rel="stylesheet" href="mainpages/post.css">
  <title>Profile</title>
</head>



<body>
<?php require "navbar.php";
require "sidebar.php";
require "postmodal.php";

?>

<div class="modal">

          <form  method="POST" 
              action="" 
              enctype="multipart/form-data">
          <i class="fas fa-times coverIcon"></i>
          <label for="file">Change Profile Cover</label>
            <input accept=".jpg, .jpeg, .png" type="file" 
            id="uploadfile"
                   name="uploadfile" 
                    value="">
            <?php if (isset($_POST['submit']) && $_SESSION['imgError'] == true && $filename !== "" ) echo "<p style='margin: -5px 30px; font-size:13px; color: red;'> Use Another Image. </p>" ?>
            <input name="submit" type="submit">
          
          </form>

    </div>

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
    

   

    <div class="coverimgbox">
      <?php if ($_GET['username'] == $_SESSION['loggedinUser'])
    echo "<div class='editProfileCoverBox'><i class='fas fa-edit'></i></div>"
    ?>
      <img src=<?php echo "'image/$profileName/$imgcoverr'"  ?> class="coverimg" alt="">

      <img class="profileimg" src=<?php echo "'image/$profileName/$profilepicture'"  ?> alt="">
        
        <h3><?php echo $profileName ?></h3>
      <div class="followersbox">
      <p >Followers: <?php echo $followers  ?></p>
        <p>Following:  <?php echo $following  ?></p>
      </div>
      <div class='buttonsprofile'> 
      <?php 
      if ($_SESSION['loggedinUser'] == $_GET['username']) 
      echo "
      <a class='btnprofile' href='editprofile.php?username=".$_SESSION['loggedinUser']."'>Edit Profile</a>"
      ?>
      <?php 
      if ($_SESSION['loggedinUser'] !== $_GET['username'] and $_SESSION['followed'] == false) 
      echo "
      <form method='post'>
      <input name='follow' type='submit' value='Follow' placeholder='Follow' class='btnprofile' id='follow'>
      </form>
      "
      ?>
       <?php 
      if ($_SESSION['loggedinUser'] !== $_GET['username'] and $_SESSION['followed'] == true) 
      echo "
      <form method='post'>
      <input name='unfollow'  type='submit' value='Unfollow' placeholder='Unfollow' class='btnprofile' id='follow'>
      
      </form>
      "
      ?>
      
     </div>
    </div>

   <div class="allPosts">
   <h1>Thoughts</h1>
    <?php 
    $NoPostCheck = true;
    $queryCheckPosts = 'SELECT posts.postcontent, posts.postdate, posts.idpost, gebruikers.username, gebruikers.profilepicture FROM posts INNER JOIN gebruikers ON gebruikers.iduser = posts.iduser  WHERE gebruikers.username ="'.$_GET['username'].'" ORDER BY posts.postdate DESC';
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
  


     

      // check if image from poster is set
      if (!isset($profilePicturePost) or $profilePicturePost == "") {
        $profilePicturePost = "image.jpeg";
      }

      if ($theUsername == $_SESSION['loggedinUser']) {
        $postSameAsLoggedUser = '<div class="postActions"><div></div><div class="deleteOrEditBox"><button class="postBtnAction deleteBtnPost"><a href="deletepost.php?idPost='.$idPost.'">Delete</a></button><Button class="postBtnAction editPostBtn"><a href="editpost.php?idEditPost='.$idPost.' ">Edit</a></Button></div></div> ';
      } else {
        $postSameAsLoggedUser = ' ';
      }
  
     // Display posts on screen
      echo '<div class="postitem">
      <div class="postProfileBox"><a style="color:black;" href="profile.php?username='.$theUsername.'"><img src="image/'.$profileName.'/'.$profilePicturePost.'" alt="">  <div class="usernameAndDate"><h4>'.$theUsername.'</h4></a><p>'.$thePostDate.'</p></div> </div>

      <p class="postContentText">'.$thePostContent.'</p>
      '.$postSameAsLoggedUser.'
     </div>';
      $NoPostCheck = false;
    }

    if ($NoPostCheck == true) {
      echo "<p style='margin-top: 25px; color: gray;'>User Has Posted No Thoughts</p>";
    }
    ?>



  
   </div>
  </div>
  <div class="container2">
   <?php require 'container3.php' ?>
  </div>



 

</body>
</html>




<script src="mainpages/mainpage.js"></script>
<script>

 <?php 
 // Button To Open Change Cover Modal
 if ($_GET['username'] == $_SESSION['loggedinUser'])  
  echo " let clicker = document.querySelector('.editProfileCoverBox');
   let closeIcon = document.querySelector('.coverIcon');
   let modal = document.querySelector('.modal');

    clicker.addEventListener('click', function() {
      modal.classList.toggle('hidden');
    })

    closeIcon.addEventListener('click', function() {
      modal.classList.toggle('hidden');
    });


   modal.addEventListener('click', e => {
  if(e.target !== e.currentTarget) {
    console.log('test');
  } else {
  modal.classList.toggle('hidden');
  }
});
"
?>




 

 
    </script>