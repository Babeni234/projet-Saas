<template>
    <div class="flex h-screen bg-gradient-to-br from-slate-50 to-slate-100 font-sans antialiased">
        <!-- Sidebar Navigation -->
        <aside 
            class="fixed left-0 top-0 h-full bg-gradient-to-b from-white via-slate-50 to-slate-100 text-slate-800 transition-all duration-300 ease-in-out z-50 shadow-xl shadow-slate-200/50 overflow-y-auto scrollbar-hide border-r border-slate-200/50"
            :class="sidebarCollapsed ? 'w-16' : 'w-64'"
            style="scrollbar-width: none; -ms-overflow-style: none;"
        >
            <div class="p-6 flex items-center justify-between border-b border-slate-200/50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 10a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <span v-if="!sidebarCollapsed" class="text-xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Enterprise</span>
                </div>
                <button 
                    @click="toggleSidebar" 
                    class="p-2 rounded-lg hover:bg-slate-100 transition-all duration-200 group"
                >
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-slate-500 group-hover:text-slate-800 transition-colors">
                        <path d="M3 10h14M3 6h14M3 14h10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>

            <nav class="flex-1 py-6 px-4 overflow-y-auto">
                <div class="mb-6">
                    <div v-if="!sidebarCollapsed" class="px-4 mb-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Vue Globale</div>
                    <a 
                        href="#" 
                        class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden"
                        :class="currentView === 'master' ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800'"
                        @click.prevent="currentView = 'master'"
                    >
                        <div class="relative z-10">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 10a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <span v-if="!sidebarCollapsed" class="relative z-10 font-medium">Master Dashboard</span>
                    </a>
                </div>

                <div class="mb-6">
                    <div v-if="!sidebarCollapsed" class="px-4 mb-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Gestion</div>
                    <div class="space-y-1">
                        <a 
                            href="#" 
                            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden"
                            :class="currentView === 'immobilier' || immobilierMenuOpen ? 'bg-gradient-to-r from-emerald-500 to-emerald-600 text-white shadow-lg shadow-emerald-500/30' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800'"
                            @click.prevent="toggleImmobilierMenu"
                        >
                            <div class="relative z-10">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <span v-if="!sidebarCollapsed" class="relative z-10 font-medium flex-1">Immobilier</span>
                            <span v-if="!sidebarCollapsed && alerts.immobilier > 0" class="relative z-10 bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full shadow-lg shadow-red-500/30">{{ alerts.immobilier }}</span>
                            <svg v-if="!sidebarCollapsed" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-slate-400 transition-transform duration-200" :class="immobilierMenuOpen ? 'rotate-180 text-slate-800' : ''">
                                <path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                        
                        <div v-if="immobilierMenuOpen && !sidebarCollapsed" class="ml-6 space-y-1 mt-1">
                            <a href="#" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-800 transition-all duration-200">
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Gestion des agences
                            </a>
                            <a href="#" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-800 transition-all duration-200">
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 12H2V2h10v10zM4 4h6v6H4V4z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Gestion des bâtiments
                            </a>
                            <a href="#" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-800 transition-all duration-200">
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3 4a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM10 10h2a1 1 0 001-1V5a1 1 0 00-1-1h-2v6z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Gestion des logements
                            </a>
                            <a href="#" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-800 transition-all duration-200">
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 12H5V7h4v5zM3 12V7a1 1 0 011-1h6a1 1 0 011 1v5M3 7V4a1 1 0 011-1h6a1 1 0 011 1v3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Gestion des contrats de bail
                            </a>
                            <a href="#" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-800 transition-all duration-200">
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7 7a3 3 0 100-6 3 3 0 000 6zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Gestion des locataires
                            </a>
                            <a href="#" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-800 transition-all duration-200">
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 1H2a1 1 0 00-1 1v10a1 1 0 001 1h10a1 1 0 001-1V2a1 1 0 00-1-1zM5 5h4M5 8h4M5 11h2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Paiement des loyers
                            </a>
                            <a href="#" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-800 transition-all duration-200">
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 14H5V9h4v5zM3 14V9a1 1 0 011-1h6a1 1 0 011 1v5M3 9V6a1 1 0 011-1h6a1 1 0 011 1v3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Factures et détails
                            </a>
                            <a href="#" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-800 transition-all duration-200">
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 1H5a1 1 0 00-1 1v10a1 1 0 001 1h4a1 1 0 001-1V2a1 1 0 00-1-1zM5 4h4M5 7h4M5 10h2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                États des paiements
                            </a>
                            <a href="#" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-800 transition-all duration-200">
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M11 2H3a1 1 0 00-1 1v8a1 1 0 001 1h8a1 1 0 001-1V3a1 1 0 00-1-1zM5 5h4M5 8h2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Enquêtes et suivi
                            </a>
                            <a href="#" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-800 transition-all duration-200">
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8 3v5l3 3m3-8a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Sync hors ligne ↔ en ligne
                            </a>
                        </div>
                    </div>
                    
                    <div class="space-y-1 mt-2">
                        <a 
                            href="#" 
                            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden"
                            :class="currentView === 'hotel' || hotelMenuOpen ? 'bg-gradient-to-r from-amber-500 to-amber-600 text-white shadow-lg shadow-amber-500/30' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800'"
                            @click.prevent="toggleHotelMenu"
                        >
                            <div class="relative z-10">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <span v-if="!sidebarCollapsed" class="relative z-10 font-medium flex-1">Hôtellerie</span>
                            <span v-if="!sidebarCollapsed && alerts.hotel > 0" class="relative z-10 bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full shadow-lg shadow-red-500/30">{{ alerts.hotel }}</span>
                            <svg v-if="!sidebarCollapsed" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-slate-400 transition-transform duration-200" :class="hotelMenuOpen ? 'rotate-180 text-slate-800' : ''">
                                <path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                        
                        <div v-if="hotelMenuOpen && !sidebarCollapsed" class="ml-6 space-y-1 mt-1">
                            <a href="#" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-800 transition-all duration-200">
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 12H2V2h10v10zM4 4h6v6H4V4z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Gestion des hôtels
                            </a>
                            <a href="#" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-800 transition-all duration-200">
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8 2H6a1 1 0 00-1 1v8a1 1 0 001 1h2a1 1 0 001-1V3a1 1 0 00-1-1zM3 6h8M3 8h8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Réservations de chambres
                            </a>
                            <a href="#" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-800 transition-all duration-200">
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5 3v8M9 3v8M3 7h8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Check-in / Check-out
                            </a>
                            <a href="#" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-800 transition-all duration-200">
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7 7a3 3 0 100-6 3 3 0 000 6zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Gestion des clients
                            </a>
                            <a href="#" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-800 transition-all duration-200">
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3 4a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM10 10h2a1 1 0 001-1V5a1 1 0 00-1-1h-2v6z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Gestion des chambres
                            </a>
                            <a href="#" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-800 transition-all duration-200">
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 1H5a1 1 0 00-1 1v10a1 1 0 001 1h4a1 1 0 001-1V2a1 1 0 00-1-1zM5 4h4M5 7h4M5 10h2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Types de chambres
                            </a>
                            <a href="#" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-800 transition-all duration-200">
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 7H2m5-5v10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Tarification dynamique
                            </a>
                            <a href="#" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-800 transition-all duration-200">
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 1H5a1 1 0 00-1 1v10a1 1 0 001 1h4a1 1 0 001-1V2a1 1 0 00-1-1zM5 4h4M5 7h4M5 10h2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Facturation
                            </a>
                            <a href="#" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-800 transition-all duration-200">
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 1H2a1 1 0 00-1 1v10a1 1 0 001 1h10a1 1 0 001-1V2a1 1 0 00-1-1zM5 5h4M5 8h2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Gestion des services
                            </a>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <div v-if="!sidebarCollapsed" class="px-4 mb-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Modules</div>
                    <a 
                        href="#" 
                        class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden mb-2"
                        :class="currentView === 'comptabilite' ? 'bg-gradient-to-r from-purple-500 to-purple-600 text-white shadow-lg shadow-purple-500/30' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800'"
                        @click.prevent="currentView = 'comptabilite'"
                    >
                        <div class="relative z-10">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <span v-if="!sidebarCollapsed" class="relative z-10 font-medium">Comptabilité</span>
                    </a>
                    <a 
                        href="#" 
                        class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden mb-2"
                        :class="currentView === 'maintenance' ? 'bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-lg shadow-orange-500/30' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800'"
                        @click.prevent="currentView = 'maintenance'"
                    >
                        <div class="relative z-10">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <span v-if="!sidebarCollapsed" class="relative z-10 font-medium">Maintenance</span>
                        <span v-if="!sidebarCollapsed && alerts.maintenance > 0" class="relative z-10 ml-auto bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full shadow-lg shadow-red-500/30">{{ alerts.maintenance }}</span>
                    </a>
                    <a 
                        href="#" 
                        class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden"
                        :class="currentView === 'rapports' ? 'bg-gradient-to-r from-cyan-500 to-cyan-600 text-white shadow-lg shadow-cyan-500/30' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800'"
                        @click.prevent="currentView = 'rapports'"
                    >
                        <div class="relative z-10">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <span v-if="!sidebarCollapsed" class="relative z-10 font-medium">Rapports</span>
                    </a>
                    <a 
                        href="#" 
                        class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden"
                        :class="currentView === 'maps' ? 'bg-gradient-to-r from-indigo-500 to-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800'"
                        @click.prevent="currentView = 'maps'"
                    >
                        <div class="relative z-10">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <span v-if="!sidebarCollapsed" class="relative z-10 font-medium">Localisation Maps</span>
                    </a>
                </div>
            </nav>

            <div v-if="!sidebarCollapsed" class="p-6 border-t border-slate-200/50">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold shadow-lg shadow-blue-500/30">AD</div>
                    <div class="flex-1">
                        <div class="font-semibold text-slate-800">Admin</div>
                        <div class="text-xs text-slate-500">Administrateur</div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col overflow-hidden ml-64 transition-all duration-300" :class="sidebarCollapsed ? 'ml-16' : 'ml-64'">
            <!-- Header -->
            <header class="bg-white/80 backdrop-blur-xl border-b border-slate-200/50 px-8 py-5 flex items-center justify-between sticky top-0 z-40">
                <div class="flex items-center gap-4">
                    <h1 class="text-2xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">{{ pageTitle }}</h1>
                </div>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2 px-4 py-2 bg-slate-100 rounded-xl">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-slate-500">
                            <path d="M8 0a8 8 0 100 16A8 8 0 008 0zm1 12H7V8h2v4zm0-6H7V4h2v2z" fill="currentColor"/>
                        </svg>
                        <span class="text-sm font-medium text-slate-600">{{ currentDateRange }}</span>
                    </div>
                    <button 
                        @click="refreshData" 
                        class="p-2 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:border-slate-300 transition-all duration-200 shadow-sm"
                    >
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-slate-600">
                            <path d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" fill="currentColor"/>
                        </svg>
                    </button>
                </div>
            </header>

            <!-- Dynamic Content -->
            <div class="flex-1 overflow-y-auto p-8">
                <MasterDashboard v-if="currentView === 'master'" />
                <ImmobilierDashboard v-if="currentView === 'immobilier'" />
                <HotelDashboard v-if="currentView === 'hotel'" />
                <AccountingDashboard v-if="currentView === 'comptabilite'" />
                <MaintenanceDashboard v-if="currentView === 'maintenance'" />
                <ReportsDashboard v-if="currentView === 'rapports'" />
                <MapsDashboard v-if="currentView === 'maps'" />
            </div>
        </main>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import MasterDashboard from './components/MasterDashboard.vue';
