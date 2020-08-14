<?php
include "face_afeka_connect.php";
include "friend.php";
include "game.php";

$user_name = @$_POST["user_name"];

DeletePersonalRequest($conn, $game_table, $user_name);


$get_game_request = "SELECT distinct games.player1 
                     FROM " . $game_table . " 
                     WHERE player2 = '" . $user_name . "'";

@$result = $conn->query($get_game_request);

if (@$result->num_rows > 0) {
    @$row = $result->fetch_assoc();
    @$result_picture = $conn->query("SELECT picture FROM " . $user_table . " WHERE username='" . $row["player1"] . "'");
    if (@$result_picture->num_rows > 0)
        $picture = FriendPicture($result_picture->fetch_assoc());
    $player = array(
        "name" => $row['player1'],
        "picture" => ($picture) ? $picture[1] : ""
    );
}

echo @json_encode($player);
?>