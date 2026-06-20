<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    pendingPayment: {
        type: Object,
        default: null
    },
    error: {
        type: String,
        default: ''
    }
});

const pinInput = ref('');
const pinError = ref('');
const validating = ref(false);
const success = ref(false);
const pinInputEl = ref(null);
const activeError = ref(props.error);

onMounted(() => {
    if (props.pendingPayment) {
        focusInput();
    }
});

const focusInput = () => {
    if (pinInputEl.value && !validating.value && !success.value) {
        pinInputEl.value.focus();
    }
};

const formatCurrency = (val) => {
    if (val === undefined || val === null || isNaN(val) || val === '') return '0 €';
    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(val);
};

const handleInput = () => {
    pinInput.value = pinInput.value.replace(/\D/g, '').slice(0, 4);
    if (pinInput.value.length === 4) {
        submitPin();
    }
};

const playSuccessChime = () => {
    try {
        const ctx = new (window.AudioContext || window.webkitAudioContext)();
        const notes = [523.25, 587.33, 659.25, 783.99];
        notes.forEach((freq, i) => {
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.type = 'sine';
            osc.frequency.value = freq;
            const t = i * 0.08;
            gain.gain.setValueAtTime(0, t);
            gain.gain.linearRampToValueAtTime(0.12, t + 0.03);
            gain.gain.exponentialRampToValueAtTime(0.001, t + 0.6);
            osc.connect(gain).connect(ctx.destination);
            osc.start(t);
            osc.stop(t + 0.65);
        });
    } catch (_) {}
};

const submitPin = async () => {
    if (pinInput.value.length < 4 || validating.value || success.value) return;

    validating.value = true;
    pinError.value = '';

    try {
        const res = await axios.post(`/api/wallet/validate-payment/${props.pendingPayment.token}`, {
            pin: pinInput.value
        });
        
        success.value = true;
        playSuccessChime();
    } catch (err) {
        pinInput.value = '';
        pinError.value = err.response?.data?.message || 'Une erreur est survenue lors de la validation.';
        setTimeout(focusInput, 100);
    } finally {
        validating.value = false;
    }
};
</script>

