<?php
include('./common/header.php');
$dbConnection = parse_ini_file("DataSource.ini");
extract($dbConnection);
$myPdo = new PDO($dsn, $user, $password);

$UserId = "";
$Password = "";

$UserIdRegex = '/^.{1,16}$/';
$PasswordRegex = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{6,256}$/";

$errorMessageUserId = "";
$errorMessagePassword = "";

if (isset($_POST['submit'])) {
    $UserId = trim($_POST["inputUserId"]);
    if (empty($UserId)) {
        $errorMessageUserId = "User ID is required.";
    } elseif (!preg_match($UserIdRegex, $UserId)) {
        $errorMessageUserId = "Entered User Id is not valid.";
    } else {
        $errorMessageUserId = "";
    }

    $Password = trim($_POST["inputPassword"]);
    if (empty($Password)) {
        $errorMessagePassword = "Passwrod is required.";
    } elseif (!preg_match($PasswordRegex, $Password)) {
        $errorMessagePassword = " Entered Password is not valid.";
    } else {
        $errorMessagePassword = "";
    }

    if (empty($errorMessageUserId) && (empty($errorMessagePassword))) {
        $sql = "select UserId, Name, Phone, Password from user where UserId = :inputUserId";
        $stmt = $myPdo->prepare($sql);
        $stmt->bindParam(':inputUserId', $UserId);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row && password_verify($Password, $row['Password'])) {
            $_SESSION['UserInfo'] = $row;
            $_SESSION["inputUserId"] = $row['UserId'];
            $_SESSION["inputName"] = $row["Name"];
            $_SESSION["inputPhone"] = $row["Phone"];
            $_SESSION["inputPassword"] = $row["Password"];
              header("Location: MyAlbums.php");
            exit;
        } elseif ($row && !password_verify($Password, $row['Password'])) {
            $errorMessagePassword = " Entered Password is Wrong.";
        } else {
            $errorMessageUserId = "Entered User Id is Wrong.";
        }
    }
}
?>

<div class="container mt-5">
    <h1 class="text-center"> Log In </h1>
    <p class="text-center mb-4">Please Enter Your ID and Password.</p>
    <form method="post" action="Login.php" class="myForm" id="myForm">
        <div class="row mb-3">
            <div class="col-md-2">
                <label for="inputUserId" class="form-label">User ID:</label>
            </div>
            <div class="col-md-4">
                <input type="text" name="inputUserId" class="form-control" id="inputUserId" value="<?php echo $UserId ?>">
            </div>
            <div class="col-md-4 text-danger">
<?php echo $errorMessageUserId; ?>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-2">
                <label for="inputPassword" class="form-label">Password:</label>
            </div>
            <div class="col-md-4">
                <input type="password" name="inputPassword" class="form-control" id="inputPassword" value="<?php echo $Password ?>">
            </div>
            <div class="col-md-4 text-danger">
<?php echo $errorMessagePassword; ?>
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