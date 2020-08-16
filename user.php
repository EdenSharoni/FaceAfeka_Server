<?php

function UpdateUser($conn, $user_table, $json_user, $imagesPath, $password_update, $request)
{
    if (@$_FILES["file"]["name"] != null) {
        $user_update_query = "UPDATE " . $user_table . "
          SET
          firstname='" . $json_user->first_name . "',
          lastname='" . $json_user->last_name . "',
          gender='" . $json_user->gender . "',
          picture = '" . $imagesPath . "',
          password='" . $password_update . "'
          WHERE userid='" . $json_user->userid . "'";
    } else {
        $user_update_query = "UPDATE " . $user_table . "
          SET
          firstname='" . $json_user->first_name . "',
          lastname='" . $json_user->last_name . "',
          gender='" . $json_user->gender . "',
          password='" . $password_update . "'
          WHERE userid='" . $json_user->userid . "'";
    }

    if ($conn->query($user_update_query) === TRUE) {
        $sql = "SELECT * FROM " . $user_table . " WHERE username='" . $json_user->user_name . "'";
        CheckUserAndLogin($conn, $request, $sql);
        return true;
    } else {
        @$request->text = "Error";
        return false;
    }
}

function CheckUserExsits($conn, $user_table, $user)
{
    $get_username = "SELECT * FROM " . $user_table . " WHERE username='" . $user . "'";
    $user_result = @$conn->query($get_username);
    if ($user_result->num_rows == 1)
        return true;
    else
        return false;
}

function CheckPassword($pass, $rpass, $request)
{
    if ($pass !== $rpass) {
        @$request->text = "Passwords don't match";
        echo @json_encode($request);
        return false;
    }
    return true;
}

function CheckEmailExsits($conn, $user_table, $email)
{
    $get_username = "SELECT * FROM " . $user_table . " WHERE email='" . $email . "'";
    $user_result = @$conn->query($get_username);
    if ($user_result->num_rows == 1)
        return true;
    else
        return false;
}

function CheckUserAndLogin($conn, $request, $sql)
{
    $user_result = @$conn->query($sql);

    if ($user_result->num_rows == 1) {
        $user_row = $user_result->fetch_assoc();

        $images = array();
        if ($user_row["picture"] !== null) {       
            $curImg = explode("@", $user_row["picture"]);
            foreach ($curImg as $img)
                array_push($images, $img);
        }

        @$request->user = [
            "userid" => $user_row["userid"],
            "user_name" => $user_row["username"],
            "first_name" => $user_row["firstname"],
            "last_name" => $user_row["lastname"],
            "email" => $user_row["email"],
            "gender" => $user_row["gender"],
            "picture" => ($images) ? $images : null
        ];
        return true;
    } else
        return false;
}

function AddNewUser($conn, $user_table, $json_user, $pass)
{
    try {
        if (@$_FILES["file"]["name"] !== null) {
            setFileForImagePath($user);
            $imagesPath = getImagePath($json_user->user_name);
            if ($imagesPath === null)
                return null;
            $stmt = $conn->prepare("INSERT INTO " . $user_table . " (username, firstname, lastname, email, gender, picture, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $json_user->user_name, $json_user->first_name, $json_user->last_name, $json_user->email, $json_user->gender, $imagesPath, $pass);
        } else {
            $stmt = $conn->prepare("INSERT INTO " . $user_table . " (username, firstname, lastname, email, gender, password) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $json_user->user_name, $json_user->first_name, $json_user->last_name, $json_user->email, $json_user->gender, $pass);
        }
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

function setFileForImagePath($user)
{
    $imagesPath = "";
    if (! file_exists("faceAfeka/userPic/" . $user))
        mkdir("faceAfeka/userPic");
    if (! file_exists("faceAfeka/userPic/img"))
        mkdir("faceAfeka/userPic/img");
    if (! file_exists("faceAfeka/userPic/thumbs"))
        mkdir("faceAfeka/userPic/thumbs");

    $target_path = "faceAfeka/userPic/img/";
    $pathToThumbs = "faceAfeka/userPic/thumbs/";
}

function getImagePath($user)
{
    $target_path = "faceAfeka/userPic/img/";
    $pathToThumbs = "faceAfeka/userPic/thumbs/";

    $allowedExts = array(
        "gif",
        "jpeg",
        "jpg",
        "png"
    );
    $temp = explode(".", $_FILES["file"]["name"]);
    $extension = end($temp);
    $imagesPath = $target_path . $user . "." . $extension;

    if (in_array($extension, $allowedExts))
        move_uploaded_file($_FILES["file"]["tmp_name"], $imagesPath);
    else
        return null;
    createUserThumbs($target_path, $pathToThumbs, 200, $user);

    $thumbPath = $pathToThumbs . $user . "." . $extension;
    return $imagesPath . '@' . $thumbPath;
}

function createUserThumbs($pathToImages, $pathToThumbs, $thumbWidth, $user)
{
    $dir = opendir($pathToImages);
    while (false !== ($fname = readdir($dir))) {

        $info = pathinfo($pathToImages . $fname);
        if ($fname != $user . "." . $info['extension'])
            continue;
        $img = false;
        if (strtolower($info['extension']) == 'jpg') {
            $img = imagecreatefromjpeg("{$pathToImages}{$fname}");
        } elseif (strtolower($info['extension']) == 'png') {
            $img = imagecreatefrompng("{$pathToImages}{$fname}");
        } elseif (strtolower($info['extension']) == 'gif') {
            $img = imagecreatefromgif("{$pathToImages}{$fname}");
        }

        if ($img) {
            $width = imagesx($img);
            $height = imagesy($img);
            $new_width = $thumbWidth;
            $new_height = floor($height * ($thumbWidth / $width));
            $tmp_img = imagecreatetruecolor($new_width, $new_height);
            imagecopyresized($tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

            if (strtolower($info['extension']) == 'jpg') {
                imagejpeg($tmp_img, "{$pathToThumbs}{$fname}");
            } else if (strtolower($info['extension']) == 'png') {
                imagepng($tmp_img, "{$pathToThumbs}{$fname}");
            } else if (strtolower($info['extension']) == 'gif') {
                imagegif($tmp_img, "{$pathToThumbs}{$fname}");
            }
            imagedestroy($img);
            imagedestroy($tmp_img);
        }
    }
    closedir($dir);
}

function deleteUser($conn, $user_table, $user_id)
{
    $stmt = "DELETE FROM " . $user_table . " WHERE userid= '" . $user_id . "'";
    if ($conn->query($stmt) === FALSE) {
        echo "Couldn't Delete user";
        return;
    } else {
        echo "User deleted";
        return;
    }
}

?>