import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:cached_network_image/cached_network_image.dart';
import '../config/theme.dart';
import '../providers/app_state.dart';
import '../models/illustration.dart';
import 'video_player_widget.dart';
import 'action_sidebar.dart';
import 'overlay_info.dart';
import 'floating_hearts.dart';

class FeedPlayer extends StatefulWidget {
  final VoidCallback onCommentTap;
  final VoidCallback onDetailsTap;
  final VoidCallback onShareTap;
  final VoidCallback onProfileTap;
  final Function(int companyId) onSubscribeTap;

  const FeedPlayer({
    super.key,
    required this.onCommentTap,
    required this.onDetailsTap,
    required this.onShareTap,
    required this.onProfileTap,
    required this.onSubscribeTap,
  });

  @override
  State<FeedPlayer> createState() => _FeedPlayerState();
}

class _FeedPlayerState extends State<FeedPlayer> {
  final PageController _pageController = PageController();
  final List<FloatingHeart> _floatingHearts = [];
  bool _isPlaying = true;
  int _lastTapTime = 0;
  Duration _currentTime = Duration.zero;
  Duration _duration = Duration.zero;
  final Map<int, GlobalKey<VideoPlayerWidgetState>> _videoKeys = {};

  @override
  void dispose() {
    _pageController.dispose();
    super.dispose();
  }

  void _handleDoubleTap(TapDownDetails details) {
    final state = context.read<AppState>();
    final item = state.currentItem;
    if (item == null) return;

    setState(() {
      _floatingHearts.add(FloatingHeart(
        id: DateTime.now().millisecondsSinceEpoch,
        x: details.localPosition.dx,
        y: details.localPosition.dy,
      ));
    });

    Future.delayed(const Duration(milliseconds: 800), () {
      if (mounted) {
        setState(() {
          _floatingHearts.removeWhere((h) => h.id < DateTime.now().millisecondsSinceEpoch - 700);
        });
      }
    });

    if (!item.hasLiked) {
      state.toggleLike(item);
    }
  }

  void _handleSingleTap() {
    final now = DateTime.now().millisecondsSinceEpoch;
    if (now - _lastTapTime < 300) return; // Skip, double tap handled
    _lastTapTime = now;

    Future.delayed(const Duration(milliseconds: 310), () {
      if (DateTime.now().millisecondsSinceEpoch - _lastTapTime >= 300) {
        _togglePlayPause();
      }
    });
  }

  void _togglePlayPause() {
    final state = context.read<AppState>();
    final item = state.currentItem;
    if (item == null) return;

    if (item.mediaType == 'video') {
      final key = _videoKeys[state.currentIndex];
      key?.currentState?.togglePlayPause();
    }
    setState(() => _isPlaying = !_isPlaying);
  }

  String _formatTime(Duration d) {
    final minutes = d.inMinutes;
    final seconds = d.inSeconds % 60;
    return '$minutes:${seconds.toString().padLeft(2, '0')}';
  }

