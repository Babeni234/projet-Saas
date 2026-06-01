<template>
    <div class="flex flex-col gap-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Localisation Maps</h2>
                <p class="text-slate-500">Carte interactive en temps réel des emplacements mondiaux</p>
            </div>
            <div class="flex items-center gap-3">
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
                        <span class="text-sm text-slate-600">{{ settings.autoRotate ? 'Activé' : 'Désactivé' }}</span>
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
        <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 overflow-hidden" style="height: 600px;">
            <div ref="globeContainer" class="w-full h-full relative">
                <!-- Globe will be rendered here -->
                <div v-if="loading" class="absolute inset-0 flex items-center justify-center bg-slate-50">
                    <div class="text-center">
                        <div class="w-12 h-12 border-4 border-indigo-500 border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
                        <p class="text-slate-600">Chargement de la carte...</p>
                    </div>
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

const globeContainer = ref(null);
const loading = ref(true);
const showSettings = ref(false);

const settings = ref({
    displayMode: 'globe',
    autoRotate: true,
    rotationSpeed: 1,
    showPoints: true,
    showLines: true,
    nightMode: false
});

const totalLocations = ref(156);
const onlineLocations = ref(142);
const recentActivity = ref(28);
const countries = ref(23);

let globeInstance = null;
let mapInstance = null;
let mapMarkers = [];
let mapPolylines = [];

const toggleSettings = () => {
    showSettings.value = !showSettings.value;
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
        { name: 'Hong Kong', lat: 22.3193, lng: 114.1694, type: 'hotel' }
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

// Initialize flat map with Leaflet
const initMap = () => {
    if (!globeContainer.value) {
        console.error('Map container not found');
        return;
    }

    const container = globeContainer.value;
    const width = container.clientWidth;
    const height = container.clientHeight;

    console.log('Initializing flat map with dimensions:', width, height);

    try {
        // Clear existing globe if any
        if (globeInstance) {
            globeInstance();
            globeInstance = null;
        }

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
    const width = container.clientWidth;
    const height = container.clientHeight;

    console.log('Initializing globe with dimensions:', width, height);
    console.log('Container element:', container);

    try {
        // Clear existing map if any
        if (mapInstance) {
            mapInstance.remove();
            mapInstance = null;
        }

        // Correct globe.gl API with full configuration
        globeInstance = Globe()(container)
            .width(width)
            .height(height)
            .globeImageUrl(settings.value.nightMode 
                ? 'https://unpkg.com/three-globe/example/img/earth-night.jpg'
                : 'https://unpkg.com/three-globe/example/img/earth-blue-marble.jpg'
            )
            .bumpImageUrl('https://unpkg.com/three-globe/example/img/earth-topology.png')
            .backgroundImageUrl(settings.value.nightMode
                ? 'https://unpkg.com/three-globe/example/img/night-sky.png'
                : 'https://unpkg.com/three-globe/example/img/night-sky.png'
            )
            .pointOfView({ lat: 20, lng: 0, altitude: 3 })
            .controlsAutoRotate(settings.value.autoRotate)
            .controlsAutoRotateSpeed(settings.value.rotationSpeed)
            .controlsMinDistance(1.2) // Allow zoom even closer
            .controlsMaxDistance(10); // Allow zoom further

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
        }
    }, 100);
});

// Watch for settings changes
watch(() => settings.value.nightMode, () => {
    if (settings.value.displayMode === 'globe' && globeInstance) {
        globeInstance.globeImageUrl(settings.value.nightMode 
            ? 'https://unpkg.com/three-globe/example/img/earth-night.jpg'
            : 'https://unpkg.com/three-globe/example/img/earth-blue-marble.jpg'
        );
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

watch(() => settings.value.autoRotate, () => {
    if (globeInstance) {
        globeInstance.controlsAutoRotate(settings.value.autoRotate);
    }
});

watch(() => settings.value.rotationSpeed, () => {
    if (globeInstance) {
        globeInstance.controlsAutoRotateSpeed(settings.value.rotationSpeed);
    }
});

watch(() => settings.value.showPoints, () => {
    if (settings.value.displayMode === 'globe') {
        updateGlobeData();
    } else if (settings.value.displayMode === 'map') {
        updateMapData();
    }
});

watch(() => settings.value.showLines, () => {
    if (settings.value.displayMode === 'globe') {
        updateGlobeData();
    } else if (settings.value.displayMode === 'map') {
        updateMapData();
    }
});

onMounted(() => {
    // Initialize based on current display mode
    setTimeout(() => {
        if (settings.value.displayMode === 'globe') {
            initGlobe();
        } else if (settings.value.displayMode === 'map') {
            initMap();
        }
    }, 200);
});

onUnmounted(() => {
    // Clean up both instances
    if (globeInstance) {
        globeInstance();
        globeInstance = null;
    }
    if (mapInstance) {
        mapInstance.remove();
        mapInstance = null;
    }
});
</script>
