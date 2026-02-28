<?php
require_once __DIR__ . '/../src/header.php';
$id = (int)($_GET['id'] ?? 0);
$stmt = $db->prepare("SELECT * FROM groups WHERE id=?"); $stmt->execute([$id]);
$g = $stmt->fetch(); if(!$g) { echo '<div class="card">Brak</div>'; require_once __DIR__.'/../src/footer.php'; exit; }
$members = $db->prepare("SELECT u.id,u.username FROM group_members gm JOIN users u ON u.id=gm.user_id WHERE gm.group_id = ?"); $members->execute([$id]);
?>
<div class="card">
<h2><?php echo e($g['name']); ?></h2>
<p class="small"><?php echo e($g['description']); ?></p>
<div class="small">Miejsc: <?php echo e($g['max_members']); ?></div>
<div style="margin-top:12px">
<strong>Uczestnicy:</strong>
<ul>
<?php while($m = $members->fetch()): ?>
<li><?php echo e($m['username']); ?></li>
<?php endwhile; ?>
</ul>
</div>
<?php if(is_logged_in()): ?>
<form method="post" action="/socialapp/groups/join_group.php">
<input type="hidden" name="_csrf" value="<?php echo $csrf; ?>">
<input type="hidden" name="group_id" value="<?php echo $id; ?>">
<button class="btn">Dołącz</button>
</form>
<?php endif; ?>
</div>
<?php require_once __DIR__ . '/../src/footer.php'; ?>