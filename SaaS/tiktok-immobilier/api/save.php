<?php
require_once __DIR__ . '/config.php';
$user = requireAuth();
$db = getDB();

$propertyId = $_GET['id'] ?? null;
if (!$propertyId) error('ID du bien requis');

$stmt = $db->prepare("SELECT id FROM saves WHERE user_id = ? AND property_id = ?");
$stmt->execute([$user['id'], $propertyId]);

if ($stmt->fetch()) {
    $db->prepare("DELETE FROM saves WHERE user_id = ? AND property_id = ?")->execute([$user['id'], $propertyId]);
    $db->prepare("UPDATE properties SET saves_count = GREATEST(saves_count - 1, 0) WHERE id = ?")->execute([$propertyId]);
    json(['saved' => false, 'message' => 'Retiré des favoris']);
} else {
    $db->prepare("INSERT INTO saves (user_id, property_id) VALUES (?, ?)")->execute([$user['id'], $propertyId]);
    $db->prepare("UPDATE properties SET saves_count = saves_count + 1 WHERE id = ?")->execute([$propertyId]);
    json(['saved' => true, 'message' => 'Bien sauvegardé']);
}
