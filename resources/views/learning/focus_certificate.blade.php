<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat Kelulusan Fokus - {{ $course->title }}</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('css/certificate.css') }}">
    <style>
        :root {
            --coral: #d4af37; /* Premium Gold */
            --mint: #10b981;  /* Emerald */
            --olive: #b7a99a; /* Warm grey/bronze */
            --navy: #0b1528;  /* Deep dark navy */
        }
    </style>
</head>
<body>

    <div class="toolbar">
        <a href="{{ route('dashboard') }}" class="btn-action btn-back">
            <i class='bx bx-arrow-back'></i> Kembali ke Dashboard
        </a>
        <button onclick="window.print()" class="btn-action">
            <i class='bx bx-printer'></i> Cetak / Simpan PDF
        </button>
    </div>

    <div class="certificate-wrapper">
        <div class="certificate-inner">
            
            <div class="certificate-content">
                <div class="brand">
                    <div class="brand-emblem">tc</div>
                    <div class="brand-logo">TurnCode</div>
                </div>

                <div class="cert-title-container">
                    <span class="cert-title-badge">Focus Certificate of Graduation</span>
                    <h1 class="cert-title">Sertifikat<br>Kelulusan</h1>
                </div>

                <div class="cert-recipient">{{ $user->name }}</div>

                <div class="cert-description">
                    Telah sukses menyelesaikan program evaluasi akhir dan dinyatakan lulus dari kelas spesialisasi fokus
                    <strong>{{ $course->title }}</strong> sebagai bukti penguasaan materi teoretis dan pemahaman logika pemrograman secara mendalam.
                </div>

                <div class="cert-footer">
                    <div class="signature-block">
                        <div class="cert-date">
                            @php
                                \Carbon\Carbon::setLocale('id');
                                echo $completionDate->translatedFormat('d F Y');
                            @endphp
                        </div>
                        <div class="signature-line" style="margin-top: 8px;"></div>
                        <p class="signature-title">Tanggal Kelulusan</p>
                    </div>

                    <div class="signature-block">
                        <div class="signature-handwriting" style="color: var(--coral);">HanzzSama</div>
                        <div class="signature-line"></div>
                        <p class="signature-name">HanzzSama</p>
                        <p class="signature-title">Founder TurnCode</p>
                    </div>
                </div>
            </div>

            <!-- Geometric Shapes Background (Right Column) -->
            <div class="certificate-patterns">
                <svg width="100%" height="100%" viewBox="0 0 500 793" fill="none" xmlns="http://www.w3.org/2000/svg">

                    <!-- ===== ROW 1: Top Zone (y: 40–200) ===== -->
                    <!-- Olive vertical pill -->
                    <rect x="60" y="50" width="60" height="120" rx="30" stroke="var(--olive)" stroke-width="20" />
                    <!-- Coral arc bridging from pill -->
                    <path d="M 150,120 A 35,35 0 0,1 220,120" stroke="var(--coral)" stroke-width="20" stroke-linecap="round" />
                    <!-- Navy dot pair -->
                    <circle cx="280" cy="70" r="7" fill="var(--navy)" />
                    <circle cx="280" cy="100" r="7" fill="var(--navy)" />
                    <!-- Mint U-curve top right -->
                    <path d="M 320,60 L 320,130 A 35,35 0 0,0 390,130 L 390,60" stroke="var(--mint)" stroke-width="20" stroke-linecap="round" />
                    <!-- Navy L-hook far right -->
                    <path d="M 440,60 L 440,110 A 25,25 0 0,1 415,135" stroke="var(--navy)" stroke-width="20" stroke-linecap="round" />

                    <!-- ===== ROW 2: Upper-Mid Zone (y: 220–360) ===== -->
                    <!-- Coral horizontal pill -->
                    <rect x="50" y="240" width="120" height="55" rx="27" stroke="var(--coral)" stroke-width="20" />
                    <!-- Olive arc below pill -->
                    <path d="M 200,280 A 30,30 0 0,0 260,280" stroke="var(--olive)" stroke-width="20" stroke-linecap="round" />
                    <!-- Navy solid circle -->
                    <circle cx="340" cy="260" r="28" fill="var(--navy)" />
                    <!-- Mint vertical pill far right -->
                    <rect x="400" y="220" width="55" height="110" rx="27" stroke="var(--mint)" stroke-width="20" />

                    <!-- ===== ROW 3: Center Zone (y: 380–510) ===== -->
                    <!-- Mint wave connector -->
                    <path d="M 80,400 L 130,400 A 30,30 0 0,1 160,430 L 160,490" stroke="var(--mint)" stroke-width="20" stroke-linecap="round" />
                    <!-- Coral S-curve -->
                    <path d="M 230,390 L 230,440 A 30,30 0 0,0 260,470 L 340,470" stroke="var(--coral)" stroke-width="20" stroke-linecap="round" />
                    <!-- Olive vertical pill right -->
                    <rect x="390" y="380" width="55" height="110" rx="27" stroke="var(--olive)" stroke-width="20" />
                    <!-- Navy dot accent -->
                    <circle cx="350" cy="400" r="7" fill="var(--navy)" />

                    <!-- ===== ROW 4: Lower-Mid Zone (y: 540–650) ===== -->
                    <!-- Navy U-curve inverted -->
                    <path d="M 60,560 A 40,40 0 0,1 140,560" stroke="var(--navy)" stroke-width="20" stroke-linecap="round" />
                    <!-- Coral vertical pill -->
                    <rect x="200" y="540" width="55" height="100" rx="27" stroke="var(--coral)" stroke-width="20" />
                    <!-- Olive arc -->
                    <path d="M 310,600 A 35,35 0 0,0 380,600" stroke="var(--olive)" stroke-width="20" stroke-linecap="round" />
                    <!-- Mint L-hook -->
                    <path d="M 430,540 L 430,600 A 25,25 0 0,1 405,625" stroke="var(--mint)" stroke-width="20" stroke-linecap="round" />

                    <!-- ===== ROW 5: Bottom Zone (y: 670–760) ===== -->
                    <!-- Olive horizontal pill -->
                    <rect x="60" y="690" width="110" height="50" rx="25" stroke="var(--olive)" stroke-width="20" />
                    <!-- Mint arc bottom center -->
                    <path d="M 220,720 A 35,35 0 0,1 290,720" stroke="var(--mint)" stroke-width="20" stroke-linecap="round" />
                    <!-- Coral dot -->
                    <circle cx="340" cy="720" r="7" fill="var(--coral)" />
                    <!-- Navy solid circle bottom right -->
                    <circle cx="430" cy="720" r="35" fill="var(--navy)" />

                </svg>
            </div>

        </div>
    </div>

</body>
</html>
