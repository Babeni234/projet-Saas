<template>
  <!-- ═══════════════════════════════════════════════════════════════
       TENANT DASHBOARD — Light Glassmorphism Ultra-Premium
       Vue.js 3 Composition API + Tailwind CSS
       Connecteur Inertia.js ready (props: auth, contracts, invoices, tickets)
  ═══════════════════════════════════════════════════════════════ -->
  <div class="dashboard-root" :class="{ 'sidebar-collapsed': sidebarCollapsed, 'mobile-open': mobileSidebarOpen }">

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
    <aside class="sidebar" :class="{ collapsed: sidebarCollapsed }">
      <!-- Logo & Brand -->
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
            <img :src="auth.user.avatar || defaultAvatar" :alt="auth.user.name" class="tenant-avatar"/>
            <span class="status-dot"></span>
          </div>
          <div class="tenant-meta">
            <p class="tenant-name">{{ auth.user.name }}</p>
            <p class="tenant-role">Locataire</p>
          </div>
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

      <!-- Bottom actions -->
      <div class="sidebar-footer">
        <button class="nav-item logout-btn" @click="handleLogout" :title="sidebarCollapsed ? 'Déconnexion' : ''">
          <span class="nav-icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>
          </span>
          <Transition name="fade-slide">
            <span v-if="!sidebarCollapsed" class="nav-label">Déconnexion</span>
          </Transition>
        </button>
      </div>
    </aside>

    <!-- ══════════════════════════════════════════════════
         MAIN CONTENT
    ══════════════════════════════════════════════════ -->
    <main class="main-content">

      <!-- Mobile Header -->
      <header class="mobile-header">
        <button class="burger-btn" @click="mobileSidebarOpen = true">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
        </button>
        <span class="mobile-title">{{ currentNavLabel }}</span>
        <div class="mobile-avatar">
          <img :src="auth.user.avatar || defaultAvatar" :alt="auth.user.name"/>
        </div>
      </header>

      <!-- Page Transition Wrapper -->
      <Transition name="page-transition" mode="out-in">
        <div :key="activeTab" class="page-panel">

          <!-- ═══════════════════════════════
               A. VUE D'ENSEMBLE
          ═══════════════════════════════ -->
          <section v-if="activeTab === 'overview'" class="tab-section">
            <div class="page-header">
              <div>
                <h1 class="page-title">Vue d'ensemble</h1>
                <p class="page-subtitle">Bonjour, <strong>{{ auth.user.first_name || auth.user.name }}</strong> 👋 — Voici l'état de votre location</p>
              </div>
              <div class="header-date">
                <span>{{ formattedToday }}</span>
              </div>
            </div>

            <!-- KPI Cards -->
            <div class="kpi-grid">
              <div class="glass-card kpi-card" :class="kpiStatusClass(currentRentStatus)">
                <div class="kpi-icon-wrap">
                  <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="3"/><path d="M3 9h18M9 21V9"/></svg>
                </div>
                <div class="kpi-body">
                  <p class="kpi-label">Loyer du mois</p>
                  <p class="kpi-value">{{ formatCurrency(currentRent) }}</p>
                  <span class="kpi-badge" :class="'badge-' + currentRentStatus">
                    {{ rentStatusLabel(currentRentStatus) }}
                  </span>
                </div>
              </div>

              <div class="glass-card kpi-card">
                <div class="kpi-icon-wrap kpi-icon-amber">
                  <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
                </div>
                <div class="kpi-body">
                  <p class="kpi-label">Solde dû total</p>
                  <p class="kpi-value">{{ formatCurrency(totalDue) }}</p>
                  <span class="kpi-hint">{{ totalDue === 0 ? 'Aucun impayé 🎉' : 'À régulariser' }}</span>
                </div>
              </div>

              <div class="glass-card kpi-card">
                <div class="kpi-icon-wrap kpi-icon-emerald">
                  <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg>
                </div>
                <div class="kpi-body">
                  <p class="kpi-label">Prochaine échéance</p>
                  <p class="kpi-value">{{ nextDueDate }}</p>
                  <span class="kpi-hint">dans {{ daysUntilDue }} jours</span>
                </div>
              </div>

              <div class="glass-card kpi-card">
                <div class="kpi-icon-wrap kpi-icon-violet">
                  <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.5 10c-.83 0-1.5-.67-1.5-1.5v-5c0-.83.67-1.5 1.5-1.5s1.5.67 1.5 1.5v5c0 .83-.67 1.5-1.5 1.5z"/><path d="M20.5 10H19V8.5c0-.83.67-1.5 1.5-1.5s1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/><path d="M9.5 14c.83 0 1.5.67 1.5 1.5v5c0 .83-.67 1.5-1.5 1.5S8 21.33 8 20.5v-5c0-.83.67-1.5 1.5-1.5z"/><path d="M3.5 14H5v1.5c0 .83-.67 1.5-1.5 1.5S2 16.33 2 15.5 2.67 14 3.5 14z"/><path d="M14 14.5c0-.83.67-1.5 1.5-1.5h5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5h-5c-.83 0-1.5-.67-1.5-1.5z"/><path d="M15.5 19H14v1.5c0 .83.67 1.5 1.5 1.5s1.5-.67 1.5-1.5-.67-1.5-1.5-1.5z"/><path d="M10 9.5C10 8.67 9.33 8 8.5 8h-5C2.67 8 2 8.67 2 9.5S2.67 11 3.5 11h5c.83 0 1.5-.67 1.5-1.5z"/><path d="M8.5 5H10V3.5C10 2.67 9.33 2 8.5 2S7 2.67 7 3.5 7.67 5 8.5 5z"/></svg>
                </div>
                <div class="kpi-body">
                  <p class="kpi-label">Tickets ouverts</p>
                  <p class="kpi-value">{{ openTicketsCount }}</p>
                  <span class="kpi-hint">{{ openTicketsCount === 0 ? 'Tout va bien' : 'En cours de traitement' }}</span>
                </div>
              </div>
            </div>

            <!-- Chart + Quick Actions Row -->
            <div class="overview-lower-grid">
              <!-- Expense Chart -->
              <div class="glass-card chart-card">
                <div class="card-header-row">
                  <h3 class="card-title">Historique des dépenses</h3>
                  <div class="chart-legend">
                    <span class="legend-dot blue"></span><span>Loyer</span>
                    <span class="legend-dot amber" style="margin-left:12px"></span><span>Charges</span>
                  </div>
                </div>
                <div class="chart-wrapper">
                  <svg :viewBox="`0 0 ${chartW} ${chartH}`" class="expense-chart">
                    <!-- Grid lines -->
                    <g class="grid-lines">
                      <line v-for="(g, i) in 5" :key="'g'+i"
                        :x1="chartPad" :y1="chartPad + (i * (chartH - 2*chartPad)/4)"
                        :x2="chartW - chartPad" :y2="chartPad + (i * (chartH - 2*chartPad)/4)"
                        stroke="#E5E7EB" stroke-width="1" stroke-dasharray="4,4"/>
                    </g>
                    <!-- Y labels -->
                    <g class="y-labels">
                      <text v-for="(label, i) in chartYLabels" :key="'yl'+i"
                        :x="chartPad - 8" :y="chartPad + (i * (chartH - 2*chartPad)/4) + 4"
                        text-anchor="end" font-size="10" fill="#9CA3AF">{{ label }}</text>
                    </g>
                    <!-- Area fill rent -->
                    <defs>
                      <linearGradient id="rentGrad" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#2563EB" stop-opacity="0.15"/>
                        <stop offset="100%" stop-color="#2563EB" stop-opacity="0"/>
                      </linearGradient>
                      <linearGradient id="chargesGrad" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#F59E0B" stop-opacity="0.12"/>
                        <stop offset="100%" stop-color="#F59E0B" stop-opacity="0"/>
                      </linearGradient>
                    </defs>
                    <path :d="rentAreaPath" fill="url(#rentGrad)"/>
                    <path :d="chargesAreaPath" fill="url(#chargesGrad)"/>
                    <!-- Lines -->
                    <path :d="rentLinePath" fill="none" stroke="#2563EB" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path :d="chargesLinePath" fill="none" stroke="#F59E0B" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <!-- Dots -->
                    <circle v-for="(pt, i) in rentPoints" :key="'rp'+i"
                      :cx="pt.x" :cy="pt.y" r="4" fill="white" stroke="#2563EB" stroke-width="2.5"/>
                    <circle v-for="(pt, i) in chargesPoints" :key="'cp'+i"
                      :cx="pt.x" :cy="pt.y" r="4" fill="white" stroke="#F59E0B" stroke-width="2.5"/>
                    <!-- X labels -->
                    <g class="x-labels">
                      <text v-for="(m, i) in chartMonths" :key="'xl'+i"
                        :x="chartXforIndex(i)" :y="chartH - chartPad + 18"
                        text-anchor="middle" font-size="11" fill="#6B7280">{{ m }}</text>
                    </g>
                  </svg>
                </div>
              </div>

              <!-- Quick Actions -->
              <div class="glass-card quick-actions-card">
                <h3 class="card-title">Raccourcis rapides</h3>
                <div class="quick-actions-list">
                  <button class="quick-action-btn primary" @click="openPaymentModal">
                    <span class="qa-icon">
                      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                    </span>
                    <span class="qa-text">
                      <strong>Payer mon loyer</strong>
                      <small>Paiement sécurisé en ligne</small>
                    </span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                  </button>
                  <button class="quick-action-btn" @click="switchTab('support'); openNewTicket()">
                    <span class="qa-icon amber">
                      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    </span>
                    <span class="qa-text">
                      <strong>Déclarer un problème</strong>
                      <small>Ouvrir un ticket de support</small>
                    </span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                  </button>
                  <button class="quick-action-btn" @click="downloadLastReceipt">
                    <span class="qa-icon emerald">
                      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                    </span>
                    <span class="qa-text">
                      <strong>Dernière quittance</strong>
                      <small>Télécharger en PDF</small>
                    </span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                  </button>
                </div>
              </div>
            </div>
          </section>

          <!-- ═══════════════════════════════
               B. LOGEMENTS & CONTRATS
          ═══════════════════════════════ -->
          <section v-else-if="activeTab === 'contract'" class="tab-section">
            <div class="page-header">
              <div>
                <h1 class="page-title">Logements & Contrats de Bail</h1>
                <p class="page-subtitle">Détails de votre occupation et documents associés</p>
              </div>
            </div>

            <div v-for="contract in contracts" :key="contract.id" class="contract-block">
              <!-- Property Card -->
              <div class="glass-card property-card">
                <div class="property-image-wrap">
                  <img :src="contract.property.photo || '/img/property-placeholder.jpg'" :alt="contract.property.name" class="property-image"/>
                  <div class="property-image-overlay">
                    <span class="property-type-badge">{{ contract.property.type }}</span>
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
                    <div class="property-status-badge active">Occupé</div>
                  </div>

                  <div class="property-specs-grid">
                    <div class="spec-item" v-for="spec in contract.property.specs" :key="spec.label">
                      <span class="spec-label">{{ spec.label }}</span>
                      <span class="spec-value">{{ spec.value }}</span>
                    </div>
                  </div>

                  <div class="equipment-section">
                    <h4 class="section-subtitle">Équipements inclus</h4>
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
                    <div class="bail-row readonly-row">
                      <span class="bail-key">Type de contrat</span>
                      <span class="bail-value">{{ contract.type }}</span>
                      <span class="readonly-tag">Lecture seule</span>
                    </div>
                    <div class="bail-row readonly-row">
                      <span class="bail-key">Date de début</span>
                      <span class="bail-value">{{ formatDate(contract.start_date) }}</span>
                      <span class="readonly-tag">Lecture seule</span>
                    </div>
                    <div class="bail-row readonly-row">
                      <span class="bail-key">Date de fin</span>
                      <span class="bail-value">{{ formatDate(contract.end_date) }}</span>
                      <span class="readonly-tag">Lecture seule</span>
                    </div>
                    <div class="bail-row readonly-row">
                      <span class="bail-key">Loyer mensuel</span>
                      <span class="bail-value accent-blue">{{ formatCurrency(contract.rent) }}</span>
                      <span class="readonly-tag">Lecture seule</span>
                    </div>
                    <div class="bail-row readonly-row">
                      <span class="bail-key">Dépôt de garantie</span>
                      <span class="bail-value">{{ formatCurrency(contract.deposit) }}</span>
                      <span class="readonly-tag">Lecture seule</span>
                    </div>
                    <div class="bail-row readonly-row">
                      <span class="bail-key">Charges mensuelles</span>
                      <span class="bail-value">{{ formatCurrency(contract.charges) }}</span>
                      <span class="readonly-tag">Lecture seule</span>
                    </div>
                    <div class="bail-row readonly-row">
                      <span class="bail-key">Clause de révision</span>
                      <span class="bail-value">{{ contract.revision_clause }}</span>
                      <span class="readonly-tag">Lecture seule</span>
                    </div>
                  </div>
                </div>

                <!-- Documents Section -->
                <div class="glass-card documents-card">
                  <h3 class="card-title">Documents annexes</h3>
                  <p class="card-subtitle-text">Déposez ou mettez à jour vos pièces justificatives</p>

                  <div class="documents-list">
                    <div v-for="doc in contract.documents" :key="doc.id" class="document-row">
                      <div class="doc-icon-wrap" :class="doc.status === 'uploaded' ? 'doc-ok' : 'doc-missing'">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                      </div>
                      <div class="doc-info">
                        <p class="doc-name">{{ doc.name }}</p>
                        <p class="doc-meta" v-if="doc.status === 'uploaded'">{{ doc.filename }} — {{ formatDate(doc.uploaded_at) }}</p>
                        <p class="doc-meta missing" v-else>Non fourni</p>
                      </div>
                      <div class="doc-actions">
                        <a v-if="doc.status === 'uploaded'" :href="doc.url" target="_blank" class="doc-btn view">Voir</a>
                        <button class="doc-btn upload" @click="triggerDocUpload(contract.id, doc.id)">
                          {{ doc.status === 'uploaded' ? 'Remplacer' : 'Déposer' }}
                        </button>
                      </div>
                    </div>
                  </div>

                  <!-- Drag & Drop Zone -->
                  <div class="dropzone"
                    :class="{ 'dragging': isDragging }"
                    @dragover.prevent="isDragging = true"
                    @dragleave="isDragging = false"
                    @drop.prevent="handleDocDrop($event, contract.id)">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12"/></svg>
                    <p class="dropzone-text">Glissez un document ici</p>
                    <p class="dropzone-hint">PDF, JPG, PNG — max 10 Mo</p>
                  </div>
                </div>
              </div>
            </div>
          </section>

          <!-- ═══════════════════════════════
               C. FINANCES
          ═══════════════════════════════ -->
          <section v-else-if="activeTab === 'finances'" class="tab-section">
            <div class="page-header">
              <div>
                <h1 class="page-title">Finances</h1>
                <p class="page-subtitle">Loyers, eau, électricité — Historique complet de vos transactions</p>
              </div>
              <button class="btn-primary" @click="openPaymentModal">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                Payer maintenant
              </button>
            </div>

            <!-- Finance Summary Row -->
            <div class="finance-summary-row">
              <div class="glass-card fin-summary-card">
                <p class="fin-sum-label">Total payé (12 mois)</p>
                <p class="fin-sum-value">{{ formatCurrency(totalPaid12Months) }}</p>
              </div>
              <div class="glass-card fin-summary-card">
                <p class="fin-sum-label">Loyers payés</p>
                <p class="fin-sum-value blue">{{ paidRentsCount }} / {{ totalRentsCount }}</p>
              </div>
              <div class="glass-card fin-summary-card">
                <p class="fin-sum-label">Factures en attente</p>
                <p class="fin-sum-value amber">{{ pendingInvoicesCount }}</p>
              </div>
              <div class="glass-card fin-summary-card">
                <p class="fin-sum-label">En retard</p>
                <p class="fin-sum-value red">{{ lateInvoicesCount }}</p>
              </div>
            </div>

            <!-- Filter Bar -->
            <div class="glass-card filter-bar">
              <div class="filter-tabs">
                <button v-for="f in financeFilters" :key="f.id"
                  class="filter-tab" :class="{ active: financeFilter === f.id }"
                  @click="financeFilter = f.id">{{ f.label }}</button>
              </div>
              <div class="filter-right">
                <input type="text" class="search-input" placeholder="Rechercher..." v-model="invoiceSearch"/>
                <button class="icon-btn" title="Exporter CSV">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                </button>
              </div>
            </div>

            <!-- Invoices Table -->
            <div class="glass-card table-card">
              <div class="table-wrapper">
                <table class="premium-table">
                  <thead>
                    <tr>
                      <th>Référence</th>
                      <th>Type</th>
                      <th>Période</th>
                      <th>Montant</th>
                      <th>Consommation</th>
                      <th>Statut</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="invoice in filteredInvoices" :key="invoice.id" class="table-row">
                      <td class="ref-cell">
                        <span class="ref-code">{{ invoice.reference }}</span>
                      </td>
                      <td>
                        <span class="type-badge" :class="'type-' + invoice.type.toLowerCase()">
                          {{ invoiceTypeLabel(invoice.type) }}
                        </span>
                      </td>
                      <td class="period-cell">{{ invoice.period }}</td>
                      <td class="amount-cell">
                        <strong>{{ formatCurrency(invoice.amount) }}</strong>
                      </td>
                      <td class="conso-cell">
                        <span v-if="invoice.consumption">
                          {{ invoice.consumption.value }} {{ invoice.consumption.unit }}
                          <small class="conso-index">Index: {{ invoice.consumption.index }}</small>
                        </span>
                        <span v-else class="text-gray-400">—</span>
                      </td>
                      <td>
                        <span class="status-pill" :class="'status-' + invoice.status">
                          {{ invoiceStatusLabel(invoice.status) }}
                        </span>
                      </td>
                      <td>
                        <div class="action-buttons">
                          <button v-if="invoice.status !== 'paid'" class="action-btn pay-btn" @click="openPaymentModal(invoice)">Payer</button>
                          <button v-if="invoice.status === 'paid'" class="action-btn receipt-btn" @click="downloadReceipt(invoice)">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                            Quittance
                          </button>
                          <button v-if="invoice.status === 'pending' || invoice.status === 'late'" class="action-btn upload-proof-btn" @click="openProofUpload(invoice)">
                            Justificatif
                          </button>
                        </div>
                      </td>
                    </tr>
                    <tr v-if="filteredInvoices.length === 0">
                      <td colspan="7" class="empty-row">Aucune transaction trouvée</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </section>

          <!-- ═══════════════════════════════
               D. SUPPORT & INCIDENTS
          ═══════════════════════════════ -->
          <section v-else-if="activeTab === 'support'" class="tab-section">
            <div class="page-header">
              <div>
                <h1 class="page-title">Centre de Support</h1>
                <p class="page-subtitle">Gérez vos demandes d'intervention et échangez avec votre gestionnaire</p>
              </div>
              <button class="btn-primary" @click="openNewTicket">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                Nouveau ticket
              </button>
            </div>

            <div class="support-layout">
              <!-- Tickets List -->
              <div class="tickets-panel">
                <div v-for="ticket in tickets" :key="ticket.id"
                  class="glass-card ticket-card"
                  :class="{ 'ticket-selected': selectedTicket?.id === ticket.id, 'ticket-open': ticket.status === 'open', 'ticket-progress': ticket.status === 'in_progress', 'ticket-closed': ticket.status === 'closed' }"
                  @click="selectTicket(ticket)">
                  <div class="ticket-header">
                    <span class="ticket-category-icon" v-html="ticketCategoryIcon(ticket.category)"></span>
                    <div class="ticket-meta">
                      <p class="ticket-title">{{ ticket.title }}</p>
                      <p class="ticket-date">{{ formatDate(ticket.created_at) }}</p>
                    </div>
                    <span class="ticket-status-dot" :class="'dot-' + ticket.status"></span>
                  </div>
                  <p class="ticket-preview">{{ ticket.description.substring(0, 90) }}{{ ticket.description.length > 90 ? '...' : '' }}</p>
                  <div class="ticket-footer">
                    <span class="ticket-status-label" :class="'ts-' + ticket.status">{{ ticketStatusLabel(ticket.status) }}</span>
                    <span class="ticket-msgs">
                      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
                      {{ ticket.messages.length }}
                    </span>
                  </div>
                </div>
                <div v-if="tickets.length === 0" class="empty-state">
                  <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#D1D5DB" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                  <p>Aucun ticket en cours</p>
                </div>
              </div>

              <!-- Chat Panel -->
              <div class="glass-card chat-panel">
                <template v-if="selectedTicket">
                  <div class="chat-header">
                    <div class="chat-header-left">
                      <span v-html="ticketCategoryIcon(selectedTicket.category)" class="chat-cat-icon"></span>
                      <div>
                        <h3 class="chat-title">{{ selectedTicket.title }}</h3>
                        <span class="ticket-status-label" :class="'ts-' + selectedTicket.status">{{ ticketStatusLabel(selectedTicket.status) }}</span>
                      </div>
                    </div>
                    <div class="chat-header-actions">
                      <button v-if="selectedTicket.status === 'open'" class="btn-secondary small" @click="editTicket(selectedTicket)">Modifier</button>
                      <button v-if="selectedTicket.status !== 'closed'" class="btn-danger small" @click="closeTicket(selectedTicket)">Fermer</button>
                    </div>
                  </div>

                  <div class="chat-messages" ref="chatMessagesRef">
                    <div v-for="msg in selectedTicket.messages" :key="msg.id"
                      class="chat-message"
                      :class="msg.sender === 'tenant' ? 'msg-tenant' : 'msg-manager'">
                      <div class="msg-bubble">
                        <p class="msg-text">{{ msg.text }}</p>
                        <span class="msg-time">{{ formatTime(msg.created_at) }}</span>
                      </div>
                      <div class="msg-avatar" v-if="msg.sender === 'manager'">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2M12 11a4 4 0 100-8 4 4 0 000 8z"/></svg>
                      </div>
                    </div>
                  </div>

                  <div class="chat-input-row" v-if="selectedTicket.status !== 'closed'">
                    <input
                      type="text"
                      class="chat-input"
                      placeholder="Votre message..."
                      v-model="newMessage"
                      @keydown.enter="sendMessage"/>
                    <button class="send-btn" @click="sendMessage" :disabled="!newMessage.trim()">
                      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                    </button>
                  </div>
                  <div class="ticket-closed-notice" v-else>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    Ce ticket est clôturé
                  </div>
                </template>
                <div v-else class="chat-empty">
                  <svg width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="#D1D5DB" stroke-width="1.5"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
                  <p>Sélectionnez un ticket pour voir la discussion</p>
                </div>
              </div>
            </div>
          </section>

          <!-- ═══════════════════════════════
               E. PROFIL & PARAMÈTRES
          ═══════════════════════════════ -->
          <section v-else-if="activeTab === 'profile'" class="tab-section">
            <div class="page-header">
              <div>
                <h1 class="page-title">Profil & Paramètres</h1>
                <p class="page-subtitle">Gérez vos informations personnelles et vos préférences</p>
              </div>
            </div>

            <div class="profile-layout">
              <!-- Personal Info -->
              <div class="glass-card profile-card">
                <h3 class="card-title">Informations personnelles</h3>

                <div class="avatar-upload-section">
                  <div class="avatar-large-wrap">
                    <img :src="profileForm.avatar || defaultAvatar" class="avatar-large" :alt="profileForm.name"/>
                    <button class="avatar-edit-btn" @click="$refs.avatarInput.click()">
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </button>
                    <input type="file" ref="avatarInput" accept="image/*" class="hidden-input" @change="handleAvatarChange"/>
                  </div>
                  <div class="avatar-meta">
                    <p class="avatar-name">{{ profileForm.first_name }} {{ profileForm.last_name }}</p>
                    <p class="avatar-email">{{ profileForm.email }}</p>
                  </div>
                </div>

                <div class="form-grid">
                  <div class="form-group">
                    <label>Prénom</label>
                    <input type="text" v-model="profileForm.first_name" class="form-input"/>
                  </div>
                  <div class="form-group">
                    <label>Nom</label>
                    <input type="text" v-model="profileForm.last_name" class="form-input"/>
                  </div>
                  <div class="form-group">
                    <label>Email</label>
                    <input type="email" v-model="profileForm.email" class="form-input"/>
                  </div>
                  <div class="form-group">
                    <label>Téléphone</label>
                    <input type="tel" v-model="profileForm.phone" class="form-input"/>
                  </div>
                </div>

                <div class="form-actions">
                  <button class="btn-primary" @click="saveProfile" :class="{ loading: savingProfile }">
                    <svg v-if="!savingProfile" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    <span v-if="savingProfile" class="spinner-sm"></span>
                    Sauvegarder
                  </button>
                </div>
              </div>

              <!-- Security -->
              <div class="glass-card security-card">
                <h3 class="card-title">Sécurité</h3>

                <div class="security-section">
                  <h4 class="section-subtitle">Changer le mot de passe</h4>
                  <div class="form-group">
                    <label>Mot de passe actuel</label>
                    <div class="password-input-wrap">
                      <input :type="showCurrentPwd ? 'text' : 'password'" v-model="passwordForm.current" class="form-input"/>
                      <button 
  type="button" 
  @click="showCurrentPwd = !showCurrentPwd"
  class="focus:outline-none"
