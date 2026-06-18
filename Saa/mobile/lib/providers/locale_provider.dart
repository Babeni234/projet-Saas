import 'package:flutter/material.dart';

class LocaleProvider extends ChangeNotifier {
  Locale _locale = const Locale('fr');

  Locale get locale => _locale;
  bool get isFrench => _locale.languageCode == 'fr';

  String get label => isFrench ? 'Français' : 'English';
  String get flag => isFrench ? '🇫🇷' : '🇬🇧';

  void toggleLocale() {
    _locale = isFrench ? const Locale('en') : const Locale('fr');
    notifyListeners();
  }

  void setLocale(Locale locale) {
    _locale = locale;
    notifyListeners();
  }
}
