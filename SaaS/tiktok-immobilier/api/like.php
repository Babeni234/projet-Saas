<?php
require_once __DIR__ . '/config.php';
$user = requireAuth();
$db = getDB();

$propertyId = $_GET['id'] ?? null;
if (!$propertyId) error('ID du bien requis');

$action = $_GET['action'] ?? 'toggle';

// Check if already liked
$stmt = $db->prepare("SELECT id FROM likes WHERE user_id = ? AND property_id = ?");
$stmt->execute([$user['id'], $propertyId]);
$alreadyLiked = $stmt->fetch();

if ($action === 'like') {
    // Always like (double-tap TikTok style)
    if ($alreadyLiked) {
        // Already liked → do nothing, just return liked=true
        json(['liked' => true, 'message' => 'Déjà aimé']);
    } else {
        $db->prepare("INSERT INTO likes (user_id, property_id) VALUES (?, ?)")->execute([$user['id'], $propertyId]);
        $db->prepare("UPDATE properties SET likes_count = likes_count + 1 WHERE id = ?")->execute([$propertyId]);
        // Create notification
        $propStmt = $db->prepare("SELECT user_id FROM properties WHERE id = ?");
        $propStmt->execute([$propertyId]);
        $propOwner = $propStmt->fetch();
        if ($propOwner && $propOwner['user_id'] != $user['id']) {
            $db->prepare("INSERT INTO notifications (user_id, from_user_id, type, property_id) VALUES (?, ?, 'like', ?)")
               ->execute([$propOwner['user_id'], $user['id'], $propertyId]);
        }
        json(['liked' => true, 'message' => 'Bien aimé']);
    }
} else {
    // Toggle (like/unlike) — comportement par défaut
    if ($alreadyLiked) {
        $db->prepare("DELETE FROM likes WHERE user_id = ? AND property_id = ?")->execute([$user['id'], $propertyId]);
        $db->prepare("UPDATE properties SET likes_count = GREATEST(likes_count - 1, 0) WHERE id = ?")->execute([$propertyId]);
        json(['liked' => false, 'message' => 'Like retiré']);
    } else {
        $db->prepare("INSERT INTO likes (user_id, property_id) VALUES (?, ?)")->execute([$user['id'], $propertyId]);
        $db->prepare("UPDATE properties SET likes_count = likes_count + 1 WHERE id = ?")->execute([$propertyId]);
        // Create notification
        $propStmt = $db->prepare("SELECT user_id FROM properties WHERE id = ?");
        $propStmt->execute([$propertyId]);
        $propOwner = $propStmt->fetch();
        if ($propOwner && $propOwner['user_id'] != $user['id']) {
            $db->prepare("INSERT INTO notifications (user_id, from_user_id, type, property_id) VALUES (?, ?, 'like', ?)")
               ->execute([$propOwner['user_id'], $user['id'], $propertyId]);
        }
        json(['liked' => true, 'message' => 'Bien aimé']);
    }
}
