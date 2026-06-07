<template>
    <Transition
        enter-active-class="transition duration-300 ease-out"
        enter-from-class="transform scale-95 opacity-0"
        enter-to-class="transform scale-100 opacity-100"
        leave-active-class="transition duration-200 ease-in"
        leave-from-class="transform scale-100 opacity-100"
        leave-to-class="transform scale-95 opacity-0"
    >
        <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <!-- Backdrop -->
            <div 
                class="absolute inset-0 bg-black/60 backdrop-blur-sm"
                @click="close"
            ></div>

            <!-- Modal Content -->
            <div 
                class="relative bg-white rounded-3xl shadow-2xl shadow-slate-900/20 max-w-2xl w-full max-h-[90vh] overflow-hidden"
                :class="sizeClass"
            >
                <!-- Header -->
                <div 
                    class="relative px-8 py-6 border-b border-slate-100"
                    :class="headerClass"
                >
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div 
                                v-if="icon"
                                class="w-12 h-12 rounded-2xl flex items-center justify-center"
                                :class="iconBgClass"
                            >
                                <component :is="icon" class="w-6 h-6" :class="iconClass" />
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                                    {{ title }}
                                </h3>
                                <p v-if="subtitle" class="text-slate-500 mt-1">{{ subtitle }}</p>
                            </div>
                        </div>
                        <button
                            @click="close"
                            class="p-2 rounded-xl hover:bg-slate-100 transition-all duration-200 group"
                        >
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-slate-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Body -->
                <div class="px-8 py-6 overflow-y-auto max-h-[60vh]">
                    <slot></slot>
                </div>

                <!-- Footer -->
                <div v-if="$slots.footer" class="px-8 py-6 bg-gradient-to-r from-slate-50 to-slate-100/50 border-t border-slate-100">
                    <slot name="footer"></slot>
                </div>
            </div>
        </div>
    </Transition>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    show: {
        type: Boolean,
        required: true
    },
    title: {
        type: String,
        default: ''
    },
    subtitle: {
        type: String,
        default: ''
    },
    size: {
        type: String,
        default: 'md',
        validator: (value) => ['sm', 'md', 'lg', 'xl'].includes(value)
    },
    type: {
        type: String,
        default: 'default',
        validator: (value) => ['default', 'success', 'error', 'warning', 'info'].includes(value)
    },
    icon: {
        type: [String, Object],
        default: null
    }
});

const emit = defineEmits(['close']);

const close = () => {
    emit('close');
};

const sizeClass = computed(() => {
    const sizes = {
        sm: 'max-w-md',
        md: 'max-w-2xl',
        lg: 'max-w-4xl',
        xl: 'max-w-6xl'
    };
    return sizes[props.size] || sizes.md;
});

const headerClass = computed(() => {
    const classes = {
        default: 'bg-white',
        success: 'bg-gradient-to-r from-emerald-50 to-emerald-100/50',
        error: 'bg-gradient-to-r from-red-50 to-red-100/50',
        warning: 'bg-gradient-to-r from-amber-50 to-amber-100/50',
        info: 'bg-gradient-to-r from-blue-50 to-blue-100/50'
    };
    return classes[props.type] || classes.default;
});

const iconBgClass = computed(() => {
    const classes = {
        default: 'bg-slate-100',
        success: 'bg-gradient-to-br from-emerald-500 to-emerald-600',
        error: 'bg-gradient-to-br from-red-500 to-red-600',
        warning: 'bg-gradient-to-br from-amber-500 to-amber-600',
        info: 'bg-gradient-to-br from-blue-500 to-blue-600'
    };
    return classes[props.type] || classes.default;
});

const iconClass = computed(() => {
    const classes = {
        default: 'text-slate-600',
        success: 'text-white',
        error: 'text-white',
        warning: 'text-white',
        info: 'text-white'
    };
    return classes[props.type] || classes.default;
});
</script>
