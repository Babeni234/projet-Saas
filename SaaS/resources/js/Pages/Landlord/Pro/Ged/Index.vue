<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    folders: Array,
    current_folder: Object,
    breadcrumbs: Array,
    current_children: Array,
    documents: Object,
    filters: Object,
    starred_count: Number,
    tenents: Array,
});

function fm(v) {
    if (!v) return '0 o';
    const units = ['o', 'Ko', 'Mo', 'Go'];
    let i = 0;
    let size = v;
    while (size >= 1024 && i < units.length - 1) { size /= 1024; i++; }
    return size.toFixed(i === 0 ? 0 : 1) + ' ' + units[i];
}

const iconMap = {
    pdf: 'text-red-500', jpg: 'text-blue-500', jpeg: 'text-blue-500',
    png: 'text-purple-500', doc: 'text-indigo-500', docx: 'text-indigo-500',
    xls: 'text-emerald-500', xlsx: 'text-emerald-500',
    txt: 'text-gray-500',
};

function fileIcon(type) {
    return iconMap[type] || 'text-gray-400';
}

const folderInput = ref(null);
const renameDialog = ref(null);
const folderForm = useForm({ name: '', parent_id: props.current_folder?.id || '', color: '#6366f1' });
function createFolder() { folderForm.post(route('landlord.pro.ged.folders.store')); }
function openFolderInput() { folderForm.parent_id = props.current_folder?.id || ''; folderForm.name = ''; folderInput.value?.showModal(); }

const renameForm = useForm({ name: '', color: '' });
function startRename(f) { renameForm.name = f.name; renameForm.color = f.color; renameDialog.value?.showModal(); }
function submitRename(f) { renameForm.post(route('landlord.pro.ged.folders.update', f.id)); }

const uploadForm = useForm({ file: null, folder_id: props.current_folder?.id || '', name: '' });
function onFileChange(e) { uploadForm.file = e.target.files[0]; if (!uploadForm.name) { uploadForm.name = e.target.files[0]?.name?.replace(/\.[^/.]+$/, '') || ''; } }
function upload() { uploadForm.post(route('landlord.pro.ged.upload'), { onSuccess: () => { uploadForm.reset(); } }); }

const docForm = useForm({ name: '', tags: '', expires_at: '', notes: '' });
function startEdit(d) { docForm.name = d.name; docForm.tags = d.tags || ''; docForm.expires_at = d.expires_at || ''; docForm.notes = d.notes || ''; }
function submitEdit(d) { docForm.post(route('landlord.pro.ged.documents.update', d.id)); }

const shareForm = useForm({ tenant_id: '', permission: 'view', expires_at: '' });
function startShare(d) { shareForm.tenant_id = ''; shareForm.permission = 'view'; shareForm.expires_at = ''; }
function submitShare(d) { shareForm.post(route('landlord.pro.ged.documents.share', d.id)); }

const moveForm = useForm({ folder_id: '' });
function startMove(d) { moveForm.folder_id = d.folder_id || ''; }
function submitMove(d) { moveForm.post(route('landlord.pro.ged.documents.move', d.id)); }

function toggleStar(d) { useForm().post(route('landlord.pro.ged.documents.star', d.id)); }
function destroyDoc(d) { if (confirm(`Supprimer "${d.name}" ?`)) { useForm().delete(route('landlord.pro.ged.documents.destroy', d.id)); } }
function destroyFolder(f) { if (confirm(`Supprimer le dossier "${f.name}" ? Les documents seront déplacés vers le dossier parent.`)) { useForm().delete(route('landlord.pro.ged.folders.destroy', f.id)); } }
</script>

