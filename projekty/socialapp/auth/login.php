<?php
require_once __DIR__ . '/../src/header.php';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
if(!verify_csrf($_POST['_csrf'])) die('CSRF');
$email = $_POST['email']; $pass = $_POST['password'];
$stmt = $db->prepare("SELECT id,password_hash,is_verified FROM users WHERE email = ?");
$stmt->execute([$email]);
$row = $stmt->fetch();
if($row && password_verify($pass, $row['password_hash'])){
if(!$row['is_verified']){$error='Potwierdź email';}
else{
session_regenerate_id(true);
$_SESSION['user_id'] = $row['id'];
header('Location: /socialapp/public');exit;
}
} else $error='Błędny login';
}
?>
<div class="card">
<h2>Logowanie</h2>
<?php if(!empty($error)): ?><div class="notice"><?php echo e($error); ?></div><?php endif; ?>
<form method="post">
<input type="hidden" name="_csrf" value="<?php echo $csrf; ?>">
<label class="field">E-mail <input class="input" name="email"></label>
<label class="field">Hasło <input type="password" class="input" name="password"></label>
<button class="btn">Zaloguj</button>
</form>
</div>
<?php require_once __DIR__ . '/../src/footer.php'; ?>