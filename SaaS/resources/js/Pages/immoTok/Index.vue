<template>
  <div class="h-screen w-screen bg-[#030712] text-white overflow-hidden flex flex-col font-sans select-none relative">
    
    <!-- SPLASH SCREEN (Premium loading) -->
    <Transition name="fade">
      <div v-if="showSplash" class="absolute inset-0 z-[9999] bg-[#030712] flex flex-col items-center justify-center">
        <div class="flex flex-col items-center gap-6">
          <!-- Logo ImmoTok -->
          <div class="relative flex items-center gap-2">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-600 p-[3px] shadow-2xl shadow-blue-500/30 animate-pulse">
              <div class="w-full h-full rounded-[13px] bg-[#030712] flex items-center justify-center">
                <span class="text-4xl font-black text-white">I</span>
              </div>
            </div>
            <div class="flex flex-col">
              <span class="text-4xl font-black tracking-tight">Immo<span class="text-blue-500">Tok</span></span>
              <span class="text-[10px] text-gray-500 font-semibold tracking-widest uppercase">Immobilier &amp; Découverte</span>
            </div>
          </div>
          <!-- Progress bar style YouTube -->
          <div class="w-48 h-1 rounded-full bg-white/10 overflow-hidden">
            <div 
              class="h-full rounded-full bg-gradient-to-r from-blue-600 to-blue-400 transition-all duration-200 ease-out"
              :style="{ width: splashProgress + '%' }"
            ></div>
          </div>
          <span class="text-[11px] text-gray-600 font-mono">{{ Math.round(splashProgress) }}%</span>
        </div>
      </div>
    </Transition>

    <!-- TOP NAV -->
    <header class="absolute top-0 left-0 w-full z-30 flex items-center justify-between px-2 sm:px-4 py-2.5 sm:py-3 bg-gradient-to-b from-black/80 to-transparent">
      <div class="flex items-center gap-1 sm:gap-2">
        <button @click="openFilterSheet" class="w-9 h-9 sm:w-10 sm:h-10 rounded-full flex items-center justify-center bg-black/40 hover:bg-black/60 transition active:scale-95 text-base sm:text-xl">
          <i class="fas fa-sliders-h text-white"></i>
        </button>
      </div>
      <nav class="flex items-center gap-2 sm:gap-4">
        <button 
          v-for="tab in ['foryou', 'subs', 'explore']" 
          :key="tab" 
          @click="activeTab = tab"
          class="text-[11px] sm:text-sm font-semibold tracking-wider transition relative pb-1 border-b-2 whitespace-nowrap"
          :class="activeTab === tab ? 'text-white border-blue-500 scale-105' : 'text-gray-400 border-transparent hover:text-white'"
        >
          {{ tab === 'foryou' ? t('foryou') : (tab === 'subs' ? t('subs') : t('explore')) }}
        </button>
      </nav>
      <div class="flex items-center gap-2 sm:gap-3">
        <button @click="toggleMute" class="w-9 h-9 sm:w-10 sm:h-10 rounded-full flex items-center justify-center bg-black/40 hover:bg-black/60 transition active:scale-95 text-sm sm:text-lg">
          <i :class="isMuted ? 'fas fa-volume-mute text-blue-500' : 'fas fa-volume-up text-white'"></i>
        </button>
        <button @click="toggleLang" class="px-2 sm:px-3 h-7 sm:h-8 rounded-full flex items-center justify-center bg-black/40 hover:bg-black/60 border border-white/20 text-[10px] sm:text-xs font-bold transition active:scale-95 text-white">
          {{ currentLang.toUpperCase() }}
        </button>
      </div>
    </header>

    <!-- FEED CONTAINER -->
    <main 
      class="flex-1 w-full h-full relative"
      @wheel="handleWheel"
      @touchstart="handleTouchStart"
      @touchmove="handleTouchMove"
      @touchend="handleTouchEnd"
    >
      <!-- Pull to Refresh Spinner -->
      <div 
        v-if="isPullingRefresh || isRefreshing" 
        class="absolute top-16 left-0 right-0 z-40 flex justify-center pointer-events-none transition-all duration-150"
        :style="{ transform: `translateY(${pullDisplacement}px)` }"
      >
        <div class="px-4 py-2 rounded-full bg-black/80 border border-white/10 flex items-center gap-2 shadow-2xl text-xs font-bold text-white">
          <i class="fas fa-spinner" :class="isRefreshing ? 'animate-spin' : ''" :style="{ transform: `rotate(${pullDisplacement * 3.6}deg)` }"></i>
          <span>{{ isRefreshing ? t('refreshing') : t('pullToRefresh') }}</span>
        </div>
      </div>

      <!-- EXPLORE PAGE -->
      <div v-if="activeTab === 'explore'" class="w-full h-full bg-[#030712] overflow-y-auto px-3 sm:px-4 pt-16 sm:pt-20 pb-20 flex flex-col gap-4 sm:gap-5">
        <!-- Search Bar -->
        <div class="flex items-center gap-2 sm:gap-3">
          <div class="flex-1 bg-[#181924] rounded-full px-3 sm:px-4 py-2 sm:py-2.5 flex items-center gap-2 border border-white/10">
            <i class="fas fa-search text-gray-400 text-sm sm:text-base"></i>
            <input 
              v-model="searchQuery" 
              type="text" 
              class="flex-1 bg-transparent text-xs sm:text-sm text-white focus:outline-none placeholder-gray-500" 
              :placeholder="t('searchPlaceholder')"
              @keyup.enter="handleExploreSearch"
            />
            <button v-if="searchQuery" @click="searchQuery = ''; handleExploreSearch()" class="text-gray-400"><i class="fas fa-times-circle"></i></button>
          </div>
          <button @click="handleExploreSearch" class="px-3 sm:px-4 py-2 sm:py-2.5 bg-blue-600 hover:bg-blue-700 font-bold rounded-full text-xs sm:text-sm active:scale-95 transition">{{ t('searchBtn') }}</button>
        </div>

        <!-- Suggestions/Trending tags -->
        <div class="flex flex-col gap-1.5 sm:gap-2">
          <h4 class="text-[10px] sm:text-xs font-bold text-gray-400 uppercase tracking-wider">{{ t('popularSearches') }}</h4>
          <div class="flex gap-1.5 sm:gap-2 overflow-x-auto pb-1 select-none">
            <button 
              v-for="tag in ['Cocody', 'Studio', 'Appartement', 'Loyer < 500k', 'Plateau']"
              :key="tag"
              @click="selectTrendingTag(tag)"
              class="px-2.5 sm:px-3.5 py-1 sm:py-1.5 bg-[#181924] border border-white/5 hover:bg-[#20212f] rounded-full text-[10px] sm:text-xs font-semibold text-gray-300 transition whitespace-nowrap"
            >
              🔥 {{ tag }}
            </button>
          </div>
        </div>

        <!-- Explore Grid -->
        <div v-if="exploreLoading" class="flex-1 flex items-center justify-center py-20">
          <i class="fas fa-spinner animate-spin text-3xl text-blue-500"></i>
        </div>
        <div v-else-if="exploreResults.length === 0" class="flex-1 flex flex-col items-center justify-center text-center text-gray-500 gap-2 py-16 sm:py-20 px-4">
          <i class="fas fa-search-minus text-3xl sm:text-4xl"></i>
          <p class="text-xs sm:text-sm">{{ t('noResults') }}</p>
        </div>
        <div v-else class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2 sm:gap-3">
          <div 
            v-for="(item, idx) in exploreResults" 
            :key="item.id"
            @click="playExploreItem(idx)"
            class="bg-[#181924] rounded-xl overflow-hidden border border-white/5 shadow-lg active:scale-[0.98] transition cursor-pointer flex flex-col"
          >
            <!-- Card Thumbnail -->
            <div class="aspect-[3/4] bg-black relative flex items-center justify-center overflow-hidden">
              <img v-if="item.media_type === 'image'" :src="item.media_url" class="w-full h-full object-contain" />
              <video v-else :src="item.media_url" class="w-full h-full object-contain" muted></video>
              <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
              
              <!-- Price Badge -->
              <div class="absolute bottom-2 left-2 px-2 py-0.5 rounded bg-emerald-600/90 text-[10px] font-bold text-white shadow-md">
                {{ item.property.price_label.split(' ')[0] }} F
              </div>
              <!-- Play Icon for Video -->
              <div v-if="item.media_type === 'video'" class="absolute top-2 right-2 w-6 h-6 rounded-full bg-black/40 flex items-center justify-center text-[10px] text-white">
                <i class="fas fa-play"></i>
              </div>
            </div>

            <!-- Card info -->
            <div class="p-2 flex flex-col gap-1.5">
              <p class="text-[11px] sm:text-xs text-gray-200 line-clamp-2 font-medium leading-relaxed">{{ item.description }}</p>
              <div class="flex items-center justify-between border-t border-white/5 pt-2">
                <div class="flex items-center gap-1.5 truncate max-w-[65%]">
                  <img :src="item.company.logo" class="w-4 h-4 sm:w-4.5 sm:h-4.5 rounded-full object-cover" />
                  <span class="text-[9px] sm:text-[10px] font-bold text-gray-400 truncate">@{{ item.company.name.split(' ')[0] }}</span>
                </div>
                <div class="flex items-center gap-1 text-[9px] sm:text-[10px] text-gray-400 font-bold">
                  <i class="fas fa-heart text-red-500"></i> {{ item.likes_count }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- FEED SLIDESHOW -->
      <div v-else-if="feed.length === 0" class="absolute inset-0 flex flex-col items-center justify-center gap-4 p-6 text-center">
        <i class="fas fa-video-slash text-5xl text-gray-600 animate-pulse"></i>
        <p class="text-gray-400 text-lg">{{ t('noFilters') }}</p>
        <button @click="resetFilters" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-semibold rounded-full shadow-lg transition">
          {{ t('resetBtn') }}
        </button>
      </div>

      <div v-else class="w-full h-full relative flex items-center justify-center">
        <!-- Active Slide with TikTok-like transition -->
        <Transition name="slide-vertical" mode="out-in">
          <div 
            :key="feed.length === 1 ? 'single-' + singleItemKeySuffix : currentIndex"
            class="w-full h-full absolute inset-0 flex items-center justify-center bg-black overflow-hidden"
          >
            <!-- Media Player -->
            <div 
              class="w-full h-full flex items-center justify-center relative cursor-pointer"
              @click="handleMediaClick"
            >
            <!-- Image Player -->
            <img 
              v-if="currentItem.media_type === 'image'" 
              :src="currentItem.media_url" 
              class="w-full h-full object-contain select-none pointer-events-none"
              alt="Propriété"
            />

            <!-- Video Player -->
            <video 
              v-else 
              ref="videoRef"
              :src="currentItem.media_url"
              class="w-full h-full object-contain"
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
            <div class="absolute inset-x-0 bottom-0 px-3 sm:px-4 pb-3 sm:pb-4 pt-16 bg-gradient-to-t from-black/90 via-black/40 to-transparent flex flex-col gap-2 sm:gap-3 z-10 pointer-events-none">
            


            <!-- Client/Owner Info and Caption Overlay -->
            <div class="flex items-end justify-between gap-2 sm:gap-4">
              <!-- Text Info -->
              <div class="flex flex-col gap-1 sm:gap-2 max-w-[70%] sm:max-w-[80%] pointer-events-auto">
                <div class="flex items-center gap-1.5 sm:gap-2">
                  <h2 class="font-bold text-sm sm:text-lg tracking-wide text-white drop-shadow-md truncate">@{{ currentItem.company.name }}</h2>
                  <span class="px-1.5 sm:px-2 py-0.5 rounded bg-blue-600/90 text-[8px] sm:text-[10px] font-bold uppercase tracking-wider text-white shrink-0">PRO</span>
                </div>
                <p class="text-[11px] sm:text-sm text-gray-200 line-clamp-1 sm:line-clamp-2 leading-relaxed drop-shadow-md">
                  {{ currentItem.description }}
                </p>
                <!-- Property Badges -->
                <div class="flex flex-wrap items-center gap-1 sm:gap-2 mt-0.5 sm:mt-1">
                  <span class="px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-full bg-black/60 backdrop-blur-sm text-[10px] sm:text-xs font-bold text-emerald-450 border border-emerald-500/20">
                    💰 {{ currentItem.property.price_label }}
                  </span>
                  <span v-if="currentItem.property.city" class="px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-full bg-black/60 backdrop-blur-sm text-[10px] sm:text-xs font-bold text-blue-400 border border-blue-500/20">
                    📍 {{ currentItem.property.city }}
                  </span>
                  <span v-if="currentItem.property.surface" class="px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-full bg-black/60 backdrop-blur-sm text-[10px] sm:text-xs font-bold text-green-400 border border-green-500/20">
                    📐 {{ currentItem.property.surface }} m²
                  </span>
                </div>
              </div>

              <!-- Action Buttons (Right Sidebar) -->
              <div class="flex flex-col items-center gap-2 sm:gap-4 pb-1 sm:pb-2 pointer-events-auto z-20">
                <!-- Profile Avatar -->
                <button @click="openProfile(currentItem.company)" class="relative group active:scale-90 transition">
                  <img :src="currentItem.company.logo" class="w-10 h-10 sm:w-12 sm:h-12 rounded-full border-2 border-white/95 object-contain bg-black shadow-xl" alt="avatar"/>
                  <span 
                    v-if="!currentItem.has_subscribed" 
                    @click.stop="toggleSubscribe(currentItem.company.id)"
                    class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-4 h-4 sm:w-5 sm:h-5 rounded-full bg-blue-500 flex items-center justify-center border-2 border-[#030712] hover:scale-115 transition"
                  >
                    <i class="fas fa-plus text-[7px] sm:text-[9px] text-white"></i>
                  </span>
                </button>

                <!-- Likes -->
                <div class="flex flex-col items-center gap-0.5 sm:gap-1">
                  <button 
                    @click="toggleLike(currentItem)" 
                    class="w-9 h-9 sm:w-11 sm:h-11 rounded-full bg-black/55 backdrop-blur-sm flex items-center justify-center text-base sm:text-xl transition active:scale-75 shadow-lg"
                    :class="currentItem.has_liked ? 'text-red-500' : 'text-white hover:text-red-400'"
                  >
                    <i class="fas fa-heart"></i>
                  </button>
                  <span class="text-[10px] sm:text-xs font-semibold text-gray-200 drop-shadow-md">{{ currentItem.likes_count }}</span>
                </div>

                <!-- Comments -->
                <div class="flex flex-col items-center gap-0.5 sm:gap-1">
                  <button 
                    @click="openComments" 
                    class="w-9 h-9 sm:w-11 sm:h-11 rounded-full bg-black/55 backdrop-blur-sm flex items-center justify-center text-base sm:text-xl text-white hover:text-blue-400 transition active:scale-75 shadow-lg"
                  >
                    <i class="fas fa-comment-dots"></i>
                  </button>
                  <span class="text-[10px] sm:text-xs font-semibold text-gray-200 drop-shadow-md">{{ currentItem.comments_count }}</span>
                </div>

                <!-- Favorites -->
                <div class="flex flex-col items-center gap-0.5 sm:gap-1">
                  <button 
                    @click="toggleFavorite(currentItem)" 
                    class="w-9 h-9 sm:w-11 sm:h-11 rounded-full bg-black/55 backdrop-blur-sm flex items-center justify-center text-base sm:text-xl transition active:scale-75 shadow-lg"
                    :class="currentItem.has_favorited ? 'text-yellow-400' : 'text-white hover:text-yellow-400'"
                  >
                    <i class="fas fa-bookmark"></i>
                  </button>
                  <span class="text-[10px] sm:text-xs font-semibold text-gray-200 drop-shadow-md">{{ currentItem.favorites_count }}</span>
                </div>

                <!-- Details CTA -->
                <div class="flex flex-col items-center gap-0.5 sm:gap-1">
                  <button 
                    @click="openDetails" 
                    class="w-9 h-9 sm:w-11 sm:h-11 rounded-full bg-black/55 backdrop-blur-sm flex items-center justify-center text-base sm:text-xl text-white hover:text-green-400 transition active:scale-75 shadow-lg"
                  >
                    <i class="fas fa-info-circle"></i>
                  </button>
                  <span class="text-[8px] sm:text-[10px] font-semibold text-gray-200 drop-shadow-md">{{ t('detailsBtn') }}</span>
                </div>

                <!-- Share -->
                <div class="flex flex-col items-center gap-0.5 sm:gap-1">
                  <button 
                    @click="openShare" 
                    class="w-9 h-9 sm:w-11 sm:h-11 rounded-full bg-black/55 backdrop-blur-sm flex items-center justify-center text-base sm:text-xl text-white hover:text-pink-400 transition active:scale-75 shadow-lg"
                  >
                    <i class="fas fa-share-alt"></i>
                  </button>
                  <span class="text-[8px] sm:text-[10px] font-semibold text-gray-200 drop-shadow-md">{{ t('shareBtn') }}</span>
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
                  <div v-if="isPlaying" class="absolute -top-3 -right-2 flex flex-col gap-0.5 text-xs text-blue-500/70 select-none animate-pulse">
                    🎵
                  </div>
                </div>

            </div>
            </div>

            </div>

            <!-- TikTok-style thin timeline at the bottom edge of the player area -->
            <div 
              v-if="currentItem.media_type === 'video'"
              class="absolute bottom-0 left-0 w-full h-1 sm:h-1.5 bg-white/10 z-20 cursor-pointer pointer-events-auto group"
              @mousedown="startDragProgress"
              @touchstart="startDragProgress"
              ref="progressBarRef"
            >
              <div 
                class="h-full bg-blue-500 transition-all duration-75"
                :style="{ width: progressPercentage + '%' }"
              ></div>
              <div 
                class="w-2.5 h-2.5 rounded-full bg-white absolute top-1/2 -translate-y-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity shadow-lg"
                :style="{ left: progressPercentage + '%' }"
              ></div>
            </div>
          </div>
        </Transition>
      </div>
    </main>

    <!-- BOTTOM NAV -->
    <nav class="w-full z-30 bg-[#030712]/95 backdrop-blur-md border-t border-white/5 py-2 px-6 flex items-center justify-between pb-safe">
      <button 
        @click="activeTab = 'foryou'" 
        class="flex flex-col items-center gap-1 transition active:scale-95 text-gray-400"
        :class="activeTab !== 'explore' ? 'text-blue-500' : 'hover:text-white'"
      >
        <i class="fas fa-home text-lg"></i>
        <span class="text-[10px] font-semibold">{{ t('homeTab') }}</span>
      </button>
      <button 
        @click="activeTab = 'explore'" 
        class="flex flex-col items-center gap-1 transition active:scale-95 text-gray-400"
        :class="activeTab === 'explore' ? 'text-blue-500' : 'hover:text-white'"
      >
        <i class="fas fa-compass text-lg"></i>
        <span class="text-[10px] font-semibold">{{ t('explore') }}</span>
      </button>
      <div class="px-2">
        <button @click="openCreatePostHint" class="w-12 h-9 rounded-xl bg-gradient-to-r from-blue-500 via-white to-green-500 p-[2px] transition active:scale-90 shadow-lg shadow-blue-500/10">
          <div class="w-full h-full rounded-[10px] bg-[#030712] flex items-center justify-center text-white">
            <i class="fas fa-plus text-xs"></i>
          </div>
        </button>
      </div>
      <button @click="openInbox" class="flex flex-col items-center gap-1 transition active:scale-95 text-gray-400 hover:text-white relative">
        <i class="fas fa-bell text-lg"></i>
        <span class="text-[10px] font-semibold">{{ t('alertsTab') }}</span>
        <span v-if="unreadCount > 0" class="absolute -top-1 -right-1.5 min-w-[16px] h-4 rounded-full bg-blue-500 text-[9px] font-bold flex items-center justify-center px-1 border border-[#030712]">
          {{ unreadCount }}
        </span>
      </button>
      <button @click="openMe" class="flex flex-col items-center gap-1 transition active:scale-95 text-gray-400 hover:text-white">
        <i class="fas fa-user-circle text-lg"></i>
        <span class="text-[10px] font-semibold">{{ t('meTab') }}</span>
      </button>
    </nav>

    <!-- CHAT FAB -->
    <button 
      @click="openChat"
      class="absolute bottom-20 right-4 z-20 w-12 h-12 rounded-full bg-gradient-to-r from-blue-600 to-blue-400 text-white flex items-center justify-center text-xl shadow-2xl active:scale-90 transition hover:scale-105"
      title="Assistant immobilier"
    >
      <i class="fas fa-comment-dots"></i>
      <span class="absolute top-0 right-0 w-3 h-3 bg-green-500 border-2 border-[#030712] rounded-full animate-ping"></span>
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
          <h3 class="font-bold text-base">{{ t('commentsTitle') }} <span class="text-xs px-2 py-0.5 bg-white/10 rounded-full text-gray-300 ml-1">{{ comments.length }}</span></h3>
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
            <p class="text-sm">{{ t('noComments') }}</p>
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
                  <i class="fas fa-reply text-[10px]"></i> {{ t('replyBtn') }}
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
          <span>{{ t('inReplyTo') }} <strong>@{{ replyCommentTarget.name }}</strong></span>
          <button @click="replyCommentTarget = null" class="text-blue-400"><i class="fas fa-times"></i></button>
        </div>
        <!-- Comment Composer -->
        <div class="p-3 bg-[#1e202d] border-t border-white/5 flex items-center gap-3">
          <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center font-bold text-sm text-white">
            {{ client ? client.name.charAt(0).toUpperCase() : 'M' }}
          </div>
          <div class="flex-1 bg-black/20 rounded-full px-4 py-2 flex items-center gap-2 border border-white/10">
            <input 
              v-model="commentText" 
              type="text" 
              class="flex-1 bg-transparent text-sm text-white focus:outline-none placeholder-gray-500" 
              :placeholder="t('addCommentPlaceholder')"
              @keyup.enter="sendComment"
            />
            <button @click="sendComment" class="text-blue-500 hover:text-blue-400 active:scale-90 transition">
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
          <h3 class="font-bold text-base">{{ t('shareTitle') }}</h3>
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
            <span class="text-xs text-gray-300">{{ t('copyBtn') }}</span>
          </button>
        </div>
        <div class="bg-black/35 rounded-xl p-3 flex items-center justify-between border border-white/5 text-xs text-gray-300">
          <span class="truncate pr-4"><i class="fas fa-link text-blue-400 mr-2"></i>{{ getShareUrl() }}</span>
          <button @click="copyToClipboard" class="px-3 py-1 bg-blue-600 hover:bg-blue-700 font-bold rounded-full text-white transition active:scale-90 shadow-md">
            {{ t('copyBtn') }}
          </button>
        </div>
      </div>
    </Transition>

    <!-- PROPERTY DETAILS SHEET -->
    <Transition name="slide-up">
      <div v-if="activeSheet === 'details'" class="absolute inset-x-0 bottom-0 h-[70vh] bg-[#181924] rounded-t-2xl z-50 flex flex-col text-white pb-safe">
        <div class="w-12 h-1.5 bg-white/10 rounded-full mx-auto my-3 cursor-pointer" @click="closeActiveSheet"></div>
        <div class="px-4 pb-3 border-b border-white/5 flex items-center justify-between">
          <h3 class="font-bold text-base">{{ t('detailsTitle') }}</h3>
          <button @click="closeActiveSheet" class="text-gray-400 hover:text-white"><i class="fas fa-times"></i></button>
        </div>
        <div class="flex-1 overflow-y-auto p-4 flex flex-col gap-6">
          <!-- Price and basic info -->
          <div class="bg-black/30 rounded-2xl p-4 border border-white/5 flex flex-col gap-2">
            <span class="text-gray-400 text-xs font-semibold uppercase tracking-wider">{{ t('transactionLabel') }} {{ currentItem.property.transaction === 'vente' ? t('sale') : t('rent') }}</span>
            <h2 class="text-3xl font-extrabold text-emerald-500">{{ currentItem.property.price_label }}</h2>
            <div class="flex items-center gap-4 text-sm text-gray-300 mt-2 border-t border-white/5 pt-2">
              <span v-if="currentItem.property.rooms"><i class="fas fa-door-open mr-1.5 text-blue-400"></i>{{ currentItem.property.rooms }} {{ currentItem.property.type === 'Immeuble' ? 'Étages' : 'Chambres/Pièces' }}</span>
              <span v-if="currentItem.property.surface"><i class="fas fa-ruler-combined mr-1.5 text-blue-400"></i>{{ currentItem.property.surface }} m²</span>
              <span><i class="fas fa-tag mr-1.5 text-green-400"></i>{{ currentItem.property.type }}</span>
            </div>
          </div>

          <!-- Location & Description -->
          <div class="flex flex-col gap-2">
            <h4 class="font-bold text-sm text-gray-400 uppercase tracking-wider"><i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>{{ t('locationTitle') }}</h4>
            <p class="text-base text-white font-semibold">
              {{ currentItem.property.neighborhood ? currentItem.property.neighborhood + ', ' : '' }}{{ currentItem.property.city }}
            </p>
            <div class="mt-4 flex flex-col gap-2">
              <h4 class="font-bold text-sm text-gray-400 uppercase tracking-wider"><i class="fas fa-file-alt mr-2 text-blue-500"></i>{{ t('descriptionTitle') }}</h4>
              <p class="text-sm text-gray-300 leading-relaxed bg-black/10 rounded-xl p-3 border border-white/5">
                {{ currentItem.description }}
              </p>
            </div>
          </div>

          <!-- Features/Equipments -->
          <div v-if="currentItem.property.features && currentItem.property.features.length > 0" class="flex flex-col gap-2">
            <h4 class="font-bold text-sm text-gray-400 uppercase tracking-wider"><i class="fas fa-concierge-bell mr-2 text-green-500"></i>{{ t('equipmentsTitle') }}</h4>
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
          <a :href="'tel:' + currentItem.company.phone" class="flex-1 h-12 bg-white/5 border border-white/10 hover:bg-white/10 rounded-full font-bold flex items-center justify-center gap-2 transition active:scale-95 text-white">
            <i class="fas fa-phone"></i> {{ t('contactBtn') }}
          </a>
          <button @click="openReserveSheet" class="flex-1 h-12 bg-blue-600 hover:bg-blue-700 rounded-full font-bold flex items-center justify-center gap-2 transition active:scale-95 shadow-lg shadow-blue-600/20 text-white">
            <i class="fas fa-calendar-check"></i> {{ t('reserveBtn') }}
          </button>
        </div>
      </div>
    </Transition>

    <!-- RESERVE VISIT SHEET -->
    <Transition name="slide-up">
      <div v-if="activeSheet === 'reserve'" class="absolute inset-x-0 bottom-0 h-[80vh] bg-[#181924] rounded-t-2xl z-50 flex flex-col text-white pb-safe">
        <div class="w-12 h-1.5 bg-white/10 rounded-full mx-auto my-3 cursor-pointer" @click="closeActiveSheet"></div>
        <div class="px-4 pb-3 border-b border-white/5 flex items-center justify-between">
          <h3 class="font-bold text-base">{{ t('reserveVisitTitle') }}</h3>
          <button @click="closeActiveSheet" class="text-gray-400 hover:text-white"><i class="fas fa-times"></i></button>
        </div>
        <form @submit.prevent="submitReservation" class="flex-1 overflow-y-auto p-4 flex flex-col gap-4">
          <div class="grid grid-cols-2 gap-3">
            <div class="flex flex-col gap-1">
              <label class="text-xs text-gray-400">{{ t('firstNameLabel') }}</label>
              <input v-model="rsvForm.firstname" type="text" required class="h-10 bg-black/20 border border-white/10 rounded-lg px-3 text-sm focus:outline-none focus:border-blue-500 text-white" placeholder="Kouamé"/>
            </div>
            <div class="flex flex-col gap-1">
              <label class="text-xs text-gray-400">{{ t('lastNameLabel') }}</label>
              <input v-model="rsvForm.lastname" type="text" required class="h-10 bg-black/20 border border-white/10 rounded-lg px-3 text-sm focus:outline-none focus:border-blue-500 text-white" placeholder="Diallo"/>
            </div>
          </div>
          <div class="flex flex-col gap-1">
            <label class="text-xs text-gray-400">{{ t('phoneLabelReq') }}</label>
            <input v-model="rsvForm.phone" type="tel" required class="h-10 bg-black/20 border border-white/10 rounded-lg px-3 text-sm focus:outline-none focus:border-blue-500 text-white" placeholder="+225 07..."/>
          </div>
          <div class="flex flex-col gap-1">
            <label class="text-xs text-gray-400">{{ t('emailLabel') }}</label>
            <input v-model="rsvForm.email" type="email" class="h-10 bg-black/20 border border-white/10 rounded-lg px-3 text-sm focus:outline-none focus:border-blue-500 text-white" placeholder="vous@email.com"/>
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div class="flex flex-col gap-1">
              <label class="text-xs text-gray-400">{{ t('desiredDateLabel') }}</label>
              <input v-model="rsvForm.date" type="date" required class="h-10 bg-black/20 border border-white/10 rounded-lg px-3 text-sm focus:outline-none focus:border-blue-500 text-white"/>
            </div>
            <div class="flex flex-col gap-1">
              <label class="text-xs text-gray-400">{{ t('timeSlotLabel') }}</label>
              <select v-model="rsvForm.time" class="h-10 bg-[#030712] border border-white/10 rounded-lg px-2 text-sm focus:outline-none focus:border-blue-500 text-white">
                <option value="">{{ t('chooseLabel') }}</option>
                <option>08h00</option><option>09h00</option><option>10h00</option><option>11h00</option>
                <option>14h00</option><option>15h00</option><option>16h00</option><option>17h00</option>
              </select>
            </div>
          </div>
          <div class="flex flex-col gap-1">
            <label class="text-xs text-gray-400">{{ t('visitTypeLabel') }}</label>
            <select v-model="rsvForm.visittype" class="h-10 bg-[#030712] border border-white/10 rounded-lg px-2 text-sm focus:outline-none focus:border-blue-500 text-white">
              <option value="visite">{{ t('physicalVisit') }}</option>
              <option value="virtuelle">{{ t('virtualVisit') }}</option>
              <option value="info">{{ t('infoRequest') }}</option>
            </select>
          </div>
          <div class="flex flex-col gap-1">
            <label class="text-xs text-gray-400">{{ t('messageLabelOpt') }}</label>
            <textarea v-model="rsvForm.message" rows="3" class="bg-black/20 border border-white/10 rounded-lg p-3 text-sm focus:outline-none focus:border-blue-500 text-white" placeholder="Budget, questions..."></textarea>
          </div>
          <button type="submit" class="mt-2 h-12 w-full bg-blue-600 hover:bg-blue-700 rounded-full font-bold shadow-lg shadow-blue-600/20 active:scale-95 transition text-white">
            {{ t('submitRequestBtn') }}
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
            <i class="fas fa-play-circle text-blue-500 text-xl"></i>
            <span class="font-extrabold text-lg">Immo<span class="text-blue-500">Tok</span></span>
          </div>
          <button @click="closeActiveSheet" class="text-gray-400 hover:text-white"><i class="fas fa-times"></i></button>
        </div>
        
        <div class="flex border-b border-white/5">
          <button 
            @click="authTab = 'login'" 
            class="flex-1 py-2 text-sm font-bold border-b-2 transition"
            :class="authTab === 'login' ? 'border-blue-500 text-white' : 'border-transparent text-gray-400'"
          >
            {{ t('loginTitle') }}
          </button>
          <button 
            @click="authTab = 'register'" 
            class="flex-1 py-2 text-sm font-bold border-b-2 transition"
            :class="authTab === 'register' ? 'border-blue-500 text-white' : 'border-transparent text-gray-400'"
          >
            {{ t('registerTitle') }}
          </button>
        </div>

        <div v-if="authTab === 'login'" class="flex flex-col gap-3">
          <div class="flex flex-col gap-1">
            <label class="text-xs text-gray-400">{{ t('emailLabel') }}</label>
            <input v-model="authForm.email" type="email" class="h-10 bg-black/20 border border-white/10 rounded-lg px-3 text-sm focus:outline-none focus:border-blue-500 text-white" placeholder="exemple@mail.com"/>
          </div>
          <div class="flex flex-col gap-1">
            <label class="text-xs text-gray-400">{{ t('passwordLabel') }}</label>
            <input v-model="authForm.password" @keyup.enter="submitLogin" type="password" class="h-10 bg-black/20 border border-white/10 rounded-lg px-3 text-sm focus:outline-none focus:border-blue-500 text-white" placeholder="••••••••"/>
          </div>
          <button @click="submitLogin" class="mt-2 h-11 bg-blue-600 hover:bg-blue-700 rounded-full font-bold active:scale-95 transition text-white">
            {{ t('loginBtn') }}
          </button>
        </div>

        <div v-else class="flex flex-col gap-3 max-h-[50vh] overflow-y-auto">
          <div class="flex flex-col gap-1">
            <label class="text-xs text-gray-400">{{ t('fullnameLabel') }}</label>
            <input v-model="registerForm.name" type="text" class="h-10 bg-black/20 border border-white/10 rounded-lg px-3 text-sm focus:outline-none focus:border-blue-500 text-white" placeholder="Jean Dupont"/>
          </div>
          <div class="flex flex-col gap-1">
            <label class="text-xs text-gray-400">{{ t('emailLabel') }}</label>
            <input v-model="registerForm.email" type="email" class="h-10 bg-black/20 border border-white/10 rounded-lg px-3 text-sm focus:outline-none focus:border-blue-500 text-white" placeholder="exemple@mail.com"/>
          </div>
          <div class="flex flex-col gap-1">
            <label class="text-xs text-gray-400">{{ t('phoneLabel') }}</label>
            <input v-model="registerForm.phone" type="tel" class="h-10 bg-black/20 border border-white/10 rounded-lg px-3 text-sm focus:outline-none focus:border-blue-500 text-white" placeholder="+225 07..."/>
          </div>
          <div class="flex flex-col gap-1">
            <label class="text-xs text-gray-400">{{ t('passwordLabel') }}</label>
            <input v-model="registerForm.password" type="password" class="h-10 bg-black/20 border border-white/10 rounded-lg px-3 text-sm focus:outline-none focus:border-blue-500 text-white" placeholder="••••••••"/>
          </div>
          <div class="flex flex-col gap-1">
            <label class="text-xs text-gray-400">{{ t('confirmPasswordLabel') }}</label>
            <input v-model="registerForm.password_confirmation" @keyup.enter="submitRegister" type="password" class="h-10 bg-black/20 border border-white/10 rounded-lg px-3 text-sm focus:outline-none focus:border-blue-500 text-white" placeholder="••••••••"/>
          </div>
          <button @click="submitRegister" class="mt-2 h-11 bg-blue-600 hover:bg-blue-700 rounded-full font-bold active:scale-95 transition text-white">
            {{ t('createAccountBtn') }}
          </button>
        </div>
      </div>
    </Transition>

    <!-- FILTER SHEET -->
    <Transition name="slide-up">
      <div v-if="activeSheet === 'filter'" class="absolute inset-x-0 bottom-0 bg-[#181924] rounded-t-2xl z-50 flex flex-col text-white p-4 pb-safe gap-4">
        <div class="w-12 h-1.5 bg-white/10 rounded-full mx-auto cursor-pointer" @click="closeActiveSheet"></div>
        <div class="flex items-center justify-between border-b border-white/5 pb-2">
          <h3 class="font-bold text-base">{{ t('filterTitle') }}</h3>
          <button @click="closeActiveSheet" class="text-gray-400 hover:text-white"><i class="fas fa-times"></i></button>
        </div>
        <div class="flex flex-col gap-3">
          <div class="flex flex-col gap-1">
            <div class="flex gap-2">
              <button 
                @click="filterOptions.transaction = 'all'"
                class="flex-1 py-2 px-3 rounded-xl text-xs font-bold border transition animate-pulse-subtle"
                :class="filterOptions.transaction === 'all' ? 'bg-blue-600 border-blue-600 text-white' : 'bg-black/20 border-white/10 text-gray-400'"
              >
                {{ t('allBtn') }}
              </button>
              <button 
                @click="filterOptions.transaction = 'location'"
                class="flex-1 py-2 px-3 rounded-xl text-xs font-bold border transition animate-pulse-subtle"
                :class="filterOptions.transaction === 'location' ? 'bg-blue-600 border-blue-600 text-white' : 'bg-black/20 border-white/10 text-gray-400'"
              >
                {{ t('rent') }}
              </button>
              <button 
                @click="filterOptions.transaction = 'vente'"
                class="flex-1 py-2 px-3 rounded-xl text-xs font-bold border transition animate-pulse-subtle"
                :class="filterOptions.transaction === 'vente' ? 'bg-blue-600 border-blue-600 text-white' : 'bg-black/20 border-white/10 text-gray-400'"
              >
                {{ t('sale') }}
              </button>
            </div>
          </div>
          <div class="flex flex-col gap-1 mt-2">
            <label class="text-xs text-gray-400 font-semibold uppercase tracking-wider">{{ t('propertyType') }}</label>
            <div class="flex flex-wrap gap-2 mt-1.5 max-h-[140px] overflow-y-auto p-1 bg-black/10 rounded-lg">
              <button 
                @click="filterOptions.type = 'all'"
                class="px-3.5 py-1.5 text-xs rounded-full border transition font-bold"
                :class="filterOptions.type === 'all' ? 'bg-blue-600 border-blue-600 text-white' : 'bg-transparent border-white/10 text-gray-400 hover:text-white'"
              >
                {{ t('allBtn') }}
              </button>
              <button 
                v-for="cat in categories" 
                :key="cat"
                @click="filterOptions.type = cat"
                class="px-3.5 py-1.5 text-xs rounded-full border transition font-bold"
                :class="filterOptions.type === cat ? 'bg-blue-600 border-blue-600 text-white' : 'bg-transparent border-white/10 text-gray-400 hover:text-white'"
              >
                {{ cat.toUpperCase() }}
              </button>
            </div>
          </div>
          <div class="flex flex-col gap-1 mt-1">
            <label class="text-xs text-gray-400">{{ t('budgetLimit') }} : {{ filterOptions.budget ? formatBudgetLabel(filterOptions.budget) : t('limitNone') }}</label>
            <input v-model="filterOptions.budget" type="range" min="0" max="10000000" step="50000" class="w-full mt-2 accent-blue-600 bg-white/10 rounded-lg appearance-none cursor-pointer" />
          </div>
          <div class="flex flex-col gap-1 mt-1">
            <label class="text-xs text-gray-400">{{ t('cityNeighborhood') }}</label>
            <input v-model="filterOptions.city" type="text" class="h-10 bg-black/20 border border-white/10 rounded-lg px-3 text-sm focus:outline-none focus:border-blue-500 text-white" placeholder="Ex: Cocody, Plateau..."/>
          </div>
          <div class="flex gap-3 mt-4">
            <button @click="resetFilters" class="flex-1 py-3 border border-white/10 hover:bg-white/5 active:scale-95 rounded-full text-sm font-semibold transition text-white">
              {{ t('resetBtn') }}
            </button>
            <button @click="applyFilters" class="flex-1 py-3 bg-blue-600 hover:bg-blue-700 active:scale-95 rounded-full text-sm font-semibold transition shadow-lg shadow-blue-600/20 text-white">
              {{ t('applyBtn') }}
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
                    <div class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center text-white text-base">
              <i class="fas fa-robot"></i>
            </div>
            <div>
              <h3 class="text-sm font-bold text-white">{{ currentItem.company.name }} AI</h3>
              <span class="text-[10px] text-green-400 flex items-center gap-1 font-semibold">
                <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span> {{ t('onlineStatus') }}
              </span>
            </div>
          </div>
          <button @click="closeActiveSheet" class="text-gray-400 hover:text-white"><i class="fas fa-times text-lg"></i></button>
        </header>

        <!-- Messages List -->
        <div ref="chatBoxRef" class="flex-1 overflow-y-auto p-4 flex flex-col gap-4">
          <div class="self-center bg-white/5 rounded-xl px-4 py-2 text-xs text-gray-400 text-center max-w-[80%] my-2 leading-relaxed">
            {{ t('welcomeChat', { name: currentItem.company.name }) }}
          </div>

          <div 
            v-for="msg in chatMessages" 
            :key="msg.id"
            class="max-w-[75%] rounded-2xl px-4 py-2.5 text-sm leading-relaxed"
            :class="msg.sender === 'client' ? 'self-end bg-blue-600 text-white rounded-br-none' : 'self-start bg-[#181924] text-gray-200 rounded-bl-none'"
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
            class="flex-1 h-11 bg-black/20 border border-white/10 rounded-full px-4 text-sm text-white focus:outline-none focus:border-blue-500 placeholder-gray-500" 
            :placeholder="t('chatPlaceholder')"
            @keyup.enter="sendChatMessage"
          />
          <button @click="sendChatMessage" class="w-11 h-11 rounded-full bg-blue-600 hover:bg-blue-700 flex items-center justify-center text-white transition active:scale-90 shadow-lg shadow-blue-600/20">
            <i class="fas fa-paper-plane text-sm"></i>
          </button>
        </div>
      </div>
    </Transition>

    <!-- COMPANY PROFILE SHEET (FULL SCREEN) -->
    <Transition name="slide-up">
      <div v-if="activeSheet === 'profile' && profileCompany" class="absolute inset-0 bg-[#030712] z-50 flex flex-col text-white pb-safe overflow-hidden" @touchstart="handleTouchStart" @touchend="handleTouchEnd">
        <!-- Profile Header -->
        <header class="w-full bg-[#181924] border-b border-white/5 py-4 px-4 flex items-center justify-between">
          <button @click="closeActiveSheet" class="text-gray-400 hover:text-white flex items-center gap-1.5"><i class="fas fa-arrow-left text-lg"></i> {{ t('backBtn') }}</button>
          <span class="font-bold text-sm tracking-wide">{{ t('profileTitle') }}</span>
          <button @click="toggleSubscribe(profileCompany.id)" class="text-xs font-bold text-blue-500 hover:text-blue-650 transition">
            {{ profileHasSubscribed ? t('subscribedBtn') : t('subscribeBtn') }}
          </button>
        </header>

        <!-- Profile content (scrollable) -->
        <div class="flex-1 overflow-y-auto px-6 py-6 flex flex-col items-center gap-6">
          <!-- Logo and Name -->
          <div class="flex flex-col items-center gap-3">
            <div class="relative">
              <img :src="profileCompany.logo" class="w-24 h-24 rounded-full object-cover border-4 border-white/10 shadow-2xl" alt="logo"/>
              <span 
                v-if="!profileHasSubscribed" 
                @click.stop="toggleSubscribe(profileCompany.id)"
                class="absolute bottom-0 right-1 w-7 h-7 rounded-full bg-blue-500 flex items-center justify-center border-2 border-[#030712] hover:scale-110 active:scale-95 transition cursor-pointer shadow-lg"
              >
                <i class="fas fa-plus text-[10px] text-white"></i>
              </span>
            </div>
            <div class="text-center">
              <h2 class="text-xl font-extrabold text-white">@{{ profileCompany.name }}</h2>
              <span class="px-2 py-0.5 rounded bg-blue-600/90 text-[10px] font-bold uppercase tracking-wider text-white mt-1.5 inline-block">PRO</span>
            </div>
            <p class="text-xs text-gray-400 flex items-center gap-1"><i class="fas fa-map-marker-alt text-blue-500"></i> {{ profileCompany.city || 'Côte d\'Ivoire' }}</p>
          </div>

          <!-- Profile Stats -->
          <div class="flex justify-around w-full max-w-sm border-y border-white/5 py-4">
            <div class="text-center">
              <div class="text-lg font-black text-white">{{ profileSubscribersCount }}</div>
              <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ t('followers') }}</div>
            </div>
            <div class="text-center">
              <div class="text-lg font-black text-white">{{ profileLikesCount }}</div>
              <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ t('likes') }}</div>
            </div>
            <div class="text-center">
              <div class="text-lg font-black text-white">{{ profileIllustrations.length }}</div>
              <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ t('videos') }}</div>
            </div>
          </div>

          <!-- Follow & Message buttons -->
          <div class="flex gap-3 w-full max-w-sm">
            <button 
              @click="toggleSubscribe(profileCompany.id)"
              class="flex-1 h-11 rounded-lg font-bold text-sm shadow-md active:scale-95 transition flex items-center justify-center gap-1.5"
              :class="profileHasSubscribed ? 'bg-white/5 border border-white/10 hover:bg-white/10 text-gray-300' : 'bg-blue-600 hover:bg-blue-700 text-white shadow-blue-600/10'"
            >
              <i :class="profileHasSubscribed ? 'fas fa-check-circle text-green-500' : 'fas fa-user-plus'"></i>
              {{ profileHasSubscribed ? t('subscribedBtn') : t('subscribeBtn') }}
            </button>
            <button @click="openChatFromProfile" class="flex-1 h-11 bg-white/5 border border-white/10 hover:bg-white/10 rounded-lg font-bold text-sm text-white active:scale-95 transition flex items-center justify-center gap-1.5">
              <i class="fas fa-comment-dots"></i> {{ t('messageBtn') }}
            </button>
          </div>

          <!-- Grid of company illustrations -->
          <div class="w-full flex flex-col gap-3 mt-4">
            <h3 class="font-black text-sm text-gray-400 uppercase tracking-wider self-start"><i class="fas fa-th mr-2 text-blue-500"></i>{{ t('publicationsTitle') }}</h3>
            <div v-if="profileIllustrations.length === 0" class="py-10 text-center text-gray-500 text-sm">
              {{ t('noIllustrations') }}
            </div>
            <div v-else class="grid grid-cols-3 gap-1.5 w-full">
              <div 
                v-for="img in profileIllustrations" 
                :key="img.id"
                @click="playProfileIllustration(img.id)"
                class="aspect-[3/4] bg-black relative rounded-md overflow-hidden cursor-pointer group hover:opacity-85 transition"
              >
                <img v-if="img.media_type === 'image'" :src="img.media_url" class="w-full h-full object-contain" />
                <video v-else :src="img.media_url" class="w-full h-full object-contain" muted></video>
                <div v-if="img.media_type === 'video'" class="absolute bottom-1 right-1 text-white text-[9px] bg-black/40 px-1 rounded flex items-center gap-0.5"><i class="fas fa-play"></i></div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </Transition>

    <!-- MY PROFILE SHEET (FULL SCREEN - TIKTOK STYLE) -->
    <Transition name="slide-up">
      <div v-if="activeSheet === 'myprofile'" class="absolute inset-0 bg-[#030712] z-50 flex flex-col text-white pb-safe overflow-hidden">
        <!-- Profile Header -->
        <header class="w-full bg-[#181924] border-b border-white/5 py-4 px-4 flex items-center justify-between">
          <button @click="closeActiveSheet" class="text-gray-400 hover:text-white flex items-center gap-1.5"><i class="fas fa-arrow-left text-lg"></i> {{ t('backBtn') }}</button>
          <div class="flex items-center gap-1.5">
            <i class="fas fa-play-circle text-blue-500 text-base"></i>
            <span class="font-extrabold text-sm">Immo<span class="text-blue-500">Tok</span></span>
          </div>
          <button @click="handleLogout" class="text-xs font-bold text-red-500 hover:text-red-400 transition">{{ t('logoutBtn') }}</button>
        </header>

        <div class="flex-1 overflow-y-auto">
          <!-- Profile Card -->
          <div class="flex flex-col items-center gap-3 py-6 px-6">
            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-blue-600 via-indigo-500 to-cyan-400 p-[3px] shadow-2xl">
              <div class="w-full h-full rounded-full bg-[#030712] flex items-center justify-center text-4xl font-black text-white">
                {{ client ? client.name.charAt(0).toUpperCase() : '?' }}
              </div>
            </div>
            <h2 class="text-xl font-extrabold text-white">{{ client?.name || 'Utilisateur' }}</h2>
            <p class="text-xs text-gray-400 flex items-center gap-1.5">
              <i class="fas fa-envelope text-blue-400"></i> {{ client?.email || '' }}
            </p>
            <p v-if="client?.phone" class="text-xs text-gray-400 flex items-center gap-1.5">
              <i class="fas fa-phone text-green-400"></i> {{ client.phone }}
            </p>
          </div>

          <!-- Stats Row -->
          <div class="flex justify-around border-y border-white/5 py-4 mx-6">
            <div class="text-center">
              <div class="text-lg font-black text-white">{{ myProfileData.stats?.subscriptions_count || 0 }}</div>
              <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ t('subs') }}</div>
            </div>
            <div class="text-center">
              <div class="text-lg font-black text-white">{{ myProfileData.stats?.favorites_count || 0 }}</div>
              <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ t('favorites') }}</div>
            </div>
            <div class="text-center">
              <div class="text-lg font-black text-white">{{ myProfileData.stats?.likes_count || 0 }}</div>
              <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ t('likes') }}</div>
            </div>
          </div>

          <!-- Tabs -->
          <div class="flex border-b border-white/5 mt-2">
            <button 
              @click="myProfileTab = 'subs'" 
              class="flex-1 py-3 text-sm font-bold border-b-2 transition flex items-center justify-center gap-2"
              :class="myProfileTab === 'subs' ? 'border-blue-500 text-white' : 'border-transparent text-gray-400'"
            >
              <i class="fas fa-building"></i> {{ t('subs') }}
            </button>
            <button 
              @click="myProfileTab = 'favs'" 
              class="flex-1 py-3 text-sm font-bold border-b-2 transition flex items-center justify-center gap-2"
              :class="myProfileTab === 'favs' ? 'border-blue-500 text-white' : 'border-transparent text-gray-400'"
            >
              <i class="fas fa-bookmark"></i> {{ t('favorites') }}
            </button>
            <button 
              @click="myProfileTab = 'likes'" 
              class="flex-1 py-3 text-sm font-bold border-b-2 transition flex items-center justify-center gap-2"
              :class="myProfileTab === 'likes' ? 'border-blue-500 text-white' : 'border-transparent text-gray-400'"
            >
              <i class="fas fa-heart"></i> {{ t('likes') }}
            </button>
          </div>

          <!-- Loading -->
          <div v-if="myProfileLoading" class="py-16 flex items-center justify-center">
            <i class="fas fa-spinner animate-spin text-2xl text-blue-500"></i>
          </div>

          <!-- Subscriptions Tab -->
          <div v-else-if="myProfileTab === 'subs'" class="px-4 py-4">
            <div v-if="myProfileData.subscriptions?.length === 0" class="py-16 flex flex-col items-center justify-center text-center text-gray-500 gap-3">
              <i class="fas fa-users text-4xl"></i>
              <p class="text-sm">{{ t('noSubscriptions') }}</p>
              <button @click="closeActiveSheet(); activeTab = 'explore'" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 rounded-full text-sm font-bold text-white active:scale-95 transition">{{ t('explore') }}</button>
            </div>
            <div class="flex flex-col gap-3">
              <div 
                v-for="sub in myProfileData.subscriptions" 
                :key="sub.id"
                @click="closeActiveSheet(); openProfile(sub)"
                class="flex items-center gap-3 p-3 bg-[#181924] rounded-xl border border-white/5 hover:bg-[#1e202d] active:scale-[0.98] transition cursor-pointer"
              >
                <img :src="sub.logo" class="w-12 h-12 rounded-full object-cover border-2 border-white/10" alt="logo"/>
                <div class="flex-1 min-w-0">
                  <h4 class="text-sm font-bold text-white truncate">{{ sub.name }}</h4>
                  <p class="text-[10px] text-gray-400 flex items-center gap-1 mt-0.5">
                    <i class="fas fa-tag text-blue-400"></i> {{ sub.business_type }}
                    <span v-if="sub.city"> · <i class="fas fa-map-marker-alt text-blue-400"></i> {{ sub.city }}</span>
                  </p>
                </div>
                <div class="flex flex-col items-end gap-1">
                  <span class="text-[9px] text-gray-500">{{ sub.subscribed_at }}</span>
                  <i class="fas fa-chevron-right text-xs text-gray-600"></i>
                </div>
              </div>
            </div>
          </div>

          <!-- Favorites Tab -->
          <div v-else-if="myProfileTab === 'favs'" class="px-4 py-4">
            <div v-if="myProfileData.favorites?.length === 0" class="py-16 flex flex-col items-center justify-center text-center text-gray-500 gap-3">
              <i class="fas fa-bookmark text-4xl"></i>
              <p class="text-sm">{{ t('noFavorites') }}</p>
              <p class="text-xs text-gray-600">{{ t('favoritesHelp') }}</p>
            </div>
            <div v-else class="grid grid-cols-3 gap-1.5">
              <div 
                v-for="fav in myProfileData.favorites" 
                :key="fav.id"
                @click="playFavoriteItem(fav.id)"
                class="aspect-[3/4] bg-black relative rounded-md overflow-hidden cursor-pointer group hover:opacity-85 transition"
              >
                <img v-if="fav.media_type === 'image'" :src="fav.media_url" class="w-full h-full object-contain" />
                <video v-else :src="fav.media_url" class="w-full h-full object-contain" muted></video>
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent"></div>
                <div v-if="fav.media_type === 'video'" class="absolute top-1.5 right-1.5 text-white text-[9px] bg-black/40 px-1.5 py-0.5 rounded flex items-center gap-0.5"><i class="fas fa-play"></i></div>
                <div class="absolute bottom-1.5 left-1.5 right-1.5">
                  <div class="flex items-center gap-1 mb-0.5">
                    <img :src="fav.company_logo" class="w-3.5 h-3.5 rounded-full object-cover" />
                    <span class="text-[8px] font-bold text-gray-300 truncate">{{ fav.company_name }}</span>
                  </div>
                  <div class="flex items-center gap-1 text-[9px] text-gray-300"><i class="fas fa-heart text-red-500"></i> {{ fav.likes_count }}</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Likes Tab -->
          <div v-else-if="myProfileTab === 'likes'" class="px-4 py-4">
            <div v-if="myProfileData.likes?.length === 0" class="py-16 flex flex-col items-center justify-center text-center text-gray-500 gap-3">
              <i class="fas fa-heart text-4xl"></i>
              <p class="text-sm">{{ t('noLikes') }}</p>
            </div>
            <div v-else class="grid grid-cols-3 gap-1.5">
              <div 
                v-for="like in myProfileData.likes" 
                :key="like.id"
                @click="playFavoriteItem(like.id)"
                class="aspect-[3/4] bg-black relative rounded-md overflow-hidden cursor-pointer group hover:opacity-85 transition"
              >
                <img v-if="like.media_type === 'image'" :src="like.media_url" class="w-full h-full object-contain" />
                <video v-else :src="like.media_url" class="w-full h-full object-contain" muted></video>
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent"></div>
                <div v-if="like.media_type === 'video'" class="absolute top-1.5 right-1.5 text-white text-[9px] bg-black/40 px-1.5 py-0.5 rounded flex items-center gap-0.5"><i class="fas fa-play"></i></div>
                <div class="absolute bottom-1.5 left-1.5 right-1.5">
                  <div class="flex items-center gap-1 mb-0.5">
                    <img :src="like.company_logo" class="w-3.5 h-3.5 rounded-full object-cover" />
                    <span class="text-[8px] font-bold text-gray-300 truncate">{{ like.company_name }}</span>
                  </div>
                  <div class="flex items-center gap-1 text-[9px] text-gray-300"><i class="fas fa-heart text-red-500"></i> {{ like.likes_count }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </Transition>

    <!-- INBOX NOTIFICATIONS SHEET -->
    <Transition name="slide-up">
      <div v-if="activeSheet === 'inbox'" class="absolute inset-0 bg-[#030712] z-50 flex flex-col text-white pb-safe">
        <header class="w-full bg-[#181924] border-b border-white/5 py-4 px-4 flex items-center justify-between">
          <button @click="closeActiveSheet" class="text-gray-400 hover:text-white flex items-center gap-1.5"><i class="fas fa-arrow-left text-lg"></i> {{ t('backBtn') }}</button>
          <div class="flex items-center gap-1.5">
            <i class="fas fa-bell text-blue-500 text-base"></i>
            <span class="font-bold text-sm">{{ t('inboxTitle') }}</span>
          </div>
          <button v-if="notifications.length > 0" @click="markAllNotifsRead" class="text-xs font-bold text-blue-500 hover:text-blue-400 transition">{{ t('markAllRead') }}</button>
        </header>
        <div v-if="notifsLoading" class="flex-1 flex items-center justify-center">
          <i class="fas fa-spinner animate-spin text-2xl text-blue-500"></i>
        </div>
        <div v-else-if="notifications.length === 0" class="flex-1 flex flex-col items-center justify-center text-center text-gray-500 gap-3 px-8">
          <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-blue-600 via-indigo-500 to-cyan-400 p-[3px] shadow-2xl">
            <div class="w-full h-full rounded-[14px] bg-[#030712] flex items-center justify-center">
              <i class="fas fa-bell text-blue-500 text-3xl"></i>
            </div>
          </div>
          <h3 class="font-extrabold text-xl">Immo<span class="text-blue-500">Tok</span></h3>
          <p class="text-sm text-gray-400">{{ t('noNotifications') }}</p>
          <p class="text-xs text-gray-600">{{ t('notifSubText') }}</p>
        </div>
        <div v-else class="flex-1 overflow-y-auto px-4 py-4 flex flex-col gap-2">
          <div 
            v-for="notif in notifications" 
            :key="notif.id"
            @click="handleNotifClick(notif)"
            class="flex items-start gap-3 p-3 rounded-xl transition cursor-pointer"
            :class="notif.is_read ? 'bg-transparent hover:bg-white/5' : 'bg-blue-600/5 border border-blue-500/10 hover:bg-blue-600/10'"
          >
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center shrink-0 mt-0.5">
              <i class="fas fa-home text-white text-sm"></i>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-semibold text-white truncate">{{ notif.title }}</p>
              <p class="text-xs text-gray-400 mt-0.5 line-clamp-2">{{ notif.message }}</p>
              <span class="text-[10px] text-gray-600 mt-1 block">{{ notif.created_at }}</span>
            </div>
            <div v-if="!notif.is_read" class="w-2 h-2 rounded-full bg-blue-500 shrink-0 mt-2"></div>
          </div>
        </div>
      </div>
    </Transition>

    <!-- CREATE POST INFO SHEET -->
    <Transition name="slide-up">
      <div v-if="activeSheet === 'createpost'" class="absolute inset-x-0 bottom-0 bg-[#181924] rounded-t-2xl z-50 flex flex-col text-white p-6 pb-safe gap-5">
        <div class="w-12 h-1.5 bg-white/10 rounded-full mx-auto cursor-pointer" @click="closeActiveSheet"></div>
        <div class="flex flex-col items-center gap-4 py-4">
          <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-blue-500 via-white to-green-500 p-[3px] shadow-2xl">
            <div class="w-full h-full rounded-[14px] bg-[#030712] flex items-center justify-center">
              <i class="fas fa-camera text-white text-3xl"></i>
            </div>
          </div>
          <div class="text-center">
            <h3 class="font-extrabold text-xl">Immo<span class="text-blue-500">Tok</span></h3>
            <p class="text-xs text-gray-400 mt-1">{{ t('createPostTitle') }}</p>
          </div>
          <div class="w-full bg-black/20 rounded-xl p-4 border border-white/5 flex flex-col gap-4">
            <div class="flex items-start gap-3">
              <div class="w-10 h-10 rounded-full bg-purple-500/10 flex items-center justify-center shrink-0 mt-0.5"><i class="fas fa-building text-purple-400"></i></div>
              <div>
                <p class="font-semibold text-white text-sm">{{ t('managerSpace') }}</p>
                <p class="text-xs text-gray-400 mt-1 leading-relaxed">{{ t('managerSubText') }}</p>
              </div>
            </div>
            <div class="border-t border-white/5 pt-3 flex items-start gap-3">
              <div class="w-10 h-10 rounded-full bg-cyan-500/10 flex items-center justify-center shrink-0 mt-0.5"><i class="fas fa-upload text-cyan-400"></i></div>
              <div>
                <p class="font-semibold text-white text-sm">{{ t('howItWorks') }}</p>
                <p class="text-xs text-gray-400 mt-1 leading-relaxed">{{ t('howItWorksSubText') }}</p>
              </div>
            </div>
          </div>
          <a href="/login" class="w-full h-12 bg-blue-600 hover:bg-blue-700 rounded-full font-bold text-sm flex items-center justify-center gap-2 shadow-lg shadow-blue-600/20 active:scale-95 transition text-white">
            <i class="fas fa-sign-in-alt"></i> {{ t('managerConnBtn') }}
          </a>
        </div>
      </div>
    </Transition>

    <!-- Global Mute Info Banner -->
    <Transition name="fade">
      <div v-if="showMuteInfoBanner" class="absolute top-16 left-1/2 -translate-x-1/2 z-40 bg-black/75 backdrop-blur-sm text-xs font-bold px-4 py-2 rounded-full flex items-center gap-2 border border-white/10">
        <i class="fas" :class="isMuted ? 'fa-volume-mute text-blue-500' : 'fa-volume-up text-green-500'"></i>
        <span>{{ isMuted ? t('muteOn') : t('muteOff') }}</span>
      </div>
    </Transition>

  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted, nextTick } from 'vue';
