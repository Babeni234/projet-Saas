<template>
    <div class="flex flex-col gap-8">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Gestion de la Maintenance</h1>
                <p class="text-sm text-slate-500 mt-1">Gérez le cycle de vie des pannes, planifiez les interventions et suivez les budgets en temps réel.</p>
            </div>
            <button
                @click="openCreateMaintenanceModal"
                class="px-5 py-3 bg-gradient-to-r from-blue-500 via-blue-600 to-indigo-600 text-white rounded-xl text-sm font-bold hover:shadow-lg hover:shadow-blue-500/20 transition-all transform hover:scale-[1.02] flex items-center gap-2 self-start md:self-auto shadow-md shadow-blue-500/10"
            >
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 4V20M4 12H20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Créer une maintenance
            </button>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Open Tickets Card -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 hover:shadow-xl hover:shadow-slate-300/50 transition-all duration-300 hover:-translate-y-1 border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                            <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                        Actifs
                    </div>
                </div>
                <div class="text-3xl font-bold text-slate-800 mb-1">{{ openTicketsCount }}</div>
                <div class="text-sm text-slate-500 mb-4">Tickets Actifs</div>
                <div class="text-xs text-slate-400">Pour l'ensemble de la compagnie</div>
            </div>

            <!-- Critical Issues Card -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 hover:shadow-xl hover:shadow-slate-300/50 transition-all duration-300 hover:-translate-y-1 border border-slate-100 border-l-4 border-l-red-500">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg shadow-red-500/30">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                            <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-1 px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold" v-if="criticalTicketsCount > 0">
                        Urgent
                    </div>
                    <div class="flex items-center gap-1 px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold" v-else>
                        RAS
                    </div>
                </div>
                <div class="text-3xl font-bold text-slate-800 mb-1">{{ criticalTicketsCount }}</div>
                <div class="text-sm text-slate-500 mb-4">Problèmes Critiques</div>
                <div class="text-xs text-slate-400">Intervention immédiate requise</div>
            </div>

            <!-- Avg Resolution Time Card -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 hover:shadow-xl hover:shadow-slate-300/50 transition-all duration-300 hover:-translate-y-1 border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/30">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                            <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-1 px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold">
                        Moyen
                    </div>
                </div>
                <div class="text-3xl font-bold text-slate-800 mb-1">{{ formatDuration(avgResolutionTimeMinutes) }}</div>
                <div class="text-sm text-slate-500 mb-4">Temps de Résolution Moyen</div>
                <div class="text-xs text-slate-400">Pour les tâches terminées</div>
            </div>

            <!-- Maintenance Budget Card -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 hover:shadow-xl hover:shadow-slate-300/50 transition-all duration-300 hover:-translate-y-1 border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/30">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                            <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-1 px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-semibold">
                        Global
                    </div>
                </div>
                <div class="text-3xl font-bold text-slate-800 mb-1">{{ formatCurrency(budgetTotal) }}</div>
                <div class="text-sm text-slate-500 mb-4">Budget Total Estimé</div>
                <div class="text-xs text-slate-400">Terminé (Dépensé) : {{ formatCurrency(budgetUsed) }}</div>
            </div>
        </div>

        <!-- Critical Issues Section -->
        <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100" v-if="criticalTickets.length > 0">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-slate-800 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-red-500 animate-ping"></span>
                    Problèmes Critiques en Attente
                </h3>
            </div>
            <div class="space-y-3">
                <div v-for="ticket in criticalTickets" :key="ticket.id" class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 bg-red-50/70 border border-red-150 rounded-xl">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center text-red-600 mt-0.5">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div>
                            <div class="font-bold text-slate-850">{{ ticket.description }}</div>
                            <div class="text-xs text-slate-500 mt-1">
                                Cible : <span class="font-semibold text-slate-700">{{ ticket.target_type_label }} {{ ticket.target_name }}</span>
                                <span class="mx-1.5">|</span>
                                Catégorie : <span class="font-semibold text-slate-700">{{ ticket.type_maintenance?.nom || 'Inconnu' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 self-end sm:self-auto">
                        <button
                            v-if="ticket.statut === 'Créé'"
                            @click="startExecution(ticket)"
                            class="px-3.5 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg text-xs font-bold hover:shadow-md hover:shadow-blue-500/20 transition-all"
                        >
                            Débuter l'intervention
                        </button>
                        <button
                            v-if="ticket.statut === 'En cours'"
                            @click="endExecution(ticket)"
                            class="px-3.5 py-2 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-lg text-xs font-bold hover:shadow-md hover:shadow-emerald-500/20 transition-all"
                        >
                            Terminer
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Tickets by Category -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-slate-800">Tickets par Catégorie</h3>
                </div>
                <div class="h-64">
                    <canvas id="ticketsCategoryChart"></canvas>
                </div>
            </div>

            <!-- Resolution Time Trend -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-slate-800">Temps de Résolution</h3>
                </div>
                <div class="h-64">
                    <canvas id="resolutionTimeChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Real-time Maintenance Assets -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Buildings under Maintenance -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
                <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 bg-amber-500 rounded-full animate-ping"></span>
                        <h3 class="text-lg font-bold text-slate-800">Bâtiments en Maintenance Actuelle</h3>
                    </div>
                    <span class="px-2.5 py-0.5 bg-amber-100 text-amber-800 rounded-full text-xs font-bold">{{ realMaintenanceBuildings.length }}</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 font-bold">
                                <th class="p-3">Bâtiment</th>
                                <th class="p-3">Référence</th>
                                <th class="p-3">Propriétaire</th>
                                <th class="p-3">Localisation</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="b in realMaintenanceBuildings" :key="b.id" class="border-t border-slate-100 hover:bg-slate-50/50">
                                <td class="p-3 font-bold text-slate-700">{{ b.nom }}</td>
                                <td class="p-3 text-slate-500">{{ b.reference }}</td>
                                <td class="p-3 text-slate-600">{{ b.proprietaire_nom || 'Sans propriétaire' }}</td>
                                <td class="p-3 text-slate-500">{{ b.ville }}</td>
                            </tr>
                            <tr v-if="realMaintenanceBuildings.length === 0">
                                <td colspan="4" class="p-4 text-center text-slate-400 italic">Aucun bâtiment en cours de maintenance.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Lodgings under Maintenance -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
                <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 bg-amber-500 rounded-full animate-ping"></span>
                        <h3 class="text-lg font-bold text-slate-800">Logements en Maintenance Actuelle</h3>
                    </div>
                    <span class="px-2.5 py-0.5 bg-amber-100 text-amber-800 rounded-full text-xs font-bold">{{ realMaintenanceLogements.length }}</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 font-bold">
                                <th class="p-3">Logement</th>
                                <th class="p-3">Catégorie</th>
                                <th class="p-3">Bâtiment</th>
                                <th class="p-3">Loyer</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="l in realMaintenanceLogements" :key="l.id" class="border-t border-slate-100 hover:bg-slate-50/50">
                                <td class="p-3 font-bold text-slate-700">{{ l.reference }}</td>
                                <td class="p-3 text-slate-500">
                                    <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-[10px] font-bold">{{ l.categorie }}</span>
                                </td>
                                <td class="p-3 text-slate-600">{{ l.batiment || 'Indépendant' }}</td>
                                <td class="p-3 text-slate-700 font-semibold">{{ l.loyer }} €</td>
                            </tr>
                            <tr v-if="realMaintenanceLogements.length === 0">
                                <td colspan="4" class="p-4 text-center text-slate-400 italic">Aucun logement en cours de maintenance.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Tickets -->
        <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-slate-800">Tous les Tickets de Maintenance</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-4 py-3 text-xs font-semibold text-slate-600 uppercase tracking-wider">Réf</th>
                            <th class="px-4 py-3 text-xs font-semibold text-slate-600 uppercase tracking-wider">Cible</th>
                            <th class="px-4 py-3 text-xs font-semibold text-slate-600 uppercase tracking-wider">Type</th>
                            <th class="px-4 py-3 text-xs font-semibold text-slate-600 uppercase tracking-wider">Description</th>
                            <th class="px-4 py-3 text-xs font-semibold text-slate-600 uppercase tracking-wider">Priorité</th>
                            <th class="px-4 py-3 text-xs font-semibold text-slate-600 uppercase tracking-wider">Assigné à</th>
                            <th class="px-4 py-3 text-xs font-semibold text-slate-600 uppercase tracking-wider">Budget</th>
                            <th class="px-4 py-3 text-xs font-semibold text-slate-600 uppercase tracking-wider">Statut</th>
                            <th class="px-4 py-3 text-xs font-semibold text-slate-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        <tr v-for="ticket in tickets" :key="ticket.id" :class="{'bg-red-50/10': ticket.priorite === 'Critique'}">
                            <td class="px-4 py-4 font-bold text-slate-700">MNT-{{ ticket.id }}</td>
                            <td class="px-4 py-4">
                                <div class="font-semibold text-slate-800">{{ ticket.target_name }}</div>
                                <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">{{ ticket.target_type_label }}</div>
                            </td>
                            <td class="px-4 py-4">
                                <span class="px-2 py-1 bg-slate-100 rounded text-slate-600 text-xs font-semibold">
                                    {{ ticket.type_maintenance?.nom || 'Inconnu' }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-slate-600 max-w-xs truncate" :title="ticket.description">
                                {{ ticket.description }}
                            </td>
                            <td class="px-4 py-4">
                                <span :class="[
                                    'px-2.5 py-1 rounded-full text-xs font-extrabold shadow-sm',
                                    ticket.priorite === 'Critique' ? 'bg-red-100 text-red-800 border border-red-200' :
                                    ticket.priorite === 'Haute' ? 'bg-amber-105 bg-amber-50 text-amber-800 border border-amber-200' :
                                    ticket.priorite === 'Normale' ? 'bg-blue-50 text-blue-800 border border-blue-200' : 'bg-slate-100 text-slate-750'
                                ]">
                                    {{ ticket.priorite }}
                                </span>
                            </td>
                            <td class="px-4 py-4 font-medium text-slate-650">
                                {{ ticket.assigned_user?.name || 'Non assigné' }}
                            </td>
                            <td class="px-4 py-4 font-bold text-slate-800">
                                {{ formatCurrency(ticket.budget) }}
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex flex-col">
                                    <span :class="[
                                        'px-2.5 py-1 rounded-full text-xs font-bold self-start',
                                        ticket.statut === 'En cours' ? 'bg-amber-100 text-amber-700' :
                                        ticket.statut === 'Créé' ? 'bg-blue-100 text-blue-700' : 'bg-emerald-100 text-emerald-700'
                                    ]">
                                        {{ ticket.statut === 'En cours' ? 'En cours d\'exécution' : ticket.statut }}
                                    </span>
                                    <span v-if="ticket.duree_execution_minutes !== null" class="text-[10px] text-slate-400 mt-1 font-semibold">
                                        Durée : {{ formatDuration(ticket.duree_execution_minutes) }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex gap-2">
                                    <button
                                        v-if="ticket.statut === 'Créé'"
                                        @click="startExecution(ticket)"
                                        class="px-2.5 py-1 bg-blue-50 text-blue-600 border border-blue-200 rounded-lg text-xs font-bold hover:bg-blue-100 transition-colors"
                                    >
                                        Débuter
                                    </button>
                                    <button
                                        v-if="ticket.statut === 'En cours'"
                                        @click="endExecution(ticket)"
                                        class="px-2.5 py-1 bg-emerald-50 text-emerald-600 border border-emerald-200 rounded-lg text-xs font-bold hover:bg-emerald-100 transition-colors"
                                    >
                                        Terminer
                                    </button>
                                    <button
                                        @click="deleteTicket(ticket)"
                                        class="px-2.5 py-1 bg-white border border-rose-200 text-rose-600 rounded-lg text-xs font-bold hover:bg-rose-50 transition-colors"
                                    >
                                        Supprimer
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="tickets.length === 0">
                            <td colspan="9" class="px-4 py-8 text-center text-slate-400 italic">Aucune maintenance enregistrée</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Create Maintenance Modal -->
        <ModalPremium
            :show="showCreateModal"
            title="Créer une tâche de maintenance"
            subtitle="Ajoutez une nouvelle fiche d'intervention et affectez un maintenancier"
            size="lg"
            type="default"
            @close="showCreateModal = false"
        >
            <form @submit.prevent="submitCreateForm" class="space-y-6">
                <!-- Target Selection -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Type d'Actif <span class="text-red-500">*</span></label>
                        <select
                            v-model="createForm.target_type"
                            required
                            @change="createForm.target_id = ''"
                            class="w-full px-5 py-3.5 bg-slate-55 border-2 border-slate-200 rounded-2xl text-slate-707 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all font-semibold"
                        >
                            <option value="batiment">Bâtiment</option>
                            <option value="logement">Logement</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Sélectionner l'Actif <span class="text-red-500">*</span></label>
                        <select
                            v-model="createForm.target_id"
                            required
                            class="w-full px-5 py-3.5 bg-slate-55 border-2 border-slate-200 rounded-2xl text-slate-707 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all font-semibold"
                        >
                            <option value="" disabled>-- Choisir la cible --</option>
                            <option
                                v-for="t in filteredTargets"
                                :key="t.id"
                                :value="t.id"
                            >
                                {{ t.name }} [{{ t.statut_maintenance }}]
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Type, Maintenancier, Budget -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Type de Maintenance <span class="text-red-500">*</span></label>
                        <select
                            v-model="createForm.type_maintenance_id"
                            required
                            class="w-full px-5 py-3.5 bg-slate-55 border-2 border-slate-200 rounded-2xl text-slate-707 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all font-semibold"
                        >
                            <option value="" disabled>-- Choisir le type --</option>
                            <option
                                v-for="type in typeMaintenances"
                                :key="type.id"
                                :value="type.id"
                            >
                                {{ type.nom }}
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Maintenancier Assigné</label>
                        <select
                            v-model="createForm.assigned_to"
                            class="w-full px-5 py-3.5 bg-slate-55 border-2 border-slate-200 rounded-2xl text-slate-707 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all font-semibold"
                        >
                            <option :value="null">Non assigné</option>
                            <option
                                v-for="m in maintenanciers"
                                :key="m.id"
                                :value="m.id"
                            >
                                {{ m.name }} ({{ m.position }})
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Budget Estimé (€) <span class="text-red-500">*</span></label>
                        <input
                            v-model.number="createForm.budget"
                            type="number"
                            step="0.01"
                            min="0"
                            required
                            class="w-full px-5 py-3.5 bg-slate-55 border-2 border-slate-200 rounded-2xl text-slate-707 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all font-semibold"
                        />
                    </div>
                </div>

                <!-- Priority -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Niveau de Priorité <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                        <label
                            v-for="prio in priorityOptions"
                            :key="prio.value"
                            :class="[
                                'flex flex-col items-center justify-center p-3 border-2 rounded-2xl cursor-pointer text-center transition-all',
                                createForm.priorite === prio.value
                                    ? prio.activeClass
                                    : 'border-slate-200 bg-white hover:bg-slate-50 text-slate-650'
                            ]"
                        >
                            <input
                                type="radio"
                                name="priorite"
                                :value="prio.value"
                                v-model="createForm.priorite"
                                class="sr-only"
                            />
                            <span class="text-xs font-extrabold uppercase tracking-wide">{{ prio.label }}</span>
                        </label>
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Description de la Panne / Travaux <span class="text-red-500">*</span></label>
                    <textarea
                        v-model="createForm.description"
                        rows="4"
                        required
                        placeholder="Décrivez précisément le problème constaté, ex: Fuite au niveau de la canalisation principale..."
                        class="w-full px-5 py-3.5 bg-slate-55 border-2 border-slate-200 rounded-2xl text-slate-707 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all font-semibold"
                    ></textarea>
                </div>

                <!-- Modal Actions -->
                <div class="flex gap-4 justify-end pt-4 border-t border-slate-100">
                    <button
                        type="button"
                        @click="showCreateModal = false"
                        class="px-6 py-3.5 bg-white border-2 border-slate-300 text-slate-700 rounded-2xl text-sm font-bold hover:bg-slate-50 transition-all transform hover:scale-[1.02]"
                    >
                        Annuler
                    </button>
                    <button
                        type="submit"
                        :disabled="isSubmitting"
                        class="px-6 py-3.5 bg-gradient-to-r from-blue-500 via-blue-600 to-indigo-600 text-white rounded-xl text-sm font-bold hover:shadow-lg hover:shadow-blue-500/20 transition-all transform hover:scale-[1.02] flex items-center justify-center gap-2"
                    >
                        <span v-if="isSubmitting" class="animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full"></span>
                        <span>{{ isSubmitting ? 'Création (IA possible)...' : 'Créer la tâche' }}</span>
                    </button>
                </div>
            </form>
        </ModalPremium>

        <!-- Notification -->
        <NotificationPremium
            :show="notification.show"
            :type="notification.type"
            :title="notification.title"
            :message="notification.message"
            @close="closeNotification"
        />
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Chart } from 'chart.js/auto';
import ModalPremium from '../../../../Components/ModalPremium.vue';
import NotificationPremium from '../../../../Components/NotificationPremium.vue';

