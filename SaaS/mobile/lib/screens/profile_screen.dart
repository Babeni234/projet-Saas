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
        padding: const EdgeInsets.fromLTRB(24, 24, 24, 120),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            Text('Profil & Paramètres', style: Theme.of(context).textTheme.headlineMedium?.copyWith(fontSize: 28)),
            const SizedBox(height: 8),
            Text('Gérez vos informations, sécurité et préférences.', style: Theme.of(context).textTheme.bodyMedium?.copyWith(fontSize: 15)),
            const SizedBox(height: 28),

            GlassContainer(
              padding: const EdgeInsets.all(24),
              borderRadius: 28,
              child: Row(
                children: [
                  Container(
                    width: 72, height: 72,
                    decoration: BoxDecoration(
                      gradient: AppColors.actionGradient,
                      shape: BoxShape.circle,
                      boxShadow: [BoxShadow(color: AppColors.primary.withValues(alpha: 0.3), blurRadius: 15, offset: const Offset(0, 8))],
                    ),
                    child: const Center(child: Icon(Icons.person_rounded, color: Colors.white, size: 36)),
                  ),
                  const SizedBox(width: 20),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        const Text('Thomas Dubois', style: TextStyle(fontSize: 20, fontWeight: FontWeight.w700)),
                        const SizedBox(height: 4),
                        const Text('thomas.dubois@email.com', style: TextStyle(color: AppColors.textSecondary, fontSize: 14)),
                        const SizedBox(height: 6),
                        Container(
                          padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 4),
                          decoration: BoxDecoration(color: AppColors.success.withValues(alpha: 0.12), borderRadius: BorderRadius.circular(12)),
                          child: const Text('Locataire vérifié', style: TextStyle(color: AppColors.success, fontSize: 12, fontWeight: FontWeight.w600)),
                        ),
                      ],
                    ),
                  ),
                  IconButton(onPressed: () {}, icon: const Icon(Icons.edit_rounded, color: AppColors.primary)),
                ],
              ),
            ),
            const SizedBox(height: 28),

            Text('Informations personnelles', style: Theme.of(context).textTheme.titleLarge),
            const SizedBox(height: 16),
            GlassContainer(
              padding: const EdgeInsets.all(20),
              child: Column(
                children: [
                  _buildInfoRow(Icons.person_rounded, 'Nom', 'Thomas Dubois'),
                  const Divider(height: 24, color: AppColors.background),
                  _buildInfoRow(Icons.phone_rounded, 'Téléphone', '06 12 34 56 78'),
                  const Divider(height: 24, color: AppColors.background),
                  _buildInfoRow(Icons.email_rounded, 'Email', 'thomas.dubois@email.com'),
                  const Divider(height: 24, color: AppColors.background),
                  _buildInfoRow(Icons.location_on_rounded, 'Adresse', '14 Rue des fleurs, 75000 Paris'),
                ],
              ),
            ),
            const SizedBox(height: 28),

            Text('Sécurité', style: Theme.of(context).textTheme.titleLarge),
            const SizedBox(height: 16),
            GlassContainer(
              padding: const EdgeInsets.all(20),
              child: Column(
                children: [
                  _buildSecurityItem(Icons.lock_rounded, 'Mot de passe', 'Modifier', () {}),
                  const Divider(height: 24, color: AppColors.background),
                  _build2FAItem(),
                  const Divider(height: 24, color: AppColors.background),
                  _buildSecurityItem(Icons.fingerprint_rounded, 'Connexion biométrique', _biometric ? 'Activée' : 'Désactivée', () {
                    setState(() => _biometric = !_biometric);
                  }),
                ],
              ),
            ),
            const SizedBox(height: 28),

            Text('Apparence', style: Theme.of(context).textTheme.titleLarge),
            const SizedBox(height: 16),
            GlassContainer(
              padding: const EdgeInsets.all(20),
              child: Column(
                children: [
                  Row(
                    children: [
                      Container(
                        padding: const EdgeInsets.all(8),
                        decoration: BoxDecoration(color: AppColors.primary.withValues(alpha: 0.1), shape: BoxShape.circle),
                        child: Icon(isDark ? Icons.dark_mode_rounded : Icons.light_mode_rounded, color: AppColors.primary, size: 20),
                      ),
                      const SizedBox(width: 14),
                      const Expanded(child: Text('Thème d\'affichage', style: TextStyle(fontWeight: FontWeight.w600, fontSize: 15))),
                      GestureDetector(
                        onTap: () => themeProvider.toggleTheme(),
                        child: Container(
                          padding: const EdgeInsets.all(4),
                          decoration: BoxDecoration(
                            color: AppColors.background,
                            borderRadius: BorderRadius.circular(24),
                            border: Border.all(color: AppColors.textTertiary.withValues(alpha: 0.3)),
                          ),
                          child: Row(
                            mainAxisSize: MainAxisSize.min,
                            children: [
                              Container(
                                padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 6),
                                decoration: BoxDecoration(
                                  color: !isDark ? AppColors.primary : Colors.transparent,
                                  borderRadius: BorderRadius.circular(20),
                                ),
                                child: Text('Clair', style: TextStyle(
                                  color: !isDark ? Colors.white : AppColors.textSecondary,
                                  fontSize: 13, fontWeight: FontWeight.w600,
                                )),
                              ),
                              Container(
                                padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 6),
                                decoration: BoxDecoration(
                                  color: isDark ? AppColors.primary : Colors.transparent,
                                  borderRadius: BorderRadius.circular(20),
                                ),
                                child: Text('Sombre', style: TextStyle(
                                  color: isDark ? Colors.white : AppColors.textSecondary,
                                  fontSize: 13, fontWeight: FontWeight.w600,
                                )),
                              ),
                            ],
                          ),
                        ),
                      ),
                    ],
                  ),
                  const Divider(height: 24, color: AppColors.background),
                  Row(
                    children: [
                      Container(
                        padding: const EdgeInsets.all(8),
                        decoration: BoxDecoration(color: AppColors.primary.withValues(alpha: 0.1), shape: BoxShape.circle),
                        child: const Icon(Icons.language_rounded, color: AppColors.primary, size: 20),
                      ),
                      const SizedBox(width: 14),
                      const Expanded(child: Text('Langue', style: TextStyle(fontWeight: FontWeight.w600, fontSize: 15))),
                      GestureDetector(
                        onTap: () => localeProvider.toggleLocale(),
                        child: Container(
                          padding: const EdgeInsets.all(4),
                          decoration: BoxDecoration(
                            color: AppColors.background,
                            borderRadius: BorderRadius.circular(24),
                            border: Border.all(color: AppColors.textTertiary.withValues(alpha: 0.3)),
                          ),
                          child: Row(
                            mainAxisSize: MainAxisSize.min,
                            children: [
                              Container(
                                padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 6),
                                decoration: BoxDecoration(
                                  color: language == 'fr' ? AppColors.primary : Colors.transparent,
                                  borderRadius: BorderRadius.circular(20),
                                ),
                                child: Text('FR', style: TextStyle(
                                  color: language == 'fr' ? Colors.white : AppColors.textSecondary,
                                  fontSize: 13, fontWeight: FontWeight.w600,
                                )),
                              ),
                              Container(
                                padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 6),
                                decoration: BoxDecoration(
                                  color: language == 'en' ? AppColors.primary : Colors.transparent,
                                  borderRadius: BorderRadius.circular(20),
                                ),
                                child: Text('EN', style: TextStyle(
                                  color: language == 'en' ? Colors.white : AppColors.textSecondary,
                                  fontSize: 13, fontWeight: FontWeight.w600,
                                )),
                              ),
                            ],
                          ),
                        ),
                      ),
                    ],
                  ),
                ],
              ),
            ),
            const SizedBox(height: 28),

            Text('Notifications', style: Theme.of(context).textTheme.titleLarge),
            const SizedBox(height: 16),
            GlassContainer(
              padding: const EdgeInsets.all(20),
              child: Column(
                children: [
                  _buildSettingItem(Icons.notifications_rounded, 'Notifications push', _pushNotifications, (v) => setState(() => _pushNotifications = v)),
                  const Divider(height: 24, color: AppColors.background),
                  _buildSettingItem(Icons.payments_rounded, 'Rappel de loyer', _rentReminder, (v) => setState(() => _rentReminder = v)),
                  const Divider(height: 24, color: AppColors.background),
                  _buildSettingItem(Icons.email_rounded, 'Reçus par email', _emailReceipts, (v) => setState(() => _emailReceipts = v)),
                  const Divider(height: 24, color: AppColors.background),
                  _buildSettingItem(Icons.sms_rounded, 'Alertes SMS', false, (_) {}),
                ],
              ),
            ),
            const SizedBox(height: 28),

            Text('Journal de connexion', style: Theme.of(context).textTheme.titleLarge),
            const SizedBox(height: 16),
            GlassContainer(
              padding: const EdgeInsets.all(20),
              child: Column(
                children: [
                  _buildLoginEntry('Aujourd\'hui', '10:32', 'Chrome, Windows', 'Paris, France'),
                  const Divider(height: 20, color: AppColors.background),
                  _buildLoginEntry('Hier', '18:15', 'Safari, iPhone', 'Paris, France'),
                  const Divider(height: 20, color: AppColors.background),
                  _buildLoginEntry('15 Juin 2026', '09:40', 'Firefox, MacOS', 'Paris, France'),
                ],
              ),
            ),
            const SizedBox(height: 28),

            SizedBox(
              height: 56,
              child: OutlinedButton.icon(
                onPressed: () {},
                style: OutlinedButton.styleFrom(
                  foregroundColor: AppColors.error,
                  side: const BorderSide(color: AppColors.error, width: 1.5),
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
                ),
                icon: const Icon(Icons.logout_rounded),
                label: const Text('Déconnexion', style: TextStyle(fontWeight: FontWeight.w600, fontSize: 16)),
              ),
            ),
            const SizedBox(height: 16),
          ],
        ),
      ),
    );
  }

  Widget _buildInfoRow(IconData icon, String label, String value) {
    return Row(
      children: [
        Container(
          padding: const EdgeInsets.all(8),
          decoration: BoxDecoration(color: AppColors.primary.withValues(alpha: 0.1), shape: BoxShape.circle),
          child: Icon(icon, color: AppColors.primary, size: 20),
        ),
        const SizedBox(width: 14),
        Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(label, style: const TextStyle(color: AppColors.textSecondary, fontSize: 12)),
            Text(value, style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 15)),
          ],
        ),
      ],
    );
  }

  Widget _buildSecurityItem(IconData icon, String label, String value, VoidCallback onTap) {
    return Row(
      children: [
        Container(
          padding: const EdgeInsets.all(8),
          decoration: BoxDecoration(color: AppColors.primary.withValues(alpha: 0.1), shape: BoxShape.circle),
          child: Icon(icon, color: AppColors.primary, size: 20),
        ),
        const SizedBox(width: 14),
        Expanded(child: Text(label, style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 15))),
        GestureDetector(
          onTap: onTap,
          child: Container(
            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
            decoration: BoxDecoration(
              color: AppColors.primary.withValues(alpha: 0.1),
              borderRadius: BorderRadius.circular(12),
            ),
            child: Text(value, style: TextStyle(color: AppColors.primary, fontSize: 12, fontWeight: FontWeight.w600)),
          ),
        ),
      ],
    );
  }

  Widget _build2FAItem() {
    return Row(
      children: [
        Container(
          padding: const EdgeInsets.all(8),
          decoration: BoxDecoration(color: AppColors.primary.withValues(alpha: 0.1), shape: BoxShape.circle),
          child: const Icon(Icons.verified_user_rounded, color: AppColors.primary, size: 20),
        ),
        const SizedBox(width: 14),
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const Text('Validation double facteur (2FA)', style: TextStyle(fontWeight: FontWeight.w600, fontSize: 15)),
              const Text('Sécurisez votre compte via une clé TOTP', style: TextStyle(color: AppColors.textSecondary, fontSize: 12)),
            ],
          ),
        ),
        Container(
          padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
          decoration: BoxDecoration(
            color: AppColors.warning.withValues(alpha: 0.12),
            borderRadius: BorderRadius.circular(12),
          ),
          child: const Text('Inactive', style: TextStyle(color: AppColors.warning, fontSize: 12, fontWeight: FontWeight.w600)),
        ),
      ],
    );
  }

  Widget _buildSettingItem(IconData icon, String label, bool value, ValueChanged<bool> onChanged) {
    return Row(
      children: [
        Container(
          padding: const EdgeInsets.all(8),
          decoration: BoxDecoration(color: AppColors.primary.withValues(alpha: 0.1), shape: BoxShape.circle),
          child: Icon(icon, color: AppColors.primary, size: 20),
        ),
        const SizedBox(width: 14),
        Expanded(child: Text(label, style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 15))),
        Switch.adaptive(
          value: value,
          onChanged: onChanged,
          activeTrackColor: AppColors.primary,
        ),
      ],
    );
  }

  Widget _buildLoginEntry(String date, String time, String device, String location) {
    return Row(
      children: [
        Container(
          padding: const EdgeInsets.all(8),
          decoration: BoxDecoration(color: AppColors.primary.withValues(alpha: 0.08), shape: BoxShape.circle),
          child: const Icon(Icons.login_rounded, color: AppColors.primary, size: 18),
        ),
        const SizedBox(width: 14),
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text('$date à $time', style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 14)),
              Text('$device • $location', style: const TextStyle(color: AppColors.textSecondary, fontSize: 12)),
            ],
          ),
        ),
      ],
    );
  }
}
