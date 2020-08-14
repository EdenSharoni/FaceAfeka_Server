<?php
include "face_afeka_connect.php";
include "post.php";

$user_id = @$_POST["user_id"];
$posts = array();

@$result = $conn->query(GetPostsWithFriendsQuery($post_table, $user_table, $friend_table, $user_id));

if (@$result->num_rows == 0)
    @$result = $conn->query(GetPersonalPostQuery($post_table, $user_table, $user_id));

if (@$result->num_rows > 0) {
    while (@$row = $result->fetch_assoc())
        array_push($posts, @GetPost($conn, $row, $like_table, $user_id));
    echo @json_encode($posts);
} else
    echo "Couldn't get posts";
?>