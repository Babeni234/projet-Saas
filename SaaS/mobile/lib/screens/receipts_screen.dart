import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../services/api_service.dart';
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
    final apiService = context.watch<ApiService>();
    final receipts = apiService.receipts;

    // Filter receipts based on selected filter
    List<dynamic> filteredReceipts = receipts;
    if (_filter != 'all') {
      filteredReceipts = receipts.where((r) {
        final type = r['type'] as String? ?? '';
        if (_filter == 'rent') return type == 'rent';
        if (_filter == 'invoice') return type == 'water' || type == 'electricity';
        if (_filter == 'contract') return type == 'contract_fee';
        return true;
      }).toList();
    }

    return SafeArea(
      bottom: false,
      child: SingleChildScrollView(
        physics: const BouncingScrollPhysics(),
        padding: const EdgeInsets.fromLTRB(24, 24, 24, 140),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text('Documents', style: Theme.of(context).textTheme.headlineMedium?.copyWith(fontSize: 34, fontWeight: FontWeight.w800, letterSpacing: -1)),
            Text('Quittances et reçus officiels', style: TextStyle(color: AppColors.textSecondary, fontSize: 16, fontWeight: FontWeight.w500)),
            const SizedBox(height: 32),

            SingleChildScrollView(
              scrollDirection: Axis.horizontal,
              physics: const BouncingScrollPhysics(),
              child: Row(
                children: [
                  _buildFilterChip('Tous', 'all'),
                  const SizedBox(width: 12),
                  _buildFilterChip('Loyers', 'rent'),
                  const SizedBox(width: 12),
                  _buildFilterChip('Factures', 'invoice'),
                  const SizedBox(width: 12),
                  _buildFilterChip('Bail', 'contract'),
                ],
              ),
            ),
            const SizedBox(height: 24),

            _buildSectionHeader('RÉCENTS'),
            const SizedBox(height: 12),
            if (filteredReceipts.isEmpty)
              Padding(
                padding: const EdgeInsets.all(24),
                child: Text('Aucun document disponible', style: TextStyle(color: AppColors.textSecondary)),
              )
            else
              ...filteredReceipts.map((receipt) {
                final type = receipt['type'] as String? ?? 'rent';
                final isRent = type == 'rent';
                final isUtility = type == 'water' || type == 'electricity';
                final isContract = type == 'contract_fee';
                
                String displayType;
                if (isRent) displayType = 'Quittance de loyer';
                else if (isUtility) displayType = type == 'water' ? 'Facture Eau' : 'Facture Électricité';
                else if (isContract) displayType = 'Frais de contrat';
                else displayType = receipt['title'] ?? 'Document';

                return Padding(
                  padding: const EdgeInsets.only(bottom: 16),
                  child: _buildReceiptCard(
                    context,
                    type: displayType,
                    period: receipt['period'] ?? '---',
                    amount: '${(receipt['amount'] as num?)?.toStringAsFixed(2) ?? '0'} €',
                    date: receipt['paid_at'] ?? receipt['date'] ?? '---',
                    reference: receipt['reference'] ?? '---',
                    isRent: isRent,
                  ),
                );
              }).toList(),
          ],
        ),
      ),
    );
  }

  Widget _buildSectionHeader(String title) {
    return Padding(
      padding: const EdgeInsets.only(left: 12),
      child: Text(
        title,
        style: const TextStyle(
          color: AppColors.textSecondary,
          fontSize: 13,
          fontWeight: FontWeight.w700,
          letterSpacing: 1.2,
        ),
      ),
    );
  }

  Widget _buildFilterChip(String label, String value) {
    final isActive = _filter == value;
    return GestureDetector(
      onTap: () => setState(() => _filter = value),
      child: AnimatedContainer(
        duration: const Duration(milliseconds: 200),
        padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 10),
        decoration: BoxDecoration(
          color: isActive ? AppColors.primary : AppColors.primary.withValues(alpha: 0.05),
          borderRadius: BorderRadius.circular(16),
        ),
        child: Text(label, style: TextStyle(
          color: isActive ? Colors.white : AppColors.primary,
          fontWeight: FontWeight.w700,
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
    final color = isRent ? AppColors.primary : AppColors.warning;
    return GlassContainer(
      padding: const EdgeInsets.all(20),
      borderRadius: 28,
      child: Row(
        children: [
          Container(
            padding: const EdgeInsets.all(12),
            decoration: BoxDecoration(
              color: color.withValues(alpha: 0.1),
              borderRadius: BorderRadius.circular(16),
            ),
            child: Icon(isRent ? Icons.receipt_long_rounded : Icons.bolt_rounded, color: color, size: 24),
          ),
          const SizedBox(width: 16),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(type, style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 16)),
                Text('$period • $date', style: const TextStyle(color: AppColors.textSecondary, fontSize: 13, fontWeight: FontWeight.w500)),
                const SizedBox(height: 4),
                Text(reference, style: const TextStyle(color: AppColors.textTertiary, fontSize: 11)),
              ],
            ),
          ),
          Column(
            crossAxisAlignment: CrossAxisAlignment.end,
            children: [
              Text(amount, style: TextStyle(fontWeight: FontWeight.w900, fontSize: 16, color: color)),
              const SizedBox(height: 8),
              Container(
                padding: const EdgeInsets.all(8),
                decoration: BoxDecoration(color: AppColors.primary.withValues(alpha: 0.1), shape: BoxShape.circle),
                child: const Icon(Icons.download_rounded, color: AppColors.primary, size: 18),
              ),
            ],
          ),
        ],
      ),
    );
  }
}
