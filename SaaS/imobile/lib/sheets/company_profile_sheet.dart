import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:cached_network_image/cached_network_image.dart';
import '../config/theme.dart';
import '../providers/app_state.dart';
import '../models/company.dart';

class CompanyProfileSheet extends StatelessWidget {
  final Company company;
  const CompanyProfileSheet({super.key, required this.company});

  @override
  Widget build(BuildContext context) {
    return Consumer<AppState>(
      builder: (context, state, _) {
        return Scaffold(
          backgroundColor: ImmoTokTheme.bgDark,
          appBar: AppBar(
            backgroundColor: ImmoTokTheme.cardDark,
            elevation: 0,
            leading: IconButton(icon: const Icon(Icons.arrow_back, color: ImmoTokTheme.gray400), onPressed: () => Navigator.pop(context)),
            title: const Text('Profil', style: TextStyle(fontSize: 14, fontWeight: FontWeight.w700, color: Colors.white)),
            centerTitle: true,
            actions: [
              TextButton(
                onPressed: () => state.toggleSubscribe(company.id),
                child: Text(
                  state.profileHasSubscribed ? 'Se désabonner' : "S'abonner",
                  style: const TextStyle(fontSize: 12, fontWeight: FontWeight.w700, color: ImmoTokTheme.redPrimary),
                ),
              ),
            ],
          ),
          body: ListView(
            padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 24),
            children: [
              // Avatar + Name
              Column(
                children: [
                  Stack(
                    clipBehavior: Clip.none,
                    children: [
                      Container(
                        width: 96, height: 96,
                        decoration: BoxDecoration(
                          shape: BoxShape.circle,
                          border: Border.all(color: Colors.white.withOpacity(0.1), width: 4),
                          boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.3), blurRadius: 16)],
                        ),
                        child: ClipOval(
                          child: CachedNetworkImage(imageUrl: state.profileCompany?.logo ?? company.logo, fit: BoxFit.cover,
                            errorWidget: (_, __, ___) => Container(color: ImmoTokTheme.cardDark, child: const Icon(Icons.business, color: Colors.white, size: 40))),
                        ),
                      ),
                      if (!state.profileHasSubscribed)
                        Positioned(
                          bottom: 0, right: 4,
                          child: GestureDetector(
                            onTap: () => state.toggleSubscribe(company.id),
                            child: Container(
                              width: 28, height: 28,
                              decoration: BoxDecoration(
                                color: ImmoTokTheme.redPrimary, shape: BoxShape.circle,
                                border: Border.all(color: ImmoTokTheme.bgDark, width: 2),
                              ),
                              child: const Icon(Icons.add, size: 14, color: Colors.white),
                            ),
                          ),
                        ),
                    ],
                  ),
                  const SizedBox(height: 12),
                  Text('@${state.profileCompany?.name ?? company.name}',
                    style: const TextStyle(fontSize: 20, fontWeight: FontWeight.w900, color: Colors.white)),
                  const SizedBox(height: 4),
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                    decoration: BoxDecoration(color: ImmoTokTheme.blueAccent.withOpacity(0.9), borderRadius: BorderRadius.circular(4)),
                    child: const Text('PRO', style: TextStyle(fontSize: 10, fontWeight: FontWeight.w700, color: Colors.white, letterSpacing: 1)),
                  ),
                  const SizedBox(height: 6),
                  Row(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      const Icon(Icons.location_on, size: 14, color: ImmoTokTheme.blueAccent),
                      const SizedBox(width: 4),
                      Text(state.profileCompany?.city ?? company.city ?? "Côte d'Ivoire",
                        style: const TextStyle(fontSize: 12, color: ImmoTokTheme.gray400)),
                    ],
                  ),
                ],
              ),
              const SizedBox(height: 24),
              // Stats
              Container(
                padding: const EdgeInsets.symmetric(vertical: 16),
                decoration: BoxDecoration(border: Border.symmetric(horizontal: BorderSide(color: Colors.white.withOpacity(0.05)))),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.spaceAround,
                  children: [
                    _StatItem(count: '${state.profileSubscribersCount}', label: 'Abonnés'),
                    _StatItem(count: '${state.profileLikesCount}', label: "J'aime"),
                    _StatItem(count: '${state.profileIllustrations.length}', label: 'Vidéos'),
                  ],
                ),
              ),
              const SizedBox(height: 16),
              // Buttons
              Row(
                children: [
                  Expanded(
                    child: GestureDetector(
                      onTap: () => state.toggleSubscribe(company.id),
                      child: Container(
                        height: 44,
                        decoration: BoxDecoration(
                          color: state.profileHasSubscribed ? Colors.white.withOpacity(0.05) : ImmoTokTheme.redPrimary,
                          borderRadius: BorderRadius.circular(8),
                          border: state.profileHasSubscribed ? Border.all(color: Colors.white.withOpacity(0.1)) : null,
                        ),
                        child: Row(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            Icon(state.profileHasSubscribed ? Icons.check_circle : Icons.person_add,
                              size: 16, color: state.profileHasSubscribed ? ImmoTokTheme.greenOnline : Colors.white),
                            const SizedBox(width: 6),
                            Text(state.profileHasSubscribed ? 'Abonné' : "S'abonner",
                              style: TextStyle(fontSize: 13, fontWeight: FontWeight.w700,
                                color: state.profileHasSubscribed ? ImmoTokTheme.gray300 : Colors.white)),
                          ],
                        ),
                      ),
                    ),
                  ),
                  const SizedBox(width: 12),
                  Expanded(
                    child: GestureDetector(
                      onTap: () {
                        Navigator.pop(context, 'chat');
                      },
                      child: Container(
                        height: 44,
                        decoration: BoxDecoration(
                          color: Colors.white.withOpacity(0.05),
                          borderRadius: BorderRadius.circular(8),
                          border: Border.all(color: Colors.white.withOpacity(0.1)),
                        ),
                        child: const Row(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            Icon(Icons.chat_bubble, size: 16, color: Colors.white),
                            SizedBox(width: 6),
                            Text('Message', style: TextStyle(fontSize: 13, fontWeight: FontWeight.w700, color: Colors.white)),
                          ],
                        ),
                      ),
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 24),
              // Publications grid
              Row(
                children: [
                  const Icon(Icons.grid_view, size: 16, color: ImmoTokTheme.redPrimary),
                  const SizedBox(width: 8),
                  Text('PUBLICATIONS', style: TextStyle(fontSize: 12, fontWeight: FontWeight.w900, color: ImmoTokTheme.gray400, letterSpacing: 1)),
                ],
              ),
              const SizedBox(height: 12),
              if (state.profileIllustrations.isEmpty)
                const Padding(
                  padding: EdgeInsets.symmetric(vertical: 40),
                  child: Center(child: Text("Cette entreprise n'a pas encore publié d'illustrations.",
                    style: TextStyle(color: ImmoTokTheme.gray500, fontSize: 13))),
                )
              else
                GridView.builder(
                  shrinkWrap: true,
                  physics: const NeverScrollableScrollPhysics(),
                  gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                    crossAxisCount: 3, childAspectRatio: 0.75, crossAxisSpacing: 6, mainAxisSpacing: 6),
                  itemCount: state.profileIllustrations.length,
                  itemBuilder: (context, idx) {
                    final img = state.profileIllustrations[idx];
                    return GestureDetector(
                      onTap: () {
                        state.playProfileIllustration(img.id);
                        Navigator.pop(context);
                      },
                      child: Stack(
                        fit: StackFit.expand,
                        children: [
                          ClipRRect(
                            borderRadius: BorderRadius.circular(6),
                            child: CachedNetworkImage(imageUrl: img.mediaUrl, fit: BoxFit.cover,
                              errorWidget: (_, __, ___) => Container(color: Colors.black)),
                          ),
                          if (img.mediaType == 'video')
                            Positioned(
                              bottom: 4, right: 4,
                              child: Container(
                                padding: const EdgeInsets.symmetric(horizontal: 4, vertical: 2),
                                decoration: BoxDecoration(color: Colors.black.withOpacity(0.4), borderRadius: BorderRadius.circular(4)),
                                child: const Icon(Icons.play_arrow, size: 10, color: Colors.white),
                              ),
                            ),
                        ],
                      ),
                    );
                  },
                ),
            ],
          ),
        );
      },
    );
  }
}

class _StatItem extends StatelessWidget {
  final String count;
  final String label;
  const _StatItem({required this.count, required this.label});

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Text(count, style: const TextStyle(fontSize: 18, fontWeight: FontWeight.w900, color: Colors.white)),
        Text(label, style: TextStyle(fontSize: 10, fontWeight: FontWeight.w700, color: ImmoTokTheme.gray400, letterSpacing: 1)),
      ],
    );
  }
}
