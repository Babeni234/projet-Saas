<template>
  <div class="ai-assistant">
    <button class="ai-fab" :class="{ 'ai-fab-open': isOpen }" @click="toggleOpen">
      <svg v-if="!isOpen" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M12 2a10 10 0 0110 10c0 2.5-1 4.8-2.6 6.4L21 22l-4.6-1.6A10 10 0 1112 2z"/>
        <line x1="12" y1="8" x2="12" y2="14"/><line x1="9" y1="11" x2="15" y2="11"/>
      </svg>
      <svg v-else width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
      </svg>
    </button>

    <Transition name="ai-slide">
      <div v-if="isOpen" class="ai-panel glass-card">
        <div class="ai-header">
          <div class="ai-header-left">
            <div class="ai-avatar">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 2a10 10 0 0110 10c0 2.5-1 4.8-2.6 6.4L21 22l-4.6-1.6A10 10 0 1112 2z"/>
              </svg>
            </div>
            <div>
              <div class="ai-name">Agent HABITATUM</div>
              <div class="ai-status" :class="agentStatusClass">● {{ agentStatusText }}</div>
            </div>
          </div>
          <div class="ai-header-actions">
            <button class="ai-header-btn" @click="clearConversation" title="Nouvelle conversation">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
            </button>
            <button class="ai-minimize" @click="toggleOpen" title="Réduire">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/></svg>
            </button>
          </div>
        </div>

        <div class="ai-messages" ref="messagesRef">
          <div v-for="(msg, i) in messages" :key="i" class="ai-msg-row" :class="msg.role === 'assistant' ? 'ai-msg-assistant' : 'ai-msg-user'">
            <div v-if="msg.role === 'assistant'" class="ai-msg-avatar">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2a10 10 0 0110 10c0 2.5-1 4.8-2.6 6.4L21 22l-4.6-1.6A10 10 0 1112 2z"/></svg>
            </div>
            <div class="ai-msg-bubble" :class="msg.role === 'assistant' ? 'ai-bubble-assistant' : 'ai-bubble-user'">
              <div v-if="msg.type === 'action' && msg.actions" class="ai-action-group">
                <div v-for="(act, ai) in msg.actions" :key="ai" class="ai-action-card" @click="executeAction(act.action)">
                  <div class="ai-action-icon" v-html="act.iconSvg || ''"></div>
                  <div>
                    <div class="ai-action-label">{{ act.label || act.action }}</div>
                    <div class="ai-action-desc">Cliquez pour exécuter</div>
                  </div>
                  <svg class="ai-action-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </div>
              </div>
              <div v-else-if="msg.type === 'table' && msg.rows" class="ai-table-msg">
                <div v-for="(row, ri2) in msg.rows" :key="ri2" class="ai-table-row">
                  <span class="ai-table-label">{{ row.label }}</span>
                  <span class="ai-table-value" :style="{color: row.color || 'inherit'}">{{ row.value }}</span>
                </div>
              </div>
              <div v-else-if="msg.type === 'notification'" class="ai-notif-banner" :class="'ai-notif-' + msg.severity">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/>
                </svg>
                <span>{{ msg.text }}</span>
              </div>
              <div v-else class="ai-text-msg">{{ msg.text }}</div>
              <div class="ai-msg-time">{{ msg.time }}</div>
            </div>
          </div>
          <div v-if="isTyping" class="ai-msg-row ai-msg-assistant">
            <div class="ai-msg-avatar">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2a10 10 0 0110 10c0 2.5-1 4.8-2.6 6.4L21 22l-4.6-1.6A10 10 0 1112 2z"/></svg>
            </div>
            <div class="ai-msg-bubble ai-bubble-assistant">
              <div class="ai-typing"><span></span><span></span><span></span></div>
            </div>
          </div>
        </div>

        <div v-if="suggestions.length > 0" class="ai-suggestions">
          <button v-for="(s, si) in suggestions" :key="si" class="ai-pill" @click="sendUserMessage(s)">{{ s }}</button>
        </div>

        <div class="ai-input-area">
          <input ref="inputRef" v-model="userInput" type="text" class="ai-input"
            placeholder="Posez votre question à l'agent…"
            @keydown.enter.prevent="sendMessage" maxlength="1000"/>
          <button class="ai-send-btn" :disabled="!userInput.trim() || isTyping" @click="sendMessage">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
          </button>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, computed, nextTick, watch, onMounted, onUnmounted } from 'vue'
