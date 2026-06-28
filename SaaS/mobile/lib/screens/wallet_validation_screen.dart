import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:provider/provider.dart';
import '../services/api_service.dart';
import '../theme/app_colors.dart';
import '../widgets/glass_container.dart';

class WalletValidationScreen extends StatefulWidget {
  final String? initialToken;

  const WalletValidationScreen({super.key, this.initialToken});

  @override
  State<WalletValidationScreen> createState() => _WalletValidationScreenState();
}

class _WalletValidationScreenState extends State<WalletValidationScreen> with SingleTickerProviderStateMixin {
  final _tokenController = TextEditingController();
  final _pinController = TextEditingController();
  final _pinFocusNode = FocusNode();

  late AnimationController _shakeController;
  late Animation<double> _shakeAnimation;

  String? _token;
  bool _loading = false;
  bool _validating = false;
  bool _success = false;
  String? _error;
  String? _pinError;

  Map<String, dynamic>? _pendingPayment;

  @override
  void initState() {
    super.initState();
    _token = widget.initialToken;
    if (_token != null) {
      _tokenController.text = _token!;
      WidgetsBinding.instance.addPostFrameCallback((_) {
        _loadPendingPayment();
      });
    }

    _shakeController = AnimationController(
      vsync: this,
      duration: const Duration(milliseconds: 500),
    );
    _shakeAnimation = Tween<double>(begin: 0.0, end: 10.0)
        .chain(CurveTween(curve: Curves.elasticIn))
        .animate(_shakeController);

    _shakeController.addStatusListener((status) {
      if (status == AnimationStatus.completed) {
        _shakeController.reverse();
      }
    });
  }

  @override
  void dispose() {
    _tokenController.dispose();
    _pinController.dispose();
    _pinFocusNode.dispose();
    _shakeController.dispose();
    super.dispose();
  }

  void _triggerShake() {
    _shakeController.forward(from: 0.0);
    HapticFeedback.lightImpact();
  }

  Future<void> _loadPendingPayment() async {
    if (_token == null || _token!.isEmpty) return;

    setState(() {
      _loading = true;
      _error = null;
      _pendingPayment = null;
      _pinController.clear();
      _pinError = null;
    });

    final api = context.read<ApiService>();
    final payment = await api.fetchPendingPayment(_token!);

    if (mounted) {
      setState(() {
        _loading = false;
        if (payment != null) {
          _pendingPayment = payment;
        } else {
          _error = 'Cette demande de paiement est introuvable, déjà validée ou expirée.';
        }
      });
    }
  }

  Future<void> _submitPin() async {
    final pin = _pinController.text;
    if (pin.length < 4 || _token == null || _validating || _success) return;

    setState(() {
      _validating = true;
      _pinError = null;
    });

    final api = context.read<ApiService>();
    final result = await api.validatePendingPayment(_token!, pin);

    if (mounted) {
      setState(() {
        _validating = false;
        if (result['success'] == true) {
          _success = true;
          // Play a small success vibration/sound
          HapticFeedback.mediumImpact();
          SystemSound.play(SystemSoundType.click);
        } else {
          _pinController.clear();
          _pinError = result['message'] ?? 'Code secret incorrect.';
          _triggerShake();
          FocusScope.of(context).requestFocus(_pinFocusNode);
        }
      });
    }
  }

