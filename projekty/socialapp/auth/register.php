<?php
require_once __DIR__ . '/../src/header.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
if(!verify_csrf($_POST['_csrf'])) die('CSRF');
$username = trim($_POST['username']);
$email = trim($_POST['email']);
$pass = $_POST['password'];
if(strlen($username)<3) $error='Nazwa za krótka';
if(!filter_var($email,FILTER_VALIDATE_EMAIL)) $error='Zły email';


if(empty($error)){
// check exists
$stmt = $db->prepare("SELECT id FROM users WHERE email=? OR username=?");
$stmt->execute([$email,$username]);
if($stmt->fetch()) $error='Konto już istnieje';
}
if(empty($error)){
$hash = password_hash($pass, PASSWORD_DEFAULT);
$token = null;
$stmt = $db->prepare("INSERT INTO users (username,email,password_hash,verification_token,created_at) VALUES (?,?,?,?,NOW())");
$stmt->execute([$username,$email,$hash,$token]);
// send mail
$link = $config['base_url'].'/../auth/verify.php?token='.$token;
$body = "Kliknij w link, aby potwierdzić: <a href=\"{$link}\">Potwierdź</a>";
send_mail($email,'Potwierdź konto',$body);
$success = 'Zarejestrowano. Sprawdź email.';
}
}
?>
<div class="card">
<h2>Rejestracja</h2>
<?php if(!empty($error)): ?><div class="notice"><?php echo e($error); ?></div><?php endif; ?>
<?php if(!empty($success)): ?><div class="notice"><?php echo e($success); ?></div><?php endif; ?>
<form method="post">
<input type="hidden" name="_csrf" value="<?php echo $csrf; ?>">
<label class="field">Nazwa <input class="input" name="username"></label>
<label class="field">E-mail <input class="input" name="email"></label>
<label class="field">Hasło <input type="password" class="input" name="password"></label>
<button class="btn">Zarejestruj</button>
</form>
</div>
<?php require_once __DIR__ . '/../src/footer.php'; ?>