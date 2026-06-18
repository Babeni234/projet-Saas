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
        padding: const EdgeInsets.fromLTRB(24, 24, 24, 120),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            Text(
              'Logements & Baux',
              style: Theme.of(context).textTheme.headlineMedium?.copyWith(fontSize: 28),
            ),
            const SizedBox(height: 8),
            Text(
              'Consultez les détails de votre location en cours.',
              style: Theme.of(context).textTheme.bodyMedium?.copyWith(fontSize: 15),
            ),
            const SizedBox(height: 24),

            Container(
              height: 240,
              decoration: BoxDecoration(
                borderRadius: BorderRadius.circular(32),
                color: Colors.white,
                boxShadow: [
                  BoxShadow(
                    color: Colors.black.withValues(alpha: 0.05),
                    blurRadius: 20,
                    offset: const Offset(0, 10),
                  ),
                ],
                image: const DecorationImage(
                  image: NetworkImage('https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?q=80&w=800&auto=format&fit=crop'),
                  fit: BoxFit.cover,
                ),
              ),
              child: Container(
                decoration: BoxDecoration(
                  borderRadius: BorderRadius.circular(32),
                  gradient: LinearGradient(
                    begin: Alignment.topCenter,
                    end: Alignment.bottomCenter,
                    colors: [Colors.transparent, Colors.black.withValues(alpha: 0.7)],
                  ),
                ),
                padding: const EdgeInsets.all(24),
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.end,
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Container(
                      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 4),
                      decoration: BoxDecoration(
                        color: AppColors.success.withValues(alpha: 0.9),
                        borderRadius: BorderRadius.circular(10),
                      ),
                      child: const Text('Bail actif', style: TextStyle(color: Colors.white, fontSize: 11, fontWeight: FontWeight.w600)),
                    ),
                    const SizedBox(height: 8),
                    const Text('Appartement T3 - Les Lilas', style: TextStyle(color: Colors.white, fontSize: 20, fontWeight: FontWeight.bold)),
                    const SizedBox(height: 4),
                    const Text('14 Rue des fleurs, 75000 Paris', style: TextStyle(color: Colors.white70, fontSize: 14)),
                  ],
                ),
              ),
            ),
            const SizedBox(height: 32),

            Row(
              children: [
                Expanded(child: _buildSpecItem(Icons.square_foot_rounded, 'Surface', '65 m²')),
                const SizedBox(width: 12),
                Expanded(child: _buildSpecItem(Icons.bed_rounded, 'Chambres', '2')),
              ],
            ),
            const SizedBox(height: 12),
            Row(
              children: [
                Expanded(child: _buildSpecItem(Icons.bathtub_rounded, 'Salles de bain', '1')),
                const SizedBox(width: 12),
                Expanded(child: _buildSpecItem(Icons.balcony_rounded, 'Balcon', 'Oui')),
              ],
            ),
            const SizedBox(height: 32),

            Text('Détails du bail', style: Theme.of(context).textTheme.titleLarge),
            const SizedBox(height: 16),
            GlassContainer(
              padding: const EdgeInsets.all(20),
              child: Column(
                children: [
                  _buildBailRow(Icons.euro_rounded, 'Loyer mensuel', '850,00 €'),
                  const Divider(height: 24, color: AppColors.background),
                  _buildBailRow(Icons.receipt_long_rounded, 'Charges', '120,00 € / mois'),
                  const Divider(height: 24, color: AppColors.background),
                  _buildBailRow(Icons.calendar_today_rounded, 'Début du bail', '01 Mars 2024'),
                  const Divider(height: 24, color: AppColors.background),
                  _buildBailRow(Icons.calendar_today_rounded, 'Fin du bail', '28 Février 2026'),
                  const Divider(height: 24, color: AppColors.background),
                  _buildBailRow(Icons.savings_rounded, 'Dépôt de garantie', '1 700,00 €'),
                  const Divider(height: 24, color: AppColors.background),
                  _buildBailRow(Icons.location_city_rounded, 'Bailleur', 'SCI Habitatum'),
                ],
              ),
            ),
            const SizedBox(height: 32),

            Text('Documents', style: Theme.of(context).textTheme.titleLarge),
            const SizedBox(height: 16),
            _buildDocItem(context, 'Contrat de bail', 'PDF - 2.4 Mo', Icons.description_rounded, AppColors.primary),
            const SizedBox(height: 12),
            _buildDocItem(context, 'État des lieux entrée', 'PDF - 1.8 Mo', Icons.assignment_rounded, AppColors.success),
            const SizedBox(height: 12),
            _buildDocItem(context, 'Règlement intérieur', 'PDF - 0.5 Mo', Icons.gavel_rounded, AppColors.warning),
            const SizedBox(height: 12),
            _buildDocItem(context, 'Diagnostics techniques', 'PDF - 3.1 Mo', Icons.health_and_safety_rounded, AppColors.primary),
          ],
        ),
      ),
    );
  }

  Widget _buildSpecItem(IconData icon, String label, String value) {
    return GlassContainer(
      padding: const EdgeInsets.all(16),
      borderRadius: 24,
      child: Row(
        children: [
          Container(
            padding: const EdgeInsets.all(8),
            decoration: BoxDecoration(color: AppColors.primary.withValues(alpha: 0.1), shape: BoxShape.circle),
            child: Icon(icon, color: AppColors.primary, size: 20),
          ),
          const SizedBox(width: 12),
          Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(label, style: const TextStyle(fontSize: 11, color: AppColors.textSecondary)),
              Text(value, style: const TextStyle(fontSize: 14, fontWeight: FontWeight.w600)),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildBailRow(IconData icon, String label, String value) {
    return Row(
      children: [
        Icon(icon, color: AppColors.textSecondary, size: 20),
        const SizedBox(width: 12),
        Text(label, style: const TextStyle(color: AppColors.textSecondary, fontSize: 14)),
        const Spacer(),
        Text(value, style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 14)),
      ],
    );
  }

  Widget _buildDocItem(BuildContext context, String name, String meta, IconData icon, Color color) {
    return GlassContainer(
      padding: const EdgeInsets.all(16),
      borderRadius: 18,
      child: Row(
        children: [
          Container(
            padding: const EdgeInsets.all(10),
            decoration: BoxDecoration(color: color.withValues(alpha: 0.12), borderRadius: BorderRadius.circular(14)),
            child: Icon(icon, color: color, size: 22),
          ),
          const SizedBox(width: 14),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(name, style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 15)),
                Text(meta, style: const TextStyle(color: AppColors.textSecondary, fontSize: 12)),
              ],
            ),
          ),
          Icon(Icons.download_rounded, color: AppColors.primary, size: 22),
        ],
      ),
    );
  }
}
