<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Persetujuan Ujian Akhir - TurnCode</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        :root {
            --bg-dark: #0f0c13;
            --bg-card: #151317;
            --text-main: #f3f4f6;
            --text-muted: #9ca3af;
            --accent-primary: #7c6af7;
            --accent-hover: #6352ce;
            --gold: #D4AF37;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: var(--bg-dark);
            color: var(--text-main);
            font-family: 'Plus Jakarta Sans', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            overflow: hidden;
            background-image: 
                radial-gradient(circle at 15% 50%, rgba(124, 106, 247, 0.08), transparent 25%),
                radial-gradient(circle at 85% 30%, rgba(212, 175, 55, 0.05), transparent 25%);
        }

        .agreement-container {
            width: 100%;
            max-width: 800px;
            background: var(--bg-card);
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .header {
            padding: 2rem 2.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .header-icon {
            width: 60px;
            height: 60px;
            background: rgba(124, 106, 247, 0.1);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: var(--accent-primary);
            border: 1px solid rgba(124, 106, 247, 0.2);
        }

        .header-text h1 {
            margin: 0 0 0.25rem 0;
            font-size: 1.5rem;
            font-weight: 800;
        }

        .header-text p {
            margin: 0;
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        .slides-wrapper {
            position: relative;
            height: 400px;
            overflow: hidden;
        }

        .slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            padding: 2.5rem;
            box-sizing: border-box;
            transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.5s ease;
            opacity: 0;
            pointer-events: none;
            overflow-y: auto;
        }

        .slide.active {
            opacity: 1;
            pointer-events: auto;
            transform: translateX(0);
        }

        .slide.next {
            transform: translateX(100%);
        }

        .slide.prev {
            transform: translateX(-100%);
        }

        .slide-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--gold);
        }

        .rules-list {
            list-style: none;
            padding: 0;
            margin: 0 0 2rem 0;
        }

        .rules-list li {
            position: relative;
            padding-left: 2rem;
            margin-bottom: 1rem;
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.85);
        }

        .rules-list li::before {
            content: '\eb3b';
            font-family: 'boxicons';
            position: absolute;
            left: 0;
            top: 2px;
            color: var(--accent-primary);
            font-size: 1.2rem;
        }

        /* Checkbox styling */
        .agreement-box {
            background: rgba(124, 106, 247, 0.05);
            border: 1px solid rgba(124, 106, 247, 0.2);
            padding: 1.5rem;
            border-radius: 12px;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            cursor: pointer;
            transition: all 0.3s;
        }

        .agreement-box:hover {
            background: rgba(124, 106, 247, 0.1);
        }

        .agreement-box input[type="checkbox"] {
            width: 24px;
            height: 24px;
            accent-color: var(--accent-primary);
            cursor: pointer;
            margin-top: 2px;
        }

        .agreement-text {
            font-weight: 600;
            font-size: 1.05rem;
            margin: 0;
        }

        .footer {
            padding: 1.5rem 2.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(0, 0, 0, 0.2);
        }

        .btn {
            padding: 0.8rem 1.5rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s;
            font-family: inherit;
        }

        .btn-outline {
            background: transparent;
            color: var(--text-main);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .btn-primary {
            background: var(--accent-primary);
            color: #fff;
        }

        .btn-primary:hover {
            background: var(--accent-hover);
            transform: translateY(-2px);
        }

        .btn-primary:disabled {
            background: rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.3);
            cursor: not-allowed;
            transform: none;
        }

        /* Scrollbar */
        .slide::-webkit-scrollbar {
            width: 6px;
        }
        .slide::-webkit-scrollbar-track {
            background: transparent;
        }
        .slide::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        .dots {
            display: flex;
            gap: 8px;
        }
        .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            transition: 0.3s;
        }
        .dot.active {
            background: var(--accent-primary);
            width: 24px;
            border-radius: 6px;
        }

    </style>
