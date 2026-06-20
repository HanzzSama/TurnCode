<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Interest;
use App\Models\Fokus;

class OnboardingSeeder extends Seeder
{
    public function run(): void
    {
        $interests = [
            [
                'val' => 'web-dev',
                'name' => 'Web Development',
                'desc' => 'HTML, CSS, JS, React, Laravel',
                'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>'
            ],
            [
                'val' => 'game-dev',
                'name' => 'Game Development',
                'desc' => 'Unity, Godot, C#, GDScript',
                'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="6" y1="12" x2="10" y2="12"/><line x1="8" y1="10" x2="8" y2="14"/><line x1="15" y1="13" x2="15.01" y2="13"/><line x1="18" y1="11" x2="18.01" y2="11"/><rect x="2" y="6" width="20" height="12" rx="3"/></svg>'
            ],
            [
                'val' => 'mobile-dev',
                'name' => 'Mobile Development',
                'desc' => 'Flutter, React Native',
                'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>'
            ],
            [
                'val' => 'cybersecurity',
                'name' => 'Cybersecurity',
                'desc' => 'Ethical hacking, Security',
                'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>'
            ],
            [
                'val' => 'ui-ux',
                'name' => 'UI/UX Design',
                'desc' => 'Figma, Design Systems',
                'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12c0 5.5228 4.47715 10 10 10z"/><circle cx="7.5" cy="10.5" r="1"/><circle cx="11.5" cy="7.5" r="1"/><circle cx="16.5" cy="9.5" r="1"/><circle cx="15.5" cy="14.5" r="1.5"/></svg>'
            ],
        ];

        foreach ($interests as $item) {
            Interest::updateOrCreate(['val' => $item['val']], $item);
        }

        $focusMap = [
            'web-dev' => [
                ['val' => 'frontend', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="9" y1="3" x2="9" y2="21"/><line x1="9" y1="9" x2="21" y2="9"/></svg>', 'name' => 'Frontend Dev', 'desc' => 'Tampilan & interaksi user', 'tags' => 'HTML,CSS,JS,React'],
                ['val' => 'backend', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/><path d="M3 12c0 1.66 4 3 9 3s9-1.34 9-3"/></svg>', 'name' => 'Backend Dev', 'desc' => 'Server, API & database', 'tags' => 'PHP,Node.js,Laravel'],
                ['val' => 'fullstack', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polygon points="2 17 12 22 22 17"/><polygon points="2 12 12 17 22 12"/></svg>', 'name' => 'Fullstack Dev', 'desc' => 'Frontend + Backend sekaligus', 'tags' => 'React,Laravel,MySQL'],
                ['val' => 'ui-ux', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>', 'name' => 'UI/UX Web', 'desc' => 'Desain antarmuka web', 'tags' => 'Figma,CSS,Animation'],
            ],
            'game-dev' => [
                ['val' => 'unity-dev', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="6" y1="12" x2="10" y2="12"/><line x1="8" y1="10" x2="8" y2="14"/><line x1="15" y1="13" x2="15.01" y2="13"/><line x1="18" y1="11" x2="18.01" y2="11"/><rect x="2" y="6" width="20" height="12" rx="3"/></svg>', 'name' => 'Unity Developer', 'desc' => 'Game 2D & 3D dengan Unity', 'tags' => 'C#,Unity,2D/3D'],
                ['val' => 'godot-dev', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="4"/><line x1="12" y1="2" x2="12" y2="8"/></svg>', 'name' => 'Godot Developer', 'desc' => 'Game open-source dengan Godot', 'tags' => 'GDScript,Godot'],
                ['val' => 'game-design', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>', 'name' => 'Game Designer', 'desc' => 'Desain mekanik & level game', 'tags' => 'Design,Level Design'],
                ['val' => 'game-art', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>', 'name' => 'Game Artist', 'desc' => 'Aset visual untuk game', 'tags' => 'Pixel Art,Blender'],
            ],
            'mobile-dev' => [
                ['val' => 'flutter', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>', 'name' => 'Flutter', 'desc' => 'Satu kode iOS & Android', 'tags' => 'Dart,Flutter,Firebase'],
                ['val' => 'react-native', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="12" rx="10" ry="4" transform="rotate(45 12 12)"/><ellipse cx="12" cy="12" rx="10" ry="4" transform="rotate(-45 12 12)"/><circle cx="12" cy="12" r="1.5"/></svg>', 'name' => 'React Native', 'desc' => 'Mobile dengan React', 'tags' => 'JS,React,Expo'],
                ['val' => 'android', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="9" width="16" height="11" rx="2"/><path d="M9 9V5a3 3 0 0 1 6 0v4"/><circle cx="8" cy="14" r="1"/><circle cx="16" cy="14" r="1"/></svg>', 'name' => 'Android Native', 'desc' => 'Aplikasi Android murni', 'tags' => 'Kotlin,Android Studio'],
                ['val' => 'ios', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20.94c1.38-1.35 1.79-2.31 2.5-3.32.72.69 1.5 1 2.5 1s1.5-1 2.5-1.5c-1-1.5-1.5-2-1.5-3.5 0-2 1.5-3.5 2.5-4C19 8 17 8 16 9c-1-1.5-2-1.5-3.5-1.5S10 8 9 9c-1-1-3-1-4.5.5C5.5 10 7 11.5 7 13.5c0 1.5-.5 2-1.5 3.5 1 .5 1.5 1.5 2.5 1.5s1.78-.31 2.5-1c.71 1.01 1.12 1.97 2.5 3.44z"/><path d="M12 7.5V2"/></svg>', 'name' => 'iOS Native', 'desc' => 'Aplikasi Apple native', 'tags' => 'Swift,Xcode'],
            ],
            'cybersecurity' => [
                ['val' => 'pen-tester', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>', 'name' => 'Pentester', 'desc' => 'Uji penetrasi & keamanan', 'tags' => 'Kali,Metasploit,Burp'],
                ['val' => 'sec-analyst', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>', 'name' => 'Security Analyst', 'desc' => 'Analisis & mitigasi ancaman', 'tags' => 'SIEM,Firewall,SOC'],
                ['val' => 'network-sec', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"/><rect x="2" y="14" width="20" height="8" rx="2" ry="2"/><line x1="6" y1="6" x2="6.01" y2="6"/><line x1="6" y1="18" x2="6.01" y2="18"/></svg>', 'name' => 'Network Security', 'desc' => 'Pengamanan infrastruktur jaringan', 'tags' => 'VPN,IDS/IPS,Cisco'],
                ['val' => 'forensics', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>', 'name' => 'Digital Forensics', 'desc' => 'Investigasi kejahatan siber', 'tags' => 'EnCase,Autopsy,FTK'],
            ],
            'ui-ux' => [
                ['val' => 'ux-researcher', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>', 'name' => 'UX Researcher', 'desc' => 'Riset kebutuhan & perilaku user', 'tags' => 'Interview,Survey,Testing'],
                ['val' => 'ui-designer', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/><path d="M2 12h20"/></svg>', 'name' => 'UI Designer', 'desc' => 'Membuat antarmuka visual produk', 'tags' => 'Figma,Sketch,Prototyping'],
                ['val' => 'prod-designer', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12c0 5.5228 4.47715 10 10 10z"/><circle cx="12" cy="12" r="3"/></svg>', 'name' => 'Product Designer', 'desc' => 'Mengembangkan produk end-to-end', 'tags' => 'Strategy,UX,UI'],
                ['val' => 'interaction', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>', 'name' => 'Interaction Designer', 'desc' => 'Mendesain detail interaksi mikro', 'tags' => 'Motion,IxD,Principle'],
            ],
        ];

        foreach ($focusMap as $interestVal => $foci) {
            foreach ($foci as $focus) {
                Fokus::updateOrCreate(
                    ['val' => $focus['val']],
                    [
                        'interest_val' => $interestVal,
                        'name' => $focus['name'],
                        'desc' => $focus['desc'],
                        'icon' => $focus['icon'],
                        'tags' => $focus['tags']
                    ]
                );
            }
        }
    }
}
