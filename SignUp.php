<?php
include('./common/header.php');

$dbConnection = parse_ini_file("DataSource.ini");
extract($dbConnection);
$myPdo = new PDO($dsn, $user, $password);

include ('Validation.php');
?>

<div class="container mt-5">
    <h1 class="text-center">Sign Up</h1>
    <p class="text text-center mb-4">All fields are required.</p> 
    <form method="post" action="SignUp.php" class="myForm" id="myForm">
        <div class="row mb-3">
            <div class="col-md-2">
                <label for="inputUserId" class="form-label">User ID:</label>
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" id="inputUserId" name="inputUserId" value='<?php echo $UserId; ?>'>
            </div>
            <div class="col-md-4 text-danger">
                <?php echo $errorMessageUserId; ?>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-2">
                <label for="inputName" class="form-label">Name:</label>
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" id="inputName" name="inputName" value='<?php echo $Name; ?>'>
            </div>
            <div class="col-md-4 text-danger">
                <?php echo $errorMessageName; ?>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-2">
                <label for="inputPhone" class="form-label">Phone Number:</label>
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" id="inputPhone" name="inputPhone" value='<?php echo $Phone; ?>'>
            </div>
            <div class="col-md-4 text-danger">
                <?php echo $errorMessagePhone; ?>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-2">
                <label for="inputPassword" class="form-label">Password:</label>
            </div>
            <div class="col-md-4">
                <input type="password" class="form-control" id="inputPassword" name="inputPassword" value='<?php echo $Password; ?>'>
            </div>
            <div class="col-md-4 text-danger">
                <?php echo $errorMessagePassword; ?>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-2">
                <label for="inputConfirmPassword" class="form-label">Confirm Password:</label>
            </div>
            <div class="col-md-4">
                <input type="password" class="form-control" id="inputConfirmPassword" name="inputConfirmPassword" value='<?php echo $ConfirmPassword; ?>'>
            </div>
            <div class="col-md-4 text-danger">
                <?php echo $errorMessageConfirmPassword; ?>
            </div>
        </div>

        <div class="mt-5 signupButton">
            <button class="btn btn-primary" type="submit" name="submit" value="submit">Submit</button>
            <button class="btn btn-danger ms-3" type="clear" name="clear" value="clear">Clear</button>
        </div>
    </form>
</div>
<?php
include('./common/footer.php');
?>
