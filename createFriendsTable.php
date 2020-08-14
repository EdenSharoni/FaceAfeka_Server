<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "face_afeka";
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (! $conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "CREATE TABLE Friends (
friend_id int(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
followed_user int(6) NOT NULL,
following_user int(6) NOT NULL,
are_friends int(1) DEFAULT 0
)";
if (mysqli_query($conn, $sql)) {
    echo "Table Friends FaceAfeka created successfully";
} else {
    echo "Error creating table: " . mysqli_error($conn);
}
mysqli_close($conn);
?>
