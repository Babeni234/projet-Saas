import 'package:shared_preferences/shared_preferences.dart';
import '../models/client.dart';
import 'api_service.dart';

class AuthService {
  final ApiService _api;
  Client? _currentClient;
  String? _token;

  AuthService(this._api);

  Client? get currentClient => _currentClient;
  bool get isAuthenticated => _currentClient != null;

  Future<void> init() async {
    final prefs = await SharedPreferences.getInstance();
    _token = prefs.getString('auth_token');
    if (_token != null) {
      _api.setAuthToken(_token);
      await checkAuth();
    }
  }

  Future<bool> checkAuth() async {
    try {
      final res = await _api.checkAuth();
      if (res['success'] == true && res['client'] != null) {
        _currentClient = Client.fromJson(res['client']);
        return true;
      }
      _currentClient = null;
      return false;
    } catch (e) {
      _currentClient = null;
      return false;
    }
  }

  Future<Client?> login(String email, String password) async {
    final res = await _api.login(email, password);
    if (res['success'] == true && res['client'] != null) {
      _currentClient = Client.fromJson(res['client']);
      if (res['token'] != null) {
        _token = res['token'];
        _api.setAuthToken(_token);
        final prefs = await SharedPreferences.getInstance();
        await prefs.setString('auth_token', _token!);
      }
      return _currentClient;
    }
    throw Exception(res['message'] ?? 'Identifiants incorrects.');
  }

  Future<Client?> register(Map<String, dynamic> data) async {
    final res = await _api.register(data);
    if (res['success'] == true && res['client'] != null) {
      _currentClient = Client.fromJson(res['client']);
      if (res['token'] != null) {
        _token = res['token'];
        _api.setAuthToken(_token);
        final prefs = await SharedPreferences.getInstance();
        await prefs.setString('auth_token', _token!);
      }
      return _currentClient;
    }
    if (res['errors'] != null) {
      final errors = res['errors'] as Map<String, dynamic>;
      final firstError = (errors.values.first as List).first;
      throw Exception(firstError.toString());
    }
    throw Exception("Erreur lors de l'inscription.");
  }

  Future<void> logout() async {
    try {
      await _api.logout();
    } catch (_) {}
    _currentClient = null;
    _token = null;
    _api.setAuthToken(null);
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('auth_token');
  }
}
