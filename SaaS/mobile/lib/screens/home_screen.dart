import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/theme_provider.dart';
import '../services/api_service.dart';
import '../theme/app_colors.dart';
import '../widgets/glass_container.dart';
import '../widgets/glass_bottom_nav.dart';
import '../widgets/app_drawer.dart';
import 'property_screen.dart';
import 'rents_screen.dart';
import 'profile_screen.dart';
import 'utilities_screen.dart';
import 'receipts_screen.dart';
import 'support_screen.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  int _currentScreenIndex = 0;
  final _scaffoldKey = GlobalKey<ScaffoldState>();

  final List<Widget> _screens = [
    const DashboardView(),
    const PropertyScreen(),
    const RentsScreen(),
    const UtilitiesScreen(),
    const ReceiptsScreen(),
    const _OldContractsPlaceholder(),
    const SupportScreen(),
    const ProfileScreen(),
  ];

  static const _screenToBottomNav = {0: 0, 2: 1, 6: 2, 7: 3};

  int _getBottomNavIndex() => _screenToBottomNav[_currentScreenIndex] ?? -1;

  void _onDrawerTap(int index) {
    setState(() => _currentScreenIndex = index);
  }

  void _onBottomNavTap(int index) {
    const map = [0, 2, 6, 7];
    if (index < map.length) {
      setState(() => _currentScreenIndex = map[index]);
    }
  }

  @override
  Widget build(BuildContext context) {
    final bottomNavIdx = _getBottomNavIndex();
    final themeProvider = context.watch<ThemeProvider>();
    return Scaffold(
      key: _scaffoldKey,
      extendBody: true,
      drawer: AppDrawer(
        activeIndex: _currentScreenIndex,
        onItemTap: _onDrawerTap,
        isDark: themeProvider.isDark,
      ),
      body: Stack(
        fit: StackFit.expand,
        children: [
          Positioned(
            top: -100,
            right: -100,
            child: Container(
              width: 300,
              height: 300,
              decoration: BoxDecoration(
                shape: BoxShape.circle,
                color: AppColors.primaryLight.withValues(alpha: 0.15),
              ),
            ),
          ),
          Positioned(
            bottom: 100,
            left: -100,
            child: Container(
              width: 250,
              height: 250,
              decoration: BoxDecoration(
                shape: BoxShape.circle,
                color: AppColors.secondary.withValues(alpha: 0.1),
              ),
            ),
          ),
          Positioned.fill(
            child: AnimatedSwitcher(
              duration: const Duration(milliseconds: 400),
              transitionBuilder: (child, animation) {
                return FadeTransition(opacity: animation, child: child);
              },
              child: KeyedSubtree(
                key: ValueKey(_currentScreenIndex),
                child: _screens[_currentScreenIndex],
              ),
            ),
          ),
          GlassBottomNav(
            currentIndex: bottomNavIdx >= 0 ? bottomNavIdx : 0,
            onTap: _onBottomNavTap,
          ),
        ],
      ),
    );
  }
}

class _OldContractsPlaceholder extends StatelessWidget {
  const _OldContractsPlaceholder();

