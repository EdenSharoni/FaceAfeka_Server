<?php

function GetCommentQuery($comment_table, $post_id)
{
    return "SELECT comment_id, postid, userid, description, reg_date FROM " . $comment_table . " WHERE postid = '" . $post_id . "' ORDER BY reg_date";
}

function GetComment($conn, $com_row, $user_table)
{
    $user_id = $com_row["userid"];
    $name1 = "SELECT username FROM " . $user_table . " WHERE userid = '" . $user_id . "'";
    @$result_name = $conn->query($name1);
    @$name1 = $result_name->fetch_assoc();
    return $comment = [
        'user_id' => $user_id,
        'name' => $name1["username"],
        'comment_id' => $com_row["comment_id"],
        'post_id' => $com_row["postid"],
        'description' => $com_row["description"],
        'date' => $com_row["reg_date"],
        'userImg' => getUserPic($user_id, $user_table, $conn)
    ];
}

function getUserPic($user_id, $user_table, $conn)
{
    $sql = "SELECT picture FROM " . $user_table . " WHERE userid='" . $user_id . "'";
    @$result = $conn->query($sql);
    if (@$result->num_rows > 0) {
        $post_row = $result->fetch_assoc();
        $picture = array();
        if ($post_row["picture"] !== "" && $post_row["picture"] !== null) {
            $curImg = explode("@", $post_row["picture"]);
            foreach ($curImg as $img)
                array_push($picture, $img);
        }
        if ($picture)
            return $picture[1];
        else
            return null;
    }
}

function AddCommentToDb($conn, $comment_table, $post_id, $user_id, $description, $request)
{
    $insert_query = "INSERT INTO " . $comment_table . " (postid, userid, description)
					VALUES ('" . $post_id . "', '" . $user_id . "', '" . $description . "')";
    if (@$result = $conn->query($insert_query) === FALSE)
        @$request->text = "Couldn't save comment to DB";
}

function deleteCommentByPostid($conn, $comment_table, $post_id)
{
    $stmt = "DELETE FROM " . $comment_table . " WHERE postid= '" . $post_id . "'";
    if ($conn->query($stmt) === FALSE)
        return false;
    else
        return true;
}

function deleteCommentByUserid($conn, $comment_table, $user_id)
{
    $stmt = "DELETE FROM " . $comment_table . " WHERE userid= '" . $user_id . "'";
    if ($conn->query($stmt) === FALSE)
        return false;
    else
        return true;
}

?>