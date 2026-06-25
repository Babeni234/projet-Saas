<?php
require_once __DIR__ . '/config.php';

$db = getDB();
$user = getAuthUser();

$page = max(1, intval($_GET['page'] ?? 1));
$limit = min(20, max(5, intval($_GET['limit'] ?? 10)));
$offset = ($page - 1) * $limit;
$feed = $_GET['feed'] ?? 'foryou';
$filters = [];

$where = "WHERE p.status = 'active'";
$params = [];

// Transaction filter
if (!empty($_GET['trans']) && $_GET['trans'] !== 'all') {
    $where .= " AND p.transaction = ?";
    $params[] = $_GET['trans'];
}

// Type filter
if (!empty($_GET['type']) && $_GET['type'] !== 'all') {
    $where .= " AND p.type = ?";
    $params[] = $_GET['type'];
}

// Budget filter
if (!empty($_GET['budget']) && intval($_GET['budget']) < 500000000) {
    $where .= " AND p.price <= ?";
    $params[] = intval($_GET['budget']);
}

// Surface filter
if (!empty($_GET['surface']) && intval($_GET['surface']) > 0) {
    $where .= " AND p.surface >= ?";
    $params[] = intval($_GET['surface']);
}

// City filter
if (!empty($_GET['city'])) {
    $where .= " AND (p.city LIKE ? OR p.neighborhood LIKE ?)";
    $c = '%' . $_GET['city'] . '%';
    $params[] = $c; $params[] = $c;
}

// Subscriptions feed
if ($feed === 'subs' && $user) {
    $stmtSubs = $db->prepare("SELECT following_id FROM follows WHERE follower_id = ?");
    $stmtSubs->execute([$user['id']]);
    $following = $stmtSubs->fetchAll(PDO::FETCH_COLUMN);
    if (count($following) > 0) {
        $placeholders = implode(',', array_fill(0, count($following), '?'));
        $where .= " AND p.user_id IN ($placeholders)";
        $params = array_merge($params, $following);
    } else {
        json(['properties' => [], 'page' => $page, 'has_more' => false]);
    }
}

// User-specific feed
if (!empty($_GET['user_id'])) {
    $where .= " AND p.user_id = ?";
    $params[] = intval($_GET['user_id']);
}

// Liked properties
if (!empty($_GET['liked']) && $user) {
    $where .= " AND p.id IN (SELECT property_id FROM likes WHERE user_id = ?)";
    $params[] = $user['id'];
}

// Saved properties
if (!empty($_GET['saved']) && $user) {
    $where .= " AND p.id IN (SELECT property_id FROM saves WHERE user_id = ?)";
    $params[] = $user['id'];
}

// Order
$order = "ORDER BY p.created_at DESC";
if ($feed === 'explore') $order = "ORDER BY p.views DESC";
if ($feed === 'foryou') $order = "ORDER BY RAND()";

$countStmt = $db->prepare("SELECT COUNT(*) FROM properties p $where");
$countStmt->execute($params);
$total = $countStmt->fetchColumn();

$sql = "SELECT p.*, u.username, u.full_name, u.avatar, u.type as user_type, u.verified
        FROM properties p
        JOIN users u ON p.user_id = u.id
        $where
        $order
        LIMIT ? OFFSET ?";
$allParams = array_merge($params, [$limit, $offset]);
$stmt = $db->prepare($sql);
$stmt->execute($allParams);
$properties = $stmt->fetchAll();

// Enrich with user interaction status
foreach ($properties as &$prop) {
    $prop['id'] = (int)$prop['id'];
    $prop['price'] = (int)$prop['price'];
    $prop['liked'] = false;
    $prop['saved'] = false;
    $prop['following'] = false;

    if ($user) {
        // Check if liked
        $lk = $db->prepare("SELECT id FROM likes WHERE user_id = ? AND property_id = ?");
        $lk->execute([$user['id'], $prop['id']]);
        $prop['liked'] = (bool)$lk->fetch();

        // Check if saved
        $sv = $db->prepare("SELECT id FROM saves WHERE user_id = ? AND property_id = ?");
        $sv->execute([$user['id'], $prop['id']]);
        $prop['saved'] = (bool)$sv->fetch();

        // Check if following
        $fw = $db->prepare("SELECT id FROM follows WHERE follower_id = ? AND following_id = ?");
        $fw->execute([$user['id'], $prop['user_id']]);
        $prop['following'] = (bool)$fw->fetch();
    }

    // Format video URL
    if ($prop['video_url']) {
        if (strpos($prop['video_url'], 'http') === 0) {
            // Already absolute URL
        } elseif (strpos($prop['video_url'], 'uploads/') === 0) {
            $prop['video_url'] = VIDEO_URL_BASE . basename($prop['video_url']);
        } else {
            $prop['video_url'] = VIDEO_URL_BASE . $prop['video_url'];
        }
    }
    if ($prop['thumbnail'] && strpos($prop['thumbnail'], 'http') !== 0) {
        $prop['thumbnail_url'] = THUMB_URL_BASE . basename($prop['thumbnail']);
    } else {
        $prop['thumbnail_url'] = $prop['thumbnail'] ?: null;
    }

    // Format avatar URL
    if ($prop['avatar'] && strpos($prop['avatar'], 'http') !== 0) {
        $prop['avatar_url'] = THUMB_URL_BASE . basename($prop['avatar']);
    } else {
        $prop['avatar_url'] = $prop['avatar'] ?: 'https://i.pravatar.cc/150?u=' . $prop['user_id'];
    }

    // Count comments
    $cc = $db->prepare("SELECT COUNT(*) FROM comments WHERE property_id = ?");
    $cc->execute([$prop['id']]);
    $prop['comments_count'] = (int)$cc->fetchColumn();
}

$hasMore = ($offset + $limit) < $total;

json([
    'properties' => $properties,
    'page' => $page,
    'has_more' => $hasMore,
    'total' => (int)$total
]);