import axios from 'axios';

// ── Translation System ────────────────────────────────────────────────────────
const currentLang = ref('fr');

const translations = {
  fr: {
    foryou: 'Pour vous',
    subs: 'Abonnements',
    explore: 'Explorer',
    refreshing: 'Actualisation...',
    pullToRefresh: 'Tirez pour actualiser',
    searchPlaceholder: 'Rechercher des biens, quartiers, villes...',
    searchBtn: 'Rechercher',
    popularSearches: 'Recherches populaires',
    noResults: 'Aucun bien correspondant à votre recherche.',
    noFilters: 'Aucun bien ne correspond aux filtres de recherche.',
    resetBtn: 'Réinitialiser',
    applyBtn: 'Appliquer',
    filterTitle: 'Filtrer les offres',
    transactionType: 'Type de transaction',
    propertyType: 'Type de bien',
    budgetLimit: 'Budget Maximum (FCFA)',
    limitNone: 'Sans limite',
    cityNeighborhood: 'Ville / Quartier',
    welcomeChat: 'Bienvenue sur la messagerie de {name}. Notre conseiller IA peut répondre instantanément à vos questions sur nos offres.',
    onlineStatus: 'Assistant en ligne',
    chatPlaceholder: 'Écrivez votre message...',
    profileTitle: 'Profil',
    followers: 'Abonnés',
    likes: 'J\'aime',
    videos: 'Vidéos',
    subscribeBtn: 'S\'abonner',
    subscribedBtn: 'Abonné',
    messageBtn: 'Message',
    noIllustrations: 'Cette entreprise n\'a pas encore publié d\'illustrations.',
    publicationsTitle: 'Publications',
    logoutBtn: 'Déconnexion',
    notConnected: 'Non connecté.',
    backBtn: 'Retour',
    inboxTitle: 'Alertes',
    markAllRead: 'Tout lu',
    noNotifications: 'Aucune notification pour le moment.',
    notifSubText: 'Suivez des entreprises pour recevoir leurs nouvelles publications.',
    createPostTitle: 'Publier du contenu',
    managerSpace: 'Espace Gestionnaire',
    managerSubText: 'Pour publier vos propres vidéos et illustrations de biens immobiliers, connectez-vous à votre espace Gestionnaire Entreprise ou Agence.',
    howItWorks: 'Comment ça marche ?',
    howItWorksSubText: 'Rendez-vous dans le menu Illustrations de votre dashboard pour importer vos photos et vidéos. Elles apparaîtront automatiquement sur ImmoTok !',
    managerConnBtn: 'Connexion Gestionnaire',
    muteOn: 'Audio désactivé',
    muteOff: 'Audio activé en boucle',
    homeTab: 'Accueil',
    alertsTab: 'Alertes',
    meTab: 'Moi',
    commentsTitle: 'Commentaires',
    noComments: 'Aucun commentaire pour le moment. Soyez le premier !',
    replyBtn: 'Répondre',
    inReplyTo: 'En réponse à',
    addCommentPlaceholder: 'Ajouter un commentaire...',
    shareTitle: 'Partager ce bien',
    copyBtn: 'Copier',
    detailsTitle: 'Fiche descriptive du bien',
    transactionLabel: 'Transaction :',
    sale: 'Vente',
    rent: 'Location',
    locationTitle: 'Localisation',
    descriptionTitle: 'Description de l\'offre',
    equipmentsTitle: 'Équipements & Services',
    contactBtn: 'Contacter',
    reserveBtn: 'Réserver visite',
    detailsBtn: 'Détails',
    shareBtn: 'Partager',
    allBtn: 'Tous',
    fullnameLabel: 'Nom complet *',
    emailLabel: 'Email *',
    phoneLabel: 'Téléphone',
    passwordLabel: 'Mot de passe *',
    confirmPasswordLabel: 'Confirmer mot de passe *',
    createAccountBtn: 'Créer mon compte',
    firstNameLabel: 'Prénom *',
    lastNameLabel: 'Nom *',
    phoneLabelReq: 'Téléphone *',
    desiredDateLabel: 'Date souhaitée *',
    timeSlotLabel: 'Créneau horaire',
    chooseLabel: 'Choisir...',
    visitTypeLabel: 'Type de visite',
    physicalVisit: 'Visite physique',
    virtualVisit: 'Visite virtuelle (vidéo)',
    infoRequest: 'Demande d\'informations',
    messageLabelOpt: 'Message (optionnel)',
    submitRequestBtn: 'Soumettre la demande',
    loginTitle: 'Se connecter',
    registerTitle: 'Créer un compte',
    loginBtn: 'Connexion',
    reserveVisitTitle: 'Réserver une visite',
    favorites: 'Favoris',
    noSubscriptions: "Vous n'êtes abonné à aucune entreprise.",
    noFavorites: "Vous n'avez aucun favori pour le moment.",
    favoritesHelp: "Appuyez sur le signet pour sauvegarder des biens.",
    noLikes: "Vous n'avez aimé aucun bien pour le moment.",
    linkCopied: "Lien copié dans le presse-papier !",
    reservationSuccess: "Demande de visite soumise avec succès !",
    reservationError: "Une erreur s'est produite lors de la réservation.",
    invalidCredentials: "Identifiants incorrects.",
    registrationError: "Erreur lors de l'inscription."
  },
  en: {
    foryou: 'For You',
    subs: 'Following',
    explore: 'Explore',
    refreshing: 'Refreshing...',
    pullToRefresh: 'Pull to refresh',
    searchPlaceholder: 'Search properties, neighborhoods, cities...',
    searchBtn: 'Search',
    popularSearches: 'Popular searches',
    noResults: 'No properties matching your search.',
    noFilters: 'No properties match the search filters.',
    resetBtn: 'Reset',
    applyBtn: 'Apply',
    filterTitle: 'Filter Offers',
    transactionType: 'Transaction Type',
    propertyType: 'Property Type',
    budgetLimit: 'Maximum Budget (FCFA)',
    limitNone: 'No limit',
    cityNeighborhood: 'City / Neighborhood',
    welcomeChat: 'Welcome to the chat of {name}. Our AI assistant can instantly answer your questions about our offers.',
    onlineStatus: 'Assistant online',
    chatPlaceholder: 'Type your message...',
    profileTitle: 'Profile',
    followers: 'Followers',
    likes: 'Likes',
    videos: 'Videos',
    subscribeBtn: 'Subscribe',
    subscribedBtn: 'Subscribed',
    messageBtn: 'Message',
    noIllustrations: 'This company has not published any illustrations yet.',
    publicationsTitle: 'Publications',
    logoutBtn: 'Log out',
    notConnected: 'Not connected.',
    backBtn: 'Back',
    inboxTitle: 'Alerts',
    markAllRead: 'Mark all read',
    noNotifications: 'No notifications at the moment.',
    notifSubText: 'Follow companies to receive their new publications.',
    createPostTitle: 'Publish Content',
    managerSpace: 'Manager Space',
    managerSubText: 'To publish your own videos and property illustrations, log in to your Enterprise or Agency Manager space.',
    howItWorks: 'How it works',
    howItWorksSubText: 'Go to the Illustrations menu in your dashboard to import your photos and videos. They will automatically appear on ImmoTok!',
    managerConnBtn: 'Manager Login',
    muteOn: 'Audio muted',
    muteOff: 'Audio playing looped',
    homeTab: 'Home',
    alertsTab: 'Alerts',
    meTab: 'Me',
    commentsTitle: 'Comments',
    noComments: 'No comments yet. Be the first!',
    replyBtn: 'Reply',
    inReplyTo: 'In reply to',
    addCommentPlaceholder: 'Add a comment...',
    shareTitle: 'Share this property',
    copyBtn: 'Copy',
    detailsTitle: 'Property description sheet',
    transactionLabel: 'Transaction:',
    sale: 'Sale',
    rent: 'Rent',
    locationTitle: 'Location',
    descriptionTitle: 'Offer description',
    equipmentsTitle: 'Amenities & Services',
    contactBtn: 'Contact',
    reserveBtn: 'Book visit',
    detailsBtn: 'Details',
    shareBtn: 'Share',
    allBtn: 'All',
    fullnameLabel: 'Full name *',
    emailLabel: 'Email *',
    phoneLabel: 'Phone',
    passwordLabel: 'Password *',
    confirmPasswordLabel: 'Confirm password *',
    createAccountBtn: 'Create my account',
    firstNameLabel: 'First name *',
    lastNameLabel: 'Last name *',
    phoneLabelReq: 'Phone *',
    desiredDateLabel: 'Desired date *',
    timeSlotLabel: 'Time slot',
    chooseLabel: 'Choose...',
    visitTypeLabel: 'Visit type',
    physicalVisit: 'Physical visit',
    virtualVisit: 'Virtual visit (video)',
    infoRequest: 'Request info',
    messageLabelOpt: 'Message (optional)',
    submitRequestBtn: 'Submit request',
    loginTitle: 'Log In',
    registerTitle: 'Create Account',
    loginBtn: 'Log In',
    reserveVisitTitle: 'Book a Visit',
    favorites: 'Favorites',
    noSubscriptions: "You are not subscribed to any company.",
    noFavorites: "You have no favorites yet.",
    favoritesHelp: "Press the bookmark icon to save properties.",
    noLikes: "You have not liked any properties yet.",
    linkCopied: "Link copied to clipboard!",
    reservationSuccess: "Visit request submitted successfully!",
    reservationError: "An error occurred during reservation.",
    invalidCredentials: "Incorrect credentials.",
    registrationError: "Error during registration."
  }
};

