import 'package:flutter/material.dart';
import '../config/theme.dart';

class SplashScreen extends StatefulWidget {
  final VoidCallback onComplete;
  const SplashScreen({super.key, required this.onComplete});

  @override
  State<SplashScreen> createState() => _SplashScreenState();
}

class _SplashScreenState extends State<SplashScreen> with SingleTickerProviderStateMixin {
  double _progress = 0;

  @override
  void initState() {
    super.initState();
    _runSplash();
  }

  void _runSplash() async {
    while (_progress < 100) {
      await Future.delayed(Duration(milliseconds: 120 + (50 * (DateTime.now().millisecond % 3)).toInt()));
      if (!mounted) return;
      setState(() {
        _progress += (8 + (DateTime.now().millisecond % 18)).toDouble();
        if (_progress > 100) _progress = 100;
      });
    }
    await Future.delayed(const Duration(milliseconds: 300));
    if (mounted) widget.onComplete();
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      color: ImmoTokTheme.bgDark,
      child: Center(
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            // Logo
            Row(
              mainAxisSize: MainAxisSize.min,
              children: [
                Container(
                  width: 64,
                  height: 64,
                  decoration: BoxDecoration(
                    borderRadius: BorderRadius.circular(16),
                    gradient: const LinearGradient(
                      colors: [Color(0xFF2563EB), Color(0xFF4F46E5)],
                    ),
                  ),
                  padding: const EdgeInsets.all(3),
                  child: Container(
                    decoration: BoxDecoration(
                      color: ImmoTokTheme.bgDark,
                      borderRadius: BorderRadius.circular(13),
                    ),
                    child: const Center(
                      child: Text('I', style: TextStyle(fontSize: 32, fontWeight: FontWeight.w900, color: Colors.white)),
                    ),
                  ),
                ),
                const SizedBox(width: 12),
                Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    RichText(
                      text: const TextSpan(
                        style: TextStyle(fontSize: 32, fontWeight: FontWeight.w900),
                        children: [
                          TextSpan(text: 'Immo', style: TextStyle(color: Colors.white)),
                          TextSpan(text: 'Tok', style: TextStyle(color: ImmoTokTheme.redPrimary)),
                        ],
                      ),
                    ),
                    Text(
                      'IMMOBILIER & DÉCOUVERTE',
                      style: TextStyle(fontSize: 10, fontWeight: FontWeight.w600, color: ImmoTokTheme.gray500, letterSpacing: 2),
                    ),
                  ],
                ),
              ],
            ),
            const SizedBox(height: 32),
            // Progress bar
            SizedBox(
              width: 192,
              height: 4,
              child: ClipRRect(
                borderRadius: BorderRadius.circular(4),
                child: LinearProgressIndicator(
                  value: _progress / 100,
                  backgroundColor: Colors.white.withOpacity(0.1),
                  valueColor: const AlwaysStoppedAnimation<Color>(ImmoTokTheme.redPrimary),
                ),
              ),
            ),
            const SizedBox(height: 8),
            Text(
              '${_progress.round()}%',
              style: TextStyle(fontSize: 11, color: ImmoTokTheme.gray600, fontFamily: 'monospace'),
            ),
          ],
        ),
      ),
    );
  }
}
