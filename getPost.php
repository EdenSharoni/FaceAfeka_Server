<?php
include "face_afeka_connect.php";
include "post.php";
global $conn, $post_table, $user_table;
$user_id = @$_POST["user_id"];

$get_post = GetPersonalPostQuery($post_table, $user_table, $user_id) . " LIMIT 1";
@$result = $conn->query($get_post);

if (@$result->num_rows > 0) {
    @$row = $result->fetch_assoc();
    echo @json_encode(GetPost($conn, $row, $like_table, $user_id));
} else
    echo "Username or password are inncorect";

?>