import axios from 'axios'

const props = defineProps({
  user: { type: Object, default: () => ({}) },
  invoices: { type: Array, default: () => [] },
  contract: { type: Object, default: () => ({}) },
  walletBalance: { type: Number, default: 0 },
  currentTab: { type: String, default: 'overview' },
  penaltyInfo: { type: Object, default: () => ({}) },
  latestWaterConso: { type: [String, Number], default: '' },
  latestWaterUnit: { type: String, default: '' },
  latestWaterCost: { type: [String, Number], default: 0 },
  latestWaterPeriod: { type: String, default: '' },
  waterStatus: { type: String, default: '' },
  latestElecConso: { type: [String, Number], default: '' },
  latestElecUnit: { type: String, default: '' },
  latestElecCost: { type: [String, Number], default: 0 },
  latestElecPeriod: { type: String, default: '' },
  elecStatus: { type: String, default: '' },
})

const emit = defineEmits(['navigate', 'action'])

const isOpen = ref(false)
const userInput = ref('')
const messages = ref([])
const isTyping = ref(false)
const suggestions = ref([])
const messagesRef = ref(null)
const inputRef = ref(null)
const conversationId = ref(null)
const agentStatus = ref('online')
let firstOpen = true
let notificationPoll = null

const agentStatusClass = computed(() => ({
  'ai-status-online': agentStatus.value === 'online',
  'ai-status-thinking': agentStatus.value === 'thinking',
  'ai-status-error': agentStatus.value === 'error',
}))

const agentStatusText = computed(() => {
  switch (agentStatus.value) {
    case 'online': return 'Agent actif'
    case 'thinking': return 'Réflexion en cours…'
    case 'error': return 'Erreur de connexion'
    default: return 'En ligne'
  }
})

function buildDashboardData() {
  return {
    auth: props.user,
    invoices: props.invoices,
    contract: props.contract,
    walletBalance: props.walletBalance,
    currentTab: props.currentTab,
    penaltyInfo: props.penaltyInfo,
    latestWaterConso: props.latestWaterConso,
    latestWaterUnit: props.latestWaterUnit,
    latestWaterCost: props.latestWaterCost,
    latestWaterPeriod: props.latestWaterPeriod,
    waterStatus: props.waterStatus,
    latestElecConso: props.latestElecConso,
    latestElecUnit: props.latestElecUnit,
    latestElecCost: props.latestElecCost,
    latestElecPeriod: props.latestElecPeriod,
    elecStatus: props.elecStatus,
  }
}

async function sendMessage() {
  const text = userInput.value.trim()
  if (!text || isTyping.value) return
  userInput.value = ''
  suggestions.value = []
  addMessage('user', text)
  isTyping.value = true
  agentStatus.value = 'thinking'
  scrollDown()

  try {
    const { data } = await axios.post('/api/agent/message', {
      message: text,
      conversation_id: conversationId.value,
      dashboard_data: buildDashboardData(),
    })

    conversationId.value = data.conversation_id
    isTyping.value = false
    agentStatus.value = 'online'

    if (data.text) {
      addMessage('assistant', data.text)
    }

    if (data.frontend_actions && data.frontend_actions.length > 0) {
      nextTick(() => {
        addActionCards(data.frontend_actions)
      })
    }

    const detected = detectSuggestions(data.text || '', data.frontend_actions || [])
    suggestions.value = detected
    scrollDown()
  } catch (err) {
    console.error('AI Agent error:', err)
    isTyping.value = false
    agentStatus.value = 'error'
    const errorMsg = err.response?.data?.message || 'Erreur de communication avec l\'agent. Veuillez réessayer.'
    addMessage('assistant', '⚠️ ' + errorMsg)
    suggestions.value = ['Réessayer', 'Payer mon loyer', 'Mon contrat']
    scrollDown()
  }
}

