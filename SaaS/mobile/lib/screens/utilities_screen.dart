import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../services/api_service.dart';
import '../theme/app_colors.dart';
import '../widgets/glass_container.dart';

class UtilitiesScreen extends StatefulWidget {
  const UtilitiesScreen({super.key});

  @override
  State<UtilitiesScreen> createState() => _UtilitiesScreenState();
}

class _UtilitiesScreenState extends State<UtilitiesScreen> {
  String _filter = 'all';

  Future<void> _payUtility(String type, double amount) async {
    final apiService = context.read<ApiService>();
    final success = await apiService.payUtility(amount, type);
    
    if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(success ? 'Paiement effectué avec succès' : 'Erreur lors du paiement'),
          backgroundColor: success ? AppColors.success : AppColors.error,
          behavior: SnackBarBehavior.floating,
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
        ),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    final api = context.watch<ApiService>();
    final invoices = api.invoices.where((inv) {
      final type = inv['type']?.toString() ?? '';
      return type == 'water' || type == 'electricity' || type == 'WATER' || type == 'ELECTRIC';
    }).toList();

    final waterInvoices = invoices.where((i) => (i['type']?.toString() ?? '').toLowerCase().contains('water')).toList();
    final elecInvoices = invoices.where((i) => (i['type']?.toString() ?? '').toLowerCase().contains('electric')).toList();
    final pendingTotal = invoices.fold<double>(0, (sum, i) => i['status']?.toString() == 'paid' ? sum : sum + ((i['amount'] as num?)?.toDouble() ?? 0));
    final lastPeriod = invoices.isNotEmpty ? invoices.last['period']?.toString() ?? '---' : '---';

    List<dynamic> filteredInvoices = invoices;
    if (_filter == 'water') filteredInvoices = waterInvoices;
    else if (_filter == 'elec') filteredInvoices = elecInvoices;

    return SafeArea(
      bottom: false,
      child: SingleChildScrollView(
        padding: const EdgeInsets.fromLTRB(24, 24, 24, 120),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            Text(
              'Eau & Électricité',
              style: Theme.of(context).textTheme.headlineMedium?.copyWith(fontSize: 28),
            ),
            const SizedBox(height: 8),
            Text(
              'Consultez vos factures, suivez votre consommation et payez en un clic.',
              style: Theme.of(context).textTheme.bodyMedium?.copyWith(fontSize: 15),
            ),
            const SizedBox(height: 28),

            if (waterInvoices.isNotEmpty)
              _buildConsumptionCard(
                context,
                icon: Icons.water_drop_rounded,
                label: 'Eau',
                conso: '${waterInvoices.last['conso'] ?? '---'}',
                cost: '${(waterInvoices.last['amount'] as num?)?.toStringAsFixed(2) ?? '0'} €',
                period: waterInvoices.last['period']?.toString() ?? '---',
                status: waterInvoices.last['status']?.toString() == 'paid' ? 'Payé' : 'En attente',
                statusColor: waterInvoices.last['status']?.toString() == 'paid' ? AppColors.success : AppColors.warning,
                progress: 0.6,
                barColor: const Color(0xFF3B82F6),
              ),
            if (elecInvoices.isNotEmpty)
              Padding(
                padding: EdgeInsets.only(top: waterInvoices.isNotEmpty ? 16 : 0),
                child: _buildConsumptionCard(
                  context,
                  icon: Icons.bolt_rounded,
                  label: 'Électricité',
                  conso: '${elecInvoices.last['conso'] ?? '---'}',
                  cost: '${(elecInvoices.last['amount'] as num?)?.toStringAsFixed(2) ?? '0'} €',
                  period: elecInvoices.last['period']?.toString() ?? '---',
                  status: elecInvoices.last['status']?.toString() == 'paid' ? 'Payé' : 'En attente',
                  statusColor: elecInvoices.last['status']?.toString() == 'paid' ? AppColors.success : AppColors.warning,
                  progress: 0.7,
                  barColor: const Color(0xFFD97706),
                ),
              ),
            if (invoices.isEmpty) ...[
              _buildConsumptionCard(context, icon: Icons.water_drop_rounded, label: 'Eau', conso: '---', cost: '0 €', period: '---', status: 'Aucune', statusColor: AppColors.textSecondary, progress: 0, barColor: AppColors.textSecondary),
              const SizedBox(height: 16),
              _buildConsumptionCard(context, icon: Icons.bolt_rounded, label: 'Électricité', conso: '---', cost: '0 €', period: '---', status: 'Aucune', statusColor: AppColors.textSecondary, progress: 0, barColor: AppColors.textSecondary),
            ],
            const SizedBox(height: 28),

            Row(
              children: [
                Expanded(child: _buildSummaryCard(context, 'Eau', '${waterInvoices.length}', Icons.water_drop_rounded, AppColors.primary)),
                const SizedBox(width: 12),
                Expanded(child: _buildSummaryCard(context, 'Électricité', '${elecInvoices.length}', Icons.bolt_rounded, AppColors.warning)),
              ],
            ),
            const SizedBox(height: 12),
            Row(
              children: [
                Expanded(child: _buildSummaryCard(context, 'Total dû', '${pendingTotal.toStringAsFixed(2)} €', Icons.payments_rounded, AppColors.error)),
                const SizedBox(width: 12),
                Expanded(child: _buildSummaryCard(context, 'Dernière facture', lastPeriod, Icons.calendar_today_rounded, AppColors.success)),
              ],
            ),
            const SizedBox(height: 28),

            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Text('Factures', style: Theme.of(context).textTheme.titleLarge),
              ],
            ),
            const SizedBox(height: 16),

