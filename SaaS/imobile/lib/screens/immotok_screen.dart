import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../config/theme.dart';
import '../providers/app_state.dart';
import '../widgets/splash_screen.dart';
import '../widgets/feed_player.dart';
import '../widgets/top_nav_bar.dart';
import '../widgets/bottom_nav_bar.dart';
import '../widgets/explore_page.dart';
import '../widgets/chat_fab.dart';
import '../sheets/comments_sheet.dart';
import '../sheets/share_sheet.dart';
import '../sheets/details_sheet.dart';
import '../sheets/reserve_sheet.dart';
import '../sheets/auth_sheet.dart';
import '../sheets/filter_sheet.dart';
import '../sheets/chat_sheet.dart';
import '../sheets/company_profile_sheet.dart';
import '../sheets/my_profile_sheet.dart';
import '../sheets/inbox_sheet.dart';
import '../sheets/create_post_sheet.dart';

class ImmoTokScreen extends StatefulWidget {
  const ImmoTokScreen({super.key});

  @override
  State<ImmoTokScreen> createState() => _ImmoTokScreenState();
}

class _ImmoTokScreenState extends State<ImmoTokScreen> {
  bool _showSplash = true;

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      context.read<AppState>().init();
    });
  }

  void _showAuthSheet({VoidCallback? onSuccess}) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (_) => Padding(
        padding: EdgeInsets.only(bottom: MediaQuery.of(context).viewInsets.bottom),
        child: AuthSheet(onSuccess: onSuccess),
      ),
    );
  }

  void _requireAuth(VoidCallback action) {
    final state = context.read<AppState>();
    if (state.isAuthenticated) {
      action();
    } else {
      _showAuthSheet(onSuccess: action);
    }
  }

  void _openComments() {
    final state = context.read<AppState>();
    if (state.currentItem == null) return;
    state.loadComments(state.currentItem!.id);
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (_) => const CommentsSheet(),
    );
  }

  void _openShare() {
    final state = context.read<AppState>();
    if (state.currentItem == null) return;
    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.transparent,
      builder: (_) => ShareSheet(item: state.currentItem!),
    );
  }

  void _openDetails() {
    final state = context.read<AppState>();
    if (state.currentItem == null) return;
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (_) => DetailsSheet(
        item: state.currentItem!,
        onReserveTap: () => _openReserve(),
      ),
    );
  }

  void _openReserve() {
    final state = context.read<AppState>();
    if (state.currentItem == null) return;
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (_) => ReserveSheet(illustrationId: state.currentItem!.id),
    );
  }

  void _openFilter() {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (_) => const FilterSheet(),
    );
  }

  void _openChat() {
    final state = context.read<AppState>();
    if (state.currentItem == null) return;
    _requireAuth(() {
      Navigator.push(
        context,
        MaterialPageRoute(builder: (_) => ChatSheet(item: state.currentItem!)),
      );
    });
  }

  void _openProfile() {
    final state = context.read<AppState>();
    if (state.currentItem == null) return;
    state.openProfile(state.currentItem!.company);
    Navigator.push(
      context,
      MaterialPageRoute(builder: (_) => CompanyProfileSheet(company: state.currentItem!.company)),
    ).then((result) {
      if (result == 'chat') {
        _openChat();
      }
    });
  }

  void _openInbox() {
    _requireAuth(() {
      Navigator.push(
        context,
        MaterialPageRoute(builder: (_) => const InboxSheet()),
      );
    });
  }

  void _openMyProfile() {
    _requireAuth(() {
      Navigator.push(
        context,
        MaterialPageRoute(builder: (_) => const MyProfileSheet()),
      );
    });
  }

  void _openCreatePost() {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (_) => const CreatePostSheet(),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: ImmoTokTheme.bgDark,
      body: Stack(
        children: [
          // Main content
          Consumer<AppState>(
            builder: (context, state, _) {
              return Column(
                children: [
                  Expanded(
                    child: Stack(
                      children: [
                        // Feed or Explore
                        if (state.activeTab == 'explore')
                          const ExplorePage()
                        else
                          FeedPlayer(
                            onCommentTap: _openComments,
                            onDetailsTap: _openDetails,
                            onShareTap: _openShare,
                            onProfileTap: _openProfile,
                            onSubscribeTap: (companyId) {
                              _requireAuth(() => state.toggleSubscribe(companyId));
                            },
                          ),

                        // Top Nav
                        Positioned(
                          top: 0,
                          left: 0,
                          right: 0,
                          child: TopNavBar(
                            onFilterTap: _openFilter,
                            onMuteTap: () {
                              state.toggleMute();
                              ScaffoldMessenger.of(context).showSnackBar(
                                SnackBar(
                                  content: Row(children: [
                                    Icon(state.isMuted ? Icons.volume_off : Icons.volume_up,
                                      color: state.isMuted ? ImmoTokTheme.redPrimary : ImmoTokTheme.greenOnline, size: 16),
                                    const SizedBox(width: 8),
                                    Text(state.isMuted ? 'Audio désactivé' : 'Audio activé en boucle'),
                                  ]),
                                  backgroundColor: Colors.black.withOpacity(0.75),
                                  duration: const Duration(milliseconds: 1200),
                                  behavior: SnackBarBehavior.floating,
                                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
                                  margin: EdgeInsets.only(
                                    top: 0,
                                    left: MediaQuery.of(context).size.width * 0.2,
                                    right: MediaQuery.of(context).size.width * 0.2,
                                    bottom: MediaQuery.of(context).size.height - 120,
                                  ),
                                ),
                              );
                            },
                          ),
                        ),
                      ],
                    ),
                  ),
                  // Bottom Nav
                  BottomNavBar(
                    activeTab: state.activeTab,
                    unreadCount: state.unreadCount,
                    onHomeTap: () => state.setActiveTab('foryou'),
                    onExploreTap: () => state.setActiveTab('explore'),
                    onCreateTap: _openCreatePost,
                    onInboxTap: _openInbox,
                    onProfileTap: _openMyProfile,
                  ),
                ],
              );
            },
          ),

          // Chat FAB
          Consumer<AppState>(
            builder: (context, state, _) {
              if (state.activeTab == 'explore') return const SizedBox.shrink();
              return Positioned(
                bottom: 80 + MediaQuery.of(context).padding.bottom,
                right: 16,
                child: ChatFab(onTap: _openChat),
              );
            },
          ),

          // Splash Screen
          if (_showSplash)
            SplashScreen(
              onComplete: () => setState(() => _showSplash = false),
            ),
        ],
      ),
    );
  }
}
