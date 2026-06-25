<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Quittance {{ $receipt->reference }}</title>
<style>
  body { font-family: 'DejaVu Sans', sans-serif; font-size: 13px; color: #1f2937; padding: 40px; }
  .header { text-align: center; margin-bottom: 30px; }
  .header h1 { font-size: 18px; margin: 0; }
  .header p { color: #6b7280; font-size: 12px; margin: 4px 0 0; }
  .ref { text-align: right; font-size: 11px; margin-bottom: 20px; color: #6b7280; }
  table { width: 100%; border-collapse: collapse; }
  th, td { padding: 8px 12px; text-align: left; border-bottom: 1px solid #e5e7eb; }
  th { background: #f9fafb; font-size: 11px; text-transform: uppercase; color: #6b7280; }
  .total td { font-weight: bold; font-size: 14px; border-top: 2px solid #1f2937; }
  .footer { margin-top: 30px; font-size: 11px; color: #9ca3af; text-align: center; }
</style>
</head>
<body>
<div class="header">
  <h1>Quittance de loyer</h1>
  <p>{{ $receipt->contract->property->title ?? '' }} - {{ $receipt->contract->property->address ?? '' }}</p>
</div>
<div class="ref">Réf : {{ $receipt->reference }}<br>Période : {{ $receipt->period }}</div>
<table>
  <tr><th colspan="2">Détail</th></tr>
  <tr><td>Loyer</td><td>{{ number_format($receipt->rent, 2) }} €</td></tr>
  <tr><td>Charges</td><td>{{ number_format($receipt->charges, 2) }} €</td></tr>
  <tr class="total"><td>Total</td><td>{{ number_format($receipt->total, 2) }} €</td></tr>
  <tr><td>Échéance</td><td>{{ $receipt->due_date->format('d/m/Y') }}</td></tr>
  <tr><td>Payée le</td><td>{{ $receipt->payment_date ? $receipt->payment_date->format('d/m/Y') : '-' }}</td></tr>
</table>
<div class="footer">ImmoSaas - Quittance générée le {{ now()->format('d/m/Y') }}</div>
</body>
</html>
