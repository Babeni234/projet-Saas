<template>
  <div class="global-map-container">
    <div class="header-section">
      <h1 class="title">Carte Mondiale</h1>
      <p class="subtitle">Visualisation en temps réel des utilisateurs actifs</p>
    </div>
    
    <div ref="globeContainer" class="globe-wrapper">
      <div class="platform-base"></div>
      <div class="platform-ring"></div>
      <div class="platform-glow"></div>
      <canvas ref="globeCanvas"></canvas>
      
      <!-- User position marker -->
      <div class="user-marker" v-if="userPosition">
        <div class="marker-pulse"></div>
        <div class="marker-icon">
          <svg viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
          </svg>
        </div>
        <div class="marker-label">Votre position</div>
      </div>
      
      <!-- Stats overlay -->
      <div class="stats-overlay">
        <div class="stat-item">
          <div class="stat-value">{{ activeUsers.toLocaleString() }}</div>
          <div class="stat-label">Utilisateurs actifs</div>
        </div>
        <div class="stat-item">
          <div class="stat-value">{{ countriesCount }}</div>
          <div class="stat-label">Pays</div>
        </div>
        <div class="stat-item">
          <div class="stat-value">{{ companiesCount.toLocaleString() }}</div>
          <div class="stat-label">Entreprises</div>
        </div>
      </div>
      
      <!-- Controls -->
      <div class="controls">
        <button @click="resetView" class="control-btn" title="Réinitialiser la vue">
          <svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20">
            <path d="M12 5V1L7 6l5 5V7c3.31 0 6 2.69 6 6s-2.69 6-6 6-6-2.69-6-6H4c0 4.42 3.58 8 8 8s8-3.58 8-8-3.58-8-8-8z"/>
          </svg>
        </button>
        <button @click="toggleAutoRotate" class="control-btn" :class="{ active: autoRotate }" title="Rotation automatique">
          <svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
          </svg>
        </button>
        <button @click="toggleMarkers" class="control-btn" :class="{ active: showMarkers }" title="Afficher les marqueurs">
          <svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20">
            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
          </svg>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import * as THREE from 'three'
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls'

const globeContainer = ref(null)
const globeCanvas = ref(null)
const autoRotate = ref(true)
const showMarkers = ref(true)
const userPosition = ref({ lat: 48.8566, lng: 2.3522 }) // Paris par défaut
const activeUsers = ref(1247)
const countriesCount = ref(45)
const companiesCount = ref(89)

let scene, camera, renderer, globe, controls, animationId
let markers = []

onMounted(() => {
  initGlobe()
  animate()
  window.addEventListener('resize', onWindowResize)
})

onUnmounted(() => {
  cancelAnimationFrame(animationId)
  window.removeEventListener('resize', onWindowResize)
  if (renderer) {
    renderer.dispose()
    globeContainer.value?.removeChild(globeCanvas.value)
  }
})

