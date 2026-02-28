<?php
// src/header.php
require_once __DIR__ . '/functions.php';
$csrf = generate_csrf();
$user = is_logged_in() ? current_user() : null;
?>
<!doctype html>
<html lang="pl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>SocialApp</title>
<link rel="stylesheet" href="/socialapp/public/css/style.css">
</head>
<body>
<header class="sa-header">
<div class="container">
<a class="logo" href="/socialapp/public">SocialApp</a>
<nav>
<?php if ($user): ?>
<a href="/socialapp/public">Mapa</a>
<a href="/socialapp/user/profile.php?id=<?php echo $user['id']; ?>">Mój profil</a>
<a href="/socialapp/groups/create_group.php">Grupy</a>
<?php if ($user['is_admin']): ?>
<a href="/socialapp/admin">Admin</a>
<?php endif; ?>
<a href="/socialapp/auth/logout.php">Wyloguj</a>
<?php else: ?>
<a href="/socialapp/auth/login.php">Zaloguj</a>
<a href="/socialapp/auth/register.php">Rejestracja</a>
<?php endif; ?>
</nav>
</div>
</header>
<main class="container">