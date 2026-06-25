<?php
require_once __DIR__ . '/config.php';
$db = getDB();

$id = $_GET['id'] ?? null;
if (!$id) error('ID utilisateur requis');

$stmt = $db->prepare("SELECT id, username, full_name, avatar, bio, type, verified, phone, website, followers_count, following_count, properties_count, cover_color, created_at FROM users WHERE id = ?");
$stmt->execute([$id]);
$profile = $stmt->fetch();

if (!$profile) error('Utilisateur introuvable', 404);

if ($profile['avatar']) {
    $profile['avatar_url'] = strpos($profile['avatar'], 'http') === 0 ? $profile['avatar'] : THUMB_URL_BASE . basename($profile['avatar']);
} else {
    $profile['avatar_url'] = 'https://i.pravatar.cc/150?u=' . $profile['id'];
}

$profile['id'] = (int)$profile['id'];

// Check follow status
$user = getAuthUser();
$profile['is_following'] = false;
$profile['is_owner'] = false;
if ($user) {
    $profile['is_owner'] = ($user['id'] == $profile['id']);
    $fw = $db->prepare("SELECT id FROM follows WHERE follower_id = ? AND following_id = ?");
    $fw->execute([$user['id'], $profile['id']]);
    $profile['is_following'] = (bool)$fw->fetch();
}

// Get user properties count
$pc = $db->prepare("SELECT COUNT(*) FROM properties WHERE user_id = ? AND status = 'active'");
$pc->execute([$id]);
$profile['properties_count'] = (int)$pc->fetchColumn();

// Get user stats
$lk = $db->prepare("SELECT COUNT(*) FROM likes l JOIN properties p ON l.property_id = p.id WHERE p.user_id = ?");
$lk->execute([$id]);
$profile['total_likes'] = (int)$lk->fetchColumn();

json($profile);
