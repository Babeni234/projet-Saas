<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Notification de Paiement de Facture - {{ $companyName }}</title>
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
        .details-container {
            background-color: #ecfdf5;
            border: 1px dashed #a7f3d0;
            border-radius: 12px;
            padding: 24px;
            margin: 32px 0;
        }
        .detail-item {
            margin: 12px 0;
            font-size: 16px;
            border-bottom: 1px solid #d1faf0;
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
            color: #064e3b;
            float: right;
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
            <p>Bonjour,</p>
            <p>Nous vous notifions que le locataire <strong>{{ $locataire->nom }}</strong> occupant le logement <strong>{{ $logementRef }}</strong> a réglé sa facture depuis son portefeuille électronique (Wallet).</p>
            
            <div class="details-container">
                <div class="detail-item">
                    <span class="label">Montant payé :</span>
                    <span class="value">{{ number_format($invoice->total, 2, ',', ' ') }} €</span>
                </div>
                <div class="detail-item">
                    <span class="label">Numéro Facture :</span>
                    <span class="value">{{ $invoice->numero }}</span>
                </div>
                <div class="detail-item">
                    <span class="label">Type de Facture :</span>
                    <span class="value">{{ $invoice->typeFacture?->nom ?? 'Autre' }}</span>
                </div>
                <div class="detail-item">
                    <span class="label">Période correspondante :</span>
                    <span class="value">
                        @if($invoice->periode)
                            @php
                                $parts = explode('-', $invoice->periode);
                                if (count($parts) >= 2) {
                                    $monthsList = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
                                    echo $monthsList[intval($parts[1]) - 1] . ' ' . $parts[0];
                                } else {
                                    echo $invoice->periode;
                                }
                            @endphp
                        @else
                            N/A
                        @endif
                    </span>
                </div>
                <div class="detail-item">
                    <span class="label">Mode de règlement :</span>
                    <span class="value">Portefeuille électronique (Wallet)</span>
                </div>
                <div class="detail-item">
                    <span class="label">Date de paiement :</span>
                    <span class="value">{{ now()->format('d/m/Y H:i') }}</span>
                </div>
            </div>

            <p>Ce règlement a été automatiquement validé et enregistré dans la trésorerie.</p>
            
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
