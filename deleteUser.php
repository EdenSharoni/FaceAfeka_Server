<?php
include "face_afeka_connect.php";
include "user.php";
include "post.php";
include "comment.php";
include "like.php";
include "friend.php";
$user_id = @$_POST["user_id"];

deleteUser($conn, $user_table, $user_id);
deletePostByUserid($conn, $post_table, $user_id);
deleteCommentByUserid($conn, $comment_table, $user_id);
deleteLikeByUserid($conn, $like_table, $user_id);
deleteFriendByUserid($conn, $friend_table, $user_id);
?>