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

class _OldContractsPlaceholder extends StatefulWidget {
  const _OldContractsPlaceholder();

  @override
  State<_OldContractsPlaceholder> createState() => _OldContractsPlaceholderState();
}

class _OldContractsPlaceholderState extends State<_OldContractsPlaceholder> {
  String _searchQuery = '';
  final _searchCtrl = TextEditingController();
  Map<String, dynamic>? _selectedContract;

  @override
  void dispose() {
    _searchCtrl.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final api = context.watch<ApiService>();
    final oldContracts = api.oldContracts;

    List<dynamic> filtered = oldContracts.where((c) {
      if (_searchQuery.isEmpty) return true;
      final q = _searchQuery.toLowerCase();
      final prop = (c['property_name']?.toString() ?? c['logement']?['name']?.toString() ?? '').toLowerCase();
      final addr = (c['address']?.toString() ?? c['logement']?['address']?.toString() ?? '').toLowerCase();
      return prop.contains(q) || addr.contains(q);
    }).toList();

    return SafeArea(
      bottom: false,
      child: Stack(
        children: [
          SingleChildScrollView(
            padding: const EdgeInsets.fromLTRB(24, 24, 24, 120),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.stretch,
              children: [
                Text('Anciens Contrats', style: Theme.of(context).textTheme.headlineMedium?.copyWith(fontSize: 28)),
                const SizedBox(height: 8),
                Text('Consultez vos anciens baux', style: TextStyle(color: AppColors.textSecondary, fontSize: 15)),
                const SizedBox(height: 20),
                GlassContainer(
                  padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 4),
                  borderRadius: 20,
                  child: TextField(
                    controller: _searchCtrl,
                    decoration: const InputDecoration(
                      hintText: 'Rechercher...',
                      prefixIcon: Icon(Icons.search_rounded, size: 20),
                      border: InputBorder.none, isDense: true,
                    ),
                    onChanged: (v) => setState(() => _searchQuery = v),
                  ),
                ),
                const SizedBox(height: 24),
                if (filtered.isEmpty)
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
                  )
                else
                  ...filtered.map((oc) => Padding(
                    padding: const EdgeInsets.only(bottom: 16),
                    child: _buildOldContractCard(context, oc, api),
                  )),
              ],
            ),
          ),
          if (_selectedContract != null) _buildDetailOverlay(_selectedContract!),
        ],
      ),
    );
  }

  Widget _buildOldContractCard(BuildContext context, Map<String, dynamic> oc, ApiService api) {
    final property = oc['property_name']?.toString() ?? oc['logement']?['name']?.toString() ?? 'Logement';
    final address = oc['address']?.toString() ?? oc['logement']?['address']?.toString() ?? '';
    final start = oc['start_date']?.toString() ?? oc['debut']?.toString() ?? '---';
    final end = oc['end_date']?.toString() ?? oc['fin']?.toString() ?? '---';
    final rent = oc['loyer'] is num ? (oc['loyer'] as num).toDouble() : 0;
    final deposit = oc['caution'] is num ? (oc['caution'] as num).toDouble() : 0;
    final docs = oc['documents'] as List<dynamic>? ?? [];

    return GlassContainer(
      padding: const EdgeInsets.all(18),
      borderRadius: 24,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Container(
                padding: const EdgeInsets.all(10),
                decoration: BoxDecoration(color: AppColors.textSecondary.withValues(alpha: 0.1), borderRadius: BorderRadius.circular(12)),
                child: const Icon(Icons.home_work_rounded, color: AppColors.textSecondary, size: 22),
              ),
              const SizedBox(width: 14),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(property, style: const TextStyle(fontWeight: FontWeight.w700, fontSize: 16)),
                    if (address.isNotEmpty) Text(address, style: const TextStyle(color: AppColors.textSecondary, fontSize: 13)),
                  ],
                ),
              ),
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 4),
                decoration: BoxDecoration(color: AppColors.textSecondary.withValues(alpha: 0.1), borderRadius: BorderRadius.circular(10)),
                child: const Text('Terminé', style: TextStyle(color: AppColors.textSecondary, fontSize: 11, fontWeight: FontWeight.w600)),
              ),
            ],
          ),
          const SizedBox(height: 14),
          Row(
            children: [
              Text('$start — $end', style: const TextStyle(color: AppColors.textSecondary, fontSize: 13, fontWeight: FontWeight.w500)),
              const Spacer(),
              Text('${docs.length} doc(s)', style: const TextStyle(color: AppColors.textTertiary, fontSize: 12)),
            ],
          ),
          const SizedBox(height: 14),
          Row(
            children: [
              GestureDetector(
                onTap: () => setState(() => _selectedContract = oc),
                child: Container(
                  padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                  decoration: BoxDecoration(color: AppColors.primary.withValues(alpha: 0.1), borderRadius: BorderRadius.circular(16)),
                  child: const Text('Détails', style: TextStyle(color: AppColors.primary, fontWeight: FontWeight.w700, fontSize: 13)),
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildDetailOverlay(Map<String, dynamic> oc) {
    final property = oc['property_name']?.toString() ?? oc['logement']?['name']?.toString() ?? 'Logement';
    final address = oc['address']?.toString() ?? oc['logement']?['address']?.toString() ?? '';
    final start = oc['start_date']?.toString() ?? oc['debut']?.toString() ?? '---';
    final end = oc['end_date']?.toString() ?? oc['fin']?.toString() ?? '---';
    final rent = oc['loyer'] is num ? (oc['loyer'] as num).toDouble() : 0;
    final deposit = oc['caution'] is num ? (oc['caution'] as num).toDouble() : 0;
    final owner = oc['proprietaire']?.toString() ?? oc['owner']?.toString() ?? 'Non renseigné';
    final docs = oc['documents'] as List<dynamic>? ?? [];

    return Positioned.fill(
      child: Container(
        color: Colors.black.withValues(alpha: 0.6),
        child: Center(
          child: SingleChildScrollView(
            padding: const EdgeInsets.all(24),
            child: GlassContainer(
              padding: const EdgeInsets.all(24),
              borderRadius: 32,
              child: Column(
                mainAxisSize: MainAxisSize.min,
                crossAxisAlignment: CrossAxisAlignment.stretch,
                children: [
                  Row(mainAxisAlignment: MainAxisAlignment.spaceBetween, children: [
                    Expanded(child: Text(property, style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 20))),
                    GestureDetector(onTap: () => setState(() => _selectedContract = null), child: const Icon(Icons.close_rounded)),
                  ]),
                  if (address.isNotEmpty) ...[
                    const SizedBox(height: 4),
                    Text(address, style: const TextStyle(color: AppColors.textSecondary, fontSize: 14)),
                  ],
                  const SizedBox(height: 20),
                  _detailRow('Période', '$start — $end'),
                  _detailRow('Propriétaire', owner),
                  _detailRow('Loyer', '${rent.toStringAsFixed(0)} €/mois'),
                  _detailRow('Caution', '${deposit.toStringAsFixed(0)} €'),
                  if (docs.isNotEmpty) ...[
                    const SizedBox(height: 16),
                    const Text('Documents', style: TextStyle(fontWeight: FontWeight.w700, fontSize: 15)),
                    const SizedBox(height: 8),
                    ...docs.map((d) => Padding(
                      padding: const EdgeInsets.only(bottom: 6),
                      child: Row(
                        children: [
                          const Icon(Icons.description_rounded, size: 16, color: AppColors.primary),
                          const SizedBox(width: 8),
                          Expanded(child: Text(d['name']?.toString() ?? 'Document', style: const TextStyle(fontSize: 13))),
                          const Icon(Icons.download_rounded, size: 16, color: AppColors.textSecondary),
                        ],
                      ),
                    )),
                  ],
                  const SizedBox(height: 24),
                  SizedBox(
                    height: 48,
                    child: ElevatedButton(
                      onPressed: () => setState(() => _selectedContract = null),
                      child: const Text('Fermer', style: TextStyle(fontWeight: FontWeight.w800)),
                    ),
                  ),
                ],
              ),
            ),
          ),
        ),
      ),
    );
  }

  Widget _detailRow(String label, String value) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 6),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(label, style: const TextStyle(color: AppColors.textSecondary, fontSize: 14)),
          Text(value, style: const TextStyle(fontWeight: FontWeight.w700, fontSize: 14)),
        ],
      ),
    );
  }
}

