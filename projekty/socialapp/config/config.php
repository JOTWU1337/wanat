<?php
// config/config.php
return [
'db' => [
'host' => '127.0.0.1',
'dbname' => 'social_app',
'user' => 'root',
'pass' => ''
],
// base url dla linków w mailach (lokalny)
'base_url' => 'http://localhost/socialapp/public',
// mailer - do testów lokalnych możesz użyć Mailtrap lub lokalnego smtp
'mailer' => [
'host' => 'smtp.mailtrap.io',
'username' => 'YOUR_MAILTRAP_USER',
'password' => 'YOUR_MAILTRAP_PASS',
'port' => 587,
'from_email' => 'no-reply@socialapp.local',
'from_name' => 'SocialApp'
]
];