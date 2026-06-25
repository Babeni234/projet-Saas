/* ═══════════════════════════════════════════════════════════════════
   ImmoTok v4.0 — Script JS Complet & Fusionné
   Fonctionnalités : feed vidéo TikTok, profils entreprise/particulier,
   swipes latéraux, chat IA contextuel, messagerie DM TikTok-style,
   partage WhatsApp enrichi, filtres, notifications, auth, splash screen,
   i18n FR/EN, paramètres, sauvegarde, scroll infini, son, likes, etc.
   Author: ImmoTok Team — CI 2025
═══════════════════════════════════════════════════════════════════ */
'use strict';

/* ══════════════════════════════════════════════════════════════════
   0. SPLASH SCREEN — s'exécute immédiatement au chargement
══════════════════════════════════════════════════════════════════ */
(function initSplash() {
  var s = document.createElement('div');
  s.id = 'splashScreen';
  s.style.cssText = [
    'position:fixed','inset:0','z-index:99999',
    'background:linear-gradient(135deg,#07080d 0%,#0d0a1a 50%,#07080d 100%)',
    'display:flex','flex-direction:column','align-items:center','justify-content:center','gap:18px',
    "font-family:'Bricolage Grotesque',system-ui,sans-serif",
    'transition:opacity .6s cubic-bezier(.4,0,.2,1),transform .6s cubic-bezier(.4,0,.2,1)',
    'overflow:hidden'
  ].join(';');

  // Particules de fond
  var particlesHTML = '';
  for (var pi = 0; pi < 12; pi++) {
    var px = Math.random() * 100, py = Math.random() * 100;
    var ps = (Math.random() * 4 + 2).toFixed(1);
    var pa = (Math.random() * 0.3 + 0.05).toFixed(2);
    var pd = (Math.random() * 3 + 1).toFixed(1);
    particlesHTML += '<div style="position:absolute;left:'+px+'%;top:'+py+'%;width:'+ps+'px;height:'+ps+'px;background:#ff2d55;border-radius:50%;opacity:'+pa+';animation:splashFloat '+pd+'s ease-in-out infinite alternate"></div>';
  }

  s.innerHTML = '<style>@keyframes splashFloat{0%{transform:translateY(0) scale(1)}100%{transform:translateY(-20px) scale(1.3)}}'
    + '@keyframes splashPulse{0%,100%{box-shadow:0 0 0 0 rgba(255,45,85,.4)}50%{box-shadow:0 0 0 20px rgba(255,45,85,0)}}'
    + '@keyframes splashBar{0%{width:0}100%{width:130px}}</style>'
    + particlesHTML
    + '<div id="_si" style="font-size:80px;color:#ff2d55;opacity:0;transform:scale(.2) rotate(-20deg);transition:all .7s cubic-bezier(.34,1.56,.64,1);animation:splashPulse 2s ease-in-out infinite .8s;filter:drop-shadow(0 0 20px rgba(255,45,85,.5))">'
    + '<i class="fas fa-play-circle"></i></div>'
    + '<div id="_st" style="font-size:36px;font-weight:900;color:#fff;letter-spacing:-1px;opacity:0;transform:translateY(20px);transition:all .55s .2s cubic-bezier(.4,0,.2,1)">'
    + 'Immo<span style="color:#ff2d55;text-shadow:0 0 20px rgba(255,45,85,.6)">Tok</span></div>'
    + '<div id="_ss" style="font-size:14px;color:#5a6280;letter-spacing:.5px;opacity:0;transition:opacity .45s .4s;text-transform:uppercase">L\'immobilier en vidéo</div>'
    + '<div id="_sb" style="height:3px;background:linear-gradient(90deg,#ff2d55,#ff6b8a);border-radius:3px;margin-top:8px;opacity:0;width:0;transition:opacity .3s .6s,width 1s .6s cubic-bezier(.4,0,.2,1);box-shadow:0 0 10px rgba(255,45,85,.4)"></div>'
    + '<div id="_sv" style="font-size:11px;color:#3a4060;opacity:0;transition:opacity .3s .9s">v4.0 — Abidjan, Côte d\'Ivoire</div>';

  document.body.appendChild(s);

  requestAnimationFrame(function () {
    requestAnimationFrame(function () {
      var ico = document.getElementById('_si');
      var txt = document.getElementById('_st');
      var sub = document.getElementById('_ss');
      var bar = document.getElementById('_sb');
      var ver = document.getElementById('_sv');
      if (ico) { ico.style.opacity = '1'; ico.style.transform = 'scale(1) rotate(0)'; }
      if (txt) { txt.style.opacity = '1'; txt.style.transform = 'translateY(0)'; }
      if (sub) sub.style.opacity = '1';
      if (bar) { bar.style.opacity = '1'; bar.style.width = '130px'; }
      if (ver) ver.style.opacity = '1';
    });
  });

  setTimeout(function () {
    s.style.opacity = '0';
    s.style.transform = 'scale(1.06)';
    setTimeout(function () { if (s.parentNode) s.remove(); }, 650);
  }, 2800);
})();

/* ══════════════════════════════════════════════════════════════════
   1. TRADUCTIONS FR / EN
══════════════════════════════════════════════════════════════════ */
var TR = {
  fr: {
    'nav.foryou': 'Pour vous', 'nav.subs': 'Abonnements', 'nav.explore': 'Explorer',
    'nav.home': 'Accueil', 'nav.discover': 'Explorer', 'nav.notif': 'Alertes', 'nav.me': 'Moi',
    'comments.title': 'Commentaires', 'comments.placeholder': 'Ajouter un commentaire…',
    'share.title': 'Partager ce bien', 'share.copy': 'Copier', 'share.save': 'Sauver',
    'more.report': 'Signaler ce bien', 'more.save': 'Sauvegarder',
    'more.notint': 'Pas intéressé', 'more.copylink': 'Copier le lien',
    'more.download': 'Télécharger', 'more.cancel': 'Annuler',
    'report.title': 'Signaler ce contenu', 'report.lead': 'Pourquoi signalez-vous ce bien ?',
    'report.note': 'Précisions (optionnel)…', 'report.send': 'Envoyer le signalement',
    'filter.title': 'Filtrer les biens', 'filter.trans': 'Transaction',
    'filter.type': 'Type de bien', 'filter.budget': 'Budget max (FCFA)',
    'filter.surface': 'Surface min (m²)', 'filter.city': 'Ville / Quartier',
    'filter.reset': 'Réinitialiser', 'filter.apply': 'Appliquer',
    'music.title': 'Son original', 'prop.contact': 'Contacter', 'prop.reserve': 'Réserver',
    'discover': 'Découvrir l\'offre',
    'reserve.title': 'Réserver une visite', 'reserve.coords': 'Vos coordonnées',
    'reserve.firstname': 'Prénom *', 'reserve.lastname': 'Nom *',
    'reserve.phone': 'Téléphone *', 'reserve.email': 'Email',
    'reserve.slot': 'Créneau souhaité', 'reserve.date': 'Date *', 'reserve.time': 'Heure',
    'reserve.visittype': 'Type de visite', 'reserve.message': 'Message',
    'reserve.finance': 'Financement', 'reserve.cash': 'Fonds propres',
    'reserve.credit': 'Crédit bancaire', 'reserve.mixed': 'Mixte',
    'reserve.note': 'Données protégées. Réponse sous 24h.', 'reserve.send': 'Envoyer',
    'contact.title': 'Contacter l\'agence', 'contact.msg': 'Message direct',
    'contact.yourname': 'Votre nom', 'contact.yourphone': 'Téléphone',
    'contact.subject': 'Sujet', 'contact.message': 'Message *',
    'search.recent': 'Recherches récentes', 'search.trending': 'Tendances',
    'inbox.title': 'Notifications', 'inbox.all': 'Tout',
    'inbox.reservations': 'Réservations', 'inbox.messages': 'Messages', 'inbox.alerts': 'Alertes prix',
    'me.title': 'Mon espace', 'settings.title': 'Paramètres',
    'chat.botname': 'ImmoBot Assistant', 'chat.online': 'En ligne · Répond instantanément',
    'chat.placeholder': 'Écrivez votre message…', 'success.close': 'Parfait, merci !',
    'cancel': 'Annuler', 'close': 'Fermer', 'send': 'Envoyer',
    'liked': 'J\'aime', 'saved': 'Sauvegardé', 'following': 'Abonné',
    'follow': 'S\'abonner', 'unfollow': 'Se désabonner',
    'seeMore': 'voir plus', 'seeLess': 'voir moins',
    'empty.feed': 'Aucun bien ne correspond à vos filtres',
    'empty.subs': 'Abonnez-vous à des comptes\npour voir leur contenu ici',
    'auth.login': 'Se connecter', 'auth.register': 'S\'inscrire',
    'dm.request': 'Demande de message', 'dm.accept': 'Accepter', 'dm.decline': 'Refuser',
    'dm.pending': 'En attente d\'acceptation', 'dm.placeholder': 'Votre message…'
  },
  en: {
    'nav.foryou': 'For You', 'nav.subs': 'Following', 'nav.explore': 'Explore',
    'nav.home': 'Home', 'nav.discover': 'Explore', 'nav.notif': 'Alerts', 'nav.me': 'Me',
    'comments.title': 'Comments', 'comments.placeholder': 'Add a comment…',
    'share.title': 'Share this property', 'share.copy': 'Copy', 'share.save': 'Save',
    'more.report': 'Report property', 'more.save': 'Save',
    'more.notint': 'Not interested', 'more.copylink': 'Copy link',
    'more.download': 'Download', 'more.cancel': 'Cancel',
    'report.title': 'Report content', 'report.lead': 'Why are you reporting this?',
    'report.note': 'Details (optional)…', 'report.send': 'Send report',
    'filter.title': 'Filter properties', 'filter.trans': 'Transaction',
    'filter.type': 'Property type', 'filter.budget': 'Max budget (FCFA)',
    'filter.surface': 'Min surface (m²)', 'filter.city': 'City / District',
    'filter.reset': 'Reset', 'filter.apply': 'Apply',
    'music.title': 'Original sound', 'prop.contact': 'Contact', 'prop.reserve': 'Book visit',
    'discover': 'Discover offer',
    'reserve.title': 'Book a visit', 'reserve.coords': 'Your details',
    'reserve.firstname': 'First name *', 'reserve.lastname': 'Last name *',
    'reserve.phone': 'Phone *', 'reserve.email': 'Email',
    'reserve.slot': 'Preferred slot', 'reserve.date': 'Date *', 'reserve.time': 'Time',
    'reserve.visittype': 'Visit type', 'reserve.message': 'Message',
    'reserve.finance': 'Financing', 'reserve.cash': 'Own funds',
    'reserve.credit': 'Bank credit', 'reserve.mixed': 'Mixed',
    'reserve.note': 'Protected data. Response within 24h.', 'reserve.send': 'Send',
    'contact.title': 'Contact agency', 'contact.msg': 'Direct message',
    'contact.yourname': 'Your name', 'contact.yourphone': 'Phone',
    'contact.subject': 'Subject', 'contact.message': 'Message *',
    'search.recent': 'Recent searches', 'search.trending': 'Trending',
    'inbox.title': 'Notifications', 'inbox.all': 'All',
    'inbox.reservations': 'Bookings', 'inbox.messages': 'Messages', 'inbox.alerts': 'Price alerts',
    'me.title': 'My space', 'settings.title': 'Settings',
    'chat.botname': 'ImmoBot Assistant', 'chat.online': 'Online · Instant responses',
    'chat.placeholder': 'Write your message…', 'success.close': 'Great, thanks!',
    'cancel': 'Cancel', 'close': 'Close', 'send': 'Send',
    'liked': 'Liked', 'saved': 'Saved', 'following': 'Following',
    'follow': 'Follow', 'unfollow': 'Unfollow',
    'seeMore': 'see more', 'seeLess': 'see less',
    'empty.feed': 'No properties match your filters',
    'empty.subs': 'Follow accounts to see\ntheir content here',
    'auth.login': 'Sign in', 'auth.register': 'Sign up',
    'dm.request': 'Message request', 'dm.accept': 'Accept', 'dm.decline': 'Decline',
    'dm.pending': 'Waiting for acceptance', 'dm.placeholder': 'Your message…'
  }
};

/* ══════════════════════════════════════════════════════════════════
   2. DONNÉES — COMPTES & PROPRIÉTÉS
══════════════════════════════════════════════════════════════════ */
var ACCOUNTS = [
  {
    id: 'a1', type: 'enterprise', name: 'Prestige Immobilier CI',
    handle: '@prestige.immo.ci', avatar: 'https://i.pravatar.cc/150?img=11',
    verified: true, followers: 142000, following: 230, videos: 87, likes: 2100000,
    bio: "L'excellence immobilière depuis 2005. Villas, appartements, bureaux premium à Abidjan et en Côte d'Ivoire.",
    website: 'https://prestige-immo.ci', phone: '+225 27 22 44 55 66',
    email: 'contact@prestige-immo.ci', address: 'Plateau, Abidjan',
    founded: '2005', employees: '45-60',
    specialties: ['Vente', 'Location', 'Gestion locative'],
    cities: ['Abidjan', 'Yamoussoukro', 'Bouaké'],
    licenseNo: 'CI-IMM-2005-047',
    coverGradient: 'linear-gradient(135deg,#0a0f2e,#1a0530,#0a1a14)',
    subscribed: false
  },
  {
    id: 'a2', type: 'enterprise', name: 'Alpha Properties',
    handle: '@alpha.properties', avatar: 'https://i.pravatar.cc/150?img=15',
    verified: true, followers: 88500, following: 180, videos: 56, likes: 980000,
    bio: "Votre partenaire de confiance pour l'investissement immobilier en Afrique de l'Ouest.",
    website: 'https://alpha-properties.net', phone: '+225 27 20 33 44 55',
    email: 'info@alpha-properties.net', address: 'Cocody, Abidjan',
    founded: '2012', employees: '20-35',
    specialties: ['Investissement', 'Neuf', 'Location meublée'],
    cities: ['Abidjan', 'Dakar', 'Lomé'],
    licenseNo: 'CI-IMM-2012-183',
    coverGradient: 'linear-gradient(135deg,#071a2e,#0a2535,#071420)',
    subscribed: false
  },
  {
    id: 'a3', type: 'enterprise', name: 'ImmoVerde Côte d\'Ivoire',
    handle: '@immoverde.ci', avatar: 'https://i.pravatar.cc/150?img=22',
    verified: false, followers: 34200, following: 420, videos: 31, likes: 445000,
    bio: "Spécialiste de l'immobilier vert et durable. Constructions éco-responsables.",
    website: '', phone: '+225 07 58 99 44 11',
    email: 'hello@immoverde.ci', address: 'Marcory, Abidjan',
    founded: '2018', employees: '10-20',
    specialties: ['Éco-construction', 'Location', 'Viager'],
    cities: ['Abidjan', 'San-Pédro'],
    licenseNo: 'CI-IMM-2018-312',
    coverGradient: 'linear-gradient(135deg,#071a10,#0a2a14,#07180e)',
    subscribed: false
  },
  {
    id: 'p1', type: 'particulier', name: 'Kouamé Assouman',
    handle: '@kouame.asso', avatar: 'https://i.pravatar.cc/150?img=33',
    verified: false, followers: 1250, following: 340, videos: 8, likes: 22000,
    bio: "Propriétaire, je vends ma villa à Cocody. Direct, sans intermédiaire.",
    phone: '+225 07 11 22 33 44', email: 'kouame.asso@gmail.com', subscribed: false
  },
  {
    id: 'p2', type: 'particulier', name: 'Fatou Diallo',
    handle: '@fatou.diallo.immo', avatar: 'https://i.pravatar.cc/150?img=44',
    verified: false, followers: 3100, following: 210, videos: 12, likes: 67000,
    bio: "J'investis dans l'immobilier depuis 10 ans. Je partage mes biens en location.",
    phone: '+225 05 77 88 99 00', email: 'fatou.d@outlook.com', subscribed: false
  },
  {
    id: 'p3', type: 'particulier', name: 'Ibrahim Koné',
    handle: '@ibrahim.kone', avatar: 'https://i.pravatar.cc/150?img=55',
    verified: false, followers: 780, following: 150, videos: 5, likes: 9800,
    bio: "Studio à Treichville, quartier calme, bien entretenu. Urgent.",
    phone: '+225 01 44 55 66 77', email: 'ibkone@yahoo.fr', subscribed: false
  }
];

var PROPERTIES = [
  {
    id: 'v1', accountId: 'a1', title: 'Villa Luxe Cocody Riviera',
    type: 'villa', transaction: 'vente', price: 285000000, priceLabel: '285 M FCFA',
    surface: 380, rooms: 6, bathrooms: 4, city: 'Cocody', neighborhood: 'Riviera 3',
    description: 'Magnifique villa contemporaine de 380m² avec piscine privée, jardin paysager et finitions haut de gamme. Quartier résidentiel sécurisé, proche écoles internationales.',
    features: ['Piscine', 'Jardin', 'Garage 3 voitures', 'Groupe électrogène', 'Sécurité 24/7', 'Cuisine équipée'],
    tags: ['#villacocody', '#luxe', '#piscine', '#vente'],
    music: { title: 'Summer Vibes', artist: 'Afrobeats Studio', uses: 12400 },
    likes: 4821, comments: 234, shares: 891, saves: 1203,
    videoUrl: 'Nanatsu no Taizai Saison 2 E13 VF.mp4',
    // poster: 'https://images.unsplash.com/photo-1613490493576-7fde63acd811?w=500&q=80',
    liked: false, saved: false, views: 128000
  },
  {
    id: 'v2', accountId: 'a2', title: 'Appartement T3 Plateau Vue Lagune',
    type: 'appartement', transaction: 'location', price: 350000, priceLabel: '350K/mois',
    surface: 95, rooms: 3, bathrooms: 2, city: 'Plateau', neighborhood: 'Centre Plateau',
    description: 'Bel appartement T3 au 8ème étage avec vue imprenable sur la lagune. Meublé haut de gamme, climatisation centralisée, parking sécurisé inclus.',
    features: ['Meublé', 'Climatisation', 'Vue lagune', 'Parking', 'Gardiennage', 'Ascenseur'],
    tags: ['#plateau', '#appartement', '#location', '#meuble'],
    music: { title: 'City Dreams', artist: 'Lo-Fi Collective', uses: 8720 },
    likes: 2340, comments: 89, shares: 445, saves: 678,
    videoUrl: 'Bleach.mp4',
    // poster: 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=500&q=80',
    liked: false, saved: false, views: 67000
  },
  {
    id: 'v3', accountId: 'a1', title: 'Bureau Open Space Marcory Zone 4',
    type: 'bureau', transaction: 'location', price: 850000, priceLabel: '850K/mois',
    surface: 220, rooms: 0, bathrooms: 3, city: 'Marcory', neighborhood: 'Zone 4',
    description: 'Plateau bureau moderne de 220m² entièrement open space. Fibre optique dédiée, salle de conférence, kitchenette. Idéal startup ou PME.',
    features: ['Open space', 'Salle conf.', 'Fibre 1Gbps', 'Clim centrale', 'Parking 10 places'],
    tags: ['#bureau', '#marcory', '#zone4', '#professionnel'],
    music: { title: 'Focus Mode', artist: 'Chill Work', uses: 3210 },
    likes: 892, comments: 34, shares: 201, saves: 312,
    videoUrl: 'Nanatsu no Taizai Saison 2 E13 VF.mp4',
    // poster: 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=500&q=80',
    liked: false, saved: false, views: 23400
  },
  {
    id: 'v4', accountId: 'a3', title: 'Villa Éco San-Pédro Durable',
    type: 'villa', transaction: 'vente', price: 95000000, priceLabel: '95 M FCFA',
    surface: 180, rooms: 4, bathrooms: 2, city: 'San-Pédro', neighborhood: 'Quartier Résidentiel',
    description: 'Villa éco-construite avec matériaux durables. Panneaux solaires, récupération eau de pluie. Empreinte carbone minimale pour un confort maximal.',
    features: ['Solaire 5kW', 'Récup. eau', 'Isolation thermique', 'Jardin bio', 'Garage'],
    tags: ['#eco', '#sanpedro', '#durable', '#solaire'],
    music: { title: 'Nature Sounds', artist: 'Eco Studio', uses: 1890 },
    likes: 1456, comments: 67, shares: 234, saves: 445,
    videoUrl: 'Bleach.mp4',
    // poster: 'https://images.unsplash.com/photo-1600585154526-990dced4db0d?w=500&q=80',
    liked: false, saved: false, views: 34500
  },
  {
    id: 'v5', accountId: 'p1', title: 'Villa Familiale Cocody Danga',
    type: 'villa', transaction: 'vente', price: 145000000, priceLabel: '145 M FCFA',
    surface: 250, rooms: 5, bathrooms: 3, city: 'Cocody', neighborhood: 'Danga',
    description: 'Je vends ma villa familiale, bien entretenue, dans un quartier calme de Cocody. Jardin ombragé, puits, groupe électrogène 12KVA. Vente directe propriétaire, négociable.',
    features: ['Jardin', 'Puits', 'Groupe 12KVA', 'Dépendance', 'Parking 4 voitures'],
    tags: ['#directproprio', '#villacocody', '#negociable'],
    music: { title: 'Tropical House', artist: 'Afro Beats', uses: 5600 },
    likes: 678, comments: 45, shares: 123, saves: 234,
    videoUrl: 'Nanatsu no Taizai Saison 2 E13 VF.mp4',
    // poster: 'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?w=500&q=80',
    liked: false, saved: false, views: 18900
  },
  {
    id: 'v6', accountId: 'p2', title: 'Studio Meublé Yopougon Sideci',
    type: 'studio', transaction: 'location', price: 85000, priceLabel: '85K/mois',
    surface: 32, rooms: 1, bathrooms: 1, city: 'Yopougon', neighborhood: 'Sideci',
    description: 'Studio meublé propre et sécurisé. Cuisine équipée, eau chaude, ventilateur. Idéal étudiant ou jeune actif. Charges incluses.',
    features: ['Meublé', 'Eau chaude', 'Sécurisé', 'Charges incluses', 'Wifi'],
    tags: ['#studio', '#yopougon', '#location', '#etudiant'],
    music: { title: 'Afro Pop 2024', artist: 'DJ Pulse CI', uses: 22100 },
    likes: 2100, comments: 98, shares: 341, saves: 567,
    videoUrl: 'Nanatsu no Taizai Saison 2 E13 VF.mp4',
    // poster: 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=500&q=80',
    liked: false, saved: false, views: 45600
  },
  {
    id: 'v7', accountId: 'a2', title: 'Terrain Constructible Bingerville',
    type: 'terrain', transaction: 'vente', price: 38000000, priceLabel: '38 M FCFA',
    surface: 600, rooms: 0, bathrooms: 0, city: 'Bingerville', neighborhood: 'Centre',
    description: 'Terrain viabilisé de 600m², titre foncier propre. Eau, électricité, voirie bitumée. Idéal construction villa ou immeuble R+2.',
    features: ['Titre foncier', 'Eau & électricité', 'Voirie bitumée', 'Angle de rue', 'Clôturé'],
    tags: ['#terrain', '#bingerville', '#investissement', '#construction'],
    music: { title: 'Ambiance CI', artist: 'Zouglou Mix', uses: 7800 },
    likes: 1234, comments: 56, shares: 267, saves: 389,
    videoUrl: 'Nanatsu no Taizai Saison 2 E13 VF.mp4',
    // poster: 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=500&q=80',
    liked: false, saved: false, views: 29000
  },
  {
    id: 'v8', accountId: 'p3', title: 'Studio Treichville Calme',
    type: 'studio', transaction: 'location', price: 65000, priceLabel: '65K/mois',
    surface: 28, rooms: 1, bathrooms: 1, city: 'Treichville', neighborhood: 'Quartier Centre',
    description: 'Studio calme au 2ème étage, bien entretenu, sécurisé. Proche marché et transports. Caution 2 mois. Disponible immédiatement.',
    features: ['Calme', 'Sécurisé', 'Transport proche', 'Disponible immédiat'],
    tags: ['#studio', '#treichville', '#urgent', '#location'],
    music: { title: 'Coupé-Décalé Mix', artist: 'DJ Arafat Legacy', uses: 34500 },
    likes: 987, comments: 33, shares: 178, saves: 221,
    videoUrl: 'Nanatsu no Taizai Saison 2 E13 VF.mp4',
    // poster: 'https://images.unsplash.com/photo-1554995207-c18c203602cb?w=500&q=80',
    liked: false, saved: false, views: 12300
  },
  {
    id: 'v9', accountId: 'a1', title: 'Penthouse Plateau Prestige 360°',
    type: 'appartement', transaction: 'vente', price: 520000000, priceLabel: '520 M FCFA',
    surface: 490, rooms: 7, bathrooms: 5, city: 'Plateau', neighborhood: 'Centre Affaires',
    description: 'Penthouse d\'exception au dernier étage de la Tour Prestige. Vue panoramique 360° sur Abidjan, terrasse 80m², cuisine Bulthaup, home cinéma, domotique complète.',
    features: ['Terrasse 80m²', 'Vue panoramique', 'Domotique', 'Home cinéma', 'Cave à vin', 'Concierge 24/7'],
    tags: ['#penthouse', '#plateau', '#luxe', '#exceptionnel'],
    music: { title: 'Luxury Lounge', artist: 'Monte Carlo Beats', uses: 4500 },
    likes: 8934, comments: 445, shares: 2341, saves: 3102,
    videoUrl: 'Bleach.mp4',
    // poster: 'https://images.unsplash.com/photo-1567496898669-ee935f5f647a?w=500&q=80',
    liked: false, saved: false, views: 287000
  },
  {
    id: 'v10', accountId: 'a3', title: 'Immeuble Rapport R+3 Marcory',
    type: 'commerce', transaction: 'vente', price: 320000000, priceLabel: '320 M FCFA',
    surface: 850, rooms: 12, bathrooms: 8, city: 'Marcory', neighborhood: 'Remblai',
    description: 'Immeuble de rapport R+3, 8 appartements et 4 commerces en rez-de-chaussée. Taux d\'occupation 95%. Rentabilité nette 9%/an.',
    features: ['R+3', '8 appartements', '4 commerces', 'Rentabilité 9%', 'Titre foncier', 'Gardien'],
    tags: ['#investissement', '#immeuble', '#marcory', '#rentable'],
    music: { title: 'Business Mode', artist: 'AfroTech', uses: 6700 },
    likes: 3421, comments: 178, shares: 892, saves: 1234,
    videoUrl: 'Nanatsu no Taizai Saison 2 E13 VF.mp4',
    // poster: 'https://images.unsplash.com/photo-1486325212027-8081e485255e?w=500&q=80',
    liked: false, saved: false, views: 89000
  }
];

