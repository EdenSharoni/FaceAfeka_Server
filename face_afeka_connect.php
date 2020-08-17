<?php
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Request-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Request-Headers: Origin, Content-Type");
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "eden123";
$dbname = "face_afeka";

if (! isset($user_table) && ! isset($post_table) && ! isset($like_table) && ! isset($friend_table) && ! isset($comment_table) && ! isset($game_table)) {
    $user_table = "Users";
    $post_table = "Posts";
    $like_table = "Likes";
    $friend_table = "Friends";
    $comment_table = "Comments";
    $game_table = "Games";
}


$connect = mysql_connect($servername, $username, $password) or die("Unable to connect to '$dbhost'");
mysql_select_db($dbname) or die("Could not open the database '$dbname'");
/*$result = mysql_query("SELECT * FROM Users");
while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
    printf("ID: %s  Name: %s <br>", $row[0], $row[1]);
}


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error)
    die("Connection failed: " . $conn->connect_error);
else {
    $sql = "SELECT * FROM " . $user_table . " WHERE username='Eden1480'";
    if (@$conn->query($sql) === TRUE)
        echo "TRUE " . $sql;
    else
        echo "FALSE " . $sql;
}
*/
function CalculatePassword($pass)
{
    $pass = $pass[0] . $pass . $pass[0];
    $pass = md5($pass);
    return $pass;
}

?>