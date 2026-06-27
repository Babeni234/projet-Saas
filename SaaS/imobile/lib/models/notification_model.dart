class NotificationModel {
  final int id;
  final String title;
  final String message;
  final String? createdAt;
  final int? illustrationId;
  bool isRead;

  NotificationModel({
    required this.id,
    required this.title,
    required this.message,
    this.createdAt,
    this.illustrationId,
    this.isRead = false,
  });

  factory NotificationModel.fromJson(Map<String, dynamic> json) {
    return NotificationModel(
      id: json['id'] ?? 0,
      title: json['title'] ?? '',
      message: json['message'] ?? '',
      createdAt: json['created_at'],
      illustrationId: json['illustration_id'],
      isRead: json['is_read'] ?? false,
    );
  }
}
