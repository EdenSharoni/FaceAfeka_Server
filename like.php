<?php

function CheckIfAlreadyLike($conn, $like_table, $post_id, $user_id)
{
    $sql_like = "SELECT * FROM " . $like_table . " WHERE (userid = '" . $user_id . "' AND postid = '" . $post_id . "')";
    @$result = $conn->query($sql_like);
    if (@$result->num_rows > 0)
        return false;
    return true;
}

function UpdateLikeInDB($conn, $like_table, $post_id, $user_id)
{
    $insert_query = "INSERT INTO " . $like_table . " (userid, postid) VALUES ('" . $user_id . "', '" . $post_id . "')";
    if(@$result = $conn->query($insert_query)===FALSE)
        echo "Failed to UpdateLikeInDB";
}

function DeleteLikeInDB($conn, $like_table, $post_id, $user_id)
{
    $stmt = "DELETE FROM " . $like_table . " WHERE postid= '" . $post_id . "' AND userid='" . $user_id . "'";
    @$result = $conn->query($stmt);
    if ($conn->query($stmt) === FALSE)
        echo "Failed to DeleteLikeInDB";
}

function deleteLikeByPostid($conn, $like_table, $post_id)
{
    $stmt = "DELETE FROM " . $like_table . " WHERE postid= '" . $post_id . "'";
    if ($conn->query($stmt) === FALSE)
        return false;
    else
        return true;
}

function deleteLikeByUserid($conn, $like_table, $user_id)
{
    $stmt = "DELETE FROM " . $like_table . " WHERE userid= '" . $user_id . "'";
    if ($conn->query($stmt) === FALSE)
        return false;
    else
        return true;
}

?>