import 'package:flutter/material.dart';
import 'package:video_player/video_player.dart';
import '../config/theme.dart';

class VideoPlayerWidget extends StatefulWidget {
  final String url;
  final bool isMuted;
  final bool shouldPlay;
  final ValueChanged<Duration>? onTimeUpdate;
  final ValueChanged<Duration>? onDurationLoaded;

  const VideoPlayerWidget({
    super.key,
    required this.url,
    required this.isMuted,
    required this.shouldPlay,
    this.onTimeUpdate,
    this.onDurationLoaded,
  });

  @override
  State<VideoPlayerWidget> createState() => VideoPlayerWidgetState();
}

class VideoPlayerWidgetState extends State<VideoPlayerWidget> {
  VideoPlayerController? _controller;
  bool _initialized = false;

  @override
  void initState() {
    super.initState();
    _initPlayer();
  }

  void _initPlayer() {
    _controller = VideoPlayerController.networkUrl(Uri.parse(widget.url))
      ..setLooping(true)
      ..setVolume(widget.isMuted ? 0 : 1)
      ..initialize().then((_) {
        if (!mounted) return;
        setState(() => _initialized = true);
        widget.onDurationLoaded?.call(_controller!.value.duration);
        if (widget.shouldPlay) {
          _controller!.play();
        }
        _controller!.addListener(_listener);
      });
  }

  void _listener() {
    if (_controller != null && mounted) {
      widget.onTimeUpdate?.call(_controller!.value.position);
    }
  }

  @override
  void didUpdateWidget(VideoPlayerWidget oldWidget) {
    super.didUpdateWidget(oldWidget);
    if (_controller == null) return;
    if (oldWidget.isMuted != widget.isMuted) {
      _controller!.setVolume(widget.isMuted ? 0 : 1);
    }
    if (oldWidget.shouldPlay != widget.shouldPlay) {
      if (widget.shouldPlay) {
        _controller!.play();
      } else {
        _controller!.pause();
      }
    }
  }

  void togglePlayPause() {
    if (_controller == null) return;
    if (_controller!.value.isPlaying) {
      _controller!.pause();
    } else {
      _controller!.play();
    }
  }

  bool get isPlaying => _controller?.value.isPlaying ?? false;

  void seekTo(Duration position) {
    _controller?.seekTo(position);
  }

  Duration get duration => _controller?.value.duration ?? Duration.zero;
  Duration get position => _controller?.value.position ?? Duration.zero;

  @override
  void dispose() {
    _controller?.removeListener(_listener);
    _controller?.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    if (!_initialized || _controller == null) {
      return Container(
        color: Colors.black,
        child: const Center(
          child: CircularProgressIndicator(color: ImmoTokTheme.redPrimary),
        ),
      );
    }
    return SizedBox.expand(
      child: FittedBox(
        fit: BoxFit.contain,
        child: SizedBox(
          width: _controller!.value.size.width,
          height: _controller!.value.size.height,
          child: VideoPlayer(_controller!),
        ),
      ),
    );
  }
}
