import 'dart:convert';
import 'package:flutter/foundation.dart';
import 'package:http/http.dart' as http;
import 'package:flutter_secure_storage/flutter_secure_storage.dart';

class ApiService extends ChangeNotifier {
  // Use localhost for Flutter Web/Chrome testing
  // Use 10.0.2.2 for Android emulator
  // Use your computer's IP for physical device
  final String baseUrl = 'http://localhost:8000/api';
  final _storage = const FlutterSecureStorage();

  bool _isAuthenticated = false;
  bool get isAuthenticated => _isAuthenticated;

  Map<String, dynamic>? _user;
  Map<String, dynamic>? get user => _user;

  // Locataire data
  Map<String, dynamic>? _locataireData;
  Map<String, dynamic>? get locataireData => _locataireData;

  // Error message storage
  String? _lastError;
  String? get lastError => _lastError;

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
      _lastError = null; // Clear previous error
      
      final response = await http.post(
        Uri.parse('$baseUrl/login'),
        headers: {'Content-Type': 'application/json', 'Accept': 'application/json'},
        body: jsonEncode({
          'email': email,
          'password': password,
          'device_name': 'mobile_app',
        }),
      );

      debugPrint('Login response status: ${response.statusCode}');
      debugPrint('Login response body: ${response.body}');

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        await _storage.write(key: 'token', value: data['token']);
        _user = data['user'];
        _isAuthenticated = true;
        await fetchLocataireData();
        notifyListeners();
        return true;
      } else {
        // Parse error message from backend
        final data = jsonDecode(response.body);
        _lastError = data['message'] ?? 
                     (data['errors'] != null ? data['errors']['email']?.join(', ') : null) ?? 
                     'Erreur de connexion';
        debugPrint('Login error: $_lastError');
        return false;
      }
    } catch (e) {
      _lastError = 'Erreur de connexion: $e';
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

  Future<bool> createTicket(String title, String category, String description) async {
    try {
      final token = await _getToken();
      if (token == null) return false;

      final response = await http.post(
        Uri.parse('$baseUrl/locataire/ticket/create'),
        headers: {
          'Authorization': 'Bearer $token',
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: jsonEncode({
          'title': title,
          'category': category,
          'description': description,
        }),
      );

      if (response.statusCode == 200) {
        await fetchLocataireData();
        return true;
      }
      return false;
    } catch (e) {
      debugPrint('Create Ticket Error: $e');
      return false;
    }
  }

  Future<bool> transferFunds(double amount, String? memo) async {
    try {
      final token = await _getToken();
      if (token == null) return false;

      final response = await http.post(
        Uri.parse('$baseUrl/locataire/wallet/transfer'),
        headers: {
          'Authorization': 'Bearer $token',
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: jsonEncode({
          'amount': amount,
          'memo': memo,
        }),
      );

      if (response.statusCode == 200) {
        await fetchLocataireData();
        return true;
      }
      return false;
    } catch (e) {
      debugPrint('Transfer Funds Error: $e');
      return false;
    }
  }

  Future<bool> payContractFee(int feeId, double amount) async {
    try {
      final token = await _getToken();
      if (token == null) return false;

      final response = await http.post(
        Uri.parse('$baseUrl/locataire/contract-fee/pay'),
        headers: {
          'Authorization': 'Bearer $token',
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: jsonEncode({
          'fee_id': feeId,
          'amount': amount,
        }),
      );

      if (response.statusCode == 200) {
        await fetchLocataireData();
        return true;
      }
      return false;
    } catch (e) {
      debugPrint('Pay Contract Fee Error: $e');
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

  List<dynamic> get tickets {
    return _locataireData?['tickets'] ?? [];
  }

  List<dynamic> get contractFees {
    return _locataireData?['contract_fees'] ?? [];
  }

  Map<String, dynamic>? get summary {
    return _locataireData?['summary'];
  }

  double? get totalDue {
    final value = summary?['total_due'];
    if (value == null) return 0.0;
    if (value is num) return value.toDouble();
    if (value is String) return double.tryParse(value) ?? 0.0;
    return 0.0;
  }

  int get openTicketsCount {
    final value = summary?['open_tickets_count'];
    if (value == null) return 0;
    if (value is int) return value;
    if (value is num) return value.toInt();
    return 0;
  }

  List<dynamic> get transactions {
    if (_locataireData == null || _locataireData!['wallet'] == null) return [];
    return _locataireData!['wallet']['transactions'] ?? [];
  }

  List<dynamic> get rentMonths {
    return _locataireData?['rent_months'] ?? [];
  }

  Map<String, dynamic>? get company {
    return _locataireData?['company'];
  }

  Map<String, dynamic>? get agency {
    return _locataireData?['agency'];
  }

  String get tenantFirstName {
    return _locataireData?['user']?['first_name'] ?? '';
  }

  String get tenantLastName {
    return _locataireData?['user']?['last_name'] ?? _user?['name'] ?? '';
  }

  String get tenantFullName {
    if (tenantFirstName.isNotEmpty && tenantLastName.isNotEmpty) {
      return '$tenantFirstName $tenantLastName';
    }
    return _user?['name'] ?? '';
  }
}
