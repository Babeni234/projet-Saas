<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nouveau Wallet créé par le locataire - {{ $companyName }}</title>
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
        .details-container {
            background-color: #f1f5f9;
            border: 1px dashed #cbd5e1;
            border-radius: 12px;
            padding: 24px;
            margin: 32px 0;
        }
        .detail-item {
            margin: 12px 0;
            font-size: 16px;
        }
        .label {
            font-weight: bold;
            color: #475569;
        }
        .value {
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Notification de création de Wallet</h1>
        </div>
        <div class="content">
            <p>Bonjour,</p>
            <p>Le locataire <strong>{{ $locataire->nom }}</strong> a créé son portefeuille électronique (Wallet) avec succès depuis son espace personnel.</p>
            <p>Ce portefeuille est désormais actif pour la gestion des paiements locatifs.</p>

            <div class="details-container">
                <div class="detail-item">
                    <span class="label">Locataire :</span>
                    <span class="value">{{ $locataire->nom }} (ID: {{ $locataire->id }})</span>
                </div>
                <div class="detail-item">
                    <span class="label">Email :</span>
                    <span class="value">{{ $locataire->user->email }}</span>
                </div>
                <div class="detail-item">
                    <span class="label">Agence :</span>
                    <span class="value">{{ $agencyName }}</span>
                </div>
                <div class="detail-item">
                    <span class="label">Compagnie :</span>
                    <span class="value">{{ $companyName }}</span>
                </div>
                <div class="detail-item">
                    <span class="label">Statut Wallet :</span>
                    <span class="value" style="color: #059669;">Activé</span>
                </div>
            </div>
        </div>
        <div class="footer">
            <p>Cet email a été envoyé automatiquement par le portail de gestion PropertyAI.</p>
            <p>&copy; {{ date('Y') }} PropertyAI. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>
