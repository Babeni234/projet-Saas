<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Votre portefeuille électronique (Wallet) a été alimenté - {{ $companyName }}</title>
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
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
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
        .recharge-container {
            background-color: #f0fdf4;
            border: 1px dashed #bbf7d0;
            border-radius: 12px;
            padding: 24px;
            margin: 32px 0;
        }
        .recharge-item {
            margin: 12px 0;
            font-size: 16px;
        }
        .label {
            font-weight: bold;
            color: #374151;
        }
        .value {
            font-weight: 800;
            color: #065f46;
            font-size: 18px;
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
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
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
            <h1>{{ $companyName }}</h1>
        </div>
        <div class="content">
            <p>Bonjour {{ $locataire->nom }},</p>
            <p>Nous vous informons que votre portefeuille électronique (Wallet) a été alimenté avec succès par votre agence de gestion / propriétaire.</p>
            
            <div class="recharge-container">
                <div class="recharge-item">
                    <span class="label">Montant rechargé :</span>
                    <span class="value">{{ number_format($amount, 2, ',', ' ') }} €</span>
                </div>
                <div class="recharge-item">
                    <span class="label">Nouveau solde :</span>
                    <span class="value">{{ number_format($newBalance, 2, ',', ' ') }} €</span>
                </div>
                <div class="recharge-item">
                    <span class="label">Référence transaction :</span>
                    <span class="value" style="font-family: monospace; letter-spacing: 0.5px;">{{ $refTx }}</span>
                </div>
            </div>

            <p>Vous pouvez dès à présent utiliser ce solde pour régler vos loyers ou factures d'eau et d'électricité depuis votre espace locataire.</p>
            
            <div style="text-align: center;">
                <a href="{{ url('/login') }}" class="btn">Accéder à mon espace</a>
            </div>
        </div>
        <div class="footer">
            <p>Cet email a été envoyé automatiquement par le portail de gestion {{ $companyName }}.</p>
            <p>&copy; {{ date('Y') }} {{ $companyName }}. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>
