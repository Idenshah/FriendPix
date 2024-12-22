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
    <h1>My Pictures</h1>
    <form action="MyPictures.php" method="post" class="myForm">
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="selectAlbum">Select Album</label>
            </div>
            <div class="col-md-4">
                <select class="form-control text-center" name="selectAlbum" id="selectAlbum">
                    <option value="0">Select Your Album</option>
                    <?php
                    $sqlSelectAlbum = "SELECT Album_Id, Title FROM Album WHERE Owner_Id = :ownerId;";
                    $statementSelectAlbum = $myPdo->prepare($sqlSelectAlbum);
                    $statementSelectAlbum->execute([':ownerId' => $_SESSION["inputUserId"]]);
                    $listAlbums = $statementSelectAlbum->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($listAlbums as $Album) {
                        $selectedAlbumId = $Album["Album_Id"] ?? null;
                        $selectedAlbumTitle = $Album["Title"] ?? null;
                        $selectedAlbumOption = (isset($_POST["selectAlbum"]) && $_POST["selectAlbum"] == $selectedAlbumId) ? 'selected' : '';
                        echo '<option value="' . $selectedAlbumId . '"' . $selectedAlbumOption . '>' . htmlspecialchars($selectedAlbumTitle) . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>

    <div class="d-inline-flex p-1">
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $selectedAlbumId = $_POST["selectAlbum"] ?? null;
            if ($selectedAlbumId) {
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
                    echo '<img class="picture p-1" src="' . $imagePath . '" alt="' . htmlspecialchars($selectedPictureName) . '" 
             description="' . htmlspecialchars($selectedPictureDescription) . '" title="' . htmlspecialchars($selectedPictureTitle) . '" 
             data-picture-id="' . $selectedPictureId . '">';

                    // Fetch and store the comments for each picture
                    $sqlShowComment = "SELECT Comment_Id, Author_Id, Comment_Text FROM comment WHERE Picture_Id = :imageId ORDER BY Comment_Id DESC;";
                    $statementShowComment = $myPdo->prepare($sqlShowComment);
                    $statementShowComment->execute([':imageId' => $selectedPictureId]);
                    $listComments = $statementShowComment->fetchAll(PDO::FETCH_ASSOC);

                    // Dynamically create comments div to associate with image (but we don't render it yet)
                    foreach ($listComments as $Comment) {
                        $commentWriter = $Comment["Author_Id"] ?? null;
                        $commentText = $Comment["Comment_Text"] ?? null;
                        $commentId = $Comment["Comment_Id"] ?? null;
                        echo '<div class="comment" data-comment-id="' . $commentId . '" data-comment-writer="' . $commentWriter . '" 
             data-comment-text="' . htmlspecialchars($commentText) . '" data-picture-id="' . $selectedPictureId . '"></div>';
                    }
                }
            }
        }
        ?>
    </div>
</div>

<script src="myPictures.js"></script>
<?php
include('./Common/Footer.php');
?>
