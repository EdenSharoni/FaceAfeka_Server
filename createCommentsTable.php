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

$sql = "CREATE TABLE Comments (
comment_id int(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
postid INT(6) NOT NULL,
userid INT(6) NOT NULL,
description text NOT NULL,
reg_date TIMESTAMP
)";
if (mysqli_query($conn, $sql)) {
    echo "Table Comments FaceAfeka created successfully";
} else {
    echo "Error creating table: " . mysqli_error($conn);
}
mysqli_close($conn);
?>
