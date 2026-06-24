<template>
  <div class="global-map-container">
    <div class="header-section">
      <h1 class="title">Carte Mondiale</h1>
      <p class="subtitle">Visualisation en temps réel des entreprises partenaires</p>
    </div>
    
    <div ref="globeContainer" class="globe-wrapper">
      <div class="platform-base"></div>
      <div class="platform-ring"></div>
      <div class="platform-glow"></div>
      <canvas ref="globeCanvas"></canvas>
      
      <!-- Company Detail Popup Card -->
      <Transition name="slide-fade">
        <div v-if="showPopup && selectedCompany" class="company-detail-card">
          <button @click="showPopup = false" class="close-popup-btn" title="Fermer">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
          
          <div class="card-header">
            <div class="logo-wrapper">
              <img v-if="selectedCompany.logo" :src="selectedCompany.logo" alt="Logo" class="company-logo" />
              <div v-else class="logo-fallback">
                {{ selectedCompany.name.charAt(0).toUpperCase() }}
              </div>
            </div>
            <div class="header-text">
              <h2 class="company-name">{{ selectedCompany.name }}</h2>
              <span class="company-country-badge">
                {{ selectedCompany.country_name }}
              </span>
            </div>
          </div>
          
          <div class="card-body">
            <div class="info-item">
              <div class="info-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
              </div>
              <div class="info-content">
                <div class="info-label">Promoteur</div>
                <div class="info-value">{{ selectedCompany.promoter }}</div>
              </div>
            </div>
            
            <div class="info-item">
              <div class="info-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
              </div>
              <div class="info-content">
                <div class="info-label">Emplacement</div>
                <div class="info-value">{{ selectedCompany.address }}, {{ selectedCompany.city }}</div>
              </div>
            </div>
            
            <div class="stats-row">
              <div class="mini-stat">
                <div class="mini-stat-value">{{ selectedCompany.agencies_count }}</div>
                <div class="mini-stat-label">Agences</div>
              </div>
              <div class="mini-stat">
                <div class="mini-stat-value">{{ selectedCompany.employees_count }}</div>
                <div class="mini-stat-label">Employés</div>
              </div>
              <div class="mini-stat">
                <div class="mini-stat-value">{{ selectedCompany.locataires_count || 0 }}</div>
                <div class="mini-stat-label">Locataires</div>
              </div>
            </div>
          </div>
        </div>
      </Transition>

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
        <Link :href="route('superadmin.dashboard')" class="control-btn home-btn" title="Retour au Dashboard">
          <svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20">
            <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
          </svg>
        </Link>
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
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { Link } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/vue3'
import * as THREE from 'three'
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls'

const props = defineProps({
  stats: {
    type: Object,
    default: () => ({})
  },
  userLocation: {
    type: Object,
    default: () => ({ lat: 48.8566, lng: 2.3522 })
  },
  companies: {
    type: Array,
    default: () => []
  }
})

const page = usePage()
const stats = props.stats || page.props.stats || {}
const userLocation = props.userLocation || page.props.userLocation || { lat: 48.8566, lng: 2.3522 }

const globeContainer = ref(null)
const globeCanvas = ref(null)
const autoRotate = ref(true)
const showMarkers = ref(true)
const userPosition = ref(userLocation)
const activeUsers = ref(stats.active_users || 0)
const countriesCount = ref(stats.countries_count || 0)
const companiesCount = ref(stats.companies_count || 0)

let scene, camera, renderer, globe, controls, animationId
let markers = []
let companyMarkers = []

// Raycasting & Interaction
const raycaster = new THREE.Raycaster()
const mouse = new THREE.Vector2()
const selectedCompany = ref(null)
const showPopup = ref(false)

onMounted(() => {
  initGlobe()
  animate()
  window.addEventListener('resize', onWindowResize)
})

