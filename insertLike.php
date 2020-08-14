<?php
include "face_afeka_connect.php";
include "like.php";
include "post.php";
$user_id = @$_POST["user_id"];
$post_id = @$_POST["post_id"];
$likes = @$_POST["likes"];

if (CheckIfAlreadyLike(@$conn, $like_table, $post_id, $user_id)) {
    UpdateLikePost($conn, $post_table, $likes+1, $post_id);
    UpdateLikeInDB($conn, $like_table, $post_id, $user_id);
} else {
    UpdateLikePost($conn, $post_table, $likes-1, $post_id);
    DeleteLikeInDB($conn, $like_table, $post_id, $user_id);
}
?>