var COMMENTS_DATA = {
  v1: [
    { id: 'c1', name: 'Fatou Diallo', avatar: 'https://i.pravatar.cc/150?img=44', text: 'Superbe villa ! Le jardin est magnifique. Quel est le délai de disponibilité ?', time: '2h', likes: 34, liked: false, badge: null },
    { id: 'c2', name: 'Kouamé Assouman', avatar: 'https://i.pravatar.cc/150?img=33', text: 'La piscine est vraiment top. Y a-t-il une possibilité de visite virtuelle ?', time: '4h', likes: 12, liked: false, badge: null },
    { id: 'c3', name: 'Prestige Immobilier CI', avatar: 'https://i.pravatar.cc/150?img=11', text: 'Bonjour ! Bien disponible immédiatement. Contactez-nous au +225 27 22 44 55 66 🏡', time: '3h', likes: 89, liked: false, badge: 'agency' }
  ],
  v2: [
    { id: 'c4', name: 'Marie K.', avatar: '', text: 'Vue sur la lagune, c\'est mon rêve ! Les animaux sont acceptés ?', time: '1h', likes: 8, liked: false, badge: null },
    { id: 'c5', name: 'Alpha Properties', avatar: 'https://i.pravatar.cc/150?img=15', text: 'Bonjour Marie, les petits animaux sont acceptés sous conditions. Contactez-nous !', time: '45min', likes: 15, liked: false, badge: 'agency' }
  ]
};

var NOTIFICATIONS_DATA = [
  { id: 'n1', type: 'reservation', icon: '📅', iconBg: 'rgba(66,133,244,.15)', title: 'Visite confirmée', text: 'Votre visite du Villa Luxe Cocody est confirmée demain à 10h00.', time: '5 min', unread: true, category: 'reservations' },
  { id: 'n2', type: 'like', icon: '❤️', iconBg: 'rgba(255,45,85,.12)', title: 'Nouveau like', text: 'Fatou Diallo a aimé votre commentaire sur Villa Cocody.', time: '12 min', unread: true, category: 'messages' },
  { id: 'n3', type: 'alert', icon: '💰', iconBg: 'rgba(255,201,60,.12)', title: 'Alerte prix !', text: 'Le bien "Appartement T3 Plateau" a baissé ! Nouveau : 320K/mois.', time: '1h', unread: true, category: 'alerts' },
  { id: 'n4', type: 'follow', icon: '👤', iconBg: 'rgba(0,223,200,.1)', title: 'Nouvel abonné', text: 'Ibrahim Koné s\'est abonné à votre compte.', time: '2h', unread: false, category: 'messages' },
  { id: 'n5', type: 'comment', icon: '💬', iconBg: 'rgba(168,85,247,.12)', title: 'Nouveau commentaire', text: 'Prestige Immobilier a répondu à votre commentaire.', time: '3h', unread: false, category: 'messages' },
  { id: 'n6', type: 'alert', icon: '🏠', iconBg: 'rgba(42,201,122,.1)', title: 'Nouveau bien', text: 'Alpha Properties vient de publier un bien à Cocody.', time: '5h', unread: false, category: 'alerts' },
  { id: 'n7', type: 'reservation', icon: '✅', iconBg: 'rgba(42,201,122,.1)', title: 'Demande acceptée', text: 'Votre demande de visite pour Studio Yopougon a été acceptée.', time: '1j', unread: false, category: 'reservations' }
];

/* ══════════════════════════════════════════════════════════════════
   3. ÉTAT GLOBAL (STATE)
══════════════════════════════════════════════════════════════════ */
var STATE = {
  lang: localStorage.getItem('immotok_lang') || 'fr',
  currentFeed: 'foryou',
  currentView: 'home',
  muted: true,
  currentReelIndex: 0,
  activeSheets: [],
  activePanels: [],
  activeModal: null,
  currentProperty: null,
  comments: JSON.parse(JSON.stringify(COMMENTS_DATA)),
  notifications: JSON.parse(JSON.stringify(NOTIFICATIONS_DATA)),
  unreadCount: NOTIFICATIONS_DATA.filter(function (n) { return n.unread; }).length,
  searchHistory: JSON.parse(localStorage.getItem('immotok_history') || '[]'),
  savedItems: JSON.parse(localStorage.getItem('immotok_saved') || '[]'),
  likedItems: JSON.parse(localStorage.getItem('immotok_liked') || '[]'),
  filters: { trans: 'all', type: 'all', budget: 500000000, surface: 0, city: '' },
  user: JSON.parse(localStorage.getItem('immotok_user') || 'null'),
  settings: JSON.parse(localStorage.getItem('immotok_settings') || JSON.stringify({
    notifications: true, vibrations: true, autoplay: true, dataSaver: false,
    language: 'fr', privacyProfile: 'public', privacyLikes: 'public',
    emailNotifs: true, pushNotifs: true, priceAlerts: true, newListings: true
  })),
  chatMessages: [],
  dmConversations: {},   // { accountId: { status: 'pending'|'accepted', messages: [] } }
  currentDmAccountId: null,
  properties: JSON.parse(JSON.stringify(PROPERTIES)),
  accounts: JSON.parse(JSON.stringify(ACCOUNTS)),
  iObserver: null,
  currentVideoEl: null,
  scrubbing: false,
  currentProfileAccountId: null,
  // Mémorisation de la position de scroll pour ne pas la perturber lors du changement de langue
  feedScrollTop: 0
};

/* ══════════════════════════════════════════════════════════════════
   4. UTILITAIRES
══════════════════════════════════════════════════════════════════ */
function $(id) { return document.getElementById(id); }
function $$(sel) { return document.querySelectorAll(sel); }
function t(key) { return (TR[STATE.lang] || TR.fr)[key] || (TR.fr)[key] || key; }
function fmtN(n) { if (!n || isNaN(n)) return '0'; if (n >= 1e6) return (n / 1e6).toFixed(1) + 'M'; if (n >= 1e3) return (n / 1e3).toFixed(1) + 'K'; return String(n); }
function fmtT(s) { if (!s || isNaN(s)) return '0:00'; var m = Math.floor(s / 60), sc = Math.floor(s % 60); return m + ':' + sc.toString().padStart(2, '0'); }
function fmtP(n) { if (n >= 1000000) return (n / 1000000).toFixed(0) + ' M FCFA'; if (n >= 1000) return (n / 1000).toFixed(0) + ' K FCFA'; return (n || 0).toLocaleString() + ' FCFA'; }
function clamp(v, a, b) { return Math.max(a, Math.min(b, v)); }
function shuffleArr(arr) {
  var a = arr.slice();
  for (var i = a.length - 1; i > 0; i--) {
    var j = Math.floor(Math.random() * (i + 1));
    var tmp = a[i]; a[i] = a[j]; a[j] = tmp;
  }
  return a;
}
function getAccount(id) { return STATE.accounts.find(function (a) { return a.id === id; }); }
function getProperty(id) { return STATE.properties.find(function (p) { return p.id === id; }); }
function timeNow() { return new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }); }

var _toastT;
function showToast(msg, icon, color, dur) {
  icon = icon || 'fa-check-circle'; color = color || '#2ac97a'; dur = dur || 2800;
  var el = $('toast'), ico = $('toastIco'), txt = $('toastTxt');
  if (!el) return;
  if (ico) { ico.className = 'fas ' + icon; ico.style.color = color; }
  if (txt) txt.textContent = msg;
  el.classList.add('show');
  clearTimeout(_toastT);
  _toastT = setTimeout(function () { el.classList.remove('show'); }, dur);
}

function saveStorage() {
  try {
    localStorage.setItem('immotok_lang', STATE.lang);
    localStorage.setItem('immotok_saved', JSON.stringify(STATE.savedItems));
    localStorage.setItem('immotok_liked', JSON.stringify(STATE.likedItems));
    localStorage.setItem('immotok_history', JSON.stringify(STATE.searchHistory));
    localStorage.setItem('immotok_settings', JSON.stringify(STATE.settings));
    if (STATE.user) localStorage.setItem('immotok_user', JSON.stringify(STATE.user));
  } catch (e) { }
}

/* ══════════════════════════════════════════════════════════════════
   5. i18n
══════════════════════════════════════════════════════════════════ */
function applyTranslations() {
  $$('[data-i18n]').forEach(function (el) {
    var v = t(el.getAttribute('data-i18n'));
    if (v) el.textContent = v;
  });
  $$('[data-i18n-placeholder]').forEach(function (el) {
    var v = t(el.getAttribute('data-i18n-placeholder'));
    if (v) el.placeholder = v;
  });
  document.documentElement.lang = STATE.lang;
  var ll = $('langLabel');
  if (ll) ll.textContent = STATE.lang.toUpperCase();
}

function toggleLang() {
  // Sauvegarder la position actuelle AVANT le changement de langue
  var feed = $('feed');
  if (feed) STATE.feedScrollTop = feed.scrollTop;

  STATE.lang = STATE.lang === 'fr' ? 'en' : 'fr';
  STATE.settings.language = STATE.lang;
  saveStorage();

  var btn = $('langBtn');
  if (btn) { btn.classList.add('lang-switching'); setTimeout(function () { btn.classList.remove('lang-switching'); }, 300); }

  // Appliquer les traductions SANS reconstruire le feed
  applyTranslations();

  // Mettre à jour uniquement les éléments de texte du feed déjà rendus (badges, boutons)
  updateFeedTranslations();

  // Restaurer la position de scroll
  if (feed) setTimeout(function () { feed.scrollTop = STATE.feedScrollTop; }, 30);

  // Rafraîchir les panels ouverts si besoin
  if ($('mePanel') && $('mePanel').classList.contains('open')) renderMePanel();
  if ($('inboxPanel') && $('inboxPanel').classList.contains('open')) renderInbox('all');

  showToast(
    STATE.lang === 'fr' ? 'Langue : Français 🇫🇷' : 'Language: English 🇬🇧',
    'fa-language', '#4285f4'
  );
}

function updateFeedTranslations() {
  // Mettre à jour les boutons "voir plus/voir moins" visibles
  $$('.see-more-btn').forEach(function (btn) {
    var dk = btn.getAttribute('data-desc');
    if (!dk) return;
    var desc = document.getElementById('desc-' + dk);
    if (desc) btn.textContent = desc.classList.contains('expanded') ? t('seeLess') : t('seeMore');
  });
  // Mettre à jour les boutons "Découvrir"
  $$('.discover-btn').forEach(function (btn) {
    btn.innerHTML = '<i class="fas fa-eye"></i> ' + t('discover');
  });
}

/* ══════════════════════════════════════════════════════════════════
   6. GESTIONNAIRE SHEETS / PANELS / MODALS
══════════════════════════════════════════════════════════════════ */
function openSheet(id) {
  var el = $(id);
  if (!el) return;
  pauseAllVideos();
  var bd = $('backdrop');
  if (bd) bd.classList.add('open');
  el.classList.add('open');
  if (STATE.activeSheets.indexOf(id) === -1) STATE.activeSheets.push(id);
  setupDragClose(el);
}

function closeSheet(id) {
  var el = $(id);
  if (el) el.classList.remove('open');
  STATE.activeSheets = STATE.activeSheets.filter(function (s) { return s !== id; });
  if (STATE.activeSheets.length === 0) {
    var bd = $('backdrop');
    if (bd) bd.classList.remove('open');
  }
  resumeVideo();
}

function closeAllSheets() {
  $$('.sheet.open').forEach(function (el) { el.classList.remove('open'); });
  STATE.activeSheets = [];
  var bd = $('backdrop');
  if (bd) bd.classList.remove('open');
  resumeVideo();
}

function openPanel(id) {
  var el = $(id);
  if (!el) return;
  el.classList.add('open');
  if (STATE.activePanels.indexOf(id) === -1) STATE.activePanels.push(id);
  pauseAllVideos();
}

function closePanel(id) {
  var el = $(id);
  if (!el) return;
  el.style.transform = '';
  el.style.transition = '';
  el.classList.remove('open');
  STATE.activePanels = STATE.activePanels.filter(function (p) { return p !== id; });
  if (STATE.activePanels.length === 0) resumeVideo();
}

function closeTopPanel() {
  if (STATE.activePanels.length > 0) closePanel(STATE.activePanels[STATE.activePanels.length - 1]);
}

function openModal(id) {
  var el = $(id);
  if (!el) return;
  el.classList.add('open');
  STATE.activeModal = id;
  pauseAllVideos();
}

function closeModal(id) {
  var el = $(id);
  if (el) el.classList.remove('open');
  STATE.activeModal = null;
  if (!STATE.activePanels.length && !STATE.activeSheets.length) resumeVideo();
}

function pauseAllVideos() {
  $$('.reel video').forEach(function (v) { try { v.pause(); } catch (e) { } });
}

function resumeVideo() {
  if (STATE.activePanels.length || STATE.activeSheets.length || STATE.activeModal) return;
  if (STATE.currentVideoEl) {
    try { STATE.currentVideoEl.play().catch(function () { }); } catch (e) { }
  }
}

function setupDragClose(sheet) {
  var drag = sheet.querySelector('.sdrag');
  if (!drag || drag._dc) return;
  drag._dc = true;
  var startY = 0, dragging = false;
  drag.addEventListener('pointerdown', function (e) {
    startY = e.clientY; dragging = true;
    try { drag.setPointerCapture(e.pointerId); } catch (err) { }
  }, { passive: true });
  drag.addEventListener('pointermove', function (e) {
    if (!dragging) return;
    var dy = e.clientY - startY;
    if (dy > 0) sheet.style.transform = 'translateY(' + dy + 'px)';
  }, { passive: true });
  drag.addEventListener('pointerup', function (e) {
    dragging = false;
    sheet.style.transform = '';
    var dy = e.clientY - startY;
    if (dy > 80) {
      var closeId = drag.getAttribute('data-close');
      if (closeId) closeSheet(closeId);
    }
  });
}

/* ══════════════════════════════════════════════════════════════════
   7. MOTEUR DU FEED
══════════════════════════════════════════════════════════════════ */
function buildFeedOrder() {
  var vids = STATE.properties.slice();
  var f = STATE.filters;
  if (f.trans !== 'all') vids = vids.filter(function (v) { return v.transaction === f.trans; });
  if (f.type !== 'all') vids = vids.filter(function (v) { return v.type === f.type; });
  if (f.budget < 500000000) vids = vids.filter(function (v) { return v.price <= f.budget; });
  if (f.surface > 0) vids = vids.filter(function (v) { return v.surface >= f.surface; });
  if (f.city) {
    var c = f.city.toLowerCase();
    vids = vids.filter(function (v) {
      return v.city.toLowerCase().indexOf(c) > -1 || v.neighborhood.toLowerCase().indexOf(c) > -1;
    });
  }
  if (STATE.currentFeed === 'subs') {
    var fol = STATE.accounts.filter(function (a) { return a.subscribed; }).map(function (a) { return a.id; });
    vids = vids.filter(function (v) { return fol.indexOf(v.accountId) > -1; });
    if (!vids.length) return [];
  } else if (STATE.currentFeed === 'foryou') {
    vids = shuffleArr(vids);
  } else {
    vids = vids.slice().sort(function (a, b) { return b.views - a.views; });
  }
  return vids;
}

function renderFeed() {
  var feed = $('feed');
  if (!feed) return;
  if (STATE.iObserver) { STATE.iObserver.disconnect(); STATE.iObserver = null; }
  var vids = buildFeedOrder();
  if (!vids || !vids.length) {
    feed.innerHTML = '<div style="height:100svh;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:14px">'
      + '<i class="fas fa-video-slash" style="font-size:40px;color:var(--t3)"></i>'
      + '<p style="color:var(--t3);font-size:14px;text-align:center;white-space:pre-line">' + t(STATE.currentFeed === 'subs' ? 'empty.subs' : 'empty.feed') + '</p>'
      + '<button onclick="resetFilters()" style="background:var(--red);color:#fff;padding:10px 20px;border-radius:20px;border:none;cursor:pointer;font-size:13px">' + t('filter.reset') + '</button></div>';
    return;
  }
  var ext = vids.concat(shuffleArr(vids.slice()), shuffleArr(vids.slice()));
  feed.innerHTML = ext.map(function (p, i) { return buildReelHTML(p, i); }).join('');
  setupFeedDelegation();
  setupIntersectionObs();
  feed.scrollTop = 0;
  STATE.currentReelIndex = 0;
}

function buildReelHTML(prop, idx) {
  var acc = getAccount(prop.accountId);
  if (!acc) return '';
  var isLiked = STATE.likedItems.indexOf(prop.id) > -1;
  var isSaved = STATE.savedItems.indexOf(prop.id) > -1;
  var isSub = acc.subscribed;
  var tc = prop.transaction === 'vente' ? 'vente' : 'location';
  var tags = (prop.tags || []).map(function (tag) {
    return '<span class="rtag" data-tag="' + tag + '">' + tag + '</span>';
  }).join('');
  var specBadge = acc.type === 'particulier'
    ? '<span style="font-size:9px;background:rgba(168,85,247,.15);border:1px solid rgba(168,85,247,.3);color:#c084fc;padding:2px 7px;border-radius:10px;font-weight:600">PARTICULIER</span>'
    : '';

  return '<div class="reel" data-vid="' + prop.id + '" data-idx="' + idx + '" data-account="' + acc.id + '">'
    + '<video class="reel-video" src="' + prop.videoUrl + '" poster="' + prop.poster + '" playsinline muted preload="' + (idx < 2 ? 'auto' : 'metadata') + '" loop></video>'
    // + '<img class="reel-poster" src="' + prop.poster + '" alt="' + prop.title + '" loading="' + (idx < 2 ? 'eager' : 'lazy') + '">'
    + '<div class="grad-top"></div><div class="grad-bot"></div>'
    + '<span class="play-ico" id="pi-' + idx + '">▶</span>'
    + '<span class="dbl-heart" id="dh-' + idx + '" style="color:#ff2d55">❤️</span>'
    + '<div class="rspinner"></div>'
    + '<div class="rprog-wrap" id="pw-' + idx + '" data-vid="' + prop.id + '">'
    + '<div class="rprog"><div class="rprog-fill" id="fill-' + idx + '"></div></div>'
    + '<div class="rprog-thumb-preview" id="prev-' + idx + '"><img src="' + prop.poster + '" alt=""><div class="rprog-thumb-time" id="prevt-' + idx + '">0:00</div></div></div>'
    + '<div id="vtd-' + idx + '" style="position:absolute;bottom:76px;right:12px;z-index:16;font-size:10px;color:rgba(255,255,255,.65);background:rgba(7,8,13,.55);padding:2px 7px;border-radius:5px;pointer-events:none">0:00 / 0:00</div>'
    + '<div class="reel-sidebar">'
    + '<div class="av-wrap" data-account="' + acc.id + '">'
    + '<div class="av-ring' + (isSub ? ' following' : '') + '"><img src="' + acc.avatar + '" alt="' + acc.name + '" loading="lazy" onerror="this.style.display=\'none\'"></div>'
    + '<div class="av-pill' + (isSub ? ' following' : '') + '" data-follow="' + acc.id + '">' + (isSub ? '<i class="fas fa-check"></i>' : '<i class="fas fa-plus"></i>') + '</div></div>'
    + '<div class="abt like-btn" data-vid="' + prop.id + '"><div class="aico' + (isLiked ? ' liked' : '') + '"><i class="fas fa-heart"></i></div>'
    + '<span class="albl like-count">' + fmtN(isLiked ? prop.likes + 1 : prop.likes) + '</span></div>'
    + '<div class="abt comment-btn" data-vid="' + prop.id + '"><div class="aico"><i class="fas fa-comment-dots"></i></div><span class="albl">' + fmtN(prop.comments) + '</span></div>'
    + '<div class="abt save-btn" data-vid="' + prop.id + '"><div class="aico' + (isSaved ? ' saved' : '') + '"><i class="fas fa-bookmark"></i></div><span class="albl save-count">' + fmtN(prop.saves) + '</span></div>'
    + '<div class="abt share-btn" data-vid="' + prop.id + '"><div class="aico"><i class="fas fa-share-alt"></i></div><span class="albl">' + fmtN(prop.shares) + '</span></div>'
    + '<div class="abt more-btn" data-vid="' + prop.id + '"><div class="aico"><i class="fas fa-ellipsis-h"></i></div><span class="albl">Plus</span></div>'
    + '</div>'
    + '<div class="reel-info">'
    + '<div class="reel-agency" data-account="' + acc.id + '">'
    + '<span class="reel-aname">' + acc.name + '</span>'
    + (acc.verified ? '<span class="vbadge"><i class="fas fa-check"></i></span>' : '')
    + (acc.type === 'particulier' ? specBadge : '') + '</div>'
    + '<div class="reel-title">' + prop.title + '</div>'
    + '<div class="reel-desc" id="desc-' + prop.id + '-' + idx + '">' + prop.description + '</div>'
    + '<button class="see-more-btn" data-desc="' + prop.id + '-' + idx + '">' + t('seeMore') + '</button>'
    + '<div class="reel-tags">' + tags + '</div>'
    + '<div class="reel-badges">'
    + '<span class="rbadge price"><i class="fas fa-tag"></i> ' + prop.priceLabel + '</span>'
    + '<span class="rbadge type"><i class="fas fa-home"></i> ' + prop.type + '</span>'
    + '<span class="rbadge ' + tc + '"><i class="fas fa-' + (prop.transaction === 'vente' ? 'key' : 'handshake') + '"></i> ' + prop.transaction + '</span>'
    + (prop.surface > 0 ? '<span class="rbadge surf"><i class="fas fa-ruler-combined"></i> ' + prop.surface + 'm²</span>' : '')
    + '</div>'
    + '<button class="discover-btn discover-btn-pulse" data-vid="' + prop.id + '"><i class="fas fa-eye"></i> ' + t('discover') + '</button>'
    + '<div class="music-row" data-vid="' + prop.id + '">'
    + '<div class="mdisc"><div class="mdot"></div></div>'
    + '<div class="mtxt"><span class="mscroll">' + prop.music.title + ' — ' + prop.music.artist + '     ' + prop.music.title + ' — ' + prop.music.artist + '     </span></div></div>'
    + '</div></div>';
}

