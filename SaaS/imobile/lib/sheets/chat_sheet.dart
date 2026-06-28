import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../config/theme.dart';
import '../providers/app_state.dart';
import '../models/illustration.dart';

class ChatSheet extends StatefulWidget {
  final Illustration item;
  const ChatSheet({super.key, required this.item});

  @override
  State<ChatSheet> createState() => _ChatSheetState();
}

class _ChatSheetState extends State<ChatSheet> {
  final _textController = TextEditingController();
  final _scrollController = ScrollController();

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      context.read<AppState>().loadChatHistory(widget.item.company.id);
    });
  }

  @override
  void dispose() {
    _textController.dispose();
    _scrollController.dispose();
    super.dispose();
  }

  void _scrollToBottom() {
    WidgetsBinding.instance.addPostFrameCallback((_) {
      if (_scrollController.hasClients) {
        _scrollController.animateTo(
          _scrollController.position.maxScrollExtent,
          duration: const Duration(milliseconds: 300),
          curve: Curves.easeOut,
        );
      }
    });
  }

  String _formatTime(String? dateStr) {
    if (dateStr == null) return '';
    try {
      final date = DateTime.parse(dateStr);
      return '${date.hour.toString().padLeft(2, '0')}:${date.minute.toString().padLeft(2, '0')}';
    } catch (_) {
      return '';
    }
  }

  @override
  Widget build(BuildContext context) {
    return Consumer<AppState>(
      builder: (context, state, _) {
        _scrollToBottom();
        return Scaffold(
          backgroundColor: ImmoTokTheme.bgDark,
          appBar: AppBar(
            backgroundColor: ImmoTokTheme.cardDark,
            elevation: 0,
            leading: IconButton(
              icon: const Icon(Icons.arrow_back, color: ImmoTokTheme.gray400),
              onPressed: () => Navigator.pop(context),
            ),
            title: Row(
              children: [
                Container(
                  width: 36, height: 36,
                  decoration: const BoxDecoration(shape: BoxShape.circle, color: ImmoTokTheme.redPrimary),
                  child: const Icon(Icons.smart_toy, color: Colors.white, size: 18),
                ),
                const SizedBox(width: 10),
                Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text('${widget.item.company.name} AI',
                      style: const TextStyle(fontSize: 14, fontWeight: FontWeight.w700, color: Colors.white)),
                    Row(
                      children: [
                        Container(width: 6, height: 6, decoration: const BoxDecoration(shape: BoxShape.circle, color: ImmoTokTheme.greenOnline)),
                        const SizedBox(width: 4),
                        Text(state.tr('assistant_online'), style: const TextStyle(fontSize: 10, fontWeight: FontWeight.w600, color: ImmoTokTheme.greenOnline)),
                      ],
                    ),
                  ],
                ),
              ],
            ),
            actions: [
              IconButton(icon: const Icon(Icons.close, color: ImmoTokTheme.gray400), onPressed: () => Navigator.pop(context)),
            ],
          ),
          body: Column(
            children: [
              // Messages
              Expanded(
                child: ListView(
                  controller: _scrollController,
                  padding: const EdgeInsets.all(16),
                  children: [
                    // Welcome message
                    Center(
                      child: Container(
                        margin: const EdgeInsets.only(bottom: 16),
                        padding: const EdgeInsets.all(12),
                        constraints: BoxConstraints(maxWidth: MediaQuery.of(context).size.width * 0.8),
                        decoration: BoxDecoration(
                          color: Colors.white.withOpacity(0.05),
                          borderRadius: BorderRadius.circular(12),
                        ),
                        child: Text(
                          '${state.tr('chat_welcome_1')}${widget.item.company.name}${state.tr('chat_welcome_2')}',
                          textAlign: TextAlign.center,
                          style: const TextStyle(fontSize: 12, color: ImmoTokTheme.gray400, height: 1.4),
                        ),
                      ),
                    ),
                    // Messages
                    ...state.chatMessages.map((msg) {
                      final isClient = msg['sender'] == 'client';
                      return Align(
                        alignment: isClient ? Alignment.centerRight : Alignment.centerLeft,
                        child: Container(
                          margin: const EdgeInsets.only(bottom: 12),
                          constraints: BoxConstraints(maxWidth: MediaQuery.of(context).size.width * 0.75),
                          padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 10),
                          decoration: BoxDecoration(
                            color: isClient ? ImmoTokTheme.redPrimary : ImmoTokTheme.cardDark,
                            borderRadius: BorderRadius.only(
                              topLeft: const Radius.circular(16),
                              topRight: const Radius.circular(16),
                              bottomLeft: Radius.circular(isClient ? 16 : 4),
                              bottomRight: Radius.circular(isClient ? 4 : 16),
                            ),
                          ),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.end,
                            children: [
                              Text(msg['message'] ?? '', style: TextStyle(fontSize: 13, color: isClient ? Colors.white : ImmoTokTheme.gray200, height: 1.4)),
                              const SizedBox(height: 4),
                              Text(_formatTime(msg['created_at']),
                                style: TextStyle(fontSize: 9, color: isClient ? Colors.white.withOpacity(0.4) : ImmoTokTheme.gray500, fontFamily: 'monospace')),
                            ],
                          ),
                        ),
                      );
                    }),
                    // Typing indicator
                    if (state.isAiTyping)
                      Align(
                        alignment: Alignment.centerLeft,
                        child: Container(
                          padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 10),
                          decoration: BoxDecoration(
                            color: ImmoTokTheme.cardDark,
                            borderRadius: BorderRadius.circular(16),
                          ),
                          child: const Row(
                            mainAxisSize: MainAxisSize.min,
                            children: [
                              _TypingDot(delay: 0),
                              SizedBox(width: 4),
                              _TypingDot(delay: 100),
                              SizedBox(width: 4),
                              _TypingDot(delay: 200),
                            ],
                          ),
                        ),
                      ),
                  ],
                ),
              ),
              // Suggestions
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
                decoration: BoxDecoration(
                  color: Colors.black.withOpacity(0.25),
                  border: Border(top: BorderSide(color: Colors.white.withOpacity(0.05))),
                ),
                child: SingleChildScrollView(
                  scrollDirection: Axis.horizontal,
                  child: Row(
                    children: state.chatSuggestions.map((sugg) => Padding(
                      padding: const EdgeInsets.only(right: 8),
                      child: GestureDetector(
                        onTap: () => _sendMessage(state, sugg),
                        child: Container(
                          padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 8),
                          decoration: BoxDecoration(
                            color: ImmoTokTheme.cardDark,
                            borderRadius: BorderRadius.circular(20),
                            border: Border.all(color: Colors.white.withOpacity(0.05)),
                          ),
                          child: Text(sugg, style: const TextStyle(fontSize: 12, fontWeight: FontWeight.w600, color: ImmoTokTheme.gray300)),
                        ),
                      ),
                    )).toList(),
                  ),
                ),
              ),
              // Composer
              Container(
                padding: const EdgeInsets.all(12),
                decoration: BoxDecoration(
                  color: ImmoTokTheme.cardDarkAlt,
                  border: Border(top: BorderSide(color: Colors.white.withOpacity(0.05))),
                ),
                child: SafeArea(
                  child: Row(
                    children: [
                      Expanded(
                        child: Container(
                          decoration: BoxDecoration(
                            color: Colors.black.withOpacity(0.2),
                            borderRadius: BorderRadius.circular(22),
                            border: Border.all(color: Colors.white.withOpacity(0.1)),
                          ),
                          child: TextField(
                            controller: _textController,
                            style: const TextStyle(color: Colors.white, fontSize: 13),
                            decoration: InputDecoration(
                              hintText: state.tr('write_message'),
                              hintStyle: const TextStyle(color: ImmoTokTheme.gray500),
                              border: InputBorder.none,
                              contentPadding: EdgeInsets.symmetric(horizontal: 16, vertical: 10),
                            ),
                            onSubmitted: (_) => _sendMessage(state, _textController.text),
                          ),
                        ),
                      ),
                      const SizedBox(width: 8),
                      GestureDetector(
                        onTap: () => _sendMessage(state, _textController.text),
                        child: Container(
                          width: 44, height: 44,
                          decoration: BoxDecoration(
                            color: ImmoTokTheme.redPrimary,
                            shape: BoxShape.circle,
                            boxShadow: [BoxShadow(color: ImmoTokTheme.redPrimary.withOpacity(0.2), blurRadius: 8)],
                          ),
                          child: const Icon(Icons.send, color: Colors.white, size: 18),
                        ),
                      ),
                    ],
                  ),
                ),
              ),
            ],
          ),
        );
      },
    );
  }

  void _sendMessage(AppState state, String text) {
    if (text.trim().isEmpty) return;
    _textController.clear();
    state.sendChatMessage(widget.item.company.id, text, agencyId: widget.item.agencyId);
  }
}

class _TypingDot extends StatefulWidget {
  final int delay;
  const _TypingDot({required this.delay});

  @override
  State<_TypingDot> createState() => _TypingDotState();
}

class _TypingDotState extends State<_TypingDot> with SingleTickerProviderStateMixin {
  late AnimationController _controller;

  @override
  void initState() {
    super.initState();
    _controller = AnimationController(duration: const Duration(milliseconds: 600), vsync: this);
    Future.delayed(Duration(milliseconds: widget.delay), () {
      if (mounted) _controller.repeat(reverse: true);
    });
  }

  @override
  void dispose() {
    _controller.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return AnimatedBuilder(
      animation: _controller,
      builder: (context, child) {
        return Transform.translate(
          offset: Offset(0, -4 * _controller.value),
          child: Container(
            width: 8, height: 8,
            decoration: const BoxDecoration(shape: BoxShape.circle, color: ImmoTokTheme.gray400),
          ),
        );
      },
    );
  }
}
