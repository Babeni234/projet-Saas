import 'dart:math';
import 'package:flutter/material.dart';
import '../config/theme.dart';

class FloatingHeart {
  final int id;
  final double x;
  final double y;

  FloatingHeart({required this.id, required this.x, required this.y});
}

class FloatingHeartsOverlay extends StatelessWidget {
  final List<FloatingHeart> hearts;

  const FloatingHeartsOverlay({super.key, required this.hearts});

  @override
  Widget build(BuildContext context) {
    return Stack(
      children: hearts.map((heart) => _AnimatedHeart(key: ValueKey(heart.id), heart: heart)).toList(),
    );
  }
}

class _AnimatedHeart extends StatefulWidget {
  final FloatingHeart heart;

  const _AnimatedHeart({super.key, required this.heart});

  @override
  State<_AnimatedHeart> createState() => _AnimatedHeartState();
}

class _AnimatedHeartState extends State<_AnimatedHeart> with SingleTickerProviderStateMixin {
  late AnimationController _controller;
  late Animation<double> _scaleAnimation;
  late Animation<double> _opacityAnimation;
  late Animation<Offset> _positionAnimation;

  @override
  void initState() {
    super.initState();
    _controller = AnimationController(
      duration: const Duration(milliseconds: 800),
      vsync: this,
    );

    _scaleAnimation = TweenSequence<double>([
      TweenSequenceItem(tween: Tween(begin: 0.0, end: 1.2), weight: 15),
      TweenSequenceItem(tween: Tween(begin: 1.2, end: 1.0), weight: 65),
      TweenSequenceItem(tween: Tween(begin: 1.0, end: 0.6), weight: 20),
    ]).animate(_controller);

    _opacityAnimation = TweenSequence<double>([
      TweenSequenceItem(tween: Tween(begin: 0.0, end: 0.9), weight: 15),
      TweenSequenceItem(tween: Tween(begin: 0.9, end: 0.85), weight: 65),
      TweenSequenceItem(tween: Tween(begin: 0.85, end: 0.0), weight: 20),
    ]).animate(_controller);

    _positionAnimation = Tween<Offset>(
      begin: Offset.zero,
      end: Offset((Random().nextDouble() - 0.5) * 20, -80),
    ).animate(CurvedAnimation(parent: _controller, curve: Curves.easeOut));

    _controller.forward();
  }

  @override
  void dispose() {
    _controller.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return AnimatedBuilder(
      animation: _controller,
      builder: (context, child) {
        return Positioned(
          left: widget.heart.x - 24 + _positionAnimation.value.dx,
          top: widget.heart.y - 24 + _positionAnimation.value.dy,
          child: Opacity(
            opacity: _opacityAnimation.value,
            child: Transform.scale(
              scale: _scaleAnimation.value,
              child: const Icon(Icons.favorite, color: ImmoTokTheme.redPrimary, size: 48),
            ),
          ),
        );
      },
    );
  }
}
