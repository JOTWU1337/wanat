<?php
require_once __DIR__ . '/../src/functions.php';
header('Content-Type: application/json');
if(!is_logged_in()) echo json_encode(['count'=>0]);
$uid = $_SESSION['user_id'];
$stmt = $db->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read=0");
$stmt->execute([$uid]);
$c = (int)$stmt->fetchColumn();
echo json_encode(['count'=>$c]);