// Chart references
let ticketsCategoryChartInstance = null;
let resolutionTimeChartInstance = null;

// Reactive state
const tickets = ref([]);
const typeMaintenances = ref([]);
const targetAssets = ref({ batiments: [], logements: [] });
const maintenanciers = ref([]);

// Real-time assets in maintenance
const realMaintenanceBuildings = ref([]);
const realMaintenanceLogements = ref([]);

// Loading states
const isSubmitting = ref(false);
const showCreateModal = ref(false);

// Form data
const createForm = ref({
    target_type: 'logement',
    target_id: '',
    type_maintenance_id: '',
    assigned_to: null,
    budget: 0,
    priorite: 'Normale',
    description: '',
});

// Notification State
const notification = ref({ show: false, type: 'success', title: '', message: '' });
let notificationTimeout = null;

const showNotification = (type, title, message) => {
    notification.value = { show: true, type, title, message };
    if (notificationTimeout) clearTimeout(notificationTimeout);
    notificationTimeout = setTimeout(() => {
        notification.value.show = false;
    }, 8050); // display a bit longer for AI explanation readable text
};

const closeNotification = () => {
    notification.value.show = false;
    if (notificationTimeout) clearTimeout(notificationTimeout);
};

// Priority Radio Options
const priorityOptions = [
    { value: 'Basse', label: 'Basse', activeClass: 'border-slate-500 bg-slate-50 text-slate-800' },
    { value: 'Normale', label: 'Normale', activeClass: 'border-blue-500 bg-blue-50 text-blue-800' },
    { value: 'Haute', label: 'Haute', activeClass: 'border-amber-500 bg-amber-50 text-amber-800' },
    { value: 'Critique', label: 'Critique', activeClass: 'border-red-500 bg-red-50 text-red-800' },
    { value: 'IA', label: 'Définir par l\'IA', activeClass: 'border-indigo-500 bg-indigo-50 text-indigo-800 shadow-md ring-2 ring-indigo-500/20 animate-pulse' },
];

