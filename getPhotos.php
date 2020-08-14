<?php
include "face_afeka_connect.php";

$user_id = @$_POST["user_id"];
$images = array();
$sql_photos = "SELECT images FROM " . $post_table . " WHERE userid = '" . $user_id . "'";

@$result_photos = $conn->query($sql_photos);

if (@$result_photos->num_rows > 0) {
    while (@$row = $result_photos->fetch_assoc()) {
        $tempimage = explode("@", $row['images']);
        foreach ($tempimage as $img) {
            if ($img != "")
                array_push($images, $img);
        }
    }
}
echo @json_encode($images);


?>