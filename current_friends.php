<?php
include "face_afeka_connect.php";
include "friend.php";
$input = @$_POST["input"];
$user_id = @$_POST["user_id"];

@$request = [];

if (strlen(trim($input, " ")) > 0) {

    $sql = "SELECT distinct users.userid, users.username, users.picture
                             FROM " . $user_table . ", " . $friend_table . "
                             WHERE users.userid != '" . $user_id . "'
                             AND friends.following_user =  users.userid
                             AND friends.followed_user = '" . $user_id . "'
                             AND friends.are_friends ='1'";

    if ($input !== "*")
        $sql .= " AND users.username like '%" . $input . "%'";

    @$result = $conn->query($sql);

    if (@$result->num_rows > 0) {
        while (@$row = $result->fetch_assoc()) {
            $picture = FriendPicture($row);            
            array_push($request, GetFriend($row, $picture, "Unfollow"));
        }
    }
}
echo @json_encode($request);

?>