  @override
  Widget build(BuildContext context) {
    return Consumer<AppState>(
      builder: (context, state, _) {
        if (state.feed.isEmpty) {
          return Center(
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                Icon(Icons.videocam_off, size: 48, color: ImmoTokTheme.gray600),
                const SizedBox(height: 16),
                Text(
                  'Aucun bien ne correspond aux filtres.',
                  style: TextStyle(color: ImmoTokTheme.gray400, fontSize: 16),
                ),
                const SizedBox(height: 16),
                ElevatedButton(
                  onPressed: () => state.resetFilters(),
                  style: ElevatedButton.styleFrom(
                    backgroundColor: ImmoTokTheme.redPrimary,
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
                  ),
                  child: const Text('Réinitialiser', style: TextStyle(color: Colors.white)),
                ),
              ],
            ),
          );
        }

        return PageView.builder(
          controller: _pageController,
          scrollDirection: Axis.vertical,
          itemCount: state.feed.length,
          onPageChanged: (index) {
            state.navigateToIndex(index);
            setState(() {
              _isPlaying = true;
              _currentTime = Duration.zero;
              _duration = Duration.zero;
            });
          },
          itemBuilder: (context, index) {
            final item = state.feed[index];
            final isCurrentPage = index == state.currentIndex;

            _videoKeys.putIfAbsent(index, () => GlobalKey<VideoPlayerWidgetState>());

            return GestureDetector(
              onTap: _handleSingleTap,
              onDoubleTapDown: _handleDoubleTap,
              onDoubleTap: () {},
              child: Container(
                color: Colors.black,
                child: Stack(
                  fit: StackFit.expand,
                  children: [
                    // Media Content
                    if (item.mediaType == 'video')
                      VideoPlayerWidget(
                        key: _videoKeys[index],
                        url: item.mediaUrl,
                        isMuted: state.isMuted,
                        shouldPlay: isCurrentPage && _isPlaying,
                        onTimeUpdate: (d) => setState(() => _currentTime = d),
                        onDurationLoaded: (d) => setState(() => _duration = d),
                      )
                    else
                      CachedNetworkImage(
                        imageUrl: item.mediaUrl,
                        fit: BoxFit.contain,
                        placeholder: (_, __) => const Center(
                          child: CircularProgressIndicator(color: ImmoTokTheme.redPrimary),
                        ),
                        errorWidget: (_, __, ___) => Container(
                          color: ImmoTokTheme.cardDark,
                          child: const Center(child: Icon(Icons.broken_image, color: Colors.white38, size: 48)),
                        ),
                      ),

                    // Play/Pause overlay icon
                    if (!_isPlaying && isCurrentPage)
                      Center(
                        child: Container(
                          width: 64,
                          height: 64,
                          decoration: BoxDecoration(
                            color: Colors.black.withOpacity(0.5),
                            shape: BoxShape.circle,
                          ),
                          child: const Icon(Icons.play_arrow, color: Colors.white, size: 36),
                        ),
                      ),

                    // Floating Hearts
                    if (isCurrentPage)
                      FloatingHeartsOverlay(hearts: _floatingHearts),

                    // Bottom gradient overlay
                    Positioned(
                      left: 0,
                      right: 0,
                      bottom: 0,
                      child: Container(
                        padding: const EdgeInsets.only(left: 12, right: 12, bottom: 12, top: 64),
                        decoration: BoxDecoration(
                          gradient: LinearGradient(
                            begin: Alignment.topCenter,
                            end: Alignment.bottomCenter,
                            colors: [
                              Colors.transparent,
                              Colors.black.withOpacity(0.4),
                              Colors.black.withOpacity(0.9),
                            ],
                          ),
                        ),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            // Video progress bar
                            if (item.mediaType == 'video' && isCurrentPage)
                              Padding(
                                padding: const EdgeInsets.only(bottom: 8),
                                child: Row(
                                  children: [
                                    Text(_formatTime(_currentTime),
                                      style: const TextStyle(fontSize: 10, color: ImmoTokTheme.gray300, fontFamily: 'monospace'),
                                    ),
                                    const SizedBox(width: 8),
                                    Expanded(
                                      child: SliderTheme(
                                        data: SliderThemeData(
                                          thumbShape: const RoundSliderThumbShape(enabledThumbRadius: 5),
                                          trackHeight: 3,
                                          overlayShape: const RoundSliderOverlayShape(overlayRadius: 10),
                                          activeTrackColor: ImmoTokTheme.redPrimary,
                                          inactiveTrackColor: Colors.white.withOpacity(0.2),
                                          thumbColor: Colors.white,
                                        ),
                                        child: Slider(
                                          value: _duration.inMilliseconds > 0
                                              ? _currentTime.inMilliseconds / _duration.inMilliseconds
                                              : 0,
                                          onChanged: (val) {
                                            final seekPos = Duration(milliseconds: (val * _duration.inMilliseconds).toInt());
                                            _videoKeys[index]?.currentState?.seekTo(seekPos);
                                          },
                                        ),
                                      ),
                                    ),
                                    const SizedBox(width: 8),
                                    Text(_formatTime(_duration),
                                      style: const TextStyle(fontSize: 10, color: ImmoTokTheme.gray300, fontFamily: 'monospace'),
                                    ),
                                  ],
                                ),
                              ),

                            // Info + Actions row
                            Row(
                              crossAxisAlignment: CrossAxisAlignment.end,
                              children: [
                                // Left: Overlay Info
                                Expanded(
                                  child: OverlayInfo(item: item),
                                ),
                                const SizedBox(width: 8),
                                // Right: Action Sidebar
                                ActionSidebar(
                                  item: item,
                                  isPlaying: _isPlaying,
                                  onProfileTap: widget.onProfileTap,
                                  onLikeTap: () {
                                    if (!state.isAuthenticated) {
                                      _showAuthSheet(context);
                                      return;
                                    }
                                    state.toggleLike(item);
                                  },
                                  onCommentTap: widget.onCommentTap,
                                  onFavoriteTap: () {
                                    if (!state.isAuthenticated) {
                                      _showAuthSheet(context);
                                      return;
                                    }
                                    state.toggleFavorite(item);
                                  },
                                  onDetailsTap: widget.onDetailsTap,
                                  onShareTap: widget.onShareTap,
                                  onSubscribeTap: () {
                                    if (!state.isAuthenticated) {
                                      _showAuthSheet(context);
                                      return;
                                    }
                                    widget.onSubscribeTap(item.company.id);
                                  },
                                ),
                              ],
                            ),
                          ],
                        ),
                      ),
                    ),
                  ],
                ),
              ),
            );
          },
        );
      },
    );
  }

  void _showAuthSheet(BuildContext context) {
    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(content: Text('Connectez-vous pour effectuer cette action'), backgroundColor: ImmoTokTheme.redPrimary),
    );
  }
}
