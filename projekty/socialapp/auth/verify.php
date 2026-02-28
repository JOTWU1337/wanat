<?php
require_once __DIR__ . '/../src/header.php';
$token = $_GET['token'] ?? '';
if($token){
$stmt = $db->prepare("SELECT id FROM users WHERE verification_token = ?");
$stmt->execute([$token]);
if($u = $stmt->fetch()){
$db->prepare("UPDATE users SET is_verified=1, verification_token=NULL WHERE id=?")->execute([$u['id']]);
echo '<div class="card">Konto potwierdzone. <a href="/socialapp/auth/login.php">Zaloguj</a></div>';
} else {
echo '<div class="card">Token nieprawidłowy</div>';
}
}
require_once __DIR__ . '/../src/footer.php';