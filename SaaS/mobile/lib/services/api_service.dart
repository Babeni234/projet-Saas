import 'dart:convert';
import 'dart:io';
import 'package:flutter/foundation.dart';
import 'package:http/http.dart' as http;
import 'package:flutter_secure_storage/flutter_secure_storage.dart';

class ApiService extends ChangeNotifier {
  final String baseUrl = 'https://unseeking-troy-floggingly.ngrok-free.dev/api';
  final _storage = const FlutterSecureStorage();

  bool _isAuthenticated = false;
  bool get isAuthenticated => _isAuthenticated;

  Map<String, dynamic>? _user;
  Map<String, dynamic>? get user => _user;

  // Locataire data
  Map<String, dynamic>? _locataireData;
  Map<String, dynamic>? get locataireData => _locataireData;

  // Locally persisted data (mirrors web localStorage behavior)
  Map<String, dynamic> _localProfile = {};
  Map<String, dynamic> _localPrefs = {};
  List<Map<String, dynamic>> _localTickets = [];
  bool _twoFAEnabled = false;
  List<String> _backupCodes = [];

  // Error message storage
  String? _lastError;
  String? get lastError => _lastError;

  ApiService() {
    _checkAuth();
    _loadLocalData();
  }

  Future<void> _loadLocalData() async {
    try {
      final profile = await _storage.read(key: 'local_profile');
      if (profile != null) _localProfile = jsonDecode(profile);
      final prefs = await _storage.read(key: 'local_prefs');
      if (prefs != null) _localPrefs = jsonDecode(prefs);
      final tickets = await _storage.read(key: 'local_tickets');
      if (tickets != null) _localTickets = List<Map<String, dynamic>>.from(jsonDecode(tickets));
      final twoFA = await _storage.read(key: '2fa_enabled');
      if (twoFA != null) _twoFAEnabled = twoFA == 'true';
      final codes = await _storage.read(key: 'backup_codes');
      if (codes != null) _backupCodes = List<String>.from(jsonDecode(codes));
    } catch (_) {}
  }

  Future<void> _saveLocalProfile() async {
    await _storage.write(key: 'local_profile', value: jsonEncode(_localProfile));
  }

  Future<void> _saveLocalPrefs() async {
    await _storage.write(key: 'local_prefs', value: jsonEncode(_localPrefs));
  }

  Future<void> _saveLocalTickets() async {
    await _storage.write(key: 'local_tickets', value: jsonEncode(_localTickets));
  }

  Map<String, dynamic> get localProfile => _localProfile;
  Map<String, dynamic> get localPrefs => _localPrefs;
  List<Map<String, dynamic>> get localTickets => _localTickets;
  bool get twoFAEnabled => _twoFAEnabled;
  List<String> get backupCodes => _backupCodes;

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

  Future<Map<String, String>> _authHeaders() async {
    final token = await _getToken();
    return {
      'Authorization': 'Bearer $token',
      'Accept': 'application/json',
      'Content-Type': 'application/json',
    };
  }

  Future<bool> login(String email, String password) async {
    try {
      _lastError = null;
      
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

  // ═══ WALLET PIN CHANGE (real API) ═══
  Future<Map<String, dynamic>> changePin(String currentPin, String newPin) async {
    try {
      final headers = await _authHeaders();
      final response = await http.post(
        Uri.parse('$baseUrl/locataire/wallet/change-pin'),
        headers: headers,
        body: jsonEncode({'current_pin': currentPin, 'new_pin': newPin}),
      );
      final data = jsonDecode(response.body);
      if (response.statusCode == 200) {
        return {'success': true, 'message': data['message'] ?? 'PIN modifié avec succès'};
      }
      return {'success': false, 'message': data['message'] ?? 'Erreur lors du changement de PIN'};
    } catch (e) {
      return {'success': false, 'message': 'Erreur: $e'};
    }
  }

  // ═══ PROFILE OPERATIONS (local storage, matching web behavior) ═══
  void updateLocalProfile(Map<String, dynamic> profile) {
    _localProfile.addAll(profile);
    _saveLocalProfile();
    notifyListeners();
  }

  void changePassword(String current, String newPass) {
    // Web mock: just store locally
    _localProfile['password_changed_at'] = DateTime.now().toIso8601String();
    _saveLocalProfile();
    notifyListeners();
  }

  // ═══ 2FA (local storage, matching web behavior) ═══
  void enable2FA() {
    _twoFAEnabled = true;
    _storage.write(key: '2fa_enabled', value: 'true');
    notifyListeners();
  }

  void disable2FA() {
    _twoFAEnabled = false;
    _storage.write(key: '2fa_enabled', value: 'false');
    notifyListeners();
  }

  void saveBackupCodes(List<String> codes) {
    _backupCodes = codes;
    _storage.write(key: 'backup_codes', value: jsonEncode(codes));
    notifyListeners();
  }

  // ═══ NOTIFICATION PREFERENCES (local storage) ═══
  void updateNotificationPrefs(Map<String, dynamic> prefs) {
    _localPrefs = prefs;
    _saveLocalPrefs();
    notifyListeners();
  }

  // ═══ TICKET MESSAGING (local storage, matching web behavior) ═══
  void addLocalTicket(Map<String, dynamic> ticket) {
    _localTickets.insert(0, ticket);
    _saveLocalTickets();
    notifyListeners();
  }

  void addLocalMessage(int ticketIndex, Map<String, dynamic> message) {
    if (ticketIndex >= 0 && ticketIndex < _localTickets.length) {
      _localTickets[ticketIndex]['messages'].add(message);
      _saveLocalTickets();
      notifyListeners();
    }
  }

  void closeLocalTicket(int ticketIndex) {
    if (ticketIndex >= 0 && ticketIndex < _localTickets.length) {
      _localTickets[ticketIndex]['status'] = 'closed';
      _saveLocalTickets();
      notifyListeners();
    }
  }

  // ═══ DOCUMENT DOWNLOAD URL helper ═══
  Future<String?> downloadDocument(String url) async {
    // Returns the full URL for document download
    if (url.startsWith('http')) return url;
    return '$baseUrl/../storage/$url';
  }

  // ═══ GETTERS ═══
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

  List<dynamic> get oldContracts {
    return _locataireData?['old_contracts'] ?? [];
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
    return _locataireData?['user']?['first_name'] ?? _localProfile['first_name'] ?? '';
  }

  String get tenantLastName {
    return _locataireData?['user']?['last_name'] ?? _localProfile['last_name'] ?? _user?['name'] ?? '';
  }

  String get tenantFullName {
    if (tenantFirstName.isNotEmpty && tenantLastName.isNotEmpty) {
      return '$tenantFirstName $tenantLastName';
    }
    return _user?['name'] ?? '';
  }

  String get tenantEmail {
    return _locataireData?['user']?['email'] ?? _localProfile['email'] ?? _user?['email'] ?? '';
  }

  String get tenantPhone {
    return _locataireData?['telephone'] ?? _localProfile['phone'] ?? '';
  }

  String get tenantAvatar {
    return _localProfile['avatar'] ?? _locataireData?['user']?['avatar'] ?? '';
  }
}