const t = (key, params = {}) => {
  let val = translations[currentLang.value][key] || key;
  Object.keys(params).forEach(k => {
    val = val.replace(`{${k}}`, params[k]);
  });
  return val;
};

// ── Splash Screen ──────────────────────────────────────────────────────────────
const showSplash = ref(true);
const splashProgress = ref(0);

// ── Toast notifications ────────────────────────────────────────────────────────
const toast = ref({ show: false, message: '', icon: 'fa-bell', color: '#3B82F6' });
let toastTimer = null;

const showToast = (message, icon = 'fa-bell', color = '#3B82F6', duration = 3000) => {
  if (toastTimer) clearTimeout(toastTimer);
  toast.value = { show: true, message, icon, color };
  toastTimer = setTimeout(() => { toast.value.show = false; }, duration);
};

// ── Notification polling ───────────────────────────────────────────────────────
let notificationTimer = null;
const notifications = ref([]);
const notifsLoading = ref(false);

// Component State
const feed = ref([]);
const currentIndex = ref(0);
const activeTab = ref('foryou');
const client = ref(null);
const activeSheet = ref(null);
const unreadCount = ref(0);

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
const pendingAction = ref(null);

// Filter options
const filterOptions = ref({
  transaction: 'all',
  type: 'all',
  budget: 0,
  city: '',
});

