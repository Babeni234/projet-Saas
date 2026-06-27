import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:cached_network_image/cached_network_image.dart';
import '../config/theme.dart';
import '../providers/app_state.dart';
import '../models/company.dart';
import '../config/api_config.dart';

class MyProfileSheet extends StatefulWidget {
  const MyProfileSheet({super.key});

  @override
  State<MyProfileSheet> createState() => _MyProfileSheetState();
}

class _MyProfileSheetState extends State<MyProfileSheet> {
  String _tab = 'subs';

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      context.read<AppState>().loadMyProfile();
    });
  }

  @override
  Widget build(BuildContext context) {
    return Consumer<AppState>(
      builder: (context, state, _) {
        final client = state.client;
        final data = state.myProfileData;
        final stats = data['stats'] != null ? Map<String, dynamic>.from(data['stats'] as Map) : <String, dynamic>{};
        final subs = data['subscriptions'] as List? ?? [];
        final favs = data['favorites'] as List? ?? [];

        return Scaffold(
          backgroundColor: ImmoTokTheme.bgDark,
          appBar: AppBar(
            backgroundColor: ImmoTokTheme.cardDark,
            elevation: 0,
            leading: IconButton(icon: const Icon(Icons.arrow_back, color: ImmoTokTheme.gray400), onPressed: () => Navigator.pop(context)),
            title: RichText(text: const TextSpan(children: [
              TextSpan(text: 'Immo', style: TextStyle(fontSize: 14, fontWeight: FontWeight.w900, color: Colors.white)),
              TextSpan(text: 'Tok', style: TextStyle(fontSize: 14, fontWeight: FontWeight.w900, color: ImmoTokTheme.redPrimary)),
            ])),
            centerTitle: true,
            actions: [
              TextButton(
                onPressed: () async {
                  await state.logout();
                  if (context.mounted) Navigator.pop(context);
                },
                child: const Text('Déconnexion', style: TextStyle(fontSize: 12, fontWeight: FontWeight.w700, color: ImmoTokTheme.redPrimary)),
              ),
            ],
          ),
          body: Column(
            children: [
              // Profile card
              Padding(
                padding: const EdgeInsets.all(24),
                child: Column(
                  children: [
                    Container(
                      width: 96, height: 96,
                      decoration: BoxDecoration(
                        shape: BoxShape.circle,
                        gradient: const LinearGradient(colors: [ImmoTokTheme.redPrimary, ImmoTokTheme.pinkAccent, Color(0xFFFB923C)]),
                      ),
                      padding: const EdgeInsets.all(3),
                      child: Container(
                        decoration: const BoxDecoration(shape: BoxShape.circle, color: ImmoTokTheme.bgDark),
                        child: Center(
                          child: Text(
                            client != null ? client.name[0].toUpperCase() : '?',
                            style: const TextStyle(fontSize: 36, fontWeight: FontWeight.w900, color: Colors.white),
                          ),
                        ),
                      ),
                    ),
                    const SizedBox(height: 12),
                    Text(client?.name ?? 'Utilisateur', style: const TextStyle(fontSize: 20, fontWeight: FontWeight.w900, color: Colors.white)),
                    const SizedBox(height: 4),
                    Row(mainAxisSize: MainAxisSize.min, children: [
                      const Icon(Icons.email, size: 12, color: ImmoTokTheme.redPrimary),
                      const SizedBox(width: 4),
                      Text(client?.email ?? '', style: const TextStyle(fontSize: 12, color: ImmoTokTheme.gray400)),
                    ]),
                    if (client?.phone != null) ...[
                      const SizedBox(height: 2),
                      Row(mainAxisSize: MainAxisSize.min, children: [
                        const Icon(Icons.phone, size: 12, color: ImmoTokTheme.greenOnline),
                        const SizedBox(width: 4),
                        Text(client!.phone!, style: const TextStyle(fontSize: 12, color: ImmoTokTheme.gray400)),
                      ]),
                    ],
                  ],
                ),
              ),
              // Stats
              Container(
                margin: const EdgeInsets.symmetric(horizontal: 24),
                padding: const EdgeInsets.symmetric(vertical: 16),
                decoration: BoxDecoration(border: Border.symmetric(horizontal: BorderSide(color: Colors.white.withOpacity(0.05)))),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.spaceAround,
                  children: [
                    _StatItem(count: '${stats['subscriptions_count'] ?? 0}', label: 'Abonnements'),
                    _StatItem(count: '${stats['favorites_count'] ?? 0}', label: 'Favoris'),
                    _StatItem(count: '${stats['likes_count'] ?? 0}', label: "J'aime"),
                  ],
                ),
              ),
              // Tabs
              Row(
                children: [
                  _TabBtn(label: 'Abonnements', icon: Icons.business, isActive: _tab == 'subs', onTap: () => setState(() => _tab = 'subs')),
                  _TabBtn(label: 'Favoris', icon: Icons.bookmark, isActive: _tab == 'favs', onTap: () => setState(() => _tab = 'favs')),
                  _TabBtn(label: "J'aime", icon: Icons.favorite, isActive: _tab == 'likes', onTap: () => setState(() => _tab = 'likes')),
                ],
              ),
              // Content
              Expanded(
                child: state.myProfileLoading
                    ? const Center(child: CircularProgressIndicator(color: ImmoTokTheme.redPrimary))
                    : _tab == 'subs'
                        ? _buildSubsList(subs, state)
                        : _tab == 'favs'
                            ? _buildFavsList(favs, state)
                            : _buildLikesList(data['likes'] as List? ?? [], state),
              ),
            ],
          ),
        );
      },
    );
  }

  Widget _buildSubsList(List subs, AppState state) {
    if (subs.isEmpty) {
      return Center(
        child: Column(mainAxisSize: MainAxisSize.min, children: [
          Icon(Icons.people, size: 40, color: ImmoTokTheme.gray500),
          const SizedBox(height: 8),
          const Text("Vous n'êtes abonné à aucune entreprise.", style: TextStyle(color: ImmoTokTheme.gray500, fontSize: 13)),
          const SizedBox(height: 12),
          GestureDetector(
            onTap: () { Navigator.pop(context); state.setActiveTab('explore'); },
            child: Container(
              padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 10),
              decoration: BoxDecoration(color: ImmoTokTheme.redPrimary, borderRadius: BorderRadius.circular(20)),
              child: const Text('Explorer', style: TextStyle(fontSize: 13, fontWeight: FontWeight.w700, color: Colors.white)),
            ),
          ),
        ]),
      );
    }
    return ListView.builder(
      padding: const EdgeInsets.all(16),
      itemCount: subs.length,
      itemBuilder: (context, idx) {
        final sub = subs[idx] as Map<String, dynamic>;
        return GestureDetector(
          onTap: () {
            Navigator.pop(context);
            state.openProfile(
              _companyFromMap(sub),
            );
          },
          child: Container(
            margin: const EdgeInsets.only(bottom: 8),
            padding: const EdgeInsets.all(12),
            decoration: BoxDecoration(
              color: ImmoTokTheme.cardDark,
              borderRadius: BorderRadius.circular(12),
              border: Border.all(color: Colors.white.withOpacity(0.05)),
            ),
            child: Row(
              children: [
                ClipOval(child: CachedNetworkImage(imageUrl: ApiConfig.normalizeUrl(sub['logo']), width: 48, height: 48, fit: BoxFit.cover,
                  errorWidget: (_, __, ___) => Container(width: 48, height: 48, color: ImmoTokTheme.gray600))),
                const SizedBox(width: 12),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(sub['name'] ?? '', style: const TextStyle(fontSize: 13, fontWeight: FontWeight.w700, color: Colors.white)),
                      const SizedBox(height: 2),
                      Row(children: [
                        const Icon(Icons.label, size: 10, color: ImmoTokTheme.redPrimary),
                        const SizedBox(width: 4),
                        Text(sub['business_type'] ?? '', style: const TextStyle(fontSize: 10, color: ImmoTokTheme.gray400)),
                        if (sub['city'] != null) ...[
                          const Text(' · ', style: TextStyle(fontSize: 10, color: ImmoTokTheme.gray400)),
                          const Icon(Icons.location_on, size: 10, color: ImmoTokTheme.blueAccent),
                          const SizedBox(width: 2),
                          Text(sub['city'], style: const TextStyle(fontSize: 10, color: ImmoTokTheme.gray400)),
                        ],
                      ]),
                    ],
                  ),
                ),
                Column(crossAxisAlignment: CrossAxisAlignment.end, children: [
                  Text(sub['subscribed_at'] ?? '', style: const TextStyle(fontSize: 9, color: ImmoTokTheme.gray500)),
                  const SizedBox(height: 4),
                  const Icon(Icons.chevron_right, size: 14, color: ImmoTokTheme.gray600),
                ]),
              ],
            ),
          ),
        );
      },
    );
  }

  Widget _buildFavsList(List favs, AppState state) {
    if (favs.isEmpty) {
      return Center(
        child: Column(mainAxisSize: MainAxisSize.min, children: [
          Icon(Icons.bookmark, size: 40, color: ImmoTokTheme.gray500),
          const SizedBox(height: 8),
          const Text("Vous n'avez aucun favori.", style: TextStyle(color: ImmoTokTheme.gray500, fontSize: 13)),
          const SizedBox(height: 4),
          Row(mainAxisSize: MainAxisSize.min, children: [
            const Text('Appuyez sur ', style: TextStyle(fontSize: 11, color: ImmoTokTheme.gray600)),
            Icon(Icons.bookmark, size: 14, color: ImmoTokTheme.yellowFav),
            const Text(' pour sauvegarder.', style: TextStyle(fontSize: 11, color: ImmoTokTheme.gray600)),
          ]),
        ]),
      );
    }
    return GridView.builder(
      padding: const EdgeInsets.all(16),
      gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(crossAxisCount: 3, childAspectRatio: 0.75, crossAxisSpacing: 6, mainAxisSpacing: 6),
      itemCount: favs.length,
      itemBuilder: (context, idx) {
        final fav = favs[idx] as Map<String, dynamic>;
        return GestureDetector(
          onTap: () {
            Navigator.pop(context);
            state.playFavoriteItem(fav['id'] ?? 0);
          },
          child: Stack(
            fit: StackFit.expand,
            children: [
              ClipRRect(
                borderRadius: BorderRadius.circular(6),
                child: CachedNetworkImage(imageUrl: ApiConfig.normalizeUrl(fav['media_url']), fit: BoxFit.cover,
                  errorWidget: (_, __, ___) => Container(color: Colors.black)),
              ),
              Positioned.fill(child: DecoratedBox(decoration: BoxDecoration(
                gradient: LinearGradient(begin: Alignment.topCenter, end: Alignment.bottomCenter,
                  colors: [Colors.transparent, Colors.black.withOpacity(0.7)])))),
              if (fav['media_type'] == 'video')
                Positioned(top: 4, right: 4, child: Container(
                  padding: const EdgeInsets.symmetric(horizontal: 4, vertical: 2),
                  decoration: BoxDecoration(color: Colors.black.withOpacity(0.4), borderRadius: BorderRadius.circular(4)),
                  child: const Icon(Icons.play_arrow, size: 10, color: Colors.white))),
              Positioned(bottom: 6, left: 6, right: 6, child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(children: [
                    ClipOval(child: CachedNetworkImage(imageUrl: ApiConfig.normalizeUrl(fav['company_logo']), width: 14, height: 14, fit: BoxFit.cover,
                      errorWidget: (_, __, ___) => Container(width: 14, height: 14, color: ImmoTokTheme.gray600))),
                    const SizedBox(width: 4),
                    Expanded(child: Text(fav['company_name'] ?? '', style: const TextStyle(fontSize: 8, fontWeight: FontWeight.w700, color: ImmoTokTheme.gray300), overflow: TextOverflow.ellipsis)),
                  ]),
                  const SizedBox(height: 2),
                  Row(children: [
                    const Icon(Icons.favorite, size: 10, color: ImmoTokTheme.redPrimary),
                    const SizedBox(width: 2),
                    Text('${fav['likes_count'] ?? 0}', style: const TextStyle(fontSize: 9, color: ImmoTokTheme.gray300)),
                  ]),
                ],
              )),
            ],
          ),
        );
      },
    );
  }

  Widget _buildLikesList(List likes, AppState state) {
    if (likes.isEmpty) {
      return Center(
        child: Column(mainAxisSize: MainAxisSize.min, children: [
          Icon(Icons.favorite, size: 40, color: ImmoTokTheme.gray500),
          const SizedBox(height: 8),
          const Text("Vous n'avez aimé aucun bien.", style: TextStyle(color: ImmoTokTheme.gray500, fontSize: 13)),
        ]),
      );
    }
    return GridView.builder(
      padding: const EdgeInsets.all(16),
      gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(crossAxisCount: 3, childAspectRatio: 0.75, crossAxisSpacing: 6, mainAxisSpacing: 6),
      itemCount: likes.length,
      itemBuilder: (context, idx) {
        final item = likes[idx] as Map<String, dynamic>;
        return GestureDetector(
          onTap: () {
            Navigator.pop(context);
            state.playFavoriteItem(item['id'] ?? 0);
          },
          child: Stack(
            fit: StackFit.expand,
            children: [
              ClipRRect(
                borderRadius: BorderRadius.circular(6),
                child: CachedNetworkImage(imageUrl: ApiConfig.normalizeUrl(item['media_url']), fit: BoxFit.cover,
                  errorWidget: (_, __, ___) => Container(color: Colors.black)),
              ),
              Positioned.fill(child: DecoratedBox(decoration: BoxDecoration(
                gradient: LinearGradient(begin: Alignment.topCenter, end: Alignment.bottomCenter,
                  colors: [Colors.transparent, Colors.black.withOpacity(0.7)])))),
              if (item['media_type'] == 'video')
                Positioned(top: 4, right: 4, child: Container(
                  padding: const EdgeInsets.symmetric(horizontal: 4, vertical: 2),
                  decoration: BoxDecoration(color: Colors.black.withOpacity(0.4), borderRadius: BorderRadius.circular(4)),
                  child: const Icon(Icons.play_arrow, size: 10, color: Colors.white))),
              Positioned(bottom: 6, left: 6, right: 6, child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(children: [
                    ClipOval(child: CachedNetworkImage(imageUrl: ApiConfig.normalizeUrl(item['company_logo']), width: 14, height: 14, fit: BoxFit.cover,
                      errorWidget: (_, __, ___) => Container(width: 14, height: 14, color: ImmoTokTheme.gray600))),
                    const SizedBox(width: 4),
                    Expanded(child: Text(item['company_name'] ?? '', style: const TextStyle(fontSize: 8, fontWeight: FontWeight.w700, color: ImmoTokTheme.gray300), overflow: TextOverflow.ellipsis)),
                  ]),
                  const SizedBox(height: 2),
                  Row(children: [
                    const Icon(Icons.favorite, size: 10, color: ImmoTokTheme.redPrimary),
                    const SizedBox(width: 2),
                    Text('${item['likes_count'] ?? 0}', style: const TextStyle(fontSize: 9, color: ImmoTokTheme.gray300)),
                  ]),
                ],
              )),
            ],
          ),
        );
      },
    );
  }
}

