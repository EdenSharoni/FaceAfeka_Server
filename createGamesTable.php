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

$sql = "CREATE TABLE Games (
game_id int(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
player1 VARCHAR(30) NOT NULL,
player2 VARCHAR(30) NOT NULL
)";
if (mysqli_query($conn, $sql)) {
    echo "Table Games FaceAfeka created successfully";
} else {
    echo "Error creating table: " . mysqli_error($conn);
}
mysqli_close($conn);
?>
