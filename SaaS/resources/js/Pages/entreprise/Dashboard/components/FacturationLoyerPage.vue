<template>
    <div class="flex flex-col gap-8 select-none">
        
        <!-- Header with Luxury Dark Blue Banner -->
        <div class="bg-gradient-to-br from-slate-900 via-slate-950 to-slate-900 rounded-3xl p-8 border border-slate-800 shadow-2xl text-white relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-48 h-48 rounded-full bg-blue-500/10 blur-2xl pointer-events-none"></div>
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 relative z-10">
                <div>
                    <span class="text-blue-400 text-xs font-bold tracking-widest uppercase bg-blue-500/20 px-3 py-1 rounded-full border border-blue-500/30">Comptabilité Locative</span>
                    <h1 class="text-3xl font-extrabold bg-gradient-to-r from-white via-slate-100 to-slate-300 bg-clip-text text-transparent mt-2">Facturation/quittances</h1>
                    <p class="text-slate-400 text-sm mt-1">Émettez des factures et quittances pour vos locataires actifs et suivez le recouvrement en temps réel.</p>
                </div>
                <button
                    @click="openAddModal"
                    class="px-5 py-3 rounded-2xl bg-blue-600 hover:bg-blue-500 border border-blue-500/50 hover:border-blue-400 text-white font-bold text-xs transition-all duration-300 transform hover:scale-[1.02] flex items-center gap-2 shadow-lg shadow-blue-600/35"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Générer une Facture
                </button>
            </div>
        </div>

        <!-- Premium KPI Cards (Dynamically responsive to filters) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-3xl p-6 shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-blue-200/30 transition-all duration-500 hover:-translate-y-1 border border-slate-150 relative overflow-hidden group">
                <div class="absolute -right-6 -bottom-6 w-20 h-20 rounded-full bg-blue-500/5 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 bg-blue-550/10 rounded-xl flex items-center justify-center text-blue-600 font-bold shadow-sm">
                        <span>{{ deviseSymbol }}</span>
                    </div>
                </div>
                <div class="text-2xl font-extrabold text-slate-800 mb-1 tracking-tight">{{ formatCurrency(totalInvoiced) }}</div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Facturé</div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-emerald-200/30 transition-all duration-500 hover:-translate-y-1 border border-slate-150 relative overflow-hidden group">
                <div class="absolute -right-6 -bottom-6 w-20 h-20 rounded-full bg-emerald-500/5 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 bg-emerald-550/10 rounded-xl flex items-center justify-center text-emerald-600 font-bold shadow-sm">✓</div>
                </div>
                <div class="text-2xl font-extrabold text-emerald-600 mb-1 tracking-tight">{{ formatCurrency(totalPaid) }}</div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Montant Recouvré</div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-rose-200/30 transition-all duration-500 hover:-translate-y-1 border border-slate-150 relative overflow-hidden group">
                <div class="absolute -right-6 -bottom-6 w-20 h-20 rounded-full bg-rose-500/5 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 bg-rose-550/10 rounded-xl flex items-center justify-center text-rose-600 font-bold shadow-sm">!</div>
                </div>
                <div class="text-2xl font-extrabold text-rose-600 mb-1 tracking-tight">{{ formatCurrency(totalDue) }}</div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Solde Dû / Impayés</div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-violet-200/30 transition-all duration-500 hover:-translate-y-1 border border-slate-150 relative overflow-hidden group">
                <div class="absolute -right-6 -bottom-6 w-20 h-20 rounded-full bg-violet-500/5 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 bg-violet-550/10 rounded-xl flex items-center justify-center text-violet-600 font-bold shadow-sm">%</div>
                </div>
                <div class="text-2xl font-extrabold text-violet-600 mb-1 tracking-tight">{{ recoveryRate }}%</div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Taux de Recouvrement</div>
            </div>
        </div>

        <!-- Filter Panel -->
        <div class="bg-gradient-to-br from-white to-slate-50 rounded-3xl p-6 shadow-xl shadow-slate-200/50 border border-slate-150 flex flex-col gap-4">
            <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                <div class="relative w-full md:w-96">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-450" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Rechercher par locataire, facture, logement..."
                        class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-2xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm font-semibold transition-all shadow-sm"
                    />
                </div>
            </div>

            <!-- Advanced Filters Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4 pt-2">
                <!-- 1. Agency Filter -->
                <div class="flex flex-col gap-1">
                    <span class="text-[10px] font-bold uppercase text-slate-400 tracking-wider">Filtrer par Agence :</span>
                    <select v-model="agencyFilter" class="bg-white border border-slate-200 rounded-2xl px-4 py-2.5 text-xs font-bold text-slate-700 focus:outline-none shadow-sm cursor-pointer">
                        <option value="All">Toutes les agences</option>
                        <option value="siege">Siège Social</option>
                        <option v-for="agency in agencies" :key="agency.id" :value="agency.id">
                            {{ agency.name }}
                        </option>
                    </select>
                </div>

                <!-- 2. Invoice Type Filter -->
                <div class="flex flex-col gap-1">
                    <span class="text-[10px] font-bold uppercase text-slate-400 tracking-wider">Type de Facture :</span>
                    <select v-model="typeFactureFilter" class="bg-white border border-slate-200 rounded-2xl px-4 py-2.5 text-xs font-bold text-slate-700 focus:outline-none shadow-sm cursor-pointer">
                        <option value="All">Tous les types</option>
                        <option v-for="type in typeFactures" :key="type.id" :value="type.id">
                            {{ type.nom }}
                        </option>
                    </select>
                </div>

                <!-- 3. Period Filter (Select format) -->
                <div class="flex flex-col gap-1">
                    <span class="text-[10px] font-bold uppercase text-slate-400 tracking-wider">Période :</span>
                    <select v-model="periodFilter" class="bg-white border border-slate-200 rounded-2xl px-4 py-2.5 text-xs font-bold text-slate-700 focus:outline-none shadow-sm cursor-pointer">
                        <option value="All">Toutes les périodes</option>
                        <option v-for="period in availablePeriods" :key="period" :value="period">
                            {{ formatPeriod(period) }}
                        </option>
                    </select>
                </div>

                <!-- 4. Status Filter -->
                <div class="flex flex-col gap-1">
                    <span class="text-[10px] font-bold uppercase text-slate-400 tracking-wider">Statut :</span>
                    <select v-model="statusFilter" class="bg-white border border-slate-200 rounded-2xl px-4 py-2.5 text-xs font-bold text-slate-700 focus:outline-none shadow-sm cursor-pointer">
                        <option value="All">Tous les statuts</option>
                        <option value="Payé">Payé</option>
                        <option value="Impayé">Impayé</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-gradient-to-br from-white to-slate-50 rounded-3xl shadow-xl shadow-slate-200/50 overflow-hidden border border-slate-150">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-200/50 bg-slate-50/50">
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Facture</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Locataire</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Logement</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Type / Période</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Reçu</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-150">
                        <tr v-for="inv in filteredInvoices" :key="inv.id" class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 text-sm font-bold text-slate-800">
                                <div>{{ inv.numero }}</div>
                                <div class="text-[10px] text-slate-400 font-medium">Émise le {{ formatDateFr(inv.dateEmission) }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div :class="['w-9 h-9 rounded-xl flex items-center justify-center text-white font-extrabold text-xs shadow-sm bg-gradient-to-br', getAvatarGradient(inv.locataire)]">
                                        {{ getInitials(inv.locataire) }}
                                    </div>
                                    <div class="font-bold text-slate-800 text-sm">{{ inv.locataire }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-650 font-semibold">
                                <div>{{ inv.logement }}</div>
                                <div class="text-[10px] text-slate-400 font-medium">{{ inv.batiment }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-650 font-semibold">
                                <div class="font-bold text-slate-750">{{ inv.type_facture_nom }}</div>
                                <div class="text-[10px] text-slate-400" v-if="inv.periode">{{ formatPeriod(inv.periode) }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-800 font-extrabold">{{ formatCurrency(inv.total) }}</td>
                            <td class="px-6 py-4 text-sm text-emerald-600 font-bold">
                                <div>{{ formatCurrency(inv.montantPaye) }}</div>
                                <div class="text-[9px] text-slate-400 font-medium" v-if="inv.modeReglement">via {{ inv.modeReglement }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span :class="[
                                    'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border',
                                    inv.statut === 'Payé' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-red-50 text-red-700 border-red-200'
                                ]">
                                    <span :class="['w-1.5 h-1.5 rounded-full', inv.statut === 'Payé' ? 'bg-emerald-500' : 'bg-red-500 animate-pulse']"></span>
                                    {{ inv.statut }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2 items-center">
                                    <!-- Paid status: Settle payment button is hidden -->
                                    <button 
                                        v-if="inv.statut !== 'Payé'"
                                        @click="openPaymentModal(inv)" 
                                        class="px-2.5 py-1.5 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-500 rounded-lg shadow-sm transition-all"
                                        title="Enregistrer un règlement"
                                    >
                                        Régler
                                    </button>
                                    
                                    <!-- Details Button -->
                                    <button 
                                        @click="viewInvoice(inv)" 
                                        class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 shadow-sm"
                                        title="Voir les détails"
                                    >
                                        Détails
                                    </button>

                                    <!-- Delete: disappears when paid -->
                                    <button 
                                        v-if="inv.statut !== 'Payé'"
                                        @click="deleteInvoice(inv)" 
                                        class="p-2 text-red-650 hover:bg-red-50 rounded-xl transition-all" 
                                        title="Supprimer"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="filteredInvoices.length === 0">
                            <td colspan="8" class="px-6 py-12 text-center text-slate-400 font-semibold text-sm">
                                Aucune facture émise trouvée.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Modal (Generate Invoices) -->
        <div v-if="showModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-6xl w-full p-8 border border-slate-200 relative overflow-hidden animate-scale-up grid grid-cols-1 lg:grid-cols-12 gap-8 max-h-[90vh]">
                
                <!-- Left: Form Editor (col-span-7) -->
                <div class="lg:col-span-7 flex flex-col justify-between max-h-[80vh] overflow-y-auto pr-4 scrollbar-thin">
                    <div>
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-650 flex items-center justify-center shadow-lg shadow-blue-500/30">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h2 class="text-2xl font-extrabold text-slate-800">Générer une Facture</h2>
                        </div>

                        <div class="space-y-4">
                            <!-- 1. Agency Searchable Select -->
                            <div class="relative">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Agence *</label>
                                <div class="relative">
                                    <button 
                                        type="button" 
                                        @click="toggleAgencyDropdown" 
                                        class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl flex justify-between items-center text-sm font-semibold text-slate-805 shadow-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                                    >
                                        <span>{{ selectedAgencyName || 'Sélectionner l\'agence' }}</span>
                                        <svg class="w-4 h-4 text-slate-400 transition-transform" :class="{'rotate-180': isAgencyDropdownOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    <div v-if="isAgencyDropdownOpen" class="absolute z-30 w-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-xl p-2 max-h-60 overflow-y-auto" @click.stop>
                                        <input 
                                            v-model="agencySearchQuery" 
                                            type="text" 
                                            placeholder="Rechercher une agence..." 
                                            class="w-full px-3 py-2 text-xs font-semibold border border-slate-200 rounded-xl mb-2 focus:outline-none focus:border-blue-500"
                                        />
                                        <div class="space-y-1">
                                            <button 
                                                v-for="agency in filteredAgenciesDropdown" 
                                                :key="agency.id" 
                                                @click="selectAgency(agency)" 
                                                type="button" 
                                                class="w-full text-left px-3 py-2 text-xs font-bold rounded-lg hover:bg-slate-50 text-slate-700 hover:text-blue-600 transition-colors"
                                            >
                                                {{ agency.name }}
                                            </button>
                                            <div v-if="filteredAgenciesDropdown.length === 0" class="text-center py-4 text-xs text-slate-400">
                                                Aucun résultat trouvé
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 2. Building Searchable Select -->
                            <div class="relative" v-if="selectedAgencyId">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Bâtiment *</label>
                                <div class="relative">
                                    <button 
                                        type="button" 
                                        @click="toggleBuildingDropdown" 
                                        class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl flex justify-between items-center text-sm font-semibold text-slate-805 shadow-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                                    >
                                        <span>{{ selectedBuildingName || 'Sélectionner le bâtiment' }}</span>
                                        <svg class="w-4 h-4 text-slate-400 transition-transform" :class="{'rotate-180': isBuildingDropdownOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    <div v-if="isBuildingDropdownOpen" class="absolute z-30 w-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-xl p-2 max-h-60 overflow-y-auto" @click.stop>
                                        <input 
                                            v-model="buildingSearchQuery" 
                                            type="text" 
                                            placeholder="Rechercher un bâtiment..." 
                                            class="w-full px-3 py-2 text-xs font-semibold border border-slate-200 rounded-xl mb-2 focus:outline-none focus:border-blue-500"
                                        />
                                        <div class="space-y-1">
                                            <button 
                                                v-for="building in filteredBuildingsDropdown" 
                                                :key="building.id" 
                                                @click="selectBuilding(building)" 
                                                type="button" 
                                                class="w-full text-left px-3 py-2 text-xs font-bold rounded-lg hover:bg-slate-50 text-slate-700 hover:text-blue-600 transition-colors"
                                            >
                                                {{ building.nom }}
                                            </button>
                                            <div v-if="filteredBuildingsDropdown.length === 0" class="text-center py-4 text-xs text-slate-400">
                                                Aucun résultat trouvé
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 3. Tenant Searchable Select -->
                            <div class="relative" v-if="selectedBuildingId">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Locataire Actif *</label>
                                <div class="relative">
                                    <button 
                                        type="button" 
                                        @click="toggleTenantDropdown" 
                                        class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl flex justify-between items-center text-sm font-semibold text-slate-805 shadow-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                                    >
                                        <span>{{ selectedTenantLabel || 'Sélectionner le locataire' }}</span>
                                        <svg class="w-4 h-4 text-slate-400 transition-transform" :class="{'rotate-180': isTenantDropdownOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    <div v-if="isTenantDropdownOpen" class="absolute z-30 w-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-xl p-2 max-h-60 overflow-y-auto" @click.stop>
                                        <input 
                                            v-model="tenantSearchQuery" 
                                            type="text" 
                                            placeholder="Rechercher un locataire..." 
                                            class="w-full px-3 py-2 text-xs font-semibold border border-slate-200 rounded-xl mb-2 focus:outline-none focus:border-blue-500"
                                        />
                                        <div class="space-y-1">
                                            <button 
                                                v-for="contract in filteredTenantsDropdown" 
                                                :key="contract.id" 
                                                @click="selectTenant(contract)" 
                                                type="button" 
                                                class="w-full text-left px-3 py-2 text-xs font-bold rounded-lg hover:bg-slate-50 text-slate-700 hover:text-blue-600 transition-colors"
                                            >
                                                {{ contract.locataire }} (Bail {{ contract.numero }} - Loyer: {{ contract.loyer }} {{ deviseSymbol }})
                                            </button>
                                            <div v-if="filteredTenantsDropdown.length === 0" class="text-center py-4 text-xs text-slate-400">
                                                Aucun résultat trouvé
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 4. Invoice Type Searchable Select -->
                            <div class="relative" v-if="selectedLocataireId">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Type de Facture *</label>
                                <div class="relative">
                                    <button 
                                        type="button" 
                                        @click="toggleTypeFactureDropdown" 
                                        class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl flex justify-between items-center text-sm font-semibold text-slate-805 shadow-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                                    >
                                        <span>{{ selectedTypeFactureNom || 'Sélectionner le type de facture' }}</span>
                                        <svg class="w-4 h-4 text-slate-400 transition-transform" :class="{'rotate-180': isTypeFactureDropdownOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    <div v-if="isTypeFactureDropdownOpen" class="absolute z-30 w-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-xl p-2 max-h-60 overflow-y-auto" @click.stop>
                                        <input 
                                            v-model="typeFactureSearchQuery" 
                                            type="text" 
                                            placeholder="Rechercher un type..." 
                                            class="w-full px-3 py-2 text-xs font-semibold border border-slate-200 rounded-xl mb-2 focus:outline-none focus:border-blue-500"
                                        />
                                        <div class="space-y-1">
                                            <button 
                                                v-for="type in filteredTypeFacturesDropdown" 
                                                :key="type.id" 
                                                @click="selectTypeFacture(type)" 
                                                type="button" 
                                                class="w-full text-left px-3 py-2 text-xs font-bold rounded-lg hover:bg-slate-50 text-slate-700 hover:text-blue-600 transition-colors"
                                            >
                                                {{ type.nom }}
                                            </button>
                                            <div v-if="filteredTypeFacturesDropdown.length === 0" class="text-center py-4 text-xs text-slate-400">
                                                Aucun résultat trouvé
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Billing Month (Period) & Dates -->
                            <div class="space-y-4" v-if="selectedLocataireId">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Période (Mois) *</label>
                                        <input 
                                            v-model="periode" 
                                            type="month" 
                                            class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-semibold text-slate-800 text-sm shadow-sm"
                                        />
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Date d'émission *</label>
                                        <input 
                                            v-model="dateEmission" 
                                            type="date" 
                                            class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-semibold text-slate-800 text-sm shadow-sm"
                                        />
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Date d'échéance *</label>
                                        <input 
                                            v-model="dateEcheance" 
                                            type="date" 
                                            class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-semibold text-slate-800 text-sm shadow-sm"
                                        />
                                    </div>
                                </div>

                                <!-- Multi-line items editor -->
                                <div class="mt-6">
                                    <div class="flex justify-between items-center mb-3">
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest">Lignes de facturation (Multifonction) *</label>
                                        <button type="button" @click="addItem" class="text-xs font-bold text-blue-650 hover:text-blue-655 flex items-center gap-1 transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                            Ajouter une ligne
                                        </button>
                                    </div>
                                    <div class="space-y-3">
                                        <div v-for="(item, idx) in items" :key="idx" class="grid grid-cols-12 gap-3 items-center bg-slate-55 p-3 rounded-xl border border-slate-100 shadow-sm">
                                            <!-- Description -->
                                            <div class="col-span-5">
                                                <input 
                                                    v-model="item.motif" 
                                                    type="text" 
                                                    placeholder="Motif / Description" 
                                                    class="w-full px-3 py-2 text-xs font-semibold border border-slate-200 rounded-lg focus:outline-none focus:border-blue-500 bg-white"
                                                />
                                            </div>
                                            <!-- Qty -->
                                            <div class="col-span-2">
                                                <input 
                                                    v-model.number="item.quantite" 
                                                    type="number" 
                                                    min="1" 
                                                    placeholder="Qté" 
                                                    class="w-full px-2 py-2 text-xs font-semibold border border-slate-200 rounded-lg focus:outline-none focus:border-blue-500 bg-white text-center"
                                                />
                                            </div>
                                            <!-- P.U. -->
                                            <div class="col-span-2">
                                                <input 
                                                    v-model.number="item.prix_unitaire" 
                                                    type="number" 
                                                    min="0" 
                                                    placeholder="P.U." 
                                                    class="w-full px-2 py-2 text-xs font-semibold border border-slate-200 rounded-lg focus:outline-none focus:border-blue-500 bg-white text-right"
                                                />
                                            </div>
                                            <!-- P.T. -->
                                            <div class="col-span-2 text-right text-xs font-bold text-slate-700">
                                                {{ formatCurrency(Number(item.quantite || 0) * Number(item.prix_unitaire || 0)) }}
                                            </div>
                                            <!-- Actions -->
                                            <div class="col-span-1 text-center">
                                                <button 
                                                    type="button" 
                                                    @click="removeItem(idx)" 
                                                    :disabled="items.length <= 1"
                                                    class="text-red-500 hover:text-red-650 disabled:opacity-30 disabled:cursor-not-allowed transition-all"
                                                >
                                                    <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footers -->
                    <div class="flex gap-4 mt-6 border-t border-slate-100 pt-5">
                        <button @click="closeModal" class="flex-1 px-5 py-3.5 bg-slate-100 text-slate-650 rounded-xl font-bold hover:bg-slate-200 transition-all text-xs">Annuler</button>
                        <button 
                            @click="saveInvoice" 
                            :disabled="savingInvoice || !selectedLocataireId"
                            class="flex-1 px-5 py-3.5 bg-gradient-to-r from-blue-500 to-indigo-650 text-white rounded-xl font-bold shadow-lg shadow-blue-500/20 hover:shadow-xl hover:shadow-blue-500/35 transition-all text-xs flex items-center justify-center gap-2 disabled:opacity-50"
                        >
                            <svg v-if="savingInvoice" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Générer la facture
                        </button>
                    </div>
                </div>

                <!-- Right: Live Printable Style Invoice Preview (col-span-5) -->
                <div class="lg:col-span-5 border-l border-slate-200 pl-8 hidden lg:flex flex-col justify-between max-h-[80vh]">
                    <div class="bg-white border-2 border-slate-100 shadow-lg rounded-2xl p-6 flex-1 flex flex-col justify-between font-sans text-xs text-slate-800 overflow-y-auto relative">
                        <div>
                            <!-- Header -->
                            <div class="flex justify-between items-start border-b border-slate-100 pb-4 mb-4">
                                <div>
                                    <div class="font-extrabold text-slate-900 text-sm">FACTURE IMMOBILIÈRE</div>
                                    <div class="text-[10px] text-slate-400 font-semibold">{{ currentCompanyName || 'Votre Compagnie' }}</div>
                                    <div class="text-[9px] text-slate-400" v-if="selectedAgencyName">Agence: {{ selectedAgencyName }}</div>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-blue-50 text-blue-700 border border-blue-150">DRAFT</span>
                                </div>
                            </div>

                            <!-- Parties details -->
                            <div class="grid grid-cols-2 gap-4 mb-6">
                                <div>
                                    <span class="block text-[9px] font-bold text-slate-400 uppercase">Destinataire</span>
                                    <strong class="text-slate-800 text-xs" v-if="selectedTenantLabel">{{ selectedTenantLabel }}</strong>
                                    <strong class="text-slate-450 italic" v-else>Sélectionner un locataire</strong>
                                    <span class="block text-slate-550 mt-1" v-if="selectedBuildingName">Bâtiment: {{ selectedBuildingName }}</span>
                                </div>
                                <div class="text-right">
                                    <span class="block text-[9px] font-bold text-slate-400 uppercase">Période</span>
                                    <strong class="text-slate-850" v-if="periode">{{ formatPeriod(periode) }}</strong>
                                    <strong class="text-slate-400 italic" v-else>Non définie</strong>
                                    <span class="block text-[9px] text-slate-400 mt-2">Date d'échéance</span>
                                    <strong class="text-red-650" v-if="dateEcheance">{{ formatDateFr(dateEcheance) }}</strong>
                                    <strong class="text-slate-400 italic" v-else>Non définie</strong>
                                </div>
                            </div>

                            <!-- Table of items -->
                            <div class="border-b border-slate-100 mb-4">
                                <table class="w-full">
                                    <thead>
                                        <tr class="border-b border-slate-100 text-[9px] text-slate-400 font-bold uppercase text-left">
                                            <th class="pb-2">Description</th>
                                            <th class="text-center pb-2">Qté</th>
                                            <th class="text-right pb-2">P.U.</th>
                                            <th class="text-right pb-2">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, idx) in items" :key="idx" class="border-b border-slate-50/50">
                                            <td class="py-2 text-slate-700 font-medium">{{ item.motif || 'Ligne ' + (idx + 1) }}</td>
                                            <td class="py-2 text-center text-slate-650">{{ item.quantite || 1 }}</td>
                                            <td class="py-2 text-right text-slate-650">{{ formatCurrency(item.prix_unitaire || 0) }}</td>
                                            <td class="py-2 text-right font-bold text-slate-800">{{ formatCurrency((item.quantite || 1) * (item.prix_unitaire || 0)) }}</td>
                                        </tr>
                                        <tr v-if="items.length === 0">
                                            <td colspan="4" class="py-4 text-center text-slate-400 italic">Veuillez ajouter des lignes de facturation</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Total display -->
                        <div class="flex justify-between items-center border-t border-slate-100 pt-4 bg-slate-50 p-4 rounded-xl border border-slate-100 shadow-inner">
                            <span class="font-extrabold text-slate-900 text-sm">TOTAL À PAYER</span>
                            <span class="font-extrabold text-blue-600 text-base">{{ formatCurrency(invoiceTotalCalculated) }}</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- View Invoice Details Modal -->
        <div v-if="showViewModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full p-8 border border-slate-200 relative overflow-hidden animate-scale-up">
                <div class="flex justify-between items-center mb-6 border-b border-slate-100 pb-4">
                    <h3 class="text-lg font-extrabold text-slate-800">Détails de la facture</h3>
                    <button @click="closeViewModal" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-all">
                        <svg class="w-4 h-4 text-slate-550" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Printable invoice format -->
                <div id="invoice-print-area" class="border border-slate-200 rounded-2xl p-6 bg-white font-sans text-xs text-slate-850 shadow-inner relative overflow-hidden">
                    <!-- Subtle Watermark in Print Interface -->
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-[0.03] rotate-[30deg]">
                        <span class="text-4xl font-extrabold tracking-widest text-slate-900 uppercase">{{ viewingInvoice?.agency_name || 'Siège Social' }}</span>
                    </div>

                    <div class="flex justify-between items-start border-b border-slate-100 pb-4 mb-4">
                        <div>
                            <div class="font-extrabold text-slate-900 text-sm uppercase">{{ viewingInvoice?.company_name || currentCompanyName }}</div>
                            <div class="text-[9px] text-slate-400">Agence gérante : {{ viewingInvoice?.agency_name || 'Siège Social' }}</div>
                        </div>
                        <div class="text-right">
                            <div class="font-extrabold text-blue-600 text-sm">ENREGISTRÉE</div>
                            <div class="text-xs font-bold text-slate-800 mt-1">{{ viewingInvoice?.numero }}</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <span class="block text-[9px] font-bold text-slate-450 uppercase mb-0.5">Locataire</span>
                            <strong class="text-slate-800 text-xs">{{ viewingInvoice?.locataire }}</strong>
                            <span class="block text-slate-500 mt-1">Logement: {{ viewingInvoice?.logement }}</span>
                            <span class="block text-slate-500">{{ viewingInvoice?.batiment }}</span>
                        </div>
                        <div class="text-right">
                            <span class="block text-[9px] font-bold text-slate-450 uppercase mb-0.5">Période / Type</span>
                            <strong class="text-slate-800 block">{{ viewingInvoice?.type_facture_nom }}</strong>
                            <span class="block text-[10px] text-slate-550" v-if="viewingInvoice?.periode">{{ formatPeriod(viewingInvoice?.periode) }}</span>
                            <span class="block text-[9px] text-slate-400 mt-3">Statut actuel</span>
                            <span :class="[
                                'inline-flex px-2 py-0.5 rounded text-[10px] font-bold mt-1 uppercase border',
                                viewingInvoice?.statut === 'Payé' ? 'bg-emerald-55 text-emerald-800 border-emerald-200' : 'bg-red-55 text-red-800 border-red-200'
                            ]">{{ viewingInvoice?.statut }}</span>
                        </div>
                    </div>

                    <table class="w-full mb-6">
                        <thead>
                            <tr class="border-b border-slate-150 text-[9px] text-slate-400 font-bold uppercase text-left">
                                <th class="pb-2">Description</th>
                                <th class="text-center pb-2">Qté</th>
                                <th class="text-right pb-2">P.U.</th>
                                <th class="text-right pb-2">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, idx) in viewingInvoice?.items" :key="idx" class="border-b border-slate-50/50">
                                <td class="py-2 text-slate-700 font-medium">{{ item.motif }}</td>
                                <td class="py-2 text-center text-slate-650">{{ item.quantite || 1 }}</td>
                                <td class="py-2 text-right text-slate-650">{{ formatCurrency(item.prix_unitaire || 0) }}</td>
                                <td class="py-2 text-right font-bold text-slate-800">{{ formatCurrency((item.quantite || 1) * (item.prix_unitaire || 0)) }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="flex justify-between items-center bg-slate-50 p-4 rounded-xl border border-slate-100">
                        <div>
                            <span class="block text-[9px] text-slate-405 uppercase">Déjà réglé</span>
                            <strong class="text-emerald-600 font-bold text-sm">{{ formatCurrency(viewingInvoice?.montantPaye) }}</strong>
                            <span class="block text-[8px] text-slate-400 mt-0.5" v-if="viewingInvoice?.modeReglement">via {{ viewingInvoice?.modeReglement }}</span>
                        </div>
                        <div class="text-right">
                            <span class="block text-[9px] text-slate-405 uppercase">Total Facturé</span>
                            <strong class="text-blue-600 text-sm font-extrabold">{{ formatCurrency(viewingInvoice?.total) }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Print simulator button -->
                <div class="flex justify-between items-center mt-6">
                    <button @click="printInvoice" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold flex items-center gap-1.5 transition-all">
                        🖨️ Imprimer la Facture
                    </button>
                    <button @click="closeViewModal" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all">Fermer</button>
                </div>
            </div>
        </div>

        <!-- Payment Settlement Modal -->
        <div v-if="showPaymentModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-6 border border-slate-200 animate-scale-up" @click.stop>
                <div class="flex justify-between items-center mb-6 border-b border-slate-100 pb-4">
                    <h3 class="text-lg font-extrabold text-slate-800">Régler la facture</h3>
                    <button @click="closePaymentModal" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-all">
                        <svg class="w-4 h-4 text-slate-550" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="space-y-4 mb-6">
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        <div class="flex justify-between mb-1.5">
                            <span class="text-slate-400 font-medium text-xs">Facture</span>
                            <span class="font-extrabold text-slate-800 text-xs">{{ paymentInvoice?.numero }}</span>
                        </div>
                        <div class="flex justify-between mb-1.5">
                            <span class="text-slate-400 font-medium text-xs">Locataire</span>
                            <span class="font-bold text-slate-800 text-xs">{{ paymentInvoice?.locataire }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400 font-medium text-xs">Montant total</span>
                            <span class="font-extrabold text-blue-600 text-sm">{{ formatCurrency(paymentInvoice?.total) }}</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">Mode de règlement</label>
                        <div class="grid grid-cols-2 gap-3">
                            <button 
                                type="button" 
                                @click="paymentMode = 'cash'"
                                :class="[
                                    'p-4 rounded-2xl border-2 transition-all flex flex-col items-center gap-2 font-bold text-xs',
                                    paymentMode === 'cash' ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-slate-200 hover:border-slate-300 text-slate-650'
                                ]"
                            >
                                <i :class="[
                                    'fa-solid fa-money-bill-wave text-2xl transition-all',
                                    paymentMode === 'cash' ? 'text-blue-600 scale-110' : 'text-slate-400'
                                ]"></i>
                                <span>Espèces (Cash)</span>
                            </button>
                            <button 
                                type="button" 
                                @click="paymentMode = 'wallet'"
                                :class="[
                                    'p-4 rounded-2xl border-2 transition-all flex flex-col items-center gap-2 font-bold text-xs',
                                    paymentMode === 'wallet' ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-slate-200 hover:border-slate-300 text-slate-650'
                                ]"
                            >
                                <i :class="[
                                    'fa-solid fa-wallet text-2xl transition-all',
                                    paymentMode === 'wallet' ? 'text-blue-600 scale-110' : 'text-slate-400'
                                ]"></i>
                                <span>Portefeuille (Wallet)</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button @click="closePaymentModal" class="flex-1 px-4 py-3 bg-slate-100 text-slate-600 rounded-xl font-bold hover:bg-slate-200 transition-all text-xs">Annuler</button>
                    <button 
                        @click="confirmPayment" 
                        :disabled="savingPayment"
                        class="flex-1 px-4 py-3 bg-gradient-to-r from-blue-500 to-indigo-650 text-white rounded-xl font-bold shadow-lg shadow-blue-500/20 hover:shadow-xl hover:shadow-blue-500/35 transition-all text-xs flex items-center justify-center gap-2"
                    >
                        <svg v-if="savingPayment" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Valider le règlement
                    </button>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div v-if="showDeleteConfirmModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-6 border border-slate-200 animate-scale-up" @click.stop>
                <div class="flex justify-between items-center mb-6 border-b border-slate-100 pb-4">
                    <h3 class="text-lg font-extrabold text-slate-800">Confirmer la suppression</h3>
                    <button @click="closeDeleteConfirmModal" class="w-8 h-8 rounded-xl bg-slate-105 hover:bg-slate-200 flex items-center justify-center transition-all">
                        <svg class="w-4 h-4 text-slate-550" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="text-center mb-6">
                    <div class="flex items-center justify-center w-14 h-14 rounded-2xl bg-red-50 text-red-650 mx-auto mb-4 border border-red-100">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>
                    <p class="text-slate-650 font-semibold text-sm">
                        Êtes-vous sûr de vouloir supprimer la facture numéro <span class="font-extrabold text-slate-800">{{ invoiceToDelete?.numero }}</span> ?
                    </p>
                    <p class="text-[11px] text-red-500 font-bold mt-2">
                        ⚠️ Cette action est irréversible et supprimera définitivement la facture.
                    </p>
                </div>

                <div class="flex gap-3">
                    <button @click="closeDeleteConfirmModal" :disabled="deletingInvoice" class="flex-1 px-4 py-3 bg-slate-100 hover:bg-slate-250 text-slate-700 rounded-xl font-bold transition-all text-xs">Annuler</button>
                    <button 
                        @click="executeDeleteInvoice" 
                        :disabled="deletingInvoice"
                        class="flex-1 px-4 py-3 bg-red-600 hover:bg-red-500 text-white rounded-xl font-bold shadow-lg shadow-red-500/20 hover:shadow-xl hover:shadow-red-500/35 transition-all text-xs flex items-center justify-center gap-2"
                    >
                        <svg v-if="deletingInvoice" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Supprimer
                    </button>
                </div>
            </div>
        </div>

        <!-- Success Toast -->
        <div v-if="showSuccess" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-sm w-full p-8 border border-slate-200 relative overflow-hidden animate-scale-up">
                <div class="text-center">
                    <div class="flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 mx-auto mb-5 shadow-lg shadow-emerald-500/30">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-extrabold text-slate-805 mb-1">Succès !</h3>
                    <p class="text-slate-500 text-sm mb-6">{{ successMessage }}</p>
                    <button @click="closeSuccess" class="w-full px-5 py-3.5 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-xl font-bold shadow-md shadow-emerald-500/20 hover:scale-[1.01] transition-all text-xs">Fermer</button>
                </div>
            </div>
        </div>

        <!-- Error Toast -->
        <div v-if="showError" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-sm w-full p-8 border border-slate-200 relative overflow-hidden animate-scale-up">
                <div class="text-center">
                    <div class="flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-red-500 to-rose-600 mx-auto mb-5 shadow-lg shadow-red-500/30">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-extrabold text-slate-805 mb-1">Erreur</h3>
                    <p class="text-slate-500 text-sm mb-6">{{ errorMessage }}</p>
                    <button @click="closeError" class="w-full px-5 py-3.5 bg-gradient-to-r from-red-500 to-rose-600 text-white rounded-xl font-bold shadow-md shadow-red-500/20 hover:scale-[1.01] transition-all text-xs">Fermer</button>
                </div>
            </div>
        </div>

    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';

// Loaded from Backend APIs
const invoices = ref([]);
const contratsActifs = ref([]);
const batiments = ref([]);
const logements = ref([]);
const typeFactures = ref([]);
const agencies = ref([]);
const deviseSymbol = ref('€');
const currentCompanyName = ref('');

// Loading state indicators
const loading = ref(false);
const savingInvoice = ref(false);
const savingPayment = ref(false);

// Filters
const searchQuery = ref('');
const statusFilter = ref('All');
const periodFilter = ref('All');
const agencyFilter = ref('All');
const typeFactureFilter = ref('All');

// Form state variables
const showModal = ref(false);
const showViewModal = ref(false);
const showSuccess = ref(false);
const showError = ref(false);
const successMessage = ref('');
const errorMessage = ref('');
const viewingInvoice = ref(null);
const showDeleteConfirmModal = ref(false);
const invoiceToDelete = ref(null);
const deletingInvoice = ref(false);

// Form selections
const selectedAgencyId = ref(null);
const selectedAgencyName = ref('');
const selectedBuildingId = ref(null);
const selectedBuildingName = ref('');
const selectedLocataireId = ref(null);
const selectedTenantLabel = ref('');
const selectedContratId = ref(null);
const selectedTypeFactureId = ref(null);
const selectedTypeFactureNom = ref('');

const dateEmission = ref('');
const dateEcheance = ref('');
const periode = ref('');
const items = ref([]);

// Search queries inside dropdowns
const agencySearchQuery = ref('');
const buildingSearchQuery = ref('');
const tenantSearchQuery = ref('');
const typeFactureSearchQuery = ref('');

// Dropdown visibility flags
const isAgencyDropdownOpen = ref(false);
const isBuildingDropdownOpen = ref(false);
const isTenantDropdownOpen = ref(false);
const isTypeFactureDropdownOpen = ref(false);

// Settle payments state
const showPaymentModal = ref(false);
const paymentInvoice = ref(null);
const paymentMode = ref('cash');

onMounted(() => {
    fetchPageData();
    window.addEventListener('click', closeAllDropdowns);
});

// Dropdowns management
const closeAllDropdowns = () => {
    isAgencyDropdownOpen.value = false;
    isBuildingDropdownOpen.value = false;
    isTenantDropdownOpen.value = false;
    isTypeFactureDropdownOpen.value = false;
};

const toggleAgencyDropdown = (e) => {
    e.stopPropagation();
    const state = isAgencyDropdownOpen.value;
    closeAllDropdowns();
    isAgencyDropdownOpen.value = !state;
};

const toggleBuildingDropdown = (e) => {
    e.stopPropagation();
    const state = isBuildingDropdownOpen.value;
    closeAllDropdowns();
    isBuildingDropdownOpen.value = !state;
};

const toggleTenantDropdown = (e) => {
    e.stopPropagation();
    const state = isTenantDropdownOpen.value;
    closeAllDropdowns();
    isTenantDropdownOpen.value = !state;
};

const toggleTypeFactureDropdown = (e) => {
    e.stopPropagation();
    const state = isTypeFactureDropdownOpen.value;
    closeAllDropdowns();
    isTypeFactureDropdownOpen.value = !state;
};

// Dropdown filter computed
const filteredAgenciesDropdown = computed(() => {
    let list = agencies.value;
    if (agencySearchQuery.value) {
        const q = agencySearchQuery.value.toLowerCase();
        list = list.filter(a => a.name.toLowerCase().includes(q));
    }
    return list;
});

const filteredBuildingsDropdown = computed(() => {
    if (!selectedAgencyId.value) return [];
    let list = batiments.value.filter(b => Number(b.agency_id) === Number(selectedAgencyId.value));
    if (buildingSearchQuery.value) {
        const q = buildingSearchQuery.value.toLowerCase();
        list = list.filter(b => b.nom.toLowerCase().includes(q));
    }
    return list;
});

const filteredTenantsDropdown = computed(() => {
    if (!selectedBuildingId.value) return [];
    
    // Get all logement IDs that belong to the selected building
    const buildingLogementIds = logements.value
        .filter(l => Number(l.batiment_id) === Number(selectedBuildingId.value))
        .map(l => l.id);
        
    let list = contratsActifs.value.filter(c => buildingLogementIds.includes(Number(c.logement_id)));
    
    if (tenantSearchQuery.value) {
        const q = tenantSearchQuery.value.toLowerCase();
        list = list.filter(c => c.locataire.toLowerCase().includes(q) || c.numero.toLowerCase().includes(q));
    }
    return list;
});

const filteredTypeFacturesDropdown = computed(() => {
    let list = typeFactures.value;
    if (typeFactureSearchQuery.value) {
        const q = typeFactureSearchQuery.value.toLowerCase();
        list = list.filter(t => t.nom.toLowerCase().includes(q));
    }
    return list;
});

// Selection handlers
const selectAgency = (agency) => {
    selectedAgencyId.value = agency.id;
    selectedAgencyName.value = agency.name;
    isAgencyDropdownOpen.value = false;
    
    // Reset subsequent selections
    selectedBuildingId.value = null;
    selectedBuildingName.value = '';
    selectedLocataireId.value = null;
    selectedTenantLabel.value = '';
    selectedContratId.value = null;
};

const selectBuilding = (building) => {
    selectedBuildingId.value = building.id;
    selectedBuildingName.value = building.nom;
    isBuildingDropdownOpen.value = false;
    
    // Reset subsequent selections
    selectedLocataireId.value = null;
    selectedTenantLabel.value = '';
    selectedContratId.value = null;
};

const selectTenant = (contract) => {
    selectedLocataireId.value = contract.locataire_id;
    selectedContratId.value = contract.id;
    selectedTenantLabel.value = `${contract.locataire} (Bail ${contract.numero})`;
    isTenantDropdownOpen.value = false;
    
    // Autofill defaults based on contract details
    items.value = [
        { motif: 'Loyer mensuel HC', quantite: 1, prix_unitaire: Number(contract.loyer || 0), prix_total: Number(contract.loyer || 0) },
        { motif: 'Provisions de charges', quantite: 1, prix_unitaire: 75, prix_total: 75 }
    ];
};

const selectTypeFacture = (type) => {
    selectedTypeFactureId.value = type.id;
    selectedTypeFactureNom.value = type.nom;
    isTypeFactureDropdownOpen.value = false;
};

// Fetch page data
const fetchPageData = async () => {
    loading.value = true;
    try {
        const [
            invoicesRes,
            contratsRes,
            batimentsRes,
            logementsRes,
            typeFacturesRes,
            deviseRes,
            agenciesRes
        ] = await Promise.all([
            axios.get('/api/factures').catch(err => { console.error("Error loading invoices:", err); return { data: [] }; }),
            axios.get('/api/contrats').catch(err => { console.error("Error loading contrats:", err); return { data: [] }; }),
            axios.get('/api/batiments').catch(err => { console.error("Error loading batiments:", err); return { data: [] }; }),
            axios.get('/api/logements').catch(err => { console.error("Error loading logements:", err); return { data: [] }; }),
            axios.get('/api/type-factures').catch(err => { console.error("Error loading type-factures:", err); return { data: [] }; }),
            axios.get('/api/devise').catch(err => { console.error("Error loading devise:", err); return null; }),
            axios.get('/agencies', { headers: { 'Accept': 'application/json' }, params: { per_page: 1000 } }).catch(err => { console.error("Error loading agencies:", err); return null; })
        ]);

        invoices.value = invoicesRes.data || [];
        contratsActifs.value = (contratsRes.data || []).filter(c => c.statut === 'Actif');
        batiments.value = batimentsRes.data || [];
        logements.value = logementsRes.data || [];
        typeFactures.value = (typeFacturesRes.data || []).filter(t => !t.deleted);
        
        if (deviseRes && deviseRes.data) {
            deviseSymbol.value = deviseRes.data.symbole || '€';
        }
        
        if (agenciesRes && agenciesRes.data) {
            const data = agenciesRes.data;
            agencies.value = data.agencies?.data || data.agencies || [];
        }

        // Get company name from first invoice if available
        if (invoices.value.length > 0) {
            currentCompanyName.value = invoices.value[0].company_name || '';
        }
    } catch (err) {
        console.error("Critical error loading page data:", err);
        errorMessage.value = "Une erreur critique est survenue lors du chargement des données.";
        showError.value = true;
    } finally {
        loading.value = false;
    }
};

// Available unique periods
const availablePeriods = computed(() => {
    const periods = invoices.value.map(i => i.periode).filter(Boolean);
    return [...new Set(periods)].sort().reverse();
});

// Filtered invoices (Filters list and dynamically updates KPIs!)
const filteredInvoices = computed(() => {
    let result = invoices.value;

    if (agencyFilter.value !== 'All') {
        result = result.filter(i => Number(i.agency_id) === Number(agencyFilter.value) || (agencyFilter.value === 'siege' && !i.agency_id));
    }

    if (typeFactureFilter.value !== 'All') {
        result = result.filter(i => Number(i.type_facture_id) === Number(typeFactureFilter.value));
    }

    if (periodFilter.value !== 'All') {
        result = result.filter(i => i.periode === periodFilter.value);
    }

    if (statusFilter.value !== 'All') {
        result = result.filter(i => i.statut === statusFilter.value);
    }

    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        result = result.filter(i => 
            i.locataire.toLowerCase().includes(query) ||
            i.numero.toLowerCase().includes(query) ||
            i.logement.toLowerCase().includes(query)
        );
    }

    return result;
});

