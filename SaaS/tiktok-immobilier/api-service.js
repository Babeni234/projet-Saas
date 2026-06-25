/* ImmoTok API Service - v1.0 */
var API = (function () {
  var BASE = (window.API_BASE_URL || (window.location.origin + '/tiktok-immobilier/api'));
  var TOKEN = localStorage.getItem('immotok_token') || null;

  function getHeaders() {
    var h = { 'Content-Type': 'application/json' };
    if (TOKEN) h['Authorization'] = 'Bearer ' + TOKEN;
    return h;
  }

  function request(method, path, data) {
    var opts = {
      method: method,
      headers: { 'Content-Type': 'application/json' }
    };
    if (TOKEN) opts.headers['Authorization'] = 'Bearer ' + TOKEN;
    if (data && method !== 'GET') opts.body = JSON.stringify(data);

    return fetch(BASE + path, opts).then(function (r) {
      if (!r.ok) return r.json().then(function (e) { throw new Error(e.error || 'Erreur serveur'); });
      return r.json();
    });
  }

  return {
    setToken: function (t) { TOKEN = t; localStorage.setItem('immotok_token', t); },
    getToken: function () { return TOKEN; },
    clearToken: function () { TOKEN = null; localStorage.removeItem('immotok_token'); },

    // Auth
    login: function (email, password) {
      return request('POST', '/auth.php?action=login', { email: email, password: password });
    },
    register: function (data) {
      return request('POST', '/auth.php?action=register', data);
    },
    me: function () {
      return request('GET', '/auth.php?action=me');
    },

    // Feed
    getFeed: function (params) {
      var q = '';
      if (params) {
        var parts = [];
        for (var k in params) { if (params[k] !== undefined && params[k] !== null && params[k] !== '') parts.push(k + '=' + encodeURIComponent(params[k])); }
        q = '&' + parts.join('&');
      }
      return request('GET', '/feed.php?feed=' + (params.feed || 'foryou') + '&page=' + (params.page || 1) + '&limit=' + (params.limit || 10) + q);
    },

    // Property
    getProperty: function (id) { return request('GET', '/property.php?id=' + id); },
    createProperty: function (data) { return request('POST', '/property.php', data); },
    deleteProperty: function (id) { return request('DELETE', '/property.php?id=' + id); },

    // Interactions
    like: function (propertyId) { return request('POST', '/like.php?id=' + propertyId); },
    alwaysLike: function (propertyId) { return request('POST', '/like.php?id=' + propertyId + '&action=like'); },
    comment: function (propertyId, text) { return request('POST', '/comment.php?id=' + propertyId, { text: text }); },
    getComments: function (propertyId) { return request('GET', '/comment.php?id=' + propertyId); },
    deleteComment: function (commentId) { return request('DELETE', '/comment.php?comment_id=' + commentId); },
    follow: function (userId) { return request('POST', '/follow.php?id=' + userId); },
    save: function (propertyId) { return request('POST', '/save.php?id=' + propertyId); },

    // Profile
    getProfile: function (userId) { return request('GET', '/profile.php?id=' + userId); },

    // Search
    search: function (query, type) {
      return request('GET', '/search.php?q=' + encodeURIComponent(query) + '&type=' + (type || 'all'));
    },

    // Notifications
    getNotifications: function () { return request('GET', '/notifications.php'); },
    markRead: function (id) { return request('POST', '/notifications.php', { mark_read: id || 'all' }); },

    // Upload
    uploadVideo: function (file, onProgress) {
      return new Promise(function (resolve, reject) {
        var formData = new FormData();
        formData.append('video', file);
        var xhr = new XMLHttpRequest();
        xhr.open('POST', BASE + '/upload.php', true);
        if (TOKEN) xhr.setRequestHeader('Authorization', 'Bearer ' + TOKEN);
        xhr.upload.onprogress = function (e) {
          if (e.lengthComputable && onProgress) onProgress((e.loaded / e.total) * 100);
        };
        xhr.onload = function () {
          if (xhr.status >= 200 && xhr.status < 300) {
            try { resolve(JSON.parse(xhr.responseText)); } catch (e) { reject(new Error('Erreur réponse upload')); }
          } else {
            try { var e = JSON.parse(xhr.responseText); reject(new Error(e.error || 'Erreur upload')); } catch (er) { reject(new Error('Erreur upload')); }
          }
        };
        xhr.onerror = function () { reject(new Error('Erreur réseau')); };
        xhr.send(formData);
      });
    }
  };
})();
