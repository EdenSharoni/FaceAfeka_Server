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
$sql = "CREATE TABLE Users (
userid INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(30) NOT NULL,
firstname VARCHAR(30) NOT NULL,
lastname VARCHAR(30) NOT NULL,
email VARCHAR(50),
gender VARCHAR(10) NOT NULL,
picture VARCHAR(300),
password varchar(32) NOT NULL,
reg_date TIMESTAMP
)";
if (mysqli_query($conn, $sql)) {
    echo "Table Users FaceAfeka created successfully";
} else {
    echo "Error creating table: " . mysqli_error($conn);
}
mysqli_close($conn);
?>
