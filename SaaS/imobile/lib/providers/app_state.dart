import 'dart:async';
import 'package:flutter/material.dart';
import '../models/illustration.dart';
import '../models/client.dart';
import '../models/comment.dart';
import '../models/notification_model.dart';
import '../models/company.dart';
import '../services/api_service.dart';
import '../services/auth_service.dart';

class AppState extends ChangeNotifier {
  final ApiService api = ApiService();
  late final AuthService auth;

  // Feed state
  List<Illustration> feed = [];
  int currentIndex = 0;
  String activeTab = 'foryou'; // foryou, subs, explore
  String currentLang = 'fr';
  bool isMuted = true;
  bool isPlaying = true;
  int unreadCount = 0;

  // Filter options
  String filterTransaction = 'all';
  String filterType = 'all';
  int filterBudget = 0;
  String filterCity = '';

  // Categories
  List<String> categories = [];

  // Explore state
  String searchQuery = '';
  List<Illustration> exploreResults = [];
  bool exploreLoading = false;

  // Comments
  List<Comment> comments = [];
  Comment? replyTarget;

  // Company profile
  Company? profileCompany;
  int profileSubscribersCount = 0;
  int profileLikesCount = 0;
  bool profileHasSubscribed = false;
  List<Illustration> profileIllustrations = [];

  // My profile
  String myProfileTab = 'subs';
  bool myProfileLoading = false;
  Map<String, dynamic> myProfileData = {'subscriptions': [], 'favorites': [], 'stats': {}};

  // Notifications
  List<NotificationModel> notifications = [];
  bool notifsLoading = false;

  // Chat
  List<Map<String, dynamic>> chatMessages = [];
  bool isAiTyping = false;
  final List<String> chatSuggestions = [
    'Est-ce que ce bien est disponible ?',
    'Quelles sont les conditions de location ?',
    'Puis-je programmer une visite ?',
    'Quel est le loyer avec les charges ?',
  ];

  // Notification polling timer
  Timer? _notifTimer;

  AppState() {
    auth = AuthService(api);
  }

  Client? get client => auth.currentClient;
  bool get isAuthenticated => auth.isAuthenticated;

  Illustration? get currentItem {
    if (feed.isEmpty || currentIndex < 0 || currentIndex >= feed.length) return null;
    return feed[currentIndex];
  }

  // ─── Initialization ────────────────────────────────────────────────────────
  Future<void> init() async {
    await auth.init();
    await fetchCategories();
    await fetchFeed();
    _startNotificationPolling();
    notifyListeners();
  }

  // ─── Feed ──────────────────────────────────────────────────────────────────
  Future<void> fetchFeed() async {
    feed = await api.fetchFeed(
      tab: activeTab,
      transaction: filterTransaction,
      type: filterType,
      budget: filterBudget,
      city: filterCity,
    );
    currentIndex = 0;
    notifyListeners();
  }

  void navigateToIndex(int idx) {
    if (idx >= 0 && idx < feed.length) {
      currentIndex = idx;
      notifyListeners();
    }
  }

  void nextItem() {
    if (feed.isEmpty) return;
    if (currentIndex < feed.length - 1) {
      currentIndex++;
    } else {
      currentIndex = 0;
    }
    notifyListeners();
  }

  void prevItem() {
    if (feed.isEmpty) return;
    if (currentIndex > 0) {
      currentIndex--;
    } else {
      currentIndex = feed.length - 1;
    }
    notifyListeners();
  }

  void setActiveTab(String tab) {
    activeTab = tab;
    notifyListeners();
    if (tab == 'explore') {
      handleExploreSearch();
    } else {
      fetchFeed();
    }
  }

  void toggleMute() {
    isMuted = !isMuted;
    notifyListeners();
  }

  void toggleLang() {
    currentLang = currentLang == 'fr' ? 'en' : 'fr';
    notifyListeners();
  }