// Computed KPIs (Dynamically updates based on selected filters!)
const totalInvoiced = computed(() => filteredInvoices.value.reduce((sum, i) => sum + i.total, 0));
const totalPaid = computed(() => filteredInvoices.value.reduce((sum, i) => sum + i.montantPaye, 0));
const totalDue = computed(() => totalInvoiced.value - totalPaid.value);
const recoveryRate = computed(() => {
    if (!totalInvoiced.value) return 0;
    return Math.round((totalPaid.value / totalInvoiced.value) * 100);
});

// Items Editor Methods
const addItem = () => {
    items.value.push({ motif: '', quantite: 1, prix_unitaire: 0, prix_total: 0 });
};

const removeItem = (idx) => {
    items.value.splice(idx, 1);
};

const invoiceTotalCalculated = computed(() => {
    return items.value.reduce((sum, item) => sum + (Number(item.quantite || 0) * Number(item.prix_unitaire || 0)), 0);
});

// Open/Close Modals
const openAddModal = () => {
    closeAllDropdowns();
    selectedAgencyId.value = null;
    selectedAgencyName.value = '';
    selectedBuildingId.value = null;
    selectedBuildingName.value = '';
    selectedLocataireId.value = null;
    selectedTenantLabel.value = '';
    selectedContratId.value = null;
    selectedTypeFactureId.value = null;
    selectedTypeFactureNom.value = '';

    agencySearchQuery.value = '';
    buildingSearchQuery.value = '';
    tenantSearchQuery.value = '';
    typeFactureSearchQuery.value = '';

    const now = new Date();
    dateEmission.value = now.toISOString().split('T')[0];
    dateEcheance.value = '';
    periode.value = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}`;
    items.value = [{ motif: '', quantite: 1, prix_unitaire: 0, prix_total: 0 }];
    
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
};

const saveInvoice = async () => {
    if (!selectedLocataireId.value || !selectedContratId.value) {
        errorMessage.value = "Veuillez sélectionner un locataire actif.";
        showError.value = true;
        return;
    }
    if (!selectedTypeFactureId.value) {
        errorMessage.value = "Veuillez sélectionner un type de facture.";
        showError.value = true;
        return;
    }
    if (!dateEmission.value || !dateEcheance.value) {
        errorMessage.value = "Veuillez renseigner les dates d'émission et d'échéance.";
        showError.value = true;
        return;
    }
    if (items.value.some(item => !item.motif || item.quantite <= 0 || item.prix_unitaire < 0)) {
        errorMessage.value = "Veuillez remplir correctement toutes les lignes de facturation.";
        showError.value = true;
        return;
    }

    savingInvoice.value = true;
    try {
        const payload = {
            locataire_id: selectedLocataireId.value,
            contrat_id: selectedContratId.value,
            type_facture_id: selectedTypeFactureId.value,
            periode: periode.value,
            date_emission: dateEmission.value,
            date_echeance: dateEcheance.value,
            items: items.value.map(i => ({
                motif: i.motif,
                quantite: i.quantite,
                prix_unitaire: i.prix_unitaire,
                prix_total: Number(i.quantite) * Number(i.prix_unitaire)
            })),
            total: invoiceTotalCalculated.value
        };

        const res = await axios.post('/api/factures', payload);
        invoices.value.unshift(res.data);
        showModal.value = false;
        successMessage.value = "Facture locative générée et enregistrée avec succès.";
        showSuccess.value = true;
    } catch (err) {
        console.error("Error saving invoice:", err);
        errorMessage.value = err.response?.data?.message || "Une erreur est survenue lors de la création de la facture.";
        showError.value = true;
    } finally {
        savingInvoice.value = false;
    }
};

const viewInvoice = (inv) => {
    viewingInvoice.value = inv;
    showViewModal.value = true;
};

const closeViewModal = () => {
    showViewModal.value = false;
    viewingInvoice.value = null;
};

const deleteInvoice = (inv) => {
    invoiceToDelete.value = inv;
    showDeleteConfirmModal.value = true;
};

const closeDeleteConfirmModal = () => {
    showDeleteConfirmModal.value = false;
    invoiceToDelete.value = null;
    deletingInvoice.value = false;
};

const executeDeleteInvoice = async () => {
    if (!invoiceToDelete.value) return;
    deletingInvoice.value = true;
    try {
        await axios.delete(`/api/factures/${invoiceToDelete.value.id}`);
        invoices.value = invoices.value.filter(i => i.id !== invoiceToDelete.value.id);
        successMessage.value = "La facture a été supprimée avec succès.";
        showSuccess.value = true;
    } catch (err) {
        console.error("Error deleting invoice:", err);
        errorMessage.value = err.response?.data?.message || "Une erreur est survenue lors de la suppression de la facture.";
        showError.value = true;
    } finally {
        closeDeleteConfirmModal();
    }
};

// Payment actions
const openPaymentModal = (inv) => {
    paymentInvoice.value = inv;
    paymentMode.value = 'cash';
    showPaymentModal.value = true;
};

const closePaymentModal = () => {
    showPaymentModal.value = false;
    paymentInvoice.value = null;
};

const confirmPayment = async () => {
    if (!paymentInvoice.value) return;
    savingPayment.value = true;
    try {
        const res = await axios.post(`/api/factures/${paymentInvoice.value.id}/regler`, {
            mode_reglement: paymentMode.value
        });
        
        const idx = invoices.value.findIndex(i => i.id === paymentInvoice.value.id);
        if (idx !== -1) {
            invoices.value[idx] = res.data;
        }
        
        closePaymentModal();
        successMessage.value = "Le règlement de la facture a été enregistré avec succès.";
        showSuccess.value = true;
    } catch (err) {
        console.error("Error setting payment:", err);
        errorMessage.value = err.response?.data?.message || "Une erreur est survenue lors de l'enregistrement du règlement.";
        showError.value = true;
    } finally {
        savingPayment.value = false;
    }
};

const printInvoice = () => {
    const printContent = document.getElementById('invoice-print-area').innerHTML;
    const w = window.open();
    w.document.write('<html><head><title>Facture</title>');
    
    // Copy all style sheets and style elements from the main document to preserve Tailwind layout
    const styles = Array.from(document.querySelectorAll('link[rel="stylesheet"], style'));
    styles.forEach(style => {
        w.document.write(style.outerHTML);
    });
    
    w.document.write('<style>');
    w.document.write(`
        body {
            font-family: "Inter", sans-serif;
            padding: 40px !important;
            color: #334155 !important;
            position: relative;
            min-height: 100vh;
            background: #white !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .watermark {
            position: fixed !important;
            top: 50% !important;
            left: 50% !important;
            transform: translate(-50%, -50%) rotate(-30deg) !important;
            font-size: 70px !important;
            color: rgba(148, 163, 184, 0.09) !important;
            font-weight: 850 !important;
            text-transform: uppercase !important;
            white-space: nowrap !important;
            pointer-events: none !important;
            z-index: -10 !important;
        }
        @media print {
            body {
                padding: 0 !important;
                margin: 0 !important;
            }
            .absolute.inset-0.flex {
                display: none !important;
            }
        }
    `);
    w.document.write('</style>');
    w.document.write('</head><body class="bg-white">');
    const agencyName = viewingInvoice.value?.agency_name || 'Siège Social';
    w.document.write(`<div class="watermark">${agencyName}</div>`);
    w.document.write(printContent);
    w.document.write('</body></html>');
    w.document.close();
    w.focus();
    setTimeout(() => {
        w.print();
        w.close();
    }, 500);
};

const closeSuccess = () => {
    showSuccess.value = false;
};

const closeError = () => {
    showError.value = false;
};

// Formatting helpers
const formatCurrency = (val) => {
    if (val === undefined || val === null || isNaN(val) || val === '') {
        return `0 ${deviseSymbol.value}`;
    }
    const formattedVal = new Intl.NumberFormat('fr-FR', { maximumFractionDigits: 0 }).format(val);
    return `${formattedVal} ${deviseSymbol.value}`;
};

const formatPeriod = (periodStr) => {
    if (!periodStr) return '';
    const parts = periodStr.split('-');
    if (parts.length < 2) return periodStr;
    const months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
    const monthIdx = parseInt(parts[1], 10) - 1;
    return `${months[monthIdx]} ${parts[0]}`;
};

const formatDateFr = (dateStr) => {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    return new Intl.DateTimeFormat('fr-FR', { day: '2-digit', month: '2-digit', year: 'numeric' }).format(date);
};

const getInitials = (name) => {
    if (!name) return 'FAC';
    const parts = name.trim().split(' ');
    if (parts.length >= 2) {
        return (parts[0][0] + parts[1][0]).toUpperCase();
    }
    return name.substring(0, 2).toUpperCase();
};

const getAvatarGradient = (name) => {
    if (!name) return 'from-blue-500 to-indigo-650';
    const colors = [
        'from-blue-550 to-indigo-600',
        'from-emerald-500 to-teal-600',
        'from-violet-500 to-purple-600',
        'from-amber-500 to-orange-600',
        'from-rose-500 to-red-650',
        'from-cyan-500 to-blue-600'
    ];
    let hash = 0;
    for (let i = 0; i < name.length; i++) {
        hash = name.charCodeAt(i) + ((hash << 5) - hash);
    }
    const index = Math.abs(hash) % colors.length;
    return colors[index];
};
</script>

<style scoped>
@keyframes scaleUp {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.animate-scale-up {
    animation: scaleUp 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
</style>
