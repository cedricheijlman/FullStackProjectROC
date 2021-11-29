<?php
// When Post form is submitted
if (isset($_POST['postSubmit'])) {
$content = htmlspecialchars($_POST['postText']);
$iduser = $_SESSION['loggedinId'];
$postdate = date("Y-m-d H:i:s");
 
if (!empty($content)) {
$queryPost = "INSERT INTO posts (iduser,postcontent,postdate) VALUES (:iduser, :content, :postdate)";
$postStmt = $conn->prepare($queryPost);
$postStmt->execute(['iduser' => $iduser, 'content' => $content, 'postdate' => $postdate]);
header('location: mainpage.php');
} else {
 $noContent = true;
}

};

