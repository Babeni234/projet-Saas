<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $subjectTitle }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
            color: #2c3e50;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(20, 50, 50, 0.08);
            border: 1px solid #e2ece9;
        }
        .header {
            background: linear-gradient(135deg, #0f2b26 0%, #1a4d44 100%);
            padding: 35px 32px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }
        .header-subtitle {
            font-size: 13px;
            color: #a3cbd6;
            margin-top: 6px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }
        .content {
            padding: 40px 35px;
            line-height: 1.6;
        }
        .content p {
            font-size: 15px;
            margin-bottom: 20px;
            color: #4a5d5a;
        }
        .details-card {
            background-color: #f0f7f5;
            border: 1px solid #d1e5df;
            border-radius: 16px;
            padding: 24px;
            margin: 30px 0;
        }
        .details-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e1eee9;
            font-size: 14px;
        }
        .details-row:last-child {
            border-bottom: none;
        }
        .details-label {
            font-weight: 700;
            color: #1a4d44;
        }
        .details-value {
            color: #2c3e50;
            text-align: right;
            font-weight: 600;
        }
        .btn-container {
            text-align: center;
            margin-top: 30px;
        }
        .btn {
            display: inline-block;
            background: #1a4d44;
            color: #ffffff !important;
            padding: 14px 28px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 700;
            font-size: 14px;
            box-shadow: 0 4px 12px rgba(26, 77, 68, 0.2);
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
            @if(!empty($agencyName))
                <div class="header-subtitle">{{ $agencyName }}</div>
            @endif
        </div>
        <div class="content">
            <h2 style="font-size: 18px; color: #0f2b26; margin-top: 0; margin-bottom: 20px;">{{ $title }}</h2>
            
            @foreach($paragraphs as $p)
                <p>{!! $p !!}</p>
            @endforeach

            @if(!empty($details))
                <div class="details-card">
                    @foreach($details as $label => $value)
                        <div class="details-row">
                            <span class="details-label">{{ $label }}</span>
                            <span class="details-value">{!! $value !!}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            @if(!empty($buttonUrl))
                <div class="btn-container">
                    <a href="{{ $buttonUrl }}" class="btn">{{ $buttonText }}</a>
                </div>
            @endif
        </div>
        <div class="footer">
            <p>Cet e-mail automatique a été envoyé par la plateforme de gestion PropertyAI.</p>
            <p>&copy; {{ date('Y') }} {{ $companyName }}. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>
