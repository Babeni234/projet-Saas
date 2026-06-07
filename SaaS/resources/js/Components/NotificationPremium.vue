<template>
    <Transition
        enter-active-class="transition duration-500 ease-out"
        enter-from-class="transform translate-x-full opacity-0"
        enter-to-class="transform translate-x-0 opacity-100"
        leave-active-class="transition duration-300 ease-in"
        leave-from-class="transform translate-x-0 opacity-100"
        leave-to-class="transform translate-x-full opacity-0"
    >
        <div v-if="show" class="fixed top-6 right-6 z-50">
            <div 
                class="relative bg-white rounded-2xl shadow-2xl shadow-slate-900/20 p-6 min-w-[400px] border border-slate-100"
                :class="borderClass"
            >
                <!-- Icon -->
                <div class="flex items-start gap-4">
                    <div 
                        class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0"
                        :class="iconBgClass"
                    >
                        <svg v-if="type === 'success'" class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <svg v-else-if="type === 'error'" class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <svg v-else-if="type === 'warning'" class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <svg v-else class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    
                    <!-- Content -->
                    <div class="flex-1">
                        <h4 class="text-lg font-bold text-slate-800">{{ title }}</h4>
                        <p class="text-slate-600 mt-1">{{ message }}</p>
                    </div>
                    
                    <!-- Close Button -->
                    <button
                        @click="close"
                        class="p-1 rounded-lg hover:bg-slate-100 transition-all duration-200"
                    >
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <!-- Progress Bar -->
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-slate-100 rounded-b-2xl overflow-hidden">
                    <div 
                        class="h-full transition-all duration-linear"
                        :class="progressClass"
                        :style="{ width: progressWidth + '%' }"
                    ></div>
                </div>
            </div>
        </div>
    </Transition>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from 'vue';

const props = defineProps({
    show: {
        type: Boolean,
        required: true
    },
    type: {
        type: String,
        default: 'success',
        validator: (value) => ['success', 'error', 'warning', 'info'].includes(value)
    },
    title: {
        type: String,
        default: ''
    },
    message: {
        type: String,
        default: ''
    },
    duration: {
        type: Number,
        default: 5000
    }
});

const emit = defineEmits(['close']);

const progressWidth = ref(100);
let progressInterval = null;
let soundTimeout = null;

const borderClass = computed(() => {
    const classes = {
        success: 'border-l-4 border-l-emerald-500',
        error: 'border-l-4 border-l-red-500',
        warning: 'border-l-4 border-l-amber-500',
        info: 'border-l-4 border-l-blue-500'
    };
    return classes[props.type] || classes.success;
});

const iconBgClass = computed(() => {
    const classes = {
        success: 'bg-gradient-to-br from-emerald-500 to-emerald-600',
        error: 'bg-gradient-to-br from-red-500 to-red-600',
        warning: 'bg-gradient-to-br from-amber-500 to-amber-600',
        info: 'bg-gradient-to-br from-blue-500 to-blue-600'
    };
    return classes[props.type] || classes.success;
});

const progressClass = computed(() => {
    const classes = {
        success: 'bg-emerald-500',
        error: 'bg-red-500',
        warning: 'bg-amber-500',
        info: 'bg-blue-500'
    };
    return classes[props.type] || classes.success;
});

const playSound = () => {
    try {
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();
        
        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);
        
        if (props.type === 'success') {
            // Success sound - pleasant ascending tone
            oscillator.frequency.setValueAtTime(523.25, audioContext.currentTime); // C5
            oscillator.frequency.setValueAtTime(659.25, audioContext.currentTime + 0.1); // E5
            oscillator.frequency.setValueAtTime(783.99, audioContext.currentTime + 0.2); // G5
            gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.4);
            oscillator.start(audioContext.currentTime);
            oscillator.stop(audioContext.currentTime + 0.4);
        } else if (props.type === 'error') {
            // Error sound - descending tone
            oscillator.frequency.setValueAtTime(400, audioContext.currentTime);
            oscillator.frequency.setValueAtTime(300, audioContext.currentTime + 0.15);
            oscillator.frequency.setValueAtTime(200, audioContext.currentTime + 0.3);
            gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.4);
            oscillator.start(audioContext.currentTime);
            oscillator.stop(audioContext.currentTime + 0.4);
        } else if (props.type === 'warning') {
            // Warning sound - two short beeps
            oscillator.frequency.setValueAtTime(440, audioContext.currentTime);
            gainNode.gain.setValueAtTime(0.2, audioContext.currentTime);
            gainNode.gain.setValueAtTime(0, audioContext.currentTime + 0.1);
            oscillator.start(audioContext.currentTime);
            oscillator.stop(audioContext.currentTime + 0.1);
            
            setTimeout(() => {
                const osc2 = audioContext.createOscillator();
                const gain2 = audioContext.createGain();
                osc2.connect(gain2);
                gain2.connect(audioContext.destination);
                osc2.frequency.setValueAtTime(440, audioContext.currentTime);
                gain2.gain.setValueAtTime(0.2, audioContext.currentTime);
                gain2.gain.setValueAtTime(0, audioContext.currentTime + 0.1);
                osc2.start(audioContext.currentTime);
                osc2.stop(audioContext.currentTime + 0.1);
            }, 150);
        }
    } catch (error) {
        console.log('Audio not supported');
    }
};

const startProgress = () => {
    progressWidth.value = 100;
    const interval = 50;
    const decrement = (100 / (props.duration / interval));
    
    progressInterval = setInterval(() => {
        progressWidth.value -= decrement;
        if (progressWidth.value <= 0) {
            clearInterval(progressInterval);
            close();
        }
    }, interval);
};

const close = () => {
    emit('close');
};

watch(() => props.show, (newVal) => {
    if (newVal) {
        playSound();
        startProgress();
    } else {
        if (progressInterval) {
            clearInterval(progressInterval);
        }
    }
});

onUnmounted(() => {
    if (progressInterval) {
        clearInterval(progressInterval);
    }
});
</script>
