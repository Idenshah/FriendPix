<?php
include('./common/header.php');

if (!isset($_SESSION['inputUserId'])) {
    $_SESSION['RequestedPage'] = $_SERVER['REQUEST_URI'];
    header('Location: Login.php');
    exit();
}

if(isset($_POST["submit"])){
    session_unset();
    session_destroy();
    header("Location: index.php");
}
if(isset($_POST["cancel"])){

    header("Location: MyAlbums.php");
}
?>
<div class="container mt-5">
    <h1 class=" text-center">Log Out</h1>
    <p class="text-center mb-4"> Do You Want to Log Out From FriendPix ?</p>
    <form method="Post" action="LogOut.php" class="myForm" id="myForm">
         <div class="mt-5 signupButton">
            <button class="btn btn-primary" type="submit" name="submit" value="submit">Yes</button>
            <button class="btn btn-danger ms-3" type="submit" name="cancel" value="cancel">No</button>
        </div>
    </form>
</div>
 <?php
include('./Common/Footer.php');
?>