/* ══ INTERSECTION OBSERVER (autoplay) ══ */
function setupIntersectionObs() {
  var feed = $('feed');
  if (!feed) return;
  STATE.iObserver = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      var reel = entry.target;
      var vid = reel.querySelector('video');
      if (!vid) return;
      if (entry.isIntersecting) {
        STATE.currentVideoEl = vid;
        STATE.currentReelIndex = parseInt(reel.dataset.idx) || 0;
        vid.muted = STATE.muted;
        vid.play().catch(function () { vid.muted = true; vid.play().catch(function () { }); });
        reel.classList.remove('paused');
        var poster = reel.querySelector('.reel-poster');
        vid.addEventListener('canplay', function () { if (poster) poster.style.opacity = '0'; }, { once: true });
        startProgUpdate(reel, vid);
        // Pré-charger si proche de la fin
        var all = feed.querySelectorAll('.reel');
        var cur = Array.from(all).indexOf(reel);
        if (cur > all.length - 4) appendMoreReels();
      } else {
        vid.pause();
        reel.classList.add('paused');
        clearInterval(reel._pt);
      }
    });
  }, { root: feed, threshold: 0.75 });
  $$('.reel').forEach(function (r) { if (STATE.iObserver) STATE.iObserver.observe(r); });
}

function startProgUpdate(reel, vid) {
  clearInterval(reel._pt);
  var idx = parseInt(reel.dataset.idx) || 0;
  reel._pt = setInterval(function () {
    if (!vid.duration || STATE.scrubbing) return;
    var pct = (vid.currentTime / vid.duration) * 100;
    var fill = $('fill-' + idx);
    if (fill) fill.style.width = pct + '%';
    var vtd = $('vtd-' + idx);
    if (vtd) vtd.textContent = fmtT(vid.currentTime) + ' / ' + fmtT(vid.duration);
  }, 120);
  vid.addEventListener('waiting', function () { reel.classList.add('buffering'); }, { passive: true });
  vid.addEventListener('playing', function () { reel.classList.remove('buffering'); }, { passive: true });
}

function appendMoreReels() {
  var feed = $('feed');
  if (!feed) return;
  if (feed._isApiFeed) return;
  var extra = shuffleArr(buildFeedOrder() || STATE.properties.slice());
  var start = feed.children.length;
  var frag = document.createDocumentFragment();
  extra.forEach(function (p, i) {
    var div = document.createElement('div');
    div.innerHTML = buildReelHTML(p, start + i);
    var r = div.firstElementChild;
    if (r) {
      frag.appendChild(r);
      if (STATE.iObserver) STATE.iObserver.observe(r);
    }
  });
  feed.appendChild(frag);
}

/* ══════════════════════════════════════════════════════════════════
   8. DÉLÉGATION D'ÉVÉNEMENTS DU FEED (corrige bug profil)
══════════════════════════════════════════════════════════════════ */
function setupFeedDelegation() {
  var feed = $('feed');
  if (!feed || feed._del) return;
  feed._del = true;

  var tapTimer = null, tapCount = 0, lastTouch = 0;

  feed.addEventListener('click', function (e) {
    // Avatar (pas la pill)
    var avWrap = e.target.closest('.av-wrap');
    if (avWrap && !e.target.closest('.av-pill')) {
      e.stopPropagation();
      var aid = avWrap.getAttribute('data-account');
      if (aid) window.openProfilePanel(aid);
      return;
    }
    // Follow pill
    var avPill = e.target.closest('.av-pill');
    if (avPill) {
      e.stopPropagation();
      var fid = avPill.getAttribute('data-follow');
      if (fid) window.toggleFollowById(fid);
      return;
    }
    // Nom agence
    var agency = e.target.closest('.reel-agency');
    if (agency) {
      e.stopPropagation();
      var aid2 = agency.getAttribute('data-account');
      if (aid2) window.openProfilePanel(aid2);
      return;
    }
    // Like
    var lb = e.target.closest('.like-btn');
    if (lb) { e.stopPropagation(); window.toggleLike(lb.getAttribute('data-vid'), lb); return; }
    // Comment
    var cb = e.target.closest('.comment-btn');
    if (cb) { e.stopPropagation(); window.openCommentSheet(cb.getAttribute('data-vid')); return; }
    // Save
    var sb = e.target.closest('.save-btn');
    if (sb) { e.stopPropagation(); window.toggleSave(sb.getAttribute('data-vid'), sb); return; }
    // Share
    var shb = e.target.closest('.share-btn');
    if (shb) { e.stopPropagation(); window.openShareSheet(shb.getAttribute('data-vid')); return; }
    // More
    var mb = e.target.closest('.more-btn');
    if (mb) { e.stopPropagation(); window.openMoreSheet(mb.getAttribute('data-vid')); return; }
    // Discover
    var db = e.target.closest('.discover-btn');
    if (db) { e.stopPropagation(); window.openPropertySheet(db.getAttribute('data-vid')); return; }
    // Music
    var mr = e.target.closest('.music-row');
    if (mr) { e.stopPropagation(); window.openMusicSheet(mr.getAttribute('data-vid')); return; }
    // Tag
    var tag = e.target.closest('.rtag');
    if (tag) {
      e.stopPropagation();
      window.doSearch(tag.getAttribute('data-tag') || tag.textContent);
      window.openExplorePanel();
      return;
    }
    // See more/less
    var sm = e.target.closest('.see-more-btn');
    if (sm) {
      e.stopPropagation();
      var dk = sm.getAttribute('data-desc');
      var desc = document.getElementById('desc-' + dk);
      if (desc) { desc.classList.toggle('expanded'); sm.textContent = desc.classList.contains('expanded') ? t('seeLess') : t('seeMore'); }
      return;
    }
    // Tap vidéo (play/pause + double tap)
    var reel = e.target.closest('.reel');
    if (reel && !e.target.closest('.reel-sidebar') && !e.target.closest('.reel-info') && !e.target.closest('.rprog-wrap')) {
      tapCount++;
      if (tapCount === 1) {
        tapTimer = setTimeout(function () { tapCount = 0; handleVideoTap(reel); }, 240);
      } else if (tapCount === 2) {
        clearTimeout(tapTimer); tapCount = 0; handleDoubleTap(reel, e);
      }
    }
  });

  // Double tap mobile
  feed.addEventListener('touchend', function (e) {
    var now = Date.now();
    var reel = e.target.closest('.reel');
    if (!reel || e.target.closest('.reel-sidebar') || e.target.closest('.reel-info') || e.target.closest('.rprog-wrap')) return;
    if (now - lastTouch < 260 && now - lastTouch > 50) {
      var touch = e.changedTouches[0] || e;
      handleDoubleTap(reel, touch);
    }
    lastTouch = now;
  }, { passive: true });

  // Progress bar scrub
  feed.addEventListener('pointerdown', function (e) {
    var wrap = e.target.closest('.rprog-wrap');
    if (!wrap) return;
    e.stopPropagation();
    STATE.scrubbing = true;
    wrap.classList.add('scrubbing');
    try { wrap.setPointerCapture(e.pointerId); } catch (err) { }
    doScrub(wrap, e.clientX);
  });
  feed.addEventListener('pointermove', function (e) {
    if (!STATE.scrubbing) return;
    var wrap = e.target.closest('.rprog-wrap');
    if (!wrap) return;
    doScrub(wrap, e.clientX);
  });
  feed.addEventListener('pointerup', function (e) {
    if (!STATE.scrubbing) return;
    STATE.scrubbing = false;
    var wrap = e.target.closest('.rprog-wrap');
    if (!wrap) { var tip = $('timeTip'); if (tip) tip.classList.remove('show'); return; }
    wrap.classList.remove('scrubbing');
    var reel = wrap.closest('.reel');
    if (!reel) return;
    var vid = reel.querySelector('video');
    if (vid && vid.duration) {
      var tr = wrap.querySelector('.rprog');
      if (tr) {
        var rect = tr.getBoundingClientRect();
        vid.currentTime = clamp((e.clientX - rect.left) / rect.width, 0, 1) * vid.duration;
      }
    }
    var tip = $('timeTip');
    if (tip) tip.classList.remove('show');
  });

  // Swipes latéraux sur le feed (profil)
  setupFeedSwipeGestures(feed);
}

function doScrub(wrap, clientX) {
  var reel = wrap.closest('.reel');
  if (!reel) return;
  var vid = reel.querySelector('video');
  if (!vid || !vid.duration) return;
  var idx = parseInt(reel.dataset.idx) || 0;
  var tr = wrap.querySelector('.rprog');
  if (!tr) return;
  var rect = tr.getBoundingClientRect();
  var pct = clamp((clientX - rect.left) / rect.width, 0, 1);
  var time = pct * vid.duration;
  var fill = $('fill-' + idx);
  if (fill) fill.style.width = (pct * 100) + '%';
  var prevt = $('prevt-' + idx);
  if (prevt) prevt.textContent = fmtT(time);
  var prev = $('prev-' + idx);
  if (prev) prev.style.left = Math.max(0, Math.min(rect.width - 80, clientX - rect.left - 40)) + 'px';
  var tip = $('timeTip');
  if (tip) {
    tip.textContent = fmtT(time) + ' / ' + fmtT(vid.duration);
    tip.style.left = clamp(clientX, 36, window.innerWidth - 36) + 'px';
    tip.style.top = (rect.top - 38) + 'px';
    tip.classList.add('show');
  }
}

function handleVideoTap(reel) {
  var vid = reel.querySelector('video');
  if (!vid) return;
  var pi = reel.querySelector('.play-ico');
  if (vid.paused) {
    vid.play().catch(function () { });
    reel.classList.remove('paused');
  } else {
    vid.pause();
    reel.classList.add('paused');
    if (pi) pi.textContent = '▶';
    reel.classList.add('show-play');
    setTimeout(function () { reel.classList.remove('show-play'); }, 800);
  }
}

function handleDoubleTap(reel, e) {
  var vid = reel.getAttribute('data-vid');
  var heart = reel.querySelector('.dbl-heart');
  if (heart) {
    var rect = reel.getBoundingClientRect();
    var cx = (e.clientX || (rect.left + rect.width / 2)) - rect.left;
    var cy = (e.clientY || (rect.top + rect.height / 2)) - rect.top;
    heart.style.left = cx + 'px';
    heart.style.top = cy + 'px';
    heart.classList.remove('pop');
    void heart.offsetWidth;
    heart.classList.add('pop');
    setTimeout(function () { heart.classList.remove('pop'); }, 800);
  }
  if (vid) {
    var lb = reel.querySelector('.like-btn');
    if (lb) {
      if (window.alwaysLike) { window.alwaysLike(vid, lb); }
      else if (window.toggleLike) { window.toggleLike(vid, lb); }
    }
  }
}

/* ══════════════════════════════════════════════════════════════════
   9. SWIPES LATÉRAUX — PROFIL (entrer/sortir)
══════════════════════════════════════════════════════════════════ */
function setupFeedSwipeGestures(feed) {
  var swipeStartX = 0, swipeStartY = 0, swipeReel = null;

  feed.addEventListener('touchstart', function (e) {
    swipeStartX = e.touches[0].clientX;
    swipeStartY = e.touches[0].clientY;
    swipeReel = e.target.closest('.reel');
  }, { passive: true });

  feed.addEventListener('touchend', function (e) {
    if (!swipeReel) return;
    var dx = e.changedTouches[0].clientX - swipeStartX;
    var dy = e.changedTouches[0].clientY - swipeStartY;
    // Swipe horizontal dominant (>50px, plus que vertical)
    if (Math.abs(dx) > Math.abs(dy) && Math.abs(dx) > 60) {
      if (dx < 0) {
        // Swipe gauche → ouvrir profil de l'auteur du reel
        var accountId = swipeReel.getAttribute('data-account');
        if (accountId) window.openProfilePanel(accountId, 'swipe-left');
      } else {
        // Swipe droit → fermer panel si ouvert
        if (STATE.activePanels.length > 0) closeTopPanel();
      }
    }
    swipeReel = null;
  }, { passive: true });
}

function setupPanelSwipeClose(panelEl) {
  if (!panelEl || panelEl._swipeClose) return;
  panelEl._swipeClose = true;
  var startX = 0, startY = 0;

  panelEl.addEventListener('touchstart', function (e) {
    startX = e.touches[0].clientX;
    startY = e.touches[0].clientY;
  }, { passive: true });

  panelEl.addEventListener('touchmove', function (e) {
    var dx = e.touches[0].clientX - startX;
    var dy = e.touches[0].clientY - startY;
    if (Math.abs(dx) > Math.abs(dy) && dx > 0) {
      // Glisser le panel vers la droite (fermeture)
      panelEl.style.transform = 'translateX(' + Math.max(0, dx) + 'px)';
      panelEl.style.transition = 'none';
    }
  }, { passive: true });

  panelEl.addEventListener('touchend', function (e) {
    var dx = e.changedTouches[0].clientX - startX;
    panelEl.style.transition = '';
    if (dx > 80) {
      // Fermer le panel
      closePanel(panelEl.id);
    } else {
      panelEl.style.transform = '';
    }
  }, { passive: true });
}

/* ══════════════════════════════════════════════════════════════════
   10. SON
══════════════════════════════════════════════════════════════════ */
function toggleSound() {
  STATE.muted = !STATE.muted;
  $$('.reel video').forEach(function (v) { try { v.muted = STATE.muted; } catch (e) { } });
  var si = $('soundIcon');
  if (si) si.className = STATE.muted ? 'fas fa-volume-mute' : 'fas fa-volume-up';
  showToast(STATE.muted ? (STATE.lang === 'fr' ? 'Son coupé' : 'Sound off') : (STATE.lang === 'fr' ? 'Son activé' : 'Sound on'),
    STATE.muted ? 'fa-volume-mute' : 'fa-volume-up', '#4285f4', 1500);
}

/* ══════════════════════════════════════════════════════════════════
   11. LIKE / SAVE / FOLLOW
══════════════════════════════════════════════════════════════════ */
function toggleLike(vid, btn) {
  if (!vid) return;
  var prop = getProperty(vid);
  if (!prop) return;
  var idx = STATE.likedItems.indexOf(vid);
  var ico = btn && btn.querySelector('.aico');
  var lbl = btn && btn.querySelector('.like-count');
  if (idx === -1) {
    STATE.likedItems.push(vid);
    prop.likes++;
    if (ico) ico.classList.add('liked');
    showToast(STATE.lang === 'fr' ? "J'aime ajouté ❤️" : 'Added to likes ❤️', 'fa-heart', '#ff2d55', 1800);
  } else {
    STATE.likedItems.splice(idx, 1);
    prop.likes--;
    if (ico) ico.classList.remove('liked');
  }
  if (lbl) lbl.textContent = fmtN(prop.likes);
  if (ico) { ico.style.transform = 'scale(1.45)'; setTimeout(function () { ico.style.transform = ''; }, 280); }
  $$('.reel[data-vid="' + vid + '"] .like-btn .aico').forEach(function (el) {
    el.classList.toggle('liked', STATE.likedItems.indexOf(vid) > -1);
  });
  saveStorage();
}

function toggleSave(vid, btn) {
  if (!vid) return;
  var prop = getProperty(vid);
  if (!prop) return;
  var idx = STATE.savedItems.indexOf(vid);
  var ico = btn && btn.querySelector('.aico');
  var lbl = btn && btn.querySelector('.save-count');
  if (idx === -1) {
    STATE.savedItems.push(vid);
    prop.saves++;
    if (ico) ico.classList.add('saved');
    showToast(STATE.lang === 'fr' ? 'Bien sauvegardé 🔖' : 'Property saved 🔖', 'fa-bookmark', '#ffc93c', 1800);
  } else {
    STATE.savedItems.splice(idx, 1);
    prop.saves--;
    if (ico) ico.classList.remove('saved');
    showToast(STATE.lang === 'fr' ? 'Retiré des sauvegardes' : 'Removed from saved', 'fa-bookmark', '#8890b5', 1800);
  }
  if (lbl) lbl.textContent = fmtN(prop.saves);
  if (ico) { ico.style.transform = 'scale(1.35)'; setTimeout(function () { ico.style.transform = ''; }, 250); }
  $$('.reel[data-vid="' + vid + '"] .save-btn .aico').forEach(function (el) {
    el.classList.toggle('saved', STATE.savedItems.indexOf(vid) > -1);
  });
  saveStorage();
}

function toggleFollowById(accountId) {
  var acc = getAccount(accountId);
  if (!acc) return;
  acc.subscribed = !acc.subscribed;
  if (acc.subscribed) {
    acc.followers++;
    showToast('Abonné à ' + acc.name + ' ✅', 'fa-check-circle', '#00dfc8', 2000);
  } else {
    acc.followers--;
    showToast('Désabonné', 'fa-user-minus', '#8890b5', 1800);
  }
  syncFollowUI(accountId);
}

function syncFollowUI(accountId) {
  var acc = getAccount(accountId);
  if (!acc) return;
  var isSub = acc.subscribed;
  $$('.av-pill[data-follow="' + accountId + '"]').forEach(function (p) {
    p.classList.toggle('following', isSub);
    p.innerHTML = isSub ? '<i class="fas fa-check"></i>' : '<i class="fas fa-plus"></i>';
  });
  $$('.av-wrap[data-account="' + accountId + '"] .av-ring').forEach(function (r) {
    r.classList.toggle('following', isSub);
  });
  // Mettre à jour le bouton dans le panel profil si ouvert
  var sb = $('profileSubBtn');
  if (sb && sb.getAttribute('data-account-id') === accountId) {
    sb.classList.toggle('subscribed', isSub);
    sb.innerHTML = '<i class="fas ' + (isSub ? 'fa-check' : 'fa-bell') + '"></i> ' + (isSub ? t('following') : t('follow'));
  }
  // Mettre à jour compteur abonnés dans le panel
  var fc = $('profileFollowerCount');
  if (fc) fc.textContent = fmtN(acc.followers);
}

/* ══════════════════════════════════════════════════════════════════
   12. COMMENTAIRES
══════════════════════════════════════════════════════════════════ */
function openCommentSheet(vid) {
  STATE.currentProperty = vid;
  renderComments(vid);
  openSheet('commentSheet');
}

function renderComments(vid) {
  var list = $('commentList');
  var countEl = $('cCount');
  if (!list) return; // Sécurité : évite l'erreur null
  var comments = STATE.comments[vid] || [];
  if (countEl) countEl.textContent = comments.length;

  if (comments.length === 0) {
    list.innerHTML = '<div class="empty-state"><i class="fas fa-comments"></i>'
      + '<h3>' + (STATE.lang === 'fr' ? 'Aucun commentaire' : 'No comments yet') + '</h3>'
      + '<p>' + (STATE.lang === 'fr' ? 'Soyez le premier !' : 'Be the first!') + '</p></div>';
    return;
  }

  var colors = ['#4285f4', '#ff2d55', '#2ac97a', '#ffc93c', '#a855f7', '#00dfc8'];
  list.innerHTML = comments.map(function (c) {
    var initials = c.name.split(' ').map(function (n) { return n[0]; }).join('').toUpperCase().slice(0, 2);
    var color = colors[c.name.charCodeAt(0) % colors.length];
    return '<div class="ci-item">'
      + '<div class="ci-av" style="background:' + color + '20;color:' + color + '">'
      + (c.avatar ? '<img src="' + c.avatar + '" style="width:100%;height:100%;object-fit:cover;border-radius:50%" onerror="this.style.display=\'none\'">' : initials)
      + '</div>'
      + '<div class="ci-body">'
      + '<div class="ci-name">' + c.name
      + (c.badge ? '<span class="ci-badge ' + c.badge + '">' + (c.badge === 'agency' ? (STATE.lang === 'fr' ? 'Agence' : 'Agency') : c.badge) + '</span>' : '')
      + '</div>'
      + '<div class="ci-text">' + c.text + '</div>'
      + '<div class="ci-meta">'
      + '<span class="ci-time">' + c.time + '</span>'
      + '<button class="ci-like ' + (c.liked ? 'on' : '') + '" data-cid="' + c.id + '" data-vid="' + vid + '">'
      + '<i class="fas fa-heart"></i> ' + c.likes + '</button>'
      + '<button class="ci-reply">' + (STATE.lang === 'fr' ? 'Répondre' : 'Reply') + '</button>'
      + '</div></div></div>';
  }).join('');

  list.querySelectorAll('.ci-like').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var cid = btn.getAttribute('data-cid');
      var vid2 = btn.getAttribute('data-vid');
      var cmts = STATE.comments[vid2] || [];
      var com = cmts.find(function (x) { return x.id === cid; });
      if (!com) return;
      com.liked = !com.liked;
      com.likes += com.liked ? 1 : -1;
      btn.classList.toggle('on', com.liked);
      btn.innerHTML = '<i class="fas fa-heart"></i> ' + com.likes;
    });
  });
}

function sendComment() {
  var input = $('cInput');
  if (!input) return;
  var text = input.value.trim();
  if (!text) return;
  var vid = STATE.currentProperty;
  if (!vid) return;
  if (!STATE.comments[vid]) STATE.comments[vid] = [];
  var newComment = {
    id: 'c_' + Date.now(),
    name: STATE.user ? STATE.user.name : 'Moi',
    avatar: STATE.user ? (STATE.user.avatar || '') : '',
    text: text,
    time: STATE.lang === 'fr' ? 'À l\'instant' : 'Just now',
    likes: 0, liked: false, badge: null
  };
  STATE.comments[vid].unshift(newComment);
  var prop = getProperty(vid);
  if (prop) prop.comments++;
  input.value = '';
  renderComments(vid);
  var reels = document.querySelectorAll('.reel[data-vid="' + vid + '"]');
  reels.forEach(function (r) {
    var cb = r.querySelector('.comment-btn .albl');
    if (cb && prop) cb.textContent = fmtN(prop.comments);
  });
  showToast(STATE.lang === 'fr' ? 'Commentaire publié ✓' : 'Comment posted ✓', 'fa-check', '#2ac97a', 1800);
}

/* ══════════════════════════════════════════════════════════════════
   13. PARTAGE — WhatsApp enrichi + autres canaux
══════════════════════════════════════════════════════════════════ */
function openShareSheet(vid) {
  STATE.currentProperty = vid;
  var prop = getProperty(vid);
  if (!prop) return;
  var urlEl = $('shUrl');
  if (urlEl) urlEl.textContent = 'immotok.ci/bien/' + vid;
  // Afficher les boutons de partage enrichis
  renderShareButtons(vid, prop);
  openSheet('shareSheet');
}

