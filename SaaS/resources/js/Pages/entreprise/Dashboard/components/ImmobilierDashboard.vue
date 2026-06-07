<template>
    <div class="flex flex-col gap-8">
        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Vacancy Card -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 hover:shadow-xl hover:shadow-slate-300/50 transition-all duration-300 hover:-translate-y-1 border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg shadow-red-500/30">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                            <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-1 px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 9L3 6m0 0l3-3m-3 3h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        +2.1%
                    </div>
                </div>
                <div class="text-3xl font-bold text-slate-800 mb-1">7.7%</div>
                <div class="text-sm text-slate-500 mb-4">Taux de Vacance Locative</div>
                <div class="text-xs text-slate-400">12 logements / 156 total</div>
            </div>

            <!-- Revenue Card -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 hover:shadow-xl hover:shadow-slate-300/50 transition-all duration-300 hover:-translate-y-1 border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                            <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-1 px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 9L3 6m0 0l3-3m-3 3h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        +5.8%
                    </div>
                </div>
                <div class="text-3xl font-bold text-slate-800 mb-1">€156,800</div>
                <div class="text-sm text-slate-500 mb-4">Revenus Locatifs Mensuels</div>
                <div class="text-xs text-slate-400">Attendu: €164,500</div>
            </div>

            <!-- Unpaid Card -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 hover:shadow-xl hover:shadow-slate-300/50 transition-all duration-300 hover:-translate-y-1 border border-slate-100 border-l-4 border-l-amber-500">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/30">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                            <path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-1 px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 6H3m0 0l4-4m-4 4l4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        +1
                    </div>
                </div>
                <div class="text-3xl font-bold text-slate-800 mb-1">4.2%</div>
                <div class="text-sm text-slate-500 mb-4">Taux d'Impayés</div>
                <div class="text-xs text-slate-400">€6,920 en retard</div>
            </div>

            <!-- Charges Card -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 hover:shadow-xl hover:shadow-slate-300/50 transition-all duration-300 hover:-translate-y-1 border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/30">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                            <path d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-1 px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 9L3 6m0 0l3-3m-3 3h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        -3.2%
                    </div>
                </div>
                <div class="text-3xl font-bold text-slate-800 mb-1">98.5%</div>
                <div class="text-sm text-slate-500 mb-4">Récupération des Charges</div>
                <div class="text-xs text-slate-400">€24,500 / €24,900</div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Unpaid Chart -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-slate-800">Impayés par Période</h3>
                </div>
                <div class="h-64">
                    <canvas id="unpaidChart"></canvas>
                </div>
            </div>

            <!-- Charges Comparison -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-slate-800">Comparatif Charges</h3>
                </div>
                <div class="h-64">
                    <canvas id="chargesChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Lease Management -->
        <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-slate-800">Gestion des Baux</h3>
                <button class="px-4 py-2 bg-blue-500 text-white rounded-lg text-sm font-medium hover:bg-blue-600 transition-colors shadow-md shadow-blue-500/20">Voir tous les baux</button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-50">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Locataire</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Propriété</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Loyer Mensuel</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Fin de Bail</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Statut</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr class="bg-red-50">
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold text-sm">JD</div>
                                    <div>
                                        <div class="font-semibold text-slate-800">Jean Dupont</div>
                                        <div class="text-xs text-slate-500">jean.dupont@email.com</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-sm text-slate-600">Immeuble A - Apt 204</td>
                            <td class="px-4 py-4 text-sm text-slate-600">€1,200</td>
                            <td class="px-4 py-4 text-sm font-semibold text-red-600">15 Juin 2026</td>
                            <td class="px-4 py-4"><span class="px-3 py-1 bg-red-500 text-white rounded-full text-xs font-semibold">À renouveler</span></td>
                            <td class="px-4 py-4">
                                <button class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">Contacter</button>
                            </td>
                        </tr>
                        <tr class="bg-red-50">
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold text-sm">ML</div>
                                    <div>
                                        <div class="font-semibold text-slate-800">Marie Lambert</div>
                                        <div class="text-xs text-slate-500">m.lambert@email.com</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-sm text-slate-600">Immeuble B - Bureau 102</td>
                            <td class="px-4 py-4 text-sm text-slate-600">€2,500</td>
                            <td class="px-4 py-4 text-sm font-semibold text-red-600">22 Juin 2026</td>
                            <td class="px-4 py-4"><span class="px-3 py-1 bg-red-500 text-white rounded-full text-xs font-semibold">À renouveler</span></td>
                            <td class="px-4 py-4">
                                <button class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">Contacter</button>
                            </td>
                        </tr>
                        <tr class="bg-amber-50">
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold text-sm">PM</div>
                                    <div>
                                        <div class="font-semibold text-slate-800">Pierre Martin</div>
                                        <div class="text-xs text-slate-500">p.martin@email.com</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-sm text-slate-600">Immeuble C - Apt 305</td>
                            <td class="px-4 py-4 text-sm text-slate-600">€950</td>
                            <td class="px-4 py-4 text-sm font-semibold text-amber-600">28 Juin 2026</td>
                            <td class="px-4 py-4"><span class="px-3 py-1 bg-amber-500 text-white rounded-full text-xs font-semibold">Bientôt</span></td>
                            <td class="px-4 py-4">
                                <button class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">Contacter</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold text-sm">SB</div>
                                    <div>
                                        <div class="font-semibold text-slate-800">Sophie Bernard</div>
                                        <div class="text-xs text-slate-500">s.bernard@email.com</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-sm text-slate-600">Immeuble A - Apt 108</td>
                            <td class="px-4 py-4 text-sm text-slate-600">€1,100</td>
                            <td class="px-4 py-4 text-sm text-slate-600">15 Déc 2026</td>
                            <td class="px-4 py-4"><span class="px-3 py-1 bg-emerald-500 text-white rounded-full text-xs font-semibold">Actif</span></td>
                            <td class="px-4 py-4">
                                <button class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">Voir</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold text-sm">AR</div>
                                    <div>
                                        <div class="font-semibold text-slate-800">Antoine Roux</div>
                                        <div class="text-xs text-slate-500">a.roux@email.com</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-sm text-slate-600">Immeuble D - Bureau 301</td>
                            <td class="px-4 py-4 text-sm text-slate-600">€3,200</td>
                            <td class="px-4 py-4 text-sm text-slate-600">28 Fév 2027</td>
                            <td class="px-4 py-4"><span class="px-3 py-1 bg-emerald-500 text-white rounded-full text-xs font-semibold">Actif</span></td>
                            <td class="px-4 py-4">
                                <button class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">Voir</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Rent Indexation -->
        <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-slate-800">Indexation des Loyers</h3>
                <button class="px-4 py-2 bg-blue-500 text-white rounded-lg text-sm font-medium hover:bg-blue-600 transition-colors shadow-md shadow-blue-500/20">Gérer les indexations</button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="flex flex-col gap-4 p-5 bg-slate-50 rounded-xl border border-slate-200 border-l-4 border-l-amber-500">
                    <div class="flex items-center justify-between">
                        <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-sm">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-amber-500">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" fill="currentColor"/>
                            </svg>
                        </div>
                        <div class="text-xs font-semibold text-slate-600">À appliquer</div>
                    </div>
                    <div>
                        <div class="font-semibold text-slate-800">Immeuble A - 12 baux</div>
                        <div class="flex flex-col gap-1 mt-2">
                            <span class="text-xs text-slate-600">Index IRL: +3.5%</span>
                            <span class="text-xs text-slate-600">Date limite: 30 Juin 2026</span>
                        </div>
                    </div>
                    <button class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-medium text-slate-600 hover:bg-slate-100 transition-colors">Appliquer</button>
                </div>

                <div class="flex flex-col gap-4 p-5 bg-slate-50 rounded-xl border border-slate-200 border-l-4 border-l-amber-500">
                    <div class="flex items-center justify-between">
                        <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-sm">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-amber-500">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" fill="currentColor"/>
                            </svg>
                        </div>
                        <div class="text-xs font-semibold text-slate-600">À appliquer</div>
                    </div>
                    <div>
                        <div class="font-semibold text-slate-800">Immeuble B - 8 baux</div>
                        <div class="flex flex-col gap-1 mt-2">
                            <span class="text-xs text-slate-600">Index IRL: +3.5%</span>
                            <span class="text-xs text-slate-600">Date limite: 15 Juil 2026</span>
                        </div>
                    </div>
                    <button class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-medium text-slate-600 hover:bg-slate-100 transition-colors">Appliquer</button>
                </div>

                <div class="flex flex-col gap-4 p-5 bg-slate-50 rounded-xl border border-slate-200 border-l-4 border-l-emerald-500">
                    <div class="flex items-center justify-between">
                        <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-sm">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-emerald-500">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" fill="currentColor"/>
                            </svg>
                        </div>
                        <div class="text-xs font-semibold text-slate-600">Appliqué</div>
                    </div>
                    <div>
                        <div class="font-semibold text-slate-800">Immeuble C - 15 baux</div>
                        <div class="flex flex-col gap-1 mt-2">
                            <span class="text-xs text-slate-600">Index IRL: +3.2%</span>
                            <span class="text-xs text-slate-600">Appliqué le 15 Mai 2026</span>
                        </div>
                    </div>
                    <button class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-medium text-slate-600 hover:bg-slate-100 transition-colors">Voir détails</button>
                </div>

                <div class="flex flex-col gap-4 p-5 bg-slate-50 rounded-xl border border-slate-200 border-l-4 border-l-emerald-500">
                    <div class="flex items-center justify-between">
                        <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-sm">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-emerald-500">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" fill="currentColor"/>
                            </svg>
                        </div>
                        <div class="text-xs font-semibold text-slate-600">Appliqué</div>
                    </div>
                    <div>
                        <div class="font-semibold text-slate-800">Immeuble D - 10 baux</div>
                        <div class="flex flex-col gap-1 mt-2">
                            <span class="text-xs text-slate-600">Index IRL: +3.2%</span>
                            <span class="text-xs text-slate-600">Appliqué le 10 Mai 2026</span>
                        </div>
                    </div>
                    <button class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-medium text-slate-600 hover:bg-slate-100 transition-colors">Voir détails</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Chart } from 'chart.js/auto';

