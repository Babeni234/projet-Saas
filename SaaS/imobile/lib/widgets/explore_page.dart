import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:cached_network_image/cached_network_image.dart';
import '../config/theme.dart';
import '../providers/app_state.dart';

class ExplorePage extends StatelessWidget {
  const ExplorePage({super.key});

  @override
  Widget build(BuildContext context) {
    return Consumer<AppState>(
      builder: (context, state, _) {
        return Container(
          color: ImmoTokTheme.bgDark,
          child: SafeArea(
            child: Padding(
              padding: const EdgeInsets.only(top: 60),
              child: ListView(
                padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
                children: [
                  // Search Bar
                  Row(
                    children: [
                      Expanded(
                        child: Container(
                          padding: const EdgeInsets.symmetric(horizontal: 12),
                          decoration: BoxDecoration(
                            color: ImmoTokTheme.cardDark,
                            borderRadius: BorderRadius.circular(24),
                            border: Border.all(color: Colors.white.withOpacity(0.1)),
                          ),
                          child: Row(
                            children: [
                              const Icon(Icons.search, color: ImmoTokTheme.gray400, size: 18),
                              const SizedBox(width: 8),
                              Expanded(
                                child: TextField(
                                  style: const TextStyle(color: Colors.white, fontSize: 13),
                                  decoration: const InputDecoration(
                                    hintText: 'Rechercher des biens, quartiers, villes...',
                                    hintStyle: TextStyle(color: ImmoTokTheme.gray500, fontSize: 13),
                                    border: InputBorder.none,
                                    contentPadding: EdgeInsets.symmetric(vertical: 12),
                                  ),
                                  onChanged: (val) => state.searchQuery = val,
                                  onSubmitted: (_) => state.handleExploreSearch(),
                                ),
                              ),
                              if (state.searchQuery.isNotEmpty)
                                GestureDetector(
                                  onTap: () {
                                    state.searchQuery = '';
                                    state.handleExploreSearch();
                                  },
                                  child: const Icon(Icons.cancel, color: ImmoTokTheme.gray400, size: 18),
                                ),
                            ],
                          ),
                        ),
                      ),
                      const SizedBox(width: 8),
                      GestureDetector(
                        onTap: () => state.handleExploreSearch(),
                        child: Container(
                          padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 10),
                          decoration: BoxDecoration(
                            color: ImmoTokTheme.redPrimary,
                            borderRadius: BorderRadius.circular(20),
                          ),
                          child: const Text('Rechercher', style: TextStyle(fontSize: 12, fontWeight: FontWeight.w700, color: Colors.white)),
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 12),

                  // Trending tags
                  Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        'RECHERCHES POPULAIRES',
                        style: TextStyle(fontSize: 10, fontWeight: FontWeight.w700, color: ImmoTokTheme.gray500, letterSpacing: 1.5),
                      ),
                      const SizedBox(height: 8),
                      SingleChildScrollView(
                        scrollDirection: Axis.horizontal,
                        child: Row(
                          children: ['Cocody', 'Studio', 'Appartement', 'Loyer < 500k', 'Plateau'].map((tag) {
                            return Padding(
                              padding: const EdgeInsets.only(right: 8),
                              child: GestureDetector(
                                onTap: () => state.selectTrendingTag(tag),
                                child: Container(
                                  padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                                  decoration: BoxDecoration(
                                    color: ImmoTokTheme.cardDark,
                                    borderRadius: BorderRadius.circular(20),
                                    border: Border.all(color: Colors.white.withOpacity(0.05)),
                                  ),
                                  child: Text(
                                    '🔥 $tag',
                                    style: const TextStyle(fontSize: 11, fontWeight: FontWeight.w600, color: ImmoTokTheme.gray300),
                                  ),
                                ),
                              ),
                            );
                          }).toList(),
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 16),

                  // Results
                  if (state.exploreLoading)
                    const Padding(
                      padding: EdgeInsets.only(top: 80),
                      child: Center(child: CircularProgressIndicator(color: ImmoTokTheme.redPrimary)),
                    )
                  else if (state.exploreResults.isEmpty)
                    Padding(
                      padding: const EdgeInsets.only(top: 80),
                      child: Center(
                        child: Column(
                          children: [
                            Icon(Icons.search_off, size: 40, color: ImmoTokTheme.gray500),
                            const SizedBox(height: 8),
                            Text('Aucun bien correspondant.', style: TextStyle(color: ImmoTokTheme.gray500, fontSize: 13)),
                          ],
                        ),
                      ),
                    )
                  else
                    GridView.builder(
                      shrinkWrap: true,
                      physics: const NeverScrollableScrollPhysics(),
                      gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                        crossAxisCount: 2,
                        childAspectRatio: 0.65,
                        crossAxisSpacing: 8,
                        mainAxisSpacing: 8,
                      ),
                      itemCount: state.exploreResults.length,
                      itemBuilder: (context, idx) {
                        final item = state.exploreResults[idx];
                        return GestureDetector(
                          onTap: () => state.playExploreItem(idx),
                          child: Container(
                            decoration: BoxDecoration(
                              color: ImmoTokTheme.cardDark,
                              borderRadius: BorderRadius.circular(12),
                              border: Border.all(color: Colors.white.withOpacity(0.05)),
                            ),
                            clipBehavior: Clip.antiAlias,
                            child: Column(
                              children: [
                                // Thumbnail
                                Expanded(
                                  flex: 4,
                                  child: Stack(
                                    fit: StackFit.expand,
                                    children: [
                                      CachedNetworkImage(
                                        imageUrl: item.mediaUrl,
                                        fit: BoxFit.cover,
                                        errorWidget: (_, __, ___) => Container(
                                          color: Colors.black,
                                          child: const Icon(Icons.image, color: Colors.white24, size: 32),
                                        ),
                                      ),
                                      // Gradient overlay
                                      Positioned.fill(
                                        child: DecoratedBox(
                                          decoration: BoxDecoration(
                                            gradient: LinearGradient(
                                              begin: Alignment.topCenter,
                                              end: Alignment.bottomCenter,
                                              colors: [Colors.transparent, Colors.black.withOpacity(0.8)],
                                            ),
                                          ),
                                        ),
                                      ),
                                      // Price badge
                                      Positioned(
                                        bottom: 6,
                                        left: 6,
                                        child: Container(
                                          padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
                                          decoration: BoxDecoration(
                                            color: ImmoTokTheme.redPrimary.withOpacity(0.9),
                                            borderRadius: BorderRadius.circular(4),
                                          ),
                                          child: Text(
                                            item.property.priceLabel.split(' ').first + ' F',
                                            style: const TextStyle(fontSize: 10, fontWeight: FontWeight.w700, color: Colors.white),
                                          ),
                                        ),
                                      ),
                                      // Video play icon
                                      if (item.mediaType == 'video')
                                        Positioned(
                                          top: 6,
                                          right: 6,
                                          child: Container(
                                            width: 22,
                                            height: 22,
                                            decoration: BoxDecoration(
                                              color: Colors.black.withOpacity(0.4),
                                              shape: BoxShape.circle,
                                            ),
                                            child: const Icon(Icons.play_arrow, size: 12, color: Colors.white),
                                          ),
                                        ),
                                    ],
                                  ),
                                ),
                                // Card info
                                Expanded(
                                  flex: 2,
                                  child: Padding(
                                    padding: const EdgeInsets.all(8),
                                    child: Column(
                                      crossAxisAlignment: CrossAxisAlignment.start,
                                      children: [
                                        Text(
                                          item.description,
                                          maxLines: 2,
                                          overflow: TextOverflow.ellipsis,
                                          style: const TextStyle(fontSize: 11, color: ImmoTokTheme.gray200, fontWeight: FontWeight.w500, height: 1.3),
                                        ),
                                        const Spacer(),
                                        Container(
                                          padding: const EdgeInsets.only(top: 6),
                                          decoration: BoxDecoration(
                                            border: Border(top: BorderSide(color: Colors.white.withOpacity(0.05))),
                                          ),
                                          child: Row(
                                            children: [
                                              ClipOval(
                                                child: CachedNetworkImage(
                                                  imageUrl: item.company.logo,
                                                  width: 14,
                                                  height: 14,
                                                  fit: BoxFit.cover,
                                                  errorWidget: (_, __, ___) => Container(
                                                    width: 14, height: 14, color: ImmoTokTheme.gray600,
                                                  ),
                                                ),
                                              ),
                                              const SizedBox(width: 4),
                                              Expanded(
                                                child: Text(
                                                  '@${item.company.name.split(' ').first}',
                                                  style: const TextStyle(fontSize: 9, fontWeight: FontWeight.w700, color: ImmoTokTheme.gray400),
                                                  overflow: TextOverflow.ellipsis,
                                                ),
                                              ),
                                              Icon(Icons.favorite, size: 10, color: ImmoTokTheme.redPrimary),
                                              const SizedBox(width: 2),
                                              Text('${item.likesCount}', style: const TextStyle(fontSize: 9, fontWeight: FontWeight.w700, color: ImmoTokTheme.gray400)),
                                            ],
                                          ),
                                        ),
                                      ],
                                    ),
                                  ),
                                ),
                              ],
                            ),
                          ),
                        );
                      },
                    ),
                  const SizedBox(height: 80),
                ],
              ),
            ),
          ),
        );
      },
    );
  }
}
