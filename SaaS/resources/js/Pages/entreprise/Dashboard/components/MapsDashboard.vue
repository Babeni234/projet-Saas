<template>
    <div class="flex flex-col gap-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-800 flex items-center gap-2">
                    Localisation Maps
                    <span v-if="settings.autoRotate && settings.displayMode === 'globe'" class="inline-block animate-spin text-xl select-none origin-center" style="animation-duration: 4s;">🌐</span>
                </h2>
                <p class="text-slate-500">Carte interactive en temps réel des emplacements mondiaux</p>
            </div>
            <div class="flex items-center gap-3">
                <select 
                    v-model="selectedLocation" 
                    @change="centerOnLocation"
                    class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-50 transition-all duration-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                >
                    <option value="">Sélectionner une ville</option>
                    <option v-for="city in citiesList" :key="city" :value="city">
                        {{ city }}
                    </option>
                </select>
                <button 
                    @click="toggleSettings" 
                    class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-50 transition-all duration-200 shadow-sm"
                >
                    <span class="flex items-center gap-2">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Paramètres
                    </span>
                </button>
                <button 
                    @click="refreshLocations" 
                    class="px-4 py-2 bg-indigo-500 text-white rounded-xl text-sm font-medium hover:bg-indigo-600 transition-all duration-200 shadow-md shadow-indigo-500/20"
                >
                    <span class="flex items-center gap-2">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" fill="currentColor"/>
                        </svg>
                        Actualiser
                    </span>
                </button>
            </div>
        </div>

        <!-- Settings Panel -->
        <div v-if="showSettings" class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">Paramètres de la carte</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="space-y-2">
                    <label class="text-sm font-medium text-slate-700">Mode d'affichage</label>
                    <select v-model="settings.displayMode" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="globe">Globe 3D</option>
                        <option value="map">Carte plane</option>
                        <option value="satellite">Satellite</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium text-slate-700">Rotation automatique</label>
                    <div class="flex items-center gap-3">
                        <button 
                            @click="settings.autoRotate = !settings.autoRotate"
                            class="w-12 h-6 rounded-full transition-colors duration-200"
                            :class="settings.autoRotate ? 'bg-indigo-500' : 'bg-slate-300'"
                        >
                            <div class="w-5 h-5 bg-white rounded-full shadow-md transform transition-transform duration-200" :class="settings.autoRotate ? 'translate-x-6' : 'translate-x-0.5'"></div>
                        </button>
                        <span class="text-sm text-slate-600 flex items-center gap-2">
                            {{ settings.autoRotate ? 'Activé' : 'Désactivé' }}
                            <span v-if="settings.autoRotate && settings.displayMode === 'globe'" class="inline-block animate-spin text-sm select-none origin-center" style="animation-duration: 4s;">🌐</span>
                        </span>
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium text-slate-700">Vitesse de rotation</label>
                    <input 
                        v-model.number="settings.rotationSpeed" 
                        type="range" 
                        min="0.1" 
                        max="5" 
                        step="0.1" 
                        class="w-full"
                    >
                    <span class="text-sm text-slate-600">{{ settings.rotationSpeed }}x</span>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium text-slate-700">Afficher les points</label>
                    <div class="flex items-center gap-3">
                        <button 
                            @click="settings.showPoints = !settings.showPoints"
                            class="w-12 h-6 rounded-full transition-colors duration-200"
                            :class="settings.showPoints ? 'bg-indigo-500' : 'bg-slate-300'"
                        >
                            <div class="w-5 h-5 bg-white rounded-full shadow-md transform transition-transform duration-200" :class="settings.showPoints ? 'translate-x-6' : 'translate-x-0.5'"></div>
                        </button>
                        <span class="text-sm text-slate-600">{{ settings.showPoints ? 'Activé' : 'Désactivé' }}</span>
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium text-slate-700">Afficher les lignes</label>
                    <div class="flex items-center gap-3">
                        <button 
                            @click="settings.showLines = !settings.showLines"
                            class="w-12 h-6 rounded-full transition-colors duration-200"
                            :class="settings.showLines ? 'bg-indigo-500' : 'bg-slate-300'"
                        >
                            <div class="w-5 h-5 bg-white rounded-full shadow-md transform transition-transform duration-200" :class="settings.showLines ? 'translate-x-6' : 'translate-x-0.5'"></div>
                        </button>
                        <span class="text-sm text-slate-600">{{ settings.showLines ? 'Activé' : 'Désactivé' }}</span>
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium text-slate-700">Mode nuit</label>
                    <div class="flex items-center gap-3">
                        <button 
                            @click="settings.nightMode = !settings.nightMode"
                            class="w-12 h-6 rounded-full transition-colors duration-200"
                            :class="settings.nightMode ? 'bg-indigo-500' : 'bg-slate-300'"
                        >
                            <div class="w-5 h-5 bg-white rounded-full shadow-md transform transition-transform duration-200" :class="settings.nightMode ? 'translate-x-6' : 'translate-x-0.5'"></div>
                        </button>
                        <span class="text-sm text-slate-600">{{ settings.nightMode ? 'Activé' : 'Désactivé' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Map Container -->
        <div 
            :class="settings.nightMode ? 'bg-slate-950 border-slate-800' : 'bg-white border-slate-100'"
            class="rounded-2xl shadow-lg shadow-slate-200/50 border overflow-hidden relative" 
            style="height: 600px;"
        >
            <div ref="globeContainer" class="w-full h-full relative block" style="min-height: 600px; min-width: 100%;"></div>
            
            <!-- Loading overlay as a sibling, absolute to the parent container -->
            <div v-if="loading" :class="settings.nightMode ? 'bg-slate-950' : 'bg-slate-50'" class="absolute inset-0 flex items-center justify-center z-50">
                <div class="text-center">
                    <div class="w-12 h-12 border-4 border-indigo-500 border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
                    <p :class="settings.nightMode ? 'text-slate-400' : 'text-slate-600'">Chargement de la carte...</p>
                </div>
            </div>
        </div>

        <!-- Location Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/30">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                            <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-slate-800">{{ totalLocations }}</div>
                        <div class="text-sm text-slate-500">Emplacements actifs</div>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/30">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-slate-800">{{ onlineLocations }}</div>
                        <div class="text-sm text-slate-500">En ligne</div>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/30">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                            <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-slate-800">{{ recentActivity }}</div>
                        <div class="text-sm text-slate-500">Activité récente</div>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                            <path d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-slate-800">{{ countries }}</div>
                        <div class="text-sm text-slate-500">Pays couverts</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, watch } from 'vue';
import Globe from 'globe.gl';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import mapboxgl from 'mapbox-gl';
import 'mapbox-gl/dist/mapbox-gl.css';
import axios from 'axios';

const globeContainer = ref(null);
const loading = ref(true);
const showSettings = ref(false);
const selectedLocation = ref('');

const settings = ref({
    displayMode: 'globe',
    autoRotate: false,
    rotationSpeed: 1,
    showPoints: true,
    showLines: true,
    nightMode: false
});

const buildings = ref([]);
const citiesList = ref([]);
const cityCoordinates = ref({});

const totalLocations = ref(0);
const onlineLocations = ref(0);
const recentActivity = ref(0);
const countries = ref(0);

let globeInstance = null; // Mapbox Globe Map instance
let mapInstance = null; // Leaflet Flat Map instance
let satelliteInstance = null; // Mapbox Satellite Map instance
let mapMarkers = [];
let mapPolylines = [];
let rotationInterval = null; // requestAnimationFrame token

// Curated architectural photos for buildings/hotels
const getBuildingPhoto = (id, type) => {
    const buildingPhotos = [
        'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=400&q=80',
        'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=400&q=80',
        'https://images.unsplash.com/photo-1554469384-e58fac16e23a?auto=format&fit=crop&w=400&q=80',
        'https://images.unsplash.com/photo-1577495508048-b635879837f1?auto=format&fit=crop&w=400&q=80',
        'https://images.unsplash.com/photo-1479839672679-a784b2ec9353?auto=format&fit=crop&w=400&q=80'
    ];
    const hotelPhotos = [
        'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=400&q=80',
        'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?auto=format&fit=crop&w=400&q=80',
        'https://images.unsplash.com/photo-1540541338287-41700207dee6?auto=format&fit=crop&w=400&q=80',
        'https://images.unsplash.com/photo-1445019980597-93fa8acb246c?auto=format&fit=crop&w=400&q=80'
    ];
    if (type === 'hotel') {
        return hotelPhotos[id % hotelPhotos.length];
    }
    return buildingPhotos[id % buildingPhotos.length];
};

const cleanupGlobe = () => {
    stopGlobeAutoRotation();
    if (globeInstance) {
        try {
            if (globeInstance._docClickCleanup) {
                globeInstance._docClickCleanup();
            }
            if (typeof globeInstance.htmlElementsData === 'function') {
                globeInstance.htmlElementsData([]);
            }
            if (typeof globeInstance.pointsData === 'function') {
                globeInstance.pointsData([]);
            }
            if (typeof globeInstance.arcsData === 'function') {
                globeInstance.arcsData([]);
            }
            if (typeof globeInstance === 'function') {
                globeInstance();
            }
        } catch (error) {
            console.error('Error during globe cleanup:', error);
        }
        globeInstance = null;
    }
};

const toggleSettings = () => {
    showSettings.value = !showSettings.value;
};

const centerOnLocation = () => {
    if (!selectedLocation.value) return;
    
    const coords = cityCoordinates.value[selectedLocation.value];
    if (!coords || (coords.lat === 0 && coords.lng === 0)) return;
    
    if (settings.value.displayMode === 'map' && mapInstance) {
        mapInstance.setView([coords.lat, coords.lng], 12);
    } else if (settings.value.displayMode === 'globe' && globeInstance) {
        if (typeof globeInstance.pointOfView === 'function') {
            globeInstance.pointOfView({ lat: coords.lat, lng: coords.lng, altitude: 1.2 }, 1500);
        }
    } else if (settings.value.displayMode === 'satellite' && satelliteInstance) {
        if (satelliteInstance instanceof mapboxgl.Map) {
            satelliteInstance.flyTo({
                center: [coords.lng, coords.lat],
                zoom: 18,
                pitch: 45,
                bearing: 0,
                speed: 1.2
            });
        } else {
            satelliteInstance.setView([coords.lat, coords.lng], 18);
        }
    }
};

const updateDataFromBuildings = () => {
    // 1. Update KPIs
    totalLocations.value = buildings.value.length;
    onlineLocations.value = buildings.value.filter(b => b.statut === 'Actif').length;
    
    // Recent activity: buildings created within the last 30 days
    const thirtyDaysAgo = new Date();
    thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);
    recentActivity.value = buildings.value.filter(b => {
        if (!b.created_at) return false;
        const createdAt = new Date(b.created_at);
        return createdAt >= thirtyDaysAgo;
    }).length;
    
    // Countries covered
    const uniqueCountries = new Set(
        buildings.value
            .map(b => b.pays)
            .filter(p => p && p.trim() !== '')
    );
    countries.value = uniqueCountries.size;

    // 2. City select dropdown options & coordinates
    const citiesMap = {};
    buildings.value.forEach(b => {
        if (b.ville && b.ville.trim() !== '') {
            const cityName = b.ville.trim();
            const lat = parseFloat(b.latitude);
            const lng = parseFloat(b.longitude);
            
            if (!isNaN(lat) && !isNaN(lng) && lat !== 0 && lng !== 0) {
                if (!citiesMap[cityName] || (citiesMap[cityName].lat === 0 && citiesMap[cityName].lng === 0)) {
                    citiesMap[cityName] = { lat, lng };
                }
            } else if (!citiesMap[cityName]) {
                citiesMap[cityName] = { lat: 0, lng: 0 };
            }
        }
    });
    
    cityCoordinates.value = citiesMap;
    citiesList.value = Object.keys(citiesMap).sort();
};

