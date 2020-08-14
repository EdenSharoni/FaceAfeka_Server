<?php
include "face_afeka_connect.php";
include "post.php";
include "comment.php";
include "like.php";
$post_id = @$_POST["post_id"];

deletePostByPostid($conn, $post_table, $post_id);

deleteCommentByPostid($conn, $comment_table, $post_id);

deleteLikeByPostid($conn, $like_table, $post_id);
?>