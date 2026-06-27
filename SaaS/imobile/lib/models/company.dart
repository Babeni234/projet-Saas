import '../config/api_config.dart';

class Company {
  final int id;
  final String name;
  final String logo;
  final String? phone;
  final String? city;
  final String? businessType;

  Company({
    required this.id,
    required this.name,
    required this.logo,
    this.phone,
    this.city,
    this.businessType,
  });

  factory Company.fromJson(Map<String, dynamic> json) {
    return Company(
      id: json['id'] ?? 0,
      name: json['name'] ?? '',
      logo: ApiConfig.normalizeUrl(json['logo']),
      phone: json['phone'],
      city: json['city'],
      businessType: json['business_type'],
    );
  }
}