  String _formatCurrency(double? value) {
    if (value == null) return '0,00 €';
    return '${value.toStringAsFixed(2).replaceAll('.', ',')} €';
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      extendBodyBehindAppBar: true,
      appBar: AppBar(
        backgroundColor: Colors.transparent,
        elevation: 0,
        leading: IconButton(
          icon: const Icon(Icons.arrow_back_ios_new_rounded, color: AppColors.textPrimary),
          onPressed: () => Navigator.pop(context),
        ),
      ),
      body: Stack(
        fit: StackFit.expand,
        children: [
          // Background blobs
          Positioned(
            top: -100,
            right: -100,
            child: Container(
              width: 300,
              height: 300,
              decoration: BoxDecoration(
                shape: BoxShape.circle,
                color: AppColors.primary.withValues(alpha: 0.1),
              ),
            ),
          ),
          Positioned(
            bottom: -50,
            left: -50,
            child: Container(
              width: 250,
              height: 250,
              decoration: BoxDecoration(
                shape: BoxShape.circle,
                color: AppColors.secondary.withValues(alpha: 0.08),
              ),
            ),
          ),

          SafeArea(
            child: SingleChildScrollView(
              padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
              physics: const BouncingScrollPhysics(),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.stretch,
                children: [
                  const SizedBox(height: 20),
                  // Logo / Brand
                  Center(
                    child: Column(
                      children: [
                        Text(
                          'PORTEFEUILLE ÉLECTRONIQUE',
                          style: TextStyle(
                            fontSize: 11,
                            fontWeight: FontWeight.w900,
                            letterSpacing: 2.0,
                            color: AppColors.textSecondary.withValues(alpha: 0.8),
                          ),
                        ),
                        const SizedBox(height: 4),
                        const Text(
                          'Property AI',
                          style: TextStyle(
                            fontSize: 28,
                            fontWeight: FontWeight.w900,
                            letterSpacing: -1.0,
                            color: AppColors.textPrimary,
                          ),
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(height: 32),

                  // Main Interactive Box
                  AnimatedSize(
                    duration: const Duration(milliseconds: 300),
                    child: _buildMainContent(),
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildMainContent() {
    if (_loading) {
      return GlassContainer(
        padding: const EdgeInsets.symmetric(vertical: 60, horizontal: 24),
        borderRadius: 32,
        child: const Column(
          children: [
            CircularProgressIndicator(strokeWidth: 3, color: AppColors.primary),
            SizedBox(height: 16),
            Text(
              'Récupération de la demande...',
              style: TextStyle(fontWeight: FontWeight.w700, color: AppColors.textSecondary),
            ),
          ],
        ),
      );
    }

    if (_error != null) {
      return GlassContainer(
        padding: const EdgeInsets.all(32),
        borderRadius: 32,
        child: Column(
          children: [
            Container(
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(
                color: AppColors.error.withValues(alpha: 0.1),
                shape: BoxShape.circle,
              ),
              child: const Icon(Icons.error_outline_rounded, color: AppColors.error, size: 36),
            ),
            const SizedBox(height: 20),
            const Text(
              'Opération impossible',
              style: TextStyle(fontWeight: FontWeight.w800, fontSize: 18),
            ),
            const SizedBox(height: 8),
            Text(
              _error!,
              textAlign: TextAlign.center,
              style: const TextStyle(color: AppColors.textSecondary, height: 1.4),
            ),
            const SizedBox(height: 28),
            ElevatedButton(
              onPressed: () {
                setState(() {
                  _token = null;
                  _error = null;
                  _tokenController.clear();
                });
              },
              style: ElevatedButton.styleFrom(
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 12),
              ),
              child: const Text('Recommencer'),
            ),
          ],
        ),
      );
    }

    if (_success) {
      final amount = _pendingPayment?['amount'] as double?;
      final description = _pendingPayment?['description'] ?? 'Paiement autorisé';
      final companyName = _pendingPayment?['company_name'] ?? '';

      return GlassContainer(
        padding: const EdgeInsets.all(32),
        borderRadius: 32,
        child: Column(
          children: [
            Container(
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(
                color: AppColors.success.withValues(alpha: 0.1),
                shape: BoxShape.circle,
              ),
              child: const Icon(Icons.check_circle_outline_rounded, color: AppColors.success, size: 40),
            ),
            const SizedBox(height: 20),
            const Text(
              'Paiement validé !',
              style: TextStyle(fontWeight: FontWeight.w900, fontSize: 22, letterSpacing: -0.5),
            ),
            const SizedBox(height: 12),
            Text(
              'Votre règlement de ${_formatCurrency(amount)} a été autorisé avec succès.',
              textAlign: TextAlign.center,
              style: const TextStyle(color: AppColors.textSecondary, height: 1.4, fontSize: 14),
            ),
            const SizedBox(height: 24),
            Container(
              width: double.infinity,
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(
                color: AppColors.background.withValues(alpha: 0.5),
                borderRadius: BorderRadius.circular(20),
                border: Border.all(color: AppColors.textTertiary.withValues(alpha: 0.2)),
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text('BÉNÉFICIAIRE : $companyName', style: const TextStyle(fontSize: 10, fontWeight: FontWeight.bold, color: AppColors.textSecondary)),
                  const SizedBox(height: 4),
                  Text('OBJET : $description', style: const TextStyle(fontSize: 10, fontWeight: FontWeight.bold, color: AppColors.textSecondary)),
                  const SizedBox(height: 4),
                  const Text('STATUT : Enregistré & Quittancé', style: TextStyle(fontSize: 10, fontWeight: FontWeight.bold, color: AppColors.textSecondary)),
                ],
              ),
            ),
            const SizedBox(height: 28),
            SizedBox(
              width: double.infinity,
              height: 52,
              child: ElevatedButton(
                onPressed: () => Navigator.pop(context),
                style: ElevatedButton.styleFrom(
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                ),
                child: const Text('Fermer', style: TextStyle(fontWeight: FontWeight.bold)),
              ),
            ),
          ],
        ),
      );
    }

    if (_pendingPayment == null) {
      // Enter Token View
      return GlassContainer(
        padding: const EdgeInsets.all(28),
        borderRadius: 32,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            Row(
              children: [
                Container(
                  padding: const EdgeInsets.all(10),
                  decoration: BoxDecoration(
                    color: AppColors.primary.withValues(alpha: 0.1),
                    borderRadius: BorderRadius.circular(14),
                  ),
                  child: const Icon(Icons.vpn_key_rounded, color: AppColors.primary, size: 22),
                ),
                const SizedBox(width: 16),
                const Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text('Autorisation requise', style: TextStyle(fontWeight: FontWeight.w800, fontSize: 16)),
                    Text('Entrez le token de paiement', style: TextStyle(color: AppColors.textSecondary, fontSize: 12)),
                  ],
                ),
              ],
            ),
            const SizedBox(height: 24),
            TextField(
              controller: _tokenController,
              decoration: const InputDecoration(
                labelText: 'Token de transaction',
                hintText: 'Collez le token reçu',
                prefixIcon: Icon(Icons.paste_rounded),
              ),
              onChanged: (val) {
                setState(() {
                  _token = val.trim();
                });
              },
            ),
            const SizedBox(height: 24),
            SizedBox(
              height: 52,
              child: ElevatedButton(
                onPressed: _token != null && _token!.isNotEmpty ? _loadPendingPayment : null,
                style: ElevatedButton.styleFrom(
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                ),
                child: const Text('Vérifier', style: TextStyle(fontWeight: FontWeight.bold)),
              ),
            ),
          ],
        ),
      );
    }

    // Normal PIN input view
    final amount = _pendingPayment!['amount'] as double?;
    final description = _pendingPayment!['description'] ?? '';
    final locName = _pendingPayment!['locataire_nom'] ?? '';
    final companyName = _pendingPayment!['company_name'] ?? '';
    final agencyName = _pendingPayment!['agency_name'] ?? 'N/A';
    final agencyOrCompany = agencyName != 'N/A' ? agencyName : companyName;

    return Stack(
      children: [
        AnimatedBuilder(
          animation: _shakeAnimation,
          builder: (context, child) {
            return Transform.translate(
              offset: Offset(_shakeAnimation.value * (_shakeController.value > 0.5 ? 1 : -1), 0),
              child: child,
            );
          },
          child: GlassContainer(
            padding: const EdgeInsets.all(28),
            borderRadius: 32,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.stretch,
              children: [
                Row(
                  children: [
                    Container(
                      padding: const EdgeInsets.all(10),
                      decoration: BoxDecoration(
                        color: AppColors.primary.withValues(alpha: 0.1),
                        borderRadius: BorderRadius.circular(14),
                      ),
                      child: const Icon(Icons.security_rounded, color: AppColors.primary, size: 22),
                    ),
                    const SizedBox(width: 16),
                    const Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text('Autorisation requise', style: TextStyle(fontWeight: FontWeight.w800, fontSize: 16)),
                        Text('SÉCURITÉ 3D WALLET', style: TextStyle(color: AppColors.textSecondary, fontSize: 10, fontWeight: FontWeight.bold, letterSpacing: 1.0)),
                      ],
                    ),
                  ],
                ),
                const SizedBox(height: 20),
                Text(
                  'Bonjour $locName. L\'agence $agencyOrCompany sollicite un débit sur votre portefeuille électronique.',
                  style: const TextStyle(fontSize: 13, color: AppColors.textSecondary, height: 1.4),
                ),
                const SizedBox(height: 20),

                // Transaction Details Box
                Container(
                  padding: const EdgeInsets.all(16),
                  decoration: BoxDecoration(
                    color: AppColors.background.withValues(alpha: 0.5),
                    borderRadius: BorderRadius.circular(20),
                    border: Border.all(color: AppColors.textTertiary.withValues(alpha: 0.2)),
                  ),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.stretch,
                    children: [
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          const Text('MONTANT', style: TextStyle(fontSize: 10, fontWeight: FontWeight.bold, color: AppColors.textSecondary)),
                          Text(
                            _formatCurrency(amount),
                            style: const TextStyle(fontSize: 18, fontWeight: FontWeight.w900, color: AppColors.primary),
                          ),
                        ],
                      ),
                      const Divider(height: 20, color: AppColors.textTertiary),
                      const Text('OBJET DE LA TRANSACTION', style: TextStyle(fontSize: 9, fontWeight: FontWeight.bold, color: AppColors.textSecondary)),
                      const SizedBox(height: 4),
                      Text(
                        description,
                        style: const TextStyle(fontSize: 13, fontWeight: FontWeight.w700, color: AppColors.textPrimary),
                      ),
                    ],
                  ),
                ),
                const SizedBox(height: 28),

                // PIN Circular Indicators
                GestureDetector(
                  onTap: () {
                    FocusScope.of(context).requestFocus(_pinFocusNode);
                  },
                  child: Column(
                    children: [
                      const Text(
                        'SAISISSEZ VOTRE CODE PIN SECRET',
                        style: TextStyle(fontSize: 10, fontWeight: FontWeight.bold, color: AppColors.textSecondary),
                      ),
                      const SizedBox(height: 16),
                      Row(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: List.generate(4, (index) {
                          final isFilled = _pinController.text.length > index;
                          return AnimatedContainer(
                            duration: const Duration(milliseconds: 150),
                            margin: const EdgeInsets.symmetric(horizontal: 10),
                            width: 20,
                            height: 20,
                            decoration: BoxDecoration(
                              shape: BoxShape.circle,
                              color: isFilled ? AppColors.primary : Colors.transparent,
                              border: Border.all(
                                color: isFilled ? AppColors.primary : AppColors.textTertiary.withValues(alpha: 0.6),
                                width: 2,
                              ),
                            ),
                          );
                        }),
                      ),
                    ],
                  ),
                ),

                // Hidden field for actual text input
                SizedBox(
                  height: 1,
                  width: 1,
                  child: TextField(
                    controller: _pinController,
                    focusNode: _pinFocusNode,
                    autofocus: true,
                    keyboardType: TextInputType.number,
                    obscureText: true,
                    maxLength: 4,
                    showCursor: false,
                    enableInteractiveSelection: false,
                    decoration: const InputDecoration(counterText: '', border: InputBorder.none),
                    inputFormatters: [FilteringTextInputFormatter.digitsOnly],
                    onChanged: (val) {
                      setState(() {});
                      if (val.length == 4) {
                        _submitPin();
                      }
                    },
                  ),
                ),

                if (_pinError != null) ...[
                  const SizedBox(height: 16),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      const Icon(Icons.cancel_rounded, color: AppColors.error, size: 16),
                      const SizedBox(width: 6),
                      Text(
                        _pinError!,
                        style: const TextStyle(color: AppColors.error, fontWeight: FontWeight.w700, fontSize: 12),
                      ),
                    ],
                  ),
                ],

                const SizedBox(height: 28),
                const Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Icon(Icons.lock_outline_rounded, size: 14, color: AppColors.textSecondary),
                    SizedBox(width: 6),
                    Text(
                      'Connexion sécurisée de bout en bout',
                      style: TextStyle(color: AppColors.textSecondary, fontSize: 11, fontWeight: FontWeight.w500),
                    ),
                  ],
                ),
              ],
            ),
          ),
        ),

        // Processing Overlay
        if (_validating)
          Positioned.fill(
            child: Container(
              decoration: BoxDecoration(
                color: Colors.white.withValues(alpha: 0.8),
                borderRadius: BorderRadius.circular(32),
              ),
              child: const Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  CircularProgressIndicator(strokeWidth: 3, color: AppColors.primary),
                  SizedBox(height: 16),
                  Text(
                    'Validation en cours...',
                    style: TextStyle(fontWeight: FontWeight.bold, color: AppColors.textPrimary),
                  ),
                ],
              ),
            ),
          ),
      ],
    );
  }
}
