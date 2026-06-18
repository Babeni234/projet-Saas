import 'dart:convert';
import 'package:flutter/foundation.dart';
import 'package:http/http.dart' as http;
import 'package:flutter_secure_storage/flutter_secure_storage.dart';

class ApiService extends ChangeNotifier {
  // Use 10.0.2.2 for Android emulator to access localhost, or your computer's IP for physical device.
  // Replace with your actual API URL in production
  final String baseUrl = 'http://127.0.0.1:8000/api';
  final _storage = const FlutterSecureStorage();

  bool _isAuthenticated = false;
  bool get isAuthenticated => _isAuthenticated;

  Map<String, dynamic>? _user;
  Map<String, dynamic>? get user => _user;

  ApiService() {
    _checkAuth();
  }

  Future<void> _checkAuth() async {
    try {
      final token = await _storage.read(key: 'token');
      if (token != null) {
        _isAuthenticated = true;
        // Optionally fetch user details here
        notifyListeners();
      }
    } catch (e) {
      debugPrint('Storage Error: $e');
    }
  }

  Future<bool> login(String email, String password) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/login'),
        headers: {'Content-Type': 'application/json', 'Accept': 'application/json'},
        body: jsonEncode({
          'email': email,
          'password': password,
          'device_name': 'mobile_app',
        }),
      );

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        await _storage.write(key: 'token', value: data['token']);
        _user = data['user'];
        _isAuthenticated = true;
        notifyListeners();
        return true;
      }
      return false;
    } catch (e) {
      debugPrint('Login Error: $e');
      return false;
    }
  }

  Future<void> logout() async {
    final token = await _storage.read(key: 'token');
    if (token != null) {
      try {
        await http.post(
          Uri.parse('$baseUrl/logout'),
          headers: {
            'Authorization': 'Bearer $token',
            'Accept': 'application/json'
          },
        );
      } catch (e) {
        debugPrint('Logout Error: $e');
      }
    }
    await _storage.delete(key: 'token');
    _isAuthenticated = false;
    _user = null;
    notifyListeners();
  }
}
