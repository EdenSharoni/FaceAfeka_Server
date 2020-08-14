<?php
include "face_afeka_connect.php";
include "post.php";
$post = @$_POST["post"];
@$json_post = @json_decode(@$post);

UpdatePostInDB($conn, $post_table, $json_post)?>
