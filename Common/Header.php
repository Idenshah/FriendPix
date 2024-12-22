<?php
session_start();
//var_dump($_SESSION);
?>

<!DOCTYPE html>
<html lang="en" style="position: relative; min-height: 100%;">

<head>
    <title>FriendPix</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body style="padding-top: 70px; margin-bottom: 60px;">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
      
            <img src="Common/images/logo/test.png" alt="FriendPix logo" >

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="MyFriends.php">My Friends</a></li>
                    <li class="nav-item"><a class="nav-link" href="MyAlbums.php">My Albums</a></li>
                    <li class="nav-item"><a class="nav-link" href="MyPictures.php">My Pictures</a></li>
                    <li class="nav-item"><a class="nav-link" href="FriendsPictures.php">Friends Pictures</a></li>
                    <li class="nav-item"><a class="nav-link" href="UploadPictures.php">Upload Pictures</a></li>
                    <li class="nav-item"><a class="nav-link" href="AddFriend.php">Add Friend</a></li>

                    <?php
                    // Check if the user is logged in
                    if (isset($_SESSION['inputUserId'])) {
                        echo '<li class="nav-item"><a class="nav-link" href="LogOut.php">Log Out</a></li>';
                    } else {
                        echo '<li class="nav-item"><a class="nav-link" href="Login.php">Log In</a></li>';
                    }
                 
                    ?>
                </ul>
            </div>
        </div>
    </nav>
</body>

</html>