// Computed Target Assets
const filteredTargets = computed(() => {
    if (createForm.value.target_type === 'batiment') {
        return targetAssets.value.batiments;
    }
    return targetAssets.value.logements;
});

// KPIs computations
const openTicketsCount = computed(() => tickets.value.filter(t => t.statut !== 'Terminé').length);
const criticalTicketsCount = computed(() => tickets.value.filter(t => t.priorite === 'Critique' && t.statut !== 'Terminé').length);
const criticalTickets = computed(() => tickets.value.filter(t => t.priorite === 'Critique' && t.statut !== 'Terminé'));

const budgetTotal = computed(() => {
    return tickets.value.reduce((acc, t) => acc + parseFloat(t.budget || 0), 0);
});

const budgetUsed = computed(() => {
    return tickets.value
        .filter(t => t.statut === 'Terminé')
        .reduce((acc, t) => acc + parseFloat(t.budget || 0), 0);
});

const avgResolutionTimeMinutes = computed(() => {
    const completed = tickets.value.filter(t => t.statut === 'Terminé' && t.duree_execution_minutes !== null);
    if (completed.length === 0) return 0;
    const sum = completed.reduce((acc, t) => acc + t.duree_execution_minutes, 0);
    return Math.round(sum / completed.length);
});

// Formatters
const formatCurrency = (val) => {
    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR', maximumFractionDigits: 0 }).format(val);
};

