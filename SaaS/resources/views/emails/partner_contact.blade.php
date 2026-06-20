<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau message de contact</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            color: #334155;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }
        .header {
            background: linear-gradient(135deg, #4f46e5, #06b6d4);
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
        .header p {
            margin: 8px 0 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .content {
            padding: 32px;
        }
        .meta-box {
            background-color: #f1f5f9;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
        }
        .meta-row {
            margin-bottom: 12px;
            font-size: 14px;
        }
        .meta-row:last-child {
            margin-bottom: 0;
        }
        .meta-label {
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
            display: inline-block;
            width: 120px;
        }
        .meta-value {
            color: #1e293b;
        }
        .message-box {
            border-left: 4px solid #4f46e5;
            background-color: #fcfcff;
            padding: 16px 20px;
            border-radius: 0 12px 12px 0;
            margin-bottom: 30px;
        }
        .message-title {
            font-size: 12px;
            font-weight: 700;
            color: #4f46e5;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }
        .message-body {
            font-size: 15px;
            line-height: 1.6;
            color: #334155;
            white-space: pre-wrap;
        }
        .footer {
            background-color: #f8fafc;
            padding: 24px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
            border-top: 1px solid #f1f5f9;
        }
        .footer a {
            color: #4f46e5;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Property AI</h1>
            <p>Mise en relation partenaires</p>
        </div>
        <div class="content">
            <p style="font-size: 16px; margin-top: 0; line-height: 1.5;">
                Bonjour,
            </p>
            <p style="font-size: 15px; line-height: 1.5; margin-bottom: 24px;">
                Vous avez reçu un nouveau message d'intérêt depuis la page publique de **Property AI** concernant votre entreprise **{{ $companyName }}**.
            </p>
            
            <div class="meta-box">
                <div class="meta-row">
                    <span class="meta-label">De :</span>
                    <span class="meta-value">{{ $senderName }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Email de contact :</span>
                    <span class="meta-value"><a href="mailto:{{ $senderEmail }}" style="color: #4f46e5;">{{ $senderEmail }}</a></span>
                </div>
            </div>
            
            <div class="message-box">
                <div class="message-title">Message reçu</div>
                <div class="message-body">{{ $messageBody }}</div>
            </div>
            
            <p style="font-size: 14px; color: #64748b; line-height: 1.5; margin-top: 30px;">
                Pour répondre à ce prospect, vous pouvez directement lui écrire en répondant à cet e-mail.
            </p>
        </div>
        <div class="footer">
            Cet email a été envoyé automatiquement par la plateforme <a href="http://propertyai.com">Property AI</a>.<br>
            © {{ date('Y') }} Property AI. Tous droits réservés.
        </div>
    </div>
</body>
</html>
