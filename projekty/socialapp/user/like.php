<?php
require_once __DIR__ . '/../src/functions.php';
header('Content-Type: application/json');
$input = json_decode(file_get_contents('php://input'), true);
if(!$input) exit(json_encode(['ok'=>false,'error'=>'no input']));
if(!is_logged_in()) exit(json_encode(['ok'=>false,'error'=>'login']));
$target = (int)$input['target_id'];
$userId = $_SESSION['user_id'];
// check limit
$stmt = $db->prepare("SELECT COUNT(*) as c FROM likes WHERE user_id = ? AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)");
$stmt->execute([$userId]);
$c = (int)$stmt->fetchColumn();
if($c >= 5) exit(json_encode(['ok'=>false,'error'=>'limit']));
// no duplicates
$stmt = $db->prepare("SELECT id FROM likes WHERE user_id=? AND target_id=?"); $stmt->execute([$userId,$target]);
if($stmt->fetch()) exit(json_encode(['ok'=>false,'error'=>'exists']));
$db->prepare("INSERT INTO likes (user_id,target_id,created_at) VALUES (?,?,NOW())")->execute([$userId,$target]);
// check match
$stmt = $db->prepare("SELECT id FROM likes WHERE user_id=? AND target_id=?");
$stmt->execute([$target,$userId]);
if($stmt->fetch()){
// create notifications
$db->prepare("INSERT INTO notifications (user_id,message,type,created_at) VALUES (?,?,'match',NOW())")->execute([$userId, 'Masz nowy match']);
$db->prepare("INSERT INTO notifications (user_id,message,type,created_at) VALUES (?,?,'match',NOW())")->execute([$target, 'Masz nowy match']);
echo json_encode(['ok'=>true,'message'=>'Match!']);
} else {
echo json_encode(['ok'=>true,'message'=>'Polubiono']);
}