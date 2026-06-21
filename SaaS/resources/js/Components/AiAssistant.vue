<template>
  <div class="ai-assistant">
    <!-- FAB -->
    <button class="ai-fab" :class="{ 'ai-fab-open': isOpen }" @click="toggleOpen">
      <svg v-if="!isOpen" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M12 2a10 10 0 0110 10c0 2.5-1 4.8-2.6 6.4L21 22l-4.6-1.6A10 10 0 1112 2z"/>
        <line x1="12" y1="8" x2="12" y2="14"/><line x1="9" y1="11" x2="15" y2="11"/>
      </svg>
      <svg v-else width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
      </svg>
    </button>

    <!-- Chat panel -->
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
              <div class="ai-name">Assistance HABITATUM</div>
              <div class="ai-status">En ligne</div>
            </div>
          </div>
          <button class="ai-minimize" @click="toggleOpen" title="Réduire">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/></svg>
          </button>
        </div>

        <div class="ai-messages" ref="messagesRef">
          <div v-for="(msg, i) in messages" :key="i" class="ai-msg-row" :class="msg.role === 'assistant' ? 'ai-msg-assistant' : 'ai-msg-user'">
            <div v-if="msg.role === 'assistant'" class="ai-msg-avatar">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2a10 10 0 0110 10c0 2.5-1 4.8-2.6 6.4L21 22l-4.6-1.6A10 10 0 1112 2z"/></svg>
            </div>
            <div class="ai-msg-bubble" :class="msg.role === 'assistant' ? 'ai-bubble-assistant' : 'ai-bubble-user'">
              <div v-if="msg.type === 'action'" class="ai-action-msg">
                <div class="ai-action-card" @click="executeAction(msg.action)">
                  <div class="ai-action-icon" v-html="msg.actionIcon || ''"></div>
                  <div>
                    <div class="ai-action-label">{{ msg.actionLabel }}</div>
                    <div class="ai-action-desc">Cliquez pour exécuter</div>
                  </div>
                  <svg class="ai-action-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </div>
              </div>
              <div v-else-if="msg.type === 'table'" class="ai-table-msg">
                <div v-for="(row, ri) in msg.rows" :key="ri" class="ai-table-row">
                  <span class="ai-table-label">{{ row.label }}</span>
                  <span class="ai-table-value" :style="{color: row.color || 'inherit'}">{{ row.value }}</span>
                </div>
              </div>
              <div v-else-if="msg.type === 'html'" v-html="msg.html"></div>
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

        <!-- Suggested pills -->
        <div v-if="suggestions.length > 0" class="ai-suggestions">
          <button v-for="(s, si) in suggestions" :key="si" class="ai-pill" @click="sendUserMessage(s)">{{ s }}</button>
        </div>

        <div class="ai-input-area">
          <input ref="inputRef" v-model="userInput" type="text" class="ai-input" placeholder="Posez votre question…"
            @keydown.enter.prevent="sendMessage" maxlength="500"/>
          <button class="ai-send-btn" :disabled="!userInput.trim()" @click="sendMessage">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
          </button>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, computed, nextTick, watch, onMounted } from 'vue'

const props = defineProps({
  user: { type: Object, default: () => ({}) },
  invoices: { type: Array, default: () => [] },
  contract: { type: Object, default: () => ({}) },
  walletBalance: { type: Number, default: 0 },
  currentTab: { type: String, default: 'overview' },
  penaltyInfo: { type: Object, default: () => ({}) },
})

const emit = defineEmits(['navigate', 'action'])

const isOpen = ref(false)
const userInput = ref('')
const messages = ref([])
const isTyping = ref(false)
const suggestions = ref([])
const messagesRef = ref(null)
const inputRef = ref(null)
let firstOpen = true
const lastIntent = ref('')
const conversationContext = ref({ step: 'idle', lastTopic: '' })

