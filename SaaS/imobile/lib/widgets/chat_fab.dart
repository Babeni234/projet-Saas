import 'package:flutter/material.dart';
import '../config/theme.dart';

class ChatFab extends StatelessWidget {
  final VoidCallback onTap;

  const ChatFab({super.key, required this.onTap});

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        width: 48,
        height: 48,
        decoration: BoxDecoration(
          shape: BoxShape.circle,
          gradient: const LinearGradient(
            colors: [ImmoTokTheme.redPrimary, ImmoTokTheme.pinkAccent],
          ),
          boxShadow: [
            BoxShadow(
              color: ImmoTokTheme.redPrimary.withOpacity(0.4),
              blurRadius: 16,
              offset: const Offset(0, 4),
            ),
          ],
        ),
        child: Stack(
          children: [
            const Center(
              child: Icon(Icons.chat_bubble, color: Colors.white, size: 22),
            ),
            Positioned(
              top: 4,
              right: 4,
              child: Container(
                width: 10,
                height: 10,
                decoration: BoxDecoration(
                  color: ImmoTokTheme.greenOnline,
                  shape: BoxShape.circle,
                  border: Border.all(color: ImmoTokTheme.bgDark, width: 2),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
