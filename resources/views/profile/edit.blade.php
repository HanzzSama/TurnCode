@php
    $userTier = auth()->user()->tier ?? 'Initiate';
    $tierColors = [
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
    $rgbColor = $tierColors[$userTier] ?? '168, 162, 158';
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Profile - TurnCode</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    @include('layouts.transition-head')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        .profile-container-grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 2rem;
            margin-bottom: 3rem;
            align-items: start;
        }

        @media (max-width: 992px) {
            .profile-container-grid {
                grid-template-columns: 1fr;
            }
        }

        .profile-header {
            margin-bottom: 2rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .profile-title {
            font-size: 2rem;
            font-weight: 800;
            color: white;
        }

        .profile-subtitle {
            font-size: 0.9rem;
            color: #8b8591;
        }

        /* Card styles */
        .profile-card {
            background: #1e1c22;
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.04);
            padding: 2rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, border-color 0.3s ease;
        }

        .profile-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, rgba(167, 139, 250, 0.6) 0%, rgba(59, 130, 246, 0.6) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .profile-card:hover::before {
            opacity: 1;
        }

        /* Avatar Info Card Left */
        .avatar-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 1.5rem;
            padding: 1rem 0;
        }

        .large-avatar-wrapper {
            position: relative;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            padding: 4px;
            background: linear-gradient(135deg, rgba(167, 139, 250, 0.5) 0%, rgba(59, 130, 246, 0.5) 100%);
            box-shadow: 0 8px 24px rgba(167, 139, 250, 0.2);
        }

        .large-avatar {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #1e1c22;
        }

        .avatar-tier-badge {
            position: absolute;
            bottom: -6px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba({{ $rgbColor }}, 1);
            color: white;
            font-size: 0.72rem;
            font-weight: 800;
            padding: 0.3rem 0.9rem;
            border-radius: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }

        .user-details {
            margin-top: 0.5rem;
        }

        .user-display-name {
            font-size: 1.4rem;
            font-weight: 800;
            color: white;
            line-height: 1.2;
        }

        .user-display-email {
            font-size: 0.85rem;
            color: #8b8591;
            margin-top: 0.25rem;
        }

        .profile-divider {
            width: 100%;
            height: 1px;
            background: rgba(255, 255, 255, 0.05);
            margin: 1.5rem 0;
        }

        .profile-stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            width: 100%;
        }

        .profile-stat-box {
            text-align: center;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.04);
            border-radius: 16px;
            padding: 0.8rem 0.5rem;
        }

        .profile-stat-val {
            font-size: 1.1rem;
            font-weight: 800;
            color: white;
        }

        .profile-stat-lbl {
            font-size: 0.7rem;
            color: #6b6570;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 0.2rem;
        }

        /* Right Forms Columns */
        .forms-column {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .form-section-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: white;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-section-title i {
            color: #a78bfa;
            font-size: 1.3rem;
        }

        .form-section-desc {
            font-size: 0.85rem;
            color: #8b8591;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: #d1d5db;
            margin-bottom: 0.5rem;
            letter-spacing: 0.3px;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: white;
            padding: 0.8rem 1rem;
            border-radius: 14px;
            font-size: 0.92rem;
            transition: all 0.3s ease;
            width: 100%;
            outline: none;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(167, 139, 250, 0.4);
            box-shadow: 0 0 12px rgba(167, 139, 250, 0.15);
        }

        .form-control::placeholder {
            color: #4b5563;
        }

        /* Buttons styling */
        .btn-profile-save {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.8rem 1.75rem;
            border-radius: 14px;
            font-size: 0.9rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            width: fit-content;
        }

        @media (max-width: 576px) {
            .btn-profile-save {
                width: 100%;
            }
        }

        .btn-profile-primary {
            background: linear-gradient(135deg, #a78bfa 0%, #7c3aed 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(124, 58, 237, 0.3);
        }

        .btn-profile-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(124, 58, 237, 0.45);
        }

        .btn-profile-danger {
            background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
        }

        .btn-profile-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.45);
        }

        /* Back button styling */
        .btn-back-header {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #8b8591;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.06);
            padding: 0.5rem 1rem;
            border-radius: 12px;
            width: fit-content;
            margin-bottom: 1.5rem;
            transition: all 0.2s ease;
        }

        .btn-back-header:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.15);
            color: white;
            transform: translateX(-4px);
        }

        /* Alert notifications */
        .alert-profile {
            padding: 1rem 1.25rem;
            border-radius: 16px;
            margin-bottom: 1.5rem;
            font-size: 0.88rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            animation: slideDown 0.3s ease forwards;
        }

        .alert-profile-success {
            background: rgba(16, 185, 129, 0.08);
            border: 1px solid rgba(16, 185, 129, 0.2);
            color: #34d399;
        }

        .alert-profile-error {
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #f87171;
        }

        .alert-icon {
            font-size: 1.2rem;
            margin-top: 1px;
            flex-shrink: 0;
        }

        .alert-content {
            flex: 1;
        }

        .error-list {
            margin: 0.25rem 0 0 0;
            padding-left: 1.25rem;
        }

        /* Glassmorphic delete modal */
        .delete-modal-overlay {
            position: fixed;
            inset: 0;
            z-index: 1100;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .delete-modal-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .delete-modal-glass {
            background: rgba(30, 28, 34, 0.85);
            border: 1px solid rgba(239, 68, 68, 0.2);
            border-radius: 28px;
            width: 100%;
            max-width: 500px;
            padding: 2rem;
            box-shadow: 0 16px 48px rgba(0, 0, 0, 0.5);
            transform: scale(0.9) translateY(20px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .delete-modal-overlay.show .delete-modal-glass {
            transform: scale(1) translateY(0);
        }

        .delete-modal-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .delete-modal-header i {
            color: #ef4444;
            font-size: 1.8rem;
        }

        .delete-modal-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: white;
        }

        .delete-modal-desc {
            font-size: 0.88rem;
            color: #8b8591;
            line-height: 1.5;
            margin-bottom: 1.5rem;
        }

        .delete-modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }

        .btn-modal-cancel {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: white;
            padding: 0.7rem 1.25rem;
            border-radius: 12px;
            font-size: 0.88rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-modal-cancel:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.15);
        }
    </style>
