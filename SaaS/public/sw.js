// HABITATUM — Service Worker
// Gère les notifications push et les messages de l'application

const CACHE = 'hab-v1'
const ASSETS = ['/build/assets/app.css', '/build/assets/app.js']

self.addEventListener('install', (e) => {
  self.skipWaiting()
  e.waitUntil(caches.open(CACHE).then((c) => c.addAll(ASSETS)))
})

self.addEventListener('activate', (e) => {
  e.waitUntil(clients.claim())
})

// Message depuis l'application — affiche une notification même en arrière-plan
self.addEventListener('message', (e) => {
  if (e.data && e.data.type === 'SHOW_NOTIFICATION') {
    self.registration.showNotification(e.data.title, {
      body: e.data.body,
      icon: e.data.icon || '/favicon.ico',
      badge: '/favicon.ico',
      vibrate: [200, 100, 200],
      tag: 'hab-notification',
      renotify: true,
      requireInteraction: true,
    })
  }
})

// Push event — notifications envoyées depuis le serveur
self.addEventListener('push', (e) => {
  let data = { title: 'HABITATUM', body: 'Mise à jour disponible', icon: '/favicon.ico' }
  if (e.data) {
    try { data = { ...data, ...e.data.json() } } catch {}
  }
  e.waitUntil(
    self.registration.showNotification(data.title, {
      body: data.body,
      icon: data.icon,
      badge: '/favicon.ico',
      vibrate: [200, 100, 200],
      tag: 'hab-notification',
      renotify: true,
      requireInteraction: true,
    })
  )
})

// Click sur la notification — ouvre l'application
self.addEventListener('notificationclick', (e) => {
  e.notification.close()
  e.waitUntil(clients.openWindow('/'))
})
