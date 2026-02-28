<?php
require_once __DIR__ . '/../src/header.php';
$id = (int)($_GET['id'] ?? 0);
if(!$id) { echo '<div class="card">Brak użytkownika</div>'; require_once __DIR__.'/../src/footer.php'; exit; }
$stmt = $db->prepare("SELECT id,username,created_at FROM users WHERE id = ?");
$stmt->execute([$id]);
$userRow = $stmt->fetch();
if(!$userRow){ echo '<div class="card">Nie znaleziono</div>'; require_once __DIR__.'/../src/footer.php'; exit; }
$status = reputation_status($id);
?>
<div class="card profile-card">
<div class="avatar"><?php echo strtoupper(substr($userRow['username'],0,1)); ?></div>
<div style="flex:1">
<h3><?php echo e($userRow['username']); ?></h3>
<div class="small">Reputacja: <?php if($status=='green') echo '<span class="status-dot status-green"></span> Wysoka'; elseif($status=='yellow') echo '<span class="status-dot status-yellow"></span> Neutralna'; elseif($status=='red') echo '<span class="status-dot status-red"></span> Do poprawy'; else echo 'Brak ocen'; ?></div>
</div>
<div>
<?php if(is_logged_in() && $_SESSION['user_id'] != $id): ?>
<button class="btn" onclick="likeUser(<?php echo $id; ?>)">Polub</button>
<form method="post" action="/socialapp/user/rate.php" style="display:inline">
<input type="hidden" name="_csrf" value="<?php echo $csrf; ?>">
<input type="hidden" name="target_id" value="<?php echo $id; ?>">
<select name="rating" class="input" style="width:auto;display:inline-block">
<option value="green">Zielony</option>
<option value="yellow">Żółty</option>
<option value="red">Czerwony</option>
</select>
<button class="btn ghost">Wystaw</button>
</form>
<?php endif; ?>
</div>
</div>


<?php require_once __DIR__ . '/../src/footer.php'; ?>