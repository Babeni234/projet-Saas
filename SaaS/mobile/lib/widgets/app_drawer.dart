import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../theme/app_colors.dart';
import '../services/api_service.dart';

class AppDrawer extends StatelessWidget {
  final int activeIndex;
  final Function(int) onItemTap;
  final bool isDark;

  const AppDrawer({
    super.key,
    required this.activeIndex,
    required this.onItemTap,
    this.isDark = false,
  });

  @override
  Widget build(BuildContext context) {
    final apiService = context.watch<ApiService>();
    final userName = apiService.tenantFullName.isNotEmpty ? apiService.tenantFullName : (apiService.user?['name'] ?? 'Locataire');
    final userEmail = apiService.user?['email'] ?? '';
    final company = apiService.company;
    final companyName = (company?['name'] as String?) ?? (company?['nom'] as String?) ?? 'Habitatum';
    final companyLogo = company?['logo_url'] as String?;

    return Drawer(
      width: 300,
      backgroundColor: isDark ? const Color(0xFF090D16) : const Color(0xFFF8FAFC),
      child: SafeArea(
        child: Column(
          children: [
            Container(
              padding: const EdgeInsets.fromLTRB(20, 32, 20, 24),
              decoration: BoxDecoration(
                gradient: AppColors.walletGradient,
                borderRadius: const BorderRadius.vertical(bottom: Radius.circular(32)),
              ),
              child: Column(
                children: [
                  Row(
                    children: [
                      Container(
                        width: 56,
                        height: 56,
                        decoration: BoxDecoration(
                          shape: BoxShape.circle,
                          border: Border.all(color: Colors.white.withValues(alpha: 0.3), width: 2),
                        ),
                        child: const Icon(Icons.person_rounded, color: Colors.white, size: 28),
                      ),
                      const SizedBox(width: 14),
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(userName, style: const TextStyle(color: Colors.white, fontWeight: FontWeight.w700, fontSize: 17)),
                            const SizedBox(height: 2),
                            Text(userEmail, style: TextStyle(color: Colors.white.withValues(alpha: 0.7), fontSize: 12)),
                          ],
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 16),
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
                    decoration: BoxDecoration(
                      color: Colors.white.withValues(alpha: 0.15),
                      borderRadius: BorderRadius.circular(12),
                    ),
                    child: Row(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        Icon(Icons.verified_rounded, color: AppColors.success, size: 14),
                        const SizedBox(width: 6),
                        const Text('Locataire vérifié', style: TextStyle(color: Colors.white, fontSize: 12, fontWeight: FontWeight.w600)),
                      ],
                    ),
                  ),
                ],
              ),
            ),

            // Company info section
            if (company != null)
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 12),
                child: Row(
                  children: [
                    Container(
                      width: 32,
                      height: 32,
                      decoration: BoxDecoration(
                        shape: BoxShape.circle,
                        color: AppColors.primary.withValues(alpha: 0.1),
                      ),
                      child: companyLogo != null && companyLogo.isNotEmpty
                          ? ClipOval(
                              child: Image.network(
                                companyLogo,
                                width: 32,
                                height: 32,
                                fit: BoxFit.cover,
                                errorBuilder: (context, error, stackTrace) {
                                  return Icon(Icons.business_rounded, color: AppColors.primary, size: 16);
                                },
                              ),
                            )
                          : Icon(Icons.business_rounded, color: AppColors.primary, size: 16),
                    ),
                    const SizedBox(width: 12),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(companyName, style: TextStyle(color: AppColors.textPrimary, fontWeight: FontWeight.w600, fontSize: 14)),
                          Text('Gestionnaire', style: TextStyle(color: AppColors.textSecondary, fontSize: 11)),
                        ],
                      ),
                    ),
                  ],
                ),
              ),

            Expanded(
              child: ListView(
                padding: const EdgeInsets.symmetric(vertical: 12),
                children: [
                  _drawerItem(Icons.dashboard_rounded, 'Vue d\'ensemble', 0, context),
                  _drawerItem(Icons.home_work_rounded, 'Logements & Baux', 1, context),
                  _drawerItem(Icons.receipt_long_rounded, 'Loyer', 2, context),
                  _drawerItem(Icons.water_drop_rounded, 'Eau & Électricité', 3, context),
                  _drawerItem(Icons.description_rounded, 'Quittances & Reçus', 4, context),
                  _drawerItem(Icons.history_rounded, 'Anciens Contrats', 5, context),
                  _drawerItem(Icons.support_agent_rounded, 'Support & Messagerie', 6, context),
                  _drawerItem(Icons.person_rounded, 'Profil & Paramètres', 7, context),
                ],
              ),
            ),

            Container(
              padding: const EdgeInsets.all(20),
              child: Row(
                children: [
                  Icon(Icons.logout_rounded, size: 18, color: AppColors.textSecondary),
                  const SizedBox(width: 12),
                  const Text('Déconnexion', style: TextStyle(color: AppColors.textSecondary, fontWeight: FontWeight.w600)),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _drawerItem(IconData icon, String label, int index, BuildContext context) {
    final isActive = activeIndex == index;
    return Container(
      margin: const EdgeInsets.symmetric(horizontal: 12, vertical: 2),
      decoration: BoxDecoration(
        color: isActive ? AppColors.primary.withValues(alpha: 0.1) : Colors.transparent,
        borderRadius: BorderRadius.circular(14),
      ),
      child: ListTile(
        leading: Icon(icon, color: isActive ? AppColors.primary : AppColors.textSecondary, size: 22),
        title: Text(label, style: TextStyle(
          color: isActive ? AppColors.primary : AppColors.textPrimary,
          fontWeight: isActive ? FontWeight.w700 : FontWeight.w500,
          fontSize: 15,
        )),
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(14)),
        onTap: () {
          Navigator.pop(context);
          onItemTap(index);
        },
      ),
    );
  }
}
