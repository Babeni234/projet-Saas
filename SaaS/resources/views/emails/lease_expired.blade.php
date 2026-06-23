<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Expiration de votre contrat de bail</title>
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
            background: linear-gradient(135deg, #475569 0%, #334155 100%);
            padding: 32px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }
        .content {
            padding: 40px 32px;
            line-height: 1.6;
        }
        .info-card {
            background-color: #f1f5f9;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            padding: 24px;
            margin: 24px 0;
        }
        .info-title {
            margin-top: 0;
            color: #334155;
            font-size: 16px;
            border-bottom: 1px solid #cbd5e1;
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
            background: linear-gradient(135deg, #475569 0%, #334155 100%);
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 28px;
            border-radius: 8px;
            font-weight: bold;
            margin-top: 20px;
            text-align: center;
            box-shadow: 0 4px 6px -1px rgba(71, 85, 105, 0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Expiration de votre Contrat de Bail</h1>
        </div>
        <div class="content">
            <p>Bonjour <strong>{{ $locataireName }}</strong>,</p>
            <p>Nous vous informons que votre contrat de bail est arrivé à son <strong>échéance contractuelle</strong> en date du {{ \Carbon\Carbon::parse($dateFin)->format('d/m/Y') }}.</p>
            
            <p>Conformément aux termes du contrat, votre bail a expiré et votre logement a été marqué comme vacant dans notre système.</p>

            <div class="info-card">
                <h3 class="info-title">Récapitulatif du contrat expiré</h3>
                
                <div class="info-item">
                    <span class="label">Bailleur / Compagnie :</span>
                    <span class="value">{{ $companyName }}</span>
                </div>
                
                <div class="info-item">
                    <span class="label">Agence gérante :</span>
                    <span class="value">{{ $agencyName }}</span>
                </div>

                <div class="info-item">
                    <span class="label">Logement loué :</span>
                    <span class="value">{{ $logementRef }}</span>
                </div>

                <div class="info-item">
                    <span class="label">Date de fin contractuelle :</span>
                    <span class="value">{{ \Carbon\Carbon::parse($dateFin)->format('d/m/Y') }}</span>
                </div>
            </div>

            <p>Veuillez contacter rapidement votre gestionnaire au sein de l'agence <strong>{{ $agencyName }}</strong> afin de finaliser les formalités de sortie, d'organiser l'état des lieux contradictoire et de restituer les clés.</p>
            
            <div style="text-align: center; margin-top: 32px;">
                <a href="{{ url('/login') }}" class="btn">Accéder à mon Espace</a>
            </div>
        </div>
        <div class="footer">
            <p>Cet email a été envoyé automatiquement par le portail de gestion PropertyAI.</p>
            <p>&copy; {{ date('Y') }} {{ $companyName }}. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>
