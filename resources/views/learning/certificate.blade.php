<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat Penyelesaian - {{ $submateri->title }}</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('css/certificate.css') }}">
</head>
<body>

    <div class="toolbar">
        <a href="{{ route('courses.show', [$submateri->course->id, 'submateri_id' => $submateri->id]) }}" class="btn-action btn-back">
            <i class='bx bx-arrow-back'></i> Kembali
        </a>
        <button onclick="window.print()" class="btn-action">
            <i class='bx bx-printer'></i> Cetak / Simpan PDF
        </button>
    </div>

    <div class="certificate-wrapper">
        <div class="certificate-inner">
            <div class="certificate-border">
                <div class="corner corner-tl"></div>
                <div class="corner corner-tr"></div>
                <div class="corner corner-bl"></div>
                <div class="corner corner-br"></div>

                <div class="brand">
                    <div class="brand-logo">TurnCode</div>
                </div>

                <h1 class="cert-title">Certificate of Completion</h1>
                <div class="cert-subtitle">Penghargaan Diberikan Kepada</div>

                <div class="cert-recipient">{{ $user->name }}</div>

                <div class="cert-description">
                    Telah menyelesaikan dengan sukses dan menguasai materi secara penuh pada bagian<br>
                    <strong>{{ $submateri->title }}</strong><br>
                    dalam program kursus {{ $courseTitle }}.
                </div>

                <div class="cert-footer">
                    <div class="signature-block">
                        <div class="cert-date">
                            @php
                                \Carbon\Carbon::setLocale('id');
                                echo $completionDate->translatedFormat('d F Y');
                            @endphp
                        </div>
                        <div class="signature-line" style="margin-top: 15px; width: 150px;"></div>
                        <p class="signature-title">Tanggal Penyelesaian</p>
                    </div>

                    <div class="signature-block">
                        <!-- Mock signature using a styled cursive font if no image is available -->
                        <div style="font-family: 'Pinyon Script', cursive; font-size: 2.5rem; color: var(--gold-light); margin-bottom: 5px;">HanzzSama</div>
                        <div class="signature-line"></div>
                        <p class="signature-name">HanzzSama</p>
                        <p class="signature-title">Founder TurnCode</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
