<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Notification de Paiement de Loyer - {{ $companyName }}</title>
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
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
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
            background-color: #f0f9ff;
            border: 1px dashed #bae6fd;
            border-radius: 12px;
            padding: 24px;
            margin: 32px 0;
        }
        .detail-item {
            margin: 12px 0;
            font-size: 16px;
            border-bottom: 1px solid #e0f2fe;
            padding-bottom: 8px;
        }
        .detail-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
        .label {
            font-weight: 600;
            color: #475569;
        }
        .value {
            font-weight: 800;
            color: #1e3a8a;
            float: right;
        }
        .months-list {
            margin: 8px 0 0 0;
            padding-left: 20px;
            color: #1e3a8a;
            font-weight: bold;
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
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
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
            <p>Bonjour,</p>
            <p>Nous vous notifions que le locataire <strong>{{ $locataire->nom }}</strong> occupant le logement <strong>{{ $logementRef }}</strong> a réglé son loyer depuis son portefeuille électronique (Wallet).</p>
            
            <div class="details-container">
                <div class="detail-item">
                    <span class="label">Montant total payé :</span>
                    <span class="value">{{ number_format($paiement->montant_total, 2, ',', ' ') }} €</span>
                </div>
                <div class="detail-item">
                    <span class="label">Mode de règlement :</span>
                    <span class="value">Portefeuille électronique (Wallet)</span>
                </div>
                <div class="detail-item">
                    <span class="label">Référence transaction :</span>
                    <span class="value" style="font-family: monospace;">{{ $paiement->reference_tx }}</span>
                </div>
                <div class="detail-item">
                    <span class="label">Date de paiement :</span>
                    <span class="value">{{ $paiement->date_reglement ? $paiement->date_reglement->format('d/m/Y H:i') : now()->format('d/m/Y H:i') }}</span>
                </div>
                <div class="detail-item" style="border-bottom: none; padding-bottom: 0;">
                    <span class="label">Mois réglés :</span>
                    <ul class="months-list">
                        @foreach($months as $m)
                            <li>{{ $m['key'] }} (Loyer: {{ number_format((float)$m['amount'], 2, ',', ' ') }} € {{ ((float)$m['penaltyAmount'] > 0) ? '+ Pénalité: ' . number_format((float)$m['penaltyAmount'], 2, ',', ' ') . ' €' : '' }})</li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <p>Ce règlement a été automatiquement enregistré dans la trésorerie et le statut des mois correspondants a été mis à jour.</p>
            
            <div style="text-align: center;">
                <a href="{{ url('/login') }}" class="btn">Accéder à mon espace de gestion</a>
            </div>
        </div>
        <div class="footer">
            <p>Cet email a été envoyé automatiquement par le portail de gestion {{ $companyName }}.</p>
            <p>&copy; {{ date('Y') }} {{ $companyName }}. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>
