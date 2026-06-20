<script setup>
import { Link, Head, usePage } from '@inertiajs/vue3';
import { ref, onMounted, provide } from 'vue';

const page = usePage();
const adminUser = page.props.auth?.user || { name: 'Super Admin', email: 'superadmin@propertyai.com' };

const currentTime = ref('');
const theme = ref('dark');

const updateTime = () => {
    const options = { hour: '2-digit', minute: '2-digit', second: '2-digit', timeZone: 'Europe/Paris' };
    currentTime.value = new Intl.DateTimeFormat('fr-FR', options).format(new Date());
};

const applyTheme = () => {
    if (theme.value === 'light') {
        document.documentElement.classList.add('theme-light');
    } else {
        document.documentElement.classList.remove('theme-light');
    }
};

const toggleTheme = () => {
    theme.value = theme.value === 'dark' ? 'light' : 'dark';
    localStorage.setItem('propertyai-superadmin-theme', theme.value);
    applyTheme();
};

onMounted(() => {
    updateTime();
    setInterval(updateTime, 1000);
    
    const saved = localStorage.getItem('propertyai-superadmin-theme');
    if (saved === 'light' || saved === 'dark') {
        theme.value = saved;
    }
    applyTheme();
});

// Provide the theme state to child components
provide('theme', theme);

const getInitials = (name) => {
    if (!name) return 'SA';
    return name.split(' ').map(n => n[0]).join('').slice(0, 2).toUpperCase();
};
</script>