const unpaidChart = ref(null);
const chargesChart = ref(null);

onMounted(() => {
    // Unpaid Chart - Bar Chart with Gradient
    const unpaidCtx = document.getElementById('unpaidChart');
    if (unpaidCtx) {
        const gradientGreen = unpaidCtx.getContext('2d').createLinearGradient(0, 0, 0, 400);
        gradientGreen.addColorStop(0, 'rgba(16, 185, 129, 0.8)');
        gradientGreen.addColorStop(1, 'rgba(16, 185, 129, 0.3)');

        const gradientAmber = unpaidCtx.getContext('2d').createLinearGradient(0, 0, 0, 400);
        gradientAmber.addColorStop(0, 'rgba(245, 158, 11, 0.8)');
        gradientAmber.addColorStop(1, 'rgba(245, 158, 11, 0.3)');

        const gradientRed = unpaidCtx.getContext('2d').createLinearGradient(0, 0, 0, 400);
        gradientRed.addColorStop(0, 'rgba(239, 68, 68, 0.8)');
        gradientRed.addColorStop(1, 'rgba(239, 68, 68, 0.3)');

        unpaidChart.value = new Chart(unpaidCtx, {
            type: 'bar',
            data: {
                labels: ['30j', '60j', '90j', '120j', '150j+'],
                datasets: [{
                    label: 'Montant Impayé',
                    data: [1200, 1500, 1800, 2100, 2700],
                    backgroundColor: [
                        gradientGreen,
                        gradientGreen,
                        gradientAmber,
                        gradientAmber,
                        gradientRed
                    ],
                    borderColor: [
                        '#10b981',
                        '#10b981',
                        '#f59e0b',
                        '#f59e0b',
                        '#ef4444'
                    ],
                    borderWidth: 2,
                    borderRadius: 8,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: 'rgba(148, 163, 184, 0.2)',
                        borderWidth: 1,
                        cornerRadius: 12,
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                return '€' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(148, 163, 184, 0.1)',
                            drawBorder: false
                        },
                        ticks: {
                            callback: function(value) {
                                return '€' + (value / 1000) + 'k';
                            },
                            color: '#64748b',
                            font: {
                                size: 11
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#64748b',
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            }
        });
    }

    // Charges Chart - Grouped Bar Chart
    const chargesCtx = document.getElementById('chargesChart');
    if (chargesCtx) {
        const gradientRecoverable = chargesCtx.getContext('2d').createLinearGradient(0, 0, 0, 400);
        gradientRecoverable.addColorStop(0, 'rgba(59, 130, 246, 0.8)');
        gradientRecoverable.addColorStop(1, 'rgba(59, 130, 246, 0.3)');

        const gradientActual = chargesCtx.getContext('2d').createLinearGradient(0, 0, 0, 400);
        gradientActual.addColorStop(0, 'rgba(16, 185, 129, 0.8)');
        gradientActual.addColorStop(1, 'rgba(16, 185, 129, 0.3)');

        chargesChart.value = new Chart(chargesCtx, {
            type: 'bar',
            data: {
                labels: ['Immeuble A', 'Immeuble B', 'Immeuble C', 'Immeuble D'],
                datasets: [
                    {
                        label: 'Récupérables',
                        data: [6500, 6800, 6200, 7000],
                        backgroundColor: gradientRecoverable,
                        borderColor: '#3b82f6',
                        borderWidth: 2,
                        borderRadius: 6
                    },
                    {
                        label: 'Réelles',
                        data: [6000, 6500, 5800, 6500],
                        backgroundColor: gradientActual,
                        borderColor: '#10b981',
                        borderWidth: 2,
                        borderRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            color: '#64748b',
                            font: {
                                size: 12,
                                weight: 500
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: 'rgba(148, 163, 184, 0.2)',
                        borderWidth: 1,
                        cornerRadius: 12,
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': €' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(148, 163, 184, 0.1)',
                            drawBorder: false
                        },
                        ticks: {
                            callback: function(value) {
                                return '€' + (value / 1000) + 'k';
                            },
                            color: '#64748b',
                            font: {
                                size: 11
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#64748b',
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            }
        });
    }
});

onUnmounted(() => {
    if (unpaidChart.value) unpaidChart.value.destroy();
    if (chargesChart.value) chargesChart.value.destroy();
});
</script>
