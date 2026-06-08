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
                    <option value="Paris">Paris</option>
                    <option value="New York">New York</option>
                    <option value="Tokyo">Tokyo</option>
                    <option value="London">Londres</option>
                    <option value="Dubai">Dubaï</option>
                    <option value="Singapore">Singapour</option>
                    <option value="Sydney">Sydney</option>
                    <option value="Los Angeles">Los Angeles</option>
                    <option value="Berlin">Berlin</option>
                    <option value="Shanghai">Shanghai</option>
                    <option value="Mumbai">Mumbai</option>
                    <option value="São Paulo">São Paulo</option>
                    <option value="Moscow">Moscou</option>
                    <option value="Toronto">Toronto</option>
                    <option value="Hong Kong">Hong Kong</option>
                    <option value="Douala">Douala</option>
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

// City coordinates for centering
const cityCoordinates = {
    'Paris': { lat: 48.8566, lng: 2.3522 },
    'New York': { lat: 40.7128, lng: -74.0060 },
    'Tokyo': { lat: 35.6762, lng: 139.6503 },
    'London': { lat: 51.5074, lng: -0.1278 },
    'Dubai': { lat: 25.2048, lng: 55.2708 },
    'Singapore': { lat: 1.3521, lng: 103.8198 },
    'Sydney': { lat: -33.8688, lng: 151.2093 },
    'Los Angeles': { lat: 34.0522, lng: -118.2437 },
    'Berlin': { lat: 52.5200, lng: 13.4050 },
    'Shanghai': { lat: 31.2304, lng: 121.4737 },
    'Mumbai': { lat: 19.0760, lng: 72.8777 },
    'São Paulo': { lat: -23.5505, lng: -46.6333 },
    'Moscow': { lat: 55.7558, lng: 37.6173 },
    'Toronto': { lat: 43.6532, lng: -79.3832 },
    'Hong Kong': { lat: 22.3193, lng: 114.1694 },
    'Douala': { lat: 4.0483, lng: 9.7043 }
};

const totalLocations = ref(156);
const onlineLocations = ref(142);
const recentActivity = ref(28);
const countries = ref(23);

let globeInstance = null;
let mapInstance = null;
let satelliteInstance = null;
let mapMarkers = [];
let mapPolylines = [];