>
  <svg 
    v-if="showCurrentPwd" 
    width="16" 
    height="16" 
    viewBox="0 0 24 24" 
    fill="none" 
    stroke="currentColor" 
    stroke-width="2" 
    stroke-linecap="round" 
    stroke-linejoin="round"
  >
    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
    <circle cx="12" cy="12" r="3"/>
  </svg>

  <svg 
    v-else 
    width="16" 
    height="16" 
    viewBox="0 0 24 24" 
    fill="none" 
    stroke="currentColor" 
    stroke-width="2" 
    stroke-linecap="round" 
    stroke-linejoin="round"
  >
    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
    <line x1="1" y1="1" x2="23" y2="23"/>
  </svg>
</button>
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Nouveau mot de passe</label>
                    <input :type="showNewPwd ? 'text' : 'password'" v-model="passwordForm.new_password" class="form-input"/>
                    <div class="password-strength" v-if="passwordForm.new_password">
                      <div class="strength-bar" :class="passwordStrengthClass">
                        <div class="strength-fill"></div>
                      </div>
                      <span class="strength-label">{{ passwordStrengthLabel }}</span>
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Confirmer le mot de passe</label>
                    <input :type="showNewPwd ? 'text' : 'password'" v-model="passwordForm.confirm" class="form-input"/>
                    <p v-if="passwordForm.confirm && passwordForm.new_password !== passwordForm.confirm" class="field-error">Les mots de passe ne correspondent pas</p>
                  </div>
                  <button class="btn-primary small" @click="changePassword">Modifier le mot de passe</button>
                </div>

                <!-- 2FA -->
                <div class="security-section tfa-section">
                  <div class="tfa-header">
                    <div>
                      <h4 class="section-subtitle">Authentification à deux facteurs</h4>
                      <p class="section-desc">Ajoutez une couche de sécurité supplémentaire</p>
                    </div>
                    <div class="toggle-wrap">
                      <div class="toggle-switch" :class="{ on: twoFAEnabled }" @click="twoFAEnabled = !twoFAEnabled">
                        <div class="toggle-thumb"></div>
                      </div>
                      <span class="toggle-label">{{ twoFAEnabled ? 'Activé' : 'Désactivé' }}</span>
                    </div>
                  </div>
                  <Transition name="fade-slide">
                    <div v-if="twoFAEnabled" class="tfa-setup-box">
                      <p class="tfa-instruction">Scannez ce QR code avec votre application d'authentification (Google Authenticator, Authy).</p>
                      <div class="tfa-qr-placeholder">
                        <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="1.5"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><path d="M14 14h1v1h-1zM16 14h1v1h-1zM18 14h1v1h-1zM14 16h1v1h-1zM16 16h1v1h-1zM18 16h1v1h-1zM14 18h1v1h-1zM16 18h1v1h-1zM18 18h1v1h-1z"/></svg>
                        <p class="tfa-code-text">JBSWY3DPEHPK3PXP</p>
                      </div>
                    </div>
                  </Transition>
                </div>

                <!-- Login History -->
                <div class="security-section">
                  <h4 class="section-subtitle">Dernières connexions</h4>
                  <div class="login-history-list">
                    <div v-for="log in loginHistory" :key="log.id" class="login-row">
                      <span class="login-device-icon" v-html="log.device === 'mobile' ? mobileIcon : desktopIcon"></span>
                      <div class="login-info">
                        <p class="login-device">{{ log.device_name }}</p>
                        <p class="login-ip">{{ log.ip }} — {{ formatDateTime(log.date) }}</p>
                      </div>
                      <span class="login-status-tag" :class="log.current ? 'current' : ''">{{ log.current ? 'Session actuelle' : 'Terminée' }}</span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Notifications Preferences -->
              <div class="glass-card notif-card">
                <h3 class="card-title">Préférences de notifications</h3>
                <div class="notif-grid">
                  <div v-for="notif in notifPreferences" :key="notif.id" class="notif-row">
                    <div class="notif-info">
                      <p class="notif-name">{{ notif.label }}</p>
                      <p class="notif-desc">{{ notif.description }}</p>
                    </div>
                    <div class="notif-channels">
                      <label v-for="channel in notifChannels" :key="channel.id" class="channel-checkbox">
                        <input type="checkbox" v-model="notif.channels" :value="channel.id"/>
                        <span class="checkbox-label">{{ channel.label }}</span>
                      </label>
                    </div>
                  </div>
                </div>
                <button class="btn-primary" @click="saveNotifPrefs">Sauvegarder les préférences</button>
              </div>
            </div>
          </section>

        </div>
      </Transition>
    </main>

    <!-- ══════════════════════════════════════════════════
         MODALS
    ══════════════════════════════════════════════════ -->

    <!-- Payment Modal -->
    <Transition name="modal">
      <div v-if="showPaymentModal" class="modal-overlay" @click.self="showPaymentModal = false">
        <div class="modal-box payment-modal">
          <div class="modal-header">
            <h3 class="modal-title">Initier un paiement</h3>
            <button class="modal-close" @click="showPaymentModal = false">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
          </div>

          <div class="payment-summary">
            <p class="payment-label">Montant à régler</p>
            <p class="payment-amount">{{ formatCurrency(selectedPaymentInvoice?.amount || currentRent) }}</p>
            <p class="payment-ref">Réf : {{ selectedPaymentInvoice?.reference || 'LOYER-' + currentMonthRef }}</p>
          </div>

          <div class="payment-methods">
            <p class="methods-label">Choisissez votre mode de paiement</p>
            <div class="methods-grid">
              <button v-for="method in paymentMethods" :key="method.id"
                class="method-card"
                :class="{ selected: selectedPaymentMethod === method.id }"
                @click="selectedPaymentMethod = method.id">
                <span v-html="method.icon" class="method-icon"></span>
                <span class="method-name">{{ method.name }}</span>
              </button>
            </div>
          </div>

          <div v-if="selectedPaymentMethod === 'card'" class="card-form">
            <div class="form-group">
              <label>Numéro de carte</label>
              <input type="text" class="form-input" placeholder="1234 5678 9012 3456" v-model="cardForm.number" maxlength="19"/>
            </div>
            <div class="form-grid-2">
              <div class="form-group">
                <label>Expiration</label>
                <input type="text" class="form-input" placeholder="MM/AA" v-model="cardForm.expiry" maxlength="5"/>
              </div>
              <div class="form-group">
                <label>CVV</label>
                <input type="text" class="form-input" placeholder="123" v-model="cardForm.cvv" maxlength="3"/>
              </div>
            </div>
          </div>

          <div v-if="selectedPaymentMethod === 'mobile'" class="mobile-form">
            <div class="form-group">
              <label>Numéro de téléphone Mobile Money</label>
              <input type="tel" class="form-input" placeholder="+237 6XX XXX XXX" v-model="mobilePayForm.phone"/>
            </div>
            <div class="mobile-operators">
              <button v-for="op in mobileOperators" :key="op.id"
                class="operator-btn" :class="{ selected: mobilePayForm.operator === op.id }"
                @click="mobilePayForm.operator = op.id">{{ op.name }}</button>
            </div>
          </div>

          <button class="btn-primary full-width" @click="processPayment" :class="{ loading: processingPayment }">
            <span v-if="!processingPayment">Confirmer le paiement</span>
            <span v-else class="loading-dots"><span>.</span><span>.</span><span>.</span></span>
          </button>
        </div>
      </div>
    </Transition>

    <!-- New Ticket Modal -->
    <Transition name="modal">
      <div v-if="showNewTicketModal" class="modal-overlay" @click.self="showNewTicketModal = false">
        <div class="modal-box ticket-modal">
          <div class="modal-header">
            <h3 class="modal-title">Nouveau ticket de support</h3>
            <button class="modal-close" @click="showNewTicketModal = false">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
          </div>

          <div class="form-group">
            <label>Catégorie</label>
            <select class="form-input form-select" v-model="newTicketForm.category">
              <option v-for="cat in ticketCategories" :key="cat.id" :value="cat.id">{{ cat.label }}</option>
            </select>
          </div>

          <div class="form-group">
            <label>Titre du problème</label>
            <input type="text" class="form-input" placeholder="Ex: Fuite d'eau dans la cuisine" v-model="newTicketForm.title"/>
          </div>

          <div class="form-group">
            <label>Description détaillée</label>
            <textarea class="form-input form-textarea" rows="4" placeholder="Décrivez le problème en détail..." v-model="newTicketForm.description"></textarea>
          </div>

          <div class="form-group">
            <label>Priorité</label>
            <div class="priority-selector">
              <button v-for="p in priorities" :key="p.id"
                class="priority-btn" :class="['prio-' + p.id, { selected: newTicketForm.priority === p.id }]"
                @click="newTicketForm.priority = p.id">{{ p.label }}</button>
            </div>
          </div>

          <button class="btn-primary full-width" @click="submitTicket">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
            Soumettre le ticket
          </button>
        </div>
      </div>
    </Transition>

    <!-- Proof Upload Modal -->
    <Transition name="modal">
      <div v-if="showProofModal" class="modal-overlay" @click.self="showProofModal = false">
        <div class="modal-box proof-modal">
          <div class="modal-header">
            <h3 class="modal-title">Justificatif de virement</h3>
            <button class="modal-close" @click="showProofModal = false">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
          </div>
          <p class="modal-subtitle">Facture : {{ selectedProofInvoice?.reference }}</p>
          <div class="dropzone large-dropzone"
            :class="{ dragging: isProofDragging }"
            @dragover.prevent="isProofDragging = true"
            @dragleave="isProofDragging = false"
            @drop.prevent="handleProofDrop">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12"/></svg>
            <p class="dropzone-text">Déposez votre justificatif</p>
            <p class="dropzone-hint">PDF ou image — max 5 Mo</p>
            <button class="btn-secondary small" @click="$refs.proofInput.click()">Parcourir</button>
            <input type="file" ref="proofInput" accept=".pdf,image/*" class="hidden-input" @change="handleProofFileSelect"/>
          </div>
          <div v-if="proofFile" class="proof-file-preview">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            <span>{{ proofFile.name }}</span>
          </div>
          <button class="btn-primary full-width" @click="submitProof" :disabled="!proofFile">Envoyer le justificatif</button>
        </div>
      </div>
    </Transition>

    <!-- Toast Notification -->
    <Transition name="toast">
      <div v-if="toast.show" class="toast" :class="'toast-' + toast.type">
        <svg v-if="toast.type === 'success'" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        <svg v-else-if="toast.type === 'error'" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
        <span>{{ toast.message }}</span>
      </div>
    </Transition>

  </div>
