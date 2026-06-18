import 'package:flutter/material.dart';
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

  final List<TicketData> _tickets = [
    TicketData(
      id: 1, title: 'Fuite d\'eau salle de bain', category: 'Plomberie',
      status: 'in_progress', date: '12 Juin 2026',
      messages: [
        MsgData(text: 'Bonjour, j\'ai une fuite d\'eau sous le lavabo.', sender: 'tenant', time: '10:32'),
        MsgData(text: 'Nous avons reçu votre signalement. Un plombier passera demain.', sender: 'manager', time: '11:15'),
        MsgData(text: 'Merci. À quelle heure ?', sender: 'tenant', time: '11:20'),
        MsgData(text: 'Entre 8h et 10h. Notification de confirmation.', sender: 'manager', time: '14:00'),
      ],
    ),
    TicketData(
      id: 2, title: 'Problème chauffage', category: 'Chauffage',
      status: 'closed', date: '28 Mai 2026',
      messages: [
        MsgData(text: 'Le chauffage ne fonctionne plus.', sender: 'tenant', time: '09:00'),
        MsgData(text: 'Technicien intervenu. Problème résolu.', sender: 'manager', time: '16:30'),
      ],
    ),
  ];

  TicketData? get _selectedTicket => _selectedTicketIndex != null ? _tickets[_selectedTicketIndex!] : null;

  @override
  void dispose() {
    _messageController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return SingleChildScrollView(
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
              Container(
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
            ],
          ),
          const SizedBox(height: 8),
          Text('Discutez avec votre gestionnaire pour résoudre les incidents.', style: Theme.of(context).textTheme.bodyMedium?.copyWith(fontSize: 15)),
          const SizedBox(height: 28),
          if (_selectedTicket != null) _buildChatArea() else _buildTicketList(),
        ],
      ),
    );
  }

  Widget _buildTicketList() {
    return Column(
      children: _tickets.map((ticket) {
        final statusColor = ticket.status == 'in_progress' ? AppColors.warning : (ticket.status == 'closed' ? AppColors.textSecondary : AppColors.primary);
        final statusLabel = ticket.status == 'in_progress' ? 'En cours' : (ticket.status == 'closed' ? 'Fermé' : 'Ouvert');
        return Padding(
          padding: const EdgeInsets.only(bottom: 12),
          child: GestureDetector(
            onTap: () => setState(() => _selectedTicketIndex = _tickets.indexOf(ticket)),
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
                        Text(ticket.title, style: const TextStyle(fontWeight: FontWeight.w700, fontSize: 15), overflow: TextOverflow.ellipsis),
                        const SizedBox(height: 6),
                        Row(
                          children: [
                            Container(
                              padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 3),
                              decoration: BoxDecoration(color: statusColor.withValues(alpha: 0.12), borderRadius: BorderRadius.circular(10)),
                              child: Text(statusLabel, style: TextStyle(color: statusColor, fontSize: 11, fontWeight: FontWeight.w600)),
                            ),
                            const SizedBox(width: 10),
                            Text(ticket.date, style: const TextStyle(color: AppColors.textSecondary, fontSize: 12)),
                            const Spacer(),
                            const Icon(Icons.chat_bubble_outline_rounded, size: 16, color: AppColors.textSecondary),
                            const SizedBox(width: 4),
                            Text('${ticket.messages.length}', style: const TextStyle(color: AppColors.textSecondary, fontSize: 12)),
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
                  child: Center(child: Text(ticket.title[0], style: const TextStyle(color: Colors.white, fontWeight: FontWeight.w700))),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(ticket.title, style: const TextStyle(fontWeight: FontWeight.w700, fontSize: 15)),
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
            height: 300,
            child: ListView.builder(
              padding: const EdgeInsets.all(16),
              itemCount: ticket.messages.length,
              itemBuilder: (context, index) {
                final msg = ticket.messages[index];
                final isTenant = msg.sender == 'tenant';
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
                              Text(msg.text, style: TextStyle(color: isTenant ? Colors.white : AppColors.textPrimary, fontSize: 14)),
                              const SizedBox(height: 4),
                              Text(msg.time, style: TextStyle(color: isTenant ? Colors.white.withValues(alpha: 0.7) : AppColors.textTertiary, fontSize: 11)),
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
          if (ticket.status != 'closed')
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
                      ),
                    ),
                  ),
                  const SizedBox(width: 8),
                  Container(
                    padding: const EdgeInsets.all(10),
                    decoration: BoxDecoration(gradient: AppColors.actionGradient, borderRadius: BorderRadius.circular(16)),
                    child: const Icon(Icons.send_rounded, color: Colors.white, size: 20),
                  ),
                ],
              ),
            ),
        ],
      ),
    );
  }
}

class TicketData {
  final int id;
  final String title;
  final String category;
  final String status;
  final String date;
  final List<MsgData> messages;
  TicketData({required this.id, required this.title, required this.category, required this.status, required this.date, required this.messages});
}

class MsgData {
  final String text;
  final String sender;
  final String time;
  MsgData({required this.text, required this.sender, required this.time});
}
