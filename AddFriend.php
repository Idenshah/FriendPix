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
$inputFriendId = "";

$errorMessageAddFriend = "";
$actionMessageAddFriend = "";

$friendIdRegex = '/^.{1,16}$/';

if (isset($_POST['submit'])) {
    $inputFriendId = trim($_POST['inputFriendId']);
    $errorMessageAddFriend = "";

    // Validate input
    if (empty($inputFriendId)) {
        $errorMessageAddFriend = "Please insert the ID!";
    } elseif (!preg_match($friendIdRegex, $inputFriendId)) {
        $errorMessageAddFriend = "Entered Friend ID is not valid.";
    } elseif (strcasecmp($inputFriendId, $_SESSION['inputUserId']) === 0) {
        // Case-insensitive check for adding self
        $errorMessageAddFriend = "You cannot add yourself!";
    } else {
        // Query to check existing friendship
        $sqlAddFriend = "SELECT Friend_RequesterId, Friend_RequesteeId, status 
                         FROM friendship 
                         WHERE (Friend_RequesterId = :userId 
                         AND Friend_RequesteeId = :friendId)
                         OR (Friend_RequesterId = :friendId
                         AND Friend_RequesteeId = :userId)";

        $statementAddFriend = $myPdo->prepare($sqlAddFriend);
        $statementAddFriend->execute([
            ':userId' => $_SESSION['inputUserId'],
            ':friendId' => $inputFriendId
        ]);
         $addFriendRows = $statementAddFriend->fetch(PDO::FETCH_ASSOC);
         
        $sqlAvailableId = "SELECT UserId From User WHERE UserId = :friendId";
        $statementAvailableId = $myPdo->prepare($sqlAvailableId);
        $statementAvailableId->execute([':friendId' => $inputFriendId]);
        $AvailableIdRows = $statementAvailableId->fetch(PDO::FETCH_ASSOC); // Fetch single row

        if(!$AvailableIdRows){
            $errorMessageAddFriend = "Entered Id is not available!";
        } else {
             if (!$addFriendRows) {
            // No existing friendship, send request
            $sqlAddRequest = "INSERT INTO friendship (Friend_RequesterId, Friend_RequesteeId, status) 
                              VALUES (:userId, :friendId, :status)";

            $statementAddRequest = $myPdo->prepare($sqlAddRequest);
            $statementAddRequest->execute([
                ':userId' => $_SESSION['inputUserId'],
                ':friendId' => $inputFriendId,
                ':status' => "request"
            ]);

            $actionMessageAddFriend = "Friend request sent successfully!";
        } else {
            // Existing friendship found, check status
            if (strtolower($addFriendRows["status"]) === "request") {
                $errorMessageAddFriend = "A friendship request has already been sent!";
            } else {
                $errorMessageAddFriend = "You are already friends.";
            }
        }
        }
       
    }
}
?>
<div class="container mt-4">
    <h1>Add Friend</h1>
    <p>Enter your friend's ID in the box below.</p>
    <form method="post" action="AddFriend.php" class="myForm">
        <div class="row">
            <div class="col-md-2">
                <label for="inputFriendId">Friend ID:</label>
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" name="inputFriendId" id="inputFriendId" value='<?php echo $inputFriendId; ?>'>
            </div>
            <div class="col-md-5">
                <div class="text-danger"><?php echo $errorMessageAddFriend; ?></div>
            </div>
        </div>
        <div class="mt-5 signupButton">
            <button class="btn btn-primary" type="submit" name="submit" value="submit">Submit</button>
            <button class="btn btn-danger ms-3" type="clear" name="clear" value="clear">Clear</button>
        </div>
    </form>
    <div class="row mt-4 text-center text-success"><?php echo $actionMessageAddFriend; ?></div>
</div>
<?php include('./common/footer.php'); ?>