const knowledgeBase = {
  system: "HABITATUM est une plateforme de gestion locative premium. Vous gerez votre logement, vos loyers, vos factures et vos communications avec le gestionnaire depuis ce tableau de bord.",
  tabs: "Votre espace dispose de plusieurs sections : Vue d'ensemble, Contrat, Loyer, Support, Profil, Factures Eau/Electricite, Quittances, Anciens Contrats.",
  payment: "Le loyer est du avant le 10 de chaque mois. Paiement via le portefeuille HABITATUM (MTN Mobile Money, Orange Money, carte bancaire ou PayPal). Penalite progressive en cas de retard.",
  contract: "Votre bail est d'une duree determinee. Consultez les details dans la section Contrat.",
  support: "Ouvrez un ticket dans la section Support pour signaler un probleme. Le gestionnaire repond dans l'interface.",
  penalties: "Barème des penalites : a partir du 11 : 5%, a partir du 16 : 10%, a partir du 21 : 15%. Le taux s'applique a tous les mois impayes.",
  wallet: "Le portefeuille HABITATUM permet de payer loyers et factures. Rechargez via MTN MoMo, Orange Money, carte bancaire ou PayPal.",
}

const userName = computed(() => props.user?.first_name || props.user?.name || '')

function getContext() {
  const now = new Date()
  const day = now.getDate()
  const month = now.toLocaleString('fr-FR', { month: 'long' })
  const year = now.getFullYear()
  const pending = props.invoices.filter(i => i.status === 'pending' && i.type === 'RENT')
  const paid = props.invoices.filter(i => i.status === 'paid' && i.type === 'RENT')
  const lastPaid = paid.length > 0 ? paid[paid.length - 1] : null
  return { now, day, month, year, pending, paid, lastPaid, info: props.penaltyInfo }
}

function matchAny(input, keywords) {
  return keywords.some(k => input.includes(k))
}

function matchAll(input, phrases) {
  return phrases.some(p => p.split(' ').every(w => input.includes(w)))
}

function processIntent(input) {
  const n = input.toLowerCase().trim()
  const ctx = getContext()

  if (matchAny(n, ['bonjour', 'salut', 'bonsoir', 'hello', 'hi', 'coucou', 'aide', 'help', 'bonsoir', 's il vous plait', 'besoin'])) {
    return handleGreeting(ctx)
  }
  if (matchAny(n, ['oui', 'yes', 'ok', 'd accord', 'vas-y', 'vas y', 'continue', 'bien', 'd accord'])) {
    return handleAffirmative()
  }
  if (matchAny(n, ['non', 'no', 'pas', 'rien', 'arrete', 'stop'])) {
    return { text: 'D accord. Je reste a votre disposition si besoin.', suggestions: defaultSuggestions() }
  }
  if (matchAny(n, ['merci', 'thanks', 'thank', 'merci beaucoup'])) {
    return { text: `Avec plaisir ${userName.value || ''}. N hesitez pas a me solliciter.`, suggestions: defaultSuggestions() }
  }

  // Paiement
  if (matchAny(n, ['payer', 'paiement', 'paye', 'regler', 'reglement', 'montant du', 'combien je dois', 'impayer', 'impaye', 'je dois'])) {
    return handlePaymentIntent(n, ctx)
  }

  // Penalites
  if (matchAny(n, ['penalite', 'penalite', 'amende', 'majoration', 'retard', 'sursis', 'bareme', 'taux'])) {
    return handlePenaltyIntent(ctx)
  }

  // Factures / Quittances
  if (matchAny(n, ['facture', 'factures', 'quittance', 'quittances', 'recu', 'recus', 'reçu', 'reçus'])) {
    return handleInvoiceIntent(n, ctx)
  }

  // Contrat
  if (matchAny(n, ['contrat', 'bail', 'propriete', 'logement', 'appartement', 'location', 'bien'])) {
    return handleContractIntent(ctx)
  }

  // Portefeuille
  if (matchAny(n, ['portefeuille', 'solde', 'wallet', 'recharge', 'recharger', 'argent', 'compte', 'balance', 'monnaie'])) {
    return handleWalletIntent(ctx)
  }

  // Support
  if (matchAny(n, ['support', 'ticket', 'probleme', 'panne', 'signal', 'urgence', 'urgent', 'contacter', 'gestionnaire', 'proprietaire', 'reparateur'])) {
    return handleSupportIntent(n)
  }

  // Navigation (va a, affiche, montre, ouvre)
  if (matchAny(n, ['va a', 'va dans', 'affiche', 'montre', 'montre-moi', 'montre moi', 'ouvre', 'naviguer', 'section', 'page', 'accueil', 'tableau de bord', 'emmene'])) {
    return handleNavigateIntent(n)
  }

  // Profil
  if (matchAny(n, ['profil', 'mot de passe', 'identite', 'personnel', 'information', 'mon nom', 'mon email'])) {
    return handleProfileIntent(ctx)
  }

  // Notifications
  if (matchAny(n, ['notification', 'notifications', 'alerte', 'alerter', 'notifier', 'rappel', 'prevenir', 'prevenez'])) {
    return handleNotificationIntent()
  }

  // Utilités
  if (matchAny(n, ['eau', 'electricite', 'elec', 'consommation', 'compteur', 'index', 'facture eau', 'facture electricite'])) {
    return handleUtilityIntent(n, ctx)
  }

  // Système
  if (matchAny(n, ['qui es', 'c est quoi', 'habitatum', 'systeme', 'plateforme', 'logiciel', 'que fait'])) {
    return { text: knowledgeBase.system + ' ' + knowledgeBase.tabs, suggestions: defaultSuggestions() }
  }

  // Commander une action
  if (matchAny(n, ['execute', 'fais', 'fais-le', 'fait', 'effectue', 'lance', 'applique'])) {
    return handleCommandIntent(n, ctx)
  }

  return handleUnknown()
}

