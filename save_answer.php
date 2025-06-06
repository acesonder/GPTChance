<?php
session_start();
require 'config.php';
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($mysqli->connect_errno) {
    http_response_code(500);
    exit('DB connection failed');
}
$session_id = $_POST['session_id'] ?? '';
$field = $_POST['field'] ?? '';
$value = $_POST['value'] ?? '';
if (!$session_id || !$field) { exit; }
$field = preg_replace('/[^a-z0-9_]/', '', $field);
$stmt = $mysqli->prepare('SELECT id FROM survey_responses WHERE session_id=?');
$stmt->bind_param('s', $session_id);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows === 0) {
    $ins = $mysqli->prepare('INSERT INTO survey_responses (session_id) VALUES (?)');
    $ins->bind_param('s', $session_id);
    $ins->execute();
    $ins->close();
}
$stmt->close();
$query = "UPDATE survey_responses SET `$field`=? WHERE session_id=?";
$upd = $mysqli->prepare($query);
$upd->bind_param('ss', $value, $session_id);
$upd->execute();
if ($upd->errno) {
    error_log("Database update error: " . $upd->error);
    http_response_code(500);
    exit('Failed to update survey response');
}
$upd->close();
$mysqli->close();
echo 'ok';
?>
