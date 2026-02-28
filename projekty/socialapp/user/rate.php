<?php
require_once __DIR__ . '/../src/functions.php';
if($_SERVER['REQUEST_METHOD'] !== 'POST') exit(json_encode(['ok'=>false,'error'=>'Method']));
if(!verify_csrf($_POST['_csrf'])) exit(json_encode(['ok'=>false,'error'=>'CSRF']));
if(!is_logged_in()) exit(json_encode(['ok'=>false,'error'=>'login']));
$target = (int)$_POST['target_id'];
$rating = $_POST['rating'];
if(!in_array($rating,['green','yellow','red'])) exit(json_encode(['ok'=>false,'error'=>'bad']));
$userId = $_SESSION['user_id'];
// save or replace
$stmt = $db->prepare("REPLACE INTO user_ratings (rater_id,target_id,rating,created_at) VALUES (?,?,?,NOW())");
$stmt->execute([$userId,$target,$rating]);
// check mutual
$mut = $db->prepare("SELECT rating FROM user_ratings WHERE rater_id = ? AND target_id = ?");
$mut->execute([$target,$userId]);
if($mut->fetch()){
// mutual rating exists -> optionally notify
$db->prepare("INSERT INTO notifications (user_id,message,type,created_at) VALUES (?,?,'rating',NOW())")->execute([$target, "Masz wzajemną ocenę od {$userId}"]);
$db->prepare("INSERT INTO notifications (user_id,message,type,created_at) VALUES (?,?,'rating',NOW())")->execute([$userId, "Wystawiono wzajemną ocenę"]);
}
echo json_encode(['ok'=>true]);