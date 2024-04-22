<?php

include "connection.php";
session_start();

// Initialize variables
$social_media_url = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    }


    if (isset($_POST['social_media_url'])) {
        $social_media_url = $_POST['social_media_url'];
    }

    if (isset($_POST['country_id'])) {
        $country_id = $_POST['country_id'];
    }

    $conn = new mysqli($servername, $username, $password, $db);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    if($id!=""){
      $update_sql = "UPDATE grd_socmedia_links SET social_media_url = '$social_media_url' WHERE id = $id";
    
    echo $update_sql;

    if ($conn->query($update_sql) === TRUE) {
        echo "Social media URL updated successfully";
    } else {
        echo "Error updating social media URL: " . $conn->error;
    }
  }

}
?>
