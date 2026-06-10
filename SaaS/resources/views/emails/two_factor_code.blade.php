<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Votre code de sécurité</title>
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
        .code-container {
            background-color: #f1f5f9;
            border: 1px dashed #cbd5e1;
            border-radius: 12px;
            padding: 24px;
            text-align: center;
            margin: 32px 0;
        }
        .code {
            font-size: 36px;
            font-weight: 800;
            letter-spacing: 6px;
            color: #d97706; /* amber-600 */
            margin: 0;
            font-family: 'Courier New', Courier, monospace;
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
            <p>Bonjour,</p>
            <p>Une tentative de connexion à votre compte a été détectée. Pour valider votre authentification, veuillez utiliser le code de sécurité temporaire ci-dessous :</p>
            
            <div class="code-container">
                <div class="code">{{ $code }}</div>
            </div>

            <p style="color: #ef4444; font-weight: 600; font-size: 13px;">⚠️ Important : Ce code est strictement confidentiel. Il est valide pendant seulement 1 minute. Ne le partagez jamais avec qui que ce soit.</p>
            
            <p style="margin-top: 32px; font-size: 13px; color: #64748b;">Si vous n'êtes pas à l'origine de cette demande, nous vous recommandons de sécuriser immédiatement votre compte en changeant de mot de passe.</p>
        </div>
        <div class="footer">
            <p>Cet email a été envoyé automatiquement par le portail de gestion PropertyAI.</p>
            <p>&copy; {{ date('Y') }} PropertyAI. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>
