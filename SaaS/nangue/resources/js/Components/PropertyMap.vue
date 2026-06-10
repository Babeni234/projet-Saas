<script setup>
import { ref, onMounted, onUnmounted, watch } from 'vue';

const props = defineProps({
  address: { type: String, default: '' },
  city: { type: String, default: '' },
  height: { type: String, default: '250px' },
  zoom: { type: Number, default: 14 },
});

const mapContainer = ref(null);
let map = null;
let marker = null;
let leaflet = null;

const initMap = async () => {
  leaflet = await import('leaflet');
  await import('leaflet/dist/leaflet.css');

  if (!mapContainer.value || map) return;

  map = leaflet.map(mapContainer.value, {
    zoomControl: true,
    scrollWheelZoom: false,
  }).setView([48.8566, 2.3522], props.zoom);

  leaflet.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors',
    maxZoom: 19,
  }).addTo(map);

  if (props.address || props.city) {
    geocode();
  }
};

const geocode = async () => {
  if (!leaflet || !map) return;
  const q = encodeURIComponent(`${props.address}, ${props.city}`);
  try {
    const res = await fetch(`https://nominatim.openstreetmap.org/search?q=${q}&format=json&limit=1`);
    const data = await res.json();
    if (data.length) {
      const { lat, lon } = data[0];
      map.setView([lat, lon], props.zoom);
      if (marker) marker.remove();
      marker = leaflet.marker([lat, lon]).addTo(map);
    }
  } catch (e) {
    /* fallback – keep default center */
  }
};

watch(() => [props.address, props.city], () => {
  if (map) geocode();
});

onMounted(initMap);

onUnmounted(() => {
  if (map) {
    map.remove();
    map = null;
  }
});
</script>

<template>
  <div ref="mapContainer" :style="{ height }" class="rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden" />
</template>
