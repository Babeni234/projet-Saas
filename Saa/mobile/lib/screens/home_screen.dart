import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/theme_provider.dart';
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
    return SafeArea(
      bottom: false,
      child: SingleChildScrollView(
        padding: const EdgeInsets.fromLTRB(24, 24, 24, 120),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      'Bonjour,',
                      style: Theme.of(context).textTheme.bodyLarge?.copyWith(color: AppColors.textSecondary),
                    ),
                    Text(
                      'Thomas Dubois',
                      style: Theme.of(context).textTheme.headlineMedium?.copyWith(fontSize: 24),
                    ),
                  ],
                ),
                Builder(
                  builder: (ctx) => GestureDetector(
                    onTap: () => Scaffold.maybeOf(ctx)?.openDrawer(),
                    child: Container(
                      width: 48,
                      height: 48,
                      decoration: BoxDecoration(
                        gradient: AppColors.actionGradient,
                        shape: BoxShape.circle,
                        boxShadow: [
                          BoxShadow(
                            color: AppColors.primary.withValues(alpha: 0.3),
                            blurRadius: 12,
                            offset: const Offset(0, 4),
                          ),
                        ],
                      ),
                      child: const Icon(Icons.menu_rounded, color: Colors.white, size: 24),
                    ),
                  ),
                ),
              ],
            ),
            const SizedBox(height: 8),
            Text(
              'Voici l\'état de votre location',
              style: Theme.of(context).textTheme.bodyMedium?.copyWith(fontSize: 15),
            ),
            const SizedBox(height: 28),

            Container(
              height: 200,
              decoration: BoxDecoration(
                gradient: AppColors.walletGradient,
                borderRadius: BorderRadius.circular(32),
                boxShadow: [
                  BoxShadow(
                    color: Colors.black.withValues(alpha: 0.3),
                    blurRadius: 30,
                    offset: const Offset(0, 15),
                  ),
                ],
              ),
              padding: const EdgeInsets.all(28),
              child: Stack(
                children: [
                  Positioned(
                    right: -20, top: -20,
                    child: Container(
                      width: 100, height: 100,
                      decoration: BoxDecoration(
                        shape: BoxShape.circle,
                        color: Colors.white.withValues(alpha: 0.1),
                      ),
                    ),
                  ),
                  Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          const Text('Mon Portefeuille', style: TextStyle(color: Colors.white70, fontSize: 16, fontWeight: FontWeight.w500)),
                          Icon(Icons.contactless_rounded, color: Colors.white.withValues(alpha: 0.8)),
                        ],
                      ),
                      const Text(
                        '1 450,00 €',
                        style: TextStyle(color: Colors.white, fontSize: 42, fontWeight: FontWeight.w800, letterSpacing: -1),
                      ),
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          Flexible(child: Text('Habitatum Premium', style: const TextStyle(color: Colors.white54, fontSize: 14), overflow: TextOverflow.ellipsis)),
              GestureDetector(
                onTap: () => _showRechargeModal(context),
                child: Container(
                  padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                  decoration: BoxDecoration(
                    color: Colors.white.withValues(alpha: 0.2),
                    borderRadius: BorderRadius.circular(20),
                  ),
                  child: const Text('Recharger', style: TextStyle(color: Colors.white, fontWeight: FontWeight.w600, fontSize: 13)),
                ),
              ),
                        ],
                      ),
                    ],
                  ),
                ],
              ),
            ),
            const SizedBox(height: 28),

            Text('Aperçu', style: Theme.of(context).textTheme.titleLarge),
            const SizedBox(height: 16),
            Row(
              children: [
                Expanded(child: _buildKpiCard(context, 'Loyer mensuel', '850 €', Icons.receipt_long_rounded, AppColors.primary, 'À jour')),
                const SizedBox(width: 12),
                Expanded(child: _buildKpiCard(context, 'Portefeuille', '1 450 €', Icons.account_balance_wallet_rounded, AppColors.success, 'Solde')),
              ],
            ),
            const SizedBox(height: 12),
            Row(
              children: [
                Expanded(child: _buildKpiCard(context, 'Solde dû total', '0 €', Icons.payments_rounded, Colors.amber, 'À jour')),
                const SizedBox(width: 12),
                Expanded(child: _buildKpiCard(context, 'Tickets', '1 en cours', Icons.support_agent_rounded, AppColors.warning, 'En cours')),
              ],
            ),
            const SizedBox(height: 28),

            Text('Actions Rapides', style: Theme.of(context).textTheme.titleLarge),
            const SizedBox(height: 16),
            SingleChildScrollView(
              scrollDirection: Axis.horizontal,
              child: Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  _buildQuickAction(context, Icons.receipt_long_rounded, 'Payer\nLoyer', AppColors.primary),
                  const SizedBox(width: 12),
                  _buildQuickAction(context, Icons.build_rounded, 'Signaler\nProblème', AppColors.warning),
                  const SizedBox(width: 12),
                  _buildQuickAction(context, Icons.description_rounded, 'Mes\nReçus', AppColors.primary),
                  const SizedBox(width: 12),
                  _buildQuickAction(context, Icons.chat_bubble_rounded, 'Support', AppColors.success),
                ],
              ),
            ),
            const SizedBox(height: 28),

            Text('Mouvements récents', style: Theme.of(context).textTheme.titleLarge),
            const SizedBox(height: 16),
            _buildTransactionItem(context, 'Crédit Portefeuille', '+200,00 €', 'Recharge', Icons.add_circle_rounded, AppColors.success, 'Orange Money • 15 Juin'),
            const Divider(height: 24, color: AppColors.background),
            _buildTransactionItem(context, 'Paiement Loyer Mai', '-850,00 €', 'Paiement', Icons.remove_circle_rounded, Colors.amber, 'Wallet • 05 Mai'),
            const Divider(height: 24, color: AppColors.background),
            _buildTransactionItem(context, 'Crédit Portefeuille', '+500,00 €', 'Recharge', Icons.add_circle_rounded, AppColors.success, 'Visa • 28 Avril'),
          ],
        ),
      ),
    );
  }

  Widget _buildQuickAction(BuildContext context, IconData icon, String label, Color color) {
    return Column(
      children: [
        GlassContainer(
          padding: const EdgeInsets.all(16),
          borderRadius: 24,
          child: Icon(icon, color: color, size: 28),
        ),
        const SizedBox(height: 8),
        Text(label, textAlign: TextAlign.center, style: const TextStyle(fontSize: 12, fontWeight: FontWeight.w600)),
      ],
    );
  }

  Widget _buildKpiCard(BuildContext context, String title, String value, IconData icon, Color color, String badge) {
    return GlassContainer(
      padding: const EdgeInsets.all(18),
      borderRadius: 22,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Container(
                padding: const EdgeInsets.all(8),
                decoration: BoxDecoration(color: color.withValues(alpha: 0.12), shape: BoxShape.circle),
                child: Icon(icon, color: color, size: 20),
              ),
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                decoration: BoxDecoration(color: badge == 'À jour' ? AppColors.success.withValues(alpha: 0.12) : AppColors.warning.withValues(alpha: 0.12), borderRadius: BorderRadius.circular(10)),
                child: Text(badge, style: TextStyle(color: badge == 'À jour' ? AppColors.success : AppColors.warning, fontSize: 10, fontWeight: FontWeight.w600)),
              ),
            ],
          ),
          const SizedBox(height: 14),
          Text(title, style: const TextStyle(color: AppColors.textSecondary, fontSize: 12)),
          const SizedBox(height: 4),
          Text(value, style: TextStyle(fontWeight: FontWeight.w800, fontSize: 20, color: color)),
        ],
      ),
    );
  }

  void _showRechargeModal(BuildContext context) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (ctx) => Container(
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
                Expanded(child: _rechargeAmountChip('500 CFA', () {})),
                const SizedBox(width: 12),
                Expanded(child: _rechargeAmountChip('1 000 CFA', () {})),
              ],
            ),
            const SizedBox(height: 12),
            Row(
              children: [
                Expanded(child: _rechargeAmountChip('2 500 CFA', () {})),
                const SizedBox(width: 12),
                Expanded(child: _rechargeAmountChip('5 000 CFA', () {})),
              ],
            ),
            const SizedBox(height: 12),
            Row(
              children: [
                Expanded(child: _rechargeAmountChip('10 000 CFA', () {})),
                const SizedBox(width: 12),
                Expanded(child: _rechargeAmountChip('25 000 CFA', () {})),
              ],
            ),
            const SizedBox(height: 24),
            SizedBox(
              height: 56,
              child: ElevatedButton(
                onPressed: () {
                  Navigator.pop(ctx);
                  ScaffoldMessenger.of(context).showSnackBar(
                    SnackBar(
                      content: const Text('Recharge en cours de traitement...'),
                      behavior: SnackBarBehavior.floating,
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                    ),
                  );
                },
                child: const Text('Recharger maintenant'),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _rechargeAmountChip(String label, VoidCallback onTap) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        padding: const EdgeInsets.symmetric(vertical: 16),
        decoration: BoxDecoration(
          color: AppColors.primary.withValues(alpha: 0.08),
          borderRadius: BorderRadius.circular(16),
          border: Border.all(color: AppColors.primary.withValues(alpha: 0.2)),
        ),
        child: Text(label, textAlign: TextAlign.center, style: const TextStyle(
          fontWeight: FontWeight.w700, fontSize: 15, color: AppColors.primary,
        )),
      ),
    );
  }

  Widget _buildTransactionItem(BuildContext context, String label, String amount, String type, IconData icon, Color color, String meta) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 4),
      child: Row(
        children: [
          Container(
            padding: const EdgeInsets.all(10),
            decoration: BoxDecoration(color: color.withValues(alpha: 0.12), shape: BoxShape.circle),
            child: Icon(icon, color: color, size: 20),
          ),
          const SizedBox(width: 16),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(label, style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 15)),
                Text(meta, style: const TextStyle(color: AppColors.textSecondary, fontSize: 12)),
              ],
            ),
          ),
          Text(amount, style: TextStyle(fontWeight: FontWeight.w700, fontSize: 16, color: color)),
        ],
      ),
    );
  }
}
