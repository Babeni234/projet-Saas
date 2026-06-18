import 'package:flutter/material.dart';
import '../theme/app_colors.dart';
import '../widgets/glass_container.dart';

class ReceiptsScreen extends StatefulWidget {
  const ReceiptsScreen({super.key});

  @override
  State<ReceiptsScreen> createState() => _ReceiptsScreenState();
}

class _ReceiptsScreenState extends State<ReceiptsScreen> {
  String _filter = 'all';

  @override
  Widget build(BuildContext context) {
    return SafeArea(
      bottom: false,
      child: SingleChildScrollView(
        padding: const EdgeInsets.fromLTRB(24, 24, 24, 120),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            Text(
              'Quittances & Reçus',
              style: Theme.of(context).textTheme.headlineMedium?.copyWith(fontSize: 28),
            ),
            const SizedBox(height: 8),
            Text(
              'Téléchargez vos quittances de loyer et reçus de factures.',
              style: Theme.of(context).textTheme.bodyMedium?.copyWith(fontSize: 15),
            ),
            const SizedBox(height: 28),

            GlassContainer(
              padding: const EdgeInsets.all(12),
              borderRadius: 20,
              child: Row(
                children: [
                  _buildFilterChip('Tous', 'all'),
                  const SizedBox(width: 8),
                  _buildFilterChip('Loyers', 'rent'),
                  const SizedBox(width: 8),
                  _buildFilterChip('Factures', 'invoice'),
                ],
              ),
            ),
            const SizedBox(height: 24),

            _buildReceiptCard(
              context,
              type: 'Quittance de loyer',
              period: 'Mai 2026',
              amount: '850,00 €',
              date: '05 Mai 2026',
              reference: 'QUIT-2026-05-001',
              isRent: true,
            ),
            const SizedBox(height: 14),
            _buildReceiptCard(
              context,
              type: 'Facture d\'électricité',
              period: 'Mai 2026',
              amount: '89,00 €',
              date: '02 Mai 2026',
              reference: 'FAC-2026-05-E-001',
              isRent: false,
            ),
            const SizedBox(height: 14),
            _buildReceiptCard(
              context,
              type: 'Quittance de loyer',
              period: 'Avril 2026',
              amount: '850,00 €',
              date: '02 Avril 2026',
              reference: 'QUIT-2026-04-001',
              isRent: true,
            ),
            const SizedBox(height: 14),
            _buildReceiptCard(
              context,
              type: 'Facture d\'eau',
              period: 'Avril 2026',
              amount: '42,00 €',
              date: '28 Mars 2026',
              reference: 'FAC-2026-04-W-001',
              isRent: false,
            ),
            const SizedBox(height: 14),
            _buildReceiptCard(
              context,
              type: 'Quittance de loyer',
              period: 'Mars 2026',
              amount: '970,00 €',
              date: '15 Mars 2026',
              reference: 'QUIT-2026-03-001',
              isRent: true,
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildFilterChip(String label, String value) {
    final isActive = _filter == value;
    return GestureDetector(
      onTap: () => setState(() => _filter = value),
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
        decoration: BoxDecoration(
          color: isActive ? AppColors.primary : Colors.transparent,
          borderRadius: BorderRadius.circular(16),
        ),
        child: Text(label, style: TextStyle(
          color: isActive ? Colors.white : AppColors.textSecondary,
          fontWeight: FontWeight.w600,
          fontSize: 13,
        )),
      ),
    );
  }

  Widget _buildReceiptCard(
    BuildContext context, {
    required String type,
    required String period,
    required String amount,
    required String date,
    required String reference,
    required bool isRent,
  }) {
    final icon = isRent ? Icons.receipt_long_rounded : Icons.description_rounded;
    final color = isRent ? AppColors.primary : AppColors.warning;
    return GlassContainer(
      padding: const EdgeInsets.all(18),
      borderRadius: 22,
      child: Row(
        children: [
          Container(
            padding: const EdgeInsets.all(12),
            decoration: BoxDecoration(
              gradient: LinearGradient(
                colors: [color.withValues(alpha: 0.15), color.withValues(alpha: 0.05)],
              ),
              borderRadius: BorderRadius.circular(16),
            ),
            child: Icon(icon, color: color, size: 24),
          ),
          const SizedBox(width: 16),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Text(type, style: const TextStyle(fontWeight: FontWeight.w700, fontSize: 15)),
                    IconButton(
                      onPressed: () {},
                      icon: const Icon(Icons.download_rounded, color: AppColors.primary, size: 22),
                      padding: EdgeInsets.zero,
                      constraints: const BoxConstraints(),
                    ),
                  ],
                ),
                const SizedBox(height: 4),
                Row(
                  children: [
                    Text(period, style: const TextStyle(color: AppColors.textSecondary, fontSize: 13)),
                    const SizedBox(width: 12),
                    Icon(Icons.calendar_today_rounded, size: 12, color: AppColors.textSecondary),
                    const SizedBox(width: 4),
                    Text(date, style: const TextStyle(color: AppColors.textSecondary, fontSize: 12)),
                  ],
                ),
                const SizedBox(height: 6),
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Text(reference, style: const TextStyle(color: AppColors.textTertiary, fontSize: 11)),
                    Text(amount, style: TextStyle(fontWeight: FontWeight.w700, fontSize: 17, color: color)),
                  ],
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
