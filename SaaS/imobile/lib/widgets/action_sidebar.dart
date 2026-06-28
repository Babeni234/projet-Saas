import 'package:flutter/material.dart';
import '../config/theme.dart';
import '../models/illustration.dart';

class ActionSidebar extends StatelessWidget {
  final Illustration item;
  final VoidCallback onProfileTap;
  final VoidCallback onLikeTap;
  final VoidCallback onCommentTap;
  final VoidCallback onFavoriteTap;
  final VoidCallback onDetailsTap;
  final VoidCallback onShareTap;
  final VoidCallback? onSubscribeTap;
  final bool isPlaying;

  const ActionSidebar({
    super.key,
    required this.item,
    required this.onProfileTap,
    required this.onLikeTap,
    required this.onCommentTap,
    required this.onFavoriteTap,
    required this.onDetailsTap,
    required this.onShareTap,
    this.onSubscribeTap,
    this.isPlaying = true,
  });

  @override
  Widget build(BuildContext context) {
    return Column(
      mainAxisSize: MainAxisSize.min,
      children: [
        // Profile Avatar
        GestureDetector(
          onTap: onProfileTap,
          child: Stack(
            clipBehavior: Clip.none,
            children: [
              Container(
                width: 44,
                height: 44,
                decoration: BoxDecoration(
                  shape: BoxShape.circle,
                  border: Border.all(color: Colors.white.withOpacity(0.95), width: 2),
                ),
                child: ClipOval(
                  child: Image.network(
                    item.company.logo,
                    fit: BoxFit.cover,
                    errorBuilder: (_, __, ___) => Container(
                      color: ImmoTokTheme.cardDark,
                      child: const Icon(Icons.business, color: Colors.white, size: 20),
                    ),
                  ),
                ),
              ),
              if (!item.hasSubscribed && onSubscribeTap != null)
                Positioned(
                  bottom: -4,
                  left: 0,
                  right: 0,
                  child: Center(
                    child: GestureDetector(
                      onTap: onSubscribeTap,
                      child: Container(
                        width: 18,
                        height: 18,
                        decoration: BoxDecoration(
                          color: ImmoTokTheme.redPrimary,
                          shape: BoxShape.circle,
                          border: Border.all(color: ImmoTokTheme.bgDark, width: 2),
                        ),
                        child: const Icon(Icons.add, size: 10, color: Colors.white),
                      ),
                    ),
                  ),
                ),
            ],
          ),
        ),
        const SizedBox(height: 16),

        // Like
        _ActionButton(
          icon: Icons.favorite,
          label: '${item.likesCount}',
          color: item.hasLiked ? const Color(0xFFEF4444) : Colors.white,
          onTap: onLikeTap,
        ),
        const SizedBox(height: 12),

        // Comments
        _ActionButton(
          icon: Icons.chat_bubble,
          label: '${item.commentsCount}',
          onTap: onCommentTap,
        ),
        const SizedBox(height: 12),

        // Favorite
        _ActionButton(
          icon: Icons.bookmark,
          label: '${item.favoritesCount}',
          color: item.hasFavorited ? ImmoTokTheme.yellowFav : Colors.white,
          onTap: onFavoriteTap,
        ),
        const SizedBox(height: 12),

        // Details
        _ActionButton(
          icon: Icons.info_outline,
          label: 'Détails',
          labelSize: 8,
          onTap: onDetailsTap,
        ),
        const SizedBox(height: 12),

        // Share
        _ActionButton(
          icon: Icons.share,
          label: 'Partager',
          labelSize: 8,
          onTap: onShareTap,
        ),
        const SizedBox(height: 12),

        // Rotating audio disk
        if (item.audioUrl != null)
          AnimatedRotation(
            turns: isPlaying ? 1 : 0,
            duration: const Duration(seconds: 4),
            child: Container(
              width: 40,
              height: 40,
              decoration: BoxDecoration(
                shape: BoxShape.circle,
                color: ImmoTokTheme.cardDark,
                border: Border.all(color: Colors.white.withOpacity(0.1), width: 3),
              ),
              child: ClipOval(
                child: Image.network(
                  item.company.logo,
                  width: 24,
                  height: 24,
                  fit: BoxFit.cover,
                  errorBuilder: (_, __, ___) => const Icon(Icons.music_note, size: 16, color: Colors.white),
                ),
              ),
            ),
          ),
      ],
    );
  }
}

class _ActionButton extends StatelessWidget {
  final IconData icon;
  final String label;
  final double labelSize;
  final Color color;
  final VoidCallback onTap;

  const _ActionButton({
    required this.icon,
    required this.label,
    this.labelSize = 10,
    this.color = Colors.white,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          Container(
            width: 40,
            height: 40,
            decoration: BoxDecoration(
              color: Colors.black.withOpacity(0.45),
              shape: BoxShape.circle,
            ),
            child: Icon(icon, color: color, size: 20),
          ),
          const SizedBox(height: 2),
          Text(
            label,
            style: TextStyle(
              fontSize: labelSize,
              fontWeight: FontWeight.w600,
              color: ImmoTokTheme.gray200,
              shadows: [Shadow(blurRadius: 4, color: Colors.black.withOpacity(0.5))],
            ),
          ),
        ],
      ),
    );
  }
}