  // ─── Like / Favorite ───────────────────────────────────────────────────────
  Future<bool> toggleLike(Illustration item) async {
    if (!isAuthenticated) return false;
    final oldHasLiked = item.hasLiked;
    final oldLikesCount = item.likesCount;

    item.hasLiked = !oldHasLiked;
    item.likesCount = oldHasLiked ? oldLikesCount - 1 : oldLikesCount + 1;
    notifyListeners();

    try {
      final res = await api.toggleLike(item.id);
      if (res['success'] == true) {
        item.hasLiked = res['liked'] ?? !oldHasLiked;
        item.likesCount = res['likes_count'] ?? item.likesCount;
        notifyListeners();
        return true;
      } else {
        item.hasLiked = oldHasLiked;
        item.likesCount = oldLikesCount;
        notifyListeners();
      }
    } catch (e) {
      debugPrint('Like error: $e');
      item.hasLiked = oldHasLiked;
      item.likesCount = oldLikesCount;
      notifyListeners();
    }
    return false;
  }

  Future<bool> toggleFavorite(Illustration item) async {
    if (!isAuthenticated) return false;
    final oldHasFavorited = item.hasFavorited;
    final oldFavoritesCount = item.favoritesCount;

    item.hasFavorited = !oldHasFavorited;
    item.favoritesCount = oldHasFavorited ? oldFavoritesCount - 1 : oldFavoritesCount + 1;
    notifyListeners();

    try {
      final res = await api.toggleFavorite(item.id);
      if (res['success'] == true) {
        item.hasFavorited = res['favorited'] ?? !oldHasFavorited;
        item.favoritesCount = res['favorites_count'] ?? item.favoritesCount;
        notifyListeners();
        return true;
      } else {
        item.hasFavorited = oldHasFavorited;
        item.favoritesCount = oldFavoritesCount;
        notifyListeners();
      }
    } catch (e) {
      debugPrint('Favorite error: $e');
      item.hasFavorited = oldHasFavorited;
      item.favoritesCount = oldFavoritesCount;
      notifyListeners();
    }
    return false;
  }

  // ─── Comments ──────────────────────────────────────────────────────────────
  Future<void> loadComments(int illustrationId) async {
    comments = await api.fetchComments(illustrationId);
    notifyListeners();
  }

  Future<bool> sendComment(int illustrationId, String text) async {
    if (!isAuthenticated) return false;
    try {
      final res = await api.postComment(
        illustrationId,
        text,
        parentId: replyTarget?.id,
      );
      if (res['success'] == true && res['comment'] != null) {
        final newComment = Comment.fromJson(res['comment']);
        if (replyTarget != null) {
          final parent = comments.firstWhere(
            (c) => c.id == replyTarget!.id,
            orElse: () => comments.first,
          );
          parent.replies = [...parent.replies, newComment];
        } else {
          comments.insert(0, newComment);
        }
        if (currentItem != null && res['comments_count'] != null) {
          currentItem!.commentsCount = res['comments_count'];
        }
        replyTarget = null;
        notifyListeners();
        return true;
      }
    } catch (e) {
      debugPrint('Comment error: $e');
    }
    return false;
  }

  void setReplyTarget(Comment? comment) {
    replyTarget = comment;
    notifyListeners();
  }

  // ─── Subscribe ─────────────────────────────────────────────────────────────
  Future<bool> toggleSubscribe(int companyId) async {
    if (!isAuthenticated) return false;
    
    // Determine current subbed state
    bool isCurrentlySubbed = false;
    if (profileCompany != null && profileCompany!.id == companyId) {
      isCurrentlySubbed = profileHasSubscribed;
    } else {
      final match = feed.indexWhere((x) => x.company.id == companyId);
      if (match >= 0) {
        isCurrentlySubbed = feed[match].hasSubscribed;
      }
    }

    final newSubbed = !isCurrentlySubbed;

    // Save old state
    final oldHasSubscribed = profileHasSubscribed;
    final oldSubscribersCount = profileSubscribersCount;

    // Optimistic UI Update
    if (profileCompany != null && profileCompany!.id == companyId) {
      profileHasSubscribed = newSubbed;
      profileSubscribersCount = newSubbed ? oldSubscribersCount + 1 : oldSubscribersCount - 1;
    }
    for (var item in feed) {
      if (item.company.id == companyId) {
        item.hasSubscribed = newSubbed;
      }
    }
    notifyListeners();

    try {
      final res = await api.toggleSubscribe(companyId);
      if (res['success'] == true) {
        final subbed = res['subscribed'] ?? newSubbed;
        if (profileCompany != null && profileCompany!.id == companyId) {
          profileHasSubscribed = subbed;
          profileSubscribersCount = res['subscribers_count'] ?? profileSubscribersCount;
        }
        for (var item in feed) {
          if (item.company.id == companyId) {
            item.hasSubscribed = subbed;
          }
        }
        notifyListeners();
        return true;
      } else {
        // Revert on failure
        if (profileCompany != null && profileCompany!.id == companyId) {
          profileHasSubscribed = oldHasSubscribed;
          profileSubscribersCount = oldSubscribersCount;
        }
        for (var item in feed) {
          if (item.company.id == companyId) {
            item.hasSubscribed = oldHasSubscribed;
          }
        }
        notifyListeners();
      }
    } catch (e) {
      debugPrint('Subscribe error: $e');
      // Revert on error
      if (profileCompany != null && profileCompany!.id == companyId) {
        profileHasSubscribed = oldHasSubscribed;
        profileSubscribersCount = oldSubscribersCount;
      }
      for (var item in feed) {
        if (item.company.id == companyId) {
          item.hasSubscribed = oldHasSubscribed;
        }
      }
      notifyListeners();
    }
    return false;
  }

