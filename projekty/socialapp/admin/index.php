<?php
require_once __DIR__ . '/../src/header.php';
if(!is_logged_in() || !current_user()['is_admin']){ echo '<div class="card">Brak dostępu</div>'; require_once __DIR__.'/../src/footer.php'; exit; }
$reports = $db->query("SELECT r.*, u.username as reported_username FROM reports r JOIN users u ON u.id = r.reported_user_id WHERE r.status='open'");
?>
<div class="card">
<h2>Panel administracyjny</h2>
<h3>Zgłoszenia</h3>
<?php while($r = $reports->fetch()): ?>
<div class="card">
<div class="small">Zgłoszono: <?php echo e($r['reported_username']); ?></div>
<div><?php echo e($r['content']); ?></div>
<a href="manage_reports.php?resolve=<?php echo $r['id']; ?>" class="btn ghost">Rozwiąż</a>
<a href="manage_reports.php?block=<?php echo $r['reported_user_id']; ?>" class="btn">Zablokuj</a>
</div>
<?php endwhile; ?>
</div>
<?php require_once __DIR__ . '/../src/footer.php'; ?>