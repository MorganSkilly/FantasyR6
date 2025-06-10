<?php
session_start();
$_SESSION['internal_api_call'] = true;

require "api.php";

$host = $_SESSION['sql_host'];
$username = $_SESSION['sql_username'];
$password = $_SESSION['sql_password'];
$database = $_SESSION['sql_database'];

$conn = mysqli_connect($host, $username, $password, $database);

// Check the connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "Connected successfully";

$discord_id = $_SESSION['discord_user_data']['id'];

$query = "SELECT * FROM users WHERE discord_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $discord_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  $user_exists = TRUE;
} else {
  $user_exists = FALSE;
}

if ($user_exists)
{
    echo $_POST['twitch'];
    echo $_POST['twitter'];

    if(isset($_POST['twitch']) && !empty($_POST['twitch']))
    {
        $twitch_acc = $_POST['twitch'];
        $lastSlashPosition = strrpos($twitch_acc, "/");
        
        if ($lastSlashPosition !== false) {
            $parsed_twitch_acc = substr($twitch_acc, $lastSlashPosition + 1);
        }
        else{
            $parsed_twitch_acc = $twitch_acc;
        }

        $sql1 = "UPDATE users SET twitch_name = ? WHERE discord_id = ?";

        $stmtupdate1 = $conn->prepare($sql1);
        $stmtupdate1->bind_param("si", $parsed_twitch_acc, $discord_id);

        if ($stmtupdate1->execute()) {
            echo "Profile updated successfully.";
        } else {
            echo "Error updating twitch name: " . $stmtupdate1->error;
        }
    }
    if(isset($_POST['twitter']) && !empty($_POST['twitter']))
    {
        $twitter_acc = $_POST['twitter'];
        $lastSlashPosition = strrpos($twitter_acc, "/");
        
        if ($lastSlashPosition !== false) {
            $parsed_twitter_acc = substr($twitter_acc, $lastSlashPosition + 1);
        }
        else{
            $parsed_twitter_acc = $twitter_acc;
        }

        $sql2 = "UPDATE users SET twitter_name = ? WHERE discord_id = ?";

        $stmtupdate2 = $conn->prepare($sql2);
        $stmtupdate2->bind_param("si", $parsed_twitter_acc, $discord_id);

        if ($stmtupdate2->execute()) {
            echo "Profile updated successfully.";
        } else {
            echo "Error updating twitter name: " . $stmtupdate2->error;
        }
    }

    header("Location: profile.php?user=" . $discord_id);
    

}
?>