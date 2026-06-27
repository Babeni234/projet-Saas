import 'dart:io';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/theme_provider.dart';
import '../providers/locale_provider.dart';
import '../services/api_service.dart';
import '../theme/app_colors.dart';
import '../widgets/glass_container.dart';

class ProfileScreen extends StatefulWidget {
  const ProfileScreen({super.key});

  @override
  State<ProfileScreen> createState() => _ProfileScreenState();
}

class _ProfileScreenState extends State<ProfileScreen> {
  bool _biometric = true;

  // Notification preferences matrix (4 events x 3 channels)
  Map<String, Map<String, bool>> _notifPrefs = {
    'Rappel loyer': {'email': true, 'sms': false, 'push': true},
    'Confirmation paiement': {'email': true, 'sms': true, 'push': true},
    'Mise à jour ticket': {'email': false, 'sms': false, 'push': true},
    'Alerte sécurité': {'email': true, 'sms': true, 'push': true},
  };
  bool _showNotifMatrix = false;

  // 2FA wizard
  int _twoFAStep = 0; // 0=hidden, 1=scan, 2=verify, 3=backup codes
  final _twoFACodeCtrl = TextEditingController();
  bool _backupCodesSaved = false;

  // Edit profile modal
  bool _showEditProfile = false;
  final _editFirstNameCtrl = TextEditingController();
  final _editLastNameCtrl = TextEditingController();
  final _editEmailCtrl = TextEditingController();
  final _editPhoneCtrl = TextEditingController();

  // Password change modal
  bool _showPasswordChange = false;
  final _currentPassCtrl = TextEditingController();
  final _newPassCtrl = TextEditingController();
  final _confirmPassCtrl = TextEditingController();

