<div class="panel-overlay" id="menuPanel" data-turbo-permanent>
        <div class="panel-content" id="panelContentMain">

            <!-- Left: Notifications -->
            <div class="panel-notif" id="panelNotif">
                <div class="panel-notif-header">
                    Notifikasi
                    <div style="display: flex; align-items: center; gap: 0.85rem;">
                        <button class="panel-notif-checkall" title="Tandai semua dibaca">
                            <i class='bx bx-check-double'></i>
                        </button>
                        <button class="panel-notif-deleteall" title="Hapus semua notifikasi">
                            <i class='bx bx-trash'></i>
                        </button>
                    </div>
                </div>
                <div class="panel-notif-list" id="panelNotifList">
                    @forelse ($notifications as $notif)
                        @php
                            $isUnread = is_null($notif->read_at);
                            $iconColor = '#3b82f6'; // default system
                            $iconName = 'bx-bell';
                            
                            if ($notif->type === 'learning') {
                                $iconColor = '#ea580c'; // gold-orange
                                $iconName = 'bx-book-open';
                            } elseif ($notif->type === 'schedule') {
                                $iconColor = '#0d9488'; // teal
                                $iconName = 'bx-calendar';
                            } elseif ($notif->type === 'profile') {
                                $iconColor = '#7c3aed'; // purple
                                $iconName = 'bx-user-pin';
                            }
                        @endphp
                        <div class="panel-notif-item {{ $isUnread ? 'unread' : '' }}" 
                             data-id="{{ $notif->id }}" 
                             onclick="markNotificationAsRead(this, {{ $notif->id }})"
                             style="cursor: pointer; position: relative; flex-shrink: 0;">

                            <div class="panel-notif-icon">
                                <i class="bx {{ $iconName }}" style="font-size: 1.5rem; color: {{ $iconColor }};"></i>
                            </div>
                            <div class="panel-notif-body">
                                <div class="panel-notif-title">
                                    {{ $notif->title }}
                                    @if ($isUnread)
                                        <span class="unread-dot" style="display: inline-block; width: 6px; height: 6px; border-radius: 50%; background: {{ $iconColor }}; box-shadow: 0 0 8px {{ $iconColor }};"></span>
                                    @endif
                                </div>
                                <div class="panel-notif-desc">{{ $notif->description }}</div>
                            </div>
                            <div class="panel-notif-time">{{ $notif->created_at->diffForHumans() }}</div>
                        </div>
                    @empty
                        <div class="panel-notif-empty" style="padding: 3rem 1.5rem; text-align: center; color: #615f66; display: flex; flex-direction: column; align-items: center; gap: 0.75rem;">
                            <i class="bx bx-bell-off" style="font-size: 2.5rem; color: rgba(255,255,255,0.08);"></i>
                            <div style="font-size: 0.88rem; font-weight: 500;">Belum ada notifikasi</div>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Right: Quick Actions -->
            <div class="panel-right-col">
                <div class="panel-actions">
                    <!-- Volume Slider -->
                    <div class="panel-volume-row" id="volumeRow">
                        <div class="panel-volume-track"></div>
                        <div class="panel-volume-fill" id="volumeFill">
                            <svg viewBox="0 0 24 24">
                                <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5" />
                                <path d="M19.07 4.93a10 10 0 0 1 0 14.14" />
                                <path d="M15.54 8.46a5 5 0 0 1 0 7.07" />
                            </svg>
                        </div>
                        <input type="range" class="panel-volume-input" id="volumeInput" min="0" max="100" value="60">
                    </div>

                    <!-- 2x2 Grid (Swipeable Carousel) -->
                    <div class="panel-grid">
                        <!-- PAGE 1 -->
                        <div class="panel-grid-page">
                            <button class="panel-grid-btn" id="btnAccount">
                                <div class="panel-grid-icon">
                                    <svg viewBox="0 0 24 24">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                        <circle cx="12" cy="7" r="4" />
                                    </svg>
                                </div>
                                <div class="panel-grid-text">
                                    <div class="panel-grid-label">Account</div>
                                    <div class="panel-grid-sub">lihat akun mu</div>
                                </div>
                            </button>
                            <button class="panel-grid-btn active" id="btnNotif">
                                <div class="panel-grid-icon">
                                    <svg viewBox="0 0 24 24">
                                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                                        <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                                    </svg>
                                </div>
                                <div class="panel-grid-text">
                                    <div class="panel-grid-label">Notifikasi</div>
                                    <div class="panel-grid-sub">lihat notif</div>
                                </div>
                            </button>
                            <button class="panel-grid-btn">
                                <div class="panel-grid-icon">
                                    <svg viewBox="0 0 24 24">
                                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" />
                                    </svg>
                                </div>
                                <div class="panel-grid-text">
                                    <div class="panel-grid-label">Mode</div>
                                    <div class="panel-grid-sub">terang</div>
                                </div>
                            </button>
                            <button class="panel-grid-btn" id="btnFriend">
                                <div class="panel-grid-icon" style="position: relative;">
                                    <svg viewBox="0 0 24 24">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                        <circle cx="9" cy="7" r="4" />
                                        <line x1="19" y1="8" x2="19" y2="14" />
                                        <line x1="22" y1="11" x2="16" y2="11" />
                                    </svg>
                                    <span class="friend-btn-badge" id="friendBtnBadge" style="display: none; position: absolute; top: -2px; right: -2px; width: 8px; height: 8px; border-radius: 50%; background: #ea580c; border: 1.5px solid #1c191f; box-shadow: 0 0 8px rgba(234, 88, 12, 0.6);"></span>
                                </div>
                                <div class="panel-grid-text">
                                    <div class="panel-grid-label">Friend</div>
                                    <div class="panel-grid-sub">cari teman</div>
                                </div>
                            </button>
                        </div>

                        <!-- PAGE 2 -->
                        <div class="panel-grid-page">
                            <button class="panel-grid-btn" id="btnMusic">
                                <div class="panel-grid-icon">
                                    <svg viewBox="0 0 24 24">
                                        <path d="M9 18V5l12-2v13" />
                                        <circle cx="6" cy="18" r="3" />
                                        <circle cx="18" cy="16" r="3" />
                                    </svg>
                                </div>
                                <div class="panel-grid-text">
                                    <div class="panel-grid-label">Music</div>
                                    <div class="panel-grid-sub">putar lagu</div>
                                </div>
                            </button>
                            <button class="panel-grid-btn" id="btnSetting">
                                <div class="panel-grid-icon">
                                    <svg viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="3" />
                                        <path
                                            d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z" />
                                    </svg>
                                </div>
                                <div class="panel-grid-text">
                                    <div class="panel-grid-label">Setting</div>
                                    <div class="panel-grid-sub">pengaturan</div>
                                </div>
                            </button>
                            <button class="panel-grid-btn" id="btnAbout">
                                <div class="panel-grid-icon">
                                    <svg viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10" />
                                        <line x1="12" y1="16" x2="12" y2="12" />
                                        <line x1="12" y1="8" x2="12.01" y2="8" />
                                    </svg>
                                </div>
                                <div class="panel-grid-text">
                                    <div class="panel-grid-label">About</div>
                                    <div class="panel-grid-sub">tentang web</div>
                                </div>
                            </button>
                            <button class="panel-grid-btn" id="btnBug">
                                <div class="panel-grid-icon">
                                    <svg viewBox="0 0 24 24">
                                        <path
                                            d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                                        <line x1="12" y1="9" x2="12" y2="13" />
                                        <line x1="12" y1="17" x2="12.01" y2="17" />
                                    </svg>
                                </div>
                                <div class="panel-grid-text">
                                    <div class="panel-grid-label">Lapor Bug</div>
                                    <div class="panel-grid-sub">laporkan isu</div>
                                </div>
                            </button>
                        </div>
                    </div>

                    <!-- Page dots -->
                    <div class="panel-dots">
                        <div class="panel-dot active"></div>
                        <div class="panel-dot"></div>
                    </div>
                </div>

                <!-- Music Player Component -->
                <div class="panel-music" id="panelMusic" style="display: none;">
                    <div class="music-header">
                        <div class="music-title">Music Player</div>
                        <div class="music-actions">
                            <button id="musicYtToggleBtn" class="music-action-btn" title="YouTube Link">
                                <svg viewBox="0 0 24 24">
                                    <path d="M23.498 6.163c-.272-1.017-1.074-1.819-2.091-2.091C19.56 3.53 12 3.53 12 3.53s-7.56 0-9.407.542C1.576 4.344.774 5.146.502 6.163.0 8.01.0 12 .0 12s0 3.99.502 5.837c.272 1.017 1.074 1.819 2.091 2.091 1.847.542 9.407.542 9.407.542s7.56 0 9.407-.542c1.017-.272 1.819-1.074 2.091-2.091.502-1.847.502-5.837.502-5.837s0-3.99-.502-5.837zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                </svg>
                            </button>
                            <label for="musicUpload" class="music-upload-btn" title="Upload Audio Lokal">
                                <svg viewBox="0 0 24 24">
                                    <line x1="12" y1="5" x2="12" y2="19" />
                                    <line x1="5" y1="12" x2="19" y2="12" />
                                </svg>
                            </label>
                            <input type="file" id="musicUpload" accept="audio/*" style="display: none;">
                        </div>
                    </div>
                    <div class="music-visualizer" id="musicVisualizer">
                        <div class="music-bar" style="--max: 20px;"></div>
                        <div class="music-bar" style="--max: 36px;"></div>
                        <div class="music-bar" style="--max: 56px;"></div>
                        <div class="music-bar" style="--max: 80px;"></div>
                        <div class="music-bar" style="--max: 56px;"></div>
                        <div class="music-bar" style="--max: 36px;"></div>
                        <div class="music-bar" style="--max: 20px;"></div>
                    </div>
                    <div class="music-controls">
                        <div class="music-progress-bar" id="musicProgressBar">
                            <div class="music-progress-track"></div>
                            <div class="music-progress-fill" id="musicProgressFill"></div>
                        </div>
                        <button class="music-play-btn" id="musicPlayBtn">
                            <svg viewBox="0 0 24 24" id="iconPlay">
                                <polygon points="5 3 19 12 5 21 5 3" />
                            </svg>
                            <svg viewBox="0 0 24 24" id="iconPause" style="display: none;">
                                <rect x="6" y="4" width="4" height="16" />
                                <rect x="14" y="4" width="4" height="16" />
                            </svg>
                        </button>
                    </div>
                    <audio id="audioPlayer" style="display: none;"></audio>
                    <div id="youtubePlayer" style="position: absolute; width: 1px; height: 1px; opacity: 0; pointer-events: none;"></div>
                </div>

                <!-- YouTube Link Input Field (outside music container) -->
                <div class="music-yt-input-container" id="musicYtInputContainer">
                    <div class="music-yt-input-wrapper">
                        <svg viewBox="0 0 24 24">
                            <path d="M23.498 6.163c-.272-1.017-1.074-1.819-2.091-2.091C19.56 3.53 12 3.53 12 3.53s-7.56 0-9.407.542C1.576 4.344.774 5.146.502 6.163.0 8.01.0 12 .0 12s0 3.99.502 5.837c.272 1.017 1.074 1.819 2.091 2.091 1.847.542 9.407.542 9.407.542s7.56 0 9.407-.542c1.017-.272 1.819-1.074 2.091-2.091.502-1.847.502-5.837.502-5.837s0-3.99-.502-5.837zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                        </svg>
                        <input type="text" id="musicYtInput" placeholder="Masukkan link YouTube...">
                    </div>
                    <button id="musicYtSubmit" class="music-yt-btn">
                        Play
                    </button>
                </div>

                <!-- Friend Search Component -->
                <div class="panel-friend" id="panelFriend" style="display: none;">
                    <div class="friend-search-container" style="display: flex; align-items: center; gap: 0.5rem; width: 100%;">
                        <div class="friend-search" style="flex: 1;">
                            <svg viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="8" />
                                <line x1="21" y1="21" x2="16.65" y2="16.65" />
                            </svg>
                            <input type="text" id="friendSearchInput" placeholder="cari nama teman...">
                        </div>
                        <button class="friend-expand-btn" id="btnFriendExpand" title="Perbesar Layar" type="button" style="width: 42px; height: 42px; border-radius: 50%; border: 1px solid rgba(255, 255, 255, 0.05); background: #151419; color: #8e8c94; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s ease;">
                            <i class='bx bx-expand' style="font-size: 1.25rem;"></i>
                        </button>
                    </div>

                    <div class="friend-list" id="friendListContainer">
                        <!-- Rendered dynamically -->
                    </div>
                </div>

                <!-- Account Component -->
                @php
                    $menuUserTier = auth()->user()->tier ?? 'Initiate';
                    $menuTierColors = [
                        'Initiate' => '168, 162, 158',
                        'Explorer' => '34, 197, 94',
                        'Operator' => '59, 130, 246',
                        'Technician' => '139, 92, 246',
                        'Specialist' => '236, 72, 153',
                        'Professional' => '239, 68, 68',
                        'Senior Professional' => '249, 115, 22',
                        'Lead Engineer' => '234, 179, 8',
                        'Architect' => '6, 182, 212',
                        'Principal' => '15, 118, 110',
                        'Strategist' => '225, 29, 72',
                        'Visionary' => '218, 165, 32',
                    ];
                    $menuRgbColor = $menuTierColors[$menuUserTier] ?? '168, 162, 158';
                @endphp
                <div class="panel-account" id="panelAccount" style="display: none;">
                    <div class="account-header">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=random" alt="Avatar"
                            class="account-avatar" style="border: 2.5px solid rgba({{ $menuRgbColor }}, 0.4); border-radius: 50%; object-fit: cover;">
                        <div class="account-info">
                            <div class="account-name">{{ auth()->user()->name }}</div>
                            <div class="account-email">{{ auth()->user()->email }}</div>
                            <x-user-achievements :user="auth()->user()" />
                            <div class="account-badge user-tier-display" style="background: rgba({{ $menuRgbColor }}, 0.8)">{{ $menuUserTier }}</div>
                        </div>
                    </div>

                    <div class="account-stats">
                        <div class="stat-item">
                            <div class="stat-value">LV. {{ auth()->user()->level }}</div>
                            <div class="stat-label">Level</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">{{ number_format(auth()->user()->exp ?? 0, 0, ',', '.') }}</div>
                            <div class="stat-label">EXP</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value" id="accountFriendsCount">{{ auth()->user()->friends()->count() }}</div>
                            <div class="stat-label">Friends</div>
                        </div>
                    </div>

                    <div class="account-actions">
                        <button class="btn-account-outline" onclick="window.location.href='{{ route('profile.edit') }}'">Edit Profile</button>
                        <button class="btn-account-primary" onclick="const btnSetting = document.getElementById('btnSetting'); if(btnSetting) btnSetting.click();">Settings</button>
                    </div>
                </div>

                <!-- Setting Component -->
                <div class="panel-setting" id="panelSetting" style="display: none;">
                    <div class="setting-section-title">Tampilan</div>
                    <div class="setting-row">
                        <div class="setting-row-left">
                            <div class="setting-row-icon">
                                <svg viewBox="0 0 24 24">
                                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" />
                                </svg>
                            </div>
                            <div>
                                <div class="setting-row-label">Mode Gelap</div>
                                <div class="setting-row-sub">Tema antarmuka</div>
                            </div>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    <div class="setting-row">
                        <div class="setting-row-left">
                            <div class="setting-row-icon">
                                <svg viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="4" />
                                    <path
                                        d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41" />
                                </svg>
                            </div>
                            <div>
                                <div class="setting-row-label">Animasi</div>
                                <div class="setting-row-sub">Efek visual</div>
                            </div>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>

                    <div class="setting-section-title">Notifikasi</div>
                    <div class="setting-row">
                        <div class="setting-row-left">
                            <div class="setting-row-icon">
                                <svg viewBox="0 0 24 24">
                                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                                    <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                                </svg>
                            </div>
                            <div>
                                <div class="setting-row-label">Push Notif</div>
                                <div class="setting-row-sub">Peringatan real-time</div>
                            </div>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    <div class="setting-row">
                        <div class="setting-row-left">
                            <div class="setting-row-icon">
                                <svg viewBox="0 0 24 24">
                                    <path
                                        d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                                    <polyline points="22,6 12,13 2,6" />
                                </svg>
                            </div>
                            <div>
                                <div class="setting-row-label">Email Digest</div>
                                <div class="setting-row-sub">Ringkasan mingguan</div>
                            </div>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox">
                            <span class="toggle-slider"></span>
                        </label>
                    </div>

                    <div class="setting-section-title">Privasi</div>
                    <div class="setting-row">
                        <div class="setting-row-left">
                            <div class="setting-row-icon">
                                <svg viewBox="0 0 24 24">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                            </div>
                            <div>
                                <div class="setting-row-label">Profil Publik</div>
                                <div class="setting-row-sub">Bisa dilihat semua orang</div>
                            </div>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>

                    <button class="btn-setting-logout">
                        <svg viewBox="0 0 24 24">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                            <polyline points="16 17 21 12 16 7" />
                            <line x1="21" y1="12" x2="9" y2="12" />
                        </svg>
                        Keluar / Logout
                    </button>
                </div>

                <!-- About Component -->
                <div class="panel-about" id="panelAbout" style="display: none;">
                    <div class="about-logo-row">
                        <div class="about-logo">🚀</div>
                        <div>
                            <div class="about-app-name">TurnCode</div>
                            <div class="about-version">Version 1.0.0 — Stable Release</div>
                        </div>
                    </div>
                    <p class="about-desc">Platform belajar coding interaktif yang dirancang untuk membantu developer
                        tumbuh lebih cepat dengan materi, tantangan, dan komunitas.</p>
                    <div class="about-stack">
                        <span class="about-badge">Laravel 11</span>
                        <span class="about-badge">Blade</span>
                        <span class="about-badge">Vite</span>
                        <span class="about-badge">Web Audio API</span>
                        <span class="about-badge">IndexedDB</span>
                    </div>
                    <div class="about-divider"></div>
                    <div class="about-links">
                        <a href="#" class="about-link-row">
                            <div class="about-link-left">
                                <svg viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" />
                                    <line x1="12" y1="8" x2="12" y2="12" />
                                    <line x1="12" y1="16" x2="12.01" y2="16" />
                                </svg>
                                <span class="about-link-label">Dokumentasi</span>
                            </div>
                            <svg class="about-link-chevron" viewBox="0 0 24 24">
                                <polyline points="9 18 15 12 9 6" />
                            </svg>
                        </a>
                        <a href="#" class="about-link-row">
                            <div class="about-link-left">
                                <svg viewBox="0 0 24 24">
                                    <path
                                        d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22" />
                                </svg>
                                <span class="about-link-label">GitHub Repository</span>
                            </div>
                            <svg class="about-link-chevron" viewBox="0 0 24 24">
                                <polyline points="9 18 15 12 9 6" />
                            </svg>
                        </a>
                    </div>
                    <div class="about-copyright">© 2025 TurnCode. Dibuat dengan ❤️ di Indonesia.</div>
                </div>
            </div>

        </div>

        <!-- Bug Report Component (Outside panel-content) -->
        <div class="bug-report-wrapper" id="bugReportWrapper">
            <div class="bug-slides-container">
                <!-- Slide 1 -->
                <div class="bug-slide bug-slide-1">
                    <svg class="bug-prompt-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                    </svg>
                    <div class="bug-prompt-title">apakah kamu mau<br>melaporkan masalah</div>
                    <div class="bug-actions">
                        <button type="button" class="bug-btn bug-btn-no" id="btnBugClose">Tidak</button>
                        <button type="button" class="bug-btn bug-btn-yes" id="btnBugNext">Laporkan</button>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div class="bug-slide bug-slide-2" style="padding: 2rem;">
                    <div class="bug2-layout">
                        <!-- Left Column -->
                        <div class="bug2-left">
                            <div class="bug2-header-box">
                                <p>Apa masalah yang<br>kamu alami saat ini?</p>
                            </div>
                            <label class="bug-upload-area" style="flex: 1; border-radius: 16px;">
                                <svg viewBox="0 0 24 24" width="40" height="40" stroke="#8e8c94" stroke-width="1.5"
                                    fill="none" style="margin-bottom:0.5rem;">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                    <polyline points="21 15 16 10 5 21"></polyline>
                                </svg>
                                <span style="font-size: 0.85rem; text-align: center;">Upload gambar bila ada atau
                                    drop<br>disini</span>
                                <input type="file" style="display:none" accept="image/*">
                            </label>
                        </div>

                        <!-- Right Column -->
                        <div class="bug2-right">
                            <div class="bug2-title-row">
                                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                </svg>
                                <p>Berani lapor demi kemajuan Turning Code!!,<br>kami siap selesaikan secepat mungkin
                                </p>
                            </div>

                            <div class="bug-input-group">
                                <label class="bug-label" style="color: #e8e8e8; font-size: 0.9rem;">Judul
                                    laporan</label>
                                <input type="text" class="bug-input" placeholder="isi dengan topik masalah nya"
                                    style="background: rgba(255,255,255,0.03); border: none;">
                            </div>

                            <div class="bug-input-group" style="flex:1;">
                                <label class="bug-label" style="color: #e8e8e8; font-size: 0.9rem;">Deskripsi
                                    Laporan</label>
                                <textarea class="bug-textarea" placeholder="Isi keluhan yang kamu rasakan"
                                    style="background: rgba(255,255,255,0.03); border: none; flex:1;"></textarea>
                            </div>

                            <div class="bug-actions" style="margin-top: 0.5rem;">
                                <button type="button" class="bug-btn bug-btn-no" id="btnBugPrev">Batalkan</button>
                                <button type="button" class="bug-btn bug-btn-yes" id="btnBugSubmit">Laporkan</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
    <script>
        function updateNavUnreadDot() {
            const hasUnread = document.querySelectorAll('.panel-notif-item.unread').length > 0;
            const navDots = document.querySelectorAll('.nav-unread-dot');
            navDots.forEach(dot => {
                if (hasUnread) {
                    dot.style.display = 'block';
                    // force reflow
                    dot.offsetHeight;
                    dot.style.opacity = '1';
                    dot.style.transform = 'scale(1)';
                } else {
                    dot.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    dot.style.opacity = '0';
                    dot.style.transform = 'scale(0)';
                    setTimeout(() => {
                        dot.style.display = 'none';
                    }, 300);
                }
            });
        }

        function markNotificationAsRead(element, id) {
            if (!element.classList.contains('unread')) return;

            const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
            const token = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';

            fetch(`/api/notifications/${id}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    element.classList.remove('unread');
                    element.style.opacity = '';

                    const dot = element.querySelector('.unread-dot');
                    if (dot) {
                        dot.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                        dot.style.opacity = '0';
                        dot.style.transform = 'scale(0)';
                        setTimeout(() => dot.remove(), 300);
                    }
                    
                    updateNavUnreadDot();
                }
            })
            .catch(error => console.error('Error marking notification as read:', error));
        }

        function initMenuPanelJs() {
            const checkAllBtn = document.querySelector('.panel-notif-checkall');
            if (checkAllBtn) {
                checkAllBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
                    const token = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';

                    const unreadItems = document.querySelectorAll('.panel-notif-item.unread');
                    if (unreadItems.length === 0) return;

                    fetch('/api/notifications/read-all', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            unreadItems.forEach(item => {
                                item.classList.remove('unread');
                                item.style.opacity = '';

                                const dot = item.querySelector('.unread-dot');
                                if (dot) {
                                    dot.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                                    dot.style.opacity = '0';
                                    dot.style.transform = 'scale(0)';
                                    setTimeout(() => dot.remove(), 300);
                                }
                            });
                            
                            updateNavUnreadDot();
                        }
                    })
                    .catch(error => console.error('Error marking all notifications as read:', error));
                });
            }

            const deleteAllBtn = document.querySelector('.panel-notif-deleteall');
            const confirmOverlay = document.getElementById('notifConfirmOverlay');
            const confirmCancel = document.getElementById('notifConfirmCancel');
            const confirmDelete = document.getElementById('notifConfirmDelete');

            function closeConfirmPopup() {
                if (confirmOverlay) confirmOverlay.classList.remove('active');
            }

            if (deleteAllBtn && confirmOverlay) {
                deleteAllBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const items = document.querySelectorAll('.panel-notif-item');
                    if (items.length === 0) return;

                    confirmOverlay.classList.add('active');
                });
            }

            if (confirmCancel) {
                confirmCancel.addEventListener('click', function (e) {
                    e.preventDefault();
                    closeConfirmPopup();
                });
            }

            if (confirmOverlay) {
                confirmOverlay.addEventListener('click', function (e) {
                    if (e.target === confirmOverlay) closeConfirmPopup();
                });
            }

            if (confirmDelete) {
                confirmDelete.addEventListener('click', function (e) {
                    e.preventDefault();
                    closeConfirmPopup();

                    const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
                    const token = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';

                    fetch('/api/notifications/delete-all', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const notifList = document.getElementById('panelNotifList');
                            if (notifList) {
                                const items = notifList.querySelectorAll('.panel-notif-item');
                                if (items.length > 0) {
                                    items.forEach(item => {
                                        item.style.transition = 'all 0.3s ease';
                                        item.style.opacity = '0';
                                        item.style.transform = 'scale(0.9)';
                                    });

                                    setTimeout(() => {
                                        notifList.innerHTML = `
                                            <div class="panel-notif-empty" style="padding: 3rem 1.5rem; text-align: center; color: #615f66; display: flex; flex-direction: column; align-items: center; gap: 0.75rem;">
                                                <i class="bx bx-bell-off" style="font-size: 2.5rem; color: rgba(255,255,255,0.08);"></i>
                                                <div style="font-size: 0.88rem; font-weight: 500;">Belum ada notifikasi</div>
                                            </div>
                                        `;
                                        updateNavUnreadDot();
                                    }, 300);
                                }
                            }
                        }
                    })
                    .catch(error => console.error('Error deleting all notifications:', error));
                });
            }
        }

        document.addEventListener('turbo:load', initMenuPanelJs);
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initMenuPanelJs);
        } else {
            initMenuPanelJs();
        }
    </script>
</div>

<!-- Custom Confirm Popup for Delete All Notifications -->
<div class="notif-confirm-overlay" id="notifConfirmOverlay">
    <div class="notif-confirm-box">
        <div class="notif-confirm-header">
            <i class='bx bx-trash'></i>
            <div class="notif-confirm-title">Hapus Semua Notifikasi?</div>
        </div>
        <div class="notif-confirm-desc">Semua notifikasi akan dihapus secara permanen dan tidak dapat dikembalikan.</div>
        <div class="notif-confirm-actions">
            <button class="notif-confirm-btn notif-confirm-cancel" id="notifConfirmCancel">Batal</button>
            <button class="notif-confirm-btn notif-confirm-delete" id="notifConfirmDelete">Hapus Semua</button>
        </div>
    </div>
</div>

<!-- Friendship Hub Overlay Modal (Layar Lebih Besar) -->
<div class="friend-hub-overlay" id="friendHubModal" style="display: none;">
    <div class="friend-hub-glass">
        <div class="friend-hub-header">
            <div class="friend-hub-title">
                <i class='bx bx-group' style="font-size: 1.4rem; color: #ea580c;"></i>
                <span>Pusat Pertemanan</span>
            </div>
            <button class="friend-hub-close-btn" id="btnFriendHubClose" title="Tutup">&times;</button>
        </div>
        
        <div class="friend-hub-body">
            <!-- Left Side: Daftar Teman -->
            <div class="friend-hub-left">
                <div class="friend-hub-section-header">
                    <h3>Daftar Teman</h3>
                </div>
                <div class="friend-hub-search">
                    <i class='bx bx-search'></i>
                    <input type="text" id="friendHubSearchInput" placeholder="Cari nama teman...">
                </div>
                <div class="friend-hub-scroll" id="friendHubList">
                    <!-- Rendered dynamically in JS -->
                </div>
            </div>
            
            <!-- Right Side: Permintaan & Rekomendasi/Global Search -->
            <div class="friend-hub-right">
                <div class="friend-hub-tabs">
                    <button class="friend-hub-tab-btn active" id="tabFriendRequests">
                        <i class='bx bx-user-plus'></i>
                        <span>Permintaan Masuk</span>
                        <span class="friend-hub-tab-badge" id="friendHubRequestBadge" style="display: none;">0</span>
                    </button>
                    <button class="friend-hub-tab-btn" id="tabFindFriends">
                        <i class='bx bx-search-alt'></i>
                        <span>Cari Orang Lain</span>
                    </button>
                </div>
                
                <div class="friend-hub-tab-content" id="friendHubTabContent">
                    <!-- Rendered dynamically in JS -->
                </div>
            </div>
        </div>
    </div>
</div>