function renderShareButtons(vid, prop) {
  var container = $('shareChannels');
  if (!container) return;
  var acc = getAccount(prop.accountId);
  var userName = STATE.user ? STATE.user.name : (STATE.lang === 'fr' ? 'Un utilisateur ImmoTok' : 'An ImmoTok user');
  var shareUrl = 'https://immotok.ci/bien/' + vid;
  var shareMsg = STATE.lang === 'fr'
    ? '🏠 *' + userName + '* vous invite à voir cette offre immobilière sur ImmoTok !\n\n*' + prop.title + '*\n💰 ' + prop.priceLabel + '\n📍 ' + prop.neighborhood + ', ' + prop.city + '\n\n🔗 ' + shareUrl + '\n\n_ImmoTok — L\'immobilier en vidéo · immotok.ci_'
    : '🏠 *' + userName + '* invites you to see this property on ImmoTok!\n\n*' + prop.title + '*\n💰 ' + prop.priceLabel + '\n📍 ' + prop.neighborhood + ', ' + prop.city + '\n\n🔗 ' + shareUrl + '\n\n_ImmoTok — Real estate in video · immotok.ci_';

  var channels = [
    {
      icon: 'fab fa-whatsapp', label: 'WhatsApp', color: '#25D366', bg: 'rgba(37,211,102,.1)',
      action: function () {
        window.open('https://wa.me/?text=' + encodeURIComponent(shareMsg), '_blank');
        closeSheet('shareSheet');
        if (prop) prop.shares++;
        showToast('Partagé sur WhatsApp', 'fa-share-alt', '#25D366', 2000);
      }
    },
    {
      icon: 'fas fa-copy', label: STATE.lang === 'fr' ? 'Copier le lien' : 'Copy link', color: '#4285f4', bg: 'rgba(66,133,244,.1)',
      action: function () {
        if (navigator.clipboard) navigator.clipboard.writeText(shareMsg);
        showToast(STATE.lang === 'fr' ? 'Lien copié ! 📋' : 'Link copied! 📋', 'fa-check', '#2ac97a', 1800);
        closeSheet('shareSheet');
      }
    },
    {
      icon: 'fas fa-sms', label: 'SMS', color: '#2ac97a', bg: 'rgba(42,201,122,.1)',
      action: function () {
        window.location.href = 'sms:?body=' + encodeURIComponent(shareMsg);
        closeSheet('shareSheet');
      }
    },
    {
      icon: 'fas fa-envelope', label: 'Email', color: '#a855f7', bg: 'rgba(168,85,247,.1)',
      action: function () {
        var subject = encodeURIComponent('ImmoTok — ' + prop.title);
        var body = encodeURIComponent(shareMsg);
        window.location.href = 'mailto:?subject=' + subject + '&body=' + body;
        closeSheet('shareSheet');
      }
    }
  ];

  container.innerHTML = channels.map(function (ch, i) {
    return '<div class="shi-btn" data-idx="' + i + '" style="display:flex;flex-direction:column;align-items:center;gap:8px;cursor:pointer">'
      + '<div style="width:52px;height:52px;border-radius:50%;background:' + ch.bg + ';display:flex;align-items:center;justify-content:center">'
      + '<i class="' + ch.icon + '" style="font-size:22px;color:' + ch.color + '"></i></div>'
      + '<span style="font-size:11px;color:var(--t2)">' + ch.label + '</span></div>';
  }).join('');

  container.querySelectorAll('.shi-btn').forEach(function (btn) {
    var idx = parseInt(btn.getAttribute('data-idx'));
    btn.addEventListener('click', channels[idx].action);
  });
}

/* ══════════════════════════════════════════════════════════════════
   14. MORE SHEET
══════════════════════════════════════════════════════════════════ */
function openMoreSheet(vid) {
  STATE.currentProperty = vid;
  var mSave = $('mSave');
  if (mSave) {
    var isSaved = STATE.savedItems.indexOf(vid) > -1;
    mSave.innerHTML = '<i class="fas fa-bookmark"></i><span>'
      + (isSaved ? (STATE.lang === 'fr' ? 'Retirer des sauvegardes' : 'Remove from saved') : t('more.save'))
      + '</span>';
  }
  openSheet('moreSheet');
}

/* ══════════════════════════════════════════════════════════════════
   15. FICHE BIEN (Property Sheet)
══════════════════════════════════════════════════════════════════ */
function openPropertySheet(vid) {
  var prop = getProperty(vid);
  if (!prop) return;
  STATE.currentProperty = vid;
  var acc = getAccount(prop.accountId);
  var titleEl = $('propSheetTitle');
  if (titleEl) titleEl.textContent = STATE.lang === 'fr' ? 'Détail du bien' : 'Property Details';
  var body = $('propSheetBody');
  if (!body) return;

  body.innerHTML = '<div class="prop-hero">'
    + '<img src="' + prop.poster + '" alt="' + prop.title + '" onerror="this.src=\'https://via.placeholder.com/400x200/0e1019/ffffff?text=ImmoTok\'">'
    + '<div class="prop-hero-grad"></div>'
    + '<div class="prop-hero-badges">'
    + '<span class="rbadge ' + (prop.transaction === 'vente' ? 'vente' : 'location') + '">' + prop.transaction.toUpperCase() + '</span>'
    + '<span class="rbadge type">' + prop.type + '</span></div></div>'
    + '<div class="prop-body">'
    + '<div class="prop-title2">' + prop.title + '</div>'
    + '<div class="prop-price2">' + prop.priceLabel + '</div>'
    + '<div class="prop-loc"><i class="fas fa-map-marker-alt"></i>' + prop.neighborhood + ', ' + prop.city + '</div>'
    + (prop.rooms > 0 || prop.surface > 0 ? '<div class="prop-specs">'
      + (prop.surface > 0 ? '<div class="pspec"><div class="pspec-val">' + prop.surface + 'm²</div><div class="pspec-lbl">Surface</div></div>' : '')
      + (prop.rooms > 0 ? '<div class="pspec"><div class="pspec-val">' + prop.rooms + '</div><div class="pspec-lbl">' + (STATE.lang === 'fr' ? 'Pièces' : 'Rooms') + '</div></div>' : '')
      + (prop.bathrooms > 0 ? '<div class="pspec"><div class="pspec-val">' + prop.bathrooms + '</div><div class="pspec-lbl">' + (STATE.lang === 'fr' ? 'Bains' : 'Baths') + '</div></div>' : '')
      + '</div>' : '')
    + '<div class="prop-desc2">' + prop.description + '</div>'
    + '<div class="prop-feats">'
    + (prop.features || []).map(function (f) { return '<div class="pfeat"><i class="fas fa-check"></i>' + f + '</div>'; }).join('')
    + '</div>'
    + (acc ? '<div class="prop-agency-row" data-account="' + acc.id + '" style="cursor:pointer">'
      + '<div class="pag-av"><img src="' + acc.avatar + '" alt="' + acc.name + '" onerror="this.style.display=\'none\'"></div>'
      + '<div><div class="pag-name">' + acc.name + '</div>'
      + '<div class="pag-type">' + (acc.type === 'enterprise' ? (STATE.lang === 'fr' ? 'Agence immobilière' : 'Real estate agency') : (STATE.lang === 'fr' ? 'Particulier' : 'Individual')) + '</div></div>'
      + '<i class="fas fa-chevron-right pag-arr"></i></div>' : '')
    + '<div class="reel-tags" style="margin-top:4px">'
    + (prop.tags || []).map(function (tag) { return '<span class="rtag" data-tag="' + tag + '">' + tag + '</span>'; }).join('')
    + '</div></div>';

  // Lien vers profil depuis la fiche
  var agRow = body.querySelector('[data-account]');
  if (agRow) {
    agRow.addEventListener('click', function () {
      closeSheet('propSheet');
      window.openProfilePanel(agRow.getAttribute('data-account'));
    });
  }
  openSheet('propSheet');
}

/* ══════════════════════════════════════════════════════════════════
   16. RÉSERVATION ET CONTACT
══════════════════════════════════════════════════════════════════ */
function openReserveModal(vid) {
  var prop = getProperty(vid || STATE.currentProperty);
  if (!prop) return;
  STATE.currentProperty = prop.id;
  var rsvProp = $('rsvProp');
  if (rsvProp) {
    rsvProp.innerHTML = '<img src="' + prop.poster + '" alt="" onerror="this.style.display=\'none\'">'
      + '<div class="prop-prev-info"><div class="prop-prev-title">' + prop.title + '</div>'
      + '<div class="prop-prev-price">' + prop.priceLabel + '</div></div>';
  }
  var tomorrow = new Date();
  tomorrow.setDate(tomorrow.getDate() + 1);
  var rd = $('rsvDate');
  if (rd) rd.min = tomorrow.toISOString().split('T')[0];
  closeSheet('propSheet');
  openModal('reserveModal');
}

function openContactModal(vid) {
  var prop = getProperty(vid || STATE.currentProperty);
  if (!prop) return;
  var acc = getAccount(prop.accountId);
  if (!acc) return;
  STATE.currentProperty = prop.id;
  var cAgent = $('cAgentCard');
  if (cAgent) {
    cAgent.innerHTML = '<div class="cagent-card">'
      + '<div class="cagent-av"><img src="' + acc.avatar + '" alt="' + acc.name + '" onerror="this.style.display=\'none\'"></div>'
      + '<div><div class="cagent-name">' + acc.name + '</div>'
      + '<div class="cagent-sub">' + (acc.type === 'enterprise' ? 'Agence' : 'Particulier') + ' · ' + prop.title + '</div></div></div>';
  }
  var cCh = $('cChannels');
  if (cCh) {
    cCh.innerHTML = '<div class="cchannels-wrap">'
      + '<button class="cch" onclick="callPhone(\'' + acc.phone + '\')"><i class="fas fa-phone" style="color:var(--green)"></i><span>' + (STATE.lang === 'fr' ? 'Appel' : 'Call') + '</span></button>'
      + '<button class="cch" onclick="openWhatsApp(\'' + acc.phone + '\')"><i class="fab fa-whatsapp" style="color:#25D366"></i><span>WhatsApp</span></button>'
      + '<button class="cch" onclick="sendEmail(\'' + acc.email + '\')"><i class="fas fa-envelope" style="color:var(--blue)"></i><span>Email</span></button></div>';
  }
  closeSheet('propSheet');
  openModal('contactModal');
}

function callPhone(phone) {
  if (phone) window.location.href = 'tel:' + phone.replace(/\s/g, '');
}

function openWhatsApp(phone, customMsg) {
  var p = phone ? phone.replace(/[\s+]/g, '') : '';
  var msg = customMsg || (STATE.lang === 'fr' ? "Bonjour, je vous contacte via ImmoTok au sujet d'un bien." : "Hello, I'm contacting you via ImmoTok about a property.");
  window.open('https://wa.me/' + p + '?text=' + encodeURIComponent(msg), '_blank');
}

function sendEmail(email) {
  if (email) window.location.href = 'mailto:' + email;
}

function submitReservation() {
  var first = $('rsvFirst') && $('rsvFirst').value.trim();
  var last = $('rsvLast') && $('rsvLast').value.trim();
  var phone = $('rsvPhone') && $('rsvPhone').value.trim();
  var date = $('rsvDate') && $('rsvDate').value;
  if (!first || !last || !phone || !date) {
    showToast(STATE.lang === 'fr' ? 'Veuillez remplir les champs obligatoires *' : 'Please fill required fields *', 'fa-exclamation-circle', '#ff2d55', 2500);
    return;
  }
  closeModal('reserveModal');
  openModal('successModal');
  var suT = $('suTitle'), suTx = $('suText');
  if (suT) suT.textContent = STATE.lang === 'fr' ? 'Demande envoyée ! ✅' : 'Request sent! ✅';
  if (suTx) suTx.textContent = STATE.lang === 'fr'
    ? 'Merci ' + first + ' ! L\'agence vous contactera sous 24h au ' + phone + '.'
    : 'Thank you ' + first + '! The agency will contact you within 24h at ' + phone + '.';
  STATE.notifications.unshift({
    id: 'n_' + Date.now(), type: 'reservation', icon: '📅', iconBg: 'rgba(66,133,244,.15)',
    title: STATE.lang === 'fr' ? 'Demande de visite envoyée' : 'Visit request sent',
    text: first + ' ' + last + ' — ' + date,
    time: STATE.lang === 'fr' ? 'À l\'instant' : 'Just now',
    unread: true, category: 'reservations'
  });
  updateAlertBadge();
}

function submitContact() {
  var msg = $('cMessage') && $('cMessage').value.trim();
  if (!msg) { showToast(STATE.lang === 'fr' ? 'Veuillez écrire un message' : 'Please write a message', 'fa-exclamation-circle', '#ff2d55'); return; }
  closeModal('contactModal');
  openModal('successModal');
  var suT = $('suTitle'), suTx = $('suText');
  if (suT) suT.textContent = STATE.lang === 'fr' ? 'Message envoyé ! 📩' : 'Message sent! 📩';
  if (suTx) suTx.textContent = STATE.lang === 'fr' ? "Votre message a été transmis. L'agence reviendra vers vous rapidement." : "Your message has been sent. The agency will get back to you shortly.";
}

/* ══════════════════════════════════════════════════════════════════
   17. MESSAGERIE DIRECTE (DM) — Style TikTok avec demande d'autorisation
══════════════════════════════════════════════════════════════════ */
function openDmPanel(accountId) {
  var acc = getAccount(accountId);
  if (!acc) return;
  STATE.currentDmAccountId = accountId;

  // Initialiser la conversation si elle n'existe pas
  if (!STATE.dmConversations[accountId]) {
    STATE.dmConversations[accountId] = {
      status: 'pending',  // 'pending' | 'accepted'
      messages: [],
      requestSent: false
    };
  }

  var panel = $('dmPanel');
  if (!panel) {
    createDmPanelDOM();
    panel = $('dmPanel');
  }
  renderDmPanel(acc);
  openPanel('dmPanel');
  setupPanelSwipeClose(panel);
}

function createDmPanelDOM() {
  var panel = document.createElement('div');
  panel.id = 'dmPanel';
  panel.className = 'panel';
  panel.innerHTML = '<div id="dmPanelContent"></div>';
  document.body.appendChild(panel);
}

function renderDmPanel(acc) {
  var content = $('dmPanelContent');
  if (!content) return;
  var conv = STATE.dmConversations[acc.id];

  var headerHTML = '<div style="display:flex;align-items:center;gap:12px;padding:14px;border-bottom:1px solid var(--border);background:var(--bg);position:sticky;top:0;z-index:10">'
    + '<button onclick="closePanel(\'dmPanel\')" style="width:36px;height:36px;border-radius:50%;background:var(--s2);border:none;cursor:pointer;display:flex;align-items:center;justify-content:center"><i class="fas fa-arrow-left" style="color:var(--t1)"></i></button>'
    + '<img src="' + acc.avatar + '" style="width:38px;height:38px;border-radius:50%;object-fit:cover" onerror="this.style.display=\'none\'">'
    + '<div style="flex:1">'
    + '<div style="font-weight:700;font-size:14px">' + acc.name + '</div>'
    + '<div style="font-size:11px;color:var(--t3)">' + (acc.type === 'enterprise' ? (STATE.lang === 'fr' ? 'Agence' : 'Agency') : (STATE.lang === 'fr' ? 'Particulier' : 'Individual')) + '</div>'
    + '</div></div>';

  var bodyHTML = '';

  if (conv.status === 'pending' && !conv.requestSent) {
    // Afficher l'interface de demande de premier message (exactement comme TikTok)
    bodyHTML = '<div style="flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:24px;text-align:center;min-height:60vh">'
      + '<div style="width:72px;height:72px;border-radius:50%;overflow:hidden;margin-bottom:14px"><img src="' + acc.avatar + '" style="width:100%;height:100%;object-fit:cover" onerror="this.style.display=\'none\'"></div>'
      + '<div style="font-weight:700;font-size:15px;margin-bottom:6px">' + acc.name + '</div>'
      + '<div style="font-size:12px;color:var(--t3);margin-bottom:20px;line-height:1.5">'
      + (STATE.lang === 'fr' ? 'Envoyez un message à ' + acc.name + '.\nVous ne pourrez envoyer qu\'un seul message jusqu\'à ce qu\'ils acceptent votre demande.' : 'Send a message to ' + acc.name + '.\nYou can only send one message until they accept your request.')
      + '</div>'
      + '<textarea id="dmFirstMsg" placeholder="' + t('dm.placeholder') + '" style="width:100%;max-width:380px;min-height:100px;background:var(--s2);border:1px solid var(--border);border-radius:12px;padding:12px;color:var(--t1);font-size:14px;resize:none;font-family:inherit;margin-bottom:12px"></textarea>'
      + '<button onclick="sendDmRequest(\'' + acc.id + '\')" style="background:var(--red);color:#fff;padding:12px 28px;border-radius:22px;border:none;cursor:pointer;font-weight:700;font-size:14px;width:100%;max-width:380px">'
      + (STATE.lang === 'fr' ? 'Envoyer la demande' : 'Send request') + '</button>'
      + '<p style="font-size:11px;color:var(--t3);margin-top:12px">'
      + (STATE.lang === 'fr' ? 'Les messages non sollicités peuvent être signalés.' : 'Unsolicited messages can be reported.')
      + '</p></div>';
  } else if (conv.status === 'pending' && conv.requestSent) {
    // Demande envoyée, en attente
    bodyHTML = renderDmMessages(acc, conv, true);
  } else {
    // Conversation acceptée — chat libre
    bodyHTML = renderDmMessages(acc, conv, false);
  }

  content.innerHTML = headerHTML + '<div style="display:flex;flex-direction:column;min-height:calc(100vh - 65px)">' + bodyHTML + '</div>';
}

function renderDmMessages(acc, conv, isPending) {
  var msgs = conv.messages || [];
  var msgsHTML = '<div id="dmMsgList" style="flex:1;overflow-y:auto;padding:14px;display:flex;flex-direction:column;gap:10px;min-height:300px">';

  if (isPending) {
    msgsHTML += '<div style="text-align:center;padding:16px 0">'
      + '<div style="background:rgba(255,201,60,.1);border:1px solid rgba(255,201,60,.2);border-radius:10px;padding:10px 14px;font-size:12px;color:var(--gold)">'
      + (STATE.lang === 'fr' ? '⏳ Demande envoyée — En attente que ' + acc.name + ' accepte.' : '⏳ Request sent — Waiting for ' + acc.name + ' to accept.')
      + '</div></div>';
  }

  msgsHTML += msgs.map(function (m) {
    var isMe = m.sender === 'me';
    return '<div style="display:flex;justify-content:' + (isMe ? 'flex-end' : 'flex-start') + ';gap:8px;align-items:flex-end">'
      + (!isMe ? '<img src="' + acc.avatar + '" style="width:28px;height:28px;border-radius:50%;object-fit:cover;flex-shrink:0" onerror="this.style.display=\'none\'">' : '')
      + '<div style="max-width:72%;background:' + (isMe ? 'var(--red)' : 'var(--s2)') + ';color:' + (isMe ? '#fff' : 'var(--t1)') + ';padding:10px 14px;border-radius:' + (isMe ? '18px 18px 4px 18px' : '18px 18px 18px 4px') + ';font-size:13.5px;line-height:1.45">'
      + m.text
      + '<div style="font-size:10px;color:' + (isMe ? 'rgba(255,255,255,.6)' : 'var(--t3)') + ';margin-top:4px;text-align:right">' + m.time + '</div>'
      + '</div></div>';
  }).join('');

  msgsHTML += '</div>';

  if (!isPending) {
    // Input de message libre
    msgsHTML += '<div style="padding:10px 12px;border-top:1px solid var(--border);display:flex;gap:8px;background:var(--bg)">'
      + '<input id="dmInput" type="text" placeholder="' + t('dm.placeholder') + '" style="flex:1;background:var(--s2);border:1px solid var(--border);border-radius:22px;padding:10px 14px;color:var(--t1);font-size:14px;font-family:inherit" onkeydown="if(event.key===\'Enter\')sendDmMsg(\'' + acc.id + '\')">'
      + '<button onclick="sendDmMsg(\'' + acc.id + '\')" style="width:42px;height:42px;border-radius:50%;background:var(--red);border:none;cursor:pointer;display:flex;align-items:center;justify-content:center"><i class="fas fa-paper-plane" style="color:#fff;font-size:16px"></i></button></div>';
  }

  return msgsHTML;
}

function sendDmRequest(accountId) {
  var input = $('dmFirstMsg');
  if (!input) return;
  var text = input.value.trim();
  if (!text) { showToast(STATE.lang === 'fr' ? 'Écrivez un message' : 'Write a message', 'fa-exclamation-circle', '#ff2d55'); return; }

  var conv = STATE.dmConversations[accountId];
  conv.requestSent = true;
  conv.messages.push({ sender: 'me', text: text, time: timeNow() });

  var acc = getAccount(accountId);
  showToast(STATE.lang === 'fr' ? 'Demande envoyée à ' + (acc ? acc.name : '') + ' ✅' : 'Request sent to ' + (acc ? acc.name : '') + ' ✅', 'fa-check-circle', '#2ac97a', 2500);

  // Simuler une réponse après 4 secondes (acceptation automatique pour la démo)
  setTimeout(function () {
    var c = STATE.dmConversations[accountId];
    if (c) {
      c.status = 'accepted';
      var a = getAccount(accountId);
      var autoReply = STATE.lang === 'fr'
        ? 'Bonjour ! Merci de nous contacter via ImmoTok. Comment puis-je vous aider ?'
        : 'Hello! Thank you for contacting us via ImmoTok. How can I help you?';
      c.messages.push({ sender: 'them', text: autoReply, time: timeNow() });
      showToast((a ? a.name : '') + ' ' + (STATE.lang === 'fr' ? 'a accepté votre demande ✅' : 'accepted your request ✅'), 'fa-check-circle', '#2ac97a', 2500);
      STATE.notifications.unshift({
        id: 'n_dm_' + Date.now(), type: 'comment', icon: '💬', iconBg: 'rgba(168,85,247,.12)',
        title: STATE.lang === 'fr' ? 'Demande acceptée' : 'Request accepted',
        text: (a ? a.name : '') + (STATE.lang === 'fr' ? ' a accepté votre demande de message' : ' accepted your message request'),
        time: STATE.lang === 'fr' ? 'À l\'instant' : 'Just now',
        unread: true, category: 'messages'
      });
      updateAlertBadge();
      if ($('dmPanel') && $('dmPanel').classList.contains('open') && STATE.currentDmAccountId === accountId) {
        renderDmPanel(a);
        setTimeout(function () { var list = $('dmMsgList'); if (list) list.scrollTop = list.scrollHeight; }, 100);
      }
    }
  }, 4000);

  renderDmPanel(acc);
}