function initGlobe() {
  const container = globeContainer.value
  const canvas = globeCanvas.value
  
  const width = container.clientWidth
  const height = container.clientHeight
  
  // Scene
  scene = new THREE.Scene()
  
  // Camera
  camera = new THREE.PerspectiveCamera(45, width / height, 0.1, 1000)
  camera.position.z = 3
  
  // Renderer
  renderer = new THREE.WebGLRenderer({ 
    canvas, 
    antialias: true, 
    alpha: true 
  })
  renderer.setSize(width, height)
  renderer.setPixelRatio(window.devicePixelRatio)
  
  // Globe geometry
  const geometry = new THREE.SphereGeometry(1, 64, 64)
  
  // Hologram material
  const material = new THREE.MeshPhongMaterial({
    color: 0x00aaff,
    transparent: true,
    opacity: 0.3,
    wireframe: false,
    side: THREE.DoubleSide,
    shininess: 100,
    emissive: 0x004466,
    emissiveIntensity: 0.2
  })
  
  globe = new THREE.Mesh(geometry, material)
  scene.add(globe)
  
  // Wireframe overlay
  const wireframeMaterial = new THREE.MeshBasicMaterial({
    color: 0x00ffff,
    wireframe: true,
    transparent: true,
    opacity: 0.15
  })
  const wireframe = new THREE.Mesh(geometry, wireframeMaterial)
  globe.add(wireframe)
  
  // Grid lines (latitude/longitude)
  const gridMaterial = new THREE.LineBasicMaterial({
    color: 0x0088cc,
    transparent: true,
    opacity: 0.3
  })
  
  // Latitude lines
  for (let i = -80; i <= 80; i += 20) {
    const latGeometry = new THREE.BufferGeometry()
    const points = []
    const lat = (i * Math.PI) / 180
    for (let lng = 0; lng <= 360; lng += 5) {
      const phi = (lng * Math.PI) / 180
      const x = Math.cos(lat) * Math.cos(phi)
      const y = Math.sin(lat)
      const z = Math.cos(lat) * Math.sin(phi)
      points.push(new THREE.Vector3(x, y, z))
    }
    latGeometry.setFromPoints(points)
    const latLine = new THREE.Line(latGeometry, gridMaterial)
    globe.add(latLine)
  }
  
  // Longitude lines
  for (let i = 0; i < 360; i += 30) {
    const lngGeometry = new THREE.BufferGeometry()
    const points = []
    const lng = (i * Math.PI) / 180
    for (let lat = -80; lat <= 80; lat += 5) {
      const phi = (lat * Math.PI) / 180
      const theta = lng
      const x = Math.cos(phi) * Math.cos(theta)
      const y = Math.sin(phi)
      const z = Math.cos(phi) * Math.sin(theta)
      points.push(new THREE.Vector3(x, y, z))
    }
    lngGeometry.setFromPoints(points)
    const lngLine = new THREE.Line(lngGeometry, gridMaterial)
    globe.add(lngLine)
  }
  
  // Lighting
  const ambientLight = new THREE.AmbientLight(0x004466, 0.5)
  scene.add(ambientLight)
  
  const pointLight = new THREE.PointLight(0x00aaff, 2, 10)
  pointLight.position.set(2, 2, 2)
  scene.add(pointLight)
  
  const pointLight2 = new THREE.PointLight(0x00ffff, 1, 10)
  pointLight2.position.set(-2, -2, 2)
  scene.add(pointLight2)
  
  // Controls
  controls = new OrbitControls(camera, canvas)
  controls.enableDamping = true
  controls.dampingFactor = 0.05
  controls.enableZoom = true
  controls.minDistance = 1.5
  controls.maxDistance = 5
  controls.autoRotate = autoRotate.value
  controls.autoRotateSpeed = 0.5
  
  // Add user marker
  addUserMarker()
  
  // Add random markers for other users
  addRandomMarkers()
}

function addUserMarker() {
  const { lat, lng } = userPosition.value
  const position = latLngToVector3(lat, lng, 1.02)
  
  const markerGeometry = new THREE.SphereGeometry(0.02, 16, 16)
  const markerMaterial = new THREE.MeshBasicMaterial({
    color: 0x0066ff,
    transparent: true,
    opacity: 0.8
  })
  const marker = new THREE.Mesh(markerGeometry, markerMaterial)
  marker.position.copy(position)
  globe.add(marker)
  markers.push(marker)
  
  // Add glow ring
  const ringGeometry = new THREE.RingGeometry(0.025, 0.035, 32)
  const ringMaterial = new THREE.MeshBasicMaterial({
    color: 0x0066ff,
    transparent: true,
    opacity: 0.5,
    side: THREE.DoubleSide
  })
  const ring = new THREE.Mesh(ringGeometry, ringMaterial)
  ring.position.copy(position)
  ring.lookAt(new THREE.Vector3(0, 0, 0))
  globe.add(ring)
  markers.push(ring)
}