function handleGreeting(ctx) {
  const h = new Date().getHours()
  const period = h < 12 ? 'matin' : h < 18 ? 'apres-midi' : 'soiree'
  const name = userName.value
  const unpaidMonths = ctx.info.isOverdue ? ctx.info.unpaidCount : 0

  let extra = ''
  if (ctx.info.isOverdue) {
    extra = ` Attention : ${unpaidMonths} mois impaye(s) avec penalite de ${ctx.info.currentLabel}.`
  }

  return {
    text: `Bon${period === 'soiree' ? 'soir' : 'jour'} ${name || ''}.${extra} Comment puis-je vous assister ?`,
    suggestions: [
      'Payer mon loyer',
      'Situation des penalites',
      'Mon contrat',
      'Solde du portefeuille',
    ]
  }
}

function handlePaymentIntent(n, ctx) {
  // Si la demande inclut un ordre de paiement direct pour un montant specifique
  if (matchAny(n, ['paye maintenant', 'effectue le paiement', 'fais le paiement', 'paie', 'regle maintenant'])) {
    return handleDirectPayment(ctx)
  }

  if (matchAny(n, ['historique', 'dernier', 'deja', 'effectue', 'deja paye'])) {
    if (ctx.lastPaid) {
      return {
        text: `Votre dernier paiement : **${ctx.lastPaid.period}** - ${ctx.lastPaid.amount.toLocaleString()} XAF.`,
        suggestions: ['Payer le mois en cours', 'Voir toutes les factures'],
      }
    }
    return { text: 'Aucun historique de paiement trouve.', suggestions: defaultSuggestions() }
  }

  if (ctx.pending.length === 0) {
    if (ctx.info.isOverdue) {
      const dueText = `${ctx.info.unpaidCount} mois impayes. Penalite ${ctx.info.currentLabel} (${ctx.info.totalPenalties.toLocaleString()} XAF). Total du : ${ctx.info.totalDue.toLocaleString()} XAF.`
      return {
        text: dueText,
        actions: [{ id: 'pay-rent', label: `Payer ${ctx.info.totalDue.toLocaleString()} XAF`, iconSvg: 'card', action: 'navigate_payment' }],
        suggestions: ['Effectuer le paiement', 'Voir le detail des penalites'],
      }
    }
    return { text: 'Tous les loyers sont a jour.', suggestions: ['Voir mon contrat', 'Recharger le portefeuille'] }
  }

  const next = ctx.pending[0]
  const penText = ctx.info.isOverdue
    ? ` Penalite ${ctx.info.currentLabel} applicable.`
    : ` Montant : ${next.amount.toLocaleString()} XAF. A payer avant le **10 ${ctx.month} ${ctx.year}** (pas de penalite pour l'instant).`

  return {
    text: `Loyer **${next.period}** en attente.${penText}`,
    actions: [{ id: 'pay-rent', label: 'Payer maintenant', iconSvg: 'card', action: 'navigate_payment' }],
    suggestions: ['Effectuer le paiement', 'Voir toutes les factures', 'Detail des penalites'],
  }
}