</head>

<body>
    <!-- FIXED NAVBAR -->
    <nav class="fixed-navbar">
        <div class="navbar-left">
            <div class="navbar-avatar" style="background: url('https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random') center/cover; border: 2.5px solid rgba({{ $rgbColor }}, 0.4);"></div>
            <div class="navbar-user-info">
                <div class="navbar-username">{{ $user->name }}</div>
                <div class="navbar-role" style="display: flex; align-items: center; gap: 4px;">
                    <span style="background: rgba({{ $rgbColor }}, 0.15); color: rgb({{ $rgbColor }}); font-size: 0.7rem; font-weight: 700; padding: 1px 6px; border-radius: 6px; border: 1px solid rgba({{ $rgbColor }}, 0.3);">LV. {{ $user->level }}</span>
                    <span class="user-tier-display">{{ $userTier }}</span>
                </div>
            </div>
        </div>
        <div class="navbar-right">
            <button class="navbar-menu-btn" id="navMenuBtn" style="position: relative;">
                <i class='bx bx-grid-alt'></i>
                @php
                    $unreadNotificationsCount = isset($notifications) ? $notifications->whereNull('read_at')->count() : 0;
                @endphp
                <span class="nav-unread-dot" style="position: absolute; top: 12px; right: 12px; width: 8px; height: 8px; background: #ef4444; border-radius: 50%; box-shadow: 0 0 8px #ef4444; transition: opacity 0.3s ease, transform 0.3s ease; {{ $unreadNotificationsCount > 0 ? '' : 'display: none; opacity: 0; transform: scale(0);' }}"></span>
            </button>
        </div>
    </nav>
    @include('partials.menu-panel')

    <div class="container">
        <!-- Back Button -->
        <a href="{{ route('dashboard') }}" class="btn-back-header">
            <i class='bx bx-arrow-back'></i> Kembali ke Dashboard
        </a>

        <!-- Header -->
        <div class="profile-header">
            <div class="profile-title">Profil Pengguna</div>
            <div class="profile-subtitle">Atur detail akun, kredensial keamanan, dan pantau status belajarmu</div>
        </div>

        <div class="profile-container-grid">
            <!-- Left Info Card -->
            <div class="profile-card">
                <div class="avatar-section">
                    <div class="large-avatar-wrapper">
                        <img src="https://i.pinimg.com/736x/8f/a0/0b/8fa00b3f5e55e3734ec62624505370f1.jpg"
                            alt="Avatar" class="large-avatar">
                        <div class="avatar-tier-badge" style="background: rgba({{ $rgbColor }}, 1)">
                            {{ $userTier }}
                        </div>
                    </div>

                    <div class="user-details">
                        <div class="user-display-name">{{ $user->name }}</div>
                        <div class="user-display-email">{{ $user->email }}</div>
                    </div>

                    <div class="profile-divider"></div>

                    <!-- Level and EXP info -->
                    <div class="profile-stats-grid">
                        <div class="profile-stat-box">
                            <div class="profile-stat-val">{{ number_format($user->exp ?? 0, 0, ',', '.') }}</div>
                            <div class="profile-stat-lbl">Total EXP</div>
                        </div>
                        <div class="profile-stat-box">
                            <div class="profile-stat-val">LV. {{ $user->level }}</div>
                            <div class="profile-stat-lbl">Level</div>
                        </div>
                        <div class="profile-stat-box">
                            <div class="profile-stat-val">{{ number_format($user->next_tier_exp ?? 100, 0, ',', '.') }}</div>
                            <div class="profile-stat-lbl">Next Tier</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Forms Column -->
            <div class="forms-column">
                <!-- Profile Information Card -->
                <div class="profile-card">
                    <div class="form-section-title">
                        <i class='bx bx-user-circle'></i>
                        Detail Profil
                    </div>
                    <div class="form-section-desc">Perbarui nama pengguna dan alamat email Anda untuk memastikan keakuratan informasi akun.</div>

                    <!-- Alert Success -->
                    @if (session('status') === 'profile-updated')
                        <div class="alert-profile alert-profile-success">
                            <i class='bx bx-check-circle alert-icon'></i>
                            <div class="alert-content">
                                <strong>Sukses!</strong> Detail profil Anda telah berhasil diperbarui.
                            </div>
                        </div>
                    @endif

                    <!-- Alert Error -->
                    @if ($errors->any())
                        <div class="alert-profile alert-profile-error">
                            <i class='bx bx-error-circle alert-icon'></i>
                            <div class="alert-content">
                                <strong>Gagal memperbarui profil!</strong> Silakan periksa kembali isian form berikut:
                                <ul class="error-list">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <form method="post" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')

                        <div class="form-group">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" id="name" name="name" class="form-control" 
                                value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">Alamat Email</label>
                            <input type="email" id="email" name="email" class="form-control" 
                                value="{{ old('email', $user->email) }}" required autocomplete="username">

                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                <div style="margin-top: 0.75rem; font-size: 0.85rem; color: #f87171;">
                                    Alamat email Anda belum terverifikasi.
                                    <button form="send-verification" class="btn-resend" style="background:none; border:none; text-decoration:underline; color:#a78bfa; cursor:pointer; font-size: inherit; padding: 0;">
                                        Kirim ulang email verifikasi.
                                    </button>
                                </div>
                            @endif
                        </div>

                        <button type="submit" class="btn-profile-save btn-profile-primary">
                            <i class='bx bx-save'></i> Simpan Perubahan
                        </button>
                    </form>

                    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                        @csrf
                    </form>
                </div>

                <!-- Update Password Card -->
                <div class="profile-card">
                    <div class="form-section-title">
                        <i class='bx bx-key'></i>
                        Keamanan & Kata Sandi
                    </div>
                    <div class="form-section-desc">Gunakan kombinasi kata sandi yang kuat dan aman untuk melindungi akun Anda dari akses tidak sah.</div>

                    <!-- Alert Success for password -->
                    @if (session('status') === 'password-updated')
                        <div class="alert-profile alert-profile-success">
                            <i class='bx bx-check-circle alert-icon'></i>
                            <div class="alert-content">
                                <strong>Sukses!</strong> Kata sandi Anda telah berhasil diperbarui.
                            </div>
                        </div>
                    @endif

                    <!-- Alert Error for password -->
                    @if ($errors->updatePassword->any())
                        <div class="alert-profile alert-profile-error">
                            <i class='bx bx-error-circle alert-icon'></i>
                            <div class="alert-content">
                                <strong>Gagal memperbarui kata sandi!</strong> Silakan periksa kembali isian form berikut:
                                <ul class="error-list">
                                    @foreach ($errors->updatePassword->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <form method="post" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')

                        <div class="form-group">
                            <label for="current_password" class="form-label">Kata Sandi Saat Ini</label>
                            <input type="password" id="current_password" name="current_password" class="form-control" 
                                placeholder="••••••••" required autocomplete="current-password">
                        </div>

                        <div class="form-group">
                            <label for="password" class="form-label">Kata Sandi Baru</label>
                            <input type="password" id="password" name="password" class="form-control" 
                                placeholder="Minimal 8 karakter" required autocomplete="new-password">
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi Baru</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" 
                                placeholder="Masukkan kembali kata sandi baru" required autocomplete="new-password">
                        </div>

                        <button type="submit" class="btn-profile-save btn-profile-primary">
                            <i class='bx bx-key'></i> Perbarui Kata Sandi
                        </button>
                    </form>
                </div>

                <!-- Danger Zone Card -->
                <div class="profile-card" style="border-color: rgba(239, 68, 68, 0.15);">
                    <div class="form-section-title" style="color: #ef4444;">
                        <i class='bx bx-shield-quarter'></i>
                        Danger Zone
                    </div>
                    <div class="form-section-desc">Setelah Anda menghapus akun, semua data, progres belajar, sertifikat, dan aset digital akan dihapus secara permanen dan tidak dapat dipulihkan.</div>

                    <button type="button" class="btn-profile-save btn-profile-danger" id="btnOpenDeleteModal">
                        <i class='bx bx-trash'></i> Hapus Akun Saya
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal for Account Deletion -->
    <div class="delete-modal-overlay" id="deleteAccountModal">
        <div class="delete-modal-glass">
            <div class="delete-modal-header">
                <i class='bx bx-error-circle'></i>
                <div class="delete-modal-title">Hapus Akun Permanen?</div>
            </div>
            <div class="delete-modal-desc">
                Apakah Anda benar-benar yakin ingin menghapus akun TurnCode Anda? Semua data Anda akan dihapus secara permanen. Silakan masukkan kata sandi Anda untuk mengonfirmasi tindakan ini.
            </div>

            <!-- Error alert inside modal if deletion fails -->
            @if ($errors->userDeletion->any())
                <div class="alert-profile alert-profile-error" style="margin-bottom: 1rem; padding: 0.75rem 1rem;">
                    <i class='bx bx-error-circle alert-icon'></i>
                    <div class="alert-content">
                        @foreach ($errors->userDeletion->all() as $error)
                            {{ $error }}
                        @endforeach
                    </div>
                </div>
            @endif

            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')

                <div class="form-group" style="margin-bottom: 0;">
                    <label for="delete_password" class="form-label">Masukkan Password Konfirmasi</label>
                    <input type="password" id="delete_password" name="password" class="form-control" 
                        placeholder="••••••••" required>
                </div>

                <div class="delete-modal-actions">
                    <button type="button" class="btn-modal-cancel" id="btnCancelDelete">Batal</button>
                    <button type="submit" class="btn-profile-save btn-profile-danger" style="padding: 0.7rem 1.25rem; font-size: 0.88rem; border-radius: 12px; margin-top: 0;">
                        Ya, Hapus Akun
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- BOTTOM-LEFT PAGE NAV MENU -->
    <div class="page-nav" id="pageNav">
        <div class="page-nav-items" id="pageNavItems">
            <a href="{{ route('dashboard') }}" class="page-nav-item">Dashboard</a>
            <a href="{{ route('jadwal') }}" class="page-nav-item">Jadwal</a>
            <a href="{{ route('history') }}" class="page-nav-item">History</a>
            <a href="#" class="page-nav-item" onclick="event.preventDefault(); document.getElementById('logoutFormNav').submit();" style="color: #ef4444; border-color: rgba(239, 68, 68, 0.15); background: rgba(239, 68, 68, 0.03);">
                <i class='bx bx-log-out' style="margin-right: 6px; font-size: 1.15rem; vertical-align: middle;"></i>Log Out
            </a>
        </div>
        <form method="POST" action="{{ route('logout') }}" id="logoutFormNav" style="display: none;">
            @csrf
        </form>
        <button class="page-nav-btn" id="pageNavBtn" aria-label="Menu halaman">
            <span class="page-nav-line"></span>
            <span class="page-nav-line"></span>
            <span class="page-nav-line"></span>
        </button>
    </div>

    <script src="{{ asset('js/layout.js') }}"></script>
    <script src="{{ asset('js/panel.js') }}"></script>
    <script>
        function initProfileJs() {
            // Modal Delete Account Interactions
            const btnOpenDeleteModal = document.getElementById('btnOpenDeleteModal');
            const deleteAccountModal = document.getElementById('deleteAccountModal');
            const btnCancelDelete = document.getElementById('btnCancelDelete');

            if (btnOpenDeleteModal && deleteAccountModal && btnCancelDelete) {
                btnOpenDeleteModal.addEventListener('click', function () {
                    deleteAccountModal.classList.add('show');
                });

                btnCancelDelete.addEventListener('click', function () {
                    deleteAccountModal.classList.remove('show');
                });

                // Close modal when click outside of modal card
                deleteAccountModal.addEventListener('click', function (e) {
                    if (e.target === deleteAccountModal) {
                        deleteAccountModal.classList.remove('show');
                    }
                });
            }

            // If there was an error in account deletion validation, open the modal immediately
            @if ($errors->userDeletion->isNotEmpty())
                if (deleteAccountModal) {
                    deleteAccountModal.classList.add('show');
                }
            @endif
        }

        if (!window.profileTurboBound) {
            window.profileTurboBound = true;
            document.addEventListener('turbo:load', function() {
                if (typeof initProfileJs === 'function') initProfileJs();
            });
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function() {
                    if (typeof initProfileJs === 'function') initProfileJs();
                });
            } else {
                if (typeof initProfileJs === 'function') initProfileJs();
            }
        }
    </script>
</body>

</html>
