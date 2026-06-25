/* ImmoTok - API Adapter v2.3
   Session persistante robuste + Design premium iOS */
(function () {
  var _apiPage = 1;

  function parseCount(str) {
    if (!str) return 0;
    var s = String(str).replace(/,/g, '').trim();
    var m = s.match(/^(\d+(?:\.\d+)?)\s*([KkMm])?$/);
    if (!m) return parseInt(s) || 0;
    var n = parseFloat(m[1]);
    if (m[2] === 'K' || m[2] === 'k') n *= 1000;
    else if (m[2] === 'M' || m[2] === 'm') n *= 1000000;
    return Math.round(n);
  }

  // ─── Session persistante ───
  var savedUser = localStorage.getItem('immotok_user');
  var token = API.getToken();

  // Si STATE.user existe mais PAS de token → fausse session, on nettoie
  if (savedUser && token) {
    try { STATE.user = JSON.parse(savedUser); } catch (e) {}
  } else if (savedUser && !token) {
    localStorage.removeItem('immotok_user');
    STATE.user = null;
  }

  // Si token present, verifier sa validite via API.me()
  if (token) {
    API.me().then(function (data) {
      STATE.user = data.user;
      localStorage.setItem('immotok_user', JSON.stringify(data.user));
      // Si l'utilisateur est connecte et que le feed contient encore les donnees statiques,
      // le re-rendre avec l'API tout de suite
      var feed = $('feed');
      if (feed && feed.querySelector('.reel') && !feed.querySelector('[data-vid^="v"]')) return;
      renderFeed();
    }).catch(function (err) {
      // Token invalide/expire → nettoyer la session proprement
      if (err && (err.message === 'Non authentifié' || err.message.indexOf('authentifi') > -1)) {
        API.clearToken();
        STATE.user = null;
        localStorage.removeItem('immotok_user');
      }
      // Si erreur reseau, on garde la session (persistante)
    });
  }

  // ─── Helper: verifier auth avant appel API ───
  function requireAuth() {
    if (!STATE.user || !API.getToken()) {
      openModal('authModal');
      throw new Error('auth_required');
    }
    return true;
  }

  // ─── Logout: reload complet pour etat propre ───
  window.apiLogout = function () {
    API.clearToken();
    STATE.user = null;
    localStorage.removeItem('immotok_user');
    window.location.reload();
  };

  // ─── Nettoyer les reels avant destruction (évite les zombies timers) ───
  function cleanupReels(feed) {
    if (!feed) return;
    feed.querySelectorAll('.reel').forEach(function (r) {
      clearInterval(r._pt);
      r._pt = null;
      var vid = r.querySelector('video');
      if (vid) { vid.pause(); vid.removeAttribute('src'); vid.load(); }
    });
  }

  // ─── renderFeed via API ───
  window.renderFeed = function () {
    var feed = $('feed');
    if (!feed) return Promise.resolve();

    // Nettoyer les timers avant de detruire le DOM
    cleanupReels(feed);

    if (STATE.iObserver) { STATE.iObserver.disconnect(); STATE.iObserver = null; }

    _apiPage = 1;
    feed._isApiFeed = false;

    feed.innerHTML = '<div style="display:flex;align-items:center;justify-content:center;height:100svh"><div class="rspinner" style="display:block;position:static;margin:0"></div></div>';

    var params = { feed: STATE.currentFeed, page: 1, limit: 10 };
    var f = STATE.filters;
    if (f.trans !== 'all') params.trans = f.trans;
    if (f.type !== 'all') params.type = f.type;
    if (f.budget < 500000000) params.budget = f.budget;
    if (f.surface > 0) params.surface = f.surface;
    if (f.city) params.city = f.city;

    return API.getFeed(params).then(function (data) {
      STATE.apiProperties = data.properties || [];
      if (!STATE.apiProperties.length) {
        feed.innerHTML = '<div style="height:100svh;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:14px">'
          + '<i class="fas fa-video-slash" style="font-size:40px;color:var(--t3)"></i>'
          + '<p style="color:var(--t3);font-size:14px;text-align:center;white-space:pre-line">' + t(STATE.currentFeed === 'subs' ? 'empty.subs' : 'empty.feed') + '</p></div>';
        return;
      }
      var html = data.properties.map(function (p, i) { return buildApiReelHTML(p, i); }).join('');
      feed.innerHTML = html;
      setupFeedDelegation();
      feed._isApiFeed = true;
      setupIntersectionObs();
      feed.scrollTop = 0;
      STATE.currentReelIndex = 0;
    }).catch(function (err) {
      feed.innerHTML = '<div style="height:100svh;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:14px;padding:20px">'
        + '<i class="fas fa-exclamation-triangle" style="font-size:40px;color:var(--red)"></i>'
        + '<p style="color:var(--t2);font-size:13px;text-align:center">' + err.message + '</p></div>';
    });
  };

  // ─── API infinite scroll (détection scroll sur #feed) ───
  function setupApiInfiniteScroll() {
    var feed = $('feed');
    if (!feed) return;
    feed.addEventListener('scroll', function () {
      if (!feed._isApiFeed) return;
      if (_scrollTimer) clearTimeout(_scrollTimer);
      _scrollTimer = setTimeout(function () {
        var threshold = feed.scrollHeight - feed.clientHeight - feed.scrollTop;
        if (threshold > 300) return;
        if (feed._loadingMore) return;
        feed._loadingMore = true;
        _apiPage++;
        var params = { feed: STATE.currentFeed, page: _apiPage, limit: 10 };
        var f = STATE.filters;
        if (f.trans !== 'all') params.trans = f.trans;
        if (f.type !== 'all') params.type = f.type;
        if (f.budget < 500000000) params.budget = f.budget;
        if (f.surface > 0) params.surface = f.surface;
        if (f.city) params.city = f.city;
        API.getFeed(params).then(function (data) {
          feed._loadingMore = false;
          if (!data.properties || !data.properties.length) return;
          var totalIdx = feed.querySelectorAll('.reel').length;
          var html = data.properties.map(function (p, i) { return buildApiReelHTML(p, totalIdx + i); }).join('');
          var div = document.createElement('div');
          div.innerHTML = html;
          var frag = document.createDocumentFragment();
          while (div.firstChild) {
            frag.appendChild(div.firstChild);
          }
          feed.appendChild(frag);
          STATE.iObserver && feed.querySelectorAll('.reel').forEach(function (r) { STATE.iObserver.observe(r); });
        }).catch(function () { feed._loadingMore = false; });
      }, 200);
    }, { passive: true });
  }
  var _scrollTimer = null;
  // Attendre que le feed existe (DOM deja cree par immotok.js)
  if ($('feed')) {
    setupApiInfiniteScroll();
  } else {
    var _feedCheck = setInterval(function () {
      if ($('feed')) { clearInterval(_feedCheck); setupApiInfiniteScroll(); }
    }, 100);
  }

  // ─── Build reel HTML from API data ───
  function buildApiReelHTML(prop, idx) {
    var acc = {
      id: prop.user_id,
      name: prop.full_name,
      username: prop.username,
      avatar: prop.avatar || '',
      avatar_url: prop.avatar_url || 'https://i.pravatar.cc/150?u=' + prop.user_id,
      type: prop.user_type || 'particulier',
      verified: prop.verified == 1 || prop.verified === true,
      subscribed: prop.following || false
    };

    var isLiked = prop.liked === true;
    var isSaved = prop.saved === true;
    var isSub = prop.following === true;

    var tags = '';
    if (prop.tags) {
      var tagArr = typeof prop.tags === 'string' ? prop.tags.split(',').map(function (t) { return t.trim(); }) : prop.tags;
      tags = tagArr.map(function (tag) {
        return '<span class="rtag" data-tag="' + tag + '">' + tag + '</span>';
      }).join('');
    }

    var specBadge = acc.type === 'particulier'
      ? '<span style="font-size:9px;background:rgba(168,85,247,.15);border:1px solid rgba(168,85,247,.3);color:#c084fc;padding:2px 7px;border-radius:10px;font-weight:600">PARTICULIER</span>'
      : '';

    var priceLabel = prop.price_label || (prop.price >= 1000000 ? (prop.price / 1000000).toFixed(0) + ' M FCFA' : prop.price.toLocaleString() + ' FCFA');
    var tc = prop.transaction === 'vente' ? 'vente' : 'location';

    return '<div class="reel" data-vid="' + prop.id + '" data-idx="' + idx + '" data-account="' + acc.id + '">'
      + '<video class="reel-video" src="' + (prop.video_url || '') + '" playsinline muted preload="' + (idx < 2 ? 'auto' : 'metadata') + '" loop></video>'
      + '<div class="grad-top"></div><div class="grad-bot"></div>'
      + '<span class="play-ico" id="pi-' + idx + '">▶</span>'
      + '<span class="dbl-heart" id="dh-' + idx + '" style="color:#ff2d55">❤️</span>'
      + '<div class="rspinner"></div>'
      + '<div class="rprog-wrap" id="pw-' + idx + '" data-vid="' + prop.id + '">'
      + '<div class="rprog"><div class="rprog-fill" id="fill-' + idx + '"></div></div>'
      + '<div class="rprog-thumb-preview" id="prev-' + idx + '"></div></div>'
      + '<div id="vtd-' + idx + '" style="position:absolute;bottom:76px;right:12px;z-index:16;font-size:10px;color:rgba(255,255,255,.65);background:rgba(7,8,13,.55);padding:2px 7px;border-radius:5px;pointer-events:none">0:00 / 0:00</div>'
      + '<div class="reel-sidebar">'
      + '<div class="av-wrap" data-account="' + acc.id + '">'
      + '<div class="av-ring' + (isSub ? ' following' : '') + '"><img src="' + acc.avatar_url + '" alt="' + acc.name + '" loading="lazy" onerror="this.style.display=\'none\'"></div>'
      + '<div class="av-pill' + (isSub ? ' following' : '') + '" data-follow="' + acc.id + '">' + (isSub ? '<i class="fas fa-check"></i>' : '<i class="fas fa-plus"></i>') + '</div></div>'
      + '<div class="abt like-btn" data-vid="' + prop.id + '"><div class="aico' + (isLiked ? ' liked' : '') + '"><i class="fas fa-heart"></i></div>'
      + '<span class="albl like-count">' + fmtN((prop.likes_count || 0) + (isLiked ? 1 : 0)) + '</span></div>'
      + '<div class="abt comment-btn" data-vid="' + prop.id + '"><div class="aico"><i class="fas fa-comment-dots"></i></div><span class="albl">' + fmtN(prop.comments_count || 0) + '</span></div>'
      + '<div class="abt save-btn" data-vid="' + prop.id + '"><div class="aico' + (isSaved ? ' saved' : '') + '"><i class="fas fa-bookmark"></i></div><span class="albl save-count">' + fmtN(prop.saves_count || 0) + '</span></div>'
      + '<div class="abt share-btn" data-vid="' + prop.id + '"><div class="aico"><i class="fas fa-share-alt"></i></div><span class="albl">' + fmtN(prop.shares_count || 0) + '</span></div>'
      + '<div class="abt more-btn" data-vid="' + prop.id + '"><div class="aico"><i class="fas fa-ellipsis-h"></i></div><span class="albl">Plus</span></div>'
      + '</div>'
      + '<div class="reel-info">'
      + '<div class="reel-agency" data-account="' + acc.id + '">'
      + '<span class="reel-aname">' + acc.name + '</span>'
      + (acc.verified ? '<span class="vbadge"><i class="fas fa-check"></i></span>' : '')
      + (acc.type === 'particulier' ? specBadge : '') + '</div>'
      + '<div class="reel-title">' + prop.title + '</div>'
      + '<div class="reel-desc" id="desc-' + prop.id + '-' + idx + '">' + (prop.description || '') + '</div>'
      + '<button class="see-more-btn" data-desc="' + prop.id + '-' + idx + '">' + t('seeMore') + '</button>'
      + '<div class="reel-tags">' + tags + '</div>'
      + '<div class="reel-badges">'
      + '<span class="rbadge price"><i class="fas fa-tag"></i> ' + priceLabel + '</span>'
      + '<span class="rbadge type"><i class="fas fa-home"></i> ' + prop.type + '</span>'
      + '<span class="rbadge ' + tc + '"><i class="fas fa-' + (prop.transaction === 'vente' ? 'key' : 'handshake') + '"></i> ' + prop.transaction + '</span>'
      + (prop.surface > 0 ? '<span class="rbadge surf"><i class="fas fa-ruler-combined"></i> ' + prop.surface + 'm²</span>' : '')
      + '</div>'
      + '<button class="discover-btn discover-btn-pulse" data-vid="' + prop.id + '"><i class="fas fa-eye"></i> ' + t('discover') + '</button>'
      + '<div class="music-row" data-vid="' + prop.id + '">'
      + '<div class="mdisc"><div class="mdot"></div></div>'
      + '<div class="mtxt"><span class="mscroll">' + (prop.music_title || 'Son original') + ' — ' + (prop.music_artist || acc.name) + '     </span></div></div>'
      + '</div></div>';
  }

  // ─── Like via API ───
  window.toggleLike = function (vid, el) {
    try { requireAuth(); } catch (e) { return; }
    API.like(vid).then(function (data) {
      var countEl = el ? el.querySelector('.like-count') : null;
      if (data.liked) {
        if (el) { var ico = el.querySelector('.aico'); if (ico) ico.classList.add('liked'); }
        if (countEl) countEl.textContent = fmtN(parseCount(countEl.textContent) + 1);
      } else {
        if (el) { var ico = el.querySelector('.aico'); if (ico) ico.classList.remove('liked'); }
        if (countEl) countEl.textContent = fmtN(Math.max(0, parseCount(countEl.textContent) - 1));
      }
    }).catch(function (err) {
      if (err && err.message === 'Non authentifié') {
        API.clearToken(); STATE.user = null;
        localStorage.removeItem('immotok_user');
        showToast('Session expirée, reconnectez-vous', 'fa-exclamation-circle', '#ff2d55');
        return;
      }
      if (err.message !== 'auth_required') showToast(err.message, 'fa-exclamation-circle', '#ff2d55');
    });
  };

  // ─── alwaysLike (double-tap TikTok: like toujours) ───
  window.alwaysLike = function (vid, el) {
    try { requireAuth(); } catch (e) { return; }
    API.alwaysLike(vid).then(function (data) {
      if (data.liked && el) {
        var ico = el.querySelector('.aico');
        var countEl = el.querySelector('.like-count');
        if (ico && !ico.classList.contains('liked')) ico.classList.add('liked');
        if (countEl) {
          var n = parseCount(countEl.textContent);
          countEl.textContent = fmtN(n + (data.message === 'Déjà aimé' ? 0 : 1));
        }
      }
    }).catch(function (err) {
      if (err.message !== 'auth_required') showToast(err.message, 'fa-exclamation-circle', '#ff2d55');
    });
  };

  // ─── Save via API ───
  window.toggleSave = function (vid, el) {
    try { requireAuth(); } catch (e) { return; }
    API.save(vid).then(function (data) {
      var countEl = el ? el.querySelector('.save-count') : null;
      if (data.saved) {
        if (el) { var ico = el.querySelector('.aico'); if (ico) ico.classList.add('saved'); }
        if (countEl) countEl.textContent = fmtN(parseCount(countEl.textContent) + 1);
      } else {
        if (el) { var ico = el.querySelector('.aico'); if (ico) ico.classList.remove('saved'); }
        if (countEl) countEl.textContent = fmtN(Math.max(0, parseCount(countEl.textContent) - 1));
      }
    }).catch(function (err) {
      if (err && err.message === 'Non authentifié') {
        API.clearToken(); STATE.user = null;
        localStorage.removeItem('immotok_user');
        showToast('Session expirée, reconnectez-vous', 'fa-exclamation-circle', '#ff2d55');
        return;
      }
      if (err.message !== 'auth_required') showToast(err.message, 'fa-exclamation-circle', '#ff2d55');
    });
  };

  // ─── Follow via API ───
  window.toggleFollowById = function (userId) {
    try { requireAuth(); } catch (e) { return; }
    API.follow(userId).then(function (data) {
      var pills = document.querySelectorAll('.av-pill[data-follow="' + userId + '"]');
      var rings = document.querySelectorAll('.av-ring[data-account="' + userId + '"]');
      pills.forEach(function (pill) {
        if (data.following) { pill.classList.add('following'); pill.innerHTML = '<i class="fas fa-check"></i>'; }
        else { pill.classList.remove('following'); pill.innerHTML = '<i class="fas fa-plus"></i>'; }
      });
      rings.forEach(function (ring) {
        if (data.following) ring.classList.add('following');
        else ring.classList.remove('following');
      });
    }).catch(function (err) {
      if (err && err.message === 'Non authentifié') {
        API.clearToken(); STATE.user = null;
        localStorage.removeItem('immotok_user');
        showToast('Session expirée, reconnectez-vous', 'fa-exclamation-circle', '#ff2d55');
        return;
      }
      if (err.message !== 'auth_required') showToast(err.message, 'fa-exclamation-circle', '#ff2d55');
    });
  };

  // ─── Comments via API ───
  window.openCommentSheet = function (vid) {
    try { requireAuth(); } catch (e) { return; }
    STATE.currentProperty = vid;
    var list = $('commentList');
    if (list) list.innerHTML = '<div style="padding:20px;text-align:center;color:var(--t3)"><div class="rspinner" style="display:block;position:static;margin:0 auto 10px;width:24px;height:24px"></div></div>';
    openSheet('commentSheet');

    API.getComments(vid).then(function (comments) {
      if (list) {
        if (!comments || !comments.length) {
          list.innerHTML = '<div style="padding:30px 14px;text-align:center;color:var(--t3);font-size:13px">Aucun commentaire. Soyez le premier !</div>';
        } else {
          list.innerHTML = comments.map(function (c) {
            var av = c.avatar_url || 'https://i.pravatar.cc/150?u=' + c.user_id;
            var badge = c.user_type === 'enterprise' ? '<span class="ci-badge agency">Agence</span>' : '';
            return '<div class="ci-item"><div class="ci-av"><img src="' + av + '" width="34" height="34" style="object-fit:cover" onerror="this.style.display=\'none\'"></div>'
              + '<div class="ci-body"><div class="ci-name">' + c.full_name + badge + '</div>'
              + '<div class="ci-text">' + c.text + '</div>'
              + '<div class="ci-meta"><span class="ci-time">' + (c.created_at ? c.created_at.substr(0, 10) : '') + '</span></div></div></div>';
          }).join('');
        }
      }
      var cc = $('cCount');
      if (cc) cc.textContent = comments ? comments.length : 0;
    }).catch(function () {
      if (list) list.innerHTML = '<div style="padding:30px 14px;text-align:center;color:var(--t3)">Erreur de chargement</div>';
    });
  };

  // ─── Post comment (override) ───
  window.sendComment = function () {
    var input = $('cInput');
    if (!input || !input.value.trim()) return;
    var vid = STATE.currentProperty;
    if (!vid) return;
    var list = $('commentList');

    API.comment(vid, input.value.trim()).then(function () {
      input.value = '';
      return API.getComments(vid);
    }).then(function (comments) {
      if (list && comments) {
        if (!comments.length) {
          list.innerHTML = '<div style="padding:30px 14px;text-align:center;color:var(--t3);font-size:13px">Aucun commentaire. Soyez le premier !</div>';
        } else {
          list.innerHTML = comments.map(function (c) {
            var av = c.avatar_url || 'https://i.pravatar.cc/150?u=' + c.user_id;
            var badge = c.user_type === 'enterprise' ? '<span class="ci-badge agency">Agence</span>' : '';
            return '<div class="ci-item"><div class="ci-av"><img src="' + av + '" width="34" height="34" style="object-fit:cover" onerror="this.style.display=\'none\'"></div>'
              + '<div class="ci-body"><div class="ci-name">' + c.full_name + badge + '</div>'
              + '<div class="ci-text">' + c.text + '</div>'
              + '<div class="ci-meta"><span class="ci-time">' + (c.created_at ? c.created_at.substr(0, 10) : '') + '</span></div></div></div>';
          }).join('');
        }
      }
      var cc = $('cCount');
      if (cc) cc.textContent = comments ? comments.length : 0;
    }).catch(function () {
      if (list) list.innerHTML = '<div style="padding:30px 14px;text-align:center;color:var(--t3)">Erreur d\'envoi</div>';
    });
  };
  // Remplacer bouton + input pour supprimer les anciens listeners
  var csEl = $('cSend');
  if (csEl) {
    var csClone = csEl.cloneNode(true);
    csEl.parentNode.replaceChild(csClone, csEl);
    csClone.addEventListener('click', window.sendComment);
  }
  var ciEl = $('cInput');
  if (ciEl) {
    var ciClone = ciEl.cloneNode(true);
    ciEl.parentNode.replaceChild(ciClone, ciEl);
    ciClone.addEventListener('keydown', function (e) { if (e.key === 'Enter') window.sendComment(); });
  }

  // ─── Auth via API ───
  window.apiLogin = function (email, password) {
    return API.login(email, password).then(function (data) {
      API.setToken(data.token);
      STATE.user = data.user;
      localStorage.setItem('immotok_user', JSON.stringify(data.user));
      return data;
    });
  };

  window.apiRegister = function (data) {
    return API.register(data).then(function (data) {
      API.setToken(data.token);
      STATE.user = data.user;
      localStorage.setItem('immotok_user', JSON.stringify(data.user));
      return data;
    });
  };

  // ─── Override submitAuth: API obligatoire ───
  window.submitAuth = function (mode) {
    var email = document.getElementById('authEmail');
    var pwd = document.getElementById('authPwd');
    if (!email || !pwd || !email.value.trim() || !pwd.value.trim()) {
      showToast('Veuillez remplir tous les champs', 'fa-exclamation-circle', '#ff2d55');
      return;
    }

    var btn = document.querySelector('#authFormWrap .btn-primary');
    if (btn) { btn.disabled = true; btn.textContent = '...'; }

    if (mode === 'login') {
      window.apiLogin(email.value.trim(), pwd.value.trim()).then(function () {
        closeModal('authModal');
        showToast('Bienvenue !', 'fa-check-circle', '#2ac97a', 2500);
        renderFeed();
      }).catch(function (err) {
        showToast(err.message || 'Erreur de connexion', 'fa-exclamation-circle', '#ff2d55');
      }).finally(function () {
        if (btn) { btn.disabled = false; btn.textContent = 'Se connecter'; }
      });
    } else {
      var first = document.getElementById('authFirst');
      var last = document.getElementById('authLast');
      var phone = document.getElementById('authPhone');
      var fullName = ((first ? first.value : '') + ' ' + (last ? last.value : '')).trim() || email.value.split('@')[0];
      var registerData = {
        username: email.value.split('@')[0] + '_' + Date.now(),
        email: email.value.trim(),
        password: pwd.value.trim(),
        full_name: fullName,
        type: 'particulier'
      };
      window.apiRegister(registerData).then(function () {
        closeModal('authModal');
        showToast('Compte créé ! Bienvenue !', 'fa-check-circle', '#2ac97a', 2500);
        renderFeed();
      }).catch(function (err) {
        showToast(err.message || 'Erreur d\'inscription', 'fa-exclamation-circle', '#ff2d55');
      }).finally(function () {
        if (btn) { btn.disabled = false; btn.textContent = "S'inscrire"; }
      });
    }
  };

  // ─── Override submitSocial: désactiver (fausses connexions) ───
  window.submitSocial = function () {
    showToast('Connexion sociale indisponible', 'fa-exclamation-circle', '#ff2d55');
  };

  // ─── Search via API ───
  window.doSearch = function (q) {
    if (!q || !q.trim()) return;
    var resultsDiv = $('sResults');
    var recentDiv = $('sRecent');
    if (resultsDiv) resultsDiv.style.display = 'block';
    if (recentDiv) recentDiv.style.display = 'none';
    if (resultsDiv) resultsDiv.innerHTML = '<div style="padding:20px;text-align:center;color:var(--t3)"><div class="rspinner" style="display:block;position:static;margin:0 auto 10px;width:24px;height:24px"></div></div>';

    API.search(q).then(function (data) {
      if (!resultsDiv) return;
      var html = '';
      if (data.properties && data.properties.length) {
        html += '<div class="ssect">Biens immobiliers</div>';
        html += data.properties.map(function (p) {
          return '<div class="sresult-item" onclick="openPropertySheet(' + p.id + ')">'
            + '<div class="sresult-info"><div class="sresult-title">' + p.title + '</div>'
            + '<div class="sresult-meta">' + (p.city || '') + (p.city && p.price ? ' · ' : '') + (p.price ? p.price.toLocaleString() + ' FCFA' : '') + '</div>'
            + '<div class="sresult-price">' + (p.price_label || '' ) + '</div></div></div>';
        }).join('');
      }
      if (data.users && data.users.length) {
        html += '<div class="ssect">Utilisateurs</div>';
        html += data.users.map(function (u) {
          return '<div class="sresult-item" onclick="openProfilePanel(' + u.id + ')">'
            + '<div class="sresult-info"><div class="sresult-title">' + u.full_name + '</div>'
            + '<div class="sresult-meta">@' + u.username + ' · ' + u.followers_count + ' abonnés</div></div></div>';
        }).join('');
      }
      if (!html) {
        html = '<div style="padding:30px 14px;text-align:center;color:var(--t3)">Aucun résultat pour "' + q + '"</div>';
      }
      resultsDiv.innerHTML = html;
    }).catch(function () {
      if (resultsDiv) resultsDiv.innerHTML = '<div style="padding:20px;text-align:center;color:var(--t3)">Erreur de recherche</div>';
    });
  };

  // ─── Profile panel — Design premium iOS ───
  window.openProfilePanel = function (userId) {
    var content = $('profileContent');
    if (!content) { showToast('Erreur: panneau profil introuvable', 'fa-exclamation-circle', '#ff2d55'); return; }

    content.innerHTML = '<div class="ios-profile-loading"><div class="rspinner" style="display:block;position:static;width:30px;height:30px;border-width:3px;margin:0 auto"></div></div>';
    openPanel('profilePanel');

    API.getProfile(userId).then(function (profile) {
      if (!content) return;
      var isOwner = profile.is_owner || (STATE.user && STATE.user.id == profile.id);
      var isFol = profile.is_following === true;

      var heroBg = profile.cover_color || 'linear-gradient(135deg,#0a0f2e,#1a0530)';
      var avatarUrl = profile.avatar_url || 'https://i.pravatar.cc/150?u=' + profile.id;
      var propCount = profile.properties_count || 0;

      content.innerHTML =
'<div class="ios-profile">' +
  '<div class="ios-pf-hero" style="background:' + heroBg + '">' +
    '<div class="ios-pf-glow"></div>' +
    '<div class="ios-pf-av-wrap">' +
      '<div class="ios-pf-av"><img src="' + avatarUrl + '" onerror="this.style.display=\'none\'"></div>' +
      (profile.verified ? '<div class="ios-pf-badge"><i class="fas fa-check"></i></div>' : '') +
    '</div>' +
    '<div class="ios-pf-name">' + profile.full_name + '</div>' +
    '<div class="ios-pf-handle">@' + profile.username + '</div>' +
    (profile.bio ? '<div class="ios-pf-bio">' + profile.bio + '</div>' : '') +
  '</div>' +
  '<div class="ios-pf-stats">' +
    '<div class="ios-pf-stat"><span class="ios-pf-sn">' + fmtN(propCount) + '</span><span class="ios-pf-sl">Biens</span></div>' +
    '<div class="ios-pf-stat"><span class="ios-pf-sn">' + fmtN(profile.followers_count || 0) + '</span><span class="ios-pf-sl">Abonnés</span></div>' +
    '<div class="ios-pf-stat"><span class="ios-pf-sn">' + fmtN(profile.following_count || 0) + '</span><span class="ios-pf-sl">Abonnements</span></div>' +
    '<div class="ios-pf-stat"><span class="ios-pf-sn">' + fmtN(profile.total_likes || 0) + '</span><span class="ios-pf-sl">J\'aime</span></div>' +
  '</div>' +
  '<div class="ios-pf-actions">' +
    (!isOwner
      ? '<button class="ios-pf-follow' + (isFol ? ' following' : '') + '" onclick="toggleFollowById(' + profile.id + ')">'
        + (isFol ? '<i class="fas fa-check"></i> Abonné' : '<i class="fas fa-plus"></i> S\'abonner')
        + '</button>'
      : '<button class="ios-pf-follow" onclick="apiLogout()"><i class="fas fa-sign-out-alt"></i> Déconnexion</button>'
    ) +
    '<button class="ios-pf-close" onclick="closePanel(\'profilePanel\')"><i class="fas fa-chevron-down"></i></button>' +
  '</div>' +
  '<div class="ios-pf-section"><div class="ios-pf-section-title">Vidéos</div></div>' +
  '<div class="ios-pf-grid" id="pfGrid"><div class="ios-pf-grid-loading"><div class="rspinner" style="display:block;position:static;width:22px;height:22px;border-width:2px;margin:0 auto"></div></div></div>' +
'</div>';

      API.getFeed({ user_id: profile.id, limit: 30 }).then(function (data) {
        var grid = $('pfGrid');
        if (!grid) return;
        if (!data.properties || !data.properties.length) {
          grid.innerHTML = '<div class="ios-pf-empty">Aucun bien publié</div>';
          return;
        }
        grid.innerHTML = data.properties.map(function (p) {
          return '<div class="ios-pf-grid-item" onclick="openPropertySheet(' + p.id + ')">'
            + '<video src="' + (p.video_url || '') + '" preload="metadata"></video>'
            + '<div class="ios-pf-grid-ov"><span class="ios-pf-grid-price">' + (p.price_label || '') + '</span></div></div>';
        }).join('');
      }).catch(function () {});
    }).catch(function (err) {
      if (content) content.innerHTML = '<div style="padding:60px 20px;text-align:center;color:var(--t3);font-size:14px">' + err.message + '</div>';
    });
  };

  // ─── Property sheet via API ───
  window.openPropertySheet = function (vid) {
    var body = $('propSheetBody');
    var title = $('propSheetTitle');
    if (!body) return;
    if (title) title.textContent = 'Chargement...';
    openSheet('propSheet');

    API.getProperty(vid).then(function (p) {
      if (title) title.textContent = p.title;
      if (!body) return;
      var features = '';
      if (p.features) {
        var farr = typeof p.features === 'string' ? p.features.split(',').map(function (f) { return f.trim(); }) : p.features;
        features = '<div class="prop-feats">' + farr.map(function (f) {
          return '<span class="pfeat"><i class="fas fa-check"></i>' + f + '</span>';
        }).join('') + '</div>';
      }
      body.innerHTML = '<div class="prop-body">'
        + '<div class="prop-title2">' + p.title + '</div>'
        + '<div class="prop-price2">' + (p.price_label || p.price.toLocaleString() + ' FCFA') + '</div>'
        + '<div class="prop-loc"><i class="fas fa-map-marker-alt"></i> ' + (p.city || '') + (p.neighborhood ? ' · ' + p.neighborhood : '') + '</div>'
        + '<div class="prop-specs"><div class="pspec"><div class="pspec-val">' + (p.type || '-') + '</div><div class="pspec-lbl">Type</div></div>'
        + '<div class="pspec"><div class="pspec-val">' + (p.surface || '-') + '</div><div class="pspec-lbl">m²</div></div>'
        + '<div class="pspec"><div class="pspec-val">' + (p.rooms || '-') + '</div><div class="pspec-lbl">Pièces</div></div></div>'
        + '<div class="prop-desc2">' + (p.description || '') + '</div>'
        + features + '</div>';

      $('propContact').onclick = function () {
        if (p.phone) window.open('tel:' + p.phone);
        else showToast('Contact non disponible', 'fa-phone', '#ff2d55');
      };
      $('propReserve').onclick = function () {
        openModal('reserveModal');
        var rsvProp = $('rsvProp');
        if (rsvProp) rsvProp.innerHTML = '<strong>' + p.title + '</strong> — ' + (p.price_label || '');
      };
    }).catch(function (err) {
      if (body) body.innerHTML = '<div style="padding:40px;text-align:center;color:var(--t3)">' + err.message + '</div>';
    });
  };

  // ─── Auth form UI ───
  window.setupAuthForm = function () {
    var wrap = $('authFormWrap');
    if (!wrap) return;
    var activeTab = document.querySelector('.atab.active');
    var mode = activeTab ? activeTab.getAttribute('data-auth') : 'login';

    if (mode === 'login') {
      wrap.innerHTML = '<div class="fg"><label>Email ou nom d\'utilisateur</label><input type="text" id="loginEmail" placeholder="email@exemple.com"></div>'
        + '<div class="fg"><label>Mot de passe</label><input type="password" id="loginPass" placeholder="••••••••"></div>'
        + '<button class="btn-primary btn-full" id="loginBtn" style="margin-top:16px"><i class="fas fa-sign-in-alt"></i> Se connecter</button>';
      setTimeout(function () {
        var btn = $('loginBtn');
        if (btn) btn.onclick = function () {
          var email = $('loginEmail') ? $('loginEmail').value : '';
          var pass = $('loginPass') ? $('loginPass').value : '';
          if (!email || !pass) { showToast('Remplissez tous les champs', 'fa-exclamation-circle', '#ff2d55'); return; }
          btn.disabled = true; btn.innerHTML = '<div class="rspinner" style="display:block;position:static;width:18px;height:18px;margin:0 auto;border-width:2px"></div>';
          apiLogin(email, pass).then(function () {
            closeModal('authModal');
            showToast('Connecté !', 'fa-check-circle', '#2ac97a');
            renderFeed();
          }).catch(function (err) {
            showToast(err.message, 'fa-exclamation-circle', '#ff2d55');
            btn.disabled = false; btn.innerHTML = '<i class="fas fa-sign-in-alt"></i> Se connecter';
          });
        };
      }, 50);
    } else {
      wrap.innerHTML = '<div class="frow2"><div class="fg"><label>Prénom</label><input type="text" id="regFirst" placeholder="Kouamé"></div>'
        + '<div class="fg"><label>Nom</label><input type="text" id="regLast" placeholder="Diallo"></div></div>'
        + '<div class="fg"><label>Email *</label><input type="email" id="regEmail" placeholder="vous@email.com"></div>'
        + '<div class="fg"><label>Nom d\'utilisateur *</label><input type="text" id="regUser" placeholder="votre.pseudo"></div>'
        + '<div class="fg"><label>Mot de passe *</label><input type="password" id="regPass" placeholder="••••••••"></div>'
        + '<div class="fg"><label>Type de compte</label><select id="regType"><option value="particulier">Particulier</option><option value="enterprise">Agent / Agence</option></select></div>'
        + '<button class="btn-primary btn-full" id="regBtn" style="margin-top:16px"><i class="fas fa-user-plus"></i> S\'inscrire</button>';
      setTimeout(function () {
        var btn = $('regBtn');
        if (btn) btn.onclick = function () {
          var first = $('regFirst') ? $('regFirst').value : '';
          var last = $('regLast') ? $('regLast').value : '';
          var email = $('regEmail') ? $('regEmail').value : '';
          var user = $('regUser') ? $('regUser').value : '';
          var pass = $('regPass') ? $('regPass').value : '';
          if (!email || !user || !pass) { showToast('Remplissez les champs obligatoires', 'fa-exclamation-circle', '#ff2d55'); return; }
          btn.disabled = true; btn.innerHTML = '<div class="rspinner" style="display:block;position:static;width:18px;height:18px;margin:0 auto;border-width:2px"></div>';
          apiRegister({ username: user, email: email, password: pass, full_name: (first + ' ' + last).trim(), type: ($('regType') ? $('regType').value : 'particulier') }).then(function () {
            closeModal('authModal');
            showToast('Inscription réussie !', 'fa-check-circle', '#2ac97a');
            renderFeed();
          }).catch(function (err) {
            showToast(err.message, 'fa-exclamation-circle', '#ff2d55');
            btn.disabled = false; btn.innerHTML = '<i class="fas fa-user-plus"></i> S\'inscrire';
          });
        };
      }, 50);
    }
  };

  // ─── Notifications via API ───
  window.renderInbox = function (filter) {
    API.getNotifications().then(function (data) {
      STATE.unreadCount = data.unread_count || 0;
      var badge = $('alertBadge');
      if (badge) {
        badge.textContent = STATE.unreadCount;
        badge.style.display = STATE.unreadCount > 0 ? 'block' : 'none';
      }
      var list = $('inboxList');
      if (!list) return;
      var notifs = data.notifications || [];
      if (!notifs.length) {
        list.innerHTML = '<div style="padding:40px 14px;text-align:center;color:var(--t3)"><i class="fas fa-bell-slash" style="font-size:30px;margin-bottom:10px;display:block"></i>Aucune notification</div>';
        return;
      }
      list.innerHTML = notifs.map(function (n) {
        var ico = 'fa-bell';
        if (n.type === 'like') ico = 'fa-heart';
        else if (n.type === 'comment') ico = 'fa-comment';
        else if (n.type === 'follow') ico = 'fa-user-plus';
        else if (n.type === 'save') ico = 'fa-bookmark';
        var color = 'rgba(66,133,244,.15)';
        if (n.type === 'like') color = 'rgba(255,45,85,.12)';
        else if (n.type === 'follow') color = 'rgba(0,223,200,.1)';
        return '<div class="notif-item' + (!n.is_read ? ' unread' : '') + '"><div class="noti-ico" style="background:' + color + '"><i class="fas ' + ico + '" style="color:var(--t)"></i></div>'
          + '<div class="noti-body"><div class="noti-title">' + (n.from_name || 'ImmoTok') + '</div>'
          + '<div class="noti-text">' + (n.text || '') + '</div>'
          + '<div class="noti-time">' + (n.created_at ? n.created_at.substr(0, 16) : '') + '</div></div></div>';
      }).join('');
    });
  };

  // ─── Me panel ───
  window.openMePanel = function () {
    var body = $('mePanelBody');
    if (!body) return;
    if (!STATE.user) { openModal('authModal'); return; }
    renderMePanelSync(body);
    API.me().then(function (data) {
      var u = data.user;
      STATE.user = u;
      localStorage.setItem('immotok_user', JSON.stringify(u));
      renderMePanelSync(body);
    }).catch(function () {});
  };

  function renderMePanelSync(body) {
    var u = STATE.user;
    if (!u) return;
    var avatarUrl = u.avatar_url || 'https://i.pravatar.cc/150?u=' + u.id;
    body.innerHTML =
'<div class="me-hero">' +
  '<div class="me-av"><img src="' + avatarUrl + '" onerror="this.closest(\'.me-av\').innerHTML=\'<div class=me-av-placeholder><i class=\\\'fas fa-user\\\'></i></div>\'"></div>' +
  '<div><div class="me-name">' + (u.full_name || u.name || u.username) + '</div>' +
  '<div class="me-plan"><i class="fas fa-' + (u.type === 'enterprise' ? 'building' : 'user') + '"></i> ' + (u.type || 'Particulier') + '</div></div></div>' +
'<div class="me-stats"><div class="mestat"><div class="mesv">' + fmtN(u.followers_count || 0) + '</div><div class="mesl">Abonnés</div></div>' +
  '<div class="mestat"><div class="mesv">' + fmtN(u.following_count || 0) + '</div><div class="mesl">Abonnements</div></div>' +
  '<div class="mestat"><div class="mesv">' + fmtN(u.properties_count || 0) + '</div><div class="mesl">Biens</div></div></div>' +
'<div class="me-section"><div class="me-section-title">Menu</div>' +
  '<div class="me-menu-item" onclick="openProfilePanel(' + u.id + ')"><div class="me-menu-ico" style="background:rgba(168,85,247,.1)"><i class="fas fa-user" style="color:#c084fc"></i></div><span>Mon profil</span><i class="fas fa-chevron-right marr"></i></div>' +
  '<div class="me-menu-item" onclick="openSaved()"><div class="me-menu-ico" style="background:rgba(255,201,60,.1)"><i class="fas fa-bookmark" style="color:var(--gold)"></i></div><span>Mes favoris</span><i class="fas fa-chevron-right marr"></i></div>' +
  '<div class="me-menu-item" onclick="openPanel(\'settingsPanel\');renderSettings()"><div class="me-menu-ico" style="background:rgba(66,133,244,.1)"><i class="fas fa-cog" style="color:var(--blue)"></i></div><span>Paramètres</span><i class="fas fa-chevron-right marr"></i></div>' +
  '<div class="me-menu-item" onclick="openPanel(\'chatPanel\');initAIChat()"><div class="me-menu-ico" style="background:rgba(255,45,85,.1)"><i class="fas fa-comment-dots" style="color:var(--red)"></i></div><span>Assistant ImmoBot IA</span><i class="fas fa-chevron-right marr"></i></div>' +
  '<div class="me-menu-item" onclick="openUploadFlow()"><div class="me-menu-ico" style="background:rgba(255,201,60,.1)"><i class="fas fa-plus-circle" style="color:var(--gold)"></i></div><span>Publier un bien</span><i class="fas fa-chevron-right marr"></i></div>' +
  '<div class="me-menu-item" onclick="toggleLang()"><div class="me-menu-ico" style="background:rgba(0,223,200,.1)"><i class="fas fa-language" style="color:var(--teal)"></i></div><span>Langue / Language</span><span style="color:var(--t3);font-size:12px">' + STATE.lang.toUpperCase() + '</span></div>' +
  '<div class="me-section-title" style="padding-top:12px">Compte</div>' +
  '<div class="me-menu-item" onclick="apiLogout()"><div class="me-menu-ico" style="background:rgba(255,45,85,.1)"><i class="fas fa-sign-out-alt" style="color:var(--red)"></i></div><span style="color:var(--red)">Déconnexion</span></div>' +
'</div><div style="height:32px"></div>';
    openPanel('mePanel');
  }

  // ─── Upload flow ───
  var uploadInput = document.createElement('input');
  uploadInput.type = 'file';
  uploadInput.accept = 'video/*';
  uploadInput.style.display = 'none';
  document.body.appendChild(uploadInput);

  window.openUploadFlow = function () {
    if (!STATE.user) { openModal('authModal'); return; }
    uploadInput.click();
  };

  var postBtn = document.getElementById('postBtn');
  if (postBtn) {
    postBtn.addEventListener('click', function () { window.openUploadFlow(); });
  }

  uploadInput.addEventListener('change', function () {
    var file = uploadInput.files[0];
    if (!file) return;
    uploadInput.value = '';
    uploadVideoFile(file);
  });

  function uploadVideoFile(file) {
    var overlay = document.createElement('div');
    overlay.style.cssText = 'position:fixed;inset:0;z-index:9999;background:rgba(7,8,13,.95);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:16px;padding:30px';
    overlay.innerHTML = '<i class="fas fa-cloud-upload-alt" style="font-size:48px;color:var(--red)"></i>'
      + '<div style="color:#fff;font-size:18px;font-weight:600">Upload en cours...</div>'
      + '<div style="color:var(--t3);font-size:13px">' + file.name + ' (' + (file.size / (1024 * 1024)).toFixed(1) + ' MB)</div>'
      + '<div style="width:240px;height:4px;background:rgba(255,255,255,.1);border-radius:3px;overflow:hidden"><div id="uploadBar" style="width:0%;height:100%;background:linear-gradient(90deg,#ff2d55,#a855f7);border-radius:3px;transition:width .3s"></div></div>'
      + '<div id="uploadStatus" style="color:var(--t3);font-size:12px;font-family:var(--fm)">0%</div>'
      + '<button onclick="this.closest(\'div[style]\').remove()" style="margin-top:4px;padding:8px 20px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:8px;color:var(--t3);font-size:13px;cursor:pointer">Annuler</button>';
    document.body.appendChild(overlay);

    API.uploadVideo(file, function (pct) {
      var bar = document.getElementById('uploadBar');
      var st = document.getElementById('uploadStatus');
      if (bar) bar.style.width = pct + '%';
      if (st) st.textContent = Math.round(pct) + '%';
    }).then(function (data) {
      overlay.remove();
      showCreatePropertyForm(data.video_url);
    }).catch(function (err) {
      overlay.innerHTML = '<i class="fas fa-exclamation-circle" style="font-size:48px;color:var(--red)"></i>'
        + '<div style="color:#fff;font-size:18px;font-weight:600">Erreur upload</div>'
        + '<div style="color:var(--t3);font-size:13px;text-align:center;max-width:280px">' + (err.message || 'Erreur lors du téléchargement') + '</div>'
        + '<button onclick="this.closest(\'div[style]\').remove()" style="margin-top:10px;padding:10px 30px;background:var(--red);border:none;border-radius:8px;color:#fff;font-size:14px;cursor:pointer">Fermer</button>';
    });
  }

  function showCreatePropertyForm(videoUrl) {
    var overlay = document.createElement('div');
    overlay.style.cssText = 'position:fixed;inset:0;z-index:9999;background:rgba(7,8,13,.95);display:flex;flex-direction:column;align-items:center;justify-content:center;padding:20px;overflow-y:auto';
    overlay.innerHTML = '<div style="width:100%;max-width:400px;background:var(--s2);border-radius:16px;padding:24px;border:1px solid var(--border2)">'
      + '<div style="display:flex;align-items:center;gap:10px;margin-bottom:20px">'
      + '<i class="fas fa-check-circle" style="font-size:24px;color:#2ac97a"></i>'
      + '<div style="font-size:18px;font-weight:700;color:var(--t)">Vidéo uploadée !</div>'
      + '<button onclick="this.closest(\'div[style]\').remove()" style="margin-left:auto;background:none;border:none;color:var(--t3);font-size:20px;cursor:pointer">&times;</button></div>'
      + '<div class="fg"><label>Titre du bien *</label><input type="text" id="propTitle" placeholder="Ex: Villa luxueuse à Cocody"></div>'
      + '<div class="frow2"><div class="fg"><label>Prix (FCFA) *</label><input type="number" id="propPrice" placeholder="25000000"></div>'
      + '<div class="fg"><label>Surface (m²)</label><input type="number" id="propSurface" placeholder="150"></div></div>'
      + '<div class="frow2"><div class="fg"><label>Type</label><select id="propType"><option value="villa">Villa</option><option value="appartement">Appartement</option><option value="studio">Studio</option><option value="terrain">Terrain</option><option value="bureau">Bureau</option><option value="commerce">Commerce</option></select></div>'
      + '<div class="fg"><label>Transaction</label><select id="propTrans"><option value="vente">Vente</option><option value="location">Location</option></select></div></div>'
      + '<div class="fg"><label>Ville</label><input type="text" id="propCity" placeholder="Abidjan"></div>'
      + '<div class="fg"><label>Description</label><textarea id="propDesc" rows="3" style="width:100%;padding:10px;border-radius:8px;border:1px solid rgba(255,255,255,.1);background:rgba(255,255,255,.05);color:var(--t);font-size:13px;resize:none;font-family:inherit" placeholder="Description du bien..."></textarea></div>'
      + '<button class="btn-primary btn-full" id="createPropBtn" style="margin-top:16px"><i class="fas fa-upload"></i> Publier le bien</button>'
      + '<button onclick="this.closest(\'div[style]\').remove()" style="width:100%;margin-top:8px;padding:10px;background:none;border:1px solid rgba(255,255,255,.1);border-radius:8px;color:var(--t3);font-size:13px;cursor:pointer">Annuler</button></div>';
    document.body.appendChild(overlay);

    setTimeout(function () {
      var btn = document.getElementById('createPropBtn');
      if (!btn) return;
      btn.onclick = function () {
        var title = document.getElementById('propTitle') ? document.getElementById('propTitle').value : '';
        var price = document.getElementById('propPrice') ? parseInt(document.getElementById('propPrice').value) : 0;
        var type = document.getElementById('propType') ? document.getElementById('propType').value : 'villa';
        var trans = document.getElementById('propTrans') ? document.getElementById('propTrans').value : 'vente';
        var desc = document.getElementById('propDesc') ? document.getElementById('propDesc').value : '';
        var surf = document.getElementById('propSurface') ? parseInt(document.getElementById('propSurface').value) : 0;
        var city = document.getElementById('propCity') ? document.getElementById('propCity').value : '';
        if (!title || !price) { showToast('Titre et prix requis', 'fa-exclamation-circle', '#ff2d55'); return; }
        btn.disabled = true;
        btn.innerHTML = '<div class="rspinner" style="display:block;position:static;width:18px;height:18px;margin:0 auto;border-width:2px"></div>';

        API.createProperty({
          title: title, price: price, type: type, transaction: trans,
          description: desc, surface: surf, city: city,
          video_url: videoUrl
        }).then(function (res) {
          overlay.remove();
          showToast('Bien publié avec succès !', 'fa-check-circle', '#2ac97a');
          var newId = res && res.id;
          return renderFeed().then(function () {
            if (newId) {
              var feed = $('feed');
              var reel = feed && feed.querySelector('.reel[data-vid="' + newId + '"]');
              if (reel) {
                var top = reel.offsetTop - (feed.clientHeight / 2) + (reel.clientHeight / 2);
                feed.scrollTo({ top: Math.max(0, top), behavior: 'smooth' });
              }
            }
          });
        }).catch(function (err) {
          showToast(err.message || 'Erreur lors de la publication', 'fa-exclamation-circle', '#ff2d55');
          btn.disabled = false;
          btn.innerHTML = '<i class="fas fa-upload"></i> Publier le bien';
        });
      };
    }, 100);
  }

  // ─── More sheet via API ───
  window.openMoreSheet = function (vid) {
    STATE.currentProperty = vid;
    var mSave = document.getElementById('mSave');
    if (mSave) {
      var isSaved = STATE.savedItems && STATE.savedItems.indexOf(vid) > -1;
      mSave.innerHTML = '<i class="fas fa-bookmark"></i><span>' + (isSaved ? 'Retirer des sauvegardes' : 'Sauvegarder') + '</span>';
      mSave.onclick = function () {
        var reel = document.querySelector('.reel[data-vid="' + vid + '"]');
        var btn = reel && reel.querySelector('.save-btn');
        if (window.toggleSave) window.toggleSave(vid, btn);
        closeSheet('moreSheet');
      };
    }
    var mReport = document.getElementById('mReport');
    if (mReport) {
      mReport.onclick = function () { closeSheet('moreSheet'); showToast('Signalé', 'fa-flag', '#ffc93c'); };
    }
    openSheet('moreSheet');
  };

  // ─── Share sheet via API ───
  window.openShareSheet = function (vid) {
    STATE.currentProperty = vid;
    var container = document.getElementById('shareChannels');
    var urlEl = document.getElementById('shUrl');
    if (urlEl) urlEl.textContent = 'immotok.ci/bien/' + vid;

    // Fetch property data for share message
    API.getProperty(vid).then(function (prop) {
      if (!container) return;
      var userName = STATE.user ? STATE.user.full_name : 'Un utilisateur ImmoTok';
      var shareUrl = 'https://immotok.ci/bien/' + vid;
      var shareMsg = '🏠 *' + userName + '* vous invite à voir cette offre immobilière sur ImmoTok !\n\n*' + prop.title + '*\n💰 ' + (prop.price_label || '') + '\n📍 ' + (prop.neighborhood || '') + ', ' + (prop.city || '') + '\n\n🔗 ' + shareUrl + '\n\n_ImmoTok — L\'immobilier en vidéo · immotok.ci_';

      var channels = [
        { icon: 'fab fa-whatsapp', label: 'WhatsApp', color: '#25D366', bg: 'rgba(37,211,102,.1)',
          action: function () { window.open('https://wa.me/?text=' + encodeURIComponent(shareMsg), '_blank'); closeSheet('shareSheet'); showToast('Partagé sur WhatsApp', 'fa-share-alt', '#25D366', 2000); } },
        { icon: 'fas fa-copy', label: 'Copier le lien', color: '#4285f4', bg: 'rgba(66,133,244,.1)',
          action: function () { if (navigator.clipboard) navigator.clipboard.writeText(shareMsg); showToast('Lien copié !', 'fa-check', '#2ac97a', 1800); closeSheet('shareSheet'); } },
        { icon: 'fas fa-sms', label: 'SMS', color: '#2ac97a', bg: 'rgba(42,201,122,.1)',
          action: function () { window.location.href = 'sms:?body=' + encodeURIComponent(shareMsg); closeSheet('shareSheet'); } },
        { icon: 'fas fa-envelope', label: 'Email', color: '#a855f7', bg: 'rgba(168,85,247,.1)',
          action: function () { window.location.href = 'mailto:?subject=' + encodeURIComponent('ImmoTok — ' + prop.title) + '&body=' + encodeURIComponent(shareMsg); closeSheet('shareSheet'); } }
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
    }).catch(function () {
      if (container) container.innerHTML = '<div style="padding:20px;text-align:center;color:var(--t3)">Erreur de chargement</div>';
    });
    openSheet('shareSheet');
  };

  // ─── Mes favoris via API ───
  window.openSaved = function () {
    var body = $('mePanelBody');
    if (!body) return;
    if (!STATE.user) { openModal('authModal'); return; }

    body.innerHTML = '<div style="padding:20px"><div style="display:flex;align-items:center;gap:12px;margin-bottom:16px"><button onclick="openMePanel()" style="background:none;border:none;color:var(--t);font-size:18px;cursor:pointer"><i class="fas fa-chevron-left"></i></button><div style="font-size:18px;font-weight:700;color:var(--t)">Mes favoris</div></div><div id="savedGrid" style="display:grid;grid-template-columns:1fr 1fr;gap:8px"><div class="rspinner" style="display:block;position:static;width:24px;height:24px;border-width:2px;margin:20px auto"></div></div></div>';
    openPanel('mePanel');

    API.getFeed({ saved: true, limit: 50 }).then(function (data) {
      var grid = document.getElementById('savedGrid');
      if (!grid) return;
      if (!data.properties || !data.properties.length) {
        grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;color:var(--t3);padding:30px 14px"><i class="fas fa-bookmark" style="font-size:30px;display:block;margin-bottom:8px;color:var(--t3)"></i>Aucun favori</div>';
        return;
      }
      grid.innerHTML = data.properties.map(function (p) {
        return '<div class="saved-item" onclick="openPropertySheet(' + p.id + ')" style="position:relative;aspect-ratio:9/16;border-radius:8px;overflow:hidden;background:var(--s1);cursor:pointer">'
          + '<video src="' + (p.video_url || '') + '" preload="metadata" style="width:100%;height:100%;object-fit:cover"></video>'
          + '<div style="position:absolute;bottom:0;left:0;right:0;padding:6px;background:linear-gradient(transparent,rgba(0,0,0,.7));font-size:10px;color:#fff">'
          + (p.price_label || '') + '</div></div>';
      }).join('');
    }).catch(function () {
      var grid = document.getElementById('savedGrid');
      if (grid) grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;color:var(--t3);padding:20px">Erreur de chargement</div>';
    });
  };

  // ─── Settings panel — Design premium iOS ───
  window.renderSettings = function () {
    var body = $('settingsBody');
    if (!body) return;

    var muted = STATE.muted;
    var lang = STATE.lang;
    var currentFeed = STATE.currentFeed;

    body.innerHTML =
'<div class="ios-settings">' +
  '<div class="ios-sg">' +
    '<div class="ios-sgh">GÉNÉRAL</div>' +
    '<div class="ios-si">' +
      '<div class="ios-si-label"><i class="fas fa-volume-up ios-si-ico"></i><span>Son</span></div>' +
      '<div class="tog' + (muted ? '' : ' on') + '" id="togSound" onclick="toggleSound();var t=this;t.classList.toggle(\'on\')"></div>' +
    '</div>' +
    '<div class="ios-si">' +
      '<div class="ios-si-label"><i class="fas fa-language ios-si-ico"></i><span>Langue</span></div>' +
      '<select class="ios-si-select" onchange="STATE.lang=this.value;localStorage.setItem(\'immotok_lang\',this.value);renderFeed();applyTranslations();">' +
        '<option value="fr"' + (lang === 'fr' ? ' selected' : '') + '>Français</option>' +
        '<option value="en"' + (lang === 'en' ? ' selected' : '') + '>English</option>' +
      '</select>' +
    '</div>' +
    '<div class="ios-si">' +
      '<div class="ios-si-label"><i class="fas fa-rss ios-si-ico"></i><span>Fil d\'actualité</span></div>' +
      '<select class="ios-si-select" onchange="STATE.currentFeed=this.value;renderFeed()">' +
        '<option value="foryou"' + (currentFeed === 'foryou' ? ' selected' : '') + '>Pour toi</option>' +
        '<option value="subs"' + (currentFeed === 'subs' ? ' selected' : '') + '>Abonnements</option>' +
      '</select>' +
    '</div>' +
  '</div>' +
  '<div class="ios-sg">' +
    '<div class="ios-sgh">COMPTE</div>' +
    '<div class="ios-si" onclick="openPanel(\'mePanel\')"><div class="ios-si-label"><i class="fas fa-user ios-si-ico"></i><span>Mon compte</span></div><i class="fas fa-chevron-right" style="color:var(--t3);font-size:12px"></i></div>' +
    '<div class="ios-si" onclick="openSaved()"><div class="ios-si-label"><i class="fas fa-bookmark ios-si-ico"></i><span>Mes favoris</span></div><i class="fas fa-chevron-right" style="color:var(--t3);font-size:12px"></i></div>' +
  '</div>' +
  '<div class="ios-sg">' +
    '<div class="ios-sgh">À PROPOS</div>' +
    '<div class="ios-si"><div class="ios-si-label"><i class="fas fa-info-circle ios-si-ico"></i><span>Version</span></div><span style="color:var(--t3);font-size:13px">2.2.0</span></div>' +
    '<div class="ios-si"><div class="ios-si-label"><i class="fas fa-shield-alt ios-si-ico"></i><span>Conditions d\'utilisation</span></div></div>' +
    '<div class="ios-si"><div class="ios-si-label"><i class="fas fa-lock ios-si-ico"></i><span>Confidentialité</span></div></div>' +
  '</div>' +
  (STATE.user ? '<div class="ios-sg"><div class="ios-si ios-si-danger" onclick="apiLogout()"><div class="ios-si-label"><i class="fas fa-sign-out-alt ios-si-ico" style="color:var(--red)"></i><span style="color:var(--red)">Déconnexion</span></div></div></div>' : '') +
'</div>';
  };

  console.log('ImmoTok API Adapter v2.3 loaded');
})();
