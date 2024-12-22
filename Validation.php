<?php

$UserId = "";
$Name = "";
$Phone = "";
$Password = "";
$ConfirmPassword = "";

$errorMessageUserId = "";
$errorMessageName = "";
$errorMessagePhone = "";
$errorMessagePassword = "";
$errorMessageConfirmPassword = "";

$UserIdRegex = '/^.{1,16}$/';
$NameRegex = '/^.{1,256}$/';
$PhoneRegex = "/^[2-9]\d{2}-[2-9]\d{2}-\d{4}$/";
$PasswordRegex = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{6,256}$/";

if (isset($_POST["submit"])) {

    $UserId = trim($_POST["inputUserId"]);
    if (empty($UserId)) {
        $errorMessageUserId = "User ID is required.";
    } elseif (!preg_match($UserIdRegex, $UserId)) {
        $errorMessageUserId = "Entered User Id is not valid.";
    } else {

// Check if the User ID already exists in the database
        $sql = "Select count(*)from user where UserId= ?";
        $stmt = $myPdo->prepare($sql);
        $stmt->execute([$UserId]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $errorMessageUserId = "A User with this ID has already signed up.";
        } else {
            $errorMessageUserId = "";
        }

//        Other Method of Inserting Parameter to Query in Safe Way .
//        $sql = "Select count(*)from user where UserId= :inputUserId";
//        $stmt = $myPdo->prepare($sql);
//        $stmt->bindParam(':inputUserId', $UserId);
//        $stmt->execute();
//        $count = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    $Name = trim($_POST["inputName"]);
    if (empty($Name)) {
        $errorMessageName = "Name is required.";
    } elseif (!preg_match($NameRegex, $Name)) {
        $errorMessageName = " Entered Name is not valid.";
    } else {
        $errorMessageName = "";
    }

    $Phone = trim($_POST["inputPhone"]);
    if (empty($Phone)) {
        $errorMessagePhone = "Phone is required.";
    } elseif (!preg_match($PhoneRegex, $Phone)) {
        $errorMessagePhone = " Entered Phone is not valid.(***-***-****)";
    } else {
        $errorMessagePhone = "";
        $_SESSION["inputPhone"] = $Phone;
    }

    $Password = trim($_POST["inputPassword"]);
    if (empty($Password)) {
        $errorMessagePassword = "Passwrod is required.";
    } elseif (!preg_match($PasswordRegex, $Password)) {
        $errorMessagePassword = " Entered Password is not valid.";
    } else {
        $errorMessagePassword = "";
    }

    $ConfirmPassword = trim($_POST["inputConfirmPassword"]);
    if (empty($ConfirmPassword)) {
        $errorMessageConfirmPassword = "Confirm Password is required.";
    } elseif (!preg_match($PasswordRegex, $ConfirmPassword)) {
        $errorMessageConfirmPassword = " Entered Password is not valid.";
    } else {
        if ($ConfirmPassword !== $Password) {
            $errorMessageConfirmPassword = "Entered Password does not match.";
        } else {
            $errorMessageConfirmPassword = "";
        }
    }

    if (empty($errorMessageUserId) && empty($errorMessageName) && empty($errorMessagePhone) && empty($errorMessagePassword) && empty($errorMessageConfirmPassword)) {
        $hashedPassword = password_hash($Password, PASSWORD_DEFAULT);

        // Prepare and execute the SQL INSERT statement here
        $statement = $myPdo->prepare("Insert into user (UserId, Name, Phone, Password) VALUES (?, ?, ?, ?)");
        $statement->execute([$UserId, $Name, $Phone, $hashedPassword]);
        $_SESSION["inputUserId"] = $UserId;
        $_SESSION["inputName"] = $Name;
        $_SESSION["inputPhone"] = $Phone;
        $_SESSION["inputPassword"] = $Password;
        $_SESSION["inputConfirmPassword"] = $ConfirmPassword;
        header("Location: MyAlbums.php");
        exit();
    }
}
?>

