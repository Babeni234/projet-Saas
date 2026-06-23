<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Renouvellement de votre contrat de bail</title>
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
            background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%);
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
            background-color: #f0fdfa;
            border: 1px solid #99f6e4;
            border-radius: 12px;
            padding: 24px;
            margin: 24px 0;
        }
        .info-title {
            margin-top: 0;
            color: #0f766e;
            font-size: 16px;
            border-bottom: 1px solid #99f6e4;
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
            background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%);
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 28px;
            border-radius: 8px;
            font-weight: bold;
            margin-top: 20px;
            text-align: center;
            box-shadow: 0 4px 6px -1px rgba(13, 148, 136, 0.2);
        }
        .warning-text {
            color: #b45309;
            font-weight: bold;
            background-color: #fef3c7;
            padding: 12px;
            border-radius: 8px;
            font-size: 13px;
            margin-top: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            @if($step == 1)
                <h1>Votre contrat de bail arrive à échéance</h1>
            @elseif($step == 2)
                <h1>Rappel : Renouvellement de votre bail</h1>
            @elseif($step == 3)
                <h1>Deuxième Rappel : Renouvellement de votre bail</h1>
            @else
                <h1>Dernier Rappel : Action requise pour votre bail</h1>
            @endif
        </div>
        <div class="content">
            <p>Bonjour <strong>{{ $locataireName }}</strong>,</p>

            @if($step == 1)
                <p>Nous vous informons que votre contrat de bail pour le logement <strong>{{ $logementRef }}</strong> arrive à échéance dans environ <strong>deux mois</strong> (le {{ \Carbon\Carbon::parse($dateFin)->format('d/m/Y') }}).</p>
                <p>Dans le cas où vous souhaiteriez renouveler votre contrat, vous devez dès à présent vous préparer à soumettre une demande officielle de renouvellement via votre portail locataire.</p>
            @elseif($step == 2)
                <p>Ceci est un rappel concernant l'échéance de votre contrat de bail pour le logement <strong>{{ $logementRef }}</strong>, prévue le {{ \Carbon\Carbon::parse($dateFin)->format('d/m/Y') }} (dans environ 7 semaines).</p>
                <p>Si vous souhaitez rester dans le logement, nous vous prions d'initier votre demande de renouvellement dès aujourd'hui afin de faciliter les démarches administratives.</p>
            @elseif($step == 3)
                <p>Nous attirons votre attention sur le fait que votre contrat de bail actuel expire le {{ \Carbon\Carbon::parse($dateFin)->format('d/m/Y') }} (dans environ 6 semaines).</p>
                <p>Il est important de soumettre votre demande de renouvellement le plus tôt possible pour éviter que le logement ne soit remis à la location à la fin de votre bail.</p>
            @else
                <p>Il s'agit de notre <strong>dernier rappel automatique</strong> concernant l'échéance de votre bail fixée au {{ \Carbon\Carbon::parse($dateFin)->format('d/m/Y') }} (dans environ 5 semaines).</p>
                <p>Sans action ou demande de renouvellement de votre part dans les prochains jours, nous serons dans l'obligation de considérer que vous libérez le logement à la date de fin, et de commencer les visites pour un futur locataire.</p>
            @endif

            <div class="info-card">
                <h3 class="info-title">Récapitulatif de votre bail</h3>
                
                <div class="info-item">
                    <span class="label">Bailleur / Compagnie :</span>
                    <span class="value">{{ $companyName }}</span>
                </div>
                
                <div class="info-item">
                    <span class="label">Agence gérante :</span>
                    <span class="value">{{ $agencyName }}</span>
                </div>

                <div class="info-item">
                    <span class="label">Logement concerné :</span>
                    <span class="value">{{ $logementRef }}</span>
                </div>

                <div class="info-item">
                    <span class="label">Date de fin actuelle :</span>
                    <span class="value">{{ \Carbon\Carbon::parse($dateFin)->format('d/m/Y') }}</span>
                </div>
            </div>

            @if($step == 4)
                <div class="warning-text">
                    ⚠️ Attention : Si vous ne demandez pas de renouvellement rapidement, votre contrat prendra fin de manière irrévocable à la date indiquée et vous devrez quitter le logement.
                </div>
            @endif

            <p style="margin-top: 24px;">Pour faire votre demande, connectez-vous simplement à votre portail locataire et rendez-vous dans la section <strong>Renouvellement</strong>.</p>
            
            <div style="text-align: center; margin-top: 32px;">
                <a href="{{ url('/login') }}" class="btn">Soumettre une Demande de Renouvellement</a>
            </div>
        </div>
        <div class="footer">
            <p>Cet email a été envoyé automatiquement par le portail de gestion PropertyAI.</p>
            <p>&copy; {{ date('Y') }} {{ $companyName }}. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>
