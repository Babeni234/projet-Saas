<?php
require_once __DIR__ . '/config.php';
$db = getDB();

$query = $_GET['q'] ?? '';
if (empty($query)) error('Requête de recherche vide');

$type = $_GET['type'] ?? 'all'; // all, properties, users

$results = ['properties' => [], 'users' => []];

if ($type === 'all' || $type === 'properties') {
    $stmt = $db->prepare("SELECT p.*, u.username, u.full_name, u.avatar, u.type as user_type, u.verified
                          FROM properties p JOIN users u ON p.user_id = u.id
                          WHERE p.status = 'active' AND (p.title LIKE ? OR p.description LIKE ? OR p.city LIKE ? OR p.neighborhood LIKE ? OR p.tags LIKE ?)
                          ORDER BY p.views DESC LIMIT 20");
    $q = '%' . $query . '%';
    $stmt->execute([$q, $q, $q, $q, $q]);
    $results['properties'] = $stmt->fetchAll();

    foreach ($results['properties'] as &$p) {
        $p['id'] = (int)$p['id'];
        if ($p['video_url']) {
            if (strpos($p['video_url'], 'http') === 0) {
                // Already absolute
            } elseif (strpos($p['video_url'], 'uploads/') === 0) {
                $p['video_url'] = VIDEO_URL_BASE . basename($p['video_url']);
            } else {
                $p['video_url'] = VIDEO_URL_BASE . $p['video_url'];
            }
        }
    }
}

if ($type === 'all' || $type === 'users') {
    $stmt = $db->prepare("SELECT id, username, full_name, avatar, bio, type, verified, followers_count
                          FROM users WHERE username LIKE ? OR full_name LIKE ? OR bio LIKE ?
                          ORDER BY followers_count DESC LIMIT 20");
    $q = '%' . $query . '%';
    $stmt->execute([$q, $q, $q]);
    $results['users'] = $stmt->fetchAll();
}

json($results);
