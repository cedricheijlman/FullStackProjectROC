<div class="sidebar">
  <a <?php echo "href='mainpage.php?username=".$_SESSION['loggedinUser']."'"; ?> >Home</a>
  <a <?php echo "href='followingfeed.php?username=".$_SESSION['loggedinUser']."'"; ?> >Following Feed</a>
  <a <?php echo "href='profile.php?username=".$_SESSION['loggedinUser']."'"; ?> >Profile</a>
  <a <?php echo "href='editprofile.php?username=".$_SESSION['loggedinUser']."'"; ?> >Settings</a>
  <a href="logout.php">Log Out</a>


</div>