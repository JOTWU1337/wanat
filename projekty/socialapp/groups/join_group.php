<?php
// groups/join_group.php
require_once __DIR__ . '/../src/functions.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); echo json_encode(['ok'=>false,'error'=>'method']);
    exit;
}

if (!is_logged_in()) {
    http_response_code(401); echo json_encode(['ok'=>false,'error'=>'login']);
    exit;
}

$raw = $_POST; // jeśli używasz fetch+JSON, użyj json_decode(file_get_contents('php://input'), true)
if (!isset($raw['_csrf']) || !verify_csrf($raw['_csrf'])) {
    http_response_code(400); echo json_encode(['ok'=>false,'error'=>'csrf']);
    exit;
}

$groupId = (int)($raw['group_id'] ?? 0);
$userId = (int)$_SESSION['user_id'];
if ($groupId <= 0) { http_response_code(400); echo json_encode(['ok'=>false,'error'=>'bad_group']); exit; }

try {
    // zacznij transakcję
    $db->beginTransaction();

    // lockujemy wiersz grupy FOR UPDATE, żeby równoległe zapytania nie przeczyły limitowi
    $stmt = $db->prepare("SELECT id, max_members, owner_id FROM groups WHERE id = ? FOR UPDATE");
    $stmt->execute([$groupId]);
    $group = $stmt->fetch();
    if (!$group) {
        $db->rollBack();
        http_response_code(404); echo json_encode(['ok'=>false,'error'=>'no_group']);
        exit;
    }

    // sprawdź, czy user już jest w grupie
    $stmt = $db->prepare("SELECT 1 FROM group_members WHERE group_id = ? AND user_id = ? LIMIT 1");
    $stmt->execute([$groupId, $userId]);
    if ($stmt->fetch()) {
        $db->rollBack();
        echo json_encode(['ok'=>false,'error'=>'already']);
        exit;
    }

    // policz aktualną liczbę członków
    $stmt = $db->prepare("SELECT COUNT(*) AS c FROM group_members WHERE group_id = ?");
    $stmt->execute([$groupId]);
    $count = (int)$stmt->fetchColumn();

    if ($count >= (int)$group['max_members']) {
        $db->rollBack();
        echo json_encode(['ok'=>false,'error'=>'full']);
        exit;
    }

    // wszystko ok — dodaj członka
    $stmt = $db->prepare("INSERT INTO group_members (group_id, user_id, joined_at) VALUES (?,?,NOW())");
    $stmt->execute([$groupId, $userId]);

    // dodaj powiadomienie do właściciela grupy (jeśli właściciel istnieje i to nie ten sam user)
    if (!empty($group['owner_id']) && $group['owner_id'] != $userId) {
        $msg = json_encode(['type'=>'group_join','group_id'=>$groupId,'user_id'=>$userId]);
        $ins = $db->prepare("INSERT INTO notifications (user_id, message, type, meta, created_at) VALUES (?, ?, 'group_join', ?, NOW())");
        $ins->execute([$group['owner_id'], "Ktoś dołączył do Twojej grupy", $msg]);
    }

    $db->commit();
    echo json_encode(['ok'=>true,'message'=>'joined']);
    exit;

} catch (Exception $e) {
    if ($db->inTransaction()) $db->rollBack();
    error_log('Join group error: '.$e->getMessage());
    http_response_code(500);
    echo json_encode(['ok'=>false,'error'=>'server']);
    exit;
}