class DashboardView extends StatefulWidget {
  const DashboardView({super.key});

  @override
  State<DashboardView> createState() => _DashboardViewState();
}

class _DashboardViewState extends State<DashboardView> {
  bool _showTransferModal = false;
  final _transferAmountCtrl = TextEditingController();
  final _transferMemoCtrl = TextEditingController();

  @override
  void dispose() {
    _transferAmountCtrl.dispose();
    _transferMemoCtrl.dispose();
    super.dispose();
  }

  void _executeTransfer() async {
    final amount = double.tryParse(_transferAmountCtrl.text.replaceAll(',', '.'));
    if (amount == null || amount <= 0) return;
    await context.read<ApiService>().transferFunds(amount, _transferMemoCtrl.text.trim());
    setState(() => _showTransferModal = false);
    _transferAmountCtrl.clear();
    _transferMemoCtrl.clear();
    if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Transfert de $amount € effectué'), backgroundColor: AppColors.success, behavior: SnackBarBehavior.floating, shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16))),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    final apiService = Provider.of<ApiService>(context);
    final userName = apiService.user?['name'] ?? 'Utilisateur';
    final firstName = userName.split(' ')[0];
    final walletBalance = apiService.walletBalance;
    final contracts = apiService.contracts;
    final transactions = apiService.transactions;
    final rentMonths = apiService.rentMonths;

    double currentRent = 0;
    if (contracts.isNotEmpty) {
      final rentValue = contracts[0]['loyer'];
      if (rentValue is num) {
        currentRent = rentValue.toDouble();
      } else if (rentValue is String) {
        currentRent = double.tryParse(rentValue) ?? 0;
      }
    }

    // Find late months with penalties
    final lateMonths = rentMonths.where((m) {
      final penaltyRate = m['penalty_rate'];
      return penaltyRate is num && penaltyRate > 0;
    }).toList();

    return SafeArea(
      bottom: false,
      child: Stack(
        children: [
          SingleChildScrollView(
            physics: const BouncingScrollPhysics(),
            padding: const EdgeInsets.fromLTRB(24, 24, 24, 140),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
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
                          Row(
                            children: [
                              if (walletBalance != null)
                                GestureDetector(
                                  onTap: () => setState(() => _showTransferModal = true),
                                  child: Container(
                                    margin: const EdgeInsets.only(right: 8),
                                    padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
                                    decoration: BoxDecoration(color: Colors.white.withValues(alpha: 0.2), borderRadius: BorderRadius.circular(16)),
                                    child: const Text('Transférer', style: TextStyle(color: Colors.white, fontWeight: FontWeight.w800, fontSize: 13)),
                                  ),
                                ),
                              if (walletBalance == null)
                                GestureDetector(
                                  onTap: () => _showCreateWalletModal(context),
                                  child: Container(
                                    padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 12),
                                    decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(16)),
                                    child: const Text('Créer', style: TextStyle(color: Colors.black, fontWeight: FontWeight.w800, fontSize: 13)),
                                  ),
                                )
                              else
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
                    ],
                  ),
                ),
                const SizedBox(height: 32),

                if (lateMonths.isNotEmpty)
                  Padding(
                    padding: const EdgeInsets.only(bottom: 24),
                    child: Container(
                      padding: const EdgeInsets.all(16),
                      decoration: BoxDecoration(
                        color: AppColors.warning.withValues(alpha: 0.1),
                        borderRadius: BorderRadius.circular(20),
                        border: Border.all(color: AppColors.warning.withValues(alpha: 0.3)),
                      ),
                      child: Row(
                        children: [
                          const Icon(Icons.warning_amber_rounded, color: AppColors.warning, size: 28),
                          const SizedBox(width: 12),
                          Expanded(
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text('${lateMonths.length} mois en retard', style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 15, color: AppColors.warning)),
                                Text('Des pénalités sont appliquées.', style: const TextStyle(fontSize: 13, color: AppColors.textSecondary, fontWeight: FontWeight.w500)),
                              ],
                            ),
                          ),
                        ],
                      ),
                    ),
                  ),

                _buildSectionHeader('VUE D\'ENSEMBLE'),
                const SizedBox(height: 12),
                Row(
                  children: [
                    Expanded(child: _buildKpiCard(context, 'Loyer Mensuel', '${currentRent.toStringAsFixed(0)} €', Icons.house_rounded, AppColors.primary)),
                    const SizedBox(width: 16),
                    Expanded(child: _buildKpiCard(context, 'Solde Wallet', walletBalance != null ? '${walletBalance!.toStringAsFixed(0)} €' : 'Non activé', Icons.account_balance_wallet_rounded, walletBalance != null ? AppColors.success : AppColors.textSecondary)),
                  ],
                ),
                const SizedBox(height: 16),
                Row(
                  children: [
                    Expanded(child: _buildKpiCard(context, 'Solde Dû', '${(apiService.totalDue ?? 0).toStringAsFixed(0)} €', Icons.warning_rounded, AppColors.warning)),
                    const SizedBox(width: 16),
                    Expanded(child: _buildKpiCard(context, 'Tickets', '${apiService.openTicketsCount}', Icons.support_agent_rounded, AppColors.error)),
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
                        : transactions.take(5).map((tx) {
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

          if (_showTransferModal) _buildTransferOverlay(),
        ],
      ),
    );
  }

  Widget _buildTransferOverlay() {
    return Positioned.fill(
      child: Container(
        color: Colors.black.withValues(alpha: 0.6),
        child: Center(
          child: SingleChildScrollView(
            padding: const EdgeInsets.all(24),
            child: GlassContainer(
              padding: const EdgeInsets.all(24),
              borderRadius: 32,
              child: Column(
                mainAxisSize: MainAxisSize.min,
                crossAxisAlignment: CrossAxisAlignment.stretch,
                children: [
                  Row(mainAxisAlignment: MainAxisAlignment.spaceBetween, children: [
                    const Text('Transférer des fonds', style: TextStyle(fontWeight: FontWeight.w800, fontSize: 22)),
                    GestureDetector(onTap: () => setState(() => _showTransferModal = false), child: const Icon(Icons.close_rounded)),
                  ]),
                  const SizedBox(height: 20),
                  TextField(
                    controller: _transferAmountCtrl,
                    keyboardType: TextInputType.number,
                    decoration: const InputDecoration(labelText: 'Montant (€)', prefixIcon: Icon(Icons.euro_rounded)),
                  ),
                  const SizedBox(height: 14),
                  TextField(
                    controller: _transferMemoCtrl,
                    decoration: const InputDecoration(labelText: 'Motif (optionnel)', prefixIcon: Icon(Icons.notes_rounded)),
                  ),
                  const SizedBox(height: 24),
                  SizedBox(height: 56, child: ElevatedButton(onPressed: _executeTransfer, child: const Text('Transférer', style: TextStyle(fontWeight: FontWeight.w800)))),
                ],
              ),
            ),
          ),
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

  void _showCreateWalletModal(BuildContext context) {
    final _pinController = TextEditingController();
    
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
            const Text('Créer votre portefeuille', style: TextStyle(fontWeight: FontWeight.w700, fontSize: 20)),
            const SizedBox(height: 8),
            const Text('Définissez un code PIN pour sécuriser vos paiements.', style: TextStyle(color: AppColors.textSecondary, fontSize: 14)),
            const SizedBox(height: 24),
            TextField(
              controller: _pinController,
              keyboardType: TextInputType.number,
              maxLength: 6,
              obscureText: true,
              decoration: const InputDecoration(
                labelText: 'Code PIN',
                hintText: 'Entrez un code à 4-6 chiffres',
                prefixIcon: Icon(Icons.lock_rounded),
              ),
            ),
            const SizedBox(height: 24),
            SizedBox(
              height: 56,
              child: ElevatedButton(
                onPressed: () async {
                  if (_pinController.text.length >= 4) {
                    final apiService = context.read<ApiService>();
                    final success = await apiService.createWallet(_pinController.text);
                    if (ctx.mounted) {
                      Navigator.pop(ctx);
                      ScaffoldMessenger.of(context).showSnackBar(
                        SnackBar(
                          content: Text(success ? 'Portefeuille créé avec succès' : 'Erreur lors de la création'),
                          backgroundColor: success ? AppColors.success : AppColors.error,
                          behavior: SnackBarBehavior.floating,
                          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                        ),
                      );
                    }
                  }
                },
                child: const Text('Créer le portefeuille'),
              ),
            ),
          ],
        ),
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