function sendUserMessage(text) {
  userInput.value = text
  sendMessage()
}

function addMessage(role, text, type = 'text', extra = null) {
  const time = new Date().toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })
  const msg = { role, type, text: '', time }

  if (type === 'action' && extra && extra.length > 0) {
    msg.type = 'action'
    msg.actions = extra
  } else if (type === 'table' && extra) {
    msg.type = 'table'
    msg.rows = extra
  } else {
    msg.text = text
  }

  messages.value.push(msg)
}

function addActionCards(actions) {
  const time = new Date().toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })
  messages.value.push({
    role: 'assistant',
    type: 'action',
    actions: actions.map(a => ({
      action: a.action,
      label: a.label || a.action,
      iconSvg: a.iconSvg || getDefaultIcon(a.action),
    })),
    text: '',
    time,
  })
  scrollDown()
}

function getDefaultIcon(action) {
  if (action.includes('payment') || action.includes('pay')) return '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>'
  if (action.includes('recharge')) return '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>'
  if (action.includes('contract')) return '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>'
  if (action.includes('profile')) return '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>'
  if (action.includes('support')) return '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>'
  if (action.includes('utility') || action.includes('water') || action.includes('elec')) return '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>'
  return '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>'
}

function executeAction(actionId) {
  emit('action', actionId)
  isOpen.value = false
}

function clearConversation() {
  messages.value = []
  conversationId.value = null
  suggestions.value = []
  axios.delete('/api/agent/clear').catch(() => {})
  addMessage('assistant', '🧹 Conversation réinitialisée. Je suis à votre écoute !')
  suggestions.value = ['Payer mon loyer', 'Mon contrat', 'Solde du portefeuille']
}

function detectSuggestions(text, actions) {
  if (!text) return ['Payer mon loyer', 'Mon contrat', 'Solde du portefeuille']

  const s = []
  if (/solde|wallet|portefeuille|recharge/i.test(text)) s.push('Recharger le portefeuille')
  if (/loyer|payer|paiement|impayé|impaye|pénalité|penalite/i.test(text)) s.push('Payer maintenant')
  if (/contrat|bail/i.test(text)) s.push('Voir mon contrat')
  if (/facture|eau|électricité|electricite/i.test(text)) s.push('Voir mes factures')
  if (/profil|profile/i.test(text)) s.push('Voir mon profil')

  if (s.length === 0) {
    s.push('Payer mon loyer', 'Mon contrat', 'Solde du portefeuille')
  }

  return s.slice(0, 3)
}

function toggleOpen() {
  isOpen.value = !isOpen.value
  if (isOpen.value) {
    nextTick(() => {
      inputRef.value?.focus()
      if (firstOpen) {
        firstOpen = false
        setTimeout(() => {
          isTyping.value = true
          setTimeout(() => {
            isTyping.value = false
            addMessage('assistant', buildGreeting())
            suggestions.value = ['Situation des loyers', 'Mon contrat', 'Solde du portefeuille', 'Aide']
            scrollDown()
          }, 600)
        }, 300)
      }
    })
  }
}

