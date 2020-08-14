<?php
include "face_afeka_connect.php";
include "friend.php";
$followed_user = @$_POST["followed_user"]; // friend_user_id
$following_user = @$_POST["following_user"]; // current_user_id

@$request->text = "Follow";

$sql = "SELECT * FROM " . $friend_table . " WHERE followed_user ='" . $followed_user . "' AND following_user = '" . $following_user . "'";

@$result = $conn->query($sql);
if (@$result->num_rows == 0) {
    if (InsertFriendToDatabase($conn, $friend_table, $followed_user, $following_user)) {
        $sql = "SELECT * FROM " . $friend_table . " WHERE followed_user ='" . $following_user . "' AND following_user = '" . $followed_user . "'";
        @$result = $conn->query($sql);
        if (@$result->num_rows > 0) {
            UpdateFriendInDatabase($conn, $friend_table, $following_user, $followed_user);
            UpdateFriendInDatabase($conn, $friend_table, $followed_user, $following_user);
            $request->text = "Unfollow";
        } else
            $request->text = "Pending";
    }
} else {
    DeleteFriendByBothSides($conn, $friend_table, $followed_user, $following_user);
    $sql = "SELECT * FROM " . $friend_table . " WHERE followed_user ='" . $following_user . "' AND following_user = '" . $followed_user . "'";
    
    @$result = $conn->query($sql);
    if (@$result->num_rows > 0)
        DeleteFriendByBothSides($conn, $friend_table, $following_user, $followed_user);
}

echo @json_encode($request);

?>
