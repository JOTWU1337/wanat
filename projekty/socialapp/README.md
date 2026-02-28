# SocialApp (lokalny)
1. Skopiuj pliki do katalogu `htdocs/socialapp` w XAMPP/WAMP.
2. Importuj `sql/schema.sql` do MySQL (phpMyAdmin lub terminal).
3. Zmień dane w `config/config.php` (baza, base_url, mailer).
4. Zainstaluj composer dependencies (PHPMailer): `composer require phpmailer/phpmailer`.
5. Ustaw prawa zapisu dla `public/uploads/avatars`.
6. Otwórz `http://localhost/socialapp/public`.


UWAGI: To MVP — wymaga dopracowania, testów i zabezpieczeń w produkcji.