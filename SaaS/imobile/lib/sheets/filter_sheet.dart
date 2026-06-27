import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../config/theme.dart';
import '../providers/app_state.dart';

class FilterSheet extends StatefulWidget {
  const FilterSheet({super.key});

  @override
  State<FilterSheet> createState() => _FilterSheetState();
}

class _FilterSheetState extends State<FilterSheet> {
  late String _transaction;
  late String _type;
  late double _budget;
  late TextEditingController _city;

  @override
  void initState() {
    super.initState();
    final state = context.read<AppState>();
    _transaction = state.filterTransaction;
    _type = state.filterType;
    _budget = state.filterBudget.toDouble();
    _city = TextEditingController(text: state.filterCity);
  }

  @override
  void dispose() {
    _city.dispose();
    super.dispose();
  }

  String _formatBudget(double val) {
    if (val >= 10000000) return 'Sans limite';
    final intVal = val.toInt();
    if (intVal == 0) return 'Sans limite';
    final formatted = intVal.toString().replaceAllMapped(
      RegExp(r'(\d)(?=(\d{3})+(?!\d))'),
      (m) => '${m[1]} ',
    );
    return '$formatted FCFA';
  }

  @override
  Widget build(BuildContext context) {
    final state = context.read<AppState>();
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: const BoxDecoration(
        color: ImmoTokTheme.cardDark,
        borderRadius: BorderRadius.vertical(top: Radius.circular(16)),
      ),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          Container(width: 48, height: 5, margin: const EdgeInsets.only(bottom: 12),
            decoration: BoxDecoration(color: Colors.white.withOpacity(0.1), borderRadius: BorderRadius.circular(3))),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const Text('Filtrer les offres', style: TextStyle(fontWeight: FontWeight.w700, fontSize: 15, color: Colors.white)),
              GestureDetector(onTap: () => Navigator.pop(context), child: const Icon(Icons.close, color: ImmoTokTheme.gray400, size: 22)),
            ],
          ),
          const Divider(color: Colors.white10),
          const SizedBox(height: 8),
          // Transaction type
          Row(
            children: ['all', 'location', 'vente'].map((t) => Expanded(
              child: Padding(
                padding: const EdgeInsets.symmetric(horizontal: 4),
                child: GestureDetector(
                  onTap: () => setState(() => _transaction = t),
                  child: Container(
                    padding: const EdgeInsets.symmetric(vertical: 10),
                    decoration: BoxDecoration(
                      color: _transaction == t ? ImmoTokTheme.redPrimary : Colors.black.withOpacity(0.2),
                      borderRadius: BorderRadius.circular(12),
                      border: Border.all(color: _transaction == t ? ImmoTokTheme.redPrimary : Colors.white.withOpacity(0.1)),
                    ),
                    child: Center(child: Text(
                      t == 'all' ? 'Tous' : t == 'location' ? 'Location' : 'Vente',
                      style: TextStyle(fontSize: 12, fontWeight: FontWeight.w700, color: _transaction == t ? Colors.white : ImmoTokTheme.gray400),
                    )),
                  ),
                ),
              ),
            )).toList(),
          ),
          const SizedBox(height: 16),
          // Property type
          Align(
            alignment: Alignment.centerLeft,
            child: Text('TYPE DE BIEN', style: TextStyle(fontSize: 10, fontWeight: FontWeight.w700, color: ImmoTokTheme.gray400, letterSpacing: 1.5)),
          ),
          const SizedBox(height: 8),
          Container(
            constraints: const BoxConstraints(maxHeight: 120),
            padding: const EdgeInsets.all(4),
            decoration: BoxDecoration(
              color: Colors.black.withOpacity(0.1),
              borderRadius: BorderRadius.circular(8),
            ),
            child: SingleChildScrollView(
              child: Wrap(
                spacing: 8,
                runSpacing: 8,
                children: [
                  _TypeChip(label: 'Tout', isActive: _type == 'all', onTap: () => setState(() => _type = 'all')),
                  ...state.categories.map((cat) => _TypeChip(
                    label: cat.toUpperCase(),
                    isActive: _type == cat,
                    onTap: () => setState(() => _type = cat),
                  )),
                ],
              ),
            ),
          ),
          const SizedBox(height: 16),
          // Budget slider
          Align(
            alignment: Alignment.centerLeft,
            child: Text(
              'Budget Maximum (FCFA) : ${_formatBudget(_budget)}',
              style: const TextStyle(fontSize: 11, color: ImmoTokTheme.gray400),
            ),
          ),
          SliderTheme(
            data: SliderThemeData(
              activeTrackColor: ImmoTokTheme.redPrimary,
              inactiveTrackColor: Colors.white.withOpacity(0.1),
              thumbColor: ImmoTokTheme.redPrimary,
              overlayColor: ImmoTokTheme.redPrimary.withOpacity(0.2),
            ),
            child: Slider(
              value: _budget,
              min: 0,
              max: 10000000,
              divisions: 200,
              onChanged: (val) => setState(() => _budget = val),
            ),
          ),
          const SizedBox(height: 8),
          // City
          Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const Text('Ville / Quartier', style: TextStyle(fontSize: 11, color: ImmoTokTheme.gray400)),
              const SizedBox(height: 4),
              TextField(
                controller: _city,
                style: const TextStyle(color: Colors.white, fontSize: 13),
                decoration: InputDecoration(
                  hintText: 'Ex: Cocody, Plateau...',
                  hintStyle: const TextStyle(color: ImmoTokTheme.gray500),
                  filled: true,
                  fillColor: Colors.black.withOpacity(0.2),
                  contentPadding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
                  border: OutlineInputBorder(borderRadius: BorderRadius.circular(8), borderSide: BorderSide(color: Colors.white.withOpacity(0.1))),
                  enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(8), borderSide: BorderSide(color: Colors.white.withOpacity(0.1))),
                  focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(8), borderSide: const BorderSide(color: ImmoTokTheme.redPrimary)),
                ),
              ),
            ],
          ),
          const SizedBox(height: 20),
          // Buttons
          Row(
            children: [
              Expanded(
                child: GestureDetector(
                  onTap: () {
                    state.resetFilters();
                    Navigator.pop(context);
                  },
                  child: Container(
                    height: 44,
                    decoration: BoxDecoration(
                      borderRadius: BorderRadius.circular(22),
                      border: Border.all(color: Colors.white.withOpacity(0.1)),
                    ),
                    child: const Center(child: Text('Réinitialiser', style: TextStyle(fontSize: 13, fontWeight: FontWeight.w600, color: Colors.white))),
                  ),
                ),
              ),
              const SizedBox(width: 12),
              Expanded(
                child: GestureDetector(
                  onTap: () {
                    state.filterTransaction = _transaction;
                    state.filterType = _type;
                    state.filterBudget = _budget.toInt();
                    state.filterCity = _city.text;
                    state.applyFilters();
                    Navigator.pop(context);
                  },
                  child: Container(
                    height: 44,
                    decoration: BoxDecoration(
                      color: ImmoTokTheme.redPrimary,
                      borderRadius: BorderRadius.circular(22),
                      boxShadow: [BoxShadow(color: ImmoTokTheme.redPrimary.withOpacity(0.2), blurRadius: 12)],
                    ),
                    child: const Center(child: Text('Appliquer', style: TextStyle(fontSize: 13, fontWeight: FontWeight.w600, color: Colors.white))),
                  ),
                ),
              ),
            ],
          ),
          SizedBox(height: MediaQuery.of(context).padding.bottom + 8),
        ],
      ),
    );
  }
}

class _TypeChip extends StatelessWidget {
  final String label;
  final bool isActive;
  final VoidCallback onTap;
  const _TypeChip({required this.label, required this.isActive, required this.onTap});

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 6),
        decoration: BoxDecoration(
          color: isActive ? ImmoTokTheme.redPrimary : Colors.transparent,
          borderRadius: BorderRadius.circular(20),
          border: Border.all(color: isActive ? ImmoTokTheme.redPrimary : Colors.white.withOpacity(0.1)),
        ),
        child: Text(label, style: TextStyle(fontSize: 11, fontWeight: FontWeight.w700,
          color: isActive ? Colors.white : ImmoTokTheme.gray400)),
      ),
    );
  }
}
