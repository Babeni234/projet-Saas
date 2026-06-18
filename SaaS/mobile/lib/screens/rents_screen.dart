import 'package:flutter/material.dart';
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
  final List<Map<String, dynamic>> _rentMonths = [
    {'key': '2026-06', 'label': 'Juin', 'amount': 850.0, 'status': 'pending', 'penalty': 0},
    {'key': '2026-05', 'label': 'Mai', 'amount': 850.0, 'status': 'paid', 'penalty': 0},
    {'key': '2026-04', 'label': 'Avril', 'amount': 850.0, 'status': 'paid', 'penalty': 0},
    {'key': '2026-03', 'label': 'Mars', 'amount': 970.0, 'status': 'paid', 'penalty': 120},
    {'key': '2026-02', 'label': 'Février', 'amount': 850.0, 'status': 'paid', 'penalty': 0},
    {'key': '2026-01', 'label': 'Janvier', 'amount': 850.0, 'status': 'paid', 'penalty': 0},
    {'key': '2025-12', 'label': 'Décembre', 'amount': 850.0, 'status': 'paid', 'penalty': 0},
    {'key': '2025-11', 'label': 'Novembre', 'amount': 850.0, 'status': 'paid', 'penalty': 0},
    {'key': '2025-10', 'label': 'Octobre', 'amount': 850.0, 'status': 'paid', 'penalty': 0},
    {'key': '2025-09', 'label': 'Septembre', 'amount': 850.0, 'status': 'paid', 'penalty': 0},
    {'key': '2025-08', 'label': 'Août', 'amount': 850.0, 'status': 'paid', 'penalty': 0},
    {'key': '2025-07', 'label': 'Juillet', 'amount': 850.0, 'status': 'paid', 'penalty': 0},
  ];

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

  void _submitPin() {
    if (_pinInput.length == 4) {
      setState(() => _paymentFlowStep = 'success');
      Future.delayed(const Duration(seconds: 2), () {
        if (mounted) { setState(() {
          _showPaymentModal = false;
          _selectedMonths.clear();
        }); }
      });
    } else {
      setState(() => _pinError = true);
    }
  }

  @override
  Widget build(BuildContext context) {
    return SafeArea(
      bottom: false,
      child: Stack(
        children: [
          SingleChildScrollView(
            padding: const EdgeInsets.fromLTRB(24, 24, 24, 120),
            child: AnimatedBuilder(
              animation: _animationController,
              builder: (context, child) {
                return Opacity(
                  opacity: _fadeAnimation.value,
                  child: Transform.translate(
                    offset: Offset(0, 20 * (1 - _fadeAnimation.value)),
                    child: child,
                  ),
                );
              },
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.stretch,
                children: [
                  Text('Loyer & Finances', style: Theme.of(context).textTheme.headlineMedium?.copyWith(fontSize: 28)),
                  const SizedBox(height: 8),
                  Text('Réglez vos loyers successifs et factures en ligne.', style: Theme.of(context).textTheme.bodyMedium?.copyWith(fontSize: 15)),
                  const SizedBox(height: 28),

                  Row(
                    children: [
                      Expanded(child: _buildFinSummaryCard('Total réglé 12 mois', '10 200 €', Icons.trending_up_rounded, AppColors.success)),
                      const SizedBox(width: 12),
                      Expanded(child: _buildFinSummaryCard('Mois payés', '11/12', Icons.calendar_month_rounded, AppColors.primary)),
                    ],
                  ),
                  const SizedBox(height: 12),
                  Row(
                    children: [
                      Expanded(child: _buildFinSummaryCard('En attente', '1', Icons.hourglass_empty_rounded, Colors.amber)),
                      const SizedBox(width: 12),
                      Expanded(child: _buildFinSummaryCard('Portefeuille', '1 450 €', Icons.account_balance_wallet_rounded, AppColors.success)),
                    ],
                  ),
                  const SizedBox(height: 28),

                  Container(
                    padding: const EdgeInsets.all(16),
                    decoration: BoxDecoration(
                      color: Colors.amber.withValues(alpha: 0.08),
                      borderRadius: BorderRadius.circular(20),
                      border: Border.all(color: Colors.amber.withValues(alpha: 0.2)),
                    ),
                    child: Row(
                      children: [
                        Icon(Icons.warning_amber_rounded, color: AppColors.warning, size: 24),
                        const SizedBox(width: 14),
                        Expanded(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              const Text('Pénalité de retard — Mars 2026', style: TextStyle(fontWeight: FontWeight.w700, fontSize: 14)),
                              const SizedBox(height: 4),
                              const Text('Loyer: 850€ • Pénalité 15%: 120€ • Total dû: 970€', style: TextStyle(fontSize: 12, color: AppColors.textSecondary)),
                              const SizedBox(height: 6),
                              Container(
                                padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                                decoration: BoxDecoration(color: AppColors.warning.withValues(alpha: 0.15), borderRadius: BorderRadius.circular(8)),
                                child: const Text('Barème: J+11→5%  J+16→10%  J+21→15%', style: TextStyle(fontSize: 10, color: AppColors.warning, fontWeight: FontWeight.w600)),
                              ),
                            ],
                          ),
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(height: 28),

                  GlassContainer(
                    padding: const EdgeInsets.all(20),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.stretch,
                      children: [
                        const Text('Sélectionneur de loyers successifs', style: TextStyle(fontWeight: FontWeight.w700, fontSize: 17)),
                        const SizedBox(height: 6),
                        const Text('Les mois sont affichés selon votre contrat. Réglez dans l\'ordre chronologique.', style: TextStyle(color: AppColors.textSecondary, fontSize: 12)),
                        const SizedBox(height: 20),

                        _buildYearGroup(context, '2026', _rentMonths.where((m) => m['key'].toString().startsWith('2026')).toList()),
                        const SizedBox(height: 20),
                        _buildYearGroup(context, '2025', _rentMonths.where((m) => m['key'].toString().startsWith('2025')).toList()),

                        if (_selectedMonths.isNotEmpty) ...[
                          const SizedBox(height: 20),
                          Container(
                            padding: const EdgeInsets.all(16),
                            decoration: BoxDecoration(
                              color: AppColors.primary.withValues(alpha: 0.06),
                              borderRadius: BorderRadius.circular(16),
                            ),
                            child: Column(
                              children: [
                                Row(
                                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                  children: [
                                    Text('${_selectedMonths.length} mois sélectionnés', style: const TextStyle(fontWeight: FontWeight.w600)),
                                    Text('Total: ${_totalPaymentAmount.toStringAsFixed(2)} €', style: const TextStyle(fontWeight: FontWeight.w800, color: AppColors.primary, fontSize: 18)),
                                  ],
                                ),
                                const SizedBox(height: 14),
                                SizedBox(
                                  width: double.infinity,
                                  height: 50,
                                  child: ElevatedButton.icon(
                                    onPressed: _openPaymentModal,
                                    icon: const Icon(Icons.account_balance_wallet_rounded, size: 20),
                                    label: const Text('Payer via Portefeuille', style: TextStyle(fontWeight: FontWeight.w700)),
                                  ),
                                ),
                              ],
                            ),
                          ),
                        ],
                      ],
                    ),
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
                        _buildFilterChip('Payés', 'paid'),
                        const SizedBox(width: 8),
                        _buildFilterChip('En attente', 'pending'),
                        const SizedBox(width: 8),
                        _buildFilterChip('En retard', 'late'),
                      ],
                    ),
                  ),
                  const SizedBox(height: 16),

                  _buildInvoiceRow('QUIT-2026-05', 'Mai 2026', '850,00 €', 'paid'),
                  const SizedBox(height: 10),
                  _buildInvoiceRow('QUIT-2026-04', 'Avril 2026', '850,00 €', 'paid'),
                  const SizedBox(height: 10),
                  _buildInvoiceRow('QUIT-2026-03', 'Mars 2026', '970,00 €', 'late'),
                  const SizedBox(height: 10),
                  _buildInvoiceRow('QUIT-2026-02', 'Février 2026', '850,00 €', 'paid'),
                ],
              ),
            ),
          ),

          if (_showPaymentModal)
            GestureDetector(
              onTap: _paymentFlowStep != 'success' ? () => setState(() => _showPaymentModal = false) : null,
              child: Container(
                color: Colors.black.withValues(alpha: 0.5),
                child: Center(
                  child: AnimatedSwitcher(
                    duration: const Duration(milliseconds: 300),
                    child: _paymentFlowStep == 'recap'
                        ? _buildPaymentRecap(context)
                        : _paymentFlowStep == 'pin'
                            ? _buildPinModal(context)
                            : _buildSuccessAnimation(context),
                  ),
                ),
              ),
            ),
        ],
      ),
    );
  }

  Widget _buildPaymentRecap(BuildContext context) {
    return GlassContainer(
      key: const ValueKey('recap'),
      borderRadius: 28,
      padding: const EdgeInsets.all(24),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const Text('Confirmation de paiement', style: TextStyle(fontWeight: FontWeight.w700, fontSize: 18)),
              GestureDetector(onTap: () => setState(() => _showPaymentModal = false), child: const Icon(Icons.close_rounded)),
            ],
          ),
          const SizedBox(height: 4),
          const Text('Vérifiez les détails avant de valider', style: TextStyle(color: AppColors.textSecondary, fontSize: 13)),
          const SizedBox(height: 20),
          const Divider(height: 1, color: AppColors.background),
          const SizedBox(height: 16),
          ..._selectedMonths.map((k) {
            final m = _rentMonths.firstWhere((x) => x['key'] == k);
            return Padding(
              padding: const EdgeInsets.only(bottom: 8),
              child: Row(
                children: [
                  Container(width: 8, height: 8, decoration: const BoxDecoration(shape: BoxShape.circle, color: AppColors.primary)),
                  const SizedBox(width: 12),
                  Text(m['label'] as String, style: const TextStyle(fontSize: 14)),
                  const Spacer(),
                  Text('${m['amount']} €', style: const TextStyle(fontWeight: FontWeight.w600)),
                ],
              ),
            );
          }),
          const SizedBox(height: 12),
          const Divider(height: 1, color: AppColors.background),
          const SizedBox(height: 12),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const Text('Frais', style: TextStyle(color: AppColors.textSecondary)),
              const Text('Gratuit', style: TextStyle(color: AppColors.success, fontWeight: FontWeight.w600)),
            ],
          ),
          const SizedBox(height: 8),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const Text('Montant à débiter', style: TextStyle(fontWeight: FontWeight.w700, fontSize: 16)),
              Text('${_totalPaymentAmount.toStringAsFixed(2)} €', style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 18, color: AppColors.primary)),
            ],
          ),
          const SizedBox(height: 16),
          Container(
            padding: const EdgeInsets.all(12),
            decoration: BoxDecoration(
              color: AppColors.success.withValues(alpha: 0.06),
              borderRadius: BorderRadius.circular(14),
            ),
            child: Row(
              children: [
                Icon(Icons.account_balance_wallet_rounded, color: AppColors.success, size: 20),
                const SizedBox(width: 10),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Text('Paiement depuis', style: TextStyle(fontSize: 11, color: AppColors.textSecondary)),
                      const Text('Portefeuille HABITATUM', style: TextStyle(fontWeight: FontWeight.w600, fontSize: 13)),
                    ],
                  ),
                ),
                Icon(Icons.check_circle_rounded, color: AppColors.success, size: 24),
              ],
            ),
          ),
          const SizedBox(height: 20),
          Row(
            children: [
              Expanded(
                child: OutlinedButton(
                  onPressed: () => setState(() => _showPaymentModal = false),
                  style: OutlinedButton.styleFrom(
                    padding: const EdgeInsets.symmetric(vertical: 16),
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                  ),
                  child: const Text('Annuler', style: TextStyle(fontWeight: FontWeight.w600)),
                ),
              ),
              const SizedBox(width: 12),
              Expanded(
                flex: 2,
                child: ElevatedButton.icon(
                  onPressed: _confirmPayment,
                  icon: const Icon(Icons.lock_rounded, size: 18),
                  label: const Text('Confirmer le paiement', style: TextStyle(fontWeight: FontWeight.w700)),
                  style: ElevatedButton.styleFrom(
                    padding: const EdgeInsets.symmetric(vertical: 16),
                  ),
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildPinModal(BuildContext context) {
    return GlassContainer(
      key: const ValueKey('pin'),
      borderRadius: 28,
      padding: const EdgeInsets.all(28),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          Container(
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              color: AppColors.primary.withValues(alpha: 0.1),
              shape: BoxShape.circle,
            ),
            child: const Icon(Icons.lock_rounded, color: AppColors.primary, size: 32),
          ),
          const SizedBox(height: 20),
          const Text('Code de sécurité', style: TextStyle(fontWeight: FontWeight.w700, fontSize: 20)),
          const SizedBox(height: 8),
          Text('Saisissez votre code secret à 4 chiffres', style: TextStyle(color: AppColors.textSecondary, fontSize: 14)),
          const SizedBox(height: 24),
          Row(
            mainAxisAlignment: MainAxisAlignment.center,
            children: List.generate(4, (i) {
              final filled = _pinInput.length > i;
              return Container(
                margin: const EdgeInsets.symmetric(horizontal: 8),
                width: 18, height: 18,
                decoration: BoxDecoration(
                  shape: BoxShape.circle,
                  color: filled ? AppColors.primary : AppColors.background,
                  border: !filled ? Border.all(color: AppColors.textTertiary, width: 2) : null,
                ),
                child: filled ? Center(child: Container(
                  width: 8, height: 8,
                  decoration: const BoxDecoration(shape: BoxShape.circle, color: Colors.white),
                )) : null,
              );
            }),
          ),
          if (_pinError) ...[
            const SizedBox(height: 12),
            const Text('Code incorrect. Veuillez réessayer.', style: TextStyle(color: AppColors.error, fontSize: 13)),
          ],
          const SizedBox(height: 24),
          SizedBox(
            height: 56,
            child: TextField(
              autofocus: true,
              maxLength: 4,
              obscureText: true,
              keyboardType: TextInputType.number,
              textAlign: TextAlign.center,
              style: const TextStyle(fontSize: 28, letterSpacing: 16, fontWeight: FontWeight.w700),
              decoration: InputDecoration(
                counterText: '',
                filled: true,
                fillColor: AppColors.background,
                border: OutlineInputBorder(borderRadius: BorderRadius.circular(16), borderSide: BorderSide.none),
              ),
              onChanged: (v) {
                setState(() {
                  _pinInput = v;
                  _pinError = false;
                });
                if (v.length == 4) { _submitPin(); }
              },
            ),
          ),
          const SizedBox(height: 20),
          Row(
            children: [
              Expanded(
                child: OutlinedButton(
                  onPressed: () => setState(() => _showPaymentModal = false),
                  style: OutlinedButton.styleFrom(
                    padding: const EdgeInsets.symmetric(vertical: 14),
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                  ),
                  child: const Text('Annuler', style: TextStyle(fontWeight: FontWeight.w600)),
                ),
              ),
              const SizedBox(width: 12),
              Expanded(
                flex: 2,
                child: ElevatedButton(
                  onPressed: _pinInput.length == 4 ? _submitPin : null,
                  style: ElevatedButton.styleFrom(
                    padding: const EdgeInsets.symmetric(vertical: 14),
                  ),
                  child: const Text('Valider', style: TextStyle(fontWeight: FontWeight.w700)),
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildSuccessAnimation(BuildContext context) {
    return GlassContainer(
      key: const ValueKey('success'),
      borderRadius: 28,
      padding: const EdgeInsets.all(36),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          Container(
            width: 80, height: 80,
            decoration: BoxDecoration(
              gradient: const LinearGradient(colors: [Color(0xFFEEF2FF), Color(0xFFE0E7FF)]),
              shape: BoxShape.circle,
              boxShadow: [
                BoxShadow(
                  color: AppColors.primary.withValues(alpha: 0.3),
                  blurRadius: 25,
                  offset: const Offset(0, 8),
                ),
              ],
            ),
            child: const Icon(Icons.check_circle_rounded, color: AppColors.primary, size: 48),
          ),
          const SizedBox(height: 24),
          const Text('Paiement réussi !', style: TextStyle(fontWeight: FontWeight.w800, fontSize: 24, color: AppColors.primary)),
          const SizedBox(height: 8),
          Text('${_totalPaymentAmount.toStringAsFixed(2)} € débité de votre portefeuille', style: TextStyle(color: AppColors.textSecondary, fontSize: 15)),
          const SizedBox(height: 24),
          const CircularProgressIndicator(color: AppColors.primary),
        ],
      ),
    );
  }

  Widget _buildFinSummaryCard(String label, String value, IconData icon, Color color) {
    return GlassContainer(
      padding: const EdgeInsets.all(16),
      borderRadius: 20,
      child: Row(
        children: [
          Container(
            padding: const EdgeInsets.all(8),
            decoration: BoxDecoration(color: color.withValues(alpha: 0.12), shape: BoxShape.circle),
            child: Icon(icon, color: color, size: 18),
          ),
          const SizedBox(width: 10),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(value, style: TextStyle(fontWeight: FontWeight.w800, fontSize: 16, color: color)),
                Text(label, style: const TextStyle(fontSize: 10, color: AppColors.textSecondary)),
              ],
            ),
          ),
        ],
      ),
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
            Text(year, style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 16)),
            Text('$paidCount/${months.length} payés', style: const TextStyle(color: AppColors.textSecondary, fontSize: 13)),
          ],
        ),
        const SizedBox(height: 12),
        Wrap(
          spacing: 10,
          runSpacing: 10,
          children: months.map((m) {
            final key = m['key'] as String;
            final status = m['status'] as String;
            final isPaid = status == 'paid';
            final isSelected = _selectedMonths.contains(key);
            final hasPenalty = (m['penalty'] as int) > 0;

            Color cardColor;
            Color textColor;
            String badge;
            Color badgeColor;

            if (isPaid) {
              cardColor = AppColors.success.withValues(alpha: 0.08);
              textColor = AppColors.success;
              badge = 'Payé';
              badgeColor = AppColors.success;
            } else if (isSelected) {
              cardColor = AppColors.primary.withValues(alpha: 0.12);
              textColor = AppColors.primary;
              badge = 'Sélectionné';
              badgeColor = AppColors.primary;
            } else if (hasPenalty) {
              cardColor = AppColors.warning.withValues(alpha: 0.08);
              textColor = AppColors.warning;
              badge = '+15%';
              badgeColor = AppColors.warning;
            } else {
              cardColor = AppColors.textTertiary.withValues(alpha: 0.12);
              textColor = AppColors.textSecondary;
              badge = 'À régler';
              badgeColor = AppColors.textSecondary;
            }

            return GestureDetector(
              onTap: isPaid ? null : () => _toggleMonth(key),
              child: AnimatedContainer(
                duration: const Duration(milliseconds: 200),
                width: (MediaQuery.of(context).size.width - 90) / 3,
                padding: const EdgeInsets.all(14),
                decoration: BoxDecoration(
                  color: isSelected ? cardColor : Colors.transparent,
                  borderRadius: BorderRadius.circular(16),
                  border: Border.all(
                    color: isSelected ? AppColors.primary.withValues(alpha: 0.3) : AppColors.textTertiary.withValues(alpha: 0.15),
                    width: isSelected ? 1.5 : 1,
                  ),
                ),
                child: Column(
                  children: [
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Text(m['label'] as String, style: TextStyle(fontWeight: FontWeight.w600, fontSize: 13, color: textColor)),
                        Container(
                          padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
                          decoration: BoxDecoration(
                            color: badgeColor.withValues(alpha: 0.15),
                            borderRadius: BorderRadius.circular(6),
                          ),
                          child: Text(badge, style: TextStyle(fontSize: 9, fontWeight: FontWeight.w700, color: badgeColor)),
                        ),
                      ],
                    ),
                    const SizedBox(height: 8),
                    Text('${m['amount']} €', style: TextStyle(fontWeight: FontWeight.w700, fontSize: 15, color: textColor)),
                    if (hasPenalty && !isPaid)
                      Text('+${m['penalty']}€', style: const TextStyle(fontSize: 11, color: AppColors.warning, fontWeight: FontWeight.w600)),
                  ],
                ),
              ),
            );
          }).toList(),
        ),
      ],
    );
  }

  Widget _buildFilterChip(String label, String value) {
    final isActive = _invoiceFilter == value;
    return GestureDetector(
      onTap: () => setState(() => _invoiceFilter = value),
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 7),
        decoration: BoxDecoration(
          color: isActive ? AppColors.primary : Colors.transparent,
          borderRadius: BorderRadius.circular(14),
        ),
        child: Text(label, style: TextStyle(
          color: isActive ? Colors.white : AppColors.textSecondary,
          fontWeight: FontWeight.w600,
          fontSize: 12,
        )),
      ),
    );
  }

  Widget _buildInvoiceRow(String ref, String period, String amount, String status) {
    final isPaid = status == 'paid';
    final isLate = status == 'late';
    final statusColor = isPaid ? AppColors.success : (isLate ? AppColors.warning : AppColors.textSecondary);
    final statusLabel = isPaid ? 'Payé' : (isLate ? 'En retard' : 'En attente');

    return GlassContainer(
      padding: const EdgeInsets.all(16),
      borderRadius: 18,
      child: Row(
        children: [
          Container(
            padding: const EdgeInsets.all(8),
            decoration: BoxDecoration(
              color: statusColor.withValues(alpha: 0.12),
              shape: BoxShape.circle,
            ),
            child: Icon(isPaid ? Icons.check_circle_rounded : Icons.access_time_rounded, color: statusColor, size: 20),
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
                    Text('$amount ', style: const TextStyle(fontWeight: FontWeight.w700, fontSize: 15)),
                  ],
                ),
                const SizedBox(height: 6),
                Row(
                  children: [
                    Text(period, style: const TextStyle(color: AppColors.textSecondary, fontSize: 12)),
                    const SizedBox(width: 10),
                    Container(
                      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 3),
                      decoration: BoxDecoration(
                        color: statusColor.withValues(alpha: 0.12),
                        borderRadius: BorderRadius.circular(10),
                      ),
                      child: Text(statusLabel, style: TextStyle(color: statusColor, fontSize: 11, fontWeight: FontWeight.w600)),
                    ),
                    const Spacer(),
                    if (isPaid)
                      Icon(Icons.download_rounded, color: AppColors.primary, size: 20),
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
