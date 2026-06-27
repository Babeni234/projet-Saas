import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../config/theme.dart';
import '../providers/app_state.dart';

class ReserveSheet extends StatefulWidget {
  final int illustrationId;
  const ReserveSheet({super.key, required this.illustrationId});

  @override
  State<ReserveSheet> createState() => _ReserveSheetState();
}

class _ReserveSheetState extends State<ReserveSheet> {
  final _firstname = TextEditingController();
  final _lastname = TextEditingController();
  final _phone = TextEditingController();
  final _email = TextEditingController();
  final _message = TextEditingController();
  String _date = '';
  String _time = '';
  String _visitType = 'visite';

  final _timeSlots = ['08h00', '09h00', '10h00', '11h00', '14h00', '15h00', '16h00', '17h00'];

  @override
  void dispose() {
    _firstname.dispose();
    _lastname.dispose();
    _phone.dispose();
    _email.dispose();
    _message.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      height: MediaQuery.of(context).size.height * 0.8,
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
                const Text('Réserver une visite', style: TextStyle(fontWeight: FontWeight.w700, fontSize: 15, color: Colors.white)),
                GestureDetector(onTap: () => Navigator.pop(context), child: const Icon(Icons.close, color: ImmoTokTheme.gray400, size: 22)),
              ],
            ),
          ),
          const Divider(color: Colors.white10, height: 1),
          Expanded(
            child: ListView(
              padding: const EdgeInsets.all(16),
              children: [
                Row(
                  children: [
                    Expanded(child: _buildField('Prénom *', _firstname, 'Kouamé')),
                    const SizedBox(width: 12),
                    Expanded(child: _buildField('Nom *', _lastname, 'Diallo')),
                  ],
                ),
                const SizedBox(height: 12),
                _buildField('Téléphone *', _phone, '+225 07...', type: TextInputType.phone),
                const SizedBox(height: 12),
                _buildField('Email', _email, 'vous@email.com', type: TextInputType.emailAddress),
                const SizedBox(height: 12),
                Row(
                  children: [
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          const Text('Date souhaitée *', style: TextStyle(fontSize: 11, color: ImmoTokTheme.gray400)),
                          const SizedBox(height: 4),
                          GestureDetector(
                            onTap: () async {
                              final picked = await showDatePicker(
                                context: context,
                                initialDate: DateTime.now().add(const Duration(days: 1)),
                                firstDate: DateTime.now(),
                                lastDate: DateTime.now().add(const Duration(days: 90)),
                                builder: (context, child) => Theme(
                                  data: ThemeData.dark().copyWith(colorScheme: const ColorScheme.dark(primary: ImmoTokTheme.redPrimary)),
                                  child: child!,
                                ),
                              );
                              if (picked != null) {
                                setState(() => _date = '${picked.year}-${picked.month.toString().padLeft(2, '0')}-${picked.day.toString().padLeft(2, '0')}');
                              }
                            },
                            child: Container(
                              height: 40,
                              padding: const EdgeInsets.symmetric(horizontal: 12),
                              decoration: BoxDecoration(
                                color: Colors.black.withOpacity(0.2),
                                borderRadius: BorderRadius.circular(8),
                                border: Border.all(color: Colors.white.withOpacity(0.1)),
                              ),
                              child: Row(
                                children: [
                                  Expanded(child: Text(_date.isEmpty ? 'Choisir...' : _date,
                                    style: TextStyle(fontSize: 13, color: _date.isEmpty ? ImmoTokTheme.gray500 : Colors.white))),
                                  const Icon(Icons.calendar_today, size: 14, color: ImmoTokTheme.gray400),
                                ],
                              ),
                            ),
                          ),
                        ],
                      ),
                    ),
                    const SizedBox(width: 12),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          const Text('Créneau horaire', style: TextStyle(fontSize: 11, color: ImmoTokTheme.gray400)),
                          const SizedBox(height: 4),
                          Container(
                            height: 40,
                            padding: const EdgeInsets.symmetric(horizontal: 8),
                            decoration: BoxDecoration(
                              color: ImmoTokTheme.bgDark,
                              borderRadius: BorderRadius.circular(8),
                              border: Border.all(color: Colors.white.withOpacity(0.1)),
                            ),
                            child: DropdownButtonHideUnderline(
                              child: DropdownButton<String>(
                                value: _time.isEmpty ? null : _time,
                                hint: const Text('Choisir...', style: TextStyle(fontSize: 13, color: ImmoTokTheme.gray500)),
                                isExpanded: true,
                                dropdownColor: ImmoTokTheme.cardDark,
                                style: const TextStyle(fontSize: 13, color: Colors.white),
                                items: _timeSlots.map((t) => DropdownMenuItem(value: t, child: Text(t))).toList(),
                                onChanged: (val) => setState(() => _time = val ?? ''),
                              ),
                            ),
                          ),
                        ],
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 12),
                Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text('Type de visite', style: TextStyle(fontSize: 11, color: ImmoTokTheme.gray400)),
                    const SizedBox(height: 4),
                    Container(
                      height: 40,
                      padding: const EdgeInsets.symmetric(horizontal: 8),
                      decoration: BoxDecoration(
                        color: ImmoTokTheme.bgDark,
                        borderRadius: BorderRadius.circular(8),
                        border: Border.all(color: Colors.white.withOpacity(0.1)),
                      ),
                      child: DropdownButtonHideUnderline(
                        child: DropdownButton<String>(
                          value: _visitType,
                          isExpanded: true,
                          dropdownColor: ImmoTokTheme.cardDark,
                          style: const TextStyle(fontSize: 13, color: Colors.white),
                          items: const [
                            DropdownMenuItem(value: 'visite', child: Text('Visite physique')),
                            DropdownMenuItem(value: 'virtuelle', child: Text('Visite virtuelle (vidéo)')),
                            DropdownMenuItem(value: 'info', child: Text("Demande d'informations")),
                          ],
                          onChanged: (val) => setState(() => _visitType = val ?? 'visite'),
                        ),
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 12),
                Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text('Message (optionnel)', style: TextStyle(fontSize: 11, color: ImmoTokTheme.gray400)),
                    const SizedBox(height: 4),
                    TextField(
                      controller: _message,
                      maxLines: 3,
                      style: const TextStyle(color: Colors.white, fontSize: 13),
                      decoration: InputDecoration(
                        hintText: 'Budget, questions...',
                        hintStyle: const TextStyle(color: ImmoTokTheme.gray500),
                        filled: true,
                        fillColor: Colors.black.withOpacity(0.2),
                        border: OutlineInputBorder(borderRadius: BorderRadius.circular(8), borderSide: BorderSide(color: Colors.white.withOpacity(0.1))),
                        enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(8), borderSide: BorderSide(color: Colors.white.withOpacity(0.1))),
                        focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(8), borderSide: const BorderSide(color: ImmoTokTheme.redPrimary)),
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 20),
                GestureDetector(
                  onTap: _submit,
                  child: Container(
                    height: 48,
                    decoration: BoxDecoration(
                      color: ImmoTokTheme.redPrimary,
                      borderRadius: BorderRadius.circular(24),
                      boxShadow: [BoxShadow(color: ImmoTokTheme.redPrimary.withOpacity(0.2), blurRadius: 12, offset: const Offset(0, 4))],
                    ),
                    child: const Center(child: Text('Soumettre la demande', style: TextStyle(fontWeight: FontWeight.w700, color: Colors.white, fontSize: 15))),
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildField(String label, TextEditingController controller, String hint, {TextInputType type = TextInputType.text}) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(label, style: const TextStyle(fontSize: 11, color: ImmoTokTheme.gray400)),
        const SizedBox(height: 4),
        TextField(
          controller: controller,
          keyboardType: type,
          style: const TextStyle(color: Colors.white, fontSize: 13),
          decoration: InputDecoration(
            hintText: hint,
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
    );
  }

  void _submit() async {
    if (_firstname.text.isEmpty || _lastname.text.isEmpty || _phone.text.isEmpty || _date.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Veuillez remplir les champs obligatoires'), backgroundColor: ImmoTokTheme.redPrimary),
      );
      return;
    }
    final state = context.read<AppState>();
    final msg = await state.submitReservation({
      'illustration_id': widget.illustrationId,
      'firstname': _firstname.text,
      'lastname': _lastname.text,
      'phone': _phone.text,
      'email': _email.text,
      'date': _date,
      'time': _time,
      'visittype': _visitType,
      'message': _message.text,
    });
    if (msg != null && mounted) {
      Navigator.pop(context);
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(msg), backgroundColor: ImmoTokTheme.greenOnline));
    } else if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Erreur lors de la réservation'), backgroundColor: ImmoTokTheme.redPrimary),
      );
    }
  }
}
