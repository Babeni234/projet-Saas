import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../services/api_service.dart';
import '../theme/app_colors.dart';
import '../widgets/glass_container.dart';

class RentsScreen extends StatefulWidget {
  const RentsScreen({super.key});

  @override
  State<RentsScreen> createState() => _RentsScreenState();
}

class _RentsScreenState extends State<RentsScreen> with SingleTickerProviderStateMixin {
  late AnimationController _animationController;
  late Animation<double> _fadeAnimation;

  String _invoiceFilter = 'all';

  bool _showPaymentModal = false;
  String _paymentFlowStep = 'recap';
  String _pinInput = '';
  bool _pinError = false;

  final List<String> _selectedMonths = [];

  @override
  void initState() {
    super.initState();
    _animationController = AnimationController(
      vsync: this,
      duration: const Duration(milliseconds: 800),
    );
    _fadeAnimation = Tween<double>(begin: 0.0, end: 1.0).animate(
      CurvedAnimation(parent: _animationController, curve: Curves.easeOutCubic),
    );
    _animationController.forward();
    
    // Load data from API
    WidgetsBinding.instance.addPostFrameCallback((_) {
      context.read<ApiService>().fetchLocataireData();
    });
  }

  List<Map<String, dynamic>> get _rentMonths {
    final apiService = context.watch<ApiService>();
    return apiService.rentMonths.map((m) {
      final amount = m['amount'];
      final penaltyAmount = m['penalty_amount'];
      final penaltyRate = m['penalty_rate'];
      
      return {
        'key': m['key']?.toString() ?? '',
        'label': m['label']?.toString() ?? '',
        'amount': amount is num ? amount.toDouble() : 0.0,
        'status': m['status']?.toString() == 'paid' ? 'paid' : (penaltyRate is num && penaltyRate > 0 ? 'late' : 'pending'),
        'penalty': penaltyAmount is num ? penaltyAmount.toDouble() : 0.0,
        'penalty_rate': penaltyRate is num ? penaltyRate : 0,
      };
    }).toList();
  }

  @override
  void dispose() {
    _animationController.dispose();
    super.dispose();
  }

  double get _totalPaymentAmount => _selectedMonths.fold(0.0, (sum, k) {
    final m = _rentMonths.firstWhere((x) => x['key'] == k);
    return sum + (m['amount'] as double);
  });

  void _toggleMonth(String key) {
    setState(() {
      if (_selectedMonths.contains(key)) {
        _selectedMonths.remove(key);
      } else {
        _selectedMonths.add(key);
      }
    });
  }

  void _openPaymentModal() {
    setState(() {
      _showPaymentModal = true;
      _paymentFlowStep = 'recap';
      _pinInput = '';
      _pinError = false;
    });
  }

  void _confirmPayment() {
    setState(() => _paymentFlowStep = 'pin');
  }

  void _submitPin() async {
    if (_pinInput.length == 4) {
      final apiService = context.read<ApiService>();
      final success = await apiService.payRent(_totalPaymentAmount);
      
      setState(() => _paymentFlowStep = success ? 'success' : 'pin');
      if (!success) {
        setState(() => _pinError = true);
      }
      
      if (success) {
        Future.delayed(const Duration(seconds: 2), () {
          if (mounted) { setState(() {
            _showPaymentModal = false;
            _selectedMonths.clear();
          }); }
        });
      }
    } else {
      setState(() => _pinError = true);
    }
  }