const fetchBuildings = async () => {
    try {
        const response = await axios.get('/api/batiments');
        buildings.value = response.data;
        updateDataFromBuildings();
        
        // Update data based on current active display mode
        if (settings.value.displayMode === 'globe' && globeInstance) {
            updateGlobeData();
        } else if (settings.value.displayMode === 'map' && mapInstance) {
            updateMapData();
        } else if (settings.value.displayMode === 'satellite' && satelliteInstance) {
            updateSatelliteMapData();
        }
    } catch (error) {
        console.error('Error fetching buildings:', error);
    }
};

const refreshLocations = () => {
    loading.value = true;
    fetchBuildings().finally(() => {
        loading.value = false;
    });
};

const generateLocationData = () => {
    const locations = [];
    buildings.value.forEach(b => {
        const lat = parseFloat(b.latitude);
        const lng = parseFloat(b.longitude);
        if (!isNaN(lat) && !isNaN(lng) && lat !== 0 && lng !== 0) {
            const type = b.type_batiment?.toLowerCase().includes('hotel') ? 'hotel' : 'building';
            locations.push({
                id: b.id,
                lat: lat,
                lng: lng,
                size: 1.5,
                color: settings.value.nightMode ? '#60a5fa' : '#3b82f6',
                name: b.nom,
                type: type,
                statut: b.statut,
                ville: b.ville,
                pays: b.pays,
                agency_name: b.agency_name,
                etages: b.etages,
                appartements: b.appartements
            });
        }
    });
    return locations;
};

