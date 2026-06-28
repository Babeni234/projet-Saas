import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../services/api_service.dart';
import '../theme/app_colors.dart';
import '../widgets/glass_container.dart';

class SupportScreen extends StatefulWidget {
  const SupportScreen({super.key});

  @override
  State<SupportScreen> createState() => _SupportScreenState();
}

class _SupportScreenState extends State<SupportScreen> {
  int? _selectedTicketIndex;
  final _messageController = TextEditingController();
  bool _showNewTicketModal = false;
  final _newTitleController = TextEditingController();
  final _newDescController = TextEditingController();
  String _newCategory = 'plomberie';

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      context.read<ApiService>().fetchLocataireData();
    });
  }

  @override
  void dispose() {
    _messageController.dispose();
    _newTitleController.dispose();
    _newDescController.dispose();
    super.dispose();
  }

  List<Map<String, dynamic>> get _allTickets {
    final apiTickets = context.watch<ApiService>().tickets.cast<Map<String, dynamic>>();
    final localTickets = context.watch<ApiService>().localTickets;
    return [...apiTickets, ...localTickets];
  }

  Map<String, dynamic>? get _selectedTicket =>
      _selectedTicketIndex != null ? _allTickets[_selectedTicketIndex!] : null;

  String _statusLabel(Map<String, dynamic> t) {
    final s = t['status'] as String? ?? 'open';
    if (s == 'in_progress') return 'En cours';
    if (s == 'closed') return 'Fermé';
    return 'Ouvert';
  }

  Color _statusColor(Map<String, dynamic> t) {
    final s = t['status'] as String? ?? 'open';
    if (s == 'in_progress') return AppColors.warning;
    if (s == 'closed') return AppColors.textSecondary;
    return AppColors.primary;
  }

  void _submitNewTicket() {
    final api = context.read<ApiService>();
    final title = _newTitleController.text.trim();
    final desc = _newDescController.text.trim();
    if (title.isEmpty) return;

    final ticket = <String, dynamic>{
      'id': 'TKT-${_allTickets.length + 1}',
      'title': title,
      'category': _newCategory,
      'status': 'open',
      'date': DateTime.now().toIso8601String().substring(0, 10),
      'messages': [
        {'text': desc, 'sender': 'tenant', 'time': '${DateTime.now().hour}:${DateTime.now().minute.toString().padLeft(2, '0')}'}
      ],
    };
    api.addLocalTicket(ticket);
    _newTitleController.clear();
    _newDescController.clear();
    setState(() => _showNewTicketModal = false);
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(content: const Text('Ticket créé avec succès'), backgroundColor: AppColors.success, behavior: SnackBarBehavior.floating, shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16))),
    );
  }

  void _sendMessage() {
    final api = context.read<ApiService>();
    final text = _messageController.text.trim();
    if (text.isEmpty || _selectedTicketIndex == null) return;

    final msg = <String, dynamic>{
      'text': text,
      'sender': 'tenant',
      'time': '${DateTime.now().hour}:${DateTime.now().minute.toString().padLeft(2, '0')}',
    };

    final idx = _selectedTicketIndex!;
    if (idx < context.read<ApiService>().tickets.length) {
      // API ticket - add to local overrides
      _allTickets[idx]['messages'].add(msg);
    } else {
      // Local ticket
      final localIdx = idx - context.read<ApiService>().tickets.length;
      api.addLocalMessage(localIdx, msg);
    }
    _messageController.clear();
  }

  @override
  Widget build(BuildContext context) {
    return Stack(
      children: [
        SingleChildScrollView(
          physics: const BouncingScrollPhysics(),
          padding: const EdgeInsets.fromLTRB(24, 24, 24, 120),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Expanded(
                    child: Text('Support & Messagerie', style: Theme.of(context).textTheme.headlineMedium?.copyWith(fontSize: 28)),
                  ),
                  GestureDetector(
                    onTap: () => setState(() => _showNewTicketModal = true),
                    child: Container(
                      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 10),
                      decoration: BoxDecoration(
                        gradient: AppColors.actionGradient,
                        borderRadius: BorderRadius.circular(20),
                        boxShadow: [BoxShadow(color: AppColors.primary.withValues(alpha: 0.3), blurRadius: 12, offset: const Offset(0, 4))],
                      ),
                      child: const Row(
                        mainAxisSize: MainAxisSize.min,
                        children: [Icon(Icons.add_rounded, color: Colors.white, size: 18), SizedBox(width: 4), Text('Nouveau', style: TextStyle(color: Colors.white, fontWeight: FontWeight.w700, fontSize: 13))],
                      ),
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 8),
              Text('Discutez avec votre gestionnaire pour résoudre les incidents.', style: Theme.of(context).textTheme.bodyMedium?.copyWith(fontSize: 15)),
              const SizedBox(height: 28),
              if (_selectedTicket != null) _buildChatArea() else _buildTicketList(),
            ],
          ),
        ),
        if (_showNewTicketModal) _buildNewTicketOverlay(),
      ],
    );
  }

  Widget _buildTicketList() {
    return Column(
      children: _allTickets.asMap().entries.map((entry) {
        final ticket = entry.value;
        final statusColor = _statusColor(ticket);
        final statusLabel = _statusLabel(ticket);
        final messages = ticket['messages'] as List<dynamic>? ?? [];
        return Padding(
          padding: const EdgeInsets.only(bottom: 12),
          child: GestureDetector(
            onTap: () => setState(() => _selectedTicketIndex = entry.key),
            child: GlassContainer(
              padding: const EdgeInsets.all(18),
              borderRadius: 20,
              child: Row(
                children: [
                  Container(
                    padding: const EdgeInsets.all(10),
                    decoration: BoxDecoration(color: statusColor.withValues(alpha: 0.12), shape: BoxShape.circle),
                    child: Icon(Icons.support_agent_rounded, color: statusColor, size: 22),
                  ),
                  const SizedBox(width: 16),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(ticket['title'] ?? '', style: const TextStyle(fontWeight: FontWeight.w700, fontSize: 15), overflow: TextOverflow.ellipsis),
                        const SizedBox(height: 6),
                        Row(
                          children: [
                            Container(
                              padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 3),
                              decoration: BoxDecoration(color: statusColor.withValues(alpha: 0.12), borderRadius: BorderRadius.circular(10)),
                              child: Text(statusLabel, style: TextStyle(color: statusColor, fontSize: 11, fontWeight: FontWeight.w600)),
                            ),
                            const SizedBox(width: 10),
                            Text(ticket['date'] ?? '', style: const TextStyle(color: AppColors.textSecondary, fontSize: 12)),
                            const Spacer(),
                            const Icon(Icons.chat_bubble_outline_rounded, size: 16, color: AppColors.textSecondary),
                            const SizedBox(width: 4),
                            Text('${messages.length}', style: const TextStyle(color: AppColors.textSecondary, fontSize: 12)),
                          ],
                        ),
                      ],
                    ),
                  ),
                  const Icon(Icons.chevron_right_rounded, color: AppColors.textSecondary),
                ],
              ),
            ),
          ),
        );
      }).toList(),
    );
  }

  Widget _buildChatArea() {
    final ticket = _selectedTicket!;
    final messages = ticket['messages'] as List<dynamic>? ?? [];
    final isClosed = ticket['status'] == 'closed';
    final title = ticket['title'] as String? ?? '';

    return GlassContainer(
      padding: const EdgeInsets.all(0),
      borderRadius: 24,
      child: Column(
        children: [
          Container(
            padding: const EdgeInsets.all(18),
            decoration: BoxDecoration(border: Border(bottom: BorderSide(color: AppColors.background, width: 1))),
            child: Row(
              children: [
                Container(
                  width: 40, height: 40,
                  decoration: BoxDecoration(gradient: AppColors.actionGradient, borderRadius: BorderRadius.circular(12)),
                  child: Center(child: Text(title.isNotEmpty ? title[0] : '?', style: const TextStyle(color: Colors.white, fontWeight: FontWeight.w700))),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(title, style: const TextStyle(fontWeight: FontWeight.w700, fontSize: 15)),
                      Row(
                        children: [
                          Container(width: 6, height: 6, decoration: const BoxDecoration(shape: BoxShape.circle, color: AppColors.success)),
                          const SizedBox(width: 6),
                          const Text('Gestionnaire en ligne', style: TextStyle(color: AppColors.success, fontSize: 12)),
                        ],
                      ),
                    ],
                  ),
                ),
                GestureDetector(onTap: () => setState(() => _selectedTicketIndex = null), child: const Icon(Icons.close_rounded, color: AppColors.textSecondary)),
              ],
            ),
          ),
          SizedBox(
            height: MediaQuery.of(context).size.height * 0.45,
            child: ListView.builder(
              padding: const EdgeInsets.all(16),
              itemCount: messages.length,
              itemBuilder: (context, index) {
                final msg = messages[index] as Map<String, dynamic>;
                final sender = msg['sender'] as String? ?? 'tenant';
                final isTenant = sender == 'tenant';
                return Padding(
                  padding: const EdgeInsets.only(bottom: 12),
                  child: Row(
                    mainAxisAlignment: isTenant ? MainAxisAlignment.end : MainAxisAlignment.start,
                    crossAxisAlignment: CrossAxisAlignment.end,
                    children: [
                      if (!isTenant) ...[
                        Container(
                          width: 28, height: 28,
                          decoration: BoxDecoration(color: AppColors.primary.withValues(alpha: 0.1), borderRadius: BorderRadius.circular(8)),
                          child: const Icon(Icons.support_agent_rounded, size: 14, color: AppColors.primary),
                        ),
                        const SizedBox(width: 8),
                      ],
                      Flexible(
                        child: Container(
                          padding: const EdgeInsets.all(12),
                          decoration: BoxDecoration(
                            color: isTenant ? AppColors.primary : Colors.white,
                            borderRadius: BorderRadius.only(
                              topLeft: const Radius.circular(18), topRight: const Radius.circular(18),
                              bottomLeft: Radius.circular(isTenant ? 18 : 4),
                              bottomRight: Radius.circular(isTenant ? 4 : 18),
                            ),
                            boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.04), blurRadius: 8, offset: const Offset(0, 2))],
                          ),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.end,
                            children: [
                              Text(msg['text'] ?? '', style: TextStyle(color: isTenant ? Colors.white : AppColors.textPrimary, fontSize: 14)),
                              const SizedBox(height: 4),
                              Text(msg['time'] ?? '', style: TextStyle(color: isTenant ? Colors.white.withValues(alpha: 0.7) : AppColors.textTertiary, fontSize: 11)),
                            ],
                          ),
                        ),
                      ),
                      if (isTenant) ...[
                        const SizedBox(width: 8),
                        Container(
                          width: 28, height: 28,
                          decoration: BoxDecoration(gradient: AppColors.actionGradient, borderRadius: BorderRadius.circular(8)),
                          child: const Icon(Icons.person_rounded, size: 14, color: Colors.white),
                        ),
                      ],
                    ],
                  ),
                );
              },
            ),
          ),
          if (!isClosed)
            Container(
              padding: const EdgeInsets.all(12),
              decoration: BoxDecoration(border: Border(top: BorderSide(color: AppColors.background, width: 1))),
              child: Row(
                children: [
                  Container(
                    padding: const EdgeInsets.all(8),
                    decoration: BoxDecoration(color: AppColors.background, borderRadius: BorderRadius.circular(12)),
                    child: const Icon(Icons.attach_file_rounded, size: 20, color: AppColors.textSecondary),
                  ),
                  const SizedBox(width: 8),
                  Expanded(
                    child: Container(
                      padding: const EdgeInsets.symmetric(horizontal: 16),
                      decoration: BoxDecoration(color: AppColors.background, borderRadius: BorderRadius.circular(20)),
                      child: TextField(
                        controller: _messageController,
                        decoration: const InputDecoration(
                          hintText: 'Écrivez votre message...',
                          border: InputBorder.none,
                          isDense: true,
                          contentPadding: EdgeInsets.symmetric(vertical: 12),
                        ),
                        onSubmitted: (_) => _sendMessage(),
                      ),
                    ),
                  ),
                  const SizedBox(width: 8),
                  GestureDetector(
                    onTap: _sendMessage,
                    child: Container(
                      padding: const EdgeInsets.all(10),
                      decoration: BoxDecoration(gradient: AppColors.actionGradient, borderRadius: BorderRadius.circular(16)),
                      child: const Icon(Icons.send_rounded, color: Colors.white, size: 20),
                    ),
                  ),
                ],
              ),
            ),
        ],
      ),
    );
  }

  Widget _buildNewTicketOverlay() {
    return Positioned(
      left: 0, right: 0, top: 0, bottom: 0,
      child: Container(
        color: Colors.black.withValues(alpha: 0.6),
        child: Center(
          child: SingleChildScrollView(
            padding: const EdgeInsets.all(24),
            child: GlassContainer(
              padding: const EdgeInsets.all(24),
              borderRadius: 32,
              child: Column(
                mainAxisSize: MainAxisSize.min,
                crossAxisAlignment: CrossAxisAlignment.stretch,
                children: [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      const Text('Nouveau Ticket', style: TextStyle(fontWeight: FontWeight.w800, fontSize: 22)),
                      GestureDetector(onTap: () => setState(() => _showNewTicketModal = false), child: const Icon(Icons.close_rounded)),
                    ],
                  ),
                  const SizedBox(height: 20),
                  DropdownButtonFormField<String>(
                    value: _newCategory,
                    decoration: const InputDecoration(labelText: 'Catégorie', prefixIcon: Icon(Icons.category_rounded)),
                    items: const [
                      DropdownMenuItem(value: 'plomberie', child: Text('Plomberie')),
                      DropdownMenuItem(value: 'electricite', child: Text('Électricité')),
                      DropdownMenuItem(value: 'menuserie', child: Text('Menuiserie')),
                      DropdownMenuItem(value: 'autre', child: Text('Autre')),
                    ],
                    onChanged: (v) => setState(() => _newCategory = v ?? 'autre'),
                  ),
                  const SizedBox(height: 16),
                  TextField(
                    controller: _newTitleController,
                    decoration: const InputDecoration(labelText: 'Titre', hintText: 'Ex: Fuite d\'eau', prefixIcon: Icon(Icons.title_rounded)),
                  ),
                  const SizedBox(height: 16),
                  TextField(
                    controller: _newDescController,
                    maxLines: 4,
                    decoration: const InputDecoration(
                      labelText: 'Description',
                      hintText: 'Décrivez le problème...',
                      alignLabelWithHint: true,
                      prefixIcon: Padding(padding: EdgeInsets.only(bottom: 60), child: Icon(Icons.description_rounded)),
                    ),
                  ),
                  const SizedBox(height: 24),
                  SizedBox(
                    height: 56,
                    child: ElevatedButton(
                      onPressed: _submitNewTicket,
                      child: const Text('Créer le ticket', style: TextStyle(fontWeight: FontWeight.w800)),
                    ),
                  ),
                ],
              ),
            ),
          ),
        ),
      ),
    );
  }
}
