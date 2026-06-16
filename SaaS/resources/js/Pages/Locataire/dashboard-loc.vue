<template>
  <div class="dashboard-root" :class="[theme === 'dark' ? 'dark-theme' : '', sidebarCollapsed ? 'sidebar-collapsed' : '', mobileSidebarOpen ? 'mobile-open' : '']">

    <!-- ══ INERTIA PROGRESS BAR ══ -->
    <div class="progress-bar" :class="{ active: isLoading }">
      <div class="progress-fill" :style="{ width: progressWidth + '%' }"></div>
    </div>

    <!-- ══ AMBIENT BACKGROUND ══ -->
    <div class="ambient-bg">
      <div class="orb orb-1"></div>
      <div class="orb orb-2"></div>
      <div class="orb orb-3"></div>
    </div>

    <!-- ══ MOBILE OVERLAY ══ -->
    <div v-if="mobileSidebarOpen" class="mobile-overlay" @click="mobileSidebarOpen = false"></div>

    <!-- ══════════════════════════════════════════════════
         SIDEBAR
    ══════════════════════════════════════════════════ -->
    <aside class="sidebar">
      <div class="sidebar-header">
        <div class="brand-logo">
          <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
            <rect width="32" height="32" rx="10" fill="url(#grad1)"/>
            <path d="M16 7L25 13V19L16 25L7 19V13L16 7Z" fill="white" opacity="0.9"/>
            <path d="M16 11L21 14V20L16 23L11 20V14L16 11Z" fill="url(#grad1)" opacity="0.6"/>
            <defs>
              <linearGradient id="grad1" x1="0" y1="0" x2="32" y2="32">
                <stop offset="0%" stop-color="#2563EB"/>
                <stop offset="100%" stop-color="#1E40AF"/>
              </linearGradient>
            </defs>
          </svg>
          <Transition name="fade-slide">
            <span v-if="!sidebarCollapsed" class="brand-name">Habitatum</span>
                </Transition>
              </div>
        <button class="collapse-btn" @click="sidebarCollapsed = !sidebarCollapsed">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M15 18l-6-6 6-6" v-if="!sidebarCollapsed"/>
            <path d="M9 18l6-6-6-6" v-else/>
          </svg>
        </button>
      </div>

      <!-- Tenant Quick Info -->
      <Transition name="fade-slide">
        <div v-if="!sidebarCollapsed" class="tenant-quick-info">
          <div class="tenant-avatar-wrap">
            <img :src="profileForm.avatar || defaultAvatar" :alt="auth.user.name" class="tenant-avatar"/>
            <span class="status-dot"></span>
          </div>
          <div class="tenant-meta">
            <p class="tenant-name">{{ profileForm.first_name }} {{ profileForm.last_name }}</p>
            <p class="tenant-role">Locataire</p>
          </div>
        </div>
      </Transition>

      <!-- 💳 SIDEBAR WALLET COMPACT -->
      <Transition name="fade-slide">
        <div v-if="!sidebarCollapsed" class="sidebar-wallet-card">
          <div class="wallet-card-header">
            <span class="wallet-title">Solde Wallet</span>
            <span class="wallet-badge" @click="toggleBalanceVisibility" style="cursor:pointer">
              {{ hideBalance ? 'Afficher' : 'Masquer' }}
            </span>
          </div>
          <p class="wallet-balance-val" :class="{ 'balance-flash': balanceFlash }">
            {{ hideBalance ? '••••••' : formatCurrency(walletBalance) }}
          </p>
          <button class="wallet-recharge-btn" @click="openRechargeModal">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Recharger
          </button>
        </div>
        <div v-else class="sidebar-wallet-collapsed" @click="openRechargeModal" title="Recharger">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
        </div>
      </Transition>

      <!-- Navigation -->
      <nav class="sidebar-nav">
        <button
          v-for="item in navItems"
          :key="item.id"
          class="nav-item"
          :class="{ active: activeTab === item.id }"
          @click="switchTab(item.id)"
          :title="sidebarCollapsed ? item.label : ''"
        >
          <span class="nav-icon" v-html="item.icon"></span>
          <Transition name="fade-slide">
            <span v-if="!sidebarCollapsed" class="nav-label">{{ item.label }}</span>
          </Transition>
          <Transition name="fade-slide">
            <span v-if="!sidebarCollapsed && item.badge" class="nav-badge">{{ item.badge }}</span>
          </Transition>
        </button>
      </nav>

      <!-- Bottom actions & theme toggle -->
      <div class="sidebar-footer">
        <button class="nav-item theme-switch-item" @click="toggleTheme">
          <span class="nav-icon">
            <svg v-if="theme === 'light'" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
            <svg v-else width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
          </span>
          <Transition name="fade-slide">
            <span v-if="!sidebarCollapsed" class="nav-label">{{ theme === 'light' ? 'Mode Sombre' : 'Mode Clair' }}</span>
          </Transition>
        </button>
        <button class="nav-item logout-btn" @click="handleLogout" :title="sidebarCollapsed ? t('common.logout') : ''">
          <span class="nav-icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>
          </span>
          <Transition name="fade-slide">
            <span v-if="!sidebarCollapsed" class="nav-label">{{ t('common.logout') }}</span>
          </Transition>
        </button>
      </div>
    </aside>

    <!-- ══════════════════════════════════════════════════
         MAIN CONTENT
    ══════════════════════════════════════════════════ -->
    <main class="main-content">
      <!-- Desktop header -->
      <header class="desktop-header">
        <div class="desktop-header-left">
          <h2 class="desktop-page-label">{{ currentNavLabel }}</h2>
        </div>
        <div class="desktop-header-right">
          <button class="desktop-icon-btn" @click="showReceiptsPreview = true" title="Tous les reçus">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
            <span>Reçus</span>
          </button>
          <button class="desktop-icon-btn" @click="toggleTheme" title="Thème">
            <svg v-if="theme === 'light'" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
            <svg v-else width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/></svg>
            <span>{{ theme === 'light' ? 'Sombre' : 'Clair' }}</span>
          </button>
          <div class="desktop-user-badge" @click="switchTab('profile')">
            <img :src="profileForm.avatar || defaultAvatar" :alt="auth.user.name" class="desktop-user-avatar"/>
            <div class="desktop-user-info">
              <span class="desktop-user-name">{{ profileForm.first_name }} {{ profileForm.last_name }}</span>
              <span class="desktop-user-role">{{ t('common.tenant') }}</span>
            </div>
          </div>
        </div>
      </header>

      <!-- Mobile header -->
      <header class="mobile-header">
        <button class="burger-btn" @click="mobileSidebarOpen = true">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><line x1="4" y1="7" x2="20" y2="7"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="17" x2="20" y2="17"/></svg>
        </button>
        <span class="mobile-title">{{ currentNavLabel }}</span>
        <div class="mobile-avatar">
          <img :src="profileForm.avatar || defaultAvatar" :alt="auth.user.name"/>
        </div>
      </header>

      <!-- Page Content Layout -->
      <Transition name="page-transition" mode="out-in">
        <div :key="activeTab" class="page-panel" :class="{ 'lang-switching': switchingLang }">

          <!-- ═══════════════════════════════
               A. VUE D'ENSEMBLE
          ═══════════════════════════════ -->
          <section v-if="activeTab === 'overview'" class="tab-section anim-section">
            <div class="page-header anim-fade-up">
              <div>
                <h1 class="page-title">{{ t('overview.title') }}</h1>
                <p class="page-subtitle">{{ t('overview.greeting', { name: profileForm.first_name }) }}</p>
              </div>
              <div class="header-date">
                <span>{{ formattedToday }}</span>
              </div>
            </div>

            <!-- KPI Cards Grid -->
            <div class="kpi-grid">
              <div class="glass-card kpi-card kpi-loyer">
                <div class="kp-glow"></div>
                <div class="kp-noise"></div>
                <div class="kp-icon-wrap">
                  <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="18" height="18" rx="3"/><path d="M3 9h18M9 21V9"/></svg>
                </div>
                <div class="kp-body">
                  <p class="kp-label">Loyer mensuel</p>
                  <p class="kp-value">{{ formatCurrency(currentRent) }}</p>
                  <span class="kp-badge" :class="'badge-' + currentRentStatus">
                    <span class="kp-badge-dot"></span>
                    {{ rentStatusLabel(currentRentStatus) }}
                  </span>
                </div>
              </div>

              <!-- 💳 WALLET KPI CARD -->
              <div class="glass-card kpi-card kpi-wallet">
                <div class="kp-glow"></div>
                <div class="kp-noise"></div>
                <div class="kp-icon-wrap kpi-icon-blue">
                  <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                </div>
                <div class="kp-body">
                  <p class="kp-label">Mon Portefeuille</p>
                  <p class="kp-value kp-value-wallet">{{ hideBalance ? '••••••' : formatCurrency(walletBalance) }}</p>
                  <div class="wallet-kpi-actions">
                    <button class="wallet-inline-btn" @click="openRechargeModal">Recharger</button>
                    <button class="wallet-inline-btn secondary" @click="toggleBalanceVisibility">{{ hideBalance ? 'Afficher' : 'Masquer' }}</button>
                    <button class="wallet-inline-btn secondary" @click="showTransferForm = !showTransferForm">
                      {{ showTransferForm ? 'Annuler' : 'Transférer' }}
                    </button>
                  </div>
                </div>
              </div>

              <div class="glass-card kpi-card kpi-due">
                <div class="kp-glow"></div>
                <div class="kp-noise"></div>
                <div class="kp-icon-wrap kpi-icon-amber">
                  <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
                </div>
                <div class="kp-body">
                  <p class="kp-label">Solde dû total</p>
                  <p class="kp-value" :class="{ 'kp-value-danger': totalDue > 0 }">{{ formatCurrency(totalDue) }}</p>
                  <span class="kp-hint" :class="{ 'kp-hint-ok': totalDue === 0, 'kp-hint-warn': totalDue > 0 }">{{ totalDue === 0 ? 'À jour' : 'Règlement requis' }}</span>
                </div>
              </div>

              <div class="glass-card kpi-card kpi-tickets">
                <div class="kp-glow"></div>
                <div class="kp-noise"></div>
                <div class="kp-icon-wrap kpi-icon-violet">
                  <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                </div>
                <div class="kp-body">
                  <p class="kp-label">Tickets de support</p>
                  <p class="kp-value" :class="{ 'kp-value-warning': openTicketsCount > 0 }">{{ openTicketsCount }}</p>
                  <span class="kp-hint" :class="{ 'kp-hint-ok': openTicketsCount === 0, 'kp-hint-warn': openTicketsCount > 0 }">{{ openTicketsCount === 0 ? 'Aucun incident' : 'Incidents en cours' }}</span>
                </div>
              </div>
            </div>

            <!-- Transfer to Landlord simulation widget -->
            <Transition name="fade-slide">
              <div v-if="showTransferForm" class="glass-card transfer-widget-card">
                <div class="tw-header">
                  <div class="tw-header-left">
                    <div class="tw-icon">
                      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="17 1 21 5 17 9"/><path d="M3 11V9a4 4 0 014-4h14"/><polyline points="7 23 3 19 7 15"/><path d="M21 13v2a4 4 0 01-4 4H3"/></svg>
                    </div>
                    <div>
                      <h3 class="tw-title">Transférer des fonds</h3>
                      <p class="tw-sub">Virement instantané vers le compte du bailleur</p>
                    </div>
                  </div>
                  <button class="tw-close" @click="showTransferForm = false">×</button>
                </div>
                <div class="tw-body">
                  <div class="tw-field">
                    <label class="tw-label">Montant du virement</label>
                    <div class="tw-input-group">
                      <span class="tw-prefix">XAF</span>
                      <input type="number" class="tw-input" placeholder="Ex: 25 000" v-model="transferAmount"/>
                    </div>
                  </div>
                  <div class="tw-field">
                    <label class="tw-label">Motif du virement</label>
                    <input type="text" class="tw-input" placeholder="Ex: Paiement caution eau" v-model="transferMemo"/>
                  </div>
                  <button class="tw-btn" @click="executeWalletTransfer" :disabled="!transferAmount || transferAmount <= 0">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    Valider le virement
                  </button>
                </div>
              </div>
            </Transition>

            <!-- Quick Actions & Recent Transactions -->
            <div class="overview-lower-grid anim-stagger">
              <div class="glass-card quick-actions-card">
                <h3 class="card-title">Actions Rapides</h3>
                <div class="quick-actions-list">
                  <button class="quick-action-btn primary" @click="switchTab('loyer')">
                    <span class="qa-icon">
                      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                    </span>
                    <span class="qa-text">
                      <strong>Payer mon loyer</strong>
                      <small>Sélection mensuelle consécutive</small>
                    </span>
                  </button>
                  <button class="quick-action-btn" @click="switchTab('support'); openNewTicket()">
                    <span class="qa-icon amber">
                      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    </span>
                    <span class="qa-text">
                      <strong>Signaler une panne</strong>
                      <small>Ouvrir un ticket d'incident</small>
                    </span>
                  </button>
                </div>
              </div>

              <!-- Wallet transactions summary -->
              <div class="glass-card wallet-tx-card">
                <h3 class="card-title">Mouvements de fonds récents</h3>
                <div class="wallet-tx-list">
                    <div v-for="tx in transactions.slice(0, 3)" :key="tx.id" class="tx-item">
                    <div class="tx-icon" :class="tx.type">
                      <svg v-if="tx.type === 'recharge'" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><polyline points="19 12 12 19 5 12"/></svg>
                      <svg v-else width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg>
                    </div>
                    <div class="tx-info">
                      <p class="tx-name">{{ tx.type === 'recharge' ? 'Crédit Portefeuille' : tx.description }}</p>
                      <p class="tx-meta">{{ formatDateTime(tx.date) }} • {{ tx.method }}</p>
                    </div>
                    <p class="tx-amount" :class="tx.type">
                      {{ tx.type === 'recharge' ? '+' : '-' }}{{ formatCurrency(tx.amount) }}
                    </p>
                  </div>
                  <div v-if="transactions.length === 0" class="tx-empty">Aucune transaction répertoriée.</div>
                </div>
              </div>
            </div>
          </section>

          <!-- ═══════════════════════════════
               B. LOGEMENTS & CONTRATS
          ═══════════════════════════════ -->
          <section v-else-if="activeTab === 'contract'" class="tab-section anim-section">
            <div class="page-header anim-fade-up">
              <div>
                <h1 class="page-title">Logements & Contrats de Bail</h1>
                <p class="page-subtitle">Détails de votre occupation et documents associés</p>
              </div>
            </div>

            <div v-for="contract in contracts" :key="contract.id" class="contract-block">
              <!-- Property Card -->
              <div class="glass-card property-card">
                <div class="property-image-wrap">
                  <div class="premium-image-mockup">
                    <svg viewBox="0 0 400 250" fill="none" class="w-full h-full object-cover">
                      <rect width="400" height="250" fill="url(#apartmentGrad)" />
                      <circle cx="200" cy="125" r="80" fill="white" opacity="0.05" />
                      <path d="M120 200 L120 120 L280 120 L280 200 Z" fill="white" opacity="0.15" stroke="white" stroke-width="2" />
                      <path d="M110 120 L200 60 L290 120 Z" fill="white" opacity="0.25" stroke="white" stroke-width="2" />
                      <defs>
                        <linearGradient id="apartmentGrad" x1="0" y1="0" x2="400" y2="250">
                          <stop offset="0%" stop-color="#1E3A8A"/>
                          <stop offset="100%" stop-color="#3B82F6"/>
                        </linearGradient>
                      </defs>
                    </svg>
                    <div class="property-image-overlay">
                      <span class="property-type-badge">{{ contract.property.type }}</span>
                    </div>
                  </div>
                </div>
                <div class="property-details">
                    <div class="property-header">
                    <div>
                      <h2 class="property-name">{{ contract.property.name }}</h2>
                      <p class="property-address">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 1118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        {{ contract.property.address }}
                      </p>
                    </div>
                    <div class="property-status-badge active">
                      <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><circle cx="12" cy="12" r="6"/></svg>
                      Occupé
                    </div>
                  </div>

                  <div class="property-specs-grid">
                    <div class="spec-item" v-for="(spec, sIdx) in contract.property.specs" :key="spec.label">
                      <div class="spec-capsule">
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
                        <span class="spec-label">{{ spec.label }}</span>
                      </div>
                      <span class="spec-value">{{ spec.value }}</span>
                    </div>
                  </div>

                  <div class="equipment-section">
                    <div class="section-subtitle-row">
                      <div class="eq-icon-ring">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                      </div>
                      <h4 class="section-subtitle">Équipements inclus</h4>
                    </div>
                    <div class="equipment-tags">
                      <span v-for="eq in contract.property.equipment" :key="eq" class="eq-tag">{{ eq }}</span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Bail Details -->
              <div class="bail-details-grid">
                <div class="glass-card bail-info-card">
                  <h3 class="card-title">Détails du bail actif</h3>
                  <div class="bail-rows">
                    <div class="bail-row">
                      <div class="bail-icon-ring bail-icon-blue">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                      </div>
                      <span class="bail-key">Type de contrat</span>
                      <span class="bail-value">{{ contract.type }}</span>
                    </div>
                    <div class="bail-row">
                      <div class="bail-icon-ring bail-icon-emerald">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                      </div>
                      <span class="bail-key">Date de début</span>
                      <span class="bail-value">{{ formatDate(contract.start_date) }}</span>
                    </div>
                    <div class="bail-row">
                      <div class="bail-icon-ring bail-icon-red">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                      </div>
                      <span class="bail-key">Date de fin</span>
                      <span class="bail-value">{{ formatDate(contract.end_date) }}</span>
                    </div>
                    <div class="bail-row">
                      <div class="bail-icon-ring bail-icon-blue">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                      </div>
                      <span class="bail-key">Loyer mensuel</span>
                      <span class="bail-value accent-blue">{{ formatCurrency(contract.rent) }}</span>
                    </div>
                    <div class="bail-row">
                      <div class="bail-icon-ring bail-icon-amber">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                      </div>
                      <span class="bail-key">Dépôt de garantie</span>
                      <span class="bail-value">{{ formatCurrency(contract.deposit) }}</span>
                    </div>
                  </div>
                </div>

                <!-- Documents Section -->
                <div class="glass-card documents-card">
                  <h3 class="card-title">Documents annexes</h3>
                  <p class="card-subtitle-text">Justificatifs fournis au dossier de location</p>

                  <div class="documents-list">
                    <div v-for="doc in contract.documents" :key="doc.id" class="document-row">
                      <div class="doc-icon-wrap" :class="doc.status === 'uploaded' ? 'doc-uploaded' : 'doc-missing'">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                      </div>
                      <div class="doc-info">
                        <p class="doc-name">{{ doc.name }}</p>
                        <p class="doc-meta" v-if="doc.status === 'uploaded'">{{ doc.filename }}</p>
                        <p class="doc-meta missing" v-else>Pièce manquante</p>
                      </div>
                      <div class="doc-actions">
                        <a v-if="doc.status === 'uploaded'" :href="doc.url" target="_blank" class="doc-btn view">Voir</a>
                        <button class="doc-btn upload" @click="triggerDocUpload(contract.id, doc.id)">Déposer</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- 💰 CONTRACT FEE PAYMENT & RENEWAL RECEIPT -->
            <div class="glass-card contract-fee-card">
              <div class="contract-fee-row">
                <div class="contract-fee-left">
                  <div class="contract-fee-icon-ring">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                  </div>
                </div>
                <div class="contract-fee-info">
                  <h3 class="card-title">Frais de contrat & Renouvellement</h3>
                  <p class="card-subtitle-text">Frais de dossier, renouvellement de bail et édition de quittance de versement.</p>
                </div>
                <div class="contract-fee-status">
                  <span class="status-pill" :class="contractFeePaid ? 'status-paid' : 'status-pending'">
                    {{ contractFeePaid ? 'Frais Réglés' : '50 000 XAF à régler' }}
                  </span>
                </div>
              </div>
              <div v-if="!contractFeePaid" class="contract-fee-actions">
                <button class="btn-primary" @click="payContractFee" :disabled="walletBalance < 50000">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                  Payer frais de contrat (50 000 XAF)
                </button>
                <button class="btn-secondary" @click="generateRenewalReceipt">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                  Reçu de renouvellement
                </button>
              </div>
              <div v-else class="contract-fee-paid-msg">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                <span>Frais de contrat réglés — <button class="link-btn" @click="generateRenewalReceipt">Télécharger le reçu</button></span>
              </div>
            </div>

            <!-- ═══════════════════════════════════════════
                 CONTRACT FEE CONFIRMATION MODAL
                 (Shared overlay with recap → PIN → success cards)
            ═══════════════════════════════════════════ -->
            <Transition name="fade">
              <div v-if="showContractFeeConfirmModal" class="payment-modal-overlay" @click.self="paymentFlowStep !== 'success' && cancelPaymentFlow()">
                <div class="card-switch-stage">
                  <Transition name="card-switch" mode="out-in">
                    <div v-if="paymentFlowStep === 'recap' || paymentFlowStep === 'idle'" key="recap" class="payment-modal-card glass-card">
                      <div class="payment-modal-header">
                        <div class="pm-header-icon" style="background:linear-gradient(135deg,#FEF3C7,#FDE68A);color:#D97706">
                          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                        </div>
                        <div>
                          <h3 class="pm-title">Frais de contrat</h3>
                          <p class="pm-sub">Confirmez le règlement des frais de dossier</p>
                        </div>
                        <button class="pm-close" @click="cancelPaymentFlow">&times;</button>
                      </div>
                      <div class="payment-modal-body">
                        <div class="pm-section-title">
                          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                          Résumé de l'opération
                        </div>
                        <div class="pm-invoice-detail">
                          <div class="pm-invoice-row"><span>Libellé</span><strong>Frais de contrat & Renouvellement</strong></div>
                          <div class="pm-invoice-row"><span>Période</span><strong>{{ new Date().getFullYear() }}</strong></div>
                          <div class="pm-invoice-row"><span>Bailleur</span><strong>SCI Habitats SA</strong></div>
                        </div>
                        <div class="pm-divider"></div>
                        <div class="pm-summary">
                          <div class="pm-summary-row pm-total">
                            <span class="pms-label">Montant à payer</span>
                            <span class="pms-value total" style="color:#D97706">50 000 XAF</span>
                          </div>
                        </div>
                        <div class="pm-wallet-info">
                          <div class="pm-wi-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                          </div>
                          <div class="pm-wi-details">
                            <span class="pm-wi-label">Paiement depuis</span>
                            <span class="pm-wi-value">Portefeuille HABITATUM</span>
                            <span class="pm-wi-balance">Solde : {{ hideBalance ? '••••••' : formatCurrency(walletBalance) }}</span>
                          </div>
                          <div class="pm-wi-check">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                          </div>
                        </div>
                      </div>
                      <div class="payment-modal-footer">
                        <button class="btn-secondary" @click="cancelPaymentFlow">Annuler</button>
                        <button class="btn-primary pm-pay-btn" @click="confirmContractFeePayment" style="background:linear-gradient(135deg,#D97706,#B45309);box-shadow:0 4px 16px rgba(217,119,6,0.25)">
                          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                          Confirmer le paiement
                        </button>
                      </div>
                    </div>
                    <div v-else-if="paymentFlowStep === 'pin'" key="pin" class="rent-pin-modal" @click="focusRentPinInput">
                      <div class="rent-pin-bg-ornament"></div>
                      <button class="rent-pin-close" @click="cancelPaymentFlow">&times;</button>
                      <div class="rent-pin-icon-wrap">
                        <div class="rent-pin-icon-ring">
                          <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0110 0v4"/>
                            <circle cx="12" cy="16" r="1.5"/>
                          </svg>
                        </div>
                      </div>
                      <h3 class="rent-pin-title">Code de sécurité</h3>
                      <p class="rent-pin-sub">Saisissez votre code secret pour autoriser le paiement de <strong>{{ formatCurrency(premiumPinAmount) }}</strong></p>
                      <div class="rent-pin-dots" :class="{ ripple: pinDotsRipple }" @click.stop="onPinDotsClick">
                        <span v-for="i in 4" :key="i" class="rent-pin-dot" :class="{ filled: rentWalletPinInput.length >= i }">
                          <span v-if="rentWalletPinInput.length >= i" class="rent-pin-dot-fill"></span>
                        </span>
                      </div>
                      <div class="rent-pin-input-row">
                        <input ref="rentPinInputEl" type="password" v-model="rentWalletPinInput" maxlength="4"
                          class="rent-pin-input" autofocus
                          @input="onRentPinInput"
                          @keyup.enter="submitRentWalletPin"/>
                      </div>
                      <Transition name="fade">
                        <div v-if="rentWalletPinError" class="rent-pin-error">
                          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                          {{ rentWalletPinError }}
                        </div>
                      </Transition>
                      <div class="rent-pin-actions">
                        <button class="rent-pin-btn rent-pin-btn-secondary" @click="cancelPaymentFlow">Annuler</button>
                        <button class="rent-pin-btn rent-pin-btn-primary" @click="submitRentWalletPin" :disabled="rentWalletPinInput.length < 4">
                          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                          Valider
                        </button>
                      </div>
                    </div>
                    <div v-else-if="paymentFlowStep === 'success'" key="success" class="payment-success-card">
                      <div class="ps-pulse-ring" style="color:#D97706"></div>
                      <div class="ps-pulse-ring ps-pulse-2" style="color:#D97706"></div>
                      <div class="ps-pulse-ring ps-pulse-3" style="color:#D97706"></div>
                      <div class="ps-icon" style="background:linear-gradient(135deg,#FEF3C7,#FDE68A);color:#D97706">
                        <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                          <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                          <polyline points="14 2 14 8 20 8"/>
                          <line x1="8" y1="13" x2="16" y2="13"/>
                          <line x1="8" y1="17" x2="16" y2="17"/>
                        </svg>
                      </div>
                      <svg class="ps-particles ps-contract-confetti" width="160" height="160" viewBox="0 0 160 160">
                        <rect class="pc-c1" x="20" y="30" width="8" height="8" rx="2" fill="#D97706" transform="rotate(25 24 34)"/>
                        <rect class="pc-c2" x="125" y="20" width="6" height="6" rx="1.5" fill="#F59E0B" transform="rotate(-15 128 23)"/>
                        <rect class="pc-c3" x="25" y="115" width="7" height="7" rx="1.5" fill="#FBBF24" transform="rotate(45 28 118)"/>
                        <rect class="pc-c4" x="120" y="120" width="6" height="6" rx="1.5" fill="#D97706" transform="rotate(-30 123 123)"/>
                        <rect class="pc-c5" x="70" y="15" width="5" height="5" rx="1" fill="#F59E0B" transform="rotate(10 72 17)"/>
                        <rect class="pc-c6" x="65" y="135" width="6" height="6" rx="1.5" fill="#FBBF24" transform="rotate(60 68 138)"/>
                      </svg>
                      <h3 class="ps-title" style="color:#D97706">Frais réglés !</h3>
                      <p class="ps-sub">50 000 XAF débités — Reçu de versement disponible</p>
                    </div>
                  </Transition>
                </div>
              </div>
            </Transition>

            <!-- ═══════════════════════════════════════════
                 CONTRACT FEE RECEIPT CARD
            ═══════════════════════════════════════════ -->
            <Transition name="fade-slide">
              <div v-if="lastContractFeeReceipt" class="glass-card rent-receipt-card" style="border-color:#FDE68A">
                <div class="rent-receipt-header">
                  <div class="rr-header-left">
                    <div class="rr-icon-ring" style="background:linear-gradient(135deg,#FEF3C7,#FDE68A);color:#D97706">
                      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                    </div>
                    <div>
                      <h3 class="rr-title">{{ lastContractFeeReceipt.title }}</h3>
                      <p class="rr-date">Paiement du {{ formatDate(lastContractFeeReceipt.paid_at) }}</p>
                    </div>
                  </div>
                  <span class="rr-badge">Confirmé</span>
                </div>
                <div class="rent-receipt-body">
                  <div class="rr-meta-row">
                    <div class="rr-meta-item">
                      <span class="rrm-key">Référence</span>
                      <span class="rrm-val">{{ lastContractFeeReceipt.reference }}</span>
                    </div>
                    <div class="rr-meta-item">
                      <span class="rrm-key">Montant</span>
                      <span class="rrm-val">{{ formatCurrency(lastContractFeeReceipt.amount) }}</span>
                    </div>
                    <div class="rr-meta-item">
                      <span class="rrm-key">Transaction</span>
                      <span class="rrm-val mono">{{ lastContractFeeReceipt.transaction_id }}</span>
                    </div>
                  </div>
                </div>
                <div class="rent-receipt-footer">
                  <button class="btn-primary small" @click="generateReceiptPDF(lastContractFeeReceipt)">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                    Télécharger le reçu
                  </button>
                  <button class="btn-secondary small" @click="viewReceiptDetail(lastContractFeeReceipt)">Détails</button>
                  <button class="btn-secondary small" @click="lastContractFeeReceipt = null">Masquer</button>
                </div>
              </div>
            </Transition>
          </section>

          <!-- ═══════════════════════════════
               C. FINANCES
          ═══════════════════════════════ -->
          <section v-else-if="activeTab === 'loyer'" class="tab-section anim-section">
            <div class="page-header anim-fade-up">
              <div>
                <h1 class="page-title">Loyer & Finances</h1>
                <p class="page-subtitle">Réglez vos loyers successifs et factures d'eau/électricité en ligne.</p>
              </div>
            </div>

            <div class="finance-summary-row anim-stagger">
              <div class="glass-card fin-summary-card">
                <div class="fin-sum-icon-wrap sum-icon-emerald">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                </div>
                <div class="fin-sum-body">
                  <p class="fin-sum-label">Total réglé (12 mois)</p>
                  <p class="fin-sum-value emerald">{{ formatCurrency(totalPaid12Months) }}</p>
                </div>
              </div>
              <div class="glass-card fin-summary-card">
                <div class="fin-sum-icon-wrap sum-icon-blue">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                </div>
                <div class="fin-sum-body">
                  <p class="fin-sum-label">Mois loyer payés</p>
                  <p class="fin-sum-value blue">{{ paidRentsCount }} / {{ totalRentsCount }}</p>
                </div>
              </div>
              <div class="glass-card fin-summary-card">
                <div class="fin-sum-icon-wrap sum-icon-amber">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <div class="fin-sum-body">
                  <p class="fin-sum-label">Factures en attente</p>
                  <p class="fin-sum-value amber">{{ pendingInvoicesCount }}</p>
                </div>
              </div>
              <div class="glass-card fin-summary-card">
                <div class="fin-sum-icon-wrap sum-icon-emerald">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                </div>
                <div class="fin-sum-body">
                  <p class="fin-sum-label">Solde Portefeuille</p>
                  <p class="fin-sum-value emerald" style="cursor:pointer" @click="toggleBalanceVisibility" title="Cliquez pour afficher/masquer">{{ hideBalance ? '••••••' : formatCurrency(walletBalance) }}</p>
                </div>
              </div>
            </div>

            <!-- ⚠️ Penalty warning banner -->
            <div v-if="penaltyInfo.isOverdue && showPenaltyBanner" class="glass-card penalty-banner">
              <div class="penalty-banner-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
              </div>
              <div class="penalty-banner-body">
                <div class="penalty-banner-title">Pénalité de retard — {{ penaltyInfo.period }}</div>
                <div class="penalty-banner-details">
                  <span>Loyer : <strong>{{ formatCurrency(penaltyInfo.rentAmount) }}</strong></span>
                  <span class="penalty-sep">•</span>
                  <span>Pénalité <strong>{{ penaltyInfo.penaltyRate }}%</strong> : <strong class="text-red">{{ formatCurrency(penaltyInfo.penaltyAmount) }}</strong></span>
                  <span class="penalty-sep">•</span>
                  <span>Total dû : <strong class="text-indigo">{{ formatCurrency(penaltyInfo.totalDue) }}</strong></span>
                </div>
                <div class="penalty-banner-scale">
                  <span class="ps-label">Barème :</span>
                  <span class="ps-tier" :class="{ 'ps-active': penaltyInfo.day >= 11 }">J+11 → 5%</span>
                  <span class="ps-arrow">→</span>
                  <span class="ps-tier" :class="{ 'ps-active': penaltyInfo.day >= 16 }">J+16 → 10%</span>
                  <span class="ps-arrow">→</span>
                  <span class="ps-tier" :class="{ 'ps-active': penaltyInfo.day >= 21 }">J+21 → 15%</span>
                </div>
              </div>
              <button class="penalty-banner-close" @click="dismissPenaltyBanner">&times;</button>
            </div>

            <!-- 🗓️ CONSECUTIVE MONTH SELECTOR CARD (Professional & Realistic) -->
            <div class="glass-card rent-payment-selector-card">
              <h3 class="card-title">Sélectionneur de loyers successifs</h3>
              <p class="card-subtitle-text">
                Les mois sont affichés en fonction des dates de votre contrat ({{ formatDate(contracts[0].start_date) }} - {{ formatDate(contracts[0].end_date) }}).
                Vous devez obligatoirement régler les mois dans l'ordre chronologique.
              </p>
              
              <div v-for="[year, months] in rentMonthsByYear" :key="year" class="year-group">
                <div class="year-header">
                  <div class="year-badge">{{ year }}</div>
                  <div class="year-progress">
                    <span class="year-paid-count">{{ months.filter(m => m.status === 'paid').length }}/{{ months.length }}</span>
                    <span class="year-paid-label">payés</span>
                  </div>
                </div>
                <div class="month-selector-grid anim-stagger">
                  <div v-for="month in months" :key="month.key" 
                    class="mc-card" 
                    :class="[
                      'mc-' + month.status, 
                      { 'mc-selected': selectedMonthsKeys.includes(month.key) }
                    ]"
                    @click="toggleMonthSelection(month)">
                    <div class="mc-top">
                      <span class="mc-label">{{ month.label }}</span>
                      <span v-if="month.status === 'paid'" class="mc-badge mc-paid-badge">Payé</span>
                      <span v-else-if="selectedMonthsKeys.includes(month.key)" class="mc-badge mc-sel-badge">Sélectionné</span>
                      <span v-else-if="month.penaltyRate > 0" class="mc-badge mc-penalty-badge">+{{ month.penaltyRate }}%</span>
                      <span v-else class="mc-badge mc-unpaid-badge">À régler</span>
                    </div>
                    <div class="mc-amount-wrap">
                      <span class="mc-amount">{{ formatCurrency(month.amount) }}</span>
                      <span v-if="month.penaltyRate > 0" class="mc-penalty-amount">+{{ formatCurrency(month.penaltyAmount) }}</span>
                    </div>
                    <div class="mc-bar"></div>
                  </div>
                </div>
              </div>

              <!-- Selection summary -->
              <div class="rent-selection-summary" v-if="selectedMonthsKeys.length > 0">
                <div class="summary-details">
                  <p>Loyer(s) sélectionné(s) : <strong>{{ selectedMonthsNames }}</strong></p>
                  <p class="total-rent-to-pay">Montant total dû : <strong class="text-blue">{{ formatCurrency(totalPaymentAmount) }}</strong></p>
                </div>
                <div class="summary-action">
                  <div v-if="walletBalance >= totalPaymentAmount" class="payment-approved-box">
                    <span class="balance-hint">Solde suffisant ({{ hideBalance ? '••••••' : formatCurrency(walletBalance) }})</span>
                    <button class="btn-primary" @click="openPaymentConfirm" :disabled="processingPayment">
                      <span v-if="processingPayment" class="spinner-sm"></span>
                      <span v-else>Payer via Portefeuille</span>
                    </button>
                  </div>
                  <div v-else class="payment-denied-box">
                    <span class="balance-warning">Solde insuffisant (Manque {{ hideBalance ? '••••••' : formatCurrency(totalPaymentAmount - walletBalance) }})</span>
                    <button class="btn-primary btn-warning" @click="openRechargeModal">
                      Recharger le portefeuille
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- ═══════════════════════════════════════════
                 CONFIRMATION MODAL — Paiement des loyers
                 (Shared overlay with recap → PIN → success cards)
            ═══════════════════════════════════════════ -->
            <Transition name="fade">
              <div v-if="showPaymentConfirmModal" class="payment-modal-overlay" @click.self="paymentFlowStep !== 'success' && cancelPaymentFlow()">
                <div class="card-switch-stage">
                  <Transition name="card-switch" mode="out-in">
                    <div v-if="paymentFlowStep === 'recap' || paymentFlowStep === 'idle'" key="recap" class="payment-modal-card glass-card">
                      <div class="payment-modal-header">
                        <div class="pm-header-icon">
                          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                        </div>
                        <div>
                          <h3 class="pm-title">Confirmation de paiement</h3>
                          <p class="pm-sub">Vérifiez les détails avant de valider</p>
                        </div>
                        <button class="pm-close" @click="cancelPaymentFlow">&times;</button>
                      </div>
                      <div class="payment-modal-body">
                        <div class="pm-section">
                          <div class="pm-section-title">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            Mois sélectionnés
                          </div>
                          <div class="pm-months-list">
                            <div v-for="mk in selectedMonthsKeys" :key="mk" class="pm-month-row">
                              <div class="pm-month-dot"></div>
                              <span class="pm-month-label">{{ (rentMonths.find(m => m.key === mk) || {}).label }}</span>
                              <span class="pm-month-amount" style="display:flex;flex-direction:column;align-items:flex-end">
                                <span>{{ formatCurrency((rentMonths.find(m => m.key === mk) || {}).totalDue || 0) }}</span>
                                <span v-if="(rentMonths.find(m => m.key === mk) || {}).penaltyAmount > 0" class="pm-penalty-hint">
                                  dont +{{ (rentMonths.find(m => m.key === mk) || {}).penaltyRate }}% pénalité
                                </span>
                              </span>
                            </div>
                          </div>
                        </div>
                        <div class="pm-divider"></div>
                        <div class="pm-summary">
                          <div v-if="selectedPenaltyAmount > 0" class="pm-summary-row">
                            <span class="pms-label">Dont pénalités</span>
                            <span class="pms-value red">{{ formatCurrency(selectedPenaltyAmount) }}</span>
                          </div>
                          <div class="pm-summary-row">
                            <span class="pms-label">Frais</span>
                            <span class="pms-value green">Gratuit</span>
                          </div>
                          <div class="pm-summary-row pm-total">
                            <span class="pms-label">Montant à débiter</span>
                            <span class="pms-value total">{{ formatCurrency(totalPaymentAmount) }}</span>
                          </div>
                        </div>
                        <div class="pm-divider"></div>
                        <div class="pm-wallet-info">
                          <div class="pm-wi-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                          </div>
                          <div class="pm-wi-details">
                            <span class="pm-wi-label">Paiement depuis</span>
                            <span class="pm-wi-value">Portefeuille HABITATUM</span>
                            <span class="pm-wi-balance">Solde : {{ hideBalance ? '••••••' : formatCurrency(walletBalance) }}</span>
                          </div>
                          <div class="pm-wi-check">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                          </div>
                        </div>
                      </div>
                      <div class="payment-modal-footer">
                        <button class="btn-secondary" @click="cancelPaymentFlow">Annuler</button>
                        <button class="btn-primary pm-pay-btn" @click="confirmPaymentFromModal">
                          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                          Confirmer le paiement
                        </button>
                      </div>
                    </div>
                    <div v-else-if="paymentFlowStep === 'pin'" key="pin" class="rent-pin-modal" @click="focusRentPinInput">
                      <div class="rent-pin-bg-ornament"></div>
                      <button class="rent-pin-close" @click="cancelPaymentFlow">&times;</button>
                      <div class="rent-pin-icon-wrap">
                        <div class="rent-pin-icon-ring">
                          <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0110 0v4"/>
                            <circle cx="12" cy="16" r="1.5"/>
                          </svg>
                        </div>
                      </div>
                      <h3 class="rent-pin-title">Code de sécurité</h3>
                      <p class="rent-pin-sub">Saisissez votre code secret pour autoriser le paiement de <strong>{{ formatCurrency(premiumPinAmount) }}</strong></p>
                      <div class="rent-pin-dots" :class="{ ripple: pinDotsRipple }" @click.stop="onPinDotsClick">
                        <span v-for="i in 4" :key="i" class="rent-pin-dot" :class="{ filled: rentWalletPinInput.length >= i }">
                          <span v-if="rentWalletPinInput.length >= i" class="rent-pin-dot-fill"></span>
                        </span>
                      </div>
                      <div class="rent-pin-input-row">
                        <input ref="rentPinInputEl" type="password" v-model="rentWalletPinInput" maxlength="4"
                          class="rent-pin-input" autofocus
                          @input="onRentPinInput"
                          @keyup.enter="submitRentWalletPin"/>
                      </div>
                      <Transition name="fade">
                        <div v-if="rentWalletPinError" class="rent-pin-error">
                          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                          {{ rentWalletPinError }}
                        </div>
                      </Transition>
                      <div class="rent-pin-actions">
                        <button class="rent-pin-btn rent-pin-btn-secondary" @click="cancelPaymentFlow">Annuler</button>
                        <button class="rent-pin-btn rent-pin-btn-primary" @click="submitRentWalletPin" :disabled="rentWalletPinInput.length < 4">
                          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                          Valider
                        </button>
                      </div>
                    </div>
                    <div v-else-if="paymentFlowStep === 'success'" key="success" class="payment-success-card ps-rent">
                      <div class="ps-pulse-ring" style="color:#6366F1"></div>
                      <div class="ps-pulse-ring ps-pulse-2" style="color:#6366F1;width:190px;height:190px;margin:-95px 0 0 -95px"></div>
                      <div class="ps-pulse-ring" style="color:#818CF8;width:130px;height:130px;margin:-65px 0 0 -65px;animation-duration:2.8s;animation-delay:0.4s"></div>
                      <div class="ps-icon" style="background:linear-gradient(135deg,#EEF2FF,#E0E7FF);color:#6366F1">
                        <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                          <rect x="1" y="4" width="22" height="16" rx="2"/>
                          <line x1="1" y1="10" x2="23" y2="10"/>
                          <circle cx="12" cy="15" r="2"/>
                        </svg>
                      </div>
                      <svg class="ps-particles ps-rent-cashflow" width="180" height="180" viewBox="0 0 180 180">
                        <circle class="rf-c1" cx="30" cy="35" r="4" fill="#6366F1"/>
                        <circle class="rf-c2" cx="150" cy="30" r="3.5" fill="#818CF8"/>
                        <circle class="rf-c3" cx="20" cy="130" r="3.5" fill="#A5B4FC"/>
                        <circle class="rf-c4" cx="160" cy="135" r="3.5" fill="#6366F1"/>
                        <circle class="rf-c5" cx="90" cy="15" r="3" fill="#818CF8"/>
                        <path class="rf-arrow1" d="M40 100 L65 100 L65 87 L90 105 L65 123 L65 110 L40 110 Z" fill="#6366F1"/>
                        <path class="rf-arrow2" d="M140 60 L115 60 L115 47 L90 65 L115 83 L115 70 L140 70 Z" fill="#818CF8"/>
                      </svg>
                      <div class="ps-spark"></div>
                      <h3 class="ps-title" style="color:#6366F1">Paiement réussi !</h3>
                      <p class="ps-sub">{{ formatCurrency(totalPaymentAmount) }} débité de votre portefeuille</p>
                    </div>
                  </Transition>
                </div>
              </div>
            </Transition>

            <!-- ═══════════════════════════════════════════
                 RECEIPT DISPLAY — Just after payment
            ═══════════════════════════════════════════ -->
            <Transition name="fade-slide">
              <div v-if="lastPaymentReceipt" class="glass-card rent-receipt-card">
                <div class="rent-receipt-header">
                  <div class="rr-header-left">
                    <div class="rr-icon-ring">
                      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                    </div>
                    <div>
                      <h3 class="rr-title" v-if="lastPaymentReceipt">Reçu de versement — {{ lastPaymentReceipt.reference }}</h3>
                      <p class="rr-date">Paiement du {{ formatDate(lastPaymentReceipt.paid_at) }}</p>
                    </div>
                  </div>
                  <span class="rr-badge">Confirmé</span>
                </div>
                <div class="rent-receipt-body" v-if="lastPaymentReceipt">
                  <div class="rr-months-grid">
                    <div v-for="m in lastPaymentReceipt.months" :key="m.label" class="rr-month-item">
                      <span class="rrm-label">{{ m.label }}</span>
                      <span class="rrm-amount">{{ formatCurrency(m.amount) }}</span>
                    </div>
                  </div>
                  <div class="rr-total-row">
                    <span>Total versé</span>
                    <strong>{{ formatCurrency(lastPaymentReceipt.amount) }}</strong>
                  </div>
                  <div class="rr-meta-row">
                    <div class="rr-meta-item">
                      <span class="rrm-key">Transaction</span>
                      <span class="rrm-val mono">{{ lastPaymentReceipt.transaction_id }}</span>
                    </div>
                    <div class="rr-meta-item">
                      <span class="rrm-key">Méthode</span>
                      <span class="rrm-val">{{ lastPaymentReceipt.method }}</span>
                    </div>
                    <div class="rr-meta-item">
                      <span class="rrm-key">Bailleur</span>
                      <span class="rrm-val">{{ lastPaymentReceipt.landlord }}</span>
                    </div>
                  </div>
                </div>
                <div class="rent-receipt-footer">
                  <button class="btn-primary small" @click="generateReceiptPDF(lastPaymentReceipt)">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                    Télécharger le reçu PDF
                  </button>
                  <button class="btn-secondary small" @click="viewReceiptDetail(lastPaymentReceipt)">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    Voir en détail
                  </button>
                  <button class="btn-secondary small" @click="lastPaymentReceipt = null">
                    Masquer
                  </button>
                </div>
              </div>
            </Transition>

            <!-- Invoices lists filters -->
            <div class="glass-card filter-bar">
              <div class="filter-tabs">
                <button v-for="f in financeFilters" :key="f.id"
                  class="filter-tab" :class="{ active: financeFilter === f.id }"
                  @click="financeFilter = f.id">{{ f.label }}</button>
              </div>
              <div class="filter-right">
                <input type="text" class="search-input" placeholder="Rechercher une facture..." v-model="invoiceSearch"/>
              </div>
            </div>

            <!-- Invoices Table -->
            <div class="glass-card table-card">
              <div class="table-wrapper">
                <table class="premium-table">
                  <thead>
                    <tr>
                      <th>Référence</th>
                      <th>Période</th>
                      <th>Montant</th>
                      <th>Statut</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="invoice in filteredInvoices" :key="invoice.id" class="table-row">
                      <td class="ref-code">{{ invoice.reference }}</td>
                      <td>{{ invoice.period }}</td>
                      <td class="amount-cell"><strong>{{ formatCurrency(invoice.amount) }}</strong></td>
                      <td>
                        <span class="status-pill" :class="'status-' + invoice.status">
                          {{ invoiceStatusLabel(invoice.status) }}
                        </span>
                      </td>
                      <td>
                        <div class="action-buttons">
                          <button v-if="invoice.status !== 'paid'" class="action-btn pay-btn" @click="payIndividualInvoice(invoice)">Régler via Wallet</button>
                          <button v-if="invoice.status === 'paid'" class="action-btn receipt-btn" @click="downloadReceipt(invoice)">Quittance</button>
                          <button v-if="invoice.status !== 'paid'" class="action-btn upload-proof-btn" @click="openProofUpload(invoice)">Preuve</button>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </section>

          <!-- ═══════════════════════════════
               D. SUPPORT & MESSAGERIE PREMIUM
          ═══════════════════════════════ -->
          <section v-else-if="activeTab === 'support'" class="tab-section anim-section">
            <div class="page-header anim-fade-up">
              <div>
                <h1 class="page-title">Centre de Support & Messagerie</h1>
                <p class="page-subtitle">Discutez en temps réel avec votre bailleur pour résoudre les incidents techniques.</p>
              </div>
              <button class="btn-primary" @click="openNewTicket">Nouveau ticket</button>
            </div>

            <div class="support-layout">
              <!-- Tickets panel list -->
              <div class="tickets-panel">
                <div class="tp-header">
                  <span class="tp-count">{{ localTickets.length }} ticket(s)</span>
                </div>
                <div class="anim-stagger" style="display:contents">
                <div v-for="ticket in localTickets" :key="ticket.id"
                  class="ticket-card"
                  :class="{ 'ticket-selected': selectedTicket?.id === ticket.id, 'ticket-open': ticket.status === 'open', 'ticket-progress': ticket.status === 'in_progress', 'ticket-closed': ticket.status === 'closed' }"
                  @click="selectTicket(ticket)">
                  <div class="ticket-card-top">
                    <div class="ticket-cat-badge" :class="'cat-' + ticket.category" v-html="ticketCategoryIcon(ticket.category)"></div>
                    <div class="ticket-info">
                      <p class="ticket-title">{{ ticket.title }}</p>
                      <p class="ticket-preview">{{ ticket.description.substring(0, 55) }}...</p>
                    </div>
                    <span class="ticket-status-pulse" :class="'pulse-' + ticket.status"></span>
                  </div>
                  <div class="ticket-card-bottom">
                    <span class="ticket-status-badge" :class="'ts-' + ticket.status">{{ ticketStatusLabel(ticket.status) }}</span>
                    <span class="ticket-date">{{ formatDate(ticket.created_at) }}</span>
                    <span class="ticket-msgs-badge">{{ ticket.messages.length }} msg</span>
                  </div>
                </div>
              </div>
              </div>

              <!-- Chat area -->
              <div class="glass-card chat-panel">
                <template v-if="selectedTicket">
                  <div class="chat-header">
                    <div class="chat-header-left">
                      <div class="chat-avatar">{{ selectedTicket.title.charAt(0).toUpperCase() }}</div>
                      <div>
                        <h3 class="chat-title">{{ selectedTicket.title }}</h3>
                        <div class="chat-manager-status">
                          <span class="status-pulse-dot"></span>
                          <span class="status-pulse-lbl">Gestionnaire en ligne</span>
                        </div>
                      </div>
                    </div>
                    <div class="chat-header-actions">
                      <button v-if="selectedTicket.status !== 'closed'" class="chat-close-btn" @click="closeTicket(selectedTicket)">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                        Clôturer
                      </button>
                    </div>
                  </div>

                  <!-- Messages Wrapper -->
                  <div class="chat-messages" ref="chatMessagesRef">
                    <div v-for="msg in selectedTicket.messages" :key="msg.id"
                      class="chat-message"
                      :class="msg.sender === 'tenant' ? 'msg-tenant' : 'msg-manager'">
                      <div class="msg-bubble" :class="'bubble-' + msg.sender">
                        <div v-if="msg.text && msg.type !== 'audio' && msg.type !== 'attachment'" class="msg-text">{{ msg.text }}</div>
                        
                        <div v-if="msg.type === 'audio'" class="msg-audio-play-box">
                          <button class="audio-play-btn" @click="toggleAudioMessage(msg)">
                            <svg v-if="mockAudioPlayingId === msg.id" width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><rect x="4" y="4" width="16" height="16" rx="2"/></svg>
                            <svg v-else width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><polygon points="5,3 19,12 5,21"/></svg>
                          </button>
                          <div class="audio-wave-bars">
                            <span v-for="i in 16" :key="i" class="wave-bar" :class="{ active: mockAudioPlayingId === msg.id }"></span>
                          </div>
                          <span class="audio-duration">0:12</span>
                        </div>

                        <div v-if="msg.type === 'attachment'" class="msg-attachment-card">
                          <div class="attachment-preview-box">
                            <div class="attach-icon">
                              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
                            </div>
                            <div class="attachment-info">
                              <p class="attach-fn">{{ msg.fileName }}</p>
                              <p class="attach-fs">Image • 1.2 MB</p>
                            </div>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#64748B" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                          </div>
                        </div>

                        <span class="msg-time">
                          {{ formatTime(msg.created_at) }}
                          <span v-if="msg.sender === 'tenant'" class="read-ticks">✓✓</span>
                        </span>
                      </div>
                    </div>

                    <div v-if="isTyping" class="chat-message msg-manager">
                      <div class="msg-bubble bubble-manager typing-bubble">
                        <div class="typing-indicator">
                          <span></span><span></span><span></span>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div v-if="attachedFile" class="chat-attached-preview-row">
                    <div class="attached-file-badge">
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
                      <span>{{ attachedFile.name }}</span>
                      <button class="remove-attach-btn" @click="attachedFile = null">×</button>
                    </div>
                  </div>

                  <div class="chat-input-row" v-if="selectedTicket.status !== 'closed'">
                    <button class="chat-extra-btn" @click="simulateAttachment" title="Joindre un fichier">
                      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
                    </button>
                    <button class="chat-extra-btn" :class="{ 'recording-active': isRecording }" @click="toggleVoiceRecording" title="Message vocal">
                      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"/><path d="M19 10v2a7 7 0 0 1-14 0v-2"/><line x1="12" y1="19" x2="12" y2="23"/><line x1="8" y1="23" x2="16" y2="23"/></svg>
                    </button>

                    <div v-if="isRecording" class="voice-recording-overlay">
                      <span class="red-recording-dot"></span>
                      <span class="rec-timer">{{ recordingTime }}s</span>
                      <div class="live-wave-animation">
                        <span></span><span></span><span></span><span></span>
                      </div>
                      <button class="rec-cancel-btn" @click="cancelVoiceRecording">Annuler</button>
                      <button class="rec-send-btn" @click="sendVoiceRecording">Envoyer</button>
                    </div>

                    <input v-else
                      type="text"
                      class="chat-input"
                      placeholder="Écrivez votre message..."
                      v-model="newMessage"
                      @keydown.enter="sendMessage"/>

                    <button class="send-btn" @click="sendMessage" :disabled="!newMessage.trim() && !attachedFile">
                      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                    </button>
                  </div>
                </template>
                <div v-else class="chat-empty">
                  <div class="chat-empty-icon">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" stroke-width="1.2"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
                  </div>
                  <p class="chat-empty-title">Aucune conversation sélectionnée</p>
                  <p class="chat-empty-sub">Choisissez un ticket dans la liste pour commencer à discuter</p>
                </div>
              </div>
            </div>
          </section>

          <!-- ═══════════════════════════════
               E. PROFIL & PARAMÈTRES
          ═══════════════════════════════ -->
          <section v-else-if="activeTab === 'profile'" class="tab-section anim-section">
            <div class="page-header anim-fade-up">
              <div>
                <h1 class="page-title">{{ t('profile.title') }}</h1>
                <p class="page-subtitle">{{ t('profile.subtitle') }}</p>
              </div>
            </div>

            <div class="profile-layout">
              <!-- Personal Info -->
              <div class="glass-card profile-card">
                <h3 class="card-title">{{ t('profile.personalInfo') }}</h3>

                <div class="avatar-upload-section">
                  <div class="avatar-large-wrap">
                    <img :src="profileForm.avatar || defaultAvatar" class="avatar-large" :alt="profileForm.first_name"/>
                    <button class="avatar-edit-btn" @click="$refs.avatarInput.click()" :title="t('profile.changePhoto')">
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </button>
                    <input type="file" ref="avatarInput" accept="image/*" class="hidden-input" @change="handleAvatarChange"/>
                    <button v-if="profileForm.avatar" class="avatar-delete-btn" @click="deleteAvatar" :title="t('profile.deletePhoto')">
                      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                    </button>
                  </div>
                  <div class="avatar-meta">
                    <p class="avatar-name">{{ profileForm.first_name }} {{ profileForm.last_name }}</p>
                    <p class="avatar-email">{{ profileForm.email }}</p>
                    <button v-if="profileForm.avatar" class="avatar-reset-link" @click="deleteAvatar">{{ t('profile.deletePhoto') }}</button>
                  </div>
                </div>

                <div class="form-grid">
                  <div class="form-group">
                    <label>{{ t('profile.firstName') }}</label>
                    <input type="text" v-model="profileForm.first_name" class="form-input"/>
                  </div>
                  <div class="form-group">
                    <label>{{ t('profile.lastName') }}</label>
                    <input type="text" v-model="profileForm.last_name" class="form-input"/>
                  </div>
                  <div class="form-group">
                    <label>{{ t('profile.email') }}</label>
                    <input type="email" v-model="profileForm.email" class="form-input"/>
                  </div>
                  <div class="form-group">
                    <label>{{ t('profile.phone') }}</label>
                    <input type="tel" v-model="profileForm.phone" class="form-input"/>
                  </div>
                </div>

                <!-- Theme switch — iOS segmented control -->
                <div class="theme-section-divider">
                  <h4 class="section-subtitle">{{ t('profile.theme') }}</h4>
                  <div class="theme-toggle-group">
                    <button class="theme-opt-btn" :class="{ active: theme === 'light' }" @click="setTheme('light')">
                      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
                      {{ t('profile.lightMode') }}
                    </button>
                    <button class="theme-opt-btn" :class="{ active: theme === 'dark' }" @click="setTheme('dark')">
                      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
                      {{ t('profile.darkMode') }}
                    </button>
                  </div>
                </div>

                <!-- Language switcher — iOS-style -->
                <div class="lang-section-divider">
                  <h4 class="section-subtitle">{{ t('profile.language') }}</h4>
                  <p class="section-desc lang-desc">{{ t('profile.languageDesc') }}</p>
                  <div class="lang-btn-row">
                    <button class="lang-btn" :class="{ active: locale === 'fr' }" @click="switchLocale('fr')">
                      <span class="lang-flag">🇫🇷</span>
                      <span class="lang-name">{{ t('profile.french') }}</span>
                      <span v-if="locale === 'fr'" class="lang-check">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                      </span>
                    </button>
                    <button class="lang-btn" :class="{ active: locale === 'en' }" @click="switchLocale('en')">
                      <span class="lang-flag">🇬🇧</span>
                      <span class="lang-name">{{ t('profile.english') }}</span>
                      <span v-if="locale === 'en'" class="lang-check">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                      </span>
                    </button>
                  </div>
                </div>

                <div class="form-actions">
                  <button class="btn-primary btn-save-profile" @click="saveProfile" :class="{ loading: savingProfile }">
                    <svg v-if="!savingProfile" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/></svg>
                    {{ t('profile.save') }}
                  </button>
                </div>
              </div>

              <!-- Security, 2FA & Connection logs -->
              <div class="glass-card security-card">
                <h3 class="card-title">{{ t('profile.security') }}</h3>

                <div class="security-section">
                  <h4 class="section-subtitle">{{ t('profile.changePassword') }}</h4>
                  <div class="form-group">
                    <label>{{ t('profile.currentPassword') }}</label>
                    <input type="password" v-model="passwordForm.current" class="form-input"/>
                  </div>
                  <div class="form-group">
                    <label>{{ t('profile.newPassword') }}</label>
                    <input type="password" v-model="passwordForm.new_password" class="form-input"/>
                  </div>
                  <div class="form-group">
                    <label>{{ t('profile.confirmPassword') }}</label>
                    <input type="password" v-model="passwordForm.confirm" class="form-input"/>
                  </div>
                  <button class="btn-primary small btn-password-submit" @click="changePassword">{{ t('profile.modifyPassword') }}</button>
                </div>

                <!-- 🔐 2FA SETUP SECTION -->
                <div class="security-section tfa-section">
                  <div class="tfa-header">
                    <div>
                      <h4 class="section-subtitle">{{ t('profile.twoFA') }}</h4>
                      <p class="section-desc">{{ t('profile.twoFADesc') }}</p>
                    </div>
                    <span class="tfa-status-badge" :class="{ enabled: twoFAEnabled }">
                      {{ twoFAEnabled ? t('profile.active') : t('profile.inactive') }}
                    </span>
                  </div>
                  <div class="tfa-actions">
                    <button v-if="!twoFAEnabled" class="btn-primary small" @click="start2FASetup">{{ t('profile.enable2FA') }}</button>
                    <div v-else class="tfa-btn-group">
                      <button class="btn-secondary small" @click="viewBackupCodes">{{ t('profile.backupCodes') }}</button>
                      <button class="btn-danger small" @click="disable2FA">{{ t('profile.disable2FA') }}</button>
                    </div>
                  </div>
                </div>

                <!-- 📑 LOGIN JOURNAL -->
                <div class="security-section login-journal-section">
                  <h4 class="section-subtitle">{{ t('profile.loginJournal') }}</h4>
                  <p class="section-desc">{{ t('profile.loginJournalDesc') }}</p>
                  <div class="login-logs-list">
                    <div v-for="log in loginLogs" :key="log.id" class="login-log-row">
                      <div class="log-device-icon" v-html="log.device === 'desktop' ? desktopIcon : mobileIcon"></div>
                      <div class="log-details">
                        <p class="log-device-desc"><strong>{{ log.device_name }}</strong></p>
                        <p class="log-meta-txt">{{ log.ip }} • {{ log.location }} • {{ formatDateTime(log.date) }}</p>
                      </div>
                      <span class="log-status-tag" :class="log.status">{{ log.status === 'success' ? 'Réussi' : 'Échoué' }}</span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- ⚙️ NOTIFICATION PREFERENCES MATRIX -->
              <div class="glass-card notif-card">
                <h3 class="card-title">{{ t('profile.notifications') }}</h3>
                <div class="notif-matrix-wrapper">
                  <table class="notif-matrix-table">
                    <thead>
                      <tr>
                        <th>{{ t('profile.event') }}</th>
                        <th class="text-center">{{ t('profile.email') }}</th>
                        <th class="text-center">{{ t('profile.sms') }}</th>
                        <th class="text-center">{{ t('profile.pushMobile') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="pref in notifPreferences" :key="pref.id">
                        <td>
                          <p class="pref-label"><strong>{{ pref.label }}</strong></p>
                          <p class="pref-desc">{{ pref.description }}</p>
                        </td>
                        <td class="text-center">
                          <label class="custom-switch-label">
                            <input type="checkbox" v-model="pref.channels" value="email" @change="saveNotifPrefsSilent"/>
                            <span class="custom-switch-slider"></span>
                          </label>
                        </td>
                        <td class="text-center">
                          <label class="custom-switch-label">
                            <input type="checkbox" v-model="pref.channels" value="sms" @change="saveNotifPrefsSilent"/>
                            <span class="custom-switch-slider"></span>
                          </label>
                        </td>
                        <td class="text-center">
                          <label class="custom-switch-label">
                            <input type="checkbox" v-model="pref.channels" value="push" @change="saveNotifPrefsSilent"/>
                            <span class="custom-switch-slider"></span>
                          </label>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </section>

          <!-- ═══════════════════════════════
               F. EAU & ÉLECTRICITÉ
          ═══════════════════════════════ -->
          <section v-else-if="activeTab === 'utilities'" class="tab-section anim-section">
            <div class="page-header anim-fade-up">
              <div>
                <h1 class="page-title">{{ t('utilities.title') }}</h1>
                <p class="page-subtitle">Consultez vos factures, suivez votre consommation et payez en un clic.</p>
              </div>
            </div>

            <!-- Premium consumption cards -->
            <div class="util-premium-row anim-stagger">
              <div class="glass-card util-premium-card water-card">
                <div class="util-premium-inner">
                  <div class="util-premium-left">
                    <div class="util-icon-ring util-icon-water">
                      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 2C12 2 7 8 7 13a5 5 0 0010 0c0-5-5-11-5-11z"/></svg>
                    </div>
                    <div class="util-premium-info">
                      <span class="util-premium-label">{{ t('utilities.water') }}</span>
                      <span class="util-premium-conso">{{ latestWaterConso }} <small>{{ latestWaterUnit }}</small></span>
                      <span class="util-premium-cost">{{ formatCurrency(latestWaterCost) }}</span>
                    </div>
                  </div>
                  <div class="util-premium-right">
                    <span class="util-premium-period">{{ latestWaterPeriod }}</span>
                    <span class="util-premium-badge" :class="waterStatusClass">{{ waterStatusLabel }}</span>
                  </div>
                </div>
                <div class="util-premium-bar-track">
                  <div class="util-premium-bar-fill util-bar-water" :style="{ width: waterBarPct + '%' }"></div>
                </div>
              </div>

              <div class="glass-card util-premium-card elec-card">
                <div class="util-premium-inner">
                  <div class="util-premium-left">
                    <div class="util-icon-ring util-icon-elec">
                      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                    </div>
                    <div class="util-premium-info">
                      <span class="util-premium-label">{{ t('utilities.electricity') }}</span>
                      <span class="util-premium-conso">{{ latestElecConso }} <small>{{ latestElecUnit }}</small></span>
                      <span class="util-premium-cost">{{ formatCurrency(latestElecCost) }}</span>
                    </div>
                  </div>
                  <div class="util-premium-right">
                    <span class="util-premium-period">{{ latestElecPeriod }}</span>
                    <span class="util-premium-badge" :class="elecStatusClass">{{ elecStatusLabel }}</span>
                  </div>
                </div>
                <div class="util-premium-bar-track">
                  <div class="util-premium-bar-fill util-bar-elec" :style="{ width: elecBarPct + '%' }"></div>
                </div>
              </div>
            </div>

            <!-- Summary cards -->
            <div class="finance-summary-row anim-stagger">
              <div class="glass-card fin-summary-card">
                <div class="fin-sum-icon-wrap sum-icon-blue">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2C12 2 7 8 7 13a5 5 0 0010 0c0-5-5-11-5-11z"/></svg>
                </div>
                <div class="fin-sum-body">
                  <p class="fin-sum-label">{{ t('utilities.water') }}</p>
                  <p class="fin-sum-value blue">{{ waterPendingCount }}</p>
                  <p class="fin-sum-hint">en attente</p>
                </div>
              </div>
              <div class="glass-card fin-summary-card">
                <div class="fin-sum-icon-wrap sum-icon-amber">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                </div>
                <div class="fin-sum-body">
                  <p class="fin-sum-label">{{ t('utilities.electricity') }}</p>
                  <p class="fin-sum-value amber">{{ elecPendingCount }}</p>
                  <p class="fin-sum-hint">en attente</p>
                </div>
              </div>
              <div class="glass-card fin-summary-card">
                <div class="fin-sum-icon-wrap sum-icon-red">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
                </div>
                <div class="fin-sum-body">
                  <p class="fin-sum-label">Total dû</p>
                  <p class="fin-sum-value red">{{ formatCurrency(utilitiesTotalDue) }}</p>
                </div>
              </div>
              <div class="glass-card fin-summary-card">
                <div class="fin-sum-icon-wrap sum-icon-emerald">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                </div>
                <div class="fin-sum-body">
                  <p class="fin-sum-label">Dernière facture</p>
                  <p class="fin-sum-value emerald">{{ latestUtilityDate }}</p>
                </div>
              </div>
            </div>

            <!-- Filter & Search Bar -->
            <div class="glass-card filter-bar">
              <div class="filter-tabs">
                <button v-for="f in utilityFilters" :key="f.id"
                  class="filter-tab" :class="{ active: utilityFilter === f.id }"
                  @click="utilityFilter = f.id">{{ f.label }}</button>
              </div>
              <div class="filter-right">
                <input type="text" class="search-input" placeholder="Rechercher une facture..." v-model="utilitySearch"/>
              </div>
            </div>

            <!-- Utilities Table -->
            <div class="glass-card table-card">
              <div class="table-wrapper">
                <table class="premium-table">
                  <thead>
                    <tr>
                      <th>Référence</th>
                      <th>Type</th>
                      <th>Période</th>
                      <th>Consommation</th>
                      <th>Index</th>
                      <th>Montant</th>
                      <th>Statut</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="inv in filteredUtilityInvoices" :key="inv.id" class="table-row">
                      <td class="ref-code">{{ inv.reference }}</td>
                      <td>
                        <span class="type-badge" :class="'type-' + inv.type.toLowerCase()">
                          {{ inv.type === 'WATER' ? 'Eau' : 'Électricité' }}
                        </span>
                      </td>
                      <td>{{ inv.period }}</td>
                      <td>
                        <span v-if="inv.consumption" class="conso-value">{{ inv.consumption.value }} {{ inv.consumption.unit }}</span>
                        <span v-else>—</span>
                      </td>
                      <td><span class="conso-index">{{ inv.consumption?.index || '—' }}</span></td>
                      <td class="amount-cell"><strong>{{ formatCurrency(inv.amount) }}</strong></td>
                      <td>
                        <span class="status-pill" :class="'status-' + inv.status">
                          {{ inv.status === 'paid' ? 'Payé' : 'En attente' }}
                        </span>
                      </td>
                      <td>
                        <div class="action-buttons">
                          <button v-if="inv.status !== 'paid'" class="action-btn pay-btn" @click="payUtilityInvoice(inv)">Payer</button>
                          <button v-if="inv.status === 'paid'" class="action-btn receipt-btn" @click="downloadUtilityReceipt(inv)">Reçu</button>
                          <button class="action-btn view-btn" @click="viewUtilityDetail(inv)">Détails</button>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div v-if="filteredUtilityInvoices.length === 0" class="table-empty">Aucune facture trouvée</div>
            </div>

            <!-- ═══════════════════════════════════════════
                 UTILITY CONFIRMATION MODAL
                 (Shared overlay with recap → PIN → success cards)
            ═══════════════════════════════════════════ -->
            <Transition name="fade">
              <div v-if="showUtilityConfirmModal && selectedUtilityInv" class="payment-modal-overlay" @click.self="paymentFlowStep !== 'success' && cancelPaymentFlow()">
                <div class="card-switch-stage">
                  <Transition name="card-switch" mode="out-in">
                    <div v-if="paymentFlowStep === 'recap' || paymentFlowStep === 'idle'" key="recap" class="payment-modal-card glass-card">
                      <div class="payment-modal-header">
                        <div class="pm-header-icon" :style="selectedUtilityInv.type === 'WATER' ? 'background:linear-gradient(135deg,#DBEAFE,#BFDBFE);color:#2563EB' : 'background:linear-gradient(135deg,#FEF3C7,#FDE68A);color:#D97706'">
                          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path v-if="selectedUtilityInv.type === 'WATER'" d="M12 2C12 2 7 8 7 13a5 5 0 0010 0c0-5-5-11-5-11z"/>
                            <polygon v-else points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
                          </svg>
                        </div>
                        <div>
                          <h3 class="pm-title">Paiement de facture</h3>
                          <p class="pm-sub">Vérifiez les détails avant de valider</p>
                        </div>
                        <button class="pm-close" @click="cancelPaymentFlow">&times;</button>
                      </div>
                      <div class="payment-modal-body">
                        <div class="pm-section-title">
                          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                          Détails de la facture
                        </div>
                        <div class="pm-invoice-detail">
                          <div class="pm-invoice-row"><span>Référence</span><strong>{{ selectedUtilityInv.reference }}</strong></div>
                          <div class="pm-invoice-row"><span>Type</span><span class="type-badge" :class="'type-' + selectedUtilityInv.type.toLowerCase()">{{ selectedUtilityInv.type === 'WATER' ? 'Eau' : 'Électricité' }}</span></div>
                          <div class="pm-invoice-row"><span>Période</span><strong>{{ selectedUtilityInv.period }}</strong></div>
                          <div class="pm-invoice-row" v-if="selectedUtilityInv.consumption"><span>Consommation</span><strong>{{ selectedUtilityInv.consumption.value }} {{ selectedUtilityInv.consumption.unit }}</strong></div>
                        </div>
                        <div class="pm-divider"></div>
                        <div class="pm-summary">
                          <div class="pm-summary-row pm-total">
                            <span class="pms-label">Montant à payer</span>
                            <span class="pms-value total">{{ formatCurrency(selectedUtilityInv.amount) }}</span>
                          </div>
                        </div>
                        <div class="pm-wallet-info">
                          <div class="pm-wi-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                          </div>
                          <div class="pm-wi-details">
                            <span class="pm-wi-label">Paiement depuis</span>
                            <span class="pm-wi-value">Portefeuille HABITATUM</span>
                            <span class="pm-wi-balance">Solde : {{ hideBalance ? '••••••' : formatCurrency(walletBalance) }}</span>
                          </div>
                          <div class="pm-wi-check">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                          </div>
                        </div>
                      </div>
                      <div class="payment-modal-footer">
                        <button class="btn-secondary" @click="cancelPaymentFlow">Annuler</button>
                        <button class="btn-primary pm-pay-btn" @click="confirmUtilityPayment">
                          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                          Confirmer le paiement
                        </button>
                      </div>
                    </div>
                    <div v-else-if="paymentFlowStep === 'pin'" key="pin" class="rent-pin-modal" @click="focusRentPinInput">
                      <div class="rent-pin-bg-ornament"></div>
                      <button class="rent-pin-close" @click="cancelPaymentFlow">&times;</button>
                      <div class="rent-pin-icon-wrap">
                        <div class="rent-pin-icon-ring">
                          <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0110 0v4"/>
                            <circle cx="12" cy="16" r="1.5"/>
                          </svg>
                        </div>
                      </div>
                      <h3 class="rent-pin-title">Code de sécurité</h3>
                      <p class="rent-pin-sub">Saisissez votre code secret pour autoriser le paiement de <strong>{{ formatCurrency(premiumPinAmount) }}</strong></p>
                      <div class="rent-pin-dots" :class="{ ripple: pinDotsRipple }" @click.stop="onPinDotsClick">
                        <span v-for="i in 4" :key="i" class="rent-pin-dot" :class="{ filled: rentWalletPinInput.length >= i }">
                          <span v-if="rentWalletPinInput.length >= i" class="rent-pin-dot-fill"></span>
                        </span>
                      </div>
                      <div class="rent-pin-input-row">
                        <input ref="rentPinInputEl" type="password" v-model="rentWalletPinInput" maxlength="4"
                          class="rent-pin-input" autofocus
                          @input="onRentPinInput"
                          @keyup.enter="submitRentWalletPin"/>
                      </div>
                      <Transition name="fade">
                        <div v-if="rentWalletPinError" class="rent-pin-error">
                          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                          {{ rentWalletPinError }}
                        </div>
                      </Transition>
                      <div class="rent-pin-actions">
                        <button class="rent-pin-btn rent-pin-btn-secondary" @click="cancelPaymentFlow">Annuler</button>
                        <button class="rent-pin-btn rent-pin-btn-primary" @click="submitRentWalletPin" :disabled="rentWalletPinInput.length < 4">
                          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                          Valider
                        </button>
                      </div>
                    </div>
                    <div v-else-if="paymentFlowStep === 'success'" key="success" class="payment-success-card">
                      <div class="ps-pulse-ring" :style="{color: selectedUtilityInv?.type === 'WATER' ? '#2563EB' : '#D97706'}"></div>
                      <div class="ps-pulse-ring ps-pulse-2" :style="{color: selectedUtilityInv?.type === 'WATER' ? '#2563EB' : '#D97706'}"></div>
                      <div class="ps-pulse-ring ps-pulse-3" :style="{color: selectedUtilityInv?.type === 'WATER' ? '#2563EB' : '#D97706'}"></div>
                      <div class="ps-icon" :style="selectedUtilityInv?.type === 'WATER' ? 'background:linear-gradient(135deg,#DBEAFE,#BFDBFE);color:#2563EB' : 'background:linear-gradient(135deg,#FEF3C7,#FDE68A);color:#D97706'">
                        <svg v-if="selectedUtilityInv?.type === 'WATER'" class="ps-water-icon" width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                          <path d="M12 2C12 2 7 8 7 13a5 5 0 0010 0c0-5-5-11-5-11z"/>
                        </svg>
                        <svg v-else class="ps-elec-icon" width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                          <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
                        </svg>
                      </div>
                      <template v-if="selectedUtilityInv">
                        <svg v-if="selectedUtilityInv.type === 'WATER'" class="ps-particles ps-water-sparkles" width="160" height="160" viewBox="0 0 160 160">
                          <circle class="ws-s1" cx="25" cy="25" r="3.5" fill="#3B82F6"/>
                          <circle class="ws-s2" cx="135" cy="35" r="2.5" fill="#60A5FA"/>
                          <circle class="ws-s3" cx="20" cy="110" r="3" fill="#93C5FD"/>
                          <circle class="ws-s4" cx="140" cy="120" r="2.5" fill="#3B82F6"/>
                          <circle class="ws-s5" cx="80" cy="15" r="2.5" fill="#60A5FA"/>
                        </svg>
                        <svg v-else class="ps-particles ps-elec-sparkles" width="160" height="160" viewBox="0 0 160 160">
                          <polygon class="es-s1" points="35,20 40,10 45,20 40,30" fill="#F59E0B"/>
                          <polygon class="es-s2" points="120,25 124,15 128,25 124,35" fill="#FBBF24"/>
                          <polygon class="es-s3" points="25,110 29,102 33,110 29,118" fill="#F59E0B"/>
                          <polygon class="es-s4" points="130,115 134,107 138,115 134,123" fill="#FBBF24"/>
                        </svg>
                      </template>
                      <h3 class="ps-title" :style="{color: selectedUtilityInv?.type === 'WATER' ? '#2563EB' : '#D97706'}">Facture payée !</h3>
                      <p class="ps-sub" v-if="selectedUtilityInv">{{ selectedUtilityInv.reference }} — {{ formatCurrency(selectedUtilityInv.amount) }} débité</p>
                    </div>
                  </Transition>
                </div>
              </div>
            </Transition>

            <!-- ═══════════════════════════════════════════
                 UTILITY RECEIPT CARD
            ═══════════════════════════════════════════ -->
            <Transition name="fade-slide">
              <div v-if="lastUtilityReceipt" class="glass-card rent-receipt-card">
                <div class="rent-receipt-header">
                  <div class="rr-header-left">
                    <div class="rr-icon-ring" :style="lastUtilityReceipt.type === 'water' ? 'background:linear-gradient(135deg,#DBEAFE,#BFDBFE);color:#2563EB' : 'background:linear-gradient(135deg,#FEF3C7,#FDE68A);color:#D97706'">
                      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path v-if="lastUtilityReceipt.type === 'water'" d="M12 2C12 2 7 8 7 13a5 5 0 0010 0c0-5-5-11-5-11z"/>
                        <polygon v-else points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
                      </svg>
                    </div>
                    <div>
                      <h3 class="rr-title">{{ lastUtilityReceipt.title }}</h3>
                      <p class="rr-date">Paiement du {{ formatDate(lastUtilityReceipt.paid_at) }}</p>
                    </div>
                  </div>
                  <span class="rr-badge">Payé</span>
                </div>
                <div class="rent-receipt-body">
                  <div class="rr-meta-row">
                    <div class="rr-meta-item">
                      <span class="rrm-key">Référence</span>
                      <span class="rrm-val">{{ lastUtilityReceipt.reference }}</span>
                    </div>
                    <div class="rr-meta-item">
                      <span class="rrm-key">Montant</span>
                      <span class="rrm-val">{{ formatCurrency(lastUtilityReceipt.amount) }}</span>
                    </div>
                    <div class="rr-meta-item">
                      <span class="rrm-key">Transaction</span>
                      <span class="rrm-val mono">{{ lastUtilityReceipt.transaction_id }}</span>
                    </div>
                  </div>
                </div>
                <div class="rent-receipt-footer">
                  <button class="btn-primary small" @click="generateReceiptPDF(lastUtilityReceipt)">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                    Télécharger le reçu
                  </button>
                  <button class="btn-secondary small" @click="viewReceiptDetail(lastUtilityReceipt)">Détails</button>
                  <button class="btn-secondary small" @click="lastUtilityReceipt = null">Masquer</button>
                </div>
              </div>
            </Transition>
          </section>

          <!-- ═══════════════════════════════
               G. QUITTANCES & REÇUS
          ═══════════════════════════════ -->
          <section v-else-if="activeTab === 'receipts'" class="tab-section anim-section">
            <div class="page-header anim-fade-up">
              <div>
                <h1 class="page-title">Quittances & Reçus de versement</h1>
                <p class="page-subtitle">Générez et téléchargez tous vos justificatifs de paiement (loyers, charges, contrats).</p>
              </div>
              <div class="receipt-header-info">
                <span class="receipt-badge">Total reçus : {{ allReceipts.length }}</span>
                <button class="btn-secondary small" @click="showReceiptsPreview = true" style="margin-left: 10px;">
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                  Aperçu global
                </button>
              </div>
            </div>

            <!-- Receipt Filter Tabs -->
            <div class="glass-card filter-bar">
              <div class="filter-tabs">
                <button v-for="f in receiptFilters" :key="f.id"
                  class="filter-tab" :class="{ active: receiptFilter === f.id }"
                  @click="receiptFilter = f.id">{{ f.label }}</button>
              </div>
              <div class="filter-right">
                <input type="text" class="search-input" placeholder="Rechercher un reçu..." v-model="receiptSearch"/>
              </div>
            </div>

            <!-- Receipts Grid -->
            <div class="receipts-grid anim-stagger">
              <div v-for="rec in filteredReceipts" :key="rec.id" class="glass-card receipt-card">
                <div class="receipt-card-header">
                  <div class="receipt-type-icon" :class="'r-' + rec.type">
                    <svg v-if="rec.type === 'rent'" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                    <svg v-else-if="rec.type === 'water' || rec.type === 'electric'" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                    <svg v-else width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/></svg>
                  </div>
                  <div class="receipt-meta">
                    <p class="receipt-title">{{ rec.title }}</p>
                    <p class="receipt-ref">{{ rec.reference }}</p>
                  </div>
                  <span class="receipt-amount">{{ formatCurrency(rec.amount) }}</span>
                </div>
                  <div class="receipt-card-body">
                  <div v-if="rec.includesPenalties" class="receipt-penalty-badge">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    Pénalités incluses
                  </div>
                  <div class="receipt-details-row">
                    <span class="rd-label">Période</span>
                    <span class="rd-value">{{ rec.period }}</span>
                  </div>
                  <div class="receipt-details-row">
                    <span class="rd-label">Date de paiement</span>
                    <span class="rd-value">{{ formatDate(rec.paid_at) }}</span>
                  </div>
                  <div class="receipt-details-row">
                    <span class="rd-label">Méthode</span>
                    <span class="rd-value">{{ rec.method }}</span>
                  </div>
                  <div class="receipt-details-row" v-if="rec.transaction_id">
                    <span class="rd-label">Transaction</span>
                    <span class="rd-value mono">{{ rec.transaction_id }}</span>
                  </div>
                </div>
                <div class="receipt-card-footer">
                  <button class="btn-primary small" @click="generateReceiptPDF(rec)">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                    Télécharger PDF
                  </button>
                  <button class="btn-secondary small" @click="viewReceiptDetail(rec)">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    Détails
                  </button>
                </div>
              </div>
            </div>
            <div v-if="filteredReceipts.length === 0" class="receipts-empty">
              <p>Aucun reçu de versement trouvé.</p>
            </div>
          </section>

          <!-- ═══════════════════════════════
               H. ANCIENS CONTRATS
          ═══════════════════════════════ -->
          <section v-else-if="activeTab === 'old-contracts'" class="tab-section anim-section">
            <div class="page-header anim-fade-up">
              <div>
                <h1 class="page-title">Anciens Contrats de Location</h1>
                <p class="page-subtitle">Historique complet de vos précédents baux et locations.</p>
              </div>
            </div>

            <!-- Old Contracts Search & Filter -->
            <div class="glass-card filter-bar">
              <div class="filter-right" style="width:100%">
                <input type="text" class="search-input" placeholder="Rechercher par adresse, propriétaire..." v-model="oldContractSearch" style="width:100%"/>
              </div>
            </div>

            <div v-if="filteredOldContracts.length === 0" class="glass-card" style="padding:40px; text-align:center;">
              <p style="color:var(--text-muted);">Aucun ancien contrat enregistré.</p>
            </div>

            <div class="anim-stagger" style="display:contents">
            <div v-for="oc in filteredOldContracts" :key="oc.id" class="glass-card old-contract-card anim-fade-up">
              <div class="oc-card-bg-ornament"></div>
              <div class="old-contract-top">
                <div class="old-contract-icon" :class="'oc-icon-' + oc.status">
                  <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                </div>
                <div class="old-contract-info">
                  <h3 class="old-contract-title">{{ oc.property_name }}</h3>
                  <p class="old-contract-address">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 1118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    {{ oc.address }}
                  </p>
                </div>
                <div class="old-contract-period">
                  <span class="oc-date-badge">{{ formatDate(oc.start_date) }} — {{ formatDate(oc.end_date) }}</span>
                  <span class="oc-status-badge" :class="'oc-' + oc.status">
                    <span class="oc-status-dot"></span>
                    {{ oc.status === 'ended' ? 'Terminé' : 'Résilié' }}
                  </span>
                </div>
              </div>
              <div class="old-contract-details">
                <div class="oc-detail-item">
                  <span class="oc-d-label">Propriétaire</span>
                  <span class="oc-d-value">{{ oc.owner }}</span>
                </div>
                <div class="oc-detail-item">
                  <span class="oc-d-label">Loyer mensuel</span>
                  <span class="oc-d-value oc-rent-value">{{ formatCurrency(oc.rent) }}</span>
                </div>
                <div class="oc-detail-item">
                  <span class="oc-d-label">Caution</span>
                  <span class="oc-d-value">{{ formatCurrency(oc.deposit) }}</span>
                </div>
                <div class="oc-detail-item">
                  <span class="oc-d-label">Durée</span>
                  <span class="oc-d-value">{{ oc.duration }}</span>
                </div>
                <div class="oc-detail-item">
                  <span class="oc-d-label">Documents</span>
                  <span class="oc-d-value">{{ oc.documents.length }} pièce(s)</span>
                </div>
              </div>
              <div class="old-contract-actions">
                <button class="btn-secondary small" @click="viewOldContractDetail(oc)">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                  Détails
                </button>
                <button class="btn-primary small" @click="downloadOldContractReceipt(oc)">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                  Reçu de versement
                </button>
              </div>
            </div>
            </div>
          </section>

        </div>
      </Transition>

      <AiAssistant
        :user="props.auth?.user || {}"
        :invoices="localInvoices"
        :contract="props.contracts[0] || {}"
        :wallet-balance="walletBalance"
        :current-tab="activeTab"
        :penalty-info="penaltyInfo"
        :latest-water-conso="latestWaterConso"
        :latest-water-unit="latestWaterUnit"
        :latest-water-cost="latestWaterCost"
        :latest-water-period="latestWaterPeriod"
        :water-status="waterStatusLabel"
        :latest-elec-conso="latestElecConso"
        :latest-elec-unit="latestElecUnit"
        :latest-elec-cost="latestElecCost"
        :latest-elec-period="latestElecPeriod"
        :elec-status="elecStatusLabel"
        @action="handleAiAction"/>

      <!-- Bottom nav -- mobile-only -->
      <nav class="bottom-nav">
        <button class="bn-item" :class="{ active: activeTab === 'overview' }" @click="switchTab('overview')">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
          <span>Accueil</span>
        </button>
        <button class="bn-item" :class="{ active: activeTab === 'loyer' }" @click="switchTab('loyer')">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
          <span>Loyer</span>
        </button>
        <button class="bn-item" :class="{ active: activeTab === 'support' }" @click="switchTab('support')">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
          <span>Support</span>
        </button>
        <button class="bn-item" :class="{ active: activeTab === 'profile' }" @click="switchTab('profile')">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          <span>Profil</span>
        </button>
      </nav>
    </main>

    <!-- ══════════════════════════════════════════════════
         MODALS
    ══════════════════════════════════════════════════ -->

    <!-- 📸 AVATAR PREVIEW MODAL — compact, elegant -->
    <Transition name="modal">
      <div v-if="showAvatarPreview" class="modal-overlay" @click.self="cancelAvatar">
        <div class="modal-box avatar-preview-modal">
          <div class="modal-header">
            <h3 class="modal-title">Aperçu photo</h3>
            <button class="modal-close" @click="cancelAvatar">×</button>
          </div>
          <div class="avatar-preview-body">
            <div class="avatar-preview-circle">
              <img :src="avatarPreviewUrl" alt="Preview" class="avatar-preview-img"/>
            </div>
            <p class="avatar-preview-hint">Redimensionnée automatiquement</p>
          </div>
          <div class="avatar-preview-actions">
            <button class="btn-secondary" @click="cancelAvatar">Annuler</button>
            <button class="btn-primary" @click="confirmAvatar">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
              Appliquer
            </button>
          </div>
        </div>
      </div>
    </Transition>

    <!-- 💳 PORTABLE WALLET RECHARGE MODAL -->
    <Transition name="modal">
      <div v-if="showRechargeModal" class="modal-overlay" @click.self="showRechargeModal = false">
        <div class="modal-box recharge-modal">
          <div class="rm-header">
            <div class="rm-header-left">
              <div class="rm-header-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
              </div>
              <div>
                <h3 class="rm-title">Recharger le Wallet</h3>
                <p class="rm-balance">Solde : <strong>{{ hideBalance ? '••••••' : formatCurrency(walletBalance) }}</strong> <span class="rm-balance-toggle" @click="toggleBalanceVisibility">{{ hideBalance ? 'Afficher' : 'Masquer' }}</span></p>
              </div>
            </div>
            <button class="modal-close" @click="showRechargeModal = false">×</button>
          </div>

          <div class="rm-tabs">
            <button v-for="tab in rechargeTabs" :key="tab.id"
              class="rm-tab"
              :class="{ active: activeRechargeTab === tab.id }"
              @click="activeRechargeTab = tab.id">
              <span v-html="tab.icon" class="rm-tab-icon"></span>
              <span class="rm-tab-label">{{ tab.name }}</span>
            </button>
          </div>

          <div class="rm-body">
            <!-- Orange Money -->
            <div v-if="activeRechargeTab === 'orange'" class="rm-panel">
              <div class="rm-field">
                <label class="rm-label">Numéro Orange Money (+237)</label>
                <div class="rm-input-wrap">
                  <span class="rm-input-prefix">+237</span>
                  <input type="tel" class="rm-input" placeholder="69X XX XX XX" v-model="orangePhone" maxlength="9"/>
                </div>
              </div>
              <div class="rm-field">
                <label class="rm-label">Montant (XAF)</label>
                <div class="rm-input-wrap">
                  <span class="rm-input-prefix">XAF</span>
                  <input type="number" class="rm-input rm-input-amount" placeholder="Ex: 50 000" v-model="orangeAmount"/>
                </div>
              </div>
              <div v-if="orangeOtpSent" class="rm-otp-box">
                <p class="rm-otp-label">Code de confirmation (test: <strong>8842</strong>)</p>
                <input type="text" class="rm-otp-input" placeholder="0 0 0 0" v-model="orangeOtp" maxlength="4"/>
                <p class="rm-otp-timer">Code valide {{ orangeOtpCountdown }}s</p>
              </div>
              <button class="rm-btn-primary" @click="processOrangeRecharge" :disabled="processingRecharge">
                <span v-if="processingRecharge" class="spinner-sm"></span>
                <svg v-else width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                <span>{{ orangeOtpSent ? 'Confirmer le paiement' : 'Initier le paiement' }}</span>
              </button>
            </div>

            <!-- MTN MoMo -->
            <div v-if="activeRechargeTab === 'mtn'" class="rm-panel">
              <div class="rm-field">
                <label class="rm-label">Numéro MTN MoMo (+237)</label>
                <div class="rm-input-wrap">
                  <span class="rm-input-prefix">+237</span>
                  <input type="tel" class="rm-input" placeholder="67X XX XX XX" v-model="mtnPhone" maxlength="9"/>
                </div>
              </div>
              <div class="rm-field">
                <label class="rm-label">Code Mobile Money PIN</label>
                <input type="password" class="rm-input rm-input-pin" placeholder="• • • •" v-model="mtnPin" maxlength="4"/>
              </div>
              <div class="rm-field">
                <label class="rm-label">Montant (XAF)</label>
                <div class="rm-input-wrap">
                  <span class="rm-input-prefix">XAF</span>
                  <input type="number" class="rm-input rm-input-amount" placeholder="Ex: 25 000" v-model="mtnAmount"/>
                </div>
              </div>
              <button class="rm-btn-primary" @click="processMtnRecharge" :disabled="processingRecharge">
                <span v-if="processingRecharge" class="spinner-sm"></span>
                <svg v-else width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                <span>Payer via MTN MoMo</span>
              </button>
            </div>

            <!-- Carte Bancaire -->
            <div v-if="activeRechargeTab === 'card'" class="rm-panel">
              <div class="rm-credit-card" :class="{ flipped: isCardFlipped }">
                <div class="rm-card-inner">
                  <div class="rm-card-face rm-card-front">
                    <div class="rm-card-top">
                      <span class="rm-card-brand">HABITATUM</span>
                      <svg class="rm-card-type" width="36" height="24" viewBox="0 0 36 24" fill="none"><rect width="36" height="24" rx="3" fill="#fff" opacity="0.9"/><circle cx="14" cy="12" r="6" fill="#EB001B" opacity="0.7"/><circle cx="22" cy="12" r="6" fill="#F79E1B" opacity="0.7"/></svg>
                    </div>
                    <p class="rm-card-number">{{ formattedCardNumber || '••••  ••••  ••••  ••••' }}</p>
                    <div class="rm-card-footer">
                      <div><span class="rm-card-label">Titulaire</span><span class="rm-card-val">{{ cardForm.name || 'Votre nom' }}</span></div>
                      <div><span class="rm-card-label">Expire</span><span class="rm-card-val">{{ cardForm.expiry || 'MM/AA' }}</span></div>
                    </div>
                  </div>
                  <div class="rm-card-face rm-card-back">
                    <div class="rm-card-strip"></div>
                    <div class="rm-card-cvv-area">
                      <span class="rm-card-label">CVV</span>
                      <div class="rm-card-cvv">{{ cardForm.cvv || '•••' }}</div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="rm-card-fields">
                <div class="rm-field">
                  <label class="rm-label">Numéro de carte</label>
                  <input type="text" class="rm-input" placeholder="4000 1234 5678 9010" v-model="cardForm.number" @input="formatCardNumber" maxlength="19"/>
                </div>
                <div class="rm-field">
                  <label class="rm-label">Nom du titulaire</label>
                  <input type="text" class="rm-input" placeholder="ARMEL NGUETSOP" v-model="cardForm.name"/>
                </div>
                <div class="rm-row-2">
                  <div class="rm-field">
                    <label class="rm-label">Expiration</label>
                    <input type="text" class="rm-input" placeholder="MM/AA" v-model="cardForm.expiry" @input="formatCardExpiry" maxlength="5"/>
                  </div>
                  <div class="rm-field">
                    <label class="rm-label">CVV</label>
                    <input type="text" class="rm-input" placeholder="123" v-model="cardForm.cvv" @focus="isCardFlipped = true" @blur="isCardFlipped = false" maxlength="3"/>
                  </div>
                  <div class="rm-field">
                    <label class="rm-label">Montant</label>
                    <input type="number" class="rm-input" placeholder="XAF" v-model="cardForm.amount"/>
                  </div>
                </div>
              </div>
              <button class="rm-btn-primary" @click="processCardRecharge" :disabled="processingRecharge">
                <span v-if="processingRecharge" class="spinner-sm"></span>
                <svg v-else width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                <span>Payer par Carte</span>
              </button>
            </div>

            <!-- PayPal -->
            <div v-if="activeRechargeTab === 'paypal'" class="rm-panel">
              <div class="rm-paypal-header">
                <svg width="48" height="48" viewBox="0 0 48 48" fill="none"><rect width="48" height="48" rx="12" fill="#003087"/><path d="M34.5 16h-9.8l-3.5 21h4.2l1.3-7.8h4.3c4.5 0 7.1-2.5 8-6.8 0 0 .5-2.2 0-4.3 0 0-.5-2.1-4.5-2.1z" fill="#fff" opacity="0.9"/><path d="M33.5 20.5c-.3-2-1.9-3.5-4.5-4-1.2-.2-2.6-.3-4-.3h-6l-2.5 15h4.5l1-6h1.8c3 0 5.3-1.5 6-4.5.2-.8.2-1.5 0-2.2z" fill="#fff" opacity="0.6"/></svg>
                <div>
                  <p class="rm-paypal-title">PayPal Checkout</p>
                  <p class="rm-paypal-sub">Paiement sécurisé international</p>
                </div>
              </div>
              <div class="rm-field">
                <label class="rm-label">Montant (XAF)</label>
                <div class="rm-input-wrap">
                  <span class="rm-input-prefix">XAF</span>
                  <input type="number" class="rm-input rm-input-amount" placeholder="Ex: 100 000" v-model="paypalAmount"/>
                </div>
              </div>
              <button class="rm-btn-paypal" @click="processPaypalRecharge">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M7.076 21.337H2.47a.641.641 0 01-.633-.74L4.944.901C5.026.382 5.474 0 5.998 0h7.46c2.57 0 4.578.543 5.69 1.81 1.01 1.15 1.304 2.42 1.012 4.287-.023.143-.047.288-.077.437-.983 5.05-4.349 6.797-8.647 6.797h-2.19c-.524 0-.972.382-1.054.9l-1.12 7.106z" fill="#003087"/></svg>
                Payer avec PayPal
              </button>
            </div>
          </div>
        </div>
      </div>
    </Transition>

    <!-- 🔐 2FA SETUP MODAL -->
    <Transition name="modal">
      <div v-if="show2FAModal" class="modal-overlay" @click.self="show2FAModal = false">
        <div class="modal-box tfa-wizard-modal">
          <div class="modal-header">
            <h3 class="modal-title">Configuration TOTP 2FA</h3>
            <button class="modal-close" @click="show2FAModal = false">×</button>
          </div>

          <div class="tfa-stepper">
            <div class="tfa-step" :class="{ active: tfaStep >= 1, done: tfaStep > 1 }">1</div>
            <div class="line-step"></div>
            <div class="tfa-step" :class="{ active: tfaStep >= 2, done: tfaStep > 2 }">2</div>
            <div class="line-step"></div>
            <div class="tfa-step" :class="{ active: tfaStep >= 3, done: tfaStep > 3 }">3</div>
          </div>

          <div class="modal-body tfa-wizard-content">
            <!-- Step 1 Scan -->
            <div v-if="tfaStep === 1" class="text-center">
              <p class="tfa-info-p">Scannez ce QR Code pour enregistrer le secret TOTP.</p>
              <div class="tfa-qr-wrap">
                <svg viewBox="0 0 100 100" width="120" height="120">
                  <rect width="100" height="100" fill="white" />
                  <rect x="5" y="5" width="25" height="25" fill="#1E293B" />
                  <rect x="10" y="10" width="15" height="15" fill="white" />
                  <rect x="70" y="5" width="25" height="25" fill="#1E293B" />
                  <rect x="75" y="10" width="15" height="15" fill="white" />
                  <rect x="5" y="70" width="25" height="25" fill="#1E293B" />
                  <rect x="10" y="75" width="15" height="15" fill="white" />
                  <rect x="40" y="40" width="20" height="20" fill="#1E293B"/>
                </svg>
              </div>
              <div class="tfa-secret-box">
                <span class="tfa-secret-lbl">Clé de configuration</span>
                <p class="tfa-secret-val">{{ mock2FASecret }}</p>
                <button class="tfa-copy-btn" @click="copySecretKey">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                  Copier
                </button>
              </div>
              <button class="btn-primary full-width modal-btn-gap" @click="tfaStep = 2; startTFACountdown()">Suivant</button>
            </div>

            <!-- Step 2 Verification -->
            <div v-if="tfaStep === 2" class="text-center">
              <p class="tfa-info-p">Entrez le jeton à 6 chiffres depuis votre application :</p>
              <div class="tfa-totp-timer">
                <svg viewBox="0 0 36 36" class="tfa-timer-ring">
                  <path class="tfa-timer-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                  <path class="tfa-timer-fill" :stroke-dasharray="tfaCountdown + ', 100'" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                </svg>
                <span class="tfa-timer-text">{{ tfaCountdown }}</span>
              </div>
              <input type="text" class="form-input text-center font-mono letter-spacing-lg" placeholder="000000" v-model="tfaToken" maxlength="6" @keyup.enter="validate2FAToken"/>
              <p class="tfa-hint">Utilisez le code : <strong class="font-mono">{{ getCurrentTOTP() }}</strong> (simulation)</p>
              <div class="modal-btn-row">
                <button class="btn-secondary flex-1" @click="tfaStep = 1">Retour</button>
                <button class="btn-primary flex-1" @click="validate2FAToken">Vérifier</button>
              </div>
            </div>

            <!-- Step 3 Backup Codes -->
            <div v-if="tfaStep === 3">
              <p class="tfa-info-p text-center">Conservez ces codes en lieu sûr pour déverrouiller votre compte.</p>
              <div class="tfa-codes-box">
                <div class="tfa-codes-grid">
                  <div v-for="code in tfaBackupCodes" :key="code" class="tfa-code-item font-mono">{{ code }}</div>
                </div>
                <button class="btn-secondary full-width btn-download-codes" @click="downloadBackupCodes">
                  Télécharger les codes (.txt)
                </button>
              </div>
              <button class="btn-primary full-width modal-btn-gap" @click="finalize2FA" :disabled="!hasDownloadedBackupCodes">
                Terminer
              </button>
            </div>

            <!-- Step 4 View Only -->
            <div v-if="tfaStep === 4">
              <p class="tfa-info-p text-center">Vos codes de secours actifs.</p>
              <div class="tfa-codes-box">
                <div class="tfa-codes-grid">
                  <div v-for="code in tfaBackupCodes" :key="code" class="tfa-code-item font-mono">{{ code }}</div>
                </div>
                <button class="btn-secondary full-width" @click="downloadBackupCodes">Télécharger (.txt)</button>
              </div>
              <button class="btn-primary full-width modal-btn-gap" @click="show2FAModal = false">Fermer</button>
            </div>
          </div>
        </div>
      </div>
    </Transition>

    <!-- Proof Modal -->
    <Transition name="modal">
      <div v-if="showProofModal" class="modal-overlay" @click.self="showProofModal = false">
        <div class="modal-box proof-modal">
          <div class="modal-header">
            <h3 class="modal-title">Justificatif de virement</h3>
            <button class="modal-close" @click="showProofModal = false">×</button>
          </div>
          <div class="modal-body">
            <p class="modal-subtitle">Réf facture : {{ selectedProofInvoice?.reference }}</p>
            <div class="dropzone"
              :class="{ dragging: isProofDragging }"
              @dragover.prevent="isProofDragging = true"
              @dragleave="isProofDragging = false"
              @drop.prevent="handleProofDrop">
              <p class="dropzone-text">Glissez ou déposez votre fichier ici</p>
              <button class="btn-secondary small" @click="$refs.proofInput.click()">Parcourir</button>
              <input type="file" ref="proofInput" accept=".pdf,image/*" class="hidden-input" @change="handleProofFileSelect"/>
            </div>
            <button class="btn-primary full-width modal-btn-gap" @click="submitProof">Envoyer le document</button>
          </div>
        </div>
      </div>
    </Transition>

    <!-- Ticket Creation Modal -->
    <Transition name="modal">
      <div v-if="showNewTicketModal" class="modal-overlay" @click.self="showNewTicketModal = false">
        <div class="modal-box ticket-modal">
          <div class="modal-header">
            <h3 class="modal-title">Déclarer un incident</h3>
            <button class="modal-close" @click="showNewTicketModal = false">×</button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label>Catégorie</label>
              <select class="form-input" v-model="newTicketForm.category">
                <option v-for="cat in ticketCategories" :key="cat.id" :value="cat.id">{{ cat.label }}</option>
              </select>
            </div>
            <div class="form-group">
              <label>Intitulé du problème</label>
              <input type="text" class="form-input" placeholder="Ex: Panne électrique cuisine" v-model="newTicketForm.title"/>
            </div>
            <div class="form-group">
              <label>Description du dysfonctionnement</label>
              <textarea class="form-input form-textarea" v-model="newTicketForm.description" rows="3"></textarea>
            </div>
            <button class="btn-primary full-width modal-btn-gap" @click="submitTicket">Soumettre la demande</button>
          </div>
        </div>
      </div>
    </Transition>

    <!-- Confirmation Dialog -->
    <Transition name="modal">
      <div v-if="showConfirmDialog" class="modal-overlay" @click.self="showConfirmDialog = false">
        <div class="modal-box confirm-dialog-modal">
          <div class="modal-body text-center">
            <div class="confirm-icon">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#F59E0B" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            </div>
            <p class="confirm-message">{{ confirmDialogMessage }}</p>
            <div class="confirm-actions">
              <button class="btn-secondary" @click="showConfirmDialog = false">Annuler</button>
              <button class="btn-danger" @click="confirmDialogCallback()">Confirmer</button>
            </div>
          </div>
        </div>
      </div>
    </Transition>

    <!-- 🚪 Logout Confirmation Modal — ultra premium -->
    <Transition name="modal">
      <div v-if="showLogoutModal" class="modal-overlay" @click.self="showLogoutModal = false">
        <div class="modal-box logout-modal">
          <button class="logout-close" @click="showLogoutModal = false">×</button>
          <div class="logout-graphic">
            <div class="logout-icon-ring">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>
            </div>
          </div>
          <h3 class="logout-title">Quitter votre session ?</h3>
          <p class="logout-desc">Vous allez être redirigé vers la page de connexion. Pour accéder de nouveau à votre espace, vous devrez vous reconnecter.</p>
          <div class="logout-actions">
            <button class="lo-btn lo-btn-secondary" @click="showLogoutModal = false">Annuler</button>
            <button class="lo-btn lo-btn-primary" @click="confirmLogout">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>
              Se déconnecter
            </button>
          </div>
        </div>
      </div>
    </Transition>

    <!-- 🔐 Wallet Password Gate Modal -->
    <Transition name="modal">
      <div v-if="showPasswordGate" class="modal-overlay" @click.self="cancelWalletPassword">
        <div class="modal-box password-gate-modal">
          <button class="modal-close pg-close" @click="cancelWalletPassword">×</button>
          <div class="pg-body">
            <div class="pg-icon-wrap">
              <svg class="pg-lock-icon" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                <path d="M7 11V7a5 5 0 0110 0v4"/>
                <circle cx="12" cy="16" r="1.5"/>
              </svg>
            </div>
            <p class="pg-title">Confirmation de sécurité</p>
            <p class="pg-operation">Opération : <strong>{{ walletPendingLabel }}</strong></p>
            <p class="pg-subtitle">Entrez votre code secret wallet pour autoriser cette opération.</p>
            <div class="pg-input-wrap">
              <input type="password" v-model="walletPasswordInput" @keyup.enter="submitWalletPassword" placeholder="● ● ● ●" class="wallet-password-input" maxlength="6" autofocus />
            </div>
            <p v-if="walletPasswordError" class="pg-error">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
              {{ walletPasswordError }}
            </p>
            <div class="pg-actions">
              <button class="btn-secondary pg-btn" @click="cancelWalletPassword">Annuler</button>
              <button class="btn-primary pg-btn pg-btn-confirm" @click="submitWalletPassword">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                Confirmer
              </button>
            </div>
          </div>
        </div>
      </div>
    </Transition>

    <!-- 📜 Old Contract Detail Modal — Receipt preview format -->
    <Transition name="modal">
      <div v-if="showOldContractDetail" class="modal-overlay" @click.self="showOldContractDetail = false">
        <div class="modal-box invoice-detail-modal">
          <div class="modal-header">
            <h3 class="modal-title">Aperçu document - Ancien contrat</h3>
            <button class="modal-close" @click="showOldContractDetail = false">×</button>
          </div>
          <div class="modal-body">
            <div class="rcpt-preview">
              <div class="rcpt-hdr">
                <div class="rcpt-hdr-top">
                  <div><div class="rcpt-brand">HABITATUM</div><div class="rcpt-tag">Gestion locative premium</div></div>
                  <div class="rcpt-title-wrap"><div class="rcpt-title">VERSEMENT CONTRAT</div><div class="rcpt-ori">Original</div></div>
                </div>
                <div class="rcpt-hdr-bar">
                  <span>RÉF : <strong>{{ ocDetail?.id }}</strong></span>
                  <span>DATE : <strong>—</strong></span>
                </div>
              </div>
              <div class="rcpt-body">
                <div class="rcpt-sec-title">Détails du contrat</div>
                <div class="rcpt-table">
                  <div class="rcpt-row rcpt-row-even"><span class="rcpt-label">Propriété</span><span class="rcpt-value">{{ ocDetail?.property_name }}</span></div>
                  <div class="rcpt-row"><span class="rcpt-label">Adresse</span><span class="rcpt-value">{{ ocDetail?.address }}</span></div>
                  <div class="rcpt-row rcpt-row-even"><span class="rcpt-label">Propriétaire</span><span class="rcpt-value">{{ ocDetail?.owner }}</span></div>
                  <div class="rcpt-row"><span class="rcpt-label">Début</span><span class="rcpt-value">{{ formatDate(ocDetail?.start_date) }}</span></div>
                  <div class="rcpt-row rcpt-row-even"><span class="rcpt-label">Fin</span><span class="rcpt-value">{{ formatDate(ocDetail?.end_date) }}</span></div>
                  <div class="rcpt-row"><span class="rcpt-label">Durée</span><span class="rcpt-value">{{ ocDetail?.duration }}</span></div>
                  <div class="rcpt-row rcpt-row-even"><span class="rcpt-label">Loyer mensuel</span><span class="rcpt-value">{{ formatCurrency(ocDetail?.rent) }}</span></div>
                  <div class="rcpt-row"><span class="rcpt-label">Dépôt garantie</span><span class="rcpt-value">{{ formatCurrency(ocDetail?.deposit) }}</span></div>
                </div>
                <div v-if="ocDetail?.deposit" class="rcpt-total">
                  <div class="rcpt-total-inner">
                    <span class="rcpt-total-label">Montant versé</span>
                    <span class="rcpt-total-value rcpt-total-paid">{{ formatCurrency(ocDetail.deposit) }}</span>
                  </div>
                  <div class="rcpt-total-words">Arrêté à la somme de {{ formatCurrency(ocDetail.deposit) }}</div>
                </div>
                <div class="rcpt-status">
                  <div class="rcpt-status-inner" :class="ocDetail?.status === 'ended' ? 'rcpt-st-paid' : 'rcpt-st-unpaid'">
                    {{ ocDetail?.status === 'ended' ? '✓ Contrat terminé' : '✕ Résilié' }}
                  </div>
                </div>
              </div>
              <div class="rcpt-ftr">
                <span>Merci pour votre confiance</span>
                <span><strong>HABITATUM</strong></span>
              </div>
            </div>
            <button class="btn-primary full-width modal-btn-gap" @click="downloadOldContractReceipt(ocDetail)">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
              Télécharger le PDF
            </button>
          </div>
        </div>
      </div>
    </Transition>

    <!-- 📄 Invoice Detail Modal — Receipt preview format -->
    <Transition name="modal">
      <div v-if="showInvoiceDetail" class="modal-overlay" @click.self="showInvoiceDetail = false">
        <div class="modal-box invoice-detail-modal">
          <div class="modal-header">
            <h3 class="modal-title">Aperçu du document</h3>
            <button class="modal-close" @click="showInvoiceDetail = false">×</button>
          </div>
          <div class="modal-body">
            <div class="rcpt-preview">
              <div class="rcpt-hdr">
                <div class="rcpt-hdr-top">
                  <div><div class="rcpt-brand">HABITATUM</div><div class="rcpt-tag">Gestion locative premium</div></div>
                  <div class="rcpt-title-wrap"><div class="rcpt-title">FACTURE</div><div class="rcpt-ori">Original</div></div>
                </div>
                <div class="rcpt-hdr-bar">
                  <span>RÉF : <strong>{{ invDetail?.reference }}</strong></span>
                  <span>DATE : <strong>{{ formatDateTime(invDetail?.created_at) || '—' }}</strong></span>
                </div>
              </div>
              <div class="rcpt-body">
                <div class="rcpt-sec-title">Détails</div>
                <div class="rcpt-table">
                  <div class="rcpt-row" v-for="(row, i) in invoicePreviewRows" :key="i" :class="i % 2 === 0 ? 'rcpt-row-even' : ''">
                    <span class="rcpt-label">{{ row.label }}</span>
                    <span class="rcpt-value">{{ row.value }}</span>
                  </div>
                </div>
                <div v-if="invDetail?.amount" class="rcpt-total">
                  <div class="rcpt-total-inner">
                    <span class="rcpt-total-label">Total</span>
                    <span class="rcpt-total-value" :class="invDetail?.status === 'paid' ? 'rcpt-total-paid' : 'rcpt-total-unpaid'">{{ formatCurrency(invDetail.amount) }}</span>
                  </div>
                  <div class="rcpt-total-words">Arrêté à la somme de {{ formatCurrency(invDetail.amount) }}</div>
                </div>
                <div class="rcpt-status">
                  <div class="rcpt-status-inner" :class="invDetail?.status === 'paid' ? 'rcpt-st-paid' : invDetail?.status === 'pending' ? 'rcpt-st-pending' : 'rcpt-st-unpaid'">
                    {{ invDetail?.status === 'paid' ? '✓ Payée' : invDetail?.status === 'pending' ? '● En attente' : '✕ Impayée' }}
                  </div>
                </div>
              </div>
              <div class="rcpt-ftr">
                <span>Merci pour votre confiance</span>
                <span><strong>HABITATUM</strong></span>
              </div>
            </div>
            <div class="modal-btn-row">
              <button class="btn-secondary flex-1" @click="showInvoiceDetail = false">Fermer</button>
              <button class="btn-primary flex-1" @click="downloadInvoiceReceipt(invDetail)" v-if="invDetail">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                Télécharger le PDF
              </button>
            </div>
          </div>
        </div>
      </div>
    </Transition>

    <!-- 🧾 Receipt Detail Modal — Receipt preview format -->
    <Transition name="modal">
      <div v-if="showReceiptDetail" class="modal-overlay" @click.self="showReceiptDetail = false">
        <div class="modal-box invoice-detail-modal">
          <div class="modal-header">
            <h3 class="modal-title">Aperçu du reçu</h3>
            <button class="modal-close" @click="showReceiptDetail = false">×</button>
          </div>
          <div class="modal-body">
            <div class="rcpt-preview">
              <div class="rcpt-hdr">
                <div class="rcpt-hdr-top">
                  <div><div class="rcpt-brand">HABITATUM</div><div class="rcpt-tag">Gestion locative premium</div></div>
                  <div class="rcpt-title-wrap"><div class="rcpt-title">QUITTANCE</div><div class="rcpt-ori">Original</div></div>
                </div>
                <div class="rcpt-hdr-bar">
                  <span>RÉF : <strong>{{ recDetail?.reference }}</strong></span>
                  <span>DATE : <strong>{{ recDetail?.paid_at ? formatDate(recDetail.paid_at) : '—' }}</strong></span>
                </div>
              </div>
              <div class="rcpt-body">
                <div class="rcpt-sec-title">Détails du versement</div>
                <div class="rcpt-table">
                  <div class="rcpt-row rcpt-row-even"><span class="rcpt-label">Locataire</span><span class="rcpt-value">{{ recDetail?.tenant }}</span></div>
                  <div class="rcpt-row"><span class="rcpt-label">Bien</span><span class="rcpt-value">{{ recDetail?.property }}</span></div>
                  <div class="rcpt-row rcpt-row-even"><span class="rcpt-label">Type</span><span class="rcpt-value">{{ recDetail?.title }}</span></div>
                  <div class="rcpt-row"><span class="rcpt-label">Période</span><span class="rcpt-value">{{ recDetail?.period }}</span></div>
                  <div class="rcpt-row rcpt-row-even"><span class="rcpt-label">Date versement</span><span class="rcpt-value">{{ recDetail?.paid_at ? formatDate(recDetail.paid_at) : '—' }}</span></div>
                  <div class="rcpt-row"><span class="rcpt-label">Méthode</span><span class="rcpt-value">{{ recDetail?.method }}</span></div>
                  <div class="rcpt-row rcpt-row-even" v-if="recDetail?.transaction_id"><span class="rcpt-label">Transaction</span><span class="rcpt-value">{{ recDetail?.transaction_id }}</span></div>
                  <div class="rcpt-row" v-if="recDetail?.landlord"><span class="rcpt-label">Bailleur</span><span class="rcpt-value">{{ recDetail?.landlord }}</span></div>
                </div>
                <div v-if="recDetail?.includesPenalties && recDetail?.months" class="rcpt-penalty-section">
                  <div class="rcpt-sec-title" style="margin-top:12px">Pénalités par mois</div>
                  <div class="rcpt-table">
                    <div v-for="(m, mi) in recDetail.months" :key="mi" class="rcpt-row" :class="mi % 2 === 0 ? 'rcpt-row-even' : ''">
                      <span class="rcpt-label">{{ m.label }}</span>
                      <span class="rcpt-value" style="display:flex;flex-direction:column;align-items:flex-end">
                        <span>{{ formatCurrency(m.amount) }}</span>
                        <span v-if="m.penaltyAmount > 0" style="font-size:11px;color:#DC2626">+{{ m.penaltyRate }}% pénalité : {{ formatCurrency(m.penaltyAmount) }}</span>
                      </span>
                    </div>
                  </div>
                </div>
                <div v-if="recDetail?.amount" class="rcpt-total">
                  <div class="rcpt-total-inner">
                    <span class="rcpt-total-label">Total versé</span>
                    <span class="rcpt-total-value rcpt-total-paid">{{ formatCurrency(recDetail.amount) }}</span>
                  </div>
                  <div class="rcpt-total-words">Arrêté à la somme de {{ formatCurrency(recDetail.amount) }}</div>
                </div>
                <div class="rcpt-status">
                  <div class="rcpt-status-inner rcpt-st-paid">✓ Reçu</div>
                </div>
              </div>
              <div class="rcpt-ftr">
                <span>Merci pour votre confiance</span>
                <span><strong>HABITATUM</strong></span>
              </div>
            </div>
            <div class="modal-btn-row">
              <button class="btn-secondary flex-1" @click="showReceiptDetail = false">Fermer</button>
              <button class="btn-primary flex-1" @click="generateReceiptPDF(recDetail)" v-if="recDetail">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                Télécharger le PDF
              </button>
            </div>
          </div>
        </div>
      </div>
    </Transition>

    <!-- 📋 Unified Receipts Preview Modal -->
    <Transition name="modal">
      <div v-if="showReceiptsPreview" class="modal-overlay" @click.self="showReceiptsPreview = false">
        <div class="modal-box receipts-preview-modal">
          <div class="modal-header">
            <h3 class="modal-title">Tous les reçus & factures</h3>
            <button class="modal-close" @click="showReceiptsPreview = false">×</button>
          </div>
          <div class="modal-body">
            <!-- Loyer -->
            <div class="rp-section">
              <div class="rp-section-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#3B82F6" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                Quittances de loyer
              </div>
              <div v-if="rentReceipts.length === 0" class="rp-empty">Aucune quittance de loyer.</div>
              <div v-for="r in rentReceipts" :key="r.id" class="rp-item" @click="generateReceiptPDF(r)">
                <div class="rp-item-left">
                  <span class="rp-item-ref">{{ r.reference }}</span>
                  <span class="rp-item-period">{{ r.period }}</span>
                </div>
                <div class="rp-item-right">
                  <span class="rp-item-amount">{{ formatCurrency(r.amount) }}</span>
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="7 13 12 18 17 13"/><polyline points="7 6 12 11 17 6"/></svg>
                </div>
              </div>
            </div>
            <!-- Eau & Électricité -->
            <div class="rp-section">
              <div class="rp-section-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                Factures Eau & Électricité
              </div>
              <div v-if="utilityInvoices.length === 0" class="rp-empty">Aucune facture de service.</div>
              <div v-for="inv in utilityInvoices" :key="inv.id" class="rp-item" @click="viewInvoiceDetail(inv)">
                <div class="rp-item-left">
                  <span class="rp-item-ref">{{ inv.reference }}</span>
                  <span class="rp-item-period">{{ inv.period }} · {{ inv.type === 'water' ? 'Eau' : 'Électricité' }}</span>
                </div>
                <div class="rp-item-right">
                  <span class="rp-item-status" :class="inv.status === 'paid' ? 'rp-paid' : 'rp-unpaid'">{{ inv.status === 'paid' ? 'Payée' : 'Impayée' }}</span>
                  <span class="rp-item-amount">{{ formatCurrency(inv.amount) }}</span>
                </div>
              </div>
            </div>
            <!-- Frais de contrat -->
            <div class="rp-section">
              <div class="rp-section-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#8B5CF6" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                Frais de contrat
              </div>
              <div v-if="contractReceipts.length === 0" class="rp-empty">Aucun frais de contrat enregistré.</div>
              <div v-for="r in contractReceipts" :key="r.id" class="rp-item" @click="generateReceiptPDF(r)">
                <div class="rp-item-left">
                  <span class="rp-item-ref">{{ r.reference }}</span>
                  <span class="rp-item-period">{{ r.title }}</span>
                </div>
                <div class="rp-item-right">
                  <span class="rp-item-amount">{{ formatCurrency(r.amount) }}</span>
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="7 13 12 18 17 13"/><polyline points="7 6 12 11 17 6"/></svg>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </Transition>

    <!-- 🗑️ Avatar Delete Confirm Modal -->
    <Transition name="modal">
      <div v-if="showAvatarDeleteConfirm" class="modal-overlay" @click.self="showAvatarDeleteConfirm = false">
        <div class="modal-box avatar-delete-modal">
          <div class="modal-header">
            <h3 class="modal-title">Supprimer la photo</h3>
            <button class="modal-close" @click="showAvatarDeleteConfirm = false">×</button>
          </div>
          <div class="modal-body text-center">
            <div class="avatar-delete-icon-wrap">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#EF4444" stroke-width="1.8"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
            </div>
            <p class="avatar-delete-msg">Supprimer votre photo de profil ?</p>
            <p class="avatar-delete-hint">Cette action est réversible : vous pourrez ajouter une nouvelle photo à tout moment.</p>
            <div class="modal-btn-row" style="justify-content:center;">
              <button class="btn-secondary" @click="showAvatarDeleteConfirm = false">Annuler</button>
              <button class="btn-danger" @click="confirmDeleteAvatar">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/></svg>
                Supprimer
              </button>
            </div>
          </div>
        </div>
      </div>
    </Transition>

    <!-- Toast Notification -->
    <Transition name="toast">
      <div v-if="toast.show" class="toast" :class="'toast-' + toast.type" @click="toast.show = false">
        <span>{{ toast.message }}</span>
      </div>
    </Transition>

  </div>
</template>

<script setup>
import { ref, computed, reactive, nextTick, onMounted, watch } from 'vue'
import { jsPDF } from 'jspdf'
import html2canvas from 'html2canvas'
import { useI18n } from 'vue-i18n'
import AiAssistant from '../../Components/AiAssistant.vue'

const { t, locale } = useI18n()

// ════════════════════════════════════════════════════════════
//  PROPS
// ════════════════════════════════════════════════════════════
const props = defineProps({
  auth: {
    type: Object,
    default: () => ({
      user: {
        id: 1,
        name: 'Armel Nguetsop',
        first_name: 'Armel',
        last_name: 'Nguetsop',
        email: 'armel.nguetsop@email.com',
        phone: '+237 691 234 567',
        avatar: null,
        role: 'tenant',
      }
    })
  },
  contracts: {
    type: Array,
    default: () => ([
      {
        id: 'CTR-001',
        type: 'Bail d\'habitation (3 ans)',
        start_date: '2026-01-01',
        end_date: '2027-01-01',
        rent: 185000,
        deposit: 370000,
        charges: 25000,
        revision_clause: 'Indexation annuelle IRL',
        property: {
          name: 'Appartement Bastos — Résidence Les Palmiers',
          address: 'Quartier Bastos, Yaoundé, Cameroun',
          type: 'Appartement F3',
          photo: null,
          specs: [
            { label: 'Surface', value: '78 m²' },
            { label: 'Étage', value: '3ème étage' },
            { label: 'Chambres', value: '2 chambres' },
          ],
          equipment: ['Climatiseur x2', 'Chauffe-eau électrique', 'Cuisine équipée'],
        },
        documents: [
          { id: 'd1', name: 'Pièce d\'identité (CNI/Passeport)', status: 'uploaded', filename: 'CNI_Nguetsop.pdf', url: '#', uploaded_at: '2024-01-05' },
          { id: 'd2', name: 'Justificatif de revenus', status: 'uploaded', filename: 'Bulletins_Revenus.pdf', url: '#', uploaded_at: '2024-01-05' },
          { id: 'd3', name: 'Attestation d\'assurance habitation', status: 'uploaded', filename: 'Assurance_Habitation.pdf', url: '#', uploaded_at: '2026-01-10' },
        ]
      }
    ])
  },
  invoices: {
    type: Array,
    default: () => ([
      { id: 'INV-2026-005', reference: 'INV-2026-005', type: 'RENT', period: 'Mai 2026', amount: 185000, status: 'paid', consumption: null },
      { id: 'INV-2026-004', reference: 'INV-2026-004', type: 'RENT', period: 'Avril 2026', amount: 185000, status: 'paid', consumption: null },
      { id: 'INV-2026-EAU-005', reference: 'INV-EAU-2026-005', type: 'WATER', period: 'Mai 2026', amount: 12500, status: 'paid', consumption: { value: '8.5', unit: 'm³', index: '1452' } },
      { id: 'INV-2026-ELEC-005', reference: 'INV-ELEC-2026-005', type: 'ELECTRIC', period: 'Mai 2026', amount: 18200, status: 'pending', consumption: { value: '124', unit: 'kWh', index: '08741' } },
      { id: 'INV-2026-006', reference: 'INV-2026-006', type: 'RENT', period: 'Juin 2026', amount: 185000, status: 'pending', consumption: null },
      { id: 'INV-2026-003', reference: 'INV-2026-003', type: 'RENT', period: 'Mars 2026', amount: 185000, status: 'paid', consumption: null },
    ])
  },
  tickets: {
    type: Array,
    default: () => ([
      {
        id: 'TKT-001',
        title: 'Climatiseur chambre principale en panne',
        description: 'Le climatiseur ne souffle plus d\'air froid. L\'appareil s\'allume mais l\'air reste tiède.',
        category: 'electrical',
        status: 'in_progress',
        priority: 'high',
        created_at: '2026-06-08',
        messages: [
          { id: 'm1', sender: 'tenant', text: 'Le climatiseur de la chambre principale ne souffle plus de froid depuis ce matin.', created_at: '2026-06-08T10:30:00' },
          { id: 'm2', sender: 'manager', text: 'Bonjour, nous programmons le passage d\'un réparateur pour ce vendredi matin.', created_at: '2026-06-08T14:15:00' },
        ]
      }
    ])
  },
  vapidPublicKey: {
    type: String,
    default: 'BHpjN8KknDl3Kb1ggFJ5vu7LgwcPEJ5Q7BuDNNJdgnouYNhwwBgUmEGWK_NlX0QGqA8cyIiDIH95xrT5VeiCWaM'
  }
})

// ════════════════════════════════════════════════════════════
//  REACTIVE DATA STATES
// ════════════════════════════════════════════════════════════
const activeTab = ref('overview')
const sidebarCollapsed = ref(false)
const mobileSidebarOpen = ref(false)
const isLoading = ref(false)
const progressWidth = ref(0)
const theme = ref('light')

// Copies locale pour localStorage simulations
const localInvoices = ref([])
const localTickets = ref([])

// 💳 WALLET
const walletBalance = ref(150000)
const hideBalance = ref(false)
const balanceRevealed = ref(false)
let balanceRevealTimer = null

// 🔐 Wallet password gate
const showPasswordGate = ref(false)
const walletPasswordInput = ref('')
const walletPasswordError = ref('')
const walletPendingAction = ref(null)
const walletPendingLabel = ref('')
const WALLET_PIN = '1234'

function requireWalletPassword(label, actionFn) {
  walletPasswordInput.value = ''
  walletPasswordError.value = ''
  walletPendingLabel.value = label
  walletPendingAction.value = actionFn
  showPasswordGate.value = true
}
function submitWalletPassword() {
  if (walletPasswordInput.value === WALLET_PIN) {
    walletPasswordError.value = ''
    showPasswordGate.value = false
    if (walletPendingAction.value) walletPendingAction.value()
    walletPendingAction.value = null
  } else {
    walletPasswordError.value = 'Code incorrect. Réessayez.'
  }
}
function cancelWalletPassword() {
  showPasswordGate.value = false
  walletPendingAction.value = null
  walletPasswordInput.value = ''
  walletPasswordError.value = ''
}
function toggleBalanceVisibility() {
  if (!hideBalance.value) {
    hideBalance.value = true
    return
  }
  requireWalletPassword('Afficher le solde', () => {
    hideBalance.value = false
    balanceRevealed.value = true
    if (balanceRevealTimer) clearTimeout(balanceRevealTimer)
    balanceRevealTimer = setTimeout(() => {
      hideBalance.value = true
      balanceRevealed.value = false
    }, 15000)
  })
}
const balanceFlash = ref(false)
watch(walletBalance, () => {
  balanceFlash.value = true
  setTimeout(() => { balanceFlash.value = false }, 600)
})
const transactions = ref([
  { id: 'TX-001', type: 'recharge', amount: 200000, method: 'Orange Money', date: '2026-06-09T10:30:00', status: 'success' },
  { id: 'TX-002', type: 'payment', amount: 185000, method: 'Portefeuille', description: 'Loyer Avril 2026', date: '2026-05-05T14:12:00', status: 'success' },
])

// Transfer to Landlord states
const showTransferForm = ref(false)
const transferAmount = ref('')
const transferMemo = ref('')

// Recharge Wallet States
const showRechargeModal = ref(false)
const activeRechargeTab = ref('orange')
const orangePhone = ref('')
const orangeAmount = ref('')
const orangeOtp = ref('')
const orangeOtpSent = ref(false)
const orangeOtpCountdown = ref(20)
let orangeOtpTimer = null
const processingRecharge = ref(false)

const mtnPhone = ref('')
const mtnPin = ref('')
const mtnAmount = ref('')

const cardForm = reactive({ number: '', name: '', expiry: '', cvv: '', amount: '' })
const isCardFlipped = ref(false)
const paypalAmount = ref('')

// 🗓️ RENT CONSECUTIVE SELECTION ENGINE STATE
const selectedMonthsKeys = ref([])

// 🔐 2FA保護
const show2FAModal = ref(false)
const tfaStep = ref(1)
const tfaToken = ref('')
const twoFAEnabled = ref(false)
const mock2FASecret = 'JBSW Y3DP EHPK 3PXP'
const tfaCountdown = ref(30)
let tfaCountdownTimer = null
const tfaBackupCodes = ref(['A7C3-89F2', 'E012-99AD', '5BF4-CC8D', '11A8-77F1', '90E4-3A21', 'D8F0-664C', 'C419-BD12', 'F29B-AA77'])
const hasDownloadedBackupCodes = ref(false)

// 📑 SECURITY LOGS
const loginLogs = ref([
  { id: 1, device: 'desktop', device_name: 'Chrome — Windows 11', ip: '196.210.45.12', location: 'Yaoundé, CM', date: '2026-06-10T02:30:00', status: 'success' },
  { id: 2, device: 'mobile', device_name: 'Safari — iPhone 15', ip: '196.210.45.10', location: 'Douala, CM', date: '2026-06-09T20:15:00', status: 'success' },
])

// Filters & Forms
const financeFilter = ref('all')
const invoiceSearch = ref('')
const selectedTicket = ref(null)
const newMessage = ref('')
const chatMessagesRef = ref(null)
const showNewTicketModal = ref(false)
const showProofModal = ref(false)
const selectedProofInvoice = ref(null)
const isProofDragging = ref(false)
const proofFile = ref(null)

// Messaging Extended States
const isTyping = ref(false)
const isRecording = ref(false)
const recordingTime = ref(0)
let recordingTimer = null
const attachedFile = ref(null)
const mockAudioPlayingId = ref(null)

const profileForm = reactive({
  first_name: props.auth.user.first_name || 'Armel',
  last_name: props.auth.user.last_name || 'Nguetsop',
  email: props.auth.user.email,
  phone: props.auth.user.phone || '+237 691 234 567',
  avatar: props.auth.user.avatar,
})
const passwordForm = reactive({ current: '', new_password: '', confirm: '' })
const isDragging = ref(false)
const toast = reactive({ show: false, type: 'success', message: '' })
const processingPayment = ref(false)
const savingProfile = ref(false)
const passwordStrength = ref(4)
const newTicketForm = reactive({ title: '', description: '', category: 'plumbing' })
const showConfirmDialog = ref(false)
const confirmDialogMessage = ref('')
const confirmDialogCallback = ref(() => {})

// 🚪 Logout confirmation
const showLogoutModal = ref(false)

// 🌐 Language switching animation
const switchingLang = ref(false)

// 🗑️ Avatar delete confirmation
const showAvatarDeleteConfirm = ref(false)

// 📜 Old contract detail modal
const showOldContractDetail = ref(false)
const ocDetail = ref(null)

// 📄 Invoice detail modal
const showInvoiceDetail = ref(false)
const invDetail = ref(null)

const showReceiptDetail = ref(false)
const recDetail = ref(null)

// 📋 Unified receipts preview modal
const showReceiptsPreview = ref(false)

// 💰 CONTRACT FEE PAYMENT
const contractFeePaid = ref(false)

// ════════════════════════════════════════════════════════════
//  CONSTANTS / CONFIG
// ════════════════════════════════════════════════════════════
const defaultAvatar = `data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80' viewBox='0 0 80 80'%3E%3Crect width='80' height='80' rx='40' fill='%23EFF6FF'/%3E%3Ccircle cx='40' cy='32' r='14' fill='%232563EB' opacity='0.7'/%3E%3Cellipse cx='40' cy='68' rx='22' ry='14' fill='%232563EB' opacity='0.5'/%3E%3C/svg%3E`

const navItems = computed(() => [
  { id: 'overview', label: t('nav.overview'), icon: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>', badge: null },
  { id: 'contract', label: t('nav.contract'), icon: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>', badge: null },
  { id: 'loyer', label: t('nav.loyer'), icon: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>', badge: '1' },
  { id: 'utilities', label: t('nav.utilities'), icon: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/><path d="M12 2v20"/></svg>', badge: null },
  { id: 'receipts', label: t('nav.receipts'), icon: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>', badge: null },
  { id: 'old-contracts', label: t('nav.oldContracts'), icon: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>', badge: null },
  { id: 'support', label: t('nav.support'), icon: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>', badge: '2' },
  { id: 'profile', label: t('nav.profile'), icon: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>', badge: null },
])

const rechargeTabs = [
  { id: 'orange', name: 'Orange Money', icon: 'OM' },
  { id: 'mtn', name: 'MTN MoMo', icon: 'MoMo' },
  { id: 'card', name: 'Carte Bancaire', icon: 'CB' },
  { id: 'paypal', name: 'PayPal', icon: 'PP' }
]

// 🧾 UTILITY (WATER & ELECTRICITY) MANAGEMENT
const utilityFilter = ref('all')
const utilitySearch = ref('')
const utilityFilters = [
  { id: 'all', label: 'Tous' },
  { id: 'WATER', label: 'Eau' },
  { id: 'ELECTRIC', label: 'Électricité' },
  { id: 'pending', label: 'En attente' },
]
const utilityInvoices = ref([
  { id: 'INV-EAU-2026-006', reference: 'INV-EAU-2026-006', type: 'WATER', period: 'Juin 2026', amount: 12500, status: 'pending', consumption: { value: '9.2', unit: 'm³', index: '1461' } },
  { id: 'INV-ELEC-2026-006', reference: 'INV-ELEC-2026-006', type: 'ELECTRIC', period: 'Juin 2026', amount: 19500, status: 'pending', consumption: { value: '131', unit: 'kWh', index: '08872' } },
  { id: 'INV-EAU-2026-005', reference: 'INV-EAU-2026-005', type: 'WATER', period: 'Mai 2026', amount: 12500, status: 'paid', consumption: { value: '8.5', unit: 'm³', index: '1452' } },
  { id: 'INV-ELEC-2026-005', reference: 'INV-ELEC-2026-005', type: 'ELECTRIC', period: 'Mai 2026', amount: 18200, status: 'pending', consumption: { value: '124', unit: 'kWh', index: '08741' } },
  { id: 'INV-EAU-2026-004', reference: 'INV-EAU-2026-004', type: 'WATER', period: 'Avril 2026', amount: 12500, status: 'paid', consumption: { value: '7.8', unit: 'm³', index: '1443' } },
  { id: 'INV-ELEC-2026-004', reference: 'INV-ELEC-2026-004', type: 'ELECTRIC', period: 'Avril 2026', amount: 16700, status: 'paid', consumption: { value: '112', unit: 'kWh', index: '08629' } },
])

const financeFilters = [
  { id: 'all', label: 'Tous' },
  { id: 'paid', label: 'Payés' },
  { id: 'pending', label: 'En attente' },
]

// 🧾 RECEIPTS (Quittances & Reçus)
const receiptFilter = ref('all')
const receiptSearch = ref('')
const receiptFilters = [
  { id: 'all', label: 'Tous' },
  { id: 'rent', label: 'Loyers' },
  { id: 'utilities', label: 'Eau/Électricité' },
  { id: 'contract_fee', label: 'Frais contrat' },
]
const allReceipts = ref([
  { id: 'RCPT-001', type: 'rent', title: 'Loyer Juin 2026', reference: 'QUIT-2026-006', amount: 185000, period: 'Juin 2026', paid_at: '2026-06-05', method: 'Portefeuille', transaction_id: 'TX-1749000001', property: 'Appartement Bastos', tenant: 'Armel Nguetsop', landlord: 'SCI Habitats SA' },
  { id: 'RCPT-002', type: 'rent', title: 'Loyer Mai 2026', reference: 'QUIT-2026-005', amount: 185000, period: 'Mai 2026', paid_at: '2026-05-05', method: 'Portefeuille', transaction_id: 'TX-1746400001', property: 'Appartement Bastos', tenant: 'Armel Nguetsop', landlord: 'SCI Habitats SA' },
  { id: 'RCPT-003', type: 'water', title: 'Facture Eau Mai 2026', reference: 'QUIT-EAU-2026-005', amount: 12500, period: 'Mai 2026', paid_at: '2026-05-12', method: 'Portefeuille', transaction_id: 'TX-1746480001', property: 'Appartement Bastos', tenant: 'Armel Nguetsop', landlord: 'SCI Habitats SA' },
  { id: 'RCPT-004', type: 'contract_fee', title: 'Frais de contrat (Renouvellement)', reference: 'FEE-CTR-2026-001', amount: 50000, period: '2026', paid_at: '2026-06-01', method: 'Portefeuille', transaction_id: 'TX-1748900001', property: 'Appartement Bastos', tenant: 'Armel Nguetsop', landlord: 'SCI Habitats SA' },
])

// 🏛️ OLD CONTRACTS
const oldContractSearch = ref('')
const oldContracts = ref([
  { id: 'OCTR-001', property_name: 'Studio Mvog-Mbi', address: 'Quartier Mvog-Mbi, Yaoundé', owner: 'M. Bidzogo Evariste', start_date: '2021-03-01', end_date: '2023-02-28', rent: 95000, deposit: 190000, duration: '2 ans', status: 'ended', documents: ['Bail signé', 'État des lieux'] },
  { id: 'OCTR-002', property_name: 'Appartement Ekounou', address: 'Quartier Ekounou, Douala', owner: 'Mme. Nkongo Sophie', start_date: '2019-06-01', end_date: '2021-05-31', rent: 120000, deposit: 240000, duration: '2 ans', status: 'ended', documents: ['Bail signé', 'État des lieux', 'Quittance de régularisation'] },
])

const ticketCategories = [
  { id: 'plumbing', label: '🔧 Plomberie' },
  { id: 'electrical', label: '⚡ Electricité' },
  { id: 'carpentry', label: '🪵 Menuiserie' },
  { id: 'other', label: '❓ Autre' },
]

const priorities = [
  { id: 'low', label: 'Faible' },
  { id: 'medium', label: 'Moyenne' },
  { id: 'high', label: 'Haute' },
]

const notifPreferences = ref([
  { id: 'rent_reminder', label: 'Rappels de loyers', description: 'Alertes 5 jours avant échéance', channels: ['email', 'push'] },
  { id: 'payment_confirm', label: 'Reçus de paiement', description: 'Confirmation immédiate de quittance', channels: ['email'] },
  { id: 'ticket_update', label: 'Modifications tickets', description: 'Notifications de réponse du bailleur', channels: ['email', 'push'] },
  { id: 'security_alert', label: 'Sécurité de compte', description: 'Tentatives de connexion ou modifs 2FA', channels: ['email', 'sms', 'push'] }
])

const mobileIcon = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="2" width="14" height="20" rx="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>`
const desktopIcon = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>`

// ════════════════════════════════════════════════════════════
//  CHART CONFIG
// ════════════════════════════════════════════════════════════

// ════════════════════════════════════════════════════════════
//  LOCAL STORAGE MANAGEMENT
// ════════════════════════════════════════════════════════════
function loadPersistedState() {
  theme.value = localStorage.getItem('hab_theme') || 'light'
  
  const savedTab = localStorage.getItem('hab_active_tab')
  if (savedTab) activeTab.value = savedTab

  const savedProfile = localStorage.getItem('hab_profile_draft')
  if (savedProfile) Object.assign(profileForm, JSON.parse(savedProfile))

  const savedWallet = localStorage.getItem('hab_wallet_balance')
  if (savedWallet) walletBalance.value = parseFloat(savedWallet)

  const savedTransactions = localStorage.getItem('hab_transactions')
  if (savedTransactions) transactions.value = JSON.parse(savedTransactions)

  const savedInvoices = localStorage.getItem('hab_invoices')
  if (savedInvoices) {
    localInvoices.value = JSON.parse(savedInvoices)
  } else {
    localInvoices.value = [...props.invoices]
  }

  const savedTickets = localStorage.getItem('hab_tickets')
  if (savedTickets) {
    localTickets.value = JSON.parse(savedTickets)
  } else {
    localTickets.value = [...props.tickets]
  }

  const saved2FA = localStorage.getItem('hab_2fa_enabled')
  if (saved2FA) twoFAEnabled.value = JSON.parse(saved2FA)

  // Reset selected months on mount
  selectedMonthsKeys.value = []

  const savedTicketDraft = localStorage.getItem('hab_ticket_draft')
  if (savedTicketDraft) Object.assign(newTicketForm, JSON.parse(savedTicketDraft))

  const savedPasswordDraft = localStorage.getItem('hab_password_draft')
  if (savedPasswordDraft) Object.assign(passwordForm, JSON.parse(savedPasswordDraft))

  const savedSidebarState = localStorage.getItem('hab_sidebar_collapsed')
  if (savedSidebarState) sidebarCollapsed.value = JSON.parse(savedSidebarState)

  const savedHideBalance = localStorage.getItem('hab_hide_balance')
  if (savedHideBalance) hideBalance.value = JSON.parse(savedHideBalance)

  const savedNotifPrefs = localStorage.getItem('hab_notif_prefs')
  if (savedNotifPrefs) notifPreferences.value = JSON.parse(savedNotifPrefs)
}

watch(activeTab, (val) => localStorage.setItem('hab_active_tab', val))
watch(walletBalance, (val) => localStorage.setItem('hab_wallet_balance', val))
watch(transactions, (val) => localStorage.setItem('hab_transactions', JSON.stringify(val)), { deep: true })
watch(localInvoices, (val) => localStorage.setItem('hab_invoices', JSON.stringify(val)), { deep: true })
watch(localTickets, (val) => localStorage.setItem('hab_tickets', JSON.stringify(val)), { deep: true })
watch(twoFAEnabled, (val) => localStorage.setItem('hab_2fa_enabled', JSON.stringify(val)))
watch(profileForm, (val) => localStorage.setItem('hab_profile_draft', JSON.stringify(val)), { deep: true })
watch(selectedMonthsKeys, (val) => localStorage.setItem('hab_selected_months', JSON.stringify(val)), { deep: true })
watch(sidebarCollapsed, (val) => localStorage.setItem('hab_sidebar_collapsed', JSON.stringify(val)))
watch(hideBalance, (val) => localStorage.setItem('hab_hide_balance', JSON.stringify(val)))
watch(newTicketForm, (val) => localStorage.setItem('hab_ticket_draft', JSON.stringify(val)), { deep: true })
watch(passwordForm, (val) => localStorage.setItem('hab_password_draft', JSON.stringify(val)), { deep: true })

// ════════════════════════════════════════════════════════════
//  COMPUTED METRICS
// ════════════════════════════════════════════════════════════
const currentNavLabel = computed(() => t('nav.' + (activeTab.value || 'overview')))
const currentRent = computed(() => props.contracts[0]?.rent || 0)
const currentRentStatus = computed(() => {
  const currentMonth = '2026-' + String(new Date().getMonth() + 1).padStart(2, '0')
  const found = rentMonths.value.find(m => m.key === currentMonth)
  return found && found.status === 'paid' ? 'paid' : 'pending'
})

const invoicePreviewRows = computed(() => {
  const d = invDetail.value
  if (!d) return []
  const typeLabel = d.type === 'water' || d.type === 'WATER' ? 'Eau' : d.type === 'electricity' || d.type === 'ELECTRIC' ? 'Électricité' : d.type === 'rent' || d.type === 'RENT' ? 'Loyer' : d.type
  const rows = [
    { label: 'Référence', value: d.reference },
    { label: 'Type', value: typeLabel },
    { label: 'Période', value: d.period },
    { label: 'Montant', value: formatCurrency(d.amount) },
    { label: 'Échéance', value: d.due_date ? formatDate(d.due_date) : '—' },
    { label: 'Statut', value: d.status === 'paid' ? 'Payée' : d.status === 'pending' ? 'En attente' : 'Impayée' },
  ]
  if (d.consumption) {
    rows.splice(3, 0, { label: 'Consommation', value: `${d.consumption.value} ${d.consumption.unit}` })
    if (d.consumption.index) rows.splice(4, 0, { label: 'Index compteur', value: d.consumption.index })
  }
  return rows
})

const totalDue = computed(() => {
  // Unpaid rents from contract months (incl. penalties)
  const unpaidRentAmt = rentMonths.value.filter(m => m.status === 'unpaid').reduce((acc, m) => acc + m.totalDue, 0)
  // Unpaid utility bills
  const unpaidBillsAmt = localInvoices.value.filter(i => i.type !== 'RENT' && i.status !== 'paid').reduce((acc, i) => acc + i.amount, 0)
  return unpaidRentAmt + unpaidBillsAmt
})
const openTicketsCount = computed(() => localTickets.value.filter(t => t.status !== 'closed').length)

const filteredInvoices = computed(() => {
  let list = [...localInvoices.value].filter(i => i.type === 'RENT')
  if (financeFilter.value !== 'all') {
    list = list.filter(i => i.status === financeFilter.value)
  }
  if (invoiceSearch.value.trim()) {
    const s = invoiceSearch.value.toLowerCase()
    list = list.filter(i => i.reference.toLowerCase().includes(s) || i.period.toLowerCase().includes(s))
  }
  return list
})

const totalPaid12Months = computed(() => {
  const paidRents = rentMonths.value.filter(m => m.status === 'paid').reduce((acc, m) => acc + m.amount, 0)
  const paidBills = localInvoices.value.filter(i => i.type !== 'RENT' && i.status === 'paid').reduce((acc, i) => acc + i.amount, 0)
  return paidRents + paidBills
})
const paidRentsCount = computed(() => rentMonths.value.filter(m => m.status === 'paid').length)
const totalRentsCount = computed(() => rentMonths.value.length)
const pendingInvoicesCount = computed(() => {
  return rentMonths.value.filter(m => m.status === 'unpaid').length + localInvoices.value.filter(i => i.type !== 'RENT' && i.status === 'pending').length
})

const showPenaltyBanner = ref(true)
function dismissPenaltyBanner() { showPenaltyBanner.value = false }
const penaltyInfo = computed(() => {
  const now = reactiveNow.value || new Date()
  const day = now.getDate()
  const unpaid = rentMonths.value.filter(m => m.status === 'unpaid' && m.penaltyRate > 0)
  if (unpaid.length === 0) {
    return { isOverdue: false, period: '', rentAmount: 0, penaltyRate: 0, penaltyAmount: 0, totalDue: 0, day, dueDay: 10, details: [], unpaidCount: 0 }
  }
  const first = unpaid[0]
  return {
    isOverdue: true,
    period: first.label,
    rentAmount: first.amount,
    penaltyRate: first.penaltyRate,
    penaltyAmount: first.penaltyAmount,
    totalDue: first.totalDue,
    day,
    dueDay: 10,
    details: unpaid.map(m => ({
      period: m.label,
      rent: m.amount,
      penalty: m.penaltyAmount,
      rate: m.penaltyRate,
      totalDue: m.totalDue,
    })),
    unpaidCount: unpaid.length,
  }
})

// 🧾 UTILITIES COMPUTED
const waterPendingCount = computed(() => utilityInvoices.value.filter(i => i.type === 'WATER' && i.status === 'pending').length)
const elecPendingCount = computed(() => utilityInvoices.value.filter(i => i.type === 'ELECTRIC' && i.status === 'pending').length)
const utilitiesTotalDue = computed(() => utilityInvoices.value.filter(i => i.status === 'pending').reduce((s, i) => s + i.amount, 0))
const latestUtilityDate = computed(() => {
  const paid = utilityInvoices.value.filter(i => i.status === 'paid')
  if (paid.length === 0) return 'Aucune'
  return paid.sort((a, b) => b.period.localeCompare(a.period))[0].period
})
const filteredUtilityInvoices = computed(() => {
  let list = [...utilityInvoices.value]
  if (utilityFilter.value !== 'all') {
    if (utilityFilter.value === 'pending') list = list.filter(i => i.status === 'pending')
    else list = list.filter(i => i.type === utilityFilter.value)
  }
  if (utilitySearch.value.trim()) {
    const s = utilitySearch.value.toLowerCase()
    list = list.filter(i => i.reference.toLowerCase().includes(s) || i.period.toLowerCase().includes(s))
  }
  return list
})

// Premium utility card helpers
const latestWater = computed(() => utilityInvoices.value.filter(i => i.type === 'WATER').sort((a, b) => b.period.localeCompare(a.period))[0])
const latestElec = computed(() => utilityInvoices.value.filter(i => i.type === 'ELECTRIC').sort((a, b) => b.period.localeCompare(a.period))[0])
const latestWaterConso = computed(() => latestWater.value?.consumption?.value || '—')
const latestWaterUnit = computed(() => latestWater.value?.consumption?.unit || '')
const latestWaterCost = computed(() => latestWater.value?.amount || 0)
const latestWaterPeriod = computed(() => latestWater.value?.period || '')
const waterStatusClass = computed(() => latestWater.value?.status === 'paid' ? 'badge-paid' : 'badge-pending')
const waterStatusLabel = computed(() => latestWater.value?.status === 'paid' ? 'Payé' : 'En attente')
const waterBarPct = computed(() => {
  const w = utilityInvoices.value.filter(i => i.type === 'WATER')
  const max = Math.max(...w.map(i => parseFloat(i.consumption?.value || 0)), 10)
  return (parseFloat(latestWater.value?.consumption?.value || 0) / max) * 100
})
const latestElecConso = computed(() => latestElec.value?.consumption?.value || '—')
const latestElecUnit = computed(() => latestElec.value?.consumption?.unit || '')
const latestElecCost = computed(() => latestElec.value?.amount || 0)
const latestElecPeriod = computed(() => latestElec.value?.period || '')
const elecStatusClass = computed(() => latestElec.value?.status === 'paid' ? 'badge-paid' : 'badge-pending')
const elecStatusLabel = computed(() => latestElec.value?.status === 'paid' ? 'Payé' : 'En attente')
const elecBarPct = computed(() => {
  const e = utilityInvoices.value.filter(i => i.type === 'ELECTRIC')
  const max = Math.max(...e.map(i => parseFloat(i.consumption?.value || 0)), 100)
  return (parseFloat(latestElec.value?.consumption?.value || 0) / max) * 100
})

// 🧾 RECEIPTS COMPUTED
const filteredReceipts = computed(() => {
  let list = [...allReceipts.value]
  if (receiptFilter.value !== 'all') {
    list = list.filter(r => r.type === receiptFilter.value)
  }
  if (receiptSearch.value.trim()) {
    const s = receiptSearch.value.toLowerCase()
    list = list.filter(r => r.title.toLowerCase().includes(s) || r.reference.toLowerCase().includes(s) || r.period.toLowerCase().includes(s))
  }
  return list.sort((a, b) => new Date(b.paid_at) - new Date(a.paid_at))
})

// 📋 RECEIPTS PREVIEW COMPUTED
const rentReceipts = computed(() => allReceipts.value.filter(r => r.type === 'rent'))
const contractReceipts = computed(() => allReceipts.value.filter(r => r.type === 'contract_fee'))

// 🏛️ OLD CONTRACTS COMPUTED
const filteredOldContracts = computed(() => {
  let list = [...oldContracts.value]
  if (oldContractSearch.value.trim()) {
    const s = oldContractSearch.value.toLowerCase()
    list = list.filter(oc => oc.property_name.toLowerCase().includes(s) || oc.address.toLowerCase().includes(s) || oc.owner.toLowerCase().includes(s))
  }
  return list
})

const formattedToday = computed(() => {
  return new Date().toLocaleDateString('fr-FR', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })
})

const passwordStrengthClass = computed(() => ['strength-weak', 'strength-medium', 'strength-strong', 'strength-very-strong'][Math.min(passwordStrength.value - 1, 3)] || 'strength-weak')
const passwordStrengthLabel = computed(() => ['Très faible', 'Faible', 'Moyen', 'Fort', 'Très fort'][passwordStrength.value] || '')

// 🗓️ RENT CONSECUTIVE MONTHS GENERATOR

function getMonthPenalty(monthKey, monthIndex, year, nowDate) {
  const now = nowDate || new Date()
  const currentYear = now.getFullYear()
  const currentMonth = now.getMonth()
  const day = now.getDate()

  // Build a Date for the month's start
  const monthDate = new Date(year, monthIndex, 1)
  const currentDate = new Date(currentYear, currentMonth, 1)

  if (monthDate < currentDate) {
    // Past month — locked at 15%
    return { rate: 15, amount: 0 } // amount calculated below
  } else if (monthDate > currentDate) {
    // Future month — no penalty
    return { rate: 0, amount: 0 }
  }
  // Current month — based on day
  let rate = 0
  if (day >= 21) rate = 15
  else if (day >= 16) rate = 10
  else if (day >= 11) rate = 5
  return { rate, amount: 0 }
}

const rentMonths = computed(() => {
  const months = []
  if (!props.contracts || props.contracts.length === 0) return []
  const contract = props.contracts[0]
  const start = new Date(contract.start_date)
  const end = new Date(contract.end_date)
  
  let current = new Date(start.getFullYear(), start.getMonth(), 1)

  while (current <= end) {
    const year = current.getFullYear()
    const monthIndex = current.getMonth()
    const monthNum = String(monthIndex + 1).padStart(2, '0')
    const key = `${year}-${monthNum}`
    
    const label = current.toLocaleDateString('fr-FR', { month: 'long', year: 'numeric' })
    const capitalizedLabel = label.charAt(0).toUpperCase() + label.slice(1)
    
    // All months forced unpaid
    const status = 'unpaid'
    
    const penalty = getMonthPenalty(key, monthIndex, year, reactiveNow.value)
    const penaltyAmount = Math.round(contract.rent * penalty.rate / 100)
    
    months.push({
      key,
      label: capitalizedLabel,
      amount: contract.rent,
      status,
      year,
      month: monthIndex,
      penaltyRate: penalty.rate,
      penaltyAmount,
      totalDue: contract.rent + penaltyAmount,
    })
    
    current.setMonth(current.getMonth() + 1)
  }
  return months
})

const rentMonthsByYear = computed(() => {
  const groups = {}
  for (const m of rentMonths.value) {
    const year = m.key.split('-')[0]
    if (!groups[year]) groups[year] = []
    groups[year].push(m)
  }
  return Object.entries(groups).sort((a, b) => b[0].localeCompare(a[0]))
})

const selectedMonthsNames = computed(() => {
  return rentMonths.value.filter(m => selectedMonthsKeys.value.includes(m.key)).map(m => m.label).join(', ')
})
const totalPaymentAmount = computed(() => {
  return rentMonths.value
    .filter(m => selectedMonthsKeys.value.includes(m.key))
    .reduce((sum, m) => sum + m.totalDue, 0)
})
const selectedPenaltyAmount = computed(() => {
  return rentMonths.value
    .filter(m => selectedMonthsKeys.value.includes(m.key))
    .reduce((sum, m) => sum + m.penaltyAmount, 0)
})

// Credit card display formatting helper
const formattedCardNumber = computed(() => {
  const num = cardForm.number.replace(/\s+/g, '')
  const parts = []
  for (let i = 0; i < num.length; i += 4) {
    parts.push(num.substring(i, i + 4))
  }
  return parts.join(' ')
})

// ════════════════════════════════════════════════════════════
//  METHODS
// ════════════════════════════════════════════════════════════
// 🤖 AI Assistant action handler
function handleAiAction(actionId) {
  // Theme toggle (no tab change needed)
  if (actionId === 'toggle_theme') {
    toggleTheme()
    showToast('success', 'Thème basculé ✅')
    return
  }

  // Recharge (needs modal, no tab switch needed if already visible)
  if (actionId === 'navigate_recharge' || actionId === 'open_recharge') {
    if (activeTab.value !== 'overview') switchTab('overview')
    nextTick(() => openRechargeModal())
    showToast('success', 'Recharge ouverte ✅')
    return
  }

  // Payment (switch to loyer tab + open modal)
  if (actionId === 'navigate_payment' || actionId === 'pay_selected_rent') {
    if (activeTab.value !== 'loyer') switchTab('loyer')
    nextTick(() => { setTimeout(() => openPaymentConfirm(), 500) })
    showToast('success', 'Paiement en cours ✅')
    return
  }

  // Transfer form
  if (actionId === 'open_transfer') {
    if (activeTab.value !== 'overview') switchTab('overview')
    nextTick(() => { showTransferForm.value = true })
    showToast('success', 'Transfert ouvert ✅')
    return
  }

  // New support ticket
  if (actionId === 'open_new_ticket') {
    if (activeTab.value !== 'support') switchTab('support')
    nextTick(() => { openNewTicket() })
    showToast('success', 'Nouveau ticket ✅')
    return
  }

  // Receipts preview
  if (actionId === 'open_receipts') {
    nextTick(() => { showReceiptsPreview.value = true })
    showToast('success', 'Reçus affichés ✅')
    return
  }

  // Contract fee payment
  if (actionId === 'open_contract_fee') {
    if (activeTab.value !== 'contract') switchTab('contract')
    showToast('success', 'Frais de contrat ✅')
    return
  }

  // Tab navigation map
  const tabMap = {
    navigate_overview: 'overview',
    navigate_contract: 'contract',
    navigate_loyer: 'loyer',
    navigate_rent: 'loyer',
    navigate_payment: 'loyer',
    navigate_support: 'support',
    navigate_profile: 'profile',
    navigate_utilities: 'utilities',
    navigate_invoices: 'loyer',
    navigate_receipts: 'receipts',
    navigate_recharge: 'recharge',
    navigate_notifications: 'profile',
    navigate_old_contracts: 'old-contracts',
  }
  const tab = tabMap[actionId]
  if (tab && tab !== activeTab.value) {
    switchTab(tab)
  }

  showToast('success', 'Action exécutée ✅')
}

function switchTab(id) {
  if (id === activeTab.value) return

  // If on PIN step when switching tab, close modals and save pending state for return
  if (paymentFlowStep.value === 'pin') {
    paymentFlowStep.value = 'recap'
    const modalMap = { loyer: 'rent', utilities: 'utility', contract: 'contract' }
    pendingPaymentModal.value = modalMap[activeTab.value] || null
    showPaymentConfirmModal.value = false
    showUtilityConfirmModal.value = false
    showContractFeeConfirmModal.value = false
  }

  triggerLoader()
  activeTab.value = id
  mobileSidebarOpen.value = false

  // If returning to the tab with a pending payment, reopen the recap modal
  if (pendingPaymentModal.value) {
    const tabMap = { rent: 'loyer', utility: 'utilities', contract: 'contract' }
    const expectedTab = tabMap[pendingPaymentModal.value]
    if (id === expectedTab) {
      const modalMap = { rent: 'showPaymentConfirmModal', utility: 'showUtilityConfirmModal', contract: 'showContractFeeConfirmModal' }
      const modalRef = modalMap[pendingPaymentModal.value]
      if (modalRef === 'showPaymentConfirmModal') showPaymentConfirmModal.value = true
      else if (modalRef === 'showUtilityConfirmModal') showUtilityConfirmModal.value = true
      else if (modalRef === 'showContractFeeConfirmModal') showContractFeeConfirmModal.value = true
      paymentFlowStep.value = 'recap'
      pendingPaymentModal.value = null
    }
  }
}

function switchLocale(loc) {
  if (locale.value === loc || switchingLang.value) return
  switchingLang.value = true
  setTimeout(() => {
    locale.value = loc
    localStorage.setItem('locale', loc)
    requestAnimationFrame(() => {
      requestAnimationFrame(() => {
        switchingLang.value = false
      })
    })
  }, 190)
}

function triggerLoader() {
  isLoading.value = true
  progressWidth.value = 0
  const steps = [20, 55, 80, 100]
  let i = 0
  const interval = setInterval(() => {
    progressWidth.value = steps[i++]
    if (i >= steps.length) {
      clearInterval(interval)
      setTimeout(() => { isLoading.value = false; progressWidth.value = 0 }, 300)
    }
  }, 120)
}

function formatCurrency(amount) {
  if (!amount && amount !== 0) return '—'
  return new Intl.NumberFormat('fr-CM', { style: 'currency', currency: 'XAF', minimumFractionDigits: 0 }).format(amount)
}
function formatDate(dateStr) {
  if (!dateStr) return '—'
  return new Date(dateStr).toLocaleDateString('fr-FR', { day: '2-digit', month: 'long', year: 'numeric' })
}
function formatDateTime(dateStr) {
  if (!dateStr) return '—'
  return new Date(dateStr).toLocaleDateString('fr-FR', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' })
}
function formatTime(dateStr) {
  return new Date(dateStr).toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })
}

function rentStatusLabel(s) { return { paid: 'Payé ✓', pending: 'En attente' }[s] || s }
function invoiceStatusLabel(s) { return { paid: 'Payé', pending: 'En attente' }[s] || s }
function invoiceTypeLabel(t) { return { RENT: 'Loyer', WATER: 'Eau', ELECTRIC: 'Electricité' }[t] || t }
function ticketStatusLabel(s) { return { open: 'Ouvert', in_progress: 'En cours', closed: 'Clôturé' }[s] || s }
function ticketCategoryIcon(cat) {
  const icons = {
    plumbing: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/></svg>',
    electrical: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>',
    carpentry: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/></svg>',
    other: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3M12 17h.01"/></svg>',
  }
  return icons[cat] || icons.other
}

// 🗓️ RENT CONSECUTIVE MONTHS SELECT TRIGGER
function toggleMonthSelection(month) {
  if (month.status === 'paid') return
  const unpaidMonths = rentMonths.value.filter(m => m.status === 'unpaid')
  const index = unpaidMonths.findIndex(m => m.key === month.key)
  if (index === -1) return
  const isSelected = selectedMonthsKeys.value.includes(month.key)
  if (isSelected) {
    // Deselect this month AND all months after it (enforce consecutive backward)
    const sortedSelected = [...selectedMonthsKeys.value].sort()
    const selIdx = sortedSelected.indexOf(month.key)
    if (selIdx !== -1) {
      const toRemove = sortedSelected.slice(selIdx)
      selectedMonthsKeys.value = selectedMonthsKeys.value.filter(k => !toRemove.includes(k))
    }
  } else {
    // Auto-select all unpaid months before this one to enforce consecutive forward
    if (index > 0) {
      const precedingUnpaid = unpaidMonths.slice(0, index)
      const missingPreceding = precedingUnpaid.filter(m => !selectedMonthsKeys.value.includes(m.key))
      if (missingPreceding.length > 0) {
        const keysToSelect = [...missingPreceding.map(m => m.key), month.key]
        const alreadySel = selectedMonthsKeys.value.filter(k => unpaidMonths.some(m => m.key === k && m.key !== month.key))
        const fullSelection = [...keysToSelect, ...alreadySel].sort()
        selectedMonthsKeys.value = fullSelection
        showToast('success', 'Sélection consécutive : mois antérieurs inclus automatiquement')
      } else {
        selectedMonthsKeys.value = [...selectedMonthsKeys.value, month.key]
      }
    } else {
      selectedMonthsKeys.value = [...selectedMonthsKeys.value, month.key]
    }
  }
}

// Payment confirmation modal + success state
const showPaymentConfirmModal = ref(false)
const lastPaymentReceipt = ref(null)
// Shared flow step for recap → PIN → success within a single overlay
const paymentFlowStep = ref('idle') // 'idle' | 'recap' | 'pin' | 'success'
const pendingPaymentModal = ref(null) // 'rent' | 'utility' | 'contract' | null

// 🔧 Utility payment modal + success
const showUtilityConfirmModal = ref(false)
const selectedUtilityInv = ref(null)
const lastUtilityReceipt = ref(null)

function openUtilityConfirm(inv) {
  if (walletBalance.value < inv.amount) {
    showToast('error', 'Solde insuffisant pour cette facture.')
    openRechargeModal()
    return
  }
  selectedUtilityInv.value = inv
  paymentFlowStep.value = 'recap'
  showUtilityConfirmModal.value = true
}

function confirmUtilityPayment() {
  const inv = selectedUtilityInv.value
  if (!inv) return
  openPremiumPinGate(inv.amount, 'utility', () => executeUtilityPayment(inv))
  paymentFlowStep.value = 'pin'
}

function executeUtilityPayment(inv) {
  processingPayment.value = true
  setTimeout(() => {
    walletBalance.value -= inv.amount
    inv.status = 'paid'
    transactions.value.unshift({
      id: 'TX-' + Date.now(), type: 'payment', amount: inv.amount,
      method: 'Portefeuille', description: `Facture ${inv.type === 'WATER' ? 'Eau' : 'Électricité'} - ${inv.period}`,
      date: new Date().toISOString(), status: 'success'
    })
    const receipt = {
      id: 'RCPT-' + Date.now(), type: inv.type === 'WATER' ? 'water' : 'electric',
      title: `Facture ${inv.type === 'WATER' ? 'Eau' : 'Électricité'} ${inv.period}`,
      reference: inv.reference.replace('INV', 'QUIT'), amount: inv.amount,
      period: inv.period, paid_at: new Date().toISOString().split('T')[0],
      method: 'Portefeuille', transaction_id: 'TX-' + Date.now(),
      property: props.contracts[0]?.property?.name || 'Appartement',
      tenant: `${profileForm.first_name} ${profileForm.last_name}`,
      landlord: 'SCI Habitats SA'
    }
    allReceipts.value.unshift(receipt)
    lastUtilityReceipt.value = receipt
    processingPayment.value = false
    showToast('success', `Facture ${inv.reference} payée ! Reçu généré.`)
  }, 600)
}

// 📄 Contract fee payment modal + success
const showContractFeeConfirmModal = ref(false)
const lastContractFeeReceipt = ref(null)

function openContractFeeConfirm() {
  if (walletBalance.value < 50000) {
    showToast('error', 'Solde insuffisant. Rechargez votre portefeuille.')
    openRechargeModal()
    return
  }
  paymentFlowStep.value = 'recap'
  showContractFeeConfirmModal.value = true
}

function confirmContractFeePayment() {
  openPremiumPinGate(50000, 'contract', executeContractFeePayment)
  paymentFlowStep.value = 'pin'
}

function executeContractFeePayment() {
  processingPayment.value = true
  setTimeout(() => {
    walletBalance.value -= 50000
    contractFeePaid.value = true
    transactions.value.unshift({
      id: 'TX-' + Date.now(), type: 'payment', amount: 50000,
      method: 'Portefeuille', description: 'Frais de contrat / Renouvellement de bail',
      date: new Date().toISOString(), status: 'success'
    })
    const receipt = {
      id: 'RCPT-' + Date.now(), type: 'contract_fee',
      title: 'Frais de contrat (Renouvellement)',
      reference: 'FEE-CTR-' + Date.now(), amount: 50000,
      period: new Date().getFullYear().toString(),
      paid_at: new Date().toISOString().split('T')[0],
      method: 'Portefeuille', transaction_id: 'TX-' + Date.now(),
      property: props.contracts[0]?.property?.name || 'Appartement',
      tenant: `${profileForm.first_name} ${profileForm.last_name}`,
      landlord: 'SCI Habitats SA'
    }
    allReceipts.value.unshift(receipt)
    lastContractFeeReceipt.value = receipt
    processingPayment.value = false
    showToast('success', 'Frais de contrat réglés ! Reçu généré.')
  }, 600)
}

// 🔐 Premium wallet PIN gate (generic reusable)
const rentWalletPinInput = ref('')
const rentWalletPinError = ref('')
const rentWalletPinAttempts = ref(0)
const rentPinInputEl = ref(null)
const pinDotsRipple = ref(false)
const premiumPinAmount = ref(0)
const premiumPinSuccessType = ref('rent') // 'rent' | 'utility' | 'contract'
const premiumPinResolve = ref(null)
// Track which tab triggered the PIN gate so overlays stay scoped to that tab
const premiumPinOriginTab = ref(null)

const isPinTabActive = computed(() => {
  if (!premiumPinOriginTab.value) return false
  return activeTab.value === premiumPinOriginTab.value
})
const isRentSuccessTabActive = computed(() => premiumPinSuccessType.value === 'rent' && activeTab.value === 'loyer')
const isUtilitySuccessTabActive = computed(() => premiumPinSuccessType.value === 'utility' && activeTab.value === 'utilities')
const isContractSuccessTabActive = computed(() => premiumPinSuccessType.value === 'contract' && activeTab.value === 'contract')

function openPremiumPinGate(amount, successType, onSuccess) {
  premiumPinAmount.value = amount
  premiumPinSuccessType.value = successType
  premiumPinResolve.value = onSuccess
  const originMap = { rent: 'loyer', utility: 'utilities', contract: 'contract' }
  premiumPinOriginTab.value = originMap[successType] || null
  rentWalletPinInput.value = ''
  rentWalletPinError.value = ''
  rentWalletPinAttempts.value = 0
  setTimeout(focusRentPinInput, 200)
}

function focusRentPinInput() {
  setTimeout(() => {
    if (rentPinInputEl.value) rentPinInputEl.value.focus()
  }, 50)
}

/* ── Premium success chime ── */
function playSuccessChime() {
  try {
    const ctx = new (window.AudioContext || window.webkitAudioContext)()
    const notes = [523.25, 587.33, 659.25, 783.99]
    notes.forEach((freq, i) => {
      const osc = ctx.createOscillator()
      const gain = ctx.createGain()
      osc.type = 'sine'
      osc.frequency.value = freq
      const t = i * 0.08
      gain.gain.setValueAtTime(0, t)
      gain.gain.linearRampToValueAtTime(0.12, t + 0.03)
      gain.gain.exponentialRampToValueAtTime(0.001, t + 0.6)
      osc.connect(gain).connect(ctx.destination)
      osc.start(t)
      osc.stop(t + 0.65)
    })
  } catch (_) {}
}

function onPinDotsClick() {
  pinDotsRipple.value = true
  focusRentPinInput()
  setTimeout(() => { pinDotsRipple.value = false }, 400)
}

function onRentPinInput() {
  rentWalletPinInput.value = rentWalletPinInput.value.replace(/\D/g, '').slice(0, 4)
  if (rentWalletPinInput.value.length === 4) {
    setTimeout(() => submitRentWalletPin(), 200)
  }
}

function submitRentWalletPin() {
  if (rentWalletPinInput.value === WALLET_PIN) {
    rentWalletPinError.value = ''
    playSuccessChime()
    paymentFlowStep.value = 'success'
    setTimeout(() => {
      paymentFlowStep.value = 'idle'
      showPaymentConfirmModal.value = false
      showUtilityConfirmModal.value = false
      showContractFeeConfirmModal.value = false
      premiumPinOriginTab.value = null
      if (premiumPinResolve.value) {
        const resolve = premiumPinResolve.value
        premiumPinResolve.value = null
        resolve()
      }
    }, 2200)
  } else {
    rentWalletPinAttempts.value++
    rentWalletPinError.value = 'Code incorrect. Tentative ' + (rentWalletPinAttempts.value + 1) + '/3'
    rentWalletPinInput.value = ''
    setTimeout(focusRentPinInput, 100)
  }
}

function cancelPaymentFlow() {
  paymentFlowStep.value = 'idle'
  showPaymentConfirmModal.value = false
  showUtilityConfirmModal.value = false
  showContractFeeConfirmModal.value = false
  rentWalletPinInput.value = ''
  rentWalletPinError.value = ''
  rentWalletPinAttempts.value = 0
  premiumPinResolve.value = null
  premiumPinOriginTab.value = null
  pendingPaymentModal.value = null
}

function openPaymentConfirm() {
  if (walletBalance.value < totalPaymentAmount.value) {
    showToast('error', 'Votre solde est insuffisant pour finaliser.')
    return
  }
  paymentFlowStep.value = 'recap'
  showPaymentConfirmModal.value = true
}

function confirmPaymentFromModal() {
  openPremiumPinGate(totalPaymentAmount.value, 'rent', executePayment)
  paymentFlowStep.value = 'pin'
}

function executePayment() {
  processingPayment.value = true
  setTimeout(() => {
    walletBalance.value -= totalPaymentAmount.value
    
    // Mark each selected month as paid by adding a RENT invoice
    const paidMonths = rentMonths.value.filter(m => selectedMonthsKeys.value.includes(m.key))
    for (const m of paidMonths) {
      localInvoices.value.push({
        id: 'INV-RENT-' + Date.now() + '-' + m.key,
        type: 'RENT',
        status: 'paid',
        period: m.label,
        amount: m.totalDue,
        rentBase: m.amount,
        penaltyAmount: m.penaltyAmount,
        penaltyRate: m.penaltyRate,
        paid_at: new Date().toISOString().split('T')[0],
        reference: 'QUIT-' + new Date().getFullYear() + '-' + String(allReceipts.value.length + 1).padStart(3, '0'),
      })
    }
    
    const receipt = {
      id: 'RCPT-' + Date.now(), type: 'rent',
      title: `Loyer ${selectedMonthsNames.value}`,
      reference: 'QUIT-' + new Date().getFullYear() + '-' + String(allReceipts.value.length + 1).padStart(3, '0'),
      amount: totalPaymentAmount.value,
      period: selectedMonthsNames.value,
      paid_at: new Date().toISOString().split('T')[0],
      method: 'Portefeuille',
      transaction_id: 'TX-' + Date.now(),
      property: props.contracts[0]?.property?.name || 'Appartement',
      tenant: `${profileForm.first_name} ${profileForm.last_name}`,
      landlord: 'SCI Habitats SA',
      months: paidMonths.map(m => ({
        label: m.label,
        amount: m.amount,
        penaltyAmount: m.penaltyAmount,
        penaltyRate: m.penaltyRate,
        totalDue: m.totalDue,
      })),
      includesPenalties: paidMonths.some(m => m.penaltyAmount > 0),
    }
    
    transactions.value.unshift({
      id: 'TX-' + Date.now(), type: 'payment',
      amount: totalPaymentAmount.value, method: 'Portefeuille',
      description: `Loyer : ${selectedMonthsNames.value}`,
      date: new Date().toISOString(), status: 'success'
    })
    
    allReceipts.value.unshift(receipt)
    lastPaymentReceipt.value = receipt
    selectedMonthsKeys.value = []
    processingPayment.value = false
    showToast('success', 'Règlement de loyer enregistré avec succès !')
  }, 600)
}

// Pay individual water/electricity bills
function payIndividualInvoice(invoice) {
  if (walletBalance.value < invoice.amount) {
    showToast('error', 'Solde insuffisant pour ce règlement.')
    openRechargeModal()
    return
  }
  requireWalletPassword(`Paiement facture ${invoice.reference}`, () => {
    walletBalance.value -= invoice.amount
    invoice.status = 'paid'
    transactions.value.unshift({
      id: 'TX-' + Date.now(),
      type: 'payment',
      amount: invoice.amount,
      method: 'Portefeuille',
      description: `Facture ${invoiceTypeLabel(invoice.type)} - ${invoice.period}`,
      date: new Date().toISOString(),
      status: 'success'
    })
    showToast('success', `Règlement de la facture ${invoice.reference} effectué !`)
  })
}

// 💧 UTILITY PAYMENT (Water/Electricity via Wallet)
function payUtilityInvoice(inv) {
  openUtilityConfirm(inv)
}
// 💰 CONTRACT FEE PAYMENT
function payContractFee() {
  openContractFeeConfirm()
}
function generateRenewalReceipt() {
  const contract = props.contracts[0]
  if (!contract) { showToast('error', 'Aucun contrat actif.'); return }
  const ref = `RENEW-${contract.id}-${new Date().getFullYear()}`
  const now = formatDateTime(new Date().toISOString())
  const html = generateProReceiptHTML({
    brand: 'HABITATUM', title: 'RENOUVELLEMENT DE CONTRAT', ref,
    lines: [
      ['Locataire', `${profileForm.first_name} ${profileForm.last_name}`],
      ['Email', profileForm.email],
      ['Bien', contract.property.name],
      ['Adresse', contract.property.address || '—'],
      ['Type contrat', contract.type],
      ['Début', formatDate(contract.start_date)],
      ['Fin', formatDate(contract.end_date)],
      ['Loyer', formatCurrency(contract.rent)],
    ],
    amount: contract.rent,
    status: contractFeePaid.value ? '✓ Renouvellement confirmé' : '● En attente de paiement',
    statusColor: contractFeePaid.value ? '#059669' : '#F59E0B',
    footer: 'Merci pour votre confiance — Habitatum', now
  })
  generateProPDF(html, `Renouvellement_Contrat_${contract.id}.pdf`)
  showToast('success', 'Reçu de renouvellement généré !')
}

function downloadUtilityReceipt(inv) {
  const rcp = allReceipts.value.find(r => r.reference === inv.reference.replace('INV', 'QUIT'))
  if (rcp) generateReceiptPDF(rcp)
  else showToast('success', `Téléchargement reçu : ${inv.reference}`)
}
function viewInvoiceDetail(inv) {
  invDetail.value = inv
  showInvoiceDetail.value = true
}
function downloadInvoiceReceipt(inv) {
  const rcp = allReceipts.value.find(r => r.reference === inv.reference.replace('INV', 'QUIT'))
  if (rcp) generateReceiptPDF(rcp)
  else generateFromInvoice(inv)
} 
function viewUtilityDetail(inv) {
  viewInvoiceDetail(inv)
}

// 🧾 PDF GENERATION — Professional A4 receipt via jsPDF + html2canvas
function generateProPDF(htmlContent, filename) {
  const wrapper = document.createElement('div')
  wrapper.innerHTML = htmlContent
  wrapper.style.cssText = 'position:fixed; left:-9999px; top:0; width:800px; background:white; z-index:9999;'
  document.body.appendChild(wrapper)
  html2canvas(wrapper, { scale: 2, useCORS: true, backgroundColor: '#ffffff',
    logging: false, width: wrapper.scrollWidth, height: wrapper.scrollHeight
  }).then(canvas => {
    const imgData = canvas.toDataURL('image/jpeg', 0.95)
    const imgW = canvas.width; const imgH = canvas.height
    const pdf = new jsPDF({ orientation: 'portrait', unit: 'mm', format: 'a4' })
    const pdfW = 210; const pdfH = 297
    const margin = 15
    const usableW = pdfW - margin * 2
    const usableH = pdfH - margin * 2
    const ratio = Math.min(usableW / imgW, usableH / imgH)
    const drawW = imgW * ratio; const drawH = imgH * ratio
    const offsetX = (pdfW - drawW) / 2; const offsetY = margin
    pdf.addImage(imgData, 'JPEG', offsetX, offsetY, drawW, drawH)
    pdf.save(filename)
    document.body.removeChild(wrapper)
  }).catch(() => {
    const blob = new Blob([htmlContent], { type: 'text/html;charset=utf-8' })
    const url = URL.createObjectURL(blob)
    const link = document.createElement('a')
    link.href = url; link.download = filename.replace('.pdf', '.html')
    link.click(); URL.revokeObjectURL(url)
    document.body.removeChild(wrapper)
  })
}

function generateProReceiptHTML({ brand, title, ref, lines, amount, status, statusColor, footer, now }) {
  const sc = statusColor || '#059669'
  const badgeBg = sc === '#EF4444' ? 'rgba(239,68,68,0.12)' : sc === '#F59E0B' ? 'rgba(245,158,11,0.12)' : 'rgba(5,150,101,0.12)'
  const badgeText = sc === '#EF4444' ? '#DC2626' : sc === '#F59E0B' ? '#D97706' : '#059669'
  const totalAmount = amount || (lines.find(l => l[0] === 'Montant' || l[0] === 'Loyer')?.[1]) || ''
  const displayAmount = typeof totalAmount === 'string' ? totalAmount : (amount ? formatCurrency(amount) : '')
  const detailRows = lines.map((l, i) =>
    `<tr class="${i % 2 === 0 ? 'roweven' : ''}"><td class="cl">${l[0]}</td><td class="cr">${l[1]}</td></tr>`
  ).join('')
  return `<!DOCTYPE html>
<html><head><meta charset="utf-8"><style>
  @page { size: A4; margin: 18mm 15mm 15mm; }
  body { font-family:'Segoe UI','Helvetica Neue',Arial,sans-serif; margin:0; padding:0; color:#1E293B; -webkit-print-color-adjust:exact; print-color-adjust:exact; }
  .page { width:100%; max-width:180mm; margin:0 auto; }

  /* watermark */
  .wmark { position:fixed; top:50%; left:50%; transform:translate(-50%,-50%) rotate(-30deg); font-size:80px; font-weight:900; color:rgba(0,0,0,0.025); letter-spacing:12px; pointer-events:none; z-index:0; white-space:nowrap; }

  /* header */
  .hdr { border-bottom:3px solid #0F172A; padding-bottom:14px; margin-bottom:22px; position:relative; }
  .hdr-inner { display:flex; justify-content:space-between; align-items:flex-end; }
  .hdr-brand { font-size:28px; font-weight:900; color:#0F172A; letter-spacing:2.5px; line-height:1; }
  .hdr-tag { font-size:8.5px; color:#94A3B8; letter-spacing:1.8px; text-transform:uppercase; margin-top:4px; }
  .hdr-title { text-align:right; }
  .hdr-title-main { font-size:16px; font-weight:800; color:#0F172A; letter-spacing:0.5px; text-transform:uppercase; }
  .hdr-title-sub { font-size:9px; color:#64748B; margin-top:1px; letter-spacing:0.5px; }
  .hdr-ref { display:flex; justify-content:space-between; margin-top:10px; padding-top:10px; border-top:1px dashed #E2E8F0; font-size:9.5px; color:#64748B; }
  .hdr-ref strong { color:#0F172A; font-weight:700; }

  /* body section title */
  .sec-title { font-size:8px; font-weight:800; color:#94A3B8; text-transform:uppercase; letter-spacing:2px; margin-bottom:8px; padding-bottom:5px; border-bottom:1.5px solid #F1F5F9; }

  /* details table */
  table.det { width:100%; border-collapse:collapse; border:1px solid #E2E8F0; border-radius:8px; overflow:hidden; }
  table.det td { padding:8px 16px; font-size:12px; border-bottom:1px solid #F1F5F9; }
  table.det tr:last-child td { border-bottom:none; }
  table.det .roweven td { background:#F8FAFC; }
  table.det .cl { font-weight:500; color:#475569; width:38%; }
  table.det .cr { font-weight:700; color:#0F172A; text-align:right; }

  /* total block */
  .tot { margin-top:14px; border:2px solid #0F172A; border-radius:8px; overflow:hidden; }
  .tot-inner { display:flex; justify-content:space-between; align-items:center; padding:12px 16px; background:#F8FAFC; }
  .tot-label { font-size:10px; font-weight:800; color:#0F172A; text-transform:uppercase; letter-spacing:1px; }
  .tot-value { font-size:20px; font-weight:900; color:#059669; }
  .tot-words { padding:6px 16px 10px; font-size:9.5px; color:#64748B; font-style:italic; border-top:1px solid #E2E8F0; }

  /* status badge */
  .sb { text-align:center; margin-top:14px; padding:8px 0; }
  .sb-inner { display:inline-block; padding:6px 28px; border-radius:6px; font-size:11px; font-weight:800; letter-spacing:1px; background:${badgeBg}; color:${badgeText}; border:1px solid ${sc}25; }

  /* footer */
  .ftr { margin-top:28px; padding-top:12px; border-top:1px solid #E2E8F0; display:flex; justify-content:space-between; font-size:8.5px; color:#94A3B8; }
  .ftr strong { color:#64748B; }
</style></head><body>
<div class="wmark">ORIGINAL</div>
<div class="page">
  <div class="hdr">
    <div class="hdr-inner">
      <div><div class="hdr-brand">${brand}</div><div class="hdr-tag">Gestion locative premium</div></div>
      <div class="hdr-title"><div class="hdr-title-main">${title}</div><div class="hdr-title-sub">Document original</div></div>
    </div>
    <div class="hdr-ref"><span>RÉFÉRENCE : <strong>${ref}</strong></span><span>DATE : <strong>${now}</strong></span></div>
  </div>
  <div class="sec-title">Détails</div>
  <table class="det">${detailRows}</table>
  ${amount ? `<div class="tot"><div class="tot-inner"><span class="tot-label">Total versé</span><span class="tot-value">${displayAmount}</span></div><div class="tot-words">Arrêté à la somme de ${displayAmount}</div></div>` : ''}
  <div class="sb"><div class="sb-inner">${status}</div></div>
  <div class="ftr"><span>${footer}</span><span><strong>${brand}</strong> — ${now.split('à')[0] || now}</span></div>
</div>
</body></html>`
}

function generateFromInvoice(inv) {
  const label = inv.type === 'water' ? 'Eau' : inv.type === 'electricity' ? 'Électricité' : 'Facture'
  const now = formatDateTime(new Date().toISOString())
  const html = generateProReceiptHTML({
    brand: 'HABITATUM', title: 'FACTURE ' + label.toUpperCase(),
    ref: inv.reference, lines: [
      ['Période', inv.period],
      ['Montant', formatCurrency(inv.amount)],
      ['Échéance', inv.due_date ? formatDate(inv.due_date) : '—'],
      ['Statut', inv.status === 'paid' ? 'Payée' : 'Impayée'],
    ], amount: inv.amount,
    status: inv.status === 'paid' ? '✓ Payée' : '● Impayée',
    statusColor: inv.status === 'paid' ? '#059669' : '#EF4444',
    footer: 'Habitatum — Gestion locative premium', now
  })
  generateProPDF(html, `Facture_${inv.reference}.pdf`)
  showToast('success', `Facture ${inv.reference} téléchargée`)
}

function generateReceiptPDF(rec) {
  const now = formatDateTime(new Date().toISOString())
  const html = generateProReceiptHTML({
    brand: 'HABITATUM', title: 'QUITTANCE DE VERSEMENT',
    ref: rec.reference, lines: [
      ['Locataire', rec.tenant],
      ['Bien', rec.property],
      ['Type', rec.title],
      ['Période', rec.period],
      ['Date versement', formatDate(rec.paid_at)],
      ['Méthode', rec.method],
      ['Transaction', rec.transaction_id],
      ['Bailleur', rec.landlord],
    ], amount: rec.amount,
    status: '✓ Payé',
    footer: 'Merci pour votre confiance — Habitatum', now
  })
  generateProPDF(html, `Quittance_${rec.reference}.pdf`)
  showToast('success', `Quittance ${rec.reference} téléchargée`)
}
function viewReceiptDetail(rec) {
  recDetail.value = rec
  showReceiptDetail.value = true
}

// 🏛️ OLD CONTRACTS
function viewOldContractDetail(oc) {
  ocDetail.value = oc
  showOldContractDetail.value = true
}
function downloadOldContractReceipt(oc) {
  const now = formatDateTime(new Date().toISOString())
  const html = generateProReceiptHTML({
    brand: 'HABITATUM', title: 'VERSEMENT CONTRAT',
    ref: oc.id, lines: [
      ['Propriété', oc.property_name],
      ['Adresse', oc.address],
      ['Propriétaire', oc.owner],
      ['Début', formatDate(oc.start_date)],
      ['Fin', formatDate(oc.end_date)],
      ['Durée', oc.duration],
      ['Loyer mensuel', formatCurrency(oc.rent)],
    ],
    amount: oc.deposit,
    status: oc.status === 'ended' ? '✓ Contrat terminé' : '✕ Résilié',
    statusColor: oc.status === 'ended' ? '#059669' : '#EF4444',
    footer: 'Merci pour votre confiance — Habitatum', now
  })
  generateProPDF(html, `Recu_Contrat_${oc.id}.pdf`)
  showToast('success', `Reçu contrat ${oc.id} généré !`)
}

// 💳 WALLET SIMULATED TRANSFER TO LANDLORD WIDGET
function executeWalletTransfer() {
  const amt = parseFloat(transferAmount.value)
  if (!amt || amt <= 0) return
  if (walletBalance.value < amt) {
    showToast('error', 'Solde du portefeuille insuffisant pour ce virement.')
    return
  }
  requireWalletPassword(`Virement de ${formatCurrency(amt)}`, () => {
    walletBalance.value -= amt
    transactions.value.unshift({
      id: 'TX-' + Date.now(),
      type: 'payment',
      amount: amt,
      method: 'Virement Portefeuille',
      description: `Virement Bailleur : ${transferMemo.value || 'Sans libellé'}`,
      date: new Date().toISOString(),
      status: 'success'
    })
    
    transferAmount.value = ''
    transferMemo.value = ''
    showTransferForm.value = false
    showToast('success', 'Virement vers le compte du bailleur effectué avec succès !')
  })
}

// 💳 RECHARGE MODALS TRIGGER & ACTIONS
function openRechargeModal() {
  showRechargeModal.value = true
  orangeOtpSent.value = false
  orangeOtp.value = ''
  orangeAmount.value = ''
  orangePhone.value = ''
  mtnAmount.value = ''
  mtnPhone.value = ''
  mtnPin.value = ''
  cardForm.number = ''
  cardForm.name = ''
  cardForm.expiry = ''
  cardForm.cvv = ''
  cardForm.amount = ''
  paypalAmount.value = ''
}

function processOrangeRecharge() {
  if (!orangePhone.value || !orangeAmount.value) {
    showToast('error', 'Champs obligatoires manquants.')
    return
  }
  
  if (!orangeOtpSent.value) {
    processingRecharge.value = true
    setTimeout(() => {
      processingRecharge.value = false
      orangeOtpSent.value = true
      orangeOtpCountdown.value = 20
      showToast('success', 'Code OTP envoyé sur votre mobile !')
      
      orangeOtpTimer = setInterval(() => {
        if (orangeOtpCountdown.value > 0) {
          orangeOtpCountdown.value--
        } else {
          clearInterval(orangeOtpTimer)
          orangeOtpSent.value = false
          showToast('error', 'Le code de test OTP a expiré.')
        }
  }, 100)
    }, 1500)
  } else {
    if (orangeOtp.value === '8842') {
      requireWalletPassword('Recharge Orange Money', () => {
        clearInterval(orangeOtpTimer)
        processingRecharge.value = true
        setTimeout(() => {
          const amt = parseFloat(orangeAmount.value)
          walletBalance.value += amt
          transactions.value.unshift({
            id: 'TX-' + Date.now(),
            type: 'recharge',
            amount: amt,
            method: 'Orange Money',
            date: new Date().toISOString(),
            status: 'success'
          })
          processingRecharge.value = false
          showRechargeModal.value = false
          showToast('success', 'Portefeuille rechargé avec succès !')
        }, 1200)
      })
    } else {
      showToast('error', 'Code OTP incorrect. Veuillez utiliser 8842.')
    }
  }
}

function processMtnRecharge() {
  if (!mtnPhone.value || !mtnPin.value || !mtnAmount.value) {
    showToast('error', 'Champs requis manquants.')
    return
  }
  requireWalletPassword('Recharge MTN MoMo', () => {
    processingRecharge.value = true
    setTimeout(() => {
      const amt = parseFloat(mtnAmount.value)
      walletBalance.value += amt
      transactions.value.unshift({
        id: 'TX-' + Date.now(),
        type: 'recharge',
        amount: amt,
        method: 'MTN MoMo',
        date: new Date().toISOString(),
        status: 'success'
      })
      processingRecharge.value = false
      showRechargeModal.value = false
      showToast('success', `MTN MoMo rechargé : +${formatCurrency(amt)}`)
    }, 1800)
  })
}

function formatCardNumber(e) {
  let val = e.target.value.replace(/\D/g, '')
  if (val.length > 16) val = val.substring(0, 16)
  cardForm.number = val
}
function formatCardExpiry(e) {
  let val = e.target.value.replace(/\D/g, '')
  if (val.length > 4) val = val.substring(0, 4)
  if (val.length > 2) val = val.substring(0, 2) + '/' + val.substring(2)
  cardForm.expiry = val
}

function validateLuhn(numStr) {
  const digits = numStr.replace(/\s+/g, '').split('').map(Number)
  let sum = 0
  let isEven = false
  for (let i = digits.length - 1; i >= 0; i--) {
    let digit = digits[i]
    if (isEven) {
      digit *= 2
      if (digit > 9) digit -= 9
    }
    sum += digit
    isEven = !isEven
  }
  return sum % 10 === 0
}

function processCardRecharge() {
  const cleanNum = cardForm.number.replace(/\s+/g, '')
  if (cleanNum.length < 13 || !cardForm.name || !cardForm.expiry || cardForm.cvv.length < 3 || !cardForm.amount) {
    showToast('error', 'Champs de carte invalides.')
    return
  }
  if (!validateLuhn(cleanNum)) {
    showToast('error', 'Algorithme de Luhn invalide pour cette carte.')
    return
  }
  requireWalletPassword('Recharge Carte Bancaire', () => {
    processingRecharge.value = true
    setTimeout(() => {
      const amt = parseFloat(cardForm.amount)
      walletBalance.value += amt
      transactions.value.unshift({
        id: 'TX-' + Date.now(),
        type: 'recharge',
        amount: amt,
        method: 'Carte Bancaire',
        date: new Date().toISOString(),
        status: 'success'
      })
      processingRecharge.value = false
      showRechargeModal.value = false
      showToast('success', `Règlement CB approuvé : +${formatCurrency(amt)}`)
    }, 2000)
  })
}

function processPaypalRecharge() {
  if (!paypalAmount.value) return
  requireWalletPassword('Recharge PayPal', () => {
    processingRecharge.value = true
    setTimeout(() => {
      const amt = parseFloat(paypalAmount.value)
      walletBalance.value += amt
      transactions.value.unshift({
        id: 'TX-' + Date.now(),
        type: 'recharge',
        amount: amt,
        method: 'PayPal',
        date: new Date().toISOString(),
        status: 'success'
      })
      processingRecharge.value = false
      showRechargeModal.value = false
      showToast('success', 'Virement PayPal finalisé.')
    }, 2000)
  })
}

// 🔐 2FA SETUP WIZARDS ACTIONS
function start2FASetup() {
  tfaStep.value = 1
  tfaToken.value = ''
  hasDownloadedBackupCodes.value = false
  show2FAModal.value = true
}
function copySecretKey() {
  navigator.clipboard.writeText(mock2FASecret)
  showToast('success', 'Secret copié !')
}
function generateMockTOTP() {
  const timeChunk = Math.floor(Date.now() / 30000)
  const base = (timeChunk * 7919 + 104729) % 1000000
  return String(base).padStart(6, '0')
}

function getCurrentTOTP() {
  return generateMockTOTP().substring(0, 6)
}

function validate2FAToken() {
  const expected = getCurrentTOTP()
  if (tfaToken.value === expected || tfaToken.value.length === 6) {
    tfaStep.value = 3
    showToast('success', 'Jeton TOTP vérifié avec succès !')
    if (tfaCountdownTimer) clearInterval(tfaCountdownTimer)
  } else {
    showToast('error', 'Code 2FA invalide. Le jeton a peut-être expiré.')
  }
}

function startTFACountdown() {
  tfaCountdown.value = 30
  if (tfaCountdownTimer) clearInterval(tfaCountdownTimer)
  tfaCountdownTimer = setInterval(() => {
    if (tfaCountdown.value > 0) {
      tfaCountdown.value--
    } else {
      tfaCountdown.value = 30
    }
  }, 1000)
}
function downloadBackupCodes() {
  const content = "CODES DE SECOURS PROTECT LOCATAIRE\n\n" + tfaBackupCodes.value.join("\n")
  const blob = new Blob([content], { type: 'text/plain;charset=utf-8' })
  const url = URL.createObjectURL(blob)
  const link = document.createElement('a')
  link.href = url
  link.download = 'backup-codes-securité.txt'
  link.click()
  URL.revokeObjectURL(url)
  hasDownloadedBackupCodes.value = true
  showToast('success', 'Fichier codes_de_secours.txt téléchargé.')
}
function finalize2FA() {
  twoFAEnabled.value = true
  show2FAModal.value = false
  showToast('success', 'Authentification à double facteur activée !')
}
function viewBackupCodes() {
  tfaStep.value = 4
  show2FAModal.value = true
}
function disable2FA() {
  showConfirmDialog.value = true
  confirmDialogMessage.value = 'Voulez-vous désactiver la sécurité double facteur (2FA) ? Cette action réduit le niveau de protection de votre compte.'
  confirmDialogCallback.value = () => {
    twoFAEnabled.value = false
    showToast('success', 'Protection TOTP 2FA désactivée.')
    showConfirmDialog.value = false
  }
}

// ⚙️ THEME LIGHT/DARK SWITCH CONTROLLERS
function toggleTheme() {
  setTheme(theme.value === 'light' ? 'dark' : 'light')
}
function setTheme(t) {
  theme.value = t
  localStorage.setItem('hab_theme', t)
}

// Messaging and live typing simulations
function selectTicket(ticket) {
  selectedTicket.value = ticket
  isTyping.value = false
  
  nextTick(() => {
    if (chatMessagesRef.value) {
      chatMessagesRef.value.scrollTop = chatMessagesRef.value.scrollHeight
    }
  })
  
  // Simulate live manager typing if ticket has only one message (new ticket)
  if (ticket.messages.length === 1 && ticket.status !== 'closed') {
    setTimeout(() => {
      isTyping.value = true
      nextTick(() => {
        if (chatMessagesRef.value) chatMessagesRef.value.scrollTop = chatMessagesRef.value.scrollHeight
      })
      setTimeout(() => {
        isTyping.value = false
        ticket.messages.push({
          id: 'msg-' + Date.now(),
          sender: 'manager',
          text: 'Bonjour, j\'ai bien pris en compte votre signalement. Un technicien va vous contacter pour planifier l\'intervention.',
          created_at: new Date().toISOString()
        })
        localStorage.setItem('hab_tickets', JSON.stringify(localTickets.value))
        nextTick(() => {
          if (chatMessagesRef.value) chatMessagesRef.value.scrollTop = chatMessagesRef.value.scrollHeight
        })
      }, 2000)
    }, 1000)
  }
}

function sendMessage() {
  if ((!newMessage.value.trim() && !attachedFile.value) || !selectedTicket.value) return
  
  const msgObj = {
    id: 'm-' + Date.now(),
    sender: 'tenant',
    created_at: new Date().toISOString()
  }

  if (attachedFile.value) {
    msgObj.type = 'attachment'
    msgObj.fileName = attachedFile.value.name
    attachedFile.value = null
  } else {
    msgObj.text = newMessage.value
  }

  selectedTicket.value.messages.push(msgObj)
  newMessage.value = ''
  
  localStorage.setItem('hab_tickets', JSON.stringify(localTickets.value))
  
  nextTick(() => {
    if (chatMessagesRef.value) {
      chatMessagesRef.value.scrollTop = chatMessagesRef.value.scrollHeight
    }
  })
}

// 🎤 AUDIO MESSAGING RECORD SIMULATORS
function toggleVoiceRecording() {
  if (isRecording.value) {
    cancelVoiceRecording()
  } else {
    isRecording.value = true
    recordingTime.value = 0
    recordingTimer = setInterval(() => {
      recordingTime.value++
    }, 1000)
  }
}
function cancelVoiceRecording() {
  isRecording.value = false
  clearInterval(recordingTimer)
}
function sendVoiceRecording() {
  isRecording.value = false
  clearInterval(recordingTimer)
  
  if (!selectedTicket.value) return
  
  selectedTicket.value.messages.push({
    id: 'm-audio-' + Date.now(),
    sender: 'tenant',
    type: 'audio',
    created_at: new Date().toISOString()
  })
  
  localStorage.setItem('hab_tickets', JSON.stringify(localTickets.value))
  
  nextTick(() => {
    if (chatMessagesRef.value) {
      chatMessagesRef.value.scrollTop = chatMessagesRef.value.scrollHeight
    }
  })
}

function toggleAudioMessage(msg) {
  if (mockAudioPlayingId.value === msg.id) {
    mockAudioPlayingId.value = null
  } else {
    mockAudioPlayingId.value = msg.id
    setTimeout(() => {
      if (mockAudioPlayingId.value === msg.id) mockAudioPlayingId.value = null
    }, 12000) // stop simulation after 12s
  }
}

// Attach file simulation
function simulateAttachment() {
  attachedFile.value = {
    name: 'Photo_Dysfonctionnement_Clim.jpg',
    size: '1.2 MB'
  }
  showToast('success', 'Fichier joint prêt à l\'envoi !')
}

// Silent Preferences matrix watcher callback
function saveNotifPrefsSilent() {
  localStorage.setItem('hab_notif_prefs', JSON.stringify(notifPreferences.value))
}

function closeTicket(ticket) {
  ticket.status = 'closed'
  localStorage.setItem('hab_tickets', JSON.stringify(localTickets.value))
  showToast('success', 'Incident clôturé.')
}
function submitTicket() {
  if (!newTicketForm.title || !newTicketForm.description) return
  const tk = {
    id: 'TKT-00' + (localTickets.value.length + 1),
    title: newTicketForm.title,
    description: newTicketForm.description,
    category: newTicketForm.category,
    status: 'open',
    priority: 'medium',
    created_at: new Date().toISOString().split('T')[0],
    messages: [{ id: 'm-init', sender: 'tenant', text: newTicketForm.description, created_at: new Date().toISOString() }]
  }
  localTickets.value.push(tk)
  showNewTicketModal.value = false
  newTicketForm.title = ''
  newTicketForm.description = ''
  newTicketForm.category = 'plumbing'
  localStorage.removeItem('hab_ticket_draft')
  showToast('success', 'Ticket créé !')
  selectTicket(tk)
}

function downloadReceipt(invoice) {
  showToast('success', `Téléchargement quittance : ${invoice.reference}`)
}
function openProofUpload(invoice) {
  selectedProofInvoice.value = invoice
  proofFile.value = null
  showProofModal.value = true
}
function handleProofDrop(e) {
  isProofDragging.value = false
  const file = e.dataTransfer.files[0]
  if (file) proofFile.value = file
}
function handleProofFileSelect(e) {
  proofFile.value = e.target.files[0] || null
}
function submitProof() {
  showProofModal.value = false
  showToast('success', 'Justificatif de virement transmis au bailleur !')
}

function handleDocDrop(e) { showToast('success', 'Document importé.') }
function triggerDocUpload() { showToast('success', 'Parcourez vos fichiers pour ce justificatif.') }

// 📸 PREMIUM AVATAR UPLOAD WITH PREVIEW
const showAvatarPreview = ref(false)
const avatarPreviewUrl = ref('')
function handleAvatarChange(e) {
  const file = e.target.files[0]
  if (!file) return
  const r = new FileReader()
  r.onload = (ev) => {
    const fullUrl = ev.target.result
    avatarPreviewUrl.value = fullUrl
    showAvatarPreview.value = true
  }
  r.readAsDataURL(file)
}
function confirmAvatar() {
  const img = new Image()
  img.onload = () => {
    const canvas = document.createElement('canvas')
    const maxSize = 200
    let w = img.width, h = img.height
    if (w > maxSize || h > maxSize) {
      const ratio = Math.min(maxSize / w, maxSize / h)
      w *= ratio; h *= ratio
    }
    canvas.width = w; canvas.height = h
    const ctx = canvas.getContext('2d')
    ctx.drawImage(img, 0, 0, w, h)
    profileForm.avatar = canvas.toDataURL('image/jpeg', 0.85)
    showAvatarPreview.value = false
    showToast('success', 'Photo de profil mise à jour')
  }
  img.src = avatarPreviewUrl.value
}
function cancelAvatar() {
  showAvatarPreview.value = false
  avatarPreviewUrl.value = ''
}
function deleteAvatar() { showAvatarDeleteConfirm.value = true }

function saveProfile() {
  savingProfile.value = true
  setTimeout(() => {
    savingProfile.value = false
    showToast('success', 'Données de profil enregistrées.')
  }, 1000)
}
function changePassword() {
  showToast('success', 'Mot de passe réinitialisé.')
  passwordForm.current = ''
  passwordForm.new_password = ''
  passwordForm.confirm = ''
  localStorage.removeItem('hab_password_draft')
}
function handleLogout() { showLogoutModal.value = true }
function confirmLogout() {
  showLogoutModal.value = false
  showToast('success', 'Fermeture de session...')
  setTimeout(() => { window.location.href = '/logout' }, 800)
}
function confirmDeleteAvatar() {
  showAvatarDeleteConfirm.value = false
  profileForm.avatar = null
  showToast('success', 'Photo de profil supprimée')
}

function showToast(type, msg) {
  toast.type = type
  toast.message = msg
  toast.show = true
  setTimeout(() => { toast.show = false }, 3500)
}

// 🕒 Reactive date — auto-updates every 30s so penalties re-evaluate without page reload
const reactiveNow = ref(new Date())
let dateWatcherTimer = null
let lastPenaltyRates = {}

function startDateWatcher() {
  snapshotPenaltyRates()
  dateWatcherTimer = setInterval(() => {
    const old = reactiveNow.value
    const n = new Date()
    reactiveNow.value = n
    // Day change → run full notification check + detect penalty changes
    if (old.getDate() !== n.getDate() || old.getMonth() !== n.getMonth()) {
      checkAndNotify()
      const changed = detectPenaltyChange()
      if (changed) {
        showPushNotification('Pénalité mise à jour ⚡', changed)
      }
    }
  }, 100)
}

function snapshotPenaltyRates() {
  lastPenaltyRates = {}
  const now = reactiveNow.value
  for (const m of rentMonths.value) {
    lastPenaltyRates[m.key] = { rate: m.penaltyRate, amount: m.penaltyAmount, label: m.label }
  }
}

function detectPenaltyChange() {
  // Compare current penalty with last snapshot
  const changes = []
  for (const m of rentMonths.value) {
    const prev = lastPenaltyRates[m.key]
    if (prev && (prev.rate !== m.penaltyRate || prev.amount !== m.penaltyAmount)) {
      changes.push(`${m.label} : ${prev.rate}% → ${m.penaltyRate}% (${(m.penaltyAmount - (prev.amount||0)).toLocaleString()} XAF)`)
    }
  }
  if (changes.length > 0) {
    // Update snapshot
    for (const m of rentMonths.value) {
      lastPenaltyRates[m.key] = { rate: m.penaltyRate, amount: m.penaltyAmount, label: m.label }
    }
    return changes.join(' | ')
  }
  return null
}

// 🔔 Push notification helper (SW si disponible, sinon Notification API, toujours toast)
function showPushNotification(title, body, icon = '🔔') {
  if ('serviceWorker' in navigator && navigator.serviceWorker.controller) {
    navigator.serviceWorker.controller.postMessage({
      type: 'SHOW_NOTIFICATION',
      title: `HABITATUM — ${title}`,
      body,
      icon: '/favicon.ico'
    })
  } else if ('Notification' in window && Notification.permission === 'granted') {
    new Notification(`HABITATUM — ${title}`, { body, icon: '/favicon.ico' })
  }
  showToast('info', `${icon} ${title} : ${body}`)
}

// ⏰ Schedule periodic checks for payment reminders
let notificationInterval = null

function startNotificationScheduler() {
  checkAndNotify()
  notificationInterval = setInterval(checkAndNotify, 60 * 60 * 1000) // every hour
}

function checkAndNotify() {
  const now = reactiveNow.value
  const day = now.getDate()
  const daysInMonth = new Date(now.getFullYear(), now.getMonth() + 1, 0).getDate()
  const month = now.toLocaleDateString('fr-FR', { month: 'long', year: 'numeric' })
  const nextMonth = new Date(now.getFullYear(), now.getMonth() + 1, 1).toLocaleDateString('fr-FR', { month: 'long', year: 'numeric' })
  const rentAmt = props.contracts[0]?.rent || 185000

  // Déduplication : une seule notification par jour par type
  const notified = JSON.parse(localStorage.getItem('hab_notified_dates') || '{}')
  const todayKey = `${now.getFullYear()}-${now.getMonth()+1}-${day}`
  if (!notified[todayKey]) notified[todayKey] = {}

  // Reminder: 5 days before due date (5th of month)
  if (day === 5 && !notified[todayKey].reminder) {
    const unpaid = rentMonths.value.find(m => m.status === 'unpaid' && m.label === month)
    if (unpaid) {
      showPushNotification('Rappel échéance', `Le loyer de ${month} (${rentAmt.toLocaleString()} XAF) est à payer avant le 10.`)
      notified[todayKey].reminder = true
    }
  }

  // End-of-month reminder: next month's rent due soon
  if (day >= daysInMonth - 3 && day <= daysInMonth && !notified[todayKey].endmonth) {
    const nextUnpaid = rentMonths.value.find(m => m.status === 'unpaid' && m.label === nextMonth)
    if (nextUnpaid) {
      showPushNotification('Rappel fin de mois', `Le loyer de ${nextMonth} (${rentAmt.toLocaleString()} XAF) sera à payer sous peu.`)
      notified[todayKey].endmonth = true
    }
  }

  // Overdue penalty notification
  if ((day === 11 || day === 16 || day === 21) && !notified[todayKey].penalty) {
    const info = penaltyInfo.value
    if (info.isOverdue) {
      showPushNotification('Pénalité activée ⚠️', `${info.period} : pénalité de ${info.penaltyRate}% (${info.penaltyAmount.toLocaleString()} XAF). Régularisez dès maintenant.`)
      notified[todayKey].penalty = true
    }
  }

  // New invoice notification (simulated — first day of month)
  if (day === 1 && !notified[todayKey].invoice) {
    showPushNotification('Nouvelle facture', `La facture de ${month} est disponible. Consultez votre espace factures.`)
    notified[todayKey].invoice = true
  }

  // Nettoyage des entrées de plus de 7 jours
  const todayMs = now.getTime()
  for (const key of Object.keys(notified)) {
    const d = new Date(key)
    if (todayMs - d.getTime() > 7 * 86400000) delete notified[key]
  }
  localStorage.setItem('hab_notified_dates', JSON.stringify(notified))
}

onMounted(() => {
  loadPersistedState()
  triggerLoader()
  // Request notification permission and start scheduler
  if ('Notification' in window && Notification.permission === 'default') {
    Notification.requestPermission()
  }
  startNotificationScheduler()
  startDateWatcher()
  registerServiceWorker()
})

// 🛎️ Service Worker — background push notifications
async function registerServiceWorker() {
  try {
    if (!('serviceWorker' in navigator)) return
    const reg = await navigator.serviceWorker.register('/sw.js')
    console.log('[SW] Registered')
    // Subscribe to push if supported
    if ('PushManager' in window) {
      await subscribeToPush(reg)
    }
  } catch (e) {
    console.warn('[SW] Registration failed:', e)
  }
}

async function subscribeToPush(reg) {
  try {
    // Request permission if not yet decided
    if (Notification.permission === 'default') {
      const result = await Notification.requestPermission()
      if (result !== 'granted') return
    }
    if (Notification.permission !== 'granted') return

    // Get existing subscription
    let sub = await reg.pushManager.getSubscription()

    if (!sub) {
      sub = await reg.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: urlBase64ToUint8Array(props.vapidPublicKey)
      })
    }
    // Send subscription to backend
    await window.axios.post('/push-subscriptions', sub.toJSON())
  } catch (e) {
    console.warn('[SW] Push subscribe failed:', e)
    // If VAPID key mismatch, unsubscribe and retry once
    try {
      const old = await reg.pushManager.getSubscription()
      if (old) {
        await old.unsubscribe()
        const sub = await reg.pushManager.subscribe({
          userVisibleOnly: true,
          applicationServerKey: urlBase64ToUint8Array(props.vapidPublicKey)
        })
        await window.axios.post('/push-subscriptions', sub.toJSON())
      }
    } catch (retryErr) {
      console.warn('[SW] Push retry also failed:', retryErr)
    }
  }
}

// Utility: convert base64 to Uint8Array for VAPID key
function urlBase64ToUint8Array(base64String) {
  const padding = '='.repeat((4 - base64String.length % 4) % 4)
  const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/')
  const rawData = window.atob(base64)
  const outputArray = new Uint8Array(rawData.length)
  for (let i = 0; i < rawData.length; ++i) {
    outputArray[i] = rawData.charCodeAt(i)
  }
  return outputArray
}
</script>

<style>
/* ══════════════════════════════════════════════════════════════════
   RESET & THEME VARIABLES
   ══════════════════════════════════════════════════════════════════ */
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@300;400;500&display=swap');

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
  --bg-root: #F8FAFC;
  --bg-sidebar: rgba(255, 255, 255, 0.92);
  --bg-card: rgba(255, 255, 255, 0.72);
  --border-card: rgba(255, 255, 255, 0.6);
  --border-input: #E2E8F0;
  --text-title: #0F172A;
  --text-body: #334155;
  --text-muted: #64748B;
  --bg-input: #FFFFFF;
  --shadow-card: 0 4px 20px rgba(37, 99, 235, 0.04), 0 1px 3px rgba(0,0,0,0.02);
  --shadow-card-hover: 0 12px 36px rgba(37, 99, 235, 0.08), 0 2px 8px rgba(0,0,0,0.04);
  --bg-active-tab: #EFF6FF;
  --text-active-tab: #2563EB;
  
  --blue-50: #EFF6FF;
  --blue-100: #DBEAFE;
  --blue-500: #3B82F6;
  --blue-600: #2563EB;
  --blue-700: #1D4ED8;
  --emerald-50: #ECFDF5;
  --emerald-100: #D1FAE5;
  --emerald-500: #10B981;
  --emerald-600: #059669;
  --amber-50: #FFFBEB;
  --amber-500: #F59E0B;
  --red-50: #FEF2F2;
  --red-500: #EF4444;
  --red-600: #DC2626;
  --gray-50: #F8FAFC;
  --gray-100: #F1F5F9;
  --gray-200: #E2E8F0;
  --gray-300: #CBD5E1;
  --gray-400: #94A3B8;
  --gray-500: #64748B;
  --gray-600: #475569;
  --gray-700: #334155;
  --gray-800: #1E293B;
  --gray-900: #0F172A;
  
  --radius-lg: 14px;
  --radius-xl: 18px;
  --radius-2xl: 22px;
  --font-main: 'Plus Jakarta Sans', sans-serif;
  --font-mono: 'DM Mono', monospace;
  --sidebar-w: 280px;
  --sidebar-w-collapsed: 80px;
}

/* 🕶️ PREMIUM OBSIDIAN DARK THEME VARIABLES */
.dashboard-root.dark-theme {
  --bg-root: #090D16;
  --bg-sidebar: rgba(15, 23, 42, 0.95);
  --bg-card: rgba(30, 41, 59, 0.55);
  --border-card: rgba(255, 255, 255, 0.08);
  --border-input: #334155;
  --text-title: #F8FAFC;
  --text-body: #CBD5E1;
  --text-muted: #64748B;
  --bg-input: #0F172A;
  --shadow-card: 0 8px 32px rgba(0, 0, 0, 0.4);
  --shadow-card-hover: 0 12px 48px rgba(0, 0, 0, 0.6);
  --bg-active-tab: rgba(37, 99, 235, 0.16);
  --text-active-tab: #60A5FA;
  
  --gray-50: #0F172A;
  --gray-100: #1E293B;
  --gray-200: #334155;
  --gray-300: #475569;
  --gray-400: #64748B;
  --gray-500: #94A3B8;
  --gray-600: #CBD5E1;
  --gray-700: #E2E8F0;
  --gray-800: #F1F5F9;
  --gray-900: #F8FAFC;
}

/* ══════════════════════════════════════════════════════════════════
   GLOBAL SCROLL & MAIN LAYOUTS
   ══════════════════════════════════════════════════════════════════ */

/* Premium thin scrollbar */
::-webkit-scrollbar { width: 5px; height: 5px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: var(--gray-300); border-radius: 3px; transition: background 0.2s; }
::-webkit-scrollbar-thumb:hover { background: var(--gray-400); }
.dashboard-root.dark-theme ::-webkit-scrollbar-thumb { background: var(--gray-600); }
.dashboard-root.dark-theme ::-webkit-scrollbar-thumb:hover { background: var(--gray-500); }
.dashboard-root {
  font-family: var(--font-main);
  background: var(--bg-root);
  min-height: 100vh;
  display: flex;
  position: relative;
  overflow: clip;
  color: var(--text-body);
  transition: background-color 0.4s ease, color 0.3s ease;
}

/* Moving ambient background orbs with premium floating animation */
.ambient-bg { position: fixed; inset: 0; z-index: 0; pointer-events: none; overflow: hidden; }
.orb { position: absolute; border-radius: 50%; filter: blur(90px); opacity: 0.25; will-change: transform; }
.orb-1 { width: 500px; height: 500px; background: radial-gradient(circle, #3b82f633, transparent); top: -150px; left: -100px; animation: orbFloat 25s ease-in-out infinite; }
.orb-2 { width: 450px; height: 450px; background: radial-gradient(circle, #10b98122, transparent); bottom: -150px; right: -80px; animation: orbFloat 30s ease-in-out infinite reverse; }
.orb-3 { width: 350px; height: 350px; background: radial-gradient(circle, #f59e0b11, transparent); top: 35%; left: 35%; animation: orbFloat 20s ease-in-out infinite 5s; }
@keyframes orbFloat {
  0%, 100% { transform: translate(0, 0) scale(1); }
  33% { transform: translate(30px, -25px) scale(1.05); }
  66% { transform: translate(-20px, 20px) scale(0.95); }
}

/* Progress bar loader */
.progress-bar { position: fixed; top: 0; left: 0; right: 0; height: 3px; z-index: 9999; opacity: 0; transition: opacity 0.2s; }
.progress-bar.active { opacity: 1; }
.progress-fill { height: 100%; background: linear-gradient(90deg, var(--blue-600), var(--emerald-500)); width: 0; transition: width 0.3s ease; }

.main-content {
  flex: 1;
  margin-left: var(--sidebar-w);
  padding: 0;
  z-index: 10;
  position: relative;
  height: 100vh;
  overflow-y: auto;
  transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.sidebar-collapsed .main-content { margin-left: var(--sidebar-w-collapsed); }

.page-panel {
  width: 100%;
  max-width: 100%;
  padding: 28px 30px;
  overflow-x: hidden;
  overflow-y: visible;
  transition: opacity 0.25s cubic-bezier(0.4, 0, 0.2, 1), transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), filter 0.25s ease;
  will-change: transform, opacity;
}
.page-panel.lang-switching {
  opacity: 0;
  transform: scale(0.97) translateY(6px);
  filter: blur(2px);
  pointer-events: none;
}

.tab-section {
  padding-bottom: 40px;
  max-width: 100%;
  overflow-x: hidden;
}

.page-header {
  display: flex; align-items: flex-start; justify-content: space-between;
  margin-bottom: 28px;
}
.page-title {
  font-size: 24px; font-weight: 750; color: var(--text-title);
  letter-spacing: -0.5px;
}
.page-subtitle {
  font-size: 14px; color: var(--text-muted); margin-top: 4px;
  font-weight: 400;
  display:flex;
}
.header-date {
  font-size: 13px; color: var(--text-muted);
  background: var(--bg-input);
  padding: 6px 14px;
  border-radius: 8px;
  border: 1px solid var(--border-input);
  white-space: nowrap;
  font-weight: 500;
}
.sidebar-collapsed .main-content { margin-left: var(--sidebar-w-collapsed); }

.glass-card {
  background: var(--bg-card);
  backdrop-filter: blur(14px);
  border: 1px solid var(--border-card);
  border-radius: var(--radius-xl);
  box-shadow: var(--shadow-card);
  transition: background-color 0.3s, border-color 0.3s, box-shadow 0.35s cubic-bezier(0.4, 0, 0.2, 1), transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
}
@media (hover: hover) {
  .glass-card:hover {
    box-shadow: var(--shadow-card-hover);
    transform: translateY(-1px);
  }
}
.glass-card:active {
  transform: translateY(0) scale(0.998);
}

/* ══════════════════════════════════════════════════════════════════
   SIDEBAR & COMPACT PORTABLE WALLET
   ══════════════════════════════════════════════════════════════════ */
.sidebar {
  position: fixed;
  left: 0; top: 0; bottom: 0;
  width: var(--sidebar-w);
  z-index: 100;
  display: flex;
  flex-direction: column;
  background: var(--bg-sidebar);
  backdrop-filter: blur(24px);
  border-right: 1px solid var(--border-card);
  transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1), background-color 0.3s;
  padding: 16px 14px;
  overflow-y: auto;
}
.sidebar-collapsed .sidebar { width: var(--sidebar-w-collapsed); padding: 24px 10px; }
.sidebar-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; }
.brand-logo { display: flex; align-items: center; gap: 10px; }
.brand-name { font-size: 18px; font-weight: 800; color: var(--text-title); letter-spacing: -0.5px; }
.collapse-btn { border: none; background: var(--gray-100); color: var(--text-muted); width: 26px; height: 26px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; }

.tenant-quick-info { display: flex; align-items: center; gap: 10px; padding: 8px 10px; background: rgba(37,99,235,0.05); border-radius: 10px; margin-bottom: 12px; }
.tenant-avatar-wrap { position: relative; }
.tenant-avatar { width: 36px; height: 36px; border-radius: 50%; object-fit: cover; border: 2px solid white; }
.status-dot { position: absolute; bottom: 0; right: 0; width: 9px; height: 9px; border-radius: 50%; background: var(--emerald-500); border: 1.5px solid white;animation: pulseGlow 1.5s infinite;  }
.tenant-name { font-size: 13px; font-weight: 700; color: var(--text-title); }
.tenant-role { font-size: 10.5px; color: var(--text-muted); }

/* 💳 PORTABLE WALLET SIDEBAR — PREMIUM CREDIT-CARD STYLE */
.sidebar-wallet-card {
  padding: 12px 14px;
  border-radius: 12px;
  background: linear-gradient(145deg, #1E293B 0%, #0F172A 50%, #1E1B4B 100%);
  border: 1px solid rgba(99, 102, 241, 0.15);
  margin-bottom: 10px;
  display: flex;
  flex-direction: column;
  gap: 6px;
  position: relative;
  overflow: hidden;
  flex-shrink: 0;
  transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
  box-shadow: 0 4px 24px rgba(15, 23, 42, 0.12), inset 0 1px 0 rgba(255,255,255,0.06);
}
.sidebar-wallet-card::before {
  content: '';
  position: absolute;
  top: -50%; left: -50%;
  width: 200%; height: 200%;
  background: conic-gradient(from 0deg at 50% 50%, transparent 0deg, rgba(99,102,241,0.03) 90deg, transparent 180deg, rgba(139,92,246,0.03) 270deg, transparent 360deg);
  animation: walletShimmer 8s linear infinite;
  pointer-events: none;
}
@keyframes walletShimmer {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
.sidebar-wallet-card::after {
  content: '';
  position: absolute;
  inset: 0;
  border-radius: 14px;
  background: linear-gradient(135deg, rgba(99,102,241,0.08) 0%, transparent 40%, transparent 60%, rgba(139,92,246,0.06) 100%);
  pointer-events: none;
}
.sidebar-wallet-card:hover {
  border-color: rgba(99, 102, 241, 0.3);
  box-shadow: 0 8px 32px rgba(99, 102, 241, 0.12);
  transform: translateY(-1px);
}
.wallet-card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  position: relative;
  z-index: 1;
}
.wallet-title {
  font-size: 9.5px;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 1.2px;
  color: rgba(255,255,255,0.5);
}
.wallet-badge {
  font-size: 8.5px;
  font-weight: 700;
  padding: 2px 8px;
  border-radius: 20px;
  background: rgba(99,102,241,0.15);
  color: #A5B4FC;
  letter-spacing: 0.3px;
  transition: all 0.25s;
}
.wallet-badge:hover { background: rgba(99,102,241,0.25); color: #C7D2FE; }
.wallet-balance-val {
  font-size: 20px;
  font-weight: 800;
  color: #F8FAFC;
  letter-spacing: -0.3px;
  transition: color 0.3s ease;
  position: relative;
  z-index: 1;
  line-height: 1.3;
  font-variant-numeric: tabular-nums;
}
.wallet-balance-val.balance-flash {
  color: #34D399;
  animation: countPop 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
}
@keyframes countPop { 0% { transform: scale(1); } 50% { transform: scale(1.08); } 100% { transform: scale(1); } }
.wallet-recharge-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 5px;
  padding: 5px 10px;
  border-radius: 8px;
  border: none;
  background: linear-gradient(135deg, #6366F1, #8B5CF6);
  color: white;
  font-size: 10px;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
  position: relative;
  z-index: 1;
  letter-spacing: 0.3px;
  box-shadow: 0 2px 8px rgba(99,102,241,0.2);
  align-self: flex-start;
}
.wallet-recharge-btn:hover {
  box-shadow: 0 4px 16px rgba(99,102,241,0.35);
  transform: translateY(-1px);
}
.wallet-recharge-btn:active { transform: scale(0.96); }
.sidebar-wallet-collapsed {
  margin-bottom: 16px; width: 44px; height: 44px; border-radius: 50%;
  background: linear-gradient(135deg, rgba(99,102,241,0.12), rgba(139,92,246,0.06));
  border: 1px solid rgba(99,102,241,0.15);
  display: flex; align-items: center; justify-content: center;
  color: #A5B4FC; cursor: pointer; transition: all 0.25s;
}
.sidebar-wallet-collapsed:hover {
  background: linear-gradient(135deg, rgba(99,102,241,0.2), rgba(139,92,246,0.1));
  border-color: rgba(99,102,241,0.3);
  transform: scale(1.06);
}

/* 🔐 Password Gate Modal */
.password-gate-modal {
  max-width: 380px; width: 90%; border-radius: 20px; padding: 40px 32px 28px;
  background: linear-gradient(145deg, #ffffff, #f8faff);
  box-shadow: 0 25px 60px rgba(15,23,42,0.15), 0 0 0 1px rgba(99,102,241,0.08);
  position: relative; overflow: clip;
}
.password-gate-modal::before {
  content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px;
  background: linear-gradient(90deg, #6366F1, #8B5CF6, #6366F1);
  background-size: 200% 100%; animation: pgShimmer 2s ease-in-out infinite;
}
@keyframes pgShimmer { 0% { background-position: 0% 0%; } 50% { background-position: 100% 0%; } 100% { background-position: 0% 0%; } }
.pg-close { position: absolute; top: 12px; right: 14px; font-size: 22px; color: #94A3B8; background: transparent; border: none; cursor: pointer; line-height: 1; padding: 4px; border-radius: 6px; transition: 0.2s; }
.pg-close:hover { background: #F1F5F9; color: #475569; }
.pg-body { text-align: center; }
.pg-icon-wrap {
  width: 64px; height: 64px; border-radius: 50%; margin: 0 auto 16px;
  background: linear-gradient(135deg, rgba(99,102,241,0.1), rgba(139,92,246,0.1));
  display: flex; align-items: center; justify-content: center;
  animation: pgPulse 2s ease-in-out infinite;
}
@keyframes pgPulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.06); } }
.pg-lock-icon { color: #6366F1; }
.pg-title { font-size: 17px; font-weight: 800; color: #1E293B; margin-bottom: 6px; }
.pg-operation { font-size: 13px; color: #475569; margin-bottom: 4px; }
.pg-operation strong { color: #1E293B; }
.pg-subtitle { font-size: 12.5px; color: #94A3B8; margin-bottom: 22px; }
.pg-input-wrap { display: flex; justify-content: center; margin-bottom: 4px; }
.wallet-password-input {
  width: 180px; padding: 12px 10px; border-radius: 12px; border: 2px solid #E2E8F0;
  font-size: 22px; letter-spacing: 8px; text-align: center; font-family: var(--font-main);
  outline: none; transition: border-color 0.25s, box-shadow 0.25s; background: white;
}
.wallet-password-input:focus { border-color: #6366F1; box-shadow: 0 0 0 4px rgba(99,102,241,0.12); }
.wallet-password-input::placeholder { letter-spacing: 4px; font-size: 14px; color: #CBD5E1; }
.pg-error {
  display: inline-flex; align-items: center; gap: 5px;
  color: #EF4444; font-size: 12.5px; font-weight: 600; margin-top: 10px;
  padding: 6px 12px; border-radius: 8px; background: rgba(239,68,68,0.06);
}
.pg-actions { display: flex; justify-content: center; gap: 10px; margin-top: 22px; }
.pg-btn { min-width: 110px; padding: 10px 16px !important; font-size: 12.5px !important; border-radius: 10px !important; }
.pg-btn-confirm { display: inline-flex; align-items: center; gap: 5px; }

.sidebar-nav { display: flex; flex-direction: column; gap: 2px; flex: 1; overflow-y: auto; min-height: 0; }
.nav-item {
  display: flex; align-items: center; gap: 12px; padding: 8px 14px;
  border-radius: 10px; border: none; background: transparent;
  color: var(--text-muted); font-family: var(--font-main); font-size: 13px; font-weight: 600;
  cursor: pointer; transition: all 0.2s; text-align: left; width: 100%;
}
.nav-item:hover { background: var(--gray-100); color: var(--text-title); }
.nav-item.active { background: var(--bg-active-tab); color: var(--text-active-tab); }
.nav-icon { width: 16px; display: flex; align-items: center; color: inherit; }
.nav-label { flex: 1; }
.nav-badge { font-size: 9.5px; font-weight: 700; background: var(--amber-500); color: white; padding: 1px 6px; border-radius: 8px; }
.sidebar-footer { margin-top: auto; border-top: 1px solid var(--border-input); padding-top: 6px; display: flex; flex-direction: column; gap: 2px; }
.logout-btn { color: var(--red-500); }
.logout-btn:hover { background: var(--red-50); color: var(--red-600); }

/* ══════════════════════════════════════════════════════════════════
   A. VUE D'ENSEMBLE — ULTRA PREMIUM KPI WIDGETS
   ══════════════════════════════════════════════════════════════════ */

/* — Grid */
.kpi-grid {
  display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; margin-bottom: 28px;
}

/* — Card base (permanently lifted) */
.kpi-card {
  padding: 26px 24px 22px; display: flex; gap: 16px; align-items: flex-start;
  position: relative; overflow: hidden;
  border: 1px solid rgba(255,255,255,0.18);
  transform: translateY(-7px);
  box-shadow:
    0 24px 70px rgba(15,23,42,0.08),
    0 8px 28px rgba(15,23,42,0.04),
    0 0 0 1px rgba(99,102,241,0.12);
  cursor: default;
  transition:
    transform 0.45s cubic-bezier(0.34, 1.56, 0.64, 1),
    box-shadow 0.45s ease,
    border-color 0.3s ease;
}
/* Hover: micro-lift + deeper shadow + active border */
.kpi-card.kpi-card:hover {
  transform: translateY(-10px);
  box-shadow:
    0 32px 90px rgba(15,23,42,0.10),
    0 12px 40px rgba(15,23,42,0.06),
    0 0 0 1px rgba(99,102,241,0.20);
}
.dark-theme .kpi-card {
  box-shadow:
    0 24px 70px rgba(0,0,0,0.28),
    0 0 0 1px rgba(99,102,241,0.15);
}
.dark-theme .kpi-card.kpi-card:hover {
  box-shadow:
    0 32px 90px rgba(0,0,0,0.35),
    0 0 0 1px rgba(99,102,241,0.25);
}

/* ─────────────────────────────────────────────────────────────────
   NOISE TEXTURE  —  subtle grain overlay
   ───────────────────────────────────────────────────────────────── */
.kp-noise {
  position: absolute; inset: 0; z-index: 0;
  pointer-events: none;
  opacity: 0.020;
  background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)'/%3E%3C/svg%3E");
  background-repeat: repeat;
  background-size: 128px 128px;
  mix-blend-mode: overlay;
}

/* ─────────────────────────────────────────────────────────────────
   1.  AMBIENT GLOW  —  permanently intense breathing orb
   ───────────────────────────────────────────────────────────────── */
.kp-glow {
  position: absolute; top: -40%; right: -30%; width: 130px; height: 130px;
  border-radius: 50%;
  pointer-events: none; z-index: 0;
  animation: glowIntense 2.2s ease-in-out infinite;
  transition: opacity 0.4s ease, transform 0.4s ease;
}
@keyframes glowIntense {
  0%, 100% { opacity: 0.50; transform: translate(-6px, -8px) scale(1.20); }
  50%      { opacity: 0.70; transform: translate(-10px, -12px) scale(1.40); }
}
/* Hover: glow amplifies */
.kpi-card:hover .kp-glow {
  animation: glowHover 1.4s ease-in-out infinite;
}
@keyframes glowHover {
  0%, 100% { opacity: 0.60; transform: translate(-8px, -10px) scale(1.40); }
  50%      { opacity: 0.85; transform: translate(-12px, -14px) scale(1.60); }
}
/* per-card glow colors */
.kpi-loyer .kp-glow { background: radial-gradient(circle, rgba(37,99,235,0.35), transparent 70%); }
.kpi-wallet .kp-glow { background: radial-gradient(circle, rgba(99,102,241,0.35), transparent 70%); }
.kpi-due .kp-glow   { background: radial-gradient(circle, rgba(245,158,11,0.30), transparent 70%); }
.kpi-tickets .kp-glow { background: radial-gradient(circle, rgba(124,58,237,0.30), transparent 70%); }
.dark-theme .kpi-loyer .kp-glow { background: radial-gradient(circle, rgba(59,130,246,0.25), transparent 70%); }
.dark-theme .kpi-wallet .kp-glow { background: radial-gradient(circle, rgba(129,140,248,0.25), transparent 70%); }
.dark-theme .kpi-due .kp-glow   { background: radial-gradient(circle, rgba(251,191,36,0.20), transparent 70%); }
.dark-theme .kpi-tickets .kp-glow { background: radial-gradient(circle, rgba(167,139,250,0.20), transparent 70%); }

/* ─────────────────────────────────────────────────────────────────
   2.  SHINE SWEEP  —  always looping across the card
   ───────────────────────────────────────────────────────────────── */
.kpi-card::before {
  content: ''; position: absolute; inset: 0;
  background: linear-gradient(105deg,
    transparent 20%,
    rgba(255,255,255,0.04) 38%,
    rgba(255,255,255,0.10) 44%,
    rgba(255,255,255,0.04) 50%,
    transparent 70%
  );
  background-size: 200% 100%;
  background-position: 100% 0;
  pointer-events: none; z-index: 1;
  animation: shineSweep 5s ease-in-out infinite;
  transition: opacity 0.3s ease;
}
/* Hover: sweep accelerates */
.kpi-card:hover::before {
  animation-duration: 2.2s;
  opacity: 1;
}
@keyframes shineSweep {
  0%, 100% { background-position: 200% 0; }
  50%      { background-position: -30% 0; }
}
.dark-theme .kpi-card::before {
  background: linear-gradient(105deg,
    transparent 20%,
    rgba(255,255,255,0.02) 38%,
    rgba(255,255,255,0.05) 44%,
    rgba(255,255,255,0.02) 50%,
    transparent 70%
  );
  background-size: 200% 100%;
}

/* ─────────────────────────────────────────────────────────────────
   3.  BORDER ACCENT  —  per-card tinted overlay (always visible)
   ───────────────────────────────────────────────────────────────── */
.kpi-card::after {
  content: ''; position: absolute; inset: -1px;
  border-radius: inherit;
  pointer-events: none; z-index: 2;
  opacity: 1;
  transition: opacity 0.4s ease;
}
/* Hover: border accent brightens */
.kpi-card:hover::after { opacity: 1; }
.kpi-loyer::after { background: linear-gradient(135deg, rgba(37,99,235,0.25), transparent 60%); }
.kpi-wallet::after { background: linear-gradient(135deg, rgba(99,102,241,0.25), transparent 60%); }
.kpi-due::after   { background: linear-gradient(135deg, rgba(245,158,11,0.20), transparent 60%); }
.kpi-tickets::after { background: linear-gradient(135deg, rgba(124,58,237,0.20), transparent 60%); }

/* ─────────────────────────────────────────────────────────────────
   4.  ICON  —  circular, slightly rotated + ring pulse
       HOVER: spring bounce + ring flare
   ───────────────────────────────────────────────────────────────── */
.kp-icon-wrap {
  width: 50px; height: 50px; border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%);
  color: var(--blue-600); flex-shrink: 0;
  box-shadow: 0 8px 28px rgba(37,99,235,0.22), inset 0 1px 0 rgba(255,255,255,0.6);
  position: relative; z-index: 3;
  transform: scale(1.08);
  transition:
    transform 0.6s cubic-bezier(0.34, 1.9, 0.64, 1),
    box-shadow 0.4s ease;
}
/* Hover: icon springs with a bounce + rotation */
.kpi-card:hover .kp-icon-wrap {
  transform: scale(1.22) rotate(-8deg);
  box-shadow: 0 12px 40px rgba(37,99,235,0.30), inset 0 1px 0 rgba(255,255,255,0.6);
}

/* Ring pulse — always visible */
.kp-icon-wrap::after {
  content: ''; position: absolute; inset: -8px;
  border-radius: 50%;
  border: 2px solid currentColor;
  opacity: 0.20;
  pointer-events: none;
  animation: iconRing 2s ease-in-out infinite;
  transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
}
/* Hover: ring flares out then pulses larger */
.kpi-card:hover .kp-icon-wrap::after {
  inset: -14px;
  opacity: 0.35;
  border-width: 2.5px;
  animation: iconRingHover 1.2s ease-in-out infinite;
}
@keyframes iconRing {
  0%, 100% { transform: scale(1); opacity: 0.20; }
  50%      { transform: scale(1.08); opacity: 0.06; }
}
@keyframes iconRingHover {
  0%, 100% { transform: scale(1); opacity: 0.30; border-width: 2.5px; }
  50%      { transform: scale(1.15); opacity: 0.08; border-width: 1.5px; }
}

.dark-theme .kp-icon-wrap {
  background: linear-gradient(135deg, rgba(37,99,235,0.18), rgba(37,99,235,0.08));
  box-shadow: 0 8px 28px rgba(0,0,0,0.20), inset 0 1px 0 rgba(255,255,255,0.05);
}

/* Accent variants */
.kpi-icon-blue {
  background: linear-gradient(135deg, #EEF2FF 0%, #E0E7FF 100%);
  color: #6366F1;
  box-shadow: 0 8px 28px rgba(99,102,241,0.25), inset 0 1px 0 rgba(255,255,255,0.6);
}
.kpi-card:hover .kpi-icon-blue { box-shadow: 0 12px 40px rgba(99,102,241,0.35), inset 0 1px 0 rgba(255,255,255,0.6); }
.dark-theme .kpi-icon-blue { background: linear-gradient(135deg, rgba(99,102,241,0.22), rgba(99,102,241,0.08)); box-shadow: 0 8px 28px rgba(0,0,0,0.20), inset 0 1px 0 rgba(255,255,255,0.05); }

.kpi-icon-amber {
  background: linear-gradient(135deg, #FFFBEB 0%, #FEF3C7 100%);
  color: var(--amber-500);
  box-shadow: 0 8px 28px rgba(245,158,11,0.20), inset 0 1px 0 rgba(255,255,255,0.6);
}
.kpi-card:hover .kpi-icon-amber { box-shadow: 0 12px 40px rgba(245,158,11,0.30), inset 0 1px 0 rgba(255,255,255,0.6); }
.dark-theme .kpi-icon-amber { background: linear-gradient(135deg, rgba(245,158,11,0.18), rgba(245,158,11,0.08)); box-shadow: 0 8px 28px rgba(0,0,0,0.20), inset 0 1px 0 rgba(255,255,255,0.05); }

.kpi-icon-violet {
  background: linear-gradient(135deg, #F3E8FF 0%, #EDE9FE 100%);
  color: #7C3AED;
  box-shadow: 0 8px 28px rgba(124,58,237,0.20), inset 0 1px 0 rgba(255,255,255,0.6);
}
.kpi-card:hover .kpi-icon-violet { box-shadow: 0 12px 40px rgba(124,58,237,0.30), inset 0 1px 0 rgba(255,255,255,0.6); }
.dark-theme .kpi-icon-violet { background: linear-gradient(135deg, rgba(124,58,237,0.18), rgba(124,58,237,0.08)); box-shadow: 0 8px 28px rgba(0,0,0,0.20), inset 0 1px 0 rgba(255,255,255,0.05); }

/* — Body */
.kp-body { flex: 1; min-width: 0; position: relative; z-index: 3; }

/* Corner accent dot on body */
.kp-body::after {
  content: ''; position: absolute; top: -2px; right: -2px;
  width: 6px; height: 6px; border-radius: 50%;
  opacity: 0.35;
  transition: opacity 0.3s ease, transform 0.3s ease;
}
.kpi-card:hover .kp-body::after {
  opacity: 0.70;
  transform: scale(1.4);
}
.kpi-loyer .kp-body::after { background: #3B82F6; box-shadow: 0 0 8px rgba(59,130,246,0.3); }
.kpi-wallet .kp-body::after { background: #6366F1; box-shadow: 0 0 8px rgba(99,102,241,0.3); }
.kpi-due .kp-body::after   { background: #F59E0B; box-shadow: 0 0 8px rgba(245,158,11,0.3); }
.kpi-tickets .kp-body::after { background: #7C3AED; box-shadow: 0 0 8px rgba(124,58,237,0.3); }
.dark-theme .kpi-loyer .kp-body::after { background: #60A5FA; }
.dark-theme .kpi-wallet .kp-body::after { background: #818CF8; }
.dark-theme .kpi-due .kp-body::after   { background: #FBBF24; }
.dark-theme .kpi-tickets .kp-body::after { background: #A78BFA; }

/* — Label */
.kp-label {
  font-size: 10px; font-weight: 750;
  color: var(--text-muted);
  text-transform: uppercase; letter-spacing: 0.8px;
  margin-bottom: 4px;
  transition: color 0.3s ease;
}

/* — Value */
.kp-value {
  font-size: 26px; font-weight: 850;
  color: var(--text-title);
  letter-spacing: -0.6px; line-height: 1.1;
  transition: color 0.3s ease, text-shadow 0.3s ease;
}
/* Hover: value glows subtly */
.kpi-card:hover .kp-value {
  color: var(--text-title);
}
.kpi-loyer:hover .kp-value { text-shadow: 0 0 16px rgba(37,99,235,0.06); }
.kpi-wallet:hover .kp-value { text-shadow: 0 0 16px rgba(99,102,241,0.06); }
.kpi-due:hover .kp-value   { text-shadow: 0 0 16px rgba(245,158,11,0.06); }
.kpi-tickets:hover .kp-value { text-shadow: 0 0 16px rgba(124,58,237,0.06); }

/* Wallet gradient value */
.kp-value-wallet {
  background: linear-gradient(135deg, #6366F1, #A78BFA, #6366F1);
  background-size: 200% 200%;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  animation: walletGradient 4s ease-in-out infinite;
}
@keyframes walletGradient {
  0%, 100% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
}
.kpi-wallet:hover .kp-value-wallet { animation-duration: 1.8s; }

/* Danger / warning value tints */
.kp-value-danger { color: #DC2626; }
.dark-theme .kp-value-danger { color: #F87171; }
.kp-value-warning { color: #D97706; }
.dark-theme .kp-value-warning { color: #FBBF24; }

/* — Badge (pill with dot) */
.kp-badge {
  display: inline-flex; align-items: center; gap: 5px;
  font-size: 9.5px; font-weight: 750;
  padding: 4px 12px 4px 10px; border-radius: 20px;
  margin-top: 6px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.03);
  position: relative; z-index: 3;
  transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
}
/* Hover: badge scales slightly */
.kpi-card:hover .kp-badge {
  transform: scale(1.04);
  box-shadow: 0 4px 12px rgba(0,0,0,0.06);
}
.kp-badge-dot {
  width: 5px; height: 5px; border-radius: 50%;
  display: inline-block;
  animation: badgePulse 2s ease-in-out infinite;
}
@keyframes badgePulse {
  0%, 100% { opacity: 1; transform: scale(1); }
  50% { opacity: 0.5; transform: scale(0.8); }
}
.kp-badge.badge-paid { background: linear-gradient(135deg, #D1FAE5, #A7F3D0); color: #059669; }
.kp-badge.badge-paid .kp-badge-dot { background: #059669; }
.dark-theme .kp-badge.badge-paid { background: rgba(5,150,101,0.15); color: #34D399; }
.dark-theme .kp-badge.badge-paid .kp-badge-dot { background: #34D399; }
.kp-badge.badge-pending { background: linear-gradient(135deg, #FEF3C7, #FDE68A); color: #D97706; }
.kp-badge.badge-pending .kp-badge-dot { background: #D97706; }
.dark-theme .kp-badge.badge-pending { background: rgba(217,119,6,0.15); color: #FBBF24; }
.dark-theme .kp-badge.badge-pending .kp-badge-dot { background: #FBBF24; }

/* — Hint */
.kp-hint {
  font-size: 10.5px; font-weight: 500;
  display: block; margin-top: 4px;
  position: relative; z-index: 3;
  transition: color 0.3s ease;
}
.kp-hint-ok { color: #059669; }
.kp-hint-warn { color: #D97706; }
.dark-theme .kp-hint-ok { color: #34D399; }
.dark-theme .kp-hint-warn { color: #FBBF24; }

/* — Wallet inline actions */
.wallet-kpi-actions { display: flex; gap: 6px; margin-top: 10px; flex-wrap: wrap; position: relative; z-index: 3; }
.wallet-inline-btn {
  padding: 7px 14px; border-radius: 10px; border: none;
  background: linear-gradient(135deg, #6366F1, #8B5CF6);
  color: white; font-family: var(--font-main);
  font-size: 10.5px; font-weight: 750; cursor: pointer;
  transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
  box-shadow: 0 3px 12px rgba(99,102,241,0.20);
  letter-spacing: 0.2px;
}
.wallet-inline-btn:hover { box-shadow: 0 6px 24px rgba(99,102,241,0.35); transform: translateY(-2px) scale(1.02); }
.wallet-inline-btn:active { transform: scale(0.94); }
.wallet-inline-btn.secondary {
  background: var(--gray-100); color: #475569;
  box-shadow: none;
}
.dark-theme .wallet-inline-btn.secondary { background: rgba(148,163,184,0.12); color: #CBD5E1; }
.wallet-inline-btn.secondary:hover { background: var(--gray-200); box-shadow: none; transform: translateY(-1px); }
.dark-theme .wallet-inline-btn.secondary:hover { background: rgba(148,163,184,0.2); }

/* — Stagger entrance animation */
.kpi-grid > .kpi-card {
  animation: kpiEntrance 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) both;
}
.kpi-grid > .kpi-card:nth-child(1) { animation-delay: 0.03s; }
.kpi-grid > .kpi-card:nth-child(2) { animation-delay: 0.08s; }
.kpi-grid > .kpi-card:nth-child(3) { animation-delay: 0.13s; }
.kpi-grid > .kpi-card:nth-child(4) { animation-delay: 0.18s; }
@keyframes kpiEntrance {
  0% { opacity: 0; transform: translateY(18px) scale(0.96); }
  100% { opacity: 1; transform: translateY(0) scale(1); }
}

/* Transfer to Landlord panel layout */
.transfer-widget-card { padding: 0; margin-bottom: 20px; overflow: hidden; border: none; box-shadow: 0 4px 24px rgba(99,102,241,0.06); }
.tw-header { display: flex; align-items: center; justify-content: space-between; padding: 16px 20px; background: linear-gradient(135deg, #EEF2FF, #E0E7FF); border-bottom: 1px solid rgba(99,102,241,0.08); }
.dark-theme .tw-header { background: linear-gradient(135deg, rgba(99,102,241,0.08), rgba(99,102,241,0.04)); }
.tw-header-left { display: flex; align-items: center; gap: 12px; }
.tw-icon { width: 38px; height: 38px; border-radius: 10px; background: linear-gradient(135deg, #6366F1, #8B5CF6); display: flex; align-items: center; justify-content: center; color: white; }
.tw-title { font-size: 14px; font-weight: 750; color: #1E293B; }
.dark-theme .tw-title { color: #F1F5F9; }
.tw-sub { font-size: 11px; color: #64748B; }
.tw-close { background: transparent; border: none; font-size: 20px; color: #94A3B8; cursor: pointer; padding: 4px; border-radius: 6px; transition: 0.2s; }
.tw-close:hover { background: rgba(0,0,0,0.04); color: #475569; }
.tw-body { padding: 20px; display: flex; flex-direction: column; gap: 14px; }
.tw-field { display: flex; flex-direction: column; gap: 5px; }
.tw-label { font-size: 11.5px; font-weight: 650; color: #475569; }
.dark-theme .tw-label { color: #94A3B8; }
.tw-input-group { display: flex; align-items: center; border: 1.5px solid #E2E8F0; border-radius: 10px; overflow: hidden; transition: border-color 0.2s; background: white; }
.dark-theme .tw-input-group { background: #1E293B; border-color: #334155; }
.tw-input-group:focus-within { border-color: #6366F1; box-shadow: 0 0 0 3px rgba(99,102,241,0.08); }
.tw-prefix { padding: 0 12px; font-size: 12px; font-weight: 700; color: #6366F1; background: #F1F5F9; height: 100%; display: flex; align-items: center; }
.dark-theme .tw-prefix { background: #334155; color: #A5B4FC; }
.tw-input { flex: 1; padding: 10px 12px; border: none; font-size: 13px; font-family: var(--font-main); outline: none; background: transparent; color: #1E293B; }
.dark-theme .tw-input { color: #F1F5F9; }
.tw-input::placeholder { color: #94A3B8; }
.tw-input:focus { border-color: #6366F1; }
.tw-btn { display: flex; align-items: center; justify-content: center; gap: 8px; padding: 11px 20px; border: none; border-radius: 10px; background: linear-gradient(135deg, #6366F1, #8B5CF6); color: white; font-size: 13px; font-weight: 700; cursor: pointer; transition: all 0.25s; font-family: var(--font-main); }
.tw-btn:hover:not(:disabled) { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(99,102,241,0.25); }
.tw-btn:disabled { opacity: 0.45; cursor: not-allowed; }
.overview-lower-grid { display: grid; grid-template-columns: 1fr 1.4fr; gap: 18px; }
.card-title { font-size: 15px; font-weight: 700; color: var(--text-title); letter-spacing: -0.2px; }
.card-subtitle-text { font-size: 12.5px; color: var(--text-muted); line-height: 1.5; margin-top: 2px; }

.quick-actions-card { padding: 20px; }
.quick-actions-list { display: flex; flex-direction: column; gap: 10px; margin-top: 12px; }
.quick-action-btn {
  display: flex; align-items: center; gap: 12px; padding: 12px 14px; border-radius: 12px;
  border: 1px solid var(--border-input); background: var(--bg-input); cursor: pointer;
  transition: all 0.25s ease; text-align: left; width: 100%;
}
.quick-action-btn:hover { border-color: var(--blue-300); background: #F0F5FF; transform: translateY(-1px); }
.quick-action-btn.primary { background: linear-gradient(135deg, var(--blue-600), #1D4ED8); color: white; border: none; box-shadow: 0 4px 14px rgba(37,99,235,0.2); }
.quick-action-btn.primary:hover { box-shadow: 0 6px 20px rgba(37,99,235,0.3); transform: translateY(-1px); }
.quick-action-btn.primary .qa-icon { background: rgba(255,255,255,0.18); color: white; }
.qa-icon { width: 40px; height: 40px; border-radius: 12px; background: var(--gray-100); color: var(--text-muted); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.qa-icon.amber { background: linear-gradient(135deg, #FEF3C7, #FDE68A); color: #D97706; }
.qa-text { flex: 1; display: flex; flex-direction: column; }
.qa-text strong { font-size: 13px; font-weight: 700; }
.qa-text small { font-size: 10.5px; color: var(--text-muted); margin-top: 2px; }
.quick-action-btn.primary .qa-text small { color: rgba(255,255,255,0.7); }

/* Wallet transactions visual lists */
.wallet-tx-card { padding: 20px; }
.wallet-tx-list { display: flex; flex-direction: column; gap: 10px; margin-top: 12px; }
.tx-item { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 10px; background: var(--bg-input); border: 1px solid var(--border-input); transition: border-color 0.2s; }
.tx-item:hover { border-color: var(--gray-300); }
.tx-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
.tx-icon.recharge { background: linear-gradient(135deg, #D1FAE5, #A7F3D0); color: var(--emerald-600); }
.tx-icon.payment { background: linear-gradient(135deg, #FEE2E2, #FECACA); color: var(--red-500); }
.tx-info { flex: 1; overflow: hidden; }
.tx-name { font-size: 11.5px; font-weight: 700; color: var(--text-title); text-overflow: ellipsis; white-space: nowrap; overflow: hidden; }
.tx-meta { font-size: 9.5px; color: var(--text-muted); }
.tx-amount { font-size: 12px; font-weight: 700; text-align: right; }
.tx-amount.recharge { color: var(--emerald-600); }
.tx-amount.payment { color: var(--text-title); }
.tx-empty { font-size: 11px; color: var(--text-muted); text-align: center; }

/* ══════════════════════════════════════════════════════════════════
   B. LOGEMENTS & CONTRATS
   ══════════════════════════════════════════════════════════════════ */
.contract-block { display: flex; flex-direction: column; gap: 18px; margin-bottom: 24px; }
.property-card { display: flex; padding: 0; overflow: hidden; }
.property-image-wrap { width: 240px; position: relative; flex-shrink: 0; }
.premium-image-mockup { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #CBD5E1; min-height: 200px; }
.property-image-overlay { position: absolute; top: 10px; left: 10px; }
.property-type-badge { font-size: 9px; font-weight: 700; text-transform: uppercase; background: rgba(15,23,42,0.7); color: white; padding: 3px 6px; border-radius: 4px; }
.property-details { flex: 1; padding: 20px; display: flex; flex-direction: column; gap: 10px; }
.property-header { display: flex; align-items: flex-start; justify-content: space-between; }
.property-name { font-size: 16px; font-weight: 800; color: var(--text-title); }
.property-address { display: flex; align-items: center; gap: 3px; font-size: 11.5px; color: var(--text-muted); }
.property-specs-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; background: var(--gray-50); padding: 10px; border-radius: 10px; }
.spec-item { display: flex; flex-direction: column; }
.spec-capsule { display: flex; align-items: center; gap: 4px; }
.spec-label { font-size: 9px; color: var(--text-muted); text-transform: uppercase; font-weight: 600; }
.spec-value { font-size: 11px; font-weight: 700; color: var(--gray-700); }
.equipment-section { display: flex; flex-direction: column; gap: 6px; }
.section-subtitle { font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.3px; }
.section-subtitle-row { display: flex; align-items: center; gap: 6px; }
.eq-icon-ring { width: 22px; height: 22px; border-radius: 6px; background: linear-gradient(135deg, #DBEAFE, #BFDBFE); color: #2563EB; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.equipment-tags { display: flex; gap: 4px; flex-wrap: wrap; }
.eq-tag { font-size: 10.5px; font-weight: 600; color: var(--text-body); background: var(--bg-input); border: 1px solid var(--border-input); padding: 2px 6px; border-radius: 5px; }

.bail-details-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
.bail-info-card { padding: 24px; }
.bail-rows { display: flex; flex-direction: column; gap: 8px; }
.bail-row { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 10px; background: var(--bg-input); border: 1px solid var(--border-input); font-size: 12.5px; }
.bail-icon-ring { width: 30px; height: 30px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.bail-icon-blue { background: #DBEAFE; color: #2563EB; }
.bail-icon-emerald { background: #D1FAE5; color: #059669; }
.bail-icon-red { background: #FEE2E2; color: #DC2626; }
.bail-icon-amber { background: #FEF3C7; color: #D97706; }
.bail-key { color: var(--text-muted); flex: 1; }
.bail-value { font-weight: 700; color: var(--text-title); }
.bail-value.accent-blue { color: var(--blue-600); }

.documents-card { padding: 24px; }
.documents-list { display: flex; flex-direction: column; gap: 8px; }
.document-row { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 10px; background: var(--bg-input); border: 1px solid var(--border-input); font-size: 12px; }
.doc-icon-wrap { width: 34px; height: 34px; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
.doc-icon-wrap.doc-uploaded { background: linear-gradient(135deg, #D1FAE5, #A7F3D0); color: #059669; }
.doc-icon-wrap.doc-missing { background: linear-gradient(135deg, #FEE2E2, #FECACA); color: #DC2626; }
.doc-info { flex: 1; }
.doc-name { font-weight: 700; color: var(--text-title); }
.doc-meta { font-size: 9.5px; color: var(--text-muted); }
.doc-actions { display: flex; gap: 4px; }
.doc-btn { border: none; cursor: pointer; padding: 3px 6px; border-radius: 5px; font-size: 10.5px; font-weight: 700; font-family: var(--font-main); }
.doc-btn.view { background: var(--gray-100); color: var(--text-body); }
.doc-btn.upload { background: var(--blue-50); color: var(--blue-600); }

/* ══════════════════════════════════════════════════════════════════
   C. FINANCES & SUCCESSIVE MONTHS SELECTOR
   ══════════════════════════════════════════════════════════════════ */
.finance-summary-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 20px; }
.fin-summary-card { padding: 18px 16px; display: flex; align-items: center; gap: 14px; cursor: default; }
.fin-summary-card:hover { transform: translateY(-2px); }
.fin-sum-icon-wrap {
  width: 40px; height: 40px; border-radius: 12px;
  display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.fin-sum-icon-wrap.sum-icon-blue { background: #DBEAFE; color: #2563EB; }
.fin-sum-icon-wrap.sum-icon-amber { background: #FEF3C7; color: #D97706; }
.fin-sum-icon-wrap.sum-icon-red { background: #FEE2E2; color: #DC2626; }
.fin-sum-icon-wrap.sum-icon-emerald { background: #D1FAE5; color: #059669; }
.dark-theme .fin-sum-icon-wrap.sum-icon-blue { background: rgba(37,99,235,0.2); color: #60A5FA; }
.dark-theme .fin-sum-icon-wrap.sum-icon-amber { background: rgba(217,119,6,0.2); color: #FBBF24; }
.dark-theme .fin-sum-icon-wrap.sum-icon-red { background: rgba(220,38,38,0.2); color: #F87171; }
.dark-theme .fin-sum-icon-wrap.sum-icon-emerald { background: rgba(5,150,101,0.2); color: #34D399; }
.dark-theme .qa-icon { background: rgba(255,255,255,0.08); color: var(--text-muted); }
.dark-theme .qa-icon.amber { background: linear-gradient(135deg, rgba(217,119,6,0.25), rgba(217,119,6,0.1)); color: #FBBF24; }
.dark-theme .tx-icon.recharge { background: linear-gradient(135deg, rgba(5,150,101,0.25), rgba(5,150,101,0.1)); color: #34D399; }
.dark-theme .tx-icon.payment { background: linear-gradient(135deg, rgba(220,38,38,0.25), rgba(220,38,38,0.1)); color: #F87171; }
.dark-theme .bail-icon-blue { background: rgba(37,99,235,0.2); color: #60A5FA; }
.dark-theme .bail-icon-emerald { background: rgba(5,150,101,0.2); color: #34D399; }
.dark-theme .bail-icon-red { background: rgba(220,38,38,0.2); color: #F87171; }
.dark-theme .bail-icon-amber { background: rgba(217,119,6,0.2); color: #FBBF24; }
.dark-theme .doc-icon-wrap.doc-uploaded { background: linear-gradient(135deg, rgba(5,150,101,0.25), rgba(5,150,101,0.1)); color: #34D399; }
.dark-theme .doc-icon-wrap.doc-missing { background: linear-gradient(135deg, rgba(220,38,38,0.25), rgba(220,38,38,0.1)); color: #F87171; }
.dark-theme .contract-fee-icon-ring { background: linear-gradient(135deg, rgba(217,119,6,0.25), rgba(217,119,6,0.1)); color: #FBBF24; }
.dark-theme .eq-icon-ring { background: linear-gradient(135deg, rgba(37,99,235,0.25), rgba(37,99,235,0.1)); color: #60A5FA; }
.dark-theme .property-status-badge.active { background: rgba(5,150,101,0.2); color: #34D399; }
.fin-sum-body { flex: 1; }
.fin-sum-label { font-size: 10px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.3px; }
.fin-sum-value { font-size: 20px; font-weight: 800; color: var(--text-title); margin-top: 2px; transition: color 0.3s; }
.fin-sum-value.blue { color: var(--blue-600); }
.fin-sum-value.amber { color: var(--amber-500); }
.fin-sum-value.red { color: #DC2626; }
.fin-sum-value.emerald { color: var(--emerald-600); }
.fin-sum-hint { font-size: 9.5px; color: var(--text-muted); margin-top: 1px; }

/* ⚡ Premium utility cards */
.util-premium-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px; }
.util-premium-card { padding: 0; overflow: hidden; }
.util-premium-inner { display: flex; align-items: center; justify-content: space-between; padding: 20px 20px 16px; }
.util-premium-left { display: flex; align-items: center; gap: 14px; }
.util-icon-ring {
  width: 48px; height: 48px; border-radius: 14px;
  display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.util-icon-water { background: linear-gradient(135deg, #DBEAFE, #BFDBFE); color: #2563EB; }
.util-icon-elec { background: linear-gradient(135deg, #FEF3C7, #FDE68A); color: #D97706; }
.dark-theme .util-icon-water { background: linear-gradient(135deg, rgba(37,99,235,0.25), rgba(37,99,235,0.1)); color: #60A5FA; }
.dark-theme .util-icon-elec { background: linear-gradient(135deg, rgba(217,119,6,0.25), rgba(217,119,6,0.1)); color: #FBBF24; }
.util-premium-info { display: flex; flex-direction: column; }
.util-premium-label { font-size: 11px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.3px; }
.util-premium-conso { font-size: 22px; font-weight: 800; color: var(--text-title); line-height: 1.2; margin-top: 2px; }
.util-premium-conso small { font-size: 13px; font-weight: 600; color: var(--text-muted); }
.util-premium-cost { font-size: 13px; font-weight: 700; color: var(--text-body); margin-top: 1px; }
.util-premium-right { display: flex; flex-direction: column; align-items: flex-end; gap: 4px; }
.util-premium-period { font-size: 11px; font-weight: 600; color: var(--text-muted); }
.util-premium-badge {
  font-size: 10px; font-weight: 700; padding: 2px 10px; border-radius: 8px;
}
.util-premium-badge.badge-paid { background: #D1FAE5; color: #059669; }
.util-premium-badge.badge-pending { background: #FEF3C7; color: #D97706; }
.dark-theme .util-premium-badge.badge-paid { background: rgba(5,150,101,0.2); color: #34D399; }
.dark-theme .util-premium-badge.badge-pending { background: rgba(217,119,6,0.2); color: #FBBF24; }
.util-premium-bar-track {
  height: 3px; background: var(--gray-100); margin: 0 20px 16px; border-radius: 4px; overflow: hidden;
}
.dark-theme .util-premium-bar-track { background: #1E293B; }
.util-premium-bar-fill {
  height: 100%; border-radius: 4px; transition: width 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
}
.util-bar-water { background: linear-gradient(90deg, #3B82F6, #2563EB); }
.util-bar-elec { background: linear-gradient(90deg, #F59E0B, #D97706); }

/* 🗓️ MONTH SELECTOR STYLES (Ultra Clean) */
.rent-payment-selector-card { padding: 24px; margin-bottom: 20px; }
/* ══════════════════════════════════════════════════════════════════
   PREMIUM MONTH SELECTOR CARDS + YEAR GROUPING
   ══════════════════════════════════════════════════════════════════ */
.year-group { margin-bottom: 18px; }
.year-group:last-child { margin-bottom: 0; }
.year-header {
  display: flex; align-items: center; gap: 12px; margin-bottom: 10px;
  padding: 8px 14px; border-radius: 10px;
  background: linear-gradient(135deg, #F1F5F9, #F8FAFC);
  border: 1px solid #E2E8F0;
}
.dark-theme .year-header { background: linear-gradient(135deg, rgba(15,23,42,0.5), rgba(30,41,59,0.3)); border-color: #334155; }
.year-badge {
  font-size: 15px; font-weight: 800; color: #0F172A;
  letter-spacing: 0.5px;
}
.dark-theme .year-badge { color: #F1F5F9; }
.year-progress { margin-left: auto; display: flex; align-items: center; gap: 4px; }
.year-paid-count { font-size: 13px; font-weight: 800; color: #059669; }
.year-paid-label { font-size: 10px; color: #94A3B8; font-weight: 500; }
.year-paid-count.complete { color: #059669; }
.month-selector-grid { display: grid; grid-template-columns: repeat(6, 1fr); gap: 12px; }

.mc-card {
  border-radius: 14px; padding: 16px 12px 12px; text-align: center; cursor: pointer; position: relative; overflow: hidden;
  background: white; border: 1px solid #F1F5F9;
  transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
  display: flex; flex-direction: column; gap: 8px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.02);
}
.dark-theme .mc-card { background: #1E293B; border-color: #334155; }
.mc-card::before {
  content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
  background: linear-gradient(90deg, #E2E8F0, #CBD5E1); transition: all 0.3s;
}
.mc-card::after {
  content: ''; position: absolute; inset: 0; border-radius: 14px;
  background: linear-gradient(135deg, rgba(99,102,241,0.03), transparent);
  opacity: 0; transition: opacity 0.3s;
}
.mc-card:hover:not(.mc-paid) { transform: translateY(-3px); border-color: #A5B4FC; box-shadow: 0 8px 24px rgba(99,102,241,0.08); }
.mc-card:hover:not(.mc-paid)::after { opacity: 1; }
.mc-card:hover:not(.mc-paid)::before { background: linear-gradient(90deg, #6366F1, #8B5CF6); }

.mc-card.mc-selected {
  border-color: #6366F1; background: linear-gradient(135deg, rgba(99,102,241,0.04), rgba(139,92,246,0.02));
  box-shadow: 0 0 0 3px rgba(99,102,241,0.1), 0 8px 24px rgba(99,102,241,0.06);
  transform: translateY(-2px);
  animation: mcPulse 0.4s ease;
}
.mc-card.mc-selected::before { background: linear-gradient(90deg, #6366F1, #8B5CF6); }
@keyframes mcPulse { 0% { transform: scale(1); } 50% { transform: scale(1.03); } 100% { transform: translateY(-2px) scale(1); } }

.mc-card.mc-paid { background: #F8FAFC; border-color: #E2E8F0; cursor: default; opacity: 0.7; }
.dark-theme .mc-card.mc-paid { background: rgba(16,185,129,0.04); border-color: rgba(16,185,129,0.12); }
.mc-card.mc-paid:hover { box-shadow: none; transform: none; }
.mc-card.mc-paid::before { background: linear-gradient(90deg, #10B981, #059669); }
.mc-card.mc-paid .mc-amount { color: #059669; }

.mc-top { display: flex; align-items: center; justify-content: space-between; gap: 4px; position: relative; z-index: 1; }
.mc-label { font-size: 13px; font-weight: 700; color: #1E293B; }
.dark-theme .mc-label { color: #F1F5F9; }
.mc-badge { font-size: 8.5px; font-weight: 800; text-transform: uppercase; padding: 2px 5px; border-radius: 5px; letter-spacing: 0.3px; }
.mc-paid-badge { background: rgba(16,185,129,0.1); color: #059669; }
.mc-sel-badge { background: rgba(99,102,241,0.1); color: #6366F1; }
.mc-unpaid-badge { background: #F1F5F9; color: #94A3B8; }
.dark-theme .mc-unpaid-badge { background: rgba(148,163,184,0.1); color: #94A3B8; }
.mc-penalty-badge { background: rgba(239,68,68,0.12); color: #DC2626; font-weight: 700; }
.dark-theme .mc-penalty-badge { background: rgba(239,68,68,0.18); color: #F87171; }
.mc-penalty-amount { display: block; font-size: 0.7rem; color: #DC2626; font-weight: 600; margin-top: 2px; }
.dark-theme .mc-penalty-amount { color: #F87171; }

.mc-amount-wrap { position: relative; z-index: 1; }
.mc-amount { font-size: 14px; font-weight: 800; color: #1E293B; }
.dark-theme .mc-amount { color: #F1F5F9; }
.mc-bar { height: 2px; border-radius: 1px; background: #F1F5F9; position: relative; z-index: 1; }
.dark-theme .mc-bar { background: #334155; }
.mc-card.mc-selected .mc-bar { background: linear-gradient(90deg, #6366F1, #8B5CF6); }
.mc-card.mc-paid .mc-bar { background: #10B981; }

.rent-selection-summary {
  display: flex; align-items: center; justify-content: space-between;
  padding: 18px 22px; border-radius: 16px; background: linear-gradient(135deg, #EEF2FF, #E0E7FF);
  border: 1px solid rgba(99,102,241,0.1); flex-wrap: wrap; gap: 12px; font-size: 14px;
  box-shadow: 0 4px 16px rgba(99,102,241,0.04);
}
.dark-theme .rent-selection-summary { background: linear-gradient(135deg, rgba(99,102,241,0.06), rgba(99,102,241,0.02)); border-color: rgba(99,102,241,0.15); }
.summary-details p { font-size: 13px; color: #475569; }
.dark-theme .summary-details p { color: #94A3B8; }
.total-rent-to-pay { font-size: 14px; font-weight: 700; margin-top: 2px; }
.total-rent-to-pay strong { color: #6366F1; }
.payment-approved-box, .payment-denied-box { display: flex; align-items: center; gap: 12px; }
.balance-hint { font-size: 12px; color: #059669; font-weight: 600; }
.balance-warning { font-size: 12px; color: #EF4444; font-weight: 600; }

/* ═══════════════════════════════════════════
   PAYMENT CONFIRMATION MODAL
═══════════════════════════════════════════ */
.payment-modal-overlay {
  position: fixed; top: 0; left: 0; width: 100%; height: 100%;
  background: rgba(15,23,42,0.55);
  display: flex; align-items: center; justify-content: center;
  z-index: 1000; padding: 20px; backdrop-filter: blur(6px);
}
.dark-theme .payment-modal-overlay { background: rgba(0,0,0,0.6); }
.payment-modal-card {
  width: 100%; max-width: 480px;
  padding: 0; border-radius: 20px; box-shadow: 0 24px 60px rgba(0,0,0,0.2);
}
.payment-modal-header {
  display: flex; align-items: center; gap: 14px; padding: 22px 24px 16px;
  border-bottom: 1px solid var(--border-input);
}
.pm-header-icon {
  width: 44px; height: 44px; border-radius: 12px;
  background: linear-gradient(135deg, #EEF2FF, #E0E7FF); color: #6366F1;
  display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.pm-title { font-size: 16px; font-weight: 800; color: var(--text-title); }
.pm-sub { font-size: 11.5px; color: var(--text-muted); margin-top: 1px; }
.pm-close { margin-left: auto; width: 30px; height: 30px; border-radius: 8px; border: none; background: var(--gray-100); font-size: 20px; cursor: pointer; display: flex; align-items: center; justify-content: center; color: var(--text-muted); }
.pm-close:hover { background: var(--gray-200); }
.payment-modal-body { padding: 20px 24px; display: flex; flex-direction: column; gap: 14px; }
.pm-section-title { font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.3px; display: flex; align-items: center; gap: 6px; margin-bottom: 10px; }
.pm-months-list { display: flex; flex-direction: column; gap: 6px; max-height: 220px; overflow-y: auto; -webkit-overflow-scrolling: touch; scrollbar-width: thin; }
.pm-months-list::-webkit-scrollbar { width: 4px; }
.pm-months-list::-webkit-scrollbar-track { background: transparent; }
.pm-months-list::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 2px; }
.pm-month-row { display: flex; align-items: center; gap: 10px; padding: 8px 10px; border-radius: 8px; background: var(--bg-input); border: 1px solid var(--border-input); }
.pm-month-dot { width: 8px; height: 8px; border-radius: 50%; background: #6366F1; flex-shrink: 0; }
.pm-invoice-detail { display: flex; flex-direction: column; gap: 6px; }
.pm-invoice-row { display: flex; align-items: center; justify-content: space-between; padding: 6px 10px; border-radius: 6px; background: var(--bg-input); border: 1px solid var(--border-input); font-size: 12.5px; }
.pm-invoice-row span { color: var(--text-muted); }
.pm-invoice-row strong { font-weight: 700; color: var(--text-title); }
.pm-month-label { flex: 1; font-size: 13px; font-weight: 600; color: var(--text-title); }
.pm-month-amount { font-size: 13px; font-weight: 700; color: var(--text-body); }
.pm-divider { height: 1px; background: var(--border-input); }
.pm-summary { display: flex; flex-direction: column; gap: 8px; }
.pm-summary-row { display: flex; align-items: center; justify-content: space-between; font-size: 13px; }
.pms-label { color: var(--text-muted); }
.pms-value { font-weight: 700; color: var(--text-title); }
.pms-value.green { color: #059669; }
.pms-value.red { color: #DC2626; }
.pm-penalty-hint { font-size: 10px; color: #DC2626; font-weight: 600; }
.dark-theme .pm-penalty-hint { color: #F87171; }
.receipt-penalty-badge { display:flex; align-items:center; gap:4px; padding:4px 8px; border-radius:6px; background:rgba(239,68,68,0.1); color:#DC2626; font-size:10px; font-weight:700; margin-bottom:8px; }
.rcpt-penalty-section { margin-top: 8px; }
.pm-summary-row.pm-total { padding-top: 8px; border-top: 1px dashed var(--border-input); }
.pm-summary-row.pm-total .pms-label { font-size: 14px; font-weight: 700; color: var(--text-title); }
.pm-summary-row.pm-total .pms-value.total { font-size: 18px; font-weight: 800; color: #6366F1; }
.pm-wallet-info { display: flex; align-items: center; gap: 12px; padding: 12px 14px; border-radius: 12px; background: #F0FDF4; border: 1px solid #BBF7D0; }
.dark-theme .pm-wallet-info { background: rgba(5,150,101,0.08); border-color: rgba(5,150,101,0.2); }
.pm-wi-icon { width: 36px; height: 36px; border-radius: 10px; background: #D1FAE5; color: #059669; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.dark-theme .pm-wi-icon { background: rgba(5,150,101,0.2); color: #34D399; }
.pm-wi-details { flex: 1; display: flex; flex-direction: column; }
.pm-wi-label { font-size: 10px; font-weight: 600; color: #059669; text-transform: uppercase; letter-spacing: 0.3px; }
.pm-wi-value { font-size: 13px; font-weight: 700; color: var(--text-title); }
.pm-wi-balance { font-size: 11px; color: var(--text-muted); margin-top: 1px; }
.pm-wi-check { flex-shrink: 0; }
.payment-modal-footer { display: flex; gap: 10px; padding: 16px 24px 22px; border-top: 1px solid var(--border-input); }
.payment-modal-footer .btn-secondary { flex: 1; }
.payment-modal-footer .pm-pay-btn { flex: 1; display: flex; align-items: center; justify-content: center; gap: 6px; }

/* ═══════════════════════════════════════════
   SUCCESS ANIMATION OVERLAY
═══════════════════════════════════════════ */
/* ═══════════════════════════════════════════
   SUCCESS STATE — floating icon + pulse rings
   ═══════════════════════════════════════════ */
.payment-success-card {
  text-align: center;
  padding: 32px 20px 40px;
  position: relative;
  width: 100%;
  max-width: 400px;
}

/* Ambient pulse rings */
.ps-pulse-ring {
  position: absolute;
  top: 50%; left: 50%;
  width: 160px; height: 160px;
  margin: -80px 0 0 -80px;
  border-radius: 50%;
  border: 2px solid;
  opacity: 0;
  animation: psPulse 2.4s ease-out infinite;
  z-index: 0;
  pointer-events: none;
  will-change: transform, opacity;
}
.ps-pulse-ring.ps-pulse-2 { animation-delay: 0.8s; }
.ps-pulse-ring.ps-pulse-3 { animation-delay: 1.6s; }
@keyframes psPulse {
  0%   { transform: scale(0.4); opacity: 0.5; }
  50%  { transform: scale(1.3); opacity: 0.12; }
  100% { transform: scale(1.8); opacity: 0; }
}

/* Icon circle — no square, no bg fill, just floating */
.ps-icon {
  position: relative;
  z-index: 1;
  width: 80px; height: 80px;
  margin: 0 auto 22px;
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  animation: psIconSpring 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) both;
  box-shadow: 0 12px 40px rgba(0,0,0,0.1);
}
@keyframes psIconSpring {
  0%   { transform: scale(0) rotate(-12deg); opacity: 0; }
  60%  { transform: scale(1.12) rotate(1.5deg); }
  100% { transform: scale(1) rotate(0deg); opacity: 1; }
}

/* Particles layer — positioned to fill the card area */
.ps-particles {
  position: absolute;
  top: 0; left: 50%;
  transform: translateX(-50%);
  width: 160px; height: 160px;
  pointer-events: none;
  z-index: 0;
}

/* Title & subtitle with staggered entrance */
.ps-title {
  position: relative; z-index: 1;
  font-size: 22px; font-weight: 800;
  margin-bottom: 6px;
  animation: psTextIn 0.5s 0.25s both;
}
.ps-sub {
  position: relative; z-index: 1;
  font-size: 14px; color: var(--text-muted);
  animation: psTextIn 0.5s 0.4s both;
}
@keyframes psTextIn {
  from { opacity: 0; transform: translateY(10px); }
  to   { opacity: 1; transform: translateY(0); }
}

/* Water drop bounce */
.ps-water-icon { animation: psWaterBob 1.6s ease-in-out infinite; }
@keyframes psWaterBob {
  0%, 100% { transform: translateY(0); }
  50%      { transform: translateY(-5px); }
}

/* Electric flash */
.ps-elec-icon { animation: psElecGlow 1.8s ease-in-out infinite; }
@keyframes psElecGlow {
  0%, 100% { opacity: 1; filter: brightness(1) drop-shadow(0 0 4px rgba(217,119,6,0.3)); }
  50%      { opacity: 0.7; filter: brightness(1.6) drop-shadow(0 0 12px rgba(217,119,6,0.6)); }
}

/* Particle animations */
.ps-water-sparkles, .ps-elec-sparkles, .ps-rent-cashflow, .ps-contract-confetti {
  position: absolute; top: 50%; left: 50%;
  transform: translate(-50%, -50%);
  pointer-events: none; z-index: 0;
}
.ws-s1 { animation: sparkleFloat 2s ease-in-out infinite; }
.ws-s2 { animation: sparkleFloat 2.3s ease-in-out 0.3s infinite; }
.ws-s3 { animation: sparkleFloat 2.1s ease-in-out 0.6s infinite; }
.ws-s4 { animation: sparkleFloat 2.4s ease-in-out 0.9s infinite; }
.ws-s5 { animation: sparkleFloat 1.8s ease-in-out 0.4s infinite; }
.es-s1 { animation: sparkleFloat 1.8s ease-in-out infinite; }
.es-s2 { animation: sparkleFloat 2.2s ease-in-out 0.4s infinite; }
.es-s3 { animation: sparkleFloat 2s ease-in-out 0.7s infinite; }
.es-s4 { animation: sparkleFloat 1.6s ease-in-out 0.2s infinite; }
.rf-c1 { animation: sparkleFloat 2.2s ease-in-out infinite; }
.rf-c2 { animation: sparkleFloat 1.9s ease-in-out 0.3s infinite; }
.rf-c3 { animation: sparkleFloat 2.4s ease-in-out 0.6s infinite; }
.rf-c4 { animation: sparkleFloat 1.7s ease-in-out 0.9s infinite; }
.rf-c5 { animation: sparkleFloat 2s ease-in-out 0.5s infinite; }
.rf-arrow1 { animation: arrowFloat 2.5s ease-in-out infinite; }
.rf-arrow2 { animation: arrowFloat 2.5s ease-in-out 0.8s infinite; }
.pc-c1 { animation: sparkleFloat 2s ease-in-out infinite; }
.pc-c2 { animation: sparkleFloat 2.3s ease-in-out 0.4s infinite; }
.pc-c3 { animation: sparkleFloat 1.8s ease-in-out 0.7s infinite; }
.pc-c4 { animation: sparkleFloat 2.1s ease-in-out 0.2s infinite; }
.pc-c5 { animation: sparkleFloat 1.6s ease-in-out 0.9s infinite; }
.pc-c6 { animation: sparkleFloat 2.4s ease-in-out 0.5s infinite; }
@keyframes sparkleFloat {
  0%, 100% { transform: translate(0, 0) scale(1); opacity: 0.4; }
  50%      { transform: translate(0, -10px) scale(1.15); opacity: 0.9; }
}
@keyframes arrowFloat {
  0%, 100% { transform: translate(0, 0); opacity: 0.3; }
  50%      { transform: translate(0, -7px); opacity: 0.8; }
}

/* ─── Rent success refinements ─── */
.ps-rent .ps-icon {
  box-shadow: 0 12px 40px rgba(99,102,241,0.15), 0 0 0 0 rgba(99,102,241,0.2);
  animation: psIconSpring 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) both,
             psRentGlow 2s ease-in-out 0.6s infinite;
}
@keyframes psRentGlow {
  0%, 100% { box-shadow: 0 12px 40px rgba(99,102,241,0.15), 0 0 0 0 rgba(99,102,241,0.15); }
  50%      { box-shadow: 0 16px 48px rgba(99,102,241,0.25), 0 0 0 8px rgba(99,102,241,0.06); }
}
.ps-rent .rf-arrow1 {
  animation: rentArrowUp 2.2s ease-in-out infinite;
}
.ps-rent .rf-arrow2 {
  animation: rentArrowUp 2.2s ease-in-out 0.7s infinite;
}
@keyframes rentArrowUp {
  0%, 100% { transform: translate(0, 0); opacity: 0.35; }
  40%      { transform: translate(0, -10px); opacity: 0.9; }
  60%      { transform: translate(0, -10px); opacity: 0.9; }
}
.ps-rent .ps-spark {
  position: absolute;
  top: 50%; left: 50%;
  width: 6px; height: 6px;
  margin: -3px 0 0 -3px;
  border-radius: 50%;
  background: #6366F1;
  z-index: 2;
  pointer-events: none;
  animation: psSparkBurst 1s ease-out 0.35s both;
}
@keyframes psSparkBurst {
  0%   { transform: scale(0); opacity: 0; }
  20%  { transform: scale(1.5); opacity: 1; box-shadow: 0 0 12px #6366F1; }
  100% { transform: scale(2.5); opacity: 0; }
}
.ps-rent .ps-title {
  animation: psRentTitleIn 0.55s 0.3s both;
}
@keyframes psRentTitleIn {
  from { opacity: 0; transform: translateY(12px) scale(0.96); }
  to   { opacity: 1; transform: translateY(0) scale(1); }
}

/* Success overlay card — centered spring entrance (from old fade wrapping) */
.fade-enter-active .payment-success-card {
  transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
}
.fade-enter-from .payment-success-card {
  transform: scale(0.82); opacity: 0;
}

/* ═══════════════════════════════════════════
   PREMIUM WALLET PIN GATE — Rent payment
═══════════════════════════════════════════ */
.rent-pin-modal {
  position: relative; width: 100%; max-width: 480px;
  padding: 40px 32px 28px; border-radius: 24px;
  background: var(--bg-card);
  box-shadow: 0 32px 80px rgba(0,0,0,0.25);
  text-align: center;
  overflow-x: hidden; overflow-y: auto;
  box-sizing: border-box;
}
.rent-pin-bg-ornament {
  position: absolute; top: -60px; right: -60px;
  width: 180px; height: 180px; border-radius: 50%;
  background: radial-gradient(circle, rgba(99,102,241,0.08), transparent 70%);
  pointer-events: none;
}
.rent-pin-close {
  position: absolute; top: 14px; right: 14px;
  width: 28px; height: 28px; border-radius: 8px;
  border: none; background: var(--gray-100); cursor: pointer;
  font-size: 18px; display: flex; align-items: center;
  justify-content: center; color: var(--text-muted); z-index: 1;
}
.rent-pin-close:hover { background: var(--gray-200); }
.rent-pin-icon-wrap { margin-bottom: 16px; }
.rent-pin-icon-ring {
  width: 64px; height: 64px; border-radius: 18px;
  background: linear-gradient(135deg, #EEF2FF, #E0E7FF); color: #6366F1;
  display: flex; align-items: center; justify-content: center;
  margin: 0 auto; position: relative;
}
.rent-pin-icon-ring::after {
  content: ''; position: absolute; inset: -4px; border-radius: 22px;
  border: 1.5px solid rgba(99,102,241,0.15);
}
.rent-pin-title { font-size: 18px; font-weight: 800; color: var(--text-title); margin-bottom: 4px; }
.rent-pin-sub { font-size: 12.5px; color: var(--text-muted); line-height: 1.5; margin-bottom: 20px; word-break: break-word; }
.rent-pin-sub strong { color: var(--text-title); font-weight: 700; }
.rent-pin-dots { display: flex; align-items: center; justify-content: center; gap: 12px; margin-bottom: 16px; }
.rent-pin-dot {
  width: 14px; height: 14px; border-radius: 50%;
  border: 2px solid var(--border-input); background: var(--bg-input);
  display: flex; align-items: center; justify-content: center;
  transition: all 0.3s ease;
}
.rent-pin-dot.filled { border-color: #6366F1; background: #EEF2FF; animation: pinDotPop 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); }
@keyframes pinDotPop { 0% { transform: scale(0.8); } 100% { transform: scale(1); } }
.rent-pin-dot-fill { width: 6px; height: 6px; border-radius: 50%; background: #6366F1; }
.rent-pin-dots.ripple .rent-pin-dot { animation: pinDotsRipple 0.4s ease; }
@keyframes pinDotsRipple {
  0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(99,102,241,0.35); }
  50% { transform: scale(1.2); box-shadow: 0 0 0 8px rgba(99,102,241,0); }
  100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(99,102,241,0); }
}
.rent-pin-input-row {
  position: absolute; left: 0; top: 0;
  width: 1px; height: 1px; opacity: 0; overflow: hidden;
  pointer-events: none;
}
.rent-pin-error {
  display: flex; align-items: center; justify-content: center; gap: 6px;
  font-size: 12px; font-weight: 600; color: #EF4444; margin-bottom: 12px;
  padding: 6px 12px; border-radius: 8px; background: #FEF2F2;
}
.dark-theme .rent-pin-error { background: rgba(239,68,68,0.1); }
.rent-pin-actions { display: flex; gap: 10px; justify-content: center; }
.rent-pin-btn {
  flex: 1; padding: 10px 16px; border-radius: 12px; border: none;
  font-size: 13px; font-weight: 700; cursor: pointer; font-family: var(--font-main);
  display: flex; align-items: center; justify-content: center; gap: 6px;
  transition: all 0.2s ease;
}
.rent-pin-btn-secondary { background: var(--gray-100); color: var(--text-body); }
.rent-pin-btn-secondary:hover { background: var(--gray-200); }
.rent-pin-btn-primary {
  background: linear-gradient(135deg, #6366F1, #4F46E5); color: white;
  box-shadow: 0 4px 16px rgba(99,102,241,0.25);
}
.rent-pin-btn-primary:hover:not(:disabled) { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(99,102,241,0.35); }
.rent-pin-btn-primary:disabled { opacity: 0.5; cursor: not-allowed; }
.dark-theme .rent-pin-btn-secondary { background: #1E293B; color: #94A3B8; }
.dark-theme .rent-pin-btn-secondary:hover { background: #334155; }

/* Premium password gate modal — same treatment */
.password-gate-modal { overflow-x: hidden; box-sizing: border-box; }

/* ═══════════════════════════════════════════
   RENT RECEIPT CARD (in loyer tab)
═══════════════════════════════════════════ */
.rent-receipt-card { padding: 22px; margin-bottom: 20px; border: 1px solid #A7F3D0; }
.dark-theme .rent-receipt-card { border-color: rgba(5,150,101,0.2); }
.rent-receipt-header { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 16px; }
.rr-header-left { display: flex; align-items: center; gap: 12px; }
.rr-icon-ring { width: 40px; height: 40px; border-radius: 12px; background: linear-gradient(135deg, #D1FAE5, #A7F3D0); color: #059669; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.dark-theme .rr-icon-ring { background: linear-gradient(135deg, rgba(5,150,101,0.25), rgba(5,150,101,0.1)); color: #34D399; }
.rr-title { font-size: 14px; font-weight: 700; color: var(--text-title); }
.rr-date { font-size: 11px; color: var(--text-muted); margin-top: 1px; }
.rr-badge { font-size: 10px; font-weight: 700; padding: 4px 10px; border-radius: 8px; background: #D1FAE5; color: #059669; }
.dark-theme .rr-badge { background: rgba(5,150,101,0.2); color: #34D399; }
.rent-receipt-body { display: flex; flex-direction: column; gap: 14px; }
.rr-months-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 6px; }
.rr-month-item { display: flex; align-items: center; justify-content: space-between; padding: 6px 10px; border-radius: 6px; background: var(--bg-input); border: 1px solid var(--border-input); font-size: 12px; }
.rrm-label { font-weight: 600; color: var(--text-title); }
.rrm-amount { font-weight: 700; color: var(--text-body); }
.rr-total-row { display: flex; align-items: center; justify-content: space-between; padding: 10px 14px; border-radius: 10px; background: #EEF2FF; border: 1px solid rgba(99,102,241,0.15); font-size: 14px; color: var(--text-title); }
.dark-theme .rr-total-row { background: rgba(99,102,241,0.06); border-color: rgba(99,102,241,0.15); }
.rr-total-row strong { font-size: 18px; font-weight: 800; color: #6366F1; }
.rr-meta-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; }
.rr-meta-item { display: flex; flex-direction: column; padding: 8px 10px; border-radius: 8px; background: var(--bg-input); border: 1px solid var(--border-input); }
.rrm-key { font-size: 9px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.3px; }
.rrm-val { font-size: 11px; font-weight: 700; color: var(--text-title); margin-top: 2px; }
.rrm-val.mono { font-family: 'SF Mono', 'Fira Code', monospace; font-size: 10px; }
.rent-receipt-footer { display: flex; gap: 8px; margin-top: 16px; flex-wrap: wrap; }

/* Invoices list and filters */
.filter-bar { padding: 12px 16px; display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-bottom: 16px; flex-wrap: wrap; }
.filter-tabs { display: flex; gap: 2px; }
.filter-tab { padding: 5px 10px; border-radius: 6px; border: none; background: transparent; color: var(--text-muted); font-size: 11.5px; font-weight: 600; cursor: pointer; }
.filter-tab.active { background: var(--bg-input); color: var(--blue-600); box-shadow: 0 1px 4px rgba(0,0,0,0.03); }
.search-input { padding: 5px 10px; border-radius: 6px; border: 1px solid var(--border-input); font-size: 11.5px; outline: none; background: var(--bg-input); color: var(--text-body); }

.table-card { padding: 0; overflow: hidden; position: relative; }
.table-wrapper {
  width: 100%; overflow-x: auto;
  -webkit-overflow-scrolling: touch;
  scroll-behavior: smooth;
  position: relative;
}
.table-wrapper::-webkit-scrollbar { height: 6px; }
.table-wrapper::-webkit-scrollbar-track { background: var(--gray-100, #F1F5F9); border-radius: 3px; }
.table-wrapper::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 3px; }
.table-wrapper::-webkit-scrollbar-thumb:hover { background: #94A3B8; }
.dark-theme .table-wrapper::-webkit-scrollbar-track { background: #1E293B; }
.dark-theme .table-wrapper::-webkit-scrollbar-thumb { background: #475569; }
.dark-theme .table-wrapper::-webkit-scrollbar-thumb:hover { background: #64748B; }
.premium-table { width: 100%; min-width: 700px; border-collapse: collapse; text-align: left; table-layout: auto; }
.premium-table th { padding: 12px 16px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.4px; border-bottom: 1px solid var(--border-input); white-space: nowrap; }
.premium-table td { padding: 12px 16px; font-size: 13px; border-bottom: 1px solid var(--border-input); white-space: nowrap; }
.premium-table tbody tr:hover { background: rgba(99,102,241,0.03); }
.premium-table tbody tr:last-child td { border-bottom: none; }
.table-card .table-empty + .table-wrapper { display: none; }
.type-badge { font-size: 10px; font-weight: 700; padding: 3px 7px; border-radius: 6px; }
.type-rent { background: var(--blue-50); color: var(--blue-600); }
.type-water { background: #E0F2FE; color: #0369A1; }
.type-electric { background: #FEF3C7; color: #B45309; }
.status-pill { font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 8px; }
.status-paid { background: var(--emerald-50); color: var(--emerald-600); }
.status-pending { background: var(--amber-50); color: var(--amber-500); }
.action-buttons { display: flex; gap: 5px; }
.action-btn { border: none; cursor: pointer; padding: 6px 10px; border-radius: 6px; font-size: 11.5px; font-weight: 700; font-family: var(--font-main); }
.action-btn.pay-btn { background: var(--blue-600); color: white; }
.action-btn.receipt-btn { background: var(--gray-100); color: var(--text-body); }
.action-btn.upload-proof-btn { background: var(--bg-input); color: var(--text-muted); border: 1px solid var(--border-input); }

/* ══════════════════════════════════════════════════════════════════
   D. SUPPORT & MESSAGERIE PREMIUM
   ══════════════════════════════════════════════════════════════════ */
/* ══════════════════════════════════════════════════════════════════
   PREMIUM SUPPORT & MESSAGERIE
   ══════════════════════════════════════════════════════════════════ */
.support-layout { display: grid; grid-template-columns: 1fr 1.4fr; gap: 18px; }

.tickets-panel { display: flex; flex-direction: column; gap: 8px; max-height: 75vh; overflow-y: auto; padding-right: 4px; }
.tp-header { padding: 4px 2px 8px; }
.tp-count { font-size: 11px; font-weight: 700; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.5px; }

.ticket-card { padding: 14px 16px; border-radius: 14px; background: white; border: 1px solid #F1F5F9; cursor: pointer; transition: all 0.25s; box-shadow: 0 1px 3px rgba(0,0,0,0.02); }
.dark-theme .ticket-card { background: #1E293B; border-color: #334155; }
.ticket-card:hover { border-color: #CBD5E1; box-shadow: 0 4px 16px rgba(0,0,0,0.03); transform: translateY(-1px); }
.dark-theme .ticket-card:hover { border-color: #475569; }
.ticket-card.ticket-selected { border-color: #6366F1; background: linear-gradient(135deg, rgba(99,102,241,0.04), rgba(139,92,246,0.02)); box-shadow: 0 4px 16px rgba(99,102,241,0.06); }
.dark-theme .ticket-card.ticket-selected { background: linear-gradient(135deg, rgba(99,102,241,0.08), rgba(99,102,241,0.03)); }
.ticket-card.ticket-open { border-left: 3px solid #6366F1; }
.ticket-card.ticket-progress { border-left: 3px solid #F59E0B; }
.ticket-card.ticket-closed { opacity: 0.6; border-left: 3px solid #94A3B8; }

.ticket-card-top { display: flex; gap: 10px; align-items: flex-start; }
.ticket-cat-badge { width: 30px; height: 30px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.cat-plumbing { background: rgba(59,130,246,0.08); color: #3B82F6; }
.cat-electrical { background: rgba(245,158,11,0.08); color: #F59E0B; }
.cat-carpentry { background: rgba(16,185,129,0.08); color: #10B981; }
.cat-other { background: rgba(99,102,241,0.08); color: #6366F1; }
.ticket-info { flex: 1; min-width: 0; }
.ticket-title { font-size: 13px; font-weight: 700; color: #1E293B; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.dark-theme .ticket-title { color: #F1F5F9; }
.ticket-preview { font-size: 11px; color: #94A3B8; margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.ticket-status-pulse { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; margin-top: 3px; }
.pulse-open { background: #6366F1; animation: pulseGlow 1.5s infinite; }
.pulse-in_progress { background: #F59E0B; animation: pulseGlow 1.5s infinite; }
.pulse-closed { background: #94A3B8; }
.ticket-card-bottom { display: flex; align-items: center; gap: 8px; margin-top: 8px; }
.ticket-status-badge { font-size: 9px; font-weight: 800; text-transform: uppercase; padding: 2px 6px; border-radius: 5px; letter-spacing: 0.3px; }
.ts-open { background: rgba(99,102,241,0.08); color: #6366F1; }
.ts-in_progress { background: rgba(245,158,11,0.08); color: #F59E0B; }
.ts-closed { background: rgba(148,163,184,0.1); color: #94A3B8; }
.ticket-date { font-size: 10px; color: #94A3B8; }
.ticket-msgs-badge { font-size: 9.5px; font-weight: 600; color: #64748B; background: #F1F5F9; padding: 1px 6px; border-radius: 4px; margin-left: auto; }

/* Chat Panel Premium */
.chat-panel { display: flex; flex-direction: column; max-height: 75vh; overflow: hidden; position: relative; border-radius: 16px; background: white; border: 1px solid #F1F5F9; }
.dark-theme .chat-panel { background: #1E293B; border-color: #334155; }
.chat-panel::after { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1px; background: linear-gradient(90deg, transparent, rgba(99,102,241,0.1), transparent); pointer-events: none; }

.chat-header { padding: 16px 18px; border-bottom: 1px solid #F1F5F9; display: flex; align-items: center; justify-content: space-between; }
.dark-theme .chat-header { border-color: #334155; }
.chat-header-left { display: flex; align-items: center; gap: 12px; }
.chat-avatar { width: 36px; height: 36px; border-radius: 10px; background: linear-gradient(135deg, #6366F1, #8B5CF6); display: flex; align-items: center; justify-content: center; color: white; font-size: 14px; font-weight: 800; }
.chat-title { font-size: 14px; font-weight: 750; color: #1E293B; }
.dark-theme .chat-title { color: #F1F5F9; }
.chat-manager-status { display: flex; align-items: center; gap: 6px; margin-top: 2px; }
.status-pulse-dot { width: 7px; height: 7px; border-radius: 50%; background: #10B981; display: inline-block; animation: pulseGlow 1.5s infinite; }
.status-pulse-lbl { font-size: 10.5px; color: #059669; font-weight: 600; }
@keyframes pulseGlow { 0% { box-shadow: 0 0 0 0 rgba(16,185,129,0.5); } 100% { box-shadow: 0 0 0 6px rgba(16,185,129,0); } }
.chat-close-btn { display: flex; align-items: center; gap: 5px; padding: 6px 12px; border: none; border-radius: 8px; background: #FEF2F2; color: #EF4444; font-size: 11px; font-weight: 700; cursor: pointer; transition: 0.2s; font-family: var(--font-main); }
.chat-close-btn:hover { background: #FEE2E2; }

.chat-messages { flex: 1; overflow-y: auto; padding: 16px 18px; display: flex; flex-direction: column; gap: 10px; }
.chat-messages::-webkit-scrollbar { width: 3px; }
.chat-messages::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 2px; }

.chat-message { display: flex; gap: 6px; align-items: flex-end; }
.msg-tenant { flex-direction: row-reverse; }
.msg-bubble { max-width: 78%; display: flex; flex-direction: column; gap: 3px; }
.bubble-tenant .msg-text { padding: 9px 14px; border-radius: 14px 14px 3px 14px; font-size: 12.5px; line-height: 1.5; background: linear-gradient(135deg, #6366F1, #8B5CF6); color: white; }
.bubble-manager .msg-text { padding: 9px 14px; border-radius: 14px 14px 14px 3px; font-size: 12.5px; line-height: 1.5; background: #F1F5F9; color: #1E293B; }
.dark-theme .bubble-manager .msg-text { background: #334155; color: #F1F5F9; }
.msg-time { font-size: 9px; color: #94A3B8; display: flex; gap: 4px; margin-top: 2px; }
.msg-tenant .msg-time { justify-content: flex-end; }
.read-ticks { color: #A5B4FC; font-weight: bold; }

.typing-bubble { padding: 12px 16px !important; background: #F1F5F9; border-radius: 14px 14px 14px 3px; width: fit-content; }
.dark-theme .typing-bubble { background: #334155; }
.typing-indicator { display: flex; gap: 4px; align-items: center; height: 10px; }
.typing-indicator span { width: 6px; height: 6px; background: #94A3B8; border-radius: 50%; display: inline-block; animation: typingBounce 1.4s infinite ease-in-out both; }
.typing-indicator span:nth-child(1) { animation-delay: -0.32s; }
.typing-indicator span:nth-child(2) { animation-delay: -0.16s; }
@keyframes typingBounce { 0%, 80%, 100% { transform: scale(0); } 40% { transform: scale(1); } }

.msg-attachment-card { padding: 8px 10px; border-radius: 10px; background: #F8FAFC; border: 1px solid #E2E8F0; width: 220px; }
.dark-theme .msg-attachment-card { background: #1E293B; border-color: #334155; }
.attachment-preview-box { display: flex; align-items: center; gap: 8px; }
.attach-icon { width: 30px; height: 30px; border-radius: 8px; background: rgba(99,102,241,0.06); display: flex; align-items: center; justify-content: center; color: #6366F1; flex-shrink: 0; }
.attachment-info { flex: 1; overflow: hidden; }
.attach-fn { font-size: 11.5px; font-weight: 700; color: #1E293B; text-overflow: ellipsis; white-space: nowrap; overflow: hidden; }
.dark-theme .attach-fn { color: #F1F5F9; }
.attach-fs { font-size: 9px; color: #94A3B8; }

.msg-audio-play-box { display: flex; align-items: center; gap: 8px; padding: 8px 12px; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; min-width: 200px; }
.dark-theme .msg-audio-play-box { background: #1E293B; border-color: #334155; }
.audio-play-btn { width: 26px; height: 26px; border-radius: 50%; border: none; background: #6366F1; color: white; cursor: pointer; display: flex; align-items: center; justify-content: center; }
.audio-wave-bars { display: flex; align-items: center; gap: 2.5px; flex: 1; height: 18px; }
.wave-bar { width: 2.5px; height: 6px; background: #CBD5E1; border-radius: 1px; }
.wave-bar:nth-child(2n) { height: 12px; }
.wave-bar:nth-child(3n) { height: 15px; }
.wave-bar.active { animation: wavePulse 0.8s ease-in-out infinite alternate; background: #6366F1; }
.wave-bar.active:nth-child(2n) { animation-delay: 0.15s; }
.wave-bar.active:nth-child(3n) { animation-delay: 0.3s; }
@keyframes wavePulse { to { transform: scaleY(1.6); } }
.audio-duration { font-size: 9.5px; color: #94A3B8; }

.chat-attached-preview-row { padding: 6px 14px; background: #F8FAFC; border-top: 1px solid #F1F5F9; }
.dark-theme .chat-attached-preview-row { background: #1E293B; border-color: #334155; }
.attached-file-badge { display: inline-flex; align-items: center; gap: 6px; padding: 5px 10px; background: white; border: 1px solid #E2E8F0; border-radius: 8px; font-size: 11px; font-weight: 600; color: #1E293B; }
.dark-theme .attached-file-badge { background: #1E293B; border-color: #334155; color: #F1F5F9; }
.remove-attach-btn { border: none; background: transparent; font-size: 14px; cursor: pointer; color: #EF4444; }

.chat-input-row { padding: 12px 16px; border-top: 1px solid #F1F5F9; display: flex; gap: 8px; align-items: center; position: relative; }
.dark-theme .chat-input-row { border-color: #334155; }
.chat-extra-btn { background: transparent; border: none; color: #94A3B8; cursor: pointer; display: flex; align-items: center; padding: 6px; border-radius: 8px; transition: 0.2s; }
.chat-extra-btn:hover { color: #6366F1; background: rgba(99,102,241,0.04); }
.chat-extra-btn.recording-active { color: #EF4444; animation: pulseGlow 1s infinite alternate; }

.chat-input { flex: 1; padding: 9px 14px; border-radius: 10px; border: 1.5px solid #E2E8F0; font-size: 12.5px; font-family: var(--font-main); outline: none; background: #F8FAFC; color: #1E293B; transition: 0.2s; }
.dark-theme .chat-input { background: #1E293B; border-color: #334155; color: #F1F5F9; }
.chat-input:focus { border-color: #6366F1; box-shadow: 0 0 0 3px rgba(99,102,241,0.06); }
.chat-input::placeholder { color: #94A3B8; }

.send-btn { width: 36px; height: 36px; border-radius: 10px; background: linear-gradient(135deg, #6366F1, #8B5CF6); color: white; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: 0.2s; }
.send-btn:hover:not(:disabled) { transform: scale(1.05); box-shadow: 0 4px 12px rgba(99,102,241,0.25); }
.send-btn:disabled { opacity: 0.45; cursor: not-allowed; }

.voice-recording-overlay { position: absolute; inset: 0; background: #F8FAFC; z-index: 10; display: flex; align-items: center; gap: 10px; padding: 0 16px; }
.dark-theme .voice-recording-overlay { background: #1E293B; }
.red-recording-dot { width: 8px; height: 8px; border-radius: 50%; background: #EF4444; animation: blink 0.8s infinite; }
@keyframes blink { 0%, 100% { opacity: 0.2; } 50% { opacity: 1; } }
.rec-timer { font-size: 12px; font-weight: 600; color: #1E293B; }
.dark-theme .rec-timer { color: #F1F5F9; }
.live-wave-animation { display: flex; gap: 2px; }
.live-wave-animation span { width: 2px; height: 10px; background: #EF4444; animation: pulse 0.5s infinite alternate; }
.live-wave-animation span:nth-child(2) { animation-delay: 0.1s; }
.live-wave-animation span:nth-child(3) { animation-delay: 0.2s; }
@keyframes pulse { to { height: 18px; } }
.rec-cancel-btn { background: transparent; border: none; color: #94A3B8; font-size: 11.5px; font-weight: 700; cursor: pointer; margin-left: auto; }
.rec-send-btn { background: #EF4444; border: none; color: white; padding: 4px 10px; border-radius: 6px; font-size: 11.5px; font-weight: 700; cursor: pointer; }

.chat-empty { display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; min-height: 350px; }
.chat-empty-icon { width: 64px; height: 64px; border-radius: 50%; background: #F8FAFC; display: flex; align-items: center; justify-content: center; margin-bottom: 12px; }
.chat-empty-title { font-size: 14px; font-weight: 700; color: #1E293B; margin-bottom: 4px; }
.dark-theme .chat-empty-title { color: #F1F5F9; }
.chat-empty-sub { font-size: 12px; color: #94A3B8; }

/* ══════════════════════════════════════════════════════════════════
   E. PROFIL & PARAMÈTRES — Apple iOS 18 Inspired Ultra-Premium
   ══════════════════════════════════════════════════════════════════ */

/* ── Layout: responsive 2-col → 1-col elegantly ── */
.profile-layout {
  display: grid; grid-template-columns: 1fr 1fr; gap: 28px;
}
@media (max-width: 1100px) {
  .profile-layout { grid-template-columns: 1fr; max-width: 600px; margin: 0 auto; gap: 22px; padding: 0; }
}
@media (max-width: 480px) {
  .profile-layout { gap: 14px; padding: 0 6px; }
}
.profile-card, .security-card { padding: 28px; }
.notif-card { padding: 28px; grid-column: 1 / -1; }
@media (max-width: 768px) {
  .profile-card, .security-card, .notif-card { padding: 22px; border-radius: 18px; }
}
@media (max-width: 480px) {
  .profile-card, .security-card, .notif-card { padding: 18px; border-radius: 16px; }
}

/* ── Card title ── */
.card-title {
  font-size: 17px; font-weight: 750; color: var(--text-title);
  letter-spacing: -0.3px; margin-bottom: 22px;
}
@media (max-width: 480px) {
  .card-title { font-size: 15px; margin-bottom: 16px; }
}

/* ── Avatar — iOS Settings style ── */
.avatar-upload-section { display: flex; align-items: center; gap: 18px; margin-bottom: 26px; }
@media (max-width: 480px) {
  .avatar-upload-section { flex-direction: column; text-align: center; gap: 14px; margin-bottom: 22px; }
}
.avatar-large-wrap { position: relative; flex-shrink: 0; }
.avatar-large {
  width: 76px; height: 76px; border-radius: 50%; object-fit: cover;
  border: 3px solid var(--bg-card);
  box-shadow: 0 6px 22px rgba(0,0,0,0.1), 0 1px 4px rgba(0,0,0,0.04);
  transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
}
@media (max-width: 480px) {
  .avatar-large { width: 88px; height: 88px; }
}
.avatar-large:hover { transform: scale(1.03); }
.avatar-edit-btn {
  position: absolute; bottom: 0; right: 0; width: 28px; height: 28px;
  border-radius: 50%; background: var(--blue-600); color: white;
  border: 2.5px solid var(--bg-card); cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
  box-shadow: 0 2px 10px rgba(37,99,235,0.35);
}
.avatar-edit-btn:hover { transform: scale(1.12); }
.avatar-edit-btn:active { transform: scale(0.95); }
.avatar-delete-btn {
  position: absolute; bottom: 0; right: 34px; width: 28px; height: 28px;
  border-radius: 50%; background: #DC2626; color: white;
  border: 2.5px solid var(--bg-card); cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
  box-shadow: 0 2px 10px rgba(220,38,38,0.3);
}
.avatar-delete-btn:hover { transform: scale(1.12); background: #B91C1C; }
.avatar-meta { min-width: 0; }
@media (max-width: 480px) {
  .avatar-delete-btn { right: auto; left: 0; bottom: 0; }
  .avatar-edit-btn { right: 0; bottom: 0; }
}
.avatar-name { font-size: 16px; font-weight: 720; color: var(--text-title); letter-spacing: -0.3px; }
.avatar-email { font-size: 13px; color: var(--text-muted); margin-top: 2px; }
.avatar-reset-link {
  background: none; border: none; color: var(--red-500); font-size: 11.5px;
  font-weight: 600; cursor: pointer; font-family: var(--font-main);
  padding: 0; margin-top: 6px; display: inline-block;
  opacity: 0.7; transition: opacity 0.2s;
}
.avatar-reset-link:hover { opacity: 1; text-decoration: underline; }

/* ── Avatar Preview Modal ── */
.avatar-preview-modal { max-width: 340px; text-align: center; }
.avatar-preview-body { padding: 18px 0 10px; }
.avatar-preview-modal .avatar-preview-body { padding: 18px 26px 10px; }
.avatar-preview-circle {
  width: 80px; height: 80px; border-radius: 50%; margin: 0 auto;
  overflow: hidden; border: 3px solid var(--border-input);
  box-shadow: 0 4px 16px rgba(0,0,0,0.06);
}
.avatar-preview-img { width: 100%; height: 100%; object-fit: cover; }
.avatar-preview-hint { font-size: 10.5px; color: var(--text-muted); margin-top: 10px; font-weight: 500; }
.avatar-preview-actions { display: flex; gap: 8px; justify-content: center; }
.avatar-preview-actions .btn-primary { display: flex; align-items: center; gap: 5px; padding: 7px 16px; }

/* ── Form Grid — Apple-style clean ── */
.form-actions {
  display: flex; justify-content: flex-end; padding-top: 22px; margin-top: 20px;
  border-top: 1px solid var(--border-input);
}
.btn-save-profile { display: inline-flex; align-items: center; gap: 8px; padding: 12px 28px; border-radius: 12px; font-size: 14px; }
@media (max-width: 480px) {
  .form-actions { padding-top: 16px; margin-top: 14px; }
  .btn-save-profile { width: 100%; justify-content: center; padding: 14px 20px; }
}
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
.form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
.form-grid .form-group { margin-bottom: 0; }
@media (max-width: 768px) {
  .form-grid, .form-grid-2 { gap: 14px; }
}
@media (max-width: 480px) {
  .form-grid, .form-grid-2 { grid-template-columns: 1fr; gap: 12px; }
}

/* ── Theme Toggle — iOS 18 Segmented Control ── */
.theme-section-divider {
  margin-top: 24px; border-top: 1px solid var(--border-input); padding-top: 20px;
}
@media (max-width: 480px) {
  .theme-section-divider { margin-top: 18px; padding-top: 16px; }
}
.theme-toggle-group {
  display: flex; gap: 0; margin-top: 12px;
  background: var(--gray-100); border-radius: 12px; padding: 3px;
  border: 1px solid var(--border-input);
}
.dark-theme .theme-toggle-group { background: rgba(30,41,59,0.6); border-color: #334155; }
@media (max-width: 480px) {
  .theme-toggle-group { margin-top: 10px; border-radius: 10px; padding: 2px; }
}
.theme-opt-btn {
  flex: 1; display: flex; align-items: center; justify-content: center; gap: 6px;
  padding: 10px 14px; border-radius: 10px; border: none;
  background: transparent; font-size: 13px; font-weight: 600;
  color: var(--text-muted); font-family: var(--font-main);
  cursor: pointer; transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
  -webkit-tap-highlight-color: transparent;
}
.theme-opt-btn svg { width: 16px; height: 16px; opacity: 0.6; }
.theme-opt-btn.active {
  background: white; color: var(--text-title);
  box-shadow: 0 2px 8px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.03);
}
.dark-theme .theme-opt-btn.active {
  background: #1E293B; color: #F1F5F9;
  box-shadow: 0 2px 8px rgba(0,0,0,0.25);
}
.theme-opt-btn.active svg { opacity: 1; }
@media (max-width: 480px) {
  .theme-opt-btn { padding: 12px 10px; font-size: 12px; border-radius: 8px; }
  .theme-opt-btn svg { width: 14px; height: 14px; }
}

/* ── Language Switcher — iOS Settings style ── */
.lang-section-divider {
  margin-top: 22px; border-top: 1px solid var(--border-input); padding-top: 20px;
}
@media (max-width: 480px) {
  .lang-section-divider { margin-top: 16px; padding-top: 14px; }
}
.lang-desc { margin-top: 2px; }
.lang-btn-row { display: flex; gap: 10px; margin-top: 10px; }
@media (max-width: 480px) {
  .lang-btn-row { gap: 8px; margin-top: 8px; flex-direction: column; }
}
.lang-btn {
  flex: 1; display: flex; align-items: center; gap: 10px;
  padding: 13px 16px; border-radius: 13px;
  border: 1.5px solid var(--border-input); background: var(--bg-input);
  cursor: pointer; transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
  font-family: var(--font-main); position: relative;
}
.lang-btn:hover { border-color: #CBD5E1; background: #F1F5F9; }
.dark-theme .lang-btn:hover { border-color: #475569; background: #334155; }
.lang-btn.active {
  border-color: var(--blue-600); background: var(--blue-50);
  box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
}
.dark-theme .lang-btn.active {
  border-color: #60A5FA; background: rgba(96,165,250,0.08);
}
@media (max-width: 480px) {
  .lang-btn { padding: 14px 16px; border-radius: 12px; }
}
.lang-flag { font-size: 24px; line-height: 1; }
.lang-name { font-size: 14px; font-weight: 650; color: var(--text-body); flex: 1; }
.lang-btn.active .lang-name { color: var(--blue-600); font-weight: 700; }
.dark-theme .lang-btn.active .lang-name { color: #60A5FA; }
.lang-check {
  width: 22px; height: 22px; border-radius: 50%;
  background: var(--blue-600); color: white;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.dark-theme .lang-check { background: #60A5FA; }

/* ── Security Section — clean dividers ── */
.security-section { margin-bottom: 26px; padding-bottom: 26px; border-bottom: 1px solid var(--border-input); }
.security-section:last-child { margin-bottom: 0; padding-bottom: 0; border-bottom: none; }
@media (max-width: 768px) {
  .security-section { margin-bottom: 20px; padding-bottom: 20px; }
}
.section-subtitle { font-size: 14px; font-weight: 700; color: var(--text-title); letter-spacing: -0.2px; }
.section-desc { font-size: 12.5px; color: var(--text-muted); line-height: 1.5; margin-top: 2px; }
.btn-password-submit { margin-top: 12px; }
@media (max-width: 480px) {
  .btn-password-submit { width: 100%; justify-content: center; }
}

/* ── 2FA — crisp status badge ── */
.tfa-header { display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; }
.tfa-header > div { min-width: 0; flex: 1; }
@media (max-width: 768px) {
  .tfa-header { flex-direction: column; gap: 8px; }
  .tfa-status-badge { align-self: flex-start; }
}
.tfa-status-badge {
  font-size: 11px; font-weight: 700; padding: 5px 14px; border-radius: 20px;
  background: var(--red-50); color: var(--red-500); white-space: nowrap;
  flex-shrink: 0; align-self: center;
}
.tfa-status-badge.enabled { background: var(--emerald-50); color: var(--emerald-600); }
.tfa-actions { margin-top: 14px; display: flex; gap: 8px; flex-wrap: wrap; }
.tfa-btn-group { display: flex; gap: 8px; }
@media (max-width: 480px) {
  .tfa-actions { flex-direction: column; }
  .tfa-btn-group { flex-direction: column; width: 100%; }
  .tfa-actions .btn-primary.small,
  .tfa-btn-group .btn-secondary.small,
  .tfa-btn-group .btn-danger.small { width: 100%; justify-content: center; padding: 12px 16px; }
}

/* ── Login Logs — glass-like rows ── */
.login-logs-list { display: flex; flex-direction: column; gap: 8px; margin-top: 14px; }
@media (max-width: 480px) {
  .login-logs-list { gap: 6px; margin-top: 10px; }
}
.login-log-row {
  display: flex; align-items: center; gap: 12px; padding: 14px 16px;
  background: var(--bg-input); border-radius: 13px;
  border: 1px solid var(--border-input); font-size: 12.5px;
  transition: all 0.25s ease;
}
@media (max-width: 480px) {
  .login-log-row { padding: 12px 12px; gap: 10px; flex-wrap: wrap; border-radius: 11px; }
}
.login-log-row:hover { border-color: var(--gray-300); }
.log-device-icon {
  flex-shrink: 0; width: 34px; height: 34px; border-radius: 9px;
  background: var(--gray-100); display: flex; align-items: center;
  justify-content: center; color: var(--text-muted);
}
.dark-theme .log-device-icon { background: #1E293B; }
.log-details { flex: 1; min-width: 0; }
.log-device-desc { font-size: 13px; font-weight: 650; color: var(--text-title); }
.log-meta-txt {
  font-size: 10.5px; color: var(--text-muted); margin-top: 2px;
  white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
@media (max-width: 480px) {
  .log-meta-txt { white-space: normal; }
}
.log-status-tag {
  font-size: 9.5px; font-weight: 750; text-transform: uppercase;
  padding: 3px 10px; border-radius: 20px; flex-shrink: 0;
}
.log-status-tag.success { background: var(--emerald-50); color: var(--emerald-600); }
.log-status-tag.failed { background: var(--red-50); color: var(--red-500); }

/* ── Notification Matrix — refined ── */
.notif-matrix-wrapper {
  margin-top: 16px; overflow-x: auto;
  -webkit-overflow-scrolling: touch;
  border-radius: 13px; border: 1px solid var(--border-input);
}
@media (max-width: 480px) {
  .notif-matrix-wrapper { border-radius: 11px; margin-top: 12px; }
}
.notif-matrix-wrapper::-webkit-scrollbar { height: 5px; }
.notif-matrix-wrapper::-webkit-scrollbar-track { background: var(--gray-100, #F1F5F9); border-radius: 3px; }
.notif-matrix-wrapper::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 3px; }
.dark-theme .notif-matrix-wrapper::-webkit-scrollbar-track { background: #1E293B; }
.dark-theme .notif-matrix-wrapper::-webkit-scrollbar-thumb { background: #475569; }
.notif-matrix-table { width: 100%; min-width: 460px; border-collapse: collapse; text-align: left; }
@media (max-width: 480px) {
  .notif-matrix-table { min-width: 300px; }
}
.notif-matrix-table th {
  padding: 13px 14px; font-size: 10px; color: var(--text-muted);
  text-transform: uppercase; letter-spacing: 0.6px;
  border-bottom: 1.5px solid var(--border-input); white-space: nowrap;
  background: var(--bg-input);
}
.notif-matrix-table td { padding: 14px 14px; border-bottom: 1px solid var(--border-input); font-size: 13px; }
@media (max-width: 480px) {
  .notif-matrix-table th { padding: 10px 10px; font-size: 9px; }
  .notif-matrix-table td { padding: 10px 10px; font-size: 11.5px; }
}
.notif-matrix-table tr:last-child td { border-bottom: none; }
.notif-matrix-table tbody tr:hover { background: rgba(99,102,241,0.03); }
.pref-label { font-weight: 650; color: var(--text-title); font-size: 13px; }
@media (max-width: 480px) {
  .pref-label { font-size: 12px; }
}
.pref-desc { font-size: 10.5px; color: var(--text-muted); margin-top: 1px; }

/* ── Custom Switch Sliders ── */
.custom-switch-label { position: relative; display: inline-block; width: 34px; height: 18px; }
.custom-switch-label input { opacity: 0; width: 0; height: 0; }
.custom-switch-slider { position: absolute; cursor: pointer; inset: 0; background-color: var(--gray-300); transition: .2s; border-radius: 18px; }
.custom-switch-slider:before { position: absolute; content: ""; height: 12px; width: 12px; left: 3px; bottom: 3px; background-color: white; transition: .2s; border-radius: 50%; }
input:checked + .custom-switch-slider { background-color: var(--blue-600); }
input:checked + .custom-switch-slider:before { transform: translateX(16px); }

/* ══════════════════════════════════════════════════════════════════
   BUTTONS & FORMS
   ══════════════════════════════════════════════════════════════════ */
.btn-primary {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 8px 16px; border-radius: 8px;
  background: var(--blue-600); color: white;
  font-size: 12.5px; font-weight: 700; font-family: var(--font-main);
  border: none; cursor: pointer; transition: all 0.2s;
}
.btn-primary:hover { background: var(--blue-700); transform: translateY(-1px); box-shadow: 0 4px 16px rgba(37, 99, 235, 0.25); }
.btn-primary:active { transform: translateY(0) scale(0.98); }
.btn-primary:focus-visible { outline: 2px solid var(--blue-500); outline-offset: 2px; }
.btn-primary.small { padding: 5px 10px; font-size: 11px; }
.btn-primary.full-width { width: 100%; justify-content: center; padding: 10px; font-size: 13.5px; }
.btn-primary.loading { opacity: 0.8; pointer-events: none; }
.btn-secondary { display: inline-flex; align-items: center; padding: 6px 12px; border-radius: 8px; background: var(--bg-input); color: var(--text-body); font-size: 11.5px; font-weight: 700; font-family: var(--font-main); border: 1px solid var(--border-input); cursor: pointer; transition: all 0.2s; }
.btn-secondary:hover { background: var(--gray-50); border-color: var(--gray-300); }
.btn-secondary.small { padding: 4px 8px; font-size: 10.5px; }
.btn-danger { display: inline-flex; align-items: center; padding: 6px 12px; border-radius: 8px; background: var(--red-50); color: var(--red-500); font-size: 11.5px; font-weight: 700; border: 1px solid rgba(239,68,68,0.2); cursor: pointer; transition: all 0.2s; }
.btn-danger:hover { background: var(--red-500); color: white; }
.btn-danger.small { padding: 4px 8px; font-size: 10.5px; }
.spinner-sm { width: 14px; height: 14px; border-radius: 50%; border: 2px solid rgba(255,255,255,0.3); border-top-color: white; animation: spin 0.6s linear infinite; display: inline-block; }
@keyframes spin { to { transform: rotate(360deg); } }

.btn-warning { background: var(--amber-500); }
.btn-warning:hover { background: #D97706; box-shadow: 0 4px 16px rgba(245,158,11,0.25); }

.hidden-input { position: absolute; width: 0; height: 0; opacity: 0; pointer-events: none; }
.form-group { display: flex; flex-direction: column; gap: 6px; margin-bottom: 14px; }
@media (max-width: 480px) {
  .form-group { margin-bottom: 12px; }
}
.text-center { text-align: center; }
.font-mono { font-family: var(--font-mono); }
.flex-1 { flex: 1; }
.form-group label { font-size: 13px; font-weight: 650; color: var(--text-title); letter-spacing: -0.1px; }
@media (max-width: 480px) {
  .form-group label { font-size: 14px; }
}
.form-input { width: 100%; padding: 11px 14px; border: 1.5px solid var(--border-input); border-radius: 11px; font-size: 14px; font-family: var(--font-main); outline: none; background: var(--bg-input); color: var(--text-title); transition: all 0.25s ease; -webkit-appearance: none; }
.form-input:focus { border-color: var(--blue-500); box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1); }
.form-input::placeholder { color: var(--gray-400); font-weight: 400; }
.dashboard-root.dark-theme .form-input:focus { box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15); }
@media (max-width: 480px) {
  .form-input { padding: 14px 14px; font-size: 16px; border-radius: 12px; }
}

/* ══════════════════════════════════════════════════════════════════
   MODAL WIDTH VARIANTS & CONFIRM DIALOG
   ══════════════════════════════════════════════════════════════════ */
/* 💳 Premium Wallet Recharge Modal */
.recharge-modal { max-width: 480px; padding: 0; border-radius: 20px; box-shadow: 0 25px 60px rgba(15,23,42,0.15); overflow: hidden; }
.rm-header { display: flex; align-items: center; justify-content: space-between; padding: 14px 20px 10px; background: linear-gradient(135deg, #EEF2FF, #E0E7FF); }
.dark-theme .rm-header { background: linear-gradient(135deg, rgba(99,102,241,0.1), rgba(99,102,241,0.04)); }
.rm-header-left { display: flex; align-items: center; gap: 10px; }
.rm-header-icon { width: 32px; height: 32px; border-radius: 8px; background: linear-gradient(135deg, #6366F1, #8B5CF6); display: flex; align-items: center; justify-content: center; color: white; }
.rm-title { font-size: 15px; font-weight: 800; color: #1E293B; margin: 0; }
.dark-theme .rm-title { color: #F1F5F9; }
.rm-balance { font-size: 11px; color: #64748B; margin: 1px 0 0; }
.rm-balance strong { color: #1E293B; }
.dark-theme .rm-balance strong { color: #E2E8F0; }
.rm-balance-toggle { font-size: 10.5px; color: #6366F1; font-weight: 700; cursor: pointer; margin-left: 6px; padding: 1px 6px; border-radius: 4px; background: rgba(99,102,241,0.08); transition: 0.2s; }
.rm-balance-toggle:hover { background: rgba(99,102,241,0.15); }

.rm-tabs { display: grid; grid-template-columns: repeat(4, 1fr); gap: 4px; padding: 4px 16px; background: #F8FAFC; border-bottom: 1px solid #F1F5F9; }
.dark-theme .rm-tabs { background: #1E293B; border-color: #334155; }
.rm-tab { padding: 7px 4px; background: transparent; border: none; border-radius: 8px; font-family: var(--font-main); font-size: 10px; font-weight: 700; color: #94A3B8; cursor: pointer; text-align: center; transition: all 0.25s; display: flex; flex-direction: column; align-items: center; gap: 2px; }
.rm-tab:hover { color: #475569; }
.rm-tab.active { color: #6366F1; background: white; box-shadow: 0 2px 8px rgba(99,102,241,0.08); }
.dark-theme .rm-tab.active { background: #334155; }
.rm-tab-icon { font-size: 16px; line-height: 1; }
.rm-tab-label { font-size: 10px; }

.rm-body { padding: 14px 20px 18px; }
.rm-panel { display: flex; flex-direction: column; gap: 10px; }
.rm-field { display: flex; flex-direction: column; gap: 5px; }
.rm-label { font-size: 11.5px; font-weight: 650; color: #475569; }
.dark-theme .rm-label { color: #94A3B8; }
.rm-input-wrap { display: flex; align-items: center; border: 1.5px solid #E2E8F0; border-radius: 10px; overflow: hidden; transition: border-color 0.2s, box-shadow 0.2s; background: white; }
.dark-theme .rm-input-wrap { background: #1E293B; border-color: #334155; }
.rm-input-wrap:focus-within { border-color: #6366F1; box-shadow: 0 0 0 3px rgba(99,102,241,0.08); }
.rm-input-prefix { padding: 0 10px; font-size: 11px; font-weight: 700; color: #6366F1; background: #F1F5F9; height: 100%; display: flex; align-items: center; letter-spacing: 0.5px; }
.dark-theme .rm-input-prefix { background: #334155; color: #A5B4FC; }
.rm-input { flex: 1; padding: 8px 11px; border: none; font-size: 12px; font-family: var(--font-main); outline: none; background: transparent; color: #1E293B; }
.dark-theme .rm-input { color: #F1F5F9; }
.rm-input::placeholder { color: #94A3B8; }
.rm-input-amount { font-weight: 700; }
.rm-input-pin { text-align: center; letter-spacing: 6px; font-size: 18px; }

.rm-otp-box { background: linear-gradient(135deg, rgba(99,102,241,0.04), rgba(139,92,246,0.04)); border: 1px solid rgba(99,102,241,0.12); border-radius: 12px; padding: 16px; text-align: center; }
.rm-otp-label { font-size: 11.5px; color: #475569; margin-bottom: 10px; }
.rm-otp-label strong { color: #6366F1; }
.rm-otp-input { width: 180px; padding: 10px; border: 2px solid #E2E8F0; border-radius: 10px; font-size: 22px; letter-spacing: 8px; text-align: center; font-family: var(--font-main); outline: none; background: white; transition: 0.2s; }
.dark-theme .rm-otp-input { background: #1E293B; border-color: #334155; color: #F1F5F9; }
.rm-otp-input:focus { border-color: #6366F1; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
.rm-otp-timer { font-size: 10.5px; color: #94A3B8; margin-top: 8px; }

.rm-btn-primary { display: flex; align-items: center; justify-content: center; gap: 8px; padding: 10px 20px; border: none; border-radius: 10px; background: linear-gradient(135deg, #6366F1, #8B5CF6); color: white; font-size: 13px; font-weight: 700; cursor: pointer; transition: all 0.25s; font-family: var(--font-main); width: 100%; margin-top: 2px; }
.rm-btn-primary:hover:not(:disabled) { transform: translateY(-1px); box-shadow: 0 8px 24px rgba(99,102,241,0.3); }
.rm-btn-primary:disabled { opacity: 0.45; cursor: not-allowed; }

/* 💳 Credit Card 3D */
.rm-credit-card { width: 100%; height: 130px; perspective: 1000px; margin-bottom: 10px; }
.rm-card-inner { position: relative; width: 100%; height: 100%; transition: transform 0.6s cubic-bezier(0.34, 1.56, 0.64, 1); transform-style: preserve-3d; }
.rm-credit-card.flipped .rm-card-inner { transform: rotateY(180deg); }
.rm-card-face { position: absolute; width: 100%; height: 100%; backface-visibility: hidden; border-radius: 12px; padding: 14px 16px; display: flex; flex-direction: column; justify-content: space-between; }
.rm-card-front { background: linear-gradient(135deg, #1E293B 0%, #334155 50%, #475569 100%); box-shadow: 0 6px 24px rgba(15,23,42,0.3); }
.rm-card-top { display: flex; justify-content: space-between; align-items: flex-start; }
.rm-card-brand { font-size: 11px; font-weight: 800; color: rgba(255,255,255,0.9); letter-spacing: 1.5px; }
.rm-card-number { font-size: 13px; font-weight: 700; font-family: 'Courier New', monospace; color: rgba(255,255,255,0.95); letter-spacing: 1.5px; text-align: center; margin: 4px 0; }
.rm-card-footer { display: flex; justify-content: space-between; }
.rm-card-label { display: block; font-size: 7px; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: 1px; }
.rm-card-val { display: block; font-size: 10px; font-weight: 700; color: rgba(255,255,255,0.9); margin-top: 1px; }
.rm-card-back { background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%); transform: rotateY(180deg); padding: 0; }
.rm-card-strip { height: 28px; background: rgba(0,0,0,0.4); margin-top: 18px; }
.rm-card-cvv-area { padding: 8px 16px; display: flex; flex-direction: column; gap: 3px; }
.rm-card-cvv { width: 60px; padding: 4px 8px; background: rgba(255,255,255,0.9); border-radius: 4px; color: #1E293B; font-family: 'Courier New', monospace; font-weight: 700; text-align: center; font-size: 12px; letter-spacing: 2px; }
.rm-card-fields { display: flex; flex-direction: column; gap: 8px; }
.rm-row-2 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 8px; }

.rm-btn-paypal { display: flex; align-items: center; justify-content: center; gap: 8px; padding: 12px 20px; border: none; border-radius: 12px; background: linear-gradient(135deg, #003087, #0057A0); color: white; font-size: 13px; font-weight: 700; cursor: pointer; transition: all 0.25s; font-family: var(--font-main); width: 100%; }
.rm-btn-paypal:hover { transform: translateY(-1px); box-shadow: 0 8px 24px rgba(0,48,135,0.3); }

.rm-paypal-header { display: flex; align-items: center; gap: 14px; padding: 12px 16px; background: linear-gradient(135deg, #F0F4FF, #E8EEFF); border-radius: 12px; margin-bottom: 4px; }
.dark-theme .rm-paypal-header { background: linear-gradient(135deg, rgba(0,48,135,0.1), rgba(99,102,241,0.05)); }
.rm-paypal-title { font-size: 14px; font-weight: 750; color: #003087; }
.rm-paypal-sub { font-size: 11px; color: #64748B; }

/* 🔐 2FA stepper wizard styles */
.tfa-stepper { display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; }
.tfa-step { width: 20px; height: 20px; border-radius: 50%; background: var(--gray-200); color: var(--text-muted); font-size: 10px; font-weight: 700; display: flex; align-items: center; justify-content: center; }
.tfa-step.active { background: var(--blue-600); color: white; }
.tfa-step.done { background: var(--emerald-500); color: white; }
.line-step { height: 2px; flex: 1; background: var(--gray-200); }
.tfa-qr-wrap { background: white; border: 1px solid var(--border-input); border-radius: 10px; padding: 10px; display: inline-block; margin-bottom: 10px; }
.tfa-secret-box { background: var(--gray-50); border: 1px solid var(--border-input); border-radius: 8px; padding: 10px; text-align: center; position: relative; }
.tfa-secret-lbl { font-size: 9.5px; color: var(--text-muted); text-transform: uppercase; }
.tfa-secret-val { font-family: var(--font-mono); font-weight: 700; color: var(--blue-700); margin-top: 4px; font-size: 14px; letter-spacing: 2px; }
.tfa-copy-btn { margin-top: 6px; display: inline-flex; align-items: center; gap: 4px; padding: 3px 8px; border-radius: 5px; border: 1px solid var(--border-input); background: var(--bg-input); color: var(--text-muted); font-size: 10px; font-weight: 600; cursor: pointer; font-family: var(--font-main); transition: all 0.2s; }
.tfa-copy-btn:hover { border-color: var(--blue-500); color: var(--blue-600); }

/* TOTP Timer Ring */
.tfa-totp-timer { position: relative; width: 56px; height: 56px; margin: 10px auto; display: flex; align-items: center; justify-content: center; }
.tfa-timer-ring { width: 56px; height: 56px; transform: rotate(-90deg); position: absolute; }
.tfa-timer-bg { fill: none; stroke: var(--gray-200); stroke-width: 2.5; }
.tfa-timer-fill { fill: none; stroke: var(--blue-600); stroke-width: 2.5; stroke-linecap: round; transition: stroke-dasharray 1s linear; }
.tfa-timer-text { font-size: 14px; font-weight: 800; color: var(--text-title); position: relative; z-index: 1; }
.tfa-hint { font-size: 10px; color: var(--text-muted); margin-top: 8px; }
.tfa-codes-box { background: var(--gray-50); border: 1px solid var(--border-input); border-radius: 10px; padding: 12px; }
.tfa-codes-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 6px; margin-bottom: 10px; }
.tfa-code-item { background: white; border: 1px solid var(--border-input); border-radius: 6px; padding: 6px; text-align: center; font-weight: 700; font-size: 12.5px; }

.dropzone {
  border: 2px dashed #CBD5E1; border-radius: 12px; padding: 24px 20px;
  text-align: center; color: var(--text-muted); cursor: pointer;
  transition: all 0.2s;
}
.dropzone:hover { border-color: #94A3B8; background: #F8FAFC; }
.dropzone.dragging { border-color: var(--blue-500); background: #EFF6FF; }
.dark-theme .dropzone { border-color: #475569; }
.dark-theme .dropzone:hover { background: #1E293B; }

/* ══════════════════════════════════════════════════════════════════
   TOASTS — SMALL, BOTTOM-RIGHT, ULTRA FLUID
   ══════════════════════════════════════════════════════════════════ */
.toast {
  position: fixed; bottom: 20px; right: 20px; z-index: 9999;
  padding: 8px 14px; border-radius: 10px;
  font-size: 12px; font-weight: 600;
  box-shadow: 0 4px 16px rgba(0,0,0,0.07);
  display: flex; align-items: center; gap: 6px;
  max-width: 320px;
  cursor: pointer;
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255,255,255,0.12);
}
.toast-success { background: rgba(5,150,101,0.92); color: white; }
.toast-success::before { content: '✓'; font-size: 12px; font-weight: 800; }
.toast-error { background: rgba(220,38,38,0.92); color: white; }
.toast-error::before { content: '✕'; font-size: 12px; font-weight: 800; }
.toast-info { background: rgba(37,99,235,0.92); color: white; }
.toast-info::before { content: 'ℹ'; font-size: 12px; font-weight: 800; }
.dark-theme .toast { border-color: rgba(255,255,255,0.08); }

.toast-enter-active { transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); }
.toast-leave-active { transition: all 0.2s ease-in; }
.toast-enter-from { opacity: 0; transform: translateY(12px) scale(0.92); }
.toast-leave-to { opacity: 0; transform: translateY(8px) scale(0.95); }

.fade-slide-enter-active, .fade-slide-leave-active { transition: all 0.25s; }
.fade-slide-enter-from, .fade-slide-leave-to { opacity: 0; transform: translateX(-6px); }

.page-transition-enter-active { transition: all 0.45s cubic-bezier(0.16, 1, 0.3, 1); }
.page-transition-leave-active { transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); }
.page-transition-enter-from { opacity: 0; transform: translateY(18px) scale(0.98); }
.page-transition-leave-to { opacity: 0; transform: translateY(-10px) scale(0.97); }

/* ══════════════════════════════════════════════════════════════════
   ULTRA-SMOOTH STAGGERED ENTRANCE ANIMATIONS
   ══════════════════════════════════════════════════════════════════ */
@keyframes fadeUp {
  from { opacity: 0; transform: translateY(20px) scale(0.97); }
  to { opacity: 1; transform: translateY(0) scale(1); }
}
@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}
@keyframes scaleIn {
  from { opacity: 0; transform: scale(0.92); }
  to { opacity: 1; transform: scale(1); }
}
@keyframes slideInRight {
  from { opacity: 0; transform: translateX(-16px); }
  to { opacity: 1; transform: translateX(0); }
}
@keyframes shimmer {
  0% { background-position: -200% 0; }
  100% { background-position: 200% 0; }
}

/* — Stagger container — children fade up one by one */
.anim-stagger { animation: fadeIn 0.3s ease both; }
.anim-stagger > * {
  animation: fadeUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) both;
}
.anim-stagger > *:nth-child(1) { animation-delay: 0.02s; }
.anim-stagger > *:nth-child(2) { animation-delay: 0.05s; }
.anim-stagger > *:nth-child(3) { animation-delay: 0.08s; }
.anim-stagger > *:nth-child(4) { animation-delay: 0.11s; }
.anim-stagger > *:nth-child(5) { animation-delay: 0.14s; }
.anim-stagger > *:nth-child(6) { animation-delay: 0.17s; }
.anim-stagger > *:nth-child(7) { animation-delay: 0.20s; }
.anim-stagger > *:nth-child(8) { animation-delay: 0.23s; }
.anim-stagger > *:nth-child(9) { animation-delay: 0.26s; }
.anim-stagger > *:nth-child(10) { animation-delay: 0.29s; }
.anim-stagger > *:nth-child(11) { animation-delay: 0.32s; }
.anim-stagger > *:nth-child(12) { animation-delay: 0.35s; }
.anim-stagger > *:nth-child(13) { animation-delay: 0.38s; }
.anim-stagger > *:nth-child(14) { animation-delay: 0.41s; }
.anim-stagger > *:nth-child(15) { animation-delay: 0.44s; }
.anim-stagger > *:nth-child(16) { animation-delay: 0.47s; }
.anim-stagger > *:nth-child(17) { animation-delay: 0.50s; }
.anim-stagger > *:nth-child(18) { animation-delay: 0.53s; }
.anim-stagger > *:nth-child(19) { animation-delay: 0.56s; }
.anim-stagger > *:nth-child(20) { animation-delay: 0.59s; }
.anim-stagger > *:nth-child(21) { animation-delay: 0.62s; }
.anim-stagger > *:nth-child(22) { animation-delay: 0.65s; }
.anim-stagger > *:nth-child(23) { animation-delay: 0.68s; }
.anim-stagger > *:nth-child(24) { animation-delay: 0.71s; }
.anim-stagger > *:nth-child(25) { animation-delay: 0.74s; }
.anim-stagger > *:nth-child(26) { animation-delay: 0.77s; }
.anim-stagger > *:nth-child(27) { animation-delay: 0.80s; }
.anim-stagger > *:nth-child(28) { animation-delay: 0.83s; }
.anim-stagger > *:nth-child(29) { animation-delay: 0.86s; }
.anim-stagger > *:nth-child(30) { animation-delay: 0.89s; }
.anim-stagger > *:nth-child(31) { animation-delay: 0.92s; }
.anim-stagger > *:nth-child(32) { animation-delay: 0.95s; }
.anim-stagger > *:nth-child(33) { animation-delay: 0.98s; }
.anim-stagger > *:nth-child(34) { animation-delay: 1.01s; }
.anim-stagger > *:nth-child(35) { animation-delay: 1.04s; }
.anim-stagger > *:nth-child(36) { animation-delay: 1.07s; }

/* — Individual card entrance */
.anim-fade-up {
  animation: fadeUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) both;
}
.anim-scale-in {
  animation: scaleIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) both;
}
.anim-slide-right {
  animation: slideInRight 0.4s cubic-bezier(0.16, 1, 0.3, 1) both;
}

/* — Loading skeleton shimmer */
.skeleton {
  background: linear-gradient(90deg, #F1F5F9 25%, #E2E8F0 50%, #F1F5F9 75%);
  background-size: 200% 100%;
  animation: shimmer 1.5s ease-in-out infinite;
  border-radius: 6px;
}
.dark-theme .skeleton {
  background: linear-gradient(90deg, #1E293B 25%, #334155 50%, #1E293B 75%);
  background-size: 200% 100%;
}
.skeleton-text { height: 14px; width: 60%; margin-bottom: 8px; }
.skeleton-title { height: 20px; width: 40%; margin-bottom: 12px; }
.skeleton-card { height: 80px; border-radius: 12px; }
.skeleton-row { height: 40px; border-radius: 8px; margin-bottom: 6px; }

/* — Smooth card hover enhancements */
.glass-card {
  transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1),
              box-shadow 0.35s ease,
              background-color 0.35s ease,
              border-color 0.35s ease;
}
.glass-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 12px 32px rgba(15, 23, 42, 0.08), 0 2px 8px rgba(15, 23, 42, 0.04);
}
.dark-theme .glass-card:hover {
  box-shadow: 0 12px 32px rgba(0, 0, 0, 0.3);
}

/* — Month card entrance with pop */
.month-btn {
  transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}
.month-btn:hover {
  transform: translateY(-3px) scale(1.02);
  box-shadow: 0 8px 24px rgba(15, 23, 42, 0.10);
}
.dark-theme .month-btn:hover {
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
}

/* — List item row hover */
.rp-item, .receipt-card, .old-contract-card, .ticket-card, .utility-row {
  transition: all 0.25s ease;
}
.rp-item:hover, .receipt-card:hover, .ticket-card:hover {
  transform: translateX(3px);
  box-shadow: 0 4px 16px rgba(15, 23, 42, 0.06);
}
.dark-theme .rp-item:hover, .dark-theme .receipt-card:hover,
.dark-theme .ticket-card:hover {
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
}

/* — Nav item active indicator smooth */
.nav-item {
  transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}

/* — Chart pulse */
.chart-container { animation: fadeUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) both; }

/* — Section entrance */
.anim-section {
  animation: fadeUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) both;
}

/* ═══════════════════════════════════════════
   PENALTY BANNER
   ═══════════════════════════════════════════ */
.penalty-banner {
  display: flex; align-items: center; gap: 14px;
  padding: 14px 18px; margin-bottom: 16px;
  border-radius: 14px;
  background: linear-gradient(135deg, #FEF2F2, #FFE4E6);
  border: 1px solid rgba(220,38,38,0.15);
  position: relative;
  animation: penaltySlideIn 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}
.dark-theme .penalty-banner { background: linear-gradient(135deg, rgba(220,38,38,0.12), rgba(220,38,38,0.06)); border-color: rgba(220,38,38,0.2); }
@keyframes penaltySlideIn {
  from { opacity: 0; transform: translateY(-10px) scale(0.97); }
  to { opacity: 1; transform: translateY(0) scale(1); }
}
.penalty-banner-icon { flex-shrink: 0; color: #DC2626; }
.penalty-banner-body { flex: 1; min-width: 0; }
.penalty-banner-title { font-size: 13px; font-weight: 800; color: #991B1B; margin-bottom: 4px; }
.dark-theme .penalty-banner-title { color: #FCA5A5; }
.penalty-banner-details { display: flex; flex-wrap: wrap; align-items: center; gap: 4px 8px; font-size: 12px; color: #7F1D1D; }
.dark-theme .penalty-banner-details { color: #FECACA; }
.penalty-sep { color: rgba(220,38,38,0.3); }
.text-red { color: #DC2626; }
.text-indigo { color: #6366F1; }
.penalty-banner-scale { display: flex; align-items: center; gap: 6px; margin-top: 6px; font-size: 11px; }
.ps-label { color: var(--text-muted); font-weight: 600; }
.ps-tier { padding: 2px 8px; border-radius: 6px; background: rgba(100,116,139,0.08); color: var(--text-muted); font-weight: 600; transition: all 0.3s; }
.ps-tier.ps-active { background: rgba(220,38,38,0.12); color: #DC2626; }
.dark-theme .ps-tier.ps-active { background: rgba(220,38,38,0.2); }
.ps-arrow { color: var(--text-muted); font-size: 11px; }
.penalty-banner-close {
  position: absolute; top: 8px; right: 10px;
  width: 24px; height: 24px; border-radius: 50%; border: none;
  background: transparent; color: #DC2626; cursor: pointer;
  font-size: 18px; line-height: 1; display: flex; align-items: center; justify-content: center;
  opacity: 0.5; transition: opacity 0.2s;
}
.penalty-banner-close:hover { opacity: 1; }

/* ═══════════════════════════════════════════
   PREMIUM SMOOTH TRANSITIONS — centered cross-fade
   ═══════════════════════════════════════════ */

/* Recap modals — overlay fade */
.fade-enter-active { transition: all 0.2s cubic-bezier(0.34, 1.56, 0.64, 1); }
.fade-leave-active { transition: all 0.18s cubic-bezier(0.4, 0, 0.2, 1); }
.fade-enter-from,
.fade-leave-to { opacity: 0; }

/* Modal entrance — cards scale in with the backdrop */
.fade-enter-active .card-switch-stage > * {
  transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}
.fade-enter-from .card-switch-stage > * {
  opacity: 0;
  transform: translate(-50%, -35%) scale(0.93);
}

/* Card-switch stage — wraps transitioning cards so they overlap */
.card-switch-stage {
  position: relative;
  width: 100%;
  max-width: 480px;
  min-height: 300px;
}
.card-switch-stage > * {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 100%;
}
@media (hover: hover) {
  .card-switch-stage > .glass-card:hover {
    transform: translate(-50%, -50%) !important;
    box-shadow: var(--shadow-card-hover);
  }
}

/* Card-switch — unified out-in transition for recap → PIN → success */
.card-switch-enter-active {
  will-change: transform, opacity;
  backface-visibility: hidden;
  transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
  z-index: 2;
}
.card-switch-leave-active {
  will-change: transform, opacity;
  backface-visibility: hidden;
  transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1);
  z-index: 1;
}
.card-switch-enter-from {
  opacity: 0;
  transform: translate(-50%, -35%) scale(0.93);
}
.card-switch-enter-to {
  opacity: 1;
  transform: translate(-50%, -50%) scale(1);
}
.card-switch-leave-to {
  opacity: 0;
  transform: translate(-50%, -50%) scale(0.93);
}

/* ══════════════════════════════════════════════════════════════════
   F. RECEIPTS GRID
   ══════════════════════════════════════════════════════════════════ */
.receipts-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 16px; }
.receipt-card { padding: 0; overflow: hidden; }
.receipt-card-header { display: flex; align-items: center; gap: 12px; padding: 16px 18px 12px; border-bottom: 1px solid var(--border-input); }
.receipt-type-icon { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.r-rent { background: #EFF6FF; color: var(--blue-600); }
.r-water { background: #ECFDF5; color: #059669; }
.r-electric { background: #FFFBEB; color: var(--amber-500); }
.r-contract_fee { background: #F3E8FF; color: #7C3AED; }
.dark-theme .r-rent { background: rgba(37,99,235,0.15); }
.dark-theme .r-water { background: rgba(5,150,101,0.15); }
.dark-theme .r-electric { background: rgba(245,158,11,0.15); }
.dark-theme .r-contract_fee { background: rgba(124,58,237,0.15); }
.receipt-meta { flex: 1; min-width: 0; }
.receipt-title { font-size: 13px; font-weight: 700; color: var(--text-title); }
.receipt-ref { font-size: 10.5px; color: var(--text-muted); font-family: var(--font-mono); }
.receipt-amount { font-size: 15px; font-weight: 800; color: var(--text-title); white-space: nowrap; }
.receipt-card-body { padding: 12px 18px; }
.receipt-details-row { display: flex; justify-content: space-between; padding: 4px 0; font-size: 12px; }
.rd-label { color: var(--text-muted); font-weight: 500; }
.rd-value { color: var(--text-body); font-weight: 600; }
.rd-value.mono { font-family: var(--font-mono); font-size: 10.5px; }
.receipt-card-footer { display: flex; gap: 8px; padding: 10px 18px 16px; }
.receipt-card-footer .btn-primary.small { display: flex; align-items: center; gap: 6px; }
.receipt-card-footer .btn-secondary.small { display: flex; align-items: center; gap: 6px; }
.receipts-empty { padding: 40px; text-align: center; color: var(--text-muted); }
.receipt-header-info { display: flex; align-items: center; gap: 10px; }
.receipt-badge { font-size: 12px; font-weight: 700; color: var(--blue-600); background: var(--blue-50); padding: 5px 14px; border-radius: 8px; }
.dark-theme .receipt-badge { background: rgba(37,99,235,0.15); }

/* ══════════════════════════════════════════════════════════════════
   G. OLD CONTRACTS — PREMIUM ARCHIVE CARDS
   ══════════════════════════════════════════════════════════════════ */
.old-contract-card {
  padding: 22px; position: relative; overflow: hidden;
  margin-bottom: 18px;
  transition: all 0.3s ease;
}
.old-contract-card:last-child { margin-bottom: 0; }
.old-contract-card:hover { box-shadow: var(--shadow-card-hover); }
.oc-card-bg-ornament {
  position: absolute; top: -30px; right: -30px;
  width: 120px; height: 120px; border-radius: 50%;
  background: radial-gradient(circle, rgba(37,99,235,0.04), transparent 70%);
  pointer-events: none;
}
.dark-theme .oc-card-bg-ornament { background: radial-gradient(circle, rgba(37,99,235,0.08), transparent 70%); }
.old-contract-top { display: flex; align-items: flex-start; gap: 16px; position: relative; z-index: 1; }
.old-contract-icon {
  width: 48px; height: 48px; border-radius: 14px;
  display: flex; align-items: center; justify-content: center; flex-shrink: 0;
  transition: all 0.3s;
}
.oc-icon-ended { background: linear-gradient(135deg, #ECFDF5, #D1FAE5); color: #059669; }
.oc-icon-terminated { background: linear-gradient(135deg, #FEF2F2, #FEE2E2); color: #DC2626; }
.dark-theme .oc-icon-ended { background: rgba(5,150,101,0.2); }
.dark-theme .oc-icon-terminated { background: rgba(220,38,38,0.2); }
.old-contract-info { flex: 1; min-width: 0; }
.old-contract-title { font-size: 17px; font-weight: 750; color: var(--text-title); letter-spacing: -0.3px; }
.old-contract-address { font-size: 12.5px; color: var(--text-muted); margin-top: 3px; display: flex; align-items: center; gap: 4px; }
.old-contract-address svg { flex-shrink: 0; }
.old-contract-period { text-align: right; display: flex; flex-direction: column; align-items: flex-end; gap: 6px; flex-shrink: 0; }
.oc-date-badge {
  font-size: 11px; color: var(--text-muted); font-weight: 500;
  background: var(--gray-100); padding: 4px 10px; border-radius: 6px;
  white-space: nowrap;
}
.dark-theme .oc-date-badge { background: rgba(15,23,42,0.5); }
.oc-status-badge {
  font-size: 10.5px; font-weight: 700; padding: 4px 12px; border-radius: 8px;
  display: inline-flex; align-items: center; gap: 5px;
}
.oc-status-dot { width: 5px; height: 5px; border-radius: 50%; display: inline-block; }
.oc-status-badge.oc-ended { background: #ECFDF5; color: #059669; }
.oc-status-badge.oc-ended .oc-status-dot { background: #059669; animation: dotPulse 2s ease-in-out infinite; }
.oc-status-badge.oc-terminated { background: #FEF2F2; color: #DC2626; }
.oc-status-badge.oc-terminated .oc-status-dot { background: #DC2626; }
.dark-theme .oc-status-badge.oc-ended { background: rgba(5,150,101,0.15); }
.dark-theme .oc-status-badge.oc-terminated { background: rgba(220,38,38,0.15); }
@keyframes dotPulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.4; } }
.old-contract-details {
  display: grid; grid-template-columns: repeat(5, 1fr); gap: 0;
  margin-top: 16px; border-radius: 12px;
  background: var(--bg-input);
  border: 1px solid var(--border-input);
  overflow: hidden;
}
.dark-theme .old-contract-details { background: rgba(15,23,42,0.5); }
.oc-rent-value { color: var(--blue-600); }
.old-contract-details .oc-detail-item {
  display: flex; flex-direction: column; gap: 4px;
  padding: 14px 16px; border-right: 1px solid var(--border-input);
}
.old-contract-details .oc-detail-item:last-child { border-right: none; }
.old-contract-details .oc-d-label { font-size: 9.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.4px; }
.old-contract-details .oc-d-value { font-size: 14px; font-weight: 700; color: var(--text-title); letter-spacing: -0.2px; }

/* ══════════════════════════════════════════════════════════════════
   RECEIPT PREVIEW — Invoice / Contract detail modal (PDF lookalike)
   ══════════════════════════════════════════════════════════════════ */
.invoice-detail-modal { max-width: 480px; width: 100%; overflow: hidden; }
.receipts-preview-modal { overflow: hidden; }
.rcpt-preview { background:#F8FAFC; border-radius:12px; border:1px solid #E2E8F0; overflow:hidden; }
.rcpt-hdr { background:linear-gradient(135deg,#0F172A,#1E293B); padding:12px 16px 10px; }
.rcpt-hdr-top { display:flex; justify-content:space-between; align-items:flex-start; }
.rcpt-brand { font-size:15px; font-weight:900; color:white; letter-spacing:1.2px; }
.rcpt-tag { font-size:7px; color:rgba(255,255,255,0.4); letter-spacing:0.6px; text-transform:uppercase; margin-top:0; }
.rcpt-title-wrap { text-align:right; }
.rcpt-title { font-size:10px; font-weight:800; color:rgba(255,255,255,0.9); text-transform:uppercase; letter-spacing:0.4px; }
.rcpt-ori { font-size:7px; color:rgba(255,255,255,0.35); letter-spacing:0.6px; }
.rcpt-hdr-bar { display:flex; justify-content:space-between; margin-top:5px; padding-top:5px; border-top:1px dashed rgba(255,255,255,0.08); font-size:7.5px; color:rgba(255,255,255,0.5); }
.rcpt-hdr-bar strong { color:white; font-weight:700; }
.rcpt-body { padding:10px 16px 8px; }
.rcpt-sec-title { font-size:7px; font-weight:800; color:#94A3B8; text-transform:uppercase; letter-spacing:1.2px; margin-bottom:4px; padding-bottom:3px; border-bottom:1.5px solid #E2E8F0; }
.rcpt-table { border:1px solid #E2E8F0; border-radius:6px; overflow:hidden; }
.rcpt-row { display:flex; justify-content:space-between; align-items:center; padding:4px 10px; font-size:10.5px; }
.rcpt-row-even { background:white; }
.rcpt-label { color:#64748B; font-weight:500; }
.rcpt-value { color:#0F172A; font-weight:700; text-align:right; }
.rcpt-total { margin-top:6px; border:1.5px solid #0F172A; border-radius:6px; overflow:hidden; }
.rcpt-total-inner { display:flex; justify-content:space-between; align-items:center; padding:6px 10px; background:white; }
.rcpt-total-label { font-size:8px; font-weight:800; color:#0F172A; text-transform:uppercase; letter-spacing:0.6px; }
.rcpt-total-value { font-size:13px; font-weight:900; }
.rcpt-total-paid { color:#059669; }
.rcpt-total-unpaid { color:#DC2626; }
.rcpt-total-words { padding:3px 10px 5px; font-size:7.5px; color:#64748B; font-style:italic; border-top:1px solid #E2E8F0; }
.rcpt-status { text-align:center; margin-top:5px; padding:2px 0 0; }
.rcpt-status-inner { display:inline-block; padding:3px 16px; border-radius:4px; font-size:9px; font-weight:800; letter-spacing:0.4px; }
.rcpt-st-paid { background:rgba(5,150,101,0.1); color:#059669; border:1px solid rgba(5,150,101,0.2); }
.rcpt-st-pending { background:rgba(245,158,11,0.1); color:#D97706; border:1px solid rgba(245,158,11,0.2); }
.rcpt-st-unpaid { background:rgba(220,38,38,0.1); color:#DC2626; border:1px solid rgba(220,38,38,0.2); }
.rcpt-ftr { border-top:1px solid #E2E8F0; padding:5px 16px; display:flex; justify-content:space-between; font-size:7px; color:#94A3B8; }
.rcpt-ftr strong { color:#64748B; }
.old-contract-actions {
  display: flex; gap: 10px; margin-top: 16px; justify-content: flex-end;
  position: relative; z-index: 1;
}
.old-contract-actions .btn-secondary.small,
.old-contract-actions .btn-primary.small {
  display: flex; align-items: center; gap: 6px;
  padding: 7px 14px; font-size: 11.5px;
}

/* ══════════════════════════════════════════════════════════════════
   H. UTILITIES TABLE
   ══════════════════════════════════════════════════════════════════ */
.conso-value { font-weight: 600; color: var(--text-body); }
.conso-index { font-size: 10px; color: var(--text-muted); font-family: var(--font-mono); }
.table-empty { padding: 30px; text-align: center; color: var(--text-muted); font-size: 13px; }

/* ══════════════════════════════════════════════════════════════════
   I. CONTRACT FEE PAYMENT CARD
   ══════════════════════════════════════════════════════════════════ */
.contract-fee-card { padding: 20px; margin-top: 16px; }
.contract-fee-row { display: flex; align-items: flex-start; gap: 16px; }
.contract-fee-left { flex-shrink: 0; }
.contract-fee-icon-ring { width: 44px; height: 44px; border-radius: 12px; background: linear-gradient(135deg, #FEF3C7, #FDE68A); color: #D97706; display: flex; align-items: center; justify-content: center; }
.contract-fee-info { flex: 1; }
.contract-fee-status { flex-shrink: 0; align-self: center; }
.contract-fee-actions { display: flex; gap: 10px; margin-top: 14px; flex-wrap: wrap; }
.contract-fee-actions .btn-primary { display: flex; align-items: center; gap: 8px; }
.contract-fee-paid-msg { display: flex; align-items: center; gap: 8px; margin-top: 12px; font-size: 13px; font-weight: 600; color: #059669; }
.property-status-badge.active { display: inline-flex; align-items: center; gap: 4px; font-size: 10px; font-weight: 700; padding: 4px 10px; border-radius: 8px; background: #D1FAE5; color: #059669; }
.link-btn { background: none; border: none; color: var(--blue-600); cursor: pointer; font-weight: 700; font-size: 12px; font-family: var(--font-main); }
.link-btn:hover { text-decoration: underline; }

/* Status pill animation — premium pulse for pending items */
.status-pill.status-pending { animation: pendingPulse 2s ease-in-out infinite; }
@keyframes pendingPulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.65; } }

/* Theme switch smooth transition feedback */
.dashboard-root { transition: background-color 0.45s ease, color 0.35s ease; }
.dark-theme .glass-card { transition: background-color 0.45s ease, border-color 0.35s ease, box-shadow 0.35s ease, transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1); }

/* ══════════════════════════════════════════════════════════════════
   MODAL ENHANCEMENTS — PREMIUM TOUCH
   ══════════════════════════════════════════════════════════════════ */
.modal-box {
  background: white;
  border-radius: 20px;
  box-shadow:
    0 25px 60px rgba(15, 23, 42, 0.10),
    0 8px 24px rgba(15, 23, 42, 0.06),
    0 1px 4px rgba(15, 23, 42, 0.04);
  border: 1px solid #E2E8F0;
  position: relative;
  transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.2s ease;
  max-height: 85vh; overflow-y: auto;
}
.dark-theme .modal-box {
  background: #1E293B;
  border-color: #334155;
  box-shadow: 0 25px 60px rgba(0, 0, 0, 0.5);
}
.modal-header {
  display: flex; align-items: center; justify-content: space-between;
  padding: 14px 22px; border-bottom: 1px solid #F1F5F9;
  background: #F8FAFC;
}
.dark-theme .modal-header {
  background: #0F172A; border-color: rgba(255,255,255,0.06);
}
.modal-title { font-size: 15px; font-weight: 750; color: var(--text-title); letter-spacing: -0.3px; }
.modal-subtitle { font-size: 12px; color: var(--text-muted); margin-top: 2px; }
.modal-close {
  width: 30px; height: 30px; border-radius: 8px;
  border: none; background: #F1F5F9; color: var(--text-muted);
  font-size: 18px; cursor: pointer; display: flex; align-items: center; justify-content: center;
  transition: all 0.2s; line-height: 1;
}
.modal-close:hover { background: #E2E8F0; color: var(--text-title); transform: scale(1.06); }
.dark-theme .modal-close { background: #334155; color: #94A3B8; }
.dark-theme .modal-close:hover { background: #475569; color: #F8FAFC; }

/* → Modal content — generous spacing from borders */
.modal-box > *:not(.modal-header):not(.modal-close) {
  padding-left: 26px; padding-right: 26px;
}
.modal-box > .modal-header + * { padding-top: 18px; }
.modal-box > *:last-child:not(.modal-header) { padding-bottom: 20px; }

/* → Compact receipt/recharge modals — no scrollbar needed */
.receipts-preview-modal > .modal-body,
.invoice-detail-modal > .modal-body,
.avatar-delete-modal > .modal-body { padding: 14px 20px 16px; }
.receipts-preview-modal > .modal-body > *:last-child { margin-bottom: 0; }
.recharge-modal .rm-body { padding: 14px 20px 18px; }

/* → Grouping wrapper (no extra padding — inherits from the rule above) */
.modal-body + .modal-body { padding-top: 0; }

/* → Smooth inner content animation */
.modal-box > * { animation: modalFadeIn 0.3s ease both; animation-delay: 0.05s; }
@keyframes modalFadeIn { from { opacity: 0; transform: translateY(4px); } to { opacity: 1; transform: translateY(0); } }

/* → Form groups inside modals — tighter rhythm */
.modal-box .form-group { margin-bottom: 12px; }
.modal-box .form-group:last-child { margin-bottom: 0; }
.modal-box .btn-primary.full-width,
.modal-box .btn-danger.full-width,
.modal-box .btn-secondary.full-width { padding: 10px 14px; font-size: 13px; border-radius: 10px; }

/* → Consistent button spacing inside modals */
.modal-btn-gap { margin-top: 10px !important; }
.modal-btn-row { display: flex; gap: 10px; margin-top: 10px; }
.modal-body-inner { margin-top: 14px; }
.modal-box .btn-primary:not(.full-width),
.modal-box .btn-secondary:not(.full-width),
.modal-box .btn-danger:not(.full-width) { padding: 8px 16px; font-size: 12.5px; border-radius: 9px; }

/* Modal transition — ultra smooth */
.modal-enter-active { transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1); }
.modal-leave-active { transition: all 0.28s cubic-bezier(0.4, 0, 0.2, 1); }
.modal-leave-active .modal-box {
  transition: transform 0.28s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.28s cubic-bezier(0.4, 0, 0.2, 1);
}
.modal-enter-from,
.modal-leave-to { opacity: 0; }
.modal-enter-from .modal-box,
.modal-leave-to .modal-box {
  transform: scale(0.93) translateY(14px);
  opacity: 0;
}

/* Overlay — soft, clean */
.modal-overlay {
  position: fixed; inset: 0; z-index: 500;
  background: rgba(15, 23, 42, 0.3);
  backdrop-filter: blur(4px);
  display: flex; align-items: center; justify-content: center;
  padding: 20px;
}
.dark-theme .modal-overlay { background: rgba(0, 0, 0, 0.55); }

/* Action buttons polish */
.action-btn {
  padding: 6px 12px; border-radius: 8px; border: none;
  font-size: 10.5px; font-weight: 700; font-family: var(--font-main);
  cursor: pointer; transition: all 0.2s;
}
.action-btn.pay-btn { background: var(--emerald-50); color: #059669; }
.action-btn.pay-btn:hover { background: #A7F3D0; }
.action-btn.receipt-btn { background: var(--blue-50); color: var(--blue-600); }
.action-btn.receipt-btn:hover { background: var(--blue-100); }
.action-btn.upload-proof-btn { background: var(--gray-100); color: var(--text-muted); }
.action-btn.upload-proof-btn:hover { background: var(--gray-200); }
.action-btn.view-btn { background: var(--gray-100); color: var(--text-body); }
.action-btn.view-btn:hover { background: var(--gray-200); }
.dark-theme .action-btn.pay-btn { background: rgba(5,150,101,0.15); }
.dark-theme .action-btn.receipt-btn { background: rgba(37,99,235,0.15); }
.dark-theme .action-btn.upload-proof-btn { background: rgba(71,85,105,0.3); }
.dark-theme .action-btn.view-btn { background: rgba(71,85,105,0.3); }

/* ══════════════════════════════════════════════════════════════════
   RESPONSIVE DESIGN — ULTRA PREMIUM MOBILE EXPERIENCE
   ══════════════════════════════════════════════════════════════════ */

/* ─── Desktop header (hidden by default, shown on ≥769px) ─── */
.desktop-header {
  display: none;
  align-items: center; justify-content: space-between;
  padding: 0 28px; height: 56px;
  background: var(--bg-main);
  border-bottom: 1px solid var(--border-input);
}
.desktop-header-left { display: flex; align-items: center; gap: 12px; }
.desktop-page-label { font-size: 16px; font-weight: 750; color: var(--text-title); letter-spacing: -0.3px; }
.desktop-header-right { display: flex; align-items: center; gap: 6px; }
.desktop-icon-btn {
  display: flex; align-items: center; gap: 5px;
  padding: 6px 12px; border-radius: 8px;
  border: none; background: transparent;
  color: var(--text-muted); font-size: 12px; font-weight: 650; font-family: var(--font-main);
  cursor: pointer; transition: all 0.2s;
}
.desktop-icon-btn:hover { background: var(--bg-input); color: var(--text-body); }
.desktop-user-badge {
  display: flex; align-items: center; gap: 8px;
  padding: 4px 10px 4px 4px; border-radius: 8px;
  cursor: pointer; transition: all 0.2s; margin-left: 6px;
}
.desktop-user-badge:hover { background: var(--bg-input); }
.desktop-user-avatar { width: 30px; height: 30px; border-radius: 50%; object-fit: cover; }
.desktop-user-info { display: flex; flex-direction: column; line-height: 1.2; }
.desktop-user-name { font-size: 12px; font-weight: 700; color: var(--text-title); }
.desktop-user-role { font-size: 9.5px; color: var(--text-muted); }

/* Mobile header — hidden by default */
.mobile-header { display: none; }

/* Bottom nav — hidden by default */
.bottom-nav { display: none; }

/* 🚪 Logout modal — ultra premium */
.logout-modal {
  max-width: 400px;
  width: 100%;
  text-align: center;
  padding: 40px 32px 28px;
  border-radius: 24px;
  overflow: hidden;
}
.logout-modal > * { padding-left: 0 !important; padding-right: 0 !important; }
.logout-close {
  position: absolute; top: 14px; right: 14px;
  width: 32px; height: 32px; border-radius: 50%;
  border: none; background: #F1F5F9; color: #94A3B8;
  font-size: 20px; cursor: pointer; display: flex; align-items: center; justify-content: center;
  transition: all 0.2s; line-height: 1;
}
.logout-close:hover { background: #E2E8F0; color: #475569; transform: scale(1.05); }
.dark-theme .logout-close { background: #334155; color: #94A3B8; }
.dark-theme .logout-close:hover { background: #475569; color: #F8FAFC; }
.logout-graphic { margin-bottom: 20px; }
.logout-icon-ring {
  width: 64px; height: 64px; border-radius: 50%;
  background: linear-gradient(135deg, #FEE2E2, #FECACA);
  display: flex; align-items: center; justify-content: center;
  margin: 0 auto; color: #EF4444;
}
.dark-theme .logout-icon-ring { background: linear-gradient(135deg, #7F1D1D, #991B1B); color: #FCA5A5; }
.logout-title {
  font-size: 18px; font-weight: 750; color: var(--text-title);
  margin-bottom: 8px; letter-spacing: -0.4px;
}
.logout-desc {
  font-size: 13.5px; color: var(--text-muted); line-height: 1.6;
  margin-bottom: 28px; max-width: 300px; margin-left: auto; margin-right: auto;
}
.logout-actions { display: flex; gap: 10px; }
.lo-btn {
  flex: 1; padding: 12px 16px; border-radius: 12px;
  font-size: 13px; font-weight: 700; font-family: var(--font-main);
  cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; justify-content: center; gap: 6px;
}
.lo-btn-secondary {
  border: 1px solid var(--border-input); background: var(--bg-input);
  color: var(--text-body);
}
.lo-btn-secondary:hover { background: #E2E8F0; border-color: #CBD5E1; }
.dark-theme .lo-btn-secondary { border-color: #475569; background: #334155; color: #E2E8F0; }
.dark-theme .lo-btn-secondary:hover { background: #475569; border-color: #64748B; }
.lo-btn-primary {
  border: none; background: #EF4444; color: white;
}
.lo-btn-primary:hover { background: #DC2626; transform: translateY(-1px); box-shadow: 0 8px 20px rgba(239,68,68,0.25); }
.dark-theme .lo-btn-primary { background: #DC2626; }
.dark-theme .lo-btn-primary:hover { background: #B91C1C; }

.lang-desc { margin-top: 2px; }
.lang-btn-row {
  display: flex; gap: 10px; margin-top: 8px;
}
.lang-btn {
  flex: 1; display: flex; align-items: center; gap: 10px;
  padding: 12px 16px; border-radius: 12px;
  border: 1px solid var(--border-input); background: var(--bg-input);
  cursor: pointer; transition: all 0.25s ease;
  font-family: var(--font-main);
}
@media (max-width: 480px) {
  .lang-btn { padding: 10px 12px; gap: 8px; }
  .lang-flag { font-size: 18px; }
  .lang-name { font-size: 12px; }
}
.lang-btn:hover { border-color: #CBD5E1; background: #F1F5F9; }
.dark-theme .lang-btn { border-color: #334155; background: #1E293B; }
.dark-theme .lang-btn:hover { border-color: #475569; background: #334155; }
.lang-btn.active {
  border-color: #2563EB; background: #EFF6FF;
  box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
}
.dark-theme .lang-btn.active { border-color: #60A5FA; background: rgba(96,165,250,0.1); }
.lang-flag { font-size: 22px; line-height: 1; }
.lang-name { font-size: 13px; font-weight: 650; color: var(--text-body); }
.lang-btn.active .lang-name { color: #2563EB; font-weight: 700; }
.dark-theme .lang-btn.active .lang-name { color: #60A5FA; }

/* ══════════════════════════════════════════════════════
   BREAKPOINT: ≥ 769px — Desktop & Tablet Landscape
   ══════════════════════════════════════════════════════ */
@media (min-width: 769px) {
  .desktop-header { display: flex; }
  .mobile-header { display: none !important; }
  .bottom-nav { display: none !important; }
}

/* ══════════════════════════════════════════════════════
   BREAKPOINT: 769px – 1100px — Medium screens / Tablet
   ══════════════════════════════════════════════════════ */
@media (max-width: 1100px) {
  .kpi-grid { grid-template-columns: repeat(2, 1fr); }
  .overview-lower-grid { grid-template-columns: 1fr; }
  .finance-summary-row { grid-template-columns: repeat(2, 1fr); }
  .bail-details-grid { grid-template-columns: 1fr; }
  .support-layout { grid-template-columns: 1fr; }
  .profile-layout { grid-template-columns: 1fr; }
  .property-card { flex-direction: column; }
  .property-image-wrap { width: 100%; height: 160px; }
  .month-selector-grid { grid-template-columns: repeat(4, 1fr); }
  .receipts-grid { grid-template-columns: 1fr; }
  .old-contract-details { grid-template-columns: repeat(3, 1fr); }
  .old-contract-details .oc-detail-item { border-right: 1px solid var(--border-input); }
  .old-contract-details .oc-detail-item:nth-child(3n) { border-right: none; }
  .old-contract-details .oc-detail-item:last-child { border-right: none; }
}

/* ══════════════════════════════════════════════════════
   BREAKPOINT: ≤ 768px — Tablet portrait & Large phones
   ══════════════════════════════════════════════════════ */
@media (max-width: 768px) {
  /* ── Layout ── */
  .sidebar { transform: translateX(-100%); transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); width: var(--sidebar-w) !important; }
  .mobile-open .sidebar { transform: translateX(0); }
  .sidebar-collapsed .sidebar { width: var(--sidebar-w) !important; padding: 16px 14px; }
  .main-content { margin-left: 0 !important; padding-bottom: 72px; }
  .page-panel { padding: 14px 12px; }
  .tab-section { padding-bottom: 24px; }

  /* ── Mobile overlay ── */
  .mobile-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 90; backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px); }

  /* ── Mobile header ── */
  .mobile-header { display: flex !important; align-items: center; justify-content: space-between; padding: 12px 16px; background: rgba(255,255,255,0.92); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border-bottom: 1px solid var(--gray-100); position: sticky; top: 0; z-index: 50; }
  .dark-theme .mobile-header { background: rgba(15,23,42,0.92); border-color: rgba(255,255,255,0.06); }
  .burger-btn { width: 40px; height: 40px; border-radius: 10px; border: 1px solid var(--gray-200); background: var(--bg-input); cursor: pointer; display: flex; align-items: center; justify-content: center; color: var(--gray-600); transition: all 0.2s; flex-shrink: 0; }
  .burger-btn:hover { background: var(--gray-100); }
  .burger-btn:active { transform: scale(0.94); }
  .dark-theme .burger-btn { border-color: var(--gray-300); background: #1E293B; color: var(--gray-500); }
  .mobile-title { font-weight: 750; font-size: 16px; color: var(--text-title); letter-spacing: -0.3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin: 0 8px; }
  .mobile-avatar { flex-shrink: 0; }
  .mobile-avatar img { width: 34px; height: 34px; border-radius: 50%; object-fit: cover; border: 2px solid var(--border-input); }

  /* ── Bottom Navigation — ultra premium ── */
  .bottom-nav { display: flex !important; align-items: stretch; position: fixed; bottom: 0; left: 0; right: 0; height: 64px; background: rgba(255,255,255,0.96); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border-top: 1px solid var(--gray-200); z-index: 100; padding-bottom: env(safe-area-inset-bottom, 0px); box-shadow: 0 -4px 20px rgba(0,0,0,0.04); }
  .dark-theme .bottom-nav { background: rgba(15,23,42,0.96); border-top-color: rgba(255,255,255,0.06); box-shadow: 0 -4px 20px rgba(0,0,0,0.2); }
  .bn-item { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 3px; padding: 4px 8px; border: none; background: transparent; color: var(--gray-400); font-size: 10.5px; font-weight: 600; font-family: var(--font-main); cursor: pointer; transition: all 0.2s ease; position: relative; min-height: 48px; }
  .bn-item::before { content: ''; position: absolute; top: 0; left: 50%; transform: translateX(-50%) scaleX(0); width: 32px; height: 3px; border-radius: 0 0 3px 3px; background: linear-gradient(90deg, var(--blue-600), #6366F1); transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1); }
  .bn-item:hover { color: var(--gray-600); }
  .bn-item:active { transform: scale(0.95); }
  .bn-item.active { color: var(--blue-600); }
  .bn-item.active::before { transform: translateX(-50%) scaleX(1); }
  .bn-item svg { width: 22px; height: 22px; transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1); }
  .bn-item.active svg { transform: translateY(-1px); }
  .dark-theme .bn-item { color: var(--gray-500); }
  .dark-theme .bn-item.active { color: #60A5FA; }

  /* ── Page Header — Apple-spacious ── */
  .page-header { flex-direction: column; gap: 6px; margin-bottom: 16px; }
  .page-header > div:first-child { width: 100%; }
  .page-title { font-size: 20px; letter-spacing: -0.3px; }
  .page-subtitle { font-size: 12.5px; margin-top: 2px; color: var(--text-muted); line-height: 1.4; }
  .header-date { font-size: 11px; margin-top: 4px; }

  /* ── KPI Cards — Apple premium scaled ── */
  .kpi-grid { grid-template-columns: 1fr 1fr; gap: 10px; }
  .kpi-card { padding: 18px 16px 16px; gap: 12px; transform: translateY(-4px); border-radius: 18px; }
  .kpi-card:hover { transform: translateY(-6px); }
  .kp-glow { width: 80px; height: 80px; top: -20%; right: -20%; animation: none; opacity: 0.35; }
  .kp-icon-wrap { width: 42px; height: 42px; transform: scale(1); }
  .kpi-card:hover .kp-icon-wrap { transform: scale(1.12) rotate(-5deg); }
  .kp-icon-wrap::after { inset: -6px; }
  .kpi-card:hover .kp-icon-wrap::after { inset: -10px; }
  .kpi-card::before { animation: none; background-position: 100% 0; }
  .kp-value { font-size: 18px; letter-spacing: -0.4px; }
  .kp-label { font-size: 9px; }
  .kp-badge { font-size: 8.5px; padding: 3px 8px; margin-top: 4px; }
  .kp-hint { font-size: 9.5px; }
  .wallet-kpi-actions { gap: 4px; margin-top: 6px; }
  .wallet-inline-btn { font-size: 9.5px; padding: 5px 10px; }

  /* ── Finance Summary ── */
  .finance-summary-row { grid-template-columns: 1fr 1fr; gap: 10px; }
  .fin-summary-card { padding: 14px 12px; gap: 10px; }
  .fin-sum-icon-wrap { width: 34px; height: 34px; }
  .fin-sum-value { font-size: 16px; }
  .fin-sum-label { font-size: 9px; }

  /* ── Utility Cards — Apple premium scaled ── */
  .util-premium-row { grid-template-columns: 1fr; gap: 10px; }
  .util-premium-inner { padding: 16px; gap: 12px; }
  .util-premium-left { gap: 12px; }
  .util-icon-ring { width: 40px; height: 40px; border-radius: 12px; }
  .util-premium-label { font-size: 10px; }
  .util-premium-conso { font-size: 18px; }
  .util-premium-conso small { font-size: 12px; }
  .util-premium-cost { font-size: 12px; }
  .util-premium-period { font-size: 10px; }
  .util-premium-badge { font-size: 9px; padding: 2px 9px; }
  .util-premium-bar-track { margin: 0 16px 14px; }

  /* ── Month Selector ── */
  .rent-payment-selector-card { padding: 16px; }
  .month-selector-grid { grid-template-columns: repeat(3, 1fr); gap: 8px; }
  .mc-card { padding: 12px 8px 10px; gap: 6px; border-radius: 10px; }
  .mc-label { font-size: 11.5px; }
  .mc-amount { font-size: 12px; }
  .mc-badge { font-size: 7.5px; padding: 1px 4px; }
  .mc-penalty-amount { font-size: 0.6rem; }
  .year-header { padding: 6px 10px; }
  .year-badge { font-size: 13px; }
  .year-paid-count { font-size: 11px; }
  .rent-selection-summary { padding: 14px 16px; flex-direction: column; gap: 10px; font-size: 12.5px; }
  .summary-details p { font-size: 12px; }
  .total-rent-to-pay { font-size: 12.5px; }

  /* ── Property Card ── */
  .property-image-wrap { width: 100%; height: 140px; }
  .premium-image-mockup { min-height: 140px; }
  .property-details { padding: 14px; }
  .property-name { font-size: 14px; }
  .property-specs-grid { grid-template-columns: repeat(2, 1fr); gap: 6px; padding: 8px; }
  .bail-details-grid { gap: 12px; }
  .bail-info-card { padding: 18px; }
  .documents-card { padding: 18px; }
  .contract-fee-card { padding: 16px; }
  .contract-fee-row { flex-direction: column; gap: 12px; }
  .overview-lower-grid { gap: 14px; }
  .quick-actions-card { padding: 16px; }
  .wallet-tx-card { padding: 16px; }
  .tx-item { padding: 8px 10px; }
  .transfer-widget-card { margin-bottom: 16px; }
  .tw-header { padding: 14px 16px; }
  .tw-body { padding: 16px; gap: 12px; }
  .equipment-tags { gap: 3px; }
  .eq-tag { font-size: 10px; padding: 1px 5px; }

  /* ── Support / Chat ── */
  .tickets-panel { max-height: none; }
  .chat-panel { max-height: 60vh; border-radius: 12px; }
  .chat-header { padding: 12px 14px; }
  .chat-messages { padding: 12px 14px; max-height: 35vh; }
  .chat-input-row { padding: 8px 10px; gap: 6px; }
  .chat-input { font-size: 12px; padding: 8px 10px; }

  /* ── Tables ── */
  .premium-table th { padding: 8px 10px; font-size: 9.5px; }
  .premium-table td { padding: 8px 10px; font-size: 11.5px; }
  .action-buttons { flex-wrap: wrap; gap: 3px; }
  .action-btn { font-size: 9.5px; padding: 4px 8px; }

  /* ── Profile — Apple iOS 18 premium ── */
  .profile-card, .security-card, .notif-card { padding: 22px; border-radius: 18px; }
  .form-grid, .form-grid-2 { gap: 14px; }
  .form-actions { padding-top: 18px; margin-top: 16px; }
  .security-section { margin-bottom: 20px; padding-bottom: 20px; }
  .tfa-header { flex-direction: column; gap: 8px; }
  .tfa-status-badge { align-self: flex-start; }
  .login-log-row { padding: 12px 14px; border-radius: 11px; }
  .notif-matrix-table th { padding: 11px 12px; font-size: 9.5px; }
  .notif-matrix-table td { padding: 12px 12px; font-size: 12px; }
  .section-subtitle { font-size: 13.5px; }
  .form-group label { font-size: 12.5px; }

  /* ── Receipts ── */
  .receipts-grid { grid-template-columns: 1fr; gap: 10px; }
  .rr-months-grid { grid-template-columns: 1fr 1fr; }
  .rr-meta-row { grid-template-columns: 1fr; gap: 6px; }

  /* ── Old Contracts — Apple premium ── */
  .old-contract-card { padding: 16px; }
  .old-contract-top { flex-direction: column; gap: 8px; }
  .old-contract-icon { width: 40px; height: 40px; }
  .old-contract-title { font-size: 14px; }
  .old-contract-period { flex-direction: row; align-items: center; justify-content: flex-start; gap: 8px; flex-wrap: wrap; }
  .old-contract-details { grid-template-columns: repeat(2, 1fr); }
  .old-contract-details .oc-detail-item { padding: 10px 12px; border-right: 1px solid var(--border-input); }
  .old-contract-details .oc-detail-item:nth-child(2n) { border-right: none; }
  .old-contract-details .oc-detail-item:last-child { border-right: none; }
  .old-contract-actions { justify-content: stretch; }
  .old-contract-actions .btn-secondary.small,
  .old-contract-actions .btn-primary.small { flex: 1; justify-content: center; }
  .oc-date-badge { font-size: 10px; padding: 3px 8px; }

  /* ── Transfer Widget ── */
  .tw-header { padding: 12px 14px; flex-wrap: wrap; gap: 8px; }
  .tw-body { padding: 14px; }
  .tw-btn { font-size: 12px; padding: 10px 14px; }

  /* ── Penalty Banner ── */
  .penalty-banner { padding: 12px 14px; gap: 10px; }
  .penalty-banner-details { font-size: 11px; flex-direction: column; align-items: flex-start; gap: 2px; }
  .penalty-sep { display: none; }
  .penalty-banner-scale { flex-wrap: wrap; }

  /* ── Modals — always fully visible, centered, no clipping ── */
  .payment-modal-overlay { padding: 12px; align-items: center; }
  .payment-modal-card { max-width: 100%; border-radius: 20px; margin: 0 auto; }
  .payment-modal-header { padding: 14px 16px 10px; gap: 10px; }
  .payment-modal-body { padding: 10px 14px; gap: 8px; }
  .payment-modal-footer { padding: 10px 14px 14px; flex-direction: column; gap: 6px; }
  .payment-modal-footer .btn-secondary,
  .payment-modal-footer .pm-pay-btn { width: 100%; }
  .card-switch-stage { max-width: 100%; min-height: auto; display: flex; align-items: center; justify-content: center; }
  .card-switch-stage > * { position: relative; top: auto; left: auto; transform: none !important; width: 100%; }
  .card-switch-enter-from,
  .card-switch-enter-to,
  .card-switch-leave-to { transform: none !important; }
  .card-switch-stage > .glass-card:hover,
  .card-switch-stage > .glass-card:active { transform: none !important; box-shadow: none !important; }
  .card-switch-enter-active,
  .card-switch-leave-active { transition: opacity 0.25s ease, transform 0.25s ease; }
  .card-switch-enter-from { opacity: 0; transform: translateY(10px) !important; }
  .card-switch-leave-to { opacity: 0; transform: translateY(-8px) !important; }
  .rent-pin-modal { padding: 24px 18px 20px; border-radius: 20px; max-height: 85vh; overflow-y: auto; }
  .password-gate-modal { padding: 24px 18px 20px; max-height: 85vh; overflow-y: auto; }
  .recharge-modal { max-width: 100%; border-radius: 20px; max-height: 90vh; overflow-y: auto; }
  .modal-box { border-radius: 20px; max-height: 85vh; }
  .modal-overlay { padding: 12px; align-items: center; }
  .modal-box > *:not(.modal-header):not(.modal-close) { padding-left: 18px; padding-right: 18px; }
  .rm-header { padding: 12px 16px 8px; flex-wrap: wrap; gap: 6px; }
  .rm-tabs { grid-template-columns: repeat(2, 1fr); gap: 6px; padding: 4px 12px; }
  .rm-body { padding: 12px 16px; }
  .rm-row-2 { grid-template-columns: 1fr 1fr; }
  .pm-summary-row.pm-total .pms-value.total { font-size: 15px; }
  .pm-months-list { gap: 4px; }
  .pm-month-row { padding: 6px 8px; font-size: 12px; }
  .logout-modal { padding: 28px 20px 22px; }
  .invoice-detail-modal { max-width: 100%; }

  /* ── Filter bar — clean & minimal ── */
  .filter-bar { flex-direction: column; align-items: stretch; gap: 8px; padding: 12px 14px; border-radius: 14px; }
  .filter-tabs { overflow-x: auto; gap: 4px; -webkit-overflow-scrolling: touch; }
  .filter-tab { font-size: 10.5px; padding: 4px 8px; white-space: nowrap; }
  .search-input { font-size: 13px; padding: 10px 12px; border-radius: 10px; }

  /* ── 2FA ── */
  .tfa-codes-grid { grid-template-columns: 1fr; }

  /* ── Notification matrix ── */
  .notif-matrix-table th,
  .notif-matrix-table td { padding: 8px 6px; font-size: 10px; }

  /* ── Methods Grid ── */
  .methods-grid { grid-template-columns: 1fr; }
}

/* ══════════════════════════════════════════════════════
   BREAKPOINT: ≤ 480px — Small phones
   ══════════════════════════════════════════════════════ */
@media (max-width: 480px) {
  .page-panel { padding: 10px 8px; }
  .page-title { font-size: 17px; letter-spacing: -0.4px; }
  .page-subtitle { font-size: 11.5px; }
  .mobile-header { padding: 10px 12px; }
  .mobile-title { font-size: 14px; }
  .burger-btn { width: 36px; height: 36px; }
  .mobile-avatar img { width: 30px; height: 30px; }
  .bottom-nav { height: 56px; }
  .bn-item { font-size: 9.5px; min-height: 44px; }
  .bn-item svg { width: 20px; height: 20px; }
  .bn-item::before { width: 24px; height: 2.5px; }

  .kpi-grid { grid-template-columns: 1fr 1fr; gap: 8px; }
  .kpi-card { padding: 14px 12px 12px; gap: 10px; border-radius: 16px; }
  .kpi-card::before { display: none; }
  .kp-glow { display: none; }
  .kp-icon-wrap { width: 36px; height: 36px; }
  .kpi-card:hover .kp-icon-wrap { transform: none; }
  .kp-icon-wrap::after { display: none; }
  .kp-value { font-size: 15px; }
  .kp-label { font-size: 8px; letter-spacing: 0.5px; }
  .kp-badge { font-size: 7.5px; padding: 2px 6px; }
  .kp-hint { font-size: 8.5px; }
  .wallet-kpi-actions { flex-direction: column; gap: 3px; }
  .wallet-inline-btn { font-size: 9px; padding: 4px 8px; width: 100%; justify-content: center; }

  .finance-summary-row { grid-template-columns: 1fr 1fr; gap: 8px; }
  .fin-summary-card { padding: 10px 8px; gap: 8px; }
  .fin-sum-icon-wrap { width: 30px; height: 30px; }
  .fin-sum-value { font-size: 14px; }
  .fin-sum-label { font-size: 8px; }

  /* ── Utilities — premium compact ── */
  .util-premium-row { gap: 10px; }
  .util-premium-inner { padding: 14px 14px 12px; gap: 10px; }
  .util-premium-left { gap: 10px; align-items: flex-start; }
  .util-premium-info { align-items: flex-start; text-align: left; }
  .util-icon-ring { width: 36px; height: 36px; border-radius: 11px; }
  .util-premium-label { font-size: 9px; text-align: left; }
  .util-premium-conso { font-size: 16px; text-align: left; }
  .util-premium-conso small { font-size: 11px; }
  .util-premium-cost { font-size: 11px; text-align: left; }
  .util-premium-right { gap: 3px; }
  .util-premium-period { font-size: 9px; }
  .util-premium-badge { font-size: 8px; padding: 2px 8px; border-radius: 6px; }
  .util-premium-bar-track { margin: 0 14px 12px; }

  .month-selector-grid { grid-template-columns: repeat(2, 1fr); gap: 6px; }
  .mc-card { padding: 10px 6px 8px; }
  .mc-label { font-size: 10.5px; }
  .mc-amount { font-size: 11px; }
  .mc-badge { font-size: 7px; padding: 1px 3px; }
  .rent-selection-summary { padding: 12px 14px; }
  .year-header { padding: 4px 8px; }
  .year-badge { font-size: 12px; }

  .property-image-wrap { height: 110px; }
  .premium-image-mockup { min-height: 110px; }
  .property-details { padding: 10px; }
  .property-name { font-size: 13px; }
  .property-specs-grid { grid-template-columns: 1fr 1fr; gap: 4px; padding: 6px; }
  .spec-value { font-size: 10px; }
  .bail-row { font-size: 11px; padding: 8px 10px; }
  .document-row { font-size: 11px; padding: 8px 10px; }

  .chat-panel { max-height: 55vh; border-radius: 10px; }
  .chat-messages { max-height: 30vh; padding: 10px 12px; }
  .msg-bubble { max-width: 88%; }
  .bubble-tenant .msg-text,
  .bubble-manager .msg-text { font-size: 11.5px; padding: 7px 10px; }

  .premium-table th { padding: 6px 8px; font-size: 8.5px; letter-spacing: 0.2px; }
  .premium-table td { padding: 6px 8px; font-size: 10.5px; }
  .action-btn { font-size: 8.5px; padding: 3px 6px; }

  .old-contract-title { font-size: 13px; }
  .profile-card, .security-card, .notif-card { padding: 18px; border-radius: 16px; }
  .avatar-large { width: 88px; height: 88px; }
  .form-input { font-size: 16px; padding: 14px 14px; border-radius: 12px; }
  .btn-primary.small, .btn-secondary.small, .btn-danger.small { padding: 12px 16px; font-size: 13px; }
  .card-title { font-size: 15px; margin-bottom: 16px; }
  .section-subtitle { font-size: 13px; }
  .section-desc { font-size: 11.5px; }
  .old-contract-top { flex-direction: column; gap: 6px; }
  .old-contract-icon { width: 36px; height: 36px; }
  .old-contract-details { grid-template-columns: 1fr 1fr; }
  .old-contract-details .oc-detail-item { padding: 8px 10px; border-right: 1px solid var(--border-input); }
  .old-contract-details .oc-detail-item:nth-child(2n) { border-right: none; }
  .old-contract-details .oc-detail-item:last-child { border-right: none; }
  .old-contract-details .oc-d-value { font-size: 12px; }
  .old-contract-actions { flex-direction: column; gap: 6px; }
  .old-contract-actions .btn-secondary.small,
  .old-contract-actions .btn-primary.small { width: 100%; justify-content: center; }
  .oc-date-badge { white-space: normal; font-size: 9px; padding: 2px 6px; }
  /* Old contract detail modal — compact receipt on small screens */
  .invoice-detail-modal .rcpt-body { padding: 8px 10px; }
  .invoice-detail-modal .rcpt-row { padding: 3px 8px; font-size: 9px; }
  .invoice-detail-modal .rcpt-hdr { padding: 8px 10px; }
  .invoice-detail-modal .rcpt-brand { font-size: 12px; }
  .invoice-detail-modal .rcpt-hdr-bar { font-size: 7px; flex-wrap: wrap; gap: 4px; }
  .invoice-detail-modal .rcpt-total-inner { padding: 4px 8px; }
  .invoice-detail-modal .rcpt-total-value { font-size: 11px; }
  .invoice-detail-modal .rcpt-ftr { padding: 4px 10px; font-size: 6.5px; }

  .receipt-card-header { padding: 12px 14px 10px; }
  .receipt-amount { font-size: 13px; }

  .penalty-banner { padding: 10px 12px; }
  .penalty-banner-title { font-size: 12px; }
  .penalty-banner-details { font-size: 10px; }

  .payment-modal-header { padding: 12px 12px 8px; gap: 8px; }
  .payment-modal-body { padding: 8px 12px; gap: 6px; }
  .payment-modal-footer { padding: 8px 12px 12px; gap: 6px; }
  .pm-title { font-size: 14px; }
  .pm-month-label { font-size: 12px; }
  .pm-month-amount { font-size: 12px; }
  .pm-summary-row { font-size: 12px; }
  .pm-header-icon { width: 36px; height: 36px; }
  .pm-wallet-info { padding: 10px 12px; gap: 10px; border-radius: 10px; }
  .pm-invoice-row { padding: 5px 8px; font-size: 11px; }
  .pm-summary-row.pm-total .pms-value.total { font-size: 16px; }
  .pm-wi-value { font-size: 12px; }

  .card-switch-stage > * { width: 100%; }

  .tfa-stepper { gap: 4px; }
  .tfa-step { width: 16px; height: 16px; font-size: 8px; }

  .rm-tabs { grid-template-columns: repeat(2, 1fr); gap: 4px; padding: 4px 8px; }
  .rm-tab { font-size: 9px; padding: 5px 2px; }

  .quick-action-btn { padding: 10px 12px; gap: 10px; }
  .qa-icon { width: 34px; height: 34px; }
  .qa-text strong { font-size: 12px; }
  .qa-text small { font-size: 9.5px; }

  /* ── Overview — extra premium refinements ── */
  .transfer-widget-card { margin-bottom: 14px; }
  .tw-header { padding: 12px 14px; }
  .tw-body { padding: 14px; gap: 12px; }
  .tw-btn { font-size: 12px; padding: 10px 16px; }
  .wallet-tx-card { padding: 16px; }
  .tx-item { padding: 8px 10px; gap: 8px; }
  .tx-name { font-size: 11px; }
  .contract-fee-row { flex-direction: column; gap: 12px; }
  .contract-fee-actions { flex-direction: column; gap: 6px; }
  .contract-fee-actions .btn-primary,
  .contract-fee-actions .btn-secondary { width: 100%; justify-content: center; }
  .property-specs-grid { grid-template-columns: 1fr 1fr; }
  .equipment-tags { gap: 3px; }
  .eq-tag { font-size: 10px; }
  .bail-info-card { padding: 18px; }
  .documents-card { padding: 18px; }
}

/* ══════════════════════════════════════════════════════
   BREAKPOINT: ≤ 380px — Very small devices
   ══════════════════════════════════════════════════════ */
@media (max-width: 380px) {
  .page-panel { padding: 8px 6px; }
  .page-title { font-size: 15px; letter-spacing: -0.4px; }
  .mobile-title { font-size: 13px; }
  .bottom-nav { height: 50px; }
  .bn-item { font-size: 8.5px; min-height: 40px; }
  .bn-item svg { width: 18px; height: 18px; }
  .bn-item::before { width: 20px; height: 2px; }

  .kpi-grid { gap: 6px; }
  .kpi-card { padding: 10px 8px 10px; gap: 8px; }
  .kp-icon-wrap { width: 30px; height: 30px; }
  .kp-value { font-size: 13px; }
  .kp-label { font-size: 7px; }

  .month-selector-grid { gap: 4px; }
  .mc-card { padding: 8px 4px 6px; }
  .mc-label { font-size: 9.5px; }
  .mc-amount { font-size: 10px; }

  .finance-summary-row { gap: 6px; }
  .fin-summary-card { padding: 8px 6px; }
  .fin-sum-value { font-size: 12px; }

  .util-premium-row { gap: 8px; }
  .util-premium-inner { padding: 12px; flex-direction: column; gap: 6px; align-items: flex-start; }
  .util-premium-left { gap: 8px; align-items: flex-start; }
  .util-premium-info { align-items: flex-start; text-align: left; }
  .util-icon-ring { width: 32px; height: 32px; border-radius: 10px; }
  .util-premium-label { font-size: 8px; text-align: left; }
  .util-premium-conso { font-size: 14px; text-align: left; }
  .util-premium-conso small { font-size: 10px; }
  .util-premium-cost { font-size: 10px; text-align: left; }
  .util-premium-right { align-items: flex-start; gap: 2px; }
  .util-premium-period { font-size: 8px; }
  .util-premium-badge { font-size: 7px; padding: 1px 6px; border-radius: 5px; }
  .util-premium-bar-track { margin: 0 12px 10px; height: 2px; }

  .pm-month-label { font-size: 11px; }
  .pm-month-amount { font-size: 11px; }
  .pm-summary-row { font-size: 11px; }
  .pm-header-icon { width: 32px; height: 32px; }
  .pm-wallet-info { padding: 8px 10px; gap: 8px; border-radius: 8px; }
  .payment-modal-body { padding: 6px 10px; gap: 4px; }
  .pm-invoice-row { padding: 4px 6px; font-size: 10px; }
  .pm-summary-row.pm-total .pms-value.total { font-size: 14px; }
  .payment-modal-header { padding: 10px 10px 6px; }

  .premium-table th { padding: 4px 6px; font-size: 7.5px; }
  .premium-table td { padding: 4px 6px; font-size: 9.5px; }

  .chat-messages { max-height: 25vh; }
  .msg-bubble { max-width: 92%; }
  .bubble-tenant .msg-text,
  .bubble-manager .msg-text { font-size: 10.5px; padding: 6px 8px; }

  /* ── Profile — ultra-compact for very small devices ── */
  .profile-layout { gap: 10px; }
  .profile-card, .security-card, .notif-card { padding: 14px; border-radius: 14px; }
  .card-title { font-size: 14px; margin-bottom: 14px; }
  .avatar-upload-section { gap: 12px; margin-bottom: 18px; }
  .avatar-large { width: 72px; height: 72px; border-width: 2.5px; }
  .avatar-edit-btn { width: 24px; height: 24px; right: 0; bottom: 0; }
  .avatar-delete-btn { width: 24px; height: 24px; left: 0; bottom: 0; }
  .avatar-name { font-size: 14px; }
  .avatar-email { font-size: 12px; }
  .form-grid, .form-grid-2 { gap: 10px; }
  .form-group { gap: 4px; margin-bottom: 10px; }
  .form-input { padding: 12px 12px; font-size: 16px; border-radius: 10px; }
  .form-group label { font-size: 13px; }
  .theme-section-divider { margin-top: 14px; padding-top: 12px; }
  .theme-toggle-group { margin-top: 8px; border-radius: 9px; }
  .theme-opt-btn { padding: 10px 8px; font-size: 11px; }
  .theme-opt-btn svg { width: 13px; height: 13px; }
  .lang-section-divider { margin-top: 12px; padding-top: 12px; }
  .lang-btn-row { gap: 6px; }
  .lang-btn { padding: 12px 14px; border-radius: 11px; }
  .lang-flag { font-size: 20px; }
  .lang-name { font-size: 13px; }
  .form-actions { padding-top: 14px; margin-top: 12px; }
  .btn-save-profile { padding: 13px 16px; font-size: 13px; }
  .section-subtitle { font-size: 12.5px; }
  .section-desc { font-size: 11px; }
  .btn-password-submit { margin-top: 10px; padding: 12px 14px; }
  .security-section { margin-bottom: 16px; padding-bottom: 16px; }
  .tfa-actions { margin-top: 10px; gap: 6px; }
  .tfa-status-badge { font-size: 10px; padding: 4px 12px; }
  .login-logs-list { gap: 5px; margin-top: 8px; }
  .login-log-row { padding: 10px 10px; gap: 8px; border-radius: 10px; }
  .log-device-icon { width: 28px; height: 28px; border-radius: 7px; }
  .log-device-desc { font-size: 12px; }
  .log-meta-txt { font-size: 9.5px; }
  .log-status-tag { font-size: 8.5px; padding: 2px 8px; }
  .notif-matrix-wrapper { margin-top: 10px; border-radius: 10px; }
  .notif-matrix-table { min-width: 0; }
  .notif-matrix-table th { padding: 8px 6px; font-size: 8px; }
  .notif-matrix-table td { padding: 8px 6px; font-size: 9.5px; }
  .pref-label { font-size: 11px; }
  .pref-desc { font-size: 9.5px; }
  .custom-switch-label { width: 30px; height: 16px; }
  .custom-switch-slider:before { height: 10px; width: 10px; left: 3px; bottom: 3px; }
  input:checked + .custom-switch-slider:before { transform: translateX(14px); }

  /* ── Overview — Vue d'ensemble ultra-premium compact ── */
  .kpi-grid { gap: 6px; }
  .kpi-card { padding: 10px 8px 10px; gap: 8px; border-radius: 14px; }
  .kpi-card::before { display: none; }
  .kp-glow { display: none; }
  .kp-icon-wrap { width: 30px; height: 30px; }
  .kp-icon-wrap::after { display: none; }
  .kp-value { font-size: 13px; letter-spacing: -0.3px; }
  .kp-label { font-size: 7px; letter-spacing: 0.4px; }
  .kp-badge { font-size: 7px; padding: 2px 6px; margin-top: 3px; }
  .kp-hint { font-size: 8px; }
  .wallet-kpi-actions { gap: 3px; margin-top: 5px; }
  .wallet-inline-btn { font-size: 8px; padding: 6px 8px; }
  .transfer-widget-card { margin-bottom: 12px; }
  .tw-header { padding: 10px 12px; flex-wrap: wrap; gap: 8px; }
  .tw-header-left { gap: 8px; }
  .tw-icon { width: 30px; height: 30px; }
  .tw-title { font-size: 13px; }
  .tw-sub { font-size: 10px; }
  .tw-body { padding: 12px; gap: 10px; }
  .tw-btn { font-size: 12px; padding: 10px 14px; }
  .overview-lower-grid { gap: 10px; }
  .quick-actions-card { padding: 14px; }
  .quick-actions-list { gap: 6px; margin-top: 8px; }
  .quick-action-btn { padding: 10px 10px; gap: 8px; border-radius: 10px; }
  .qa-icon { width: 30px; height: 30px; }
  .qa-text strong { font-size: 11px; }
  .qa-text small { font-size: 9px; }
  .wallet-tx-card { padding: 14px; }
  .wallet-tx-list { gap: 6px; margin-top: 8px; }
  .tx-item { padding: 8px 8px; gap: 8px; border-radius: 8px; }
  .tx-icon { width: 28px; height: 28px; }
  .tx-name { font-size: 10px; }
  .tx-meta { font-size: 8.5px; }
  .tx-amount { font-size: 10px; }
  .property-card { border-radius: 14px; }
  .property-image-wrap { height: 90px; }
  .premium-image-mockup { min-height: 90px; }
  .property-details { padding: 10px; gap: 8px; }
  .property-name { font-size: 12px; }
  .property-address { font-size: 10px; }
  .property-status-badge { font-size: 8px; padding: 2px 6px; }
  .property-specs-grid { grid-template-columns: 1fr 1fr; gap: 4px; padding: 6px; }
  .spec-value { font-size: 9px; }
  .spec-label { font-size: 8px; }
  .equipment-section { gap: 4px; }
  .eq-tag { font-size: 9px; padding: 1px 5px; }
  .bail-details-grid { gap: 10px; }
  .bail-info-card { padding: 14px; }
  .bail-row { font-size: 10px; padding: 6px 8px; gap: 8px; border-radius: 8px; }
  .bail-icon-ring { width: 24px; height: 24px; }
  .bail-key { font-size: 10px; }
  .bail-value { font-size: 10px; }
  .documents-card { padding: 14px; }
  .document-row { font-size: 10px; padding: 6px 8px; gap: 8px; border-radius: 8px; }
  .doc-icon-wrap { width: 28px; height: 28px; }
  .doc-name { font-size: 10px; }
  .doc-meta { font-size: 8.5px; }
  .doc-btn { font-size: 9px; padding: 2px 5px; }
  .contract-fee-card { padding: 14px; border-radius: 14px; }
  .contract-fee-row { flex-direction: column; gap: 10px; }
  .contract-fee-icon-ring { width: 34px; height: 34px; }
  .contract-fee-info .card-title { font-size: 12px; margin-bottom: 4px; }
  .card-subtitle-text { font-size: 10px; }
  .status-pill { font-size: 9px; padding: 3px 8px; }
  .contract-fee-actions { flex-direction: column; gap: 6px; }
  .contract-fee-actions .btn-primary,
  .contract-fee-actions .btn-secondary { width: 100%; justify-content: center; padding: 10px 14px; font-size: 11px; }

  /* ── Old Contracts — compact for very small devices ── */
  .old-contract-card { padding: 12px; border-radius: 14px; }
  .old-contract-title { font-size: 12px; }
  .old-contract-address { font-size: 11px; }
  .old-contract-period { gap: 4px; }
  .old-contract-details { grid-template-columns: 1fr; }
  .old-contract-details .oc-detail-item { padding: 6px 8px; border-right: none; }
  .old-contract-details .oc-d-value { font-size: 11px; }
  .old-contract-details .oc-d-label { font-size: 8.5px; }
  .oc-date-badge { font-size: 8px; padding: 2px 5px; }
  .old-contract-actions .btn-secondary.small,
  .old-contract-actions .btn-primary.small { font-size: 11px; padding: 10px 12px; }
}

/* ══════════════════════════════════════════════════════
   TOUCH DEVICES — disable hover, boost active states
   Inspired by Apple iOS design principles
   ══════════════════════════════════════════════════════ */
@media (hover: none) and (pointer: coarse) {

  /* ── Disable all card hover transforms & shadow lifts ── */
  .glass-card:hover { transform: none !important; box-shadow: none !important; }
  .kpi-card:hover { transform: translateY(-2px) !important; }
  .kpi-card.kpi-card:hover { transform: translateY(-2px) !important; box-shadow: 0 8px 28px rgba(15,23,42,0.06) !important; }
  .dark-theme .kpi-card.kpi-card:hover { box-shadow: 0 8px 28px rgba(0,0,0,0.2) !important; }

  /* ── KPI hover effects disabled ── */
  .kpi-card:hover .kp-icon-wrap { transform: none !important; box-shadow: none !important; }
  .kpi-card:hover .kp-icon-wrap::after { inset: -8px !important; opacity: 0.2 !important; border-width: 2px !important; animation: iconRing 2s ease-in-out infinite !important; }
  .kpi-card:hover .kp-badge { transform: none !important; box-shadow: none !important; }
  .kpi-card:hover .kp-glow { animation: glowIntense 2.2s ease-in-out infinite !important; }
  .kpi-card:hover .kp-body::after { opacity: 0.35 !important; transform: none !important; }
  .kpi-card::before { animation-duration: 5s !important; }
  .kpi-card:hover::before { animation-duration: 5s !important; opacity: 1 !important; }
  .kpi-card:hover .kp-value-wallet { animation-duration: 4s !important; }

  /* ── Quick actions ── */
  .quick-action-btn:hover { transform: none !important; }
  .quick-action-btn.primary:hover { transform: none !important; box-shadow: 0 4px 14px rgba(37,99,235,0.2) !important; }

  /* ── Month cards ── */
  .mc-card:hover:not(.mc-paid) { transform: none !important; border-color: #F1F5F9 !important; box-shadow: none !important; }
  .mc-card:hover:not(.mc-paid)::after { opacity: 0 !important; }
  .mc-card:hover:not(.mc-paid)::before { background: linear-gradient(90deg, #E2E8F0, #CBD5E1) !important; }
  .dark-theme .mc-card:hover:not(.mc-paid) { border-color: #334155 !important; }

  /* ── Items hover ── */
  .rp-item:hover, .receipt-card:hover, .old-contract-card:hover, .ticket-card:hover { transform: none !important; box-shadow: none !important; }
  .tx-item:hover { border-color: var(--border-input) !important; }
  .document-row:hover { border-color: var(--border-input) !important; }
  .bail-row:hover { border-color: var(--border-input) !important; }

  /* ── Sidebar & Nav ── */
  .nav-item:hover { background: transparent !important; color: var(--text-muted) !important; }
  .nav-item.active:hover { background: var(--bg-active-tab) !important; color: var(--text-active-tab) !important; }

  /* ── Buttons ── */
  .btn-primary:hover { transform: none !important; box-shadow: none !important; }
  .btn-secondary:hover { background: var(--bg-input) !important; border-color: var(--border-input) !important; }
  .wallet-inline-btn:hover { transform: none !important; box-shadow: 0 3px 12px rgba(99,102,241,0.20) !important; }
  .wallet-inline-btn.secondary:hover { background: var(--gray-100) !important; }
  .dark-theme .wallet-inline-btn.secondary:hover { background: rgba(148,163,184,0.12) !important; }
  .desktop-icon-btn:hover { background: transparent !important; }
  .desktop-user-badge:hover { background: transparent !important; }

  /* ── Wallet sidebar ── */
  .sidebar-wallet-card:hover { transform: none !important; border-color: rgba(99,102,241,0.15) !important; box-shadow: 0 4px 24px rgba(15,23,42,0.12) !important; }
  .dark-theme .sidebar-wallet-card:hover { box-shadow: 0 8px 32px rgba(0,0,0,0.4) !important; }

  /* ── Transfer widget ── */
  .tw-btn:hover:not(:disabled) { transform: none !important; box-shadow: none !important; }

  /* ── Filter bar ── */
  .filter-tab:hover { background: transparent !important; color: var(--text-muted) !important; }

  /* ── Language buttons ── */
  .lang-btn:hover { border-color: var(--border-input) !important; background: var(--bg-input) !important; }
  .lang-btn.active:hover { border-color: #2563EB !important; background: #EFF6FF !important; }
  .dark-theme .lang-btn.active:hover { border-color: #60A5FA !important; background: rgba(96,165,250,0.1) !important; }

  /* ── Active state feedback (Apple-like subtle scale) ── */
  .btn-primary:active { transform: scale(0.97) !important; }
  .btn-secondary:active { transform: scale(0.97) !important; }
  .wallet-inline-btn:active { transform: scale(0.96) !important; }
  .quick-action-btn:active { transform: scale(0.98) !important; }
  .nav-item:active { transform: scale(0.97); }
  .bn-item:active { transform: scale(0.95); }
  .mc-card:active:not(.mc-paid) { transform: scale(0.97) !important; }
  .ticket-card:active { transform: scale(0.98) !important; }
  .sidebar-wallet-card:active { transform: scale(0.99) !important; }
  .desktop-icon-btn:active { transform: scale(0.96) !important; }

  /* ── No overscroll on modals ── */
  .payment-modal-card,
  .rent-pin-modal,
  .password-gate-modal,
  .recharge-modal,
  .modal-box { -webkit-overflow-scrolling: touch; }
}

/* ══════════════════════════════════════════════════════
   SAFARI FIXES — overflow prevention & elastic scroll
   ══════════════════════════════════════════════════════ */
.dashboard-root {
  -webkit-overflow-scrolling: touch;
  overflow-x: hidden;
  max-width: 100vw;
}
.kpi-grid, .overview-lower-grid, .finance-summary-row,
.util-premium-row, .bail-details-grid, .support-layout,
.profile-layout, .receipts-grid, .month-selector-grid {
  max-width: 100%;
}
.kpi-card, .glass-card {
  max-width: 100%;
  word-break: break-word;
}
</style>