function sendDmMsg(accountId) {
  var input = $('dmInput');
  if (!input) return;
  var text = input.value.trim();
  if (!text) return;
  var conv = STATE.dmConversations[accountId];
  if (!conv || conv.status !== 'accepted') return;
  conv.messages.push({ sender: 'me', text: text, time: timeNow() });
  input.value = '';
  var acc = getAccount(accountId);
  // Re-render messages only
  var list = $('dmMsgList');
  if (list) {
    var msgDiv = document.createElement('div');
    msgDiv.style.cssText = 'display:flex;justify-content:flex-end;gap:8px;align-items:flex-end';
    msgDiv.innerHTML = '<div style="max-width:72%;background:var(--red);color:#fff;padding:10px 14px;border-radius:18px 18px 4px 18px;font-size:13.5px;line-height:1.45">'
      + text + '<div style="font-size:10px;color:rgba(255,255,255,.6);margin-top:4px;text-align:right">' + timeNow() + '</div></div>';
    list.appendChild(msgDiv);
    list.scrollTop = list.scrollHeight;
  }
  // Réponse automatique simulée
  if (acc) {
    setTimeout(function () {
      var reply = generateDmAutoReply(acc, text);
      conv.messages.push({ sender: 'them', text: reply, time: timeNow() });
      var replyDiv = document.createElement('div');
      replyDiv.style.cssText = 'display:flex;justify-content:flex-start;gap:8px;align-items:flex-end';
      replyDiv.innerHTML = '<img src="' + acc.avatar + '" style="width:28px;height:28px;border-radius:50%;object-fit:cover;flex-shrink:0">'
        + '<div style="max-width:72%;background:var(--s2);color:var(--t1);padding:10px 14px;border-radius:18px 18px 18px 4px;font-size:13.5px;line-height:1.45">'
        + reply + '<div style="font-size:10px;color:var(--t3);margin-top:4px;text-align:right">' + timeNow() + '</div></div>';
      if (list) { list.appendChild(replyDiv); list.scrollTop = list.scrollHeight; }
    }, 1200 + Math.random() * 1000);
  }
}

function generateDmAutoReply(acc, msg) {
  var m = msg.toLowerCase();
  if (m.includes('prix') || m.includes('price') || m.includes('combien') || m.includes('coût') || m.includes('tarif')) {
    return STATE.lang === 'fr' ? 'Le prix dépend du bien qui vous intéresse. Pouvez-vous me préciser lequel ?' : 'The price depends on the property you\'re interested in. Could you specify which one?';
  }
  if (m.includes('visite') || m.includes('visit') || m.includes('voir') || m.includes('rendez')) {
    return STATE.lang === 'fr' ? 'Bien sûr, nous organisons des visites du lundi au samedi. Quelle est votre disponibilité ?' : 'Of course, we organize visits Monday to Saturday. What is your availability?';
  }
  if (m.includes('disponible') || m.includes('available') || m.includes('libre') || m.includes('louer') || m.includes('acheter')) {
    return STATE.lang === 'fr' ? 'Ce bien est actuellement disponible. Souhaitez-vous planifier une visite ?' : 'This property is currently available. Would you like to schedule a visit?';
  }
  if (m.includes('merci') || m.includes('thanks') || m.includes('parfait') || m.includes('super')) {
    return STATE.lang === 'fr' ? 'Avec plaisir ! N\'hésitez pas si vous avez d\'autres questions. 😊' : 'My pleasure! Don\'t hesitate if you have more questions. 😊';
  }
  if (m.includes('bonjour') || m.includes('hello') || m.includes('salut') || m.includes('bonsoir')) {
    return STATE.lang === 'fr' ? 'Bonjour ! Comment puis-je vous aider aujourd\'hui ?' : 'Hello! How can I help you today?';
  }
  if (m.includes('agent') || m.includes('contact') || m.includes('parler') || m.includes('appel')) {
    return STATE.lang === 'fr' ? 'Vous pouvez nous appeler directement au ' + (acc.phone || '+225 07 00 00 00') + '. Nous sommes disponibles 7j/7.' : 'You can call us directly at ' + (acc.phone || '+225 07 00 00 00') + '. We\'re available 7 days a week.';
  }
  // Réponse générique personnalisée avec le nom de l'agence
  return STATE.lang === 'fr'
    ? 'Merci pour votre message ! Notre équipe ' + acc.name + ' traite votre demande. Un conseiller reviendra vers vous très prochainement. 🏠'
    : 'Thank you for your message! Our ' + acc.name + ' team is processing your request. An advisor will get back to you very soon. 🏠';
}

/* ══════════════════════════════════════════════════════════════════
   18. PROFIL PANEL (Entreprise & Particulier) — corrigé
══════════════════════════════════════════════════════════════════ */
function openProfilePanel(accountId, direction) {
  var acc = getAccount(accountId);
  if (!acc) return;
  STATE.currentProfileAccountId = accountId;
  var panel = $('profilePanel');
  if (!panel) return;
  renderProfileContent(acc);
  openPanel('profilePanel');
  // Swipe pour fermer
  setupPanelSwipeClose(panel);
}

function renderProfileContent(account) {
  var content = $('profileContent');
  if (!content) return;
  var props = STATE.properties.filter(function (p) { return p.accountId === account.id; });
  var isFollowing = account.subscribed;
  if (account.type === 'enterprise') {
    content.innerHTML = buildEnterpriseProfileHTML(account, props, isFollowing);
  } else {
    content.innerHTML = buildParticulierProfileHTML(account, props, isFollowing);
  }
  bindProfileEvents(content, account);
}

function buildEnterpriseProfileHTML(account, props, isFollowing) {
  return '<div style="display:flex;flex-direction:column;min-height:100vh">'
    + '<div class="profile-hero-ent">'
    + '<div class="phe-bg" style="background:' + (account.coverGradient || 'var(--s1)') + '"></div>'
    + '<div class="phe-pattern"></div>'
    + '<div class="phe-glow"></div>'
    + '<div class="phe-badges"><span class="ent-badge">AGENCE</span>'
    + (account.verified ? '<span class="verified-ent"><i class="fas fa-check"></i> Vérifié</span>' : '') + '</div>'
    + '<div class="phe-back">'
    + '<button id="profileBackBtn" class="nb" style="cursor:pointer"><i class="fas fa-arrow-left"></i></button></div>'
    + '<div class="phe-av"><div class="ent-av"><img src="' + account.avatar + '" alt="' + account.name + '" onerror="this.style.display=\'none\'"></div></div></div>'
    + '<div class="profile-body-ent">'
    + '<div class="ent-name">' + account.name + (account.verified ? '<span class="vbadge" style="font-size:14px"><i class="fas fa-check"></i></span>' : '') + '</div>'
    + '<div class="ent-handle">' + account.handle + '</div>'
    + '<div class="ent-bio">' + account.bio + '</div>'
    + '<div class="ent-stats">'
    + '<div class="ent-stat"><div class="esv" id="profileFollowerCount">' + fmtN(account.followers) + '</div><div class="esl">' + (STATE.lang === 'fr' ? 'Abonnés' : 'Followers') + '</div></div>'
    + '<div class="ent-stat"><div class="esv">' + account.videos + '</div><div class="esl">' + (STATE.lang === 'fr' ? 'Vidéos' : 'Videos') + '</div></div>'
    + '<div class="ent-stat"><div class="esv">' + fmtN(account.likes) + '</div><div class="esl">' + (STATE.lang === 'fr' ? 'J\'aimes' : 'Likes') + '</div></div>'
    + '<div class="ent-stat"><div class="esv">' + props.length + '</div><div class="esl">' + (STATE.lang === 'fr' ? 'Biens' : 'Props') + '</div></div></div>'
    + '<div class="ent-actions">'
    + '<button class="btn-sub' + (isFollowing ? ' subscribed' : '') + '" id="profileSubBtn" data-account-id="' + account.id + '">'
    + '<i class="fas ' + (isFollowing ? 'fa-check' : 'fa-bell') + '"></i> ' + (isFollowing ? t('following') : t('follow')) + '</button>'
    + '<button class="btn-ico" id="profileDmBtn" title="Message"><i class="fas fa-comment"></i></button>'
    + '<button class="btn-ico" id="profileShareBtn"><i class="fas fa-share-alt"></i></button></div>'
    + '<div class="company-info-card"><div class="cic-body">'
    + '<div class="cic-title"><i class="fas fa-building"></i>' + (STATE.lang === 'fr' ? 'Informations entreprise' : 'Company info') + '</div>'
    + (account.address ? '<div class="cic-row"><div class="cic-ico"><i class="fas fa-map-marker-alt"></i></div><div><div class="cic-lbl">Adresse</div><div class="cic-val">' + account.address + '</div></div></div>' : '')
    + (account.phone ? '<div class="cic-row" style="cursor:pointer" id="profileCallBtn"><div class="cic-ico"><i class="fas fa-phone"></i></div><div><div class="cic-lbl">Téléphone</div><div class="cic-val">' + account.phone + '</div></div></div>' : '')
    + (account.founded ? '<div class="cic-row"><div class="cic-ico"><i class="fas fa-calendar"></i></div><div><div class="cic-lbl">Fondée en</div><div class="cic-val">' + account.founded + '</div></div></div>' : '')
    + (account.employees ? '<div class="cic-row"><div class="cic-ico"><i class="fas fa-users"></i></div><div><div class="cic-lbl">Employés</div><div class="cic-val">' + account.employees + '</div></div></div>' : '')
    + '</div></div>'
    + ((account.specialties && account.specialties.length) ? '<div class="ent-chips">' + account.specialties.map(function (s) { return '<div class="echip"><i class="fas fa-tag"></i>' + s + '</div>'; }).join('') + '</div>' : '')
    + '</div>'
    + '<div class="profile-tabs" style="position:sticky;top:0;z-index:10;background:var(--bg)">'
    + '<button class="ptab active" data-tab="videos"><i class="fas fa-play"></i>' + (STATE.lang === 'fr' ? 'Vidéos' : 'Videos') + '</button>'
    + '<button class="ptab" data-tab="biens"><i class="fas fa-home"></i>' + (STATE.lang === 'fr' ? 'Biens' : 'Props') + '</button>'
    + '<button class="ptab" data-tab="infos"><i class="fas fa-info-circle"></i>Info</button></div>'
    + '<div data-tab-content="videos"><div class="vgrid">' + props.map(function (p) {
      return '<div class="vgi" data-vid="' + p.id + '">'
        + '<img src="' + p.poster + '" alt="' + p.title + '" loading="lazy">'
        + '<div class="vgi-ov"><span class="vgi-type">' + p.type + '</span><div class="vgi-price">' + p.priceLabel + '</div><div class="vgi-views"><i class="fas fa-play"></i>' + fmtN(p.views) + '</div></div></div>';
    }).join('') + '</div></div>'
    + '<div data-tab-content="biens" style="display:none">' + props.map(function (p) {
      return '<div style="display:flex;gap:12px;padding:12px 14px;border-bottom:1px solid var(--border);cursor:pointer" data-prop-link="' + p.id + '">'
        + '<img src="' + p.poster + '" style="width:72px;height:72px;border-radius:10px;object-fit:cover;flex-shrink:0">'
        + '<div style="flex:1"><div style="font-weight:600;font-size:13.5px;margin-bottom:3px">' + p.title + '</div>'
        + '<div style="font-size:12px;color:var(--t3)">' + p.neighborhood + ' · ' + p.city + '</div>'
        + '<div style="font-weight:700;color:var(--red);margin-top:4px">' + p.priceLabel + '</div></div></div>';
    }).join('') + '</div>'
    + '<div data-tab-content="infos" style="display:none;padding:14px">'
    + '<div style="font-size:13.5px;color:var(--t2);line-height:1.7">' + account.bio + '</div>'
    + (account.website ? '<a href="' + account.website + '" target="_blank" style="display:flex;align-items:center;gap:8px;margin-top:12px;color:var(--teal)"><i class="fas fa-globe"></i>' + account.website + '</a>' : '')
    + (account.licenseNo ? '<div style="margin-top:12px;font-size:12px;color:var(--t3)"><i class="fas fa-id-card"></i> Licence: ' + account.licenseNo + '</div>' : '')
    + '</div></div>';
}

function buildParticulierProfileHTML(account, props, isFollowing) {
  return '<div style="display:flex;flex-direction:column;min-height:100vh">'
    + '<div class="profile-hero-part">'
    + '<div style="position:absolute;inset:0;background:linear-gradient(180deg,#0a1020 0%,#1a1030 100%)"></div>'
    + '<button id="profileBackBtn" class="nb" style="position:absolute;top:14px;left:14px;z-index:10;cursor:pointer"><i class="fas fa-arrow-left"></i></button>'
    + '<div class="php-av"><div class="part-av"><img src="' + account.avatar + '" alt="' + account.name + '" onerror="this.style.display=\'none\'"></div></div></div>'
    + '<div class="profile-body-part">'
    + '<div class="part-name">' + account.name + '</div>'
    + '<div class="part-handle">' + account.handle + '</div>'
    + '<div class="part-bio">' + account.bio + '</div>'
    + '<div class="part-stats">'
    + '<div class="part-stat"><div class="psv" id="profileFollowerCount">' + fmtN(account.followers) + '</div><div class="psl">' + (STATE.lang === 'fr' ? 'Abonnés' : 'Followers') + '</div></div>'
    + '<div class="part-stat"><div class="psv">' + account.following + '</div><div class="psl">' + (STATE.lang === 'fr' ? 'Abonnements' : 'Following') + '</div></div>'
    + '<div class="part-stat"><div class="psv">' + fmtN(account.likes) + '</div><div class="psl">' + (STATE.lang === 'fr' ? 'J\'aimes' : 'Likes') + '</div></div></div>'
    + '<div class="part-badge-row">'
    + '<div class="pbadge"><i class="fas fa-user" style="color:var(--red)"></i>' + (STATE.lang === 'fr' ? 'Particulier' : 'Individual') + '</div>'
    + (account.verified ? '<div class="pbadge"><i class="fas fa-check" style="color:var(--teal)"></i>Vérifié</div>' : '')
    + '<div class="pbadge"><i class="fas fa-home" style="color:var(--gold)"></i>' + props.length + ' ' + (STATE.lang === 'fr' ? 'bien(s)' : 'prop(s)') + '</div></div>'
    + '<div style="display:flex;gap:8px;justify-content:center">'
    + '<button class="btn-sub' + (isFollowing ? ' subscribed' : '') + '" id="profileSubBtn" data-account-id="' + account.id + '" style="flex:2">'
    + '<i class="fas ' + (isFollowing ? 'fa-check' : 'fa-bell') + '"></i> ' + (isFollowing ? t('following') : t('follow')) + '</button>'
    + '<button class="btn-ico" id="profileDmBtn"><i class="fas fa-comment"></i></button>'
    + '<button class="btn-ico" id="profileShareBtn"><i class="fas fa-share-alt"></i></button></div>'
    + (account.phone ? '<div style="display:flex;gap:8px;margin-top:10px;justify-content:center">'
      + '<button class="btn-outline" style="flex:1" id="profileCallBtn"><i class="fas fa-phone" style="color:var(--green)"></i> ' + (STATE.lang === 'fr' ? 'Appeler' : 'Call') + '</button>'
      + '<button class="btn-outline" style="flex:1" id="profileWaBtn"><i class="fab fa-whatsapp" style="color:#25D366"></i> WhatsApp</button></div>' : '')
    + '</div>'
    + '<div class="profile-tabs" style="position:sticky;top:0;z-index:10;background:var(--bg)">'
    + '<button class="ptab active" data-tab="videos"><i class="fas fa-play"></i>' + (STATE.lang === 'fr' ? 'Vidéos' : 'Videos') + '</button>'
    + '<button class="ptab" data-tab="liked2"><i class="fas fa-heart"></i>' + (STATE.lang === 'fr' ? 'J\'aimes' : 'Liked') + '</button></div>'
    + '<div data-tab-content="videos">'
    + (props.length > 0 ? '<div class="vgrid">' + props.map(function (p) {
      return '<div class="vgi" data-vid="' + p.id + '"><img src="' + p.poster + '" alt="' + p.title + '" loading="lazy">'
        + '<div class="vgi-ov"><span class="vgi-type">' + p.type + '</span><div class="vgi-price">' + p.priceLabel + '</div><div class="vgi-views"><i class="fas fa-play"></i>' + fmtN(p.views) + '</div></div></div>';
    }).join('') + '</div>'
      : '<div class="empty-state"><i class="fas fa-video-slash"></i><h3>' + (STATE.lang === 'fr' ? 'Aucune vidéo' : 'No videos') + '</h3></div>')
    + '</div>'
    + '<div data-tab-content="liked2" style="display:none"><div class="empty-state"><i class="fas fa-lock"></i><h3>' + (STATE.lang === 'fr' ? 'Contenu privé' : 'Private content') + '</h3><p>' + (STATE.lang === 'fr' ? 'Ce compte a rendu ses likes privés.' : 'This account has made their likes private.') + '</p></div></div></div>';
}

function bindProfileEvents(content, account) {
  // Bouton retour (corrigé — utilise onclick simple qui fonctionne toujours)
  var backBtn = content.querySelector('#profileBackBtn');
  if (backBtn) {
    backBtn.onclick = function (e) {
      e.stopPropagation();
      closePanel('profilePanel');
    };
  }

  // Abonnement
  var subBtn = content.querySelector('#profileSubBtn');
  if (subBtn) {
    subBtn.onclick = function () {
      account.subscribed = !account.subscribed;
      if (account.subscribed) {
        account.followers++;
        subBtn.className = 'btn-sub subscribed';
        subBtn.innerHTML = '<i class="fas fa-check"></i> ' + t('following');
        showToast((STATE.lang === 'fr' ? 'Abonné à ' : 'Following ') + account.name + ' ✅', 'fa-check-circle', '#00dfc8', 2000);
      } else {
        account.followers--;
        subBtn.className = 'btn-sub';
        subBtn.innerHTML = '<i class="fas fa-bell"></i> ' + t('follow');
        showToast(t('unfollow') + ' ' + account.name, 'fa-user-minus', '#8890b5', 2000);
      }
      var fc = $('profileFollowerCount');
      if (fc) fc.textContent = fmtN(account.followers);
      syncFollowUI(account.id);
    };
  }

  // Message DM
  var dmBtn = content.querySelector('#profileDmBtn');
  if (dmBtn) {
    dmBtn.onclick = function () {
      openDmPanel(account.id);
    };
  }

  // Partager profil
  var shareBtn = content.querySelector('#profileShareBtn');
  if (shareBtn) {
    shareBtn.onclick = function () {
      var url = 'https://immotok.ci/profil/' + account.id;
      if (navigator.clipboard) navigator.clipboard.writeText(url);
      showToast(STATE.lang === 'fr' ? 'Lien profil copié ! 🔗' : 'Profile link copied! 🔗', 'fa-link', '#4285f4', 1800);
    };
  }

  // Appel
  var callBtn = content.querySelector('#profileCallBtn');
  if (callBtn && account.phone) {
    callBtn.onclick = function () { callPhone(account.phone); };
  }

  // WhatsApp
  var waBtn = content.querySelector('#profileWaBtn');
  if (waBtn && account.phone) {
    waBtn.onclick = function () { openWhatsApp(account.phone); };
  }

  // Onglets
  var tabs = content.querySelectorAll('.ptab');
  tabs.forEach(function (tab) {
    tab.onclick = function () {
      tabs.forEach(function (t2) { t2.classList.remove('active'); });
      tab.classList.add('active');
      var tabId = tab.getAttribute('data-tab');
      content.querySelectorAll('[data-tab-content]').forEach(function (tc) { tc.style.display = 'none'; });
      var tc = content.querySelector('[data-tab-content="' + tabId + '"]');
      if (tc) tc.style.display = '';
    };
  });

  // Grid vidéos — clic pour aller sur le bien
  content.querySelectorAll('.vgi[data-vid]').forEach(function (vgi) {
    vgi.onclick = function () {
      closePanel('profilePanel');
      window.openPropertySheet(vgi.getAttribute('data-vid'));
    };
  });

  // Liens biens
  content.querySelectorAll('[data-prop-link]').forEach(function (el) {
    el.onclick = function () {
      closePanel('profilePanel');
      window.openPropertySheet(el.getAttribute('data-prop-link'));
    };
  });
}

/* ══════════════════════════════════════════════════════════════════
   19. SHEET MUSIQUE
══════════════════════════════════════════════════════════════════ */
function openMusicSheet(vid) {
  var prop = getProperty(vid);
  if (!prop) return;
  var reel = document.querySelector('.reel[data-vid="' + vid + '"]');
  var disc = $('musicSheetBody');
  if (!disc) return;
  var isPlaying = reel && !reel.classList.contains('paused');
  disc.innerHTML = '<div class="music-info-card">'
    + '<div class="music-disc-lg ' + (isPlaying ? '' : 'paused') + '"><div class="mdot"></div></div>'
    + '<div><div class="music-title">' + prop.music.title + '</div>'
    + '<div class="music-artist">' + prop.music.artist + '</div>'
    + '<div class="music-uses"><i class="fas fa-play-circle"></i> ' + fmtN(prop.music.uses) + ' ' + (STATE.lang === 'fr' ? 'vidéos' : 'videos') + '</div></div></div>'
    + '<div class="music-actions">'
    + '<button class="btn-primary flex1" onclick="showToast(\'' + (STATE.lang === 'fr' ? 'Son sauvegardé ♪' : 'Sound saved ♪') + '\',\'fa-music\',\'#a855f7\')"><i class="fas fa-bookmark"></i> ' + (STATE.lang === 'fr' ? 'Sauvegarder' : 'Save') + '</button>'
    + '<button class="btn-outline flex1" onclick="showToast(\'' + (STATE.lang === 'fr' ? 'Partage son...' : 'Sharing sound...') + '\',\'fa-share-alt\',\'#4285f4\')"><i class="fas fa-share-alt"></i> ' + (STATE.lang === 'fr' ? 'Partager' : 'Share') + '</button></div>'
    + '<div class="ssect">' + (STATE.lang === 'fr' ? 'Vidéos avec ce son' : 'Videos with this sound') + '</div>'
    + '<div class="music-vids">'
    + STATE.properties.slice(0, 6).map(function (p) {
      return '<div class="music-vid-item" onclick="closeSheet(\'musicSheet\');openPropertySheet(\'' + p.id + '\')">'
        + '<img src="' + p.poster + '" alt="" loading="lazy"></div>';
    }).join('') + '</div>';
  openSheet('musicSheet');
}

/* ══════════════════════════════════════════════════════════════════
   20. FILTRES
══════════════════════════════════════════════════════════════════ */
function resetFilters() {
  STATE.filters = { trans: 'all', type: 'all', budget: 500000000, surface: 0, city: '' };
  var fb = $('fBudget'), fv = $('fBudgetVal'), fs = $('fSurface'), fvs = $('fSurfaceVal'), fc = $('fCity');
  if (fb) fb.value = 500000000;
  if (fv) fv.textContent = STATE.lang === 'fr' ? 'Sans limite' : 'No limit';
  if (fs) fs.value = 0;
  if (fvs) fvs.textContent = STATE.lang === 'fr' ? 'Sans limite' : 'No limit';
  if (fc) fc.value = '';
  $$('#fTrans .fchip').forEach(function (c) { c.classList.toggle('active', c.getAttribute('data-v') === 'all'); });
  $$('#fType .fchip').forEach(function (c) { c.classList.toggle('active', c.getAttribute('data-v') === 'all'); });
  showToast(STATE.lang === 'fr' ? 'Filtres réinitialisés' : 'Filters reset', 'fa-redo', '#4285f4', 1800);
}

