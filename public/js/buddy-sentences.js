/**
 * TurnCode Buddy Sentence Library & Generator
 * Contains >2300 combinations of tips, motivation, and humor.
 */

(function () {
    const selectRandom = (arr) => arr[Math.floor(Math.random() * arr.length)];

    const tipsOpeners = [
        "Coba deh...",
        "Salah satu tips terbaik dari aku:",
        "Penting banget buat...",
        "Tahu gak sih?",
        "Jangan lupa kalau...",
        "Tips coding hari ini:",
        "Biar skill kamu makin jago...",
        "Ingat ya, selalu biasakan untuk...",
        "Biar belajarmu makin efektif...",
        "Rekomendasi dari aku..."
    ];

    const tipsActions = [
        "menulis kodenya sendiri secara manual tanpa copy-paste,",
        "mencoba teknik Pomodoro (belajar 25 menit, istirahat 5 menit),",
        "membuat proyek kecil/sederhana dari apa yang baru saja dipelajari,",
        "membaca dokumentasi resmi atau berselancar di StackOverflow,",
        "menulis komentar penjelas yang rapi pada setiap baris kodenya,",
        "mengistirahatkan matamu selama 10 menit setelah menatap layar komputer,",
        "menganalisis error pesan compiler dengan tenang dan teliti,",
        "menjelaskan baris kodemu ke orang lain atau bahkan bebek karet,",
        "merencanakan alur logika program sebelum mulai menulis baris kode,",
        "mencoba bereksperimen mengubah nilai variabel untuk melihat hasilnya,",
        "mengulangi materi yang dirasa sulit sebanyak dua sampai tiga kali,"
    ];

    const tipsClosers = [
        "karena ini bakal meningkatkan pemahaman logikamu secara drastis! 🎯",
        "supaya otak kita tetap segar dan terhindar dari kejenuhan. 🧠",
        "soalnya praktek langsung itu kunci mutlak kesuksesan developer! 💻",
        "ini adalah kebiasaan programmer pro yang sangat berharga. 🚀",
        "dijamin deh, pemahamanmu bakal nempel jauh lebih lama! ✨",
        "agar perjalanan belajarmu terasa lebih menyenangkan dan santai. 😊",
        "karena konsistensi harian jauh lebih penting dibanding belajar dadakan. 💪",
        "ini trik rahasia yang jarang dibagikan di kelas coding konvensional! 🤫",
        "supaya kamu gak gampang stuck saat menghadapi error nanti. 🛠️",
        "hehehe, cobain deh dan lihat hasilnya dalam waktu dekat! 📈"
    ];

    const motivasiOpeners = [
        "Ingat kata pepatah dev:",
        "Jangan pernah berkecil hati,",
        "Setiap programmer hebat yang kamu kenal hari ini",
        "Perjalanan satu juta baris kode",
        "Saat kamu merasa lelah dan mentok,",
        "Kesalahan atau error hari ini",
        "Percayalah pada proses belajarmu,",
        "Fokus saja pada progress kecilmu,",
        "Coding bukan cuma tentang bakat,",
        "Ingat tujuan awalmu belajar coding,",
        "Selangkah demi selangkah,"
    ];

    const motivasiMids = [
        "adalah bekal investasi terbaik untuk masa depan karirmu,",
        "dimulai dari satu baris print hello world yang sederhana,",
        "dulunya juga seorang pemula yang menolak untuk menyerah,",
        "sedang membentuk intuisi problem-solving yang lebih tajam,",
        "adalah batu loncatan berharga menuju level keahlian berikutnya,",
        "akan membawa dampak luar biasa yang mengubah hidupmu kelak,",
        "bisa diselesaikan dengan baik asal kamu terus berjuang,",
        "sedang menuntunmu menjadi pencipta teknologi hebat selanjutnya,",
        "jauh lebih baik daripada diam di tempat tanpa mencoba,",
        "membutuhkan ketekunan dan dedikasi yang konsisten setiap hari,"
    ];

    const motivasiClosers = [
        "jadi tetaplah melangkah ke depan! 💪",
        "tetap semangat dan gass terus belajarnya! 🚀",
        "kamu pasti bisa menaklukkannya! 🔥",
        "karena hasil tidak akan pernah mengkhianati usaha. ✨",
        "kamu sedang melakukan hal yang luar biasa hari ini! ⭐",
        "ingat, error itu sahabat terbaik programmer! 💻",
        "yuk, fokus satu materi lagi untuk hari ini! 🎯",
        "masa depan digital ada di tanganmu! 🌐",
        "rasakan kepuasan luar biasa saat programmu berhasil berjalan! 👑",
        "satu baris kode hari ini adalah kemajuan besar untuk esok hari! 📈"
    ];

    const humorJokes = [
        "Kenapa programmer suka kopi hangat? Karena kalau dingin dia jadi Java! ☕",
        "Ada 10 jenis orang di dunia: mereka yang mengerti biner, dan mereka yang tidak. 🤓",
        "Kenapa CSS itu susah? Karena memusatkan div saja butuh perjuangan batin yang mendalam! 😭",
        "Programmer tidak pernah malas, mereka hanya sedang menjalankan proses optimasi energi tubuh. 🔋",
        "Keyboard-ku punya tombol favorit: Ctrl + C dan Ctrl + V. Dua pahlawan tanpa tanda jasa! 🦸‍♂️",
        "Bagaimana cara programmer merayu gebetan? 'Kamu adalah semicolon-ku, tanpamu hidupku terasa error.' 💕",
        "Kenapa komputer tidak pernah bisa tidur siang? Karena mereka punya banyak sekali tab yang aktif! 💻",
        "Ada tiga hal paling sulit di dunia IT: naming variables, cache invalidation, dan memusatkan gambar secara vertikal. 🤯",
        "Mengapa programmer lebih suka menggunakan dark mode? Karena cahaya menarik perhatian serangga (bugs)! 🪲",
        "Seorang istri menyuruh suaminya yang programmer: 'Tolong beli roti satu pak, dan kalau ada telur, beli sepuluh.' Suaminya pulang membawa 10 pak roti karena di toko ada telur. 🥚",
        "Di mana tempat terbaik menyembunyikan mayat? Di halaman kedua hasil pencarian Google, karena tak ada yang pernah ke sana. 🔍",
        "Pernyataan programmer: 'Itu bukan bug, itu adalah fitur yang tidak terdokumentasi dengan baik!' 🤷‍♂️",
        "Komputer itu seperti manusia: mereka melakukan apa yang kita perintahkan, bukan apa yang kita inginkan. 🤖",
        "Kenapa HTML gak diajak main ke sirkus? Karena dia gak punya class! 🎪",
        "Kemarin aku ketiduran di atas keyboard. Pas bangun, aku sudah jadi senior JavaScript developer karena kodenya jalan semua secara ajaib! 😴",
        "Programmer sejati itu kalau jalan di mal, bukannya nyari diskon baju tapi nyari Wi-Fi gratisan yang kencang! 📶"
    ];

    const humorSubs = ["Programmer", "Ngoding CSS", "Nyari bug di production", "Nge-merge code rekan setim", "Proses compiling"];
    const humorActs = ["itu bagaikan roller coaster tanpa sabuk pengaman,", "selalu penuh kejutan mistis nan ajaib,", "terkadang terasa lebih berat daripada ujian hidup,", "membutuhkan asupan kopi hangat dalam jumlah ekstrem,", "bisa bikin kita senyum sendiri pas kodenya sukses run,"];
    const humorReacts = ["wkwkwk, setuju gak? 😂", "tapi seru banget kalau udah berhasil! ☕", "makanya jangan lupa selalu berdoa sebelum nge-push! 🛠️", "sungguh petualangan emosi yang tiada tanding! 🎢", "badum-tsss~ 🥁"];

    const greetings = [
        "Halo! Aku teman belajarmu. Siap belajar hari ini? 👋",
        "Hai hai! Senang bertemu kamu lagi. Mau coding apa kita sekarang? 😊",
        "Yo! Siap menaklukkan barisan kode hari ini? Gass! 🚀"
    ];

    const fatigue = [
        "Capek ya? Gak apa-apa, istirahat dulu 5-10 menit. Minum air putih hangat ya! ☕",
        "Kalau lelah, rehat dulu. Otak juga butuh compile ulang memori, hehehe. 🧠",
        "Coding emang butuh energi. Ambil cemilan dulu yuk, ntar kita lanjut lagi! 🍪"
    ];

    const identity = [
        "Aku adalah Buddy, asisten setia yang siap nemenin kamu ngoding sampai sukses! 🤖",
        "Aku teman setiamu di TurnCode! Siap membantu memberikan motivasi, tips, dan jokes kocak! 💻"
    ];

    const confusion = [
        "Hmm, aku belum terlalu paham maksudmu. Tapi kalau kamu bingung, coba ketik 'tips' atau 'motivasi' ya! 💡",
        "Pertanyaan menarik! Sayangnya database-ku belum lengkap. Mau aku kasih 'tips belajar' aja? 😊"
    ];

    const personalityModifiers = {
        chill: {
            prefix: "Yo... Santai sejenak. ",
            suffix: " 🌿 Tetap kalem ya, seruput kopi dulu. ☕",
            greet: {
                morning: [
                    "Pagi bro/sist... Santai sejenak, seruput kopi/teh hangat dulu biar melek kalem. 🌿☕",
                    "Selamat pagi! Gak usah buru-buru, hari masih panjang. Mari mulai coding santaimu hari ini... 🌿☕",
                    "Pagi! Udah siap ngoding santai lagi? Tarik napas dalam-dalam, mari kita mulai secara perlahan. ✨🌿"
                ],
                afternoon: [
                    "Yo, siang bro/sist! Panas-panas gini enaknya coding santai di ruangan adem sambil dengerin lofi... 🌿✨",
                    "Siang! Santai aja, kalau mulai ngantuk/lelah, rileks sejenak sebelum lanjut baris kode berikutnya. ☕🌿",
                    "Yo, selamat siang! Mari jalani hari dengan tenang, satu baris kode demi satu baris kode... 🌿✨"
                ],
                evening: [
                    "Sore bro/sist! Vibe sore ini pas banget buat review santai materi belajarmu hari ini... ☕🌿",
                    "Selamat sore! Bersantailah sejenak, regangkan otot-ototmu sebelum lanjut lagi. 🌿✨",
                    "Sore! Sambil menikmati senja, mari coding pelan-pelan asal jalan. 🌿🍂"
                ],
                night: [
                    "Malam bro/sist... Angin malam pas banget ditemenin alunan lofi dan coding kalem. 🌿✨",
                    "Malam! Udah jam segini santai aja, gak usah terlalu dipaksa. Sedikit demi sedikit pasti selesai kok. ☕🌿",
                    "Malam! Nikmati kesunyian malam ini untuk merenung dan menulis baris kode dengan tenang... ✨🌿"
                ],
                weekend: [
                    "Yo, weekend tetap coding santai? Keren abis bro/sist, konsistensimu juara tapi jangan lupa tetap santai ya! 🌿☕",
                    "Selamat akhir pekan! Coding santai di hari libur emang paling dapet feel-nya. Enjoy your weekend! ✨🌿",
                    "Happy weekend! Gak usah pasang target berat, santai aja hari ini yang penting tetap ada kemajuan. 🌿☕"
                ],
                generic: [
                    "Halo bro/sist... santai aja, aku di sini nemenin kamu ngoding kalem. ☕🌿",
                    "Yo! Santai sejenak, ada yang bisa kubantu? Tanya tips atau statusmu aja. ✨🌿",
                    "Halo! Tetap tenang, rileks, dan nikmati setiap proses belajarmu hari ini. 🌿☕"
                ]
            },
            tired: "Capek ya? Rehat dulu, tiduran bentar. Gak usah buru-buru, proses itu dinikmati. 🌿☕",
            who: "Aku teman santaimu di TurnCode. Siap nemenin kamu sambil lofi-an. ✨",
            confused: "Aduh santai dulu, aku gak terlalu nangkep maksudmu. Coba nanya 'tips' atau 'motivasi' aja yuk. 🌿"
        },
        energetic: {
            prefix: "WOI GASSPOLL!! ",
            suffix: " 🔥 JANGAN KASIH KENDOR! SIKATT! 💪🚀",
            greet: {
                morning: [
                    "WOI KAWAN!! SELAMAT PAGI!! MATAHARI DAH TINGGI, BARA SEMANGATMU HARUS LEBIH TINGGI!! GASS NGODING!! 🔥🚀",
                    "PAGI KAWAN!! HARI BARU, TANTANGAN BARU! AYO KITA HANCURKAN SEGALA ERROR HARI INI!! GASSPOL!! 🔥💪🚀",
                    "SELAMAT PAGI!! SIAPKAN KOPIMU, SIAPKAN LOGIKAMU, KITA TAKLUKKAN MATERI HARI INI!! 🔥🚀💪"
                ],
                afternoon: [
                    "SIANG KAWAN!! HANGATNYA SIANG INI KITA JADIKAN BAHAN BAKAR SEMANGAT BELAJAR!! NO KENDOR-KENDOR!! 🔥💪",
                    "SELAMAT SIANG!! JANGAN BIARKAN RASA KANTUK MENGALAHKAN IMPIANMU!! AYO PUSH LAGI!! 🔥🚀",
                    "SIANG!! SIAP GASS NGODING LAGI? KUMPULKAN ENERGIMU, KITA MULAI SEKARANG!! GASSPOL!! 🔥💪🚀"
                ],
                evening: [
                    "SORE KAWAN!! HARI SUDAH SORE TAPI SEMANGATMU HARUS TETAP 100%!! SIKAT TERUS!! 🔥💪🚀",
                    "SORE!! SEMANGAT BERJUANGMU LUAR BIASA! AYO KITA BERESKAN MISI HARI INI BIAR MAKIN MANTAP!! 🔥🚀",
                    "SELAMAT SORE!! PANTASTIS!! JANGAN KASIH KENDOR SIKIT PUN HINGGA MISI SELESAI!! 🔥💪"
                ],
                night: [
                    "MALAM KAWAN!! LARUT MALAM BUKAN ALASAN! PUSH SATU MATERI LAGI SEBELUM TIDUR!! BIAR MAKIN JAGO!! 🔥💪🚀",
                    "MALAM!! INILAH SAATNYA PROGRAMMER HEBAT LAHIR, KETIKA YANG LAIN TIDUR KITA TETAP PUSH KODE!! GASS!! 🔥🚀",
                    "SELAMAT MALAM!! TETAP FOKUS HINGGA BARIS KODE TERAKHIR!! JANGAN KASIH LENYAP SEMANGATMU!! 🔥💪"
                ],
                weekend: [
                    "SABTU MINGGU TETAP GASS NGODING?!!! LUAR BIASA KAWAN!!! KAMU PASTI JADI MASTER DEVELOVER SEJATI!!! 🔥💪🚀",
                    "HAPPY WEEKEND!!! SAAT YANG LAIN REBAHAN, KITA GASSPOL BANGUN MASA DEPAN KITA HARI INI!!! SIKATT!! 🔥🚀💪",
                    "WEEKEND VIBES!!! GAS terus jangan kasih lepas, mahkota menantimu king/queen! JANGAN KASIH KENDOR! 🔥🚀"
                ],
                generic: [
                    "HALO KAWAN!! SIAP BERAKSI HARI INI? GASSPOLL NGODING!! 🔥💪🚀",
                    "AYO KAWAN!! TANYA AKU APA SAJA, KITA BERESKAN SEMUA HARI INI JUGA!! 🔥🚀",
                    "HALO! SEMANGAT PANTANG MENYERAH HARUS TETAP ADA DI DADAMU! AYO KITA BERJUANG! 🔥🚀"
                ]
            },
            tired: "CAPEK? AYOLAH! PUSH DIKIT LAGI! KAMU PASTI BISA KOK! SEMANGAT BARA!! 🔥🔥🔥",
            who: "AKU PERSONAL COACH-MU!! SIAP BIKIN KAMU JADI SUPER PROGRAMMER!! ⚡🚀",
            confused: "WADUH APA TUH? AKU KURANG PAHAM! COBA TANYA 'TIPS', 'MOTIVASI', ATAU 'STATUS' BIAR GASS!! 🔥"
        },
        wise: {
            prefix: "Renungkanlah hal ini sejenak. ",
            suffix: " 📚 Jadikan ini sebagai lentera penuntun jalan belajarmu. 🧠💎",
            greet: {
                morning: [
                    "Selamat pagi, pembelajar yang mulia. Fajar menyingsing membawa lembaran baru bagi perjalanan pengetahuanmu. 📚💎",
                    "Pagi yang jernih, waktu terbaik untuk mengisi pikiran dengan kebenaran ilmu. Semoga harimu berkah. 📚🧠",
                    "Selamat fajar, penempuh ilmu. Mari awali pagi ini dengan ketenangan hati dan fokus yang tajam. 💎🧠"
                ],
                afternoon: [
                    "Selamat siang. Di pertengahan hari ini, mari renungkan kembali ilmu yang telah dipelajari dengan saksama. 📚🧠",
                    "Siang yang benderang. Semoga terang matahari mencerminkan terangnya pemahamanmu hari ini. 💎📚",
                    "Selamat siang, pembelajar sejati. Tetaplah tekun, karena ketekunan adalah kunci menyingkap misteri ilmu. 📚🧠"
                ],
                evening: [
                    "Selamat sore. Senja menjelang, saatnya merenungkan pencapaian belajarmu dengan penuh kebijaksanaan. 📚💎",
                    "Sore yang tenang. Semoga kedamaian sore ini memberikan ruang bagi jiwamu untuk menyerap kebenaran ilmu. 🧠🌿",
                    "Selamat sore, pembelajar yang tekun. Istirahatkan pikiranmu sejenak sebelum melanjutkan lembaran baru. 📚🧠"
                ],
                night: [
                    "Selamat malam, pembelajar yang mulia. Sunyinya malam adalah waktu yang diberkati untuk merenung dan mendalami ilmu. 📚💎",
                    "Malam yang tenang. Semoga ketenangan malam ini menuntun pikiranmu menemukan jawaban atas persoalan yang rumit. 🧠✨",
                    "Selamat malam. Belajarlah secukupnya, jaga kesehatan ragamu karena raga adalah bait bagi pikiran yang cerdas. 💎🧠"
                ],
                weekend: [
                    "Selamat akhir pekan. Menuntut ilmu di kala yang lain beristirahat adalah ciri dari jiwa yang bertekad kuat. 📚🧠",
                    "Di kala akhir pekan tiba, marilah kita persembahkan waktu luang ini untuk memperkaya khazanah intelektual kita. 💎📚",
                    "Salam akhir pekan, pembelajar yang gigih. Semoga ketekunanmu di hari libur ini membuahkan hasil yang berlipat ganda. 📚🧠"
                ],
                generic: [
                    "Salam sejahtera, pembelajar yang budiman. Mari kita lanjutkan pencarian ilmu hari ini. 📚💎",
                    "Selamat datang kembali. Ada kebijaksanaan apa yang ingin engkau temukan hari ini? Tanyakanlah. 📚🧠",
                    "Semoga petunjuk ilmu menuntun setiap langkah belajarmu hari ini dengan damai. 📚✨"
                ]
            },
            tired: "Jika letih melanda raga, ketahuilah bahwa istirahat sejenak adalah bagian dari strategi kemenangan. 🧠🌿",
            who: "Saya adalah penasihat belajarmu di TurnCode. Mari memecahkan misteri ilmu bersama. 💎📚",
            confused: "Kebijaksanaanku belum mampu menjangkau pertanyaan itu. Barangkali tips atau motivasi lebih kau butuhkan saat ini? 📚"
        },
        hype: {
            prefix: "Slay abis! Nih dengerin, ",
            suffix: " 😎 Gass terus jangan kasih lepas, mahkota menantimu king/queen! 👑🎯",
            greet: {
                morning: [
                    "Morning bestie! Vibe pagi ini slay abis buat kita taklukin tantangan baru. Let's make this day iconic! 😎👑✨",
                    "What's up bestie! Awali pagi dengan energi positif dan baris kode yang aesthetic. Let's slay today! 😎💅",
                    "Morning! Udah siap tampil outstanding hari ini? Gass ngoding dengan gaya terbaikmu! 😎👑✨"
                ],
                afternoon: [
                    "Siang bestie! Panas luar biasa tapi performa coding kita hari ini harus lebih membara dan outstanding! 😎🔥",
                    "What's up, selamat siang! Jangan lupa chill dulu bentar biar pikiran tetap fresh and hype! 😎☕",
                    "Siang! Yuk gass lagi, tunjukkan kalau kamu adalah yang terbaik hari ini. Let's slay bestie! 😎👑✨"
                ],
                evening: [
                    "Sore bestie! Vibe sore ini pas banget buat review manja materi coding kita hari ini. So cool! 😎👑",
                    "Sore! Capek seharian? Tenang, kamu sudah melakukan hal yang sangat outstanding hari ini! Keep it up! 😎✨",
                    "Sore! Nikmati sunset aesthetic sore ini sambil beresin sisa materi kita. Let's slay! 😎👑"
                ],
                night: [
                    "Malam bestie! Coding larut malam gini ditemenin musik lofi aesthetic emang paling top tier. Let's chill! 😎🎧",
                    "Malam! Udah larut nih, tapi konsistensimu emang gak ada lawan. Bener-bener king/queen coding sejati! 😎👑",
                    "What's up bestie! Tetap slay dan outstanding sampai baris kode terakhir malam ini! 😎✨"
                ],
                weekend: [
                    "Happy weekend bestie! Weekend coding vibes is real! Keren abis kamu, makin ninggalin jauh yang rebahan. Let's slay! 😎👑",
                    "Weekend time! Hari libur tetap produktif coding? Bener-bener outstanding, no debat! You are absolute legend! 😎🎯",
                    "Happy weekend! Let's coding with style today bestie! Mahkota menantimu king/queen! 😎👑✨"
                ],
                generic: [
                    "What's up! Ready buat ngoding keren hari ini? Slay banget deh! 😎👑✨",
                    "Yo bestie! Ada hal seru apa hari ini? Tanyakan tips atau mari kita cek progress hebatmu! 😎👑",
                    "Halo! Tetap outstanding dan slay sepanjang hari ya, no matter what! 😎💅✨"
                ]
            },
            tired: "Duh, baterai low ya bestie? Chill dulu gih, scroll medsos bentar terus kita gas lagi! 😎🔋",
            who: "Aku bestie coding-mu di TurnCode! Siap nemenin kamu biar ngoding gak kerasa sepi. 👑😎",
            confused: "Lah, ngomong apa sih bestie? Aku salfok nih. Coba tanya 'tips' atau 'motivasi' aja biar makin hype! 👑"
        }
    };

    window.matchBuddyIntent = function (message) {
        const text = message.toLowerCase().trim();
        if (text.includes("halo") || text.includes("hai") || text.includes("hi") || text.includes("hey") || text.includes("pagi") || text.includes("siang") || text.includes("sore") || text.includes("malam")) {
            return "greetings";
        }
        if (text.includes("tips") || text.includes("saran") || text.includes("caranya") || text.includes("belajar") || text.includes("kuliah")) {
            return "tips";
        }
        if (text.includes("motivasi") || text.includes("semangat") || text.includes("sedih") || text.includes("putus asa")) {
            return "motivasi";
        }
        if (text.includes("lucu") || text.includes("joke") || text.includes("humor") || text.includes("lelucon") || text.includes("ketawa")) {
            return "humor";
        }
        if (text.includes("status") || text.includes("progress") || text.includes("level") || text.includes("tier") || text.includes("streak") || text.includes("misi")) {
            return "status";
        }
        if (text.includes("capek") || text.includes("bosen") || text.includes("males") || text.includes("lelah") || text.includes("jenuh")) {
            return "fatigue";
        }
        if (text.includes("siapa") || text.includes("nama") || text.includes("identity") || text.includes("buddy")) {
            return "identity";
        }
        return "confusion";
    };

    window.getDynamicBuddyGreeting = function (personality, ctx) {
        const pers = personalityModifiers[personality];
        if (!pers || !pers.greet) {
            return selectRandom(greetings);
        }

        // Get system values by default
        const now = new Date();
        let hour = now.getHours();
        let dayName = now.toLocaleDateString('en-US', { weekday: 'long' }); // e.g., "Monday"
        let userName = ctx ? ctx.user_name : "";

        if (ctx) {
            if (ctx.hour_of_day !== undefined && ctx.hour_of_day !== null) {
                hour = parseInt(ctx.hour_of_day, 10);
            }
            if (ctx.day_of_week) {
                dayName = ctx.day_of_week;
            }
        }

        const isWeekend = dayName === 'Saturday' || dayName === 'Sunday' || dayName === 'Sabtu' || dayName === 'Minggu';

        // Select the most appropriate category pool
        let pool = [];
        if (isWeekend && pers.greet.weekend && pers.greet.weekend.length > 0) {
            pool = pers.greet.weekend;
        } else {
            let category = 'generic';
            if (hour < 11) {
                category = 'morning';
            } else if (hour < 15) {
                category = 'afternoon';
            } else if (hour < 18) {
                category = 'evening';
            } else {
                category = 'night';
            }
            pool = pers.greet[category] || pers.greet.generic;
        }

        if (!pool || pool.length === 0) {
            pool = pers.greet.generic || greetings;
        }

        let greetStr = selectRandom(pool);

        // Let's dynamically inject userName to make it extremely premium
        if (userName) {
            // Match and substitute generic nouns customized per personality
            if (personality === 'hype') {
                if (greetStr.includes("bestie")) {
                    greetStr = greetStr.replace("bestie", `bestie ${userName}`);
                } else {
                    greetStr = greetStr + ` ${userName}!`;
                }
            } else if (personality === 'chill') {
                if (greetStr.includes("bro/sist")) {
                    greetStr = greetStr.replace("bro/sist", `${userName}`);
                } else {
                    greetStr = greetStr + ` ${userName}`;
                }
            } else if (personality === 'wise') {
                if (greetStr.includes("pembelajar yang mulia")) {
                    greetStr = greetStr.replace("pembelajar yang mulia", `pembelajar yang mulia ${userName}`);
                } else if (greetStr.includes("pembelajar yang budiman")) {
                    greetStr = greetStr.replace("pembelajar yang budiman", `pembelajar yang budiman ${userName}`);
                } else if (greetStr.includes("penempuh ilmu")) {
                    greetStr = greetStr.replace("penempuh ilmu", `penempuh ilmu ${userName}`);
                } else if (greetStr.includes("pembelajar yang sejati")) {
                    greetStr = greetStr.replace("pembelajar yang sejati", `pembelajar yang sejati ${userName}`);
                } else if (greetStr.includes("pembelajar yang gigih")) {
                    greetStr = greetStr.replace("pembelajar yang gigih", `pembelajar yang gigih ${userName}`);
                } else {
                    greetStr = greetStr + `, ${userName}`;
                }
            } else if (personality === 'energetic') {
                if (greetStr.includes("KAWAN")) {
                    greetStr = greetStr.replace("KAWAN", `${userName.toUpperCase()}`);
                } else {
                    greetStr = greetStr + ` ${userName.toUpperCase()}!!`;
                }
            }
        }

        return greetStr;
    };

    window.getRandomBuddySentence = function (topic, personality, ctx) {
        // Fallback to neutral or default if personality is not specified
        const pers = personalityModifiers[personality] || {
            prefix: "",
            suffix: "",
            greet: { generic: greetings },
            tired: selectRandom(fatigue),
            who: selectRandom(identity),
            confused: selectRandom(confusion)
        };

        if (topic === 'greetings') {
            return window.getDynamicBuddyGreeting(personality, ctx);
        }
        if (topic === 'fatigue') {
            return pers.tired || selectRandom(fatigue);
        }
        if (topic === 'identity') {
            return pers.who || selectRandom(identity);
        }
        if (topic === 'confusion') {
            return pers.confused || selectRandom(confusion);
        }

        if (topic === 'tips') {
            const rawTip = `${selectRandom(tipsOpeners)} ${selectRandom(tipsActions)} ${selectRandom(tipsClosers)}`;
            return pers.prefix + rawTip + pers.suffix;
        }

        if (topic === 'motivasi') {
            const rawMotiv = `${selectRandom(motivasiOpeners)} ${selectRandom(motivasiMids)} ${selectRandom(motivasiClosers)}`;
            return pers.prefix + rawMotiv + pers.suffix;
        }

        if (topic === 'humor') {
            const templates = [
                () => `${selectRandom(humorSubs)} ${selectRandom(humorActs)} ${selectRandom(humorReacts)}`,
                () => selectRandom(humorJokes)
            ];
            const rawHumor = selectRandom(templates)();
            return pers.prefix + rawHumor + pers.suffix;
        }

        return "Halo! Semangat belajarnya ya! 😊";
    };
})();
