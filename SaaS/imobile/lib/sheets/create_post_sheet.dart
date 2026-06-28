import 'package:flutter/material.dart';
import '../config/theme.dart';

class CreatePostSheet extends StatelessWidget {
  const CreatePostSheet({super.key});

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(24),
      decoration: const BoxDecoration(
        color: ImmoTokTheme.cardDark,
        borderRadius: BorderRadius.vertical(top: Radius.circular(16)),
      ),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          Container(width: 48, height: 5, margin: const EdgeInsets.only(bottom: 16),
            decoration: BoxDecoration(color: Colors.white.withOpacity(0.1), borderRadius: BorderRadius.circular(3))),
          // Icon
          Container(
            width: 80, height: 80,
            decoration: BoxDecoration(
              borderRadius: BorderRadius.circular(16),
              gradient: const LinearGradient(colors: [ImmoTokTheme.blueAccent, Colors.white, ImmoTokTheme.pinkAccent]),
            ),
            padding: const EdgeInsets.all(3),
            child: Container(
              decoration: BoxDecoration(color: ImmoTokTheme.bgDark, borderRadius: BorderRadius.circular(14)),
              child: const Center(child: Icon(Icons.camera_alt, color: Colors.white, size: 32)),
            ),
          ),
          const SizedBox(height: 12),
          RichText(text: const TextSpan(children: [
            TextSpan(text: 'Immo', style: TextStyle(fontSize: 20, fontWeight: FontWeight.w900, color: Colors.white)),
            TextSpan(text: 'Tok', style: TextStyle(fontSize: 20, fontWeight: FontWeight.w900, color: ImmoTokTheme.redPrimary)),
          ])),
          const SizedBox(height: 4),
          const Text('Publier du contenu', style: TextStyle(fontSize: 12, color: ImmoTokTheme.gray400)),
          const SizedBox(height: 20),
          // Info card
          Container(
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              color: Colors.black.withOpacity(0.2),
              borderRadius: BorderRadius.circular(12),
              border: Border.all(color: Colors.white.withOpacity(0.05)),
            ),
            child: Column(
              children: [
                Row(crossAxisAlignment: CrossAxisAlignment.start, children: [
                  Container(
                    width: 40, height: 40,
                    decoration: BoxDecoration(shape: BoxShape.circle, color: Colors.purple.withOpacity(0.1)),
                    child: Icon(Icons.business, color: Colors.purple.shade300, size: 20),
                  ),
                  const SizedBox(width: 12),
                  Expanded(child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
                    const Text('Espace Gestionnaire', style: TextStyle(fontSize: 13, fontWeight: FontWeight.w600, color: Colors.white)),
                    const SizedBox(height: 4),
                    RichText(text: TextSpan(
                      style: const TextStyle(fontSize: 12, color: ImmoTokTheme.gray400, height: 1.4),
                      children: [
                        const TextSpan(text: 'Pour publier vos propres vidéos et illustrations de biens immobiliers, connectez-vous à votre '),
                        TextSpan(text: 'espace Gestionnaire Entreprise', style: TextStyle(fontWeight: FontWeight.w700, color: Colors.red.shade300)),
                        const TextSpan(text: ' ou '),
                        TextSpan(text: 'Agence', style: TextStyle(fontWeight: FontWeight.w700, color: Colors.red.shade300)),
                        const TextSpan(text: '.'),
                      ],
                    )),
                  ])),
                ]),
                const SizedBox(height: 16),
                const Divider(color: Colors.white10),
                const SizedBox(height: 12),
                Row(crossAxisAlignment: CrossAxisAlignment.start, children: [
                  Container(
                    width: 40, height: 40,
                    decoration: BoxDecoration(shape: BoxShape.circle, color: Colors.cyan.withOpacity(0.1)),
                    child: Icon(Icons.cloud_upload, color: Colors.cyan.shade300, size: 20),
                  ),
                  const SizedBox(width: 12),
                  Expanded(child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
                    const Text('Comment ça marche ?', style: TextStyle(fontSize: 13, fontWeight: FontWeight.w600, color: Colors.white)),
                    const SizedBox(height: 4),
                    RichText(text: const TextSpan(
                      style: TextStyle(fontSize: 12, color: ImmoTokTheme.gray400, height: 1.4),
                      children: [
                        TextSpan(text: 'Rendez-vous dans le menu '),
                        TextSpan(text: 'Illustrations', style: TextStyle(fontWeight: FontWeight.w700, color: Colors.white)),
                        TextSpan(text: ' de votre dashboard pour importer vos photos et vidéos. Elles apparaîtront automatiquement sur ImmoTok !'),
                      ],
                    )),
                  ])),
                ]),
              ],
            ),
          ),
          const SizedBox(height: 20),
          // CTA
          GestureDetector(
            onTap: () => Navigator.pop(context),
            child: Container(
              height: 48,
              decoration: BoxDecoration(
                color: ImmoTokTheme.redPrimary,
                borderRadius: BorderRadius.circular(24),
                boxShadow: [BoxShadow(color: ImmoTokTheme.redPrimary.withOpacity(0.2), blurRadius: 12)],
              ),
              child: const Row(mainAxisAlignment: MainAxisAlignment.center, children: [
                Icon(Icons.login, color: Colors.white, size: 18),
                SizedBox(width: 8),
                Text('Connexion Gestionnaire', style: TextStyle(fontSize: 14, fontWeight: FontWeight.w700, color: Colors.white)),
              ]),
            ),
          ),
          SizedBox(height: MediaQuery.of(context).padding.bottom + 8),
        ],
      ),
    );
  }
}
