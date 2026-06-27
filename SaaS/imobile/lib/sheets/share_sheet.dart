import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:share_plus/share_plus.dart';
import 'package:url_launcher/url_launcher.dart';
import '../config/theme.dart';
import '../config/api_config.dart';
import '../models/illustration.dart';

class ShareSheet extends StatelessWidget {
  final Illustration item;
  const ShareSheet({super.key, required this.item});

  String _getShareUrl() {
    return '${ApiConfig.baseUrl}/immotok?id=${item.id}';
  }

  String _getShareText() {
    return 'Découvrez cette superbe offre immobilière de @${item.company.name} sur ImmoTok !';
  }

  @override
  Widget build(BuildContext context) {
    final shareUrl = _getShareUrl();
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: const BoxDecoration(
        color: ImmoTokTheme.cardDark,
        borderRadius: BorderRadius.vertical(top: Radius.circular(16)),
      ),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          // Handle
          Container(width: 48, height: 5, margin: const EdgeInsets.only(bottom: 12),
            decoration: BoxDecoration(color: Colors.white.withOpacity(0.1), borderRadius: BorderRadius.circular(3))),
          // Header
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const Text('Partager ce bien', style: TextStyle(fontWeight: FontWeight.w700, fontSize: 15, color: Colors.white)),
              GestureDetector(onTap: () => Navigator.pop(context), child: const Icon(Icons.close, color: ImmoTokTheme.gray400, size: 22)),
            ],
          ),
          const Divider(color: Colors.white10),
          const SizedBox(height: 8),
          // Share buttons grid
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceEvenly,
            children: [
              _ShareButton(
                icon: Icons.chat,
                label: 'WhatsApp',
                color: const Color(0xFF25D366),
                onTap: () => _share('whatsapp', context),
              ),
              _ShareButton(
                icon: Icons.send,
                label: 'Telegram',
                color: const Color(0xFF0088CC),
                onTap: () => _share('telegram', context),
              ),
              _ShareButton(
                icon: Icons.facebook,
                label: 'Facebook',
                color: const Color(0xFF1877F2),
                onTap: () => _share('facebook', context),
              ),
              _ShareButton(
                icon: Icons.link,
                label: 'Copier',
                color: Colors.white.withOpacity(0.1),
                onTap: () => _copyLink(context),
              ),
            ],
          ),
          const SizedBox(height: 16),
          // Link preview
          Container(
            padding: const EdgeInsets.all(12),
            decoration: BoxDecoration(
              color: Colors.black.withOpacity(0.35),
              borderRadius: BorderRadius.circular(12),
              border: Border.all(color: Colors.white.withOpacity(0.05)),
            ),
            child: Row(
              children: [
                const Icon(Icons.link, color: ImmoTokTheme.redPrimary, size: 14),
                const SizedBox(width: 8),
                Expanded(child: Text(shareUrl, style: const TextStyle(fontSize: 11, color: ImmoTokTheme.gray300), overflow: TextOverflow.ellipsis)),
                const SizedBox(width: 8),
                GestureDetector(
                  onTap: () => _copyLink(context),
                  child: Container(
                    padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                    decoration: BoxDecoration(
                      color: ImmoTokTheme.redPrimary,
                      borderRadius: BorderRadius.circular(16),
                    ),
                    child: const Text('Copier', style: TextStyle(fontSize: 11, fontWeight: FontWeight.w700, color: Colors.white)),
                  ),
                ),
              ],
            ),
          ),
          SizedBox(height: MediaQuery.of(context).padding.bottom + 8),
        ],
      ),
    );
  }

  void _share(String platform, BuildContext context) async {
    final url = Uri.encodeFull(_getShareUrl());
    final text = Uri.encodeFull(_getShareText());
    String shareHref = '';
    if (platform == 'whatsapp') {
      shareHref = 'https://api.whatsapp.com/send?text=$text%20$url';
    } else if (platform == 'telegram') {
      shareHref = 'https://t.me/share/url?url=$url&text=$text';
    } else if (platform == 'facebook') {
      shareHref = 'https://www.facebook.com/sharer/sharer.php?u=$url';
    }
    try {
      await launchUrl(Uri.parse(shareHref), mode: LaunchMode.externalApplication);
    } catch (_) {
      await Share.share('${_getShareText()} ${_getShareUrl()}');
    }
  }

  void _copyLink(BuildContext context) {
    Clipboard.setData(ClipboardData(text: _getShareUrl()));
    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(content: Text('Lien copié !'), backgroundColor: ImmoTokTheme.redPrimary, duration: Duration(seconds: 2)),
    );
  }
}

class _ShareButton extends StatelessWidget {
  final IconData icon;
  final String label;
  final Color color;
  final VoidCallback onTap;

  const _ShareButton({required this.icon, required this.label, required this.color, required this.onTap});

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          Container(
            width: 48, height: 48,
            decoration: BoxDecoration(color: color, shape: BoxShape.circle,
              boxShadow: [BoxShadow(color: color.withOpacity(0.3), blurRadius: 8)]),
            child: Icon(icon, color: Colors.white, size: 22),
          ),
          const SizedBox(height: 6),
          Text(label, style: const TextStyle(fontSize: 11, color: ImmoTokTheme.gray300)),
        ],
      ),
    );
  }
}