function initFilterSheet() {
  $$('#fTrans .fchip').forEach(function (chip) {
    chip.addEventListener('click', function () {
      $$('#fTrans .fchip').forEach(function (c) { c.classList.remove('active'); });
      chip.classList.add('active');
      STATE.filters.trans = chip.getAttribute('data-v');
    });
  });
  $$('#fType .fchip').forEach(function (chip) {
    chip.addEventListener('click', function () {
      $$('#fType .fchip').forEach(function (c) { c.classList.remove('active'); });
      chip.classList.add('active');
      STATE.filters.type = chip.getAttribute('data-v');
    });
  });
  var fb = $('fBudget');
  if (fb) fb.addEventListener('input', function () {
    var val = parseInt(fb.value);
    STATE.filters.budget = val;
    var fv = $('fBudgetVal');
    if (fv) fv.textContent = val >= 500000000 ? (STATE.lang === 'fr' ? 'Sans limite' : 'No limit') : fmtP(val);
  });
  var fs = $('fSurface');
  if (fs) fs.addEventListener('input', function () {
    var val = parseInt(fs.value);
    STATE.filters.surface = val;
    var fvs = $('fSurfaceVal');
    if (fvs) fvs.textContent = val === 0 ? (STATE.lang === 'fr' ? 'Sans limite' : 'No limit') : val + 'm²';
  });
  var freset = $('fReset');
  if (freset) freset.addEventListener('click', resetFilters);
  var fapply = $('fApply');
  if (fapply) fapply.addEventListener('click', function () {
    var fc = $('fCity');
    STATE.filters.city = fc ? fc.value : '';
    closeSheet('filterSheet');
    window.renderFeed();
    showToast(STATE.lang === 'fr' ? 'Filtres appliqués ✓' : 'Filters applied ✓', 'fa-check', '#2ac97a', 2000);
  });
}

/* ══════════════════════════════════════════════════════════════════
   21. EXPLORE PANEL
══════════════════════════════════════════════════════════════════ */
function openExplorePanel() {
  openPanel('explorePanel');
  var si = $('sInput');
  if (si) setTimeout(function () { si.focus(); }, 300);
  renderSearchRecent();
}

