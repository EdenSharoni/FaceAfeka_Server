<?php
include "face_afeka_connect.php";
include "user.php";

$user = @$_POST["user"];
$current_password = @$_POST["current_password"];
$new_password = @$_POST["new_password"];
$password_change = @$_POST["password_change"];

@$json_user = @json_decode(@$user);

$password_update = "";
@$request->text = "";
@$request->user = [];
$imagesPath = null;

if ($password_change) {
    if (trim($new_password) !== "")
        $password_update = CalculatePassword($new_password);
    else {
        @$request->text = "Please fill all fields";
        echo @json_encode($request);
        return;
    }
}

$get_user_id = "SELECT * FROM " . $user_table . " WHERE userid='" . $json_user->userid . "'";

@$result = $conn->query($get_user_id);
if (@$result->num_rows > 0) {
    while (@$row = $result->fetch_assoc()) {
        // User updates password
        if ($password_update === "")
            $password_update = @$row["password"];
        else {
            if (! CheckPassword($row["password"], CalculatePassword($current_password), $request))
                return;
        }

        $currentImage = $row["picture"];

        if (@$_FILES["file"]["name"] != null) {
            $imagesPath = getImagePath($json_user->user_name);
            if ($imagesPath == null) {
                @$request->text = "Error Uploading File";
                echo @json_encode($request);
                return;
            }
            if (@$_FILES["file"]["error"] > 0) {
                @$request->text = "Image too large, change image";
                return;
            }
        }

        if ($currentImage != null && $imagesPath == null)
            $imagesPath = $currentImage;
    }
    
    if (UpdateUser($conn, $user_table, $json_user, $imagesPath, $password_update, $request))
        echo @json_encode($request);
    else
        return null;
}
?>