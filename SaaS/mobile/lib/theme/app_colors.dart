import 'package:flutter/material.dart';

class AppColors {
  // Vibrant & Modern Palette
  static const Color primary = Color(0xFF5856D6); // Modern iOS Indigo
  static const Color primaryLight = Color(0xFF7D7AFF);
  static const Color secondary = Color(0xFFFF2D55); // Vibrant Pink
  static const Color accent = Color(0xFF00C7BE); // Teal / Mint

  // Backgrounds - Using soft tints for a premium feel
  static const Color background = Color(0xFFF2F2F7); // iOS System Background
  static const Color surface = Colors.white;
  static const Color surfaceGlass = Color(0xAAFFFFFF);

  // Text
  static const Color textPrimary = Color(0xFF1C1C1E); // Deep Navy/Black
  static const Color textSecondary = Color(0xFF8E8E93);
  static const Color textTertiary = Color(0xFFC7C7CC);

  // Status
  static const Color error = Color(0xFFFF3B30);
  static const Color success = Color(0xFF34C759);
  static const Color warning = Color(0xFFFF9500);

  // Modern Luxury Gradients
  static const LinearGradient walletGradient = LinearGradient(
    colors: [
      Color(0xFF1E1E1E),
      Color(0xFF2C2C2E),
    ],
    begin: Alignment.topLeft,
    end: Alignment.bottomRight,
  );

  static const LinearGradient actionGradient = LinearGradient(
    colors: [
      Color(0xFF5856D6), // Indigo
      Color(0xFFAF52DE), // Purple
    ],
    begin: Alignment.topLeft,
    end: Alignment.bottomRight,
  );

  static const LinearGradient successGradient = LinearGradient(
    colors: [
      Color(0xFF34C759),
      Color(0xFF30D158),
    ],
    begin: Alignment.topLeft,
    end: Alignment.bottomRight,
  );

  static const LinearGradient premiumGradient = LinearGradient(
    colors: [
      Color(0xFFFFD60A), // Gold
      Color(0xFFFF9F0A), // Orange
    ],
    begin: Alignment.topLeft,
    end: Alignment.bottomRight,
  );
}
