<?php
require_once __DIR__ . '/../src/header.php';
require_login();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {    if (!verify_csrf($_POST['_csrf']))
        die('CSRF');    $name = trim($_POST['name']);
    $desc = trim($_POST['description']);
    $max = (int)$_POST['max_members'];    if (strlen($name) < 3)
        $error = 'Nazwa za krótka';    if (empty($error)) {        $stmt = $db->prepare("INSERT INTO groups (name,description,max_members,owner_id,created_at) VALUES (?,?,?,?,NOW())");        $stmt->execute([$name, $desc, $max, $_SESSION['user_id']]);        $gid = $db->lastInsertId();        $db->prepare("INSERT INTO group_members (group_id,user_id,joined_at) VALUES (?,?,NOW())")->execute([$gid, $_SESSION['user_id']]);        header('Location: /socialapp/groups/group.php?id=' . $gid);
        exit;    }
}
?>
<style>
/* Modern styling for the create group page */
.modern-container {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 3rem 1rem;
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
    animation: fadeIn 0.6s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(15px); }
    to { opacity: 1; transform: translateY(0); }
}

.modern-card {
    background: rgba(30, 30, 35, 0.7);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 20px;
    padding: 2.5rem;
    width: 100%;
    max-width: 480px;
    box-shadow: 0 24px 48px rgba(0, 0, 0, 0.5);
    color: #e0e0e0;
}

.modern-card h2 {
    margin-top: 0;
    margin-bottom: 1.5rem;
    font-size: 1.85rem;
    font-weight: 600;
    letter-spacing: -0.5px;
    color: #fff;
    text-align: center;
    background: linear-gradient(90deg, #fff, #a5b4fc);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.modern-notice {
    background: rgba(239, 68, 68, 0.15);
    border-left: 4px solid #ef4444;
    color: #fca5a5;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    font-size: 0.95rem;
}

.modern-form .field-group {
    display: flex;
    flex-direction: column;
    margin-bottom: 1.4rem;
}

.modern-form label {
    font-size: 0.9rem;
    font-weight: 500;
    margin-bottom: 0.5rem;
    color: #aeb1b8;
    transition: color 0.3s ease;
}

.modern-input {
    width: 100%;
    background: rgba(15, 15, 18, 0.6);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 0.9rem 1rem;
    font-size: 1rem;
    color: #fff;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-sizing: border-box;
    font-family: inherit;
}

.modern-input:focus {
    outline: none;
    border-color: #6366f1;
    background: rgba(15, 15, 18, 0.8);
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
}

.modern-form .field-group:focus-within label {
    color: #6366f1;
}

textarea.modern-input {
    resize: vertical;
    min-height: 110px;
}

.modern-btn {
    width: 100%;
    margin-top: 1rem;
    background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
    color: #fff;
    border: none;
    border-radius: 12px;
    padding: 1.05rem;
    font-size: 1.05rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
}

.modern-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
    background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
}

.modern-btn:active {
    transform: translateY(1px);
    box-shadow: 0 2px 8px rgba(99, 102, 241, 0.3);
}
</style>

<div class="modern-container">
    <div class="modern-card">
        <h2>Utwórz grupę</h2>
        <?php if (!empty($error))
    echo '<div class="modern-notice">' . e($error) . '</div>'; ?>
        <form method="post" class="modern-form">
            <input type="hidden" name="_csrf" value="<?php echo $csrf; ?>">
            <div class="field-group">
                <label for="name">Nazwa</label>
                <input type="text" id="name" class="modern-input" name="name" placeholder="np. Wyjazd w góry" required>
            </div>
            <div class="field-group">
                <label for="description">Opis</label>
                <textarea id="description" name="description" class="modern-input" placeholder="Napisz coś więcej..."></textarea>
            </div>
            <div class="field-group">
                <label for="max_members">Maks członków</label>
                <input type="number" id="max_members" name="max_members" class="modern-input" value="5" min="2" required>
            </div>
            <button class="modern-btn">Utwórz</button>
        </form>
    </div>
</div>
<?php require_once __DIR__ . '/../src/footer.php'; ?>