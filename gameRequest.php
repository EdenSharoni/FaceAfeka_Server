<?php
include "face_afeka_connect.php";
include "game.php";
$player1 = @$_POST["player1"];
$player2 = @$_POST["player2"];
@$request->text = "";
@$request->game = [];

@$result = $conn->query("SELECT * FROM " . $game_table);
while (@$row = $result->fetch_assoc()) {
    // Match
    if ($row["player1"] == $player2 && $row["player2"] == $player1) {
        @$request->text = "";
        SetPlayer($conn, $game_table, $player2, $player1, $request);
        InsertRequestToDB($conn, $game_table, $player1, $player2, $request);
        DeleteRequest($conn, $game_table, $player1, $player2);
        echo @json_encode($request);
        return;
    }
    // Other player in game
    if ($row["player2"] == $player2 || $row["player1"] == $player2) {
        @$request->text = "User is Playing with another opponent";
    }
}
if (@$request->text !== "") {
    echo @json_encode($request);
    return;
}
// New Request
InsertRequestToDB($conn, $game_table, $player1, $player2, $request);
SetPlayer($conn, $game_table, $player1, $player2, $request);
echo @json_encode($request);
?>