import 'package:flutter/material.dart';
import '../config/theme.dart';
import '../providers/app_state.dart';
import 'package:provider/provider.dart';

class TopNavBar extends StatelessWidget {
  final VoidCallback onFilterTap;
  final VoidCallback onMuteTap;

  const TopNavBar({
    super.key,
    required this.onFilterTap,
    required this.onMuteTap,
  });

  @override
  Widget build(BuildContext context) {
    return Consumer<AppState>(
      builder: (context, state, _) {
        return Container(
          padding: EdgeInsets.only(
            top: MediaQuery.of(context).padding.top + 8,
            left: 8,
            right: 8,
            bottom: 8,
          ),
          decoration: BoxDecoration(
            gradient: LinearGradient(
              begin: Alignment.topCenter,
              end: Alignment.bottomCenter,
              colors: [Colors.black.withOpacity(0.8), Colors.transparent],
            ),
          ),
          child: Row(
            children: [
              // Filter button
              _CircleBtn(
                icon: Icons.tune,
                onTap: onFilterTap,
              ),
              const Spacer(),
              // Tabs
              Row(
                mainAxisSize: MainAxisSize.min,
                children: [
                  _TabBtn(
                    label: 'Pour vous',
                    isActive: state.activeTab == 'foryou',
                    onTap: () => state.setActiveTab('foryou'),
                  ),
                  const SizedBox(width: 16),
                  _TabBtn(
                    label: 'Abonnements',
                    isActive: state.activeTab == 'subs',
                    onTap: () {
                      if (!state.isAuthenticated) {
                        _showAuthNeeded(context);
                        return;
                      }
                      state.setActiveTab('subs');
                    },
                  ),
                  const SizedBox(width: 16),
                  _TabBtn(
                    label: 'Explorer',
                    isActive: state.activeTab == 'explore',
                    onTap: () => state.setActiveTab('explore'),
                  ),
                ],
              ),
              const Spacer(),
              // Mute button
              _CircleBtn(
                icon: state.isMuted ? Icons.volume_off : Icons.volume_up,
                iconColor: state.isMuted ? ImmoTokTheme.redPrimary : Colors.white,
                onTap: onMuteTap,
              ),
              const SizedBox(width: 4),
              // Language toggle
              GestureDetector(
                onTap: () => state.toggleLang(),
                child: Container(
                  padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                  decoration: BoxDecoration(
                    color: Colors.black.withOpacity(0.4),
                    borderRadius: BorderRadius.circular(20),
                    border: Border.all(color: Colors.white.withOpacity(0.2)),
                  ),
                  child: Text(
                    state.currentLang.toUpperCase(),
                    style: const TextStyle(fontSize: 10, fontWeight: FontWeight.w700, color: Colors.white),
                  ),
                ),
              ),
            ],
          ),
        );
      },
    );
  }

  void _showAuthNeeded(BuildContext context) {
    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(content: Text('Connectez-vous pour voir vos abonnements')),
    );
  }
}

class _CircleBtn extends StatelessWidget {
  final IconData icon;
  final Color iconColor;
  final VoidCallback onTap;

  const _CircleBtn({
    required this.icon,
    this.iconColor = Colors.white,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        width: 36,
        height: 36,
        decoration: BoxDecoration(
          color: Colors.black.withOpacity(0.4),
          shape: BoxShape.circle,
        ),
        child: Icon(icon, color: iconColor, size: 18),
      ),
    );
  }
}

class _TabBtn extends StatelessWidget {
  final String label;
  final bool isActive;
  final VoidCallback onTap;

  const _TabBtn({
    required this.label,
    required this.isActive,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          Text(
            label,
            style: TextStyle(
              fontSize: 12,
              fontWeight: FontWeight.w600,
              color: isActive ? Colors.white : ImmoTokTheme.gray400,
              letterSpacing: 0.5,
            ),
          ),
          const SizedBox(height: 4),
          Container(
            height: 2,
            width: 24,
            decoration: BoxDecoration(
              color: isActive ? ImmoTokTheme.redPrimary : Colors.transparent,
              borderRadius: BorderRadius.circular(1),
            ),
          ),
        ],
      ),
    );
  }
}
