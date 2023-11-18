<?php
session_start();
require __DIR__ . '/session-variables.php';
setDiscordOauthKeys();

$token = $_SESSION['bot_token'];
$app_Id = $_SESSION['app_id'];

$command = [
    'name' => 'test',
    'description' => 'test',
    'type' => 1, // 1 for slash commands
];

$ch = curl_init("https://discord.com/api/v10/applications/{$app_Id}/commands");

$headers = [
    "Authorization: Bot {$token}",
    "Content-Type: application/json",
];

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($command));

$response = curl_exec($ch);

if ($response === false) {
    echo 'Error: ' . curl_error($ch);
} else {
    echo 'Global slash command registered successfully: ' . $response;
}

curl_close($ch);
session_destroy();
?>