function buildGreeting() {
  const h = new Date().getHours()
  const period = h < 12 ? 'matin' : h < 18 ? 'après-midi' : 'soirée'
  const prefix = period === 'soirée' ? 'Bon' : 'Bon'
  const greeting = period === 'soirée' ? 'soir' : 'jour'
  const name = props.user?.first_name || props.user?.name || ''
  const overdue = props.penaltyInfo?.isOverdue

  let extra = ''
  if (overdue) {
    extra = `\n\n⚠️ **${props.penaltyInfo.unpaidCount} mois impayés** avec pénalité ${props.penaltyInfo.currentLabel}. Total dû : **${(props.penaltyInfo.totalDue || 0).toLocaleString()} XAF**.`
  }

  return `${prefix}${greeting} ${name} ! Je suis votre **agent intelligent HABITATUM**. Je peux consulter vos informations et effectuer des actions pour vous en temps réel.${extra}\n\nQue souhaitez-vous ?`
}

function scrollDown() {
  nextTick(() => { if (messagesRef.value) messagesRef.value.scrollTop = messagesRef.value.scrollHeight })
}

function startNotificationPolling() {
  notificationPoll = setInterval(async () => {
    try {
      const { data } = await axios.get('/api/agent/state')
      if (data.changes && data.changes.length > 0) {
        for (const change of data.changes) {
          if (!messages.value.find(m => m.text?.includes(change.message))) {
            const time = new Date().toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })
            messages.value.push({
              role: 'assistant',
              type: 'notification',
              severity: 'warning',
              text: '🔔 ' + change.message,
              action: change.action,
              time,
            })
          }
        }
      }
    } catch (e) {}
  }, 30000)
}

watch(isOpen, (val) => {
  if (val) document.body.style.overflow = 'hidden'
  else document.body.style.overflow = ''
})

onMounted(() => {
  startNotificationPolling()
  if (typeof window !== 'undefined' && 'Notification' in window && Notification.permission === 'granted') {
    const info = props.penaltyInfo
    if (info?.isOverdue) {
      setTimeout(() => {
        addMessage('assistant', `🔔 **Rappel :** ${info.unpaidCount} mois impayés. Pénalité ${info.currentLabel} appliquée. Total dû : ${(info.totalDue || 0).toLocaleString()} XAF.`)
      }, 1500)
    }
  }
})

onUnmounted(() => {
  if (notificationPoll) clearInterval(notificationPoll)
})
</script>

