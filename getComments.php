<?php
include "face_afeka_connect.php";
include "comment.php";
$post_id = @$_POST["post_id"];

$comments = array();

@$result = $conn->query(GetCommentQuery($comment_table, $post_id) . " ASC");

if (@$result->num_rows > 0)
    while (@$com_row = $result->fetch_assoc())
        array_push($comments, GetComment($conn, $com_row, $user_table));

echo @json_encode($comments);
?>