</template>

<script setup>
import { ref, computed, reactive, nextTick, onMounted } from 'vue'

// ════════════════════════════════════════════════════════════
//  PROPS (Inertia.js ready — connectables à un backend Laravel)
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
        start_date: '2023-01-01',
        end_date: '2025-12-31',
        rent: 185000,
        deposit: 370000,
        charges: 25000,
        revision_clause: 'Indexation annuelle IRL (plafonnée à 3,5%)',
        property: {
          name: 'Appartement Bastos — Résidence Les Palmiers',
          address: 'Rue 3.120, Quartier Bastos, Yaoundé, Centre',
          type: 'Appartement F3',
          photo: null,
          specs: [
            { label: 'Surface', value: '78 m²' },
            { label: 'Étage', value: '3ème (sur 5)' },
            { label: 'Chambres', value: '2 chambres' },
            { label: 'Salles de bain', value: '1 SDB + WC séparé' },
            { label: 'Parking', value: '1 place couverte' },
            { label: 'Exposition', value: 'Sud-Ouest' },
          ],
          equipment: ['Climatiseur x2', 'Chauffe-eau électrique', 'Cuisine équipée', 'Garde-robe intégrée', 'Fibre optique', 'Digicode + Interphone', 'Balcon 6m²'],
        },
        documents: [
          { id: 'd1', name: 'Pièce d\'identité (CNI/Passeport)', status: 'uploaded', filename: 'CNI_Nguetsop.pdf', url: '#', uploaded_at: '2023-01-05' },
          { id: 'd2', name: 'Justificatif de revenus (3 derniers)', status: 'uploaded', filename: 'Bulletins_2022.pdf', url: '#', uploaded_at: '2023-01-05' },
          { id: 'd3', name: 'Attestation d\'assurance habitation', status: 'uploaded', filename: 'Assurance_2024.pdf', url: '#', uploaded_at: '2024-01-10' },
          { id: 'd4', name: 'Contrat de travail / Justificatif emploi', status: 'uploaded', filename: 'Contrat_Nguetsop.pdf', url: '#', uploaded_at: '2023-01-05' },
          { id: 'd5', name: 'RIB bancaire', status: 'missing', filename: null, url: null, uploaded_at: null },
        ]
      }
    ])
  },
  invoices: {
    type: Array,
    default: () => ([
      { id: 'INV-2024-012', reference: 'INV-2024-012', type: 'RENT', period: 'Décembre 2024', amount: 185000, status: 'paid', consumption: null },
      { id: 'INV-2024-011', reference: 'INV-2024-011', type: 'RENT', period: 'Novembre 2024', amount: 185000, status: 'paid', consumption: null },
      { id: 'INV-2024-EAU-012', reference: 'INV-EAU-2024-012', type: 'WATER', period: 'Décembre 2024', amount: 12500, status: 'paid', consumption: { value: '8.5', unit: 'm³', index: '1452' } },
      { id: 'INV-2024-ELEC-012', reference: 'INV-ELEC-2024-012', type: 'ELECTRIC', period: 'Décembre 2024', amount: 18200, status: 'pending', consumption: { value: '124', unit: 'kWh', index: '08741' } },
      { id: 'INV-2025-001', reference: 'INV-2025-001', type: 'RENT', period: 'Janvier 2025', amount: 185000, status: 'pending', consumption: null },
      { id: 'INV-2024-010', reference: 'INV-2024-010', type: 'RENT', period: 'Octobre 2024', amount: 185000, status: 'paid', consumption: null },
      { id: 'INV-2024-EAU-011', reference: 'INV-EAU-2024-011', type: 'WATER', period: 'Novembre 2024', amount: 11800, status: 'paid', consumption: { value: '7.9', unit: 'm³', index: '1443' } },
      { id: 'INV-2024-009', reference: 'INV-2024-009', type: 'RENT', period: 'Septembre 2024', amount: 185000, status: 'paid', consumption: null },
      { id: 'INV-2024-ELEC-009', reference: 'INV-ELEC-2024-009', type: 'ELECTRIC', period: 'Septembre 2024', amount: 15300, status: 'late', consumption: { value: '98', unit: 'kWh', index: '08617' } },
    ])
  },
  tickets: {
    type: Array,
    default: () => ([
      {
        id: 'TKT-001',
        title: 'Climatiseur chambre principale défaillant',
        description: 'Le climatiseur de la chambre principale ne refroidit plus depuis 3 jours. Il souffle de l\'air tiède uniquement. La télécommande répond mais l\'appareil ne descend pas en dessous de 28°C.',
        category: 'electrical',
        status: 'in_progress',
        priority: 'high',
        created_at: '2025-01-08',
        messages: [
          { id: 'm1', sender: 'tenant', text: 'Le climatiseur de la chambre principale ne fonctionne plus correctement. Il ne refroidit plus depuis mercredi.', created_at: '2025-01-08T10:30:00' },
          { id: 'm2', sender: 'manager', text: 'Bonjour M. Nguetsop, nous avons bien reçu votre signalement. Un technicien sera disponible vendredi 10 janvier entre 9h et 12h. Pouvez-vous confirmer votre disponibilité ?', created_at: '2025-01-08T14:15:00' },
          { id: 'm3', sender: 'tenant', text: 'Oui, je serai disponible vendredi matin. Merci pour la réactivité !', created_at: '2025-01-08T15:02:00' },
        ]
      },
      {
        id: 'TKT-002',
        title: 'Fuite d\'eau sous l\'évier cuisine',
        description: 'Une petite fuite apparaît sous l\'évier de la cuisine depuis quelques jours. La fuite est lente mais constante. Un seau est posé en dessous pour l\'instant.',
        category: 'plumbing',
        status: 'open',
        priority: 'medium',
        created_at: '2025-01-10',
        messages: [
          { id: 'm4', sender: 'tenant', text: 'Bonjour, j\'ai remarqué une fuite sous l\'évier de cuisine. Ce n\'est pas urgent mais ça mérite d\'être réparé rapidement.', created_at: '2025-01-10T09:00:00' },
        ]
      },
      {
        id: 'TKT-003',
        title: 'Ampoule de la cage d\'escalier grillée',
        description: 'L\'ampoule du palier 3ème étage est grillée depuis une semaine. La cage d\'escalier est très sombre la nuit.',
        category: 'general',
        status: 'closed',
        priority: 'low',
        created_at: '2024-12-20',
        messages: [
          { id: 'm5', sender: 'tenant', text: 'L\'ampoule du palier est grillée.', created_at: '2024-12-20T18:00:00' },
          { id: 'm6', sender: 'manager', text: 'Remplacée ce matin. Merci de votre signalement !', created_at: '2024-12-21T11:30:00' },
        ]
      },
    ])
  }
})