function handleDirectPayment(ctx) {
  if (!ctx.info.isOverdue && ctx.pending.length === 0) {
    return { text: 'Aucun impaye. Tous les loyers sont a jour.', suggestions: defaultSuggestions() }
  }
  return {
    text: `Je vous redirige vers la page de paiement. Le montant total du est de **${ctx.info.totalDue > 0 ? ctx.info.totalDue.toLocaleString() : ctx.pending[0]?.amount.toLocaleString() || '?'} XAF**. Veuillez confirmer votre code de securite.`,
    actions: [{ id: 'pay-rent', label: 'Proceder au paiement securise', iconSvg: 'card', action: 'navigate_payment' }],
  }
}

function handlePenaltyIntent(ctx) {
  const info = ctx.info
  if (info.isOverdue) {
    const rows = info.details.map(d => ({
      label: d.period,
      value: `${d.rent.toLocaleString()} XAF + ${d.rateLabel} = ${d.penalty.toLocaleString()} XAF`,
      color: '#DC2626'
    }))
    rows.push(
      { label: 'Total penalites', value: `${info.totalPenalties.toLocaleString()} XAF`, color: '#DC2626' },
      { label: 'Total general du', value: `${info.totalDue.toLocaleString()} XAF`, color: '#6366F1' },
    )
    return {
      text: `Detail des penalites (taux actuel : **${info.currentLabel}**) :`,
      type: 'table',
      rows,
      suggestions: ['Payer maintenant', 'Voir le bareme'],
    }
  }
  return {
    text: 'Aucune penalite en cours. Le bareme : 5% a partir du 11, 10% a partir du 16, 15% a partir du 21 de chaque mois.',
    suggestions: defaultSuggestions(),
  }
}

function handleInvoiceIntent(n, ctx) {
  const utilities = props.invoices.filter(i => i.type === 'WATER' || i.type === 'ELECTRIC')
  const pending = props.invoices.filter(i => i.status === 'pending')

  if (matchAny(n, ['eau', 'electricite', 'elec', 'conso'])) {
    return {
      text: `Services : ${utilities.length} facture(s). ${utilities.filter(i => i.status === 'pending').length} en attente.`,
      actions: [{ id: 'view-utilities', label: 'Voir les factures', iconSvg: 'chart', action: 'navigate_utilities' }],
      suggestions: ['Payer facture eau', 'Payer facture electricite'],
    }
  }

  const totalUnpaid = pending.reduce((s, i) => s + i.amount, 0)
  return {
    text: `${props.invoices.length} facture(s) au total. ${pending.length} en attente (${totalUnpaid.toLocaleString()} XAF).`,
    actions: pending.length > 0
      ? [{ id: 'view-invoices', label: 'Voir les factures en attente', iconSvg: 'doc', action: 'navigate_invoices' }]
      : [],
    suggestions: ['Payer un loyer', 'Voir les quittances'],
  }
}

function handleContractIntent(ctx) {
  const c = props.contract
  if (!c || !c.property) {
    return { text: 'Aucun contrat actif trouve.', suggestions: defaultSuggestions() }
  }
  return {
    text: `**${c.property.name}** - Bail ${c.type || 'en cours'}.`,
    type: 'table',
    rows: [
      { label: 'Loyer mensuel', value: `${c.rent?.toLocaleString() || '--'} XAF` },
      { label: 'Depot de garantie', value: `${c.deposit?.toLocaleString() || '--'} XAF` },
      { label: 'Charges', value: `${c.charges?.toLocaleString() || '--'} XAF` },
      { label: 'Debut', value: c.start_date || '--' },
      { label: 'Fin', value: c.end_date || '--' },
      { label: 'Propriete', value: c.property.name || '--' },
    ],
    actions: [{ id: 'view-contract', label: 'Voir le contrat complet', iconSvg: 'contract', action: 'navigate_contract' }],
    suggestions: ['Payer mon loyer', 'Voir mes documents', 'Contacter le gestionnaire'],
  }
}

