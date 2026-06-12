<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Votre compte Locataire - {{ $agencyName ?? 'PropertyAI' }}</title>
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
            border: 1px dashed #cbd5e1;
            border-radius: 12px;
            padding: 24px;
            margin: 32px 0;
        }
        .credential-item {
            margin: 12px 0;
            font-size: 16px;
        }
        .label {
            font-weight: bold;
            color: #475569;
        }
        .value {
            font-family: 'Courier New', Courier, monospace;
            font-weight: 800;
            color: #1e293b;
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
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #d97706 0%, #ea580c 100%);
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: bold;
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $agencyName ?? 'PropertyAI' }}</h1>
        </div>
        <div class="content">
            <p>Bonjour {{ $user->name }},</p>
            <p>Votre compte locataire a été créé avec succès par votre agence de gestion.</p>

            <div style="background-color: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 12px; padding: 20px; margin: 24px 0; text-align: left;">
                <h3 style="margin-top: 0; color: #1e293b; font-size: 15px; border-bottom: 1px solid #e2e8f0; padding-bottom: 8px; font-weight: 700;">Informations de gestion</h3>
                <p style="margin: 8px 0; font-size: 14px;"><span style="color: #475569; font-weight: 600;">Compagnie :</span> <strong style="color: #0f172a;">{{ $companyName ?? 'N/A' }}</strong></p>
                @if(!empty($agencyName))
                    <p style="margin: 8px 0; font-size: 14px;"><span style="color: #475569; font-weight: 600;">Agence gérante :</span> <strong style="color: #0f172a;">{{ $agencyName }}</strong></p>
                    <p style="margin: 8px 0; font-size: 14px;"><span style="color: #475569; font-weight: 600;">Ville de l'agence :</span> <strong style="color: #0f172a;">{{ $agencyCity ?? 'N/A' }}</strong></p>
                @endif
            </div>

            <p>Vous pourrez vous connecter à votre espace personnel pour suivre vos affectations, vos documents de bail et vos factures quand votre contrat sera activé. 
            A cet effet, vous recevrez un mail de votre agence de gestion.</p>

            <p>Voici vos identifiants de connexion temporaires :</p>
            
            <div class="credentials-container">
                <div class="credential-item">
                    <span class="label">Adresse Email :</span>
                    <span class="value">{{ $user->email }}</span>
                </div>
                <div class="credential-item">
                    <span class="label">Mot de passe :</span>
                    <span class="value">{{ $password }}</span>
                </div>
            </div>

            <p style="color: #64748b; font-size: 13px;">⚠️ Pour des raisons de sécurité, nous vous conseillons vivement de modifier ce mot de passe dès votre première connexion.</p>
            
            <div style="text-align: center;">
                <a href="{{ url('/login') }}" class="btn">Me connecter</a>
            </div>
        </div>
        <div class="footer">
            <p>Cet email a été envoyé automatiquement par le portail de gestion PropertyAI.</p>
            <p>&copy; {{ date('Y') }} PropertyAI. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>
