import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:flutter/services.dart';
import 'dart:async';
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
import 'wallet_validation_screen.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  int _currentScreenIndex = 0;
  final _scaffoldKey = GlobalKey<ScaffoldState>();

  late final List<Widget> _screens;

  @override
  void initState() {
    super.initState();
    _screens = [
      DashboardView(onNavigate: (index) {
        setState(() {
          _currentScreenIndex = index;
        });
      }),
      const PropertyScreen(),
      const RentsScreen(),
      const UtilitiesScreen(),
      const ReceiptsScreen(),
      const _OldContractsPlaceholder(),
      const SupportScreen(),
      const ProfileScreen(),
    ];
  }

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
  final Function(int)? onNavigate;
  const DashboardView({super.key, this.onNavigate});

  @override
  State<DashboardView> createState() => _DashboardViewState();
}

class _DashboardViewState extends State<DashboardView> {
  bool _showTransferModal = false;
  final _transferAmountCtrl = TextEditingController();
  final _transferMemoCtrl = TextEditingController();

  bool _hideBalance = true;
  Timer? _balanceRevealTimer;

  @override
  void dispose() {
    _transferAmountCtrl.dispose();
    _transferMemoCtrl.dispose();
    _balanceRevealTimer?.cancel();
    super.dispose();
  }

  void _toggleBalanceVisibility() {
    if (!_hideBalance) {
      setState(() {
        _hideBalance = true;
      });
      _balanceRevealTimer?.cancel();
      return;
    }
    _showPinGateDialog('Afficher le solde', () {
      setState(() {
        _hideBalance = false;
      });
      _balanceRevealTimer?.cancel();
      _balanceRevealTimer = Timer(const Duration(seconds: 15), () {
        if (mounted) {
          setState(() {
            _hideBalance = true;
          });
        }
      });
    });
  }

  void _showPinGateDialog(String label, VoidCallback onSuccess) {
    final pinCtrl = TextEditingController();
    bool pinError = false;

    showDialog(
      context: context,
      barrierDismissible: true,
      builder: (ctx) => StatefulBuilder(
        builder: (context, setDialogState) => Dialog(
          backgroundColor: Colors.transparent,
          child: GlassContainer(
            padding: const EdgeInsets.all(28),
            borderRadius: 32,
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                const Icon(Icons.lock_outline_rounded, color: AppColors.primary, size: 40),
                const SizedBox(height: 16),
                Text(
                  label,
                  style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                ),
                const SizedBox(height: 8),
                const Text(
                  'Entrez votre code PIN secret (1234)',
                  style: TextStyle(color: AppColors.textSecondary, fontSize: 13),
                  textAlign: TextAlign.center,
                ),
                const SizedBox(height: 24),
                TextField(
                  controller: pinCtrl,
                  autofocus: true,
                  keyboardType: TextInputType.number,
                  obscureText: true,
                  maxLength: 4,
                  textAlign: TextAlign.center,
                  style: const TextStyle(fontSize: 24, letterSpacing: 8, fontWeight: FontWeight.bold),
                  decoration: InputDecoration(
                    counterText: '',
                    errorText: pinError ? 'Code PIN incorrect' : null,
                  ),
                  onChanged: (val) {
                    if (val.length == 4) {
                      if (val == '1234') {
                        Navigator.pop(ctx);
                        onSuccess();
                      } else {
                        setDialogState(() {
                          pinCtrl.clear();
                          pinError = true;
                        });
                        HapticFeedback.heavyImpact();
                      }
                    }
                  },
                ),
                const SizedBox(height: 20),
                TextButton(
                  onPressed: () => Navigator.pop(ctx),
                  child: const Text('Annuler', style: TextStyle(color: AppColors.textSecondary)),
                ),
              ],
            ),
          ),
        ),
      ),
    );
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
                          if (walletBalance != null)
                            GestureDetector(
                              onTap: _toggleBalanceVisibility,
                              child: Text(
                                _hideBalance ? 'Afficher' : 'Masquer',
                                style: const TextStyle(color: Colors.white70, fontSize: 11, fontWeight: FontWeight.bold, decoration: TextDecoration.underline),
                              ),
                            )
                          else
                            Icon(Icons.nfc_rounded, color: Colors.white.withValues(alpha: 0.4)),
                        ],
                      ),
                      const SizedBox(height: 12),
                      Text(
                        walletBalance == null
                            ? '--- €'
                            : (_hideBalance ? '•••••• €' : '${walletBalance.toStringAsFixed(2)} €'),
                        style: const TextStyle(color: Colors.white, fontSize: 42, fontWeight: FontWeight.w900, letterSpacing: -1.5),
                      ),
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
                      _buildQuickAction(context, Icons.receipt_long_rounded, 'Payer', AppColors.primary, () => widget.onNavigate?.call(2)),
                      const SizedBox(width: 16),
                      _buildQuickAction(context, Icons.security_rounded, 'Valider', AppColors.primary, () {
                        Navigator.push(context, MaterialPageRoute(builder: (_) => const WalletValidationScreen()));
                      }),
                      const SizedBox(width: 16),
                      _buildQuickAction(context, Icons.build_rounded, 'Signaler', AppColors.warning, () => widget.onNavigate?.call(6)),
                      const SizedBox(width: 16),
                      _buildQuickAction(context, Icons.history_rounded, 'Historique', AppColors.success, () => widget.onNavigate?.call(4)),
                      const SizedBox(width: 16),
                      _buildQuickAction(context, Icons.chat_bubble_rounded, 'Support', AppColors.primaryLight, () => widget.onNavigate?.call(6)),
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

  Widget _buildQuickAction(BuildContext context, IconData icon, String label, Color color, VoidCallback onTap) {
    return GestureDetector(
      onTap: onTap,
      child: Column(
        children: [
          GlassContainer(
            padding: const EdgeInsets.all(20),
            borderRadius: 24,
            child: Icon(icon, color: color, size: 28),
          ),
          const SizedBox(height: 10),
          Text(label, style: const TextStyle(fontSize: 12, fontWeight: FontWeight.w700)),
        ],
      ),
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
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (ctx) => const RechargeModal(),
    );
  }
}