const formatDuration = (minutes) => {
    if (!minutes || minutes <= 0) return '0 min';
    if (minutes < 60) return `${minutes} min`;
    const hours = Math.floor(minutes / 60);
    const mins = minutes % 60;
    if (hours < 24) {
        return mins > 0 ? `${hours}h ${mins}m` : `${hours}h`;
    }
    const days = Math.floor(hours / 24);
    const remainingHours = hours % 24;
    return remainingHours > 0 ? `${days}j ${remainingHours}h` : `${days}j`;
};

// Data Loading
const loadAllData = async () => {
    try {
        const [resTickets, resTypes, resTargets, resMaintenanciers] = await Promise.all([
            fetch('/api/maintenances', { headers: { 'Accept': 'application/json' } }),
            fetch('/api/type-maintenances', { headers: { 'Accept': 'application/json' } }),
            fetch('/api/maintenances/targets', { headers: { 'Accept': 'application/json' } }),
            fetch('/api/maintenances/maintenanciers', { headers: { 'Accept': 'application/json' } }),
        ]);

        if (resTickets.ok) {
            tickets.value = await resTickets.json();
        }
        if (resTypes.ok) {
            typeMaintenances.value = await resTypes.json();
        }
        if (resTargets.ok) {
            targetAssets.value = await resTargets.json();
        }
        if (resMaintenanciers.ok) {
            maintenanciers.value = await resMaintenanciers.json();
        }

        // Filter buildings and lodgings that currently have statut_maintenance === 'En cours de maintenance'
        realMaintenanceBuildings.value = targetAssets.value.batiments.filter(b => b.statut_maintenance === 'En cours de maintenance');
        realMaintenanceLogements.value = targetAssets.value.logements.filter(l => l.statut_maintenance === 'En cours de maintenance');

        // Re-render charts
        renderCharts();
    } catch (error) {
        console.error("Error loading data:", error);
    }
};

