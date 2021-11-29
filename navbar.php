<?php 


// set Profile User Icon Navbar
$profileNavQuery = 'SELECT * FROM gebruikers where username ="'.$_SESSION['loggedinUser'].'"';
$stmtProfileNavQuery = $conn->prepare($profileNavQuery);
$stmtProfileNavQuery->execute();
while($a = $stmtProfileNavQuery->fetch()) {
 $profileNav = $a['profilepicture'];

   if (!isset($profileNav) or $profileNav == "") {
     $profileNav = 'image.jpeg';
   }

}
?>

<header>
<nav>
<div class="container">
<i  class="fas fa-bars hamburger"></i>
  <h2 class="logo">Logo</h2>
  <div class="searchbardiv">
  <i class="fas fa-search iconsearch"></i>
    <input placeholder="Search for people" onkeyup="check(this.value)" class="searchbar" type="search">
    <div id="contentSearch" class="contentSearch">

    </div>
    <script>
      let content = document.getElementById("contentSearch");
/* HTTP Request for navbar live search*/
function check(x) {
  if (x.length == 0) {
    content.style.display = "none";
  } else {
    var XML = new XMLHttpRequest();
    XML.onreadystatechange = function () {
      content.style.display = "flex";
      if (XML.readyState == 4 && XML.status == 200) {
        content.innerHTML = XML.responseText;
      }
    };

    XML.open("GET", "search.php?q=" + x, true);
    XML.send();
  }
}
    </script>
    </div>
    
  <div class="rightnavitems">


  
    <button class="postbtn">+ Post</button>
   <div class="imgbox">
      <img class="profile" src=<?php echo "'image/".$_SESSION['loggedinUser']."/".$profileNav."'"?> alt="">
      <ul class="list">
        <a href="profile.php">Profile</a>
        <a href="logout.php">Logout</a>
        
      </ul>
   </div>
</div>
</div>
</nav>
</header>