            GlassContainer(
              padding: const EdgeInsets.all(12),
              borderRadius: 20,
              child: Row(
                children: [
                  _buildFilterChip('Tous', 'all'),
                  const SizedBox(width: 8),
                  _buildFilterChip('Eau', 'water'),
                  const SizedBox(width: 8),
                  _buildFilterChip('Électricité', 'elec'),
                ],
              ),
            ),
            const SizedBox(height: 16),

            if (filteredInvoices.isEmpty)
              Padding(
                padding: const EdgeInsets.all(24),
                child: Text('Aucune facture disponible', style: TextStyle(color: AppColors.textSecondary)),
              )
            else
              ...filteredInvoices.map((inv) {
                final isWater = (inv['type']?.toString() ?? '').toLowerCase().contains('water');
                final status = inv['status']?.toString() ?? 'pending';
                return Padding(
                  padding: const EdgeInsets.only(bottom: 10),
                  child: _buildInvoiceRow(
                    context,
                    inv['reference']?.toString() ?? '---',
                    isWater ? 'Eau' : 'Électricité',
                    inv['period']?.toString() ?? '---',
                    inv['conso']?.toString() ?? '',
                    '${(inv['amount'] as num?)?.toStringAsFixed(2) ?? '0'} €',
                    status,
                    isWater,
                  ),
                );
              }),
          ],
        ),
      ),
    );
  }

  Widget _buildConsumptionCard(
    BuildContext context, {
    required IconData icon,
    required String label,
    required String conso,
    required String cost,
    required String period,
    required String status,
    required Color statusColor,
    required double progress,
    required Color barColor,
  }) {
    return GlassContainer(
      padding: const EdgeInsets.all(20),
      borderRadius: 24,
      child: Column(
        children: [
          Row(
            children: [
              Container(
                padding: const EdgeInsets.all(12),
                decoration: BoxDecoration(
                  color: barColor.withValues(alpha: 0.12),
                  shape: BoxShape.circle,
                ),
                child: Icon(icon, color: barColor, size: 24),
              ),
              const SizedBox(width: 16),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(label, style: const TextStyle(fontSize: 13, color: AppColors.textSecondary)),
                    Row(
                      children: [
                        Text(conso, style: const TextStyle(fontWeight: FontWeight.w700, fontSize: 18)),
                        const SizedBox(width: 8),
                        Text(cost, style: TextStyle(color: barColor, fontWeight: FontWeight.w700, fontSize: 15)),
                      ],
                    ),
                  ],
                ),
              ),
              Column(
                crossAxisAlignment: CrossAxisAlignment.end,
                children: [
                  Text(period, style: const TextStyle(fontSize: 12, color: AppColors.textSecondary)),
                  const SizedBox(height: 4),
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 3),
                    decoration: BoxDecoration(
                      color: statusColor.withValues(alpha: 0.12),
                      borderRadius: BorderRadius.circular(10),
                    ),
                    child: Text(status, style: TextStyle(color: statusColor, fontSize: 11, fontWeight: FontWeight.w600)),
                  ),
                ],
              ),
            ],
          ),
          const SizedBox(height: 16),
          ClipRRect(
            borderRadius: BorderRadius.circular(8),
            child: Container(
              height: 8,
              color: AppColors.background,
              child: FractionallySizedBox(
                alignment: Alignment.centerLeft,
                widthFactor: progress,
                child: Container(
                  decoration: BoxDecoration(
                    gradient: LinearGradient(
                      colors: [barColor, barColor.withValues(alpha: 0.6)],
                    ),
                    borderRadius: BorderRadius.circular(8),
                  ),
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildSummaryCard(BuildContext context, String label, String value, IconData icon, Color color) {
    return GlassContainer(
      padding: const EdgeInsets.all(16),
      borderRadius: 20,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Icon(icon, color: color, size: 22),
          const SizedBox(height: 12),
          Text(value, style: TextStyle(fontWeight: FontWeight.w700, fontSize: 18, color: color)),
          const SizedBox(height: 4),
          Text(label, style: const TextStyle(color: AppColors.textSecondary, fontSize: 12)),
        ],
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

  Widget _buildInvoiceRow(BuildContext context, String ref, String type, String period, String conso, String amount, String status, [bool? isWater]) {
    final isPaid = status == 'paid';
    final typeColor = (isWater ?? type == 'Eau') ? AppColors.primary : AppColors.warning;
    return GlassContainer(
      padding: const EdgeInsets.all(16),
      borderRadius: 18,
      child: Row(
        children: [
          Container(
            padding: const EdgeInsets.all(8),
            decoration: BoxDecoration(
              color: typeColor.withValues(alpha: 0.12),
              shape: BoxShape.circle,
            ),
            child: Icon(type == 'Eau' ? Icons.water_drop_rounded : Icons.bolt_rounded, color: typeColor, size: 18),
          ),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Text(ref, style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 13)),
                    Text('$amount ', style: const TextStyle(fontWeight: FontWeight.w700, fontSize: 14)),
                  ],
                ),
                const SizedBox(height: 4),
                Row(
                  children: [
                    Container(
                      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                      decoration: BoxDecoration(
                        color: typeColor.withValues(alpha: 0.12),
                        borderRadius: BorderRadius.circular(8),
                      ),
                      child: Text(type, style: TextStyle(color: typeColor, fontSize: 10, fontWeight: FontWeight.w600)),
                    ),
                    const SizedBox(width: 8),
                    Text(period, style: const TextStyle(color: AppColors.textSecondary, fontSize: 12)),
                    if (conso.isNotEmpty) ...[
                      const SizedBox(width: 8),
                      Text('• $conso', style: const TextStyle(color: AppColors.textSecondary, fontSize: 12)),
                    ],
                    const Spacer(),
                    if (isPaid)
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 3),
                        decoration: BoxDecoration(
                          color: AppColors.success.withValues(alpha: 0.12),
                          borderRadius: BorderRadius.circular(10),
                        ),
                        child: Text('Payé', style: TextStyle(
                          color: AppColors.success,
                          fontSize: 11, fontWeight: FontWeight.w600,
                        )),
                      )
                    else
                      GestureDetector(
                        onTap: () {
                          final amountValue = double.tryParse(amount.replaceAll('€', '').replaceAll(',', '.').trim()) ?? 0;
                          _payUtility(type.toLowerCase(), amountValue);
                        },
                        child: Container(
                          padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                          decoration: BoxDecoration(
                            color: AppColors.primary,
                            borderRadius: BorderRadius.circular(10),
                          ),
                          child: const Text('Payer', style: TextStyle(
                            color: Colors.white,
                            fontSize: 11, fontWeight: FontWeight.w700,
                          )),
                        ),
                      ),
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
