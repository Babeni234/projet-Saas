import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../config/theme.dart';
import '../providers/app_state.dart';

class AuthSheet extends StatefulWidget {
  final VoidCallback? onSuccess;
  const AuthSheet({super.key, this.onSuccess});

  @override
  State<AuthSheet> createState() => _AuthSheetState();
}

class _AuthSheetState extends State<AuthSheet> {
  String _tab = 'login';
  final _loginEmail = TextEditingController();
  final _loginPassword = TextEditingController();
  final _regName = TextEditingController();
  final _regEmail = TextEditingController();
  final _regPhone = TextEditingController();
  final _regPassword = TextEditingController();
  final _regPasswordConfirm = TextEditingController();
  bool _loading = false;

  @override
  void dispose() {
    _loginEmail.dispose();
    _loginPassword.dispose();
    _regName.dispose();
    _regEmail.dispose();
    _regPhone.dispose();
    _regPassword.dispose();
    _regPasswordConfirm.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
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
          // Header
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Row(children: [
                const Icon(Icons.play_circle_fill, color: ImmoTokTheme.redPrimary, size: 22),
                const SizedBox(width: 6),
                RichText(text: const TextSpan(
                  style: TextStyle(fontSize: 17, fontWeight: FontWeight.w900),
                  children: [
                    TextSpan(text: 'Immo', style: TextStyle(color: Colors.white)),
                    TextSpan(text: 'Tok', style: TextStyle(color: ImmoTokTheme.redPrimary)),
                  ],
                )),
              ]),
              GestureDetector(onTap: () => Navigator.pop(context), child: const Icon(Icons.close, color: ImmoTokTheme.gray400, size: 22)),
            ],
          ),
          const SizedBox(height: 12),
          // Tabs
          Row(
            children: [
              Expanded(
                child: GestureDetector(
                  onTap: () => setState(() => _tab = 'login'),
                  child: Container(
                    padding: const EdgeInsets.symmetric(vertical: 10),
                    decoration: BoxDecoration(border: Border(bottom: BorderSide(
                      color: _tab == 'login' ? ImmoTokTheme.redPrimary : Colors.transparent, width: 2))),
                    child: Text('Se connecter', textAlign: TextAlign.center,
                      style: TextStyle(fontSize: 13, fontWeight: FontWeight.w700,
                        color: _tab == 'login' ? Colors.white : ImmoTokTheme.gray400)),
                  ),
                ),
              ),
              Expanded(
                child: GestureDetector(
                  onTap: () => setState(() => _tab = 'register'),
                  child: Container(
                    padding: const EdgeInsets.symmetric(vertical: 10),
                    decoration: BoxDecoration(border: Border(bottom: BorderSide(
                      color: _tab == 'register' ? ImmoTokTheme.redPrimary : Colors.transparent, width: 2))),
                    child: Text('Créer un compte', textAlign: TextAlign.center,
                      style: TextStyle(fontSize: 13, fontWeight: FontWeight.w700,
                        color: _tab == 'register' ? Colors.white : ImmoTokTheme.gray400)),
                  ),
                ),
              ),
            ],
          ),
          const SizedBox(height: 16),
          // Forms
          if (_tab == 'login') ...[
            _field('Email', _loginEmail, 'exemple@mail.com', type: TextInputType.emailAddress),
            const SizedBox(height: 12),
            _field('Mot de passe', _loginPassword, '••••••••', obscure: true),
            const SizedBox(height: 16),
            _submitBtn('Connexion', _handleLogin),
          ] else ...[
            _field('Nom complet *', _regName, 'Jean Dupont'),
            const SizedBox(height: 12),
            _field('Email *', _regEmail, 'exemple@mail.com', type: TextInputType.emailAddress),
            const SizedBox(height: 12),
            _field('Téléphone', _regPhone, '+225 07...', type: TextInputType.phone),
            const SizedBox(height: 12),
            _field('Mot de passe *', _regPassword, '••••••••', obscure: true),
            const SizedBox(height: 12),
            _field('Confirmer mot de passe *', _regPasswordConfirm, '••••••••', obscure: true),
            const SizedBox(height: 16),
            _submitBtn('Créer mon compte', _handleRegister),
          ],
          SizedBox(height: MediaQuery.of(context).viewInsets.bottom > 0 ? 8 : MediaQuery.of(context).padding.bottom + 8),
        ],
      ),
    );
  }

  Widget _field(String label, TextEditingController ctrl, String hint, {TextInputType type = TextInputType.text, bool obscure = false}) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(label, style: const TextStyle(fontSize: 11, color: ImmoTokTheme.gray400)),
        const SizedBox(height: 4),
        TextField(
          controller: ctrl,
          keyboardType: type,
          obscureText: obscure,
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

  Widget _submitBtn(String label, VoidCallback onTap) {
    return GestureDetector(
      onTap: _loading ? null : onTap,
      child: Container(
        height: 44,
        decoration: BoxDecoration(
          color: ImmoTokTheme.redPrimary,
          borderRadius: BorderRadius.circular(22),
        ),
        child: Center(
          child: _loading
              ? const SizedBox(width: 20, height: 20, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2))
              : Text(label, style: const TextStyle(fontWeight: FontWeight.w700, color: Colors.white, fontSize: 14)),
        ),
      ),
    );
  }

  void _handleLogin() async {
    setState(() => _loading = true);
    final state = context.read<AppState>();
    final error = await state.login(_loginEmail.text, _loginPassword.text);
    setState(() => _loading = false);
    if (error == null && mounted) {
      Navigator.pop(context);
      widget.onSuccess?.call();
    } else if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text(error ?? 'Erreur de connexion'), backgroundColor: ImmoTokTheme.redPrimary),
      );
    }
  }

  void _handleRegister() async {
    setState(() => _loading = true);
    final state = context.read<AppState>();
    final error = await state.register({
      'name': _regName.text,
      'email': _regEmail.text,
      'phone': _regPhone.text,
      'password': _regPassword.text,
      'password_confirmation': _regPasswordConfirm.text,
    });
    setState(() => _loading = false);
    if (error == null && mounted) {
      Navigator.pop(context);
      widget.onSuccess?.call();
    } else if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text(error ?? "Erreur d'inscription"), backgroundColor: ImmoTokTheme.redPrimary),
      );
    }
  }
}