  @override
  Widget build(BuildContext context) {
    final apiService = context.watch<ApiService>();
    final walletBalance = apiService.walletBalance ?? 0;
    final invoices = apiService.invoices;
    final receipts = apiService.receipts;
    final lateMonths = _rentMonths.where((m) => m['status'] == 'late').toList();

    return SafeArea(
      bottom: false,
      child: Stack(
        children: [
          SingleChildScrollView(
            physics: const BouncingScrollPhysics(),
            padding: const EdgeInsets.fromLTRB(24, 24, 24, 140),
            child: AnimatedBuilder(
              animation: _animationController,
              builder: (context, child) {
                return Opacity(
                  opacity: _fadeAnimation.value,
                  child: Transform.translate(
                    offset: Offset(0, 30 * (1 - _fadeAnimation.value)),
                    child: child,
                  ),
                );
              },
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text('Finances', style: Theme.of(context).textTheme.headlineMedium?.copyWith(fontSize: 34, fontWeight: FontWeight.w800, letterSpacing: -1)),
                  Text('Suivi des loyers et paiements', style: TextStyle(color: AppColors.textSecondary, fontSize: 16, fontWeight: FontWeight.w500)),
                  const SizedBox(height: 32),

                  // Wallet & Summary
                  Container(
                    width: double.infinity,
                    padding: const EdgeInsets.all(28),
                    decoration: BoxDecoration(
                      gradient: AppColors.walletGradient,
                      borderRadius: BorderRadius.circular(32),
                      boxShadow: [
                        BoxShadow(color: Colors.black.withValues(alpha: 0.3), blurRadius: 30, offset: const Offset(0, 15)),
                      ],
                    ),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        const Text('Solde Portefeuille', style: TextStyle(color: Colors.white70, fontSize: 16, fontWeight: FontWeight.w500)),
                        const SizedBox(height: 8),
                        Text('${walletBalance.toStringAsFixed(2)} €', style: const TextStyle(color: Colors.white, fontSize: 36, fontWeight: FontWeight.w800, letterSpacing: -1)),
                        const SizedBox(height: 24),
                        Row(
                          children: [
                            _buildMiniSummary('Factures', '${invoices.length}'),
                            const SizedBox(width: 24),
                            _buildMiniSummary('Reçus', '${receipts.length}'),
                          ],
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(height: 32),
                  if (_selectedMonths.isEmpty && lateMonths.isNotEmpty) ...[
                    _buildSectionHeader('ATTENTION'),
                    const SizedBox(height: 12),
                    ...lateMonths.map((lm) {
                      final label = lm['label']?.toString() ?? '';
                      final rate = lm['penalty_rate'] ?? 0;
                      final penalty = lm['penalty'] ?? 0.0;
                      return Padding(
                        padding: const EdgeInsets.only(bottom: 12),
                        child: Container(
                          padding: const EdgeInsets.all(20),
                          decoration: BoxDecoration(
                            color: AppColors.warning.withValues(alpha: 0.1),
                            borderRadius: BorderRadius.circular(24),
                            border: Border.all(color: AppColors.warning.withValues(alpha: 0.2)),
                          ),
                          child: Row(
                            children: [
                              const Icon(Icons.warning_amber_rounded, color: AppColors.warning, size: 28),
                              const SizedBox(width: 16),
                              Expanded(
                                child: Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    Text('Retard : $label', style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 15, color: AppColors.warning)),
                                    Text('Pénalité de $rate% appliquée au loyer (+$penalty €).', style: const TextStyle(fontSize: 13, color: AppColors.textSecondary, fontWeight: FontWeight.w500)),
                                  ],
                                ),
                              ),
                            ],
                          ),
                        ),
                      );
                    }),
                    const SizedBox(height: 20),
                  ],

                  _buildSectionHeader('SÉLECTIONNEUR DE LOYERS'),
                  const SizedBox(height: 12),
                  GlassContainer(
                    padding: const EdgeInsets.all(24),
                    borderRadius: 32,
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        _buildYearGroup(context, '2026', _rentMonths.where((m) => m['key'].toString().startsWith('2026')).toList()),
                        const Padding(padding: EdgeInsets.symmetric(vertical: 20), child: Divider(height: 1, color: AppColors.background)),
                        _buildYearGroup(context, '2025', _rentMonths.where((m) => m['key'].toString().startsWith('2025')).toList()),
                      ],
                    ),
                  ),
                  const SizedBox(height: 32),

                  _buildSectionHeader('DERNIÈRES FACTURES'),
                  const SizedBox(height: 12),
                  SingleChildScrollView(
                    scrollDirection: Axis.horizontal,
                    physics: const BouncingScrollPhysics(),
                    child: Row(
                      children: [
                        _buildFilterChip('Tous', 'all'),
                        const SizedBox(width: 10),
                        _buildFilterChip('Payés', 'paid'),
                        const SizedBox(width: 10),
                        _buildFilterChip('En attente', 'pending'),
                        const SizedBox(width: 10),
                        _buildFilterChip('Retard', 'late'),
                      ],
                    ),
                  ),
                  const SizedBox(height: 16),
                  Column(
                    children: invoices.isEmpty
                        ? [
                            Padding(
                              padding: const EdgeInsets.all(24),
                              child: Text('Aucune facture disponible', style: TextStyle(color: AppColors.textSecondary)),
                            )
                          ]
                        : invoices.take(5).map((invoice) {
                            final status = invoice['status'] as String? ?? 'pending';
                            return Padding(
                              padding: const EdgeInsets.only(bottom: 12),
                              child: _buildInvoiceRow(
                                invoice['reference'] as String? ?? '---',
                                invoice['period'] as String? ?? '---',
                                '${(invoice['amount'] as num?)?.toStringAsFixed(2) ?? '0'} €',
                                status,
                              ),
                            );
                          }).toList(),
                  ),
                ],
              ),
            ),
          ),

          // Selection Floating Action
          if (_selectedMonths.isNotEmpty)
            Positioned(
              bottom: 120,
              left: 24,
              right: 24,
              child: Hero(
                tag: 'payment_bar',
                child: GlassContainer(
                  padding: const EdgeInsets.all(16),
                  borderRadius: 24,
                  blur: 30,
                  color: AppColors.primary.withValues(alpha: 0.9),
                  child: Row(
                    children: [
                      Expanded(
                        child: Column(
                          mainAxisSize: MainAxisSize.min,
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text('${_selectedMonths.length} mois sélectionnés', style: const TextStyle(color: Colors.white70, fontSize: 12, fontWeight: FontWeight.w600)),
                            Text('${_totalPaymentAmount.toStringAsFixed(2)} €', style: const TextStyle(color: Colors.white, fontSize: 20, fontWeight: FontWeight.w800)),
                          ],
                        ),
                      ),
                      ElevatedButton(
                        onPressed: _openPaymentModal,
                        style: ElevatedButton.styleFrom(
                          backgroundColor: Colors.white,
                          foregroundColor: AppColors.primary,
                          padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 12),
                          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                        ),
                        child: const Text('Régler', style: TextStyle(fontWeight: FontWeight.w800)),
                      ),
                    ],
                  ),
                ),
              ),
            ),

          if (_showPaymentModal)
            GestureDetector(
              onTap: _paymentFlowStep != 'success' ? () => setState(() => _showPaymentModal = false) : null,
              child: Container(
                color: Colors.black.withValues(alpha: 0.7),
                child: Center(
                  child: Padding(
                    padding: const EdgeInsets.symmetric(horizontal: 24),
                    child: AnimatedSwitcher(
                      duration: const Duration(milliseconds: 400),
                      child: _paymentFlowStep == 'recap'
                          ? _buildPaymentRecap(context)
                          : _paymentFlowStep == 'pin'
                              ? _buildPinModal(context)
                              : _buildSuccessAnimation(context),
                    ),
                  ),
                ),
              ),
            ),
        ],
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

  Widget _buildMiniSummary(String label, String value) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(label, style: const TextStyle(color: Colors.white54, fontSize: 12)),
        Text(value, style: const TextStyle(color: Colors.white, fontSize: 18, fontWeight: FontWeight.w700)),
      ],
    );
  }

  Widget _buildYearGroup(BuildContext context, String year, List<Map<String, dynamic>> months) {
    final paidCount = months.where((m) => m['status'] == 'paid').length;
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Text(year, style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 18, letterSpacing: -0.5)),
            Text('$paidCount/${months.length} payés', style: const TextStyle(color: AppColors.textSecondary, fontSize: 13, fontWeight: FontWeight.w600)),
          ],
        ),
        const SizedBox(height: 16),
        GridView.builder(
          shrinkWrap: true,
          physics: const NeverScrollableScrollPhysics(),
          gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
            crossAxisCount: 3,
            crossAxisSpacing: 12,
            mainAxisSpacing: 12,
            childAspectRatio: 0.85,
          ),
          itemCount: months.length,
          itemBuilder: (context, index) {
            final m = months[index];
            final key = m['key'] as String;
            final status = m['status'] as String;
            final isPaid = status == 'paid';
            final isSelected = _selectedMonths.contains(key);
            final hasPenalty = (m['penalty'] as int) > 0;

            Color bgColor;
            Color contentColor;
            String badgeText = '';

            if (isPaid) {
              bgColor = AppColors.success.withValues(alpha: 0.1);
              contentColor = AppColors.success;
              badgeText = 'PAYÉ';
            } else if (isSelected) {
              bgColor = AppColors.primary;
              contentColor = Colors.white;
              badgeText = 'SÉL.';
            } else if (hasPenalty) {
              bgColor = AppColors.warning.withValues(alpha: 0.1);
              contentColor = AppColors.warning;
              badgeText = '+15%';
            } else {
              bgColor = AppColors.background.withValues(alpha: 0.5);
              contentColor = AppColors.textSecondary;
            }

            return GestureDetector(
              onTap: isPaid ? null : () => _toggleMonth(key),
              child: AnimatedContainer(
                duration: const Duration(milliseconds: 200),
                decoration: BoxDecoration(
                  color: bgColor,
                  borderRadius: BorderRadius.circular(20),
                  border: isSelected ? null : Border.all(color: AppColors.textTertiary.withValues(alpha: 0.2)),
                ),
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    if (badgeText.isNotEmpty)
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
                        decoration: BoxDecoration(
                          color: isSelected ? Colors.white.withValues(alpha: 0.2) : contentColor.withValues(alpha: 0.1),
                          borderRadius: BorderRadius.circular(6),
                        ),
                        child: Text(badgeText, style: TextStyle(color: isSelected ? Colors.white : contentColor, fontSize: 9, fontWeight: FontWeight.w800)),
                      ),
                    const SizedBox(height: 8),
                    Text(m['label'] as String, style: TextStyle(fontWeight: FontWeight.w700, fontSize: 14, color: contentColor)),
                    const SizedBox(height: 4),
                    Text('${m['amount']}€', style: TextStyle(fontWeight: FontWeight.w800, fontSize: 12, color: isSelected ? Colors.white70 : AppColors.textTertiary)),
                  ],
                ),
              ),
            );
          },
        ),
      ],
    );
  }

  Widget _buildFilterChip(String label, String value) {
    final isActive = _invoiceFilter == value;
    return GestureDetector(
      onTap: () => setState(() => _invoiceFilter = value),
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

  Widget _buildInvoiceRow(String ref, String period, String amount, String status) {
    final isPaid = status == 'paid';
    final isLate = status == 'late';
    final statusColor = isPaid ? AppColors.success : (isLate ? AppColors.warning : AppColors.textSecondary);

    return GlassContainer(
      padding: const EdgeInsets.all(16),
      borderRadius: 24,
      child: Row(
        children: [
          Container(
            padding: const EdgeInsets.all(12),
            decoration: BoxDecoration(color: statusColor.withValues(alpha: 0.1), shape: BoxShape.circle),
            child: Icon(isPaid ? Icons.check_rounded : Icons.priority_high_rounded, color: statusColor, size: 20),
          ),
          const SizedBox(width: 16),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(period, style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 15)),
                Text(ref, style: const TextStyle(color: AppColors.textSecondary, fontSize: 12, fontWeight: FontWeight.w500)),
              ],
            ),
          ),
          Column(
            crossAxisAlignment: CrossAxisAlignment.end,
            children: [
              Text(amount, style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 16)),
              if (isPaid)
                const Icon(Icons.download_for_offline_rounded, color: AppColors.primary, size: 20)
              else
                Text(isLate ? 'Retard' : 'En attente', style: TextStyle(color: statusColor, fontSize: 11, fontWeight: FontWeight.w700)),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildPaymentRecap(BuildContext context) {
    return GlassContainer(
      key: const ValueKey('recap'),
      borderRadius: 32,
      padding: const EdgeInsets.all(28),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const Text('Confirmation', style: TextStyle(fontWeight: FontWeight.w800, fontSize: 24, letterSpacing: -0.5)),
              GestureDetector(
                onTap: () => setState(() => _showPaymentModal = false),
                child: Container(
                  padding: const EdgeInsets.all(4),
                  decoration: BoxDecoration(color: AppColors.background, shape: BoxShape.circle),
                  child: const Icon(Icons.close_rounded, size: 20),
                ),
              ),
            ],
          ),
          const SizedBox(height: 24),
          ..._selectedMonths.map((k) {
            final m = _rentMonths.firstWhere((x) => x['key'] == k);
            return Padding(
              padding: const EdgeInsets.only(bottom: 12),
              child: Row(
                children: [
                  Container(width: 10, height: 10, decoration: const BoxDecoration(shape: BoxShape.circle, color: AppColors.primary)),
                  const SizedBox(width: 16),
                  Text('Loyer ${m['label']}', style: const TextStyle(fontSize: 16, fontWeight: FontWeight.w600)),
                  const Spacer(),
                  Text('${m['amount']} €', style: const TextStyle(fontWeight: FontWeight.w700, fontSize: 16)),
                ],
              ),
            );
          }),
          const SizedBox(height: 16),
          const Divider(height: 1, color: AppColors.background),
          const SizedBox(height: 16),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const Text('Total à régler', style: TextStyle(fontWeight: FontWeight.w600, fontSize: 16, color: AppColors.textSecondary)),
              Text('${_totalPaymentAmount.toStringAsFixed(2)} €', style: const TextStyle(fontWeight: FontWeight.w900, fontSize: 24, color: AppColors.primary)),
            ],
          ),
          const SizedBox(height: 24),
          Container(
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              color: AppColors.primary.withValues(alpha: 0.05),
              borderRadius: BorderRadius.circular(20),
            ),
            child: Row(
              children: [
                const Icon(Icons.account_balance_wallet_rounded, color: AppColors.primary),
                const SizedBox(width: 12),
                const Expanded(
                  child: Text('Débit depuis Portefeuille', style: TextStyle(fontWeight: FontWeight.w700, fontSize: 14)),
                ),
              ],
            ),
          ),
          const SizedBox(height: 24),
          SizedBox(
            height: 60,
            child: ElevatedButton(
              onPressed: _confirmPayment,
              style: ElevatedButton.styleFrom(
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
              ),
              child: const Text('Confirmer le paiement', style: TextStyle(fontWeight: FontWeight.w800, fontSize: 16)),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildPinModal(BuildContext context) {
    return GlassContainer(
      key: const ValueKey('pin'),
      borderRadius: 32,
      padding: const EdgeInsets.all(32),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          const Text('Code PIN', style: TextStyle(fontWeight: FontWeight.w800, fontSize: 24, letterSpacing: -0.5)),
          const SizedBox(height: 8),
          const Text('Sécurisez votre transaction', style: TextStyle(color: AppColors.textSecondary, fontWeight: FontWeight.w500)),
          const SizedBox(height: 32),
          Row(
            mainAxisAlignment: MainAxisAlignment.center,
            children: List.generate(4, (i) {
              final filled = _pinInput.length > i;
              return Container(
                margin: const EdgeInsets.symmetric(horizontal: 10),
                width: 20, height: 20,
                decoration: BoxDecoration(
                  shape: BoxShape.circle,
                  color: filled ? AppColors.primary : AppColors.background,
                  border: !filled ? Border.all(color: AppColors.textTertiary.withValues(alpha: 0.5), width: 2) : null,
                ),
              );
            }),
          ),
          if (_pinError) ...[
            const SizedBox(height: 16),
            const Text('Code incorrect', style: TextStyle(color: AppColors.error, fontWeight: FontWeight.w700)),
          ],
          const SizedBox(height: 32),
          SizedBox(
            width: 150,
            child: TextField(
              autofocus: true,
              maxLength: 4,
              obscureText: true,
              keyboardType: TextInputType.number,
              textAlign: TextAlign.center,
              style: const TextStyle(fontSize: 32, letterSpacing: 20, fontWeight: FontWeight.w800),
              decoration: const InputDecoration(counterText: '', border: InputBorder.none, filled: false),
              onChanged: (v) {
                setState(() { _pinInput = v; _pinError = false; });
                if (v.length == 4) { _submitPin(); }
              },
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildSuccessAnimation(BuildContext context) {
    return GlassContainer(
      key: const ValueKey('success'),
      borderRadius: 32,
      padding: const EdgeInsets.all(40),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          Container(
            width: 100, height: 100,
            decoration: BoxDecoration(color: AppColors.success.withValues(alpha: 0.1), shape: BoxShape.circle),
            child: const Icon(Icons.check_rounded, color: AppColors.success, size: 60),
          ),
          const SizedBox(height: 24),
          const Text('Succès !', style: TextStyle(fontWeight: FontWeight.w900, fontSize: 28, letterSpacing: -0.5)),
          const SizedBox(height: 8),
          const Text('Votre paiement a été validé.', style: TextStyle(color: AppColors.textSecondary, fontWeight: FontWeight.w500)),
        ],
      ),
    );
  }
}
