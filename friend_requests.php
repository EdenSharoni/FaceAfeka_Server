<?php
include "face_afeka_connect.php";
include "friend.php";
$user_id = @$_POST["user_id"];

@$request = [];

@$result = $conn->query("SELECT *
                         FROM " . $friend_table . "
                         WHERE followed_user = '" . $user_id . "'");

if (@$result->num_rows > 0) {
    while (@$row = $result->fetch_assoc()) {

        @$result_2 = $conn->query("SELECT * FROM " . $friend_table . " WHERE followed_user='" . $row["following_user"] . "' AND following_user='" . $user_id . "'");

        if (@$result_2->num_rows != 0)
            continue;

        $sql_user = "SELECT * FROM " . $user_table . " WHERE userid='" . $row["following_user"] . "'";
        @$result_user = $conn->query($sql_user);

        if (@$result_user->num_rows > 0) {
            $user_row = $result_user->fetch_assoc();
            $picture = FriendPicture($user_row);
            array_push($request, GetFriend($user_row, $picture,  "Confirm"));
        }
    }
}

echo @json_encode($request);

?>