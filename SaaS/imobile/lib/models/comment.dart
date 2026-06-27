class Comment {
  final int id;
  final String name;
  final String avatar;
  final String text;
  final String? createdAt;
  List<Comment> replies;

  Comment({
    required this.id,
    required this.name,
    required this.avatar,
    required this.text,
    this.createdAt,
    this.replies = const [],
  });

  factory Comment.fromJson(Map<String, dynamic> json) {
    return Comment(
      id: json['id'] ?? 0,
      name: json['name'] ?? '',
      avatar: json['avatar'] ?? '',
      text: json['text'] ?? '',
      createdAt: json['created_at'],
      replies: json['replies'] != null
          ? (json['replies'] as List).map((r) => Comment.fromJson(r)).toList()
          : [],
    );
  }
}
