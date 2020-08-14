<?php

header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Request-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Request-Headers: Origin, Content-Type");
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "face_afeka";

if (! isset($user_table) && ! isset($post_table) && ! isset($like_table)&& ! isset($friend_table) && ! isset($comment_table)&& ! isset($game_table)) {
    $user_table = "Users";
    $post_table = "Posts";
    $like_table = "Likes";
    $friend_table = "Friends";
    $comment_table = "Comments";
    $game_table = "Games";
}

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error)
    die("Connection failed: " . $conn->connect_error);
    
function CalculatePassword($pass)
{
    $pass = $pass[0] . $pass . $pass[0];
    $pass = md5($pass);
    return $pass;
}

?>