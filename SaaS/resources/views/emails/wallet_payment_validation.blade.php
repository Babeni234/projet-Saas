<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Autorisation de Paiement Wallet Requise</title>
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
            background: linear-gradient(135deg, #3b82f6 0%, #1e3a8a 100%);
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
        .intro {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 24px;
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
        .btn-container {
            text-align: center;
            margin: 32px 0;
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 12px;
            font-weight: bold;
            font-size: 15px;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.2);
            transition: all 0.2s ease;
        }
        .warning-box {
            background-color: #fffaf0;
            border-left: 4px solid #f59e0b;
            padding: 16px;
            border-radius: 8px;
            margin-top: 24px;
            font-size: 14px;
            color: #713f12;
            line-height: 1.5;
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
            <h1>{{ $companyName }}</h1>
        </div>
        <div class="content">
            <p class="intro">Bonjour <strong>{{ $locataire->nom }}</strong>,</p>
            <p class="intro">Le bailleur ou l'agence de gestion a initié une demande de règlement par débit de votre portefeuille électronique (Wallet). Pour votre sécurité, nous requérons votre validation explicite avant de procéder à la transaction.</p>
            
            <div class="details-container">
                <div class="detail-item">
                    <span class="label">Montant à débiter :</span>
                    <span class="value">{{ number_format($pendingPayment->amount, 2, ',', ' ') }} €</span>
                </div>
                <div class="detail-item">
                    <span class="label">Objet du paiement :</span>
                    <span class="value">{{ $paymentDescription }}</span>
                </div>
                <div class="detail-item">
                    <span class="label">Source de paiement :</span>
                    <span class="value">Portefeuille électronique (Wallet)</span>
                </div>
            </div>

            <p class="intro" style="text-align: center;">Cliquez sur le bouton ci-dessous pour accéder au formulaire de validation sécurisé afin de saisir votre code PIN secret et valider ce paiement.</p>

            <div class="btn-container">
                <a href="{{ $validationUrl }}" class="btn">Autoriser & Valider le Paiement</a>
            </div>

            <div class="warning-box">
                <strong>Sécurité importante :</strong> Ne partagez jamais votre code secret Wallet avec qui que ce soit. Si vous n'êtes pas à l'origine de cette demande de paiement ou si vous ne la reconnaissez pas, veuillez ignorer cet e-mail. Aucun fonds ne sera débité sans votre code secret.
            </div>
        </div>
        <div class="footer">
            <p>Cet email a été envoyé automatiquement par le système sécurisé de {{ $companyName }}.</p>
            <p>&copy; {{ date('Y') }} {{ $companyName }}. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>
