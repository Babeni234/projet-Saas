import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/theme_provider.dart';
import '../providers/locale_provider.dart';
import '../theme/app_colors.dart';
import '../widgets/glass_container.dart';

class ProfileScreen extends StatefulWidget {
  const ProfileScreen({super.key});

  @override
  State<ProfileScreen> createState() => _ProfileScreenState();
}

class _ProfileScreenState extends State<ProfileScreen> {
  bool _pushNotifications = true;
  bool _rentReminder = true;
  bool _emailReceipts = false;
  bool _biometric = true;

  @override
  Widget build(BuildContext context) {
    final themeProvider = context.watch<ThemeProvider>();
    final localeProvider = context.watch<LocaleProvider>();
    final isDark = themeProvider.isDark;
    final language = localeProvider.isFrench ? 'fr' : 'en';

    return SafeArea(
      bottom: false,
      child: SingleChildScrollView(
        physics: const BouncingScrollPhysics(),
        padding: const EdgeInsets.fromLTRB(24, 24, 24, 140),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Header Section
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
                Container(
                  padding: const EdgeInsets.all(4),
                  decoration: BoxDecoration(
                    color: AppColors.primary.withValues(alpha: 0.1),
                    shape: BoxShape.circle,
                  ),
                  child: IconButton(
                    onPressed: () {},
                    icon: const Icon(Icons.settings_outlined, color: AppColors.primary),
                  ),
                ),
              ],
            ),
            const SizedBox(height: 32),

            // Profile Card
            GlassContainer(
              padding: const EdgeInsets.all(24),
              borderRadius: 32,
              child: Column(
                children: [
                  Stack(
                    alignment: Alignment.bottomRight,
                    children: [
                      Container(
                        width: 100, height: 100,
                        decoration: BoxDecoration(
                          shape: BoxShape.circle,
                          gradient: AppColors.actionGradient,
                          boxShadow: [
                            BoxShadow(
                              color: AppColors.primary.withValues(alpha: 0.3),
                              blurRadius: 20,
                              offset: const Offset(0, 10),
                            ),
                          ],
                        ),
                        child: const Center(child: Icon(Icons.person_rounded, color: Colors.white, size: 50)),
                      ),
                      Container(
                        padding: const EdgeInsets.all(6),
                        decoration: const BoxDecoration(color: Colors.white, shape: BoxShape.circle),
                        child: Container(
                          padding: const EdgeInsets.all(4),
                          decoration: const BoxDecoration(color: AppColors.success, shape: BoxShape.circle),
                          child: const Icon(Icons.check_rounded, color: Colors.white, size: 14),
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 20),
                  const Text('Thomas Dubois', style: TextStyle(fontSize: 24, fontWeight: FontWeight.w800, letterSpacing: -0.5)),
                  const SizedBox(height: 4),
                  const Text('thomas.dubois@email.com', style: TextStyle(color: AppColors.textSecondary, fontSize: 15, fontWeight: FontWeight.w500)),
                  const SizedBox(height: 20),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      _buildHeaderAction(Icons.edit_rounded, 'Éditer'),
                      const SizedBox(width: 12),
                      _buildHeaderAction(Icons.share_rounded, 'Partager'),
                    ],
                  ),
                ],
              ),
            ),
            const SizedBox(height: 32),

            _buildSectionHeader('INFORMATIONS PERSONNELLES'),
            const SizedBox(height: 12),
            GlassContainer(
              padding: EdgeInsets.zero,
              borderRadius: 24,
              child: Column(
                children: [
                  _buildListTile(Icons.person_outline_rounded, 'Nom', 'Thomas Dubois', showDivider: true),
                  _buildListTile(Icons.phone_iphone_rounded, 'Téléphone', '06 12 34 56 78', showDivider: true),
                  _buildListTile(Icons.mail_outline_rounded, 'Email', 'thomas.dubois@email.com', showDivider: true),
                  _buildListTile(Icons.location_on_outlined, 'Adresse', '14 Rue des fleurs, 75000 Paris'),
                ],
              ),
            ),
            const SizedBox(height: 32),

            _buildSectionHeader('SÉCURITÉ ET ACCÈS'),
            const SizedBox(height: 12),
            GlassContainer(
              padding: EdgeInsets.zero,
              borderRadius: 24,
              child: Column(
                children: [
                  _buildListTile(Icons.lock_reset_rounded, 'Mot de passe', 'Modifier', isAction: true, showDivider: true),
                  _buildListTile(Icons.verified_user_outlined, 'Double facteur (2FA)', 'Désactivé', isAction: true, showDivider: true, color: AppColors.warning),
                  _buildListTile(Icons.fingerprint_rounded, 'Biométrie', _biometric ? 'Activée' : 'Désactivée', isAction: true, onTap: () => setState(() => _biometric = !_biometric)),
                ],
              ),
            ),
            const SizedBox(height: 32),

            _buildSectionHeader('PRÉFÉRENCES'),
            const SizedBox(height: 12),
            GlassContainer(
              padding: EdgeInsets.zero,
              borderRadius: 24,
              child: Column(
                children: [
                  _buildPreferenceRow(
                    isDark ? Icons.dark_mode_rounded : Icons.light_mode_rounded,
                    'Thème d\'affichage',
                    Row(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        _buildThemeToggle(isDark, themeProvider),
                      ],
                    ),
                    showDivider: true,
                  ),
                  _buildPreferenceRow(
                    Icons.language_rounded,
                    'Langue',
                    _buildLanguageToggle(language, localeProvider),
                    showDivider: true,
                  ),
                  _buildPreferenceRow(
                    Icons.notifications_none_rounded,
                    'Notifications Push',
                    Switch.adaptive(value: _pushNotifications, onChanged: (v) => setState(() => _pushNotifications = v), activeColor: AppColors.primary),
                    showDivider: true,
                  ),
                  _buildPreferenceRow(
                    Icons.receipt_long_outlined,
                    'Reçus par email',
                    Switch.adaptive(value: _emailReceipts, onChanged: (v) => setState(() => _emailReceipts = v), activeColor: AppColors.primary),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 32),

            _buildSectionHeader('HISTORIQUE DE CONNEXION'),
            const SizedBox(height: 12),
            GlassContainer(
              padding: const EdgeInsets.symmetric(vertical: 8),
              borderRadius: 24,
              child: Column(
                children: [
                  _buildLoginRow('Aujourd\'hui, 10:32', 'Chrome • Paris, FR', true),
                  const Padding(padding: EdgeInsets.symmetric(horizontal: 20), child: Divider(height: 1, color: AppColors.background)),
                  _buildLoginRow('Hier, 18:15', 'iPhone 15 • Paris, FR', false),
                ],
              ),
            ),
            const SizedBox(height: 40),

            SizedBox(
              width: double.infinity,
              height: 64,
              child: ElevatedButton.icon(
                onPressed: () {},
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

  Widget _buildHeaderAction(IconData icon, String label) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 12),
      decoration: BoxDecoration(
        color: AppColors.primary.withValues(alpha: 0.08),
        borderRadius: BorderRadius.circular(16),
      ),
      child: Row(
        children: [
          Icon(icon, color: AppColors.primary, size: 18),
          const SizedBox(width: 8),
          Text(label, style: const TextStyle(color: AppColors.primary, fontWeight: FontWeight.w700, fontSize: 14)),
        ],
      ),
    );
  }

  Widget _buildListTile(IconData icon, String label, String value, {bool showDivider = false, bool isAction = false, VoidCallback? onTap, Color? color}) {
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
                  decoration: BoxDecoration(color: (color ?? AppColors.primary).withValues(alpha: 0.1), borderRadius: BorderRadius.circular(12)),
                  child: Icon(icon, color: color ?? AppColors.primary, size: 22),
                ),
                const SizedBox(width: 16),
                Expanded(child: Text(label, style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 16))),
                if (isAction)
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 8),
                    decoration: BoxDecoration(
                      color: (color ?? AppColors.primary).withValues(alpha: 0.1),
                      borderRadius: BorderRadius.circular(12),
                    ),
                    child: Text(value, style: TextStyle(color: color ?? AppColors.primary, fontSize: 13, fontWeight: FontWeight.w700)),
                  )
                else
                  Text(value, style: const TextStyle(color: AppColors.textSecondary, fontSize: 15, fontWeight: FontWeight.w500)),
              ],
            ),
          ),
          if (showDivider)
            const Padding(
              padding: EdgeInsets.only(left: 68),
              child: Divider(height: 1, color: AppColors.background),
            ),
        ],
      ),
    );
  }

  Widget _buildPreferenceRow(IconData icon, String label, Widget trailing, {bool showDivider = false}) {
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
        if (showDivider)
          const Padding(
            padding: EdgeInsets.only(left: 68),
            child: Divider(height: 1, color: AppColors.background),
          ),
      ],
    );
  }

  Widget _buildThemeToggle(bool isDark, ThemeProvider provider) {
    return GestureDetector(
      onTap: () => provider.toggleTheme(),
      child: Container(
        padding: const EdgeInsets.all(4),
        decoration: BoxDecoration(color: AppColors.background, borderRadius: BorderRadius.circular(20)),
        child: Row(
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
      decoration: BoxDecoration(
        color: active ? AppColors.primary : Colors.transparent,
        borderRadius: BorderRadius.circular(16),
      ),
      child: Text(
        label,
        style: TextStyle(
          color: active ? Colors.white : AppColors.textSecondary,
          fontWeight: FontWeight.w700,
          fontSize: 13,
        ),
      ),
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

}
