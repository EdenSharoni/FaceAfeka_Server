<?php
include "face_afeka_connect.php";
include "post.php";
$user_name = @$_POST["user_name"];
$user_id = @$_POST["user_id"];
$description = @$_POST["description"];
$private = @$_POST["radio"];

@$request->text = "";

if (trim($description) === "" && @$_FILES["myFiles"] === null) {
    $request->text = "Cannot post empty post";
    echo @json_encode($request);
    return;
}

if (! AddPostToDB($user_id, $private, $description, GetPostImagePath($user_name, @$_FILES["myFiles"])))
    $request->text = "Failed to save post";
echo @json_encode($request);
?>