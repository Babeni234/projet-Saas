<?php
require_once __DIR__ . '/config.php';
$user = requireAuth();
$db = getDB();

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $stmt = $db->prepare("SELECT n.*, u.full_name as from_name, u.username as from_username
                          FROM notifications n
                          LEFT JOIN users u ON n.from_user_id = u.id
                          WHERE n.user_id = ?
                          ORDER BY n.created_at DESC LIMIT 50");
    $stmt->execute([$user['id']]);
    $notifs = $stmt->fetchAll();

    $unread = $db->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = FALSE");
    $unread->execute([$user['id']]);

    json(['notifications' => $notifs, 'unread_count' => (int)$unread->fetchColumn()]);
}

elseif ($method === 'POST') {
    $data = getBody();
    if (isset($data['mark_read'])) {
        if ($data['mark_read'] === 'all') {
            $db->prepare("UPDATE notifications SET is_read = TRUE WHERE user_id = ?")->execute([$user['id']]);
        } else {
            $db->prepare("UPDATE notifications SET is_read = TRUE WHERE id = ? AND user_id = ?")
               ->execute([intval($data['mark_read']), $user['id']]);
        }
        json(['message' => 'Notifications marquées comme lues']);
    }
}
