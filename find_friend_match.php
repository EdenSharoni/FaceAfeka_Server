<?php
include "face_afeka_connect.php";
include "friend.php";
$input = @$_POST["input"];
$following_user = @$_POST["user_id"];

@$request = [];

if (strlen(trim($input, " ")) > 0) {

    @$result = $conn->query("SELECT userid, username, picture 
                             FROM " . $user_table . " 
                             WHERE username like '%" . $input . "%'
                             AND userid != '" . $following_user . "'");

    if (@$result->num_rows > 0) {
        while (@$row = $result->fetch_assoc()) {
            $picture = FriendPicture($row);
            $friends = GetRelationshipStatus($conn, $friend_table, $row, $following_user);
            array_push($request, GetFriend($row, $picture, $friends));
        }
    }
}
echo @json_encode($request);

?>