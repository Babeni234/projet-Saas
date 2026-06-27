import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../services/api_service.dart';
import '../theme/app_colors.dart';
import '../widgets/glass_container.dart';

class PropertyScreen extends StatefulWidget {
  const PropertyScreen({super.key});

  @override
  State<PropertyScreen> createState() => _PropertyScreenState();
}

class _PropertyScreenState extends State<PropertyScreen> {
  bool _renewalLoading = false;

  void _submitRenewal(ApiService api) async {
    setState(() => _renewalLoading = true);
    final fees = api.contractFees;
    if (fees.isNotEmpty) {
      final fee = fees[0];
      await api.payContractFee(fee['id'] as int? ?? 0, (fee['amount'] as num? ?? 50000).toDouble());
    }
    setState(() => _renewalLoading = false);
    if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: const Text('Demande de renouvellement soumise'),
          backgroundColor: AppColors.success,
          behavior: SnackBarBehavior.floating,
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
        ),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    final apiService = context.watch<ApiService>();
    final contracts = apiService.contracts;
    final contractFees = apiService.contractFees;

    if (contracts.isEmpty) {
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
              GlassContainer(
                padding: const EdgeInsets.all(32),
                borderRadius: 24,
                child: Column(
                  children: [
                    Icon(Icons.home_work_rounded, size: 64, color: AppColors.textSecondary.withValues(alpha: 0.5)),
                    const SizedBox(height: 16),
                    const Text('Aucun contrat actif', style: TextStyle(color: AppColors.textSecondary, fontSize: 18, fontWeight: FontWeight.w600)),
                    const SizedBox(height: 8),
                    const Text('Vous n\'avez pas de bail en cours.', style: TextStyle(color: AppColors.textTertiary, fontSize: 14)),
                  ],
                ),
              ),
            ],
          ),
        ),
      );
    }

    final contract = contracts[0];
    final property = contract['logement'] as Map<String, dynamic>?;
    final propertyName = property?['name']?.toString() ?? 'Logement';
    final propertyAddress = property?['address']?.toString() ?? property?['adresse']?.toString() ?? 'Adresse non renseignée';
    final specs = property?['specs'] as List<dynamic>?;
    final equipment = property?['equipment'] as List<dynamic>?;
    final documents = contract['documents'] as List<dynamic>?;

    // Extract spec values
    String surface = '—';
    String floor = '—';
    String reference = '—';
    String building = '—';
    String city = '—';

    if (specs != null) {
      for (var spec in specs) {
        final label = spec['label']?.toString();
        final value = spec['value']?.toString();
        if (label != null && value != null) {
          if (label.contains('Surface')) surface = value;
          if (label.contains('Étage')) floor = value;
          if (label.contains('Référence')) reference = value;
          if (label.contains('Bâtiment')) building = value;
          if (label.contains('Ville')) city = value;
        }
      }
    }

    // Extract contract details safely
    final contractNumber = contract['contrat_numero']?.toString() ?? contract['numero']?.toString() ?? '—';
    final contractType = contract['type']?.toString() ?? 'Bail d\'habitation';
    final startDate = contract['start_date']?.toString() ?? contract['debut']?.toString() ?? '—';
    final endDate = contract['end_date']?.toString() ?? contract['fin']?.toString() ?? '—';
    final rent = contract['loyer'] is num ? (contract['loyer'] as num).toDouble() : 0.0;
    final deposit = contract['caution'] is num ? (contract['caution'] as num).toDouble() : 0.0;

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
                    Text(propertyName, style: const TextStyle(color: Colors.white, fontSize: 24, fontWeight: FontWeight.w800, letterSpacing: -0.5)),
                    const SizedBox(height: 4),
                    Text(propertyAddress, style: const TextStyle(color: Colors.white70, fontSize: 15, fontWeight: FontWeight.w500)),
                  ],
                ),
              ),
            ),
            const SizedBox(height: 32),

            _buildSectionHeader('CARACTÉRISTIQUES'),
            const SizedBox(height: 12),
            Row(
              children: [
                Expanded(child: _buildSpecItem(Icons.square_foot_rounded, 'Surface', surface)),
                const SizedBox(width: 16),
                Expanded(child: _buildSpecItem(Icons.layers_rounded, 'Étage', floor)),
              ],
            ),
            const SizedBox(height: 16),
            Row(
              children: [
                Expanded(child: _buildSpecItem(Icons.tag_rounded, 'Référence', reference)),
                const SizedBox(width: 16),
                Expanded(child: _buildSpecItem(Icons.apartment_rounded, 'Bâtiment', building)),
              ],
            ),
            if (equipment != null && equipment.isNotEmpty) ...[
              const SizedBox(height: 16),
              _buildSectionHeader('ÉQUIPEMENTS'),
              const SizedBox(height: 12),
              Wrap(
                spacing: 8,
                runSpacing: 8,
                children: equipment.map((eq) => _buildEquipmentChip(eq.toString())).toList(),
              ),
            ],
            const SizedBox(height: 32),

            _buildSectionHeader('DÉTAILS DU CONTRAT'),
            const SizedBox(height: 12),
            GlassContainer(
              padding: EdgeInsets.zero,
              borderRadius: 32,
              child: Column(
                children: [
                  _buildBailRow(Icons.payments_outlined, 'Loyer mensuel', '${(contract['rent'] as num?)?.toStringAsFixed(2) ?? '0'} €', true),
                  _buildBailRow(Icons.receipt_long_outlined, 'Caution', '${(contract['deposit'] as num?)?.toStringAsFixed(2) ?? '0'} €', true),
                  _buildBailRow(Icons.calendar_today_outlined, 'Début du bail', contract['start_date'] ?? '—', true),
                  _buildBailRow(Icons.calendar_month_outlined, 'Fin du bail', contract['end_date'] ?? '—', true),
                  _buildBailRow(Icons.description_rounded, 'Type', contract['type'] ?? '—', true),
                  _buildBailRow(Icons.numbers_rounded, 'Référence', contract['contrat_numero'] ?? '—', false),
                ],
              ),
            ),
            const SizedBox(height: 32),

            _buildSectionHeader('DOCUMENTS OFFICIELS'),
            const SizedBox(height: 12),
            if (documents != null && documents.isNotEmpty)
              ...documents.map((doc) => Padding(
                padding: const EdgeInsets.only(bottom: 12),
                child: _buildDocItem(context, doc['name'] ?? 'Document', doc['filename'] ?? '', Icons.description_rounded, AppColors.primary),
              ))
            else ...[
              _buildDocItem(context, 'Contrat de bail', 'PDF • 2.4 Mo', Icons.description_rounded, AppColors.primary),
              const SizedBox(height: 12),
              _buildDocItem(context, 'État des lieux', 'PDF • 1.8 Mo', Icons.assignment_rounded, AppColors.success),
              const SizedBox(height: 12),
              _buildDocItem(context, 'Règlement', 'PDF • 0.5 Mo', Icons.gavel_rounded, AppColors.warning),
            ],
            const SizedBox(height: 32),

            _buildSectionHeader('FRAIS DE CONTRAT'),
            const SizedBox(height: 12),
            ..._buildContractFees(apiService),
            const SizedBox(height: 16),
            _buildSectionHeader('RENOUVELLEMENT'),
            const SizedBox(height: 12),
            _buildRenewalCard(context, apiService),
          ],
        ),
      ),
    );
  }

  Widget _buildEquipmentChip(String label) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 10),
      decoration: BoxDecoration(
        color: AppColors.primary.withValues(alpha: 0.1),
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: AppColors.primary.withValues(alpha: 0.2)),
      ),
      child: Text(
        label,
        style: const TextStyle(
          color: AppColors.primary,
          fontWeight: FontWeight.w700,
          fontSize: 13,
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

  List<Widget> _buildContractFees(ApiService api) {
    final fees = api.contractFees;
    if (fees.isEmpty) {
      return [
        GlassContainer(
          padding: const EdgeInsets.all(20),
          borderRadius: 24,
          child: Row(
            children: [
              Icon(Icons.check_circle_rounded, color: AppColors.success, size: 28),
              const SizedBox(width: 16),
              const Expanded(child: Text('Aucun frais de contrat en attente', style: TextStyle(fontWeight: FontWeight.w600, fontSize: 15))),
            ],
          ),
        ),
      ];
    }
    return fees.map((fee) {
      final paid = fee['status']?.toString() == 'paid';
      return Padding(
        padding: const EdgeInsets.only(bottom: 12),
        child: GlassContainer(
          padding: const EdgeInsets.all(18),
          borderRadius: 24,
          child: Row(
            children: [
              Container(
                padding: const EdgeInsets.all(10),
                decoration: BoxDecoration(color: (paid ? AppColors.success : AppColors.warning).withValues(alpha: 0.1), borderRadius: BorderRadius.circular(12)),
                child: Icon(paid ? Icons.check_rounded : Icons.pending_rounded, color: paid ? AppColors.success : AppColors.warning, size: 22),
              ),
              const SizedBox(width: 14),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(fee['label'] ?? 'Frais de contrat', style: const TextStyle(fontWeight: FontWeight.w700, fontSize: 15)),
                    Text('${(fee['amount'] as num?)?.toStringAsFixed(0) ?? '0'} €', style: const TextStyle(color: AppColors.textSecondary, fontSize: 13)),
                  ],
                ),
              ),
              if (!paid)
                GestureDetector(
                  onTap: () async {
                    await api.payContractFee(fee['id'] as int? ?? 0, (fee['amount'] as num? ?? 0).toDouble());
                  },
                  child: Container(
                    padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                    decoration: BoxDecoration(color: AppColors.primary, borderRadius: BorderRadius.circular(16)),
                    child: const Text('Payer', style: TextStyle(color: Colors.white, fontWeight: FontWeight.w700, fontSize: 13)),
                  ),
                ),
            ],
          ),
        ),
      );
    }).toList();
  }

  Widget _buildRenewalCard(BuildContext context, ApiService api) {
    return GlassContainer(
      padding: const EdgeInsets.all(20),
      borderRadius: 24,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Container(
                padding: const EdgeInsets.all(10),
                decoration: BoxDecoration(color: AppColors.primary.withValues(alpha: 0.1), borderRadius: BorderRadius.circular(12)),
                child: const Icon(Icons.replay_rounded, color: AppColors.primary, size: 24),
              ),
              const SizedBox(width: 16),
              const Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text('Demande de renouvellement', style: TextStyle(fontWeight: FontWeight.w700, fontSize: 16)),
                    Text('Soumettez une demande pour prolonger votre bail', style: TextStyle(color: AppColors.textSecondary, fontSize: 13)),
                  ],
                ),
              ),
            ],
          ),
          const SizedBox(height: 16),
          SizedBox(
            width: double.infinity,
            height: 48,
            child: ElevatedButton(
              onPressed: _renewalLoading ? null : () => _submitRenewal(api),
              child: _renewalLoading
                  ? const SizedBox(width: 20, height: 20, child: CircularProgressIndicator(strokeWidth: 2, color: Colors.white))
                  : const Text('Soumettre', style: TextStyle(fontWeight: FontWeight.w800)),
            ),
          ),
        ],
      ),
    );
  }
}
