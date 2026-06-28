<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat Penyelesaian - {{ $submateri->title }}</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('css/certificate.css') }}">
    <style>
        :root {
            --coral: #fba293;
            --mint: #64c4a5;
            --olive: #ccd8c0;
            --navy: #0c2340;
        }
    </style>
</head>

<body>

    <div class="toolbar">
        <a href="{{ route('courses.show', [$submateri->course->id, 'submateri_id' => $submateri->id]) }}"
            class="btn-action btn-back">
            <i class='bx bx-arrow-back'></i> Kembali
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
                    <span class="cert-title-badge">Certificate of Completion</span>
                    <h1 class="cert-title">Sertifikat<br>Penyelesaian</h1>
                </div>

                <div class="cert-recipient">{{ $user->name }}</div>

                <div class="cert-description">
                    Telah sukses menyelesaikan materi dan lulus uji pemahaman secara penuh pada bagian
                    <strong>{{ $submateri->title }}</strong> di program spesialisasi kelas {{ $courseTitle }}.
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
                        <p class="signature-title">Tanggal Penyelesaian</p>
                    </div>

                    <div class="signature-block">
                        <div class="signature-handwriting">HanzzSama</div>
                        <div class="signature-line"></div>
                        <p class="signature-name">HanzzSama</p>
                        <p class="signature-title">Founder TurnCode</p>
                    </div>
                </div>
            </div>

            <!-- Geometric Shapes Background (Right Column) - Bauhaus Style -->
            <div class="certificate-patterns">
                <svg width="100%" height="100%" viewBox="0 0 500 793" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Outer Grid Border -->
                    <rect x="30" y="66.5" width="440" height="660" stroke="var(--navy)" stroke-width="2" fill="none" />

                    <!-- Internal Grid Lines -->
                    <!-- Vertical Lines -->
                    <line x1="140" y1="66.5" x2="140" y2="726.5" stroke="var(--navy)" stroke-width="2" />
                    <line x1="250" y1="66.5" x2="250" y2="726.5" stroke="var(--navy)" stroke-width="2" />
                    <line x1="360" y1="66.5" x2="360" y2="726.5" stroke="var(--navy)" stroke-width="2" />

                    <!-- Horizontal Lines -->
                    <line x1="30" y1="176.5" x2="470" y2="176.5" stroke="var(--navy)" stroke-width="2" />
                    <line x1="30" y1="286.5" x2="470" y2="286.5" stroke="var(--navy)" stroke-width="2" />
                    <line x1="30" y1="396.5" x2="470" y2="396.5" stroke="var(--navy)" stroke-width="2" />
                    <line x1="30" y1="506.5" x2="470" y2="506.5" stroke="var(--navy)" stroke-width="2" />
                    <line x1="30" y1="616.5" x2="470" y2="616.5" stroke="var(--navy)" stroke-width="2" />

                    <!-- Geometric Shapes -->
                    <!-- Column 1 -->
                    <!-- Row 1: Vertical Lens -->
                    <path d="M 30 66.5 A 55 55 0 0 1 30 176.5" stroke="var(--navy)" stroke-width="2" fill="none" />
                    <path d="M 140 66.5 A 55 55 0 0 0 140 176.5" stroke="var(--navy)" stroke-width="2" fill="none" />
                    <!-- Row 2: Horizontal Lens -->
                    <path d="M 30 176.5 A 55 55 0 0 0 140 176.5" stroke="var(--navy)" stroke-width="2" fill="none" />
                    <path d="M 30 286.5 A 55 55 0 0 1 140 286.5" stroke="var(--navy)" stroke-width="2" fill="none" />
                    <!-- Row 3: Vertical Lens -->
                    <path d="M 30 286.5 A 55 55 0 0 1 30 396.5" stroke="var(--navy)" stroke-width="2" fill="none" />
                    <path d="M 140 286.5 A 55 55 0 0 0 140 396.5" stroke="var(--navy)" stroke-width="2" fill="none" />
                    <!-- Row 4: Horizontal Lens -->
                    <path d="M 30 396.5 A 55 55 0 0 0 140 396.5" stroke="var(--navy)" stroke-width="2" fill="none" />
                    <path d="M 30 506.5 A 55 55 0 0 1 140 506.5" stroke="var(--navy)" stroke-width="2" fill="none" />
                    <!-- Row 5: Vertical Lens -->
                    <path d="M 30 506.5 A 55 55 0 0 1 30 616.5" stroke="var(--navy)" stroke-width="2" fill="none" />
                    <path d="M 140 506.5 A 55 55 0 0 0 140 616.5" stroke="var(--navy)" stroke-width="2" fill="none" />
                    <!-- Row 6: Circle -->
                    <circle cx="85" cy="671.5" r="55" stroke="var(--navy)" stroke-width="2" fill="none" />

                    <!-- Column 2 -->
                    <!-- Row 1: Horizontal Lens -->
                    <path d="M 140 66.5 A 55 55 0 0 0 250 66.5" stroke="var(--navy)" stroke-width="2" fill="none" />
                    <path d="M 140 176.5 A 55 55 0 0 1 250 176.5" stroke="var(--navy)" stroke-width="2" fill="none" />
                    <!-- Row 2: Circle -->
                    <circle cx="195" cy="231.5" r="55" stroke="var(--navy)" stroke-width="2" fill="none" />
                    <!-- Row 3: Circle -->
                    <circle cx="195" cy="341.5" r="55" stroke="var(--navy)" stroke-width="2" fill="none" />
                    <!-- Row 4: Horizontal Lens -->
                    <path d="M 140 396.5 A 55 55 0 0 0 250 396.5" stroke="var(--navy)" stroke-width="2" fill="none" />
                    <path d="M 140 506.5 A 55 55 0 0 1 250 506.5" stroke="var(--navy)" stroke-width="2" fill="none" />
                    <!-- Row 5: Horizontal Lens -->
                    <path d="M 140 506.5 A 55 55 0 0 0 250 506.5" stroke="var(--navy)" stroke-width="2" fill="none" />
                    <path d="M 140 616.5 A 55 55 0 0 1 250 616.5" stroke="var(--navy)" stroke-width="2" fill="none" />
                    <!-- Row 6: Horizontal Lens -->
                    <path d="M 140 616.5 A 55 55 0 0 0 250 616.5" stroke="var(--navy)" stroke-width="2" fill="none" />
                    <path d="M 140 726.5 A 55 55 0 0 1 250 726.5" stroke="var(--navy)" stroke-width="2" fill="none" />

                    <!-- Column 3 -->
                    <!-- Row 1: Horizontal Lens -->
                    <path d="M 250 66.5 A 55 55 0 0 0 360 66.5" stroke="var(--navy)" stroke-width="2" fill="none" />
                    <path d="M 250 176.5 A 55 55 0 0 1 360 176.5" stroke="var(--navy)" stroke-width="2" fill="none" />
                    <!-- Row 2: Vertical Lens -->
                    <path d="M 250 176.5 A 55 55 0 0 1 250 286.5" stroke="var(--navy)" stroke-width="2" fill="none" />
                    <path d="M 360 176.5 A 55 55 0 0 0 360 286.5" stroke="var(--navy)" stroke-width="2" fill="none" />
                    <!-- Row 3: Vertical Lens -->
                    <path d="M 250 286.5 A 55 55 0 0 1 250 396.5" stroke="var(--navy)" stroke-width="2" fill="none" />
                    <path d="M 360 286.5 A 55 55 0 0 0 360 396.5" stroke="var(--navy)" stroke-width="2" fill="none" />
                    <!-- Row 4: Circle -->
                    <circle cx="305" cy="451.5" r="55" stroke="var(--navy)" stroke-width="2" fill="none" />
                    <!-- Row 5: Circle -->
                    <circle cx="305" cy="561.5" r="55" stroke="var(--navy)" stroke-width="2" fill="none" />
                    <!-- Row 6: Vertical Lens + bottom curve -->
                    <path d="M 250 616.5 A 55 55 0 0 1 250 726.5" stroke="var(--navy)" stroke-width="2" fill="none" />
                    <path d="M 360 616.5 A 55 55 0 0 0 360 726.5" stroke="var(--navy)" stroke-width="2" fill="none" />
                    <path d="M 250 726.5 A 55 55 0 0 1 360 726.5" stroke="var(--navy)" stroke-width="2" fill="none" />

                    <!-- Column 4 -->
                    <!-- Row 1: Vertical Lens -->
                    <path d="M 360 66.5 A 55 55 0 0 1 360 176.5" stroke="var(--navy)" stroke-width="2" fill="none" />
                    <path d="M 470 66.5 A 55 55 0 0 0 470 176.5" stroke="var(--navy)" stroke-width="2" fill="none" />
                    <!-- Row 2: Horizontal Lens -->
                    <path d="M 360 176.5 A 55 55 0 0 0 470 176.5" stroke="var(--navy)" stroke-width="2" fill="none" />
                    <path d="M 360 286.5 A 55 55 0 0 1 470 286.5" stroke="var(--navy)" stroke-width="2" fill="none" />
                    <!-- Row 3: Vertical Lens -->
                    <path d="M 360 286.5 A 55 55 0 0 1 360 396.5" stroke="var(--navy)" stroke-width="2" fill="none" />
                    <path d="M 470 286.5 A 55 55 0 0 0 470 396.5" stroke="var(--navy)" stroke-width="2" fill="none" />
                    <!-- Row 4: Horizontal Lens -->
                    <path d="M 360 396.5 A 55 55 0 0 0 470 396.5" stroke="var(--navy)" stroke-width="2" fill="none" />
                    <path d="M 360 506.5 A 55 55 0 0 1 470 506.5" stroke="var(--navy)" stroke-width="2" fill="none" />
                    <!-- Row 5: Vertical Lens -->
                    <path d="M 360 506.5 A 55 55 0 0 1 360 616.5" stroke="var(--navy)" stroke-width="2" fill="none" />
                    <path d="M 470 506.5 A 55 55 0 0 0 470 616.5" stroke="var(--navy)" stroke-width="2" fill="none" />
                    <!-- Row 6: Vertical Lens + bottom curve -->
                    <path d="M 360 616.5 A 55 55 0 0 1 360 726.5" stroke="var(--navy)" stroke-width="2" fill="none" />
                    <path d="M 470 616.5 A 55 55 0 0 0 470 726.5" stroke="var(--navy)" stroke-width="2" fill="none" />
                    <path d="M 360 726.5 A 55 55 0 0 1 470 726.5" stroke="var(--navy)" stroke-width="2" fill="none" />
                </svg>

                <!-- Unique QR Verification Card -->
                <div class="cert-qr-container">
                    <div class="cert-qr-code">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode(url()->current()) }}&color=0c2340&bgcolor=faf8f5"
                            alt="QR Verification">
                    </div>
                    <div class="cert-qr-info">
                        <span class="cert-qr-label">Verifikasi</span>
                        <p class="cert-qr-desc">Scan untuk memverifikasi keaslian sertifikat ini.</p>
                        <div class="cert-qr-id">ID:
                            TC-SUB-{{ strtoupper(substr(md5($user->id . $completionDate->timestamp . $submateri->id), 0, 8)) }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</body>

</html>