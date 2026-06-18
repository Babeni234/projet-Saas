// HABITATUM — Service Worker
// Gère le cache, les notifications push et les messages de l'application

const CACHE = 'hab-v2'
const STATIC_ASSETS = [
  '/manifest.json',
  '/favicon.ico',
]
const ICON_CACHE = 'hab-icons'
const API_CACHE = 'hab-api'

// ─── Installation : pré-cache des assets statiques ───
self.addEventListener('install', (e) => {
  self.skipWaiting()
  e.waitUntil(
    caches.open(CACHE).then((c) => c.addAll(STATIC_ASSETS)).catch(() => {})
  )
})

// ─── Activation : nettoyage des anciens caches ───
self.addEventListener('activate', (e) => {
  e.waitUntil(
    caches.keys().then((keys) =>
      Promise.all(keys.filter((k) => k !== CACHE && k !== ICON_CACHE && k !== API_CACHE).map((k) => caches.delete(k)))
    ).then(() => clients.claim())
  )
})

// ─── Stratégie de cache : NetworkFirst pour l'App Shell, CacheFirst pour les assets ───
self.addEventListener('fetch', (e) => {
  const { request } = e
  const url = new URL(request.url)

  // Cache First pour les icônes et images
  if (url.pathname.startsWith('/icons/') || url.pathname.startsWith('/build/assets/')) {
    e.respondWith(cacheFirst(request, ICON_CACHE))
    return
  }

  // Network First pour les pages (App Shell)
  if (request.mode === 'navigate') {
    e.respondWith(networkFirst(request))
    return
  }

  // Network Only pour le reste (API, etc.)
  e.respondWith(fetch(request).catch(() => new Response('Offline', { status: 503 })))
})

async function cacheFirst(request, cacheName) {
  const cached = await caches.match(request)
  if (cached) return cached
  try {
    const response = await fetch(request)
    const cache = await caches.open(cacheName)
    cache.put(request, response.clone())
    return response
  } catch {
    return new Response('Offline', { status: 503 })
  }
}

async function networkFirst(request) {
  try {
    const response = await fetch(request)
    const cache = await caches.open(CACHE)
    cache.put(request, response.clone())
    return response
  } catch {
    const cached = await caches.match(request)
    return cached || new Response('Offline', { status: 503 })
  }
}

// ─── Message depuis l'application — notification en arrière-plan ───
self.addEventListener('message', (e) => {
  if (e.data && e.data.type === 'SHOW_NOTIFICATION') {
    self.registration.showNotification(e.data.title, {
      body: e.data.body,
      icon: e.data.icon || '/icons/icon-192.svg',
      badge: '/icons/icon-72.svg',
      vibrate: [200, 100, 200],
      tag: 'hab-notification',
      renotify: true,
    })
  }
})

// ─── Push event — notifications serveur (même navigateur fermé) ───
self.addEventListener('push', (e) => {
  let data = { title: 'HABITATUM', body: 'Mise à jour disponible', icon: '/icons/icon-192.svg', tag: 'hab-notification' }
  if (e.data) {
    try { data = { ...data, ...e.data.json() } } catch {}
  }
  e.waitUntil(
    self.registration.showNotification(data.title, {
      body: data.body,
      icon: data.icon,
      badge: '/icons/icon-72.svg',
      vibrate: [200, 100, 200],
      tag: data.tag || 'hab-notification',
      renotify: true,
    })
  )
})

// ─── Clic sur la notification — ouvre l'application ───
self.addEventListener('notificationclick', (e) => {
  e.notification.close()
  e.waitUntil(clients.openWindow('/dashboard-locataire'))
})
