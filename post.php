<?php

function GetPostsWithFriendsQuery($post_table, $user_table, $friend_table, $user_id)
{
    return "SELECT distinct posts.postid, posts.userid, posts.reg_date, posts.private,
					   posts.description, posts.images, posts.likes_num,
					   posts.comments_num, users.username, users.picture FROM " . $post_table . ", " . $user_table . ", " . $friend_table . "
                       WHERE posts.userid='" . $user_id . "' AND users.userid='" . $user_id . "' OR (friends.following_user='" . $user_id . "'
					   AND posts.userid=friends.followed_user AND users.userid=posts.userid)
                       ORDER BY reg_date DESC";
}

function GetPersonalPostQuery($post_table, $user_table, $user_id)
{
    return "SELECT distinct posts.postid, posts.userid, posts.reg_date, posts.private,
					   posts.description, posts.images, posts.likes_num,
					   posts.comments_num, users.username, users.picture FROM " . $post_table . ", " . $user_table . "
                       WHERE posts.userid='" . $user_id . "' AND users.userid='" . $user_id . "'
                       ORDER BY reg_date DESC";
}

function GetPost($conn, $row, $like_table, $user_id)
{
    $images = array();
    if ($row["images"] !== "") {
        $curImg = explode("@", $row["images"]);
        foreach ($curImg as $img)
            array_push($images, $img);
    }
    
    $picture = array();
    if ($row["picture"] !== "" && $row["picture"] !== null) {
        $curImg = explode("@", $row["picture"]);
        foreach ($curImg as $img)
            array_push($picture, $img);
    }
    
    $liked_post = 0;
    $get_post_like = "SELECT likeid FROM " . $like_table . " WHERE userid='" . $user_id . "' AND postid='" . $row["postid"] . "'";
    @$result_post_like = $conn->query($get_post_like);
    if (@$result_post_like->num_rows > 0)
        $liked_post = 1;
        
        return $post = [
            'post_username' => $row["username"],
            'post_id' => $row["postid"],
            'post_user_id' => $row["userid"],
            'date' => $row["reg_date"],
            'private' => (int) $row["private"],
            'description' => $row["description"],
            'picture' => ($picture) ? $picture[1] : null,
            'images' => $images,
            'likes_num' => (int) $row["likes_num"],
            'comments_num' => (int) $row["comments_num"],
            'liked_post' => $liked_post
        ];
}

function UpdatePostInDB($conn, $post_table, $json_post)
{
    $user_update_query = "UPDATE " . $post_table . "
          SET
          description='" . $json_post->description . "',
          private='" . $json_post->private . "'
          WHERE postid='" . $json_post->post_id . "'";

    if ($conn->query($user_update_query) === FALSE)
        return false;
    else
        return true;
}

function AddPostToDB($user_id, $private, $description, $imagesPath)
{
    global $conn, $post_table;

    $stmt = "INSERT INTO " . $post_table . " (userid, description, private, images, likes_num, comments_num) VALUES (";
    $stmt .= "'" . $user_id . "',";
    $stmt .= "'" . $description . "',";
    $stmt .= "'" . $private . "',";
    $stmt .= "'" . $imagesPath . "',";
    $stmt .= "'0',";
    $stmt .= "'0'";
    $stmt .= ")";

    if ($conn->query($stmt) === FALSE)
        return false;
    else
        return true;
}

function UpdateLikePost($conn, $post_table, $likes, $post_id)
{
    $update_query = "UPDATE " . $post_table . " SET likes_num = '" . $likes . "', reg_date=reg_date WHERE postid = '" . $post_id . "'";
    @$result = $conn->query($update_query);
}

function UpdateCommentPostInDB($conn, $post_table, $post_id, $request)
{
    $sql = "SELECT comments_num FROM " . $post_table . " WHERE postid='" . $post_id . "'";
    @$result = $conn->query($sql);
    // if we got a row, then we got a match
    if (@$result->num_rows > 0) {
        @$row = $result->fetch_assoc();
        $oldVal = $row["comments_num"];
    }

    $newVal = $oldVal + 1;
    $update_query = "UPDATE " . $post_table . " SET comments_num = '" . $newVal . "' WHERE postid = '" . $post_id . "'";
    if (@$result = $conn->query($update_query) === FALSE)
        @$request->text = "Couldn't update comment post in DB";
}

function GetPostImagePath($user_name, $img)
{
    $imagesPath = "";
    if (! file_exists("faceAfeka/PostsImages/" . $user_name))
        mkdir("faceAfeka/PostsImages");
    if (! file_exists("faceAfeka/PostsImages/" . $user_name))
        mkdir("faceAfeka/PostsImages/" . $user_name);
    if (! file_exists("faceAfeka/PostsImages/" . $user_name . "/img"))
        mkdir("faceAfeka/PostsImages/" . $user_name . "/img");
    if (! file_exists("faceAfeka/PostsImages/" . $user_name . "/thumbs"))
        mkdir("faceAfeka/PostsImages/" . $user_name . "/thumbs");

    $target_path = "faceAfeka/PostsImages/" . $user_name . "/img/";
    $pathToThumbs = "faceAfeka/PostsImages/" . $user_name . "/thumbs/";

    if (is_array($img)) {
        foreach ($_FILES['myFiles']['name'] as $name => $value) {
            $validextensions = array(
                "jpeg",
                "jpg",
                "png"
            ); // Extensions which are allowed.
            $ext = explode(".", basename($value));
            $file_extension = end($ext); // Store extensions in the variable.
            $imagesPath .= basename($value) . "@";
            if (in_array($file_extension, $validextensions))
                move_uploaded_file($_FILES['myFiles']['tmp_name'][$name], $target_path . $value);
        }
    }
    createPostThumbs($target_path, $pathToThumbs, 200);
    return $imagesPath;
}

function createPostThumbs($pathToImages, $pathToThumbs, $thumbWidth)
{
    $dir = opendir($pathToImages);
    while (false !== ($fname = readdir($dir))) {
        $info = pathinfo($pathToImages . $fname);
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

function deletePostByPostid($conn, $post_table, $post_id)
{
    $stmt = "DELETE FROM " . $post_table . " WHERE postid= '" . $post_id . "'";
    if ($conn->query($stmt) === FALSE)
        return false;
    else
        return true;
}

function deletePostByUserid($conn, $post_table, $user_id)
{
    $stmt = "DELETE FROM " . $post_table . " WHERE userid= '" . $user_id . "'";
    if ($conn->query($stmt) === FALSE)
        return false;
    else
        return true;
}

?>