// Snapchat-like Mapbox pin markers and popups helper
const updateMapboxMarkers = (instance, locations) => {
    mapMarkers.forEach(marker => marker.remove());
    mapMarkers = [];
    
    if (!settings.value.showPoints) return;
    
    locations.forEach(location => {
        const markerElement = document.createElement('div');
        markerElement.className = 'custom-building-pin';
        markerElement.style.cursor = 'pointer';
        
        const svgIcon = location.type === 'hotel' 
            ? `<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                 <path d="M3 21h18"></path>
                 <path d="M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16"></path>
                 <path d="M9 9h6"></path>
                 <path d="M9 13h6"></path>
                 <path d="M9 17h6"></path>
               </svg>`
            : `<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                 <rect x="4" y="2" width="16" height="20" rx="2" ry="2"></rect>
                 <line x1="9" y1="22" x2="9" y2="16"></line>
                 <line x1="15" y1="22" x2="15" y2="16"></line>
                 <line x1="9" y1="16" x2="15" y2="16"></line>
                 <path d="M8 6h.01"></path>
                 <path d="M16 6h.01"></path>
                 <path d="M8 10h.01"></path>
                 <path d="M16 10h.01"></path>
               </svg>`;
               
        const photoUrl = getBuildingPhoto(location.id, location.type);
        
        markerElement.innerHTML = `
            <div style="
                display: flex;
                flex-direction: column;
                align-items: center;
                filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.35));
                transform: scale(0.9);
                transition: transform 0.2s ease;
            " onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(0.9)'">
                <div style="
                    width: 44px;
                    height: 44px;
                    border-radius: 12px 12px 0 12px;
                    transform: rotate(45deg);
                    background: linear-gradient(135deg, ${location.type === 'hotel' ? '#ff9f43' : '#4834d4'}, ${location.type === 'hotel' ? '#ee5253' : '#686de0'});
                    border: 2.5px solid white;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                ">
                    <div style="transform: rotate(-45deg); display: flex; align-items: center; justify-content: center;">
                        ${svgIcon}
                    </div>
                </div>
                <div style="
                    width: 8px;
                    height: 8px;
                    border-radius: 50%;
                    background: rgba(0,0,0,0.4);
                    margin-top: 2px;
                    filter: blur(1px);
                "></div>
            </div>
        `;
        
        const popupHtml = `
            <div style="
                width: 250px;
                background: #ffffff;
                border-radius: 14px;
                overflow: hidden;
                box-shadow: 0 8px 20px rgba(0,0,0,0.15);
                font-family: 'Inter', system-ui, -apple-system, sans-serif;
                color: #1e293b;
            ">
                <div style="
                    width: 100%;
                    height: 110px;
                    background-image: url('${photoUrl}');
                    background-size: cover;
                    background-position: center;
                    position: relative;
                ">
                    <span style="
                        position: absolute;
                        top: 8px;
                        right: 8px;
                        background: ${location.statut === 'Actif' ? '#ecfdf5' : '#fffbeb'};
                        color: ${location.statut === 'Actif' ? '#065f46' : '#92400e'};
                        font-size: 9px;
                        font-weight: 700;
                        padding: 3px 7px;
                        border-radius: 9999px;
                        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
                        text-transform: uppercase;
                    ">
                        ${location.statut || 'Actif'}
                    </span>
                </div>
                <div style="padding: 12px; display: flex; flex-direction: column; gap: 6px;">
                    <h4 style="
                        margin: 0;
                        font-size: 14px;
                        font-weight: 700;
                        color: #0f172a;
                        line-height: 1.2;
                    ">${location.name}</h4>
                    <p style="
                        margin: 0;
                        font-size: 11px;
                        color: #64748b;
                        display: flex;
                        align-items: center;
                        gap: 3px;
                    ">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        ${location.ville || ''}, ${location.pays || ''}
                    </p>
                    <div style="
                        margin-top: 4px;
                        border-top: 1.5px solid #f1f5f9;
                        padding-top: 8px;
                        display: flex;
                        flex-direction: column;
                        gap: 3px;
                    ">
                        <div style="font-size: 11px; color: #64748b;">
                            <span style="font-weight: 600; color: #475569;">Géré par :</span> 
                            <span style="color: #4f46e5; font-weight: 700;">${location.agency_name || 'Siège général'}</span>
                        </div>
                        <div style="display: flex; gap: 10px; font-size: 10px; color: #64748b; margin-top: 1px;">
                            <div><span style="font-weight: 600; color: #475569;">Étages :</span> ${location.etages || 0}</div>
                            <div><span style="font-weight: 600; color: #475569;">Apparts :</span> ${location.appartements || 0}</div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        const popup = new mapboxgl.Popup({ offset: 25, closeButton: false })
            .setHTML(popupHtml);
            
        const marker = new mapboxgl.Marker(markerElement)
            .setLngLat([location.lng, location.lat])
            .setPopup(popup)
            .addTo(instance);
            
        mapMarkers.push(marker);
    });
};

// Leaflet pin markers and popups helper
const updateLeafletMarkers = (instance, locations) => {
    mapMarkers.forEach(marker => instance.removeLayer(marker));
    mapPolylines.forEach(polyline => instance.removeLayer(polyline));
    mapMarkers = [];
    mapPolylines = [];
    
    if (!settings.value.showPoints) return;
    
    locations.forEach(location => {
        const svgIcon = location.type === 'hotel' 
            ? `<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                 <path d="M3 21h18"></path>
                 <path d="M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16"></path>
                 <path d="M9 9h6"></path>
                 <path d="M9 13h6"></path>
                 <path d="M9 17h6"></path>
               </svg>`
            : `<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                 <rect x="4" y="2" width="16" height="20" rx="2" ry="2"></rect>
                 <line x1="9" y1="22" x2="9" y2="16"></line>
                 <line x1="15" y1="22" x2="15" y2="16"></line>
                 <line x1="9" y1="16" x2="15" y2="16"></line>
                 <path d="M8 6h.01"></path>
                 <path d="M16 6h.01"></path>
                 <path d="M8 10h.01"></path>
                 <path d="M16 10h.01"></path>
               </svg>`;
               
        const photoUrl = getBuildingPhoto(location.id, location.type);
        
        const pinHtml = `
            <div style="
                display: flex;
                flex-direction: column;
                align-items: center;
                filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.35));
            ">
                <div style="
                    width: 44px;
                    height: 44px;
                    border-radius: 12px 12px 0 12px;
                    transform: rotate(45deg);
                    background: linear-gradient(135deg, ${location.type === 'hotel' ? '#ff9f43' : '#4834d4'}, ${location.type === 'hotel' ? '#ee5253' : '#686de0'});
                    border: 2.5px solid white;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                ">
                    <div style="transform: rotate(-45deg); display: flex; align-items: center; justify-content: center;">
                        ${svgIcon}
                    </div>
                </div>
            </div>
        `;
        
        const customIcon = L.divIcon({
            html: pinHtml,
            className: 'custom-building-pin',
            iconSize: [44, 44],
            iconAnchor: [22, 44],
            popupAnchor: [0, -44]
        });
        
        const popupHtml = `
            <div style="
                width: 250px;
                background: #ffffff;
                border-radius: 14px;
                overflow: hidden;
                box-shadow: 0 8px 20px rgba(0,0,0,0.15);
                font-family: 'Inter', system-ui, -apple-system, sans-serif;
                color: #1e293b;
            ">
                <div style="
                    width: 100%;
                    height: 110px;
                    background-image: url('${photoUrl}');
                    background-size: cover;
                    background-position: center;
                    position: relative;
                ">
                    <span style="
                        position: absolute;
                        top: 8px;
                        right: 8px;
                        background: ${location.statut === 'Actif' ? '#ecfdf5' : '#fffbeb'};
                        color: ${location.statut === 'Actif' ? '#065f46' : '#92400e'};
                        font-size: 9px;
                        font-weight: 700;
                        padding: 3px 7px;
                        border-radius: 9999px;
                        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
                        text-transform: uppercase;
                    ">
                        ${location.statut || 'Actif'}
                    </span>
                </div>
                <div style="padding: 12px; display: flex; flex-direction: column; gap: 6px;">
                    <h4 style="
                        margin: 0;
                        font-size: 14px;
                        font-weight: 700;
                        color: #0f172a;
                        line-height: 1.2;
                    ">${location.name}</h4>
                    <p style="
                        margin: 0;
                        font-size: 11px;
                        color: #64748b;
                        display: flex;
                        align-items: center;
                        gap: 3px;
                    ">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        ${location.ville || ''}, ${location.pays || ''}
                    </p>
                    <div style="
                        margin-top: 4px;
                        border-top: 1.5px solid #f1f5f9;
                        padding-top: 8px;
                        display: flex;
                        flex-direction: column;
                        gap: 3px;
                    ">
                        <div style="font-size: 11px; color: #64748b;">
                            <span style="font-weight: 600; color: #475569;">Géré par :</span> 
                            <span style="color: #4f46e5; font-weight: 700;">${location.agency_name || 'Siège général'}</span>
                        </div>
                        <div style="display: flex; gap: 10px; font-size: 10px; color: #64748b; margin-top: 1px;">
                            <div><span style="font-weight: 600; color: #475569;">Étages :</span> ${location.etages || 0}</div>
                            <div><span style="font-weight: 600; color: #475569;">Apparts :</span> ${location.appartements || 0}</div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        const marker = L.marker([location.lat, location.lng], { icon: customIcon })
            .bindPopup(popupHtml, { maxWidth: 280, minWidth: 250, className: 'snap-popup' })
            .addTo(instance);
            
        mapMarkers.push(marker);
    });
};

