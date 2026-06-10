(function () {
            // MODAL WIZARD LOGIC
            const modal = document.getElementById('jadwalModal');
            if (!modal) return;
            const btnOpen = document.getElementById('btnTambahModal');
            const btnClose = document.getElementById('btnCloseModal');

            const slider = document.getElementById('wizardSlider');
            const btnNext = document.getElementById('btnNext');
            const btnPrev = document.getElementById('btnPrev');

            let currentStep = 0;
            const totalSteps = 6;

            let isEditMode = false;
            let editScheduleId = null;
            let originalSchedule = null;

            const timeStart = document.getElementById('timeStart');
            const timeEnd = document.getElementById('timeEnd');
            const durHours = document.getElementById('durHours');
            const durMinutes = document.getElementById('durMinutes');
            const durSeconds = document.getElementById('durSeconds');
            const durationNote = document.getElementById('durationNote');

            function calcDuration() {
                if (!timeStart || !timeEnd) return;
                const [sh, sm] = timeStart.value.split(':').map(Number);
                const [eh, em] = timeEnd.value.split(':').map(Number);
                let totalMin = (eh * 60 + em) - (sh * 60 + sm);
                if (totalMin < 0) totalMin += 24 * 60; // crosses midnight
                const h = Math.floor(totalMin / 60);
                const m = totalMin % 60;
                if (durHours) durHours.textContent = String(h).padStart(2, '0');
                if (durMinutes) durMinutes.textContent = String(m).padStart(2, '0');
                if (durSeconds) durSeconds.textContent = '00';
                if (durationNote) durationNote.innerHTML = `Sesi berlangsung dari <strong>${timeStart.value}</strong> sampai <strong>${timeEnd.value}</strong>`;
            }

            function resetWizard() {
                isEditMode = false;
                editScheduleId = null;
                originalSchedule = null;
                currentStep = 0;

                // Reset title
                const modalTitle = modal.querySelector('.modal-header h2');
                if (modalTitle) modalTitle.textContent = "Tambah Jadwal Baru";

                // Clear fields
                document.getElementById('inputJudul').value = '';
                document.getElementById('inputDeskripsi').value = '';
                
                const now = new Date();
                const startHour = String(now.getHours()).padStart(2, '0');
                const startMin = String(now.getMinutes()).padStart(2, '0');
                const endHour = String((now.getHours() + 1) % 24).padStart(2, '0');
                document.getElementById('timeStart').value = `${startHour}:${startMin}`;
                document.getElementById('timeEnd').value = `${endHour}:${startMin}`;

                // Reset topic
                const firstTopic = document.querySelector('.topic-card');
                if (firstTopic) {
                    document.querySelectorAll('.topic-card').forEach(c => c.classList.remove('active'));
                    firstTopic.classList.add('active');
                    const topicTitle = firstTopic.querySelector('.topic-title-active');
                    if (topicTitle) {
                        renderCourses(topicTitle.textContent.trim());
                    }
                }

                // Reset color picker
                document.querySelectorAll('.color-option').forEach(c => c.classList.remove('selected'));
                const defaultColor = document.querySelector('.color-option.green');
                if (defaultColor) defaultColor.classList.add('selected');

                // Reset routine type
                document.querySelectorAll('.time-box').forEach(c => c.classList.remove('selected'));
                const defaultTimeBox = document.querySelector('.time-box[data-type="Harian"]');
                if (defaultTimeBox) defaultTimeBox.classList.add('selected');

                document.querySelectorAll('.config-panel').forEach(p => p.classList.remove('active'));
                const defaultPanel = document.getElementById('configHarian');
                if (defaultPanel) defaultPanel.classList.add('active');

                // Reset all day-btns in all config panels
                document.querySelectorAll('.day-btn').forEach(btn => btn.classList.remove('active'));

                // Reset custom date
                const customDateInput = document.querySelector('#configCustom input[type="date"]');
                if (customDateInput) customDateInput.value = '';

                // Recalculate duration
                calcDuration();

                // Slide to first step
                updateSlider();
            }

            function populateWizardForEdit(sch) {
                isEditMode = true;
                editScheduleId = sch.id;
                originalSchedule = sch;
                currentStep = 0;

                // Update title
                const modalTitle = modal.querySelector('.modal-header h2');
                if (modalTitle) modalTitle.textContent = "Edit Jadwal";

                // Fill fields
                document.getElementById('inputJudul').value = sch.title || '';
                document.getElementById('inputDeskripsi').value = sch.description || '';
                
                const startTime = sch.start_time ? sch.start_time.substring(0, 5) : '08:00';
                const endTime = sch.end_time ? sch.end_time.substring(0, 5) : '09:30';
                document.getElementById('timeStart').value = startTime;
                document.getElementById('timeEnd').value = endTime;

                // Select topic
                document.querySelectorAll('.topic-card').forEach(card => {
                    const topicTitle = card.querySelector('.topic-title-active');
                    if (topicTitle && topicTitle.textContent.trim() === sch.topic) {
                        document.querySelectorAll('.topic-card').forEach(c => c.classList.remove('active'));
                        card.classList.add('active');
                    }
                });

                // Render and select course
                renderCourses(sch.topic);
                document.querySelectorAll('.course-box').forEach(box => {
                    const courseName = box.querySelector('.course-name');
                    if (courseName && courseName.textContent.trim() === sch.course) {
                        document.querySelectorAll('.course-box').forEach(c => c.classList.remove('selected'));
                        box.classList.add('selected');
                    }
                });

                // Select custom color
                const config = sch.routine_config || {};
                const schColor = config.color || 'green';
                document.querySelectorAll('.color-option').forEach(opt => {
                    opt.classList.remove('selected');
                    if (opt.getAttribute('data-color') === schColor) {
                        opt.classList.add('selected');
                    }
                });

                // Select routine type
                document.querySelectorAll('.time-box').forEach(box => {
                    box.classList.remove('selected');
                    if (box.getAttribute('data-type') === sch.routine_type) {
                        box.classList.add('selected');
                    }
                });

                document.querySelectorAll('.config-panel').forEach(panel => panel.classList.remove('active'));
                const targetPanel = document.getElementById('config' + sch.routine_type);
                if (targetPanel) targetPanel.classList.add('active');

                // Reset active day buttons first
                document.querySelectorAll('.day-btn').forEach(btn => btn.classList.remove('active'));

                // Activate active checkboxes/buttons based on type
                if (sch.routine_type === 'Harian') {
                    const days = config.days || [];
                    document.querySelectorAll('#configHarian .day-btn').forEach(btn => {
                        if (days.includes(btn.textContent.trim())) {
                            btn.classList.add('active');
                        }
                    });
                } else if (sch.routine_type === 'Mingguan') {
                    const days = config.days || [];
                    const weeks = config.weeks || [];
                    document.querySelectorAll('#configMingguan .form-group:nth-child(1) .day-btn').forEach(btn => {
                        if (days.includes(btn.textContent.trim())) {
                            btn.classList.add('active');
                        }
                    });
                    document.querySelectorAll('#configMingguan .form-group:nth-child(2) .day-btn').forEach(btn => {
                        if (weeks.includes(btn.textContent.trim())) {
                            btn.classList.add('active');
                        }
                    });
                } else if (sch.routine_type === 'Bulanan') {
                    const months = config.months || [];
                    const weeks = config.weeks || [];
                    document.querySelectorAll('#configBulanan .form-group:nth-child(1) .day-btn').forEach(btn => {
                        if (months.includes(btn.textContent.trim())) {
                            btn.classList.add('active');
                        }
                    });
                    document.querySelectorAll('#configBulanan .form-group:nth-child(2) .day-btn').forEach(btn => {
                        if (weeks.includes(btn.textContent.trim())) {
                            btn.classList.add('active');
                        }
                    });
                } else if (sch.routine_type === 'Custom') {
                    const customDateInput = document.querySelector('#configCustom input[type="date"]');
                    if (customDateInput) {
                        customDateInput.value = config.date || '';
                    }
                }

                // Recalculate duration
                calcDuration();

                // Slide to first step
                updateSlider();
            }

            function showPremiumConfirm(title, message) {
                return new Promise((resolve) => {
                    const confirmOverlay = document.createElement('div');
                    confirmOverlay.style.position = 'fixed';
                    confirmOverlay.style.top = '0';
                    confirmOverlay.style.left = '0';
                    confirmOverlay.style.right = '0';
                    confirmOverlay.style.bottom = '0';
                    confirmOverlay.style.background = 'rgba(0, 0, 0, 0.75)';
                    confirmOverlay.style.backdropFilter = 'blur(10px)';
                    confirmOverlay.style.display = 'flex';
                    confirmOverlay.style.alignItems = 'center';
                    confirmOverlay.style.justifyContent = 'center';
                    confirmOverlay.style.zIndex = '99999';
                    confirmOverlay.style.opacity = '0';
                    confirmOverlay.style.transition = 'opacity 0.3s ease';

                    confirmOverlay.innerHTML = `
                        <div class="confirm-glass" style="background: #151515; border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 24px; padding: 2rem; width: 90%; max-width: 420px; text-align: center; transform: scale(0.9); transition: transform 0.3s ease; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);">
                            <div style="font-size: 3rem; color: #f87171; margin-bottom: 1rem;"><i class='bx bx-error-circle'></i></div>
                            <h3 style="font-size: 1.3rem; font-weight: 800; color: white; margin-bottom: 0.75rem;">${title}</h3>
                            <p style="font-size: 0.9rem; color: #a1a1aa; line-height: 1.5; margin-bottom: 2rem;">${message}</p>
                            <div style="display: flex; gap: 1rem; justify-content: center;">
                                <button id="btnConfirmCancel" style="flex: 1; padding: 0.75rem 1.5rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); color: white; border-radius: 12px; font-weight: 700; cursor: pointer; transition: all 0.2s;">Batal</button>
                                <button id="btnConfirmYes" style="flex: 1; padding: 0.75rem 1.5rem; background: #ef4444; border: none; color: white; border-radius: 12px; font-weight: 700; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 15px rgba(239, 68, 68, 0.25);">Hapus</button>
                            </div>
                        </div>
                    `;

                    document.body.appendChild(confirmOverlay);

                    // Trigger reflow & fade in
                    confirmOverlay.offsetHeight;
                    confirmOverlay.style.opacity = '1';
                    confirmOverlay.querySelector('.confirm-glass').style.transform = 'scale(1)';

                    const yesBtn = confirmOverlay.querySelector('#btnConfirmYes');
                    const cancelBtn = confirmOverlay.querySelector('#btnConfirmCancel');

                    const cleanup = (value) => {
                        confirmOverlay.style.opacity = '0';
                        confirmOverlay.querySelector('.confirm-glass').style.transform = 'scale(0.9)';
                        setTimeout(() => {
                            confirmOverlay.remove();
                            resolve(value);
                        }, 300);
                    };

                    yesBtn.addEventListener('click', () => cleanup(true));
                    cancelBtn.addEventListener('click', () => cleanup(false));
                    confirmOverlay.addEventListener('click', (e) => {
                        if (e.target === confirmOverlay) cleanup(false);
                    });
                });
            }

            async function handleDeleteSchedule(schId, cardEl) {
                const confirmed = await showPremiumConfirm(
                    "Hapus Jadwal?",
                    "Apakah Anda yakin ingin menghapus jadwal belajar ini? Tindakan ini tidak dapat dibatalkan."
                );

                if (!confirmed) return;

                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const res = await fetch(`/jadwal/${schId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    });

                    if (res.ok) {
                        // 1. Smoothly fade & translate out the card
                        cardEl.style.transition = 'all 0.5s cubic-bezier(0.4, 0, 0.2, 1)';
                        cardEl.style.opacity = '0';
                        cardEl.style.transform = 'translateX(-50px) scale(0.9)';
                        cardEl.style.height = '0';
                        cardEl.style.padding = '0';
                        cardEl.style.margin = '0';
                        cardEl.style.border = 'none';

                        // 2. Parse schedule from data-schedule inside edit button to update statistics instantly
                        const editBtn = cardEl.querySelector('.edit-sch-btn');
                        if (editBtn) {
                            const schData = editBtn.getAttribute('data-schedule');
                            if (schData) {
                                const sch = JSON.parse(schData);
                                const isActive = isScheduleActiveToday(sch.routine_type, sch.routine_config);

                                if (isActive) {
                                    // Decrement today sessions count
                                    const countEl = document.getElementById('todaySessionsCount');
                                    if (countEl) {
                                        const currentCount = parseInt(countEl.textContent.trim()) || 0;
                                        countEl.textContent = Math.max(0, currentCount - 1);
                                    }

                                    // Subtract duration from total belajar hours
                                    const totalHoursEl = document.getElementById('totalStudyHours');
                                    if (totalHoursEl) {
                                        const { durationMinutes } = checkSessionStatus(sch.start_time, sch.end_time);
                                        const currentHoursStr = totalHoursEl.textContent.trim().replace('h', '');
                                        const currentHours = parseFloat(currentHoursStr) || 0;
                                        const newHours = Math.max(0, currentHours - (durationMinutes / 60)).toFixed(1);
                                        totalHoursEl.textContent = `${newHours}h`;
                                    }
                                }
                            }
                        }

                        setTimeout(() => {
                            cardEl.remove();
                            // Check if grid is empty, show empty state if so
                            ['todaySessionsGrid', 'upcomingSessionsGrid'].forEach(gridId => {
                                const grid = document.getElementById(gridId);
                                if (grid && !grid.querySelector('.session-card')) {
                                    grid.innerHTML = `
                                        <div style="background: rgba(255,255,255,0.02); border: 2px dashed rgba(255,255,255,0.05); border-radius: 24px; padding: 3rem; text-align: center;">
                                            <i class='bx bx-calendar-x' style="font-size: 3rem; color: #6b6570; margin-bottom: 1rem;"></i>
                                            <div style="font-weight: 700; color: white; font-size: 1.1rem; margin-bottom: 0.25rem;">Tidak ada jadwal</div>
                                            <div style="color: #6b6570; font-size: 0.85rem;">Mau bikin sesi belajar baru? klik "+ Tambah Jadwal" di atas!</div>
                                        </div>
                                    `;
                                }
                            });
                        }, 500);

                        // 3. Show premium success toast
                        const toast = document.getElementById('premiumToast');
                        const toastTitle = document.getElementById('premiumToastTitle');
                        const toastDesc = document.getElementById('premiumToastDesc');
                        if (toast && toastTitle && toastDesc) {
                            toastTitle.textContent = "Terhapus!";
                            toastDesc.textContent = "Jadwal berhasil dihapus.";
                            toast.classList.add('show');
                        }

                        // 4. Background reload page after 3.5 seconds
                        setTimeout(() => {
                            if (toast) toast.classList.remove('show');
                            window.location.reload();
                        }, 3500);
                    } else {
                        console.error(await res.text());
                        showError("Gagal menghapus jadwal.");
                    }
                } catch (err) {
                    console.error(err);
                    showError("Terjadi kesalahan sistem saat menghapus.");
                }
            }

            // Open/Close Modal
            if (btnOpen) {
                btnOpen.addEventListener('click', () => {
                    resetWizard();
                    modal.style.display = 'flex';
                    // Trigger reflow for transition
                    modal.offsetHeight;
                    modal.classList.add('show');
                });
            }

            if (btnClose) {
                btnClose.addEventListener('click', () => {
                    modal.classList.remove('show');
                    setTimeout(() => { modal.style.display = 'none'; }, 300);
                });
            }

            modal.addEventListener('click', (e) => {
                if(e.target === modal) {
                    modal.classList.remove('show');
                    setTimeout(() => { modal.style.display = 'none'; }, 300);
                }
            });

            // Event Delegation for Edit & Delete buttons
            document.addEventListener('click', (e) => {
                const editBtn = e.target.closest('.edit-sch-btn');
                if (editBtn) {
                    e.preventDefault();
                    e.stopPropagation();
                    const schData = editBtn.getAttribute('data-schedule');
                    if (schData) {
                        const sch = JSON.parse(schData);
                        populateWizardForEdit(sch);
                        modal.style.display = 'flex';
                        modal.offsetHeight;
                        modal.classList.add('show');
                    }
                    return;
                }

                const deleteBtn = e.target.closest('.delete-sch-btn');
                if (deleteBtn) {
                    e.preventDefault();
                    e.stopPropagation();
                    const schId = deleteBtn.getAttribute('data-id');
                    if (schId) {
                        const cardEl = deleteBtn.closest('.session-card');
                        handleDeleteSchedule(schId, cardEl);
                    }
                    return;
                }
            });

            // Error Toast displayer
            const errorToast = document.getElementById('wizardError');
            let toastTimeout;
            function showError(message) {
                clearTimeout(toastTimeout);
                errorToast.textContent = message;
                errorToast.classList.add('show');
                toastTimeout = setTimeout(() => {
                    errorToast.classList.remove('show');
                }, 3500);
            }

            // Status checker helper
            function checkSessionStatus(startTimeStr, endTimeStr) {
                const now = new Date();

                const [sh, sm] = startTimeStr.split(':').map(Number);
                const [eh, em] = endTimeStr.split(':').map(Number);

                const sessionStart = new Date();
                sessionStart.setHours(sh, sm, 0, 0);

                const sessionEnd = new Date();
                sessionEnd.setHours(eh, em, 0, 0);
                if (sessionEnd < sessionStart) {
                    sessionEnd.setDate(sessionEnd.getDate() + 1);
                }

                const isLive = now >= sessionStart && now <= sessionEnd;
                const isPast = now > sessionEnd;
                const isUpcoming = now < sessionStart;

                let durationMinutes = Math.floor((sessionEnd - sessionStart) / 60000);
                if (durationMinutes < 0) durationMinutes += 24 * 60;

                return { isLive, isPast, isUpcoming, durationMinutes };
            }

            // Recurrence text builder helper
            function getRecurrenceText(routineType, config) {
                let recurrenceText = '';
                if (routineType === 'Harian') {
                    const days = config.days || [];
                    if (days.length === 0) {
                        recurrenceText = 'Setiap hari';
                    } else {
                        recurrenceText = 'Hari: ' + days.join(', ');
                    }
                } else if (routineType === 'Mingguan') {
                    const days = config.days || [];
                    const weeks = config.weeks || [];
                    recurrenceText = days.join(', ') + ' (' + weeks.join(', ') + ')';
                } else if (routineType === 'Bulanan') {
                    const months = config.months || [];
                    const weeks = config.weeks || [];
                    recurrenceText = weeks.join(', ') + ' di bulan ' + months.join(', ');
                } else if (routineType === 'Custom') {
                    const customDate = config.date || '';
                    if (customDate) {
                        const d = new Date(customDate);
                        const day = String(d.getDate()).padStart(2, '0');
                        const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
                        const month = monthNames[d.getMonth()];
                        const year = d.getFullYear();
                        recurrenceText = 'Sekali: ' + day + ' ' + month + ' ' + year;
                    } else {
                        recurrenceText = 'Sekali: -';
                    }
                }
                return recurrenceText;
            }

            // Schedule active today helper
            function isScheduleActiveToday(routineType, config) {
                const today = new Date();
                const daysMap = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
                const todayName = daysMap[today.getDay()];

                // Get week of month (1-5)
                const weekOfMonth = Math.ceil(today.getDate() / 7);
                const weekStr = 'Minggu ' + weekOfMonth;
                const weekStrShort = 'M' + weekOfMonth;

                const monthsMap = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
                const currentMonthName = monthsMap[today.getMonth()];

                if (routineType === 'Harian') {
                    const days = config.days || [];
                    if (days.length === 0 || days.includes(todayName)) {
                        return true;
                    }
                } else if (routineType === 'Mingguan') {
                    const days = config.days || [];
                    const weeks = config.weeks || [];
                    if (days.includes(todayName) && (weeks.includes(weekStr) || weeks.includes('Tiap Minggu'))) {
                        return true;
                    }
                } else if (routineType === 'Bulanan') {
                    const months = config.months || [];
                    const weeks = config.weeks || [];
                    if (months.includes(currentMonthName) && weeks.includes(weekStrShort)) {
                        return true;
                    }
                } else if (routineType === 'Custom') {
                    const customDate = config.date || '';
                    const todayDateString = today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0') + '-' + String(today.getDate()).padStart(2, '0');
                    if (customDate === todayDateString) {
                        return true;
                    }
                }
                return false;
            }

            // Build session card DOM
            function buildSessionCard(sch) {
                const config = sch.routine_config || {};
                const schColor = config.color || '';
                const colorMap = {
                    'green': '#38b2ac',
                    'purple': '#a855f7',
                    'blue': '#3b82f6',
                    'white': '#e5e7eb'
                };
                const colorHex = colorMap[schColor] || '';
                const colorStyle = colorHex ? `style="background: ${colorHex}; box-shadow: 0 0 10px ${colorHex};"` : '';

                const { isLive, isPast, isUpcoming, durationMinutes } = checkSessionStatus(sch.start_time, sch.end_time);
                const recurrenceText = getRecurrenceText(sch.routine_type, config);

                let cardClass = 'session-card';
                if (isLive) cardClass += ' active-now';
                cardClass += ' animate-new-card';

                let indicatorHTML = '';
                if (isLive) {
                    indicatorHTML = `<div class="session-active-indicator" ${colorStyle}></div>`;
                } else {
                    indicatorHTML = `<div class="session-divider" ${colorStyle}></div>`;
                }

                let tagsHTML = '';
                if (isLive) {
                    tagsHTML += `<span class="session-tag live">🔴 Live</span>`;
                }
                tagsHTML += `<span class="session-tag">${sch.topic}</span>`;
                tagsHTML += `<span class="session-tag">${sch.routine_type}</span>`;
                if (recurrenceText) {
                    tagsHTML += `<span class="session-tag"><i class='bx bx-refresh' style="vertical-align: middle; margin-right: 2px;"></i> ${recurrenceText}</span>`;
                }

                let statusHTML = '';
                if (isLive) {
                    statusHTML = `<div class="session-status live">Sedang berlangsung</div>`;
                } else if (isPast) {
                    statusHTML = `<div class="session-status">Selesai</div>`;
                } else {
                    statusHTML = `<div class="session-status">Akan datang</div>`;
                }

                const descSuffix = sch.description ? ` - ${sch.description}` : '';
                const escapedSch = JSON.stringify(sch).replace(/"/g, '&quot;');

                const cardEl = document.createElement('div');
                cardEl.className = cardClass;
                cardEl.innerHTML = `
                    <div class="session-time-col">
                        <div class="session-time">${sch.start_time}</div>
                        <div class="session-dur">${durationMinutes} mnt</div>
                    </div>
                    ${indicatorHTML}
                    <div class="session-info">
                        <div class="session-subject">${sch.course}</div>
                        <div class="session-topic">${sch.title}${descSuffix}</div>
                        <div class="session-tags">
                            ${tagsHTML}
                        </div>
                    </div>
                    ${statusHTML}
                    <div class="session-actions">
                        <button type="button" class="btn-action-premium edit-sch-btn" data-id="${sch.id}" data-schedule="${escapedSch}" title="Edit Jadwal">
                            <i class='bx bx-edit-alt'></i>
                        </button>
                        <button type="button" class="btn-action-premium delete-sch-btn" data-id="${sch.id}" title="Hapus Jadwal">
                            <i class='bx bx-trash'></i>
                        </button>
                    </div>
                `;
                return cardEl;
            }

            // Insert card to grid dynamically with sorting
            function insertCardToGrid(gridId, cardEl, startTime) {
                const grid = document.getElementById(gridId);
                if (!grid) return;

                // If there is an empty state container (no session-card element exists), clear it first
                if (!grid.querySelector('.session-card')) {
                    grid.innerHTML = '';
                }

                const cards = Array.from(grid.querySelectorAll('.session-card'));
                let inserted = false;
                for (const card of cards) {
                    const timeEl = card.querySelector('.session-time');
                    if (timeEl && timeEl.textContent.trim() > startTime) {
                        grid.insertBefore(cardEl, card);
                        inserted = true;
                        break;
                    }
                }
                if (!inserted) {
                    grid.appendChild(cardEl);
                }
            }

            function validateStep(stepIndex) {
                if (stepIndex === 0) {
                    const activeTopic = document.querySelector('.topic-card.active');
                    if (!activeTopic) {
                        return "Silakan pilih salah satu materi belajar terlebih dahulu.";
                    }
                } else if (stepIndex === 1) {
                    const selectedCourse = document.querySelector('.course-box.selected');
                    if (!selectedCourse) {
                        return "Silakan pilih salah satu course yang ingin Anda pelajari.";
                    }
                } else if (stepIndex === 2) {
                    const title = document.getElementById('inputJudul').value.trim();
                    if (!title) {
                        return "Kolom Judul Jadwal wajib diisi.";
                    }
                } else if (stepIndex === 3) {
                    const selectedColor = document.querySelector('.color-option.selected');
                    if (!selectedColor) {
                        return "Silakan pilih salah satu warna penanda.";
                    }
                } else if (stepIndex === 4) {
                    const selectedTimeBox = document.querySelector('.time-box.selected');
                    if (!selectedTimeBox) {
                        return "Silakan pilih salah satu opsi rutinitas waktu.";
                    }

                    const routineType = selectedTimeBox.getAttribute('data-type');
                    if (routineType === 'Harian') {
                        const activeDays = document.querySelectorAll('#configHarian .day-btn.active');
                        if (activeDays.length === 0) {
                            return "Pilih minimal satu hari untuk opsi Harian.";
                        }
                    } else if (routineType === 'Mingguan') {
                        const activeDays = document.querySelectorAll('#configMingguan .form-group:nth-child(1) .day-btn.active');
                        const activeWeeks = document.querySelectorAll('#configMingguan .form-group:nth-child(2) .day-btn.active');
                        if (activeDays.length === 0) {
                            return "Pilih minimal satu hari untuk opsi Mingguan.";
                        }
                        if (activeWeeks.length === 0) {
                            return "Pilih minimal satu minggu untuk opsi Mingguan.";
                        }
                    } else if (routineType === 'Bulanan') {
                        const activeMonths = document.querySelectorAll('#configBulanan .form-group:nth-child(1) .day-btn.active');
                        const activeWeeks = document.querySelectorAll('#configBulanan .form-group:nth-child(2) .day-btn.active');
                        if (activeMonths.length === 0) {
                            return "Pilih minimal satu bulan untuk opsi Bulanan.";
                        }
                        if (activeWeeks.length === 0) {
                            return "Pilih minimal satu minggu untuk opsi Bulanan.";
                        }
                    } else if (routineType === 'Custom') {
                        const customDate = document.querySelector('#configCustom input[type="date"]').value;
                        if (!customDate) {
                            return "Pilih tanggal kustom untuk opsi Custom.";
                        }
                    }
                } else if (stepIndex === 5) {
                    const timeStartVal = document.getElementById('timeStart').value;
                    const timeEndVal = document.getElementById('timeEnd').value;
                    if (!timeStartVal || !timeEndVal) {
                        return "Waktu mulai dan waktu selesai wajib ditentukan.";
                    }
                }
                return true;
            }

            // Navigation
            function updateSlider() {
                slider.style.transform = `translateX(-${currentStep * (100 / 6)}%)`;

                if(currentStep === 0) {
                    btnPrev.style.visibility = 'hidden';
                } else {
                    btnPrev.style.visibility = 'visible';
                }

                if(currentStep === totalSteps - 1) {
                    btnNext.textContent = 'Simpan';
                } else {
                    btnNext.textContent = 'Lanjut';
                }
            }

            btnNext.addEventListener('click', async () => {
                const validationResult = validateStep(currentStep);
                if (validationResult !== true) {
                    showError(validationResult);
                    return;
                }

                if(currentStep < totalSteps - 1) {
                    currentStep++;
                    updateSlider();
                } else {
                    // SIMPAN LOGIC HERE
                    btnNext.textContent = isEditMode ? 'Memperbarui...' : 'Menyimpan...';
                    btnNext.disabled = true;

                    const activeTopic = document.querySelector('.topic-card.active .topic-title-active');
                    const selectedCourse = document.querySelector('.course-box.selected .course-name');

                    const topic = activeTopic ? activeTopic.textContent.trim() : 'Umum';
                    const course = selectedCourse ? selectedCourse.textContent.trim() : 'Umum';
                    const title = document.getElementById('inputJudul').value || 'Jadwal Baru';
                    const description = document.getElementById('inputDeskripsi').value || '';

                    let timeStartVal = document.getElementById('timeStart').value;
                    let timeEndVal = document.getElementById('timeEnd').value;
                    if (timeStartVal && timeStartVal.length > 5) timeStartVal = timeStartVal.substring(0, 5);
                    if (timeEndVal && timeEndVal.length > 5) timeEndVal = timeEndVal.substring(0, 5);

                    const selectedTimeBox = document.querySelector('.time-box.selected');
                    const routineType = selectedTimeBox ? selectedTimeBox.getAttribute('data-type') : 'Harian';

                    // Parse Routine Config
                    let routineConfig = {};
                    if(routineType === 'Harian') {
                        const activeDays = Array.from(document.querySelectorAll('#configHarian .day-btn.active')).map(el => el.textContent.trim());
                        routineConfig = { days: activeDays };
                    } else if(routineType === 'Mingguan') {
                        const activeDays = Array.from(document.querySelectorAll('#configMingguan .form-group:nth-child(1) .day-btn.active')).map(el => el.textContent.trim());
                        const activeWeeks = Array.from(document.querySelectorAll('#configMingguan .form-group:nth-child(2) .day-btn.active')).map(el => el.textContent.trim());
                        routineConfig = { days: activeDays, weeks: activeWeeks };
                    } else if(routineType === 'Bulanan') {
                        const activeMonths = Array.from(document.querySelectorAll('#configBulanan .form-group:nth-child(1) .day-btn.active')).map(el => el.textContent.trim());
                        const activeWeeks = Array.from(document.querySelectorAll('#configBulanan .form-group:nth-child(2) .day-btn.active')).map(el => el.textContent.trim());
                        routineConfig = { months: activeMonths, weeks: activeWeeks };
                    } else if(routineType === 'Custom') {
                        const customDate = document.querySelector('#configCustom input[type="date"]').value;
                        routineConfig = { date: customDate };
                    }

                    // Extract color picker value
                    const selectedColorOpt = document.querySelector('.color-option.selected');
                    const colorVal = selectedColorOpt ? selectedColorOpt.getAttribute('data-color') : 'green';
                    routineConfig.color = colorVal;

                    const payload = {
                        topic,
                        course,
                        title,
                        description,
                        routine_type: routineType,
                        routine_config: routineConfig,
                        start_time: timeStartVal,
                        end_time: timeEndVal
                    };

                    try {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        const url = isEditMode ? `/jadwal/${editScheduleId}` : '/jadwal';
                        const method = isEditMode ? 'PUT' : 'POST';

                        const res = await fetch(url, {
                            method: method,
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify(payload)
                        });

                        if(res.ok) {
                            const resJson = await res.json();
                            const sch = resJson.schedule;

                            // 1. Close Modal immediately
                            modal.classList.remove('show');
                            setTimeout(() => { modal.style.display = 'none'; }, 300);

                            // If we were in Edit Mode, we need to subtract the old schedule's stats and remove its old card
                            if (isEditMode && originalSchedule) {
                                const oldActiveToday = isScheduleActiveToday(originalSchedule.routine_type, originalSchedule.routine_config);
                                if (oldActiveToday) {
                                    // Decrement count
                                    const countEl = document.getElementById('todaySessionsCount');
                                    if (countEl) {
                                        const currentCount = parseInt(countEl.textContent.trim()) || 0;
                                        countEl.textContent = Math.max(0, currentCount - 1);
                                    }

                                    // Subtract duration
                                    const totalHoursEl = document.getElementById('totalStudyHours');
                                    if (totalHoursEl) {
                                        const { durationMinutes } = checkSessionStatus(originalSchedule.start_time, originalSchedule.end_time);
                                        const currentHoursStr = totalHoursEl.textContent.trim().replace('h', '');
                                        const currentHours = parseFloat(currentHoursStr) || 0;
                                        const newHours = Math.max(0, currentHours - (durationMinutes / 60)).toFixed(1);
                                        totalHoursEl.textContent = `${newHours}h`;
                                    }
                                }

                                // Remove old card
                                const oldCard = document.querySelector(`.edit-sch-btn[data-id="${originalSchedule.id}"]`);
                                if (oldCard) {
                                    oldCard.closest('.session-card').remove();
                                }
                            }

                            // 2. Determine if active today & insert card dynamically
                            const activeToday = isScheduleActiveToday(sch.routine_type, sch.routine_config);
                            const cardEl = buildSessionCard(sch);

                            if (activeToday) {
                                insertCardToGrid('todaySessionsGrid', cardEl, sch.start_time);

                                // Update summary stats
                                const countEl = document.getElementById('todaySessionsCount');
                                if (countEl) {
                                    const currentCount = parseInt(countEl.textContent.trim()) || 0;
                                    countEl.textContent = currentCount + 1;
                                }

                                const totalHoursEl = document.getElementById('totalStudyHours');
                                if (totalHoursEl) {
                                    const { durationMinutes } = checkSessionStatus(sch.start_time, sch.end_time);
                                    const currentHoursStr = totalHoursEl.textContent.trim().replace('h', '');
                                    const currentHours = parseFloat(currentHoursStr) || 0;
                                    const newHours = (currentHours + (durationMinutes / 60)).toFixed(1);
                                    totalHoursEl.textContent = `${newHours}h`;
                                }
                            } else {
                                insertCardToGrid('upcomingSessionsGrid', cardEl, sch.start_time);
                            }

                            // Check grids empty state
                            ['todaySessionsGrid', 'upcomingSessionsGrid'].forEach(gridId => {
                                const grid = document.getElementById(gridId);
                                if (grid) {
                                    if (!grid.querySelector('.session-card')) {
                                        grid.innerHTML = `
                                            <div style="background: rgba(255,255,255,0.02); border: 2px dashed rgba(255,255,255,0.05); border-radius: 24px; padding: 3rem; text-align: center;">
                                                <i class='bx bx-calendar-x' style="font-size: 3rem; color: #6b6570; margin-bottom: 1rem;"></i>
                                                <div style="font-weight: 700; color: white; font-size: 1.1rem; margin-bottom: 0.25rem;">Tidak ada jadwal</div>
                                                <div style="color: #6b6570; font-size: 0.85rem;">Mau bikin sesi belajar baru? klik "+ Tambah Jadwal" di atas!</div>
                                            </div>
                                        `;
                                    } else {
                                        // If there is any element with text "Tidak ada jadwal" or empty-state dashed box, remove it
                                        const dashBox = Array.from(grid.childNodes).find(n => n.nodeType === Node.ELEMENT_NODE && !n.classList.contains('session-card'));
                                        if (dashBox) dashBox.remove();
                                    }
                                }
                            });

                            // 3. Show premium success toast
                            const toast = document.getElementById('premiumToast');
                            const toastTitle = document.getElementById('premiumToastTitle');
                            const toastDesc = document.getElementById('premiumToastDesc');
                            if (toast && toastTitle && toastDesc) {
                                toastTitle.textContent = "Sukses!";
                                toastDesc.textContent = isEditMode ? `Jadwal "${sch.title}" berhasil diperbarui.` : `Jadwal "${sch.title}" berhasil disimpan.`;
                                toast.classList.add('show');
                            }

                            // 4. Background reload page after 3.5 seconds
                            setTimeout(() => {
                                if (toast) toast.classList.remove('show');
                                window.location.reload();
                            }, 3500);
                        } else {
                            console.error(await res.text());
                            showError("Gagal menyimpan jadwal.");
                        }
                    } catch(err) {
                        console.error(err);
                        showError("Terjadi kesalahan sistem.");
                    } finally {
                        btnNext.textContent = 'Simpan';
                        btnNext.disabled = false;
                    }
                }
            });

            btnPrev.addEventListener('click', () => {
                if(currentStep > 0) {
                    currentStep--;
                    updateSlider();
                }
            });

            // Course Data Mapping
            const coursesData = {
                'Front End': [
                    { name: 'Dasar HTML', icon: 'bx bxl-html5' },
                    { name: 'CSS Modern', icon: 'bx bxl-css3' },
                    { name: 'Javascript', icon: 'bx bxl-javascript' },
                    { name: 'React JS', icon: 'bx bxl-react' }
                ],
                'Back End': [
                    { name: 'PHP Dasar', icon: 'bx bxl-php' },
                    { name: 'Laravel Framework', icon: 'bx bxl-laravel' },
                    { name: 'Node JS', icon: 'bx bxl-nodejs' },
                    { name: 'Database SQL', icon: 'bx bx-data' }
                ],
                'Data Analyze': [
                    { name: 'Python Data Science', icon: 'bx bxl-python' },
                    { name: 'Pandas & NumPy', icon: 'bx bx-table' },
                    { name: 'Visualisasi Data', icon: 'bx bx-bar-chart-alt-2' },
                    { name: 'Tableau & SQL', icon: 'bx bx-pie-chart-alt-2' }
                ],
                'Full Stack Dev': [
                    { name: 'Fullstack Laravel & Vue', icon: 'bx bxl-vuejs' },
                    { name: 'MERN Stack', icon: 'bx bxl-mongodb' },
                    { name: 'DevOps Dasar', icon: 'bx bx-server' },
                    { name: 'Git & GitHub Advanced', icon: 'bx bxl-git' }
                ]
            };

            const courseGrid = document.querySelector('.course-grid');

            function renderCourses(topicName) {
                const courses = coursesData[topicName] || [];
                courseGrid.innerHTML = '';

                courses.forEach((course, index) => {
                    const courseBox = document.createElement('div');
                    courseBox.className = `course-box${index === 1 ? ' selected' : ''}`; // select 2nd by default
                    courseBox.innerHTML = `
                        <i class='${course.icon} course-icon'></i>
                        <div class="course-name">${course.name}</div>
                    `;

                    courseBox.addEventListener('click', () => {
                        document.querySelectorAll('.course-box').forEach(c => c.classList.remove('selected'));
                        courseBox.classList.add('selected');
                    });

                    courseGrid.appendChild(courseBox);
                });
            }

            // Topic Cards Interaction (Slide 1)
            const topicCards = document.querySelectorAll('.topic-card');
            topicCards.forEach(card => {
                card.addEventListener('click', () => {
                    topicCards.forEach(c => c.classList.remove('active'));
                    card.classList.add('active');

                    // Update courses grid on Slide 2
                    const topicTitle = card.querySelector('.topic-title-active');
                    if (topicTitle) {
                        renderCourses(topicTitle.textContent.trim());
                    }
                });
            });

            // Initial Course Box Selection (Slide 2)
            const courseBoxes = document.querySelectorAll('.course-box');
            courseBoxes.forEach(box => {
                box.addEventListener('click', () => {
                    document.querySelectorAll('.course-box').forEach(c => c.classList.remove('selected'));
                    box.classList.add('selected');
                });
            });

            // Color Selection (Slide 3)
            const colorOptions = document.querySelectorAll('.color-option');
            colorOptions.forEach(opt => {
                opt.addEventListener('click', () => {
                    colorOptions.forEach(c => c.classList.remove('selected'));
                    opt.classList.add('selected');
                });
            });

            // Initial render course list based on default active topic card
            const activeTopicOnLoad = document.querySelector('.topic-card.active .topic-title-active');
            if (activeTopicOnLoad) {
                renderCourses(activeTopicOnLoad.textContent.trim());
            }

            // Time Box Selection (Slide 4)
            const timeBoxes = document.querySelectorAll('.time-box');
            const configPanels = document.querySelectorAll('.config-panel');
            timeBoxes.forEach(box => {
                box.addEventListener('click', () => {
                    timeBoxes.forEach(c => c.classList.remove('selected'));
                    box.classList.add('selected');

                    const type = box.getAttribute('data-type');
                    configPanels.forEach(panel => {
                        panel.classList.remove('active');
                    });
                    const targetPanel = document.getElementById('config' + type);
                    if(targetPanel) {
                        targetPanel.classList.add('active');
                    }
                });
            });

            // Day/Week Button Selection
            const dayBtns = document.querySelectorAll('.day-btn');
            dayBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    btn.classList.toggle('active');
                });
            });

            // Auto Generate Text (Slide 3)
            const btnAutoGenerate = document.getElementById('btnAutoGenerate');
            const inputJudul = document.getElementById('inputJudul');
            const inputDeskripsi = document.getElementById('inputDeskripsi');

            if (btnAutoGenerate) {
                btnAutoGenerate.addEventListener('click', () => {
                    const activeTopic = document.querySelector('.topic-card.active .topic-title-active');
                    const selectedCourse = document.querySelector('.course-box.selected .course-name');

                    let topicText = activeTopic ? activeTopic.textContent.trim() : 'Materi Umum';
                    let courseText = selectedCourse ? selectedCourse.textContent.trim() : 'Pelajaran';

                    if (inputJudul) inputJudul.value = `Sesi Belajar ${courseText} - ${topicText}`;

                    const descriptions = [
                        `Fokus sesi ini adalah menguasai materi ${courseText} dari kurikulum ${topicText}. Targetkan untuk memahami teori fundamental dan langsung praktik mini koding.`,
                        `Mendalami ${courseText} khususnya pada area ${topicText}. Ingat untuk mengecek dokumentasi, menyelesaikan challenge, dan push hasil latihan.`,
                        `Waktu dedikasi khusus untuk latihan ${courseText} (${topicText}). Jangan lupa review ulang bagian yang masih belum paham kemarin.`
                    ];

                    if (inputDeskripsi) inputDeskripsi.value = descriptions[Math.floor(Math.random() * descriptions.length)];

                    btnAutoGenerate.innerHTML = "<i class='bx bx-check'></i> Berhasil Dibuat!";
                    setTimeout(() => {
                        btnAutoGenerate.innerHTML = "<i class='bx bx-magic-wand'></i> Auto Generate";
                    }, 2000);
                });
            }

            // Initial default time setup on page load based on current local time
            if (timeStart && timeEnd) {
                const now = new Date();
                const startHour = String(now.getHours()).padStart(2, '0');
                const startMin = String(now.getMinutes()).padStart(2, '0');
                const endHour = String((now.getHours() + 1) % 24).padStart(2, '0');
                timeStart.value = `${startHour}:${startMin}`;
                timeEnd.value = `${endHour}:${startMin}`;
                calcDuration();
            }

            // Duration Calculator (Slide 5) input listeners
            if (timeStart) {
                timeStart.addEventListener('input', () => {
                    const val = timeStart.value;
                    if (val) {
                        const [h, m] = val.split(':').map(Number);
                        const endH = String((h + 1) % 24).padStart(2, '0');
                        const endM = String(m).padStart(2, '0');
                        if (timeEnd) timeEnd.value = `${endH}:${endM}`;
                    }
                    calcDuration();
                });
            }
            if (timeEnd) timeEnd.addEventListener('input', calcDuration);

            // Auto-open modal logic based on URL params
            const urlParams = new URLSearchParams(window.location.search);
            const action = urlParams.get('action');
            const topicParam = urlParams.get('topic');

            if (action === 'add_schedule' && topicParam) {
                setTimeout(() => {
                    if (typeof resetWizard === 'function') resetWizard();
                    if (modal) {
                        modal.style.display = 'flex';
                        modal.offsetHeight; // trigger reflow
                        modal.classList.add('show');
                    }
                    
                    // Find matching topic card
                    const tCards = document.querySelectorAll('.topic-card');
                    tCards.forEach(tc => {
                        const titleEl = tc.querySelector('.topic-title-active');
                        if (titleEl && titleEl.textContent.trim().toLowerCase() === topicParam.toLowerCase()) {
                            tCards.forEach(c => c.classList.remove('active'));
                            tc.classList.add('active');
                        }
                    });

                    // Move to slide 2
                    if (typeof updateSlider === 'function') {
                        currentStep = 1;
                        updateSlider();
                    }
                    
                    // Clear query params so it doesn't trigger again on refresh
                    const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
                    window.history.replaceState({path:newUrl}, '', newUrl);
                }, 300); // slight delay to ensure DOM is ready and animations can trigger
            }
            // Expose openJadwalModal globally
            window.openJadwalModal = function(topicName) {
                if (typeof resetWizard === 'function') resetWizard();
                if (modal) {
                    modal.style.display = 'flex';
                    modal.offsetHeight; // trigger reflow
                    modal.classList.add('show');
                }
                
                if (topicName) {
                    // Find matching topic card
                    const tCards = document.querySelectorAll('.topic-card');
                    tCards.forEach(tc => {
                        const titleEl = tc.querySelector('.topic-title-active');
                        if (titleEl && titleEl.textContent.trim().toLowerCase() === topicName.toLowerCase()) {
                            tCards.forEach(c => c.classList.remove('active'));
                            tc.classList.add('active');
                        }
                    });

                    // Render courses for active topic
                    if (typeof renderCourses === 'function') {
                        renderCourses(topicName);
                    }

                    // Move to slide 2 (index 1) directly
                    currentStep = 1;
                    if (typeof updateSlider === 'function') {
                        updateSlider();
                    }
                }
            };

            // Find best course box match for the given submaterial name
            function findBestCourseMatch(coursesList, submateriName) {
                if (!submateriName) return coursesList[0] ? coursesList[0].name : '';
                const normSub = submateriName.toLowerCase().replace(/\s+/g, '');
                
                // 1. Exact match
                let match = coursesList.find(c => c.name.toLowerCase() === submateriName.toLowerCase());
                if (match) return match.name;
                
                // 2. Submateri name is contained in Course name (e.g. 'html' matches 'Dasar HTML')
                match = coursesList.find(c => c.name.toLowerCase().includes(submateriName.toLowerCase()));
                if (match) return match.name;

                // 3. Course name is contained in Submateri name (e.g. 'javascript' matches 'Javascript')
                match = coursesList.find(c => submateriName.toLowerCase().includes(c.name.toLowerCase()));
                if (match) return match.name;
                
                // 4. Special manual mappings
                if (normSub === 'mysql') {
                    const sqlMatch = coursesList.find(c => c.name.toLowerCase().includes('sql') || c.name.toLowerCase().includes('database'));
                    if (sqlMatch) return sqlMatch.name;
                }
                if (normSub === 'python') {
                    const pyMatch = coursesList.find(c => c.name.toLowerCase().includes('python'));
                    if (pyMatch) return pyMatch.name;
                }
                if (normSub === 'arsitekturmodern') {
                    // Fallback to the first course in Full Stack Dev
                    return coursesList[0] ? coursesList[0].name : '';
                }

                // Default: return the first course in the list
                return coursesList[0] ? coursesList[0].name : '';
            }

            // Click listener for the inline "Jadwalkan" dashboard buttons
            document.addEventListener('click', (e) => {
                const btnJadwalkan = e.target.closest('.btn-jadwalkan-modal');
                if (!btnJadwalkan) return;

                e.preventDefault();
                e.stopPropagation();

                const targetMateri = btnJadwalkan.getAttribute('data-materi') || 'Front End';
                const targetSubmateri = btnJadwalkan.getAttribute('data-submateri') || '';

                // Reset wizard first
                resetWizard();

                // 1. Select the correct topic card in Slide 1
                let foundTopic = false;
                const tCards = document.querySelectorAll('.topic-card');
                tCards.forEach(tc => {
                    const titleEl = tc.querySelector('.topic-title-active');
                    if (titleEl && titleEl.textContent.trim().toLowerCase() === targetMateri.trim().toLowerCase()) {
                        tCards.forEach(c => c.classList.remove('active'));
                        tc.classList.add('active');
                        foundTopic = true;
                    }
                });

                // 2. Render courses list for the topic on Slide 2
                const actualTopicName = foundTopic ? targetMateri.trim() : 'Front End';
                renderCourses(actualTopicName);

                // 3. Find matching course box on Slide 2 and select it
                const coursesList = coursesData[actualTopicName] || [];
                const matchedCourseName = findBestCourseMatch(coursesList, targetSubmateri);

                document.querySelectorAll('.course-box').forEach(box => {
                    const nameEl = box.querySelector('.course-name');
                    if (nameEl && nameEl.textContent.trim().toLowerCase() === matchedCourseName.toLowerCase()) {
                        document.querySelectorAll('.course-box').forEach(c => c.classList.remove('selected'));
                        box.classList.add('selected');
                    }
                });

                // 4. Auto Generate description/title based on selection
                const activeTopic = document.querySelector('.topic-card.active .topic-title-active');
                const selectedCourse = document.querySelector('.course-box.selected .course-name');
                let topicText = activeTopic ? activeTopic.textContent.trim() : 'Materi Umum';
                let courseText = selectedCourse ? selectedCourse.textContent.trim() : 'Pelajaran';

                if (inputJudul) inputJudul.value = `Sesi Belajar ${courseText} - ${topicText}`;
                const descriptions = [
                    `Fokus sesi ini adalah menguasai materi ${courseText} dari kurikulum ${topicText}. Targetkan untuk memahami teori fundamental dan langsung praktik mini koding.`,
                    `Mendalami ${courseText} khususnya pada area ${topicText}. Ingat untuk mengecek dokumentasi, menyelesaikan challenge, dan push hasil latihan.`,
                    `Waktu dedikasi khusus untuk latihan ${courseText} (${topicText}). Jangan lupa review ulang bagian yang masih belum paham kemarin.`
                ];
                if (inputDeskripsi) inputDeskripsi.value = descriptions[0];

                // 5. Jump directly to Slide 3 (index 2)
                currentStep = 2;
                updateSlider();

                // 6. Display the modal
                modal.style.display = 'flex';
                modal.offsetHeight; // trigger reflow
                modal.classList.add('show');

                // 7. Auto focus the input title for smooth UX
                setTimeout(() => {
                    if (inputJudul) inputJudul.focus();
                }, 400);
            });
        })();
