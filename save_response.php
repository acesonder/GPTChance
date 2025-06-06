<?php
session_start();
require 'config.php';
$sessionId = session_id();
$field = $_POST['field'] ?? null;
$value = $_POST['value'] ?? null;
if ($field === null) {
    exit;
}
$stmt = $pdo->prepare('SELECT id FROM responses WHERE session_id = ?');
$stmt->execute([$sessionId]);
$response = $stmt->fetch();
if (!$response) {
    $pdo->prepare('INSERT INTO responses (session_id) VALUES (?)')->execute([$sessionId]);
    $responseId = $pdo->lastInsertId();
} else {
    $responseId = $response['id'];
}
$pdo->prepare("UPDATE responses SET `$field` = ? WHERE id = ?")->execute([$value, $responseId]);
?>