// Create Maintenance Modal handlers
const openCreateMaintenanceModal = () => {
    createForm.value = {
        target_type: 'logement',
        target_id: '',
        type_maintenance_id: '',
        assigned_to: null,
        budget: 0,
        priorite: 'Normale',
        description: '',
    };
    showCreateModal.value = true;
};

// Submit creation
const submitCreateForm = async () => {
    isSubmitting.value = true;
    try {
        const response = await fetch('/api/maintenances', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content,
            },
            body: JSON.stringify(createForm.value),
        });

        if (response.ok) {
            const data = await response.json();
            showCreateModal.value = false;
            
            let message = 'La tâche de maintenance a été créée avec succès.';
            if (data.ai_explanation) {
                message += ` [Détection IA : Priorité ${data.priorite} - ${data.ai_explanation}]`;
            }
            showNotification('success', 'Tâche Créée', message);
            await loadAllData();
        } else {
            const data = await response.json();
            showNotification('error', 'Erreur de création', data.message || 'Une erreur est survenue.');
        }
    } catch (error) {
        console.error(error);
        showNotification('error', 'Erreur', 'Impossible de contacter le serveur.');
    } finally {
        isSubmitting.value = false;
    }
};

// Start Intervention
const startExecution = async (ticket) => {
    try {
        const response = await fetch(`/api/maintenances/${ticket.id}/start`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content,
            }
        });
        if (response.ok) {
            showNotification('success', 'Intervention Débutée', 'Le statut est passé à En cours d\'exécution et le chronomètre a démarré.');
            await loadAllData();
        } else {
            const data = await response.json();
            showNotification('error', 'Erreur', data.error || 'Une erreur est survenue.');
        }
    } catch (error) {
        console.error(error);
        showNotification('error', 'Erreur', 'Impossible de débuter l\'exécution.');
    }
};

