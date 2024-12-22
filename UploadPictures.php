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

$imageTitleRegex = '/^.{1,256}$/';
$imageDescriptionRegex = '/^.{1,3000}$/';
$allowedFileTypes = ['image/jpeg', 'image/png', 'image/gif'];

$imageTitle = "";
$imageDescription = "";
$uploadedFileName = "";

$uploadedFileType = "";
$uploadFilePaths = array();
$uploadDirectory = './Common/images/';
$uploadedFiles = isset($_FILES['uploadFile']) ? $_FILES['uploadFile'] : null;

if ($uploadedFiles && is_array($uploadedFiles['name']) && is_array($uploadedFiles['tmp_name'])) {
    $totalFiles = count($uploadedFiles['name']);

    for ($i = 0; $i < $totalFiles; $i++) {
        $uploadedFileName = $uploadedFiles['name'][$i];
        $uploadedFileType = $uploadedFiles['type'][$i];
        $uploadFilePaths[] = $uploadDirectory . $uploadedFileName;

        if (in_array($uploadedFileType, $allowedFileTypes)) {
            if ($uploadedFileType === 'image/jpeg') {
                $sourceImage = imagecreatefromjpeg($uploadedFiles['tmp_name'][$i]);
            } elseif ($uploadedFileType === 'image/png') {
                $sourceImage = imagecreatefrompng($uploadedFiles['tmp_name'][$i]);
            } elseif ($uploadedFileType === 'image/gif') {
                $sourceImage = imagecreatefromgif($uploadedFiles['tmp_name'][$i]);
            }

            $thumbnail = imagecreatetruecolor(100, 100);
            imagecopyresampled($thumbnail, $sourceImage, 0, 0, 0, 0, 100, 100, imagesx($sourceImage), imagesy($sourceImage));

            $thumbnailFilePath = $uploadDirectory . 'thumbnails/' . 'thumb_' . $uploadedFileName;
            imagejpeg($thumbnail, $thumbnailFilePath);

            imagedestroy($sourceImage);
            imagedestroy($thumbnail);
        }
    }
}

$errorMessageSelectAlbum = "";
$errorMessageInputFile = "";
$errorMessageImageTitle = "";
$errorMessageImageDescription = "";
$successMessage = "";

if (isset($_POST['submit'])) {

    $selectedAlbumTitle = trim($_POST['selectAlbum']);
    if (empty($selectedAlbumTitle)) {
        $errorMessageSelectAlbum = "Please select an Album from the list.";
    } else {
        $errorMessageSelectAlbum = "";
    }

    if (empty($uploadedFileName)) {
        $errorMessageInputFile = "No any file is selected!";
    } elseif (!in_array($uploadedFileType, $allowedFileTypes)) {
        $errorMessageInputFile = "Invalid file type. Only JPG (JPEG), GIF, and PNG are allowed.";
    } else {
        $errorMessageInputFile = "";
    }

    $imageTitle = trim($_POST['imageTitle']);
    if (empty($imageTitle)) {
        $errorMessageImageTitle = " Image title is required.";
    } elseif (!preg_match($imageTitleRegex, $imageTitle)) {
        $errorMessageImageTitle = " Selected image title is not valid.";
    } else {
        $errorMessageImageTitle = "";
    }

    $imageDescription = trim($_POST['imageDescription']);
    if (empty($imageDescription)) {
        $errorMessageImageDescription = " Image description is required.";
    } elseif (!preg_match($imageDescriptionRegex, $imageDescription)) {
        $errorMessageImageDescription = " Selected image description is not valid.";
    } else {
        $errorMessageImageDescription = "";
    }

    if (empty($errorMessageSelectAlbum) && empty($errorMessageInputFile) && empty($errorMessageImageTitle) && empty($errorMessageImageDescription)) {
        $i = 0;

        for ($i = 0; $i < $totalFiles; $i++) {
            $uploadedFileName = $uploadedFiles['name'][$i];
            $uploadedFileType = $uploadedFiles['type'][$i];

            if (move_uploaded_file($uploadedFiles['tmp_name'][$i], $uploadFilePaths[$i])) {
                $sqlInsertImage = "INSERT INTO picture (Album_Id, File_Name, Title, Description) VALUES (?, ?, ?, ?)";
                $statmentPicture = $myPdo->prepare($sqlInsertImage);
                $statmentPicture->execute([$selectedAlbumTitle, $uploadedFileName, $imageTitle, $imageDescription]);
               $_SESSION['successMessage'] = "File(s) uploaded successfully and stored in the specified Album.";
                header("Location: UploadPictures.php");
                exit();
            } else {
                $errorMessageInputFile = "File upload failed. Please try again.";
            }
        }
    }
}
?>
<div class="container">
    <h1>Upload Pictures</h1>
    <p>Accepted picture types: JPG (JPEG), GIF and PNG.</p>
    <p>You can upload multiple picture at a time by pressing the shift key while selecting pictures.</p>
    <p class="mb-4">When uploading multiple pictures, the title and description fields will be applied to all pictures.</p>
    <?php
    if (isset($_SESSION['successMessage'])) {
        echo "<p class='mb-4 p-2 text-center text-success'>" . $_SESSION['successMessage'] . "</p>";
        // Unset the message so it doesn't display on the next load
        unset($_SESSION['successMessage']);
    }
    ?>