// ════════════════════════════════════════════════════════════
//  REACTIVE STATE
// ════════════════════════════════════════════════════════════
const activeTab = ref('overview')
const sidebarCollapsed = ref(false)
const mobileSidebarOpen = ref(false)
const isLoading = ref(false)
const progressWidth = ref(0)

// Finance
const financeFilter = ref('all')
const invoiceSearch = ref('')

// Support
const selectedTicket = ref(null)
const newMessage = ref('')
const chatMessagesRef = ref(null)

// Modals
const showPaymentModal = ref(false)
const showNewTicketModal = ref(false)
const showProofModal = ref(false)
const selectedPaymentInvoice = ref(null)
const selectedProofInvoice = ref(null)
const selectedPaymentMethod = ref('mobile')
const processingPayment = ref(false)
const isProofDragging = ref(false)
const proofFile = ref(null)

// Profile
const savingProfile = ref(false)
const showCurrentPwd = ref(false)
const showNewPwd = ref(false)
const twoFAEnabled = ref(false)
const isDragging = ref(false)

// Toast
const toast = reactive({ show: false, type: 'success', message: '' })

// Profile Form
const profileForm = reactive({
  first_name: props.auth.user.first_name || 'Armel',
  last_name: props.auth.user.last_name || 'Nguetsop',
  email: props.auth.user.email,
  phone: props.auth.user.phone || '+237 691 234 567',
  avatar: props.auth.user.avatar,
})

const passwordForm = reactive({ current: '', new_password: '', confirm: '' })
const cardForm = reactive({ number: '', expiry: '', cvv: '' })
const mobilePayForm = reactive({ phone: '', operator: 'mtn' })
const newTicketForm = reactive({ title: '', description: '', category: 'plumbing', priority: 'medium' })

// ════════════════════════════════════════════════════════════
//  CONSTANTS / STATICS
// ════════════════════════════════════════════════════════════
const defaultAvatar = `data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80' viewBox='0 0 80 80'%3E%3Crect width='80' height='80' rx='40' fill='%23EFF6FF'/%3E%3Ccircle cx='40' cy='32' r='14' fill='%232563EB' opacity='0.7'/%3E%3Cellipse cx='40' cy='68' rx='22' ry='14' fill='%232563EB' opacity='0.5'/%3E%3C/svg%3E`

