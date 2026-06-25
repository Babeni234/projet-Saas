<?php
require_once __DIR__ . '/config.php';
$user = requireAuth();
$db = getDB();

$targetId = $_GET['id'] ?? null;
if (!$targetId) error('ID de l\'utilisateur requis');
if ($targetId == $user['id']) error('Vous ne pouvez pas vous suivre vous-même');

// Check if already following
$stmt = $db->prepare("SELECT id FROM follows WHERE follower_id = ? AND following_id = ?");
$stmt->execute([$user['id'], $targetId]);

if ($stmt->fetch()) {
    // Unfollow
    $db->prepare("DELETE FROM follows WHERE follower_id = ? AND following_id = ?")->execute([$user['id'], $targetId]);
    $db->prepare("UPDATE users SET followers_count = GREATEST(followers_count - 1, 0) WHERE id = ?")->execute([$targetId]);
    $db->prepare("UPDATE users SET following_count = GREATEST(following_count - 1, 0) WHERE id = ?")->execute([$user['id']]);
    json(['following' => false, 'message' => 'Désabonné']);
} else {
    // Follow
    $db->prepare("INSERT INTO follows (follower_id, following_id) VALUES (?, ?)")->execute([$user['id'], $targetId]);
    $db->prepare("UPDATE users SET followers_count = followers_count + 1 WHERE id = ?")->execute([$targetId]);
    $db->prepare("UPDATE users SET following_count = following_count + 1 WHERE id = ?")->execute([$user['id']]);
    json(['following' => true, 'message' => 'Abonné']);
}