// End Intervention
const endExecution = async (ticket) => {
    try {
        const response = await fetch(`/api/maintenances/${ticket.id}/end`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content,
            }
        });
        if (response.ok) {
            const data = await response.json();
            const formattedTime = formatDuration(data.duree_execution_minutes);
            showNotification('success', 'Intervention Terminée', `La tâche est résolue. Durée totale de réalisation : ${formattedTime}.`);
            await loadAllData();
        } else {
            const data = await response.json();
            showNotification('error', 'Erreur', data.error || 'Une erreur est survenue.');
        }
    } catch (error) {
        console.error(error);
        showNotification('error', 'Erreur', 'Impossible de terminer la maintenance.');
    }
};

// Delete Ticket
const deleteTicket = async (ticket) => {
    if (!confirm('Êtes-vous sûr de vouloir supprimer définitivement ce ticket de maintenance ?')) return;
    try {
        const response = await fetch(`/api/maintenances/${ticket.id}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content,
            }
        });
        if (response.ok) {
            showNotification('success', 'Ticket Supprimé', 'Le ticket a été supprimé.');
            await loadAllData();
        } else {
            const data = await response.json();
            showNotification('error', 'Erreur', data.message || 'Une erreur est survenue.');
        }
    } catch (error) {
        console.error(error);
        showNotification('error', 'Erreur', 'Impossible de supprimer le ticket.');
    }
};

// Chart rendering
const renderCharts = () => {
    if (ticketsCategoryChartInstance) ticketsCategoryChartInstance.destroy();
    if (resolutionTimeChartInstance) resolutionTimeChartInstance.destroy();

    // 1. Group tickets by category
    const categoryCounts = {};
    tickets.value.forEach(t => {
        const catName = t.type_maintenance?.nom || 'Inconnu';
        categoryCounts[catName] = (categoryCounts[catName] || 0) + 1;
    });

    const labels = Object.keys(categoryCounts);
    const counts = Object.values(categoryCounts);

    if (labels.length === 0) {
        labels.push('Aucune donnée');
        counts.push(0);
    }

    const categoryCtx = document.getElementById('ticketsCategoryChart');
    if (categoryCtx) {
        ticketsCategoryChartInstance = new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels,
                datasets: [{
                    data: counts,
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.9)',
                        'rgba(245, 158, 11, 0.9)',
                        'rgba(16, 185, 129, 0.9)',
                        'rgba(139, 92, 246, 0.9)',
                        'rgba(100, 116, 139, 0.9)'
                    ],
                    borderColor: '#ffffff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            usePointStyle: true,
                            color: '#64748b',
                            font: { size: 11 }
                        }
                    }
                },
                cutout: '65%'
            }
        });
    }

    // 2. Resolution Time Chart (historical trend)
    // We can group completed tickets by month or just list last 5 tickets
    const completedTickets = [...tickets.value]
        .filter(t => t.statut === 'Terminé' && t.duree_execution_minutes !== null)
        .reverse()
        .slice(-6); // last 6 completed

    const trendLabels = completedTickets.map((t, idx) => `Tâche ${t.id}`);
    const trendValues = completedTickets.map(t => Math.round(t.duree_execution_minutes / 60 * 10) / 10); // in hours

    if (trendLabels.length === 0) {
        trendLabels.push('Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin');
        trendValues.push(3.2, 3.0, 2.8, 2.6, 2.5, 2.4);
    }

    const resolutionCtx = document.getElementById('resolutionTimeChart');
    if (resolutionCtx) {
        const gradient = resolutionCtx.getContext('2d').createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(59, 130, 246, 0.4)');
        gradient.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

        resolutionTimeChartInstance = new Chart(resolutionCtx, {
            type: 'line',
            data: {
                labels: trendLabels,
                datasets: [{
                    label: 'Temps de résolution (heures)',
                    data: trendValues,
                    borderColor: '#3b82f6',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        ticks: { color: '#64748b' }
                    },
                    x: { ticks: { color: '#64748b' } }
                }
            }
        });
    }
};

onMounted(async () => {
    await loadAllData();
});

onUnmounted(() => {
    if (ticketsCategoryChartInstance) ticketsCategoryChartInstance.destroy();
    if (resolutionTimeChartInstance) resolutionTimeChartInstance.destroy();
});
</script>