<template>
    <Head title="Super Administration | Property AI" />

    <div 
        class="h-screen w-screen flex overflow-hidden font-sans selection:bg-indigo-500 selection:text-white theme-transition relative"
        :class="[
            theme === 'light' ? 'theme-light bg-[var(--bg-app)] text-[var(--text-main)]' : 'bg-[var(--bg-app)] text-[var(--text-main)]'
        ]"
    >
        <!-- Background decorative ambient lights -->
        <div v-if="theme === 'dark'" class="absolute -top-40 -right-40 w-[500px] h-[500px] bg-indigo-500/10 rounded-full blur-[150px] pointer-events-none z-0"></div>
        <div v-if="theme === 'dark'" class="absolute -bottom-40 -left-40 w-[500px] h-[500px] bg-cyan-500/10 rounded-full blur-[150px] pointer-events-none z-0"></div>
        
        <div v-if="theme === 'light'" class="absolute -top-40 -right-40 w-[500px] h-[500px] bg-indigo-300/10 rounded-full blur-[120px] pointer-events-none z-0"></div>
        <div v-if="theme === 'light'" class="absolute -bottom-40 -left-40 w-[500px] h-[500px] bg-sky-200/10 rounded-full blur-[120px] pointer-events-none z-0"></div>

        <!-- Sidebar -->
        <aside class="w-72 bg-[var(--bg-sidebar)] border-r border-[var(--border-color)] flex flex-col justify-between p-6 shrink-0 relative z-10 shadow-xl backdrop-blur-lg">
            <div>
                <!-- Brand logo with subtle glow -->
                <div class="flex items-center gap-3.5 mb-12 px-2 pt-2">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 via-indigo-650 to-cyan-500 shadow-lg shadow-indigo-500/25 border border-white/10 relative group">
                        <div class="absolute inset-0 bg-indigo-500 rounded-2xl blur-md opacity-25 group-hover:opacity-40 transition-opacity duration-300"></div>
                        <span class="text-base font-black text-white relative z-10 tracking-wider">PA</span>
                    </div>
                    <div>
                        <span class="text-base font-bold tracking-tight block" :class="theme === 'light' ? 'text-slate-900' : 'text-white'">Property <span class="text-indigo-400 font-extrabold">AI</span></span>
                        <span class="text-[9px] font-black text-indigo-500 uppercase tracking-widest block mt-0.5">Super Admin</span>
                    </div>
                </div>

                <!-- Navigation menu -->
                <nav class="space-y-2">
                    <Link 
                        :href="route('superadmin.dashboard')" 
                        class="group flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-sm font-semibold transition-all duration-300 relative overflow-hidden"
                        :class="[
                            $page.component === 'SuperAdmin/Dashboard' 
                                ? 'bg-gradient-to-r from-indigo-600 to-indigo-500 text-white shadow-lg shadow-indigo-600/15' 
                                : 'text-[var(--text-muted)] hover:text-[var(--text-main)] hover:bg-[var(--bg-btn-secondary)]'
                        ]"
                    >
                        <div v-if="$page.component === 'SuperAdmin/Dashboard'" class="absolute left-0 top-3.5 bottom-3.5 w-1 bg-white rounded-r-full"></div>
                        <i class="fa-solid fa-chart-pie text-base transition-transform group-hover:scale-110 duration-300" :class="[$page.component === 'SuperAdmin/Dashboard' ? 'text-white' : 'text-indigo-500/70 group-hover:text-indigo-500']"></i>
                        <span>Tableau de bord</span>
                    </Link>

                    <Link 
                        :href="route('superadmin.companies.index')" 
                        class="group flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-sm font-semibold transition-all duration-300 relative overflow-hidden"
                        :class="[
                            $page.component === 'SuperAdmin/Companies/Index' 
                                ? 'bg-gradient-to-r from-indigo-600 to-indigo-500 text-white shadow-lg shadow-indigo-600/15' 
                                : 'text-[var(--text-muted)] hover:text-[var(--text-main)] hover:bg-[var(--bg-btn-secondary)]'
                        ]"
                    >
                        <div v-if="$page.component === 'SuperAdmin/Companies/Index'" class="absolute left-0 top-3.5 bottom-3.5 w-1 bg-white rounded-r-full"></div>
                        <i class="fa-solid fa-building text-base transition-transform group-hover:scale-110 duration-300" :class="[$page.component === 'SuperAdmin/Companies/Index' ? 'text-white' : 'text-indigo-500/70 group-hover:text-indigo-500']"></i>
                        <span>Entreprises</span>
                    </Link>

                    <Link 
                        :href="route('superadmin.users.index')" 
                        class="group flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-sm font-semibold transition-all duration-300 relative overflow-hidden"
                        :class="[
                            $page.component === 'SuperAdmin/Users/Index' 
                                ? 'bg-gradient-to-r from-indigo-600 to-indigo-500 text-white shadow-lg shadow-indigo-600/15' 
                                : 'text-[var(--text-muted)] hover:text-[var(--text-main)] hover:bg-[var(--bg-btn-secondary)]'
                        ]"
                    >
                        <div v-if="$page.component === 'SuperAdmin/Users/Index'" class="absolute left-0 top-3.5 bottom-3.5 w-1 bg-white rounded-r-full"></div>
                        <i class="fa-solid fa-users text-base transition-transform group-hover:scale-110 duration-300" :class="[$page.component === 'SuperAdmin/Users/Index' ? 'text-white' : 'text-indigo-500/70 group-hover:text-indigo-500']"></i>
                        <span>Utilisateurs</span>
                    </Link>
                </nav>
            </div>

            <!-- Footer admin identity & logout -->
            <div class="border-t border-[var(--border-color)] pt-6">
                <div class="flex items-center gap-3.5 mb-5 px-2">
                    <div class="h-11 w-11 rounded-2xl bg-gradient-to-br from-indigo-500/10 to-cyan-500/10 border border-indigo-500/25 text-indigo-400 font-extrabold flex items-center justify-center shadow-inner">
                        {{ getInitials(adminUser.name) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold truncate" :class="theme === 'light' ? 'text-slate-800' : 'text-slate-200'">{{ adminUser.name }}</p>
                        <p class="text-[10px] text-[var(--text-muted-darker)] truncate font-semibold mt-0.5">{{ adminUser.email }}</p>
                    </div>
                </div>

                <Link 
                    :href="route('logout')" 
                    method="post" 
                    as="button" 
                    class="w-full flex items-center justify-center gap-2.5 px-4 py-3.5 bg-red-500/5 hover:bg-red-650 text-red-550 hover:text-white rounded-2xl text-xs font-bold border border-red-500/15 hover:border-red-600 shadow-sm transition-all duration-300 active:scale-95"
                >
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    <span>Déconnexion</span>
                </Link>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden relative z-10">
            <!-- Top Header -->
            <header class="h-20 bg-[var(--bg-header)] border-b border-[var(--border-color)] flex items-center justify-between px-8 shadow-sm backdrop-blur-md">
                <!-- Left path/indicator -->
                <div class="flex items-center gap-2.5">
                    <span class="text-[10px] font-black text-indigo-500 uppercase tracking-widest bg-indigo-500/5 px-2.5 py-1.5 rounded-lg border border-indigo-500/10">Supervision</span>
                    <span class="text-slate-500 text-xs font-bold">/</span>
                    <span class="text-xs font-bold text-[var(--text-muted)]">
                        {{ 
                            $page.component === 'SuperAdmin/Dashboard' ? 'Tableau de bord global' :
                            $page.component === 'SuperAdmin/Companies/Index' ? 'Parc des Entreprises' : 'Répertoire des Utilisateurs' 
                        }}
                    </span>
                </div>

                <!-- Right Clock & Identity & Theme switch -->
                <div class="flex items-center gap-4">
                    <!-- Theme Switcher Button -->
                    <button 
                        @click="toggleTheme" 
                        class="h-10 w-10 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl flex items-center justify-center text-[var(--text-muted)] hover:text-indigo-500 hover:border-indigo-500/30 transition-all shadow-sm active:scale-95"
                        :title="theme === 'light' ? 'Mode Sombre' : 'Mode Clair'"
                    >
                        <i class="fa-solid text-sm" :class="theme === 'light' ? 'fa-moon' : 'fa-sun'"></i>
                    </button>

                    <div class="flex items-center gap-2 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl px-4 py-2 text-xs text-[var(--text-muted)] font-semibold shadow-sm">
                        <i class="fa-regular fa-clock text-indigo-500 text-sm animate-pulse"></i>
                        <span>{{ currentTime }}</span>
                    </div>

                    <a href="/" target="_blank" class="text-xs font-bold text-[var(--text-muted)] hover:text-indigo-500 transition-colors flex items-center gap-1.5 ml-2 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl px-4 py-2 shadow-sm">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        <span>Voir le site public</span>
                    </a>
                </div>
            </header>

            <!-- Page content -->
            <main class="flex-1 overflow-y-auto p-8 bg-[var(--bg-app)]">
                <slot />
            </main>
        </div>
    </div>
</template>

<style>
:root {
  --bg-app: #060913; /* Deep midnight */
  --bg-sidebar: #02040a; /* True pitch dark */
  --bg-header: rgba(6, 9, 19, 0.7);
  --bg-card: rgba(13, 20, 38, 0.45);
  --bg-input: #0b1122;
  --border-color: rgba(99, 102, 241, 0.15); /* Sleek visible dark indigo border */
  --text-main: #f8fafc;
  --text-muted: #94a3b8;
  --text-muted-darker: #475569;
  --bg-table-hover: rgba(99, 102, 241, 0.03);
  --card-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.5);
  --bg-btn-secondary: rgba(15, 23, 42, 0.6);
}

.theme-light {
  --bg-app: #faf9f6; /* Off-white (warm alabaster) background */
  --bg-sidebar: #f4f3ef; /* Slightly darker warm off-white sidebar */
  --bg-header: rgba(250, 249, 246, 0.85);
  --bg-card: #ffffff; /* Solid white card for maximum contrast against off-white bg */
  --bg-input: #f4f3ef;
  --border-color: rgba(180, 175, 160, 0.25); /* Elegant warm stone border tint */
  --text-main: #1c1917; /* Warm dark stone text */
  --text-muted: #57534e;
  --text-muted-darker: #8c857b;
  --bg-table-hover: rgba(180, 175, 160, 0.05);
  --card-shadow: 0 10px 30px -5px rgba(120, 110, 95, 0.06), 0 4px 12px -2px rgba(120, 110, 95, 0.03);
  --bg-btn-secondary: #eae8e3;
}

.theme-transition * {
  transition: background-color 0.3s cubic-bezier(0.4, 0, 0.2, 1), 
              border-color 0.3s cubic-bezier(0.4, 0, 0.2, 1), 
              color 0.3s cubic-bezier(0.4, 0, 0.2, 1), 
              box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1),
              transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Custom Scrollbar for premium feel */
::-webkit-scrollbar {
  width: 6px;
  height: 6px;
}
::-webkit-scrollbar-track {
  background: transparent;
}
::-webkit-scrollbar-thumb {
  background: rgba(99, 102, 241, 0.15);
  border-radius: 10px;
}
::-webkit-scrollbar-thumb:hover {
  background: rgba(99, 102, 241, 0.3);
}
</style>
