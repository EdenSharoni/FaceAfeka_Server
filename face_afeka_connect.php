<?php
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Request-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Request-Headers: Origin, Content-Type");
header('Content-Type: application/json');



$dbname = 'phptest';
$dbuser = 'root';
$dbpass = 'eden123';
$dbhost = 'http://localhost';
$connect = mysql_connect($dbhost, $dbuser, $dbpass) or die("Unable to connect to '$dbhost'");
/*mysql_select_db($dbname) or die("Could not open the database '$dbname'");
$result = mysql_query("SELECT username FROM Users");
while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
    printf("Username: %s ", $row[0]);
}*/





/*$servername = "localhost";
$username = "root";
$password = "eden123";
$dbname = "face_afeka";

 * // Create connection
 * $conn = new mysqli($servername, $username, $password, $dbname);
 * // Check connection
 * if ($conn->connect_error)
 * die("Connection failed: " . $conn->connect_error);
 * else {
 * $sql = "SELECT * FROM " . $user_table . " WHERE username='Eden1480'";
 * if (@$conn->query($sql) === TRUE)
 * echo "TRUE " . $sql;
 * else
 * echo "FALSE " . $sql;
 * }
 *
 *
 *
 * if (! isset($user_table) && ! isset($post_table) && ! isset($like_table) && ! isset($friend_table) && ! isset($comment_table) && ! isset($game_table)) {
 * $user_table = "Users";
 * $post_table = "Posts";
 * $like_table = "Likes";
 * $friend_table = "Friends";
 * $comment_table = "Comments";
 * $game_table = "Games";
 * }
 */
function CalculatePassword($pass)
{
    $pass = $pass[0] . $pass . $pass[0];
    $pass = md5($pass);
    return $pass;
}

?>