  // ─── Company Profile ───────────────────────────────────────────────────────
  Future<void> openProfile(Company company) async {
    profileCompany = company;
    profileSubscribersCount = 0;
    profileLikesCount = 0;
    profileHasSubscribed = false;
    profileIllustrations = [];
    notifyListeners();

    try {
      final res = await api.fetchCompanyProfile(company.id);
      if (res['success'] == true) {
        profileCompany = Company.fromJson(res['company']);
        profileSubscribersCount = res['subscribers_count'] ?? 0;
        profileLikesCount = res['likes_count'] ?? 0;
        profileHasSubscribed = res['has_subscribed'] ?? false;
        if (res['illustrations'] != null) {
          profileIllustrations = (res['illustrations'] as List)
              .map((j) => Illustration.fromJson(j))
              .toList();
        }
        notifyListeners();
      }
    } catch (e) {
      debugPrint('Profile error: $e');
    }
  }

  Future<void> playProfileIllustration(int illustrationId) async {
    if (profileCompany == null) return;
    try {
      final items = await api.fetchFeed(companyId: profileCompany!.id);
      feed = items;
      final idx = feed.indexWhere((item) => item.id == illustrationId);
      currentIndex = idx >= 0 ? idx : 0;
      activeTab = 'foryou';
      notifyListeners();
    } catch (e) {
      debugPrint('Play profile illustration error: $e');
    }
  }

  // ─── Explore ───────────────────────────────────────────────────────────────
  Future<void> handleExploreSearch() async {
    exploreLoading = true;
    notifyListeners();
    try {
      exploreResults = await api.fetchFeed(
        q: searchQuery,
        transaction: filterTransaction,
        type: filterType,
        budget: filterBudget,
        city: filterCity,
      );
    } catch (e) {
      debugPrint('Explore error: $e');
    }
    exploreLoading = false;
    notifyListeners();
  }

  void selectTrendingTag(String tag) {
    if (tag == 'Loyer < 500k') {
      searchQuery = '';
      filterBudget = 500000;
    } else {
      searchQuery = tag;
    }
    handleExploreSearch();
  }

  void playExploreItem(int idx) {
    feed = List.from(exploreResults);
    currentIndex = idx;
    activeTab = 'foryou';
    notifyListeners();
  }

  // ─── Categories ────────────────────────────────────────────────────────────
  Future<void> fetchCategories() async {
    categories = await api.fetchCategories();
    notifyListeners();
  }

  // ─── Filters ───────────────────────────────────────────────────────────────
  void applyFilters() {
    fetchFeed();
  }

  void resetFilters() {
    filterTransaction = 'all';
    filterType = 'all';
    filterBudget = 0;
    filterCity = '';
    notifyListeners();
    fetchFeed();
  }

  // ─── Reserve Visit ─────────────────────────────────────────────────────────
  Future<String?> submitReservation(Map<String, dynamic> data) async {
    try {
      final res = await api.reserveVisit(data);
      if (res['success'] == true) {
        return res['message'] ?? 'Réservation effectuée !';
      }
      return null;
    } catch (e) {
      return null;
    }
  }

