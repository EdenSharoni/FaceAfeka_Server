<?php

function InsertRequestToDB($conn, $game_table, $player1, $player2, $request)
{
    $insert_query = "INSERT INTO " . $game_table . " (player1, player2) VALUES ('" . $player1 . "', '" . $player2 . "')";
    $conn->query($insert_query);
}

function SetPlayer($conn, $game_table, $player1, $player2, $request)
{
    $sql = "SELECT game_id FROM " . $game_table . " WHERE player1='" . $player1 . "' AND player2='" . $player2 . "'";
    @$result_room = $conn->query($sql);
    if ($result_room->num_rows > 0) {
        @$row = $result_room->fetch_assoc();
        @$request->game = [
            "player1" => $player1,
            "player2" => $player2,
            "room" => $row["game_id"]
        ];
    }
}

function DeletePersonalRequest($conn, $game_table, $player1)
{
    $conn->query("DELETE FROM " . $game_table . " WHERE player1= '" . $player1 . "'");
}

function DeleteRequest($conn, $game_table, $player1, $player2)
{
    $conn->query("DELETE FROM " . $game_table . " WHERE player1 != '" . $player2 . "' AND player2= '" . $player1 . "'");
    $conn->query("DELETE FROM " . $game_table . " WHERE player1 != '" . $player1 . "' AND player2= '" . $player2 . "'");
}
?>