function handleWalletIntent(ctx) {
  return {
    text: `Solde actuel : **${props.walletBalance?.toLocaleString() || 0} XAF**`,
    actions: [{ id: 'recharge', label: 'Recharger le portefeuille', iconSvg: 'card', action: 'navigate_recharge' }],
    suggestions: ['Payer un loyer', 'Voir mes transactions'],
  }
}

function handleSupportIntent(n) {
  if (matchAny(n, ['urgent', 'urgence', 'vite', 'rapide', 'tres urgent'])) {
    return {
      text: 'Pour une urgence technique, veuillez ouvrir un ticket immediatement. Un gestionnaire vous repondra dans les plus brefs delais.',
      actions: [{ id: 'new-ticket', label: 'Ouvrir un ticket urgent', iconSvg: 'alert', action: 'navigate_support' }],
      suggestions: ['Ouvrir un ticket', 'Contacter le gestionnaire'],
    }
  }
  return {
    text: 'La section Support permet d ouvrir des tickets et de communiquer avec le gestionnaire. Que voulez-vous faire ?',
    actions: [{ id: 'open-support', label: 'Aller au support', iconSvg: 'chat', action: 'navigate_support' }],
    suggestions: ['Ouvrir un ticket', 'Voir mes tickets', 'Contacter le proprietaire'],
  }
}

function handleNavigateIntent(n) {
  const tabMap = [
    { keys: ['accueil', 'tableau de bord', 'overview', 'resume', 'apercu', 'dashboard'], tab: 'overview', label: "Vue d'ensemble" },
    { keys: ['contrat', 'bail', 'propriete'], tab: 'contract', label: 'Contrat' },
    { keys: ['loyer', 'paiement', 'payer', 'finances'], tab: 'rent', label: 'Loyers' },
    { keys: ['support', 'ticket', 'message', 'messagerie', 'assistance', 'chat', 'contact'], tab: 'support', label: 'Support' },
    { keys: ['profil', 'parametre', 'compte', 'settings'], tab: 'profile', label: 'Profil' },
    { keys: ['facture', 'eau', 'electricite', 'utilite', 'utilities'], tab: 'utilities', label: 'Factures' },
    { keys: ['quittance', 'recu', 'recus', 'quittances', 'recep'], tab: 'receipts', label: 'Quittances' },
    { keys: ['ancien', 'historique', 'archive', 'anciens'], tab: 'old-contracts', label: 'Anciens contrats' },
  ]
  for (const entry of tabMap) {
    if (entry.keys.some(k => n.includes(k))) {
      return {
        text: `Navigation vers **${entry.label}**.`,
        actions: [{ id: `nav-${entry.tab}`, label: `Aller a ${entry.label}`, iconSvg: 'arrow', action: `navigate_${entry.tab}` }],
      }
    }
  }
  return {
    text: 'Sections disponibles : Vue d ensemble, Contrat, Loyer, Support, Profil, Factures, Quittances, Anciens contrats.',
    suggestions: ["Aller a l'accueil", 'Voir mon contrat', 'Payer mon loyer'],
  }
}

function handleProfileIntent(ctx) {
  return {
    text: `Profil : **${userName.value || '--'}** - ${props.user?.email || '--'}. Modifiable dans les parametres.`,
    actions: [{ id: 'view-profile', label: 'Modifier mon profil', iconSvg: 'user', action: 'navigate_profile' }],
    suggestions: ['Changer mot de passe', 'Voir notifications', 'Voir mon contrat'],
  }
}

function handleNotificationIntent() {
  return {
    text: 'Configurez vos notifications dans Profil > Preferences de notifications. Options : email, push navigateur, SMS.',
    actions: [{ id: 'view-notif', label: 'Configurer les notifications', iconSvg: 'bell', action: 'navigate_notifications' }],
    suggestions: defaultSuggestions(),
  }
}

function handleUtilityIntent(n, ctx) {
  const utils = props.invoices.filter(i => i.type === 'WATER' || i.type === 'ELECTRIC')
  const water = utils.filter(i => i.type === 'WATER')
  const elec = utils.filter(i => i.type === 'ELECTRIC')
  return {
    text: `Factures de services : ${water.length} eau, ${elec.length} electricite. ${utils.filter(i => i.status === 'pending').length} en attente.`,
    actions: [{ id: 'view-utils', label: 'Voir les factures', iconSvg: 'chart', action: 'navigate_utilities' }],
    suggestions: ['Payer facture eau', 'Payer facture electricite'],
  }
}

