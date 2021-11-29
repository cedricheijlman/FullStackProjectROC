 
<h3 class="followingTitle">Your Following</h3> 
    <div id="followingList" class="followinglist">
      <?php
      $followingsExist = false;
        
      // Check Following loggedIn Account

         $queryFollowList = 'select * from following where fromid ="'.$_SESSION['loggedinId'].'"';
         $stmtFollowList = $conn->prepare($queryFollowList);
         $stmtFollowList->execute();
          while($c = $stmtFollowList->fetch()) {
             $follower = $c['followuserid'];
               $followingsExist = true;
             $checkNameAndImageQuery = 'select * from gebruikers where iduser ="'.$follower.'"';
             $stmtCheckNameAndImage = $conn->prepare($checkNameAndImageQuery);
             $stmtCheckNameAndImage->execute();
             while($d = $stmtCheckNameAndImage->fetch()) {
              $profilePictureCheck = $d['profilepicture'];
               $followUsername = $d['username'];
             if (!isset($profilePictureCheck) or $profilePictureCheck == "" ) {
                $profilePictureCheck = 'image.jpeg';
             }
          
               echo "<div class='nameFollowingItem'> <a href='profile.php?username=".$d['username']."'>
               <img src='image/".$followUsername.'/'.$profilePictureCheck."'> ".$d['username']."</a> </div>";


             }
             }
             
             if ($followingsExist == false) {
              echo "<div class='noResults'><p> You currently don't follow any accounts! </p></div>";
            }
            
      ?>
    </div>
    <h3 class="followingTitle">Your Followers</h3>
    <div id="followingList" class="followinglist">
      <?php

       // Check followers from logged In User
      $followersExist = false;

         $queryFollowList = 'select * from following where followuserid ="'.$_SESSION['loggedinId'].'"';
         $stmtFollowList = $conn->prepare($queryFollowList);
         $stmtFollowList->execute();
          while($c = $stmtFollowList->fetch()) {
             $follower = $c['fromid'];
               
             $checkNameAndImageQuery = 'select * from gebruikers where iduser ="'.$follower.'"';
             $stmtCheckNameAndImage = $conn->prepare($checkNameAndImageQuery);
             $stmtCheckNameAndImage->execute();
             while($d = $stmtCheckNameAndImage->fetch()) {
              $profilePictureCheck = $d['profilepicture'];
              $followUsernameTwo = $d['username'];
              $followersExist = true;
             if (!isset($profilePictureCheck) or $profilePictureCheck == "" ) {
                $profilePictureCheck = 'image.jpeg';
             }
          
               echo "<div class='nameFollowingItem'> <a href='profile.php?username=".$d['username']."'>
               <img src='image/".$followUsernameTwo.'/'.$profilePictureCheck."'> ".$d['username']."</a> </div>";


             }
             }
             
             if ($followersExist == false) {
               echo "<div class='noResults'><p> You don't have any accounts who follow you! </p></div>";
             }
             
            
      ?>
    </div>
  </div>