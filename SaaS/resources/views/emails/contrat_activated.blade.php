<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Activation de votre contrat de bail {{ $agencyName ?? 'PropertyAI' }}</title>
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
            background: linear-gradient(135deg, #059669 0%, #0d9488 100%);
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
        .info-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 24px;
            margin: 24px 0;
        }
        .info-title {
            margin-top: 0;
            color: #0f172a;
            font-size: 16px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 8px;
            font-weight: 700;
        }
        .info-item {
            margin: 12px 0;
            font-size: 14px;
            display: flex;
            justify-content: space-between;
        }
        .label {
            font-weight: 600;
            color: #475569;
        }
        .value {
            font-weight: 700;
            color: #0f172a;
            text-align: right;
        }
        .highlight-value {
            color: #059669;
            font-weight: 800;
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
            background: linear-gradient(135deg, #059669 0%, #0d9488 100%);
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 28px;
            border-radius: 8px;
            font-weight: bold;
            margin-top: 20px;
            text-align: center;
            box-shadow: 0 4px 6px -1px rgba(5, 150, 105, 0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $agencyName ?? 'PropertyAI' }}</h1>
        </div>
        <div class="content">
            <p>Bonjour {{ $contrat->locataire?->user?->name }},</p>
            <p>Nous avons le plaisir de vous informer que votre contrat de bail a été <strong>activé</strong> avec succès.</p>
            
            <p>Vous pouvez dès à présent vous connecter à votre portail locataire pour suivre vos baux, vos factures et vos paiements.</p>

            <div class="info-card">
                <h3 class="info-title">Récapitulatif de votre bail</h3>
                
                <div class="info-item">
                    <span class="label">Bailleur / Compagnie :</span>
                    <span class="value">{{ $companyName }}</span>
                </div>
                
                @if($agencyName && $agencyName !== 'Siège')
                <div class="info-item">
                    <span class="label">Agence gérante :</span>
                    <span class="value">{{ $agencyName }}</span>
                </div>
                <div class="info-item">
                    <span class="label">Ville de l'agence :</span>
                    <span class="value">{{ $agencyCity }}</span>
                </div>
                @endif

                <div class="info-item">
                    <span class="label">Logement / Référence :</span>
                    <span class="value">{{ $logementRef }} ({{ $contrat->logement?->batiment?->nom ?? 'N/A' }})</span>
                </div>
                
                <div class="info-item">
                    <span class="label">Ville du logement :</span>
                    <span class="value">{{ $contrat->logement?->batiment?->ville ?? 'N/A' }}</span>
                </div>

                <div class="info-item">
                    <span class="label">Durée du bail :</span>
                    <span class="value">{{ $duree }}</span>
                </div>

                <div class="info-item">
                    <span class="label">Loyer mensuel :</span>
                    <span class="value highlight-value">{{ number_format($loyer, 2, ',', ' ') }} €</span>
                </div>

                <div class="info-item">
                    <span class="label">Dépôt de garantie (Caution) :</span>
                    <span class="value">{{ number_format($caution, 2, ',', ' ') }} €</span>
                </div>
            </div>

            <p style="color: #64748b; font-size: 13px;">⚠️ Si vous n'avez pas encore configuré vos identifiants, veuillez utiliser le lien d'activation reçu précédemment ou contacter votre gestionnaire.</p>
            
            <div style="text-align: center; margin-top: 32px;">
                <a href="{{ url('/login') }}" class="btn">Accéder à mon Portail Locataire</a>
            </div>
        </div>
        <div class="footer">
            <p>Cet email a été envoyé automatiquement par le portail de gestion PropertyAI.</p>
            <p>&copy; {{ date('Y') }} PropertyAI. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>
