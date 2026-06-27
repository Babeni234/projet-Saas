import 'package:flutter/material.dart';
import '../config/theme.dart';

class BottomNavBar extends StatelessWidget {
  final String activeTab;
  final int unreadCount;
  final VoidCallback onHomeTap;
  final VoidCallback onExploreTap;
  final VoidCallback onCreateTap;
  final VoidCallback onInboxTap;
  final VoidCallback onProfileTap;

  const BottomNavBar({
    super.key,
    required this.activeTab,
    required this.unreadCount,
    required this.onHomeTap,
    required this.onExploreTap,
    required this.onCreateTap,
    required this.onInboxTap,
    required this.onProfileTap,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: EdgeInsets.only(
        bottom: MediaQuery.of(context).padding.bottom + 4,
        top: 8,
        left: 24,
        right: 24,
      ),
      decoration: BoxDecoration(
        color: ImmoTokTheme.bgDark.withOpacity(0.95),
        border: Border(top: BorderSide(color: Colors.white.withOpacity(0.05))),
      ),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          _NavItem(
            icon: Icons.home,
            label: 'Accueil',
            isActive: activeTab != 'explore',
            activeColor: ImmoTokTheme.redPrimary,
            onTap: onHomeTap,
          ),
          _NavItem(
            icon: Icons.explore,
            label: 'Explorer',
            isActive: activeTab == 'explore',
            activeColor: ImmoTokTheme.redPrimary,
            onTap: onExploreTap,
          ),
          // Create button (TikTok-style gradient)
          GestureDetector(
            onTap: onCreateTap,
            child: Container(
              width: 48,
              height: 32,
              decoration: BoxDecoration(
                borderRadius: BorderRadius.circular(10),
                gradient: const LinearGradient(
                  colors: [ImmoTokTheme.cyanTok, Colors.white, ImmoTokTheme.redPrimary],
                ),
              ),
              padding: const EdgeInsets.all(2),
              child: Container(
                decoration: BoxDecoration(
                  color: ImmoTokTheme.bgDark,
                  borderRadius: BorderRadius.circular(8),
                ),
                child: const Center(
                  child: Icon(Icons.add, color: Colors.white, size: 16),
                ),
              ),
            ),
          ),
          // Inbox with badge
          Stack(
            clipBehavior: Clip.none,
            children: [
              _NavItem(
                icon: Icons.notifications_none,
                label: 'Alertes',
                isActive: false,
                onTap: onInboxTap,
              ),
              if (unreadCount > 0)
                Positioned(
                  top: -4,
                  right: -6,
                  child: Container(
                    padding: const EdgeInsets.symmetric(horizontal: 4, vertical: 1),
                    decoration: BoxDecoration(
                      color: ImmoTokTheme.redPrimary,
                      borderRadius: BorderRadius.circular(10),
                      border: Border.all(color: ImmoTokTheme.bgDark, width: 1.5),
                    ),
                    constraints: const BoxConstraints(minWidth: 16, minHeight: 16),
                    child: Text(
                      '$unreadCount',
                      textAlign: TextAlign.center,
                      style: const TextStyle(fontSize: 9, fontWeight: FontWeight.w700, color: Colors.white),
                    ),
                  ),
                ),
            ],
          ),
          _NavItem(
            icon: Icons.person_outline,
            label: 'Moi',
            isActive: false,
            onTap: onProfileTap,
          ),
        ],
      ),
    );
  }
}

class _NavItem extends StatelessWidget {
  final IconData icon;
  final String label;
  final bool isActive;
  final Color activeColor;
  final VoidCallback onTap;

  const _NavItem({
    required this.icon,
    required this.label,
    required this.isActive,
    this.activeColor = ImmoTokTheme.redPrimary,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    final color = isActive ? activeColor : ImmoTokTheme.gray400;
    return GestureDetector(
      onTap: onTap,
      behavior: HitTestBehavior.opaque,
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(icon, color: color, size: 22),
          const SizedBox(height: 2),
          Text(label, style: TextStyle(fontSize: 10, fontWeight: FontWeight.w600, color: color)),
        ],
      ),
    );
  }
}