class RechargeModal extends StatefulWidget {
  const RechargeModal({super.key});

  @override
  State<RechargeModal> createState() => _RechargeModalState();
}

class _RechargeModalState extends State<RechargeModal> {
  String _activeTab = 'orange'; // 'orange', 'mtn', 'card', 'paypal'
  bool _processing = false;

  // Orange Money
  final _orangePhoneCtrl = TextEditingController();
  final _orangeAmountCtrl = TextEditingController();
  final _orangeOtpCtrl = TextEditingController();
  bool _orangeOtpSent = false;
  int _orangeOtpCountdown = 0;
  Timer? _orangeOtpTimer;

  // MTN MoMo
  final _mtnPhoneCtrl = TextEditingController();
  final _mtnPinCtrl = TextEditingController();
  final _mtnAmountCtrl = TextEditingController();

  // Card
  final _cardNumberCtrl = TextEditingController();
  final _cardHolderCtrl = TextEditingController();
  final _cardExpiryCtrl = TextEditingController();
  final _cardCvvCtrl = TextEditingController();
  final _cardAmountCtrl = TextEditingController();

  // PayPal
  final _paypalAmountCtrl = TextEditingController();

  @override
  void dispose() {
    _orangePhoneCtrl.dispose();
    _orangeAmountCtrl.dispose();
    _orangeOtpCtrl.dispose();
    _orangeOtpTimer?.cancel();
    _mtnPhoneCtrl.dispose();
    _mtnPinCtrl.dispose();
    _mtnAmountCtrl.dispose();
    _cardNumberCtrl.dispose();
    _cardHolderCtrl.dispose();
    _cardExpiryCtrl.dispose();
    _cardCvvCtrl.dispose();
    _cardAmountCtrl.dispose();
    _paypalAmountCtrl.dispose();
    super.dispose();
  }

