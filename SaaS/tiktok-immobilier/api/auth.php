<?php
require_once __DIR__ . '/config.php';

$action = $_GET['action'] ?? 'me';

if ($action === 'register') {
    $data = getBody();
    if (empty($data['username']) || empty($data['email']) || empty($data['password'])) {
        error('Champs requis : username, email, password');
    }
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        error('Email invalide');
    }

    $db = getDB();

    $stmt = $db->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$data['username'], $data['email']]);
    if ($stmt->fetch()) error('Nom d\'utilisateur ou email déjà utilisé');

    $hash = password_hash($data['password'], PASSWORD_BCRYPT);
    $fullName = $data['full_name'] ?? $data['username'];
    $type = $data['type'] ?? 'particulier';

    $stmt = $db->prepare("INSERT INTO users (username, email, password, full_name, type) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$data['username'], $data['email'], $hash, $fullName, $type]);
    $userId = $db->lastInsertId();

    $token = generateToken($userId);

    json([
        'token' => $token,
        'user' => [
            'id' => (int)$userId,
            'username' => $data['username'],
            'email' => $data['email'],
            'full_name' => $fullName,
            'type' => $type
        ]
    ], 201);
}

elseif ($action === 'login') {
    $data = getBody();
    if (empty($data['email']) || empty($data['password'])) {
        error('Email et mot de passe requis');
    }

    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
    $stmt->execute([$data['email'], $data['email']]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($data['password'], $user['password'])) {
        error('Identifiants incorrects', 401);
    }

    $token = generateToken($user['id']);

    json([
        'token' => $token,
        'user' => [
            'id' => (int)$user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'full_name' => $user['full_name'],
            'avatar' => $user['avatar'],
            'type' => $user['type'],
            'verified' => (bool)$user['verified']
        ]
    ]);
}

elseif ($action === 'me') {
    $user = requireAuth();
    json(['user' => $user]);
}

else {
    error('Action inconnue');
}