onUnmounted(() => {
  cancelAnimationFrame(animationId)
  window.removeEventListener('resize', onWindowResize)
  
  if (globeCanvas.value) {
    globeCanvas.value.removeEventListener('click', onCanvasClick)
    globeCanvas.value.removeEventListener('mousemove', onCanvasMouseMove)
  }
  
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
  camera.position.z = 2.2
  
  // Renderer
  renderer = new THREE.WebGLRenderer({ 
    canvas, 
    antialias: true, 
    alpha: true 
  })
  renderer.setSize(width, height)
  renderer.setPixelRatio(window.devicePixelRatio)
  
  // Globe geometry - larger size
  const geometry = new THREE.SphereGeometry(1.5, 64, 64)
  
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
  
  // Add continent outlines
  addContinentOutlines()
  
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
  controls.minDistance = 2
  controls.maxDistance = 4
  controls.autoRotate = autoRotate.value
  controls.autoRotateSpeed = 0.5
  
  // Add user marker
  addUserMarker()
  
  // Add company markers from database
  addCompanyMarkers()

  // Add click & hover event listeners to the canvas
  canvas.addEventListener('click', onCanvasClick)
  canvas.addEventListener('mousemove', onCanvasMouseMove)
}

function addUserMarker() {
  const { lat, lng } = userPosition.value
  const position = latLngToVector3(lat, lng, 1.53)
  
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

function addCompanyMarkers() {
  const companyList = props.companies || page.props.companies || []
  
  // Generate unique colors for each company
  const colors = [
    0x00ff88, 0x00aaff, 0xff6b6b, 0xffd93d, 0x6bcb77, 
    0x4d96ff, 0xff6f91, 0xffc75f, 0x845ec2, 0xffc4ff,
    0x00d2fc, 0x7de2d1, 0xffa07a, 0x98fb98, 0xdda0dd
  ]
  
  companyList.forEach((company, index) => {
    // Add offset to prevent overlapping - larger spacing
    const offsetLat = (Math.random() - 0.5) * 0.3 // Larger random offset in latitude
    const offsetLng = (Math.random() - 0.5) * 0.3 // Larger random offset in longitude
    
    const position = latLngToVector3(
      company.latitude + offsetLat, 
      company.longitude + offsetLng, 
      1.53
    )
    
    // Assign unique color based on index
    const uniqueColor = colors[index % colors.length]
    
    // Create interactive sphere marker
    const markerGeometry = new THREE.SphereGeometry(0.02, 16, 16)
    const markerMaterial = new THREE.MeshBasicMaterial({
      color: uniqueColor,
      transparent: true,
      opacity: 0.8
    })
    const marker = new THREE.Mesh(markerGeometry, markerMaterial)
    marker.position.copy(position)
    marker.userData = { company }
    
    globe.add(marker)
    markers.push(marker)
    companyMarkers.push(marker)
    
    // Add pulsing ring for company marker with same color
    const ringGeometry = new THREE.RingGeometry(0.025, 0.04, 32)
    const ringMaterial = new THREE.MeshBasicMaterial({
      color: uniqueColor,
      transparent: true,
      opacity: 0.4,
      side: THREE.DoubleSide
    })
    const ring = new THREE.Mesh(ringGeometry, ringMaterial)
    ring.position.copy(position)
    ring.lookAt(new THREE.Vector3(0, 0, 0))
    globe.add(ring)
    markers.push(ring)
  })
}

function onCanvasClick(event) {
  if (!renderer) return
  const rect = renderer.domElement.getBoundingClientRect()
  mouse.x = ((event.clientX - rect.left) / rect.width) * 2 - 1
  mouse.y = -((event.clientY - rect.top) / rect.height) * 2 + 1
  
  raycaster.setFromCamera(mouse, camera)
  const intersects = raycaster.intersectObjects(companyMarkers)
  
  if (intersects.length > 0) {
    const clickedMarker = intersects[0].object
    selectedCompany.value = clickedMarker.userData.company
    showPopup.value = true
    
    autoRotate.value = false
    if (controls) {
      controls.autoRotate = false
    }
  } else {
    // Clicked elsewhere on canvas
    const intersectsGlobe = raycaster.intersectObject(globe)
    if (intersectsGlobe.length === 0) {
      showPopup.value = false
    }
  }
}

function onCanvasMouseMove(event) {
  if (!renderer) return
  const rect = renderer.domElement.getBoundingClientRect()
  mouse.x = ((event.clientX - rect.left) / rect.width) * 2 - 1
  mouse.y = -((event.clientY - rect.top) / rect.height) * 2 + 1
  
  raycaster.setFromCamera(mouse, camera)
  const intersects = raycaster.intersectObjects(companyMarkers)
  
  if (intersects.length > 0) {
    renderer.domElement.style.cursor = 'pointer'
  } else {
    renderer.domElement.style.cursor = 'default'
  }
}

function addContinentOutlines() {
  const continentMaterial = new THREE.LineBasicMaterial({
    color: 0x00ffaa,
    transparent: true,
    opacity: 0.4,
    linewidth: 2
  })
  
  const continents = {
    northAmerica: [
      { lat: 70, lng: -170 }, { lat: 70, lng: -60 }, { lat: 50, lng: -55 },
      { lat: 25, lng: -80 }, { lat: 15, lng: -90 }, { lat: 20, lng: -105 },
      { lat: 30, lng: -115 }, { lat: 50, lng: -125 }, { lat: 60, lng: -140 },
      { lat: 70, lng: -170 }
    ],
    southAmerica: [
      { lat: 12, lng: -75 }, { lat: 5, lng: -35 }, { lat: -5, lng: -35 },
      { lat: -25, lng: -45 }, { lat: -55, lng: -70 }, { lat: -55, lng: -75 },
      { lat: -20, lng: -70 }, { lat: 0, lng: -80 }, { lat: 12, lng: -75 }
    ],
    europe: [
      { lat: 70, lng: -10 }, { lat: 70, lng: 40 }, { lat: 45, lng: 40 },
      { lat: 35, lng: 25 }, { lat: 38, lng: -10 }, { lat: 45, lng: -10 },
      { lat: 55, lng: -5 }, { lat: 70, lng: -10 }
    ],
    africa: [
      { lat: 35, lng: -10 }, { lat: 35, lng: 40 }, { lat: 10, lng: 50 },
      { lat: -35, lng: 25 }, { lat: -35, lng: 15 }, { lat: -5, lng: 10 },
      { lat: 5, lng: -15 }, { lat: 35, lng: -10 }
    ],
    asia: [
      { lat: 70, lng: 40 }, { lat: 70, lng: 180 }, { lat: 35, lng: 140 },
      { lat: 5, lng: 100 }, { lat: 10, lng: 70 }, { lat: 25, lng: 65 },
      { lat: 40, lng: 40 }, { lat: 70, lng: 40 }
    ],
    australia: [
      { lat: -10, lng: 115 }, { lat: -10, lng: 150 }, { lat: -25, lng: 155 },
      { lat: -40, lng: 145 }, { lat: -35, lng: 115 }, { lat: -20, lng: 115 },
      { lat: -10, lng: 115 }
    ],
    antarctica: [
      { lat: -65, lng: -180 }, { lat: -65, lng: -90 }, { lat: -65, lng: 0 },
      { lat: -65, lng: 90 }, { lat: -65, lng: 180 }, { lat: -75, lng: 180 },
      { lat: -75, lng: -180 }, { lat: -65, lng: -180 }
    ]
  }
  
  Object.values(continents).forEach(continent => {
    const geometry = new THREE.BufferGeometry()
    const points = []
    
    continent.forEach(coord => {
      const position = latLngToVector3(coord.lat, coord.lng, 1.5)
      points.push(position)
    })
    
    if (continent.length > 0) {
      const first = latLngToVector3(continent[0].lat, continent[0].lng, 1.5)
      points.push(first)
    }
    
    geometry.setFromPoints(points)
    const line = new THREE.Line(geometry, continentMaterial)
    globe.add(line)
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
  
  const time = Date.now() * 0.001
  markers.forEach((marker, index) => {
    if (marker.material && marker.material.opacity !== undefined) {
      marker.material.opacity = 0.5 + Math.sin(time * 2 + index) * 0.3
    }
  })
  
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
    camera.position.set(0, 0, 2.2)
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
  height: 85vh;
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

.control-btn.home-btn {
  background: rgba(0, 170, 255, 0.2);
  border-color: #00aaff;
}

.control-btn.home-btn:hover {
  background: rgba(0, 170, 255, 0.4);
  border-color: #00ff88;
  color: #00ff88;
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

/* Company popup card styling */
.company-detail-card {
  position: absolute;
  top: 100px;
  left: 30px;
  width: 380px;
  background: rgba(10, 15, 30, 0.75);
  border: 1px solid rgba(0, 170, 255, 0.35);
  border-radius: 24px;
  padding: 1.75rem;
  backdrop-filter: blur(20px);
  box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5), 0 0 30px rgba(0, 170, 255, 0.15);
  z-index: 50;
  color: #fff;
  text-align: left;
}

.close-popup-btn {
  position: absolute;
  top: 16px;
  right: 16px;
  background: transparent;
  border: none;
  color: rgba(255, 255, 255, 0.5);
  cursor: pointer;
  padding: 4px;
  border-radius: 50%;
  transition: all 0.2s ease;
  display: flex;
  align-items: center;
  justify-content: center;
}

.close-popup-btn:hover {
  color: #fff;
  background: rgba(255, 255, 255, 0.1);
}

.close-popup-btn svg {
  width: 20px;
  height: 20px;
}

.card-header {
  display: flex;
  align-items: center;
  gap: 1rem;
  margin-bottom: 1.5rem;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
  padding-bottom: 1.25rem;
}

.logo-wrapper {
  width: 64px;
  height: 64px;
  border-radius: 16px;
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(255, 255, 255, 0.15);
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  box-shadow: inset 0 2px 4px rgba(0,0,0,0.2);
}

.company-logo {
  width: 100%;
  height: 100%;
  object-fit: contain;
  padding: 4px;
}

.logo-fallback {
  font-size: 1.75rem;
  font-weight: 800;
  color: #00aaff;
  text-shadow: 0 0 10px rgba(0, 170, 255, 0.5);
}

.header-text {
  flex: 1;
}

.company-name {
  font-size: 1.35rem;
  font-weight: 800;
  margin: 0 0 4px 0;
  background: linear-gradient(135deg, #fff, #a2d6ff);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.company-country-badge {
  display: inline-block;
  font-size: 0.75rem;
  font-weight: 700;
  color: #00ff88;
  background: rgba(0, 255, 136, 0.1);
  border: 1px solid rgba(0, 255, 136, 0.2);
  padding: 2px 8px;
  border-radius: 8px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.card-body {
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
}

.info-item {
  display: flex;
  gap: 0.85rem;
  align-items: flex-start;
}

.info-icon {
  width: 36px;
  height: 36px;
  border-radius: 10px;
  background: rgba(0, 170, 255, 0.1);
  border: 1px solid rgba(0, 170, 255, 0.2);
  display: flex;
  align-items: center;
  justify-content: center;
  color: #00aaff;
  flex-shrink: 0;
}

.info-icon svg {
  width: 18px;
  height: 18px;
}

.info-content {
  flex: 1;
}

.info-label {
  font-size: 0.75rem;
  text-transform: uppercase;
  color: rgba(255, 255, 255, 0.4);
  font-weight: 700;
  letter-spacing: 0.5px;
  margin-bottom: 2px;
}

.info-value {
  font-size: 0.95rem;
  font-weight: 600;
  color: rgba(255, 255, 255, 0.9);
}

.stats-row {
  display: grid;
  grid-template-cols: repeat(3, 1fr);
  gap: 0.75rem;
  margin-top: 0.5rem;
}

.mini-stat {
  background: rgba(255, 255, 255, 0.03);
  border: 1px solid rgba(255, 255, 255, 0.08);
  border-radius: 16px;
  padding: 1rem;
  text-align: center;
}

.mini-stat-value {
  font-size: 1.5rem;
  font-weight: 800;
  color: #00aaff;
  margin-bottom: 4px;
}

.mini-stat-label {
  font-size: 0.75rem;
  color: rgba(255, 255, 255, 0.5);
  font-weight: 600;
  text-transform: uppercase;
}

/* Animations */
.slide-fade-enter-active, .slide-fade-leave-active {
  transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

.slide-fade-enter-from, .slide-fade-leave-to {
  transform: translateX(-40px);
  opacity: 0;
}
</style>
