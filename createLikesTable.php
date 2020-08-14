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

$sql = "CREATE TABLE Likes (
likeid INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
userid INT(6) NOT NULL,
postid INT(6) NOT NULL
)";
if (mysqli_query($conn, $sql)) {
    echo "Table Likes FaceAfeka created successfully";
} else {
    echo "Error creating table: " . mysqli_error($conn);
}
mysqli_close($conn);
?>