<template>
    <Head title="Validation de paiement Wallet" />

    <div class="min-h-screen bg-slate-50 flex flex-col justify-center items-center p-4 selection:bg-blue-500 selection:text-white font-sans">
        <!-- Logo / Brand -->
        <div class="mb-8 text-center animate-fade-in">
            <span class="text-xs font-bold tracking-widest text-slate-400 uppercase">Portefeuille Électronique</span>
            <h2 class="text-2xl font-black text-slate-800 tracking-tight mt-1">Property AI</h2>
        </div>

        <!-- Main Card -->
        <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl border border-slate-200/60 overflow-hidden relative transition-all duration-300">
            <!-- Loading overlay -->
            <div v-if="validating" class="absolute inset-0 bg-white/80 backdrop-blur-sm z-50 flex flex-col items-center justify-center">
                <svg class="animate-spin h-10 w-10 text-blue-600 mb-3" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-sm font-bold text-slate-600">Validation en cours...</span>
            </div>

            <!-- Error State -->
            <div v-if="activeError" class="p-8 text-center">
                <div class="w-16 h-16 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center mx-auto mb-5 border border-red-100">
                    <i class="fa-solid fa-circle-exclamation text-2xl"></i>
                </div>
                <h3 class="text-lg font-black text-slate-800 mb-2">Opération impossible</h3>
                <p class="text-slate-500 text-sm leading-relaxed mb-6">{{ activeError }}</p>
                <a href="/" class="inline-flex items-center justify-center px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition-all">
                    Retour à l'accueil
                </a>
            </div>

            <!-- Success State -->
            <div v-else-if="success" class="p-8 text-center animate-scale-up">
                <div class="w-16 h-16 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center mx-auto mb-5 border border-emerald-100">
                    <i class="fa-solid fa-circle-check text-2xl"></i>
                </div>
                <h3 class="text-lg font-black text-slate-800 mb-2">Paiement validé !</h3>
                <p class="text-slate-500 text-sm leading-relaxed mb-6">
                    Votre règlement de <strong class="text-slate-800">{{ formatCurrency(pendingPayment.amount) }}</strong> a été autorisé avec succès.
                </p>
                <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 text-[11px] text-slate-400 space-y-1 mb-6 text-left">
                    <div><strong>Bénéficiaire :</strong> {{ pendingPayment.company_name }}</div>
                    <div><strong>Objet :</strong> {{ pendingPayment.description }}</div>
                    <div><strong>Statut :</strong> Enregistré &amp; Quittancé</div>
                </div>
                <p class="text-xs text-slate-400">Vous pouvez fermer cet onglet en toute sécurité.</p>
            </div>

            <!-- Normal state (PIN Input Form) -->
            <div v-else class="p-8">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                    <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center border border-blue-100">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-slate-800">Autorisation requise</h3>
                        <p class="text-[10px] text-slate-400 uppercase tracking-widest font-bold">Sécurité 3D Wallet</p>
                    </div>
                </div>

                <div class="mb-6">
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Bonjour <strong class="text-slate-800">{{ pendingPayment.locataire_nom }}</strong>. L'agence <strong class="text-slate-800">{{ pendingPayment.agency_name !== 'N/A' ? pendingPayment.agency_name : pendingPayment.company_name }}</strong> sollicite un débit sur votre portefeuille électronique.
                    </p>
                </div>

                <!-- Transaction Details Box -->
                <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 mb-8 space-y-3">
                    <div class="flex justify-between items-center pb-2 border-b border-slate-200/50">
                        <span class="text-[10px] text-slate-400 font-bold uppercase">Montant</span>
                        <strong class="text-base font-black text-blue-600">{{ formatCurrency(pendingPayment.amount) }}</strong>
                    </div>
                    <div class="space-y-1">
                        <div class="text-[9px] text-slate-400 font-bold uppercase">Objet de la transaction</div>
                        <div class="text-xs font-semibold text-slate-700 leading-snug">{{ pendingPayment.description }}</div>
                    </div>
                </div>

                <!-- PIN input entry area -->
                <div class="text-center mb-8" @click="focusInput">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-4">Saisissez votre code PIN secret</label>
                    
                    <div class="flex justify-center gap-4 mb-4">
                        <span v-for="i in 4" :key="i" 
                              class="w-12 h-12 rounded-xl border-2 flex items-center justify-center transition-all duration-150 cursor-pointer"
                              :class="[
                                  pinInput.length >= i 
                                      ? 'border-blue-500 bg-blue-50/50' 
                                      : 'border-slate-200 hover:border-slate-300 bg-slate-50/30'
                              ]"
                        >
                            <span v-if="pinInput.length >= i" class="w-3 h-3 bg-blue-600 rounded-full animate-scale-up"></span>
                        </span>
                    </div>

                    <!-- Hidden text input -->
                    <input 
                        ref="pinInputEl"
                        type="password"
                        v-model="pinInput"
                        maxlength="4"
                        @input="handleInput"
                        class="absolute opacity-0 -z-50 pointer-events-none"
                    />

                    <!-- Error Alert -->
                    <div v-if="pinError" class="mt-4 p-3 bg-red-50 border border-red-100 text-red-600 rounded-xl text-xs font-semibold flex items-center justify-center gap-2 animate-shake">
                        <i class="fa-solid fa-circle-xmark"></i>
                        <span>{{ pinError }}</span>
                    </div>
                </div>

                <div class="text-center text-[10px] text-slate-400">
                    <i class="fa-solid fa-lock mr-1 text-slate-350"></i> Connexion sécurisée de bout en bout
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
@keyframes scaleUp {
    from {
        opacity: 0;
        transform: scale(0.96);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-4px); }
    75% { transform: translateX(4px); }
}

.animate-scale-up {
    animation: scaleUp 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
.animate-fade-in {
    animation: fadeIn 0.4s ease forwards;
}
.animate-shake {
    animation: shake 0.2s ease 2;
}
</style>