  void _startOrangeOtpTimer() {
    setState(() {
      _orangeOtpSent = true;
      _orangeOtpCountdown = 20;
    });
    _orangeOtpTimer?.cancel();
    _orangeOtpTimer = Timer.periodic(const Duration(seconds: 1), (timer) {
      if (_orangeOtpCountdown > 0) {
        setState(() {
          _orangeOtpCountdown--;
        });
      } else {
        timer.cancel();
      }
    });
  }

  void _initiateOrangePayment() {
    final phone = _orangePhoneCtrl.text.trim();
    final amount = _orangeAmountCtrl.text.trim();
    if (phone.isEmpty || amount.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Veuillez remplir tous les champs requis.')),
      );
      return;
    }
    setState(() => _processing = true);
    Future.delayed(const Duration(milliseconds: 1200), () {
      if (mounted) {
        setState(() => _processing = false);
        _startOrangeOtpTimer();
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Code OTP envoyé sur votre mobile !')),
        );
      }
    });
  }

  void _confirmOrangePayment() {
    final otp = _orangeOtpCtrl.text.trim();
    final amount = double.tryParse(_orangeAmountCtrl.text) ?? 0.0;
    if (otp == '8842') {
      _executeRecharge(amount, 'Orange Money');
    } else {
      HapticFeedback.heavyImpact();
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Code OTP incorrect. Veuillez utiliser 8842.')),
      );
    }
  }

  void _executeRecharge(double amount, String method) async {
    setState(() => _processing = true);
    final apiService = context.read<ApiService>();
    final success = await apiService.rechargeWallet(amount);

    if (mounted) {
      setState(() => _processing = false);
      Navigator.pop(context);
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(success 
              ? 'Recharge de ${amount.toStringAsFixed(0)} € via $method effectuée avec succès.' 
              : 'Erreur lors de la recharge.'),
          backgroundColor: success ? AppColors.success : AppColors.error,
          behavior: SnackBarBehavior.floating,
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
        ),
      );
    }
  }

  void _submitMtnPayment() {
    final phone = _mtnPhoneCtrl.text.trim();
    final pin = _mtnPinCtrl.text.trim();
    final amountText = _mtnAmountCtrl.text.trim();
    final amount = double.tryParse(amountText) ?? 0.0;

    if (phone.isEmpty || pin.isEmpty || amount <= 0) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Veuillez remplir tous les champs.')),
      );
      return;
    }
    _executeRecharge(amount, 'MTN MoMo');
  }

  void _submitCardPayment() {
    final num = _cardNumberCtrl.text.trim();
    final holder = _cardHolderCtrl.text.trim();
    final exp = _cardExpiryCtrl.text.trim();
    final cvv = _cardCvvCtrl.text.trim();
    final amountText = _cardAmountCtrl.text.trim();
    final amount = double.tryParse(amountText) ?? 0.0;

    if (num.length < 12 || holder.isEmpty || exp.isEmpty || cvv.length < 3 || amount <= 0) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Champs de carte invalides.')),
      );
      return;
    }
    _executeRecharge(amount, 'Carte Bancaire');
  }

  void _submitPaypalPayment() {
    final amountText = _paypalAmountCtrl.text.trim();
    final amount = double.tryParse(amountText) ?? 0.0;
    if (amount <= 0) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Veuillez entrer un montant valide.')),
      );
      return;
    }
    _executeRecharge(amount, 'PayPal');
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: EdgeInsets.fromLTRB(24, 16, 24, MediaQuery.of(context).viewInsets.bottom + 40),
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
          Row(
            children: [
              Container(
                padding: const EdgeInsets.all(8),
                decoration: BoxDecoration(color: AppColors.primary.withValues(alpha: 0.1), borderRadius: BorderRadius.circular(10)),
                child: const Icon(Icons.account_balance_wallet_rounded, color: AppColors.primary),
              ),
              const SizedBox(width: 12),
              const Text('Recharger le Wallet', style: TextStyle(fontWeight: FontWeight.w800, fontSize: 20)),
            ],
          ),
          const SizedBox(height: 20),

          // Tab Bar
          SingleChildScrollView(
            scrollDirection: Axis.horizontal,
            physics: const BouncingScrollPhysics(),
            child: Row(
              children: [
                _buildTabButton('Orange Money', 'orange'),
                const SizedBox(width: 8),
                _buildTabButton('MTN MoMo', 'mtn'),
                const SizedBox(width: 8),
                _buildTabButton('Carte Bancaire', 'card'),
                const SizedBox(width: 8),
                _buildTabButton('PayPal', 'paypal'),
              ],
            ),
          ),
          const Divider(height: 32),

          // Panels
          AnimatedSize(
            duration: const Duration(milliseconds: 250),
            child: _buildActivePanel(),
          ),
        ],
      ),
    );
  }

  Widget _buildTabButton(String label, String id) {
    final isActive = _activeTab == id;
    return GestureDetector(
      onTap: () {
        setState(() {
          _activeTab = id;
          _processing = false;
        });
      },
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 10),
        decoration: BoxDecoration(
          color: isActive ? AppColors.primary : AppColors.primary.withValues(alpha: 0.05),
          borderRadius: BorderRadius.circular(16),
        ),
        child: Text(
          label,
          style: TextStyle(
            color: isActive ? Colors.white : AppColors.primary,
            fontWeight: FontWeight.w800,
            fontSize: 13,
          ),
        ),
      ),
    );
  }

  Widget _buildActivePanel() {
    if (_processing) {
      return const Padding(
        padding: EdgeInsets.symmetric(vertical: 40),
        child: Center(child: CircularProgressIndicator(color: AppColors.primary)),
      );
    }

    switch (_activeTab) {
      case 'orange':
        return Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            TextField(
              controller: _orangePhoneCtrl,
              keyboardType: TextInputType.phone,
              maxLength: 9,
              decoration: const InputDecoration(
                labelText: 'Numéro Orange Money (+237)',
                hintText: '69X XX XX XX',
                prefixText: '+237 ',
                counterText: '',
                prefixIcon: Icon(Icons.phone_android_rounded),
              ),
            ),
            const SizedBox(height: 16),
            TextField(
              controller: _orangeAmountCtrl,
              keyboardType: TextInputType.number,
              decoration: const InputDecoration(
                labelText: 'Montant (€)',
                prefixIcon: Icon(Icons.euro_rounded),
              ),
            ),
            if (_orangeOtpSent) ...[
              const SizedBox(height: 16),
              TextField(
                controller: _orangeOtpCtrl,
                keyboardType: TextInputType.number,
                maxLength: 4,
                decoration: InputDecoration(
                  labelText: 'Code de validation OTP (8842)',
                  hintText: 'Entrez le code OTP',
                  counterText: '',
                  prefixIcon: const Icon(Icons.sms_rounded),
                  suffixText: _orangeOtpCountdown > 0 ? '${_orangeOtpCountdown}s' : 'Renvoyer',
                ),
              ),
            ],
            const SizedBox(height: 24),
            SizedBox(
              height: 52,
              child: ElevatedButton(
                onPressed: _orangeOtpSent ? _confirmOrangePayment : _initiateOrangePayment,
                style: ElevatedButton.styleFrom(
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                ),
                child: Text(
                  _orangeOtpSent ? 'Confirmer le paiement' : 'Initier le paiement',
                  style: const TextStyle(fontWeight: FontWeight.bold),
                ),
              ),
            ),
          ],
        );
      case 'mtn':
        return Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            TextField(
              controller: _mtnPhoneCtrl,
              keyboardType: TextInputType.phone,
              maxLength: 9,
              decoration: const InputDecoration(
                labelText: 'Numéro MTN MoMo (+237)',
                hintText: '67X XX XX XX',
                prefixText: '+237 ',
                counterText: '',
                prefixIcon: Icon(Icons.phone_android_rounded),
              ),
            ),
            const SizedBox(height: 16),
            TextField(
              controller: _mtnPinCtrl,
              keyboardType: TextInputType.number,
              obscureText: true,
              maxLength: 5,
              decoration: const InputDecoration(
                labelText: 'Code PIN MoMo',
                counterText: '',
                prefixIcon: Icon(Icons.lock_rounded),
              ),
            ),
            const SizedBox(height: 16),
            TextField(
              controller: _mtnAmountCtrl,
              keyboardType: TextInputType.number,
              decoration: const InputDecoration(
                labelText: 'Montant (€)',
                prefixIcon: Icon(Icons.euro_rounded),
              ),
            ),
            const SizedBox(height: 24),
            SizedBox(
              height: 52,
              child: ElevatedButton(
                onPressed: _submitMtnPayment,
                style: ElevatedButton.styleFrom(
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                ),
                child: const Text('Payer via MTN MoMo', style: TextStyle(fontWeight: FontWeight.bold)),
              ),
            ),
          ],
        );
      case 'card':
        return Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            TextField(
              controller: _cardNumberCtrl,
              keyboardType: TextInputType.number,
              decoration: const InputDecoration(
                labelText: 'Numéro de Carte',
                hintText: 'XXXX XXXX XXXX XXXX',
                prefixIcon: Icon(Icons.credit_card_rounded),
              ),
            ),
            const SizedBox(height: 16),
            Row(
              children: [
                Expanded(
                  child: TextField(
                    controller: _cardExpiryCtrl,
                    decoration: const InputDecoration(
                      labelText: 'Expiration',
                      hintText: 'MM/AA',
                    ),
                  ),
                ),
                const SizedBox(width: 16),
                Expanded(
                  child: TextField(
                    controller: _cardCvvCtrl,
                    keyboardType: TextInputType.number,
                    maxLength: 3,
                    obscureText: true,
                    decoration: const InputDecoration(
                      labelText: 'CVV',
                      counterText: '',
                    ),
                  ),
                ),
              ],
            ),
            const SizedBox(height: 16),
            TextField(
              controller: _cardHolderCtrl,
              decoration: const InputDecoration(
                labelText: 'Titulaire de la Carte',
                prefixIcon: Icon(Icons.person_rounded),
              ),
            ),
            const SizedBox(height: 16),
            TextField(
              controller: _cardAmountCtrl,
              keyboardType: TextInputType.number,
              decoration: const InputDecoration(
                labelText: 'Montant (€)',
                prefixIcon: Icon(Icons.euro_rounded),
              ),
            ),
            const SizedBox(height: 24),
            SizedBox(
              height: 52,
              child: ElevatedButton(
                onPressed: _submitCardPayment,
                style: ElevatedButton.styleFrom(
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                ),
                child: const Text('Payer par Carte', style: TextStyle(fontWeight: FontWeight.bold)),
              ),
            ),
          ],
        );
      case 'paypal':
        return Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            Center(
              child: Container(
                padding: const EdgeInsets.all(16),
                decoration: BoxDecoration(
                  color: const Color(0xFF003087).withValues(alpha: 0.1),
                  shape: BoxShape.circle,
                ),
                child: const Icon(Icons.paypal_rounded, color: Color(0xFF003087), size: 48),
              ),
            ),
            const SizedBox(height: 16),
            const Text(
              'PayPal Checkout',
              textAlign: TextAlign.center,
              style: TextStyle(fontWeight: FontWeight.bold, fontSize: 18),
            ),
            const Text(
              'Paiement sécurisé international',
              textAlign: TextAlign.center,
              style: TextStyle(color: AppColors.textSecondary, fontSize: 13),
            ),
            const SizedBox(height: 24),
            TextField(
              controller: _paypalAmountCtrl,
              keyboardType: TextInputType.number,
              decoration: const InputDecoration(
                labelText: 'Montant (€)',
                prefixIcon: Icon(Icons.euro_rounded),
              ),
            ),
            const SizedBox(height: 24),
            SizedBox(
              height: 52,
              child: ElevatedButton(
                onPressed: _submitPaypalPayment,
                style: ElevatedButton.styleFrom(
                  backgroundColor: const Color(0xFFFFC439),
                  foregroundColor: Colors.black,
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                ),
                child: const Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Icon(Icons.paypal_rounded, color: Color(0xFF003087)),
                    SizedBox(width: 8),
                    Text('Payer avec PayPal', style: TextStyle(fontWeight: FontWeight.w900)),
                  ],
                ),
              ),
            ),
          ],
        );
      default:
        return const SizedBox();
    }
  }
}