function addRandomMarkers() {
  const cities = [
    { lat: 40.7128, lng: -74.0060 }, // New York
    { lat: 35.6762, lng: 139.6503 }, // Tokyo
    { lat: 51.5074, lng: -0.1278 }, // London
    { lat: -33.8688, lng: 151.2093 }, // Sydney
    { lat: 55.7558, lng: 37.6173 }, // Moscow
    { lat: -23.5505, lng: -46.6333 }, // São Paulo
    { lat: 1.3521, lng: 103.8198 }, // Singapore
    { lat: 25.2048, lng: 55.2708 }, // Dubai
  ]
  
  cities.forEach(city => {
    const position = latLngToVector3(city.lat, city.lng, 1.02)
    
    const markerGeometry = new THREE.SphereGeometry(0.015, 16, 16)
    const markerMaterial = new THREE.MeshBasicMaterial({
      color: 0x00ff88,
      transparent: true,
      opacity: 0.6
    })
    const marker = new THREE.Mesh(markerGeometry, markerMaterial)
    marker.position.copy(position)
    globe.add(marker)
    markers.push(marker)
  })
}

function latLngToVector3(lat, lng, radius) {
  const phi = (90 - lat) * (Math.PI / 180)
  const theta = (lng + 180) * (Math.PI / 180)
  
  const x = -(radius * Math.sin(phi) * Math.cos(theta))
  const y = radius * Math.cos(phi)
  const z = radius * Math.sin(phi) * Math.sin(theta)
  
  return new THREE.Vector3(x, y, z)
}

function animate() {
  animationId = requestAnimationFrame(animate)
  
  if (controls) {
    controls.update()
  }
  
  // Pulse effect for markers
  const time = Date.now() * 0.001
  markers.forEach((marker, index) => {
    if (marker.material.opacity !== undefined) {
      marker.material.opacity = 0.5 + Math.sin(time * 2 + index) * 0.3
    }
  })
  
  // Hologram flicker effect
  if (globe && globe.material) {
    globe.material.opacity = 0.25 + Math.sin(time * 3) * 0.05
  }
  
  renderer.render(scene, camera)
}

function onWindowResize() {
  const container = globeContainer.value
  if (!container || !camera || !renderer) return
  
  const width = container.clientWidth
  const height = container.clientHeight
  
  camera.aspect = width / height
  camera.updateProjectionMatrix()
  renderer.setSize(width, height)
}

function resetView() {
  if (camera && controls) {
    camera.position.set(0, 0, 3)
    controls.reset()
  }
}

function toggleAutoRotate() {
  autoRotate.value = !autoRotate.value
  if (controls) {
    controls.autoRotate = autoRotate.value
  }
}

function toggleMarkers() {
  showMarkers.value = !showMarkers.value
  markers.forEach(marker => {
    marker.visible = showMarkers.value
  })
}
</script>

