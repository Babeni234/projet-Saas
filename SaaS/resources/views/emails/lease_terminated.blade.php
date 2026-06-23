<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Résiliation de votre contrat de bail</title>
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
            background: linear-gradient(135deg, #e11d48 0%, #be123c 100%);
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
            background-color: #fff1f2;
            border: 1px solid #fecdd3;
            border-radius: 12px;
            padding: 24px;
            margin: 24px 0;
        }
        .info-title {
            margin-top: 0;
            color: #9f1239;
            font-size: 16px;
            border-bottom: 1px solid #fecdd3;
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
            color: #4f4f4f;
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
            background: linear-gradient(135deg, #e11d48 0%, #be123c 100%);
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 28px;
            border-radius: 8px;
            font-weight: bold;
            margin-top: 20px;
            text-align: center;
            box-shadow: 0 4px 6px -1px rgba(225, 29, 72, 0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Fin de Bail Exécutée</h1>
        </div>
        <div class="content">
            <p>Bonjour <strong>{{ $locataireName }}</strong>,</p>
            <p>Nous vous informons que votre contrat de bail a été <strong>résilié / terminé</strong> avec succès par votre gestionnaire.</p>
            
            <p>En conséquence, vos accès actifs à ce bail sont clos et le statut de votre dossier locataire a été mis à jour.</p>

            <div class="info-card">
                <h3 class="info-title">Détails du bail clôturé</h3>
                
                <div class="info-item">
                    <span class="label">Bailleur / Compagnie :</span>
                    <span class="value">{{ $companyName }}</span>
                </div>
                
                <div class="info-item">
                    <span class="label">Agence gérante :</span>
                    <span class="value">{{ $agencyName }}</span>
                </div>

                <div class="info-item">
                    <span class="label">Référence du logement :</span>
                    <span class="value">{{ $logementRef }}</span>
                </div>

                <div class="info-item">
                    <span class="label">Date de clôture :</span>
                    <span class="value">{{ \Carbon\Carbon::parse($dateFin)->format('d/m/Y') }}</span>
                </div>
            </div>

            <p>Nous vous invitons à vous rapprocher de votre agence <strong>{{ $agencyName }}</strong> pour toute question relative au dépôt de garantie (caution), aux clés, ou pour programmer l'état des lieux de sortie si cela n'a pas encore été fait.</p>
            
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
