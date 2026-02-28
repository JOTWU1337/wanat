<?php
// src/functions.php
session_start();
require_once __DIR__ . '/db.php';
$config = require __DIR__ . '/../config/config.php';


function generate_csrf() {
if (empty($_SESSION['_csrf_token'])) {
$_SESSION['_csrf_token'] = bin2hex(random_bytes(16));
}
return $_SESSION['_csrf_token'];
}


function verify_csrf($token) {
return isset($_SESSION['_csrf_token']) && hash_equals($_SESSION['_csrf_token'], $token);
}


function is_logged_in() {
return !empty($_SESSION['user_id']);
}


function require_login() {
if (!is_logged_in()) {
header('Location: /socialapp/auth/login.php');
exit;
}
}


function current_user() {
global $db;
if (!is_logged_in()) return null;
$stmt = $db->prepare("SELECT id, username, email, is_admin FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
return $stmt->fetch();
}


function send_mail($to, $subject, $body) {
    $dir = __DIR__ . '/../tmp_mail_logs';
    if (!is_dir($dir)) mkdir($dir, 0777, true);

    $file = $dir . '/mail_' . date('Ymd_His') . '.html';
    file_put_contents($file,
        "<h3>TO: $to</h3><h4>$subject</h4><hr>$body"
    );

    return true;
}


// proste bezpieczne output
function e($s) {
return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}


// oblicz status reputacji na podstawie ocen z ostatnich 12 miesięcy
function reputation_status($userId) {
global $db;
$stmt = $db->prepare("SELECT rating, COUNT(*) as c FROM user_ratings WHERE target_id = ? AND created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH) GROUP BY rating");
$stmt->execute([$userId]);
$counts = ['green'=>0,'yellow'=>0,'red'=>0];
while ($r = $stmt->fetch()) {
$counts[$r['rating']] = (int)$r['c'];
}
$total = $counts['green'] + $counts['yellow'] + $counts['red'];
if ($total == 0) return 'unknown';
$score = ($counts['green']*3 + $counts['yellow']*2 + $counts['red']*1)/($total*3); // 0..1
if ($score >= 0.75) return 'green';
}