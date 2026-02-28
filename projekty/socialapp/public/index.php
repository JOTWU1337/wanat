<?php
require_once __DIR__ . '/../src/header.php';
?>
<div class="card">
<h2>Mapa — przegląd lokalny</h2>
<p class="small">Mapa jest demo — pokażemy listę użytkowników z regionu. Kliknij profil, żeby zobaczyć relacje.</p>
</div>


<?php
// Prosty listing użytkowników
$stmt = $db->query("SELECT id, username FROM users ORDER BY created_at DESC LIMIT 50");
while($row = $stmt->fetch()):
?>
<div class="card profile-card">
<div class="avatar"><?php echo strtoupper(substr($row['username'],0,1)); ?></div>
<div style="flex:1">
<a href="/socialapp/user/profile.php?id=<?php echo $row['id']?>"><strong><?php echo e($row['username']); ?></strong></a>
<div class="small">Aktywny w regionie</div>
</div>
<div>
<button class="btn ghost" onclick="location.href='/socialapp/user/profile.php?id=<?php echo $row['id']?>'">Profil</button>
</div>
</div>
<?php endwhile; ?>


<?php require_once __DIR__ . '/../src/footer.php'; ?>