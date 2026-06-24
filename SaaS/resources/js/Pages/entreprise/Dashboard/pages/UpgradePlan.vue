<template>
    <div class="space-y-8 max-w-5xl mx-auto pb-12 text-left">
        <!-- Success Toast -->
        <Transition name="fade">
            <div v-if="successToast" class="fixed bottom-6 right-6 z-50 flex items-center gap-3 rounded-2xl bg-emerald-600 px-5 py-4 text-white shadow-xl shadow-emerald-600/20 border border-emerald-500/30">
                <svg class="h-6 w-6 shrink-0 animate-bounce" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-sm font-bold">{{ successToastMessage }}</div>
            </div>
        </Transition>

        <!-- Header Hero Card -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 p-8 md:p-10 text-white shadow-2xl border border-slate-800">
            <!-- Animated background elements -->
            <div class="absolute -right-20 -top-20 h-72 w-72 rounded-full bg-gradient-to-br from-indigo-500/20 to-purple-600/20 blur-3xl animate-pulse-slow"></div>
            <div class="absolute -left-20 -bottom-20 h-72 w-72 rounded-full bg-gradient-to-tr from-violet-500/10 to-indigo-600/10 blur-3xl animate-pulse-slow"></div>

            <div class="relative z-10 space-y-6">
                <div class="flex flex-wrap items-center justify-between gap-6">
                    <div class="space-y-3">
                        <span class="inline-flex items-center rounded-full bg-indigo-500/10 px-3 py-1 text-xs font-bold text-indigo-300 ring-1 ring-inset ring-indigo-500/20 uppercase tracking-widest">
                            Gestion d'Abonnement
                        </span>
                        <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight bg-gradient-to-r from-white via-slate-100 to-indigo-200 bg-clip-text text-transparent">
                            Faites évoluer votre entreprise
                        </h1>
                        <p class="text-sm text-slate-350 max-w-xl">
                            Passez à un forfait supérieur pour débloquer de nouvelles fonctionnalités d'IA, des collaborateurs illimités et gérer plus de biens.
                        </p>
                    </div>

                    <!-- Current Plan Badge Widget -->
                    <div class="bg-white/5 backdrop-blur-md rounded-2xl p-5 border border-white/10 shadow-lg shrink-0 min-w-[220px] transition-all hover:bg-white/10">
                        <p class="text-[10px] text-indigo-300 font-bold uppercase tracking-wider mb-1">Votre forfait actuel</p>
                        <h2 class="text-xl font-black text-white mb-2">
                            {{ currentPlanName }}
                        </h2>
                        <div class="flex items-baseline gap-1 text-amber-400 font-extrabold">
                            <span class="text-2xl">{{ formatPrice(currentPlanPrice) }}</span>
                            <span class="text-[10px] text-slate-400 font-bold">FCFA / mois</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Plan Limits Analysis -->
        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-slate-200/60 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-50/50 rounded-full blur-2xl -z-10"></div>
            
            <h2 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                </svg>
                Capacité & Limites Actuelles
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Logements Limit -->
                <div class="bg-slate-50/60 rounded-2xl p-5 border border-slate-100 flex flex-col justify-between transition-all hover:shadow-md">
                    <div>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Biens immobiliers / Logements</p>
                        <h3 class="text-2xl font-black text-slate-800">
                            {{ currentPlanLimitText(currentPlan?.max_logements) }}
                        </h3>
                    </div>
                    <div class="mt-4 w-full bg-slate-200 h-2 rounded-full overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 h-full rounded-full shimmer-effect" :style="{ width: currentPlan?.max_logements === -1 ? '100%' : '50%' }"></div>
                    </div>
                </div>

                <!-- Locataires Limit -->
                <div class="bg-slate-50/60 rounded-2xl p-5 border border-slate-100 flex flex-col justify-between transition-all hover:shadow-md">
                    <div>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Locataires gérés</p>
                        <h3 class="text-2xl font-black text-slate-800">
                            {{ currentPlanLimitText(currentPlan?.max_locataires) }}
                        </h3>
                    </div>
                    <div class="mt-4 w-full bg-slate-200 h-2 rounded-full overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 h-full rounded-full shimmer-effect" :style="{ width: currentPlan?.max_locataires === -1 ? '100%' : '50%' }"></div>
                    </div>
                </div>

                <!-- Collaborateurs Limit -->
                <div class="bg-slate-50/60 rounded-2xl p-5 border border-slate-100 flex flex-col justify-between transition-all hover:shadow-md">
                    <div>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Collaborateurs autorisés</p>
                        <h3 class="text-2xl font-black text-slate-800">
                            {{ currentPlanLimitText(currentPlan?.max_employees) }}
                        </h3>
                    </div>
                    <div class="mt-4 w-full bg-slate-200 h-2 rounded-full overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 h-full rounded-full shimmer-effect" :style="{ width: currentPlan?.max_employees === -1 ? '100%' : '50%' }"></div>
                    </div>
                </div>

                <!-- Agences Limit -->
                <div class="bg-slate-50/60 rounded-2xl p-5 border border-slate-100 flex flex-col justify-between transition-all hover:shadow-md">
                    <div>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Nombre d'Agences</p>
                        <h3 class="text-2xl font-black text-slate-800">
                            {{ currentPlanLimitText(currentPlan?.max_agencies) }}
                        </h3>
                    </div>
                    <div class="mt-4 w-full bg-slate-200 h-2 rounded-full overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 h-full rounded-full shimmer-effect" :style="{ width: currentPlan?.max_agencies === -1 ? '100%' : '50%' }"></div>
                    </div>
                </div>

                <!-- Buildings Limit -->
                <div class="bg-slate-50/60 rounded-2xl p-5 border border-slate-100 flex flex-col justify-between transition-all hover:shadow-md">
                    <div>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Bâtiments autorisés</p>
                        <h3 class="text-2xl font-black text-slate-800">
                            {{ currentPlanLimitText(currentPlan?.max_buildings) }}
                        </h3>
                    </div>
                    <div class="mt-4 w-full bg-slate-200 h-2 rounded-full overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 h-full rounded-full shimmer-effect" :style="{ width: currentPlan?.max_buildings === -1 ? '100%' : '50%' }"></div>
                    </div>
                </div>

                <!-- Assistant IA Access -->
                <div class="bg-slate-50/60 rounded-2xl p-5 border border-slate-100 flex flex-col justify-between transition-all hover:shadow-md">
                    <div>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">IA & Assistants Autonomes</p>
                        <h3 class="text-2xl font-black flex items-center gap-2" :class="currentPlan?.has_ai ? 'text-emerald-600' : 'text-slate-500'">
                            <span v-if="currentPlan?.has_ai" class="flex h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                            {{ currentPlan?.has_ai ? 'Inclus' : 'Non inclus' }}
                        </h3>
                    </div>
                    <p class="text-[10px] text-slate-400 mt-4 leading-normal">
                        L'IA permet de générer automatiquement des contrats, états des lieux et rapports prévisionnels.
                    </p>
                </div>
            </div>
        </div>

        <!-- Available Plans Section -->
        <div class="space-y-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="text-center md:text-left">
                    <h2 class="text-2xl font-extrabold text-slate-800">Choisissez votre nouveau forfait</h2>
                    <p class="text-sm text-slate-500">Les forfaits disponibles sont conçus pour évoluer parallèlement à votre activité.</p>
                </div>

                <!-- Billing Cycle Selector Toggle -->
                <div class="flex items-center justify-center md:justify-start gap-2 bg-slate-150 p-1.5 rounded-2xl w-fit border border-slate-200/50 mx-auto md:mx-0">
                    <button 
                        @click="billingCycle = 'monthly'"
                        class="px-5 py-2.5 rounded-xl text-xs font-bold transition-all"
                        :class="billingCycle === 'monthly' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-850'"
                    >
                        Mensuel
                    </button>
                    <button 
                        @click="billingCycle = 'yearly'"
                        class="px-5 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5"
                        :class="billingCycle === 'yearly' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-850'"
                    >
                        <span>Annuel</span>
                        <span class="bg-indigo-500/15 text-indigo-700 text-[9px] font-black uppercase px-2 py-0.5 rounded-full">-20%</span>
                    </button>
                </div>
            </div>

            <!-- Loader -->
            <div v-if="loadingPlans" class="flex flex-col items-center justify-center py-12 gap-4">
                <svg class="animate-spin h-10 w-10 text-indigo-600" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-sm text-slate-500 font-medium">Chargement des forfaits disponibles...</p>
            </div>

            <!-- Pricing Grid -->
            <div v-else class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div
                    v-for="plan in companyPlans"
                    :key="plan.slug"
                    class="plan-card relative bg-white rounded-3xl border transition-all duration-300 flex flex-col justify-between overflow-hidden group shadow-lg"
                    :class="[
                        plan.slug === currentPlanSlug 
                            ? 'plan-card-active border-indigo-500 ring-2 ring-indigo-500/20' 
                            : (plan.popular ? 'plan-card-popular border-amber-400 ring-2 ring-amber-400/20' : 'border-slate-200/80 hover:border-indigo-300 hover:shadow-xl')
                    ]"
                >
                    <!-- Popular / Current Tag banner -->
                    <div
                        v-if="plan.slug === currentPlanSlug"
                        class="absolute top-0 right-0 bg-indigo-500 text-white text-[10px] font-black uppercase tracking-wider px-4 py-1.5 rounded-bl-xl shadow-sm z-10"
                    >
                        Actuel
                    </div>
                    <div
                        v-else-if="plan.popular"
                        class="absolute top-0 right-0 bg-gradient-to-r from-amber-500 to-amber-600 text-white text-[10px] font-black uppercase tracking-wider px-4 py-1.5 rounded-bl-xl shadow-sm z-10"
                    >
                        Recommandé
                    </div>

                    <div class="p-6 md:p-8 flex-1 flex flex-col justify-between relative z-10">
                        <div>
                            <!-- Header -->
                            <div class="mb-6">
                                <h3 class="text-xl font-extrabold text-slate-900 group-hover:text-indigo-600 transition-colors">
                                    {{ plan.name }}
                                </h3>
                                <p class="text-xs text-slate-500 mt-1 uppercase tracking-wide font-medium">Cycle {{ billingCycle === 'yearly' ? 'Annuel' : 'Mensuel' }}</p>
                            </div>

                            <!-- Pricing -->
                            <div class="flex items-baseline gap-1.5 mb-8">
                                <span class="text-3xl font-black bg-gradient-to-br from-indigo-900 to-slate-900 bg-clip-text text-transparent">
                                    {{ formatPrice(billingCycle === 'yearly' ? (plan.price_yearly || (plan.price * 12 * 0.8)) : plan.price) }}
                                </span>
                                <span class="text-xs font-extrabold text-slate-500">FCFA / {{ billingCycle === 'yearly' ? 'an' : 'mois' }}</span>
                            </div>

                            <!-- Features List -->
                            <div class="space-y-4 mb-8">
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest border-b border-slate-100 pb-2">Capacité incluse</p>
                                <ul class="space-y-3">
                                    <li class="flex items-center gap-2.5 text-xs text-slate-700 font-medium">
                                        <svg class="w-5 h-5 text-indigo-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Logements : <strong class="text-slate-800 ml-auto">{{ planLimitText(plan.max_logements) }}</strong>
                                    </li>
                                    <li class="flex items-center gap-2.5 text-xs text-slate-700 font-medium">
                                        <svg class="w-5 h-5 text-indigo-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Collaborateurs : <strong class="text-slate-800 ml-auto">{{ planLimitText(plan.max_employees) }}</strong>
                                    </li>
                                    <li class="flex items-center gap-2.5 text-xs text-slate-700 font-medium">
                                        <svg class="w-5 h-5 text-indigo-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Agences : <strong class="text-slate-800 ml-auto">{{ planLimitText(plan.max_agencies) }}</strong>
                                    </li>
                                    <li class="flex items-center gap-2.5 text-xs text-slate-700 font-medium">
                                        <svg class="w-5 h-5 text-indigo-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Assistant IA : <strong class="text-slate-800 ml-auto">{{ plan.has_ai ? 'Inclus' : 'Non inclus' }}</strong>
                                    </li>
                                </ul>
                            </div>

                            <!-- Plan Features Description -->
                            <div class="space-y-3 mb-6">
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest border-b border-slate-100 pb-2">Avantages clés</p>
                                <ul class="space-y-2">
                                    <li v-for="feat in parseFeatures(plan.features)" :key="feat" class="flex items-start gap-2 text-xs text-slate-600 leading-normal">
                                        <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                        </svg>
                                        <span>{{ feat }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- CTA Action Button -->
                        <div class="mt-8">
                            <button
                                v-if="plan.slug === currentPlanSlug"
                                disabled
                                class="w-full py-3.5 px-4 rounded-2xl bg-indigo-50 text-indigo-650 font-extrabold text-xs shadow-sm border border-indigo-200/50 cursor-not-allowed text-center transition-colors"
                            >
                                Votre Plan Actuel
                            </button>
                            <button
                                v-else-if="plan.price > currentPlanPrice"
                                @click="askUpgradeConfirm(plan)"
                                class="w-full py-3.5 px-4 rounded-2xl bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-extrabold text-xs shadow-lg shadow-indigo-600/20 hover:shadow-xl hover:shadow-indigo-650/40 text-center transition-all duration-200 glow-btn"
                            >
                                Passer à ce plan (Upgrade)
                            </button>
                            <button
                                v-else
                                disabled
                                class="w-full py-3.5 px-4 rounded-2xl bg-slate-100 text-slate-400 font-extrabold text-xs cursor-not-allowed border border-slate-200/50 text-center"
                                title="Les rétrogradations de forfait ne sont pas supportées automatiquement."
                            >
                                Plan Inférieur
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upgrade Confirmation Modal -->
        <Transition name="fade">
            <div v-if="confirmModalOpen" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-50 flex items-center justify-center p-4">
                <div class="bg-gradient-to-br from-white via-white to-indigo-50/10 rounded-3xl shadow-2xl max-w-lg w-full p-8 border border-indigo-100/50 relative overflow-hidden animate-scale-up text-left">
                    <div class="absolute -top-20 -right-20 w-48 h-48 bg-gradient-to-br from-indigo-400/10 to-violet-500/10 rounded-full blur-3xl"></div>
                    
                    <div class="relative z-10 space-y-6">
                        <!-- Icon Header -->
                        <div class="flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-500 to-indigo-600 shadow-lg shadow-indigo-500/30">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                            </svg>
                        </div>

                        <!-- Step 1: Selection & Input -->
                        <div v-if="modalStep === 'confirm'" class="space-y-4">
                            <div>
                                <h3 class="text-xl font-black text-slate-800">Souscrire au forfait</h3>
                                <p class="text-slate-500 text-xs mt-1">Sélectionnez votre moyen de paiement et validez.</p>
                            </div>

                            <!-- Comparison Cards -->
                            <div class="grid grid-cols-2 gap-4 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Forfait actuel</p>
                                    <p class="text-sm font-bold text-slate-700">{{ currentPlanName }}</p>
                                    <p class="text-xs font-black text-slate-500 mt-0.5">{{ formatPrice(currentPlanPrice) }} FCFA</p>
                                </div>
                                <div class="border-l border-slate-200 pl-4">
                                    <p class="text-[10px] font-bold text-indigo-500 uppercase tracking-wide">Nouveau forfait</p>
                                    <p class="text-sm font-bold text-indigo-900">{{ targetPlan?.name }}</p>
                                    <p class="text-xs font-black text-indigo-600 mt-0.5">
                                        {{ formatPrice(billingCycle === 'yearly' ? (targetPlan?.price_yearly || (targetPlan?.price * 12 * 0.8)) : targetPlan?.price) }} FCFA
                                        <span class="text-[10px] text-slate-500 font-normal">/ {{ billingCycle === 'yearly' ? 'an' : 'mois' }}</span>
                                    </p>
                                </div>
                            </div>

                            <!-- Payment Method Picker -->
                            <div class="space-y-2">
                                <label class="block text-[10px] uppercase font-black tracking-wider text-slate-500">Moyen de paiement</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <!-- Orange Money -->
                                    <button 
                                        type="button"
                                        @click="paymentMethod = 'orange_money'"
                                        class="p-3 border rounded-xl flex items-center justify-between text-left transition-all"
                                        :class="paymentMethod === 'orange_money' ? 'border-orange-500 bg-orange-50/20 text-orange-700 font-bold' : 'border-slate-200 text-slate-600'"
                                    >
                                        <span class="text-xs">Orange Money</span>
                                        <span class="h-2 w-2 rounded-full" :class="paymentMethod === 'orange_money' ? 'bg-orange-500' : 'bg-slate-300'"></span>
                                    </button>

                                    <!-- MTN MoMo -->
                                    <button 
                                        type="button"
                                        @click="paymentMethod = 'mtn_money'"
                                        class="p-3 border rounded-xl flex items-center justify-between text-left transition-all"
                                        :class="paymentMethod === 'mtn_money' ? 'border-amber-500 bg-amber-50/20 text-amber-700 font-bold' : 'border-slate-200 text-slate-600'"
                                    >
                                        <span class="text-xs">MTN MoMo</span>
                                        <span class="h-2 w-2 rounded-full" :class="paymentMethod === 'mtn_money' ? 'bg-amber-500' : 'bg-slate-300'"></span>
                                    </button>
                                </div>
                            </div>

                            <!-- Phone Number -->
                            <div class="space-y-2">
                                <label class="block text-[10px] uppercase font-black tracking-wider text-slate-500">Numéro de Téléphone Camerounais</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-3.5 text-xs font-bold text-slate-400">+237</span>
                                    <input 
                                        type="text" 
                                        v-model="phoneNumber"
                                        required
                                        placeholder="6XXXXXXXX"
                                        class="w-full pl-14 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:border-indigo-500 focus:bg-white outline-none transition-all font-mono"
                                    />
                                </div>
                            </div>

                            <!-- Alert message -->
                            <div class="rounded-xl bg-indigo-50/50 border border-indigo-100 p-3 text-[10px] text-slate-600 leading-normal flex gap-2">
                                <svg class="w-4 h-4 text-indigo-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 111.08 1.04l-.42.416-.517-.517v-1.667zm0 6.667a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                                </svg>
                                <span>
                                    En validant, une demande de paiement sera envoyée sur votre mobile. Tapez votre code secret (ex: #150#) si nécessaire pour valider le paiement.
                                </span>
                            </div>

                            <!-- Error Message inside modal -->
                            <p v-if="modalError" class="text-rose-500 text-[10px] font-semibold bg-rose-50 border border-rose-200 rounded-xl p-3">
                                {{ modalError }}
                            </p>

                            <!-- Actions -->
                            <div class="flex gap-4 pt-2">
                                <button
                                    @click="confirmModalOpen = false"
                                    :disabled="submittingUpgrade"
                                    class="flex-1 px-5 py-3.5 bg-slate-100 text-slate-600 rounded-xl font-bold hover:bg-slate-200 transition-all text-xs disabled:opacity-50"
                                >
                                    Annuler
                                </button>
                                <button
                                    @click="startPayment"
                                    :disabled="submittingUpgrade || !phoneNumber"
                                    class="flex-1 px-5 py-3.5 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white rounded-xl font-bold shadow-lg shadow-indigo-500/30 hover:shadow-xl hover:shadow-indigo-500/40 transition-all text-xs disabled:opacity-50 flex items-center justify-center gap-2"
                                >
                                    <svg v-if="submittingUpgrade" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span>{{ submittingUpgrade ? 'Initiation...' : 'Payer' }}</span>
                                </button>
                            </div>
                        </div>

                        <!-- Step 2: Pending status check -->
                        <div v-else-if="modalStep === 'payment_pending'" class="space-y-6 text-center">
                            <div>
                                <h3 class="text-xl font-black text-slate-800">Paiement en cours</h3>
                                <p class="text-slate-500 text-xs mt-1">Attente de validation sur votre téléphone mobile.</p>
                            </div>

                            <div class="py-6 flex flex-col items-center justify-center gap-4">
                                <div class="h-20 w-20 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 relative">
                                    <span class="absolute inset-0 rounded-full bg-orange-400/20 animate-ping"></span>
                                    <i class="fa-solid fa-mobile-screen text-4xl relative z-10"></i>
                                </div>
                                <div class="space-y-2">
                                    <p class="text-xs text-slate-700 font-bold">Demande envoyée au +237 {{ phoneNumber }}</p>
                                    <p class="text-[10px] text-slate-500 leading-normal max-w-sm mx-auto">
                                        Veuillez valider la transaction sur votre mobile. Une fois la transaction confirmée, cliquez sur le bouton ci-dessous pour activer votre abonnement.
                                    </p>
                                </div>
                            </div>

                            <!-- Error Message inside modal -->
                            <p v-if="modalError" class="text-amber-600 text-[10px] font-semibold bg-amber-50 border border-amber-200 rounded-xl p-3">
                                {{ modalError }}
                            </p>

                            <!-- Actions -->
                            <div class="flex gap-4">
                                <button
                                    @click="modalStep = 'confirm'"
                                    :disabled="checkingStatus"
                                    class="flex-1 px-5 py-3.5 bg-slate-100 text-slate-600 rounded-xl font-bold hover:bg-slate-200 transition-all text-xs disabled:opacity-50"
                                >
                                    Retour
                                </button>
                                <button
                                    @click="checkStatus"
                                    :disabled="checkingStatus"
                                    class="flex-1 px-5 py-3.5 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-xl font-bold shadow-lg shadow-emerald-500/30 hover:shadow-xl hover:shadow-emerald-500/40 transition-all text-xs disabled:opacity-50 flex items-center justify-center gap-2"
                                >
                                    <svg v-if="checkingStatus" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span>Vérifier le statut</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import axios from 'axios';

const page = usePage();
const user = computed(() => page.props.auth?.user || {});
const currentPlan = computed(() => user.value?.plan_relation || null);

const currentPlanSlug = computed(() => user.value?.subscription_plan || 'starter');
const currentPlanName = computed(() => currentPlan.value?.name || 'Starter / Gratuit');
const currentPlanPrice = computed(() => currentPlan.value?.price || 0);

const loadingPlans = ref(true);
const companyPlans = ref([]);
const confirmModalOpen = ref(false);
const targetPlan = ref(null);
const submittingUpgrade = ref(false);
const modalError = ref('');

const successToast = ref(false);
const successToastMessage = ref('');

// Payment flow states
const billingCycle = ref('monthly');
const paymentMethod = ref('orange_money');
const phoneNumber = ref('');
const modalStep = ref('confirm'); // confirm, payment_pending
const transactionId = ref(null);
const checkingStatus = ref(false);

const currentPlanLimitText = (limit) => {
    if (limit === undefined || limit === null) return 'Indéterminé';
    return limit === -1 ? 'Illimité' : limit;
};

const planLimitText = (limit) => {
    if (limit === undefined || limit === null) return 'N/A';
    return limit === -1 ? 'Illimité' : limit;
};

const formatPrice = (val) => {
    if (val === undefined || val === null) return '0';
    return val.toLocaleString('fr-FR');
};

const parseFeatures = (features) => {
    if (!features) return [];
    if (Array.isArray(features)) return features;
    try {
        return JSON.parse(features);
    } catch (e) {
        return [];
    }
};

const loadPlans = async () => {
    loadingPlans.value = true;
    try {
        const response = await axios.get('/api/subscription/plans');
        companyPlans.value = response.data || [];
    } catch (err) {
        console.error('Erreur lors du chargement des forfaits:', err);
    } finally {
        loadingPlans.value = false;
    }
};

const askUpgradeConfirm = (plan) => {
    targetPlan.value = plan;
    modalError.value = '';
    phoneNumber.value = '';
    paymentMethod.value = 'orange_money';
    modalStep.value = 'confirm';
    confirmModalOpen.value = true;
};

const startPayment = async () => {
    if (!targetPlan.value) return;
    submittingUpgrade.value = true;
    modalError.value = '';
    
    try {
        const response = await axios.post('/api/subscription/payment/initiate', {
            plan: targetPlan.value.slug,
            billing_cycle: billingCycle.value,
            payment_method: paymentMethod.value,
            phone_number: phoneNumber.value,
        });

        if (response.data.success) {
            transactionId.value = response.data.transaction_id;
            modalStep.value = 'payment_pending';
        } else {
            modalError.value = response.data.error || 'Erreur lors de l\'initiation du paiement.';
        }
    } catch (err) {
        console.error(err);
        modalError.value = err.response?.data?.error || 'Erreur réseau lors de l\'initiation du paiement.';
    } finally {
        submittingUpgrade.value = false;
    }
};

const checkStatus = async () => {
    if (!transactionId.value) return;
    checkingStatus.value = true;
    modalError.value = '';
    
    try {
        const response = await axios.get(`/api/subscription/payment/status/${transactionId.value}`);
        
        if (response.data.success && response.data.status === 'success') {
            confirmModalOpen.value = false;
            successToastMessage.value = response.data.message;
            successToast.value = true;
            
            setTimeout(() => {
                successToast.value = false;
                router.reload({
                    only: ['auth'],
                    onSuccess: () => {
                        router.visit('/dashboard');
                    }
                });
            }, 3000);
        } else {
            modalError.value = response.data.message || 'Le paiement est toujours en attente sur votre téléphone.';
        }
    } catch (err) {
        console.error(err);
        modalError.value = err.response?.data?.error || 'Erreur de communication lors de la vérification du statut.';
    } finally {
        checkingStatus.value = false;
    }
};

onMounted(() => {
    loadPlans();
});
</script>



<style scoped>
/* Transiciones de opacidad */
.fade-enter-active, .fade-leave-active {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
    transform: translateY(20px);
}

.animate-scale-up {
    animation: scaleUp 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
}

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

/* Animations de pulse lent pour le fond */
.animate-pulse-slow {
    animation: pulseSlow 8s ease-in-out infinite;
}

@keyframes pulseSlow {
    0%, 100% {
        opacity: 0.2;
        transform: scale(1);
    }
    50% {
        opacity: 0.4;
        transform: scale(1.1);
    }
}

/* Styles Premium pour les cartes d'abonnement */
.plan-card {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(16px);
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

.plan-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 25px 50px -12px rgba(99, 102, 241, 0.15);
}

.plan-card-popular {
    background: linear-gradient(180deg, #ffffff 0%, rgba(254, 243, 199, 0.15) 100%);
    border-color: #fbbf24 !important;
}

.plan-card-active {
    background: linear-gradient(180deg, #ffffff 0%, rgba(238, 242, 255, 0.25) 100%);
    border-color: #6366f1 !important;
}

/* Boutons avec effet de reflet lumineux au survol */
.glow-btn {
    position: relative;
    overflow: hidden;
}

.glow-btn::after {
    content: '';
    position: absolute;
    top: -50%;
    left: -60%;
    width: 200%;
    height: 200%;
    background: linear-gradient(
        to right,
        rgba(255, 255, 255, 0) 0%,
        rgba(255, 255, 255, 0.25) 50%,
        rgba(255, 255, 255, 0) 100%
    );
    transform: rotate(25deg) translateY(-80%);
    transition: all 0.75s cubic-bezier(0.16, 1, 0.3, 1);
}

.glow-btn:hover::after {
    transform: rotate(25deg) translateY(80%);
}

/* Shimmer animé sur les barres de progression */
.shimmer-effect {
    position: relative;
    overflow: hidden;
}

.shimmer-effect::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(
        90deg,
        rgba(255, 255, 255, 0) 0%,
        rgba(255, 255, 255, 0.35) 50%,
        rgba(255, 255, 255, 0) 100%
    );
    animation: shimmer-anim 2.5s infinite;
}

@keyframes shimmer-anim {
    0% {
        transform: translateX(-100%);
    }
    100% {
        transform: translateX(100%);
    }
}
</style>
