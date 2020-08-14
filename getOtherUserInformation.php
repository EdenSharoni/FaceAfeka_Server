<?php
include "face_afeka_connect.php";
include "user.php";
$username = @$_POST["name"];

@$request->text = "";
@$request->user = [];

$sql = "SELECT * FROM  " . $user_table . " WHERE username ='" . $username . "'";

if (! CheckUserAndLogin($conn, $request, $sql))
    @$request->text = "User doesn't exist";
echo @json_encode($request);

?>