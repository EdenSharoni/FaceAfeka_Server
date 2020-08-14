<?php
include 'face_afeka_connect.php';
include "user.php";

$user = @$_POST["user"];
$option = @$_POST["option"];
$profile_respose_once = @$_POST["profile_respose_once"];
@$json_user = @json_decode(@$user);

@$request->text = "";
@$request->user = [];

$pass = @CalculatePassword($json_user->password);
$rpass = @CalculatePassword($json_user->rpassword);

$sql = "SELECT * FROM " . $user_table . " WHERE username='" . $json_user->user_name . "' AND password='" . $pass . "'";
// SignIn
if ($option === "SignIn") {

    if (trim($json_user->user_name) === "" || trim($json_user->password) === "") {
        @$request->text = "Please fill all fields";
        echo @json_encode($request);
        return;
    }
    if (! CheckUserAndLogin($conn, $request, $sql))
        @$request->text = "UserID or password are Incorrect. Please try again or sign up.";
    else {
        echo @json_encode($request);
        return;
    }
    echo @json_encode($request);
} // SignUp
else if ($option === "SignUp") {

    if (trim($json_user->user_name) === "" || trim($json_user->first_name) === "" || trim($json_user->last_name) === "" || trim($json_user->email) === "" || trim($json_user->gender) === "" || trim($json_user->password) === "" || trim($json_user->rpassword) === "") {
        @$request->text = "Please fill all fields";
        echo @json_encode($request);
        return;
    }

    if (! filter_var($json_user->email, FILTER_VALIDATE_EMAIL)) {
        @$request->text = "Invalid email format";
        echo @json_encode($request);
        return;
    }

    if (@CheckUserExsits($conn, $user_table, $json_user->user_name)) {
        @$request->text = "Username already exsit";
        echo @json_encode($request);
        return;
    }

    if (@CheckEmailExsits($conn, $user_table, $json_user->email)) {
        @$request->text = "Email already exsit";
        echo @json_encode($request);
        return;
    }

    if (@$_FILES["file"]["name"] === null && ! $profile_respose_once) {
        @$request->text = "You can pick a profile picture";
        echo @json_encode($request);
        return;
    }

    if (! CheckPassword($pass, $rpass, $request))
        return;

    $res = @AddNewUser($conn, $user_table, $json_user, $pass);
    if ($res !== null)
        CheckUserAndLogin($conn, $request, $sql);
    else {
        if ($_FILES["file"]["error"] > 0)
            @$request->text = "Image too large, change image";
        else
            @$request->text = "Invalid image";
    }
    echo @json_encode($request);
    return;
}
?>