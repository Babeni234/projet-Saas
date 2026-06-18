import 'package:flutter/material.dart';

class AppColors {
  // iOS 26 Aesthetic - Deep contrasts and vibrant accents
  static const Color primary = Color(0xFF007AFF); // Apple Blue
  static const Color primaryLight = Color(0xFF5AC8FA); // Light Blue
  static const Color secondary = Color(0xFFFF2D55); // Pink
  static const Color accent = Color(0xFF34C759); // Green

  // Backgrounds
  static const Color background = Color(0xFFFFFFFF); // iOS White Background
  static const Color surface = Colors.white;
  static const Color surfaceGlass = Color(0xCCFFFFFF); // 80% opaque white for glass

  // Text
  static const Color textPrimary = Color(0xFF000000); // Pure Black
  static const Color textSecondary = Color(0xFF8E8E93); // iOS Gray
  static const Color textTertiary = Color(0xFFC7C7CC);

  // Status
  static const Color error = Color(0xFFFF3B30); // Red
  static const Color success = Color(0xFF34C759); // Green
  static const Color warning = Color(0xFFFF9500); // Orange

  // Gradients for modern iOS look
  static const LinearGradient walletGradient = LinearGradient(
    colors: [Color(0xFF2C3E50), Color(0xFF000000)], // Sleek dark card
    begin: Alignment.topLeft,
    end: Alignment.bottomRight,
  );
  
  static const LinearGradient actionGradient = LinearGradient(
    colors: [Color(0xFF007AFF), Color(0xFF5AC8FA)],
    begin: Alignment.topLeft,
    end: Alignment.bottomRight,
  );
}
