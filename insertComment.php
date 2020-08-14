<?php
include "face_afeka_connect.php";
include "comment.php";
include "post.php";
$user_id = @$_POST["user_id"];
$post_id = @$_POST["post_id"];
$description = @$_POST["description"];

@$request->text = "";

if (trim($description) === "") {
    @$request->text = "Please fill all fields";
    echo @json_encode($request);
    return;
}

AddCommentToDb($conn, $comment_table, $post_id, $user_id, $description, $request);
UpdateCommentPostInDB($conn, $post_table, $post_id, $request);
echo @json_encode($request);
?>