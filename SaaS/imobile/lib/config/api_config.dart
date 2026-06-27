import 'package:flutter/foundation.dart';

class ApiConfig {
  static const String _defaultBaseUrl = 'http://127.0.0.1:8000';

  static String get baseUrl {
    if (kIsWeb) {
      try {
        final queryParams = Uri.base.queryParameters;
        if (queryParams.containsKey('api') && queryParams['api']!.isNotEmpty) {
          return queryParams['api']!;
        }
        if (Uri.base.host != 'localhost' && Uri.base.host != '127.0.0.1' && Uri.base.host.isNotEmpty) {
          return Uri.base.origin;
        }
      } catch (_) {}
    }
    return _defaultBaseUrl;
  }

  static String normalizeUrl(String? url) {
    if (url == null || url.isEmpty) return '';
    if (!url.startsWith('http')) return url;
    try {
      final uri = Uri.parse(url);
      if (uri.host == 'localhost' || uri.host == '127.0.0.1') {
        final baseUri = Uri.parse(baseUrl);
        return uri.replace(
          scheme: baseUri.scheme,
          host: baseUri.host,
          port: baseUri.port,
        ).toString();
      }
    } catch (_) {}
    return url;
  }

  // API Endpoints
  static const String feed = '/api/immotok/feed';
  static const String authMe = '/api/immotok/auth/me';
  static const String authLogin = '/api/immotok/auth/login';
  static const String authRegister = '/api/immotok/auth/register';
  static const String authLogout = '/api/immotok/auth/logout';
  static const String categories = '/api/immotok/categories';
  static const String reserveVisit = '/api/immotok/reserve-visit';
  static const String notifications = '/api/immotok/notifications';
  static const String notificationsMarkRead = '/api/immotok/notifications/mark-read';
  static const String notificationsUnreadCount = '/api/immotok/notifications/unread-count';
  static const String meProfile = '/api/immotok/me/profile';

  static String illustrationLike(int id) => '/api/immotok/illustrations/$id/like';
  static String illustrationFavorite(int id) => '/api/immotok/illustrations/$id/favorite';
  static String illustrationComments(int id) => '/api/immotok/illustrations/$id/comments';
  static String companyProfile(int id) => '/api/immotok/companies/$id/profile';
  static String companySubscribe(int id) => '/api/immotok/companies/$id/subscribe';
  static String chat(int companyId) => '/api/immotok/chat/$companyId';
}
