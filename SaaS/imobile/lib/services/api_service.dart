import 'package:dio/dio.dart';
import '../config/api_config.dart';
import '../models/illustration.dart';
import '../models/comment.dart';
import '../models/company.dart';
import '../models/notification_model.dart';
import '../models/client.dart';

class ApiService {
  late final Dio _dio;

  ApiService() {
    _dio = Dio(BaseOptions(
      baseUrl: ApiConfig.baseUrl,
      connectTimeout: const Duration(seconds: 15),
      receiveTimeout: const Duration(seconds: 15),
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      },
    ));
  }

  void setAuthToken(String? token) {
    if (token != null) {
      _dio.options.headers['Authorization'] = 'Bearer $token';
    } else {
      _dio.options.headers.remove('Authorization');
    }
  }

  // ─── Feed ──────────────────────────────────────────────────────────────────
  Future<List<Illustration>> fetchFeed({
    String tab = 'foryou',
    String transaction = 'all',
    String type = 'all',
    int budget = 0,
    String city = '',
    String q = '',
    int? companyId,
    int? page,
    int? perPage,
    int? random,
  }) async {
    try {
      final params = <String, dynamic>{
        'tab': tab,
        'transaction': transaction,
        'type': type,
        'budget': budget,
        'city': city,
        'q': q,
      };
      if (companyId != null) params['company_id'] = companyId;
      if (page != null) params['page'] = page;
      if (perPage != null) params['per_page'] = perPage;
      if (random != null) params['random'] = random;

      final res = await _dio.get(ApiConfig.feed, queryParameters: params);
      if (res.data is List) {
        return (res.data as List).map((j) => Illustration.fromJson(j)).toList();
      }
      return [];
    } catch (e) {
      return [];
    }
  }

  // ─── Categories ────────────────────────────────────────────────────────────
  Future<List<String>> fetchCategories() async {
    try {
      final res = await _dio.get(ApiConfig.categories);
      if (res.data is List) {
        return List<String>.from(res.data);
      }
      return [];
    } catch (e) {
      return [];
    }
  }

  // ─── Auth ──────────────────────────────────────────────────────────────────
  Future<Map<String, dynamic>> checkAuth() async {
    try {
      final res = await _dio.get(ApiConfig.authMe);
      return res.data;
    } catch (e) {
      return {'success': false};
    }
  }

  Future<Map<String, dynamic>> login(String email, String password) async {
    final res = await _dio.post(ApiConfig.authLogin, data: {
      'email': email,
      'password': password,
    });
    return res.data;
  }

  Future<Map<String, dynamic>> register(Map<String, dynamic> data) async {
    final res = await _dio.post(ApiConfig.authRegister, data: data);
    return res.data;
  }

  Future<void> logout() async {
    await _dio.post(ApiConfig.authLogout);
  }

  // ─── Like / Favorite ───────────────────────────────────────────────────────
  Future<Map<String, dynamic>> toggleLike(int illustrationId) async {
    final res = await _dio.post(ApiConfig.illustrationLike(illustrationId));
    return res.data;
  }

  Future<Map<String, dynamic>> toggleFavorite(int illustrationId) async {
    final res = await _dio.post(ApiConfig.illustrationFavorite(illustrationId));
    return res.data;
  }

  // ─── Comments ──────────────────────────────────────────────────────────────
  Future<List<Comment>> fetchComments(int illustrationId) async {
    try {
      final res = await _dio.get(ApiConfig.illustrationComments(illustrationId));
      if (res.data is List) {
        return (res.data as List).map((j) => Comment.fromJson(j)).toList();
      }
      return [];
    } catch (e) {
      return [];
    }
  }

  Future<Map<String, dynamic>> postComment(int illustrationId, String text, {int? parentId}) async {
    final res = await _dio.post(
      ApiConfig.illustrationComments(illustrationId),
      data: {
        'text': text,
        'parent_id': parentId,
      },
    );
    return res.data;
  }

  // ─── Company Profile ───────────────────────────────────────────────────────
  Future<Map<String, dynamic>> fetchCompanyProfile(int companyId) async {
    final res = await _dio.get(ApiConfig.companyProfile(companyId));
    return res.data;
  }

  Future<Map<String, dynamic>> toggleSubscribe(int companyId) async {
    final res = await _dio.post(ApiConfig.companySubscribe(companyId));
    return res.data;
  }

  // ─── Reserve Visit ─────────────────────────────────────────────────────────
  Future<Map<String, dynamic>> reserveVisit(Map<String, dynamic> data) async {
    final res = await _dio.post(ApiConfig.reserveVisit, data: data);
    return res.data;
  }

  // ─── Chat ──────────────────────────────────────────────────────────────────
  Future<List<Map<String, dynamic>>> fetchChatHistory(int companyId) async {
    try {
      final res = await _dio.get(ApiConfig.chat(companyId));
      if (res.data is List) {
        return List<Map<String, dynamic>>.from(res.data);
      }
      return [];
    } catch (e) {
      return [];
    }
  }

  Future<Map<String, dynamic>> sendChatMessage(int companyId, String message, {int? agencyId}) async {
    final res = await _dio.post(ApiConfig.chat(companyId), data: {
      'message': message,
      'agency_id': agencyId,
    });
    return res.data;
  }

  // ─── Notifications ─────────────────────────────────────────────────────────
  Future<Map<String, dynamic>> fetchNotifications() async {
    final res = await _dio.get(ApiConfig.notifications);
    return res.data;
  }

  Future<void> markNotifRead({int? id, bool all = false}) async {
    await _dio.post(ApiConfig.notificationsMarkRead, data: {
      if (id != null) 'id': id,
      if (all) 'all': true,
    });
  }

  Future<Map<String, dynamic>> fetchUnreadCount() async {
    try {
      final res = await _dio.get(ApiConfig.notificationsUnreadCount);
      return res.data;
    } catch (e) {
      return {'count': 0};
    }
  }

  // ─── My Profile ────────────────────────────────────────────────────────────
  Future<Map<String, dynamic>> fetchMyProfile() async {
    final res = await _dio.get(ApiConfig.meProfile);
    return res.data;
  }
}
