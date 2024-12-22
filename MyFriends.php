<?php
include './Common/Header.php';

if (!isset($_SESSION['inputUserId'])) {
    $_SESSION['RequestedPage'] = $_SERVER['REQUEST_URI'];
    header('Location: Login.php');
    exit();
}
$dbConnection = parse_ini_file("DataSource.ini");
extract($dbConnection);
$myPdo = new PDO($dsn, $user, $password);

$errorMassageAnswer = "";

$sqlFriendReq = 'select Friend_RequesterId,Friend_RequesteeId,status from friendship where (status ="request" and Friend_RequesteeId = :UserId);';
$statementFriendReq = $myPdo->prepare($sqlFriendReq);
$statementFriendReq->execute([':UserId' => $_SESSION['inputUserId']]);
$friendReqRows = $statementFriendReq->fetchAll(PDO::FETCH_ASSOC);

$sqlFriendList = 'select Friend_RequesterId,Friend_RequesteeId,status from friendship where (status ="accepted" and (Friend_RequesteeId = :UserId or Friend_RequesterId = :UserId ));';
$statementFriendList = $myPdo->prepare($sqlFriendList);
$statementFriendList->execute([':UserId' => $_SESSION['inputUserId']]);
$friendListRows = $statementFriendList->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $friendRequests = $_POST['friendRequests'] ?? [];
    $friendLists = $_POST['friends'] ?? [];
    $action = "";

    if (!empty($friendRequests)) {
        if (isset($_POST['accept'])) {
            $action = "accepted";
        } elseif (isset($_POST['deny'])) {
            $action = "deny";
        }
    } elseif (!empty($friendLists) && isset($_POST['deFriend'])) {
        $action = "deFriend";
    }

    if ($action === "accepted" || $action === "deny") {
        foreach ($friendRequests as $friendshipRequesterId) {
            $sqlFriendAnswer = $action === "accepted" 
                ? 'UPDATE friendship SET status = "accepted" WHERE Friend_RequesterId = :friendshipRequesterId AND Friend_RequesteeId = :UserId;'
                : 'DELETE FROM friendship WHERE Friend_RequesterId = :friendshipRequesterId AND Friend_RequesteeId = :UserId;';
            $statementFriendAnswer = $myPdo->prepare($sqlFriendAnswer);
            $statementFriendAnswer->execute([
                ':friendshipRequesterId' => $friendshipRequesterId,
                ':UserId' => $_SESSION['inputUserId']
            ]);
        }
        header('Location: MyFriends.php');
        exit();
    } elseif ($action === "deFriend") {
        foreach ($friendLists as $friendId) {
            $sqlDeFriend = 'DELETE FROM friendship WHERE 
                (Friend_RequesterId = :friendId AND Friend_RequesteeId = :UserId) OR 
                (Friend_RequesterId = :UserId AND Friend_RequesteeId = :friendId);';
            $statementDeFriend = $myPdo->prepare($sqlDeFriend);
            $statementDeFriend->execute([
                ':friendId' => $friendId,
                ':UserId' => $_SESSION['inputUserId']
            ]);
        }
        header('Location: MyFriends.php');
        exit();
    } else {
        $errorMassageAnswer = "Please select at least one option.";
    }
}
?>
<div class="container">
    <h1>My Friends</h1>
    <p>Welcome <strong><?php echo $_SESSION['inputName']; ?></strong></p>
    <p class="mt-4 ms-4">
        Friends:
        <span class="ms-4">
            <a href="AddFriend.php" style= "text-decoration: none" >Add Friends</a>
        </span>
    </p>
    <div class="ms-4 border border-1"></div>
    <form method="post" action="MyFriends.php" class="myForm">
        <table class="mt-2 ms-4 table border-0 ">
            <tr>
                <th style="border: none">Friend Id</th>
                <th style="border: none" class="text-center">De-friend</th>
            </tr>
            <?php
            foreach ($friendListRows as $friendListRow) {
                if ($friendListRow["Friend_RequesterId"] != $_SESSION['inputUserId']) {
                    $friendId = htmlspecialchars($friendListRow["Friend_RequesterId"]);
                } else {
                    $friendId = htmlspecialchars($friendListRow["Friend_RequesteeId"]);
                }
                echo '<tr>
                        <td style="border: none">' . $friendId . '</td>
                        <td style="border: none" class="text-center">
                            <input type="checkbox" name="friends[]" value="' . $friendId . '" />
                        </td>
                      </tr>';
            }
            ?>
        </table>
        <div class="mt-5 ms-4 signupButton">
            <button class="btn btn-warning" type="submit" name="deFriend" value="deFriend">Cancel Friendship</button>
        </div>
        <p class="mt-4 ms-4">Friendship Requests:</p>
        <div class="ms-4 border border-1"></div>
        <table class="mt-2 ms-4 table border-0">
            <tr>
                <th style="border: none">Friend Requester</th>
                <th style="border: none" class="text-center">Accept or Deny</th>
            </tr>
            <?php
            foreach ($friendReqRows as $friendReqRow) {
                $friendshipRequesterId = htmlspecialchars($friendReqRow["Friend_RequesterId"]);
                echo '<tr>
                        <td style="border: none">' . $friendshipRequesterId . '</td>
                        <td style="border: none" class="text-center">
                            <input type="checkbox" name="friendRequests[]" value="' . $friendshipRequesterId . '" />
                        </td>
                      </tr>';
            }
            ?>
        </table>
        <div class="row text-danger">
            <?php echo $errorMassageAnswer; ?>
        </div>
        <div class="mt-5 ms-4 signupButton">
            <button class="btn btn-primary" type="submit" name="accept" value="accept">Accept Selected</button>
            <button class="btn btn-danger" type="submit" name="deny" value="deny">Deny Selected</button>
        </div>
    </form>
</div>
<?php
include('./common/footer.php');
?>