<template>
    <Head title="GED - Documents" />
    <AppLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">GED</h2>
                    <p class="text-sm text-gray-500">Gestion Électronique de Documents</p>
                </div>
                <div class="flex items-center gap-2">
                    <Link :href="route('landlord.pro.ged.templates')"
                        class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                        Templates
                    </Link>
                </div>
            </div>
        </template>

        <div class="mb-4 flex items-center justify-between">
            <div class="flex items-center gap-1.5 text-sm">
                <Link :href="route('landlord.pro.ged.index')"
                    class="text-gray-500 hover:text-gray-700 transition font-medium">Racine</Link>
                <template v-for="(cr, i) in breadcrumbs" :key="cr.id">
                    <span class="text-gray-300">/</span>
                    <Link :href="route('landlord.pro.ged.index', { folder_id: cr.id })"
                        class="text-gray-500 hover:text-gray-700 transition"
                        :class="{ 'font-semibold text-gray-900': i === breadcrumbs.length - 1 }">
                        {{ cr.name }}
                    </Link>
                </template>
            </div>
            <div class="flex items-center gap-2">
                <Link :href="route('landlord.pro.ged.index', { starred: 1 })"
                    class="flex items-center gap-1 rounded-lg px-3 py-1.5 text-xs font-medium transition"
                    :class="filters?.starred ? 'bg-amber-100 text-amber-700' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'">
                    <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" /></svg>
                    Favoris ({{ starred_count }})
                </Link>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-4">
            <div class="space-y-3">
                <div class="rounded-xl border border-gray-200 bg-white p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-500">Dossiers</h3>
                        <button @click="openFolderInput"
                            class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">+</button>
                    </div>
                    <div v-if="folders.length === 0" class="text-xs text-gray-400 py-2">
                        Aucun dossier. Créez-en un.
                    </div>
                    <div v-else class="space-y-0.5">
                        <Link v-for="f in folders" :key="f.id"
                            :href="route('landlord.pro.ged.index', { folder_id: f.id })"
                            class="flex items-center gap-2 rounded-lg px-2.5 py-2 text-sm transition"
                            :class="current_folder?.id === f.id ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-50'">
                            <svg class="h-4 w-4 shrink-0" :style="{ color: f.color }" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M2 6a2 2 0 012-2h5l2 2h9a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" />
                            </svg>
                            <span class="truncate">{{ f.name }}</span>
                            <span class="ml-auto text-xs text-gray-400">{{ f.documents_count + (f.children_count || 0) }}</span>
                        </Link>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-4">
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-3">Uploader</h3>
                    <form @submit.prevent="upload" class="space-y-3">
                        <input @change="onFileChange" type="file"
                            class="block w-full text-xs text-gray-500 file:mr-3 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-3 file:py-1.5 file:text-xs file:font-medium file:text-indigo-700 hover:file:bg-indigo-100" />
                        <p v-if="uploadForm.errors.file" class="text-xs text-red-600">{{ uploadForm.errors.file }}</p>
                        <input v-model="uploadForm.name" type="text" placeholder="Nom (optionnel)"
                            class="block w-full rounded-lg border border-gray-300 px-3 py-1.5 text-xs focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                        <button type="submit" :disabled="!uploadForm.file || uploadForm.processing"
                            class="w-full rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-indigo-700 disabled:opacity-50 transition">
                            {{ uploadForm.processing ? 'Upload...' : 'Uploader' }}
                        </button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-3 space-y-4">
                <div v-if="current_children.length > 0" class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                    <Link v-for="f in current_children" :key="f.id"
                        :href="route('landlord.pro.ged.index', { folder_id: f.id })"
                        class="flex items-center gap-2 rounded-xl border border-gray-200 bg-white p-3 hover:border-gray-300 hover:shadow-sm transition group">
                        <svg class="h-8 w-8 shrink-0" :style="{ color: f.color }" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M2 6a2 2 0 012-2h5l2 2h9a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" />
                        </svg>
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ f.name }}</p>
                            <p class="text-xs text-gray-400">{{ f.documents_count }} document(s)</p>
                        </div>
                        <button @click.prevent="startRename(f)" class="ml-auto opacity-0 group-hover:opacity-100 text-gray-400 hover:text-gray-600">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        </button>
                        <button @click.prevent="destroyFolder(f)" class="opacity-0 group-hover:opacity-100 text-gray-400 hover:text-red-500">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                    </Link>
                </div>

                <div class="flex items-center gap-2">
                    <input v-model="filters.search" placeholder="Rechercher documents..."
                        class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                    <button @click="useForm({ ...filters, search: filters.search }).get(route('landlord.pro.ged.index'), { preserveState: true })"
                        class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 transition">
                        Chercher
                    </button>
                </div>

                <div v-if="documents.data.length === 0" class="rounded-xl border-2 border-dashed border-gray-200 bg-white p-16 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                    <p class="mt-3 text-sm text-gray-400">Ce dossier est vide.</p>
                </div>
                <div v-else class="space-y-2">
                    <div v-for="d in documents.data" :key="d.id"
                        class="rounded-xl border border-gray-200 bg-white p-4 flex items-center gap-4 hover:border-gray-300 transition group">
                        <button @click="toggleStar(d)" class="shrink-0 text-gray-300 hover:text-amber-400 transition"
                            :class="{ 'text-amber-400': d.is_starred }">
                            <svg class="h-4 w-4" :fill="d.is_starred ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                            </svg>
                        </button>

                        <div class="h-9 w-9 rounded-lg bg-gray-50 flex items-center justify-center shrink-0" :class="fileIcon(d.file_type)">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z" opacity="0.3" />
                                <path d="M14 2v6h6" />
                            </svg>
                        </div>

                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ d.name }}.{{ d.file_type }}</p>
                            <p class="text-xs text-gray-400">
                                {{ fm(d.file_size) }}
                                <span v-if="d.tags" class="ml-2"> &middot; {{ d.tags }}</span>
                                <span v-if="d.expires_at" class="ml-2"> &middot; Expire {{ new Date(d.expires_at).toLocaleDateString('fr-FR') }}</span>
                            </p>
                        </div>

                        <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition">
                            <a :href="'/' + d.file_path" target="_blank"
                                class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            </a>
                            <button @click="startEdit(d)"
                                class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            </button>
                            <button @click="startShare(d)"
                                class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" /></svg>
                            </button>
                            <button @click="startMove(d)"
                                class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                            </button>
                            <button @click="destroyDoc(d)"
                                class="rounded-lg p-1.5 text-gray-400 hover:bg-red-50 hover:text-red-500">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div v-if="documents.last_page > 1" class="flex items-center justify-center gap-2 pt-2">
                    <Link v-for="link in documents.links" :key="link.label"
                        :href="link.url || '#'" v-html="link.label"
                        class="rounded-lg px-3 py-1.5 text-xs font-medium transition"
                        :class="link.active ? 'bg-indigo-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200'" />
                </div>
            </div>
        </div>

        <dialog ref="folderInput" class="rounded-xl border border-gray-200 p-6 shadow-xl backdrop:bg-gray-900/30 max-w-md">
            <form @submit.prevent="createFolder" class="space-y-4">
                <h3 class="text-sm font-semibold text-gray-900">Nouveau dossier</h3>
                <input v-model="folderForm.name" type="text" required placeholder="Nom du dossier"
                    class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                <div class="flex items-center gap-2">
                    <input v-model="folderForm.color" type="color" class="h-8 w-8 rounded border border-gray-300 cursor-pointer" />
                    <span class="text-xs text-gray-400">Couleur</span>
                </div>
                <div class="flex items-center gap-2">
                    <button type="submit" :disabled="folderForm.processing"
                        class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50">
                        Créer
                    </button>
                    <button type="button" @click="folderInput?.close()"
                        class="text-sm text-gray-500 hover:text-gray-700">Annuler</button>
                </div>
            </form>
        </dialog>

        <dialog ref="renameDialog" class="rounded-xl border border-gray-200 p-6 shadow-xl backdrop:bg-gray-900/30 max-w-md">
            <form @submit.prevent="submitRename(current_children.find(f => f.id === renameForm.id) || {})" class="space-y-4">
                <h3 class="text-sm font-semibold text-gray-900">Renommer</h3>
                <input v-model="renameForm.name" type="text" required
                    class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                <div class="flex items-center gap-2">
                    <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                        Renommer
                    </button>
                    <button type="button" @click="renameDialog?.close()" class="text-sm text-gray-500 hover:text-gray-700">Annuler</button>
                </div>
            </form>
        </dialog>
    </AppLayout>
</template>
