import 'package:flutter/material.dart';
import '../config/theme.dart';
import '../models/illustration.dart';

class OverlayInfo extends StatelessWidget {
  final Illustration item;

  const OverlayInfo({super.key, required this.item});

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      mainAxisSize: MainAxisSize.min,
      children: [
        // Company name + PRO badge
        Row(
          children: [
            Flexible(
              child: Text(
                '@${item.company.name}',
                style: TextStyle(
                  fontSize: 14,
                  fontWeight: FontWeight.w700,
                  color: Colors.white,
                  shadows: [Shadow(blurRadius: 4, color: Colors.black.withOpacity(0.5))],
                ),
                overflow: TextOverflow.ellipsis,
              ),
            ),
            const SizedBox(width: 6),
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
              decoration: BoxDecoration(
                color: ImmoTokTheme.redPrimary.withOpacity(0.9),
                borderRadius: BorderRadius.circular(4),
              ),
              child: const Text(
                'PRO',
                style: TextStyle(fontSize: 8, fontWeight: FontWeight.w700, color: Colors.white, letterSpacing: 1),
              ),
            ),
          ],
        ),
        const SizedBox(height: 4),

        // Description
        Text(
          item.description,
          maxLines: 2,
          overflow: TextOverflow.ellipsis,
          style: TextStyle(
            fontSize: 12,
            color: ImmoTokTheme.gray200,
            height: 1.4,
            shadows: [Shadow(blurRadius: 4, color: Colors.black.withOpacity(0.5))],
          ),
        ),
        const SizedBox(height: 6),

        // Property badges
        Wrap(
          spacing: 6,
          runSpacing: 4,
          children: [
            _Badge(
              emoji: '💰',
              text: item.property.priceLabel,
              textColor: const Color(0xFFF87171),
              borderColor: const Color(0xFFEF4444).withOpacity(0.2),
            ),
            if (item.property.city != null)
              _Badge(
                emoji: '📍',
                text: item.property.city!,
                textColor: ImmoTokTheme.blueAccent,
                borderColor: const Color(0xFF3B82F6).withOpacity(0.2),
              ),
            if (item.property.surface != null)
              _Badge(
                emoji: '📐',
                text: '${item.property.surface} m²',
                textColor: const Color(0xFF4ADE80),
                borderColor: const Color(0xFF22C55E).withOpacity(0.2),
              ),
          ],
        ),
      ],
    );
  }
}

class _Badge extends StatelessWidget {
  final String emoji;
  final String text;
  final Color textColor;
  final Color borderColor;

  const _Badge({
    required this.emoji,
    required this.text,
    required this.textColor,
    required this.borderColor,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
      decoration: BoxDecoration(
        color: Colors.black.withOpacity(0.6),
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: borderColor),
      ),
      child: Text(
        '$emoji $text',
        style: TextStyle(fontSize: 10, fontWeight: FontWeight.w700, color: textColor),
      ),
    );
  }
}