// Categories & Explorer state
const categories = ref([]);
const searchQuery = ref('');
const exploreResults = ref([]);
const exploreLoading = ref(false);

// Company Profile state
const profileCompany = ref(null);
const profileSubscribersCount = ref(0);
const profileLikesCount = ref(0);
const profileHasSubscribed = ref(false);
const profileIllustrations = ref([]);

// My Profile state
const myProfileTab = ref('subs');
const myProfileLoading = ref(false);
const myProfileData = ref({ subscriptions: [], favorites: [], stats: {} });

// Infinite loop & pull-to-refresh
const singleItemKeySuffix = ref(0);
const isPullingRefresh = ref(false);
const pullDisplacement = ref(0);
const isRefreshing = ref(false);

// Chatbot messages
const chatMessages = ref([]);
const chatInputText = ref('');
const isAiTyping = ref(false);
const chatBoxRef = ref(null);
const chatSuggestions = computed(() => {
  return currentLang.value === 'fr' ? [
    'Est-ce que ce bien est disponible ?',
    'Quelles sont les conditions de location ?',
    'Puis-je programmer une visite ?',
    'Quel est le loyer avec les charges ?'
  ] : [
    'Is this property available?',
    'What are the rental conditions?',
    'Can I schedule a visit?',
    'What is the rent including charges?'
  ];
});

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

