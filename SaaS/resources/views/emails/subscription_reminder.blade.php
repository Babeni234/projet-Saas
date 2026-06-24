<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expiration de votre abonnement - Property AI</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f1f5f9;
            color: #1e293b;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
        }
        .header {
            background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);
            padding: 30px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
        }
        .content {
            padding: 30px;
            line-height: 1.6;
            text-align: left;
        }
        .content h2 {
            margin-top: 0;
            font-size: 18px;
            color: #0f172a;
        }
        .badge {
            display: inline-block;
            background-color: #fef3c7;
            color: #d97706;
            padding: 6px 12px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 20px;
        }
        .details-card {
            background-color: #f8fafc;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #e2e8f0;
            margin-bottom: 25px;
        }
        .details-row {
            margin-bottom: 10px;
            font-size: 14px;
        }
        .details-row:last-child {
            margin-bottom: 0;
        }
        .details-label {
            color: #64748b;
            font-weight: 500;
            display: inline-block;
            width: 130px;
        }
        .details-value {
            color: #0f172a;
            font-weight: 700;
        }
        .btn {
            display: block;
            text-align: center;
            background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 20px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 14px;
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);
            transition: all 0.2s;
            margin-top: 20px;
        }
        .footer {
            padding: 20px 30px;
            background-color: #f8fafc;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 11px;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Property AI</h1>
        </div>
        <div class="content">
            @if($type === 'trial')
                <div class="badge">Fin de période d'essai</div>
                <h2>Bonjour {{ $user->name }},</h2>
                <p>Nous espérons que votre expérience d'essai gratuit sur <strong>Property AI</strong> a été concluante et vous a permis d'optimiser la gestion de vos agences, biens et locataires.</p>
                <p>Votre période d'essai de 14 jours prendra fin le <strong>{{ $endsAt }}</strong>. Après cette date, l'accès à vos modules principaux (Comptabilité, Maintenance, etc.) sera temporairement suspendu jusqu'à la souscription d'un abonnement.</p>
            @else
                <div class="badge">Expiration d'abonnement</div>
                <h2>Bonjour {{ $user->name }},</h2>
                <p>Nous vous informons que votre abonnement au forfait <strong>{{ $planName }}</strong> arrive à échéance le <strong>{{ $endsAt }}</strong>.</p>
                <p>Pour éviter toute interruption de service et conserver l'accès complet à vos collaborateurs, modules d'IA et données de gestion, nous vous invitons à renouveler votre forfait dès à présent.</p>
            @endif

            <div class="details-card">
                <div class="details-row">
                    <span class="details-label">Entreprise :</span>
                    <span class="details-value">{{ $company->legal_name ?? 'Property AI' }}</span>
                </div>
                <div class="details-row">
                    <span class="details-label">Offre actuelle :</span>
                    <span class="details-value">{{ $planName }}</span>
                </div>
                <div class="details-row">
                    <span class="details-label">Date d'expiration :</span>
                    <span class="details-value">{{ $endsAt }}</span>
                </div>
            </div>

            <a href="{{ url('/dashboard/company/upgrade') }}" class="btn">Mettre à jour / Renouveler l'abonnement</a>
        </div>
        <div class="footer">
            Cet e-mail automatique a été envoyé par Property AI. Merci de ne pas y répondre directement.
        </div>
    </div>
</body>
</html>