function renderSearchRecent() {
  var list = $('sRecentList'), sRec = $('sRecent'), sTr = $('sTrending');
  if (!list) return;
  if (STATE.searchHistory.length === 0) {
    if (sRec) sRec.style.display = 'none';
    if (sTr) sTr.style.display = '';
    renderTrending();
    return;
  }
  if (sRec) sRec.style.display = '';
  if (sTr) sTr.style.display = 'none';
  list.innerHTML = STATE.searchHistory.slice(0, 8).map(function (q) {
    return '<div class="srecent-item">'
      + '<i class="fas fa-history"></i>'
      + '<span onclick="doSearch(\'' + q.replace(/'/g, "\\'") + '\')">' + q + '</span>'
      + '<button class="srecent-del" onclick="removeSearch(\'' + q.replace(/'/g, "\\'") + '\')"><i class="fas fa-times"></i></button></div>';
  }).join('');
}

function renderTrending() {
  var list = $('sTrendingList');
  if (!list) return;
  var trends = [
    { name: '#VillaCocody', count: '12.4K', desc: STATE.lang === 'fr' ? 'Villa · Vente' : 'Villa · Sale' },
    { name: '#LocationAbidjan', count: '8.7K', desc: STATE.lang === 'fr' ? 'Location · Plateau' : 'Rental · Plateau' },
    { name: '#PrestigeImmobilier', count: '6.2K', desc: STATE.lang === 'fr' ? 'Agence' : 'Agency' },
    { name: '#InvestirCI', count: '4.9K', desc: STATE.lang === 'fr' ? 'Investissement' : 'Investment' },
    { name: '#StudioYopougon', count: '3.8K', desc: STATE.lang === 'fr' ? 'Studio · Location' : 'Studio · Rental' }
  ];
  list.innerHTML = trends.map(function (tr, i) {
    return '<div class="trending-item" onclick="doSearch(\'' + tr.name + '\')">'
      + '<div class="trending-rank ' + (i < 3 ? 'top' : '') + '">' + (i + 1) + '</div>'
      + '<div class="trending-info"><div class="trending-name">' + tr.name + '</div>'
      + '<div class="trending-sub">' + tr.count + ' ' + (STATE.lang === 'fr' ? 'vidéos' : 'videos') + ' · ' + tr.desc + '</div></div>'
      + '<i class="fas fa-chevron-right" style="color:var(--t3)"></i></div>';
  }).join('');
}

function doSearch(query) {
  var si = $('sInput');
  if (si) si.value = query;
  addToHistory(query);
  var sRec = $('sRecent'), sTr = $('sTrending'), sRes = $('sResults'), acBox = $('autocompleteBox');
  if (sRec) sRec.style.display = 'none';
  if (sTr) sTr.style.display = 'none';
  if (sRes) sRes.style.display = '';
  if (acBox) acBox.style.display = 'none';

  var q = query.toLowerCase().replace('#', '');
  var results = []
    .concat(
      STATE.accounts.filter(function (a) {
        return a.name.toLowerCase().indexOf(q) > -1 || a.handle.toLowerCase().indexOf(q) > -1 || a.bio.toLowerCase().indexOf(q) > -1;
      }).map(function (a) { return { type: 'account', data: a }; })
    )
    .concat(
      STATE.properties.filter(function (p) {
        return p.title.toLowerCase().indexOf(q) > -1 || p.type.toLowerCase().indexOf(q) > -1
          || p.city.toLowerCase().indexOf(q) > -1 || p.neighborhood.toLowerCase().indexOf(q) > -1
          || (p.tags || []).some(function (tag) { return tag.toLowerCase().indexOf(q) > -1; });
      }).map(function (p) { return { type: 'property', data: p }; })
    );

  if (!sRes) return;
  if (results.length === 0) {
    sRes.innerHTML = '<div class="empty-state"><i class="fas fa-search"></i><h3>' + (STATE.lang === 'fr' ? 'Aucun résultat' : 'No results') + '</h3>'
      + '<p>' + (STATE.lang === 'fr' ? 'Aucun résultat pour "' + query + '"' : 'No results for "' + query + '"') + '</p></div>';
    return;
  }
  sRes.innerHTML = '<div class="ssect">' + results.length + ' ' + (STATE.lang === 'fr' ? 'résultat(s)' : 'result(s)') + '</div>'
    + results.map(function (r) {
      if (r.type === 'account') {
        var a = r.data;
        return '<div class="sresult-item" onclick="closePanel(\'explorePanel\');openProfilePanel(\'' + a.id + '\')">'
          + '<div class="sresult-thumb"><img src="' + a.avatar + '" alt="' + a.name + '" loading="lazy"></div>'
          + '<div class="sresult-info"><div class="sresult-title">' + a.name + (a.verified ? ' ✓' : '') + '</div>'
          + '<div class="sresult-meta">' + a.handle + ' · ' + (a.type === 'enterprise' ? (STATE.lang === 'fr' ? 'Agence' : 'Agency') : (STATE.lang === 'fr' ? 'Particulier' : 'Individual')) + '</div>'
          + '<div style="font-size:12px;color:var(--t3)">' + fmtN(a.followers) + ' ' + (STATE.lang === 'fr' ? 'abonnés' : 'followers') + '</div></div></div>';
      } else {
        var p = r.data;
        return '<div class="sresult-item" onclick="closePanel(\'explorePanel\');openPropertySheet(\'' + p.id + '\')">'
          + '<div class="sresult-thumb"><img src="' + p.poster + '" alt="' + p.title + '" loading="lazy"></div>'
          + '<div class="sresult-info"><div class="sresult-title">' + p.title + '</div>'
          + '<div class="sresult-meta">' + p.neighborhood + ' · ' + p.city + '</div>'
          + '<div class="sresult-price">' + p.priceLabel + '</div></div></div>';
      }
    }).join('');
}

function addToHistory(q) {
  STATE.searchHistory = [q].concat(STATE.searchHistory.filter(function (h) { return h !== q; })).slice(0, 10);
  saveStorage();
}

function removeSearch(q) {
  STATE.searchHistory = STATE.searchHistory.filter(function (h) { return h !== q; });
  saveStorage();
  renderSearchRecent();
}

function handleSearchInput(val) {
  var q = val.trim().toLowerCase();
  var box = $('autocompleteBox'), sRec = $('sRecent'), sTr = $('sTrending'), sRes = $('sResults');
  if (!q) {
    if (box) box.style.display = 'none';
    if (sRec) sRec.style.display = STATE.searchHistory.length > 0 ? '' : 'none';
    if (sTr) sTr.style.display = STATE.searchHistory.length === 0 ? '' : 'none';
    if (sRes) sRes.style.display = 'none';
    if (STATE.searchHistory.length === 0) renderTrending();
    return;
  }
  if (sRec) sRec.style.display = 'none';
  if (sTr) sTr.style.display = 'none';
  if (sRes) sRes.style.display = 'none';
  var suggestions = []
    .concat(STATE.accounts.filter(function (a) { return a.name.toLowerCase().indexOf(q) > -1 || a.handle.toLowerCase().indexOf(q) > -1; }).slice(0, 3))
    .concat(STATE.properties.filter(function (p) { return p.title.toLowerCase().indexOf(q) > -1 || p.city.toLowerCase().indexOf(q) > -1 || p.neighborhood.toLowerCase().indexOf(q) > -1; }).slice(0, 3));
  if (!box) return;
  if (suggestions.length === 0) { box.style.display = 'none'; return; }
  box.style.display = '';
  box.innerHTML = suggestions.map(function (item) {
    if (item.accountId !== undefined) {
      // Property
      return '<div class="ac-item" onclick="doSearch(\'' + item.title.replace(/'/g, "\\'") + '\')">'
        + '<i class="fas fa-home"></i><span><strong>' + item.title + '</strong> — <span style="color:var(--t3)">' + item.neighborhood + '</span></span></div>';
    } else {
      // Account
      return '<div class="ac-item" onclick="closePanel(\'explorePanel\');openProfilePanel(\'' + item.id + '\')">'
        + '<i class="fas fa-user"></i><span><strong>' + item.name + '</strong></span></div>';
    }
  }).join('');
}

function initExplorePanel() {
  var sb = $('searchBack');
  if (sb) sb.addEventListener('click', function () { closePanel('explorePanel'); });
  var si = $('sInput');
  if (si) {
    si.addEventListener('input', function (e) { handleSearchInput(e.target.value); });
    si.addEventListener('keydown', function (e) { if (e.key === 'Enter') doSearch(e.target.value); });
  }
  var sc = $('sClear');
  if (sc) sc.addEventListener('click', function () {
    if (si) si.value = '';
    var box = $('autocompleteBox'), sRes = $('sResults');
    if (box) box.style.display = 'none';
    if (sRes) sRes.style.display = 'none';
    renderSearchRecent();
  });
  $$('#sChips .sfchip').forEach(function (chip) {
    chip.addEventListener('click', function () {
      $$('#sChips .sfchip').forEach(function (c) { c.classList.remove('active'); });
      chip.classList.add('active');
      var sf = chip.getAttribute('data-sf');
      if (sf !== 'all') window.doSearch(sf);
    });
  });
}

/* ══════════════════════════════════════════════════════════════════
   22. INBOX (NOTIFICATIONS)
══════════════════════════════════════════════════════════════════ */
function openInboxPanel() {
  renderInbox('all');
  openPanel('inboxPanel');
}

function renderInbox(filter) {
  var list = $('inboxList');
  if (!list) return;
  var notifs = STATE.notifications;
  if (filter !== 'all') notifs = notifs.filter(function (n) { return n.category === filter; });
  list.innerHTML = notifs.length === 0
    ? '<div class="empty-state"><i class="fas fa-bell-slash"></i><h3>' + (STATE.lang === 'fr' ? 'Aucune notification' : 'No notifications') + '</h3></div>'
    : notifs.map(function (n) {
      return '<div class="notif-item ' + (n.unread ? 'unread' : '') + '" data-id="' + n.id + '">'
        + '<div class="noti-ico" style="background:' + n.iconBg + '">' + n.icon + '</div>'
        + '<div class="noti-body"><div class="noti-title">' + n.title + '</div>'
        + '<div class="noti-text">' + n.text + '</div>'
        + '<div class="noti-time">' + n.time + '</div></div></div>';
    }).join('');
  list.querySelectorAll('.notif-item').forEach(function (item) {
    item.addEventListener('click', function () {
      var n = STATE.notifications.find(function (x) { return x.id === item.getAttribute('data-id'); });
      if (n && n.unread) { n.unread = false; item.classList.remove('unread'); STATE.unreadCount = Math.max(0, STATE.unreadCount - 1); updateAlertBadge(); }
    });
  });
}

function updateAlertBadge() {
  var badge = $('alertBadge');
  var count = STATE.notifications.filter(function (n) { return n.unread; }).length;
  STATE.unreadCount = count;
  if (badge) { badge.textContent = count; badge.style.display = count > 0 ? '' : 'none'; }
}

function initInboxPanel() {
  $$('[data-notif]').forEach(function (tab) {
    tab.addEventListener('click', function () {
      $$('[data-notif]').forEach(function (t) { t.classList.remove('active'); });
      tab.classList.add('active');
      renderInbox(tab.getAttribute('data-notif'));
    });
  });
  var mr = $('markRead');
  if (mr) mr.addEventListener('click', function () {
    STATE.notifications.forEach(function (n) { n.unread = false; });
    STATE.unreadCount = 0;
    updateAlertBadge();
    renderInbox('all');
    showToast(STATE.lang === 'fr' ? 'Tout marqué comme lu ✓' : 'All marked as read ✓', 'fa-check-double', '#2ac97a', 1800);
  });
}

/* ══════════════════════════════════════════════════════════════════
   23. ME PANEL
══════════════════════════════════════════════════════════════════ */
function openMePanel() {
  renderMePanel();
  openPanel('mePanel');
}

function renderMePanel() {
  var body = $('mePanelBody');
  if (!body) return;
  var user = STATE.user;
  var uname = user ? (user.full_name || user.name || user.username || 'Utilisateur') : '';
  body.innerHTML = '<div class="me-hero">'
    + '<div class="me-av">'
    + (user ? '<img src="' + (user.avatar_url || user.avatar || '') + '" alt="' + uname + '" onerror="this.outerHTML=\'<div class=me-av-placeholder>\'+\'' + (uname[0] || '?') + '\'+ \'</div>\'">'
      : '<div class="me-av-placeholder">👤</div>') + '</div>'
    + '<div><div class="me-name">' + (user ? uname : (STATE.lang === 'fr' ? 'Non connecté' : 'Not logged in')) + '</div>'
    + (user ? '<div class="me-plan"><i class="fas fa-' + (user.type === 'enterprise' ? 'building' : 'user') + '"></i> ' + (user.type || 'Particulier') + '</div>'
      : '<button class="btn-primary" style="margin-top:8px;padding:8px 16px;font-size:13px" onclick="openModal(\'authModal\')">' + (STATE.lang === 'fr' ? 'Se connecter' : 'Sign in') + '</button>')
    + '</div></div>'
    + (user ? '<div class="me-stats">'
      + '<div class="mestat" onclick="openSaved()"><div class="mesv">' + STATE.savedItems.length + '</div><div class="mesl">' + (STATE.lang === 'fr' ? 'Sauvegardés' : 'Saved') + '</div></div>'
      + '<div class="mestat"><div class="mesv">' + STATE.likedItems.length + '</div><div class="mesl">' + (STATE.lang === 'fr' ? 'J\'aimes' : 'Likes') + '</div></div>'
      + '<div class="mestat"><div class="mesv">' + STATE.accounts.filter(function (a) { return a.subscribed; }).length + '</div><div class="mesl">' + (STATE.lang === 'fr' ? 'Abonnements' : 'Following') + '</div></div></div>' : '')
    + (STATE.savedItems.length > 0 ? '<div class="me-section-title" style="padding:14px 14px 6px">' + (STATE.lang === 'fr' ? 'Biens sauvegardés' : 'Saved Properties') + '</div>'
      + '<div class="saved-grid">'
      + STATE.savedItems.slice(0, 4).map(function (vid) {
        var p = getProperty(vid);
        if (!p) return '';
        return '<div class="saved-card" onclick="openPropertySheet(\'' + p.id + '\')">'
          + '<div class="saved-card-img"><img src="' + p.poster + '" alt="' + p.title + '" loading="lazy"></div>'
          + '<div class="saved-card-body"><div class="saved-card-title">' + p.title + '</div><div class="saved-card-price">' + p.priceLabel + '</div></div></div>';
      }).join('') + '</div>' : '')
    + '<div class="me-section"><div class="me-section-title">' + (STATE.lang === 'fr' ? 'Mon compte' : 'My Account') + '</div>'
    + (user ? '<div class="me-menu-item" onclick="window.openProfilePanel(' + (user.id || '') + ')"><div class="me-menu-ico" style="background:rgba(168,85,247,.1)"><i class="fas fa-user" style="color:#c084fc"></i></div><span>' + (STATE.lang === 'fr' ? 'Mon profil' : 'My profile') + '</span><i class="fas fa-chevron-right marr"></i></div>' : '')
    + (user ? '<div class="me-menu-item" onclick="openPanel(\'settingsPanel\');renderSettings()"><div class="me-menu-ico" style="background:rgba(66,133,244,.1)"><i class="fas fa-cog" style="color:var(--blue)"></i></div><span>' + (STATE.lang === 'fr' ? 'Paramètres' : 'Settings') + '</span><i class="fas fa-chevron-right marr"></i></div>' : '')
    + '<div class="me-menu-item" onclick="openPanel(\'chatPanel\');initAIChat()"><div class="me-menu-ico" style="background:rgba(255,45,85,.1)"><i class="fas fa-comment-dots" style="color:var(--red)"></i></div><span>' + (STATE.lang === 'fr' ? 'Assistant ImmoBot IA' : 'ImmoBot AI Assistant') + '</span><i class="fas fa-chevron-right marr"></i></div>'
    + (user ? '<div class="me-menu-item" onclick="showToast(\'' + (STATE.lang === 'fr' ? 'Bientôt disponible' : 'Coming soon') + '\',\'fa-tools\',\'#ffc93c\')"><div class="me-menu-ico" style="background:rgba(255,201,60,.1)"><i class="fas fa-plus-circle" style="color:var(--gold)"></i></div><span>' + (STATE.lang === 'fr' ? 'Publier un bien' : 'Post a property') + '</span><i class="fas fa-chevron-right marr"></i></div>' : '')
    + '<div class="me-menu-item" onclick="toggleLang()"><div class="me-menu-ico" style="background:rgba(0,223,200,.1)"><i class="fas fa-language" style="color:var(--teal)"></i></div><span>Langue / Language</span><span style="color:var(--t3);font-size:12px">' + STATE.lang.toUpperCase() + '</span></div>'
    + (user ? '<div class="me-section-title" style="padding-top:12px">Compte</div><div class="me-menu-item" onclick="logout()"><div class="me-menu-ico" style="background:rgba(255,45,85,.1)"><i class="fas fa-sign-out-alt" style="color:var(--red)"></i></div><span style="color:var(--red)">' + (STATE.lang === 'fr' ? 'Se déconnecter' : 'Sign out') + '</span></div>' : '')
    + '</div><div style="height:32px"></div>';

  var settingsBtn = $('meSettingsBtn');
  if (settingsBtn) settingsBtn.onclick = function () { openPanel('settingsPanel'); renderSettings(); };
}

function openSaved() {
  showToast(STATE.lang === 'fr' ? STATE.savedItems.length + ' bien(s) sauvegardé(s)' : STATE.savedItems.length + ' saved property(ies)', 'fa-bookmark', '#ffc93c', 2000);
}

function logout() {
  STATE.user = null;
  localStorage.removeItem('immotok_user');
  closePanel('mePanel');
  showToast(STATE.lang === 'fr' ? 'Déconnecté avec succès' : 'Successfully signed out', 'fa-sign-out-alt', '#8890b5', 2000);
  setTimeout(function () { openPanel('mePanel'); renderMePanel(); }, 300);
}

/* ══════════════════════════════════════════════════════════════════
   24. PARAMÈTRES
══════════════════════════════════════════════════════════════════ */
function renderSettings() {
  openPanel('settingsPanel');
  var body = $('settingsBody');
  if (!body) return;
  var s = STATE.settings;

  function toggle(key, label, sub, ico, color) {
    ico = ico || 'fa-toggle-on'; color = color || 'var(--red)';
    return '<div class="settings-item">'
      + '<div class="settings-item-left"><div class="settings-item-ico" style="background:' + color + '20"><i class="fas ' + ico + '" style="color:' + color + '"></i></div>'
      + '<div><div class="settings-item-title">' + label + '</div>' + (sub ? '<div class="settings-item-sub">' + sub + '</div>' : '') + '</div></div>'
      + '<div class="tog ' + (s[key] ? 'on' : '') + '" data-key="' + key + '"></div></div>';
  }

  body.innerHTML = '<div class="settings-group">'
    + '<div class="settings-group-title">' + (STATE.lang === 'fr' ? 'Notifications' : 'Notifications') + '</div>'
    + toggle('notifications', STATE.lang === 'fr' ? 'Notifications push' : 'Push notifications', '', 'fa-bell', 'var(--red)')
    + toggle('priceAlerts', STATE.lang === 'fr' ? 'Alertes de prix' : 'Price alerts', '', 'fa-tag', 'var(--gold)')
    + toggle('emailNotifs', 'Email', '', 'fa-envelope', 'var(--blue)')
    + '</div><div class="settings-group">'
    + '<div class="settings-group-title">' + (STATE.lang === 'fr' ? 'Lecture' : 'Playback') + '</div>'
    + toggle('autoplay', STATE.lang === 'fr' ? 'Lecture automatique' : 'Autoplay', '', 'fa-play-circle', 'var(--teal)')
    + toggle('dataSaver', STATE.lang === 'fr' ? 'Économiseur de données' : 'Data saver', '', 'fa-tachometer-alt', 'var(--purple)')
    + toggle('vibrations', STATE.lang === 'fr' ? 'Vibrations' : 'Haptics', '', 'fa-mobile-alt', '#4285f4')
    + '</div><div class="settings-group">'
    + '<div class="settings-group-title">' + (STATE.lang === 'fr' ? 'Langue et région' : 'Language & Region') + '</div>'
    + '<div class="settings-item"><div class="settings-item-left"><div class="settings-item-ico" style="background:rgba(66,133,244,.1)"><i class="fas fa-language" style="color:var(--blue)"></i></div><div><div class="settings-item-title">' + (STATE.lang === 'fr' ? 'Langue' : 'Language') + '</div></div></div>'
    + '<select class="settings-select" data-setting="language"><option value="fr" ' + (STATE.lang === 'fr' ? 'selected' : '') + '>Français</option><option value="en" ' + (STATE.lang === 'en' ? 'selected' : '') + '>English</option></select></div>'
    + '</div><div class="settings-group">'
    + '<div class="settings-group-title">Support</div>'
    + '<div class="settings-item" style="cursor:pointer" onclick="showToast(\'' + (STATE.lang === 'fr' ? 'Centre d\'aide bientôt disponible' : 'Help center coming soon') + '\',\'fa-question-circle\',\'#4285f4\')"><div class="settings-item-left"><div class="settings-item-ico" style="background:rgba(66,133,244,.1)"><i class="fas fa-question-circle" style="color:var(--blue)"></i></div><div><div class="settings-item-title">' + (STATE.lang === 'fr' ? 'Centre d\'aide' : 'Help center') + '</div></div></div><i class="fas fa-chevron-right" style="color:var(--t3)"></i></div>'
    + '</div><div style="text-align:center;padding:24px;color:var(--t3);font-size:11px">ImmoTok v4.0 · Made with ❤️ in Côte d\'Ivoire</div><div style="height:32px"></div>';

  body.querySelectorAll('.tog').forEach(function (tog) {
    tog.addEventListener('click', function () {
      var key = tog.getAttribute('data-key');
      STATE.settings[key] = !STATE.settings[key];
      tog.classList.toggle('on', STATE.settings[key]);
      saveStorage();
      showToast(STATE.settings[key] ? (STATE.lang === 'fr' ? 'Activé ✓' : 'Enabled ✓') : (STATE.lang === 'fr' ? 'Désactivé' : 'Disabled'),
        STATE.settings[key] ? 'fa-check' : 'fa-times', STATE.settings[key] ? '#2ac97a' : '#8890b5', 1200);
    });
  });

  body.querySelectorAll('.settings-select').forEach(function (sel) {
    sel.addEventListener('change', function () {
      var key = sel.getAttribute('data-setting');
      STATE.settings[key] = sel.value;
      saveStorage();
      if (key === 'language') {
        STATE.lang = sel.value;
        applyTranslations();
        renderSettings();
        showToast(STATE.lang === 'fr' ? 'Langue changée : Français 🇫🇷' : 'Language changed: English 🇬🇧', 'fa-language', '#4285f4');
      }
    });
  });

  var ssb = $('settingsSaveBtn');
  if (ssb) ssb.onclick = function () {
    saveStorage();
    closePanel('settingsPanel');
    showToast(STATE.lang === 'fr' ? 'Paramètres sauvegardés ✓' : 'Settings saved ✓', 'fa-check', '#2ac97a', 1800);
  };
}

/* ══════════════════════════════════════════════════════════════════
   25. CHAT IA CONTEXTUEL ET COMPLET (ImmoBot)
══════════════════════════════════════════════════════════════════ */
var AI_CHAT_STATE = {
  messages: [],
  context: null,   // Peut stocker le bien ou profil en cours
  step: null       // null | 'awaiting_type' | 'awaiting_budget' | 'awaiting_city' | 'awaiting_date'
};

var AI_SUGGESTIONS = {
  fr: ['Trouver une villa 🏠', 'Location studio 🛏️', 'Mon budget 💰', 'Organiser visite 📅', 'Parler à agent 👤', 'Investir en CI 💼'],
  en: ['Find a villa 🏠', 'Studio rental 🛏️', 'My budget 💰', 'Schedule visit 📅', 'Talk to agent 👤', 'Invest in CI 💼']
};

function openAIChatPanel(context) {
  AI_CHAT_STATE.context = context || null;
  openPanel('chatPanel');
  initAIChat();
}

function initAIChat() {
  if (AI_CHAT_STATE.messages.length === 0) {
    var greeting = STATE.lang === 'fr'
      ? 'Bonjour ! 👋 Je suis **ImmoBot**, votre assistant immobilier intelligent.\n\nJe peux vous aider à :\n🏠 Trouver le bien idéal selon vos critères\n💰 Évaluer votre budget et les offres disponibles\n📅 Organiser une visite\n📊 Analyser les tendances du marché ivoirien\n💬 Répondre à toutes vos questions immobilières\n\nQu\'est-ce qui vous intéresse ?'
      : 'Hello! 👋 I\'m **ImmoBot**, your intelligent real estate assistant.\n\nI can help you:\n🏠 Find the ideal property\n💰 Evaluate your budget\n📅 Organize a visit\n📊 Analyze Ivory Coast market trends\n\nWhat are you interested in?';
    AI_CHAT_STATE.messages = [{ role: 'bot', text: greeting, time: timeNow() }];
    if (AI_CHAT_STATE.context && AI_CHAT_STATE.context.title) {
      setTimeout(function () {
        addAIBotMessage(STATE.lang === 'fr'
          ? '🏠 Je vois que vous regardez **"' + AI_CHAT_STATE.context.title + '"**\n\n💰 Prix : ' + AI_CHAT_STATE.context.priceLabel + '\n📍 ' + AI_CHAT_STATE.context.neighborhood + ', ' + AI_CHAT_STATE.context.city + '\n\nAvez-vous des questions sur ce bien ?'
          : '🏠 I see you\'re viewing **"' + AI_CHAT_STATE.context.title + '"**\n\n💰 Price: ' + AI_CHAT_STATE.context.priceLabel + '\n📍 ' + AI_CHAT_STATE.context.neighborhood + ', ' + AI_CHAT_STATE.context.city + '\n\nDo you have questions about this property?');
      }, 800);
    }
  }
  renderAIChatMessages();
  renderAIChatSuggestions();
}

function renderAIChatMessages() {
  var box = $('chatMessages');
  if (!box) return;
  box.innerHTML = AI_CHAT_STATE.messages.map(function (m) {
    return '<div class="chat-msg ' + m.role + '">'
      + '<div class="chat-bubble">' + m.text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>').replace(/\n/g, '<br>') + '</div>'
      + '<div class="chat-msg-time">' + m.time + '</div></div>';
  }).join('');
  setTimeout(function () { box.scrollTop = box.scrollHeight; }, 50);
}

function renderAIChatSuggestions() {
  var sugg = $('chatSugg');
  if (!sugg) return;
  sugg.innerHTML = (AI_SUGGESTIONS[STATE.lang] || AI_SUGGESTIONS.fr).map(function (s) {
    return '<button class="chat-sugg">' + s + '</button>';
  }).join('');
  sugg.querySelectorAll('.chat-sugg').forEach(function (btn) {
    btn.addEventListener('click', function () { sendAIChatMessage(btn.textContent); });
  });
}

function addAIBotMessage(text) {
  var box = $('chatMessages');
  if (!box) return;
  var typing = document.createElement('div');
  typing.className = 'chat-msg bot';
  typing.innerHTML = '<div class="chat-typing"><span></span><span></span><span></span></div>';
  box.appendChild(typing);
  box.scrollTop = box.scrollHeight;
  var delay = 900 + Math.random() * 700;
  setTimeout(function () {
    typing.remove();
    var msg = { role: 'bot', text: text, time: timeNow() };
    AI_CHAT_STATE.messages.push(msg);
    renderAIChatMessages();
  }, delay);
}

function sendAIChatMessage(text) {
  var val = text || ($('chatInput') && $('chatInput').value.trim());
  if (!val) return;
  var ci = $('chatInput');
  if (ci) ci.value = '';
  AI_CHAT_STATE.messages.push({ role: 'user', text: val, time: timeNow() });
  renderAIChatMessages();
  var response = buildAIResponse(val);
  addAIBotMessage(response);
}

function buildAIResponse(msg) {
  var m = msg.toLowerCase().trim();
  var lang = STATE.lang;

  // === ÉTAPES DE QUALIFICATION ===
  if (AI_CHAT_STATE.step === 'awaiting_budget') {
    AI_CHAT_STATE.step = null;
    var budget = extractNumber(m);
    if (budget > 0) {
      var matching = STATE.properties.filter(function (p) { return p.price <= budget; });
      if (matching.length === 0) {
        return lang === 'fr'
          ? '💸 Je n\'ai pas trouvé de bien sous **' + fmtP(budget) + '** actuellement. Voici nos offres les plus abordables :\n\n' + STATE.properties.slice().sort(function (a, b) { return a.price - b.price; }).slice(0, 3).map(function (p) { return '• **' + p.title + '** — ' + p.priceLabel; }).join('\n') + '\n\nVoulez-vous revoir votre budget ou explorer ces options ?'
          : '💸 No properties found under **' + fmtP(budget) + '** currently. Here are our most affordable:\n\n' + STATE.properties.slice().sort(function (a, b) { return a.price - b.price; }).slice(0, 3).map(function (p) { return '• **' + p.title + '** — ' + p.priceLabel; }).join('\n');
      }
      return lang === 'fr'
        ? '✅ Excellent ! Avec un budget de **' + fmtP(budget) + '**, j\'ai trouvé **' + matching.length + ' bien(s)** pour vous :\n\n' + matching.slice(0, 4).map(function (p) { return '🏠 **' + p.title + '**\n   💰 ' + p.priceLabel + ' | 📍 ' + p.neighborhood + ', ' + p.city; }).join('\n\n') + '\n\nVoulez-vous des détails ou organiser une visite ?'
        : '✅ Great! With a budget of **' + fmtP(budget) + '**, I found **' + matching.length + ' property(ies)**:\n\n' + matching.slice(0, 4).map(function (p) { return '🏠 **' + p.title + '**\n   💰 ' + p.priceLabel + ' | 📍 ' + p.neighborhood + ', ' + p.city; }).join('\n\n');
    }
    return lang === 'fr' ? 'Pouvez-vous préciser votre budget en FCFA ? (Ex: 100 millions, 200K par mois)' : 'Could you specify your budget in FCFA? (e.g., 100 million, 200K per month)';
  }

  if (AI_CHAT_STATE.step === 'awaiting_city') {
    AI_CHAT_STATE.step = null;
    var city = m;
    var cityProps = STATE.properties.filter(function (p) {
      return p.city.toLowerCase().indexOf(city) > -1 || p.neighborhood.toLowerCase().indexOf(city) > -1;
    });
    if (cityProps.length === 0) {
      return lang === 'fr'
        ? '🗺️ Je n\'ai pas de biens à "**' + msg + '**" actuellement, mais nous couvrons : **Cocody, Plateau, Marcory, Yopougon, Treichville, Bingerville, San-Pédro**.\n\nQuelle zone vous intéresse ?'
        : '🗺️ No properties in "**' + msg + '**" currently. We cover: **Cocody, Plateau, Marcory, Yopougon, Treichville, Bingerville, San-Pedro**.\n\nWhich area interests you?';
    }
    return lang === 'fr'
      ? '📍 J\'ai **' + cityProps.length + ' bien(s)** dans cette zone :\n\n' + cityProps.slice(0, 4).map(function (p) { return '🏠 **' + p.title + '** — ' + p.priceLabel + '\n   ' + p.neighborhood + ' | ' + p.surface + 'm²'; }).join('\n\n') + '\n\nVoulez-vous en savoir plus ou organiser une visite ?'
      : '📍 I found **' + cityProps.length + ' property(ies)** in this area:\n\n' + cityProps.slice(0, 4).map(function (p) { return '🏠 **' + p.title + '** — ' + p.priceLabel; }).join('\n\n');
  }

  if (AI_CHAT_STATE.step === 'awaiting_type') {
    AI_CHAT_STATE.step = null;
    var types = { villa: 'villa', appartement: 'appartement', studio: 'studio', bureau: 'bureau', terrain: 'terrain', commerce: 'commerce' };
    var found = null;
    Object.keys(types).forEach(function (k) { if (m.indexOf(k) > -1) found = k; });
    if (found) {
      var typeProps = STATE.properties.filter(function (p) { return p.type === found; });
      return lang === 'fr'
        ? '🏠 Voici les **' + found + 's** disponibles :\n\n' + typeProps.map(function (p) { return '**' + p.title + '**\n   💰 ' + p.priceLabel + ' | 📍 ' + p.neighborhood + ', ' + p.city; }).join('\n\n') + '\n\nUn bien vous intéresse ?'
        : '🏠 Here are the available **' + found + 's**:\n\n' + typeProps.map(function (p) { return '**' + p.title + '** — ' + p.priceLabel; }).join('\n\n');
    }
  }

  // === INTENTIONS DÉTECTÉES ===

  // Salutations
  if (/^(bonjour|bonsoir|salut|hello|hi|hey|coucou|yo)\b/.test(m)) {
    var hour = new Date().getHours();
    var greeting2 = lang === 'fr' ? (hour < 12 ? 'Bonjour' : hour < 18 ? 'Bon après-midi' : 'Bonsoir') : 'Hello';
    return greeting2 + ' ! 😊 ' + (lang === 'fr' ? 'Comment puis-je vous aider aujourd\'hui ? Je peux vous aider à trouver un bien, calculer un budget ou organiser une visite.' : 'How can I help you today? I can help you find a property, calculate a budget, or organize a visit.');
  }

  // Budget
  if (m.indexOf('budget') > -1 || m.indexOf('combien') > -1 || m.indexOf('how much') > -1 || m.indexOf('prix') > -1 || m.indexOf('price') > -1 || m.indexOf('coût') > -1) {
    AI_CHAT_STATE.step = 'awaiting_budget';
    return lang === 'fr'
      ? '💰 Parfait ! Quel est votre budget disponible ?\n\n📌 Pour vous aider :\n• Location studio : 65 000 – 120 000 FCFA/mois\n• Location appartement : 200 000 – 500 000 FCFA/mois\n• Achat villa Cocody : 80M – 300M FCFA\n• Terrain Bingerville : 30M – 60M FCFA\n\n**Quel budget avez-vous ?** (tapez le montant)'
      : '💰 Great! What is your available budget?\n\n📌 For reference:\n• Studio rental: 65,000 – 120,000 FCFA/month\n• Apartment rental: 200,000 – 500,000 FCFA/month\n• Villa in Cocody: 80M – 300M FCFA\n\n**What is your budget?** (type the amount)';
  }

  // Villa
  if (m.indexOf('villa') > -1 || m.indexOf('maison') > -1 || m.indexOf('house') > -1) {
    var villas = STATE.properties.filter(function (p) { return p.type === 'villa'; });
    return lang === 'fr'
      ? '🏡 Nous avons **' + villas.length + ' villas** disponibles :\n\n' + villas.map(function (p) { return '**' + p.title + '**\n   💰 ' + p.priceLabel + ' | 📍 ' + p.neighborhood + ', ' + p.city + '\n   📐 ' + p.surface + 'm² | ' + p.rooms + ' pièces'; }).join('\n\n') + '\n\n💡 Laquelle vous intéresse le plus ?'
      : '🏡 We have **' + villas.length + ' villas** available:\n\n' + villas.map(function (p) { return '**' + p.title + '** — ' + p.priceLabel + ' | ' + p.neighborhood; }).join('\n\n');
  }

  // Appartement
  if (m.indexOf('appartement') > -1 || m.indexOf('apartment') > -1 || m.indexOf('flat') > -1 || m.indexOf('t3') > -1 || m.indexOf('t2') > -1 || m.indexOf('penthouse') > -1) {
    var appts = STATE.properties.filter(function (p) { return p.type === 'appartement'; });
    return lang === 'fr'
      ? '🏢 Nos appartements disponibles :\n\n' + appts.map(function (p) { return '**' + p.title + '**\n   💰 ' + p.priceLabel + ' | 📍 ' + p.neighborhood + '\n   📐 ' + p.surface + 'm² | ' + p.rooms + ' pièces | ' + p.bathrooms + ' bains'; }).join('\n\n') + '\n\n🔑 Achat ou location ?'
      : '🏢 Our available apartments:\n\n' + appts.map(function (p) { return '**' + p.title + '** — ' + p.priceLabel; }).join('\n\n');
  }

  // Studio
  if (m.indexOf('studio') > -1 || m.indexOf('chambre') > -1 || m.indexOf('étudiant') > -1 || m.indexOf('student') > -1) {
    var studios = STATE.properties.filter(function (p) { return p.type === 'studio'; });
    return lang === 'fr'
      ? '🛏️ Studios disponibles :\n\n' + studios.map(function (p) { return '**' + p.title + '**\n   💰 ' + p.priceLabel + ' | 📍 ' + p.neighborhood + ', ' + p.city + '\n   ✅ ' + (p.features || []).slice(0, 3).join(' · '); }).join('\n\n') + '\n\n💡 Idéal pour étudiants et jeunes actifs !'
      : '🛏️ Available studios:\n\n' + studios.map(function (p) { return '**' + p.title + '** — ' + p.priceLabel + ' | ' + p.neighborhood; }).join('\n\n');
  }

  // Location
  if (m.indexOf('location') > -1 || m.indexOf('louer') > -1 || m.indexOf('rent') > -1 || m.indexOf('mensuel') > -1 || m.indexOf('mois') > -1) {
    var locations = STATE.properties.filter(function (p) { return p.transaction === 'location'; });
    return lang === 'fr'
      ? '🔑 Biens disponibles à la **location** :\n\n' + locations.map(function (p) { return '**' + p.title + '**\n   💰 ' + p.priceLabel + ' | 📍 ' + p.neighborhood + ', ' + p.city; }).join('\n\n') + '\n\nVoulez-vous filtrer par type ou budget ?'
      : '🔑 Properties available for **rent**:\n\n' + locations.map(function (p) { return '**' + p.title + '** — ' + p.priceLabel; }).join('\n\n');
  }

  // Achat/Vente
  if (m.indexOf('achat') > -1 || m.indexOf('acheter') > -1 || m.indexOf('vente') > -1 || m.indexOf('buy') > -1 || m.indexOf('purchase') > -1 || m.indexOf('investir') > -1 || m.indexOf('invest') > -1) {
    var ventes = STATE.properties.filter(function (p) { return p.transaction === 'vente'; });
    return lang === 'fr'
      ? '🏠 Biens disponibles à la **vente** / investissement :\n\n' + ventes.map(function (p) { return '**' + p.title + '**\n   💰 ' + p.priceLabel + ' | 📍 ' + p.neighborhood + ', ' + p.city + '\n   📐 ' + p.surface + 'm²'; }).join('\n\n') + '\n\n💡 Quel type de bien cherchez-vous ?'
      : '🏠 Properties available for **sale/investment**:\n\n' + ventes.map(function (p) { return '**' + p.title + '** — ' + p.priceLabel; }).join('\n\n');
  }

  // Visite
  if (m.indexOf('visite') > -1 || m.indexOf('visit') > -1 || m.indexOf('rendez') > -1 || m.indexOf('rdv') > -1 || m.indexOf('voir') > -1 || m.indexOf('schedule') > -1) {
    AI_CHAT_STATE.step = 'awaiting_date';
    return lang === 'fr'
      ? '📅 Super ! Pour organiser une visite :\n\n1️⃣ Quel bien vous intéresse ?\n2️⃣ Quelles sont vos disponibilités ? (Ex: samedi matin, semaine prochaine)\n\nOu **cliquez directement sur "Réserver une visite"** dans la fiche du bien.\n\n📞 Vous pouvez aussi nous appeler directement :\n• Prestige Immo : +225 27 22 44 55 66\n• Alpha Properties : +225 27 20 33 44 55'
      : '📅 Great! To organize a visit:\n\n1️⃣ Which property interests you?\n2️⃣ What are your availabilities?\n\nOr **click "Book a visit"** directly on the property listing.\n\n📞 You can also call us:\n• Prestige Immo: +225 27 22 44 55 66';
  }

  // Agent humain
  if (m.indexOf('agent') > -1 || m.indexOf('humain') > -1 || m.indexOf('human') > -1 || m.indexOf('parler') > -1 || m.indexOf('contact') > -1 || m.indexOf('conseiller') > -1) {
    return lang === 'fr'
      ? '👤 Je vous transfère vers un conseiller humain !\n\n📞 **Prestige Immobilier CI**\nTél : +225 27 22 44 55 66\nEmail : contact@prestige-immo.ci\n\n📞 **Alpha Properties**\nTél : +225 27 20 33 44 55\nEmail : info@alpha-properties.net\n\n⏰ Disponibles lun-sam 8h-18h\n\n💬 Ou utilisez le chat DM sur n\'importe quel profil agence.'
      : '👤 Transferring you to a human advisor!\n\n📞 **Prestige Immobilier CI**\nPhone: +225 27 22 44 55 66\n\n📞 **Alpha Properties**\nPhone: +225 27 20 33 44 55\n\n⏰ Available Mon-Sat 8am-6pm';
  }

  // Quartier / ville
  if (m.indexOf('cocody') > -1 || m.indexOf('plateau') > -1 || m.indexOf('marcory') > -1 || m.indexOf('yopougon') > -1 || m.indexOf('treichville') > -1 || m.indexOf('bingerville') > -1 || m.indexOf('san') > -1 || m.indexOf('quartier') > -1 || m.indexOf('zone') > -1 || m.indexOf('where') > -1 || m.indexOf('où') > -1) {
    AI_CHAT_STATE.step = 'awaiting_city';
    var cityName = extractCity(m);
    if (cityName) {
      var cProps = STATE.properties.filter(function (p) { return p.city.toLowerCase().indexOf(cityName) > -1 || p.neighborhood.toLowerCase().indexOf(cityName) > -1; });
      if (cProps.length > 0) {
        return lang === 'fr'
          ? '📍 Biens disponibles à **' + cityName.charAt(0).toUpperCase() + cityName.slice(1) + '** :\n\n' + cProps.map(function (p) { return '🏠 **' + p.title + '**\n   💰 ' + p.priceLabel + ' | 📐 ' + p.surface + 'm²'; }).join('\n\n')
          : '📍 Properties in **' + cityName + '**:\n\n' + cProps.map(function (p) { return '🏠 **' + p.title + '** — ' + p.priceLabel; }).join('\n\n');
      }
    }
    return lang === 'fr'
      ? '🗺️ Dans quelle ville ou quartier cherchez-vous ?\n\n🏙️ Zones couvertes :\n• **Cocody** (Riviera, Danga, 2 Plateaux)\n• **Plateau** (Centre, Zone commerciale)\n• **Marcory** (Zone 4, Remblai)\n• **Yopougon** (Sideci, Wassakara)\n• **Treichville** · **Bingerville** · **San-Pédro**'
      : '🗺️ Which city or neighborhood are you looking for?\n\n🏙️ Areas covered:\n• **Cocody** · **Plateau** · **Marcory** · **Yopougon** · **Treichville** · **Bingerville**';
  }

  // Type de bien (générique)
  if (m.indexOf('type') > -1 || m.indexOf('quoi') > -1 || m.indexOf('quels') > -1 || m.indexOf('quel bien') > -1 || m.indexOf('what type') > -1) {
    AI_CHAT_STATE.step = 'awaiting_type';
    return lang === 'fr'
      ? '🏘️ Quel type de bien recherchez-vous ?\n\n• 🏡 **Villa** — Maison individuelle avec terrain\n• 🏢 **Appartement** — En immeuble, tout confort\n• 🛏️ **Studio** — Compact, idéal étudiant/jeune actif\n• 🏗️ **Terrain** — Constructible, avec titre foncier\n• 🏪 **Commerce** — Immeuble de rapport ou local\n• 🖥️ **Bureau** — Open space, salle de conf incluse'
      : '🏘️ What type of property are you looking for?\n\n• 🏡 **Villa** · 🏢 **Apartment** · 🛏️ **Studio** · 🏗️ **Land** · 🏪 **Commercial** · 🖥️ **Office**';
  }

  // Marché / tendances / analyse
  if (m.indexOf('marché') > -1 || m.indexOf('market') > -1 || m.indexOf('tendance') > -1 || m.indexOf('trend') > -1 || m.indexOf('analyse') > -1 || m.indexOf('évolution') > -1) {
    return lang === 'fr'
      ? '📊 **Analyse du marché immobilier ivoirien 2025** :\n\n📈 **Zones en hausse** : Cocody (+8%), Plateau (+12%), Bingerville (+5%)\n📉 **Zones stables** : Yopougon, Marcory\n\n💡 **Conseils d\'investissement** :\n• Le Plateau reste le marché premium\n• Bingerville : fort potentiel de valorisation\n• Studios : forte demande étudiante\n• Bureaux : demande PME en croissance\n\n🔥 **Opportunité** : Immeuble Marcory — 9% de rentabilité nette !'
      : '📊 **Ivory Coast Real Estate Market 2025**:\n\n📈 **Rising areas**: Cocody (+8%), Plateau (+12%), Bingerville (+5%)\n\n💡 **Investment advice**:\n• Plateau remains premium market\n• Bingerville: strong appreciation potential\n• Studios: high student demand\n\n🔥 **Opportunity**: Marcory Building — 9% net yield!';
  }

  // Financement / crédit
  if (m.indexOf('financement') > -1 || m.indexOf('crédit') > -1 || m.indexOf('prêt') > -1 || m.indexOf('credit') > -1 || m.indexOf('loan') > -1 || m.indexOf('finance') > -1) {
    return lang === 'fr'
      ? '🏦 **Options de financement immobilier en CI** :\n\n💳 **Crédit bancaire** :\n• SIB, BICICI, Ecobank : taux 6-9% / an\n• Durée max : 20 ans\n• Apport minimum : 20-30%\n\n💰 **Fonds propres** : Idéal pour négocier\n🤝 **Mixte** : Apport + crédit (solution la + courante)\n\n📋 Documents requis : Bulletins de salaire, relevés bancaires, pièce d\'identité\n\nVoulez-vous être mis en relation avec un conseiller financier ?'
      : '🏦 **Real Estate Financing Options in CI**:\n\n💳 **Bank credit**: 6-9% annual rate, up to 20 years\n💰 **Own funds**: Best for negotiation\n🤝 **Mixed**: Down payment + credit\n\nWould you like to be connected with a financial advisor?';
  }

  // Informations sur ImmoTok
  if (m.indexOf('immotok') > -1 || m.indexOf('application') > -1 || m.indexOf('appli') > -1 || m.indexOf('app') > -1 || m.indexOf('platform') > -1) {
    return lang === 'fr'
      ? '📱 **ImmoTok** c\'est la première plateforme immobilière en format vidéo court pour la Côte d\'Ivoire !\n\n✨ Fonctionnalités :\n• 🎬 Vidéos immobilières style TikTok\n• 🔍 Recherche par ville, type, budget\n• 📅 Réservation de visites en direct\n• 💬 Chat avec agences et particuliers\n• 🤖 Assistant IA ImmoBot (c\'est moi !)\n• 🔔 Alertes prix personnalisées\n\nImmoTok v4.0 — Made with ❤️ in Côte d\'Ivoire 🇨🇮'
      : '📱 **ImmoTok** is the first short-video real estate platform for Ivory Coast!\n\n✨ Features:\n• 🎬 TikTok-style real estate videos\n• 🔍 Search by city, type, budget\n• 📅 Direct visit booking\n• 💬 Chat with agencies\n• 🤖 AI Assistant (that\'s me!)\n\nImmoTok v4.0 — Made with ❤️ in CI 🇨🇮';
  }

  // Merci / au revoir
  if (m.indexOf('merci') > -1 || m.indexOf('thanks') > -1 || m.indexOf('parfait') > -1 || m.indexOf('super') > -1 || m.indexOf('au revoir') > -1 || m.indexOf('bye') > -1) {
    return lang === 'fr'
      ? 'Avec plaisir ! 😊 N\'hésitez pas à revenir si vous avez d\'autres questions. Bonne recherche immobilière sur ImmoTok ! 🏠✨'
      : 'My pleasure! 😊 Feel free to come back anytime. Happy property hunting on ImmoTok! 🏠✨';
  }

  // Question sur un bien spécifique par titre
  var propMatch = STATE.properties.find(function (p) {
    return m.indexOf(p.title.toLowerCase().slice(0, 8)) > -1 || m.indexOf(p.id) > -1;
  });
  if (propMatch) {
    var acc = getAccount(propMatch.accountId);
    return lang === 'fr'
      ? '🏠 **' + propMatch.title + '**\n\n💰 Prix : ' + propMatch.priceLabel + '\n📍 ' + propMatch.neighborhood + ', ' + propMatch.city + '\n📐 ' + propMatch.surface + 'm² | ' + propMatch.rooms + ' pièces\n\n📝 ' + propMatch.description.slice(0, 150) + '...\n\n✅ **Équipements** : ' + (propMatch.features || []).slice(0, 4).join(', ') + '\n\n🏢 Contact : ' + (acc ? acc.name + ' — ' + acc.phone : 'N/A') + '\n\nVoulez-vous **réserver une visite** ?'
      : '🏠 **' + propMatch.title + '**\n\n💰 Price: ' + propMatch.priceLabel + '\n📍 ' + propMatch.neighborhood + ', ' + propMatch.city + '\n📐 ' + propMatch.surface + 'm² | ' + propMatch.rooms + ' rooms\n\nWould you like to **book a visit**?';
  }

  // Réponse générique intelligente
  return lang === 'fr'
    ? '💬 Je comprends votre demande concernant "**' + msg.slice(0, 50) + '**".\n\nPour vous aider au mieux, précisez :\n🔸 Type de bien (villa, studio, appartement, bureau...)\n🔸 Transaction (achat ou location)\n🔸 Budget disponible\n🔸 Ville ou quartier souhaité\n\nOu cliquez sur une suggestion ci-dessous ! 👇'
    : '💬 I understand your request about "**' + msg.slice(0, 50) + '**".\n\nTo help you better, please specify:\n🔸 Property type (villa, studio, apartment...)\n🔸 Transaction (buy or rent)\n🔸 Available budget\n🔸 Preferred city\n\nOr click a suggestion below! 👇';
}

function extractNumber(str) {
  // Extraire un nombre de la chaîne (gère "100 millions", "200K", "350000", etc.)
  var clean = str.replace(/\s/g, '').toLowerCase();
  var n = parseFloat(clean.replace(/[^\d.]/g, ''));
  if (isNaN(n)) return 0;
  if (clean.indexOf('million') > -1 || clean.indexOf('m') > -1 && clean.indexOf('m fcfa') > -1) n *= 1000000;
  else if (clean.indexOf('k') > -1 || clean.indexOf('mille') > -1) n *= 1000;
  return n;
}

function extractCity(str) {
  var cities = ['cocody', 'plateau', 'marcory', 'yopougon', 'treichville', 'bingerville', 'san', 'pedro', 'abidjan', 'riviera', 'danga', 'zone 4'];
  for (var i = 0; i < cities.length; i++) {
    if (str.indexOf(cities[i]) > -1) return cities[i];
  }
  return null;
}

/* ══════════════════════════════════════════════════════════════════
   26. AUTH
══════════════════════════════════════════════════════════════════ */
function renderAuthForm(mode) {
  var wrap = $('authFormWrap');
  if (!wrap) return;
  if (mode === 'login') {
    wrap.innerHTML = '<div class="fg"><label>Email</label><input type="email" id="authEmail" placeholder="vous@email.com" autocomplete="email"></div>'
      + '<div class="fg"><label>' + (STATE.lang === 'fr' ? 'Mot de passe' : 'Password') + '</label>'
      + '<div class="pwd-wrap"><input type="password" id="authPwd" placeholder="••••••••" autocomplete="current-password">'
      + '<button class="pwd-eye" onclick="togglePwd()"><i class="fas fa-eye" id="pwdIcon"></i></button></div></div>'
      + '<button class="btn-primary btn-full" onclick="submitAuth(\'login\')">' + (STATE.lang === 'fr' ? 'Se connecter' : 'Sign in') + '</button>'
      + '<div class="auth-divider">' + (STATE.lang === 'fr' ? 'ou continuer avec' : 'or continue with') + '</div>'
      + '<button class="btn-social" onclick="submitSocial(\'google\')"><i class="fab fa-google" style="color:#DB4437"></i> Google</button>'
      + '<button class="btn-social" onclick="submitSocial(\'facebook\')"><i class="fab fa-facebook-f" style="color:#1877F2"></i> Facebook</button>';
  } else {
    wrap.innerHTML = '<div class="frow2">'
      + '<div class="fg"><label>' + (STATE.lang === 'fr' ? 'Prénom' : 'First name') + '</label><input type="text" id="authFirst" autocomplete="given-name"></div>'
      + '<div class="fg"><label>' + (STATE.lang === 'fr' ? 'Nom' : 'Last name') + '</label><input type="text" id="authLast" autocomplete="family-name"></div></div>'
      + '<div class="fg"><label>Email</label><input type="email" id="authEmail" autocomplete="email"></div>'
      + '<div class="fg"><label>' + (STATE.lang === 'fr' ? 'Téléphone' : 'Phone') + '</label><input type="tel" id="authPhone" placeholder="+225 07 00 00 00" autocomplete="tel"></div>'
      + '<div class="fg"><label>' + (STATE.lang === 'fr' ? 'Mot de passe' : 'Password') + '</label>'
      + '<div class="pwd-wrap"><input type="password" id="authPwd" autocomplete="new-password">'
      + '<button class="pwd-eye" onclick="togglePwd()"><i class="fas fa-eye" id="pwdIcon"></i></button></div></div>'
      + '<button class="btn-primary btn-full" onclick="submitAuth(\'register\')">' + (STATE.lang === 'fr' ? 'Créer mon compte' : 'Create account') + '</button>';
  }
}

function togglePwd() {
  var input = document.getElementById('authPwd');
  var icon = document.getElementById('pwdIcon');
  if (!input) return;
  input.type = input.type === 'password' ? 'text' : 'password';
  if (icon) icon.className = input.type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
}

function submitAuth(mode) {
  var email = document.getElementById('authEmail') && document.getElementById('authEmail').value.trim();
  var pwd = document.getElementById('authPwd') && document.getElementById('authPwd').value.trim();
  if (!email || !pwd) { showToast(STATE.lang === 'fr' ? 'Veuillez remplir tous les champs' : 'Please fill all fields', 'fa-exclamation-circle', '#ff2d55'); return; }
  var first = (document.getElementById('authFirst') && document.getElementById('authFirst').value) || 'User';
  var last = (document.getElementById('authLast') && document.getElementById('authLast').value) || '';
  STATE.user = { id: 'u_' + Date.now(), name: mode === 'login' ? email.split('@')[0] : (first + ' ' + last).trim(), email: email, type: 'particulier', avatar: 'https://i.pravatar.cc/150?u=' + email, plan: 'Free' };
  saveStorage();
  closeModal('authModal');
  showToast(STATE.lang === 'fr' ? 'Bienvenue ' + STATE.user.name + ' ! 🎉' : 'Welcome ' + STATE.user.name + '! 🎉', 'fa-check-circle', '#2ac97a', 2500);
}

function submitSocial(provider) {
  STATE.user = { id: 'u_social_' + Date.now(), name: provider === 'google' ? 'Utilisateur Google' : 'Utilisateur Facebook', email: 'user@' + provider + '.com', type: 'particulier', avatar: 'https://i.pravatar.cc/150?u=' + provider, plan: 'Free' };
  saveStorage();
  closeModal('authModal');
  showToast((STATE.lang === 'fr' ? 'Connecté via ' : 'Signed in via ') + provider.charAt(0).toUpperCase() + provider.slice(1) + ' ✓', 'fa-check-circle', '#2ac97a', 2500);
}

function initAuthModal() {
  $$('[data-auth]').forEach(function (tab) {
    tab.addEventListener('click', function () {
      $$('[data-auth]').forEach(function (t) { t.classList.remove('active'); });
      tab.classList.add('active');
      renderAuthForm(tab.getAttribute('data-auth'));
    });
  });
  renderAuthForm('login');
}

/* ══════════════════════════════════════════════════════════════════
   27. ALERT BAR
══════════════════════════════════════════════════════════════════ */
function showAlertBar(msg) {
  var bar = $('alertBar'), txt = $('alertTxt');
  if (!bar || !txt) return;
  txt.textContent = msg;
  bar.classList.add('show');
  setTimeout(function () { bar.classList.remove('show'); }, 5000);
}

/* ══════════════════════════════════════════════════════════════════
   28. NAVIGATION PRINCIPALE
══════════════════════════════════════════════════════════════════ */
function initNavigation() {
  // Onglets du feed (Pour vous / Abonnements / Explorer)
  $$('.ntab').forEach(function (tab) {
    tab.addEventListener('click', function () {
      $$('.ntab').forEach(function (t) { t.classList.remove('active'); });
      tab.classList.add('active');
      STATE.currentFeed = tab.getAttribute('data-feed');
      if (STATE.currentFeed === 'explore') {
        openExplorePanel();
      } else {
        window.renderFeed();
      }
    });
  });

  // Bottom nav
  $$('.bn[data-view]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var view = btn.getAttribute('data-view');
      if (view === 'home') {
        if (STATE.currentView === 'home') {
          var feed = $('feed');
          if (feed) feed.scrollTo({ top: 0, behavior: 'smooth' });
          STATE.currentFeed = 'foryou';
          $$('.ntab').forEach(function (t) { t.classList.toggle('active', t.getAttribute('data-feed') === 'foryou'); });
          window.renderFeed();
        }
        STATE.currentView = 'home';
        $$('.bn').forEach(function (b) { b.classList.remove('active'); });
        btn.classList.add('active');
        return;
      }
      STATE.currentView = view;
      $$('.bn').forEach(function (b) { b.classList.remove('active'); });
      btn.classList.add('active');
      if (view === 'explore') window.openExplorePanel();
      else if (view === 'inbox') window.openInboxPanel();
      else if (view === 'me') window.openMePanel();
    });
  });

  // Bouton publier (délégué à app-adapter.js)
  // Ne pas attacher de handler ici pour éviter les doublons

  // Filtres
  var filterBtn = $('filterBtn');
  if (filterBtn) filterBtn.addEventListener('click', function () { openSheet('filterSheet'); });

  // Son
  var soundBtn = $('soundBtn');
  if (soundBtn) soundBtn.addEventListener('click', toggleSound);

  // Langue
  var langBtn = $('langBtn');
  if (langBtn) langBtn.addEventListener('click', toggleLang);

  // Chat IA FAB
  var chatFab = $('chatFab');
  if (chatFab) chatFab.addEventListener('click', function () { openAIChatPanel(null); });

  // Backdrop
  var backdrop = $('backdrop');
  if (backdrop) backdrop.addEventListener('click', closeAllSheets);

  // Fermeture panels via data-panel-close
  $$('[data-panel-close]').forEach(function (btn) {
    btn.addEventListener('click', function () { closePanel(btn.getAttribute('data-panel-close')); });
  });

  // Handles de glissement sheets
  $$('.sdrag').forEach(function (drag) {
    drag.addEventListener('click', function () {
      var id = drag.getAttribute('data-close');
      if (id) closeSheet(id);
    });
  });

  // Boutons fermeture sheets
  $$('[data-close]').forEach(function (btn) {
    btn.addEventListener('click', function () { closeSheet(btn.getAttribute('data-close')); });
  });

  // Boutons fermeture modals
  $$('[data-close-modal]').forEach(function (btn) {
    btn.addEventListener('click', function () { closeModal(btn.getAttribute('data-close-modal')); });
  });

  // Alert bar
  var alertX = $('alertX');
  if (alertX) alertX.addEventListener('click', function () { var bar = $('alertBar'); if (bar) bar.classList.remove('show'); });

  // Fiche bien boutons
  var propContact = $('propContact');
  if (propContact) propContact.addEventListener('click', function () { openContactModal(STATE.currentProperty); });
  var propReserve = $('propReserve');
  if (propReserve) propReserve.addEventListener('click', function () { openReserveModal(STATE.currentProperty); });

  // Submit réservation
  var submitRsv = $('submitRsv');
  if (submitRsv) submitRsv.addEventListener('click', submitReservation);

  // Submit contact
  var submitCon = $('submitContact');
  if (submitCon) submitCon.addEventListener('click', submitContact);

  // Success close
  var suClose = $('suClose');
  if (suClose) suClose.addEventListener('click', function () { closeModal('successModal'); });

  // Commentaires
  var cSend = $('cSend');
  if (cSend) cSend.addEventListener('click', sendComment);
  var cInput = $('cInput');
  if (cInput) cInput.addEventListener('keydown', function (e) { if (e.key === 'Enter') sendComment(); });

  // Emojis
  $$('.eq').forEach(function (eq) {
    eq.addEventListener('click', function () {
      var inp = $('cInput');
      if (inp) { inp.value += eq.textContent; inp.focus(); }
    });
  });

  // Partage
  var shCopyBtn = $('shCopyBtn');
  if (shCopyBtn) shCopyBtn.addEventListener('click', function () {
    var url = $('shUrl') && $('shUrl').textContent;
    if (url && navigator.clipboard) navigator.clipboard.writeText(url);
    showToast(STATE.lang === 'fr' ? 'Lien copié ! 📋' : 'Link copied! 📋', 'fa-check', '#2ac97a', 1800);
    closeSheet('shareSheet');
  });
  var shUrlCopy = $('shUrlCopy');
  if (shUrlCopy) shUrlCopy.addEventListener('click', function () {
    var url = $('shUrl') && $('shUrl').textContent;
    if (url && navigator.clipboard) navigator.clipboard.writeText(url);
    showToast(STATE.lang === 'fr' ? 'Lien copié !' : 'Link copied!', 'fa-check', '#2ac97a', 1800);
  });

  // More sheet
  var mReport = $('mReport');
  if (mReport) mReport.addEventListener('click', function () { closeSheet('moreSheet'); openSheet('reportSheet'); });
  var mSave = $('mSave');
  if (mSave) mSave.addEventListener('click', function () { toggleSave(STATE.currentProperty, null); closeSheet('moreSheet'); });
  var mNotInt = $('mNotInt');
  if (mNotInt) mNotInt.addEventListener('click', function () {
    showToast(STATE.lang === 'fr' ? 'Moins de contenus similaires' : 'Fewer similar contents', 'fa-eye-slash', '#8890b5', 2000);
    closeSheet('moreSheet');
  });
  var mCopyL = $('mCopyL');
  if (mCopyL) mCopyL.addEventListener('click', function () {
    var url = 'immotok.ci/bien/' + STATE.currentProperty;
    if (navigator.clipboard) navigator.clipboard.writeText(url);
    showToast(STATE.lang === 'fr' ? 'Lien copié !' : 'Link copied!', 'fa-check', '#2ac97a', 1800);
    closeSheet('moreSheet');
  });
  var mDownl = $('mDownl');
  if (mDownl) mDownl.addEventListener('click', function () {
    showToast(STATE.lang === 'fr' ? 'Vidéo sauvegardée ✓' : 'Video saved ✓', 'fa-download', '#2ac97a', 1800);
    closeSheet('moreSheet');
  });

  // Report
  $$('.ropt').forEach(function (opt) {
    opt.addEventListener('click', function () {
      $$('.ropt').forEach(function (o) { o.classList.remove('on'); });
      opt.classList.add('on');
    });
  });
  var submitReport = $('submitReport');
  if (submitReport) submitReport.addEventListener('click', function () {
    closeSheet('reportSheet');
    showToast(STATE.lang === 'fr' ? 'Signalement envoyé. Merci !' : 'Report sent. Thank you!', 'fa-flag', '#ffc93c', 2500);
  });

  // Settings
  var meSettingsBtn = $('meSettingsBtn');
  if (meSettingsBtn) meSettingsBtn.addEventListener('click', function () { openPanel('settingsPanel'); renderSettings(); });

  // Chat IA
  var chatSend = $('chatSend');
  if (chatSend) chatSend.addEventListener('click', function () { sendAIChatMessage(); });
  var chatInputEl = $('chatInput');
  if (chatInputEl) chatInputEl.addEventListener('keydown', function (e) { if (e.key === 'Enter') sendAIChatMessage(); });
  var chatMenuBtn = $('chatMenuBtn');
  if (chatMenuBtn) chatMenuBtn.addEventListener('click', function () {
    AI_CHAT_STATE.messages = [];
    AI_CHAT_STATE.step = null;
    AI_CHAT_STATE.context = null;
    initAIChat();
    showToast(STATE.lang === 'fr' ? 'Conversation réinitialisée' : 'Conversation reset', 'fa-redo', '#4285f4', 1800);
  });

  // Resize
  window.addEventListener('resize', function () {
    var vh = window.innerHeight * 0.01;
    document.documentElement.style.setProperty('--vh', vh + 'px');
  });

  // Keyboard ESC
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      if (STATE.activeModal) closeModal(STATE.activeModal);
      else if (STATE.activePanels.length > 0) closeTopPanel();
      else if (STATE.activeSheets.length > 0) closeAllSheets();
    }
  });
}