  // ─── Auth ──────────────────────────────────────────────────────────────────
  Future<String?> login(String email, String password) async {
    try {
      await auth.login(email, password);
      await fetchFeed();
      notifyListeners();
      return null;
    } catch (e) {
      return e.toString().replaceFirst('Exception: ', '');
    }
  }

  Future<String?> register(Map<String, dynamic> data) async {
    try {
      await auth.register(data);
      await fetchFeed();
      notifyListeners();
      return null;
    } catch (e) {
      return e.toString().replaceFirst('Exception: ', '');
    }
  }

  Future<void> logout() async {
    await auth.logout();
    notifyListeners();
    fetchFeed();
  }

  // ─── Chat ──────────────────────────────────────────────────────────────────
  Future<void> loadChatHistory(int companyId) async {
    if (!isAuthenticated) return;
    chatMessages = await api.fetchChatHistory(companyId);
    notifyListeners();
  }

  Future<void> sendChatMessage(int companyId, String text, {int? agencyId}) async {
    if (!isAuthenticated || text.trim().isEmpty) return;

    chatMessages.add({
      'id': DateTime.now().millisecondsSinceEpoch,
      'sender': 'client',
      'message': text,
      'created_at': DateTime.now().toIso8601String(),
    });
    isAiTyping = true;
    notifyListeners();

    try {
      final res = await api.sendChatMessage(companyId, text, agencyId: agencyId);
      if (res['success'] == true && res['ai_message'] != null) {
        await Future.delayed(const Duration(milliseconds: 800));
        isAiTyping = false;
        chatMessages.add(Map<String, dynamic>.from(res['ai_message']));
        notifyListeners();
      } else {
        isAiTyping = false;
        notifyListeners();
      }
    } catch (e) {
      isAiTyping = false;
      notifyListeners();
    }
  }

  // ─── Notifications ─────────────────────────────────────────────────────────
  Future<void> loadNotifications() async {
    if (!isAuthenticated) return;
    notifsLoading = true;
    notifyListeners();
    try {
      final res = await api.fetchNotifications();
      if (res['success'] == true && res['notifications'] != null) {
        notifications = (res['notifications'] as List)
            .map((j) => NotificationModel.fromJson(j))
            .toList();
      }
    } catch (e) {
      debugPrint('Notifications error: $e');
    }
    notifsLoading = false;
    notifyListeners();
  }

  Future<void> markNotifRead(NotificationModel notif) async {
    if (!notif.isRead) {
      notif.isRead = true;
      if (unreadCount > 0) unreadCount--;
      notifyListeners();
      await api.markNotifRead(id: notif.id);
    }
  }

  Future<void> markAllNotifsRead() async {
    for (var n in notifications) {
      n.isRead = true;
    }
    unreadCount = 0;
    notifyListeners();
    await api.markNotifRead(all: true);
  }

  Future<void> playFavoriteItem(int illustrationId) async {
    try {
      final items = await api.fetchFeed();
      feed = items;
      final idx = feed.indexWhere((item) => item.id == illustrationId);
      currentIndex = idx >= 0 ? idx : 0;
      activeTab = 'foryou';
      notifyListeners();
    } catch (e) {
      debugPrint('Play favorite error: $e');
    }
  }

  // ─── My Profile ────────────────────────────────────────────────────────────
  Future<void> loadMyProfile() async {
    if (!isAuthenticated) return;
    myProfileLoading = true;
    notifyListeners();
    try {
      final res = await api.fetchMyProfile();
      if (res['success'] == true) {
        myProfileData = res;
      }
    } catch (e) {
      debugPrint('My profile error: $e');
    }
    myProfileLoading = false;
    notifyListeners();
  }

  // ─── Notification Polling ──────────────────────────────────────────────────
  void _startNotificationPolling() {
    _notifTimer = Timer.periodic(const Duration(seconds: 60), (_) async {
      if (!isAuthenticated) return;
      try {
        final res = await api.fetchUnreadCount();
        if (res['count'] != null) {
          unreadCount = res['count'];
          notifyListeners();
        }
      } catch (_) {}
    });
  }

  @override
  void dispose() {
    _notifTimer?.cancel();
    super.dispose();
  }
}