const navItems = [
  { id: 'overview', label: 'Vue d\'ensemble', icon: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>', badge: null },
  { id: 'contract', label: 'Logements & Baux', icon: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>', badge: null },
  { id: 'finances', label: 'Finances', icon: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>', badge: '2' },
  { id: 'support', label: 'Support & Incidents', icon: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>', badge: '2' },
  { id: 'profile', label: 'Profil & Paramètres', icon: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>', badge: null },
]

const paymentMethods = [
  { id: 'mobile', name: 'Mobile Money', icon: '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="2" width="14" height="20" rx="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>' },
  { id: 'card', name: 'Carte Bancaire', icon: '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>' },
  { id: 'transfer', name: 'Virement', icon: '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 100 7h5a3.5 3.5 0 110 7H6"/></svg>' },
]
const mobileOperators = [
  { id: 'mtn', name: 'MTN MoMo' },
  { id: 'orange', name: 'Orange Money' },
  { id: 'upe', name: 'Express Union' },
]

const financeFilters = [
  { id: 'all', label: 'Tous' },
  { id: 'RENT', label: 'Loyers' },
  { id: 'WATER', label: 'Eau' },
  { id: 'ELECTRIC', label: 'Électricité' },
  { id: 'pending', label: 'En attente' },
  { id: 'late', label: 'En retard' },
]

const ticketCategories = [
  { id: 'plumbing', label: '🔧 Plomberie' },
  { id: 'electrical', label: '⚡ Électricité / Électroménager' },
  { id: 'carpentry', label: '🪵 Menuiserie / Serrurerie' },
  { id: 'general', label: '🏠 Entretien général' },
  { id: 'neighbour', label: '👥 Voisinage / Nuisances' },
  { id: 'other', label: '❓ Autre' },
]
const priorities = [
  { id: 'low', label: 'Faible' },
  { id: 'medium', label: 'Moyenne' },
  { id: 'high', label: 'Haute' },
  { id: 'urgent', label: 'Urgente' },
]

const notifChannels = [
  { id: 'email', label: 'Email' },
  { id: 'sms', label: 'SMS' },
  { id: 'push', label: 'Push' },
]
const notifPreferences = reactive([
  { id: 'rent_reminder', label: 'Rappel de loyer', description: 'Rappel 5 jours avant l\'échéance mensuelle', channels: ['email', 'push'] },
  { id: 'payment_confirm', label: 'Confirmation de paiement', description: 'Reçu immédiat après chaque transaction', channels: ['email', 'sms'] },
  { id: 'ticket_update', label: 'Mise à jour des tickets', description: 'Notification quand votre gestionnaire répond', channels: ['email', 'push'] },
  { id: 'contract_alert', label: 'Alertes de bail', description: 'Révision annuelle, expiration prochaine', channels: ['email'] },
  { id: 'invoice_new', label: 'Nouvelle facture', description: 'Eau, électricité, charges disponibles', channels: ['email', 'push'] },
])

const loginHistory = [
  { id: 1, device: 'desktop', device_name: 'Chrome — Windows 11', ip: '196.210.45.12', date: '2025-01-12T08:34:00', current: true },
  { id: 2, device: 'mobile', device_name: 'Safari — iPhone 15', ip: '196.210.45.10', date: '2025-01-11T20:15:00', current: false },
  { id: 3, device: 'desktop', device_name: 'Chrome — Windows 11', ip: '196.210.45.12', date: '2025-01-10T09:02:00', current: false },
  { id: 4, device: 'mobile', device_name: 'Chrome — Android', ip: '177.92.134.56', date: '2025-01-08T15:44:00', current: false },
]

const mobileIcon = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="2" width="14" height="20" rx="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>`
const desktopIcon = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>`

// ════════════════════════════════════════════════════════════
//  CHART CONFIG
// ════════════════════════════════════════════════════════════
const chartW = 600
const chartH = 220
const chartPad = 50

const chartMonths = ['Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc', 'Jan']
const rentData = [185000, 185000, 185000, 185000, 185000, 185000, 185000]
const chargesData = [48000, 52000, 33500, 44200, 39800, 30700, 0]

const maxVal = computed(() => Math.max(...rentData, ...chargesData) * 1.15)

const chartYLabels = computed(() => {
  const step = maxVal.value / 4
  return [0,1,2,3,4].reverse().map(i => formatCurrencyShort(i * step))
})

function chartXforIndex(i) {
  const available = chartW - 2 * chartPad
  return chartPad + (i / (chartMonths.length - 1)) * available
}

function chartYforVal(val) {
  const available = chartH - 2 * chartPad
  return chartPad + (1 - val / maxVal.value) * available
}

const rentPoints = computed(() => rentData.map((v, i) => ({ x: chartXforIndex(i), y: chartYforVal(v) })))
const chargesPoints = computed(() => chargesData.map((v, i) => ({ x: chartXforIndex(i), y: chartYforVal(v) })))

const rentLinePath = computed(() => rentPoints.value.map((p, i) => `${i === 0 ? 'M' : 'L'} ${p.x} ${p.y}`).join(' '))
const chargesLinePath = computed(() => chargesPoints.value.map((p, i) => `${i === 0 ? 'M' : 'L'} ${p.x} ${p.y}`).join(' '))
const rentAreaPath = computed(() => `${rentLinePath.value} L ${chartXforIndex(chartMonths.length-1)} ${chartH - chartPad} L ${chartPad} ${chartH - chartPad} Z`)
const chargesAreaPath = computed(() => `${chargesLinePath.value} L ${chartXforIndex(chartMonths.length-1)} ${chartH - chartPad} L ${chartPad} ${chartH - chartPad} Z`)

// ════════════════════════════════════════════════════════════
//  COMPUTED
// ════════════════════════════════════════════════════════════
const currentNavLabel = computed(() => navItems.find(n => n.id === activeTab.value)?.label || '')
const currentRent = computed(() => props.contracts[0]?.rent || 0)
const currentRentStatus = computed(() => {
  const jan25 = props.invoices.find(i => i.type === 'RENT' && i.period === 'Janvier 2025')
  return jan25?.status || 'pending'
})
const totalDue = computed(() => props.invoices.filter(i => i.status !== 'paid').reduce((acc, i) => acc + i.amount, 0))
const openTicketsCount = computed(() => props.tickets.filter(t => t.status !== 'closed').length)
const nextDueDate = computed(() => '05 Fév 2025')
const daysUntilDue = computed(() => 24)
const currentMonthRef = computed(() => '2025-01')

const filteredInvoices = computed(() => {
  let list = [...props.invoices]
  if (financeFilter.value !== 'all') {
    if (['pending', 'late', 'paid'].includes(financeFilter.value)) {
      list = list.filter(i => i.status === financeFilter.value)
    } else {
      list = list.filter(i => i.type === financeFilter.value)
    }
  }
  if (invoiceSearch.value.trim()) {
    const s = invoiceSearch.value.toLowerCase()
    list = list.filter(i => i.reference.toLowerCase().includes(s) || i.period.toLowerCase().includes(s))
  }
  return list
})

const totalPaid12Months = computed(() => props.invoices.filter(i => i.status === 'paid').reduce((a, i) => a + i.amount, 0))
const paidRentsCount = computed(() => props.invoices.filter(i => i.type === 'RENT' && i.status === 'paid').length)
const totalRentsCount = computed(() => props.invoices.filter(i => i.type === 'RENT').length)
const pendingInvoicesCount = computed(() => props.invoices.filter(i => i.status === 'pending').length)
const lateInvoicesCount = computed(() => props.invoices.filter(i => i.status === 'late').length)

const formattedToday = computed(() => {
  return new Date().toLocaleDateString('fr-FR', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })
})

const passwordStrength = computed(() => {
  const p = passwordForm.new_password
  if (!p) return 0
  let score = 0
  if (p.length >= 8) score++
  if (/[A-Z]/.test(p)) score++
  if (/[0-9]/.test(p)) score++
  if (/[^A-Za-z0-9]/.test(p)) score++
  return score
})
const passwordStrengthClass = computed(() => ['strength-weak', 'strength-medium', 'strength-strong', 'strength-very-strong'][Math.min(passwordStrength.value - 1, 3)] || 'strength-weak')
const passwordStrengthLabel = computed(() => ['Très faible', 'Faible', 'Moyen', 'Fort', 'Très fort'][passwordStrength.value] || '')

// ════════════════════════════════════════════════════════════
//  METHODS
// ════════════════════════════════════════════════════════════
function switchTab(id) {
  if (id === activeTab.value) return
  triggerLoader()
  activeTab.value = id
  mobileSidebarOpen.value = false
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
function formatCurrencyShort(amount) {
  if (amount >= 1000000) return (amount/1000000).toFixed(1) + 'M'
  if (amount >= 1000) return (amount/1000).toFixed(0) + 'k'
  return Math.round(amount).toString()
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

function kpiStatusClass(status) {
  return { 'kpi-success': status === 'paid', 'kpi-warning': status === 'pending', 'kpi-danger': status === 'late' }
}
function rentStatusLabel(s) { return { paid: 'Payé ✓', pending: 'En attente', late: 'En retard' }[s] || s }
function invoiceStatusLabel(s) { return { paid: 'Payé', pending: 'En attente', late: 'En retard' }[s] || s }
function invoiceTypeLabel(t) { return { RENT: 'Loyer', WATER: 'Eau', ELECTRIC: 'Électricité' }[t] || t }
function ticketStatusLabel(s) { return { open: 'Ouvert', in_progress: 'En cours', closed: 'Clôturé' }[s] || s }
function ticketCategoryIcon(cat) {
  const icons = {
    plumbing: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/></svg>',
    electrical: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>',
    general: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>',
    carpentry: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/></svg>',
    other: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3M12 17h.01"/></svg>',
  }
  return icons[cat] || icons.other
}

function openPaymentModal(invoice = null) {
  selectedPaymentInvoice.value = invoice
  showPaymentModal.value = true
}
function processPayment() {
  if (!selectedPaymentMethod.value) return
  processingPayment.value = true
  setTimeout(() => {
    processingPayment.value = false
    showPaymentModal.value = false
    showToast('success', 'Paiement initié avec succès ! Une confirmation vous sera envoyée.')
  }, 2000)
}

function openNewTicket() {
  newTicketForm.title = ''
  newTicketForm.description = ''
  newTicketForm.category = 'plumbing'
  newTicketForm.priority = 'medium'
  showNewTicketModal.value = true
}
function submitTicket() {
  if (!newTicketForm.title || !newTicketForm.description) {
    showToast('error', 'Veuillez remplir tous les champs obligatoires.')
    return
  }
  showNewTicketModal.value = false
  showToast('success', 'Ticket créé avec succès ! Votre gestionnaire vous répondra prochainement.')
}

function selectTicket(ticket) {
  selectedTicket.value = ticket
  nextTick(() => {
    if (chatMessagesRef.value) {
      chatMessagesRef.value.scrollTop = chatMessagesRef.value.scrollHeight
    }
  })
}
function sendMessage() {
  if (!newMessage.value.trim() || !selectedTicket.value) return
  selectedTicket.value.messages.push({
    id: 'm' + Date.now(),
    sender: 'tenant',
    text: newMessage.value,
    created_at: new Date().toISOString()
  })
  newMessage.value = ''
  nextTick(() => {
    if (chatMessagesRef.value) {
      chatMessagesRef.value.scrollTop = chatMessagesRef.value.scrollHeight
    }
  })
}
function editTicket(ticket) {
  newTicketForm.title = ticket.title
  newTicketForm.description = ticket.description
  newTicketForm.category = ticket.category
  newTicketForm.priority = ticket.priority
  showNewTicketModal.value = true
}
function closeTicket(ticket) {
  ticket.status = 'closed'
  showToast('success', 'Ticket clôturé.')
}

function downloadReceipt(invoice) {
  showToast('success', `Quittance ${invoice.reference} téléchargée.`)
}
function downloadLastReceipt() {
  showToast('success', 'Dernière quittance téléchargée.')
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
  if (!proofFile.value) return
  showProofModal.value = false
  showToast('success', 'Justificatif envoyé avec succès !')
}

function handleDocDrop(e, contractId) {
  isDragging.value = false
  showToast('success', 'Document déposé avec succès !')
}
function triggerDocUpload(contractId, docId) {
  showToast('success', 'Sélectionnez un fichier pour remplacer ce document.')
}

function handleAvatarChange(e) {
  const file = e.target.files[0]
  if (!file) return
  const reader = new FileReader()
  reader.onload = (ev) => { profileForm.avatar = ev.target.result }
  reader.readAsDataURL(file)
}
function saveProfile() {
  savingProfile.value = true
  setTimeout(() => {
    savingProfile.value = false
    showToast('success', 'Profil mis à jour avec succès !')
  }, 1200)
}
function changePassword() {
  if (passwordForm.new_password !== passwordForm.confirm) {
    showToast('error', 'Les mots de passe ne correspondent pas.')
    return
  }
  showToast('success', 'Mot de passe modifié avec succès !')
  passwordForm.current = ''
  passwordForm.new_password = ''
  passwordForm.confirm = ''
}
function saveNotifPrefs() {
  showToast('success', 'Préférences de notifications enregistrées !')
}
function handleLogout() {
  showToast('success', 'Déconnexion en cours...')
}

function showToast(type, message) {
  toast.type = type
  toast.message = message
  toast.show = true
  setTimeout(() => { toast.show = false }, 4000)
}

onMounted(() => {
  triggerLoader()
})
</script>

<style>
/* ══════════════════════════════════════════════════════════════════
   IMPORTS & RESET
══════════════════════════════════════════════════════════════════ */
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@300;400;500&display=swap');

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

/* ══════════════════════════════════════════════════════════════════
   CSS VARIABLES
══════════════════════════════════════════════════════════════════ */
:root {
  --blue-50: #EFF6FF;
  --blue-100: #DBEAFE;
  --blue-500: #3B82F6;
  --blue-600: #2563EB;
  --blue-700: #1D4ED8;
  --blue-800: #1E40AF;
  --emerald-500: #10B981;
  --emerald-600: #059669;
  --amber-400: #FBBF24;
  --amber-500: #F59E0B;
  --amber-600: #D97706;
  --red-400: #F87171;
  --red-500: #EF4444;
  --red-600: #DC2626;
  --violet-500: #8B5CF6;
  --gray-50: #F9FAFB;
  --gray-100: #F3F4F6;
  --gray-200: #E5E7EB;
  --gray-300: #D1D5DB;
  --gray-400: #9CA3AF;
  --gray-500: #6B7280;
  --gray-600: #4B5563;
  --gray-700: #374151;
  --gray-800: #1F2937;
  --gray-900: #111827;
  --white: #FFFFFF;
  --glass-bg: rgba(255, 255, 255, 0.72);
  --glass-border: rgba(255, 255, 255, 0.5);
  --glass-shadow: 0 4px 24px rgba(37, 99, 235, 0.06), 0 1px 4px rgba(0,0,0,0.04);
  --glass-shadow-hover: 0 8px 40px rgba(37, 99, 235, 0.12), 0 2px 8px rgba(0,0,0,0.06);
  --sidebar-w: 260px;
  --sidebar-w-collapsed: 72px;
  --font-main: 'Plus Jakarta Sans', sans-serif;
  --font-mono: 'DM Mono', monospace;
  --radius-lg: 16px;
  --radius-xl: 20px;
  --radius-2xl: 24px;
}

/* ══════════════════════════════════════════════════════════════════
   LAYOUT ROOT
══════════════════════════════════════════════════════════════════ */
.dashboard-root {
  font-family: var(--font-main);
  background: var(--gray-50);
  min-height: 100vh;
  display: flex;
  position: relative;
  overflow: hidden;
  color: var(--gray-800);
}

/* ══ AMBIENT BG ══ */
.ambient-bg { position: fixed; inset: 0; z-index: 0; pointer-events: none; overflow: hidden; }
.orb { position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.35; }
.orb-1 { width: 600px; height: 600px; background: radial-gradient(circle, #DBEAFE, transparent); top: -200px; left: -150px; animation: orbFloat1 20s ease-in-out infinite; }
.orb-2 { width: 500px; height: 500px; background: radial-gradient(circle, #D1FAE5, transparent); bottom: -150px; right: -100px; animation: orbFloat2 25s ease-in-out infinite; }
.orb-3 { width: 400px; height: 400px; background: radial-gradient(circle, #FEF3C7, transparent); top: 40%; left: 40%; animation: orbFloat3 18s ease-in-out infinite; }
@keyframes orbFloat1 { 0%,100%{ transform: translate(0,0) scale(1); } 50%{ transform: translate(40px, 30px) scale(1.08); } }
@keyframes orbFloat2 { 0%,100%{ transform: translate(0,0) scale(1); } 50%{ transform: translate(-30px, -20px) scale(1.05); } }
@keyframes orbFloat3 { 0%,100%{ transform: translate(0,0); } 50%{ transform: translate(20px, -30px); } }

/* ══ PROGRESS BAR ══ */
.progress-bar { position: fixed; top: 0; left: 0; right: 0; height: 2px; z-index: 9999; opacity: 0; transition: opacity 0.2s; }
.progress-bar.active { opacity: 1; }
.progress-fill { height: 100%; background: linear-gradient(90deg, var(--blue-600), var(--blue-400), var(--emerald-500)); border-radius: 0 2px 2px 0; transition: width 0.3s ease; box-shadow: 0 0 8px rgba(37, 99, 235, 0.6); }

/* ══════════════════════════════════════════════════════════════════
   SIDEBAR
══════════════════════════════════════════════════════════════════ */
.sidebar {
  position: fixed;
  left: 0; top: 0; bottom: 0;
  width: var(--sidebar-w);
  z-index: 100;
  display: flex;
  flex-direction: column;
  background: rgba(255,255,255,0.88);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  border-right: 1px solid rgba(37, 99, 235, 0.08);
  box-shadow: 4px 0 32px rgba(37, 99, 235, 0.05);
  transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  overflow: hidden;
}
.sidebar.collapsed { width: var(--sidebar-w-collapsed); }

.sidebar-header {
  display: flex; align-items: center; justify-content: space-between;
  padding: 20px 16px 16px;
  border-bottom: 1px solid var(--gray-100);
  min-height: 68px;
}
.brand-logo { display: flex; align-items: center; gap: 10px; overflow: hidden; }
.brand-name { font-weight: 800; font-size: 18px; color: var(--gray-900); letter-spacing: -0.5px; white-space: nowrap; }
.collapse-btn {
  width: 32px; height: 32px; border-radius: 8px; border: 1px solid var(--gray-200);
  background: white; cursor: pointer; display: flex; align-items: center; justify-content: center;
  color: var(--gray-500); transition: all 0.2s; flex-shrink: 0;
}
.collapse-btn:hover { background: var(--blue-50); color: var(--blue-600); border-color: var(--blue-200); }

.tenant-quick-info {
  display: flex; align-items: center; gap: 10px;
  padding: 14px 16px;
  border-bottom: 1px solid var(--gray-100);
}
.tenant-avatar-wrap { position: relative; flex-shrink: 0; }
.tenant-avatar { width: 38px; height: 38px; border-radius: 50%; object-fit: cover; border: 2px solid white; box-shadow: 0 2px 8px rgba(37,99,235,0.15); }
.status-dot { position: absolute; bottom: 1px; right: 1px; width: 10px; height: 10px; border-radius: 50%; background: var(--emerald-500); border: 2px solid white; }
.tenant-meta { overflow: hidden; }
.tenant-name { font-weight: 600; font-size: 13px; color: var(--gray-800); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.tenant-role { font-size: 11px; color: var(--gray-400); font-weight: 500; }

.sidebar-nav { flex: 1; padding: 10px 10px; display: flex; flex-direction: column; gap: 2px; overflow-y: auto; }
.nav-item {
  display: flex; align-items: center; gap: 12px;
  padding: 10px 12px; border-radius: 10px;
  cursor: pointer; transition: all 0.2s ease;
  border: none; background: transparent; width: 100%;
  text-align: left; position: relative;
  color: var(--gray-600); font-family: var(--font-main);
  white-space: nowrap; overflow: hidden;
}
.nav-item:hover { background: var(--blue-50); color: var(--blue-700); }
.nav-item.active { background: linear-gradient(135deg, var(--blue-50), rgba(37,99,235,0.08)); color: var(--blue-700); font-weight: 600; }
.nav-item.active::before { content:''; position:absolute; left:0; top:20%; bottom:20%; width:3px; border-radius:0 3px 3px 0; background: var(--blue-600); }
.nav-icon { display: flex; align-items: center; justify-content: center; flex-shrink: 0; width: 18px; }
.nav-label { font-size: 13.5px; font-weight: 500; }
.nav-badge { margin-left: auto; background: var(--blue-600); color: white; font-size: 10px; font-weight: 700; padding: 1px 6px; border-radius: 20px; }
.logout-btn { color: var(--red-500); }
.logout-btn:hover { background: #FFF5F5; color: var(--red-600); }
.sidebar-footer { padding: 10px; border-top: 1px solid var(--gray-100); }

/* ══════════════════════════════════════════════════════════════════
   MAIN CONTENT
══════════════════════════════════════════════════════════════════ */
.main-content {
  margin-left: var(--sidebar-w);
  flex: 1; min-height: 100vh;
  transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative; z-index: 1;
}
.sidebar-collapsed + .main-content { margin-left: var(--sidebar-w-collapsed); }

.mobile-header { display: none; }

.page-panel { padding: 28px 32px; min-height: 100vh; }

/* ══════════════════════════════════════════════════════════════════
   GLASS CARD
══════════════════════════════════════════════════════════════════ */
.glass-card {
  background: var(--glass-bg);
  backdrop-filter: blur(16px);
  -webkit-backdrop-filter: blur(16px);
  border: 1px solid var(--glass-border);
  border-radius: var(--radius-xl);
  box-shadow: var(--glass-shadow);
  transition: box-shadow 0.3s ease, transform 0.3s ease;
}
.glass-card:hover { box-shadow: var(--glass-shadow-hover); }

/* ══════════════════════════════════════════════════════════════════
   PAGE HEADER
══════════════════════════════════════════════════════════════════ */
.page-header {
  display: flex; align-items: flex-start; justify-content: space-between;
  margin-bottom: 28px; gap: 16px; flex-wrap: wrap;
}
.page-title { font-size: 26px; font-weight: 800; color: var(--gray-900); letter-spacing: -0.5px; }
.page-subtitle { color: var(--gray-500); font-size: 14px; margin-top: 4px; }
.page-subtitle strong { color: var(--gray-700); }
.header-date { font-size: 13px; color: var(--gray-400); font-weight: 500; padding: 8px 14px; background: white; border-radius: 10px; border: 1px solid var(--gray-200); white-space: nowrap; }

/* ══════════════════════════════════════════════════════════════════
   KPI CARDS
══════════════════════════════════════════════════════════════════ */
.kpi-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px; }
.kpi-card { padding: 20px; display: flex; gap: 14px; align-items: flex-start; }
.kpi-icon-wrap {
  width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;
  background: var(--blue-50); color: var(--blue-600);
}
.kpi-icon-amber { background: #FFF8E6; color: var(--amber-500); }
.kpi-icon-emerald { background: #ECFDF5; color: var(--emerald-500); }
.kpi-icon-violet { background: #F5F3FF; color: var(--violet-500); }
.kpi-body { flex: 1; }
.kpi-label { font-size: 12px; color: var(--gray-500); font-weight: 500; margin-bottom: 4px; }
.kpi-value { font-size: 20px; font-weight: 800; color: var(--gray-900); letter-spacing: -0.5px; font-family: var(--font-main); margin-bottom: 6px; }
.kpi-badge { display: inline-flex; align-items: center; font-size: 11px; font-weight: 600; padding: 2px 8px; border-radius: 20px; }
.badge-paid { background: #ECFDF5; color: var(--emerald-600); }
.badge-pending { background: #FFF8E6; color: var(--amber-600); }
.badge-late { background: #FEF2F2; color: var(--red-600); }
.kpi-hint { font-size: 12px; color: var(--gray-400); }
.kpi-success { border-left: 3px solid var(--emerald-500); }
.kpi-warning { border-left: 3px solid var(--amber-500); }
.kpi-danger { border-left: 3px solid var(--red-500); }

/* ══════════════════════════════════════════════════════════════════
   CHART
══════════════════════════════════════════════════════════════════ */
.overview-lower-grid { display: grid; grid-template-columns: 1fr 360px; gap: 16px; }
.chart-card { padding: 24px; }
.card-header-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
.card-title { font-size: 15px; font-weight: 700; color: var(--gray-800); }
.card-subtitle-text { font-size: 13px; color: var(--gray-400); margin-bottom: 16px; }
.chart-legend { display: flex; align-items: center; gap: 6px; font-size: 12px; color: var(--gray-500); }
.legend-dot { width: 8px; height: 8px; border-radius: 50%; }
.legend-dot.blue { background: var(--blue-600); }
.legend-dot.amber { background: var(--amber-500); }
.chart-wrapper { overflow-x: auto; }
.expense-chart { width: 100%; height: 220px; }

/* ══════════════════════════════════════════════════════════════════
   QUICK ACTIONS
══════════════════════════════════════════════════════════════════ */
.quick-actions-card { padding: 24px; }
.quick-actions-list { display: flex; flex-direction: column; gap: 10px; margin-top: 16px; }
.quick-action-btn {
  display: flex; align-items: center; gap: 12px;
  padding: 14px 16px; border-radius: 12px;
  border: 1px solid var(--gray-200); background: white;
  cursor: pointer; text-align: left; width: 100%;
  font-family: var(--font-main); transition: all 0.2s;
  color: var(--gray-700);
}
.quick-action-btn:hover { border-color: var(--blue-200); background: var(--blue-50); transform: translateX(3px); }
.quick-action-btn.primary { background: linear-gradient(135deg, var(--blue-600), var(--blue-700)); color: white; border-color: transparent; }
.quick-action-btn.primary:hover { background: linear-gradient(135deg, var(--blue-700), var(--blue-800)); }
.qa-icon {
  width: 36px; height: 36px; border-radius: 10px;
  display: flex; align-items: center; justify-content: center;
  background: rgba(255,255,255,0.25); flex-shrink: 0;
}
.qa-icon.amber { background: #FFF8E6; color: var(--amber-500); }
.qa-icon.emerald { background: #ECFDF5; color: var(--emerald-500); }
.qa-text { flex: 1; }
.qa-text strong { display: block; font-size: 13px; font-weight: 600; }
.qa-text small { font-size: 11px; opacity: 0.7; }

/* ══════════════════════════════════════════════════════════════════
   CONTRACT / PROPERTY
══════════════════════════════════════════════════════════════════ */
.property-card { display: flex; overflow: hidden; margin-bottom: 20px; }
.property-image-wrap { width: 280px; flex-shrink: 0; position: relative; }
.property-image { width: 100%; height: 100%; object-fit: cover; min-height: 200px; background: linear-gradient(135deg, var(--blue-100), var(--blue-50)); }
.property-image-overlay { position: absolute; inset: 0; background: linear-gradient(to right, transparent, rgba(255,255,255,0.05)); }
.property-type-badge { position: absolute; top: 16px; left: 16px; background: rgba(255,255,255,0.9); backdrop-filter: blur(8px); padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; color: var(--blue-700); }
.property-details { flex: 1; padding: 24px; }
.property-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 20px; gap: 12px; }
.property-name { font-size: 18px; font-weight: 800; color: var(--gray-900); letter-spacing: -0.3px; }
.property-address { display: flex; align-items: center; gap: 4px; font-size: 13px; color: var(--gray-500); margin-top: 4px; }
.property-status-badge { background: #ECFDF5; color: var(--emerald-600); font-size: 12px; font-weight: 600; padding: 4px 12px; border-radius: 20px; border: 1px solid #A7F3D0; white-space: nowrap; }
.property-specs-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 20px; }
.spec-item { }
.spec-label { font-size: 11px; color: var(--gray-400); font-weight: 500; display: block; }
.spec-value { font-size: 13px; color: var(--gray-700); font-weight: 600; }
.equipment-section { }
.section-subtitle { font-size: 13px; font-weight: 700; color: var(--gray-700); margin-bottom: 10px; }
.equipment-tags { display: flex; flex-wrap: wrap; gap: 6px; }
.eq-tag { background: var(--blue-50); color: var(--blue-700); font-size: 12px; font-weight: 500; padding: 3px 10px; border-radius: 20px; border: 1px solid var(--blue-100); }

.bail-details-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.bail-info-card, .documents-card { padding: 24px; }
.bail-rows { display: flex; flex-direction: column; gap: 1px; margin-top: 16px; }
.bail-row { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 8px; font-size: 13px; }
.bail-row:nth-child(odd) { background: var(--gray-50); }
.bail-key { flex: 1; color: var(--gray-500); font-weight: 500; }
.bail-value { font-weight: 600; color: var(--gray-800); }
.bail-value.accent-blue { color: var(--blue-600); }
.readonly-row { opacity: 0.95; }
.readonly-tag { font-size: 10px; color: var(--gray-400); background: var(--gray-100); padding: 2px 6px; border-radius: 4px; font-weight: 500; }

.documents-list { display: flex; flex-direction: column; gap: 8px; margin-bottom: 16px; margin-top: 4px; }
.document-row { display: flex; align-items: center; gap: 10px; padding: 10px; border-radius: 10px; border: 1px solid var(--gray-100); background: white; }
.doc-icon-wrap { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.doc-ok { background: #ECFDF5; color: var(--emerald-600); }
.doc-missing { background: #FFF8E6; color: var(--amber-500); }
.doc-info { flex: 1; }
.doc-name { font-size: 13px; font-weight: 600; color: var(--gray-700); }
.doc-meta { font-size: 11px; color: var(--gray-400); margin-top: 2px; }
.doc-meta.missing { color: var(--amber-500); }
.doc-actions { display: flex; gap: 6px; flex-shrink: 0; }
.doc-btn { font-size: 12px; font-weight: 600; padding: 4px 10px; border-radius: 6px; border: none; cursor: pointer; font-family: var(--font-main); transition: all 0.2s; }
.doc-btn.view { background: var(--blue-50); color: var(--blue-600); }
.doc-btn.upload { background: white; color: var(--gray-600); border: 1px solid var(--gray-200); }
.doc-btn.view:hover { background: var(--blue-100); }
.doc-btn.upload:hover { background: var(--gray-50); border-color: var(--gray-300); }

.dropzone {
  border: 2px dashed var(--gray-200); border-radius: 12px;
  padding: 24px; text-align: center; cursor: pointer;
  transition: all 0.2s; display: flex; flex-direction: column; align-items: center; gap: 6px;
  color: var(--gray-400);
}
.dropzone:hover, .dropzone.dragging { border-color: var(--blue-400); background: var(--blue-50); color: var(--blue-500); }
.dropzone-text { font-size: 14px; font-weight: 600; }
.dropzone-hint { font-size: 12px; }
.large-dropzone { padding: 40px 24px; }

/* ══════════════════════════════════════════════════════════════════
   FINANCES
══════════════════════════════════════════════════════════════════ */
.finance-summary-row { display: grid; grid-template-columns: repeat(4,1fr); gap: 16px; margin-bottom: 20px; }
.fin-summary-card { padding: 18px 20px; }
.fin-sum-label { font-size: 12px; color: var(--gray-400); font-weight: 500; margin-bottom: 6px; }
.fin-sum-value { font-size: 22px; font-weight: 800; color: var(--gray-900); }
.fin-sum-value.blue { color: var(--blue-600); }
.fin-sum-value.amber { color: var(--amber-500); }
.fin-sum-value.red { color: var(--red-500); }

.filter-bar { padding: 14px 18px; margin-bottom: 16px; display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; }
.filter-tabs { display: flex; gap: 4px; flex-wrap: wrap; }
.filter-tab { font-size: 13px; font-weight: 500; padding: 6px 12px; border-radius: 8px; border: none; background: transparent; cursor: pointer; color: var(--gray-500); transition: all 0.2s; font-family: var(--font-main); }
.filter-tab.active { background: var(--blue-600); color: white; font-weight: 600; }
.filter-tab:hover:not(.active) { background: var(--gray-100); }
.filter-right { display: flex; align-items: center; gap: 8px; }
.search-input { padding: 7px 12px; border-radius: 8px; border: 1px solid var(--gray-200); font-size: 13px; font-family: var(--font-main); outline: none; transition: border-color 0.2s; background: white; }
.search-input:focus { border-color: var(--blue-400); }
.icon-btn { width: 34px; height: 34px; border-radius: 8px; border: 1px solid var(--gray-200); background: white; cursor: pointer; display: flex; align-items: center; justify-content: center; color: var(--gray-500); transition: all 0.2s; }
.icon-btn:hover { background: var(--blue-50); color: var(--blue-600); border-color: var(--blue-200); }

.table-card { overflow: hidden; }
.table-wrapper { overflow-x: auto; }
.premium-table { width: 100%; border-collapse: separate; border-spacing: 0; font-size: 13.5px; }
.premium-table thead tr { background: transparent; }
.premium-table th { padding: 14px 16px; text-align: left; font-size: 11px; font-weight: 700; color: var(--gray-400); text-transform: uppercase; letter-spacing: 0.7px; border-bottom: 1px solid var(--gray-100); white-space: nowrap; }
.premium-table tbody .table-row:hover { background: var(--blue-50); }
.premium-table td { padding: 13px 16px; border-bottom: 1px solid var(--gray-100); vertical-align: middle; }
.ref-code { font-family: var(--font-mono); font-size: 12px; color: var(--gray-500); background: var(--gray-100); padding: 2px 8px; border-radius: 4px; }
.type-badge { font-size: 12px; font-weight: 600; padding: 3px 10px; border-radius: 20px; }
.type-badge.type-rent { background: var(--blue-50); color: var(--blue-700); }
.type-badge.type-water { background: #EFF6FF; color: #0369A1; }
.type-badge.type-electric { background: #FFF8E6; color: var(--amber-600); }
.period-cell { color: var(--gray-600); font-weight: 500; }
.amount-cell strong { color: var(--gray-900); font-weight: 700; }
.conso-cell { font-size: 13px; color: var(--gray-600); }
.conso-index { display: block; font-family: var(--font-mono); font-size: 11px; color: var(--gray-400); }
.status-pill { display: inline-flex; align-items: center; font-size: 12px; font-weight: 600; padding: 3px 10px; border-radius: 20px; }
.status-paid { background: #ECFDF5; color: var(--emerald-600); }
.status-pending { background: #FFF8E6; color: var(--amber-600); }
.status-late { background: #FEF2F2; color: var(--red-600); }
.action-buttons { display: flex; gap: 6px; }
.action-btn { font-size: 12px; font-weight: 600; padding: 5px 10px; border-radius: 6px; border: none; cursor: pointer; font-family: var(--font-main); transition: all 0.2s; }
.pay-btn { background: var(--blue-600); color: white; }
.pay-btn:hover { background: var(--blue-700); }
.receipt-btn { background: var(--gray-100); color: var(--gray-700); display: flex; align-items: center; gap: 4px; }
.receipt-btn:hover { background: var(--gray-200); }
.upload-proof-btn { background: #FFF8E6; color: var(--amber-700); }
.upload-proof-btn:hover { background: #FEF3C7; }
.empty-row { text-align: center; padding: 40px; color: var(--gray-400); font-style: italic; }
.text-gray-400 { color: var(--gray-400); }

/* ══════════════════════════════════════════════════════════════════
   SUPPORT / TICKETS
══════════════════════════════════════════════════════════════════ */
.support-layout { display: grid; grid-template-columns: 360px 1fr; gap: 16px; min-height: 600px; }
.tickets-panel { display: flex; flex-direction: column; gap: 10px; overflow-y: auto; max-height: 80vh; padding-right: 4px; }
.tickets-panel::-webkit-scrollbar { width: 4px; }
.tickets-panel::-webkit-scrollbar-thumb { background: var(--gray-200); border-radius: 2px; }
.ticket-card { padding: 16px; cursor: pointer; transition: all 0.2s; }
.ticket-card:hover { transform: translateX(2px); }
.ticket-selected { border-color: var(--blue-300) !important; background: rgba(37,99,235,0.04) !important; }
.ticket-header { display: flex; align-items: flex-start; gap: 10px; margin-bottom: 8px; }
.ticket-category-icon { width: 30px; height: 30px; border-radius: 8px; background: var(--blue-50); color: var(--blue-600); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.ticket-meta { flex: 1; }
.ticket-title { font-size: 13px; font-weight: 600; color: var(--gray-800); line-height: 1.4; }
.ticket-date { font-size: 11px; color: var(--gray-400); margin-top: 2px; }
.ticket-status-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; margin-top: 5px; }
.dot-open { background: var(--blue-500); }
.dot-in_progress { background: var(--amber-500); }
.dot-closed { background: var(--gray-300); }
.ticket-preview { font-size: 12px; color: var(--gray-500); line-height: 1.5; margin-bottom: 10px; }
.ticket-footer { display: flex; align-items: center; justify-content: space-between; }
.ticket-status-label { font-size: 11px; font-weight: 600; padding: 2px 8px; border-radius: 20px; }
.ts-open { background: var(--blue-50); color: var(--blue-600); }
.ts-in_progress { background: #FFF8E6; color: var(--amber-600); }
.ts-closed { background: var(--gray-100); color: var(--gray-500); }
.ticket-msgs { display: flex; align-items: center; gap: 4px; font-size: 12px; color: var(--gray-400); }

.chat-panel { display: flex; flex-direction: column; max-height: 80vh; overflow: hidden; }
.chat-header { padding: 18px 20px; border-bottom: 1px solid var(--gray-100); display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
.chat-header-left { display: flex; align-items: center; gap: 12px; }
.chat-cat-icon { width: 36px; height: 36px; background: var(--blue-50); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--blue-600); }
.chat-title { font-size: 14px; font-weight: 700; color: var(--gray-800); }
.chat-header-actions { display: flex; gap: 8px; }
.chat-messages { flex: 1; overflow-y: auto; padding: 20px; display: flex; flex-direction: column; gap: 16px; }
.chat-messages::-webkit-scrollbar { width: 4px; }
.chat-messages::-webkit-scrollbar-thumb { background: var(--gray-200); border-radius: 2px; }
.chat-message { display: flex; gap: 8px; align-items: flex-end; }
.msg-tenant { flex-direction: row-reverse; }
.msg-bubble { max-width: 70%; }
.msg-text {
  padding: 10px 14px; border-radius: 16px; font-size: 13.5px; line-height: 1.5;
  .msg-tenant & { background: var(--blue-600); color: white; border-bottom-right-radius: 4px; }
  .msg-manager & { background: white; color: var(--gray-700); border: 1px solid var(--gray-200); border-bottom-left-radius: 4px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); }
}
.msg-time { font-size: 10px; color: var(--gray-400); margin-top: 4px; display: block; .msg-tenant & { text-align: right; } }
.msg-avatar { width: 28px; height: 28px; border-radius: 50%; background: var(--gray-100); display: flex; align-items: center; justify-content: center; color: var(--gray-500); flex-shrink: 0; }
.chat-input-row { padding: 16px 20px; border-top: 1px solid var(--gray-100); display: flex; gap: 10px; }
.chat-input { flex: 1; padding: 10px 14px; border-radius: 12px; border: 1px solid var(--gray-200); font-size: 13.5px; font-family: var(--font-main); outline: none; transition: border-color 0.2s; }
.chat-input:focus { border-color: var(--blue-400); }
.send-btn { width: 40px; height: 40px; border-radius: 12px; background: var(--blue-600); color: white; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s; flex-shrink: 0; }
.send-btn:hover:not(:disabled) { background: var(--blue-700); }
.send-btn:disabled { opacity: 0.4; cursor: not-allowed; }
.ticket-closed-notice { padding: 14px 20px; border-top: 1px solid var(--gray-100); display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--gray-400); }
.chat-empty, .empty-state { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 12px; color: var(--gray-400); font-size: 14px; padding: 40px; }

/* ══════════════════════════════════════════════════════════════════
   PROFILE
══════════════════════════════════════════════════════════════════ */
.profile-layout { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.profile-card, .security-card { padding: 28px; grid-column: auto; }
.notif-card { padding: 28px; grid-column: 1 / -1; }
.avatar-upload-section { display: flex; align-items: center; gap: 16px; margin-bottom: 24px; }
.avatar-large-wrap { position: relative; flex-shrink: 0; }
.avatar-large { width: 72px; height: 72px; border-radius: 50%; object-fit: cover; border: 3px solid white; box-shadow: 0 4px 16px rgba(37,99,235,0.15); }
.avatar-edit-btn { position: absolute; bottom: 0; right: 0; width: 24px; height: 24px; border-radius: 50%; background: var(--blue-600); color: white; border: 2px solid white; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s; }
.avatar-edit-btn:hover { background: var(--blue-700); transform: scale(1.1); }
.avatar-name { font-weight: 700; color: var(--gray-800); }
.avatar-email { font-size: 13px; color: var(--gray-400); }
.hidden-input { display: none; }
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.form-group { display: flex; flex-direction: column; gap: 6px; }
.form-group label { font-size: 12px; font-weight: 600; color: var(--gray-600); }
.form-input { padding: 9px 12px; border: 1px solid var(--gray-200); border-radius: 10px; font-size: 13.5px; font-family: var(--font-main); outline: none; transition: border-color 0.2s, box-shadow 0.2s; background: white; color: var(--gray-800); }
.form-input:focus { border-color: var(--blue-400); box-shadow: 0 0 0 3px rgba(37,99,235,0.08); }
.form-select { appearance: none; cursor: pointer; }
.form-textarea { resize: vertical; min-height: 100px; }
.form-actions { margin-top: 20px; display: flex; justify-content: flex-end; }
.password-input-wrap { position: relative; }
.password-input-wrap .form-input { padding-right: 40px; }
.pwd-toggle { position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--gray-400); display: flex; }
.pwd-toggle:hover { color: var(--gray-600); }
.password-strength { margin-top: 8px; display: flex; align-items: center; gap: 8px; }
.strength-bar { height: 4px; flex: 1; border-radius: 2px; background: var(--gray-200); overflow: hidden; }
.strength-fill { height: 100%; border-radius: 2px; }
.strength-weak .strength-fill { width: 25%; background: var(--red-500); }
.strength-medium .strength-fill { width: 50%; background: var(--amber-500); }
.strength-strong .strength-fill { width: 75%; background: var(--blue-500); }
.strength-very-strong .strength-fill { width: 100%; background: var(--emerald-500); }
.strength-label { font-size: 11px; font-weight: 600; color: var(--gray-500); white-space: nowrap; }
.field-error { font-size: 11px; color: var(--red-500); font-weight: 500; }
.security-section { margin-bottom: 28px; padding-bottom: 28px; border-bottom: 1px solid var(--gray-100); }
.security-section:last-child { margin-bottom: 0; padding-bottom: 0; border-bottom: none; }
.section-desc { font-size: 12px; color: var(--gray-400); margin-top: 2px; }
.tfa-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 16px; margin-bottom: 12px; }
.toggle-wrap { display: flex; align-items: center; gap: 8px; }
.toggle-switch { width: 44px; height: 24px; border-radius: 12px; background: var(--gray-200); cursor: pointer; position: relative; transition: background 0.2s; flex-shrink: 0; }
.toggle-switch.on { background: var(--blue-600); }
.toggle-thumb { position: absolute; top: 2px; left: 2px; width: 20px; height: 20px; border-radius: 50%; background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.15); transition: transform 0.2s; }
.toggle-switch.on .toggle-thumb { transform: translateX(20px); }
.toggle-label { font-size: 13px; font-weight: 600; color: var(--gray-600); }
.tfa-setup-box { background: var(--blue-50); border: 1px solid var(--blue-100); border-radius: 12px; padding: 16px; }
.tfa-instruction { font-size: 13px; color: var(--gray-600); margin-bottom: 12px; }
.tfa-qr-placeholder { display: flex; flex-direction: column; align-items: center; gap: 8px; }
.tfa-code-text { font-family: var(--font-mono); font-size: 14px; color: var(--blue-700); background: white; padding: 6px 14px; border-radius: 6px; letter-spacing: 2px; }
.login-history-list { display: flex; flex-direction: column; gap: 8px; margin-top: 12px; }
.login-row { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 8px; background: var(--gray-50); }
.login-device-icon { color: var(--gray-500); flex-shrink: 0; }
.login-info { flex: 1; }
.login-device { font-size: 13px; font-weight: 600; color: var(--gray-700); }
.login-ip { font-size: 11px; color: var(--gray-400); font-family: var(--font-mono); margin-top: 2px; }
.login-status-tag { font-size: 11px; font-weight: 600; padding: 2px 8px; border-radius: 6px; background: var(--gray-100); color: var(--gray-500); }
.login-status-tag.current { background: #ECFDF5; color: var(--emerald-600); }
.notif-grid { display: flex; flex-direction: column; gap: 16px; margin-bottom: 20px; }
.notif-row { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 14px; border-radius: 10px; border: 1px solid var(--gray-100); background: var(--gray-50); flex-wrap: wrap; }
.notif-name { font-size: 13px; font-weight: 600; color: var(--gray-800); }
.notif-desc { font-size: 12px; color: var(--gray-400); margin-top: 2px; }
.notif-channels { display: flex; gap: 12px; flex-wrap: wrap; }
.channel-checkbox { display: flex; align-items: center; gap: 5px; cursor: pointer; }
.channel-checkbox input { accent-color: var(--blue-600); width: 14px; height: 14px; cursor: pointer; }
.checkbox-label { font-size: 12px; font-weight: 500; color: var(--gray-600); }

/* ══════════════════════════════════════════════════════════════════
   BUTTONS
══════════════════════════════════════════════════════════════════ */
.btn-primary {
  display: inline-flex; align-items: center; gap: 7px;
  padding: 9px 18px; border-radius: 10px;
  background: var(--blue-600); color: white;
  font-size: 13.5px; font-weight: 600; font-family: var(--font-main);
  border: none; cursor: pointer; transition: all 0.2s;
  white-space: nowrap;
}
.btn-primary:hover { background: var(--blue-700); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(37,99,235,0.25); }
.btn-primary:active { transform: translateY(0); }
.btn-primary.small { padding: 6px 12px; font-size: 12px; }
.btn-primary.full-width { width: 100%; justify-content: center; padding: 12px; font-size: 15px; }
.btn-primary.loading { opacity: 0.7; cursor: not-allowed; }
.btn-secondary { display: inline-flex; align-items: center; gap: 7px; padding: 7px 14px; border-radius: 10px; background: white; color: var(--gray-700); font-size: 13px; font-weight: 600; font-family: var(--font-main); border: 1px solid var(--gray-200); cursor: pointer; transition: all 0.2s; }
.btn-secondary:hover { background: var(--gray-50); border-color: var(--gray-300); }
.btn-secondary.small { padding: 5px 10px; font-size: 12px; }
.btn-danger { display: inline-flex; align-items: center; gap: 7px; padding: 7px 14px; border-radius: 10px; background: #FEF2F2; color: var(--red-600); font-size: 13px; font-weight: 600; font-family: var(--font-main); border: 1px solid #FCA5A5; cursor: pointer; transition: all 0.2s; }
.btn-danger:hover { background: #FEE2E2; }
.btn-danger.small { padding: 5px 10px; font-size: 12px; }
.spinner-sm { width: 14px; height: 14px; border-radius: 50%; border: 2px solid rgba(255,255,255,0.4); border-top-color: white; animation: spin 0.7s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }
.loading-dots { display: flex; gap: 2px; }
.loading-dots span { animation: blink 1.2s ease-in-out infinite; font-size: 20px; }
.loading-dots span:nth-child(2) { animation-delay: 0.2s; }
.loading-dots span:nth-child(3) { animation-delay: 0.4s; }
@keyframes blink { 0%,80%,100%{opacity:0.15} 40%{opacity:1} }

/* ══════════════════════════════════════════════════════════════════
   MODALS
══════════════════════════════════════════════════════════════════ */
.modal-overlay { position: fixed; inset: 0; background: rgba(15,23,42,0.4); backdrop-filter: blur(6px); z-index: 200; display: flex; align-items: center; justify-content: center; padding: 20px; }
.modal-box { background: white; border-radius: var(--radius-2xl); padding: 28px; width: 100%; box-shadow: 0 24px 80px rgba(0,0,0,0.15); display: flex; flex-direction: column; gap: 20px; }
.payment-modal { max-width: 460px; }
.ticket-modal { max-width: 520px; }
.proof-modal { max-width: 440px; }
.modal-header { display: flex; align-items: center; justify-content: space-between; }
.modal-title { font-size: 18px; font-weight: 800; color: var(--gray-900); }
.modal-subtitle { font-size: 13px; color: var(--gray-500); }
.modal-close { width: 32px; height: 32px; border-radius: 8px; border: 1px solid var(--gray-200); background: white; cursor: pointer; display: flex; align-items: center; justify-content: center; color: var(--gray-500); transition: all 0.2s; }
.modal-close:hover { background: var(--gray-100); }
.payment-summary { background: var(--blue-50); border: 1px solid var(--blue-100); border-radius: 12px; padding: 16px 20px; text-align: center; }
.payment-label { font-size: 12px; color: var(--gray-500); font-weight: 500; }
.payment-amount { font-size: 32px; font-weight: 800; color: var(--blue-700); letter-spacing: -1px; margin: 4px 0; }
.payment-ref { font-size: 12px; color: var(--gray-400); font-family: var(--font-mono); }
.payment-methods { }
.methods-label { font-size: 13px; font-weight: 600; color: var(--gray-600); margin-bottom: 12px; }
.methods-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 8px; }
.method-card { padding: 14px 8px; border-radius: 12px; border: 1.5px solid var(--gray-200); background: white; cursor: pointer; text-align: center; transition: all 0.2s; display: flex; flex-direction: column; align-items: center; gap: 8px; font-family: var(--font-main); }
.method-card:hover { border-color: var(--blue-300); background: var(--blue-50); }
.method-card.selected { border-color: var(--blue-600); background: var(--blue-50); box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
.method-icon { color: var(--gray-600); }
.method-card.selected .method-icon { color: var(--blue-600); }
.method-name { font-size: 12px; font-weight: 600; color: var(--gray-600); }
.method-card.selected .method-name { color: var(--blue-700); }
.mobile-operators { display: flex; gap: 8px; margin-top: 8px; }
.operator-btn { flex: 1; padding: 8px; border-radius: 8px; border: 1.5px solid var(--gray-200); background: white; cursor: pointer; font-size: 12px; font-weight: 600; font-family: var(--font-main); color: var(--gray-600); transition: all 0.2s; }
.operator-btn.selected { border-color: var(--blue-600); background: var(--blue-50); color: var(--blue-700); }
.priority-selector { display: flex; gap: 6px; }
.priority-btn { flex: 1; padding: 8px; border-radius: 8px; border: 1.5px solid var(--gray-200); background: white; cursor: pointer; font-size: 12px; font-weight: 600; font-family: var(--font-main); color: var(--gray-600); transition: all 0.2s; text-align: center; }
.prio-low.selected { background: #ECFDF5; border-color: var(--emerald-500); color: var(--emerald-700); }
.prio-medium.selected { background: var(--blue-50); border-color: var(--blue-500); color: var(--blue-700); }
.prio-high.selected { background: #FFF8E6; border-color: var(--amber-500); color: var(--amber-700); }
.prio-urgent.selected { background: #FEF2F2; border-color: var(--red-500); color: var(--red-700); }
.proof-file-preview { display: flex; align-items: center; gap: 8px; padding: 10px 12px; background: #ECFDF5; border-radius: 8px; font-size: 13px; color: var(--gray-700); font-weight: 500; }

/* ══════════════════════════════════════════════════════════════════
   TOAST
══════════════════════════════════════════════════════════════════ */
.toast { position: fixed; bottom: 28px; right: 28px; z-index: 9999; display: flex; align-items: center; gap: 10px; padding: 14px 20px; border-radius: 12px; font-size: 14px; font-weight: 500; font-family: var(--font-main); box-shadow: 0 8px 32px rgba(0,0,0,0.12); max-width: 380px; }
.toast-success { background: white; color: var(--gray-800); border-left: 4px solid var(--emerald-500); }
.toast-success svg { color: var(--emerald-500); flex-shrink: 0; }
.toast-error { background: white; color: var(--gray-800); border-left: 4px solid var(--red-500); }
.toast-error svg { color: var(--red-500); flex-shrink: 0; }

/* ══════════════════════════════════════════════════════════════════
   TRANSITIONS
══════════════════════════════════════════════════════════════════ */
.fade-slide-enter-active, .fade-slide-leave-active { transition: all 0.25s ease; }
.fade-slide-enter-from { opacity: 0; transform: translateX(-8px); }
.fade-slide-leave-to { opacity: 0; transform: translateX(-8px); }
.page-transition-enter-active { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
.page-transition-leave-active { transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); }
.page-transition-enter-from { opacity: 0; transform: translateY(12px); }
.page-transition-leave-to { opacity: 0; transform: translateY(-8px); }
.modal-enter-active, .modal-leave-active { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
.modal-enter-from, .modal-leave-to { opacity: 0; }
.modal-enter-from .modal-box, .modal-leave-to .modal-box { transform: scale(0.95) translateY(20px); }
.modal-box { transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
.toast-enter-active, .toast-leave-active { transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1); }
.toast-enter-from { opacity: 0; transform: translateX(40px); }
.toast-leave-to { opacity: 0; transform: translateX(40px); }

/* ══════════════════════════════════════════════════════════════════
   RESPONSIVE — MOBILE
══════════════════════════════════════════════════════════════════ */
@media (max-width: 1200px) {
  .kpi-grid { grid-template-columns: repeat(2, 1fr); }
  .overview-lower-grid { grid-template-columns: 1fr; }
  .finance-summary-row { grid-template-columns: repeat(2, 1fr); }
  .bail-details-grid { grid-template-columns: 1fr; }
  .support-layout { grid-template-columns: 1fr; }
  .profile-layout { grid-template-columns: 1fr; }
  .property-card { flex-direction: column; }
  .property-image-wrap { width: 100%; height: 200px; }
}

@media (max-width: 768px) {
  .sidebar { transform: translateX(-100%); transition: transform 0.3s ease, width 0.3s ease; width: var(--sidebar-w) !important; }
  .mobile-open .sidebar { transform: translateX(0); }
  .main-content { margin-left: 0 !important; }
  .mobile-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.4); z-index: 90; }
  .mobile-header { display: flex; align-items: center; justify-content: space-between; padding: 16px 20px; background: rgba(255,255,255,0.88); backdrop-filter: blur(12px); border-bottom: 1px solid var(--gray-100); position: sticky; top: 0; z-index: 50; }
  .burger-btn { width: 36px; height: 36px; border-radius: 8px; border: 1px solid var(--gray-200); background: white; cursor: pointer; display: flex; align-items: center; justify-content: center; color: var(--gray-600); }
  .mobile-title { font-weight: 700; font-size: 15px; color: var(--gray-800); }
  .mobile-avatar img { width: 32px; height: 32px; border-radius: 50%; object-fit: cover; }
  .page-panel { padding: 16px; }
  .kpi-grid { grid-template-columns: 1fr 1fr; gap: 10px; }
  .kpi-value { font-size: 17px; }
  .finance-summary-row { grid-template-columns: 1fr 1fr; }
  .page-title { font-size: 20px; }
  .methods-grid { grid-template-columns: 1fr; }
  .form-grid, .form-grid-2 { grid-template-columns: 1fr; }
  .property-specs-grid { grid-template-columns: repeat(2, 1fr); }
  .tickets-panel { max-height: none; }
}
</style>