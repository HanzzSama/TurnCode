    <!-- MODAL TAMBAH JADWAL WIZARD -->
    <div class="modal-overlay" id="jadwalModal">
        <div class="modal-glass">
            <div class="wizard-error-toast" id="wizardError"></div>
            <div class="modal-header">
                <h2>Tambah Jadwal Baru</h2>
                <button class="btn-close" id="btnCloseModal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="wizard-container" id="wizardSlider">

                    <!-- Slide 1: Pilih Topik -->
                    <div class="wizard-slide">
                        <div class="slide-header">
                            <h3>Materi Belajar</h3>
                            <p>Gass!!, yok mulai belajar pilih yang kamu tertarik</p>
                        </div>
                        <div class="topic-slider">
                            <!-- Card 1: Front End -->
                            <div class="topic-card active" style="background-image: url('{{ asset('images/course-hero.png') }}');">
                                <div class="topic-content-active">
                                    <div class="topic-title-active">Front End</div>
                                    <div class="topic-desc-active">orang yang kerjaan nya bikin user interface, desain yang unik aneh tapi keren, disini lah luu belajar cara bikin UI yang keren...</div>

                                </div>
                                <div class="topic-content-inactive">
                                    <div class="topic-title-vertical">Front End</div>
                                    <div class="btn-topic-arrow"><i class='bx bx-right-arrow-alt'></i></div>
                                </div>
                            </div>

                            <!-- Card 2: Back End -->
                            <div class="topic-card" style="background-image: url('{{ asset('images/course-hero.png') }}');">
                                <div class="topic-content-active">
                                    <div class="topic-title-active">Back End</div>
                                    <div class="topic-desc-active">Bangun logika dan kelola database.</div>

                                </div>
                                <div class="topic-content-inactive">
                                    <div class="topic-title-vertical">Back End</div>
                                    <div class="btn-topic-arrow"><i class='bx bx-right-arrow-alt'></i></div>
                                </div>
                            </div>

                            <!-- Card 3: Data Analyze -->
                            <div class="topic-card" style="background-image: url('{{ asset('images/course-hero.png') }}');">
                                <div class="topic-content-active">
                                    <div class="topic-title-active">Data Analyze</div>
                                    <div class="topic-desc-active">Analisis data skala besar untuk kebutuhan bisnis.</div>

                                </div>
                                <div class="topic-content-inactive">
                                    <div class="topic-title-vertical">Data Analyze</div>
                                    <div class="btn-topic-arrow"><i class='bx bx-right-arrow-alt'></i></div>
                                </div>
                            </div>

                            <!-- Card 4: Full Stack Dev -->
                            <div class="topic-card" style="background-image: url('{{ asset('images/course-hero.png') }}');">
                                <div class="topic-content-active">
                                    <div class="topic-title-active">Full Stack Dev</div>
                                    <div class="topic-desc-active">Kuasai front end dan back end sekaligus.</div>

                                </div>
                                <div class="topic-content-inactive">
                                    <div class="topic-title-vertical">Full Stack Dev</div>
                                    <div class="btn-topic-arrow"><i class='bx bx-right-arrow-alt'></i></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Slide 2: Pilih Course Grid -->
                    <div class="wizard-slide">
                        <div class="slide-title">Pilih Course yang Spesifik</div>
                        <div class="course-grid">
                            <div class="course-box">
                                <i class='bx bxl-html5 course-icon'></i>
                                <div class="course-name">Dasar HTML</div>
                            </div>
                            <div class="course-box selected">
                                <i class='bx bxl-css3 course-icon'></i>
                                <div class="course-name">CSS Modern</div>
                            </div>
                            <div class="course-box">
                                <i class='bx bxl-javascript course-icon'></i>
                                <div class="course-name">Javascript</div>
                            </div>
                            <div class="course-box">
                                <i class='bx bxl-react course-icon'></i>
                                <div class="course-name">React JS</div>
                            </div>
                        </div>
                    </div>

                    <!-- Slide 3: Form Detail -->
                    <div class="wizard-slide">
                        <div class="slide-title" style="display: flex; justify-content: space-between; align-items: center;">
                            Atur Detail Jadwal
                            <button id="btnAutoGenerate" class="btn-wizard" style="padding: 0.5rem 1rem; background: rgba(167, 139, 250, 0.1); color: #a78bfa; border: 1px solid rgba(167, 139, 250, 0.3); font-size: 0.85rem;"><i class='bx bx-magic-wand'></i> Auto Generate</button>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Judul Jadwal</label>
                            <input type="text" class="form-control" id="inputJudul" placeholder="Contoh: Belajar Flexbox">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Deskripsi / Catatan Tambahan</label>
                            <textarea class="form-control" id="inputDeskripsi" placeholder="Target hari ini harus paham grid dan flexbox..."></textarea>
                        </div>
                    </div>

                    <!-- Slide 4: Pilih Warna Penanda -->
                    <div class="wizard-slide">
                        <div class="slide-title">Pilih Warna Penanda</div>
                        <p style="color: #a1a1aa; font-size: 0.9rem; margin-top: -1rem; margin-bottom: 1.5rem;">Pilih warna penanda visual untuk membedakan jadwal belajarmu di dashboard dan kalender.</p>

                        <div class="color-picker-container">
                            <div class="color-option green selected" data-color="green">
                                <div class="color-preview"></div>
                                <div class="color-name">Hijau</div>
                            </div>
                            <div class="color-option purple" data-color="purple">
                                <div class="color-preview"></div>
                                <div class="color-name">Ungu</div>
                            </div>
                            <div class="color-option blue" data-color="blue">
                                <div class="color-preview"></div>
                                <div class="color-name">Biru</div>
                            </div>
                            <div class="color-option white" data-color="white">
                                <div class="color-preview"></div>
                                <div class="color-name">Putih</div>
                            </div>
                        </div>
                    </div>

                    <!-- Slide 5: Pilih Waktu -->
                    <div class="wizard-slide">
                        <div class="slide-title">Pilih Rutinitas Waktu</div>
                        <div class="time-grid">
                            <div class="time-box selected" data-type="Harian">
                                <i class='bx bx-calendar-event time-icon'></i>
                                <div>
                                    <div style="font-weight:600;">Harian</div>
                                    <div style="font-size:0.8rem; color:#a1a1aa;">Setiap hari di jam yang sama</div>
                                </div>
                            </div>
                            <div class="time-box" data-type="Mingguan">
                                <i class='bx bx-calendar-week time-icon'></i>
                                <div>
                                    <div style="font-weight:600;">Mingguan</div>
                                    <div style="font-size:0.8rem; color:#a1a1aa;">Pilih hari dalam seminggu</div>
                                </div>
                            </div>
                            <div class="time-box" data-type="Bulanan">
                                <i class='bx bx-calendar time-icon'></i>
                                <div>
                                    <div style="font-weight:600;">Bulanan</div>
                                    <div style="font-size:0.8rem; color:#a1a1aa;">Setiap tanggal tertentu</div>
                                </div>
                            </div>
                            <div class="time-box" data-type="Custom">
                                <i class='bx bx-time time-icon'></i>
                                <div>
                                    <div style="font-weight:600;">Custom</div>
                                    <div style="font-size:0.8rem; color:#a1a1aa;">Tentukan waktu spesifik</div>
                                </div>
                            </div>
                        </div>

                        <!-- Dynamic Config Panels -->
                        <div class="routine-config">
                            <!-- Harian Config -->
                            <div id="configHarian" class="config-panel active">
                                <label class="form-label" style="margin-bottom: 0.75rem;">Pilih hari apa saja:</label>
                                <div class="day-picker">
                                    <div class="day-btn">Sen</div>
                                    <div class="day-btn">Sel</div>
                                    <div class="day-btn">Rab</div>
                                    <div class="day-btn">Kam</div>
                                    <div class="day-btn">Jum</div>
                                    <div class="day-btn">Sab</div>
                                    <div class="day-btn">Min</div>
                                </div>
                            </div>

                            <!-- Mingguan Config -->
                            <div id="configMingguan" class="config-panel">
                                <div class="form-group">
                                    <label class="form-label" style="margin-bottom: 0.75rem;">Hari dalam seminggu:</label>
                                    <div class="day-picker">
                                        <div class="day-btn">Sen</div>
                                        <div class="day-btn">Sel</div>
                                        <div class="day-btn">Rab</div>
                                        <div class="day-btn">Kam</div>
                                        <div class="day-btn">Jum</div>
                                        <div class="day-btn">Sab</div>
                                        <div class="day-btn">Min</div>
                                    </div>
                                </div>
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label class="form-label" style="margin-bottom: 0.75rem;">Di minggu ke berapa?</label>
                                    <div class="day-picker">
                                        <div class="day-btn">Minggu 1</div>
                                        <div class="day-btn">Minggu 2</div>
                                        <div class="day-btn">Minggu 3</div>
                                        <div class="day-btn">Minggu 4</div>
                                        <div class="day-btn">Tiap Minggu</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Bulanan Config -->
                            <div id="configBulanan" class="config-panel">
                                <div class="form-group">
                                    <label class="form-label" style="margin-bottom: 0.75rem;">Pilih bulan apa saja dalam setahun:</label>
                                    <div class="day-picker">
                                        <div class="day-btn">Jan</div>
                                        <div class="day-btn">Feb</div>
                                        <div class="day-btn">Mar</div>
                                        <div class="day-btn">Apr</div>
                                        <div class="day-btn">Mei</div>
                                        <div class="day-btn">Jun</div>
                                        <div class="day-btn">Jul</div>
                                        <div class="day-btn">Ags</div>
                                        <div class="day-btn">Sep</div>
                                        <div class="day-btn">Okt</div>
                                        <div class="day-btn">Nov</div>
                                        <div class="day-btn">Des</div>
                                    </div>
                                </div>
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label class="form-label" style="margin-bottom: 0.75rem;">Minggu ke berapa saja?</label>
                                    <div class="day-picker">
                                        <div class="day-btn">M1</div>
                                        <div class="day-btn">M2</div>
                                        <div class="day-btn">M3</div>
                                        <div class="day-btn">M4</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Custom Config -->
                            <div id="configCustom" class="config-panel">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label class="form-label">Pilih Tanggal Spesifik:</label>
                                    <input type="date" class="form-control select-dark">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Slide 6: Waktu Awal & Akhir -->
                    <div class="wizard-slide">
                        <div class="slide-title">Tentukan Waktu Belajar</div>
                        <div class="timepicker-row">
                            <div class="timepicker-group">
                                <label class="timepicker-label">Waktu Mulai</label>
                                <input type="time" class="timepicker-input" id="timeStart" value="08:00">
                            </div>
                            <div class="timepicker-group">
                                <label class="timepicker-label">Waktu Selesai</label>
                                <input type="time" class="timepicker-input" id="timeEnd" value="09:30">
                            </div>
                        </div>

                        <div class="duration-preview">
                            <div class="duration-preview-label">Total Durasi Belajar</div>
                            <div class="duration-display">
                                <div class="dur-block">
                                    <div class="dur-value" id="durHours">01</div>
                                    <div class="dur-unit">Jam</div>
                                </div>
                                <div class="dur-separator">:</div>
                                <div class="dur-block">
                                    <div class="dur-value" id="durMinutes">30</div>
                                    <div class="dur-unit">Menit</div>
                                </div>
                                <div class="dur-separator">:</div>
                                <div class="dur-block">
                                    <div class="dur-value" id="durSeconds">00</div>
                                    <div class="dur-unit">Detik</div>
                                </div>
                            </div>
                            <div class="duration-note" id="durationNote">Sesi berlangsung dari <strong>08:00</strong> sampai <strong>09:30</strong></div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button class="btn-wizard btn-prev" id="btnPrev" style="visibility: hidden;">Kembali</button>
                <button class="btn-wizard btn-next" id="btnNext">Lanjut</button>
            </div>
        </div>
    </div>

    <!-- Premium Toast Success Notification -->
    <div class="premium-toast" id="premiumToast">
        <div class="premium-toast-icon">
            <i class='bx bx-check-circle'></i>
        </div>
        <div class="premium-toast-content">
            <div class="premium-toast-title" id="premiumToastTitle">Sukses!</div>
            <div class="premium-toast-desc" id="premiumToastDesc">Jadwal berhasil disimpan.</div>
        </div>
    </div>