const cleanupGlobe = () => {
    if (globeInstance) {
        try {
            // Stop autoRotate
            const controls = globeInstance.controls();
            if (controls) {
                controls.autoRotate = false;
            }
            // Clear HTML elements data & other datasets to cleanly detach them from Three.js scene
            globeInstance.htmlElementsData([]);
            globeInstance.pointsData([]);
            globeInstance.arcsData([]);
            
            // Destroy the globe.gl instance
            globeInstance();
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
    
    const coords = cityCoordinates[selectedLocation.value];
    if (!coords) return;
    
    if (settings.value.displayMode === 'map' && mapInstance) {
        mapInstance.setView([coords.lat, coords.lng], 12);
    } else if (settings.value.displayMode === 'globe' && globeInstance) {
        globeInstance.pointOfView({ lat: coords.lat, lng: coords.lng, altitude: 1.5 });
    } else if (settings.value.displayMode === 'satellite' && satelliteInstance) {
        if (satelliteInstance instanceof mapboxgl.Map) {
            satelliteInstance.flyTo({
                center: [coords.lng, coords.lat],
                zoom: 18, // Increased zoom to see buildings in detail
                pitch: 45,
                bearing: 0,
                speed: 1.2
            });
        } else {
            satelliteInstance.setView([coords.lat, coords.lng], 18);
        }
    }
};

const refreshLocations = () => {
    loading.value = true;
    // Simulate refresh
    setTimeout(() => {
        totalLocations.value = Math.floor(Math.random() * 50) + 150;
        onlineLocations.value = Math.floor(Math.random() * 30) + 130;
        recentActivity.value = Math.floor(Math.random() * 20) + 20;
        countries.value = Math.floor(Math.random() * 10) + 20;
        loading.value = false;
        
        // Update data based on current mode
        if (settings.value.displayMode === 'globe' && globeInstance) {
            updateGlobeData();
        } else if (settings.value.displayMode === 'map' && mapInstance) {
            updateMapData();
        } else if (settings.value.displayMode === 'satellite' && satelliteInstance) {
            updateSatelliteMapData();
        }
    }, 1000);
};

// Sample location data
const generateLocationData = () => {
    const locations = [];
    const cities = [
        { name: 'Paris', lat: 48.8566, lng: 2.3522, type: 'hotel' },
        { name: 'New York', lat: 40.7128, lng: -74.0060, type: 'building' },
        { name: 'Tokyo', lat: 35.6762, lng: 139.6503, type: 'hotel' },
        { name: 'London', lat: 51.5074, lng: -0.1278, type: 'building' },
        { name: 'Dubai', lat: 25.2048, lng: 55.2708, type: 'hotel' },
        { name: 'Singapore', lat: 1.3521, lng: 103.8198, type: 'building' },
        { name: 'Sydney', lat: -33.8688, lng: 151.2093, type: 'hotel' },
        { name: 'Los Angeles', lat: 34.0522, lng: -118.2437, type: 'building' },
        { name: 'Berlin', lat: 52.5200, lng: 13.4050, type: 'hotel' },
        { name: 'Shanghai', lat: 31.2304, lng: 121.4737, type: 'building' },
        { name: 'Mumbai', lat: 19.0760, lng: 72.8777, type: 'hotel' },
        { name: 'São Paulo', lat: -23.5505, lng: -46.6333, type: 'building' },
        { name: 'Moscow', lat: 55.7558, lng: 37.6173, type: 'hotel' },
        { name: 'Toronto', lat: 43.6532, lng: -79.3832, type: 'building' },
        { name: 'Hong Kong', lat: 22.3193, lng: 114.1694, type: 'hotel' },
        { name: 'Douala - Hotel 1', lat: 4.0483, lng: 9.7043, type: 'hotel' },
        { name: 'Douala - Hotel 2', lat: 4.0583, lng: 9.7143, type: 'hotel' },
        { name: 'Douala - Hotel 3', lat: 4.0383, lng: 9.6943, type: 'hotel' }
    ];

    for (let i = 0; i < Math.min(totalLocations.value, 50); i++) {
        const city = cities[i % cities.length];
        const offset = (Math.random() - 0.5) * 0.5;
        locations.push({
            lat: city.lat + offset,
            lng: city.lng + offset,
            size: Math.random() * 2 + 0.5,
            color: settings.value.nightMode ? '#60a5fa' : '#3b82f6',
            name: city.name,
            type: city.type
        });
    }

    return locations;
};

const updateGlobeData = () => {
    if (!globeInstance) return;
    
    const locations = generateLocationData();
    
    if (settings.value.showPoints) {
        globeInstance
            .pointsData(locations)
            .pointAltitude(0.02)
            .pointRadius('size')
            .pointColor('color');
    } else {
        globeInstance.pointsData([]);
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
            .arcAltitude(0.1)
            .arcStroke(0.5);
    } else {
        globeInstance.arcsData([]);
    }

    // Add HTML labels with images for hotels and buildings
    const htmlElements = locations.map(loc => ({
        lat: loc.lat,
        lng: loc.lng,
        html: `
            <div style="
                display: flex;
                flex-direction: column;
                align-items: center;
                pointer-events: none;
                z-index: 1000;
            ">
                <div style="
                    background: ${settings.value.nightMode ? 'rgba(30, 41, 59, 0.95)' : 'rgba(255, 255, 255, 0.95)'};
                    padding: 12px 16px;
                    border-radius: 12px;
                    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
                    margin-bottom: 12px;
                    text-align: center;
                    min-width: 120px;
                ">
                    <div style="
                        width: 48px;
                        height: 48px;
                        border-radius: 50%;
                        background: linear-gradient(135deg, ${loc.type === 'hotel' ? '#f59e0b' : '#3b82f6'}, ${loc.type === 'hotel' ? '#d97706' : '#2563eb'});
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        margin: 0 auto 8px;
                        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                    ">
                        <svg width="24" height="24" viewBox="0 0 16 16" fill="white" xmlns="http://www.w3.org/2000/svg">
                            ${loc.type === 'hotel' 
                                ? '<path d="M3 4h1v1H3V4zm2 0h1v1H5V4zm2 0h1v1H7V4zm2 0h1v1H9V4zm2 0h1v1h-1V4zm2 0h1v1h-1V4zM2 6h12v7H2V6zm1 1v5h10V7H3z" fill="currentColor"/>'
                                : '<path d="M2 2h12v12H2V2zm1 1v10h10V3H3zm2 2h6v6H5V5z" fill="currentColor"/>'
                            }
                        </svg>
                    </div>
                    <div style="
                        font-size: 14px;
                        font-weight: 700;
                        color: ${settings.value.nightMode ? '#e2e8f0' : '#1e293b'};
                        white-space: nowrap;
                        margin-bottom: 4px;
                    ">
                        ${loc.name}
                    </div>
                    <div style="
                        font-size: 11px;
                        color: ${settings.value.nightMode ? '#94a3b8' : '#64748b'};
                        font-weight: 500;
                    ">
                        ${loc.type === 'hotel' ? '🏨 Hôtel' : '🏢 Bâtiment'}
                    </div>
                </div>
                <div style="
                    width: 16px;
                    height: 16px;
                    border-radius: 50%;
                    background: #3b82f6;
                    border: 3px solid white;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
                "></div>
            </div>
        `
    }));

    globeInstance.htmlElementsData(htmlElements);
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

        // Initialize Mapbox GL JS map with satellite imagery and 3D buildings
        satelliteInstance = new mapboxgl.Map({
            container: container,
            style: 'mapbox://styles/mapbox/satellite-v9',
            center: [0, 20],
            zoom: 2,
            pitch: 45, // Tilt the map for 3D effect
            bearing: 0,
            antialias: true,
            minZoom: 0,
            maxZoom: 22 // Increased zoom level to see buildings in detail
        });

        // Add 3D buildings layer with white style like Snapchat
        satelliteInstance.on('load', () => {
            // Add 3D buildings layer
            satelliteInstance.addLayer({
                'id': '3d-buildings',
                'source': 'composite',
                'source-layer': 'building',
                'filter': ['==', 'extrude', 'true'],
                'type': 'fill-extrusion',
                'minzoom': 15,
                'paint': {
                    'fill-extrusion-color': '#ffffff', // White buildings like Snapchat
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
            // Fallback to Leaflet if Mapbox fails
            console.log('Falling back to Leaflet satellite map');
            initSatelliteMapFallback();
        });

    } catch (error) {
        console.error('Error initializing satellite map:', error);
        // Fallback to Leaflet if Mapbox fails
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
            maxZoom: 22, // Increased zoom level to see buildings in detail
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

// Update satellite map data
const updateSatelliteMapData = () => {
    if (!satelliteInstance) return;
    
    // Clear existing markers
    if (satelliteInstance instanceof mapboxgl.Map) {
        // Mapbox GL JS markers
        mapMarkers.forEach(marker => marker.remove());
    } else {
        // Leaflet markers (fallback)
        mapMarkers.forEach(marker => satelliteInstance.removeLayer(marker));
        mapPolylines.forEach(polyline => satelliteInstance.removeLayer(polyline));
    }
    mapMarkers = [];
    mapPolylines = [];

    const locations = generateLocationData();
    
    if (settings.value.showPoints) {
        locations.forEach(location => {
            // Create custom marker element
            const markerElement = document.createElement('div');
            markerElement.style.width = '48px';
            markerElement.style.height = '48px';
            markerElement.innerHTML = `
                <div style="
                    width: 48px;
                    height: 48px;
                    border-radius: 50%;
                    background: linear-gradient(135deg, ${location.type === 'hotel' ? '#f59e0b' : '#3b82f6'}, ${location.type === 'hotel' ? '#d97706' : '#2563eb'});
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
                    border: 3px solid white;
                ">
                    <svg width="24" height="24" viewBox="0 0 16 16" fill="white" xmlns="http://www.w3.org/2000/svg">
                        ${location.type === 'hotel' 
                            ? '<path d="M3 4h1v1H3V4zm2 0h1v1H5V4zm2 0h1v1H7V4zm2 0h1v1H9V4zm2 0h1v1h-1V4zm2 0h1v1h-1V4zM2 6h12v7H2V6zm1 1v5h10V7H3z" fill="currentColor"/>'
                            : '<path d="M2 2h12v12H2V2zm1 1v10h10V3H3zm2 2h6v6H5V5z" fill="currentColor"/>'
                        }
                    </svg>
                </div>
            `;

            if (satelliteInstance instanceof mapboxgl.Map) {
                // Mapbox GL JS marker
                const marker = new mapboxgl.Marker(markerElement)
                    .setLngLat([location.lng, location.lat])
                    .setPopup(new mapboxgl.Popup({ offset: 25 }).setHTML(`
                        <div style="
                            text-align: center;
                            padding: 8px;
                            min-width: 150px;
                            background: rgba(255, 255, 255, 0.95);
                            border-radius: 8px;
                        ">
                            <div style="
                                font-size: 14px;
                                font-weight: 700;
                                color: #1e293b;
                                margin-bottom: 4px;
                            ">
                                ${location.name}
                            </div>
                            <div style="
                                font-size: 12px;
                                color: #64748b;
                            ">
                                ${location.type === 'hotel' ? '🏨 Hôtel' : '🏢 Bâtiment'}
                            </div>
                        </div>
                    `))
                    .addTo(satelliteInstance);
                mapMarkers.push(marker);
            } else {
                // Leaflet marker (fallback)
                const customIcon = L.divIcon({
                    html: markerElement.innerHTML,
                    className: 'custom-marker',
                    iconSize: [48, 48],
                    iconAnchor: [24, 48],
                    popupAnchor: [0, -48]
                });

                const marker = L.marker([location.lat, location.lng], { icon: customIcon })
                    .bindPopup(`
                        <div style="
                            text-align: center;
                            padding: 8px;
                            min-width: 150px;
                            background: rgba(255, 255, 255, 0.95);
                            border-radius: 8px;
                        ">
                            <div style="
                                font-size: 14px;
                                font-weight: 700;
                                color: #1e293b;
                                margin-bottom: 4px;
                            ">
                                ${location.name}
                            </div>
                            <div style="
                                font-size: 12px;
                                color: #64748b;
                            ">
                                ${location.type === 'hotel' ? '🏨 Hôtel' : '🏢 Bâtiment'}
                            </div>
                        </div>
                    `)
                    .addTo(satelliteInstance);
                mapMarkers.push(marker);
            }
        });
    }

    if (settings.value.showLines && satelliteInstance instanceof mapboxgl.Map) {
        // Mapbox GL JS lines
        const coordinates = locations.map(loc => [loc.lng, loc.lat]);
        
        if (satelliteInstance.getSource('lines')) {
            satelliteInstance.getSource('lines').setData({
                'type': 'FeatureCollection',
                'features': [{
                    'type': 'Feature',
                    'properties': {},
                    'geometry': {
                        'type': 'LineString',
                        'coordinates': coordinates
                    }
                }]
            });
        } else {
            satelliteInstance.addSource('lines', {
                'type': 'geojson',
                'data': {
                    'type': 'FeatureCollection',
                    'features': [{
                        'type': 'Feature',
                        'properties': {},
                        'geometry': {
                            'type': 'LineString',
                            'coordinates': coordinates
                        }
                    }]
                }
            });

            satelliteInstance.addLayer({
                'id': 'lines',
                'type': 'line',
                'source': 'lines',
                'layout': {
                    'line-join': 'round',
                    'line-cap': 'round'
                },
                'paint': {
                    'line-color': '#f59e0b',
                    'line-width': 3,
                    'line-opacity': 0.8
                }
            });
        }
    } else if (settings.value.showLines && !(satelliteInstance instanceof mapboxgl.Map)) {
        // Leaflet lines (fallback)
        for (let i = 0; i < locations.length - 1; i++) {
            const polyline = L.polyline([
                [locations[i].lat, locations[i].lng],
                [locations[i + 1].lat, locations[i + 1].lng]
            ], {
                color: '#f59e0b',
                weight: 3,
                opacity: 0.8
            }).addTo(satelliteInstance);
            mapPolylines.push(polyline);
        }
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
        // Clear existing globe if any
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

// Update flat map data
const updateMapData = () => {
    if (!mapInstance) return;
    
    // Clear existing markers and polylines
    mapMarkers.forEach(marker => mapInstance.removeLayer(marker));
    mapPolylines.forEach(polyline => mapInstance.removeLayer(polyline));
    mapMarkers = [];
    mapPolylines = [];

    const locations = generateLocationData();
    
    if (settings.value.showPoints) {
        locations.forEach(location => {
            // Create custom icon based on type
            const iconHtml = `
                <div style="
                    width: 48px;
                    height: 48px;
                    border-radius: 50%;
                    background: linear-gradient(135deg, ${location.type === 'hotel' ? '#f59e0b' : '#3b82f6'}, ${location.type === 'hotel' ? '#d97706' : '#2563eb'});
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
                ">
                    <svg width="24" height="24" viewBox="0 0 16 16" fill="white" xmlns="http://www.w3.org/2000/svg">
                        ${location.type === 'hotel' 
                            ? '<path d="M3 4h1v1H3V4zm2 0h1v1H5V4zm2 0h1v1H7V4zm2 0h1v1H9V4zm2 0h1v1h-1V4zm2 0h1v1h-1V4zM2 6h12v7H2V6zm1 1v5h10V7H3z" fill="currentColor"/>'
                            : '<path d="M2 2h12v12H2V2zm1 1v10h10V3H3zm2 2h6v6H5V5z" fill="currentColor"/>'
                        }
                    </svg>
                </div>
            `;

            const customIcon = L.divIcon({
                html: iconHtml,
                className: 'custom-marker',
                iconSize: [48, 48],
                iconAnchor: [24, 48],
                popupAnchor: [0, -48]
            });

            const marker = L.marker([location.lat, location.lng], { icon: customIcon })
                .bindPopup(`
                    <div style="
                        text-align: center;
                        padding: 8px;
                        min-width: 150px;
                    ">
                        <div style="
                            font-size: 14px;
                            font-weight: 700;
                            color: #1e293b;
                            margin-bottom: 4px;
                        ">
                            ${location.name}
                        </div>
                        <div style="
                            font-size: 12px;
                            color: #64748b;
                        ">
                            ${location.type === 'hotel' ? '🏨 Hôtel' : '🏢 Bâtiment'}
                        </div>
                    </div>
                `)
                .addTo(mapInstance);
            
            mapMarkers.push(marker);
        });
    }

    if (settings.value.showLines) {
        for (let i = 0; i < locations.length - 1; i++) {
            const polyline = L.polyline([
                [locations[i].lat, locations[i].lng],
                [locations[i + 1].lat, locations[i + 1].lng]
            ], {
                color: settings.value.nightMode ? '#60a5fa' : '#3b82f6',
                weight: 2,
                opacity: 0.6
            }).addTo(mapInstance);
            mapPolylines.push(polyline);
        }
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

    console.log('Initializing globe with dimensions:', width, height);
    console.log('Container element:', container);

    try {
        // Clear existing map if any
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

        // Correct globe.gl API with full configuration and fallback Github URLs
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
            .pointOfView({ lat: 20, lng: 0, altitude: 3 });

        // Configure OrbitControls directly
        const controls = globeInstance.controls();
        if (controls) {
            controls.autoRotate = settings.value.autoRotate;
            controls.autoRotateSpeed = settings.value.rotationSpeed;
            controls.minDistance = 1.2;
            controls.maxDistance = 10;
        }

        console.log('Globe instance created with full config:', globeInstance);
        
        // Update data after globe is ready
        setTimeout(() => {
            updateGlobeData();
            loading.value = false;
            console.log('Globe fully initialized');
        }, 1000);
    } catch (error) {
        console.error('Error initializing globe:', error);
        console.error('Error details:', error.message, error.stack);
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
    if (globeInstance) {
        const controls = globeInstance.controls();
        if (controls) {
            controls.autoRotate = newVal;
        }
    }
});

watch(() => settings.value.rotationSpeed, (newVal) => {
    if (globeInstance) {
        const controls = globeInstance.controls();
        if (controls) {
            controls.autoRotateSpeed = newVal;
        }
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
        globeInstance.width(width).height(height);
    }
};

onMounted(() => {
    window.addEventListener('resize', handleResize);
    // Initialize based on current display mode
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
    // Clean up all instances
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
