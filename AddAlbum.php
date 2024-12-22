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

$title = "";
$Accessibility_Code="";
$Description = "";

$errorMessageTitle = "";
$errorMessageAccessibility = "";
$errorMessageDescription = "";

$titleRegex = '/^.{1,40}$/';
$DescriptionRegex = '/^.{1,250}$/';

if (isset($_POST['submit'])) {
    
    $title = trim($_POST["inputTitle"]);
    if (empty($title)) {
        $errorMessageTitle = "Title is required.";
    } elseif (!preg_match($titleRegex, $title)) {
        $errorMessageTitle = "Entered title is not valid.";
    } else {
        $errorMessageTitle = "";
    }

    $Accessibility_Description = trim($_POST["inputAccessibility"]);
//    $Accessibility_Code= trim($_POST["inputAccessibility"]);
    if (empty($Accessibility_Description)) {
        $errorMessageAccessibility = "Accessibility is required.";
    } else {
        $errorMessageAccessibility = "";
    }

    $Description = trim($_POST["inputDescription"]);
    if (empty($Description)) {
        $errorMessageDescription = "Description is required.";
    } elseif (!preg_match($DescriptionRegex, $Description)) {
        $errorMessageDescription = "Entered description is not valid.";
    } else {
        $errorMessageDescription = "";
    }

    if (empty($errorMessageTitle) && empty($errorMessageAccessibility) && empty($errorMessageDescription)) {
        $statementAlbum = $myPdo->prepare("Insert into Album (Title, Description, Owner_Id, Accessibility_Code) values (?, ?, ?, ?)");
        $statementAlbum->execute([$title, $Description, $_SESSION["inputUserId"], $Accessibility_Description]);
//        $_SESSION["inputTitle"] = $title;
//        $_SESSION["inputDescription"] = $Description;
//        $_SESSION["Owner_Id"]=$_SESSION["inputUserId"];
//        $_SESSION["Accessibility_Code"] = $Accessibility_Code;
        header("Location: MyAlbums.php");
        exit();
    }
}
?>
<div class="container">
    <h1>Create New Album</h1>
    <p>Welcome <strong><?php echo $_SESSION["inputName"]; ?></strong> (Not you? Change user <a href="Login.php" class="primary" style= " text-decoration: none">here</a>)</p>
    <form action="AddAlbum.php" method='post' class='myForm'>
        <div class='row mb-3'>
            <div class='col-md-4'>
                <label class='form-lable' for="inputTitle">Title:</label>
            </div>
            <div class='col-md-4'>
                <input type="text" class='form-control' id="inputTitle" name="inputTitle" value="<?php echo $title; ?>" />
            </div>
            <div class='col-md-2 text-danger'>
                <?php echo $errorMessageTitle; ?>
            </div>
        </div>

        <div class='row mb-3'>
            <div class='col-md-4'>
                <label class='form-lable' for="inputAccessibility">Accessibility:</label>
            </div>
            <div class='col-md-4'>
                <select class='form-control' id="inputAccessibility" name="inputAccessibility">
                    <option value="0">Select one...</option>
                    <?php
                    $sqlAccessibility = 'Select Accessibility_Code, Description from Accessibility';
                    $stmtSelectAccessibility = $myPdo->prepare($sqlAccessibility);
                    $stmtSelectAccessibility->execute();
                    $allAccessibilities = $stmtSelectAccessibility->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($allAccessibilities as $Accessibility) {
                        $Accessibility_Code = $Accessibility['Accessibility_Code'] ?? null;
                        $Accessibility_Description = $Accessibility['Description'] ?? null;
                        $selectedAccessibility = (isset($_POST['inputAccessibility']) && $_POST['inputAccessibility'] == $Accessibility_Code) ? 'selected' : '';
                        echo '<option value="' . $Accessibility_Code . '" ' . $selectedAccessibility . '>' . $Accessibility_Description . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class='col-md-2 text-danger'>
                <?php echo $errorMessageAccessibility; ?>
            </div>
        </div>

        <div class='row mb-3'>
            <div class='col-md-4'>
                <label class='form-lable' for="inputDescription">Description:</label>
            </div>
            <div class='col-md-4'>
                <textarea class='form-control' rows="6" id="inputDescription" name="inputDescription"> <?php echo $Description; ?></textarea>
            </div>
            <div class='col-md-2 text-danger'>
                <?php echo $errorMessageDescription; ?>
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