import ImmobilierDashboard from './components/ImmobilierDashboard.vue';
import HotelDashboard from './components/HotelDashboard.vue';
import AccountingDashboard from './components/AccountingDashboard.vue';
import MaintenanceDashboard from './components/MaintenanceDashboard.vue';
import ReportsDashboard from './components/ReportsDashboard.vue';
import MapsDashboard from './components/MapsDashboard.vue';

const currentView = ref('master');
const sidebarCollapsed = ref(false);
const immobilierMenuOpen = ref(false);
const hotelMenuOpen = ref(false);

const alerts = ref({
    immobilier: 3,
    hotel: 5,
    maintenance: 2
});

const pageTitle = computed(() => {
    const titles = {
        master: 'Master Dashboard',
        immobilier: 'Gestion Immobilière',
        hotel: 'Gestion Hôtelière',
        comptabilite: 'Comptabilité & Facturation',
        maintenance: 'Maintenance / SAV',
        rapports: 'Rapports & Statistiques',
        maps: 'Localisation Maps'
    };
    return titles[currentView.value] || 'Dashboard';
});

const currentDateRange = computed(() => {
    const now = new Date();
    const firstDay = new Date(now.getFullYear(), now.getMonth(), 1);
    const lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0);
    
    const options = { month: 'short', year: 'numeric' };
    return `${firstDay.toLocaleDateString('fr-FR', options)} - ${lastDay.toLocaleDateString('fr-FR', options)}`;
});

const toggleSidebar = () => {
    sidebarCollapsed.value = !sidebarCollapsed.value;
};

const toggleImmobilierMenu = () => {
    immobilierMenuOpen.value = !immobilierMenuOpen.value;
    currentView.value = 'immobilier';
};

const toggleHotelMenu = () => {
    hotelMenuOpen.value = !hotelMenuOpen.value;
    currentView.value = 'hotel';
};

const refreshData = () => {
    console.log('Rafraîchissement des données...');
};
</script>
