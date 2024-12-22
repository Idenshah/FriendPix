<link href="style.css" rel="stylesheet" />
<?php
include("./Common/Header.php");

if (!isset($_SESSION['inputUserId'])) {
    $_SESSION['RequestedPage'] = $_SERVER['REQUEST_URI'];
    header('Location: Login.php');
    exit();
}
$dbConnection = parse_ini_file("DataSource.ini");
extract($dbConnection);
$myPdo = new PDO($dsn, $user, $password);
$uploadDirectory = './common/images/';
?>
<div class="container">
    <h1>Friends Pictures</h1>
    <?php
    if (isset($_SESSION['successMessage'])) {
        echo "<p class='mb-4 p-2 text-center text-success'>" . $_SESSION['successMessage'] . "</p>";
        // Unset the message so it doesn't display on the next load
        unset($_SESSION['successMessage']);
    }
    ?>
    <form action="FriendsPictures.php" method="post" class="myForm">
        <div class="row mb-3">
            <div class="col-md-3">
                <label for="selectFriend">Select Your Friend Id</label>
            </div>
            <div class="col-md-2">
                <select class="form-control text-center" name="selectFriend" id="selectFriend">
                    <option value="0">Select Your Friend</option>
                    <?php
                    $sqlSelectFriend = "SELECT Friend_RequesterId, Friend_RequesteeId, status 
                    FROM friendship 
                    WHERE status = 'accepted' 
                    AND (Friend_RequesteeId = :user OR Friend_RequesterId = :user)";
                    $statementSelectFriend = $myPdo->prepare($sqlSelectFriend);
                    $statementSelectFriend->execute([':user' => $_SESSION["inputUserId"]]);
                    $friends = $statementSelectFriend->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($friends as $friend) {
                        if ($friend["Friend_RequesteeId"] != $_SESSION["inputUserId"]) {
                            $friendUsername = $friend["Friend_RequesteeId"];
                        } else {
                            $friendUsername = $friend["Friend_RequesterId"];
                        }

                        $selectedFriendId = $friendUsername ?? null;
                        $selectedFriend = $friendUsername ?? null;
                        $selectedFriendUserId = (isset($_POST["selectFriend"]) && $_POST["selectFriend"] == $selectedFriendId) ? 'selected' : '';

                        echo '<option value="' . $selectedFriendId . '" ' . $selectedFriendUserId . '>' . $selectedFriend . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>

        <?php
        // Check if the form is submitted and the "selectFriend" value is set
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["selectFriend"]) && $_POST["selectFriend"] != "0") {
            $selectedFriendId = $_POST["selectFriend"];

            echo '  
              <div class="row mb-3">
             <div class="col-md-3">
            <label for="selectFriend">Select Your Friend Album</label>
        </div>
        <div class="col-md-2">
            <select class="form-control text-center" name="selectFriendAlbum" id="selectFriendAlbum">
                <option value="0">Select Friend\'s Album</option>';

            // Fetch albums based on selected friend's ID
            $sqlSelectFriendAlbum = "SELECT Album_Id, Title, Description, Owner_Id, Accessibility_Code 
                                 FROM Album 
                                 WHERE Owner_Id = :friendId AND Accessibility_Code = 'public'";

            $statementSelectFriendAlbum = $myPdo->prepare($sqlSelectFriendAlbum);
            $statementSelectFriendAlbum->execute([':friendId' => $selectedFriendId]);
            $listAlbums = $statementSelectFriendAlbum->fetchAll(PDO::FETCH_ASSOC);

            foreach ($listAlbums as $Album) {
                $selectedAlbumId = $Album["Album_Id"] ?? null;
                $selectedAlbumTitle = $Album["Title"] ?? null;
                $selectedAlbumDescription = $Album["Description"] ?? null;

                // Check if this album is selected based on the post data
                $selectedAlbum = (isset($_POST["selectFriendAlbum"]) && $_POST["selectFriendAlbum"] == $selectedAlbumId) ? 'selected' : '';

                echo '<option value="' . $selectedAlbumId . '" ' . $selectedAlbum . '>' . $selectedAlbumTitle . '</option>';
            }

            echo '</select>
        </div>
         <div class="col-md-4">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </div>';
        }
        ?>
        <?php
        // Check if the form is submitted and the "selectFriend" value is set
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["selectFriendAlbum"]) && $_POST["selectFriendAlbum"] != "0") {
            $selectedAlbumId = $_POST["selectFriendAlbum"];

            $sqlSelectAlbumPictures = "SELECT Album.Album_Id, Album.Title, Album.Description, Album.Owner_Id, Album.Accessibility_Code,
               Picture.Picture_Id, Picture.file_name, Picture.title, Picture.description 
               FROM Album 
               JOIN Picture ON Album.Album_Id = Picture.Album_Id
               WHERE Album.Album_Id = :albumId;";
            $statementSelectAlbumPictures = $myPdo->prepare($sqlSelectAlbumPictures);
            $statementSelectAlbumPictures->execute([':albumId' => $selectedAlbumId]);
            $listAlbumPictures = $statementSelectAlbumPictures->fetchAll(PDO::FETCH_ASSOC);
            foreach ($listAlbumPictures as $Picture) {
                $selectedPictureId = $Picture["Picture_Id"] ?? null;
                $selectedPictureTitle = $Picture["title"] ?? null;
                $selectedPictureDescription = $Picture["description"] ?? null;
                $selectedPictureName = $Picture["file_name"] ?? null;
                $imagePath = $uploadDirectory . $selectedPictureName;
                echo '<img class="picture p-1" src="' . $imagePath . '" alt="' . $selectedPictureName . '" description="' . $selectedPictureDescription . '" title="' . $selectedPictureTitle . '" data-picture-id="' . $Picture["Picture_Id"] . '">';
            }
        }
        ?>

        <?php
        // Check if the form is submitted and the "selectFriend" value is set
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["imageComment"]) && !empty($_POST["imageComment"])) {
            // Get comment data from POST request
            $commentText = $_POST["imageComment"];
            $authorId = $_SESSION["inputUserId"];
            $pictureId = $_POST["pictureId"];  // Assuming pictureId is passed with the comment
            // Prepare and execute the SQL to insert the comment into the database
            $sqlInsertComment = "INSERT INTO comment (Author_Id, Picture_Id, Comment_Text) VALUES (?, ?, ?)";
            $statementInsertComment = $myPdo->prepare($sqlInsertComment);
            $statementInsertComment->execute([$authorId, $pictureId, $commentText]);

            $_SESSION['successMessage'] = "Comment submitted successfully.";
            header("Location:FriendsPictures.php");
            exit();
        }
        ?>
    </form>


</div>
<script src="friendsPictures.js"></script>
<?php
include('./Common/Footer.php');
?>
