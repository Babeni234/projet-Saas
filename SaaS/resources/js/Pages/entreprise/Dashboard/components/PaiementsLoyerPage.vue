<template>
    <div class="flex flex-col gap-8 select-none">
        
        <!-- Header with Luxury Dark Teal Banner -->
        <div class="bg-gradient-to-br from-slate-900 via-slate-950 to-slate-900 rounded-3xl p-8 border border-slate-800 shadow-2xl text-white relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-48 h-48 rounded-full bg-emerald-500/10 blur-2xl pointer-events-none"></div>
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 relative z-10">
                <div>
                    <span class="text-emerald-450 text-xs font-bold tracking-widest uppercase bg-emerald-500/20 px-3 py-1 rounded-full border border-emerald-500/30">
                        <i class="fa-solid fa-wallet mr-1"></i> Trésorerie
                    </span>
                    <h1 class="text-3xl font-extrabold bg-gradient-to-r from-white via-slate-100 to-slate-300 bg-clip-text text-transparent mt-2">Paiements de Loyer</h1>
                    <p class="text-slate-400 text-sm mt-1">Enregistrez les règlements de loyers, calculez les pénalités et gérez les quittances en temps réel.</p>
                </div>
                <button
                    @click="openAddModal"
                    class="px-5 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-500 border border-emerald-500/50 hover:border-emerald-400 text-white font-bold text-xs transition-all duration-300 transform hover:scale-[1.02] flex items-center gap-2 shadow-lg shadow-emerald-600/35"
                >
                    <i class="fa-solid fa-plus"></i> Enregistrer un Paiement
                </button>
            </div>
        </div>

        <!-- Premium KPI Cards (Dynamically responsive to filters) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-3xl p-6 shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-emerald-200/30 transition-all duration-500 hover:-translate-y-1 border border-slate-150 relative overflow-hidden group">
                <div class="absolute -right-6 -bottom-6 w-20 h-20 rounded-full bg-emerald-500/5 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 font-bold shadow-sm">
                        <i class="fa-solid fa-euro-sign text-lg"></i>
                    </div>
                </div>
                <div class="text-2xl font-extrabold text-slate-800 mb-1 tracking-tight">{{ formatCurrency(totalCollected) }}</div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Encaissé</div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-blue-200/30 transition-all duration-500 hover:-translate-y-1 border border-slate-150 relative overflow-hidden group">
                <div class="absolute -right-6 -bottom-6 w-20 h-20 rounded-full bg-blue-500/5 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 font-bold shadow-sm">
                        <i class="fa-solid fa-calendar-check text-lg"></i>
                    </div>
                </div>
                <div class="text-2xl font-extrabold text-blue-600 mb-1 tracking-tight">{{ formatCurrency(collectedThisMonth) }}</div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest font-semibold">Encaissé ce Mois</div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-purple-200/30 transition-all duration-500 hover:-translate-y-1 border border-slate-150 relative overflow-hidden group">
                <div class="absolute -right-6 -bottom-6 w-20 h-20 rounded-full bg-purple-500/5 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600 font-bold shadow-sm">
                        <i class="fa-solid fa-wallet text-lg"></i>
                    </div>
                </div>
                <div class="text-2xl font-extrabold text-purple-650 mb-1 tracking-tight">{{ topPaymentMethod }}</div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Méthode Favorite</div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-violet-200/30 transition-all duration-500 hover:-translate-y-1 border border-slate-150 relative overflow-hidden group">
                <div class="absolute -right-6 -bottom-6 w-20 h-20 rounded-full bg-violet-500/5 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 bg-violet-50 rounded-xl flex items-center justify-center text-violet-600 font-bold shadow-sm">
                        <i class="fa-solid fa-receipt text-lg"></i>
                    </div>
                </div>
                <div class="text-2xl font-extrabold text-violet-600 mb-1 tracking-tight">{{ totalTransactions }}</div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Transactions</div>
            </div>
        </div>

        <!-- Filter Panel -->
        <div class="bg-gradient-to-br from-white to-slate-50 rounded-3xl p-6 shadow-xl shadow-slate-200/50 border border-slate-150 flex flex-col gap-4">
            <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                <div class="relative w-full md:w-96">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fa-solid fa-magnifying-glass text-slate-400 text-xs"></i>
                    </span>
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Rechercher par locataire, référence..."
                        class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-2xl focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-sm font-semibold transition-all shadow-sm"
                    />
                </div>
            </div>

            <!-- Advanced Filters Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 pt-2">
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

                <!-- 2. Building Filter -->
                <div class="flex flex-col gap-1">
                    <span class="text-[10px] font-bold uppercase text-slate-400 tracking-wider">Bâtiment :</span>
                    <select v-model="buildingFilter" class="bg-white border border-slate-200 rounded-2xl px-4 py-2.5 text-xs font-bold text-slate-700 focus:outline-none shadow-sm cursor-pointer">
                        <option value="All">Tous les bâtiments</option>
                        <option v-for="b in batiments" :key="b.id" :value="b.nom">
                            {{ b.nom }}
                        </option>
                    </select>
                </div>

                <!-- 3. Period Filter (Select format) -->
                <div class="flex flex-col gap-1">
                    <span class="text-[10px] font-bold uppercase text-slate-400 tracking-wider">Mois Payé :</span>
                    <select v-model="periodFilter" class="bg-white border border-slate-200 rounded-2xl px-4 py-2.5 text-xs font-bold text-slate-700 focus:outline-none shadow-sm cursor-pointer">
                        <option value="All">Tous les mois</option>
                        <option v-for="p in availablePeriods" :key="p" :value="p">
                            {{ formatPeriod(p) }}
                        </option>
                    </select>
                </div>

                <!-- 4. Payment Method Filter -->
                <div class="flex flex-col gap-1">
                    <span class="text-[10px] font-bold uppercase text-slate-400 tracking-wider">Mode de règlement :</span>
                    <select v-model="methodFilter" class="bg-white border border-slate-200 rounded-2xl px-4 py-2.5 text-xs font-bold text-slate-700 focus:outline-none shadow-sm cursor-pointer">
                        <option value="All">Tous les modes</option>
                        <option value="cash">Espèces (Cash)</option>
                        <option value="wallet">Portefeuille (Wallet)</option>
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
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Référence</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Locataire</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Logement</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Détails Mois</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Mode</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Montant Encaissé</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-150">
                        <tr v-for="pay in filteredPayments" :key="pay.id" class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 text-sm font-bold text-slate-800">
                                <div>{{ pay.reference }}</div>
                                <div class="text-[10px] text-slate-400 font-medium">Bail {{ pay.contrat_numero }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div :class="['w-9 h-9 rounded-xl flex items-center justify-center text-white font-extrabold text-xs shadow-sm bg-gradient-to-br', getAvatarGradient(pay.locataire)]">
                                        {{ getInitials(pay.locataire) }}
                                    </div>
                                    <div class="font-bold text-slate-800 text-sm">{{ pay.locataire }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-650 font-semibold">
                                <div>{{ pay.logement }}</div>
                                <div class="text-[10px] text-slate-400 font-medium">{{ pay.batiment }}</div>
                            </td>
                            <td class="px-6 py-4 text-xs font-semibold text-slate-600">
                                <div v-for="m in pay.mois_payes" :key="m.periode" class="mb-0.5">
                                    <span class="text-slate-800">{{ formatPeriod(m.periode) }}</span> :
                                    <span class="text-slate-500">{{ formatCurrency(m.loyer_de_base) }}</span>
                                    <span v-if="m.penalite > 0" class="text-red-500 ml-1 font-bold">(Pén. +{{ formatCurrency(m.penalite) }})</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 font-semibold">{{ formatDate(pay.date_reglement) }}</td>
                            <td class="px-6 py-4 text-sm text-slate-750 font-bold">
                                <span class="capitalize">
                                    <i :class="['mr-1 text-xs', pay.mode_reglement === 'cash' ? 'fa-solid fa-money-bill-wave text-emerald-600' : 'fa-solid fa-wallet text-blue-600']"></i>
                                    {{ pay.mode_reglement === 'cash' ? 'Cash' : 'Wallet' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-emerald-600 font-extrabold">{{ formatCurrency(pay.montant_total) }}</td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2 items-center">
                                    <!-- Details Button -->
                                    <button 
                                        @click="viewReceipt(pay)" 
                                        class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 shadow-sm"
                                        title="Voir les détails"
                                    >
                                        Détails
                                    </button>

                                    <!-- Delete Button (Only Enterprise dashboard can delete) -->
                                    <button 
                                        @click="deletePayment(pay)" 
                                        class="p-2 text-red-650 hover:bg-red-50 rounded-xl transition-all" 
                                        title="Supprimer"
                                    >
                                        <i class="fa-solid fa-trash-can text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="filteredPayments.length === 0">
                            <td colspan="8" class="px-6 py-12 text-center text-slate-400 font-semibold text-sm">
                                Aucun enregistrement de paiement trouvé.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Modal (Record Payment) -->
        <div v-if="showModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-6xl w-full p-8 border border-slate-200 relative overflow-hidden animate-scale-up grid grid-cols-1 lg:grid-cols-12 gap-8 max-h-[90vh]">
                
                <!-- Left: Form Setup & Month Selector (col-span-7) -->
                <div class="lg:col-span-7 flex flex-col justify-between max-h-[80vh] overflow-y-auto pr-4 scrollbar-thin">
                    <div>
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-lg shadow-emerald-500/30">
                                <i class="fa-solid fa-euro-sign text-white text-lg"></i>
                            </div>
                            <h2 class="text-2xl font-extrabold text-slate-800">Enregistrer un Règlement de Loyer</h2>
                        </div>

                        <div class="space-y-4">
                            <!-- 1. Agency Searchable Select -->
                            <div class="relative">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Agence *</label>
                                <div class="relative">
                                    <button 
                                        type="button" 
                                        @click="toggleAgencyDropdown" 
                                        class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl flex justify-between items-center text-sm font-semibold text-slate-800 shadow-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                                    >
                                        <span>{{ selectedAgencyName || 'Sélectionner l\'agence' }}</span>
                                        <i class="fa-solid fa-chevron-down text-slate-450 transition-transform" :class="{'rotate-180': isAgencyDropdownOpen}"></i>
                                    </button>
                                    <div v-if="isAgencyDropdownOpen" class="absolute z-35 w-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-xl p-2 max-h-60 overflow-y-auto" @click.stop>
                                        <input 
                                            v-model="agencySearchQuery" 
                                            type="text" 
                                            placeholder="Rechercher une agence..." 
                                            class="w-full px-3 py-2 text-xs font-semibold border border-slate-200 rounded-xl mb-2 focus:outline-none focus:border-emerald-500"
                                        />
                                        <div class="space-y-1">
                                            <button 
                                                v-for="agency in filteredAgenciesDropdown" 
                                                :key="agency.id" 
                                                @click="selectAgency(agency)" 
                                                type="button" 
                                                class="w-full text-left px-3 py-2 text-xs font-bold rounded-lg hover:bg-slate-50 text-slate-700 hover:text-emerald-600 transition-colors"
                                            >
                                                {{ agency.name }}
                                            </button>
                                            <div v-if="filteredAgenciesDropdown.length === 0" class="text-center py-4 text-xs text-slate-400">
                                                Aucune agence trouvée
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 2. Building Searchable Select -->
                            <div class="relative" v-if="selectedAgencyId !== null">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Bâtiment *</label>
                                <div class="relative">
                                    <button 
                                        type="button" 
                                        @click="toggleBuildingDropdown" 
                                        class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl flex justify-between items-center text-sm font-semibold text-slate-800 shadow-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                                    >
                                        <span>{{ selectedBuildingName || 'Sélectionner le bâtiment' }}</span>
                                        <i class="fa-solid fa-chevron-down text-slate-450 transition-transform" :class="{'rotate-180': isBuildingDropdownOpen}"></i>
                                    </button>
                                    <div v-if="isBuildingDropdownOpen" class="absolute z-35 w-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-xl p-2 max-h-60 overflow-y-auto" @click.stop>
                                        <input 
                                            v-model="buildingSearchQuery" 
                                            type="text" 
                                            placeholder="Rechercher un bâtiment..." 
                                            class="w-full px-3 py-2 text-xs font-semibold border border-slate-200 rounded-xl mb-2 focus:outline-none focus:border-emerald-500"
                                        />
                                        <div class="space-y-1">
                                            <button 
                                                v-for="building in filteredBuildingsDropdown" 
                                                :key="building.id" 
                                                @click="selectBuilding(building)" 
                                                type="button" 
                                                class="w-full text-left px-3 py-2 text-xs font-bold rounded-lg hover:bg-slate-50 text-slate-700 hover:text-emerald-600 transition-colors"
                                            >
                                                {{ building.nom }}
                                            </button>
                                            <div v-if="filteredBuildingsDropdown.length === 0" class="text-center py-4 text-xs text-slate-400">
                                                Aucun bâtiment trouvé
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 3. Tenant Searchable Select -->
                            <div class="relative" v-if="selectedBuildingId !== null">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Locataire Actif *</label>
                                <div class="relative">
                                    <button 
                                        type="button" 
                                        @click="toggleTenantDropdown" 
                                        class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl flex justify-between items-center text-sm font-semibold text-slate-800 shadow-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                                    >
                                        <span>{{ selectedTenantLabel || 'Sélectionner le locataire' }}</span>
                                        <i class="fa-solid fa-chevron-down text-slate-450 transition-transform" :class="{'rotate-180': isTenantDropdownOpen}"></i>
                                    </button>
                                    <div v-if="isTenantDropdownOpen" class="absolute z-30 w-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-xl p-2 max-h-60 overflow-y-auto" @click.stop>
                                        <input 
                                            v-model="tenantSearchQuery" 
                                            type="text" 
                                            placeholder="Rechercher par nom ou numéro de bail..." 
                                            class="w-full px-3 py-2 text-xs font-semibold border border-slate-200 rounded-xl mb-2 focus:outline-none focus:border-emerald-500"
                                        />
                                        <div class="space-y-1">
                                            <button 
                                                v-for="contract in filteredTenantsDropdown" 
                                                :key="contract.id" 
                                                @click="selectContrat(contract)" 
                                                type="button" 
                                                class="w-full text-left px-3 py-2 text-xs font-bold rounded-lg hover:bg-slate-50 text-slate-700 hover:text-emerald-600 transition-colors"
                                            >
                                                {{ contract.locataire }} (Bail {{ contract.numero }} - Loyer: {{ formatCurrency(contract.loyer) }} - Cycle: {{ contract.cycle_paiement }})
                                            </button>
                                            <div v-if="filteredTenantsDropdown.length === 0" class="text-center py-4 text-xs text-slate-400">
                                                Aucun locataire éligible trouvé
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 4. Month Selection Cards -->
                            <div class="mt-6" v-if="selectedContratId !== null">
                                <div class="flex justify-between items-center mb-3">
                                    <label class="block text-xs font-extrabold text-slate-550 uppercase tracking-widest">
                                        Sélectionner les mois de loyer à régler *
                                    </label>
                                    <span class="text-[10px] font-bold px-2 py-0.5 bg-emerald-50 text-emerald-700 rounded-full border border-emerald-200">
                                        Cycle: {{ selectedContrat?.cycle_paiement }}
                                    </span>
                                </div>
                                
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                    <div
                                        v-for="(m, idx) in contractMonthsList"
                                        :key="m.periode"
                                        @click="m.isPaid ? null : selectMonthCard(m, idx)"
                                        :class="[
                                            'p-3.5 rounded-2xl border transition-all flex flex-col justify-between min-h-[105px] relative',
                                            m.isPaid 
                                                ? 'bg-emerald-100 border-emerald-300 text-emerald-800 cursor-not-allowed'
                                                : selectedMonths.includes(m.periode)
                                                    ? 'bg-emerald-50 border-emerald-500 ring-2 ring-emerald-500/20 text-emerald-900 cursor-pointer hover:border-emerald-600'
                                                    : 'bg-white border-slate-200 text-slate-700 cursor-pointer hover:border-slate-350 hover:bg-slate-50/50'
                                        ]"
                                    >
                                        <div class="flex justify-between items-start">
                                            <span class="font-extrabold text-xs tracking-tight">{{ formatPeriod(m.periode) }}</span>
                                            <span v-if="m.isPaid" class="text-[9px] font-extrabold uppercase bg-emerald-100 text-emerald-800 px-1.5 py-0.5 rounded">
                                                Réglé
                                            </span>
                                            <span v-else-if="selectedMonths.includes(m.periode)" class="text-[9px] font-extrabold uppercase bg-emerald-600 text-white w-4 h-4 flex items-center justify-center rounded-full shadow-sm shadow-emerald-600/30">
                                                <i class="fa-solid fa-check"></i>
                                            </span>
                                        </div>

                                        <div class="mt-3">
                                            <div class="text-[10px] font-semibold text-slate-400">Loyer: {{ formatCurrency(m.loyer) }}</div>
                                            <div v-if="m.penalite > 0" class="text-[9px] font-bold text-red-500 mt-0.5">
                                                Pén: +{{ m.penaliteRate }}% (+{{ formatCurrency(m.penalite) }})
                                            </div>
                                            <div class="text-xs font-extrabold text-slate-800 mt-1">
                                                Dû: {{ formatCurrency(Number(m.loyer) + Number(m.penalite)) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer buttons -->
                    <div class="flex gap-4 mt-8 border-t border-slate-100 pt-5">
                        <button @click="closeModal" class="flex-1 px-5 py-3.5 bg-slate-100 text-slate-650 rounded-xl font-bold hover:bg-slate-200 transition-all text-xs">Annuler</button>
                        <button 
                            @click="triggerPaymentMethodSetup" 
                            :disabled="selectedMonths.length === 0"
                            class="flex-1 px-5 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold shadow-md shadow-emerald-500/20 hover:scale-[1.01] transition-all text-xs disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100"
                        >
                            Procéder au Règlement
                        </button>
                    </div>
                </div>

                <!-- Right: Real-Time Quittance Preview (col-span-5) -->
                <div class="lg:col-span-5 border-l border-slate-200 pl-4 flex flex-col justify-between max-h-[80vh]">
                    <div>
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Aperçu en temps réel</span>
                        
                        <div class="border border-slate-200 rounded-2xl p-6 bg-slate-50/50 shadow-inner font-sans text-xs text-slate-850 relative overflow-hidden h-[62vh] overflow-y-auto scrollbar-thin">
                            <!-- Subtle Watermark -->
                            <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-[0.03] rotate-[30deg]">
                                <span class="text-4xl font-extrabold tracking-widest text-slate-900 uppercase">{{ selectedAgencyName || 'Siège Social' }}</span>
                            </div>

                            <div class="text-center border-b border-slate-150 pb-4 mb-4">
                                <div class="font-extrabold text-slate-900 text-sm uppercase">REÇU DE LOYER PROVISOIRE</div>
                                <div class="text-[9px] text-slate-400 mt-1">Généré le {{ formatDate(new Date().toDateString()) }}</div>
                            </div>

                            <div class="space-y-3 mb-6">
                                <div class="flex justify-between">
                                    <span class="text-slate-400 font-medium">Bailleur / Compagnie :</span>
                                    <span class="font-bold text-slate-800 uppercase">{{ currentCompanyName || 'Enterprise Property Corp' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400 font-medium">Agence Gérante :</span>
                                    <span class="font-bold text-slate-800">{{ selectedAgencyName || 'Siège Social' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400 font-medium">Locataire :</span>
                                    <span class="font-bold text-slate-800">{{ selectedContrat?.locataire || 'Non sélectionné' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400 font-medium">Logement :</span>
                                    <span class="font-bold text-slate-850">{{ selectedContrat?.reference || 'N/A' }} ({{ selectedContrat?.batiment || 'N/A' }})</span>
                                </div>
                                <div class="flex justify-between" v-if="selectedContrat">
                                    <span class="text-slate-400 font-medium">Durée du bail :</span>
                                    <span class="font-bold text-slate-800">Du {{ formatDate(selectedContrat.debut) }} au {{ formatDate(selectedContrat.fin) }}</span>
                                </div>
                            </div>

                            <!-- List of months being paid -->
                            <table class="w-full text-left border-collapse border-b border-slate-150 pb-4 mb-4">
                                <thead>
                                    <tr class="border-b border-slate-200 text-[9px] text-slate-400 font-bold uppercase">
                                        <th class="pb-2">Période</th>
                                        <th class="pb-2 text-right">Loyer Base</th>
                                        <th class="pb-2 text-right">Pénalité</th>
                                        <th class="pb-2 text-right">Total due</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in receiptPreviewData.items" :key="item.periode" class="border-b border-slate-100/50">
                                        <td class="py-2 font-bold text-slate-800">{{ formatPeriod(item.periode) }}</td>
                                        <td class="py-2 text-right text-slate-650">{{ formatCurrency(item.loyer) }}</td>
                                        <td class="py-2 text-right text-red-500 font-semibold">
                                            +{{ formatCurrency(item.penalite) }}
                                            <span v-if="item.penaliteRate > 0" class="text-[9px] font-bold text-red-400 block">({{ item.penaliteRate }}%)</span>
                                        </td>
                                        <td class="py-2 text-right font-extrabold text-slate-800">{{ formatCurrency(Number(item.loyer) + Number(item.penalite)) }}</td>
                                    </tr>
                                    <tr v-if="receiptPreviewData.items.length === 0">
                                        <td colspan="4" class="py-6 text-center text-slate-400 font-semibold">Aucun mois sélectionné</td>
                                    </tr>
                                </tbody>
                            </table>

                            <!-- Summary totals -->
                            <div class="space-y-2 pt-2">
                                <div class="flex justify-between text-slate-500">
                                    <span>Total Loyers de Base :</span>
                                    <span class="font-bold text-slate-700">{{ formatCurrency(receiptPreviewData.subtotalRent) }}</span>
                                </div>
                                <div class="flex justify-between text-slate-500">
                                    <span>Total Pénalités :</span>
                                    <span class="font-bold text-red-500">+{{ formatCurrency(receiptPreviewData.subtotalPenalties) }}</span>
                                </div>
                                <div class="flex justify-between items-center bg-emerald-50 p-4 rounded-xl border border-emerald-100 text-emerald-800 mt-4">
                                    <span class="font-extrabold text-xs">TOTAL ENCAISSÉ GLOBAL</span>
                                    <strong class="text-base font-black">{{ formatCurrency(receiptPreviewData.total) }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Settlement Mode Modal (Pro Pop-up for Cash / Wallet) -->
        <div v-if="showPaymentMethodModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-55 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-6 border border-slate-200 animate-scale-up" @click.stop>
                <div class="flex justify-between items-center mb-6 border-b border-slate-100 pb-4">
                    <h3 class="text-lg font-extrabold text-slate-800">Finaliser le règlement</h3>
                    <button @click="closePaymentMethodModal" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-all">
                        <i class="fa-solid fa-xmark text-slate-500"></i>
                    </button>
                </div>

                <div class="space-y-4 mb-6">
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 space-y-2">
                        <div class="flex justify-between text-xs">
                            <span class="text-slate-400 font-medium">Locataire</span>
                            <span class="font-bold text-slate-800">{{ selectedContrat?.locataire }}</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-slate-400 font-medium">Mois réglés ({{ selectedMonths.length }})</span>
                            <span class="font-bold text-slate-800">{{ selectedMonths.map(p => formatPeriod(p)).join(', ') }}</span>
                        </div>
                        <div class="flex justify-between items-center border-t border-slate-200/50 pt-2">
                            <span class="text-slate-400 font-medium text-xs">Montant total</span>
                            <span class="font-extrabold text-emerald-600 text-base">{{ formatCurrency(receiptPreviewData.total) }}</span>
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
                                    paymentMode === 'cash' ? 'border-emerald-500 bg-emerald-50 text-emerald-700' : 'border-slate-200 hover:border-slate-300 text-slate-650'
                                ]"
                            >
                                <i :class="[
                                    'fa-solid fa-money-bill-wave text-2xl transition-all',
                                    paymentMode === 'cash' ? 'text-emerald-600 scale-110' : 'text-slate-400'
                                ]"></i>
                                <span>Espèces (Cash)</span>
                            </button>
                            <button 
                                type="button" 
                                @click="paymentMode = 'wallet'"
                                :class="[
                                    'p-4 rounded-2xl border-2 transition-all flex flex-col items-center gap-2 font-bold text-xs',
                                    paymentMode === 'wallet' ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-slate-200 hover:border-slate-300 text-slate-655'
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

                    <div v-if="paymentMode === 'wallet'">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Référence Transaction Wallet</label>
                        <input 
                            type="text" 
                            v-model="referenceTx" 
                            placeholder="Ex: WLT-TX-9082348" 
                            class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-semibold text-slate-800 text-sm shadow-sm"
                        />
                    </div>
                </div>

                <div class="flex gap-3">
                    <button @click="closePaymentMethodModal" class="flex-1 px-4 py-3 bg-slate-100 text-slate-600 rounded-xl font-bold hover:bg-slate-200 transition-all text-xs">Annuler</button>
                    <button 
                        @click="savePayment" 
                        :disabled="savingPayment"
                        class="flex-1 px-4 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-xl font-bold shadow-lg shadow-emerald-500/20 hover:shadow-xl hover:shadow-emerald-500/35 transition-all text-xs flex items-center justify-center gap-2"
                    >
                        <i v-if="savingPayment" class="fa-solid fa-spinner animate-spin"></i>
                        Confirmer & Payer
                    </button>
                </div>
            </div>
        </div>

        <!-- View Receipt Details Modal -->
        <div v-if="showViewModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full p-8 border border-slate-200 relative overflow-hidden animate-scale-up">
                <div class="flex justify-between items-center mb-6 border-b border-slate-100 pb-4">
                    <h3 class="text-lg font-extrabold text-slate-800">Reçu de Loyer</h3>
                    <button @click="closeViewModal" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-all">
                        <i class="fa-solid fa-xmark text-slate-500"></i>
                    </button>
                </div>

                <!-- Printable Receipt Format -->
                <div id="receipt-print-area" class="border border-slate-200 rounded-2xl p-6 bg-white font-sans text-xs text-slate-850 shadow-inner relative overflow-hidden">
                    <!-- Subtle Watermark in Print Interface -->
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-[0.03] rotate-[30deg]">
                        <span class="text-4xl font-extrabold tracking-widest text-slate-900 uppercase">{{ viewingPayment?.agency_name || 'Siège Social' }}</span>
                    </div>

                    <div class="flex justify-between items-start border-b border-slate-100 pb-4 mb-4">
                        <div>
                            <div class="font-extrabold text-slate-900 text-sm uppercase">{{ viewingPayment?.company_name || currentCompanyName }}</div>
                            <div class="text-[9px] text-slate-400">Agence gérante : {{ viewingPayment?.agency_name || 'Siège Social' }}</div>
                        </div>
                        <div class="text-right">
                            <div class="font-extrabold text-emerald-650 text-sm">ENCAISSÉ</div>
                            <div class="text-xs font-bold text-slate-800 mt-1">{{ viewingPayment?.reference }}</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <span class="block text-[9px] font-bold text-slate-450 uppercase mb-0.5">Locataire</span>
                            <strong class="text-slate-800 text-xs">{{ viewingPayment?.locataire }}</strong>
                            <span class="block text-slate-550 mt-1">Logement: {{ viewingPayment?.logement }}</span>
                            <span class="block text-slate-550">{{ viewingPayment?.batiment }}</span>
                            <span v-if="viewingPayment?.contrat_debut" class="block text-slate-550 mt-1">
                                Durée bail : du {{ formatDate(viewingPayment.contrat_debut) }} au {{ formatDate(viewingPayment.contrat_fin) }}
                            </span>
                        </div>
                        <div class="text-right">
                            <span class="block text-[9px] font-bold text-slate-450 uppercase mb-0.5">Détails de transaction</span>
                            <span class="block text-[10px] text-slate-650">Bail: {{ viewingPayment?.contrat_numero }}</span>
                            <span class="block text-[10px] text-slate-650">Date: {{ formatDate(viewingPayment?.date_reglement) }}</span>
                            <span class="block text-[10px] text-slate-650 capitalize">Mode: {{ viewingPayment?.mode_reglement === 'cash' ? 'Cash' : 'Wallet' }}</span>
                            <span class="block text-[10px] text-slate-500" v-if="viewingPayment?.reference_tx">Ref Tx: {{ viewingPayment?.reference_tx }}</span>
                        </div>
                    </div>

                    <table class="w-full mb-6">
                        <thead>
                            <tr class="border-b border-slate-150 text-[9px] text-slate-400 font-bold uppercase text-left">
                                <th class="pb-2">Période payée</th>
                                <th class="text-right pb-2">Loyer Base</th>
                                <th class="text-right pb-2">Pénalité</th>
                                <th class="text-right pb-2">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in viewingPayment?.mois_payes" :key="item.periode" class="border-b border-slate-50/50">
                                <td class="py-2 text-slate-700 font-extrabold">{{ formatPeriod(item.periode) }}</td>
                                <td class="py-2 text-right text-slate-650">{{ formatCurrency(item.loyer_de_base) }}</td>
                                <td class="py-2 text-right text-red-500 font-semibold">+{{ formatCurrency(item.penalite) }}</td>
                                <td class="py-2 text-right font-extrabold text-slate-800">{{ formatCurrency(item.total_paye) }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="flex justify-between items-center bg-slate-50 p-4 rounded-xl border border-slate-100">
                        <div>
                            <span class="block text-[9px] text-slate-405 uppercase">Status règlement</span>
                            <strong class="text-emerald-600 font-bold text-sm">Validé & Payé</strong>
                        </div>
                        <div class="text-right">
                            <span class="block text-[9px] text-slate-405 uppercase">Montant Encaissé Global</span>
                            <strong class="text-emerald-600 text-sm font-extrabold">{{ formatCurrency(viewingPayment?.montant_total) }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Action Button -->
                <div class="flex justify-between items-center mt-6">
                    <button @click="printReceipt" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold flex items-center gap-1.5 transition-all">
                        <i class="fa-solid fa-print"></i> Imprimer la Quittance
                    </button>
                    <button @click="closeViewModal" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all">Fermer</button>
                </div>
            </div>
        </div>

        <!-- Custom Delete Confirmation Modal -->
        <div v-if="showDeleteConfirmModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-6 border border-slate-200 animate-scale-up" @click.stop>
                <div class="flex justify-between items-center mb-6 border-b border-slate-100 pb-4">
                    <h3 class="text-lg font-extrabold text-slate-800">Confirmer l'annulation</h3>
                    <button @click="closeDeleteConfirmModal" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-all">
                        <i class="fa-solid fa-xmark text-slate-500"></i>
                    </button>
                </div>

                <div class="text-center mb-6">
                    <div class="flex items-center justify-center w-14 h-14 rounded-2xl bg-red-50 text-red-650 mx-auto mb-4 border border-red-100">
                        <i class="fa-solid fa-trash-can text-lg"></i>
                    </div>
                    <p class="text-slate-650 font-semibold text-sm">
                        Êtes-vous sûr de vouloir supprimer le reçu de paiement <span class="font-extrabold text-slate-800">{{ paymentToDelete?.reference }}</span> ?
                    </p>
                    <p class="text-[11px] text-red-500 font-bold mt-2">
                        ⚠️ Cette action est irréversible et annulera la transaction de loyer.
                    </p>
                </div>

                <div class="flex gap-3">
                    <button @click="closeDeleteConfirmModal" :disabled="deletingPayment" class="flex-1 px-4 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold transition-all text-xs">Annuler</button>
                    <button 
                        @click="executeDeletePayment" 
                        :disabled="deletingPayment"
                        class="flex-1 px-4 py-3 bg-red-600 hover:bg-red-500 text-white rounded-xl font-bold shadow-lg shadow-red-500/20 hover:shadow-xl hover:shadow-red-500/35 transition-all text-xs flex items-center justify-center gap-2"
                    >
                        <i v-if="deletingPayment" class="fa-solid fa-spinner animate-spin"></i>
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
                        <i class="fa-solid fa-circle-check text-white text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-extrabold text-slate-800 mb-1">Succès !</h3>
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
                        <i class="fa-solid fa-circle-exclamation text-white text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-extrabold text-slate-800 mb-1">Erreur</h3>
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

// States
const payments = ref([]);
const contratsActifs = ref([]);
const batiments = ref([]);
const regleLoyers = ref([]);
const agencies = ref([]);
const deviseSymbol = ref('€');
const currentCompanyName = ref('');

// Loading state
const loading = ref(false);
const savingPayment = ref(false);
const deletingPayment = ref(false);

// Filters
const searchQuery = ref('');
const agencyFilter = ref('All');
const buildingFilter = ref('All');
const periodFilter = ref('All');
const methodFilter = ref('All');

// Modal states
const showModal = ref(false);
const showPaymentMethodModal = ref(false);
const showViewModal = ref(false);
const showDeleteConfirmModal = ref(false);
const showSuccess = ref(false);
const showError = ref(false);
const successMessage = ref('');
const errorMessage = ref('');

// Selections
const selectedAgencyId = ref(null);
const selectedAgencyName = ref('');
const selectedBuildingId = ref(null);
const selectedBuildingName = ref('');
const selectedContratId = ref(null);
const selectedTenantLabel = ref('');

const selectedMonths = ref([]); // array of selected months (periods) e.g. ['2026-06']
const paymentMode = ref('cash');
const referenceTx = ref('');

// Search queries inside dropdowns
const agencySearchQuery = ref('');
const buildingSearchQuery = ref('');
const tenantSearchQuery = ref('');

// Toggle dropdown states
const isAgencyDropdownOpen = ref(false);
const isBuildingDropdownOpen = ref(false);
const isTenantDropdownOpen = ref(false);

// Active objects
const viewingPayment = ref(null);
const paymentToDelete = ref(null);

onMounted(() => {
    fetchPageData();
});

const fetchPageData = async () => {
    loading.value = true;
    try {
        const [
            paymentsRes,
            contratsRes,
            batimentsRes,
            rulesRes,
            deviseRes,
            agenciesRes
        ] = await Promise.all([
            axios.get('/api/paiement-loyers').catch(err => { console.error("Error loading payments:", err); return { data: [] }; }),
            axios.get('/api/contrats').catch(err => { console.error("Error loading contrats:", err); return { data: [] }; }),
            axios.get('/api/batiments').catch(err => { console.error("Error loading batiments:", err); return { data: [] }; }),
            axios.get('/api/regle-loyers').catch(err => { console.error("Error loading rules:", err); return { data: [] }; }),
            axios.get('/api/devise').catch(err => { console.error("Error loading devise:", err); return null; }),
            axios.get('/agencies', { headers: { 'Accept': 'application/json' }, params: { per_page: 1000 } }).catch(err => { console.error("Error loading agencies:", err); return null; })
        ]);

        payments.value = paymentsRes.data || [];
        contratsActifs.value = (contratsRes.data || []).filter(c => c.statut === 'Actif');
        batiments.value = batimentsRes.data || [];
        regleLoyers.value = rulesRes.data || [];

        if (deviseRes && deviseRes.data) {
            deviseSymbol.value = deviseRes.data.symbole || '€';
        }

        if (agenciesRes && agenciesRes.data) {
            const data = agenciesRes.data;
            agencies.value = data.agencies?.data || data.agencies || [];
        }

        if (payments.value.length > 0) {
            currentCompanyName.value = payments.value[0].company_name || '';
        }
    } catch (err) {
        console.error("Critical error loading page data:", err);
        errorMessage.value = "Une erreur est survenue lors du chargement des données.";
        showError.value = true;
    } finally {
        loading.value = false;
    }
};

// Available unique periods
const availablePeriods = computed(() => {
    const periods = [];
    payments.value.forEach(p => {
        if (p.mois_payes) {
            p.mois_payes.forEach(m => {
                if (m.periode) periods.push(m.periode);
            });
        }
    });
    return [...new Set(periods)].sort().reverse();
});

// Cascades & Dropdown filtering
const filteredAgenciesDropdown = computed(() => {
    const q = agencySearchQuery.value.toLowerCase();
    return agencies.value.filter(a => a.name.toLowerCase().includes(q));
});

const filteredBuildingsDropdown = computed(() => {
    const q = buildingSearchQuery.value.toLowerCase();
    return batiments.value
        .filter(b => !selectedAgencyId.value || Number(b.agency_id) === Number(selectedAgencyId.value))
        .filter(b => b.nom.toLowerCase().includes(q));
});

const filteredTenantsDropdown = computed(() => {
    const q = tenantSearchQuery.value.toLowerCase();
    return contratsActifs.value
        .filter(c => !selectedBuildingId.value || Number(c.batiment_id) === Number(selectedBuildingId.value))
        .filter(c => c.locataire.toLowerCase().includes(q) || c.numero.toLowerCase().includes(q));
});

const selectedContrat = computed(() => {
    if (!selectedContratId.value) return null;
    return contratsActifs.value.find(c => c.id === selectedContratId.value) || null;
});

// Paid months list for currently selected contract
const paidMonthsList = computed(() => {
    if (!selectedContratId.value) return [];
    const list = [];
    payments.value.forEach(p => {
        if (Number(p.contrat_id) === Number(selectedContratId.value)) {
            p.mois_payes.forEach(m => {
                list.push(m.periode);
            });
        }
    });
    return list;
});

// Months list within lease duration
const contractMonthsList = computed(() => {
    const contrat = selectedContrat.value;
    if (!contrat) return [];

    const start = new Date(contrat.debut);
    const end = new Date(contrat.fin);
    const list = [];

    let current = new Date(start.getFullYear(), start.getMonth(), 1);
    while (current <= end) {
        const yr = current.getFullYear();
        const mo = String(current.getMonth() + 1).padStart(2, '0');
        const period = `${yr}-${mo}`;

        const isPaid = paidMonthsList.value.includes(period);
        const loyer = Number(contrat.loyer);
        const penaltyInfo = isPaid ? { amount: 0, rate: 0 } : calculatePenaltyInfo(period, loyer, contrat.cycle_paiement);

        list.push({
            periode: period,
            loyer,
            penalite: penaltyInfo.amount,
            penaliteRate: penaltyInfo.rate,
            isPaid
        });
        current.setMonth(current.getMonth() + 1);
        current = new Date(current.getFullYear(), current.getMonth(), 1);
    }
    return list;
});

// Live calculated receipt preview
const receiptPreviewData = computed(() => {
    if (!selectedContrat.value || selectedMonths.value.length === 0) {
        return { items: [], subtotalRent: 0, subtotalPenalties: 0, total: 0 };
    }

    const items = [];
    let subtotalRent = 0;
    let subtotalPenalties = 0;

    selectedMonths.value.forEach(periode => {
        const matching = contractMonthsList.value.find(m => m.periode === periode);
        if (matching) {
            items.push(matching);
            subtotalRent += matching.loyer;
            subtotalPenalties += matching.penalite;
        }
    });

    return {
        items,
        subtotalRent,
        subtotalPenalties,
        total: subtotalRent + subtotalPenalties
    };
});

// Penalty calculation logic by cycle and days elapsed since block start
const calculatePenaltyInfo = (periode, baseRent, cycle) => {
    const activeCycle = cycle || 'Mensuel';
    const rules = regleLoyers.value.filter(r => (r.cycle || 'Mensuel') === activeCycle);
    if (rules.length === 0) {
        return { amount: 0, rate: 0 };
    }

    const today = new Date();
    const todayMidnight = new Date(today.getFullYear(), today.getMonth(), today.getDate());

    const parts = periode.split('-');
    const dueYear = parseInt(parts[0], 10);
    const dueMonth = parseInt(parts[1], 10) - 1; // 0-indexed
    const dueDate = new Date(dueYear, dueMonth, 1);

    const diffTime = todayMidnight - dueDate;
    const daysElapsed = Math.floor(diffTime / (1000 * 60 * 60 * 24)) + 1;

    // Future month -> No penalty
    if (daysElapsed <= 0) {
        return { amount: 0, rate: 0 };
    }

    // Filter rules where trigger is <= daysElapsed
    const matchingRules = rules.filter(r => Number(r.jour_declenchement) <= daysElapsed);
    if (matchingRules.length === 0) {
        return { amount: 0, rate: 0 };
    }

    // Get max rate matching
    const maxRate = Math.max(...matchingRules.map(r => Number(r.taux_penalite)));
    const amount = Number((baseRent * (maxRate / 100)).toFixed(2));
    return { amount, rate: maxRate };
};

// Selection of month cards ensuring sequential selection in waves of cycle
const selectMonthCard = (monthObj, idx) => {
    const isSelected = selectedMonths.value.includes(monthObj.periode);
    const unpaidList = contractMonthsList.value.filter(m => !m.isPaid);
    const relativeIdx = unpaidList.findIndex(m => m.periode === monthObj.periode);
    if (relativeIdx === -1) return;

    const cycle = selectedContrat.value?.cycle_paiement || 'Mensuel';
    let k = 1;
    if (cycle === 'Trimestriel') k = 3;
    else if (cycle === 'Annuel') k = 12;

    if (!isSelected) {
        // Select this month and round UP to the next multiple of k
        const targetCount = relativeIdx + 1;
        const remainder = targetCount % k;
        const neededCount = remainder === 0 ? targetCount : targetCount + (k - remainder);
        const limit = Math.min(neededCount, unpaidList.length);
        
        const newSelection = [];
        for (let i = 0; i < limit; i++) {
            newSelection.push(unpaidList[i].periode);
        }
        selectedMonths.value = newSelection;
    } else {
        // Deselect this month and round DOWN remaining selected count to the nearest multiple of k
        const targetCount = relativeIdx;
        const neededCount = targetCount - (targetCount % k);
        
        const newSelection = [];
        for (let i = 0; i < neededCount; i++) {
            newSelection.push(unpaidList[i].periode);
        }
        selectedMonths.value = newSelection;
    }
};

// Dropdown toggles
const toggleAgencyDropdown = () => { isAgencyDropdownOpen.value = !isAgencyDropdownOpen.value; };
const toggleBuildingDropdown = () => { isBuildingDropdownOpen.value = !isBuildingDropdownOpen.value; };
const toggleTenantDropdown = () => { isTenantDropdownOpen.value = !isTenantDropdownOpen.value; };

const selectAgency = (agency) => {
    selectedAgencyId.value = agency.id;
    selectedAgencyName.value = agency.name;
    isAgencyDropdownOpen.value = false;
    // Reset cascade
    selectedBuildingId.value = null;
    selectedBuildingName.value = '';
    selectedContratId.value = null;
    selectedTenantLabel.value = '';
    selectedMonths.value = [];
};

const selectBuilding = (building) => {
    selectedBuildingId.value = building.id;
    selectedBuildingName.value = building.nom;
    isBuildingDropdownOpen.value = false;
    // Reset tenant
    selectedContratId.value = null;
    selectedTenantLabel.value = '';
    selectedMonths.value = [];
};

const selectContrat = (contract) => {
    selectedContratId.value = contract.id;
    selectedTenantLabel.value = `${contract.locataire} (Loyer: ${contract.loyer} ${deviseSymbol.value})`;
    isTenantDropdownOpen.value = false;
    selectedMonths.value = [];
};

// Filtered payments (list)
const filteredPayments = computed(() => {
    let result = payments.value;

    if (agencyFilter.value !== 'All') {
        result = result.filter(p => Number(p.agency_id) === Number(agencyFilter.value) || (agencyFilter.value === 'siege' && !p.agency_id));
    }

    if (buildingFilter.value !== 'All') {
        result = result.filter(p => p.batiment === buildingFilter.value);
    }

    if (periodFilter.value !== 'All') {
        result = result.filter(p => p.mois_payes && p.mois_payes.some(m => m.periode === periodFilter.value));
    }

    if (methodFilter.value !== 'All') {
        result = result.filter(p => p.mode_reglement === methodFilter.value);
    }

    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        result = result.filter(p => 
            p.locataire.toLowerCase().includes(query) ||
            p.reference.toLowerCase().includes(query) ||
            p.logement.toLowerCase().includes(query)
        );
    }

    return result;
});

// Dynamically responsive KPIs
const totalCollected = computed(() => filteredPayments.value.reduce((sum, p) => sum + p.montant_total, 0));
const totalTransactions = computed(() => filteredPayments.value.length);
const collectedThisMonth = computed(() => {
    const now = new Date();
    const currentMonthStr = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}`;
    return filteredPayments.value
        .filter(p => p.date_reglement && p.date_reglement.startsWith(currentMonthStr))
        .reduce((sum, p) => sum + p.montant_total, 0);
});
const topPaymentMethod = computed(() => {
    if (filteredPayments.value.length === 0) return 'Wallet';
    const counts = {};
    filteredPayments.value.forEach(p => counts[p.mode_reglement] = (counts[p.mode_reglement] || 0) + 1);
    const fav = Object.keys(counts).reduce((a, b) => counts[a] > counts[b] ? a : b, 'wallet');
    return fav === 'cash' ? 'Cash (Espèces)' : 'Wallet (Portefeuille)';
});

// Modal controls
const openAddModal = () => {
    selectedAgencyId.value = null;
    selectedAgencyName.value = '';
    selectedBuildingId.value = null;
    selectedBuildingName.value = '';
    selectedContratId.value = null;
    selectedTenantLabel.value = '';
    selectedMonths.value = [];
    
    agencySearchQuery.value = '';
    buildingSearchQuery.value = '';
    tenantSearchQuery.value = '';
    
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
};

const triggerPaymentMethodSetup = () => {
    const cycle = selectedContrat.value?.cycle_paiement || 'Mensuel';
    const count = selectedMonths.value.length;
    const unpaidList = contractMonthsList.value.filter(m => !m.isPaid);
    const isPayingAllRemaining = count === unpaidList.length;

    // Apply cycle business rules (unless paying all remaining months at the end of lease)
    if (cycle === 'Trimestriel' && count % 3 !== 0 && !isPayingAllRemaining) {
        errorMessage.value = "Pour un cycle trimestriel, le paiement doit être effectué par tranche obligatoire de 3 mois.";
        showError.value = true;
        return;
    }

    if (cycle === 'Annuel' && count % 12 !== 0 && !isPayingAllRemaining) {
        errorMessage.value = "Pour un cycle annuel, le paiement doit être effectué par tranche obligatoire de 12 mois (1 an).";
        showError.value = true;
        return;
    }

    paymentMode.value = 'cash';
    referenceTx.value = '';
    showPaymentMethodModal.value = true;
};

const closePaymentMethodModal = () => {
    showPaymentMethodModal.value = false;
};

const savePayment = async () => {
    if (selectedMonths.value.length === 0) return;
    
    savingPayment.value = true;
    try {
        const payloadMonths = receiptPreviewData.value.items.map(m => ({
            periode: m.periode,
            loyer_de_base: m.loyer,
            penalite: m.penalite,
            total_paye: m.loyer + m.penalite
        }));

        const response = await axios.post('/api/paiement-loyers', {
            locataire_id: selectedContrat.value.locataire_id,
            contrat_id: selectedContrat.value.id,
            date_reglement: new Date().toISOString().split('T')[0],
            mode_reglement: paymentMode.value,
            reference_tx: paymentMode.value === 'wallet' ? referenceTx.value : null,
            months: payloadMonths
        });

        payments.value.unshift(response.data);
        showPaymentMethodModal.value = false;
        showModal.value = false;
        
        successMessage.value = "Le règlement de loyer a été enregistré avec succès et la quittance est prête.";
        showSuccess.value = true;
    } catch (err) {
        console.error("Error creating rent payment:", err);
        errorMessage.value = err.response?.data?.message || "Une erreur est survenue lors de l'enregistrement du paiement.";
        showError.value = true;
    } finally {
        savingPayment.value = false;
    }
};

const viewReceipt = (pay) => {
    viewingPayment.value = pay;
    showViewModal.value = true;
};

const closeViewModal = () => {
    showViewModal.value = false;
    viewingPayment.value = null;
};

// Delete confirmation modal actions
const deletePayment = (pay) => {
    paymentToDelete.value = pay;
    showDeleteConfirmModal.value = true;
};

const closeDeleteConfirmModal = () => {
    showDeleteConfirmModal.value = false;
    paymentToDelete.value = null;
    deletingPayment.value = false;
};

const executeDeletePayment = async () => {
    if (!paymentToDelete.value) return;
    deletingPayment.value = true;
    try {
        await axios.delete(`/api/paiement-loyers/${paymentToDelete.value.id}`);
        payments.value = payments.value.filter(p => p.id !== paymentToDelete.value.id);
        successMessage.value = "La transaction de paiement a été annulée avec succès.";
        showSuccess.value = true;
    } catch (err) {
        console.error("Error deleting rent payment:", err);
        errorMessage.value = err.response?.data?.message || "Une erreur est survenue lors de l'annulation.";
        showError.value = true;
    } finally {
        closeDeleteConfirmModal();
    }
};

const printReceipt = () => {
    const printContent = document.getElementById('receipt-print-area').innerHTML;
    const w = window.open();
    w.document.write('<html><head><title>Quittance de loyer</title>');
    
    // Copy parent style sheets to preserve Tailwind layout
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
            background: #fff !important;
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
    const agencyName = viewingPayment.value?.agency_name || 'Siège Social';
    w.document.write(`<div class="watermark">${agencyName}</div>`);
    w.document.write(printContent);
    w.document.write('</body></html>');
    w.document.close();
    w.focus();
    setTimeout(() => {
        w.print();
        w.close();
    }, 550);
};

const closeSuccess = () => { showSuccess.value = false; };
const closeError = () => { showError.value = false; };

// Formatting helpers
const formatCurrency = (val) => {
    if (val === undefined || val === null || isNaN(val) || val === '') return '0 €';
    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR', maximumFractionDigits: 0 }).format(val);
};

const formatDate = (dateStr) => {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    return new Intl.DateTimeFormat('fr-FR', { day: '2-digit', month: '2-digit', year: 'numeric' }).format(date);
};

const formatPeriod = (periodStr) => {
    if (!periodStr) return '';
    const parts = periodStr.split('-');
    if (parts.length < 2) return periodStr;
    const months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
    const monthIdx = parseInt(parts[1], 10) - 1;
    return `${months[monthIdx]} ${parts[0]}`;
};

const getInitials = (name) => {
    if (!name) return 'PA';
    const parts = name.trim().split(' ');
    if (parts.length >= 2) {
        return (parts[0][0] + parts[1][0]).toUpperCase();
    }
    return name.substring(0, 2).toUpperCase();
};

const getAvatarGradient = (name) => {
    if (!name) return 'from-emerald-500 to-teal-650';
    const colors = [
        'from-emerald-500 to-teal-600',
        'from-teal-500 to-cyan-600',
        'from-blue-500 to-indigo-600',
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
