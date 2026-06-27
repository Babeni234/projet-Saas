import '../config/api_config.dart';
import 'company.dart';

class PropertyInfo {
  final String priceLabel;
  final String? transaction;
  final String? city;
  final String? neighborhood;
  final String? surface;
  final String? type;
  final int? rooms;
  final List<String> features;

  PropertyInfo({
    required this.priceLabel,
    this.transaction,
    this.city,
    this.neighborhood,
    this.surface,
    this.type,
    this.rooms,
    this.features = const [],
  });

  factory PropertyInfo.fromJson(Map<String, dynamic> json) {
    return PropertyInfo(
      priceLabel: json['price_label'] ?? '',
      transaction: json['transaction'],
      city: json['city'],
      neighborhood: json['neighborhood'],
      surface: json['surface']?.toString(),
      type: json['type'],
      rooms: json['rooms'],
      features: json['features'] != null
          ? List<String>.from(json['features'])
          : [],
    );
  }
}

class Illustration {
  final int id;
  final String mediaType;
  final String mediaUrl;
  final String? audioUrl;
  final String description;
  final Company company;
  final PropertyInfo property;
  final int? agencyId;
  int likesCount;
  int commentsCount;
  int favoritesCount;
  bool hasLiked;
  bool hasFavorited;
  bool hasSubscribed;

  Illustration({
    required this.id,
    required this.mediaType,
    required this.mediaUrl,
    this.audioUrl,
    required this.description,
    required this.company,
    required this.property,
    this.agencyId,
    this.likesCount = 0,
    this.commentsCount = 0,
    this.favoritesCount = 0,
    this.hasLiked = false,
    this.hasFavorited = false,
    this.hasSubscribed = false,
  });

  factory Illustration.fromJson(Map<String, dynamic> json) {
    return Illustration(
      id: json['id'] ?? 0,
      mediaType: json['media_type'] ?? 'image',
      mediaUrl: ApiConfig.normalizeUrl(json['media_url']),
      audioUrl: json['audio_url'] != null ? ApiConfig.normalizeUrl(json['audio_url']) : null,
      description: json['description'] ?? '',
      company: Company.fromJson(json['company'] ?? {}),
      property: PropertyInfo.fromJson(json['property'] ?? {}),
      agencyId: json['agency_id'],
      likesCount: json['likes_count'] ?? 0,
      commentsCount: json['comments_count'] ?? 0,
      favoritesCount: json['favorites_count'] ?? 0,
      hasLiked: json['has_liked'] ?? false,
      hasFavorited: json['has_favorited'] ?? false,
      hasSubscribed: json['has_subscribed'] ?? false,
    );
  }
}
