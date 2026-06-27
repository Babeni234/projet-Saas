import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:provider/provider.dart';
import 'config/theme.dart';
import 'providers/app_state.dart';
import 'screens/immotok_screen.dart';

void main() {
  WidgetsFlutterBinding.ensureInitialized();
  SystemChrome.setSystemUIOverlayStyle(const SystemUiOverlayStyle(
    statusBarColor: Colors.transparent,
    statusBarIconBrightness: Brightness.light,
    systemNavigationBarColor: ImmoTokTheme.bgDark,
    systemNavigationBarIconBrightness: Brightness.light,
  ));
  SystemChrome.setPreferredOrientations([DeviceOrientation.portraitUp]);
  runApp(const ImmoTokApp());
}

class ImmoTokApp extends StatelessWidget {
  const ImmoTokApp({super.key});

  @override
  Widget build(BuildContext context) {
    return ChangeNotifierProvider(
      create: (_) => AppState(),
      child: MaterialApp(
        title: 'ImmoTok',
        debugShowCheckedModeBanner: false,
        theme: ImmoTokTheme.darkTheme,
        home: const ImmoTokScreen(),
      ),
    );
  }
}
