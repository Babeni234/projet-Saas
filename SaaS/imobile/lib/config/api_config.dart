class ApiConfig {
  // Pour émulateur Android: http://10.0.2.2:8000
  // Pour appareil physique: http://VOTRE_IP:8000
  // Pour iOS simulator: http://localhost:8000
  // Pour web (localhost): http://127.0.0.1:8000
  static const String baseUrl = 'http://127.0.0.1:8000';

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