<form method="post" action="UploadPictures.php" class="myForm" id="myForm" enctype="multipart/form-data">
    <div class="row mb-3">
        <div class="col-md-4">
            <label for="selectAlbum">Upload To Album:</label>
        </div>
        <div class="col-md-4">
            <select class="form-control text-center" name="selectAlbum" id="selectAlbum">
                <option value="0">Select Your Album </option>
                <?php
                $sqlSelectAlbum = "SELECT Album_Id, Title FROM Album WHERE Owner_Id = :ownerId;";
                $statementSelectAlbum = $myPdo->prepare($sqlSelectAlbum);
                $statementSelectAlbum->execute([':ownerId' => $_SESSION["inputUserId"]]);
                $listAlbums = $statementSelectAlbum->fetchAll(PDO::FETCH_ASSOC);
                foreach ($listAlbums as $Album) {
                    $selectedAlbumId = $Album["Album_Id"] ?? null;
                    $selectedAlbumTitle = $Album["Title"] ?? null;
                    $selectedImageAlbum = (isset($_POST["selectAlbum"]) && $_POST["selectAlbum"] == $selectedAlbumId) ? 'selected' : '';
                    echo '<option value= "' . $selectedAlbumId . '"' . $selectedImageAlbum . '>' . $selectedAlbumTitle . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="col-md-4 text-danger">
<?php echo $errorMessageSelectAlbum; ?>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-4">
            <label for="inputFile">File To Upload:</label>
        </div>
        <div class="col-md-4">
            <input class="form-control" type="file" name="uploadFile[]" accept="image/jpeg, image/png, image/gif" multiple />
        </div>
        <div class="col-md-4 text-danger">
<?php echo $errorMessageInputFile; ?>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-4">
            <label for="imageTitle">Title:</label>
        </div>
        <div class="col-md-4">
            <input type="text" class="form-control" name="imageTitle" id="imageTitle" value="<?php echo $imageTitle ?>" />
        </div>
        <div class="col-md-4 text-danger">
<?php echo $errorMessageImageTitle; ?>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-4">
            <label for="selectDescription">Description:</label>
        </div>
        <div class="col-md-4">
            <textarea class="form-control" rows="6" name="imageDescription" id="imageDescription"> <?php echo $imageDescription ?> </textarea>
        </div>
        <div class="col-md-4 text-danger">
<?php echo $errorMessageImageDescription; ?>
        </div>
    </div>

    <div class="mt-5 signupButton">
        <button class="btn btn-primary" type="submit" name="submit" value="submit">Submit</button>
        <button class="btn btn-danger ms-3" type="clear" name="clear" value="clear">Clear</button>
    </div>
</form>
</div>
 <?php
include('./Common/Footer.php');
?>