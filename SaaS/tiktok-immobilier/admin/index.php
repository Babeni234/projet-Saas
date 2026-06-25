<?php
session_start();
require_once __DIR__ . '/../api/config.php';

// Check if logged in via API
$user = getAuthUser();
$isAdmin = $user && $user['type'] === 'admin';

if (!$isAdmin && !isset($_SESSION['admin_id'])) {
    // Try simple session auth
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ? AND type = 'admin'");
        $stmt->execute([$_POST['email']]);
        $admin = $stmt->fetch();
        if ($admin && password_verify($_POST['password'], $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['full_name'];
            header('Location: index.php');
            exit;
        }
        $error = 'Identifiants incorrects';
    }
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>ImmoTok Admin</title>
        <style>
            * { margin:0; padding:0; box-sizing:border-box; }
            body { font-family:system-ui,sans-serif; background:#07080d; color:#eef0ff; display:flex; align-items:center; justify-content:center; min-height:100vh; }
            .login-box { background:#0e1019; border:1px solid rgba(255,255,255,.07); border-radius:16px; padding:32px; width:360px; }
            h1 { text-align:center; margin-bottom:24px; font-size:24px; }
            h1 span { color:#ff2d55; }
            .fg { margin-bottom:16px; }
            label { display:block; font-size:12px; color:#8890b5; margin-bottom:4px; }
            input { width:100%; padding:10px 14px; background:#151824; border:1px solid rgba(255,255,255,.07); border-radius:8px; color:#fff; font-size:14px; }
            button { width:100%; padding:12px; background:#ff2d55; color:#fff; border:none; border-radius:8px; font-size:14px; font-weight:600; cursor:pointer; }
            .error { color:#ff2d55; font-size:13px; margin-bottom:12px; text-align:center; }
        </style>
    </head>
    <body>
        <div class="login-box">
            <h1>Immo<span>Tok</span> Admin</h1>
            <?php if (isset($error)) echo '<div class="error">'.$error.'</div>'; ?>
            <form method="post">
                <div class="fg"><label>Email</label><input type="email" name="email" required></div>
                <div class="fg"><label>Mot de passe</label><input type="password" name="password" required></div>
                <button type="submit" name="login">Se connecter</button>
            </form>
        </div>
    </body>
    </html>
    <?php
    exit;
}

$db = getDB();

// Handle actions
if (isset($_GET['action'])) {
    if ($_GET['action'] === 'delete_prop' && isset($_GET['id'])) {
        $stmt = $db->prepare("SELECT video_url FROM properties WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        $prop = $stmt->fetch();
        if ($prop && $prop['video_url']) {
            $vpath = UPLOAD_DIR . 'videos/' . $prop['video_url'];
            if (file_exists($vpath)) @unlink($vpath);
        }
        $db->prepare("DELETE FROM properties WHERE id = ?")->execute([$_GET['id']]);
    }
    if ($_GET['action'] === 'delete_user' && isset($_GET['id'])) {
        $db->prepare("DELETE FROM users WHERE id = ? AND type != 'admin'")->execute([$_GET['id']]);
    }
    if ($_GET['action'] === 'toggle_verify' && isset($_GET['id'])) {
        $stmt = $db->prepare("SELECT verified FROM users WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        $u = $stmt->fetch();
        if ($u) {
            $db->prepare("UPDATE users SET verified = ? WHERE id = ?")->execute([$u['verified'] ? 0 : 1, $_GET['id']]);
        }
    }
    header('Location: index.php');
    exit;
}

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

$stats = [
    'users' => $db->query("SELECT COUNT(*) FROM users")->fetchColumn(),
    'properties' => $db->query("SELECT COUNT(*) FROM properties")->fetchColumn(),
    'active_props' => $db->query("SELECT COUNT(*) FROM properties WHERE status = 'active'")->fetchColumn(),
    'views' => $db->query("SELECT SUM(views) FROM properties")->fetchColumn(),
];

$recentProps = $db->query("SELECT p.*, u.full_name as user_name FROM properties p JOIN users u ON p.user_id = u.id ORDER BY p.created_at DESC LIMIT 10")->fetchAll();
$recentUsers = $db->query("SELECT * FROM users ORDER BY created_at DESC LIMIT 10")->fetchAll();
$allUsers = $db->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll();
$allProps = $db->query("SELECT p.*, u.full_name as user_name FROM properties p JOIN users u ON p.user_id = u.id ORDER BY p.created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>ImmoTok Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:system-ui,sans-serif; background:#07080d; color:#eef0ff; }
        .sidebar { position:fixed; left:0; top:0; bottom:0; width:220px; background:#0e1019; border-right:1px solid rgba(255,255,255,.07); padding:20px 0; }
        .sidebar h2 { padding:0 20px 20px; font-size:18px; border-bottom:1px solid rgba(255,255,255,.07); margin-bottom:12px; }
        .sidebar h2 span { color:#ff2d55; }
        .sidebar a { display:flex; align-items:center; gap:10px; padding:10px 20px; color:#8890b5; text-decoration:none; font-size:13px; transition:all .15s; }
        .sidebar a:hover, .sidebar a.active { background:rgba(255,255,255,.05); color:#fff; }
        .main { margin-left:220px; padding:24px; }
        .header { display:flex; justify-content:space-between; align-items:center; margin-bottom:24px; }
        .header h1 { font-size:20px; }
        .header .user { font-size:13px; color:#8890b5; display:flex; align-items:center; gap:10px; }
        .stats { display:grid; grid-template-columns:repeat(4,1fr); gap:14px; margin-bottom:24px; }
        .stat { background:#0e1019; border:1px solid rgba(255,255,255,.07); border-radius:12px; padding:16px; }
        .stat-num { font-size:28px; font-weight:700; color:#fff; }
        .stat-lbl { font-size:12px; color:#8890b5; margin-top:4px; }
        .card { background:#0e1019; border:1px solid rgba(255,255,255,.07); border-radius:12px; padding:16px; margin-bottom:20px; }
        .card h3 { font-size:14px; margin-bottom:12px; color:#8890b5; text-transform:uppercase; letter-spacing:.5px; }
        table { width:100%; border-collapse:collapse; font-size:13px; }
        th { text-align:left; padding:8px 12px; color:#8890b5; font-weight:500; border-bottom:1px solid rgba(255,255,255,.07); }
        td { padding:8px 12px; border-bottom:1px solid rgba(255,255,255,.04); }
        .badge { display:inline-block; padding:2px 8px; border-radius:10px; font-size:11px; font-weight:600; }
        .badge.active { background:rgba(42,201,122,.15); color:#2ac97a; }
        .badge.inactive { background:rgba(255,45,85,.15); color:#ff2d55; }
        .badge.admin { background:rgba(66,133,244,.15); color:#4285f4; }
        .badge.enterprise { background:rgba(168,85,247,.15); color:#a855f7; }
        .badge.particulier { background:rgba(255,201,60,.15); color:#ffc93c; }
        .btn-sm { padding:4px 10px; border-radius:6px; border:none; font-size:11px; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:4px; }
        .btn-danger { background:rgba(255,45,85,.15); color:#ff2d55; }
        .btn-success { background:rgba(42,201,122,.15); color:#2ac97a; }
        .btn-primary { background:rgba(66,133,244,.15); color:#4285f4; }
        .actions { display:flex; gap:4px; }
        .mt-4 { margin-top:16px; }
        @media(max-width:768px){ .sidebar { width:100%; position:relative; height:auto; } .sidebar a { display:inline-flex; padding:8px 12px; } .main { margin-left:0; } .stats { grid-template-columns:repeat(2,1fr); } }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Immo<span>Tok</span></h2>
        <a href="?page=dashboard" class="<?= $page === 'dashboard' ? 'active' : '' ?>"><i class="fas fa-chart-simple"></i> Tableau de bord</a>
        <a href="?page=properties" class="<?= $page === 'properties' ? 'active' : '' ?>"><i class="fas fa-home"></i> Biens</a>
        <a href="?page=users" class="<?= $page === 'users' ? 'active' : '' ?>"><i class="fas fa-users"></i> Utilisateurs</a>
        <a href="?page=upload" class="<?= $page === 'upload' ? 'active' : '' ?>"><i class="fas fa-upload"></i> Publier un bien</a>
        <a href="?logout=1" style="margin-top:auto;border-top:1px solid rgba(255,255,255,.07);padding-top:16px"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
    </div>

    <div class="main">
        <div class="header">
            <h1><?= ucfirst($page) ?></h1>
            <div class="user"><i class="fas fa-user-circle"></i> <?= htmlspecialchars($_SESSION['admin_name'] ?? $user['full_name']) ?></div>
        </div>

        <?php if ($page === 'dashboard'): ?>
        <div class="stats">
            <div class="stat"><div class="stat-num"><?= $stats['users'] ?></div><div class="stat-lbl">Utilisateurs</div></div>
            <div class="stat"><div class="stat-num"><?= $stats['properties'] ?></div><div class="stat-lbl">Biens</div></div>
            <div class="stat"><div class="stat-num"><?= $stats['active_props'] ?></div><div class="stat-lbl">Actifs</div></div>
            <div class="stat"><div class="stat-num"><?= number_format($stats['views']) ?></div><div class="stat-lbl">Vues totales</div></div>
        </div>

        <div class="card"><h3>Derniers biens</h3>
            <table><tr><th>Titre</th><th>Propriétaire</th><th>Prix</th><th>Vues</th><th>Date</th></tr>
            <?php foreach ($recentProps as $p): ?>
            <tr><td><?= htmlspecialchars($p['title']) ?></td><td><?= htmlspecialchars($p['user_name']) ?></td><td><?= number_format($p['price']) ?> FCFA</td><td><?= $p['views'] ?></td><td><?= substr($p['created_at'],0,10) ?></td></tr>
            <?php endforeach; ?>
            </table></div>

        <div class="card"><h3>Derniers utilisateurs</h3>
            <table><tr><th>Nom</th><th>Email</th><th>Type</th><th>Date</th></tr>
            <?php foreach ($recentUsers as $u): ?>
            <tr><td><?= htmlspecialchars($u['full_name']) ?></td><td><?= htmlspecialchars($u['email']) ?></td><td><span class="badge <?= $u['type'] ?>"><?= $u['type'] ?></span></td><td><?= substr($u['created_at'],0,10) ?></td></tr>
            <?php endforeach; ?>
            </table></div>

        <?php elseif ($page === 'properties'): ?>
        <div class="card"><h3>Tous les biens</h3>
            <table><tr><th>ID</th><th>Titre</th><th>Propriétaire</th><th>Prix</th><th>Type</th><th>Statut</th><th>Vues</th><th>Actions</th></tr>
            <?php foreach ($allProps as $p): ?>
            <tr>
                <td>#<?= $p['id'] ?></td>
                <td><?= htmlspecialchars($p['title']) ?></td>
                <td><?= htmlspecialchars($p['user_name']) ?></td>
                <td><?= number_format($p['price']) ?></td>
                <td><?= $p['type'] ?></td>
                <td><span class="badge <?= $p['status'] ?>"><?= $p['status'] ?></span></td>
                <td><?= $p['views'] ?></td>
                <td class="actions">
                    <a href="?action=delete_prop&id=<?= $p['id'] ?>" class="btn-sm btn-danger" onclick="return confirm('Supprimer ?')"><i class="fas fa-trash"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
            </table></div>

        <?php elseif ($page === 'users'): ?>
        <div class="card"><h3>Tous les utilisateurs</h3>
            <table><tr><th>ID</th><th>Nom</th><th>Email</th><th>Type</th><th>Vérifié</th><th>Abonnés</th><th>Biens</th><th>Actions</th></tr>
            <?php foreach ($allUsers as $u): ?>
            <tr>
                <td>#<?= $u['id'] ?></td>
                <td><?= htmlspecialchars($u['full_name']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td><span class="badge <?= $u['type'] ?>"><?= $u['type'] ?></span></td>
                <td><?= $u['verified'] ? '✅' : '❌' ?></td>
                <td><?= $u['followers_count'] ?></td>
                <td><?= $u['properties_count'] ?></td>
                <td class="actions">
                    <a href="?action=toggle_verify&id=<?= $u['id'] ?>" class="btn-sm btn-primary"><i class="fas fa-check"></i></a>
                    <?php if ($u['type'] !== 'admin'): ?>
                    <a href="?action=delete_user&id=<?= $u['id'] ?>" class="btn-sm btn-danger" onclick="return confirm('Supprimer ?')"><i class="fas fa-trash"></i></a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            </table></div>

        <?php elseif ($page === 'upload'): ?>
        <div class="card"><h3>Publier un bien immobilier</h3>
            <form method="post" enctype="multipart/form-data" action="../api/property.php" style="display:grid;grid-template-columns:1fr 1fr;gap:12px;max-width:700px">
                <div style="grid-column:1/-1"><label style="display:block;font-size:12px;color:#8890b5;margin-bottom:4px">Titre *</label><input type="text" name="title" required style="width:100%;padding:10px;background:#151824;border:1px solid rgba(255,255,255,.07);border-radius:8px;color:#fff"></div>
                <div style="grid-column:1/-1"><label style="display:block;font-size:12px;color:#8890b5;margin-bottom:4px">Description</label><textarea name="description" rows="3" style="width:100%;padding:10px;background:#151824;border:1px solid rgba(255,255,255,.07);border-radius:8px;color:#fff"></textarea></div>
                <div><label style="display:block;font-size:12px;color:#8890b5;margin-bottom:4px">Prix (FCFA)</label><input type="number" name="price" style="width:100%;padding:10px;background:#151824;border:1px solid rgba(255,255,255,.07);border-radius:8px;color:#fff"></div>
                <div><label style="display:block;font-size:12px;color:#8890b5;margin-bottom:4px">Type</label><select name="type" style="width:100%;padding:10px;background:#151824;border:1px solid rgba(255,255,255,.07);border-radius:8px;color:#fff"><option>villa</option><option>appartement</option><option>studio</option><option>bureau</option><option>commerce</option><option>terrain</option></select></div>
                <div><label style="display:block;font-size:12px;color:#8890b5;margin-bottom:4px">Transaction</label><select name="transaction" style="width:100%;padding:10px;background:#151824;border:1px solid rgba(255,255,255,.07);border-radius:8px;color:#fff"><option>vente</option><option>location</option></select></div>
                <div><label style="display:block;font-size:12px;color:#8890b5;margin-bottom:4px">Surface (m²)</label><input type="number" name="surface" style="width:100%;padding:10px;background:#151824;border:1px solid rgba(255,255,255,.07);border-radius:8px;color:#fff"></div>
                <div><label style="display:block;font-size:12px;color:#8890b5;margin-bottom:4px">Pièces</label><input type="number" name="rooms" style="width:100%;padding:10px;background:#151824;border:1px solid rgba(255,255,255,.07);border-radius:8px;color:#fff"></div>
                <div><label style="display:block;font-size:12px;color:#8890b5;margin-bottom:4px">Salles de bain</label><input type="number" name="bathrooms" style="width:100%;padding:10px;background:#151824;border:1px solid rgba(255,255,255,.07);border-radius:8px;color:#fff"></div>
                <div><label style="display:block;font-size:12px;color:#8890b5;margin-bottom:4px">Ville</label><input type="text" name="city" placeholder="Ex: Cocody" style="width:100%;padding:10px;background:#151824;border:1px solid rgba(255,255,255,.07);border-radius:8px;color:#fff"></div>
                <div><label style="display:block;font-size:12px;color:#8890b5;margin-bottom:4px">Quartier</label><input type="text" name="neighborhood" style="width:100%;padding:10px;background:#151824;border:1px solid rgba(255,255,255,.07);border-radius:8px;color:#fff"></div>
                <div style="grid-column:1/-1"><label style="display:block;font-size:12px;color:#8890b5;margin-bottom:4px">Caractéristiques (séparées par des virgules)</label><input type="text" name="features" placeholder="Piscine, Jardin, Garage" style="width:100%;padding:10px;background:#151824;border:1px solid rgba(255,255,255,.07);border-radius:8px;color:#fff"></div>
                <div style="grid-column:1/-1"><label style="display:block;font-size:12px;color:#8890b5;margin-bottom:4px">Tags (séparés par des virgules)</label><input type="text" name="tags" placeholder="#villa, #luxe, #cocody" style="width:100%;padding:10px;background:#151824;border:1px solid rgba(255,255,255,.07);border-radius:8px;color:#fff"></div>
                <div style="grid-column:1/-1"><label style="display:block;font-size:12px;color:#8890b5;margin-bottom:4px">Vidéo *</label><input type="file" name="video" accept="video/mp4,video/webm" required style="width:100%;padding:10px;background:#151824;border:1px solid rgba(255,255,255,.07);border-radius:8px;color:#fff"></div>
                <div style="grid-column:1/-1"><label style="display:block;font-size:12px;color:#8890b5;margin-bottom:4px">Image de couverture</label><input type="file" name="image" accept="image/*" style="width:100%;padding:10px;background:#151824;border:1px solid rgba(255,255,255,.07);border-radius:8px;color:#fff"></div>
                <div style="grid-column:1/-1"><button type="submit" style="padding:12px 24px;background:#ff2d55;color:#fff;border:none;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer">Publier le bien</button></div>
            </form>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
