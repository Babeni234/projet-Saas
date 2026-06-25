<?php
require_once __DIR__ . '/config.php';
$user = requireAuth();
$db = getDB();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') error('Méthode non supportée');

// Handle video upload
if (isset($_FILES['video']) && $_FILES['video']['error'] === UPLOAD_ERR_OK) {
    $ext = strtolower(pathinfo($_FILES['video']['name'], PATHINFO_EXTENSION));
    $allowed = ['mp4', 'mov', 'avi', 'mkv', 'webm', '3gp'];
    if (!in_array($ext, $allowed)) error('Format vidéo non supporté. Formats acceptés: mp4, mov, webm');

    $maxSize = 500 * 1024 * 1024; // 500MB
    if ($_FILES['video']['size'] > $maxSize) error('Vidéo trop volumineuse (max 500MB)');

    $filename = uniqid('vid_') . '.' . $ext;
    $dest = UPLOAD_DIR . 'videos/' . $filename;
    move_uploaded_file($_FILES['video']['tmp_name'], $dest);

    json(['video_url' => $filename, 'message' => 'Vidéo uploadée']);
}

// Handle thumbnail/image upload
elseif (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    if (!in_array($ext, $allowed)) error('Format image non supporté');

    $filename = uniqid('img_') . '.' . $ext;
    $dest = UPLOAD_DIR . 'thumbnails/' . $filename;
    move_uploaded_file($_FILES['image']['tmp_name'], $dest);

    json(['image_url' => $filename, 'message' => 'Image uploadée']);
}

// Handle avatar upload
elseif (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
    $ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
    if (!in_array($ext, $allowed)) error('Format image non supporté');

    $filename = 'avatar_' . $user['id'] . '.' . $ext;
    $dest = UPLOAD_DIR . 'thumbnails/' . $filename;
    move_uploaded_file($_FILES['avatar']['tmp_name'], $dest);

    $db->prepare("UPDATE users SET avatar = ? WHERE id = ?")->execute([$filename, $user['id']]);

    json(['avatar_url' => $filename, 'message' => 'Avatar mis à jour']);
}

else {
    error('Aucun fichier reçu');
}
