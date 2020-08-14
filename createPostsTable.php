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

$sql = "CREATE TABLE Posts (
postid INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
userid INT(6) NOT NULL,
description TEXT NOT NULL,
private tinyint(1) NOT NULL,
images VARCHAR(300),
likes_num INT(6) NOT NULL,
comments_num VARCHAR(30),
reg_date TIMESTAMP
)";
if (mysqli_query($conn, $sql)) {
    echo "Table Posts FaceAfeka created successfully";
} else {
    echo "Error creating table: " . mysqli_error($conn);
}
mysqli_close($conn);
?>
