import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../config/theme.dart';
import '../providers/app_state.dart';

class InboxSheet extends StatefulWidget {
  const InboxSheet({super.key});

  @override
  State<InboxSheet> createState() => _InboxSheetState();
}

class _InboxSheetState extends State<InboxSheet> {
  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      context.read<AppState>().loadNotifications();
    });
  }

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
            title: Row(mainAxisSize: MainAxisSize.min, children: [
              const Icon(Icons.notifications, size: 18, color: ImmoTokTheme.redPrimary),
              const SizedBox(width: 6),
              const Text('Alertes', style: TextStyle(fontSize: 14, fontWeight: FontWeight.w700, color: Colors.white)),
            ]),
            centerTitle: true,
            actions: [
              if (state.notifications.isNotEmpty)
                TextButton(
                  onPressed: () => state.markAllNotifsRead(),
                  child: const Text('Tout lu', style: TextStyle(fontSize: 12, fontWeight: FontWeight.w700, color: ImmoTokTheme.redPrimary)),
                ),
            ],
          ),
          body: state.notifsLoading
              ? const Center(child: CircularProgressIndicator(color: ImmoTokTheme.redPrimary))
              : state.notifications.isEmpty
                  ? Center(
                      child: Column(mainAxisSize: MainAxisSize.min, children: [
                        Container(
                          width: 80, height: 80,
                          decoration: BoxDecoration(
                            borderRadius: BorderRadius.circular(16),
                            gradient: const LinearGradient(colors: [ImmoTokTheme.redPrimary, ImmoTokTheme.pinkAccent, Color(0xFFFB923C)]),
                          ),
                          padding: const EdgeInsets.all(3),
                          child: Container(
                            decoration: BoxDecoration(color: ImmoTokTheme.bgDark, borderRadius: BorderRadius.circular(14)),
                            child: const Center(child: Icon(Icons.notifications, color: ImmoTokTheme.redPrimary, size: 32)),
                          ),
                        ),
                        const SizedBox(height: 16),
                        RichText(text: const TextSpan(children: [
                          TextSpan(text: 'Immo', style: TextStyle(fontSize: 20, fontWeight: FontWeight.w900, color: Colors.white)),
                          TextSpan(text: 'Tok', style: TextStyle(fontSize: 20, fontWeight: FontWeight.w900, color: ImmoTokTheme.redPrimary)),
                        ])),
                        const SizedBox(height: 8),
                        const Text('Aucune notification pour le moment.', style: TextStyle(color: ImmoTokTheme.gray400, fontSize: 13)),
                        const SizedBox(height: 4),
                        const Text('Suivez des entreprises pour recevoir leurs nouvelles publications.',
                          style: TextStyle(color: ImmoTokTheme.gray600, fontSize: 11)),
                      ]),
                    )
                  : ListView.builder(
                      padding: const EdgeInsets.all(16),
                      itemCount: state.notifications.length,
                      itemBuilder: (context, idx) {
                        final notif = state.notifications[idx];
                        return GestureDetector(
                          onTap: () {
                            state.markNotifRead(notif);
                            Navigator.pop(context);
                            if (notif.illustrationId != null) {
                              state.playFavoriteItem(notif.illustrationId!);
                            }
                          },
                          child: Container(
                            margin: const EdgeInsets.only(bottom: 8),
                            padding: const EdgeInsets.all(12),
                            decoration: BoxDecoration(
                              color: notif.isRead ? Colors.transparent : ImmoTokTheme.redPrimary.withOpacity(0.05),
                              borderRadius: BorderRadius.circular(12),
                              border: notif.isRead ? null : Border.all(color: ImmoTokTheme.redPrimary.withOpacity(0.1)),
                            ),
                            child: Row(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Container(
                                  width: 40, height: 40,
                                  decoration: BoxDecoration(
                                    shape: BoxShape.circle,
                                    gradient: const LinearGradient(colors: [ImmoTokTheme.redPrimary, ImmoTokTheme.pinkAccent]),
                                  ),
                                  child: const Icon(Icons.home, color: Colors.white, size: 18),
                                ),
                                const SizedBox(width: 12),
                                Expanded(child: Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    Text(notif.title, style: const TextStyle(fontSize: 13, fontWeight: FontWeight.w600, color: Colors.white), overflow: TextOverflow.ellipsis),
                                    const SizedBox(height: 2),
                                    Text(notif.message, maxLines: 2, overflow: TextOverflow.ellipsis, style: const TextStyle(fontSize: 12, color: ImmoTokTheme.gray400)),
                                    const SizedBox(height: 4),
                                    Text(notif.createdAt ?? '', style: const TextStyle(fontSize: 10, color: ImmoTokTheme.gray600)),
                                  ],
                                )),
                                if (!notif.isRead)
                                  Container(width: 8, height: 8, margin: const EdgeInsets.only(top: 8),
                                    decoration: const BoxDecoration(shape: BoxShape.circle, color: ImmoTokTheme.redPrimary)),
                              ],
                            ),
                          ),
                        );
                      },
                    ),
        );
      },
    );
  }
}