let activePopupEl = null;

const setupGlobeElementAccessor = () => {
    if (!globeInstance) return;

    // Document click to close active popup
    const handleDocClick = () => {
        if (activePopupEl) {
            activePopupEl.style.display = 'none';
            activePopupEl = null;
        }
    };
    document.addEventListener('click', handleDocClick);
    
    globeInstance._docClickCleanup = () => {
        document.removeEventListener('click', handleDocClick);
    };

    globeInstance.htmlElement(loc => {
        const el = document.createElement('div');
        el.style.position = 'relative';
        el.style.pointerEvents = 'auto';

        const svgIcon = loc.type === 'hotel' 
            ? `<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                 <path d="M3 21h18"></path>
                 <path d="M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16"></path>
                 <path d="M9 9h6"></path>
                 <path d="M9 13h6"></path>
                 <path d="M9 17h6"></path>
               </svg>`
            : `<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                 <rect x="4" y="2" width="16" height="20" rx="2" ry="2"></rect>
                 <line x1="9" y1="22" x2="9" y2="16"></line>
                 <line x1="15" y1="22" x2="15" y2="16"></line>
                 <line x1="9" y1="16" x2="15" y2="16"></line>
                 <path d="M8 6h.01"></path>
                 <path d="M16 6h.01"></path>
                 <path d="M8 10h.01"></path>
                 <path d="M16 10h.01"></path>
               </svg>`;

        const photoUrl = getBuildingPhoto(loc.id, loc.type);

        // Pin element
        const pinEl = document.createElement('div');
        pinEl.style.cursor = 'pointer';
        pinEl.innerHTML = `
            <div style="
                display: flex;
                flex-direction: column;
                align-items: center;
                filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.35));
                transform: scale(0.9);
                transition: transform 0.2s ease;
            " onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(0.9)'">
                <div style="
                    width: 44px;
                    height: 44px;
                    border-radius: 12px 12px 0 12px;
                    transform: rotate(45deg);
                    background: linear-gradient(135deg, ${loc.type === 'hotel' ? '#ff9f43' : '#4834d4'}, ${loc.type === 'hotel' ? '#ee5253' : '#686de0'});
                    border: 2.5px solid white;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                ">
                    <div style="transform: rotate(-45deg); display: flex; align-items: center; justify-content: center;">
                        ${svgIcon}
                    </div>
                </div>
                <div style="
                    width: 8px;
                    height: 8px;
                    border-radius: 50%;
                    background: rgba(0,0,0,0.4);
                    margin-top: 2px;
                    filter: blur(1px);
                "></div>
            </div>
        `;
        el.appendChild(pinEl);

        // Popup Card element
        const popupEl = document.createElement('div');
        popupEl.style.position = 'absolute';
        popupEl.style.bottom = '55px';
        popupEl.style.left = '50%';
        popupEl.style.transform = 'translateX(-50%)';
        popupEl.style.display = 'none';
        popupEl.style.zIndex = '9999';
        popupEl.style.pointerEvents = 'auto';

        popupEl.innerHTML = `
            <div style="
                width: 250px;
                background: #ffffff;
                border-radius: 14px;
                overflow: hidden;
                box-shadow: 0 8px 20px rgba(0,0,0,0.15);
                font-family: 'Inter', system-ui, -apple-system, sans-serif;
                color: #1e293b;
            ">
                <div style="
                    width: 100%;
                    height: 110px;
                    background-image: url('${photoUrl}');
                    background-size: cover;
                    background-position: center;
                    position: relative;
                ">
                    <span style="
                        position: absolute;
                        top: 8px;
                        right: 8px;
                        background: ${loc.statut === 'Actif' ? '#ecfdf5' : '#fffbeb'};
                        color: ${loc.statut === 'Actif' ? '#065f46' : '#92400e'};
                        font-size: 9px;
                        font-weight: 700;
                        padding: 3px 7px;
                        border-radius: 9999px;
                        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
                        text-transform: uppercase;
                    ">
                        ${loc.statut || 'Actif'}
                    </span>
                </div>
                <div style="padding: 12px; display: flex; flex-direction: column; gap: 6px;">
                    <h4 style="
                        margin: 0;
                        font-size: 14px;
                        font-weight: 700;
                        color: #0f172a;
                        line-height: 1.2;
                    ">${loc.name}</h4>
                    <p style="
                        margin: 0;
                        font-size: 11px;
                        color: #64748b;
                        display: flex;
                        align-items: center;
                        gap: 3px;
                    ">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        ${loc.ville || ''}, ${loc.pays || ''}
                    </p>
                    <div style="
                        margin-top: 4px;
                        border-top: 1.5px solid #f1f5f9;
                        padding-top: 8px;
                        display: flex;
                        flex-direction: column;
                        gap: 3px;
                    ">
                        <div style="font-size: 11px; color: #64748b;">
                            <span style="font-weight: 600; color: #475569;">Géré par :</span> 
                            <span style="color: #4f46e5; font-weight: 700;">${loc.agency_name || 'Siège général'}</span>
                        </div>
                        <div style="display: flex; gap: 10px; font-size: 10px; color: #64748b; margin-top: 1px;">
                            <div><span style="font-weight: 600; color: #475569;">Étages :</span> ${loc.etages || 0}</div>
                            <div><span style="font-weight: 600; color: #475569;">Apparts :</span> ${loc.appartements || 0}</div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        el.appendChild(popupEl);

        pinEl.addEventListener('click', (e) => {
            e.stopPropagation();
            const isShowing = popupEl.style.display === 'block';

            if (activePopupEl && activePopupEl !== popupEl) {
                activePopupEl.style.display = 'none';
            }

            if (isShowing) {
                popupEl.style.display = 'none';
                activePopupEl = null;
            } else {
                popupEl.style.display = 'block';
                activePopupEl = popupEl;
            }
        });

        popupEl.addEventListener('click', (e) => {
            e.stopPropagation();
        });

        return el;
    });
};

const updateGlobeData = () => {
    if (!globeInstance) return;
    const locations = generateLocationData();

    if (settings.value.showPoints) {
        globeInstance.htmlElementsData(locations);
    } else {
        globeInstance.htmlElementsData([]);
    }

    if (settings.value.showLines) {
        const arcs = [];
        for (let i = 0; i < locations.length - 1; i++) {
            arcs.push({
                startLat: locations[i].lat,
                startLng: locations[i].lng,
                endLat: locations[i + 1].lat,
                endLng: locations[i + 1].lng,
                color: settings.value.nightMode ? '#60a5fa' : '#3b82f6'
            });
        }
        globeInstance
            .arcsData(arcs)
            .arcColor('color')
            .arcAltitude(0.15)
            .arcStroke(0.6);
    } else {
        globeInstance.arcsData([]);
    }
};

const updateMapData = () => {
    if (!mapInstance) return;
    const locations = generateLocationData();
    updateLeafletMarkers(mapInstance, locations);
};

const updateSatelliteMapData = () => {
    if (!satelliteInstance) return;
    const locations = generateLocationData();
    if (satelliteInstance instanceof mapboxgl.Map) {
        updateMapboxMarkers(satelliteInstance, locations);
    } else {
        updateLeafletMarkers(satelliteInstance, locations);
    }
};

const startGlobeAutoRotation = () => {
    stopGlobeAutoRotation();
    if (!globeInstance || !settings.value.autoRotate) return;

    const rotationSpeed = settings.value.rotationSpeed || 1;
    const secondsPerRevolution = 120 / rotationSpeed;
    const degreesPerSecond = 360 / secondsPerRevolution;

    let lastTime = performance.now();
    
    const rotate = (time) => {
        if (!globeInstance || !settings.value.autoRotate) return;
        
        const zoom = typeof globeInstance.getZoom === 'function' ? globeInstance.getZoom() : 1.8;
        if (zoom < 5) {
            const delta = (time - lastTime) / 1000;
            const center = globeInstance.getCenter();
            center.lng = (center.lng + degreesPerSecond * delta) % 360;
            globeInstance.setCenter(center);
        }
        
        lastTime = time;
        rotationInterval = requestAnimationFrame(rotate);
    };
    
    rotationInterval = requestAnimationFrame(rotate);
};

const stopGlobeAutoRotation = () => {
    if (rotationInterval) {
        cancelAnimationFrame(rotationInterval);
        rotationInterval = null;
    }
};

const initGlobe = () => {
    if (!globeContainer.value) {
        console.error('Globe container not found');
        loading.value = false;
        return;
    }

    const container = globeContainer.value;
    const width = container.clientWidth || container.offsetWidth || 800;
    const height = container.clientHeight || container.offsetHeight || 600;

    console.log('Initializing Globe.gl with dimensions:', width, height);

    try {
        // Clear existing map/globe instances
        if (mapInstance) {
            mapInstance.remove();
            mapInstance = null;
        }
        if (satelliteInstance) {
            satelliteInstance.remove();
            satelliteInstance = null;
        }
        cleanupGlobe();

        // Clear container DOM
        container.innerHTML = '';

        // Initialize Globe.gl using the imported Globe
        globeInstance = Globe()(container)
            .width(width)
            .height(height)
            .globeImageUrl(settings.value.nightMode 
                ? 'https://raw.githubusercontent.com/vasturiano/three-globe/master/example/img/earth-night.jpg'
                : 'https://raw.githubusercontent.com/vasturiano/three-globe/master/example/img/earth-blue-marble.jpg'
            )
            .bumpImageUrl('https://raw.githubusercontent.com/vasturiano/three-globe/master/example/img/earth-topology.png')
            .backgroundImageUrl('https://raw.githubusercontent.com/vasturiano/three-globe/master/example/img/night-sky.png')
            .backgroundColor(settings.value.nightMode ? '#020617' : '#ffffff')
            .pointOfView({ lat: 20, lng: 0, altitude: 2.8 });

        // Configure OrbitControls directly for autoRotate
        const controls = globeInstance.controls();
        if (controls) {
            controls.autoRotate = settings.value.autoRotate;
            controls.autoRotateSpeed = settings.value.rotationSpeed || 1;
            controls.minDistance = 1.2;
            controls.maxDistance = 10;
        }

        // Set up the HTML elements accessor configuration
        setupGlobeElementAccessor();

        // Load data
        updateGlobeData();
        loading.value = false;

    } catch (error) {
        console.error('Error initializing Globe.gl:', error);
        initMap();
    }
};

// Initialize satellite map with 3D buildings using Mapbox GL JS
const initSatelliteMap = () => {
    if (!globeContainer.value) {
        console.error('Satellite map container not found');
        return;
    }

    const container = globeContainer.value;
    const width = container.clientWidth || container.offsetWidth || 800;
    const height = container.clientHeight || container.offsetHeight || 600;

    console.log('Initializing satellite map with 3D buildings, dimensions:', width, height);

    try {
        // Clear existing instances
        cleanupGlobe();
        if (mapInstance) {
            mapInstance.remove();
            mapInstance = null;
        }
        if (satelliteInstance) {
            satelliteInstance.remove();
            satelliteInstance = null;
        }

        // Clear container DOM
        container.innerHTML = '';

        // Configure Mapbox Access Token
        mapboxgl.accessToken = import.meta.env.VITE_MAPBOX_ACCESS_TOKEN || 'YOUR_MAPBOX_ACCESS_TOKEN';

        // Initialize Mapbox GL JS map with satellite imagery and 3D buildings
        satelliteInstance = new mapboxgl.Map({
            container: container,
            style: 'mapbox://styles/mapbox/satellite-v9',
            center: [0, 20],
            zoom: 2,
            pitch: 45,
            bearing: 0,
            antialias: true,
            minZoom: 0,
            maxZoom: 22
        });

        // Add 3D buildings layer with white style like Snapchat
        satelliteInstance.on('load', () => {
            satelliteInstance.addLayer({
                'id': '3d-buildings',
                'source': 'composite',
                'source-layer': 'building',
                'filter': ['==', 'extrude', 'true'],
                'type': 'fill-extrusion',
                'minzoom': 15,
                'paint': {
                    'fill-extrusion-color': '#ffffff',
                    'fill-extrusion-height': ['get', 'height'],
                    'fill-extrusion-base': ['get', 'min_height'],
                    'fill-extrusion-opacity': 0.9
                }
            });

            console.log('3D buildings layer added');
            updateSatelliteMapData();
            loading.value = false;
        });

        satelliteInstance.on('error', (error) => {
            console.error('Mapbox error:', error);
            console.log('Falling back to Leaflet satellite map');
            initSatelliteMapFallback();
        });

    } catch (error) {
        console.error('Error initializing satellite map:', error);
        initSatelliteMapFallback();
    }
};

// Fallback to Leaflet if Mapbox fails
const initSatelliteMapFallback = () => {
    try {
        satelliteInstance = L.map(globeContainer.value, {
            center: [20, 0],
            zoom: 3,
            minZoom: 0,
            maxZoom: 22,
            zoomControl: true
        });

        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles © Esri',
            maxZoom: 22
        }).addTo(satelliteInstance);

        updateSatelliteMapData();
        loading.value = false;
    } catch (error) {
        console.error('Error initializing fallback satellite map:', error);
        loading.value = false;
    }
};

const initMap = () => {
    if (!globeContainer.value) {
        console.error('Map container not found');
        return;
    }

    const container = globeContainer.value;
    const width = container.clientWidth || container.offsetWidth || 800;
    const height = container.clientHeight || container.offsetHeight || 600;

    console.log('Initializing flat map with dimensions:', width, height);

    try {
        cleanupGlobe();
        if (satelliteInstance) {
            satelliteInstance.remove();
            satelliteInstance = null;
        }

        // Clear container DOM
        container.innerHTML = '';

        // Initialize Leaflet map
        mapInstance = L.map(container, {
            center: [20, 0],
            zoom: 2,
            minZoom: 1,
            maxZoom: 18,
            zoomControl: true
        });

        // Add tile layer
        const tileUrl = settings.value.nightMode
            ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png'
            : 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
        
        L.tileLayer(tileUrl, {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(mapInstance);

        console.log('Map initialized successfully');
        updateMapData();
        loading.value = false;
    } catch (error) {
        console.error('Error initializing map:', error);
        loading.value = false;
    }
};

// Watch for display mode changes
watch(() => settings.value.displayMode, (newMode) => {
    loading.value = true;
    console.log('Switching to mode:', newMode);
    
    setTimeout(() => {
        if (newMode === 'globe') {
            initGlobe();
        } else if (newMode === 'map') {
            initMap();
        } else if (newMode === 'satellite') {
            initSatelliteMap();
        }
    }, 100);
});

// Watch for settings changes
watch(() => settings.value.nightMode, () => {
    if (settings.value.displayMode === 'globe' && globeInstance) {
        globeInstance
            .globeImageUrl(settings.value.nightMode 
                ? 'https://raw.githubusercontent.com/vasturiano/three-globe/master/example/img/earth-night.jpg'
                : 'https://raw.githubusercontent.com/vasturiano/three-globe/master/example/img/earth-blue-marble.jpg'
            )
            .backgroundColor(settings.value.nightMode ? '#020617' : '#ffffff');
        updateGlobeData();
    } else if (settings.value.displayMode === 'map' && mapInstance) {
        // Remove existing tile layer and add new one
        mapInstance.eachLayer((layer) => {
            if (layer instanceof L.TileLayer) {
                mapInstance.removeLayer(layer);
            }
        });
        
        const tileUrl = settings.value.nightMode
            ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png'
            : 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
        
        L.tileLayer(tileUrl, {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(mapInstance);
        
        updateMapData();
    }
});

watch(() => settings.value.autoRotate, (newVal) => {
    if (settings.value.displayMode === 'globe' && globeInstance && typeof globeInstance.controls === 'function') {
        const controls = globeInstance.controls();
        if (controls) {
            controls.autoRotate = newVal;
        }
    } else if (newVal) {
        startGlobeAutoRotation();
    } else {
        stopGlobeAutoRotation();
    }
});

watch(() => settings.value.rotationSpeed, (newVal) => {
    if (settings.value.displayMode === 'globe' && globeInstance && typeof globeInstance.controls === 'function') {
        const controls = globeInstance.controls();
        if (controls) {
            controls.autoRotateSpeed = newVal;
        }
    } else if (settings.value.autoRotate) {
        startGlobeAutoRotation();
    }
});

watch(() => settings.value.showPoints, () => {
    if (settings.value.displayMode === 'globe') {
        updateGlobeData();
    } else if (settings.value.displayMode === 'map') {
        updateMapData();
    } else if (settings.value.displayMode === 'satellite') {
        updateSatelliteMapData();
    }
});

watch(() => settings.value.showLines, () => {
    if (settings.value.displayMode === 'globe') {
        updateGlobeData();
    } else if (settings.value.displayMode === 'map') {
        updateMapData();
    } else if (settings.value.displayMode === 'satellite') {
        updateSatelliteMapData();
    }
});

const handleResize = () => {
    if (globeInstance && globeContainer.value && settings.value.displayMode === 'globe') {
        const container = globeContainer.value;
        const width = container.clientWidth || container.offsetWidth || 800;
        const height = container.clientHeight || container.offsetHeight || 600;
        if (typeof globeInstance.width === 'function') {
            globeInstance.width(width).height(height);
        }
    }
};

onMounted(() => {
    window.addEventListener('resize', handleResize);
    fetchBuildings();
    
    setTimeout(() => {
        if (settings.value.displayMode === 'globe') {
            initGlobe();
        } else if (settings.value.displayMode === 'map') {
            initMap();
        } else if (settings.value.displayMode === 'satellite') {
            initSatelliteMap();
        }
    }, 200);
});

onUnmounted(() => {
    window.removeEventListener('resize', handleResize);
    stopGlobeAutoRotation();
    cleanupGlobe();
    if (mapInstance) {
        mapInstance.remove();
        mapInstance = null;
    }
    if (satelliteInstance) {
        satelliteInstance.remove();
        satelliteInstance = null;
    }
});
</script>
