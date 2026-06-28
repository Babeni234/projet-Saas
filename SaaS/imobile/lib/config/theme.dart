import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

class ImmoTokTheme {
  // Colors
  static const Color bgDark = Color(0xFF030712);
  static const Color cardDark = Color(0xFF181924);
  static const Color cardDarkAlt = Color(0xFF1E202D);
  static const Color redPrimary = Color(0xFF3B82F6);
  static const Color redDark = Color(0xFF1D4ED8);
  static const Color pinkAccent = Color(0xFF10B981);
  static const Color greenOnline = Color(0xFF10B981);
  static const Color yellowFav = Color(0xFFFACC15);
  static const Color blueAccent = Color(0xFF3B82F6);
  static const Color cyanTok = Color(0xFF60A5FA);
  static const Color whiteText = Color(0xFFFFFFFF);
  static const Color gray200 = Color(0xFFE5E7EB);
  static const Color gray300 = Color(0xFFD1D5DB);
  static const Color gray400 = Color(0xFF9CA3AF);
  static const Color gray500 = Color(0xFF6B7280);
  static const Color gray600 = Color(0xFF4B5563);

  static ThemeData get darkTheme {
    return ThemeData(
      brightness: Brightness.dark,
      scaffoldBackgroundColor: bgDark,
      colorScheme: const ColorScheme.dark(
        primary: redPrimary,
        secondary: pinkAccent,
        surface: cardDark,
      ),
      textTheme: GoogleFonts.interTextTheme(
        ThemeData.dark().textTheme,
      ),
      useMaterial3: true,
    );
  }
}
