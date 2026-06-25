<?php
require_once __DIR__ . '/config.php';
$db = getDB();
$method = $_SERVER['REQUEST_METHOD'];
$id = $_GET['id'] ?? null;

if ($method === 'GET' && $id) {
    $stmt = $db->prepare("SELECT p.*, u.username, u.full_name, u.avatar, u.type as user_type, u.verified, u.phone, u.email, u.website
                          FROM properties p JOIN users u ON p.user_id = u.id WHERE p.id = ?");
    $stmt->execute([$id]);
    $prop = $stmt->fetch();
    if (!$prop) error('Bien introuvable', 404);

    $prop['id'] = (int)$prop['id'];
    $prop['price'] = (int)$prop['price'];

    if ($prop['video_url']) {
        if (strpos($prop['video_url'], 'http') === 0) {
            // Already absolute
        } elseif (strpos($prop['video_url'], 'uploads/') === 0) {
            $prop['video_url'] = VIDEO_URL_BASE . basename($prop['video_url']);
        } else {
            $prop['video_url'] = VIDEO_URL_BASE . $prop['video_url'];
        }
    }

    $user = getAuthUser();
    if ($user) {
        $lk = $db->prepare("SELECT id FROM likes WHERE user_id = ? AND property_id = ?");
        $lk->execute([$user['id'], $prop['id']]);
        $prop['liked'] = (bool)$lk->fetch();

        $sv = $db->prepare("SELECT id FROM saves WHERE user_id = ? AND property_id = ?");
        $sv->execute([$user['id'], $prop['id']]);
        $prop['saved'] = (bool)$sv->fetch();
    }

    // Increment views
    $db->prepare("UPDATE properties SET views = views + 1 WHERE id = ?")->execute([$id]);

    json($prop);
}

elseif ($method === 'POST') {
    $user = requireAuth();
    $data = getBody();

    if (empty($data['title'])) error('Le titre est requis');
    if (empty($data['video_url']) && empty($_FILES['video'])) error('Une vidéo est requise');

    $videoUrl = $data['video_url'] ?? null;

    // Handle file upload
    if (isset($_FILES['video']) && $_FILES['video']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['video']['name'], PATHINFO_EXTENSION);
        $filename = uniqid('vid_') . '.' . $ext;
        $dest = UPLOAD_DIR . 'videos/' . $filename;
        move_uploaded_file($_FILES['video']['tmp_name'], $dest);
        $videoUrl = $filename;

        // Generate thumbnail from video (first frame as placeholder)
        $thumbName = uniqid('thumb_') . '.jpg';
        $thumbDest = UPLOAD_DIR . 'thumbnails/' . $thumbName;
        @copy($dest, $thumbDest); // Placeholder - real thumbnail would use ffmpeg
    }

    $stmt = $db->prepare("INSERT INTO properties (user_id, title, description, price, type, transaction, surface, rooms, bathrooms, city, neighborhood, features, tags, video_url, thumbnail, music_title, music_artist) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $user['id'],
        $data['title'],
        $data['description'] ?? '',
        intval($data['price'] ?? 0),
        $data['type'] ?? 'villa',
        $data['transaction'] ?? 'vente',
        intval($data['surface'] ?? 0),
        intval($data['rooms'] ?? 0),
        intval($data['bathrooms'] ?? 0),
        $data['city'] ?? '',
        $data['neighborhood'] ?? '',
        $data['features'] ?? '',
        $data['tags'] ?? '',
        $videoUrl,
        $data['thumbnail'] ?? null,
        $data['music_title'] ?? 'Son original',
        $data['music_artist'] ?? $user['full_name']
    ]);

    $id = $db->lastInsertId();

    // Update user properties count
    $db->prepare("UPDATE users SET properties_count = (SELECT COUNT(*) FROM properties WHERE user_id = ?) WHERE id = ?")
       ->execute([$user['id'], $user['id']]);

    json(['id' => (int)$id, 'message' => 'Bien publié avec succès'], 201);
}

elseif ($method === 'PUT' && $id) {
    $user = requireAuth();
    $data = getBody();

    $stmt = $db->prepare("SELECT * FROM properties WHERE id = ?");
    $stmt->execute([$id]);
    $prop = $stmt->fetch();
    if (!$prop) error('Bien introuvable', 404);
    if ($prop['user_id'] != $user['id'] && $user['type'] !== 'admin') error('Non autorisé', 403);

    $fields = ['title', 'description', 'price', 'type', 'transaction', 'surface', 'rooms', 'bathrooms', 'city', 'neighborhood', 'features', 'tags', 'status'];
    $updates = [];
    $params = [];

    foreach ($fields as $f) {
        if (isset($data[$f])) {
            $updates[] = "$f = ?";
            $params[] = $data[$f];
        }
    }

    if (count($updates) > 0) {
        $params[] = $id;
        $db->prepare("UPDATE properties SET " . implode(', ', $updates) . " WHERE id = ?")->execute($params);
    }

    json(['message' => 'Bien mis à jour']);
}

elseif ($method === 'DELETE' && $id) {
    $user = requireAuth();
    $stmt = $db->prepare("SELECT * FROM properties WHERE id = ?");
    $stmt->execute([$id]);
    $prop = $stmt->fetch();
    if (!$prop) error('Bien introuvable', 404);
    if ($prop['user_id'] != $user['id'] && $user['type'] !== 'admin') error('Non autorisé', 403);

    // Delete video file
    if ($prop['video_url']) {
        $vname = strpos($prop['video_url'], 'uploads/') === 0 ? basename($prop['video_url']) : $prop['video_url'];
        $vpath = UPLOAD_DIR . 'videos/' . $vname;
        if (file_exists($vpath)) @unlink($vpath);
    }

    $db->prepare("DELETE FROM properties WHERE id = ?")->execute([$id]);
    json(['message' => 'Bien supprimé']);
}

else {
    error('Méthode non supportée');
}
