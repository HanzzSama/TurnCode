<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat Penyelesaian - {{ $submateri->title }}</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@600;800&family=Montserrat:wght@400;600;700&family=Pinyon+Script&display=swap');

        :root {
            --bg-color: #0f0c13;
            --cert-bg: #151317;
            --gold-light: #FFDF73;
            --gold-main: #D4AF37;
            --gold-dark: #997A00;
            --purple-accent: #7c6af7;
            --text-light: #f3f4f6;
            --text-muted: #9ca3af;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-light);
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        /* Toolbar */
        .toolbar {
            width: 100%;
            max-width: 1122px; /* A4 Landscape width */
            display: flex;
            justify-content: flex-end;
            margin-bottom: 2rem;
            gap: 1rem;
        }

        .btn-action {
            background: linear-gradient(135deg, var(--gold-main) 0%, var(--gold-dark) 100%);
            color: #111;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            font-family: 'Montserrat', sans-serif;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(212, 175, 55, 0.5);
        }

        .btn-back {
            background: rgba(255, 255, 255, 0.1);
            color: var(--text-light);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: none;
        }
        
        .btn-back:hover {
            background: rgba(255, 255, 255, 0.15);
            box-shadow: none;
        }

        /* Certificate Container (A4 Landscape) */
        .certificate-wrapper {
            position: relative;
            width: 1122px;
            height: 793px;
            background: var(--cert-bg);
            padding: 20px;
            box-sizing: border-box;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
            overflow: hidden;
            border-radius: 4px;
        }

        .certificate-inner {
            position: relative;
            width: 100%;
            height: 100%;
            border: 2px solid rgba(212, 175, 55, 0.3);
            padding: 10px;
            box-sizing: border-box;
        }

        .certificate-border {
            position: relative;
            width: 100%;
            height: 100%;
            border: 8px solid transparent;
            border-image: linear-gradient(45deg, var(--gold-dark), var(--gold-light), var(--gold-dark)) 1;
            padding: 40px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            background: radial-gradient(circle at center, rgba(124, 106, 247, 0.05) 0%, transparent 70%);
        }

        /* Corner Ornaments */
        .corner {
            position: absolute;
            width: 80px;
            height: 80px;
            border: 4px solid var(--gold-main);
        }
        .corner-tl { top: -4px; left: -4px; border-right: none; border-bottom: none; }
        .corner-tr { top: -4px; right: -4px; border-left: none; border-bottom: none; }
        .corner-bl { bottom: -4px; left: -4px; border-right: none; border-top: none; }
        .corner-br { bottom: -4px; right: -4px; border-left: none; border-top: none; }

        /* Content */
        .brand {
            margin-top: 20px;
            margin-bottom: 30px;
        }
        .brand-logo {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(to right, #ffffff, var(--purple-accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -1px;
            font-family: 'Montserrat', sans-serif;
        }

        .cert-title {
            font-family: 'Cinzel', serif;
            font-size: 3.5rem;
            color: var(--gold-main);
            margin: 0 0 20px 0;
            letter-spacing: 4px;
            text-transform: uppercase;
        }

        .cert-subtitle {
            font-size: 1.1rem;
            color: var(--text-muted);
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 40px;
        }

        .cert-recipient {
            font-family: 'Pinyon Script', cursive;
            font-size: 5rem;
            color: var(--text-light);
            margin: 20px 0 30px 0;
            line-height: 1.2;
            text-shadow: 0 0 20px rgba(255,255,255,0.1);
        }

        .cert-description {
            font-size: 1.2rem;
            color: var(--text-muted);
            max-width: 700px;
            line-height: 1.6;
            margin: 0 auto 40px auto;
        }
        .cert-description strong {
            color: var(--gold-main);
            font-weight: 600;
            font-size: 1.3rem;
        }

        .cert-footer {
            display: flex;
            justify-content: space-between;
            width: 80%;
            margin-top: auto;
            margin-bottom: 20px;
            align-items: flex-end;
        }

        .signature-block {
            text-align: center;
        }

        .signature-img {
            height: 60px;
            margin-bottom: 10px;
            opacity: 0.8;
            filter: invert(1); /* Assuming a black signature on transparent bg, adjust if needed */
        }
        .signature-line {
            width: 200px;
            height: 1px;
            background: rgba(255,255,255,0.3);
            margin: 0 auto 10px auto;
        }

        .signature-name {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-light);
            margin: 0;
        }
        .signature-title {
            font-size: 0.9rem;
            color: var(--text-muted);
            margin: 5px 0 0 0;
        }

        .cert-date {
            font-size: 1.1rem;
            color: var(--gold-light);
            font-weight: 600;
        }

        /* Print Styles */
        @media print {
            @page {
                size: A4 landscape;
                margin: 0;
            }
            body {
                background: var(--cert-bg); /* Use exact certificate background */
                padding: 0;
                margin: 0;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .toolbar {
                display: none;
            }
            .certificate-wrapper {
                width: 297mm;
                height: 210mm;
                box-shadow: none;
                border-radius: 0;
            }
        }
    </style>
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
