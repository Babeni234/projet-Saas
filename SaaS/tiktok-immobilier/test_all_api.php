<?php
// API test script
$base = 'http://localhost/tiktok-immobilier/api';

function test($label, $method, $url, $data = null, $token = null) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    $headers = ['Content-Type: application/json'];
    if ($token) $headers[] = "Authorization: Bearer $token";
    if ($data) curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $res = curl_exec($ch);
    $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $json = json_decode($res, true);
    echo sprintf("%-40s %s\n", $label, $http == 200 || $http == 201 ? '✅' : "❌ ($http)");
    return $json;
}

echo "=== AUTH ===\n";
$login = test("Login admin", "POST", "$base/auth.php?action=login", ['email' => 'admin@immotok.ci', 'password' => 'password']);
$token = $login['token'] ?? null;

$login2 = test("Login prestige_immo", "POST", "$base/auth.php?action=login", ['email' => 'contact@prestige-immo.ci', 'password' => 'password']);
$token2 = $login2['token'] ?? null;

test("Login wrong password", "POST", "$base/auth.php?action=login", ['email' => 'admin@immotok.ci', 'password' => 'wrong']);

echo "\n=== ME ===\n";
test("Me (admin)", "GET", "$base/auth.php?action=me", null, $token);

echo "\n=== FEED ===\n";
$feed = test("Feed foryou", "GET", "$base/feed.php?feed=foryou", null, $token);
echo "  Properties: " . ($feed['total'] ?? 0) . "\n";
$feedSubs = test("Feed subscriptions", "GET", "$base/feed.php?feed=subs", null, $token);
echo "  Subs count: " . ($feedSubs['total'] ?? 0) . "\n";
$feedExplore = test("Feed explore", "GET", "$base/feed.php?feed=explore", null, $token);
echo "  Explore count: " . ($feedExplore['total'] ?? 0) . "\n";

echo "\n=== PROPERTY ===\n";
$prop = test("Get property 1", "GET", "$base/property.php?id=1", null, $token);
echo "  Title: " . ($prop['title'] ?? 'N/A') . "\n";

echo "\n=== LIKES ===\n";
test("Like property 1", "POST", "$base/like.php?id=1", null, $token);
test("Like property 2", "POST", "$base/like.php?id=2", null, $token);
test("Like property 3", "POST", "$base/like.php?id=3", null, $token);

echo "\n=== COMMENTS ===\n";
test("Comment on property 1", "POST", "$base/comment.php?id=1", ['text' => 'Très belle villa !'], $token);
test("Comment on property 1", "POST", "$base/comment.php?id=1", ['text' => 'Prix négociable ?'], $token2);
$comments = test("Get comments 1", "GET", "$base/comment.php?id=1", null, $token);
echo "  Comments count: " . count($comments['comments'] ?? []) . "\n";

echo "\n=== FOLLOW ===\n";
test("Follow user 2", "POST", "$base/follow.php?id=2", null, $token);
test("Follow user 3", "POST", "$base/follow.php?id=3", null, $token);

echo "\n=== SAVE ===\n";
test("Save property 1", "POST", "$base/save.php?id=1", null, $token);
test("Save property 3", "POST", "$base/save.php?id=3", null, $token);

echo "\n=== SEARCH ===\n";
$search = test("Search 'villa'", "GET", "$base/search.php?q=villa", null, $token);
echo "  Results: " . count($search['properties'] ?? []) . "\n";
$search2 = test("Search 'location'", "GET", "$base/search.php?q=location&type=location", null, $token);
echo "  Location results: " . count($search2['properties'] ?? []) . "\n";

echo "\n=== PROFILE ===\n";
test("Get profile 2", "GET", "$base/profile.php?id=2", null, $token);

echo "\n=== NOTIFICATIONS ===\n";
$notifs = test("Get notifications", "GET", "$base/notifications.php", null, $token);
echo "  Unread: " . ($notifs['unread_count'] ?? 0) . "\n";

echo "\n=== FEED VERIFICATION ===\n";
$feed2 = test("Feed foryou (final)", "GET", "$base/feed.php?feed=foryou", null, $token);
echo "  Total properties: " . ($feed2['total'] ?? 0) . "\n";
if (!empty($feed2['properties'])) {
    foreach ($feed2['properties'] as $p) {
        echo "  #{$p['id']} {$p['title']} - {$p['price_label']} - Likes: {$p['likes_count']} - Comments: {$p['comments_count']}\n";
    }
}

echo "\n✅ TESTS TERMINÉS\n";
