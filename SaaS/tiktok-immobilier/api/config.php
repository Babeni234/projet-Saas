<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'immotok');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('UPLOAD_DIR', __DIR__ . '/../uploads/');

$baseUrl = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/tiktok-immobilier';
define('VIDEO_URL_BASE', getenv('VIDEO_URL_BASE') ?: $baseUrl . '/uploads/videos/');
define('THUMB_URL_BASE', getenv('THUMB_URL_BASE') ?: $baseUrl . '/uploads/thumbnails/');
define('JWT_SECRET', getenv('JWT_SECRET') ?: 'immotok_secret_key_2026_change_me');

function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
            exit;
        }
    }
    return $pdo;
}

function json($data, $code = 200) {
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function error($msg, $code = 400) {
    json(['error' => $msg], $code);
}

function getBody() {
    return json_decode(file_get_contents('php://input'), true) ?: [];
}

function getAuthUser() {
    $headers = getallheaders();
    $token = null;
    if (isset($headers['Authorization'])) {
        $parts = explode(' ', $headers['Authorization']);
        if (count($parts) === 2) $token = $parts[1];
    }
    if (!$token) return null;

    $parts = explode('.', $token);
    if (count($parts) !== 3) return null;

    $payload = json_decode(base64_decode($parts[1]), true);
    if (!$payload || !isset($payload['user_id'])) return null;
    if (isset($payload['exp']) && $payload['exp'] < time()) return null;

    $db = getDB();
    $stmt = $db->prepare("SELECT id, username, email, full_name, avatar, bio, type, phone, website, verified, followers_count, following_count, properties_count, cover_color FROM users WHERE id = ?");
    $stmt->execute([$payload['user_id']]);
    $user = $stmt->fetch();
    if (!$user) return null;

    if ($user['avatar']) {
        $user['avatar_url'] = THUMB_URL_BASE . $user['avatar'];
    } else {
        $user['avatar_url'] = 'https://i.pravatar.cc/150?u=' . $user['id'];
    }

    return $user;
}

function requireAuth() {
    $user = getAuthUser();
    if (!$user) error('Non authentifié', 401);
    return $user;
}

function generateToken($userId) {
    $header = base64_encode(json_encode(['typ' => 'JWT', 'alg' => 'HS256']));
    $payload = base64_encode(json_encode([
        'user_id' => $userId,
        'iat' => time(),
        'exp' => time() + (86400 * 365 * 10) // 10 ans = session permanente
    ]));
    $signature = base64_encode(hash_hmac('sha256', "$header.$payload", JWT_SECRET, true));
    return "$header.$payload.$signature";
}

function validateToken($token) {
    $parts = explode('.', $token);
    if (count($parts) !== 3) return false;
    $signature = base64_encode(hash_hmac('sha256', "$parts[0].$parts[1]", JWT_SECRET, true));
    return hash_equals($signature, $parts[2]);
}