function handleCommandIntent(n, ctx) {
  if (matchAny(n, ['paie', 'paye', 'paiement', 'regle', 'reglement'])) {
    return handleDirectPayment(ctx)
  }
  if (matchAny(n, ['navig', 'va', 'ouvre', 'montre', 'affiche'])) {
    return handleNavigateIntent(n)
  }
  if (matchAny(n, ['notifi', 'alerte', 'previens', 'rappelle'])) {
    return handleNotificationIntent()
  }
  return {
    text: 'Commande non reconnue. Que souhaitez-vous que j execute ?',
    suggestions: ['Payer mon loyer', 'Aller au support', 'Voir mes factures'],
  }
}

function handleAffirmative() {
  if (lastIntent.value) return processIntent(lastIntent.value)
  return { text: 'Que souhaitez-vous faire ?', suggestions: defaultSuggestions() }
}

function handleUnknown() {
  return {
    text: "Je n ai pas bien compris votre demande. Voici ce que je peux faire :",
    suggestions: [
      'Payer mon loyer',
      'Situation des penalites',
      'Mon contrat',
      'Solde du portefeuille',
      'Contacter le support',
    ]
  }
}

function defaultSuggestions() {
  return ['Payer mon loyer', 'Mes factures', 'Mon contrat', 'Aide']
}

// ── Actions ──
function executeAction(actionId) {
  emit('action', actionId)
  isOpen.value = false
}

// ── Send / Receive ──
function sendMessage() {
  const text = userInput.value.trim()
  if (!text || isTyping.value) return
  userInput.value = ''
  suggestions.value = []
  addMessage('user', text)
  lastIntent.value = text
  isTyping.value = true
  scrollDown()

  const response = processIntent(text)

  setTimeout(() => {
    isTyping.value = false
    if (response.text) addMessage('assistant', response.text, response.type || 'text', response.rows || null, response.actions || null, response.html || null)
    if (response.actions && response.actions.length > 0) {
      setTimeout(() => addActionCards(response.actions), 100)
    }
    if (response.suggestions) suggestions.value = response.suggestions
    scrollDown()
  }, 500 + Math.random() * 400)
}

function sendUserMessage(text) {
  userInput.value = text
  sendMessage()
}

function addMessage(role, text, type = 'text', rows = null, actions = null, html = null) {
  const time = new Date().toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })
  if (type === 'action' && actions) {
    for (const a of actions) {
      messages.value.push({ role, type: 'action', action: a.action, actionLabel: a.label, actionIcon: a.iconSvg || '', time })
    }
    return
  }
  if (type === 'table' && rows) {
    messages.value.push({ role, type: 'table', text: '', rows, time })
    if (text) messages.value.push({ role, type: 'text', text, time })
    return
  }
  messages.value.push({ role, type: 'text', text, time })
}

function addActionCards(actions) {
  const time = new Date().toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })
  for (const a of actions) {
    messages.value.push({ role: 'assistant', type: 'action', action: a.action, actionLabel: a.label, actionIcon: a.iconSvg || '', time })
  }
  scrollDown()
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
            const greeting = handleGreeting(getContext())
            addMessage('assistant', greeting.text)
            if (greeting.suggestions) suggestions.value = greeting.suggestions
            scrollDown()
          }, 800)
        }, 300)
      }
    })
  }
}

function scrollDown() {
  nextTick(() => { if (messagesRef.value) messagesRef.value.scrollTop = messagesRef.value.scrollHeight })
}

watch(isOpen, (val) => { if (val) document.body.style.overflow = 'hidden'; else document.body.style.overflow = '' })

onMounted(() => {
  if (typeof window !== 'undefined' && 'Notification' in window && Notification.permission === 'granted') {
    const info = props.penaltyInfo
    if (info.isOverdue) {
      setTimeout(() => {
        addMessage('assistant', `**Rappel :** ${info.unpaidCount} mois impayes. Penalite ${info.currentLabel} appliquee. Total du : ${info.totalDue.toLocaleString()} XAF.`)
      }, 1500)
    }
  }
})
</script>

