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

  // Locataire data
  Map<String, dynamic>? _locataireData;
  Map<String, dynamic>? get locataireData => _locataireData;

  ApiService() {
    _checkAuth();
  }

  Future<void> _checkAuth() async {
    try {
      final token = await _storage.read(key: 'token');
      if (token != null) {
        _isAuthenticated = true;
        await fetchLocataireData();
        notifyListeners();
      }
    } catch (e) {
      debugPrint('Storage Error: $e');
    }
  }

  Future<String?> _getToken() async {
    return await _storage.read(key: 'token');
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
        await fetchLocataireData();
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
    _locataireData = null;
    notifyListeners();
  }

  // Fetch locataire dashboard data
  Future<void> fetchLocataireData() async {
    try {
      final token = await _getToken();
      if (token == null) return;

      final response = await http.get(
        Uri.parse('$baseUrl/locataire/dashboard'),
        headers: {
          'Authorization': 'Bearer $token',
          'Accept': 'application/json'
        },
      );

      if (response.statusCode == 200) {
        _locataireData = jsonDecode(response.body);
        notifyListeners();
      }
    } catch (e) {
      debugPrint('Fetch Locataire Data Error: $e');
    }
  }

  // Wallet operations
  Future<bool> createWallet(String pin) async {
    try {
      final token = await _getToken();
      if (token == null) return false;

      final response = await http.post(
        Uri.parse('$baseUrl/locataire/wallet/create'),
        headers: {
          'Authorization': 'Bearer $token',
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: jsonEncode({'pin': pin}),
      );

      if (response.statusCode == 200) {
        await fetchLocataireData();
        return true;
      }
      return false;
    } catch (e) {
      debugPrint('Create Wallet Error: $e');
      return false;
    }
  }

  Future<bool> rechargeWallet(double amount) async {
    try {
      final token = await _getToken();
      if (token == null) return false;

      final response = await http.post(
        Uri.parse('$baseUrl/locataire/wallet/recharge'),
        headers: {
          'Authorization': 'Bearer $token',
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: jsonEncode({'amount': amount}),
      );

      if (response.statusCode == 200) {
        await fetchLocataireData();
        return true;
      }
      return false;
    } catch (e) {
      debugPrint('Recharge Wallet Error: $e');
      return false;
    }
  }

  Future<bool> payRent(double amount) async {
    try {
      final token = await _getToken();
      if (token == null) return false;

      final response = await http.post(
        Uri.parse('$baseUrl/locataire/wallet/pay-rent'),
        headers: {
          'Authorization': 'Bearer $token',
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: jsonEncode({'amount': amount}),
      );

      if (response.statusCode == 200) {
        await fetchLocataireData();
        return true;
      }
      return false;
    } catch (e) {
      debugPrint('Pay Rent Error: $e');
      return false;
    }
  }

  Future<bool> payUtility(double amount, String type) async {
    try {
      final token = await _getToken();
      if (token == null) return false;

      final response = await http.post(
        Uri.parse('$baseUrl/locataire/wallet/pay-utility'),
        headers: {
          'Authorization': 'Bearer $token',
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: jsonEncode({'amount': amount, 'type': type}),
      );

      if (response.statusCode == 200) {
        await fetchLocataireData();
        return true;
      }
      return false;
    } catch (e) {
      debugPrint('Pay Utility Error: $e');
      return false;
    }
  }

  // Getters for locataire data
  double? get walletBalance {
    if (_locataireData == null || _locataireData!['wallet'] == null) return null;
    return (_locataireData!['wallet']['solde'] as num?)?.toDouble();
  }

  List<dynamic> get contracts {
    return _locataireData?['contracts'] ?? [];
  }

  List<dynamic> get invoices {
    return _locataireData?['invoices'] ?? [];
  }

  List<dynamic> get receipts {
    return _locataireData?['receipts'] ?? [];
  }

  List<dynamic> get transactions {
    if (_locataireData == null || _locataireData!['wallet'] == null) return [];
    return _locataireData!['wallet']['transactions'] ?? [];
  }
}