</head>
<body>

    <div class="agreement-container">
        <div class="header">
            <div class="header-icon">
                <i class='bx bx-shield-quarter'></i>
            </div>
            <div class="header-text">
                <h1>Persiapan Ujian Akhir</h1>
                <p>Fokus: {{ $userCourse->title ?? 'Web Development' }}</p>
            </div>
        </div>

        <div class="slides-wrapper">
            <!-- Slide 1: Aturan Ujian -->
            <div class="slide active" id="slide1">
                <div class="slide-title">
                    <i class='bx bx-book-reader'></i> Aturan Ujian yang Harus Dipatuhi
                </div>
                <ul class="rules-list">
                    <li>Kerjakan ujian secara mandiri. Dilarang keras melakukan kerja sama dengan pihak manapun selama ujian berlangsung.</li>
                    <li>Dilarang melakukan kecurangan dalam bentuk apapun, termasuk namun tidak terbatas pada menggunakan joki, copy-paste jawaban dari sumber luar tanpa modifikasi dan pemahaman, atau membocorkan soal ujian.</li>
                    <li>Sistem ujian kami mencatat log aktivitas. Jika ditemukan indikasi kecurangan, TurnCode berhak membatalkan hasil ujian secara sepihak.</li>
                    <li>Integritas adalah nilai utama seorang developer profesional. Kerjakan dengan jujur untuk mengukur kemampuan Anda yang sebenarnya.</li>
                </ul>
                <label class="agreement-box">
                    <input type="checkbox" id="check-rules">
                    <div>
                        <p class="agreement-text">Saya telah membaca dan bersedia mematuhi aturan ujian.</p>
                        <p style="margin: 5px 0 0 0; font-size: 0.85rem; color: var(--text-muted);">Pelanggaran dapat mengakibatkan sanksi hingga pencabutan akun.</p>
                    </div>
                </label>
            </div>

            <!-- Slide 2: Tata Cara -->
            <div class="slide next" id="slide2">
                <div class="slide-title">
                    <i class='bx bx-cog'></i> Tata Cara / Prosedur Ujian
                </div>
                <ul class="rules-list">
                    <li>Ujian terdiri dari berbagai jenis soal (pilihan ganda, koding praktikal, dan logika kasus).</li>
                    <li>Terdapat batas waktu pengerjaan yang akan mulai berjalan segera setelah Anda menekan tombol "Mulai Ujian" di dalam ruang ujian.</li>
                    <li>Pastikan koneksi internet Anda stabil. Jika terputus, waktu akan terus berjalan di sistem kami.</li>
                    <li>Jika waktu habis, jawaban yang telah Anda pilih atau ketik akan otomatis dikumpulkan. Pastikan untuk menekan "Selesai & Kumpulkan" jika selesai lebih awal.</li>
                </ul>
                <label class="agreement-box">
                    <input type="checkbox" id="check-procedures">
                    <div>
                        <p class="agreement-text">Saya mengerti prosedur ujian dan menyatakan perangkat & koneksi saya telah siap.</p>
                    </div>
                </label>
            </div>
        </div>

        <div class="footer">
            <div class="dots">
                <div class="dot active" id="dot1"></div>
                <div class="dot" id="dot2"></div>
            </div>
            
            <div style="display: flex; gap: 1rem;">
                <a href="{{ route('dashboard') }}" class="btn btn-outline" id="btn-cancel">Batal</a>
                <button class="btn btn-primary" id="btn-next">Lanjut <i class='bx bx-right-arrow-alt'></i></button>
                <form action="{{ route('exam.room') }}" method="GET" style="display: none;" id="form-exam">
                    <button type="submit" class="btn btn-primary" id="btn-submit" disabled>Masuk Ruang Ujian <i class='bx bx-door-open'></i></button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const slide1 = document.getElementById('slide1');
        const slide2 = document.getElementById('slide2');
        const dot1 = document.getElementById('dot1');
        const dot2 = document.getElementById('dot2');
        
        const btnNext = document.getElementById('btn-next');
        const btnSubmit = document.getElementById('btn-submit');
        const formExam = document.getElementById('form-exam');
        
        const checkRules = document.getElementById('check-rules');
        const checkProcedures = document.getElementById('check-procedures');

        let currentSlide = 1;

        // Slide logic
        btnNext.addEventListener('click', () => {
            if (currentSlide === 1) {
                if (!checkRules.checked) {
                    alert('Anda harus menyetujui Aturan Ujian terlebih dahulu!');
                    return;
                }
                
                // Move to slide 2
                slide1.classList.remove('active');
                slide1.classList.add('prev');
                
                slide2.classList.remove('next');
                slide2.classList.add('active');
                
                dot1.classList.remove('active');
                dot2.classList.add('active');
                
                btnNext.style.display = 'none';
                formExam.style.display = 'block';
                
                currentSlide = 2;
                checkSubmitButton();
            }
        });

        checkProcedures.addEventListener('change', checkSubmitButton);

        function checkSubmitButton() {
            if (currentSlide === 2) {
                btnSubmit.disabled = !checkProcedures.checked;
            }
        }
    </script>
</body>
</html>
