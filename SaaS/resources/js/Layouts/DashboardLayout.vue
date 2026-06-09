<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

// Tu peux importer des sous-composants ici si tu veux séparer encore plus (ex: Navbar.vue, Sidebar.vue)
</script>

<template>
    <div class="min-h-screen bg-gray-100 flex">
        
        <aside class="w-64 bg-slate-800 text-white flex flex-col">
            <div class="p-5 font-bold text-xl border-b border-slate-700">Mon SaaS</div>
            <nav class="flex-1 p-4 space-y-2">
                <Link href="/dashboard" class="block p-2 rounded hover:bg-slate-700">Tableau de bord</Link>
                <Link href="/users" class="block p-2 rounded hover:bg-slate-700">Utilisateurs</Link>
                <Link href="/settings" class="block p-2 rounded hover:bg-slate-700">Paramètres</Link>
            </nav>
            <div class="p-4 border-t border-slate-700 text-sm text-slate-400">© 2026 Entreprise</div>
        </aside>

        <div class="flex-1 flex flex-col">
            
            <header class="h-16 bg-white shadow flex items-center justify-between px-6">
                <div class="text-gray-600">Recherche...</div>
                <div class="flex items-center space-x-4">
                    <span>Mon Profil</span>
                </div>
            </header>

            <main class="flex-1 p-6">
                <div class="mb-6">
                    <slot name="header" />
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <slot />
                </div>
            </main>

        </div>
    </div>
</template>