<?php
include "face_afeka_connect.php";
include "comment.php";

$post_id = @$_POST["post_id"];
$user_id = @$_POST["user_id"];

@$result = $conn->query(GetCommentQuery($comment_table, $post_id) . " DESC LIMIT 1");

if (@$result->num_rows > 0) {
    @$com_row = $result->fetch_assoc();
    echo @json_encode(GetComment($conn, $com_row, $user_table));
}
?>