  // PIN change modal
  bool _showPinChange = false;
  final _currentPinCtrl = TextEditingController();
  final _newPinCtrl = TextEditingController();
  final _confirmPinCtrl = TextEditingController();

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      _loadNotifPrefs(context);
    });
  }

  void _loadNotifPrefs(BuildContext context) {
    final api = context.read<ApiService>();
    final saved = api.localPrefs;
    if (saved.isNotEmpty && saved.containsKey('matrix')) {
      _notifPrefs = Map<String, Map<String, bool>>.from(
        (saved['matrix'] as Map).map((k, v) => MapEntry(k as String, Map<String, bool>.from(v as Map))),
      );
    }
  }

  void _saveNotifPrefs() {
    context.read<ApiService>().updateNotificationPrefs({'matrix': _notifPrefs});
  }

  @override
  void dispose() {
    _editFirstNameCtrl.dispose();
    _editLastNameCtrl.dispose();
    _editEmailCtrl.dispose();
    _editPhoneCtrl.dispose();
    _currentPassCtrl.dispose();
    _newPassCtrl.dispose();
    _confirmPassCtrl.dispose();
    _currentPinCtrl.dispose();
    _newPinCtrl.dispose();
    _confirmPinCtrl.dispose();
    _twoFACodeCtrl.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final themeProvider = context.watch<ThemeProvider>();
    final localeProvider = context.watch<LocaleProvider>();
    final apiService = context.watch<ApiService>();
    final isDark = themeProvider.isDark;
    final language = localeProvider.isFrench ? 'fr' : 'en';
    final twoFAEnabled = apiService.twoFAEnabled;
    final avatar = apiService.tenantAvatar;

    final name = apiService.tenantFullName;
    final email = apiService.tenantEmail;
    final phone = apiService.tenantPhone;

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
                        Text('Profil', style: Theme.of(context).textTheme.headlineMedium?.copyWith(fontSize: 34, fontWeight: FontWeight.w800, letterSpacing: -1)),
                        Text('Paramètres & Compte', style: TextStyle(color: AppColors.textSecondary, fontSize: 16, fontWeight: FontWeight.w500)),
                      ],
                    ),
                  ],
                ),
                const SizedBox(height: 32),

                GlassContainer(
                  padding: const EdgeInsets.all(24),
                  borderRadius: 32,
                  child: Column(
                    children: [
                      GestureDetector(
                        onTap: () => _showAvatarChangeSheet(context),
                        child: Stack(
                          alignment: Alignment.bottomRight,
                          children: [
                            Container(
                              width: 100, height: 100,
                              decoration: BoxDecoration(
                                shape: BoxShape.circle,
                                gradient: AppColors.actionGradient,
                                boxShadow: [BoxShadow(color: AppColors.primary.withValues(alpha: 0.3), blurRadius: 20, offset: const Offset(0, 10))],
                              ),
                              child: ClipOval(
                                child: avatar.isNotEmpty
                                    ? Image.network(avatar, fit: BoxFit.cover, width: 100, height: 100, errorBuilder: (_, __, ___) => const Icon(Icons.person_rounded, color: Colors.white, size: 50))
                                    : const Icon(Icons.person_rounded, color: Colors.white, size: 50),
                              ),
                            ),
                            Container(
                              padding: const EdgeInsets.all(6),
                              decoration: const BoxDecoration(color: Colors.white, shape: BoxShape.circle),
                              child: Container(
                                padding: const EdgeInsets.all(4),
                                decoration: const BoxDecoration(color: AppColors.primary, shape: BoxShape.circle),
                                child: const Icon(Icons.camera_alt_rounded, color: Colors.white, size: 14),
                              ),
                            ),
                          ],
                        ),
                      ),
                      const SizedBox(height: 20),
                      Text(name, style: const TextStyle(fontSize: 24, fontWeight: FontWeight.w800, letterSpacing: -0.5)),
                      const SizedBox(height: 4),
                      Text(email, style: const TextStyle(color: AppColors.textSecondary, fontSize: 15, fontWeight: FontWeight.w500)),
                      const SizedBox(height: 20),
                      Row(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          GestureDetector(
                            onTap: () => _openEditProfile(apiService),
                            child: _buildHeaderAction(Icons.edit_rounded, 'Éditer'),
                          ),
                          const SizedBox(width: 12),
                          GestureDetector(
                            onTap: () => setState(() => _showPinChange = true),
                            child: _buildHeaderAction(Icons.lock_rounded, 'PIN'),
                          ),
                        ],
                      ),
                    ],
                  ),
                ),
                const SizedBox(height: 32),

                _sectionHeader('INFORMATIONS PERSONNELLES'),
                const SizedBox(height: 12),
                GlassContainer(
                  padding: EdgeInsets.zero,
                  borderRadius: 24,
                  child: Column(
                    children: [
                      _buildInfoTile(Icons.person_outline_rounded, 'Nom', name, showDivider: true),
                      _buildInfoTile(Icons.phone_iphone_rounded, 'Téléphone', phone, showDivider: true),
                      _buildInfoTile(Icons.mail_outline_rounded, 'Email', email, showDivider: true),
                      _buildInfoTile(Icons.location_on_outlined, 'Adresse', 'Non renseigné'),
                    ],
                  ),
                ),
                const SizedBox(height: 32),

                _sectionHeader('SÉCURITÉ ET ACCÈS'),
                const SizedBox(height: 12),
                GlassContainer(
                  padding: EdgeInsets.zero,
                  borderRadius: 24,
                  child: Column(
                    children: [
                      _buildActionTile(Icons.lock_reset_rounded, 'Mot de passe', () => setState(() => _showPasswordChange = true), showDivider: true),
                      _buildActionTile(
                        Icons.verified_user_outlined, 'Double facteur (2FA)',
                        () => _handle2FA(apiService, twoFAEnabled),
                        value: twoFAEnabled ? 'Activé' : 'Désactivé',
                        valueColor: twoFAEnabled ? AppColors.success : AppColors.warning,
                        showDivider: true,
                      ),
                      _buildActionTile(
                        Icons.fingerprint_rounded, 'Biométrie',
                        () => setState(() => _biometric = !_biometric),
                        value: _biometric ? 'Activée' : 'Désactivée',
                        showDivider: false,
                      ),
                    ],
                  ),
                ),
                const SizedBox(height: 32),

                _sectionHeader('PRÉFÉRENCES'),
                const SizedBox(height: 12),
                GlassContainer(
                  padding: EdgeInsets.zero,
                  borderRadius: 24,
                  child: Column(
                    children: [
                      _buildPrefRow(isDark ? Icons.dark_mode_rounded : Icons.light_mode_rounded, 'Thème', _buildThemeToggle(isDark, themeProvider), showDivider: true),
                      _buildPrefRow(Icons.language_rounded, 'Langue', _buildLanguageToggle(language, localeProvider), showDivider: true),
                      _buildPrefRow(
                        Icons.notifications_active_rounded, 'Préférences notifications',
                        GestureDetector(
                          onTap: () => setState(() => _showNotifMatrix = !_showNotifMatrix),
                          child: Container(
                            padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 8),
                            decoration: BoxDecoration(color: AppColors.primary.withValues(alpha: 0.1), borderRadius: BorderRadius.circular(12)),
                            child: Text(_showNotifMatrix ? 'Masquer' : 'Configurer', style: const TextStyle(color: AppColors.primary, fontSize: 13, fontWeight: FontWeight.w700)),
                          ),
                        ),
                        showDivider: false,
                      ),
                    ],
                  ),
                ),

                if (_showNotifMatrix) ...[
                  const SizedBox(height: 12),
                  _buildNotifMatrix(),
                ],
                const SizedBox(height: 32),

                _sectionHeader('HISTORIQUE DE CONNEXION'),
                const SizedBox(height: 12),
                GlassContainer(
                  padding: const EdgeInsets.symmetric(vertical: 8),
                  borderRadius: 24,
                  child: Column(
                    children: [
                      _buildLoginRow("Aujourd'hui, 10:32", 'Application mobile • Paris, FR', true),
                      const Padding(padding: EdgeInsets.only(left: 68), child: Divider(height: 1, color: AppColors.background)),
                      _buildLoginRow('Hier, 18:15', 'Application mobile • Paris, FR', false),
                    ],
                  ),
                ),
                const SizedBox(height: 40),

                SizedBox(
                  width: double.infinity,
                  height: 64,
                  child: ElevatedButton.icon(
                    onPressed: () async {
                      await context.read<ApiService>().logout();
                      if (mounted) Navigator.of(context).pushNamedAndRemoveUntil('/', (route) => false);
                    },
                    style: ElevatedButton.styleFrom(
                      backgroundColor: AppColors.error.withValues(alpha: 0.1),
                      foregroundColor: AppColors.error,
                      elevation: 0,
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(24), side: const BorderSide(color: AppColors.error, width: 1.5)),
                    ),
                    icon: const Icon(Icons.logout_rounded),
                    label: const Text('Déconnexion', style: TextStyle(fontWeight: FontWeight.w800, fontSize: 18)),
                  ),
                ),
              ],
            ),
          ),

          // Modals
          if (_showEditProfile) _buildEditProfileOverlay(),
          if (_showPasswordChange) _buildPasswordChangeOverlay(),
          if (_showPinChange) _buildPinChangeOverlay(),
          if (_twoFAStep > 0) _build2FAOverlay(),
        ],
      ),
    );
  }

  void _showAvatarChangeSheet(BuildContext context) {
    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.transparent,
      builder: (ctx) => GlassContainer(
        padding: const EdgeInsets.all(24),
        borderRadius: 32,
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Container(width: 40, height: 4, decoration: BoxDecoration(color: AppColors.textTertiary.withValues(alpha: 0.4), borderRadius: BorderRadius.circular(2))),
            const SizedBox(height: 20),
            const Text('Photo de profil', style: TextStyle(fontWeight: FontWeight.w800, fontSize: 20)),
            const SizedBox(height: 24),
            ListTile(
              leading: const Icon(Icons.photo_library_rounded, color: AppColors.primary),
              title: const Text('Choisir une photo'),
              onTap: () {
                Navigator.pop(ctx);
                // Web version stores avatar as data URL locally
                context.read<ApiService>().updateLocalProfile({'avatar': 'local_avatar_placeholder'});
              },
            ),
            ListTile(
              leading: const Icon(Icons.delete_rounded, color: AppColors.error),
              title: const Text('Supprimer la photo'),
              onTap: () {
                Navigator.pop(ctx);
                context.read<ApiService>().updateLocalProfile({'avatar': ''});
              },
            ),
          ],
        ),
      ),
    );
  }

  void _openEditProfile(ApiService api) {
    _editFirstNameCtrl.text = api.tenantFirstName;
    _editLastNameCtrl.text = api.tenantLastName;
    _editEmailCtrl.text = api.tenantEmail;
    _editPhoneCtrl.text = api.tenantPhone;
    setState(() => _showEditProfile = true);
  }

  void _saveProfile() {
    context.read<ApiService>().updateLocalProfile({
      'first_name': _editFirstNameCtrl.text.trim(),
      'last_name': _editLastNameCtrl.text.trim(),
      'email': _editEmailCtrl.text.trim(),
      'phone': _editPhoneCtrl.text.trim(),
    });
    setState(() => _showEditProfile = false);
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(content: const Text('Profil mis à jour'), backgroundColor: AppColors.success, behavior: SnackBarBehavior.floating, shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16))),
    );
  }

  void _savePassword() {
    if (_newPassCtrl.text != _confirmPassCtrl.text) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: const Text('Les mots de passe ne correspondent pas'), backgroundColor: AppColors.error, behavior: SnackBarBehavior.floating, shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16))),
      );
      return;
    }
    context.read<ApiService>().changePassword(_currentPassCtrl.text, _newPassCtrl.text);
    setState(() => _showPasswordChange = false);
    _currentPassCtrl.clear(); _newPassCtrl.clear(); _confirmPassCtrl.clear();
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(content: const Text('Mot de passe modifié avec succès'), backgroundColor: AppColors.success, behavior: SnackBarBehavior.floating, shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16))),
    );
  }

  void _submitPinChange() async {
    if (_newPinCtrl.text != _confirmPinCtrl.text) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: const Text('Les codes PIN ne correspondent pas'), backgroundColor: AppColors.error, behavior: SnackBarBehavior.floating, shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16))),
      );
      return;
    }
    final result = await context.read<ApiService>().changePin(_currentPinCtrl.text, _newPinCtrl.text);
    if (mounted) {
      setState(() => _showPinChange = false);
      _currentPinCtrl.clear(); _newPinCtrl.clear(); _confirmPinCtrl.clear();
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(result['message'] as String? ?? (result['success'] == true ? 'PIN modifié avec succès' : 'Erreur')),
          backgroundColor: result['success'] == true ? AppColors.success : AppColors.error,
          behavior: SnackBarBehavior.floating,
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
        ),
      );
    }
  }

  void _handle2FA(ApiService api, bool enabled) {
    if (enabled) {
      showDialog(
        context: context,
        builder: (ctx) => AlertDialog(
          title: const Text('Désactiver 2FA'),
          content: const Text('Êtes-vous sûr de vouloir désactiver la double authentification ?'),
          actions: [
            TextButton(onPressed: () => Navigator.pop(ctx), child: const Text('Annuler')),
            TextButton(onPressed: () {
              Navigator.pop(ctx);
              api.disable2FA();
            }, child: const Text('Désactiver', style: TextStyle(color: AppColors.error))),
          ],
        ),
      );
    } else {
      setState(() => _twoFAStep = 1);
    }
  }

  // ═══ WIDGET BUILDERS ═══

  Widget _sectionHeader(String title) {
    return Padding(
      padding: const EdgeInsets.only(left: 12),
      child: Text(title, style: const TextStyle(color: AppColors.textSecondary, fontSize: 13, fontWeight: FontWeight.w700, letterSpacing: 1.2)),
    );
  }

  Widget _buildHeaderAction(IconData icon, String label) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 12),
      decoration: BoxDecoration(color: AppColors.primary.withValues(alpha: 0.08), borderRadius: BorderRadius.circular(16)),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(icon, color: AppColors.primary, size: 18),
          const SizedBox(width: 8),
          Text(label, style: const TextStyle(color: AppColors.primary, fontWeight: FontWeight.w700, fontSize: 14)),
        ],
      ),
    );
  }

  Widget _buildInfoTile(IconData icon, String label, String value, {bool showDivider = false}) {
    return Column(
      children: [
        Padding(
          padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 18),
          child: Row(
            children: [
              Container(
                padding: const EdgeInsets.all(10),
                decoration: BoxDecoration(color: AppColors.primary.withValues(alpha: 0.1), borderRadius: BorderRadius.circular(12)),
                child: Icon(icon, color: AppColors.primary, size: 22),
              ),
              const SizedBox(width: 16),
              Expanded(child: Text(label, style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 16))),
              Text(value, style: const TextStyle(color: AppColors.textSecondary, fontSize: 15, fontWeight: FontWeight.w500)),
            ],
          ),
        ),
        if (showDivider) const Padding(padding: EdgeInsets.only(left: 68), child: Divider(height: 1, color: AppColors.background)),
      ],
    );
  }

  Widget _buildActionTile(IconData icon, String label, VoidCallback onTap, {String? value, Color? valueColor, bool showDivider = false}) {
    return InkWell(
      onTap: onTap,
      child: Column(
        children: [
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 18),
            child: Row(
              children: [
                Container(
                  padding: const EdgeInsets.all(10),
                  decoration: BoxDecoration(color: (valueColor ?? AppColors.primary).withValues(alpha: 0.1), borderRadius: BorderRadius.circular(12)),
                  child: Icon(icon, color: valueColor ?? AppColors.primary, size: 22),
                ),
                const SizedBox(width: 16),
                Expanded(child: Text(label, style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 16))),
                if (value != null)
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 8),
                    decoration: BoxDecoration(color: (valueColor ?? AppColors.primary).withValues(alpha: 0.1), borderRadius: BorderRadius.circular(12)),
                    child: Text(value, style: TextStyle(color: valueColor ?? AppColors.primary, fontSize: 13, fontWeight: FontWeight.w700)),
                  )
                else
                  const Icon(Icons.chevron_right_rounded, color: AppColors.textSecondary),
              ],
            ),
          ),
          if (showDivider) const Padding(padding: EdgeInsets.only(left: 68), child: Divider(height: 1, color: AppColors.background)),
        ],
      ),
    );
  }

  Widget _buildPrefRow(IconData icon, String label, Widget trailing, {bool showDivider = false}) {
    return Column(
      children: [
        Padding(
          padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 14),
          child: Row(
            children: [
              Container(
                padding: const EdgeInsets.all(10),
                decoration: BoxDecoration(color: AppColors.primary.withValues(alpha: 0.1), borderRadius: BorderRadius.circular(12)),
                child: Icon(icon, color: AppColors.primary, size: 22),
              ),
              const SizedBox(width: 16),
              Expanded(child: Text(label, style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 16))),
              trailing,
            ],
          ),
        ),
        if (showDivider) const Padding(padding: EdgeInsets.only(left: 68), child: Divider(height: 1, color: AppColors.background)),
      ],
    );
  }

  Widget _buildNotifMatrix() {
    const events = ['Rappel loyer', 'Confirmation paiement', 'Mise à jour ticket', 'Alerte sécurité'];
    const channels = ['email', 'sms', 'push'];
    const channelIcons = [Icons.email_rounded, Icons.chat_rounded, Icons.notifications_rounded];

    return GlassContainer(
      padding: const EdgeInsets.all(16),
      borderRadius: 24,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text('Notifications', style: TextStyle(fontWeight: FontWeight.w800, fontSize: 16)),
          const SizedBox(height: 4),
          const Text('Activez les canaux pour chaque type d\'événement', style: TextStyle(color: AppColors.textSecondary, fontSize: 12)),
          const SizedBox(height: 16),
          ...events.map((event) {
            return Padding(
              padding: const EdgeInsets.only(bottom: 8),
              child: Row(
                children: [
                  SizedBox(
                    width: 130,
                    child: Text(event, style: const TextStyle(fontSize: 12, fontWeight: FontWeight.w600)),
                  ),
                  ...channels.asMap().entries.map((entry) {
                    final ch = entry.value;
                    final isOn = _notifPrefs[event]?[ch] ?? false;
                    return Expanded(
                      child: GestureDetector(
                        onTap: () {
                          setState(() {
                            _notifPrefs[event] ??= {};
                            _notifPrefs[event]![ch] = !isOn;
                          });
                          _saveNotifPrefs();
                        },
                        child: Container(
                          margin: const EdgeInsets.symmetric(horizontal: 2),
                          padding: const EdgeInsets.symmetric(vertical: 8),
                          decoration: BoxDecoration(
                            color: isOn ? AppColors.primary.withValues(alpha: 0.1) : AppColors.background,
                            borderRadius: BorderRadius.circular(8),
                            border: Border.all(color: isOn ? AppColors.primary.withValues(alpha: 0.3) : AppColors.textTertiary.withValues(alpha: 0.2)),
                          ),
                          child: Icon(channelIcons[entry.key], color: isOn ? AppColors.primary : AppColors.textTertiary, size: 16),
                        ),
                      ),
                    );
                  }),
                ],
              ),
            );
          }),
        ],
      ),
    );
  }

  Widget _buildThemeToggle(bool isDark, ThemeProvider provider) {
    return GestureDetector(
      onTap: () => provider.toggleTheme(),
      child: Container(
        padding: const EdgeInsets.all(4),
        decoration: BoxDecoration(color: AppColors.background, borderRadius: BorderRadius.circular(20)),
        child: Row(
          mainAxisSize: MainAxisSize.min,
          children: [
            _toggleItem('Clair', !isDark),
            _toggleItem('Sombre', isDark),
          ],
        ),
      ),
    );
  }

  Widget _buildLanguageToggle(String lang, LocaleProvider provider) {
    return GestureDetector(
      onTap: () => provider.toggleLocale(),
      child: Container(
        padding: const EdgeInsets.all(4),
        decoration: BoxDecoration(color: AppColors.background, borderRadius: BorderRadius.circular(20)),
        child: Row(
          mainAxisSize: MainAxisSize.min,
          children: [
            _toggleItem('FR', lang == 'fr'),
            _toggleItem('EN', lang == 'en'),
          ],
        ),
      ),
    );
  }

  Widget _toggleItem(String label, bool active) {
    return AnimatedContainer(
      duration: const Duration(milliseconds: 200),
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
      decoration: BoxDecoration(color: active ? AppColors.primary : Colors.transparent, borderRadius: BorderRadius.circular(16)),
      child: Text(label, style: TextStyle(color: active ? Colors.white : AppColors.textSecondary, fontWeight: FontWeight.w700, fontSize: 13)),
    );
  }

  Widget _buildLoginRow(String time, String info, bool current) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 16),
      child: Row(
        children: [
          Container(
            padding: const EdgeInsets.all(10),
            decoration: BoxDecoration(color: current ? AppColors.success.withValues(alpha: 0.1) : AppColors.textTertiary.withValues(alpha: 0.1), shape: BoxShape.circle),
            child: Icon(current ? Icons.check_circle_rounded : Icons.history_rounded, color: current ? AppColors.success : AppColors.textSecondary, size: 20),
          ),
          const SizedBox(width: 16),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(time, style: const TextStyle(fontWeight: FontWeight.w700, fontSize: 15)),
                Text(info, style: const TextStyle(color: AppColors.textSecondary, fontSize: 13)),
              ],
            ),
          ),
          if (current)
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
              decoration: BoxDecoration(color: AppColors.success.withValues(alpha: 0.1), borderRadius: BorderRadius.circular(8)),
              child: const Text('Actuel', style: TextStyle(color: AppColors.success, fontWeight: FontWeight.w700, fontSize: 11)),
            ),
        ],
      ),
    );
  }

  // ═══ MODALS ═══

  Widget _buildEditProfileOverlay() {
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
                    const Text('Modifier le profil', style: TextStyle(fontWeight: FontWeight.w800, fontSize: 22)),
                    GestureDetector(onTap: () => setState(() => _showEditProfile = false), child: const Icon(Icons.close_rounded)),
                  ]),
                  const SizedBox(height: 20),
                  TextField(controller: _editFirstNameCtrl, decoration: const InputDecoration(labelText: 'Prénom', prefixIcon: Icon(Icons.person_rounded))),
                  const SizedBox(height: 14),
                  TextField(controller: _editLastNameCtrl, decoration: const InputDecoration(labelText: 'Nom', prefixIcon: Icon(Icons.person_outline_rounded))),
                  const SizedBox(height: 14),
                  TextField(controller: _editEmailCtrl, decoration: const InputDecoration(labelText: 'Email', prefixIcon: Icon(Icons.email_rounded))),
                  const SizedBox(height: 14),
                  TextField(controller: _editPhoneCtrl, decoration: const InputDecoration(labelText: 'Téléphone', prefixIcon: Icon(Icons.phone_rounded)),
                    keyboardType: TextInputType.phone),
                  const SizedBox(height: 24),
                  SizedBox(height: 56, child: ElevatedButton(onPressed: _saveProfile, child: const Text('Enregistrer', style: TextStyle(fontWeight: FontWeight.w800)))),
                ],
              ),
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildPasswordChangeOverlay() {
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
                    const Text('Changer mot de passe', style: TextStyle(fontWeight: FontWeight.w800, fontSize: 22)),
                    GestureDetector(onTap: () => setState(() => _showPasswordChange = false), child: const Icon(Icons.close_rounded)),
                  ]),
                  const SizedBox(height: 20),
                  TextField(controller: _currentPassCtrl, obscureText: true, decoration: const InputDecoration(labelText: 'Mot de passe actuel', prefixIcon: Icon(Icons.lock_outline_rounded))),
                  const SizedBox(height: 14),
                  TextField(controller: _newPassCtrl, obscureText: true, decoration: const InputDecoration(labelText: 'Nouveau mot de passe', prefixIcon: Icon(Icons.lock_rounded))),
                  const SizedBox(height: 14),
                  TextField(controller: _confirmPassCtrl, obscureText: true, decoration: const InputDecoration(labelText: 'Confirmer', prefixIcon: Icon(Icons.lock_rounded))),
                  const SizedBox(height: 24),
                  SizedBox(height: 56, child: ElevatedButton(onPressed: _savePassword, child: const Text('Modifier', style: TextStyle(fontWeight: FontWeight.w800)))),
                ],
              ),
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildPinChangeOverlay() {
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
                    const Text('Changer code PIN', style: TextStyle(fontWeight: FontWeight.w800, fontSize: 22)),
                    GestureDetector(onTap: () => setState(() => _showPinChange = false), child: const Icon(Icons.close_rounded)),
                  ]),
                  const SizedBox(height: 20),
                  TextField(controller: _currentPinCtrl, maxLength: 4, obscureText: true, keyboardType: TextInputType.number,
                    decoration: const InputDecoration(labelText: 'PIN actuel', prefixIcon: Icon(Icons.pin_rounded), counterText: '')),
                  const SizedBox(height: 10),
                  TextField(controller: _newPinCtrl, maxLength: 4, obscureText: true, keyboardType: TextInputType.number,
                    decoration: const InputDecoration(labelText: 'Nouveau PIN', prefixIcon: Icon(Icons.pin_rounded), counterText: '')),
                  const SizedBox(height: 10),
                  TextField(controller: _confirmPinCtrl, maxLength: 4, obscureText: true, keyboardType: TextInputType.number,
                    decoration: const InputDecoration(labelText: 'Confirmer PIN', prefixIcon: Icon(Icons.pin_rounded), counterText: '')),
                  const SizedBox(height: 24),
                  SizedBox(height: 56, child: ElevatedButton(onPressed: _submitPinChange, child: const Text('Changer le PIN', style: TextStyle(fontWeight: FontWeight.w800)))),
                ],
              ),
            ),
          ),
        ),
      ),
    );
  }

  Widget _build2FAOverlay() {
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
                    Text(_twoFAStep == 3 ? 'Codes de secours' : 'Configurer 2FA', style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 22)),
                    GestureDetector(onTap: () => setState(() => _twoFAStep = 0), child: const Icon(Icons.close_rounded)),
                  ]),
                  const SizedBox(height: 20),
                  if (_twoFAStep == 1) ...[
                    const Icon(Icons.qr_code_scanner_rounded, size: 100, color: AppColors.primary),
                    const SizedBox(height: 16),
                    const Text('Scannez le code QR avec votre application d\'authentification', textAlign: TextAlign.center, style: TextStyle(fontSize: 14)),
                    const SizedBox(height: 8),
                    Container(
                      padding: const EdgeInsets.all(12),
                      decoration: BoxDecoration(color: AppColors.background, borderRadius: BorderRadius.circular(12)),
                      child: const SelectableText('JBSWY3DPEHPK3PXP', style: TextStyle(fontFamily: 'monospace', fontSize: 16, fontWeight: FontWeight.w700, letterSpacing: 2)),
                    ),
                    const SizedBox(height: 16),
                    const Text('Ou saisissez cette clé manuellement', style: TextStyle(color: AppColors.textSecondary, fontSize: 12)),
                    const SizedBox(height: 24),
                    SizedBox(height: 56, child: ElevatedButton(onPressed: () => setState(() => _twoFAStep = 2), child: const Text('Suivant', style: TextStyle(fontWeight: FontWeight.w800)))),
                  ],
                  if (_twoFAStep == 2) ...[
                    const Text('Saisissez le code à 6 chiffres', textAlign: TextAlign.center, style: TextStyle(fontSize: 14)),
                    const SizedBox(height: 20),
                    TextField(
                      controller: _twoFACodeCtrl,
                      maxLength: 6,
                      keyboardType: TextInputType.number,
                      textAlign: TextAlign.center,
                      style: const TextStyle(fontSize: 32, letterSpacing: 8, fontWeight: FontWeight.w800),
                      decoration: const InputDecoration(counterText: '', border: InputBorder.none, filled: false),
                    ),
                    const SizedBox(height: 24),
                    SizedBox(
                      height: 56,
                      child: ElevatedButton(
                        onPressed: () {
                          if (_twoFACodeCtrl.text.length == 6) {
                            final codes = List.generate(5, (_) => List.generate(8, (_) => (DateTime.now().millisecondsSinceEpoch % 10).toString()).join());
                            context.read<ApiService>().saveBackupCodes(codes);
                            setState(() => _twoFAStep = 3);
                          }
                        },
                        child: const Text('Vérifier', style: TextStyle(fontWeight: FontWeight.w800)),
                      ),
                    ),
                  ],
                  if (_twoFAStep == 3) ...[
                    const Icon(Icons.security_rounded, size: 60, color: AppColors.warning),
                    const SizedBox(height: 16),
                    const Text('Codes de secours', style: TextStyle(fontWeight: FontWeight.w700, fontSize: 18)),
                    const SizedBox(height: 4),
                    const Text('Conservez ces codes en lieu sûr. Ils vous permettront de vous connecter si vous perdez l\'accès à votre application d\'authentification.', textAlign: TextAlign.center, style: TextStyle(color: AppColors.textSecondary, fontSize: 13)),
                    const SizedBox(height: 16),
                    Container(
                      padding: const EdgeInsets.all(16),
                      decoration: BoxDecoration(color: AppColors.background, borderRadius: BorderRadius.circular(16)),
                      child: Column(
                        children: context.watch<ApiService>().backupCodes.map((code) => Padding(
                          padding: const EdgeInsets.symmetric(vertical: 4),
                          child: Text(code, style: const TextStyle(fontFamily: 'monospace', fontWeight: FontWeight.w700, fontSize: 16, letterSpacing: 2)),
                        )).toList(),
                      ),
                    ),
                    const SizedBox(height: 24),
                    SizedBox(
                      height: 56,
                      child: ElevatedButton(
                        onPressed: () {
                          context.read<ApiService>().enable2FA();
                          setState(() => _twoFAStep = 0);
                          ScaffoldMessenger.of(context).showSnackBar(
                            SnackBar(content: const Text('2FA activée avec succès'), backgroundColor: AppColors.success, behavior: SnackBarBehavior.floating, shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16))),
                          );
                        },
                        child: const Text('Activer 2FA', style: TextStyle(fontWeight: FontWeight.w800)),
                      ),
                    ),
                  ],
                ],
              ),
            ),
          ),
        ),
      ),
    );
  }
}