watch(activeTab, (newTab) => {
  if (newTab === 'subs' && !client.value) {
    activeSheet.value = 'auth';
    activeTab.value = 'foryou';
    return;
  }
  if (newTab === 'explore') {
    handleExploreSearch();
  } else {
    fetchFeed();
  }
});

// Fetch feed from backend
const fetchFeed = async () => {
  try {
    const params = { ...filterOptions.value, tab: activeTab.value };
    const res = await axios.get('/api/immotok/feed', { params });
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
  const token = localStorage.getItem('immotok_token');
  if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
  } else {
    delete axios.defaults.headers.common['Authorization'];
    client.value = null;
    return;
  }
  try {
    const res = await axios.get('/api/immotok/auth/me');
    if (res.data.success) {
      client.value = res.data.client;
    } else {
      client.value = null;
      localStorage.removeItem('immotok_token');
      delete axios.defaults.headers.common['Authorization'];
    }
  } catch (e) {
    client.value = null;
    localStorage.removeItem('immotok_token');
    delete axios.defaults.headers.common['Authorization'];
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
    pendingAction.value = () => toggleLike(item);
    activeSheet.value = 'auth';
    return;
  }
  const oldHasLiked = item.has_liked;
  const oldLikesCount = item.likes_count;
  
  item.has_liked = !oldHasLiked;
  item.likes_count = oldHasLiked ? oldLikesCount - 1 : oldLikesCount + 1;

  try {
    const res = await axios.post(`/api/immotok/illustrations/${item.id}/like`);
    if (res.data.success) {
      item.has_liked = res.data.liked;
      item.likes_count = res.data.likes_count;
    } else {
      item.has_liked = oldHasLiked;
      item.likes_count = oldLikesCount;
    }
  } catch (e) {
    console.error(e);
    item.has_liked = oldHasLiked;
    item.likes_count = oldLikesCount;
  }
};

