<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Submateri;
use App\Models\Chapter;
use App\Models\Lesson;
use App\Models\Quiz;

class LearningSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ==========================================
        // 1. FRONT END COURSE
        // ==========================================
        $feCourse = Course::create([
            'title' => 'Front End',
            'description' => 'Orang yang kerjaannya bikin user interface, desain yang unik aneh tapi keren. Di sini kamu belajar cara bikin UI yang premium!',
            'icon' => '🎨',
            'color' => '#3b82f6'
        ]);

        // Submateri HTML
        $feSubHTML = Submateri::create([
            'course_id' => $feCourse->id,
            'title' => 'HTML',
            'description' => 'Dasar-dasar pembangunan kerangka halaman web menggunakan tag HTML5 modern.',
            'icon' => '🌐',
            'order' => 1
        ]);

        $feHTMLChapter = Chapter::create([
            'submateri_id' => $feSubHTML->id,
            'title' => 'Dasar HTML & Struktur Web',
            'order' => 1
        ]);

        Lesson::create([
            'chapter_id' => $feHTMLChapter->id,
            'title' => 'Membangun Struktur Web',
            'content' => '<h3>Pengenalan HTML</h3><p>HTML (HyperText Markup Language) adalah tulang punggung dari setiap halaman web. HTML mendefinisikan struktur konten menggunakan elemen-elemen standar seperti paragraf, judul, gambar, dan link.</p><p>Mari mulai perjalanan frontend kita dengan menyusun kerangka dokumen HTML yang valid dan kokoh!</p>',
            'order' => 1
        ]);

        Lesson::create([
            'chapter_id' => $feHTMLChapter->id,
            'title' => 'Mengenal Tag dan Elemen',
            'content' => '<h3>Elemen & Tag HTML</h3><p>Setiap struktur di halaman web dibentuk oleh tag pembuka dan penutup. Pelajari cara menggunakan tag heading &lt;h1&gt; sampai &lt;h6&gt;, tag paragraf &lt;p&gt;, serta tag container penting lainnya.</p>',
            'order' => 2
        ]);

        // Submateri CSS
        $feSubCSS = Submateri::create([
            'course_id' => $feCourse->id,
            'title' => 'CSS',
            'description' => 'Mempelajari styling, warna, tata letak, dan desain responsif menggunakan CSS3.',
            'icon' => '💅',
            'order' => 2
        ]);

        $feCSSChapter = Chapter::create([
            'submateri_id' => $feSubCSS->id,
            'title' => 'Dasar CSS & Styling',
            'order' => 1
        ]);

        Lesson::create([
            'chapter_id' => $feCSSChapter->id,
            'title' => 'Styling dengan Selector',
            'content' => '<h3>Menghias Web dengan CSS</h3><p>Pelajari cara menghubungkan CSS dengan HTML, menggunakan class dan id selector, serta mengubah warna, background, dan font dokumen web Anda.</p>',
            'order' => 1
        ]);

        // Submateri JavaScript
        $feSubJS = Submateri::create([
            'course_id' => $feCourse->id,
            'title' => 'JavaScript',
            'description' => 'Menghidupkan halaman web dengan logika pemrograman, variabel, dan interaktivitas.',
            'icon' => '⚡',
            'order' => 3
        ]);

        $feJSChapter = Chapter::create([
            'submateri_id' => $feSubJS->id,
            'title' => 'Konsep Dasar JS',
            'order' => 1
        ]);

        Lesson::create([
            'chapter_id' => $feJSChapter->id,
            'title' => 'Variabel dan Tipe Data',
            'content' => '<h3>Pemrograman dengan JavaScript</h3><p>Pahami cara kerja variabel dengan let, const, dan tipe data primitif seperti string, number, dan boolean di ekosistem JavaScript modern.</p>',
            'order' => 1
        ]);


        // ==========================================
        // 2. BACK END COURSE
        // ==========================================
        $beCourse = Course::create([
            'title' => 'Back End',
            'description' => 'Di balik layar semua aplikasi berjalan lancar. Kamu akan belajar mengelola database, server, dan membuat API yang powerful.',
            'icon' => '⚙️',
            'color' => '#10b981'
        ]);

        // Submateri PHP
        $beSubPHP = Submateri::create([
            'course_id' => $beCourse->id,
            'title' => 'PHP',
            'description' => 'Mempelajari bahasa pemrograman sisi server yang paling populer di dunia web.',
            'icon' => '🐘',
            'order' => 1
        ]);

        $bePHPChapter = Chapter::create([
            'submateri_id' => $beSubPHP->id,
            'title' => 'Pengenalan Server & Sintaks PHP',
            'order' => 1
        ]);

        Lesson::create([
            'chapter_id' => $bePHPChapter->id,
            'title' => 'Sintaks Dasar PHP',
            'content' => '<h3>Halo Dunia PHP!</h3><p>Pelajari dasar server-side scripting menggunakan PHP. Mengerti cara kerja tag pembuka &lt;?php, melakukan echo, dan membuat variabel dinamis di server.</p>',
            'order' => 1
        ]);

        // Submateri MySQL
        $beSubMySQL = Submateri::create([
            'course_id' => $beCourse->id,
            'title' => 'MySQL',
            'description' => 'Merancang database relasional, membuat tabel, dan mengolah data menggunakan SQL.',
            'icon' => '🛢️',
            'order' => 2
        ]);

        $beMySQLChapter = Chapter::create([
            'submateri_id' => $beSubMySQL->id,
            'title' => 'Relational Database',
            'order' => 1
        ]);

        Lesson::create([
            'chapter_id' => $beMySQLChapter->id,
            'title' => 'Perancangan Skema Database',
            'content' => '<h3>Mendesain Database Relasional</h3><p>Pahami konsep Primary Key, Foreign Key, normalisasi data, serta cara membuat tabel relasional menggunakan MySQL.</p>',
            'order' => 1
        ]);


        // ==========================================
        // 3. DATA ANALYZE COURSE
        // ==========================================
        $daCourse = Course::create([
            'title' => 'Data Analyze',
            'description' => 'Mengubah data mentah menjadi informasi berharga. Pelajari visualisasi data, statistik, dan tools populer untuk analisis.',
            'icon' => '📊',
            'color' => '#f59e0b'
        ]);

        $daSubPython = Submateri::create([
            'course_id' => $daCourse->id,
            'title' => 'Python',
            'description' => 'Pustaka terpopuler di dunia data science untuk memanipulasi dataset dengan cepat.',
            'icon' => '🐍',
            'order' => 1
        ]);

        $daChapter1 = Chapter::create([
            'submateri_id' => $daSubPython->id,
            'title' => 'Python untuk Analisis Data',
            'order' => 1
        ]);

        Lesson::create([
            'chapter_id' => $daChapter1->id,
            'title' => 'Pengenalan Pandas & NumPy',
            'content' => '<h3>Analisis Data dengan Python</h3><p>Manipulasi dataset kamu dengan pustaka Python paling populer di dunia data science: Pandas dan NumPy.</p>',
            'order' => 1
        ]);


        // ==========================================
        // 4. FULL STACK DEV COURSE
        // ==========================================
        $fsCourse = Course::create([
            'title' => 'Full Stack Dev',
            'description' => 'Kuasai semuanya dari depan hingga belakang. Jadilah pengembang serba bisa yang mampu membangun aplikasi dari nol hingga rilis.',
            'icon' => '🚀',
            'color' => '#8b5cf6'
        ]);

        $fsSubArch = Submateri::create([
            'course_id' => $fsCourse->id,
            'title' => 'Arsitektur Modern',
            'description' => 'Cara merancang dan membangun aplikasi utuh yang terintegrasi penuh.',
            'icon' => '🏗️',
            'order' => 1
        ]);

        $fsChapter1 = Chapter::create([
            'submateri_id' => $fsSubArch->id,
            'title' => 'Arsitektur Aplikasi Modern',
            'order' => 1
        ]);

        Lesson::create([
            'chapter_id' => $fsChapter1->id,
            'title' => 'Menghubungkan Frontend dan Backend',
            'content' => '<h3>Integrasi Aplikasi</h3><p>Cara membangun aplikasi yang utuh dan handal dengan menghubungkan React (frontend) dan Node.js (backend) melalui restful API.</p>',
            'order' => 1
        ]);
    }
}