Company _companyFromMap(Map<String, dynamic> m) {
  return Company(id: m['id'] ?? 0, name: m['name'] ?? '', logo: m['logo'] ?? '', city: m['city'], businessType: m['business_type']);
}

class _StatItem extends StatelessWidget {
  final String count;
  final String label;
  const _StatItem({required this.count, required this.label});

  @override
  Widget build(BuildContext context) {
    return Column(children: [
      Text(count, style: const TextStyle(fontSize: 18, fontWeight: FontWeight.w900, color: Colors.white)),
      Text(label, style: TextStyle(fontSize: 10, fontWeight: FontWeight.w700, color: ImmoTokTheme.gray400, letterSpacing: 1)),
    ]);
  }
}

class _TabBtn extends StatelessWidget {
  final String label;
  final IconData icon;
  final bool isActive;
  final VoidCallback onTap;
  const _TabBtn({required this.label, required this.icon, required this.isActive, required this.onTap});

  @override
  Widget build(BuildContext context) {
    return Expanded(
      child: GestureDetector(
        onTap: onTap,
        child: Container(
          padding: const EdgeInsets.symmetric(vertical: 12),
          decoration: BoxDecoration(
            border: Border(bottom: BorderSide(color: isActive ? ImmoTokTheme.redPrimary : Colors.transparent, width: 2)),
          ),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Icon(icon, size: 16, color: isActive ? Colors.white : ImmoTokTheme.gray400),
              const SizedBox(width: 6),
              Text(label, style: TextStyle(fontSize: 13, fontWeight: FontWeight.w700, color: isActive ? Colors.white : ImmoTokTheme.gray400)),
            ],
          ),
        ),
      ),
    );
  }
}