<style scoped>
.ai-fab {
  position: fixed; bottom: 28px; right: 28px;
  width: 56px; height: 56px; border-radius: 50%;
  background: linear-gradient(135deg, #6366F1, #4F46E5);
  color: white; border: none; cursor: pointer; z-index: 9999;
  display: flex; align-items: center; justify-content: center;
  box-shadow: 0 8px 32px rgba(99,102,241,0.35);
  transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}
.ai-fab:hover { transform: scale(1.08); box-shadow: 0 12px 40px rgba(99,102,241,0.45); }
.ai-fab-open { transform: rotate(90deg); background: linear-gradient(135deg, #DC2626, #B91C1C); }
.ai-fab-open:hover { transform: rotate(90deg) scale(1.08); }

.ai-panel {
  position: fixed; bottom: 96px; right: 28px;
  width: 420px; height: 600px; max-height: calc(100vh - 140px);
  border-radius: 20px; background: var(--bg-card, #fff);
  box-shadow: 0 32px 80px rgba(0,0,0,0.2), 0 0 0 1px rgba(255,255,255,0.05);
  display: flex; flex-direction: column; z-index: 9998;
  overflow: hidden;
}
.dark-theme .ai-panel { background: #1E293B; box-shadow: 0 32px 80px rgba(0,0,0,0.5); }

.ai-header {
  display: flex; align-items: center; justify-content: space-between;
  padding: 14px 18px; border-bottom: 1px solid var(--border-input, #E2E8F0);
  flex-shrink: 0;
}
.ai-header-left { display: flex; align-items: center; gap: 10px; }
.ai-header-actions { display: flex; align-items: center; gap: 4px; }
.ai-header-btn {
  width: 32px; height: 32px; border-radius: 50%; border: none;
  background: transparent; color: var(--text-muted, #94A3B8); cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: background 0.2s;
}
.ai-header-btn:hover { background: var(--gray-100, #F1F5F9); }
.ai-avatar {
  width: 36px; height: 36px; border-radius: 50%;
  background: linear-gradient(135deg, #6366F1, #4F46E5);
  display: flex; align-items: center; justify-content: center; color: white;
}
.ai-name { font-size: 14px; font-weight: 700; color: var(--text-title, #0F172A); }
.ai-status { font-size: 11px; font-weight: 600; }
.ai-status-online { color: #059669; }
.ai-status-thinking { color: #D97706; }
.ai-status-error { color: #DC2626; }
.ai-minimize {
  width: 32px; height: 32px; border-radius: 50%; border: none;
  background: transparent; color: var(--text-muted, #94A3B8); cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: background 0.2s;
}
.ai-minimize:hover { background: var(--gray-100, #F1F5F9); }

.ai-messages {
  flex: 1; overflow-y: auto; padding: 14px 14px 6px;
  display: flex; flex-direction: column; gap: 10px;
  scroll-behavior: smooth;
}
.ai-messages::-webkit-scrollbar { width: 4px; }
.ai-messages::-webkit-scrollbar-track { background: transparent; }
.ai-messages::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 4px; }

.ai-msg-row { display: flex; gap: 8px; align-items: flex-start; width: 100%; }
.ai-msg-user { flex-direction: row-reverse; }
.ai-msg-avatar {
  width: 24px; height: 24px; border-radius: 50%; flex-shrink: 0;
  background: linear-gradient(135deg, #6366F1, #4F46E5);
  display: flex; align-items: center; justify-content: center; color: white;
  font-size: 10px; margin-top: 4px;
}

.ai-msg-bubble {
  max-width: 82%; padding: 10px 14px; border-radius: 14px;
  font-size: 13px; line-height: 1.5; animation: aiFadeIn 0.3s ease;
}
.ai-bubble-user {
  background: linear-gradient(135deg, #6366F1, #4F46E5);
  color: white; border-bottom-right-radius: 4px;
}
.ai-bubble-assistant {
  background: var(--gray-100, #F1F5F9); color: var(--text-body, #334155);
  border-bottom-left-radius: 4px;
}
.dark-theme .ai-bubble-assistant { background: #334155; color: #E2E8F0; }

.ai-text-msg { white-space: pre-wrap; }
.ai-text-msg strong { font-weight: 700; }
.ai-msg-time { font-size: 10px; color: var(--text-muted, #94A3B8); margin-top: 4px; text-align: right; }
.ai-bubble-user .ai-msg-time { color: rgba(255,255,255,0.6); }

.ai-typing { display: flex; gap: 4px; padding: 4px 0; }
.ai-typing span {
  width: 7px; height: 7px; border-radius: 50%; background: #6366F1;
  animation: aiTyping 1.2s ease-in-out infinite;
}
.ai-typing span:nth-child(2) { animation-delay: 0.2s; }
.ai-typing span:nth-child(3) { animation-delay: 0.4s; }

@keyframes aiTyping {
  0%, 60%, 100% { transform: translateY(0); opacity: 0.4; }
  30% { transform: translateY(-6px); opacity: 1; }
}

.ai-action-group { display: flex; flex-direction: column; gap: 6px; }
.ai-action-card {
  display: flex; align-items: center; gap: 10px; padding: 10px 12px;
  background: var(--bg-card, #fff); border-radius: 12px;
  cursor: pointer; transition: all 0.2s ease;
  border: 1px solid var(--border-input, #E2E8F0);
  box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}
.ai-action-card:hover { transform: translateX(3px); border-color: #6366F1; box-shadow: 0 4px 16px rgba(99,102,241,0.12); }
.dark-theme .ai-action-card { background: #1E293B; border-color: #334155; }
.ai-action-icon { font-size: 20px; width: 32px; text-align: center; flex-shrink: 0; }
.ai-action-icon svg { width: 20px; height: 20px; }
.ai-action-label { font-size: 13px; font-weight: 700; color: var(--text-title, #0F172A); }
.ai-action-desc { font-size: 11px; color: var(--text-muted, #94A3B8); }
.ai-action-arrow { flex-shrink: 0; color: #6366F1; margin-left: auto; }

.ai-table-msg { display: flex; flex-direction: column; gap: 4px; }
.ai-table-row { display: flex; justify-content: space-between; gap: 12px; padding: 3px 0; }
.ai-table-label { font-size: 12px; color: var(--text-muted, #64748B); }
.ai-table-value { font-size: 13px; font-weight: 700; }

.ai-notif-banner {
  display: flex; align-items: center; gap: 8px; padding: 8px 12px;
  border-radius: 10px; font-size: 12px; font-weight: 600;
}
.ai-notif-warning { background: #FEF3C7; color: #92400E; }
.ai-notif-error { background: #FEE2E2; color: #991B1B; }
.ai-notif-info { background: #DBEAFE; color: #1E40AF; }
.dark-theme .ai-notif-warning { background: rgba(217,119,6,0.2); color: #FBBF24; }
.dark-theme .ai-notif-error { background: rgba(220,38,38,0.2); color: #F87171; }
.dark-theme .ai-notif-info { background: rgba(37,99,235,0.2); color: #60A5FA; }

.ai-suggestions {
  display: flex; flex-wrap: wrap; gap: 6px; padding: 4px 14px 2px; flex-shrink: 0;
}
.ai-pill {
  padding: 6px 14px; border-radius: 20px; border: 1px solid var(--border-input, #E2E8F0);
  background: var(--bg-card, #fff); color: var(--text-body, #475569);
  font-size: 12px; font-weight: 600; cursor: pointer;
  transition: all 0.2s ease; white-space: nowrap;
}
.ai-pill:hover { border-color: #6366F1; color: #6366F1; background: #EEF2FF; }
.dark-theme .ai-pill { background: #1E293B; border-color: #334155; }
.dark-theme .ai-pill:hover { background: rgba(99,102,241,0.1); border-color: #6366F1; }

.ai-input-area {
  display: flex; align-items: center; gap: 8px;
  padding: 10px 14px 14px; border-top: 1px solid var(--border-input, #E2E8F0);
  flex-shrink: 0;
}
.ai-input {
  flex: 1; padding: 10px 16px; border-radius: 24px; border: 1px solid var(--border-input, #E2E8F0);
  background: var(--gray-50, #F8FAFC); color: var(--text-body, #334155);
  font-size: 13px; outline: none; transition: border-color 0.2s;
}
.ai-input:focus { border-color: #6366F1; }
.dark-theme .ai-input { background: #1E293B; border-color: #334155; color: #E2E8F0; }
.ai-send-btn {
  width: 40px; height: 40px; border-radius: 50%; border: none;
  background: linear-gradient(135deg, #6366F1, #4F46E5); color: white;
  cursor: pointer; display: flex; align-items: center; justify-content: center;
  transition: all 0.2s; flex-shrink: 0;
}
.ai-send-btn:hover:not(:disabled) { transform: scale(1.05); box-shadow: 0 4px 16px rgba(99,102,241,0.3); }
.ai-send-btn:disabled { opacity: 0.4; cursor: default; }

.ai-slide-enter-active { transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1); }
.ai-slide-leave-active { transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1); }
.ai-slide-enter-from { opacity: 0; transform: translateY(20px) scale(0.95); }
.ai-slide-leave-to { opacity: 0; transform: translateY(12px) scale(0.95); }

@keyframes aiFadeIn {
  from { opacity: 0; transform: translateY(6px); }
  to { opacity: 1; transform: translateY(0); }
}

@media (max-width: 480px) {
  .ai-panel { right: 12px; left: 12px; width: auto; height: calc(100vh - 140px); }
  .ai-fab { right: 16px; bottom: 16px; width: 50px; height: 50px; }
}
</style>
