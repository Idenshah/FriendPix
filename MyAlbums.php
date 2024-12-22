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
//        include ('Validation.php');

if (isset($_POST['save'])) {
    $albumId = $_POST['albumId']; // Get the album ID from the form
    $accessibilityCode = $_POST['inputAccessibility']; // Get the selected accessibility code
    // Update the album's accessibility code
    $statementAlbum = $myPdo->prepare("UPDATE Album SET Accessibility_Code = ? WHERE Album_Id = ?");
    $statementAlbum->execute([$accessibilityCode, $albumId]);

    // Optionally redirect to the same page to see changes
    header("Location: MyAlbums.php");
    exit();
}

if (isset($_POST['delete'])) {
    $albumId = $_POST['delete']; // Get the album ID from the form
    // Update the album's accessibility code
    $statementDeletePictures = $myPdo->prepare("delete from picture where Album_Id = ?");
    $statementDeletePictures->execute([$albumId]);
    $statementDeleteAlbum = $myPdo->prepare("delete from Album where Album_Id = ?");
    $statementDeleteAlbum->execute([$albumId]);

    // Optionally redirect to the same page to see changes
    header("Location: MyAlbums.php");
    exit();
}
?>

<div class="container mt-4">
    <h1 class="ms-4">My Albums</h1>
    <p class="ms-4 mb-3">Welcome <strong><?php echo htmlspecialchars($_SESSION["inputName"]); ?></strong> (Not you? Change user <a href="Login.php" class="primary" style="text-decoration: none">here</a>)</p>
    <p class="ms-4"><a href="AddAlbum.php" style="text-decoration: none" class="primary">Create New Album</a></p>
    <div class="ms-4 border border-1"></div>
    <form method="post" action="MyAlbums.php" class="myForm">
        <table class="mt-2 ms-4 table border-0">
            <tr>
                <th style="border-bottom: none;">Title</th>
                <th style="border-bottom: none;">Accessibility</th>
                <th style="border-bottom: none;">Number of Pictures</th>
                <th style="border-bottom: none;"></th>
            </tr>
            <?php
            $sqlAlbum = 'SELECT Album.Album_Id, Album.Title, Album.Owner_Id, Album.Accessibility_Code, COUNT(Picture.File_name) AS Picture_Count
                         FROM Album
                         Left JOIN Picture ON Album.Album_Id = Picture.Album_Id
                         GROUP BY Album.Album_Id, Album.Title, Album.Owner_Id, Album.Accessibility_Code
                         HAVING Album.Owner_Id = ?';
            $statementAlbumData = $myPdo->prepare($sqlAlbum);
            $statementAlbumData->execute([$_SESSION["inputUserId"]]);
            $AlbumDataRows = $statementAlbumData->fetchAll(PDO::FETCH_ASSOC);

            foreach ($AlbumDataRows as $AlbumDataRow) {
                echo '<tr>'
                . '<td style="border-bottom: none;">' . htmlspecialchars($AlbumDataRow['Title']) . '</td>';

                // Check if the form is in edit mode
                if (isset($_POST['edit']) && $_POST['edit'] == $AlbumDataRow['Album_Id']) {
                    echo '<td class="access" style="border-bottom: none;">'
                    . '<select class="form-control" id="inputAccessibility" name="inputAccessibility">';

                    // Fetch accessibility options
                    $sqlAccessibility = 'SELECT Accessibility_Code, Description FROM Accessibility';
                    $stmtSelectAccessibility = $myPdo->prepare($sqlAccessibility);
                    $stmtSelectAccessibility->execute();
                    $allAccessibilities = $stmtSelectAccessibility->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($allAccessibilities as $Accessibility) {
                        $Accessibility_Code = $Accessibility['Accessibility_Code'] ?? null;
                        $Accessibility_Description = $Accessibility['Description'] ?? null;
                        $selectedAccessibility = ($AlbumDataRow['Accessibility_Code'] == $Accessibility_Code) ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars($Accessibility_Code) . '" ' . $selectedAccessibility . '>' . htmlspecialchars($Accessibility_Description) . '</option>';
                    }

                    echo '</select></td>';
                    echo '<input type="hidden" name="albumId" value="' . htmlspecialchars($AlbumDataRow['Album_Id']) . '">'; // Hidden input for album ID
                } else {
                    echo '<td class="access" style="border-bottom: none;">' . htmlspecialchars($AlbumDataRow['Accessibility_Code']) . '</td>';
                }

                echo '<td style="border-bottom: none;">' . htmlspecialchars($AlbumDataRow['Picture_Count']) . '</td>'
                . '<td style="border-bottom: none;">'
                . '<button class="btn bg-success save" type="submit" name="save"> Save </button>'
                . '<button class="btn bg-warning edit ms-1" type="submit" name="edit" value="' . $AlbumDataRow['Album_Id'] . '"> Edit </button>'
                . '<button class="btn bg-danger delete ms-1" type="delete" name="delete" value="' . $AlbumDataRow['Album_Id'] . '"> Delete </button>'
                . '</td>'
                . '</tr>';
            }
            ?>
        </table>
    </form>
</div>
 <?php
include('./Common/Footer.php');
?>