<style scoped>
/* ── Floating FAB ── */
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

/* ── Panel ── */
.ai-panel {
  position: fixed; bottom: 96px; right: 28px;
  width: 400px; height: 580px; max-height: calc(100vh - 140px);
  border-radius: 20px; background: var(--bg-card, #fff);
  box-shadow: 0 32px 80px rgba(0,0,0,0.2), 0 0 0 1px rgba(255,255,255,0.05);
  display: flex; flex-direction: column; z-index: 9998;
  overflow: hidden;
}
.dark-theme .ai-panel { background: #1E293B; box-shadow: 0 32px 80px rgba(0,0,0,0.5); }

/* ── Header ── */
.ai-header {
  display: flex; align-items: center; justify-content: space-between;
  padding: 16px 20px; border-bottom: 1px solid var(--border-input, #E2E8F0);
  flex-shrink: 0;
}
.ai-header-left { display: flex; align-items: center; gap: 10px; }
.ai-avatar {
  width: 36px; height: 36px; border-radius: 50%;
  background: linear-gradient(135deg, #6366F1, #4F46E5);
  display: flex; align-items: center; justify-content: center; color: white;
}
.ai-name { font-size: 14px; font-weight: 700; color: var(--text-title, #0F172A); }
.ai-status { font-size: 11px; color: #059669; font-weight: 500; }
.ai-minimize {
  width: 32px; height: 32px; border-radius: 50%; border: none;
  background: transparent; color: var(--text-muted, #94A3B8); cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: background 0.2s;
}
.ai-minimize:hover { background: var(--gray-100, #F1F5F9); }

/* ── Messages ── */
.ai-messages {
  flex: 1; overflow-y: auto; padding: 16px 16px 8px;
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

/* ── Typing ── */
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

/* ── Action cards ── */
.ai-action-msg { padding: 0; }
.ai-action-card {
  display: flex; align-items: center; gap: 10px; padding: 10px 12px;
  background: var(--bg-card, #fff); border-radius: 12px;
  cursor: pointer; transition: all 0.2s ease;
  border: 1px solid var(--border-input, #E2E8F0);
  box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}
.ai-action-card:hover { transform: translateX(3px); border-color: #6366F1; box-shadow: 0 4px 16px rgba(99,102,241,0.12); }
.dark-theme .ai-action-card { background: #1E293B; border-color: #334155; }
.ai-action-icon { font-size: 20px; width: 32px; text-align: center; }
.ai-action-label { font-size: 13px; font-weight: 700; color: var(--text-title, #0F172A); }
.ai-action-desc { font-size: 11px; color: var(--text-muted, #94A3B8); }
.ai-action-arrow { flex-shrink: 0; color: #6366F1; }

/* ── Table rows ── */
.ai-table-msg { display: flex; flex-direction: column; gap: 4px; }
.ai-table-row { display: flex; justify-content: space-between; gap: 12px; padding: 3px 0; }
.ai-table-label { font-size: 12px; color: var(--text-muted, #64748B); }
.ai-table-value { font-size: 13px; font-weight: 700; }

/* ── Suggestions ── */
.ai-suggestions {
  display: flex; flex-wrap: wrap; gap: 6px; padding: 6px 16px 4px; flex-shrink: 0;
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

/* ── Input ── */
.ai-input-area {
  display: flex; align-items: center; gap: 8px;
  padding: 12px 16px 16px; border-top: 1px solid var(--border-input, #E2E8F0);
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

/* ── Transition ── */
.ai-slide-enter-active { transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1); }
.ai-slide-leave-active { transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1); }
.ai-slide-enter-from { opacity: 0; transform: translateY(20px) scale(0.95); }
.ai-slide-leave-to { opacity: 0; transform: translateY(12px) scale(0.95); }

@keyframes aiFadeIn {
  from { opacity: 0; transform: translateY(6px); }
  to { opacity: 1; transform: translateY(0); }
}

/* ── Responsive ── */
@media (max-width: 480px) {
  .ai-panel { right: 12px; left: 12px; width: auto; height: calc(100vh - 140px); }
  .ai-fab { right: 16px; bottom: 16px; width: 50px; height: 50px; }
}
</style>
