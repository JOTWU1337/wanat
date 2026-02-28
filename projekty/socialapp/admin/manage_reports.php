<?php
require_once __DIR__ . '/../src/functions.php';
if(!is_logged_in() || !current_user()['is_admin']) die('no');
if(isset($_GET['resolve'])){
$id = (int)$_GET['resolve'];
$db->prepare("UPDATE reports SET status='handled' WHERE id=?")->execute([$id]);
header('Location: /socialapp/admin'); exit;
}
if(isset($_GET['block'])){
$u = (int)$_GET['block'];
$db->prepare("UPDATE users SET is_blocked=1 WHERE id=?")->execute([$u]);
header('Location: /socialapp/admin'); exit;
}