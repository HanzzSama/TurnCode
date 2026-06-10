<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Administrator - TurningCode</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #08090c;
            --sidebar-bg: rgba(14, 16, 26, 0.7);
            --card-bg: rgba(22, 24, 35, 0.45);
            --border-glow: rgba(212, 175, 55, 0.08);
            --primary-gold: #d4af37;
            --primary-gold-rgb: 212, 175, 55;
            --text-color: #f3f4f6;
            --text-muted: #9ca3af;
            --table-header-bg: rgba(10, 11, 16, 0.6);
            --error-red: #ef4444;
            --success-green: #10b981;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background-color: var(--bg-color);
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(212, 175, 55, 0.03) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(212, 175, 55, 0.02) 0%, transparent 40%);
            min-height: 100vh;
            color: var(--text-color);
            display: flex;
            overflow-x: hidden;
        }

        /* SIDEBAR */
        .sidebar {
            width: 280px;
            background: var(--sidebar-bg);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border-right: 1px solid rgba(255, 255, 255, 0.06);
            padding: 40px 24px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
        }

        .brand {
            font-size: 24px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: 0.5px;
            margin-bottom: 50px;
        }

        .brand span {
            color: var(--primary-gold);
            text-shadow: 0 0 15px rgba(var(--primary-gold-rgb), 0.3);
        }

        .nav-links {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .nav-item a {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 14px 18px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 14.5px;
            font-weight: 500;
            border-radius: 14px;
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }

        .nav-item.active a, .nav-item a:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.03);
            border-color: rgba(255, 255, 255, 0.05);
        }

        .nav-item.active a {
            border-color: rgba(var(--primary-gold-rgb), 0.2);
            background: rgba(var(--primary-gold-rgb), 0.04);
            color: var(--primary-gold);
        }

        .logout-section {
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            padding-top: 25px;
        }

        .logout-btn {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 14px 18px;
            background: transparent;
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #fca5a5;
            font-size: 14.5px;
            font-weight: 600;
            border-radius: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        /* MAIN CONTENT AREA */
        .main-content {
            margin-left: 280px;
            flex: 1;
            padding: 50px 60px;
            min-height: 100vh;
        }

        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            background: var(--card-bg);
            padding: 8px 18px;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            font-size: 13.5px;
            font-weight: 600;
        }

        .admin-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-gold) 0%, #b8860b 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            color: #0b0c10;
            font-weight: 800;
            font-size: 14px;
        }

        /* ANALYTICS GRID */
        .analytics-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
            margin-bottom: 45px;
        }

        .stat-card {
            background: var(--card-bg);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 25px -10px rgba(0, 0, 0, 0.5);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            width: 4px;
            background: var(--primary-gold);
            opacity: 0.7;
        }

        .stat-info h3 {
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--text-muted);
            margin-bottom: 10px;
        }

        .stat-info .value {
            font-size: 32px;
            font-weight: 800;
            color: #ffffff;
            line-height: 1;
        }

        .stat-icon {
            width: 55px;
            height: 55px;
            border-radius: 16px;
            background: rgba(var(--primary-gold-rgb), 0.07);
            border: 1px solid rgba(var(--primary-gold-rgb), 0.15);
            display: flex;
            justify-content: center;
            align-items: center;
            color: var(--primary-gold);
        }

        /* TABLE VIEW CARD */
        .data-card {
            background: var(--card-bg);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 24px;
            padding: 35px;
            box-shadow: 0 15px 35px -15px rgba(0, 0, 0, 0.6);
            margin-bottom: 30px;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .card-title {
            font-size: 18px;
            font-weight: 700;
        }

        .alert {
            padding: 14px 20px;
            border-radius: 14px;
            font-size: 13.5px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: fadeIn 0.4s ease;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.15);
            color: #fca5a5;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.08);
            border: 1px solid rgba(16, 185, 129, 0.15);
            color: #a7f3d0;
        }

        /* TABLE STYLING */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .admin-table th {
            background: var(--table-header-bg);
            padding: 16px 20px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--text-muted);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .admin-table td {
            padding: 18px 20px;
            font-size: 14px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
            vertical-align: middle;
        }

        .admin-table tr:hover td {
            background: rgba(255, 255, 255, 0.01);
        }

        .badge-lvl {
            background: rgba(var(--primary-gold-rgb), 0.12);
            color: var(--primary-gold);
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 700;
            border: 1px solid rgba(var(--primary-gold-rgb), 0.2);
            white-space: nowrap;
        }

        .badge-tier {
            background: rgba(255, 255, 255, 0.05);
            color: #ffffff;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            border: 1px solid rgba(255, 255, 255, 0.08);
            white-space: nowrap;
        }

        /* ACTION BUTTONS */
        .action-group {
            display: flex;
            gap: 8px;
        }

        .btn-action {
            border: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(255, 255, 255, 0.02);
            color: var(--text-color);
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 12.5px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-action-edit:hover {
            background: rgba(var(--primary-gold-rgb), 0.1);
            border-color: rgba(var(--primary-gold-rgb), 0.3);
            color: var(--primary-gold);
        }

        .btn-action-delete:hover {
            background: rgba(239, 68, 68, 0.1);
            border-color: rgba(239, 68, 68, 0.3);
            color: #ef4444;
        }

        /* MODAL DIALOG */
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.3s ease;
        }

        .modal-card {
            background: #12131a;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            width: 100%;
            max-width: 420px;
            padding: 30px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.8);
            animation: modalSlide 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            position: relative;
        }

        .modal-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, transparent, var(--primary-gold), transparent);
        }

        @keyframes modalSlide {
            from { transform: translateY(15px) scale(0.97); opacity: 0; }
            to { transform: translateY(0) scale(1); opacity: 1; }
        }

        .modal-header {
            margin-bottom: 20px;
        }

        .modal-title {
            font-size: 18px;
            font-weight: 700;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 11.5px;
            font-weight: 700;
            color: var(--text-muted);
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-input {
            width: 100%;
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 10px;
            padding: 12px 14px;
            color: #ffffff;
            outline: none;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-input:focus {
            border-color: var(--primary-gold);
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 25px;
        }

        .btn-modal {
            padding: 10px 18px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 700;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-modal-cancel {
            background: rgba(255, 255, 255, 0.05);
            color: var(--text-muted);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .btn-modal-cancel:hover {
            background: rgba(255, 255, 255, 0.08);
            color: #ffffff;
        }

        .btn-modal-submit {
            background: linear-gradient(135deg, var(--primary-gold) 0%, #b8860b 100%);
            color: #0b0c10;
            box-shadow: 0 4px 15px rgba(var(--primary-gold-rgb), 0.2);
        }

        .btn-modal-submit:hover {
            filter: brightness(1.1);
        }

        /* CUSTOM PAGINATION STYLE */
        .pagination-container {
            margin-top: 30px;
            display: flex;
            justify-content: center;
        }

        .pagination-container nav {
            display: flex;
            gap: 5px;
        }

        .pagination-container a, .pagination-container span {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            min-width: 36px;
            height: 36px;
            padding: 0 6px;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.06);
            color: var(--text-muted);
            border-radius: 8px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .pagination-container a:hover {
            color: #ffffff;
            border-color: rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.05);
        }

        .pagination-container .active {
            background: rgba(var(--primary-gold-rgb), 0.1);
            border-color: rgba(var(--primary-gold-rgb), 0.3);
            color: var(--primary-gold);
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <div>
            <div class="brand">Turning<span>Code</span></div>
            <ul class="nav-links">
                <li class="nav-item active">
                    <a href="#">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="7" height="9"></rect>
                            <rect x="14" y="3" width="7" height="5"></rect>
                            <rect x="14" y="12" width="7" height="9"></rect>
                            <rect x="3" y="16" width="7" height="5"></rect>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" style="opacity: 0.6; cursor: not-allowed;" onclick="event.preventDefault();">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        <span>Kelas (🔒)</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" style="opacity: 0.6; cursor: not-allowed;" onclick="event.preventDefault();">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="3"></circle>
                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                        </svg>
                        <span>Pengaturan (🔒)</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="logout-section">
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    <span>Keluar Sistem</span>
                </button>
            </form>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <div class="header-section">
            <div>
                <h1 class="page-title">Ringkasan Sistem</h1>
                <p style="font-size: 13.5px; color: var(--text-muted); margin-top: 4px;">Selamat datang kembali di pusat kendali, Administrator.</p>
            </div>
            
            <div class="admin-profile">
                <div class="admin-avatar">A</div>
                <span>{{ session('admin_email', 'Admin') }}</span>
            </div>
        </div>

        <!-- STATS GRID -->
        <div class="analytics-grid">
            <div class="stat-card">
                <div class="stat-info">
                    <h3>Total Pengguna</h3>
                    <div class="value">{{ number_format($totalUsers, 0, ',', '.') }}</div>
                </div>
                <div class="stat-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-info">
                    <h3>Akumulasi EXP</h3>
                    <div class="value">{{ number_format($totalExp, 0, ',', '.') }}</div>
                </div>
                <div class="stat-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-info">
                    <h3>Rata-rata Level</h3>
                    <div class="value">LV. {{ $avgLevel }}</div>
                </div>
                <div class="stat-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- DATA CARD -->
        <div class="data-card">
            <div class="card-header">
                <h2 class="card-title">Daftar Akun Pengguna</h2>
            </div>

            <!-- Notifications -->
            @if(session('success'))
                <div class="alert alert-success">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Level</th>
                            <th>Tier</th>
                            <th>Akumulasi EXP</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($paginatedUsers as $u)
                            <tr>
                                <td style="font-weight: 600; color: #ffffff;">{{ $u->name }}</td>
                                <td style="color: var(--text-muted);">{{ $u->email }}</td>
                                <td>
                                    <span class="badge-lvl">LV. {{ $u->level }}</span>
                                </td>
                                <td>
                                    <span class="badge-tier">{{ $u->tier }}</span>
                                </td>
                                <td style="font-weight: 700;">{{ number_format($u->exp, 0, ',', '.') }} EXP</td>
                                <td>
                                    <div class="action-group">
                                        <button class="btn-action btn-action-edit" onclick="openEditModal('{{ $u->id }}', '{{ $u->name }}', '{{ $u->exp }}')">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4z"></path>
                                            </svg>
                                            <span>Ubah EXP</span>
                                        </button>
                                        
                                        <form action="{{ route('admin.users.delete', $u->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna {{ $u->name }} secara permanen?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-action-delete">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                                </svg>
                                                <span>Hapus</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 40px;">Belum ada akun pengguna yang terdaftar di dalam sistem.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION LINKS -->
            @if($paginatedUsers->hasPages())
                <div class="pagination-container">
                    <nav>
                        {{-- Previous Page Link --}}
                        @if ($paginatedUsers->onFirstPage())
                            <span class="disabled">&laquo;</span>
                        @else
                            <a href="{{ $paginatedUsers->previousPageUrl() }}" rel="prev">&laquo;</a>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($paginatedUsers->getUrlRange(1, $paginatedUsers->lastPage()) as $page => $url)
                            @if ($page == $paginatedUsers->currentPage())
                                <span class="active">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}">{{ $page }}</a>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($paginatedUsers->hasMorePages())
                            <a href="{{ $paginatedUsers->nextPageUrl() }}" rel="next">&raquo;</a>
                        @else
                            <span class="disabled">&raquo;</span>
                        @endif
                    </nav>
                </div>
            @endif
        </div>
    </div>

    <!-- EDIT EXP MODAL DIALOG -->
    <div id="editModal" class="modal">
        <div class="modal-card">
            <div class="modal-header">
                <h3 class="modal-title">Perbarui Akumulasi EXP</h3>
                <p style="font-size: 13px; color: var(--text-muted); margin-top: 4px;" id="modalUserDesc"></p>
            </div>
            
            <form action="" method="POST" id="editForm">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label class="form-label" for="expInput">Jumlah EXP Terbaru</label>
                    <input class="form-input" type="number" name="exp" id="expInput" min="0" required autofocus>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-modal btn-modal-cancel" onclick="closeEditModal()">Batal</button>
                    <button type="submit" class="btn-modal btn-modal-submit">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('editModal');
        const form = document.getElementById('editForm');
        const desc = document.getElementById('modalUserDesc');
        const expInput = document.getElementById('expInput');

        function openEditModal(id, name, exp) {
            desc.textContent = `Ubah data akumulasi EXP untuk pengguna "${name}".`;
            expInput.value = exp;
            form.action = `/admin/users/${id}/exp`;
            modal.style.display = 'flex';
        }

        function closeEditModal() {
            modal.style.display = 'none';
        }

        // Close modal if clicked outside
        window.onclick = function(event) {
            if (event.target === modal) {
                closeEditModal();
            }
        }
    </script>
</body>
</html>
