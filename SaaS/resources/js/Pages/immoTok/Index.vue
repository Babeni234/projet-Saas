<template>
  <div class="h-screen w-screen bg-[#07080d] text-white overflow-hidden flex flex-col font-sans select-none relative">
    
    <!-- TOP NAV -->
    <header class="absolute top-0 left-0 w-full z-30 flex items-center justify-between px-4 py-3 bg-gradient-to-b from-black/80 to-transparent">
      <div class="flex items-center gap-2">
        <button @click="openFilterSheet" class="w-10 h-10 rounded-full flex items-center justify-center bg-black/40 hover:bg-black/60 transition active:scale-95 text-xl">
          <i class="fas fa-sliders-h text-white"></i>
        </button>
      </div>
      <nav class="flex items-center gap-4">
        <button 
          v-for="tab in ['foryou', 'subs', 'explore']" 
          :key="tab" 
          @click="activeTab = tab"
          class="text-sm font-semibold tracking-wider transition relative pb-1 border-b-2"
          :class="activeTab === tab ? 'text-white border-red-500 scale-105' : 'text-gray-400 border-transparent hover:text-white'"
        >
          {{ tab === 'foryou' ? 'Pour vous' : (tab === 'subs' ? 'Abonnements' : 'Explorer') }}
        </button>
      </nav>
      <div class="flex items-center gap-3">
        <button @click="toggleMute" class="w-10 h-10 rounded-full flex items-center justify-center bg-black/40 hover:bg-black/60 transition active:scale-95 text-lg">
          <i :class="isMuted ? 'fas fa-volume-mute text-red-500' : 'fas fa-volume-up text-white'"></i>
        </button>
        <button @click="toggleLang" class="px-3 h-8 rounded-full flex items-center justify-center bg-black/40 hover:bg-black/60 border border-white/20 text-xs font-bold transition active:scale-95 text-white">
          {{ currentLang.toUpperCase() }}
        </button>
      </div>
    </header>

    <!-- FEED CONTAINER -->
    <main 
      class="flex-1 w-full h-full relative"
      @wheel="handleWheel"
      @touchstart="handleTouchStart"
      @touchend="handleTouchEnd"
    >
      <div v-if="feed.length === 0" class="absolute inset-0 flex flex-col items-center justify-center gap-4 p-6 text-center">
        <i class="fas fa-video-slash text-5xl text-gray-600 animate-pulse"></i>
        <p class="text-gray-400 text-lg">Aucun bien ne correspond aux filtres de recherche.</p>
        <button @click="resetFilters" class="px-6 py-2 bg-red-600 hover:bg-red-700 active:scale-95 text-white font-semibold rounded-full shadow-lg transition">
          Réinitialiser
        </button>
      </div>

      <div v-else class="w-full h-full relative flex items-center justify-center">
        <!-- Active Slide -->
        <div class="w-full h-full relative flex items-center justify-center bg-black overflow-hidden">
          <!-- Media Player -->
          <div 
            class="w-full h-full flex items-center justify-center relative cursor-pointer"
            @click="handleMediaClick"
          >
            <!-- Image Player -->
            <img 
              v-if="currentItem.media_type === 'image'" 
              :src="currentItem.media_url" 
              class="w-full h-full object-cover md:object-contain select-none pointer-events-none"
              alt="Propriété"
            />

            <!-- Video Player -->
            <video 
              v-else 
              ref="videoRef"
              :src="currentItem.media_url"
              class="w-full h-full object-cover md:object-contain"
              loop
              playsinline
              webkit-playsinline
              :muted="isMuted"
              @timeupdate="updateVideoProgress"
              @loadedmetadata="onVideoMetadata"
            ></video>

            <!-- Play/Pause Overlay Icon -->
            <Transition name="fade-scale">
              <div v-if="!isPlaying" class="absolute inset-0 flex items-center justify-center bg-black/10 select-none pointer-events-none">
                <div class="w-16 h-16 rounded-full bg-black/50 backdrop-blur-sm flex items-center justify-center text-3xl">
                  <i class="fas fa-play text-white ml-1"></i>
                </div>
              </div>
            </Transition>

            <!-- Floating Hearts Container (Double Tap to Like) -->
            <div 
              v-for="heart in floatingHearts" 
              :key="heart.id"
              class="absolute pointer-events-none animate-heart-float z-20 text-red-500 drop-shadow-xl"
              :style="{ left: heart.x + 'px', top: heart.y + 'px' }"
            >
              <i class="fas fa-heart text-6xl"></i>
            </div>
          </div>

          <!-- Bottom & Side Overlays -->
          <div class="absolute inset-x-0 bottom-0 p-4 pt-16 bg-gradient-to-t from-black/90 via-black/40 to-transparent flex flex-col gap-3 z-10 pointer-events-none">
            
            <!-- Timeline (Progress Bar) -->
            <div class="w-full flex items-center gap-3 pointer-events-auto" v-if="currentItem.media_type === 'video'">
              <span class="text-xs font-mono text-gray-300">{{ formatTime(currentTime) }}</span>
              <div 
                class="flex-1 h-1.5 rounded-full bg-white/20 relative cursor-pointer group"
                @mousedown="startDragProgress"
                @touchstart="startDragProgress"
                ref="progressBarRef"
              >
                <div 
                  class="h-full rounded-full bg-red-500 absolute top-0 left-0" 
                  :style="{ width: progressPercentage + '%' }"
                ></div>
                <div 
                  class="w-3.5 h-3.5 rounded-full bg-white absolute top-1/2 -translate-y-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity shadow-lg"
                  :style="{ left: progressPercentage + '%' }"
                ></div>
              </div>
              <span class="text-xs font-mono text-gray-300">{{ formatTime(duration) }}</span>
            </div>

            <!-- Client/Owner Info and Caption Overlay -->
            <div class="flex items-end justify-between gap-4">
              <!-- Text Info -->
              <div class="flex flex-col gap-2 max-w-[80%] pointer-events-auto">
                <div class="flex items-center gap-2">
                  <h2 class="font-bold text-lg tracking-wide text-white drop-shadow-md">@{{ currentItem.company.name }}</h2>
                  <span class="px-2 py-0.5 rounded bg-red-600/90 text-[10px] font-bold uppercase tracking-wider text-white">PRO</span>
                </div>
                <p class="text-sm text-gray-200 line-clamp-2 leading-relaxed drop-shadow-md">
                  {{ currentItem.description }}
                </p>
                <!-- Property Badges -->
                <div class="flex flex-wrap items-center gap-2 mt-1">
                  <span class="px-2.5 py-1 rounded-full bg-black/60 backdrop-blur-sm text-xs font-bold text-red-400 border border-red-500/20">
                    💰 {{ currentItem.property.price_label }}
                  </span>
                  <span v-if="currentItem.property.city" class="px-2.5 py-1 rounded-full bg-black/60 backdrop-blur-sm text-xs font-bold text-blue-400 border border-blue-500/20">
                    📍 {{ currentItem.property.city }}
                  </span>
                  <span v-if="currentItem.property.surface" class="px-2.5 py-1 rounded-full bg-black/60 backdrop-blur-sm text-xs font-bold text-green-400 border border-green-500/20">
                    📐 {{ currentItem.property.surface }} m²
                  </span>
                </div>
              </div>

              <!-- Action Buttons (Right Sidebar) -->
              <div class="flex flex-col items-center gap-4 pb-2 pointer-events-auto z-20">
                <!-- Profile Avatar -->
                <button @click="openProfile(currentItem.company)" class="relative group active:scale-90 transition">
                  <img :src="currentItem.company.logo" class="w-12 h-12 rounded-full border-2 border-white/95 object-cover shadow-xl" alt="avatar"/>
                  <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-5 h-5 rounded-full bg-red-500 flex items-center justify-center border-2 border-[#07080d]">
                    <i class="fas fa-plus text-[9px] text-white"></i>
                  </span>
                </button>

                <!-- Likes -->
                <div class="flex flex-col items-center gap-1">
                  <button 
                    @click="toggleLike(currentItem)" 
                    class="w-11 h-11 rounded-full bg-black/55 backdrop-blur-sm flex items-center justify-center text-xl transition active:scale-75 shadow-lg"
                    :class="currentItem.has_liked ? 'text-red-500' : 'text-white hover:text-red-400'"
                  >
                    <i class="fas fa-heart"></i>
                  </button>
                  <span class="text-xs font-semibold text-gray-200 drop-shadow-md">{{ currentItem.likes_count }}</span>
                </div>

                <!-- Comments -->
                <div class="flex flex-col items-center gap-1">
                  <button 
                    @click="openComments" 
                    class="w-11 h-11 rounded-full bg-black/55 backdrop-blur-sm flex items-center justify-center text-xl text-white hover:text-blue-400 transition active:scale-75 shadow-lg"
                  >
                    <i class="fas fa-comment-dots"></i>
                  </button>
                  <span class="text-xs font-semibold text-gray-200 drop-shadow-md">{{ currentItem.comments_count }}</span>
                </div>

                <!-- Favorites -->
                <div class="flex flex-col items-center gap-1">
                  <button 
                    @click="toggleFavorite(currentItem)" 
                    class="w-11 h-11 rounded-full bg-black/55 backdrop-blur-sm flex items-center justify-center text-xl transition active:scale-75 shadow-lg"
                    :class="currentItem.has_favorited ? 'text-yellow-400' : 'text-white hover:text-yellow-400'"
                  >
                    <i class="fas fa-bookmark"></i>
                  </button>
                  <span class="text-xs font-semibold text-gray-200 drop-shadow-md">{{ currentItem.favorites_count }}</span>
                </div>

                <!-- Details CTA -->
                <div class="flex flex-col items-center gap-1">
                  <button 
                    @click="openDetails" 
                    class="w-11 h-11 rounded-full bg-black/55 backdrop-blur-sm flex items-center justify-center text-xl text-white hover:text-green-400 transition active:scale-75 shadow-lg"
                  >
                    <i class="fas fa-info-circle"></i>
                  </button>
                  <span class="text-[10px] font-semibold text-gray-200 drop-shadow-md">Détails</span>
                </div>

                <!-- Share -->
                <div class="flex flex-col items-center gap-1">
                  <button 
                    @click="openShare" 
                    class="w-11 h-11 rounded-full bg-black/55 backdrop-blur-sm flex items-center justify-center text-xl text-white hover:text-pink-400 transition active:scale-75 shadow-lg"
                  >
                    <i class="fas fa-share-alt"></i>
                  </button>
                  <span class="text-[10px] font-semibold text-gray-200 drop-shadow-md">Partager</span>
                </div>

                <!-- Rotating Audio Disk -->
                <div v-if="currentItem.audio_url" class="relative mt-2 flex items-center justify-center">
                  <div 
                    class="w-11 h-11 rounded-full bg-[#181924] border-4 border-white/10 shadow-2xl flex items-center justify-center overflow-hidden"
                    :class="isPlaying ? 'animate-[spin_4s_linear_infinite]' : ''"
                  >
                    <img :src="currentItem.company.logo" class="w-6 h-6 rounded-full object-cover" alt="audio cover"/>
                  </div>
                  <!-- Rotating notes animation -->
                  <div v-if="isPlaying" class="absolute -top-3 -right-2 flex flex-col gap-0.5 text-xs text-red-500/70 select-none animate-pulse">
                    🎵
                  </div>
                </div>

              </div>
            </div>

          </div>
        </div>
      </div>
    </main>

    <!-- BOTTOM NAV -->
    <nav class="w-full z-30 bg-[#07080d]/95 backdrop-blur-md border-t border-white/5 py-2 px-6 flex items-center justify-between pb-safe">
      <button @click="navigateToHome" class="flex flex-col items-center gap-1 transition active:scale-95 text-red-500">
        <i class="fas fa-home text-lg"></i>
        <span class="text-[10px] font-semibold">Accueil</span>
      </button>
      <button @click="openFilterSheet" class="flex flex-col items-center gap-1 transition active:scale-95 text-gray-400 hover:text-white">
        <i class="fas fa-compass text-lg"></i>
        <span class="text-[10px] font-semibold">Explorer</span>
      </button>
      <div class="px-2">
        <button @click="openCreatePostHint" class="w-12 h-9 rounded-xl bg-gradient-to-r from-[#25F4EE] via-white to-[#FE2C55] p-[2px] transition active:scale-90 shadow-lg shadow-red-500/10">
          <div class="w-full h-full rounded-[10px] bg-[#07080d] flex items-center justify-center text-white">
            <i class="fas fa-plus text-xs"></i>
          </div>
        </button>
      </div>
      <button @click="openInbox" class="flex flex-col items-center gap-1 transition active:scale-95 text-gray-400 hover:text-white relative">
        <i class="fas fa-bell text-lg"></i>
        <span class="text-[10px] font-semibold">Alertes</span>
        <span v-if="unreadCount > 0" class="absolute -top-1 -right-1.5 min-w-[16px] h-4 rounded-full bg-red-500 text-[9px] font-bold flex items-center justify-center px-1 border border-[#07080d]">
          {{ unreadCount }}
        </span>
      </button>
      <button @click="openMe" class="flex flex-col items-center gap-1 transition active:scale-95 text-gray-400 hover:text-white">
        <i class="fas fa-user-circle text-lg"></i>
        <span class="text-[10px] font-semibold">Moi</span>
      </button>
    </nav>

    <!-- CHAT FAB -->
    <button 
      @click="openChat"
      class="absolute bottom-20 right-4 z-20 w-12 h-12 rounded-full bg-gradient-to-r from-red-500 to-pink-500 text-white flex items-center justify-center text-xl shadow-2xl active:scale-90 transition hover:scale-105"
      title="Assistant immobilier"
    >
      <i class="fas fa-comment-dots"></i>
      <span class="absolute top-0 right-0 w-3 h-3 bg-green-500 border-2 border-[#07080d] rounded-full animate-ping"></span>
    </button>

    <!-- ══════════ SHEETS (SLIDE-UP MODALS) ══════════ -->
    <div 
      v-if="activeSheet" 
      class="absolute inset-0 bg-black/60 z-40 transition-opacity duration-300"
      @click="closeActiveSheet"
    ></div>

    <!-- COMMENTS SHEET -->
    <Transition name="slide-up">
      <div v-if="activeSheet === 'comments'" class="absolute inset-x-0 bottom-0 h-[70vh] bg-[#181924] rounded-t-2xl z-50 flex flex-col text-white pb-safe">
        <div class="w-12 h-1.5 bg-white/10 rounded-full mx-auto my-3 cursor-pointer" @click="closeActiveSheet"></div>
        <div class="px-4 pb-3 border-b border-white/5 flex items-center justify-between">
          <h3 class="font-bold text-base">Commentaires <span class="text-xs px-2 py-0.5 bg-white/10 rounded-full text-gray-300 ml-1">{{ comments.length }}</span></h3>
          <button @click="closeActiveSheet" class="text-gray-400 hover:text-white"><i class="fas fa-times text-lg"></i></button>
        </div>
        <!-- Emoji Quick Bar -->
        <div class="px-4 py-2 border-b border-white/5 flex items-center gap-3 overflow-x-auto select-none bg-black/10">
          <button 
            v-for="emoji in ['😍', '🔥', '💎', '👏', '🏡', '📍', '❓']" 
            :key="emoji"
            @click="insertEmoji(emoji)" 
            class="text-xl hover:scale-110 active:scale-95 transition"
          >
            {{ emoji }}
          </button>
        </div>
        <!-- Comments List -->
        <div class="flex-1 overflow-y-auto px-4 py-3 flex flex-col gap-4">
          <div v-if="comments.length === 0" class="flex-1 flex flex-col items-center justify-center text-center text-gray-500 gap-2">
            <i class="fas fa-comments text-4xl"></i>
            <p class="text-sm">Aucun commentaire pour le moment. Soyez le premier !</p>
          </div>
          <div v-for="c in comments" :key="c.id" class="flex flex-col gap-2">
            <div class="flex gap-3">
              <img :src="c.avatar" class="w-9 h-9 rounded-full object-cover bg-gray-800" alt="avatar"/>
              <div class="flex-1">
                <div class="flex items-center justify-between">
                  <h4 class="text-xs font-bold text-gray-300">{{ c.name }}</h4>
                  <span class="text-[10px] text-gray-500">{{ c.created_at }}</span>
                </div>
                <p class="text-sm text-gray-100 mt-1 leading-relaxed">{{ c.text }}</p>
                <!-- Reply Button -->
                <button @click="selectCommentForReply(c)" class="text-xs text-gray-400 hover:text-white mt-1 font-semibold flex items-center gap-1 active:scale-95 transition">
                  <i class="fas fa-reply text-[10px]"></i> Répondre
                </button>
              </div>
            </div>
            <!-- Thread Replies -->
            <div v-if="c.replies && c.replies.length > 0" class="pl-12 flex flex-col gap-3 mt-1">
              <div v-for="reply in c.replies" :key="reply.id" class="flex gap-3">
                <img :src="reply.avatar" class="w-7 h-7 rounded-full object-cover bg-gray-800" alt="avatar"/>
                <div class="flex-1">
                  <div class="flex items-center justify-between">
                    <h4 class="text-xs font-bold text-gray-300">{{ reply.name }}</h4>
                    <span class="text-[10px] text-gray-500">{{ reply.created_at }}</span>
                  </div>
                  <p class="text-xs text-gray-200 mt-0.5 leading-relaxed">{{ reply.text }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Reply Target Hint -->
        <div v-if="replyCommentTarget" class="px-4 py-1.5 bg-black/20 border-t border-white/5 flex items-center justify-between text-xs text-gray-400">
          <span>En réponse à <strong>@{{ replyCommentTarget.name }}</strong></span>
          <button @click="replyCommentTarget = null" class="text-red-400"><i class="fas fa-times"></i></button>
        </div>
        <!-- Comment Composer -->
        <div class="p-3 bg-[#1e202d] border-t border-white/5 flex items-center gap-3">
          <div class="w-8 h-8 rounded-full bg-red-600 flex items-center justify-center font-bold text-sm text-white">
            {{ client ? client.name.charAt(0).toUpperCase() : 'M' }}
          </div>
          <div class="flex-1 bg-black/20 rounded-full px-4 py-2 flex items-center gap-2 border border-white/10">
            <input 
              v-model="commentText" 
              type="text" 
              class="flex-1 bg-transparent text-sm text-white focus:outline-none placeholder-gray-500" 
              placeholder="Ajouter un commentaire..."
              @keyup.enter="sendComment"
            />
            <button @click="sendComment" class="text-red-500 hover:text-red-400 active:scale-90 transition">
              <i class="fas fa-paper-plane"></i>
            </button>
          </div>
        </div>
      </div>
    </Transition>

    <!-- SHARE SHEET -->
    <Transition name="slide-up">
      <div v-if="activeSheet === 'share'" class="absolute inset-x-0 bottom-0 bg-[#181924] rounded-t-2xl z-50 flex flex-col text-white p-4 pb-safe gap-4">
        <div class="w-12 h-1.5 bg-white/10 rounded-full mx-auto cursor-pointer" @click="closeActiveSheet"></div>
        <div class="flex items-center justify-between border-b border-white/5 pb-2">
          <h3 class="font-bold text-base">Partager ce bien</h3>
          <button @click="closeActiveSheet" class="text-gray-400 hover:text-white"><i class="fas fa-times"></i></button>
        </div>
        <div class="grid grid-cols-4 gap-4 py-2">
          <button @click="shareLink('whatsapp')" class="flex flex-col items-center gap-1.5 active:scale-95 transition">
            <div class="w-12 h-12 rounded-full bg-[#25D366] flex items-center justify-center text-white text-xl shadow-lg"><i class="fab fa-whatsapp"></i></div>
            <span class="text-xs text-gray-300">WhatsApp</span>
          </button>
          <button @click="shareLink('telegram')" class="flex flex-col items-center gap-1.5 active:scale-95 transition">
            <div class="w-12 h-12 rounded-full bg-[#0088cc] flex items-center justify-center text-white text-xl shadow-lg"><i class="fab fa-telegram-plane"></i></div>
            <span class="text-xs text-gray-300">Telegram</span>
          </button>
          <button @click="shareLink('facebook')" class="flex flex-col items-center gap-1.5 active:scale-95 transition">
            <div class="w-12 h-12 rounded-full bg-[#1877F2] flex items-center justify-center text-white text-xl shadow-lg"><i class="fab fa-facebook-f"></i></div>
            <span class="text-xs text-gray-300">Facebook</span>
          </button>
          <button @click="copyToClipboard" class="flex flex-col items-center gap-1.5 active:scale-95 transition">
            <div class="w-12 h-12 rounded-full bg-white/10 flex items-center justify-center text-white text-xl shadow-lg"><i class="fas fa-link"></i></div>
            <span class="text-xs text-gray-300">Copier</span>
          </button>
        </div>
        <div class="bg-black/35 rounded-xl p-3 flex items-center justify-between border border-white/5 text-xs text-gray-300">
          <span class="truncate pr-4"><i class="fas fa-link text-red-400 mr-2"></i>{{ getShareUrl() }}</span>
          <button @click="copyToClipboard" class="px-3 py-1 bg-red-600 hover:bg-red-700 font-bold rounded-full text-white transition active:scale-90 shadow-md">
            Copier
          </button>
        </div>
      </div>
    </Transition>

    <!-- PROPERTY DETAILS SHEET -->
    <Transition name="slide-up">
      <div v-if="activeSheet === 'details'" class="absolute inset-x-0 bottom-0 h-[70vh] bg-[#181924] rounded-t-2xl z-50 flex flex-col text-white pb-safe">
        <div class="w-12 h-1.5 bg-white/10 rounded-full mx-auto my-3 cursor-pointer" @click="closeActiveSheet"></div>
        <div class="px-4 pb-3 border-b border-white/5 flex items-center justify-between">
          <h3 class="font-bold text-base">Fiche descriptive du bien</h3>
          <button @click="closeActiveSheet" class="text-gray-400 hover:text-white"><i class="fas fa-times"></i></button>
        </div>
        <div class="flex-1 overflow-y-auto p-4 flex flex-col gap-6">
          <!-- Price and basic info -->
          <div class="bg-black/30 rounded-2xl p-4 border border-white/5 flex flex-col gap-2">
            <span class="text-gray-400 text-xs font-semibold uppercase tracking-wider">Transaction : {{ currentItem.property.transaction === 'vente' ? 'Vente' : 'Location' }}</span>
            <h2 class="text-3xl font-extrabold text-red-500">{{ currentItem.property.price_label }}</h2>
            <div class="flex items-center gap-4 text-sm text-gray-300 mt-2 border-t border-white/5 pt-2">
              <span v-if="currentItem.property.rooms"><i class="fas fa-door-open mr-1.5 text-red-400"></i>{{ currentItem.property.rooms }} {{ currentItem.property.type === 'Immeuble' ? 'Étages' : 'Chambres/Pièces' }}</span>
              <span v-if="currentItem.property.surface"><i class="fas fa-ruler-combined mr-1.5 text-blue-400"></i>{{ currentItem.property.surface }} m²</span>
              <span><i class="fas fa-tag mr-1.5 text-green-400"></i>{{ currentItem.property.type }}</span>
            </div>
          </div>

          <!-- Location & Description -->
          <div class="flex flex-col gap-2">
            <h4 class="font-bold text-sm text-gray-400 uppercase tracking-wider"><i class="fas fa-map-marker-alt mr-2 text-red-500"></i>Localisation</h4>
            <p class="text-base text-white font-semibold">
              {{ currentItem.property.neighborhood ? currentItem.property.neighborhood + ', ' : '' }}{{ currentItem.property.city }}
            </p>
            <div class="mt-4 flex flex-col gap-2">
              <h4 class="font-bold text-sm text-gray-400 uppercase tracking-wider"><i class="fas fa-file-alt mr-2 text-blue-500"></i>Description de l'offre</h4>
              <p class="text-sm text-gray-300 leading-relaxed bg-black/10 rounded-xl p-3 border border-white/5">
                {{ currentItem.description }}
              </p>
            </div>
          </div>

          <!-- Features/Equipments -->
          <div v-if="currentItem.property.features && currentItem.property.features.length > 0" class="flex flex-col gap-2">
            <h4 class="font-bold text-sm text-gray-400 uppercase tracking-wider"><i class="fas fa-concierge-bell mr-2 text-green-500"></i>Équipements & Services</h4>
            <div class="flex flex-wrap gap-2 mt-1">
              <span 
                v-for="feat in currentItem.property.features" 
                :key="feat"
                class="px-3 py-1 rounded-full bg-white/5 border border-white/10 text-xs font-semibold text-gray-200"
              >
                ✓ {{ feat }}
              </span>
            </div>
          </div>
        </div>
        <!-- CTAs -->
        <div class="p-4 bg-[#1e202d] border-t border-white/5 flex gap-3">
          <a :href="'tel:' + currentItem.company.phone" class="flex-1 h-12 bg-white/5 border border-white/10 hover:bg-white/10 rounded-full font-bold flex items-center justify-center gap-2 transition active:scale-95">
            <i class="fas fa-phone"></i> Contacter
          </a>
          <button @click="openReserveSheet" class="flex-1 h-12 bg-red-600 hover:bg-red-700 rounded-full font-bold flex items-center justify-center gap-2 transition active:scale-95 shadow-lg shadow-red-600/20">
            <i class="fas fa-calendar-check"></i> Réserver visite
          </button>
        </div>
      </div>
    </Transition>

    <!-- RESERVE VISIT SHEET -->
    <Transition name="slide-up">
      <div v-if="activeSheet === 'reserve'" class="absolute inset-x-0 bottom-0 h-[80vh] bg-[#181924] rounded-t-2xl z-50 flex flex-col text-white pb-safe">
        <div class="w-12 h-1.5 bg-white/10 rounded-full mx-auto my-3 cursor-pointer" @click="closeActiveSheet"></div>
        <div class="px-4 pb-3 border-b border-white/5 flex items-center justify-between">
          <h3 class="font-bold text-base">Réserver une visite</h3>
          <button @click="closeActiveSheet" class="text-gray-400 hover:text-white"><i class="fas fa-times"></i></button>
        </div>
        <form @submit.prevent="submitReservation" class="flex-1 overflow-y-auto p-4 flex flex-col gap-4">
          <div class="grid grid-cols-2 gap-3">
            <div class="flex flex-col gap-1">
              <label class="text-xs text-gray-400">Prénom *</label>
              <input v-model="rsvForm.firstname" type="text" required class="h-10 bg-black/20 border border-white/10 rounded-lg px-3 text-sm focus:outline-none focus:border-red-500" placeholder="Kouamé"/>
            </div>
            <div class="flex flex-col gap-1">
              <label class="text-xs text-gray-400">Nom *</label>
              <input v-model="rsvForm.lastname" type="text" required class="h-10 bg-black/20 border border-white/10 rounded-lg px-3 text-sm focus:outline-none focus:border-red-500" placeholder="Diallo"/>
            </div>
          </div>
          <div class="flex flex-col gap-1">
            <label class="text-xs text-gray-400">Téléphone *</label>
            <input v-model="rsvForm.phone" type="tel" required class="h-10 bg-black/20 border border-white/10 rounded-lg px-3 text-sm focus:outline-none focus:border-red-500" placeholder="+225 07..."/>
          </div>
          <div class="flex flex-col gap-1">
            <label class="text-xs text-gray-400">Email</label>
            <input v-model="rsvForm.email" type="email" class="h-10 bg-black/20 border border-white/10 rounded-lg px-3 text-sm focus:outline-none focus:border-red-500" placeholder="vous@email.com"/>
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div class="flex flex-col gap-1">
              <label class="text-xs text-gray-400">Date souhaitée *</label>
              <input v-model="rsvForm.date" type="date" required class="h-10 bg-black/20 border border-white/10 rounded-lg px-3 text-sm focus:outline-none focus:border-red-500"/>
            </div>
            <div class="flex flex-col gap-1">
              <label class="text-xs text-gray-400">Créneau horaire</label>
              <select v-model="rsvForm.time" class="h-10 bg-[#07080d] border border-white/10 rounded-lg px-2 text-sm focus:outline-none focus:border-red-500">
                <option value="">Choisir...</option>
                <option>08h00</option><option>09h00</option><option>10h00</option><option>11h00</option>
                <option>14h00</option><option>15h00</option><option>16h00</option><option>17h00</option>
              </select>
            </div>
          </div>
          <div class="flex flex-col gap-1">
            <label class="text-xs text-gray-400">Type de visite</label>
            <select v-model="rsvForm.visittype" class="h-10 bg-[#07080d] border border-white/10 rounded-lg px-2 text-sm focus:outline-none focus:border-red-500">
              <option value="visite">Visite physique</option>
              <option value="virtuelle">Visite virtuelle (vidéo)</option>
              <option value="info">Demande d'informations</option>
            </select>
          </div>
          <div class="flex flex-col gap-1">
            <label class="text-xs text-gray-400">Message (optionnel)</label>
            <textarea v-model="rsvForm.message" rows="3" class="bg-black/20 border border-white/10 rounded-lg p-3 text-sm focus:outline-none focus:border-red-500" placeholder="Budget, questions..."></textarea>
          </div>
          <button type="submit" class="mt-2 h-12 w-full bg-red-600 hover:bg-red-700 rounded-full font-bold shadow-lg shadow-red-600/20 active:scale-95 transition">
            Soumettre la demande
          </button>
        </form>
      </div>
    </Transition>

    <!-- AUTHENTICATION SHEET -->
    <Transition name="slide-up">
      <div v-if="activeSheet === 'auth'" class="absolute inset-x-0 bottom-0 bg-[#181924] rounded-t-2xl z-50 flex flex-col text-white p-4 pb-safe gap-4">
        <div class="w-12 h-1.5 bg-white/10 rounded-full mx-auto cursor-pointer" @click="closeActiveSheet"></div>
        <div class="flex items-center justify-between border-b border-white/5 pb-2">
          <div class="flex items-center gap-1.5">
            <i class="fas fa-play-circle text-red-500 text-xl"></i>
            <span class="font-extrabold text-lg">Immo<span class="text-red-500">Tok</span></span>
          </div>
          <button @click="closeActiveSheet" class="text-gray-400 hover:text-white"><i class="fas fa-times"></i></button>
        </div>
        
        <div class="flex border-b border-white/5">
          <button 
            @click="authTab = 'login'" 
            class="flex-1 py-2 text-sm font-bold border-b-2 transition"
            :class="authTab === 'login' ? 'border-red-500 text-white' : 'border-transparent text-gray-400'"
          >
            Se connecter
          </button>
          <button 
            @click="authTab = 'register'" 
            class="flex-1 py-2 text-sm font-bold border-b-2 transition"
            :class="authTab === 'register' ? 'border-red-500 text-white' : 'border-transparent text-gray-400'"
          >
            Créer un compte
          </button>
        </div>

        <div v-if="authTab === 'login'" class="flex flex-col gap-3">
          <div class="flex flex-col gap-1">
            <label class="text-xs text-gray-400">Email</label>
            <input v-model="authForm.email" type="email" class="h-10 bg-black/20 border border-white/10 rounded-lg px-3 text-sm focus:outline-none focus:border-red-500" placeholder="exemple@mail.com"/>
          </div>
          <div class="flex flex-col gap-1">
            <label class="text-xs text-gray-400">Mot de passe</label>
            <input v-model="authForm.password" @keyup.enter="submitLogin" type="password" class="h-10 bg-black/20 border border-white/10 rounded-lg px-3 text-sm focus:outline-none focus:border-red-500" placeholder="••••••••"/>
          </div>
          <button @click="submitLogin" class="mt-2 h-11 bg-red-600 hover:bg-red-700 rounded-full font-bold active:scale-95 transition">
            Connexion
          </button>
        </div>

        <div v-else class="flex flex-col gap-3 max-h-[50vh] overflow-y-auto">
          <div class="flex flex-col gap-1">
            <label class="text-xs text-gray-400">Nom complet *</label>
            <input v-model="registerForm.name" type="text" class="h-10 bg-black/20 border border-white/10 rounded-lg px-3 text-sm focus:outline-none focus:border-red-500" placeholder="Jean Dupont"/>
          </div>
          <div class="flex flex-col gap-1">
            <label class="text-xs text-gray-400">Email *</label>
            <input v-model="registerForm.email" type="email" class="h-10 bg-black/20 border border-white/10 rounded-lg px-3 text-sm focus:outline-none focus:border-red-500" placeholder="exemple@mail.com"/>
          </div>
          <div class="flex flex-col gap-1">
            <label class="text-xs text-gray-400">Téléphone</label>
            <input v-model="registerForm.phone" type="tel" class="h-10 bg-black/20 border border-white/10 rounded-lg px-3 text-sm focus:outline-none focus:border-red-500" placeholder="+225 07..."/>
          </div>
          <div class="flex flex-col gap-1">
            <label class="text-xs text-gray-400">Mot de passe *</label>
            <input v-model="registerForm.password" type="password" class="h-10 bg-black/20 border border-white/10 rounded-lg px-3 text-sm focus:outline-none focus:border-red-500" placeholder="••••••••"/>
          </div>
          <div class="flex flex-col gap-1">
            <label class="text-xs text-gray-400">Confirmer mot de passe *</label>
            <input v-model="registerForm.password_confirmation" @keyup.enter="submitRegister" type="password" class="h-10 bg-black/20 border border-white/10 rounded-lg px-3 text-sm focus:outline-none focus:border-red-500" placeholder="••••••••"/>
          </div>
          <button @click="submitRegister" class="mt-2 h-11 bg-red-600 hover:bg-red-700 rounded-full font-bold active:scale-95 transition">
            Créer mon compte
          </button>
        </div>
      </div>
    </Transition>

    <!-- FILTER SHEET -->
    <Transition name="slide-up">
      <div v-if="activeSheet === 'filter'" class="absolute inset-x-0 bottom-0 bg-[#181924] rounded-t-2xl z-50 flex flex-col text-white p-4 pb-safe gap-4">
        <div class="w-12 h-1.5 bg-white/10 rounded-full mx-auto cursor-pointer" @click="closeActiveSheet"></div>
        <div class="flex items-center justify-between border-b border-white/5 pb-2">
          <h3 class="font-bold text-base">Filtrer les offres</h3>
          <button @click="closeActiveSheet" class="text-gray-400 hover:text-white"><i class="fas fa-times"></i></button>
        </div>
        <div class="flex flex-col gap-3">
          <div class="flex flex-col gap-1">
            <label class="text-xs text-gray-400">Transaction</label>
            <div class="flex gap-2 mt-1">
              <button 
                v-for="mode in ['all', 'location', 'vente']" 
                :key="mode"
                @click="filterOptions.transaction = mode"
                class="flex-1 py-2 text-xs font-semibold rounded-full border transition"
                :class="filterOptions.transaction === mode ? 'bg-red-600 border-red-600 text-white' : 'bg-transparent border-white/10 text-gray-400'"
              >
                {{ mode === 'all' ? 'Tout' : (mode === 'location' ? 'Location' : 'Vente') }}
              </button>
            </div>
          </div>
          <div class="flex flex-col gap-1 mt-1">
            <label class="text-xs text-gray-400">Type de bien</label>
            <div class="flex flex-wrap gap-2 mt-1">
              <button 
                v-for="type in ['all', 'villa', 'appartement', 'studio', 'bureau', 'terrain']" 
                :key="type"
                @click="filterOptions.type = type"
                class="px-4 py-1.5 text-xs rounded-full border transition"
                :class="filterOptions.type === type ? 'bg-red-600 border-red-600 text-white' : 'bg-transparent border-white/10 text-gray-400'"
              >
                {{ type === 'all' ? 'Tout' : type.toUpperCase() }}
              </button>
            </div>
          </div>
          <div class="flex flex-col gap-1 mt-1">
            <label class="text-xs text-gray-400">Budget Maximum (FCFA) : {{ filterOptions.budget ? formatBudgetLabel(filterOptions.budget) : 'Sans limite' }}</label>
            <input v-model="filterOptions.budget" type="range" min="0" max="10000000" step="50000" class="w-full mt-2 accent-red-600" />
          </div>
          <div class="flex flex-col gap-1 mt-1">
            <label class="text-xs text-gray-400">Ville / Quartier</label>
            <input v-model="filterOptions.city" type="text" class="h-10 bg-black/20 border border-white/10 rounded-lg px-3 text-sm focus:outline-none focus:border-red-500" placeholder="Ex: Cocody, Plateau..."/>
          </div>
          <div class="flex gap-3 mt-4">
            <button @click="resetFilters" class="flex-1 py-3 border border-white/10 hover:bg-white/5 active:scale-95 rounded-full text-sm font-semibold transition">
              Réinitialiser
            </button>
            <button @click="applyFilters" class="flex-1 py-3 bg-red-600 hover:bg-red-700 active:scale-95 rounded-full text-sm font-semibold transition shadow-lg shadow-red-600/20">
              Appliquer
            </button>
          </div>
        </div>
      </div>
    </Transition>

    <!-- CHAT (IMMOBOT ASSISTANT) PANEL -->
    <Transition name="slide-up">
      <div v-if="activeSheet === 'chat'" class="absolute inset-0 bg-[#07080d] z-50 flex flex-col text-white pb-safe">
        <!-- Chat Header -->
        <header class="w-full bg-[#181924] border-b border-white/5 py-3 px-4 flex items-center justify-between">
          <div class="flex items-center gap-3">
            <button @click="closeActiveSheet" class="text-gray-400 hover:text-white mr-1"><i class="fas fa-arrow-left text-lg"></i></button>
            <div class="w-9 h-9 rounded-full bg-red-600 flex items-center justify-center text-white text-base">
              <i class="fas fa-robot"></i>
            </div>
            <div>
              <h3 class="text-sm font-bold text-white">{{ currentItem.company.name }} AI</h3>
              <span class="text-[10px] text-green-400 flex items-center gap-1 font-semibold">
                <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span> Assistant en ligne
              </span>
            </div>
          </div>
          <button @click="closeActiveSheet" class="text-gray-400 hover:text-white"><i class="fas fa-times text-lg"></i></button>
        </header>

        <!-- Messages List -->
        <div ref="chatBoxRef" class="flex-1 overflow-y-auto p-4 flex flex-col gap-4">
          <div class="self-center bg-white/5 rounded-xl px-4 py-2 text-xs text-gray-400 text-center max-w-[80%] my-2 leading-relaxed">
            Bienvenue sur la messagerie de <strong>{{ currentItem.company.name }}</strong>. 
            Notre conseiller IA peut répondre instantanément à vos questions sur nos offres.
          </div>

          <div 
            v-for="msg in chatMessages" 
            :key="msg.id"
            class="max-w-[75%] rounded-2xl px-4 py-2.5 text-sm leading-relaxed"
            :class="msg.sender === 'client' ? 'self-end bg-red-600 text-white rounded-br-none' : 'self-start bg-[#181924] text-gray-200 rounded-bl-none'"
          >
            {{ msg.message }}
            <div class="text-[9px] text-white/40 text-right mt-1 font-mono">
              {{ formatMsgTime(msg.created_at) }}
            </div>
          </div>

          <div v-if="isAiTyping" class="self-start bg-[#181924] text-gray-400 rounded-2xl rounded-bl-none px-4 py-2.5 text-sm flex items-center gap-1">
            <span class="animate-bounce">●</span>
            <span class="animate-bounce delay-100">●</span>
            <span class="animate-bounce delay-200">●</span>
          </div>
        </div>

        <!-- Chat Suggestions -->
        <div class="px-4 py-2 flex gap-2 overflow-x-auto select-none bg-black/25 border-t border-white/5">
          <button 
            v-for="sugg in chatSuggestions" 
            :key="sugg"
            @click="sendSuggestion(sugg)" 
            class="whitespace-nowrap px-3.5 py-1.5 bg-[#181924] hover:bg-[#20212f] border border-white/5 hover:border-white/10 rounded-full text-xs font-semibold text-gray-300 transition"
          >
            {{ sugg }}
          </button>
        </div>

        <!-- Chat Composer -->
        <div class="p-3 bg-[#1e202d] border-t border-white/5 flex items-center gap-3">
          <input 
            v-model="chatInputText" 
            type="text" 
            class="flex-1 h-11 bg-black/20 border border-white/10 rounded-full px-4 text-sm text-white focus:outline-none focus:border-red-500 placeholder-gray-500" 
            placeholder="Écrivez votre message..."
            @keyup.enter="sendChatMessage"
          />
          <button @click="sendChatMessage" class="w-11 h-11 rounded-full bg-red-600 hover:bg-red-700 flex items-center justify-center text-white transition active:scale-90 shadow-lg shadow-red-600/20">
            <i class="fas fa-paper-plane text-sm"></i>
          </button>
        </div>
      </div>
    </Transition>

    <!-- Global Mute Info Banner -->
    <Transition name="fade">
      <div v-if="showMuteInfoBanner" class="absolute top-16 left-1/2 -translate-x-1/2 z-40 bg-black/75 backdrop-blur-sm text-xs font-bold px-4 py-2 rounded-full flex items-center gap-2 border border-white/10">
        <i class="fas" :class="isMuted ? 'fa-volume-mute text-red-500' : 'fa-volume-up text-green-500'"></i>
        <span>{{ isMuted ? 'Audio désactivé' : 'Audio activé en boucle' }}</span>
      </div>
    </Transition>

  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted, nextTick } from 'vue';
import axios from 'axios';

// Component State
const feed = ref([]);
const currentIndex = ref(0);
const activeTab = ref('foryou');
const currentLang = ref('fr');
const client = ref(null);
const activeSheet = ref(null);

// Audio & Video Controls
const isMuted = ref(true);
const isPlaying = ref(true);
const currentTime = ref(0);
const duration = ref(0);
const showMuteInfoBanner = ref(false);
const videoRef = ref(null);
const progressBarRef = ref(null);
const isDraggingProgress = ref(false);

// Comments state
const comments = ref([]);
const commentText = ref(null);
const replyCommentTarget = ref(null);

// Double-tap to like floating hearts
const floatingHearts = ref([]);
let lastTap = 0;

// Visits reservations form
const rsvForm = ref({
  illustration_id: '',
  firstname: '',
  lastname: '',
  phone: '',
  email: '',
  date: '',
  time: '',
  visittype: 'visite',
  message: '',
});

// Auth form
const authTab = ref('login');
const authForm = ref({ email: '', password: '' });
const registerForm = ref({ name: '', email: '', phone: '', password: '', password_confirmation: '' });

// Filter options
const filterOptions = ref({
  transaction: 'all',
  type: 'all',
  budget: 0,
  city: '',
});

// Chatbot messages
const chatMessages = ref([]);
const chatInputText = ref('');
const isAiTyping = ref(false);
const chatBoxRef = ref(null);
const chatSuggestions = ref([
  'Est-ce que ce bien est disponible ?',
  'Quelles sont les conditions de location ?',
  'Puis-je programmer une visite ?',
  'Quel est le loyer avec les charges ?'
]);

// Audio ambient loop manager
let ambientAudio = null;

const currentItem = computed(() => {
  return feed.value[currentIndex.value] || null;
});

const progressPercentage = computed(() => {
  if (duration.value === 0) return 0;
  return (currentTime.value / duration.value) * 100;
});

// Watch currentItem and handle media/audio load transitions
watch(currentIndex, (newIdx) => {
  stopAllMedia();
  nextTick(() => {
    startActiveMedia();
  });
});

watch(currentItem, (newItem) => {
  if (newItem && activeSheet.value === 'chat') {
    loadChatHistory();
  }
});

// Fetch feed from backend
const fetchFeed = async () => {
  try {
    const res = await axios.get('/api/immotok/feed', { params: filterOptions.value });
    feed.value = res.data;
    currentIndex.value = 0;
    nextTick(() => {
      startActiveMedia();
    });
  } catch (e) {
    console.error(e);
  }
};

// Check client auth state
const checkAuth = async () => {
  try {
    const res = await axios.get('/api/immotok/auth/me');
    if (res.data.success) {
      client.value = res.data.client;
    }
  } catch (e) {
    client.value = null;
  }
};

// Play / Pause media when clicked
const handleMediaClick = (e) => {
  // Check for double click
  const now = Date.now();
  const DOUBLE_PRESS_DELAY = 300;
  if (now - lastTap < DOUBLE_PRESS_DELAY) {
    handleDoubleTap(e);
  } else {
    togglePlayPause();
  }
  lastTap = now;
};

const togglePlayPause = () => {
  if (currentItem.value?.media_type === 'video' && videoRef.value) {
    if (isPlaying.value) {
      videoRef.value.pause();
      isPlaying.value = false;
    } else {
      videoRef.value.play();
      isPlaying.value = true;
    }
  } else {
    isPlaying.value = !isPlaying.value;
  }

  // Manage ambient audio playback
  if (ambientAudio) {
    if (isPlaying.value && !isMuted.value) {
      ambientAudio.play().catch(() => {});
    } else {
      ambientAudio.pause();
    }
  }
};

// Mute / Unmute
const toggleMute = () => {
  isMuted.value = !isMuted.value;
  showMuteInfoBanner.value = true;
  setTimeout(() => {
    showMuteInfoBanner.value = false;
  }, 1200);

  if (ambientAudio) {
    if (isMuted.value) {
      ambientAudio.pause();
    } else if (isPlaying.value) {
      ambientAudio.play().catch(() => {});
    }
  }
};

// Start playing current media and sound loop
const startActiveMedia = () => {
  isPlaying.value = true;
  
  if (currentItem.value?.media_type === 'video' && videoRef.value) {
    videoRef.value.currentTime = 0;
    videoRef.value.play().catch(() => {
      isPlaying.value = false;
    });
  }

  // Start sound loop
  if (ambientAudio) {
    ambientAudio.pause();
    ambientAudio = null;
  }

  if (currentItem.value?.audio_url) {
    ambientAudio = new Audio(currentItem.value.audio_url);
    ambientAudio.loop = true;
    if (!isMuted.value && isPlaying.value) {
      ambientAudio.play().catch(() => {});
    }
  }
};

// Stop all video/audio playback
const stopAllMedia = () => {
  if (videoRef.value) {
    videoRef.value.pause();
  }
  if (ambientAudio) {
    ambientAudio.pause();
  }
};

// Double click heart animations
const handleDoubleTap = (e) => {
  const rect = e.currentTarget.getBoundingClientRect();
  const x = e.clientX - rect.left;
  const y = e.clientY - rect.top;

  const id = Date.now();
  floatingHearts.value.push({ id, x, y });

  setTimeout(() => {
    floatingHearts.value = floatingHearts.value.filter(h => h.id !== id);
  }, 800);

  // Trigger Like if not liked yet
  if (currentItem.value && !currentItem.value.has_liked) {
    toggleLike(currentItem.value);
  }
};

// Likes & Favorites Toggles
const toggleLike = async (item) => {
  if (!client.value) {
    activeSheet.value = 'auth';
    return;
  }
  try {
    const res = await axios.post(`/api/immotok/illustrations/${item.id}/like`);
    if (res.data.success) {
      item.has_liked = res.data.liked;
      item.likes_count = res.data.likes_count;
    }
  } catch (e) {
    console.error(e);
  }
};

const toggleFavorite = async (item) => {
  if (!client.value) {
    activeSheet.value = 'auth';
    return;
  }
  try {
    const res = await axios.post(`/api/immotok/illustrations/${item.id}/favorite`);
    if (res.data.success) {
      item.has_favorited = res.data.favorited;
      item.favorites_count = res.data.favorites_count;
    }
  } catch (e) {
    console.error(e);
  }
};

// Video metadata & time handlers
const onVideoMetadata = () => {
  if (videoRef.value) {
    duration.value = videoRef.value.duration;
  }
};

const updateVideoProgress = () => {
  if (videoRef.value && !isDraggingProgress.value) {
    currentTime.value = videoRef.value.currentTime;
  }
};

// Video Timeline Seeking (Click/Drag)
const startDragProgress = (e) => {
  isDraggingProgress.value = true;
  seekVideo(e);

  const onMouseMove = (moveEvent) => {
    seekVideo(moveEvent);
  };

  const onMouseUp = () => {
    isDraggingProgress.value = false;
    if (videoRef.value && isPlaying.value) {
      videoRef.value.play().catch(() => {});
    }
    window.removeEventListener('mousemove', onMouseMove);
    window.removeEventListener('mouseup', onMouseUp);
    window.removeEventListener('touchmove', onMouseMove);
    window.removeEventListener('touchend', onMouseUp);
  };

  window.addEventListener('mousemove', onMouseMove);
  window.addEventListener('mouseup', onMouseUp);
  window.addEventListener('touchmove', onMouseMove, { passive: true });
  window.addEventListener('touchend', onMouseUp);
};

const seekVideo = (e) => {
  if (!progressBarRef.value || !videoRef.value) return;
  const rect = progressBarRef.value.getBoundingClientRect();
  const clientX = e.touches ? e.touches[0].clientX : e.clientX;
  let offset = (clientX - rect.left) / rect.width;
  offset = Math.max(0, Math.min(1, offset));

  currentTime.value = offset * duration.value;
  videoRef.value.currentTime = currentTime.value;
};

// Wheel & touch scroll transitions (Feed swipe)
let touchStartY = 0;
const handleTouchStart = (e) => {
  touchStartY = e.touches[0].clientY;
};

const handleTouchEnd = (e) => {
  const touchEndY = e.changedTouches[0].clientY;
  const diffY = touchStartY - touchEndY;
  const threshold = 60;

  if (diffY > threshold && currentIndex.value < feed.value.length - 1) {
    currentIndex.value++;
  } else if (diffY < -threshold && currentIndex.value > 0) {
    currentIndex.value--;
  }
};

const handleWheel = (e) => {
  // Simple throttle to avoid multiple scrolls
  if (e.deltaY > 30 && currentIndex.value < feed.value.length - 1) {
    currentIndex.value++;
  } else if (e.deltaY < -30 && currentIndex.value > 0) {
    currentIndex.value--;
  }
};

// Comments management
const openComments = async () => {
  if (!currentItem.value) return;
  activeSheet.value = 'comments';
  try {
    const res = await axios.get(`/api/immotok/illustrations/${currentItem.value.id}/comments`);
    comments.value = res.data;
  } catch (e) {
    console.error(e);
  }
};

const selectCommentForReply = (c) => {
  replyCommentTarget.value = c;
};

const insertEmoji = (emoji) => {
  if (!commentText.value) commentText.value = '';
  commentText.value += emoji;
};

const sendComment = async () => {
  if (!client.value) {
    activeSheet.value = 'auth';
    return;
  }
  if (!commentText.value || !commentText.value.trim()) return;

  try {
    const payload = {
      text: commentText.value,
      parent_id: replyCommentTarget.value ? replyCommentTarget.value.id : null,
    };
    const res = await axios.post(`/api/immotok/illustrations/${currentItem.value.id}/comments`, payload);
    if (res.data.success) {
      if (replyCommentTarget.value) {
        // Append reply local
        const parent = comments.value.find(item => item.id === replyCommentTarget.value.id);
        if (parent) {
          if (!parent.replies) parent.replies = [];
          parent.replies.push(res.data.comment);
        }
      } else {
        comments.value.unshift(res.data.comment);
      }
      currentItem.value.comments_count = res.data.comments_count;
      commentText.value = '';
      replyCommentTarget.value = null;
    }
  } catch (e) {
    console.error(e);
  }
};

// Share helpers
const openShare = () => {
  activeSheet.value = 'share';
};

const getShareUrl = () => {
  return window.location.origin + '/immotok?id=' + currentItem.value.id;
};

const shareLink = (platform) => {
  const url = encodeURIComponent(getShareUrl());
  const text = encodeURIComponent(`Découvrez cette superbe offre immobilière de @${currentItem.value.company.name} sur ImmoTok !`);
  let shareHref = '';
  if (platform === 'whatsapp') {
    shareHref = `https://api.whatsapp.com/send?text=${text}%20${url}`;
  } else if (platform === 'telegram') {
    shareHref = `https://t.me/share/url?url=${url}&text=${text}`;
  } else if (platform === 'facebook') {
    shareHref = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
  }
  window.open(shareHref, '_blank');
};

const copyToClipboard = () => {
  navigator.clipboard.writeText(getShareUrl());
  alert("Lien copié dans le presse-papier !");
};

// Details Sheet
const openDetails = () => {
  activeSheet.value = 'details';
};

// Visits
const openReserveSheet = () => {
  rsvForm.value.illustration_id = currentItem.value.id;
  activeSheet.value = 'reserve';
};

const submitReservation = async () => {
  try {
    const res = await axios.post('/api/immotok/reserve-visit', rsvForm.value);
    if (res.data.success) {
      alert(res.data.message);
      activeSheet.value = null;
      // Reset form
      rsvForm.value = {
        illustration_id: '',
        firstname: '',
        lastname: '',
        phone: '',
        email: '',
        date: '',
        time: '',
        visittype: 'visite',
        message: '',
      };
    }
  } catch (e) {
    alert("Une erreur s'est produite lors de la réservation.");
  }
};

// Auth forms submitting
const submitLogin = async () => {
  try {
    const res = await axios.post('/api/immotok/auth/login', authForm.value);
    if (res.data.success) {
      client.value = res.data.client;
      activeSheet.value = null;
    }
  } catch (e) {
    alert(e.response?.data?.message || "Identifiants incorrects.");
  }
};

const submitRegister = async () => {
  try {
    const res = await axios.post('/api/immotok/auth/register', registerForm.value);
    if (res.data.success) {
      client.value = res.data.client;
      activeSheet.value = null;
    }
  } catch (e) {
    const errs = e.response?.data?.errors;
    if (errs) {
      alert(Object.values(errs)[0][0]);
    } else {
      alert("Erreur lors de l'inscription.");
    }
  }
};

// Public profile click
const openProfile = (company) => {
  alert(`Bienvenue sur le profil de ${company.name}.\nTél : ${company.phone}`);
};

// Filters
const openFilterSheet = () => {
  activeSheet.value = 'filter';
};

const applyFilters = () => {
  activeSheet.value = null;
  fetchFeed();
};

const resetFilters = () => {
  filterOptions.value = {
    transaction: 'all',
    type: 'all',
    budget: 0,
    city: '',
  };
  activeSheet.value = null;
  fetchFeed();
};

const formatBudgetLabel = (val) => {
  if (val >= 10000000) return 'Sans limite';
  return new Intl.NumberFormat('fr-FR').format(val) + ' FCFA';
};

// Chat Direct Messaging
const openChat = () => {
  activeSheet.value = 'chat';
  loadChatHistory();
};

const loadChatHistory = async () => {
  if (!client.value || !currentItem.value) return;
  try {
    const res = await axios.get(`/api/immotok/chat/${currentItem.value.company.id}`);
    chatMessages.value = res.data;
    scrollToBottom();
  } catch (e) {
    console.error(e);
  }
};

const sendChatMessage = async () => {
  if (!client.value) {
    activeSheet.value = 'auth';
    return;
  }
  if (!chatInputText.value || !chatInputText.value.trim()) return;

  const clientMsgText = chatInputText.value;
  chatInputText.value = '';

  // Append client message instantly local
  chatMessages.value.push({
    id: Date.now(),
    sender: 'client',
    message: clientMsgText,
    created_at: new Date().toISOString(),
  });
  scrollToBottom();

  isAiTyping.value = true;

  try {
    const res = await axios.post(`/api/immotok/chat/${currentItem.value.company.id}`, {
      message: clientMsgText,
      agency_id: currentItem.value.agency_id || null,
    });
    if (res.data.success) {
      // Reload history to synchronize or append
      if (res.data.ai_message) {
        setTimeout(() => {
          isAiTyping.value = false;
          chatMessages.value.push(res.data.ai_message);
          scrollToBottom();
        }, 800);
      } else {
        isAiTyping.value = false;
      }
    }
  } catch (e) {
    isAiTyping.value = false;
    console.error(e);
  }
};

const sendSuggestion = (sugg) => {
  chatInputText.value = sugg;
  sendChatMessage();
};

const scrollToBottom = () => {
  nextTick(() => {
    if (chatBoxRef.value) {
      chatBoxRef.value.scrollTop = chatBoxRef.value.scrollHeight;
    }
  });
};

// Global handlers
const closeActiveSheet = () => {
  activeSheet.value = null;
  replyCommentTarget.value = null;
};

const navigateToHome = () => {
  resetFilters();
};

const openCreatePostHint = () => {
  alert("Pour publier vos propres vidéos d'illustrations, connectez-vous à votre espace Gestionnaire Entreprise ou Agence, puis rendez-vous dans le menu Illustrations pour importer vos fichiers médias.");
};

const openInbox = () => {
  alert("Vous n'avez pas de nouvelles alertes ou réservations.");
};

const openMe = () => {
  if (client.value) {
    alert(`Espace Client : connecté en tant que ${client.value.name} (${client.value.email}).`);
  } else {
    activeSheet.value = 'auth';
  }
};

// Formatting helpers
const formatTime = (time) => {
  if (isNaN(time)) return '0:00';
  const minutes = Math.floor(time / 60);
  const seconds = Math.floor(time % 60);
  return `${minutes}:${seconds.toString().padStart(2, '0')}`;
};

const formatMsgTime = (dateStr) => {
  if (!dateStr) return '';
  const date = new Date(dateStr);
  return date.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
};

const toggleLang = () => {
  currentLang.value = currentLang.value === 'fr' ? 'en' : 'fr';
};

// LifeCycle hooks
onMounted(() => {
  checkAuth();
  fetchFeed();
});

onUnmounted(() => {
  stopAllMedia();
});
</script>

<style>
/* FADE & SCALE ANIMATION */
.fade-scale-enter-active,
.fade-scale-leave-active {
  transition: all 0.25s ease-out;
}
.fade-scale-enter-from,
.fade-scale-leave-to {
  opacity: 0;
  transform: scale(0.65);
}

/* SLIDE UP ANIMATION */
.slide-up-enter-active,
.slide-up-leave-active {
  transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}
.slide-up-enter-from,
.slide-up-leave-to {
  transform: translateY(100%);
}

/* HEART DOUBLE TAP ANIMATION */
@keyframes heart-float {
  0% {
    transform: scale(0) rotate(-10deg);
    opacity: 0;
  }
  15% {
    transform: scale(1.2) rotate(15deg);
    opacity: 0.9;
  }
  80% {
    transform: scale(1) rotate(-5deg);
    opacity: 0.85;
  }
  100% {
    transform: translateY(-80px) scale(0.6) rotate(10deg);
    opacity: 0;
  }
}

.animate-heart-float {
  animation: heart-float 0.8s ease-out forwards;
}

/* CUSTOM SWIPE FADE */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease-in-out;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
