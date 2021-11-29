<?php
require "connection/connection.php";


if (isset($_GET['q'])) {
  $data = htmlspecialchars($_GET['q']);
}


    $query = "SELECT * FROM gebruikers where username like '$data%' ";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    while($b = $stmt->fetch()) {
     $username = $b['username'];
      $profilepicture = $b['profilepicture'];
  
      if (!isset($profilepicture) or $profilepicture == "") {
        $profilepicture = "image.jpeg";
        
      }
      

     echo "
     <div class='profielbox'>
     <a class='searchresultitem' href='profile.php?username=".$b['username']."'><img src='image/".$username."/".$profilepicture."'>".$b['username']."</a>
     </div>
     ";
      
    }