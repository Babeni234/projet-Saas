import 'package:flutter/material.dart';
import 'package:url_launcher/url_launcher.dart';
import '../config/theme.dart';
import '../models/illustration.dart';

class DetailsSheet extends StatelessWidget {
  final Illustration item;
  final VoidCallback onReserveTap;

  const DetailsSheet({super.key, required this.item, required this.onReserveTap});

  @override
  Widget build(BuildContext context) {
    return Container(
      height: MediaQuery.of(context).size.height * 0.7,
      decoration: const BoxDecoration(
        color: ImmoTokTheme.cardDark,
        borderRadius: BorderRadius.vertical(top: Radius.circular(16)),
      ),
      child: Column(
        children: [
          Container(width: 48, height: 5, margin: const EdgeInsets.symmetric(vertical: 12),
            decoration: BoxDecoration(color: Colors.white.withOpacity(0.1), borderRadius: BorderRadius.circular(3))),
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 4),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                const Text('Fiche descriptive du bien', style: TextStyle(fontWeight: FontWeight.w700, fontSize: 15, color: Colors.white)),
                GestureDetector(onTap: () => Navigator.pop(context), child: const Icon(Icons.close, color: ImmoTokTheme.gray400, size: 22)),
              ],
            ),
          ),
          const Divider(color: Colors.white10, height: 1),
          Expanded(
            child: ListView(
              padding: const EdgeInsets.all(16),
              children: [
                // Price card
                Container(
                  padding: const EdgeInsets.all(16),
                  decoration: BoxDecoration(
                    color: Colors.black.withOpacity(0.3),
                    borderRadius: BorderRadius.circular(16),
                    border: Border.all(color: Colors.white.withOpacity(0.05)),
                  ),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        'Transaction : ${item.property.transaction == 'vente' ? 'Vente' : 'Location'}',
                        style: TextStyle(fontSize: 11, fontWeight: FontWeight.w600, color: ImmoTokTheme.gray400, letterSpacing: 1),
                      ),
                      const SizedBox(height: 4),
                      Text(item.property.priceLabel, style: const TextStyle(fontSize: 28, fontWeight: FontWeight.w900, color: ImmoTokTheme.redPrimary)),
                      const SizedBox(height: 8),
                      const Divider(color: Colors.white10),
                      const SizedBox(height: 4),
                      Wrap(
                        spacing: 16,
                        runSpacing: 8,
                        children: [
                          if (item.property.rooms != null)
                            _InfoChip(icon: Icons.door_sliding, text: '${item.property.rooms} ${item.property.type == 'Immeuble' ? 'Étages' : 'Chambres'}', color: ImmoTokTheme.redPrimary),
                          if (item.property.surface != null)
                            _InfoChip(icon: Icons.square_foot, text: '${item.property.surface} m²', color: ImmoTokTheme.blueAccent),
                          _InfoChip(icon: Icons.label, text: item.property.type ?? 'N/A', color: ImmoTokTheme.greenOnline),
                        ],
                      ),
                    ],
                  ),
                ),
                const SizedBox(height: 20),
                // Location
                _SectionTitle(icon: Icons.location_on, text: 'Localisation', color: ImmoTokTheme.redPrimary),
                const SizedBox(height: 4),
                Text(
                  '${item.property.neighborhood != null ? '${item.property.neighborhood}, ' : ''}${item.property.city ?? ''}',
                  style: const TextStyle(fontSize: 15, fontWeight: FontWeight.w600, color: Colors.white),
                ),
                const SizedBox(height: 20),
                // Description
                _SectionTitle(icon: Icons.description, text: "Description de l'offre", color: ImmoTokTheme.blueAccent),
                const SizedBox(height: 4),
                Container(
                  padding: const EdgeInsets.all(12),
                  decoration: BoxDecoration(
                    color: Colors.black.withOpacity(0.1),
                    borderRadius: BorderRadius.circular(12),
                    border: Border.all(color: Colors.white.withOpacity(0.05)),
                  ),
                  child: Text(item.description, style: const TextStyle(fontSize: 13, color: ImmoTokTheme.gray300, height: 1.5)),
                ),
                const SizedBox(height: 20),
                // Features
                if (item.property.features.isNotEmpty) ...[
                  _SectionTitle(icon: Icons.room_service, text: 'Équipements & Services', color: ImmoTokTheme.greenOnline),
                  const SizedBox(height: 8),
                  Wrap(
                    spacing: 8,
                    runSpacing: 8,
                    children: item.property.features.map((feat) => Container(
                      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                      decoration: BoxDecoration(
                        color: Colors.white.withOpacity(0.05),
                        borderRadius: BorderRadius.circular(20),
                        border: Border.all(color: Colors.white.withOpacity(0.1)),
                      ),
                      child: Text('✓ $feat', style: const TextStyle(fontSize: 12, fontWeight: FontWeight.w600, color: ImmoTokTheme.gray200)),
                    )).toList(),
                  ),
                ],
                const SizedBox(height: 24),
              ],
            ),
          ),
          // CTAs
          Container(
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              color: ImmoTokTheme.cardDarkAlt,
              border: Border(top: BorderSide(color: Colors.white.withOpacity(0.05))),
            ),
            child: Row(
              children: [
                Expanded(
                  child: GestureDetector(
                    onTap: () async {
                      if (item.company.phone != null) {
                        await launchUrl(Uri.parse('tel:${item.company.phone}'));
                      }
                    },
                    child: Container(
                      height: 48,
                      decoration: BoxDecoration(
                        color: Colors.white.withOpacity(0.05),
                        borderRadius: BorderRadius.circular(24),
                        border: Border.all(color: Colors.white.withOpacity(0.1)),
                      ),
                      child: const Row(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          Icon(Icons.phone, color: Colors.white, size: 18),
                          SizedBox(width: 6),
                          Text('Contacter', style: TextStyle(fontWeight: FontWeight.w700, color: Colors.white, fontSize: 14)),
                        ],
                      ),
                    ),
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: GestureDetector(
                    onTap: () {
                      Navigator.pop(context);
                      onReserveTap();
                    },
                    child: Container(
                      height: 48,
                      decoration: BoxDecoration(
                        color: ImmoTokTheme.redPrimary,
                        borderRadius: BorderRadius.circular(24),
                        boxShadow: [BoxShadow(color: ImmoTokTheme.redPrimary.withOpacity(0.2), blurRadius: 12, offset: const Offset(0, 4))],
                      ),
                      child: const Row(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          Icon(Icons.calendar_today, color: Colors.white, size: 16),
                          SizedBox(width: 6),
                          Text('Réserver visite', style: TextStyle(fontWeight: FontWeight.w700, color: Colors.white, fontSize: 14)),
                        ],
                      ),
                    ),
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}

class _SectionTitle extends StatelessWidget {
  final IconData icon;
  final String text;
  final Color color;
  const _SectionTitle({required this.icon, required this.text, required this.color});

  @override
  Widget build(BuildContext context) {
    return Row(
      children: [
        Icon(icon, size: 16, color: color),
        const SizedBox(width: 6),
        Text(text, style: TextStyle(fontSize: 12, fontWeight: FontWeight.w700, color: ImmoTokTheme.gray400, letterSpacing: 1)),
      ],
    );
  }
}

class _InfoChip extends StatelessWidget {
  final IconData icon;
  final String text;
  final Color color;
  const _InfoChip({required this.icon, required this.text, required this.color});

  @override
  Widget build(BuildContext context) {
    return Row(
      mainAxisSize: MainAxisSize.min,
      children: [
        Icon(icon, size: 14, color: color),
        const SizedBox(width: 4),
        Text(text, style: const TextStyle(fontSize: 13, color: ImmoTokTheme.gray300)),
      ],
    );
  }
}
