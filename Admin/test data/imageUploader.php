<?php
    require 'web/d/dbconnect.php';
    function validate($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    if(isset($_POST['btnUploadProfileImg'])){
        $profileID = validate($_COOKIE['profileID']);
        // Check if an image file was uploaded
        if (isset($_FILES["profileUpload"]) && $_FILES["profileUpload"]["error"] == 0) {
            $image = $_FILES['profileUpload']['tmp_name'];
            $imgContent = base64_encode(file_get_contents($image));
    
            // Insert image data into database as BLOB
            $sql = "UPDATE tbladminprofile SET img = (?) WHERE adminProfile = '" . $profileID . "'";
            //$sql = "INSERT INTO tblfacultyinfo(img) VALUES(?) WHERE profileID = '" . $profileID . "'";
            $statement = $conn->prepare($sql);
            $statement->bind_param('s', $imgContent);
            $current_id = $statement->execute() or die("<b>Error:</b> Problem on Image Insert<br/>" . mysqli_connect_error());
        }
    }


?>