<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Création de votre compte PropertyAI</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
            color: #334155;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05);
            border: 1px solid #e2e8f0;
        }
        .header {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            padding: 32px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }
        .content {
            padding: 40px 32px;
        }
        .credentials-container {
            background-color: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 24px;
            margin: 24px 0;
        }
        .credential-item {
            margin: 8px 0;
            font-size: 14px;
        }
        .credential-label {
            font-weight: 700;
            color: #475569;
        }
        .credential-value {
            font-family: 'Courier New', Courier, monospace;
            font-weight: 800;
            color: #6366f1; /* indigo-500 */
        }
        .btn-login {
            display: inline-block;
            background-color: #6366f1;
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 28px;
            font-weight: 700;
            font-size: 14px;
            border-radius: 10px;
            margin-top: 16px;
            box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.2);
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            background-color: #4f46e5;
            box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.3);
        }
        .footer {
            background-color: #f8fafc;
            padding: 24px 32px;
            text-align: center;
            font-size: 12px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
        }
        .footer p {
            margin: 4px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>PropertyAI</h1>
        </div>
        <div class="content">
            <p>Bonjour {{ $user->name }},</p>
            <p>Nous avons le plaisir de vous informer que votre compte d'administration a été créé avec succès par le Super Administrateur de la plateforme <strong>PropertyAI</strong>.</p>
            
            <p>Voici vos identifiants temporaires de connexion pour accéder à votre espace de travail :</p>
            
            <div class="credentials-container">
                <div class="credential-item">
                    <span class="credential-label">Adresse E-mail : </span>
                    <span class="credential-value">{{ $user->email }}</span>
                </div>
                <div class="credential-item">
                    <span class="credential-label">Mot de passe temporaire : </span>
                    <span class="credential-value">{{ $password }}</span>
                </div>
            </div>

            <p style="color: #ef4444; font-weight: 600; font-size: 13px;">⚠️ Sécurité : Nous vous conseillons vivement de modifier ce mot de passe dès votre première connexion.</p>
            
            <p>Pour finaliser la configuration de votre compte et choisir votre formule d'abonnement, veuillez vous connecter en cliquant sur le bouton ci-dessous :</p>

            <div style="text-align: center; margin-top: 24px;">
                <a href="{{ $loginUrl }}" class="btn-login">Me connecter maintenant</a>
            </div>
        </div>
        <div class="footer">
            <p>Cet email a été envoyé automatiquement par le portail de gestion PropertyAI.</p>
            <p>&copy; {{ date('Y') }} PropertyAI. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>