  @override
  Widget build(BuildContext context) {
    return SafeArea(
      bottom: false,
      child: SingleChildScrollView(
        padding: const EdgeInsets.fromLTRB(24, 24, 24, 120),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            Text('Anciens Contrats', style: Theme.of(context).textTheme.headlineMedium?.copyWith(fontSize: 28)),
            const SizedBox(height: 24),
            GlassContainer(
              padding: const EdgeInsets.all(28),
              borderRadius: 24,
              child: Column(
                children: [
                  Icon(Icons.history_rounded, size: 48, color: AppColors.textSecondary.withValues(alpha: 0.5)),
                  const SizedBox(height: 16),
                  const Text('Aucun ancien contrat', style: TextStyle(color: AppColors.textSecondary, fontSize: 16, fontWeight: FontWeight.w600)),
                  const SizedBox(height: 8),
                  const Text('Vos anciens baux apparaîtront ici.', style: TextStyle(color: AppColors.textTertiary, fontSize: 14)),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}

class DashboardView extends StatelessWidget {
  const DashboardView({super.key});

  @override
  Widget build(BuildContext context) {
    final apiService = Provider.of<ApiService>(context);
    final userName = apiService.user?['name'] ?? 'Utilisateur';
    final firstName = userName.split(' ')[0];
    final walletBalance = apiService.walletBalance;
    final contracts = apiService.contracts;
    final transactions = apiService.transactions;

    // Calculate current rent from first contract
    double currentRent = 0;
    if (contracts.isNotEmpty) {
      currentRent = (contracts[0]['rent'] as num?)?.toDouble() ?? 0;
    }

    return SafeArea(
      bottom: false,
      child: SingleChildScrollView(
        physics: const BouncingScrollPhysics(),
        padding: const EdgeInsets.fromLTRB(24, 24, 24, 140),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Modern Header
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text('Habitatum', style: TextStyle(color: AppColors.primary, fontWeight: FontWeight.w900, fontSize: 14, letterSpacing: 2)),
                    const SizedBox(height: 2),
                    Text('Bonjour, $firstName', style: Theme.of(context).textTheme.headlineMedium?.copyWith(fontSize: 32, fontWeight: FontWeight.w800, letterSpacing: -1)),
                  ],
                ),
                Builder(
                  builder: (ctx) => GestureDetector(
                    onTap: () => Scaffold.maybeOf(ctx)?.openDrawer(),
                    child: Container(
                      width: 52, height: 52,
                      decoration: BoxDecoration(
                        color: AppColors.primary.withValues(alpha: 0.1),
                        shape: BoxShape.circle,
                      ),
                      child: const Icon(Icons.menu_rounded, color: AppColors.primary, size: 28),
                    ),
                  ),
                ),
              ],
            ),
            const SizedBox(height: 32),

            // Premium Wallet Card
            Container(
              width: double.infinity,
              padding: const EdgeInsets.all(32),
              decoration: BoxDecoration(
                gradient: AppColors.walletGradient,
                borderRadius: BorderRadius.circular(36),
                boxShadow: [
                  BoxShadow(color: Colors.black.withValues(alpha: 0.3), blurRadius: 40, offset: const Offset(0, 20)),
                ],
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      const Text('PORTÉFEUILLE', style: TextStyle(color: Colors.white54, fontSize: 12, fontWeight: FontWeight.w800, letterSpacing: 1.5)),
                      Icon(Icons.nfc_rounded, color: Colors.white.withValues(alpha: 0.4)),
                    ],
                  ),
                  const SizedBox(height: 12),
                  Text(walletBalance != null ? '${walletBalance.toStringAsFixed(2)} €' : '--- €', style: const TextStyle(color: Colors.white, fontSize: 42, fontWeight: FontWeight.w900, letterSpacing: -1.5)),
                  const SizedBox(height: 32),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text('STATUT COMPTE', style: TextStyle(color: Colors.white38, fontSize: 10, fontWeight: FontWeight.w700)),
                          Text(walletBalance != null ? 'Actif' : 'Non activé', style: const TextStyle(color: Colors.white, fontSize: 14, fontWeight: FontWeight.w600)),
                        ],
                      ),
                      GestureDetector(
                        onTap: () => _showRechargeModal(context),
                        child: Container(
                          padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 12),
                          decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(16)),
                          child: const Text('Recharger', style: TextStyle(color: Colors.black, fontWeight: FontWeight.w800, fontSize: 13)),
                        ),
                      ),
                    ],
                  ),
                ],
              ),
            ),
            const SizedBox(height: 32),

            _buildSectionHeader('VUE D\'ENSEMBLE'),
            const SizedBox(height: 12),
            Row(
              children: [
                Expanded(child: _buildKpiCard(context, 'Loyer Mensuel', '${currentRent.toStringAsFixed(0)} €', Icons.house_rounded, AppColors.primary)),
                const SizedBox(width: 16),
                Expanded(child: _buildKpiCard(context, 'Factures', '${apiService.invoices.length}', Icons.receipt_long_rounded, AppColors.warning)),
              ],
            ),
            const SizedBox(height: 16),
            Row(
              children: [
                Expanded(child: _buildKpiCard(context, 'Reçus', '${apiService.receipts.length}', Icons.description_rounded, AppColors.success)),
                const SizedBox(width: 16),
                Expanded(child: _buildKpiCard(context, 'Contrats', '${contracts.length}', Icons.assignment_rounded, AppColors.primaryLight)),
              ],
            ),
            const SizedBox(height: 32),

            _buildSectionHeader('ACTIONS RAPIDES'),
            const SizedBox(height: 12),
            SingleChildScrollView(
              scrollDirection: Axis.horizontal,
              physics: const BouncingScrollPhysics(),
              child: Row(
                children: [
                  _buildQuickAction(context, Icons.receipt_long_rounded, 'Payer', AppColors.primary),
                  const SizedBox(width: 16),
                  _buildQuickAction(context, Icons.build_rounded, 'Signaler', AppColors.warning),
                  const SizedBox(width: 16),
                  _buildQuickAction(context, Icons.history_rounded, 'Historique', AppColors.success),
                  const SizedBox(width: 16),
                  _buildQuickAction(context, Icons.chat_bubble_rounded, 'Support', AppColors.primaryLight),
                ],
              ),
            ),
            const SizedBox(height: 32),

            _buildSectionHeader('ACTIVITÉS RÉCENTES'),
            const SizedBox(height: 12),
            GlassContainer(
              padding: const EdgeInsets.symmetric(vertical: 8),
              borderRadius: 32,
              child: Column(
                children: transactions.isEmpty
                    ? [
                        Padding(
                          padding: const EdgeInsets.all(24),
                          child: Text('Aucune transaction récente', style: TextStyle(color: AppColors.textSecondary)),
                        )
                      ]
                    : transactions.take(3).map((tx) {
                        final isCredit = tx['type'] == 'credit' || tx['type'] == 'recharge';
                        return Column(
                          children: [
                            _buildTransactionItem(
                              context,
                              tx['description'] ?? 'Transaction',
                              '${isCredit ? '+' : '-'}${(tx['amount'] as num).toStringAsFixed(2)} €',
                              tx['date'] != null ? _formatDate(tx['date']) : '',
                              isCredit ? Icons.add_circle_rounded : Icons.remove_circle_rounded,
                              isCredit ? AppColors.success : AppColors.error,
                            ),
                            if (tx != transactions.last) const Padding(padding: EdgeInsets.symmetric(horizontal: 24), child: Divider(height: 1, color: AppColors.background)),
                          ],
                        );
                      }).toList(),
              ),
            ),
          ],
        ),
      ),
    );
  }

  String _formatDate(String dateString) {
    try {
      final date = DateTime.parse(dateString);
      return '${date.day}/${date.month}/${date.year}';
    } catch (e) {
      return dateString;
    }
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

  Widget _buildQuickAction(BuildContext context, IconData icon, String label, Color color) {
    return Column(
      children: [
        GlassContainer(
          padding: const EdgeInsets.all(20),
          borderRadius: 24,
          child: Icon(icon, color: color, size: 28),
        ),
        const SizedBox(height: 10),
        Text(label, style: const TextStyle(fontSize: 12, fontWeight: FontWeight.w700)),
      ],
    );
  }

  Widget _buildKpiCard(BuildContext context, String title, String value, IconData icon, Color color) {
    return GlassContainer(
      padding: const EdgeInsets.all(24),
      borderRadius: 28,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            padding: const EdgeInsets.all(10),
            decoration: BoxDecoration(color: color.withValues(alpha: 0.1), borderRadius: BorderRadius.circular(12)),
            child: Icon(icon, color: color, size: 22),
          ),
          const SizedBox(height: 20),
          Text(title, style: const TextStyle(color: AppColors.textSecondary, fontSize: 13, fontWeight: FontWeight.w600)),
          const SizedBox(height: 4),
          Text(value, style: TextStyle(fontWeight: FontWeight.w800, fontSize: 20, color: color, letterSpacing: -0.5)),
        ],
      ),
    );
  }

  Widget _buildTransactionItem(BuildContext context, String label, String amount, String date, IconData icon, Color color) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      child: Row(
        children: [
          Container(
            padding: const EdgeInsets.all(12),
            decoration: BoxDecoration(color: color.withValues(alpha: 0.1), shape: BoxShape.circle),
            child: Icon(icon, color: color, size: 20),
          ),
          const SizedBox(width: 16),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(label, style: const TextStyle(fontWeight: FontWeight.w700, fontSize: 15)),
                Text(date, style: const TextStyle(color: AppColors.textSecondary, fontSize: 13, fontWeight: FontWeight.w500)),
              ],
            ),
          ),
          Text(amount, style: TextStyle(fontWeight: FontWeight.w800, fontSize: 16, color: color)),
        ],
      ),
    );
  }

  void _showRechargeModal(BuildContext context) {
    double? selectedAmount;
    
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (ctx) => StatefulBuilder(
        builder: (context, setModalState) => Container(
          padding: const EdgeInsets.fromLTRB(24, 16, 24, 40),
          decoration: BoxDecoration(
            color: Theme.of(context).scaffoldBackgroundColor,
            borderRadius: const BorderRadius.vertical(top: Radius.circular(32)),
          ),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              Center(
                child: Container(
                  width: 40, height: 4,
                  decoration: BoxDecoration(color: AppColors.textTertiary.withValues(alpha: 0.4), borderRadius: BorderRadius.circular(2)),
                ),
              ),
              const SizedBox(height: 20),
              const Text('Recharger le portefeuille', style: TextStyle(fontWeight: FontWeight.w700, fontSize: 20)),
              const SizedBox(height: 8),
              const Text('Choisissez un montant à ajouter à votre portefeuille.', style: TextStyle(color: AppColors.textSecondary, fontSize: 14)),
              const SizedBox(height: 24),
              Row(
                children: [
                  Expanded(child: _rechargeAmountChip('500 €', 500, selectedAmount, (amount) => setModalState(() => selectedAmount = amount))),
                  const SizedBox(width: 12),
                  Expanded(child: _rechargeAmountChip('1 000 €', 1000, selectedAmount, (amount) => setModalState(() => selectedAmount = amount))),
                ],
              ),
              const SizedBox(height: 12),
              Row(
                children: [
                  Expanded(child: _rechargeAmountChip('2 500 €', 2500, selectedAmount, (amount) => setModalState(() => selectedAmount = amount))),
                  const SizedBox(width: 12),
                  Expanded(child: _rechargeAmountChip('5 000 €', 5000, selectedAmount, (amount) => setModalState(() => selectedAmount = amount))),
                ],
              ),
              const SizedBox(height: 24),
              SizedBox(
                height: 56,
                child: ElevatedButton(
                  onPressed: selectedAmount != null ? () async {
                    final apiService = context.read<ApiService>();
                    final success = await apiService.rechargeWallet(selectedAmount!);
                    if (ctx.mounted) {
                      Navigator.pop(ctx);
                      ScaffoldMessenger.of(context).showSnackBar(
                        SnackBar(
                          content: Text(success ? 'Recharge effectuée avec succès' : 'Erreur lors de la recharge'),
                          backgroundColor: success ? AppColors.success : AppColors.error,
                          behavior: SnackBarBehavior.floating,
                          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                        ),
                      );
                    }
                  } : null,
                  child: const Text('Confirmer la recharge'),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _rechargeAmountChip(String label, double amount, double? selectedAmount, Function(double) onTap) {
    final isSelected = selectedAmount == amount;
    return GestureDetector(
      onTap: () => onTap(amount),
      child: Container(
        padding: const EdgeInsets.symmetric(vertical: 16),
        decoration: BoxDecoration(
          color: isSelected ? AppColors.primary : AppColors.primary.withValues(alpha: 0.08),
          borderRadius: BorderRadius.circular(16),
          border: Border.all(color: isSelected ? AppColors.primary : AppColors.primary.withValues(alpha: 0.2)),
        ),
        child: Text(label, textAlign: TextAlign.center, style: TextStyle(
          fontWeight: FontWeight.w700, fontSize: 15, color: isSelected ? Colors.white : AppColors.primary,
        )),
      ),
    );
  }


}
