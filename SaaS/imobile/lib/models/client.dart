class Client {
  final int id;
  final String name;
  final String email;
  final String? phone;

  Client({
    required this.id,
    required this.name,
    required this.email,
    this.phone,
  });

  factory Client.fromJson(Map<String, dynamic> json) {
    return Client(
      id: json['id'] ?? 0,
      name: json['name'] ?? '',
      email: json['email'] ?? '',
      phone: json['phone'],
    );
  }
}