<style scoped>
.global-map-container {
  min-height: 100vh;
  background: linear-gradient(135deg, #0a0a1a 0%, #1a1a3a 50%, #0a0a2a 100%);
  padding: 2rem;
  overflow: hidden;
}

.header-section {
  text-align: center;
  margin-bottom: 2rem;
  position: relative;
  z-index: 10;
}

.title {
  font-size: 2.5rem;
  font-weight: 700;
  background: linear-gradient(135deg, #00aaff, #00ff88);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  margin-bottom: 0.5rem;
  text-shadow: 0 0 30px rgba(0, 170, 255, 0.3);
}

.subtitle {
  color: rgba(255, 255, 255, 0.6);
  font-size: 1.1rem;
}

.globe-wrapper {
  position: relative;
  width: 100%;
  height: 70vh;
  display: flex;
  align-items: center;
  justify-content: center;
}

.globe-wrapper canvas {
  width: 100%;
  height: 100%;
}

.platform-base {
  position: absolute;
  bottom: 10%;
  left: 50%;
  transform: translateX(-50%);
  width: 300px;
  height: 20px;
  background: linear-gradient(90deg, transparent, rgba(0, 170, 255, 0.3), transparent);
  border-radius: 50%;
  filter: blur(10px);
  animation: platformPulse 3s ease-in-out infinite;
}

.platform-ring {
  position: absolute;
  bottom: 12%;
  left: 50%;
  transform: translateX(-50%);
  width: 250px;
  height: 250px;
  border: 2px solid rgba(0, 170, 255, 0.4);
  border-radius: 50%;
  animation: ringRotate 20s linear infinite;
}

.platform-ring::before {
  content: '';
  position: absolute;
  top: -5px;
  left: -5px;
  right: -5px;
  bottom: -5px;
  border: 1px solid rgba(0, 255, 136, 0.3);
  border-radius: 50%;
  animation: ringRotate 15s linear infinite reverse;
}

.platform-glow {
  position: absolute;
  bottom: 8%;
  left: 50%;
  transform: translateX(-50%);
  width: 400px;
  height: 100px;
  background: radial-gradient(ellipse, rgba(0, 170, 255, 0.2) 0%, transparent 70%);
  filter: blur(20px);
  animation: glowPulse 4s ease-in-out infinite;
}

.user-marker {
  position: absolute;
  bottom: 25%;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  flex-direction: column;
  align-items: center;
  z-index: 20;
}

.marker-pulse {
  width: 40px;
  height: 40px;
  background: radial-gradient(circle, rgba(0, 102, 255, 0.6) 0%, transparent 70%);
  border-radius: 50%;
  animation: markerPulse 2s ease-out infinite;
}

.marker-icon {
  width: 32px;
  height: 32px;
  color: #0066ff;
  background: rgba(0, 102, 255, 0.2);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 2px solid #0066ff;
  box-shadow: 0 0 20px rgba(0, 102, 255, 0.5);
  position: absolute;
  top: 4px;
}

.marker-label {
  margin-top: 45px;
  padding: 6px 12px;
  background: rgba(0, 102, 255, 0.2);
  border: 1px solid rgba(0, 102, 255, 0.4);
  border-radius: 8px;
  color: #00aaff;
  font-size: 0.85rem;
  font-weight: 600;
  backdrop-filter: blur(10px);
  white-space: nowrap;
}

.stats-overlay {
  position: absolute;
  top: 20px;
  right: 20px;
  display: flex;
  flex-direction: column;
  gap: 1rem;
  z-index: 20;
}

.stat-item {
  background: rgba(0, 20, 40, 0.8);
  border: 1px solid rgba(0, 170, 255, 0.3);
  border-radius: 12px;
  padding: 1rem 1.5rem;
  backdrop-filter: blur(10px);
  min-width: 150px;
}

.stat-value {
  font-size: 1.8rem;
  font-weight: 700;
  color: #00aaff;
  margin-bottom: 0.25rem;
}

.stat-label {
  font-size: 0.85rem;
  color: rgba(255, 255, 255, 0.6);
  text-transform: uppercase;
  letter-spacing: 1px;
}

.controls {
  position: absolute;
  bottom: 20px;
  right: 20px;
  display: flex;
  gap: 0.5rem;
  z-index: 20;
}

.control-btn {
  width: 44px;
  height: 44px;
  background: rgba(0, 20, 40, 0.8);
  border: 1px solid rgba(0, 170, 255, 0.3);
  border-radius: 12px;
  color: #00aaff;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
  backdrop-filter: blur(10px);
}

.control-btn:hover {
  background: rgba(0, 170, 255, 0.2);
  border-color: #00aaff;
  transform: translateY(-2px);
  box-shadow: 0 5px 20px rgba(0, 170, 255, 0.3);
}

.control-btn.active {
  background: rgba(0, 170, 255, 0.3);
  border-color: #00aaff;
  box-shadow: 0 0 15px rgba(0, 170, 255, 0.5);
}

@keyframes platformPulse {
  0%, 100% {
    opacity: 0.5;
    transform: translateX(-50%) scaleX(1);
  }
  50% {
    opacity: 0.8;
    transform: translateX(-50%) scaleX(1.2);
  }
}

@keyframes ringRotate {
  from {
    transform: translateX(-50%) rotateX(70deg) rotateZ(0deg);
  }
  to {
    transform: translateX(-50%) rotateX(70deg) rotateZ(360deg);
  }
}

@keyframes glowPulse {
  0%, 100% {
    opacity: 0.5;
    transform: translateX(-50%) scale(1);
  }
  50% {
    opacity: 0.8;
    transform: translateX(-50%) scale(1.1);
  }
}

@keyframes markerPulse {
  0% {
    transform: scale(0.5);
    opacity: 0.8;
  }
  100% {
    transform: scale(1.5);
    opacity: 0;
  }
}
</style>
