<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ruang Ujian Akhir - TurnCode</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Fira+Code:wght@400;500&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        :root {
            --bg-dark: #0f0c13;
            --bg-card: #151317;
            --bg-card-hover: #1e1b24;
            --text-main: #f3f4f6;
            --text-muted: #9ca3af;
            --accent-primary: #7c6af7;
            --accent-hover: #6352ce;
            --danger: #ef4444;
            --success: #10b981;
            --gold: #D4AF37;
            --border-color: rgba(255, 255, 255, 0.08);
        }

        body {
            margin: 0;
            padding: 0;
            background-color: var(--bg-dark);
            color: var(--text-main);
            font-family: 'Plus Jakarta Sans', sans-serif;
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* Top Navbar */
        .exam-navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 70px;
            background: rgba(21, 19, 23, 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2rem;
            z-index: 100;
        }

        .exam-brand {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .exam-brand-logo {
            font-weight: 800;
            font-size: 1.25rem;
            background: linear-gradient(to right, #ffffff, var(--accent-primary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .exam-title {
            color: var(--text-muted);
            font-size: 0.9rem;
            font-weight: 600;
            padding-left: 1rem;
            border-left: 1px solid var(--border-color);
        }

        .exam-timer {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
            border: 1px solid rgba(239, 68, 68, 0.2);
            padding: 0.5rem 1.5rem;
            border-radius: 20px;
            font-family: 'Fira Code', monospace;
            font-weight: 700;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 0 15px rgba(239, 68, 68, 0.2);
        }

        .btn-finish {
            background: var(--accent-primary);
            color: white;
            border: none;
            padding: 0.6rem 1.5rem;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-finish:hover {
            background: var(--accent-hover);
            transform: translateY(-2px);
        }

        /* Sidebar Nav */
        .exam-sidebar {
            width: 280px;
            background: var(--bg-card);
            border-right: 1px solid var(--border-color);
            padding: 90px 1.5rem 1.5rem 1.5rem;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        .sidebar-title {
            font-size: 0.9rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .question-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 0.5rem;
        }

        .q-node {
            aspect-ratio: 1;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            border: 1px solid var(--border-color);
            background: rgba(255,255,255,0.02);
            transition: all 0.2s;
        }

        .q-node:hover {
            background: rgba(255,255,255,0.1);
        }

        .q-node.active {
            border-color: var(--accent-primary);
            background: rgba(124, 106, 247, 0.15);
            color: #b9affc;
        }

        .q-node.answered {
            background: var(--accent-primary);
            color: white;
            border-color: var(--accent-primary);
        }

        /* Main Content */
        .exam-main {
            flex: 1;
            padding: 100px 3rem 3rem 3rem;
            overflow-y: auto;
            position: relative;
            background-image: radial-gradient(circle at top right, rgba(124, 106, 247, 0.05), transparent 40%);
        }

        .question-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .question-number {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--gold);
        }

        .question-points {
            background: rgba(255,255,255,0.05);
            padding: 0.3rem 0.8rem;
            border-radius: 6px;
            font-size: 0.85rem;
            color: var(--text-muted);
            font-weight: 600;
        }

        .question-text {
            font-size: 1.15rem;
            line-height: 1.7;
            margin-bottom: 2.5rem;
        }

        /* Options */
        .options-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .option-item {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .option-item:hover {
            background: var(--bg-card-hover);
            border-color: rgba(255,255,255,0.15);
            transform: translateX(5px);
        }

        .option-label {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: var(--text-muted);
            flex-shrink: 0;
            border: 1px solid var(--border-color);
        }

        .option-text {
            font-size: 1rem;
            line-height: 1.5;
        }

        /* Fake selected state */
        .option-item.selected {
            border-color: var(--accent-primary);
            background: rgba(124, 106, 247, 0.05);
        }
        .option-item.selected .option-label {
            background: var(--accent-primary);
            color: white;
            border-color: var(--accent-primary);
        }

        .exam-controls {
            margin-top: 4rem;
            display: flex;
            justify-content: space-between;
            padding-top: 2rem;
            border-top: 1px solid var(--border-color);
        }

        .btn-nav {
            padding: 0.8rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            border: 1px solid var(--border-color);
            background: transparent;
            color: var(--text-main);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s;
        }

        .btn-nav:hover {
            background: rgba(255,255,255,0.05);
        }

        /* Code Block Mock */
        pre {
            background: #09080b;
            padding: 1.5rem;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            overflow-x: auto;
            font-family: 'Fira Code', monospace;
            font-size: 0.95rem;
            color: #a89f91;
            margin-bottom: 2rem;
            box-shadow: inset 0 2px 10px rgba(0,0,0,0.5);
        }
        .code-keyword { color: #f92672; }
        .code-func { color: #a6e22e; }
        .code-string { color: #e6db74; }

    </style>
</head>
<body>

    <!-- Navbar -->
    <div class="exam-navbar">
        <div class="exam-brand">
            <div class="exam-brand-logo">TurnCode</div>
            <div class="exam-title">Ujian Akhir: {{ $userCourse->title ?? 'Front End Development' }}</div>
        </div>

        <div class="exam-timer" id="timer">
            <i class='bx bx-time-five'></i> 01:59:45
        </div>

        <button class="btn-finish" onclick="finishExam()">Selesai & Kumpulkan</button>
    </div>

    <!-- Sidebar -->
    <div class="exam-sidebar">
        <div class="sidebar-title">Navigasi Soal</div>
        <div class="question-grid">
            <div class="q-node answered">1</div>
            <div class="q-node answered">2</div>
            <div class="q-node answered">3</div>
            <div class="q-node active">4</div>
            <div class="q-node">5</div>
            <div class="q-node">6</div>
            <div class="q-node">7</div>
            <div class="q-node">8</div>
            <div class="q-node">9</div>
            <div class="q-node">10</div>
            <div class="q-node">11</div>
            <div class="q-node">12</div>
            <div class="q-node">13</div>
            <div class="q-node">14</div>
            <div class="q-node">15</div>
        </div>

        <div style="margin-top: 2rem;">
            <div class="sidebar-title">Legenda</div>
            <div style="display: flex; flex-direction: column; gap: 0.8rem; font-size: 0.85rem; color: var(--text-muted);">
                <div style="display: flex; align-items: center; gap: 0.8rem;">
                    <div style="width: 16px; height: 16px; background: var(--accent-primary); border-radius: 4px;"></div> Dijawab
                </div>
                <div style="display: flex; align-items: center; gap: 0.8rem;">
                    <div style="width: 16px; height: 16px; background: rgba(124, 106, 247, 0.15); border: 1px solid var(--accent-primary); border-radius: 4px;"></div> Saat ini
                </div>
                <div style="display: flex; align-items: center; gap: 0.8rem;">
                    <div style="width: 16px; height: 16px; background: rgba(255,255,255,0.02); border: 1px solid var(--border-color); border-radius: 4px;"></div> Belum
                </div>
            </div>
        </div>
    </div>

    <!-- Main Question Area -->
    <div class="exam-main">
        <div style="max-width: 800px; margin: 0 auto;">
            
            <div class="question-header">
                <div class="question-number">Soal 4</div>
                <div class="question-points">Pilihan Ganda - 5 Poin</div>
            </div>

            <div class="question-text">
                Perhatikan potongan kode React JS di bawah ini. Apa yang akan dirender pada browser jika komponen `Counter` dijalankan dan user mengklik tombol sebanyak 3 kali berturut-turut dalam waktu cepat?
            </div>

            <pre>
<span class="code-keyword">import</span> { useState } <span class="code-keyword">from</span> <span class="code-string">'react'</span>;

<span class="code-keyword">export default function</span> <span class="code-func">Counter</span>() {
  <span class="code-keyword">const</span> [count, setCount] = <span class="code-func">useState</span>(0);

  <span class="code-keyword">const</span> handleClick = () => {
    <span class="code-func">setCount</span>(count + 1);
    <span class="code-func">setCount</span>(count + 1);
    <span class="code-func">setCount</span>(count + 1);
  };

  <span class="code-keyword">return</span> (
    &lt;button onClick={handleClick}&gt;
      Klik saya (Total: {count})
    &lt;/button&gt;
  );
}</pre>

            <div class="options-list">
                <div class="option-item" onclick="selectOption(this)">
                    <div class="option-label">A</div>
                    <div class="option-text">Angka akan bertambah 3 setiap kali tombol diklik. Setelah 3 kali klik, hasilnya adalah 9.</div>
                </div>
                <div class="option-item selected" onclick="selectOption(this)">
                    <div class="option-label">B</div>
                    <div class="option-text">Angka hanya bertambah 1 setiap kali siklus render. Setelah 3 kali klik, hasilnya adalah 3.</div>
                </div>
                <div class="option-item" onclick="selectOption(this)">
                    <div class="option-label">C</div>
                    <div class="option-text">React akan melemparkan Error: "Too many re-renders" karena state diubah terlalu cepat.</div>
                </div>
                <div class="option-item" onclick="selectOption(this)">
                    <div class="option-label">D</div>
                    <div class="option-text">Hasil akhirnya adalah 0 karena state `count` tidak di-mutate secara langsung.</div>
                </div>
            </div>

            <div class="exam-controls">
                <button class="btn-nav"><i class='bx bx-left-arrow-alt'></i> Soal Sebelumnya</button>
                <button class="btn-nav" style="background: rgba(255,255,255,0.05);">Soal Selanjutnya <i class='bx bx-right-arrow-alt'></i></button>
            </div>

            <!-- Coming soon overlay -->
            <div style="margin-top: 3rem; padding: 2rem; background: rgba(239, 68, 68, 0.05); border: 1px dashed rgba(239, 68, 68, 0.3); border-radius: 12px; text-align: center; color: var(--text-muted);">
                <i class='bx bx-info-circle' style="font-size: 2rem; color: var(--danger); margin-bottom: 0.5rem;"></i>
                <p>Ini adalah pratinjau (dummy) antarmuka Ruang Ujian. Sistem soal dinamis sedang dalam pengembangan.</p>
            </div>
        </div>
    </div>

    <script>
        // Simple mock interaction
        function selectOption(element) {
            document.querySelectorAll('.option-item').forEach(el => el.classList.remove('selected'));
            element.classList.add('selected');
        }

        function finishExam() {
            if(confirm("Apakah Anda yakin ingin mengakhiri ujian ini? Anda tidak akan bisa mengubah jawaban lagi.")) {
                window.location.href = "{{ route('dashboard') }}";
            }
        }

        // Mock timer
        let timeLeft = 2 * 3600 - 15; // roughly 2 hours
        setInterval(() => {
            timeLeft--;
            const h = Math.floor(timeLeft / 3600).toString().padStart(2, '0');
            const m = Math.floor((timeLeft % 3600) / 60).toString().padStart(2, '0');
            const s = (timeLeft % 60).toString().padStart(2, '0');
            document.getElementById('timer').innerHTML = `<i class='bx bx-time-five'></i> ${h}:${m}:${s}`;
        }, 1000);
    </script>
</body>
</html>