const toggleFavorite = async (item) => {
  if (!client.value) {
    pendingAction.value = () => toggleFavorite(item);
    activeSheet.value = 'auth';
    return;
  }
  const oldHasFavorited = item.has_favorited;
  const oldFavoritesCount = item.favorites_count;
  
  item.has_favorited = !oldHasFavorited;
  item.favorites_count = oldHasFavorited ? oldFavoritesCount - 1 : oldFavoritesCount + 1;

  try {
    const res = await axios.post(`/api/immotok/illustrations/${item.id}/favorite`);
    if (res.data.success) {
      item.has_favorited = res.data.favorited;
      item.favorites_count = res.data.favorites_count;
    } else {
      item.has_favorited = oldHasFavorited;
      item.favorites_count = oldFavoritesCount;
    }
  } catch (e) {
    console.error(e);
    item.has_favorited = oldHasFavorited;
    item.favorites_count = oldFavoritesCount;
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
let touchStartX = 0;
let touchStartTime = 0;
let isScrolling = false;
let swipeVelocity = 0;

const handleTouchStart = (e) => {
  touchStartY = e.touches[0].clientY;
  touchStartX = e.touches[0].clientX;
  touchStartTime = Date.now();
  isScrolling = false;
  isPullingRefresh.value = false;
  pullDisplacement.value = 0;
};

const handleTouchMove = (e) => {
  if (activeSheet.value && activeSheet.value !== 'profile') return;
  if (activeTab.value === 'explore') return;

  const currentY = e.touches[0].clientY;
  const currentX = e.touches[0].clientX;
  const diffY = currentY - touchStartY;
  const diffX = currentX - touchStartX;

  // Pull-to-refresh: only if at first slide, moving down, and mostly vertical
  if (currentIndex.value === 0 && diffY > 0 && Math.abs(diffX) < 40) {
    pullDisplacement.value = Math.min(diffY * 0.4, 90);
    if (pullDisplacement.value > 15) {
      isPullingRefresh.value = true;
    }
  }
};

const handleTouchEnd = (e) => {
  // If pull to refresh triggered
  if (isPullingRefresh.value && pullDisplacement.value > 55) {
    triggerRefresh();
    isPullingRefresh.value = false;
    pullDisplacement.value = 0;
    return;
  }
  isPullingRefresh.value = false;
  pullDisplacement.value = 0;

  const touchEndY = e.changedTouches[0].clientY;
  const touchEndX = e.changedTouches[0].clientX;
  const diffY = touchStartY - touchEndY;
  const diffX = touchStartX - touchEndX;
  const diffTime = Date.now() - touchStartTime;
  swipeVelocity = Math.abs(diffY / diffTime);

  // Swipe X: Horizontal swiping to/from profile
  if (Math.abs(diffX) > 85 && Math.abs(diffY) < 65) {
    if (diffX > 85) {
      // Left swipe -> Open profile
      if (currentItem.value && currentItem.value.company) {
        openProfile(currentItem.value.company);
      }
    } else if (diffX < -85) {
      // Right swipe -> Close profile if open
      if (activeSheet.value === 'profile') {
        closeActiveSheet();
      }
    }
    return;
  }

  // Swipe Y: Vertical swiping through feed
  const threshold = swipeVelocity > 0.5 ? 25 : 55;

  if (feed.value.length <= 1) {
    if (feed.value.length === 1 && Math.abs(diffY) > threshold) {
      singleItemKeySuffix.value = Date.now();
    }
    return;
  }

  if (diffY > threshold) {
    if (currentIndex.value < feed.value.length - 1) {
      currentIndex.value++;
    } else {
      currentIndex.value = 0; // Infinite loop forward
    }
  } else if (diffY < -threshold) {
    if (currentIndex.value > 0) {
      currentIndex.value--;
    } else {
      currentIndex.value = feed.value.length - 1; // Infinite loop backward
    }
  }
};

const handleWheel = (e) => {
  if (feed.value.length <= 1) {
    if (feed.value.length === 1 && Math.abs(e.deltaY) > 10) {
      singleItemKeySuffix.value = Date.now();
    }
    return;
  }
  if (isScrolling) return;

  if (Math.abs(e.deltaY) > 10) {
    isScrolling = true;
    if (e.deltaY > 0) {
      if (currentIndex.value < feed.value.length - 1) {
        currentIndex.value++;
      } else {
        currentIndex.value = 0; // Infinite loop forward
      }
    } else {
      if (currentIndex.value > 0) {
        currentIndex.value--;
      } else {
        currentIndex.value = feed.value.length - 1; // Infinite loop backward
      }
    }
    setTimeout(() => {
      isScrolling = false;
    }, 850); // Restore 850ms mouse wheel throttle lock
  }
};

const triggerRefresh = async () => {
  isRefreshing.value = true;
  await fetchFeed();
  setTimeout(() => {
    isRefreshing.value = false;
  }, 800);
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
    pendingAction.value = () => sendComment();
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
  showToast(t('linkCopied'), 'fa-check-circle', '#10B981');
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
      showToast(res.data.message || t('reservationSuccess'), 'fa-check-circle', '#10B981');
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
    showToast(t('reservationError'), 'fa-exclamation-triangle', '#EF4444');
  }
};

// Auth forms submitting
const submitLogin = async () => {
  try {
    const res = await axios.post('/api/immotok/auth/login', authForm.value);
    if (res.data.success) {
      client.value = res.data.client;
      if (res.data.token) {
        localStorage.setItem('immotok_token', res.data.token);
        axios.defaults.headers.common['Authorization'] = `Bearer ${res.data.token}`;
      }
      activeSheet.value = null;
      fetchFeed(); // Refresh the feed with the user's states!
      if (pendingAction.value) {
        const fn = pendingAction.value;
        pendingAction.value = null;
        fn();
      }
    }
  } catch (e) {
    showToast(e.response?.data?.message || t('invalidCredentials'), 'fa-exclamation-triangle', '#EF4444');
  }
};

const submitRegister = async () => {
  try {
    const res = await axios.post('/api/immotok/auth/register', registerForm.value);
    if (res.data.success) {
      client.value = res.data.client;
      if (res.data.token) {
        localStorage.setItem('immotok_token', res.data.token);
        axios.defaults.headers.common['Authorization'] = `Bearer ${res.data.token}`;
      }
      activeSheet.value = null;
      fetchFeed(); // Refresh the feed with the user's states!
      if (pendingAction.value) {
        const fn = pendingAction.value;
        pendingAction.value = null;
        fn();
      }
    }
  } catch (e) {
    const errs = e.response?.data?.errors;
    if (errs) {
      showToast(Object.values(errs)[0][0], 'fa-exclamation-triangle', '#EF4444');
    } else {
      showToast(t('registrationError'), 'fa-exclamation-triangle', '#EF4444');
    }
  }
};

// Public profile click & subscriptions
const openProfile = async (company) => {
  activeSheet.value = 'profile';
  profileCompany.value = company;
  profileSubscribersCount.value = 0;
  profileLikesCount.value = 0;
  profileHasSubscribed.value = false;
  profileIllustrations.value = [];
  try {
    const res = await axios.get(`/api/immotok/companies/${company.id}/profile`);
    if (res.data.success) {
      profileCompany.value = res.data.company;
      profileSubscribersCount.value = res.data.subscribers_count;
      profileLikesCount.value = res.data.likes_count;
      profileHasSubscribed.value = res.data.has_subscribed;
      profileIllustrations.value = res.data.illustrations;
    }
  } catch (e) {
    console.error(e);
  }
};

const toggleSubscribe = async (companyId) => {
  if (!client.value) {
    pendingAction.value = () => toggleSubscribe(companyId);
    activeSheet.value = 'auth';
    return;
  }
  const oldHasSubscribed = profileCompany.value && profileCompany.value.id === companyId ? profileHasSubscribed.value : false;
  const oldSubscribersCount = profileCompany.value && profileCompany.value.id === companyId ? profileSubscribersCount.value : 0;
  
  const firstMatch = feed.value.find(item => item.company.id === companyId);
  const isCurrentlySubbed = firstMatch ? firstMatch.has_subscribed : oldHasSubscribed;
  const newSubbed = !isCurrentlySubbed;

  if (profileCompany.value && profileCompany.value.id === companyId) {
    profileHasSubscribed.value = newSubbed;
    profileSubscribersCount.value = newSubbed ? oldSubscribersCount + 1 : oldSubscribersCount - 1;
  }
  feed.value.forEach(item => {
    if (item.company.id === companyId) {
      item.has_subscribed = newSubbed;
    }
  });

  try {
    const res = await axios.post(`/api/immotok/companies/${companyId}/subscribe`);
    if (res.data.success) {
      const subbed = res.data.subscribed;
      if (profileCompany.value && profileCompany.value.id === companyId) {
        profileHasSubscribed.value = subbed;
        profileSubscribersCount.value = res.data.subscribers_count;
      }
      feed.value.forEach(item => {
        if (item.company.id === companyId) {
          item.has_subscribed = subbed;
        }
      });
    } else {
      if (profileCompany.value && profileCompany.value.id === companyId) {
        profileHasSubscribed.value = isCurrentlySubbed;
        profileSubscribersCount.value = oldSubscribersCount;
      }
      feed.value.forEach(item => {
        if (item.company.id === companyId) {
          item.has_subscribed = isCurrentlySubbed;
        }
      });
    }
  } catch (e) {
    console.error(e);
    if (profileCompany.value && profileCompany.value.id === companyId) {
      profileHasSubscribed.value = isCurrentlySubbed;
      profileSubscribersCount.value = oldSubscribersCount;
    }
    feed.value.forEach(item => {
      if (item.company.id === companyId) {
        item.has_subscribed = isCurrentlySubbed;
      }
    });
  }
};

// Explore & Search Page handlers
const handleExploreSearch = async () => {
  exploreLoading.value = true;
  try {
    const params = {
      q: searchQuery.value,
      transaction: filterOptions.value.transaction,
      type: filterOptions.value.type,
      budget: filterOptions.value.budget,
      city: filterOptions.value.city
    };
    const res = await axios.get('/api/immotok/feed', { params });
    exploreResults.value = res.data;
  } catch (e) {
    console.error(e);
  } finally {
    exploreLoading.value = false;
  }
};

const selectTrendingTag = (tag) => {
  if (tag === 'Loyer < 500k') {
    searchQuery.value = '';
    filterOptions.value.budget = 500000;
  } else {
    searchQuery.value = tag;
  }
  handleExploreSearch();
};

const playExploreItem = (idx) => {
  feed.value = [...exploreResults.value];
  currentIndex.value = idx;
  activeTab.value = 'foryou';
};

const playProfileIllustration = async (illustrationId) => {
  if (!profileCompany.value) return;
  try {
    const res = await axios.get('/api/immotok/feed', { params: { company_id: profileCompany.value.id } });
    feed.value = res.data;
    const idx = feed.value.findIndex(item => item.id === illustrationId);
    currentIndex.value = idx >= 0 ? idx : 0;
    activeSheet.value = null; // Close profile sheet
    activeTab.value = 'foryou';
  } catch (e) {
    console.error(e);
  }
};

const openChatFromProfile = () => {
  activeSheet.value = 'chat';
  loadChatHistory();
};

const fetchCategories = async () => {
  try {
    const res = await axios.get('/api/immotok/categories');
    categories.value = res.data;
  } catch (e) {
    console.error(e);
  }
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
    pendingAction.value = () => sendChatMessage();
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
  pendingAction.value = null;
};

const navigateToHome = () => {
  resetFilters();
};

const openCreatePostHint = () => {
  activeSheet.value = 'createpost';
};

const openInbox = async () => {
  if (!client.value) {
    pendingAction.value = () => openInbox();
    activeSheet.value = 'auth';
    return;
  }
  activeSheet.value = 'inbox';
  notifsLoading.value = true;
  try {
    const res = await axios.get('/api/immotok/notifications');
    if (res.data.success) {
      notifications.value = res.data.notifications;
    }
  } catch (e) {
    console.error(e);
  } finally {
    notifsLoading.value = false;
  }
};

const openMe = async () => {
  if (!client.value) {
    pendingAction.value = () => openMe();
    activeSheet.value = 'auth';
    return;
  }
  activeSheet.value = 'myprofile';
  myProfileTab.value = 'subs';
  myProfileLoading.value = true;
  try {
    const res = await axios.get('/api/immotok/me/profile');
    if (res.data.success) {
      myProfileData.value = res.data;
    }
  } catch (e) {
    console.error(e);
  } finally {
    myProfileLoading.value = false;
  }
};

const handleNotifClick = (notif) => {
  if (!notif.is_read) {
    axios.post('/api/immotok/notifications/mark-read', { id: notif.id }).catch(() => {});
    notif.is_read = true;
    if (unreadCount.value > 0) unreadCount.value--;
  }
  activeSheet.value = null;
  if (notif.illustration_id) {
    playFavoriteItem(notif.illustration_id);
  }
};

const markAllNotifsRead = async () => {
  try {
    await axios.post('/api/immotok/notifications/mark-read', { all: true });
    notifications.value.forEach(n => n.is_read = true);
    unreadCount.value = 0;
  } catch (e) {
    console.error(e);
  }
};

const handleLogout = async () => {
  try {
    await axios.post('/api/immotok/auth/logout');
  } catch (e) {
    console.error(e);
  } finally {
    client.value = null;
    localStorage.removeItem('immotok_token');
    delete axios.defaults.headers.common['Authorization'];
    activeSheet.value = null;
    pendingAction.value = null;
    fetchFeed();
  }
};

const playFavoriteItem = async (illustrationId) => {
  closeActiveSheet();
  try {
    const res = await axios.get('/api/immotok/feed');
    feed.value = res.data;
    const idx = feed.value.findIndex(item => item.id === illustrationId);
    currentIndex.value = idx >= 0 ? idx : 0;
    activeTab.value = 'foryou';
  } catch (e) {
    console.error(e);
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

// ── Title & Favicon ───────────────────────────────────────────────────────────
const setupPageMeta = () => {
  // Titre de l'onglet
  document.title = 'Immo ToK';
  // Favicon SVG inline ImmoToK
  const existingFavicon = document.querySelector('link[rel="icon"]');
  if (existingFavicon) existingFavicon.remove();
  const svgFavicon = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
    <rect width="64" height="64" rx="14" fill="#030712"/>
    <text x="50%" y="54%" dominant-baseline="middle" text-anchor="middle"
      font-family="Arial Black,sans-serif" font-weight="900" font-size="36" fill="white">I</text>
    <text x="50%" y="54%" dominant-baseline="middle" text-anchor="middle"
      font-family="Arial Black,sans-serif" font-weight="900" font-size="36"
      fill="#3B82F6" dx="18">T</text>
  </svg>`;
  const blob = new Blob([svgFavicon], { type: 'image/svg+xml' });
  const url = URL.createObjectURL(blob);
  const link = document.createElement('link');
  link.rel = 'icon'; link.href = url;
  document.head.appendChild(link);
};

// ── Splash Screen logic ────────────────────────────────────────────────────────
const runSplash = () => {
  // Animate progress bar 0 → 100% in ~2s
  let progress = 0;
  const step = () => {
    progress += Math.random() * 18 + 8;
    if (progress >= 100) {
      splashProgress.value = 100;
      setTimeout(() => { showSplash.value = false; }, 300);
    } else {
      splashProgress.value = progress;
      setTimeout(step, 120 + Math.random() * 80);
    }
  };
  setTimeout(step, 200);
};

// ── New publication notification polling ───────────────────────────────────────
const pollNotifications = () => {
  notificationTimer = setInterval(async () => {
    try {
      const res = await axios.get('/api/immotok/notifications/unread-count');
      if (res.data && typeof res.data.count === 'number') {
          const prev = unreadCount.value;
          unreadCount.value = res.data.count;
          // Si de nouvelles notifs arrivent, afficher un toast
          if (res.data.count > prev && res.data.latest) {
            showToast('🏠 ' + res.data.latest, 'fa-bell', '#3B82F6', 4000);
          }
      }
    } catch (_) {
      // endpoint absent : on ignore silencieusement
    }
  }, 60000); // toutes les 60s
};

// LifeCycle hooks
onMounted(async () => {
  setupPageMeta();
  runSplash();
  await checkAuth();
  fetchCategories();
  fetchFeed();
  pollNotifications();
});

onUnmounted(() => {
  stopAllMedia();
  if (notificationTimer) clearInterval(notificationTimer);
  if (toastTimer) clearTimeout(toastTimer);
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

/* TIKTOK-LIKE VERTICAL SLIDE TRANSITION */
.slide-vertical-enter-active,
.slide-vertical-leave-active {
  transition: all 0.35s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.slide-vertical-enter-from {
  opacity: 0;
  transform: translateY(100%);
}

.slide-vertical-leave-to {
  opacity: 0;
  transform: translateY(-30%);
}

.slide-vertical-enter-to {
  opacity: 1;
  transform: translateY(0);
}

.slide-vertical-leave-from {
  opacity: 1;
  transform: translateY(0);
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
