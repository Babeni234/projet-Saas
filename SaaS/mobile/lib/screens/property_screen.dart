import 'package:flutter/material.dart';
import '../theme/app_colors.dart';
import '../widgets/glass_container.dart';

class PropertyScreen extends StatelessWidget {
  const PropertyScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return SafeArea(
      bottom: false,
      child: SingleChildScrollView(
        physics: const BouncingScrollPhysics(),
        padding: const EdgeInsets.fromLTRB(24, 24, 24, 140),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text('Logement', style: Theme.of(context).textTheme.headlineMedium?.copyWith(fontSize: 34, fontWeight: FontWeight.w800, letterSpacing: -1)),
            Text('Détails de votre bail actif', style: TextStyle(color: AppColors.textSecondary, fontSize: 16, fontWeight: FontWeight.w500)),
            const SizedBox(height: 32),

            Container(
              height: 260,
              width: double.infinity,
              decoration: BoxDecoration(
                borderRadius: BorderRadius.circular(36),
                image: const DecorationImage(
                  image: NetworkImage('https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?q=80&w=800&auto=format&fit=crop'),
                  fit: BoxFit.cover,
                ),
                boxShadow: [
                  BoxShadow(color: Colors.black.withValues(alpha: 0.2), blurRadius: 30, offset: const Offset(0, 15)),
                ],
              ),
              child: Container(
                decoration: BoxDecoration(
                  borderRadius: BorderRadius.circular(36),
                  gradient: LinearGradient(
                    begin: Alignment.topCenter,
                    end: Alignment.bottomCenter,
                    colors: [Colors.transparent, Colors.black.withValues(alpha: 0.8)],
                  ),
                ),
                padding: const EdgeInsets.all(28),
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.end,
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Container(
                      padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 6),
                      decoration: BoxDecoration(
                        color: AppColors.success,
                        borderRadius: BorderRadius.circular(12),
                      ),
                      child: const Text('BAIL ACTIF', style: TextStyle(color: Colors.white, fontSize: 10, fontWeight: FontWeight.w900, letterSpacing: 1)),
                    ),
                    const SizedBox(height: 12),
                    const Text('Appartement T3 - Les Lilas', style: TextStyle(color: Colors.white, fontSize: 24, fontWeight: FontWeight.w800, letterSpacing: -0.5)),
                    const SizedBox(height: 4),
                    const Text('14 Rue des fleurs, 75000 Paris', style: TextStyle(color: Colors.white70, fontSize: 15, fontWeight: FontWeight.w500)),
                  ],
                ),
              ),
            ),
            const SizedBox(height: 32),

            _buildSectionHeader('CARACTÉRISTIQUES'),
            const SizedBox(height: 12),
            Row(
              children: [
                Expanded(child: _buildSpecItem(Icons.square_foot_rounded, 'Surface', '65 m²')),
                const SizedBox(width: 16),
                Expanded(child: _buildSpecItem(Icons.bed_rounded, 'Chambres', '2')),
              ],
            ),
            const SizedBox(height: 16),
            Row(
              children: [
                Expanded(child: _buildSpecItem(Icons.bathtub_rounded, 'Bains', '1')),
                const SizedBox(width: 16),
                Expanded(child: _buildSpecItem(Icons.balcony_rounded, 'Balcon', 'Oui')),
              ],
            ),
            const SizedBox(height: 32),

            _buildSectionHeader('DÉTAILS DU CONTRAT'),
            const SizedBox(height: 12),
            GlassContainer(
              padding: EdgeInsets.zero,
              borderRadius: 32,
              child: Column(
                children: [
                  _buildBailRow(Icons.payments_outlined, 'Loyer mensuel', '850,00 €', true),
                  _buildBailRow(Icons.receipt_long_outlined, 'Charges', '120,00 €', true),
                  _buildBailRow(Icons.calendar_today_outlined, 'Début du bail', '01 Mar 2024', true),
                  _buildBailRow(Icons.calendar_month_outlined, 'Fin du bail', '28 Fév 2026', true),
                  _buildBailRow(Icons.corporate_fare_rounded, 'Bailleur', 'SCI Habitatum', false),
                ],
              ),
            ),
            const SizedBox(height: 32),

            _buildSectionHeader('DOCUMENTS OFFICIELS'),
            const SizedBox(height: 12),
            _buildDocItem(context, 'Contrat de bail', 'PDF • 2.4 Mo', Icons.description_rounded, AppColors.primary),
            const SizedBox(height: 12),
            _buildDocItem(context, 'État des lieux', 'PDF • 1.8 Mo', Icons.assignment_rounded, AppColors.success),
            const SizedBox(height: 12),
            _buildDocItem(context, 'Règlement', 'PDF • 0.5 Mo', Icons.gavel_rounded, AppColors.warning),
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

  Widget _buildSpecItem(IconData icon, String label, String value) {
    return GlassContainer(
      padding: const EdgeInsets.all(20),
      borderRadius: 24,
      child: Row(
        children: [
          Container(
            padding: const EdgeInsets.all(10),
            decoration: BoxDecoration(color: AppColors.primary.withValues(alpha: 0.1), borderRadius: BorderRadius.circular(12)),
            child: Icon(icon, color: AppColors.primary, size: 22),
          ),
          const SizedBox(width: 16),
          Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(label, style: const TextStyle(fontSize: 11, color: AppColors.textSecondary, fontWeight: FontWeight.w600)),
              Text(value, style: const TextStyle(fontSize: 16, fontWeight: FontWeight.w800)),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildBailRow(IconData icon, String label, String value, bool showDivider) {
    return Column(
      children: [
        Padding(
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 18),
          child: Row(
            children: [
              Icon(icon, color: AppColors.primary, size: 20),
              const SizedBox(width: 16),
              Text(label, style: const TextStyle(color: AppColors.textSecondary, fontSize: 15, fontWeight: FontWeight.w500)),
              const Spacer(),
              Text(value, style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 15)),
            ],
          ),
        ),
        if (showDivider)
          const Padding(padding: EdgeInsets.only(left: 64), child: Divider(height: 1, color: AppColors.background)),
      ],
    );
  }

  Widget _buildDocItem(BuildContext context, String name, String meta, IconData icon, Color color) {
    return GlassContainer(
      padding: const EdgeInsets.all(16),
      borderRadius: 24,
      child: Row(
        children: [
          Container(
            padding: const EdgeInsets.all(12),
            decoration: BoxDecoration(color: color.withValues(alpha: 0.1), borderRadius: BorderRadius.circular(16)),
            child: Icon(icon, color: color, size: 24),
          ),
          const SizedBox(width: 16),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(name, style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 16)),
                Text(meta, style: const TextStyle(color: AppColors.textSecondary, fontSize: 13, fontWeight: FontWeight.w500)),
              ],
            ),
          ),
          Container(
            padding: const EdgeInsets.all(8),
            decoration: BoxDecoration(color: AppColors.primary.withValues(alpha: 0.1), shape: BoxShape.circle),
            child: const Icon(Icons.download_rounded, color: AppColors.primary, size: 20),
          ),
        ],
      ),
    );
  }

}
