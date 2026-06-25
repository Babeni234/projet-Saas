<?php
require_once __DIR__ . '/config.php';
$db = getDB();
$method = $_SERVER['REQUEST_METHOD'];
$propertyId = $_GET['id'] ?? null;

if ($method === 'GET') {
    if (!$propertyId) error('ID du bien requis');
    $stmt = $db->prepare("SELECT c.*, u.username, u.full_name, u.avatar, u.type as user_type, u.verified
                          FROM comments c JOIN users u ON c.user_id = u.id
                          WHERE c.property_id = ? ORDER BY c.created_at DESC");
    $stmt->execute([$propertyId]);
    $comments = $stmt->fetchAll();

    foreach ($comments as &$c) {
        if ($c['avatar'] && strpos($c['avatar'], 'http') !== 0) {
            $c['avatar_url'] = 'http://localhost/tiktok-immobilier/uploads/thumbnails/' . $c['avatar'];
        } else {
            $c['avatar_url'] = 'https://i.pravatar.cc/150?u=' . $c['user_id'];
        }
        $c['badge'] = $c['user_type'] === 'enterprise' ? 'agency' : null;
    }

    json($comments);
}

elseif ($method === 'POST') {
    $user = requireAuth();
    if (!$propertyId) error('ID du bien requis');
    $data = getBody();
    if (empty($data['text'])) error('Le texte du commentaire est requis');

    $stmt = $db->prepare("INSERT INTO comments (user_id, property_id, text) VALUES (?, ?, ?)");
    $stmt->execute([$user['id'], $propertyId, $data['text']]);
    $commentId = $db->lastInsertId();
    $stmt2 = $db->prepare("UPDATE properties SET comments_count = (SELECT COUNT(*) FROM comments WHERE property_id = ?) WHERE id = ?");
    $stmt2->execute([$propertyId, $propertyId]);

    // Create notification
    $propStmt = $db->prepare("SELECT user_id FROM properties WHERE id = ?");
    $propStmt->execute([$propertyId]);
    $propOwner = $propStmt->fetch();
    if ($propOwner && $propOwner['user_id'] != $user['id']) {
        $db->prepare("INSERT INTO notifications (user_id, from_user_id, type, property_id, text) VALUES (?, ?, 'comment', ?, ?)")
           ->execute([$propOwner['user_id'], $user['id'], $propertyId, substr($data['text'], 0, 100)]);
    }

    json(['id' => (int)$commentId, 'message' => 'Commentaire ajouté'], 201);
}

elseif ($method === 'DELETE') {
    $user = requireAuth();
    $commentId = $_GET['comment_id'] ?? null;
    if (!$commentId) error('ID du commentaire requis');

    $stmt = $db->prepare("SELECT c.*, p.user_id as prop_owner FROM comments c JOIN properties p ON c.property_id = p.id WHERE c.id = ?");
    $stmt->execute([$commentId]);
    $comment = $stmt->fetch();

    if (!$comment) error('Commentaire introuvable', 404);
    if ($comment['user_id'] != $user['id'] && $user['type'] !== 'admin') error('Non autorisé', 403);

    $db->prepare("DELETE FROM comments WHERE id = ?")->execute([$commentId]);
    json(['message' => 'Commentaire supprimé']);
}
