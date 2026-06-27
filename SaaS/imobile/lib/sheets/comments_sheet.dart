import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../config/theme.dart';
import '../providers/app_state.dart';
import '../models/comment.dart';

class CommentsSheet extends StatefulWidget {
  const CommentsSheet({super.key});

  @override
  State<CommentsSheet> createState() => _CommentsSheetState();
}

class _CommentsSheetState extends State<CommentsSheet> {
  final _textController = TextEditingController();
  final _emojis = ['😍', '🔥', '💎', '👏', '🏡', '📍', '❓'];

  @override
  void dispose() {
    _textController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Consumer<AppState>(
      builder: (context, state, _) {
        return Container(
          height: MediaQuery.of(context).size.height * 0.7,
          decoration: const BoxDecoration(
            color: ImmoTokTheme.cardDark,
            borderRadius: BorderRadius.vertical(top: Radius.circular(16)),
          ),
          child: Column(
            children: [
              // Handle
              Container(
                width: 48, height: 5,
                margin: const EdgeInsets.symmetric(vertical: 12),
                decoration: BoxDecoration(
                  color: Colors.white.withOpacity(0.1),
                  borderRadius: BorderRadius.circular(3),
                ),
              ),
              // Header
              Padding(
                padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 4),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Row(
                      children: [
                        const Text('Commentaires', style: TextStyle(fontWeight: FontWeight.w700, fontSize: 15, color: Colors.white)),
                        const SizedBox(width: 8),
                        Container(
                          padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                          decoration: BoxDecoration(
                            color: Colors.white.withOpacity(0.1),
                            borderRadius: BorderRadius.circular(12),
                          ),
                          child: Text('${state.comments.length}', style: const TextStyle(fontSize: 12, color: ImmoTokTheme.gray300)),
                        ),
                      ],
                    ),
                    GestureDetector(
                      onTap: () => Navigator.pop(context),
                      child: const Icon(Icons.close, color: ImmoTokTheme.gray400, size: 22),
                    ),
                  ],
                ),
              ),
              const Divider(color: Colors.white10, height: 1),
              // Emoji bar
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                decoration: BoxDecoration(color: Colors.black.withOpacity(0.1)),
                child: Row(
                  children: _emojis.map((emoji) => Padding(
                    padding: const EdgeInsets.only(right: 12),
                    child: GestureDetector(
                      onTap: () => _textController.text += emoji,
                      child: Text(emoji, style: const TextStyle(fontSize: 20)),
                    ),
                  )).toList(),
                ),
              ),
              const Divider(color: Colors.white10, height: 1),
              // Comments list
              Expanded(
                child: state.comments.isEmpty
                    ? Center(
                        child: Column(
                          mainAxisSize: MainAxisSize.min,
                          children: [
                            Icon(Icons.chat_bubble_outline, size: 40, color: ImmoTokTheme.gray500),
                            const SizedBox(height: 8),
                            Text('Aucun commentaire. Soyez le premier !',
                              style: TextStyle(color: ImmoTokTheme.gray500, fontSize: 13),
                            ),
                          ],
                        ),
                      )
                    : ListView.builder(
                        padding: const EdgeInsets.all(12),
                        itemCount: state.comments.length,
                        itemBuilder: (context, idx) => _buildComment(state.comments[idx], state),
                      ),
              ),
              // Reply target hint
              if (state.replyTarget != null)
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 6),
                  decoration: BoxDecoration(
                    color: Colors.black.withOpacity(0.2),
                    border: Border(top: BorderSide(color: Colors.white.withOpacity(0.05))),
                  ),
                  child: Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Text(
                        'En réponse à @${state.replyTarget!.name}',
                        style: const TextStyle(fontSize: 12, color: ImmoTokTheme.gray400),
                      ),
                      GestureDetector(
                        onTap: () => state.setReplyTarget(null),
                        child: const Icon(Icons.close, color: ImmoTokTheme.redPrimary, size: 16),
                      ),
                    ],
                  ),
                ),
              // Composer
              Container(
                padding: const EdgeInsets.all(12),
                decoration: BoxDecoration(
                  color: ImmoTokTheme.cardDarkAlt,
                  border: Border(top: BorderSide(color: Colors.white.withOpacity(0.05))),
                ),
                child: Row(
                  children: [
                    Container(
                      width: 32, height: 32,
                      decoration: const BoxDecoration(shape: BoxShape.circle, color: ImmoTokTheme.redPrimary),
                      child: Center(
                        child: Text(
                          state.client != null ? state.client!.name[0].toUpperCase() : 'M',
                          style: const TextStyle(fontWeight: FontWeight.w700, color: Colors.white),
                        ),
                      ),
                    ),
                    const SizedBox(width: 8),
                    Expanded(
                      child: Container(
                        padding: const EdgeInsets.symmetric(horizontal: 12),
                        decoration: BoxDecoration(
                          color: Colors.black.withOpacity(0.2),
                          borderRadius: BorderRadius.circular(20),
                          border: Border.all(color: Colors.white.withOpacity(0.1)),
                        ),
                        child: TextField(
                          controller: _textController,
                          style: const TextStyle(color: Colors.white, fontSize: 13),
                          decoration: const InputDecoration(
                            hintText: 'Ajouter un commentaire...',
                            hintStyle: TextStyle(color: ImmoTokTheme.gray500),
                            border: InputBorder.none,
                            contentPadding: EdgeInsets.symmetric(vertical: 10),
                          ),
                          onSubmitted: (_) => _send(state),
                        ),
                      ),
                    ),
                    const SizedBox(width: 8),
                    GestureDetector(
                      onTap: () => _send(state),
                      child: const Icon(Icons.send, color: ImmoTokTheme.redPrimary, size: 20),
                    ),
                  ],
                ),
              ),
            ],
          ),
        );
      },
    );
  }

  Widget _buildComment(Comment c, AppState state) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Row(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            ClipOval(
              child: Image.network(c.avatar, width: 32, height: 32, fit: BoxFit.cover,
                errorBuilder: (_, __, ___) => Container(width: 32, height: 32, color: ImmoTokTheme.gray600)),
            ),
            const SizedBox(width: 10),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Text(c.name, style: const TextStyle(fontSize: 12, fontWeight: FontWeight.w700, color: ImmoTokTheme.gray300)),
                      Text(c.createdAt ?? '', style: const TextStyle(fontSize: 10, color: ImmoTokTheme.gray500)),
                    ],
                  ),
                  const SizedBox(height: 4),
                  Text(c.text, style: const TextStyle(fontSize: 13, color: Colors.white, height: 1.4)),
                  const SizedBox(height: 4),
                  GestureDetector(
                    onTap: () => state.setReplyTarget(c),
                    child: const Row(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        Icon(Icons.reply, size: 12, color: ImmoTokTheme.gray400),
                        SizedBox(width: 4),
                        Text('Répondre', style: TextStyle(fontSize: 11, fontWeight: FontWeight.w600, color: ImmoTokTheme.gray400)),
                      ],
                    ),
                  ),
                ],
              ),
            ),
          ],
        ),
        // Replies
        if (c.replies.isNotEmpty)
          Padding(
            padding: const EdgeInsets.only(left: 42, top: 8),
            child: Column(
              children: c.replies.map((reply) => Padding(
                padding: const EdgeInsets.only(bottom: 8),
                child: Row(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    ClipOval(
                      child: Image.network(reply.avatar, width: 24, height: 24, fit: BoxFit.cover,
                        errorBuilder: (_, __, ___) => Container(width: 24, height: 24, color: ImmoTokTheme.gray600)),
                    ),
                    const SizedBox(width: 8),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              Text(reply.name, style: const TextStyle(fontSize: 11, fontWeight: FontWeight.w700, color: ImmoTokTheme.gray300)),
                              Text(reply.createdAt ?? '', style: const TextStyle(fontSize: 9, color: ImmoTokTheme.gray500)),
                            ],
                          ),
                          const SizedBox(height: 2),
                          Text(reply.text, style: const TextStyle(fontSize: 12, color: ImmoTokTheme.gray200, height: 1.3)),
                        ],
                      ),
                    ),
                  ],
                ),
              )).toList(),
            ),
          ),
        const SizedBox(height: 16),
      ],
    );
  }

  void _send(AppState state) async {
    final text = _textController.text.trim();
    if (text.isEmpty) return;
    if (state.currentItem == null) return;
    if (!state.isAuthenticated) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Connectez-vous pour commenter'), backgroundColor: ImmoTokTheme.redPrimary),
      );
      return;
    }
    final success = await state.sendComment(state.currentItem!.id, text);
    if (success) _textController.clear();
  }
}
