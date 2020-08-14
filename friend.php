<?php
function GetRelationshipStatus($conn, $friend_table, $row, $following_user){
    $friends = "Follow";
    // Request Pending
    $sql = "SELECT *
                    FROM " . $friend_table . "
                    WHERE followed_user ='" . $row['userid'] . "'
                    AND following_user = '" . $following_user . "'";
    @$result_friends = $conn->query($sql);
    
    if (@$result_friends->num_rows > 0) {
        $friends = "Pending";
        // Friends
        $sql = "SELECT *
                    FROM " . $friend_table . "
                    WHERE followed_user ='" . $following_user . "'
                    AND following_user = '" . $row['userid'] . "'";
        @$result_friends = $conn->query($sql);
        if (@$result_friends->num_rows > 0) {
            $friends = "Unfollow";
        }
    }
    else{
        $sql = "SELECT *
                    FROM " . $friend_table . "
                    WHERE followed_user ='" . $following_user . "'
                    AND following_user = '" . $row['userid'] . "'";
        
        @$result_friends = $conn->query($sql);
        if (@$result_friends->num_rows > 0) {
            $friends = "Confirm";
        }
    }
    return $friends;
}

function FriendPicture($row){
    $picture = array();
    if ($row["picture"] !== "" && $row["picture"] !== null) {
        $curImg = explode("@", $row["picture"]);
        foreach ($curImg as $img)
            array_push($picture, $img);
    }
    return $picture;
}

function GetFriend($user_row, $picture, $status){
    return array(
        "name" => $user_row['username'],
        "picture" => ($picture) ? $picture[1] : null,
        "friendid" => $user_row['userid'],
        "are_friends" => $status
    );
}

function UpdateFriendInDatabase($conn, $friend_table, $user1, $user2)
{
    $update_query = "UPDATE " . $friend_table . " SET are_friends='1' WHERE followed_user ='" . $user1 . "' AND following_user = '" . $user2 . "'";
    @$result = $conn->query($update_query);
}

function InsertFriendToDatabase($conn, $friend_table, $followed_user, $following_user)
{
    $insert_query = "INSERT INTO " . $friend_table . " (followed_user, following_user) VALUES ('" . $followed_user . "', '" . $following_user . "')";
    if (@$result = $conn->query($insert_query) === TRUE)
        return true;
    return false;
}

function deleteFriendByUserid($conn, $friend_table, $user_id)
{
    $stmt = "DELETE FROM " . $friend_table . " WHERE following_user= '" . $user_id . "' OR followed_user='" . $user_id . "'";
    if ($conn->query($stmt) === FALSE)
        return false;
    else
        return true;
}

function DeleteFriendByBothSides($conn, $friend_table, $followed_user, $following_user)
{
    $insert_query = "DELETE FROM " . $friend_table . " WHERE followed_user='" . $followed_user . "' AND following_user='" . $following_user . "'";
    if (@$result = $conn->query($insert_query) === FALSE)
        echo "Couldn't delete friends";
}

?>