/* ══════════════════════════════════════════════════════════════════
   29. SWIPE GLOBAL APP-LEVEL (retour arrière)
══════════════════════════════════════════════════════════════════ */
function initGlobalSwipe() {
  var app = document.getElementById('app') || document.body;
  var tStartX = 0, tStartY = 0;
  app.addEventListener('touchstart', function (e) {
    tStartX = e.touches[0].clientX;
    tStartY = e.touches[0].clientY;
  }, { passive: true });
  app.addEventListener('touchend', function (e) {
    var dx = e.changedTouches[0].clientX - tStartX;
    var dy = e.changedTouches[0].clientY - tStartY;
    if (Math.abs(dx) > Math.abs(dy) && dx > 70 && STATE.activePanels.length > 0) {
      closeTopPanel();
    }
  }, { passive: true });
}

/* ══════════════════════════════════════════════════════════════════
   30. INITIALISATION PRINCIPALE
══════════════════════════════════════════════════════════════════ */
document.addEventListener('DOMContentLoaded', function () {
  try {
    var bl = document.getElementById('bootLog');
    if (bl) bl.textContent += '\nDOMContentLoaded: start';

    // Viewport mobile
    var vh = window.innerHeight * 0.01;
    document.documentElement.style.setProperty('--vh', vh + 'px');

    applyTranslations();
    if (bl) bl.textContent += '\n  applyTranslations OK';
    initNavigation();
    if (bl) bl.textContent += '\n  initNavigation OK';
    initFilterSheet();
    initExplorePanel();
    initInboxPanel();
    initAuthModal();
    initGlobalSwipe();
    window.renderFeed();
    updateAlertBadge();

    // Son icon
    var si = $('soundIcon');
    if (si) si.className = STATE.muted ? 'fas fa-volume-mute' : 'fas fa-volume-up';

    if (bl) bl.textContent += '\nDOMContentLoaded: done';

    console.log('%cImmoTok v4.0 — Initialized 🏡🇨🇮', 'color:#ff2d55;font-size:16px;font-weight:bold');
  } catch (e) {
    var bl2 = document.getElementById('bootLog');
    if (bl2) bl2.textContent += '\n\n*** CATCH ***\n' + e.message + '\n' + e.stack;
    console.error('ImmoTok init error:', e);
  }
});

/* ══════════════════════════════════════════════════════════════════
   31. SERVICE WORKER (PWA) — silencieux si absent
══════════════════════════════════════════════════════════════════ */
if ('serviceWorker' in navigator) {
  window.addEventListener('load', function () {
    navigator.serviceWorker.register('/sw.js').catch(function () {
      // SW non disponible en dev, silencieux
    });
  });
}

/* ══════════════════════════════════════════════════════════════════
   32. EXPOSITIONS GLOBALES (pour les onclick inline dans le HTML)
══════════════════════════════════════════════════════════════════ */
window.renderFeed = renderFeed;
window.openProfilePanel = openProfilePanel;
window.openPropertySheet = openPropertySheet;
window.openReserveModal = openReserveModal;
window.openContactModal = openContactModal;
window.openAIChatPanel = openAIChatPanel;
window.openDmPanel = openDmPanel;
window.sendDmRequest = sendDmRequest;
window.sendDmMsg = sendDmMsg;
window.closeSheet = closeSheet;
window.closePanel = closePanel;
window.closeModal = closeModal;
window.closeAllSheets = closeAllSheets;
window.closeTopPanel = closeTopPanel;
window.toggleLang = toggleLang;
window.togglePwd = togglePwd;
window.submitAuth = submitAuth;
window.submitSocial = submitSocial;
window.submitReservation = submitReservation;
window.submitContact = submitContact;
window.sendAIChatMessage = sendAIChatMessage;
window.sendComment = sendComment;
window.doSearch = doSearch;
window.removeSearch = removeSearch;
window.resetFilters = resetFilters;
window.callPhone = callPhone;
window.openWhatsApp = openWhatsApp;
window.sendEmail = sendEmail;
window.showToast = showToast;
window.logout = logout;
window.renderSettings = renderSettings;
window.initAIChat = initAIChat;
window.renderInbox = renderInbox;
window.openMePanel = openMePanel;
window.openInboxPanel = openInboxPanel;
window.openExplorePanel = openExplorePanel;
window.openSaved = openSaved;
window.toggleSound = toggleSound;
window.toggleLike = toggleLike;
window.toggleSave = toggleSave;
window.openCommentSheet = openCommentSheet;
window.openShareSheet = openShareSheet;
window.openMoreSheet = openMoreSheet;
window.openMusicSheet = openMusicSheet;
window.toggleFollowById = toggleFollowById;

/* ══════════════════════════════════════════════════════════════════
   FIN — ImmoTok v4.0 JS Complet (~3600 lignes)
══════════════════════════════════════════════════════════════════ */