@php
$dayMap = [
    'Monday' => 'Sen',
    'Tuesday' => 'Sel',
    'Wednesday' => 'Rab',
    'Thursday' => 'Kam',
    'Friday' => 'Jum',
    'Saturday' => 'Sab',
    'Sunday' => 'Min',
];
$todayName = $dayMap[date('l')];
$weekOfMonth = ceil(date('d') / 7);
$weekStr = 'Minggu ' . $weekOfMonth;

$monthMap = [
    'Jan' => 'Jan',
    'Feb' => 'Feb',
    'Mar' => 'Mar',
    'Apr' => 'Apr',
    'May' => 'Mei',
    'Jun' => 'Jun',
    'Jul' => 'Jul',
    'Aug' => 'Ags',
    'Sep' => 'Sep',
    'Oct' => 'Okt',
    'Nov' => 'Nov',
    'Dec' => 'Des'
];
$currentMonthName = $monthMap[date('M')] ?? 'Jan';
$weekStrShort = 'M' . $weekOfMonth;

$todaySchedules = [];
$upcomingSchedules = [];

foreach ($schedules as $sch) {
    $isActiveToday = false;
    $config = $sch->routine_config;

    if ($sch->routine_type === 'Harian') {
        $days = $config['days'] ?? [];
        if (empty($days) || in_array($todayName, $days)) {
            $isActiveToday = true;
        }
    } elseif ($sch->routine_type === 'Mingguan') {
        $days = $config['days'] ?? [];
        $weeks = $config['weeks'] ?? [];
        if (in_array($todayName, $days) && (in_array($weekStr, $weeks) || in_array('Tiap Minggu', $weeks))) {
            $isActiveToday = true;
        }
    } elseif ($sch->routine_type === 'Bulanan') {
        $months = $config['months'] ?? [];
        $weeks = $config['weeks'] ?? [];
        if (in_array($currentMonthName, $months) && in_array($weekStrShort, $weeks)) {
            $isActiveToday = true;
        }
    } elseif ($sch->routine_type === 'Custom') {
        $customDate = $config['date'] ?? '';
        if ($customDate === date('Y-m-d')) {
            $isActiveToday = true;
        }
    }

    if ($isActiveToday) {
        $todaySchedules[] = $sch;
    } else {
        $upcomingSchedules[] = $sch;
    }
}

// Sort schedules by start_time
usort($todaySchedules, function ($a, $b) {
    return strcmp($a->start_time, $b->start_time);
});
usort($upcomingSchedules, function ($a, $b) {
    return strcmp($a->start_time, $b->start_time);
});

// Calculate total study minutes for today
$totalMinutes = 0;
foreach ($todaySchedules as $sch) {
    $start = Carbon\Carbon::parse($sch->start_time);
    $end = Carbon\Carbon::parse($sch->end_time);
    if ($end->lt($start)) {
        $end->addDay();
    }
    $totalMinutes += $start->diffInMinutes($end);
}
$totalHours = round($totalMinutes / 60, 1);

$userTier = auth()->user()->tier ?? 'Initiate';
$tierColors = [
    'Initiate' => '168, 162, 158',
    'Explorer' => '34, 197, 94',
    'Operator' => '59, 130, 246',
    'Technician' => '139, 92, 246',
    'Specialist' => '236, 72, 153',
    'Professional' => '239, 68, 68',
    'Senior Professional' => '249, 115, 22',
    'Lead Engineer' => '234, 179, 8',
    'Architect' => '6, 182, 212',
    'Principal' => '15, 118, 110',
    'Strategist' => '225, 29, 72',
    'Visionary' => '218, 165, 32',
];
$rgbColor = $tierColors[$userTier] ?? '168, 162, 158';

// Set up all tiers mapping for progress cards row
$allTiers = [
    ['name' => 'Initiate', 'level' => 1, 'color' => '168, 162, 158'],
    ['name' => 'Explorer', 'level' => 2, 'color' => '34, 197, 94'],
    ['name' => 'Operator', 'level' => 6, 'color' => '59, 130, 246'],
    ['name' => 'Technician', 'level' => 11, 'color' => '139, 92, 246'],
    ['name' => 'Specialist', 'level' => 21, 'color' => '236, 72, 153'],
    ['name' => 'Professional', 'level' => 36, 'color' => '239, 68, 68'],
    ['name' => 'Senior Professional', 'level' => 56, 'color' => '249, 115, 22'],
    ['name' => 'Lead Engineer', 'level' => 81, 'color' => '234, 179, 8'],
    ['name' => 'Architect', 'level' => 111, 'color' => '6, 182, 212'],
    ['name' => 'Principal', 'level' => 141, 'color' => '15, 118, 110'],
    ['name' => 'Strategist', 'level' => 171, 'color' => '225, 29, 72'],
    ['name' => 'Visionary', 'level' => 196, 'color' => '218, 165, 32'],
];

$currentIndex = 0;
foreach ($allTiers as $idx => $t) {
    if ($t['name'] === $userTier) {
        $currentIndex = $idx;
        break;
    }
}

$startIdx = $currentIndex;
if ($startIdx + 5 > count($allTiers)) {
    $startIdx = max(0, count($allTiers) - 5);
}
$displayTiers = array_slice($allTiers, $startIdx, 5);
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - TurnCode</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=DotGothic16&family=Silkscreen:wght@400;700&display=swap"
        rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    @include('layouts.transition-head')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal-jadwal.css') }}">
    <style>
        .kelola-jadwal-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            font-size: 0.8rem;
            font-weight: 500;
            backdrop-filter: blur(8px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .kelola-jadwal-btn:hover {
            background: rgba(255, 255, 255, 0.09);
            border-color: rgba(255, 255, 255, 0.15);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
        }

        .kelola-jadwal-btn i {
            font-size: 1.1rem;
            color: #38b2ac;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .kelola-jadwal-btn:hover i {
            transform: scale(1.15) rotate(5deg);
        }

        .section-header-flex {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 1.5rem;
        }

        .section-header-flex .section-header {
            margin-bottom: 0;
        }

        @media (max-width: 576px) {
            .section-header-flex {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
            }

            .kelola-jadwal-btn {
                width: 100%;
                justify-content: center;
            }
        }

        /* Custom scrollbar for our timeline */
        .dashboard-timeline-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .dashboard-timeline-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .dashboard-timeline-scroll::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        /* Android Modern & Dot Matrix Styles */
        .android-exam-wrapper {
            margin-bottom: 2.5rem;
        }

        .android-exam-card {
            background: transparent;
            border: none;
            box-shadow: none;
            padding: 0;
            display: flex;
            gap: 1.5rem;
            align-items: stretch;
            transition: none;
            touch-action: auto;
            overflow: visible;
        }

        .android-exam-card:hover {
            border-color: transparent;
            box-shadow: none;
        }

        .android-exam-grid-bg {
            display: none;
        }

        .android-exam-content {
            /* background: linear-gradient(#332E34, #332e34df); */
            background-image:
                radial-gradient(circle at 10% 20%, rgba(var(--accent-rgb), 0.08) 0%, transparent 60%),
                linear-gradient(rgba(255, 255, 255, 0.015) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.015) 1px, transparent 1px);
            background-size: 100% 100%, 20px 20px, 20px 20px;
            border-radius: 200px 1000px 1000px 200px;
            padding: 4rem 3rem 4rem 6rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            gap: 1.5rem;
            flex: 1.3;
            min-height: 400px;
            position: relative;
            z-index: 2;
            box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px;
            overflow: hidden;
        }

        .android-exam-content::before {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: inherit;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.05) 0%, transparent 40%);
            pointer-events: none;
            border: 1px solid rgba(255, 255, 255, 0.03);
            z-index: -1;
        }

        .android-exam-badge {
            display: none;
        }

        .android-exam-title {
            font-family: 'Inter', sans-serif;
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--text-main);
            margin: 0;
            line-height: 1.2;
            letter-spacing: -0.5px;
        }

        .android-exam-desc {
            font-size: 0.95rem;
            color: #b3aeb6;
            line-height: 1.6;
            margin: 0;
            max-width: 440px;
        }

        .android-exam-desc strong {
            color: #ffffff;
            font-weight: 700;
        }

        .btn-android-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #dcd9dd;
            color: #1c191f;
            padding: 1rem 2rem;
            border-radius: 50px;
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-decoration: none;
            border: none;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            margin-top: 0.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 32em;
        }

        .btn-android-pill:hover {
            background: #ffffff;
            color: #1c191f;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(255, 255, 255, 0.15);
        }

        .btn-android-pill i {
            display: none;
        }

        .android-exam-widget {
            flex: 1;
            background: #343137;
            border-radius: 1000px 200px 200px 1000px;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            align-items: stretch;
            justify-content: stretch;
            min-height: 400px;
            position: relative;
            z-index: 2;
            box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px;
        }

        .android-exam-screen {
            background: #110E12;
            border: 20px solid #181519;
            border-radius: 1000px 160px 160px 1000px;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
        }

        .android-exam-screen-dot-matrix {
            font-family: 'Silkscreen', monospace;
            color: rgb(var(--accent-rgb));
            font-size: 0.65rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            opacity: 0.9;
            text-align: center;
            text-shadow: 0 0 6px rgba(var(--accent-rgb), 0.5);
            margin-top: 0.8rem;
        }

        .android-exam-svg-dot {
            fill: rgba(var(--accent-rgb), 0.08);
            transition: fill 0.3s ease, filter 0.3s ease;
        }

        .android-exam-svg-dot.active {
            fill: rgb(var(--accent-rgb));
            filter: drop-shadow(0 0 3px rgba(var(--accent-rgb), 0.9)) drop-shadow(0 0 8px rgba(var(--accent-rgb), 0.45));
            animation: dotActiveBreathe 2s ease-in-out infinite alternate;
        }

        @keyframes dotActiveBreathe {
            0% {
                filter: drop-shadow(0 0 2px rgba(var(--accent-rgb), 0.75)) drop-shadow(0 0 6px rgba(var(--accent-rgb), 0.35));
                opacity: 0.85;
            }

            100% {
                filter: drop-shadow(0 0 5px rgba(var(--accent-rgb), 1)) drop-shadow(0 0 12px rgba(var(--accent-rgb), 0.55));
                opacity: 1;
            }
        }

        @media (max-width: 768px) {
            .android-exam-card {
                flex-direction: column;
                gap: 1rem;
            }

            .android-exam-content {
                border-radius: 28px;
                padding: 2.5rem 1.5rem;
                min-height: auto;
                align-items: center;
                text-align: center;
            }

            .android-exam-title {
                font-size: 1.6rem;
            }

            .android-exam-desc {
                font-size: 0.88rem;
                max-width: 100%;
            }

            .btn-android-pill {
                max-width: 100%;
            }

            .android-exam-widget {
                border-radius: 28px;
                padding: 1.5rem;
                min-height: 240px;
            }

            .android-exam-screen {
                border-radius: 16px;
                padding: 1.2rem;
            }
        }

        /* Premium Flash Notification Alerts */
        .premium-alert-banner {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.5rem;
            border-radius: 16px;
            backdrop-filter: blur(12px);
            border: 1px solid;
            animation: slideDownFadeIn 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: calc(100% - 40px);
            max-width: 50em;
            overflow: hidden;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.4);
            z-index: 9999;
            transition: opacity 0.5s ease, transform 0.5s ease;
        }

        .premium-alert-success {
            background: rgba(16, 185, 129, 0.08);
            border-color: rgba(16, 185, 129, 0.2);
            color: #34d399;
        }

        .premium-alert-error {
            background: rgba(239, 68, 68, 0.08);
            border-color: rgba(239, 68, 68, 0.2);
            color: #f87171;
        }

        .premium-alert-close {
            margin-left: auto;
            background: transparent;
            border: none;
            color: inherit;
            cursor: pointer;
            opacity: 0.6;
            transition: opacity 0.2s;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .premium-alert-close:hover {
            opacity: 1;
        }

        .premium-alert-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: rgba(255, 255, 255, 0.06);
            border-radius: 0 0 16px 16px;
            overflow: hidden;
        }

        .premium-alert-progress-bar {
            height: 100%;
            width: 100%;
            border-radius: inherit;
            animation: alertCountdown 10s linear forwards;
        }

        .premium-alert-success .premium-alert-progress-bar {
            background: linear-gradient(90deg, #10b981, #34d399);
            box-shadow: 0 0 8px rgba(16, 185, 129, 0.5);
        }

        .premium-alert-error .premium-alert-progress-bar {
            background: linear-gradient(90deg, #ef4444, #f87171);
            box-shadow: 0 0 8px rgba(239, 68, 68, 0.5);
        }

        @keyframes alertCountdown {
            from {
                width: 100%;
            }

            to {
                width: 0%;
            }
        }

        .premium-alert-banner.dismissing {
            opacity: 0;
            transform: translate(-50%, -20px);
            pointer-events: none;
        }

        @keyframes slideDownFadeIn {
            from {
                opacity: 0;
                transform: translate(-50%, -20px);
            }

            to {
                opacity: 1;
                transform: translate(-50%, 0);
            }
        }

        /* "Pilih Tujuan Lainnya" Component Styles */
        .pilih-tujuan-wrapper {
            margin-top: 2rem;
            margin-bottom: 3rem;
            background: rgba(30, 27, 31, 0.65);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 24px;
            padding: 2.5rem;
            backdrop-filter: blur(16px);
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
        }

        .pilih-tujuan-wrapper::after {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(var(--accent-rgb), 0.05) 0%, transparent 70%);
            bottom: -150px;
            right: -150px;
            z-index: 1;
            pointer-events: none;
        }

        .pilih-tujuan-title {
            font-family: 'Inter', sans-serif;
            font-size: 1.8rem;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.5px;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .pilih-tujuan-title i {
            color: #eab308;
            filter: drop-shadow(0 0 8px rgba(234, 179, 8, 0.4));
        }

        .pilih-tujuan-subtitle {
            font-size: 0.95rem;
            color: #b3aeb6;
            margin-bottom: 2rem;
        }

        /* Tab Controls */
        .interest-tabs {
            display: flex;
            gap: 0.75rem;
            margin-bottom: 2rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            padding-bottom: 1rem;
            overflow-x: auto;
            scrollbar-width: none;
            /* Firefox */
        }

        .interest-tabs::-webkit-scrollbar {
            display: none;
            /* Safari and Chrome */
        }

        .interest-tab-btn {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.06);
            color: #b3aeb6;
            padding: 0.75rem 1.25rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            white-space: nowrap;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .interest-tab-btn svg {
            width: 16px;
            height: 16px;
            stroke-width: 2.2;
            transition: transform 0.3s;
        }

        .interest-tab-btn:hover {
            background: rgba(255, 255, 255, 0.08);
            color: #ffffff;
            border-color: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
        }

        .interest-tab-btn.active {
            background: rgb(var(--accent-rgb));
            color: #110e12;
            border-color: rgb(var(--accent-rgb));
            font-weight: 700;
            box-shadow: 0 4px 20px rgba(var(--accent-rgb), 0.3);
        }

        .interest-tab-btn.active svg {
            transform: scale(1.1);
        }

        /* Focus Cards Grid */
        .focus-panels-container {
            position: relative;
        }

        .focus-panel {
            display: none;
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .focus-panel.active {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 1.25rem;
            opacity: 1;
            animation: fadeInUp 0.4s forwards;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .focus-option-card {
            position: relative;
            background: rgba(255, 255, 255, 0.02);
            border: 1.5px solid rgba(255, 255, 255, 0.06);
            border-radius: 20px;
            padding: 1.5rem;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            user-select: none;
        }

        .focus-option-card:hover {
            background: rgba(255, 255, 255, 0.04);
            border-color: rgba(255, 255, 255, 0.12);
            transform: translateY(-4px) scale(1.01);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
        }

        .focus-option-card.selected {
            background: rgba(var(--accent-rgb), 0.04);
            border-color: rgb(var(--accent-rgb));
            box-shadow: 0 0 25px rgba(var(--accent-rgb), 0.15);
        }

        .focus-card-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .focus-card-icon {
            width: 42px;
            height: 42px;
            background: rgba(255, 255, 255, 0.04);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            transition: all 0.3s;
        }

        .focus-option-card:hover .focus-card-icon {
            background: rgba(255, 255, 255, 0.08);
            color: rgb(var(--accent-rgb));
        }

        .focus-option-card.selected .focus-card-icon {
            background: rgba(var(--accent-rgb), 0.15);
            color: rgb(var(--accent-rgb));
        }

        .focus-card-icon svg {
            width: 20px;
            height: 20px;
            stroke-width: 2;
        }

        .focus-card-title-wrap {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .focus-card-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #ffffff;
        }

        .focus-card-desc {
            font-size: 0.85rem;
            color: #b3aeb6;
            line-height: 1.4;
            min-height: 40px;
        }

        .focus-card-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: auto;
        }

        .focus-card-tag {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.06);
            color: #b3aeb6;
            font-size: 0.7rem;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 6px;
        }

        .focus-option-card.selected .focus-card-tag {
            border-color: rgba(var(--accent-rgb), 0.2);
            color: rgb(var(--accent-rgb));
        }

        /* Check Indicator */
        .focus-card-check {
            position: absolute;
            top: 1.25rem;
            right: 1.25rem;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            background: transparent;
        }

        .focus-option-card:hover .focus-card-check {
            border-color: rgba(255, 255, 255, 0.3);
        }

        .focus-option-card.selected .focus-card-check {
            border-color: rgb(var(--accent-rgb));
            background: rgb(var(--accent-rgb));
            color: #110e12;
        }

        .focus-card-check svg {
            width: 12px;
            height: 12px;
            stroke-width: 4;
            opacity: 0;
            transform: scale(0.6);
            transition: all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .focus-option-card.selected .focus-card-check svg {
            opacity: 1;
            transform: scale(1);
        }

        /* Completed & Active Focus states */
        .focus-option-card.is-active {
            opacity: 0.75;
            border-color: rgba(124, 106, 247, 0.3) !important;
            background: rgba(124, 106, 247, 0.02) !important;
            cursor: not-allowed;
            pointer-events: none;
        }

        .focus-option-card.is-completed {
            border-color: rgba(16, 185, 129, 0.25);
            background: rgba(16, 185, 129, 0.02);
        }

        .focus-option-card.is-completed:hover {
            border-color: rgba(16, 185, 129, 0.45);
            background: rgba(16, 185, 129, 0.04);
        }

        .focus-badge {
            font-size: 0.65rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 3px 8px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            margin-left: 6px;
        }

        .focus-badge-active {
            background: rgba(124, 106, 247, 0.15);
            color: #7c6af7;
            border: 1px solid rgba(124, 106, 247, 0.3);
        }

        .focus-badge-completed {
            background: rgba(16, 185, 129, 0.15);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.3);
            box-shadow: 0 0 10px rgba(16, 185, 129, 0.1);
        }

        /* Action Row */
        .pilih-tujuan-actions {
            margin-top: 2.5rem;
            display: flex;
            justify-content: flex-end;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            padding-top: 1.5rem;
        }

        .btn-pilih-tujuan-submit {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #ffffff;
            color: #110e12;
            border: none;
            padding: 0.9rem 2.25rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.25s ease;
            box-shadow: 0 4px 15px rgba(255, 255, 255, 0.1);
        }

        .btn-pilih-tujuan-submit:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 255, 255, 0.2);
            background: #f4eff5;
        }

        .btn-pilih-tujuan-submit:disabled {
            opacity: 0.4;
            cursor: not-allowed;
            background: rgba(255, 255, 255, 0.05);
            color: rgba(255, 255, 255, 0.3);
            box-shadow: none;
        }
    </style>
</head>

<body class="dashboard-page">

    <!-- FIXED NAVBAR -->
    @php
$navUserTier = auth()->user()->tier ?? 'Initiate';
$navTierColors = [
    'Initiate' => '168, 162, 158',
    'Explorer' => '34, 197, 94',
    'Operator' => '59, 130, 246',
    'Technician' => '139, 92, 246',
    'Specialist' => '236, 72, 153',
    'Professional' => '239, 68, 68',
    'Senior Professional' => '249, 115, 22',
    'Lead Engineer' => '234, 179, 8',
    'Architect' => '6, 182, 212',
    'Principal' => '15, 118, 110',
    'Strategist' => '225, 29, 72',
    'Visionary' => '218, 165, 32',
];
$navRgbColor = $navTierColors[$navUserTier] ?? '168, 162, 158';
    @endphp
    <nav class="fixed-navbar">
        <div class="navbar-left">
            <div class="navbar-avatar"
                style="background: url('https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=random') center/cover; border: 2.5px solid rgba({{ $navRgbColor }}, 0.4);">
            </div>
            <div class="navbar-user-info">
                <div class="navbar-username">{{ auth()->user()->name }}</div>
                <div class="navbar-role" style="display: flex; align-items: center; gap: 4px;">
                    <span
                        style="background: rgba({{ $navRgbColor }}, 0.15); color: rgb({{ $navRgbColor }}); font-size: 0.6rem; font-weight: 700; padding: 1px 6px; border-radius: 6px; margin: 5px 5px 0 0; border: 1px solid rgba({{ $navRgbColor }}, 0.3);">LV.
                        {{ auth()->user()->level }}</span>
                    <span class="user-tier-display">{{ auth()->user()->tier ?? 'Initiate' }}</span>
                </div>
            </div>
        </div>

        <div class="navbar-right">
            <button class="navbar-menu-btn" id="navMenuBtn" style="position: relative;">
                <i class='bx bx-grid-alt'></i>
                @php
$unreadNotificationsCount = isset($notifications) ? $notifications->whereNull('read_at')->count() : 0;
                @endphp
                <span class="nav-unread-dot"
                    style="position: absolute; top: 12px; right: 12px; width: 8px; height: 8px; background: #ef4444; border-radius: 50%; box-shadow: 0 0 8px #ef4444; transition: opacity 0.3s ease, transform 0.3s ease; {{ $unreadNotificationsCount > 0 ? '' : 'display: none; opacity: 0; transform: scale(0);' }}"></span>
            </button>
        </div>
    </nav>
    <!-- MENU PANEL OVERLAY -->
    @include('partials.menu-panel')

    <div class="container">
        <!-- System Alerts -->
        @if(session('success'))
            <div class="premium-alert-banner premium-alert-success" id="alertBannerSuccess">
                <i class="bx bx-check-circle" style="font-size: 1.4rem;"></i>
                <span>{{ session('success') }}</span>
                <button class="premium-alert-close" onclick="dismissAlert(this.parentElement)">&times;</button>
                <div class="premium-alert-progress">
                    <div class="premium-alert-progress-bar"></div>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="premium-alert-banner premium-alert-error" id="alertBannerError">
                <i class="bx bx-error-circle" style="font-size: 1.4rem;"></i>
                <span>{{ session('error') }}</span>
                <button class="premium-alert-close" onclick="dismissAlert(this.parentElement)">&times;</button>
                <div class="premium-alert-progress">
                    <div class="premium-alert-progress-bar"></div>
                </div>
            </div>
        @endif

        <!-- Header -->
        <div class="header-section">
            <div class="hero-card" id="heroCard">
                <div class="hero-bg-layer" id="heroBg1" style="opacity: 1;"></div>
                <div class="hero-bg-layer" id="heroBg2" style="opacity: 0;"></div>
                <div class="hero-bg-layer hero-ad-layer" id="heroBg3" style="opacity: 0; z-index: 1;"></div>
                <script>
                    (function () {
                        var savedIndex = localStorage.getItem('heroBgIndex') || 0;
                        var bgs = [
                            '/images/scenery_header.gif',
                            '/images/scenery_header01.gif',
                            '/images/scenery_header02.gif',
                            '/images/scenery_header03.gif',
                            '/images/scenery_header04.gif'
                        ];
                        var bgImg = bgs[savedIndex] || bgs[0];
                        document.getElementById('heroBg1').style.backgroundImage = 'url(' + bgImg + ')';
                    })();
                    // Iklan images scanned from /images/iklan/
                    window.__heroAdImages = @json(
    collect(glob(public_path('images/iklan/*')))
        ->filter(fn($f) => preg_match('/\.(jpg|jpeg|png|gif|webp|svg)$/i', $f))
        ->map(fn($f) => '/images/iklan/' . basename($f))
        ->values()
);
                </script>
                <div class="hero-overlay">
                    <div class="hero-icons">
                        <div class="hero-icon-wrapper">
                            <div class="hero-icon" id="btnHeroCalendar" style="cursor:pointer;">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                    stroke="rgba(255,255,255,0.85)" stroke-width="1.8" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="3" />
                                    <line x1="16" y1="2" x2="16" y2="6" />
                                    <line x1="8" y1="2" x2="8" y2="6" />
                                    <line x1="3" y1="10" x2="21" y2="10" />
                                </svg>
                            </div>
                        </div>
                        <div class="hero-icon-wrapper">
                            <div class="hero-icon">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                    stroke="rgba(255,255,255,0.85)" stroke-width="1.8" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path
                                        d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" />
                                    <rect x="8" y="2" width="8" height="4" rx="1" ry="1" />
                                    <line x1="9" y1="12" x2="15" y2="12" />
                                    <line x1="9" y1="16" x2="13" y2="16" />
                                </svg>
                            </div>
                        </div>
                        <div class="hero-icon-wrapper">
                            <div class="hero-icon">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                    stroke="rgba(255,255,255,0.85)" stroke-width="1.8" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                                    <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                                </svg>
                            </div>
                        </div>
                        <div class="hero-icon-wrapper">
                            <div class="hero-icon">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                    stroke="rgba(255,255,255,0.85)" stroke-width="1.8" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="3" />
                                    <path
                                        d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="hero-clock-container">
                        <div class="hero-clock-text">
                            <div id="hero-clock-hours">12</div>
                            <div id="hero-clock-minutes">32</div>
                        </div>
                        <div class="hero-clock-star"></div>
                    </div>

                    <!-- Hero Wallpaper Scroller Dots -->
                    <div class="hero-slider-dots">
                        <div class="hero-dot active" data-bg="/images/scenery_header.gif"></div>
                        <div class="hero-dot" data-bg="/images/scenery_header01.gif"></div>
                        <div class="hero-dot" data-bg="/images/scenery_header02.gif"></div>
                        <div class="hero-dot" data-bg="/images/scenery_header03.gif"></div>
                        <div class="hero-dot" data-bg="/images/scenery_header04.gif"></div>
                    </div>
                </div>
            </div>

            <div class="stats-col">
                <div class="stat-card-dark">
                    <div class="exp-row">
                        <div class="exp-star"
                            style="display: flex; align-items: center; justify-content: center; flex-direction: column; color: #fff; font-weight: 800; font-size: 0.8rem; line-height: 1.1; text-shadow: 0 1px 3px rgba(0,0,0,0.5);">
                            <span
                                style="font-size: 0.55rem; opacity: 0.85; font-weight: 600; letter-spacing: 0.5px;">LV</span>
                            <span style="font-size: 1.35rem; font-weight: 900; margin-top: -2px;"
                                class="user-level-display">{{ auth()->user()->level }}</span>
                        </div>
                        <div class="exp-text-col">
                            <div class="exp-label user-next-tier-display" data-format="number">
                                {{ number_format(auth()->user()->next_tier_exp ?? 100, 0, ',', '.') }}
                            </div>
                            <div class="exp-amount">
                                <span
                                    class="user-exp-display">{{ number_format(auth()->user()->exp ?? 0, 0, ',', '.') }}</span>
                                <span class="exp-label"
                                    style="font-size: 0.65rem; color: #fff; align-self: baseline; margin-left: 2px;">EXP</span>
                            </div>
                        </div>
                    </div>
                    <div class="exp-bar-container">
                        <div class="exp-bar-track"></div>
                        @php $expPct = auth()->user()->exp_percentage ?? 0; @endphp
                        <div class="exp-bar-fill user-exp-bar" style="width: {{ $expPct }}%">
                            {{ $expPct < 25 ? $expPct . '%' : $expPct . '% EXP' }}
                        </div>
                    </div>
                </div>

                <div class="stat-card new-tier-card" style="--tier-color: {{ $rgbColor }};">
                    <div class="new-tier-bg-image"></div>
                    <div class="new-tier-bg-gradient"></div>
                    <div class="new-tier-content">
                        <div class="new-tier-badge-row">
                            <span class="new-tier-lvl">LV. <span
                                    class="user-level-display">{{ auth()->user()->level }}</span></span>
                            <span class="new-tier-lbl">TIER</span>
                        </div>
                        <div class="new-tier-title user-tier-display">{{ $userTier }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- BUDDY COMPONENT -->
        <div class="buddy-section">
            <div class="buddy-card-container">
                <!-- Silhouette Cover -->
                <img id="buddy-silhouette" class="buddy-silhouette" src="" alt="">

                <div id="buddy-state-1" class="buddy-state-inner"
                    style="display: {{ auth()->user()->buddy_name ? 'none' : 'flex' }};">
                    <div class="buddy-header text-center">
                        <h3 id="buddy-selection-header" class="text-visible" style="margin-bottom: 5px;">Pilih teman
                            belajar mu!</h3>
                        <p id="buddy-selection-subtitle" class="text-visible"
                            style="color: var(--text-muted); font-size: 0.9rem;">Teman yang akan menemani mu sampai
                            lulus
                        </p>
                    </div>
                    <div class="buddy-avatars-row">
                        <img src="{{ asset('images/buddies/box-1.gif') }}" class="buddy-avatar-option" data-id="1"
                            alt="Buddy 1">
                        <img src="{{ asset('images/buddies/box-2.gif') }}" class="buddy-avatar-option" data-id="2"
                            alt="Buddy 2">
                        <img src="{{ asset('images/buddies/box-3.gif') }}" class="buddy-avatar-option" data-id="3"
                            alt="Buddy 3">
                        <img src="{{ asset('images/buddies/box-4.gif') }}" class="buddy-avatar-option" data-id="4"
                            alt="Buddy 4">
                    </div>
                </div>

                <div id="buddy-state-2" class="buddy-state-inner buddy-naming-state" style="display: none;">
                    <img id="selected-buddy-preview" src="" class="buddy-avatar-preview" alt="Selected Buddy">
                    <div class="buddy-form">
                        <h3 style="margin-bottom: 15px;">Siapa nama teman mu?</h3>
                        <div class="buddy-input-row">
                            <input type="text" id="buddy-name-input" placeholder="isi nama nya" class="buddy-input">
                            <button type="button" id="btn-save-buddy" class="btn-buddy-submit">Selesai</button>
                        </div>
                        <span id="buddy-error-msg" class="buddy-error" style="display: none;">isi nama yang niat
                            yak!!!...</span>
                    </div>
                </div>

                <div id="buddy-state-3" class="buddy-state-inner buddy-active-state"
                    style="display: {{ auth()->user()->buddy_name ? 'flex' : 'none' }}; flex-direction: column; align-items: stretch;">

                    <div class="buddy-chat-header" style="display: flex; align-items: center; gap: 1rem; width: 100%;">
                        <div style="position: relative;" id="active-buddy-avatar-wrapper">
                            <img id="active-buddy-avatar"
                                src="{{ auth()->user()->buddy_avatar ? asset('images/buddies/box-' . auth()->user()->buddy_avatar . '.gif') : '' }}"
                                class="buddy-avatar-preview" alt="My Buddy" style="width: 80px; height: 80px;">
                            <div class="buddy-mood-dot"></div>
                            <!-- Floating Speech Bubble / Toast -->
                            <div id="buddy-toast" class="buddy-toast">
                                <span id="buddy-toast-text"></span>
                                <div class="buddy-toast-arrow"></div>
                            </div>
                        </div>
                        <div class="buddy-chat" style="flex: 1;">
                            <h3 id="active-buddy-name"
                                style="margin-bottom: 5px; display: flex; align-items: center; justify-content: space-between;">
                                <span>{{ auth()->user()->buddy_name ?? '' }}</span>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <span id="buddy-status-label"
                                        style="font-size: 0.8rem; font-weight: normal; color: var(--buddy-mood-color, #10B981); display: flex; align-items: center; gap: 6px; transition: color 0.5s ease;">
                                        <div
                                            style="width: 8px; height: 8px; border-radius: 50%; background: var(--buddy-mood-color, #10B981); box-shadow: 0 0 8px var(--buddy-mood-color, #10B981); transition: background 0.5s ease, box-shadow 0.5s ease;">
                                        </div> <span id="buddy-status-text">Online</span>
                                    </span>
                                    <div id="buddy-mode-switch" class="buddy-mode-switch">
                                        <div class="buddy-mode-slider" id="buddy-mode-slider"></div>
                                        <button type="button" class="buddy-mode-btn active" data-mode="auto"
                                            id="btn-mode-auto" title="Chat Otomatis">
                                            <i class="fas fa-robot"></i>
                                        </button>
                                        <button type="button" class="buddy-mode-btn" data-mode="chat" id="btn-mode-chat"
                                            title="Chat Input">
                                            <i class="fas fa-keyboard"></i>
                                        </button>
                                    </div>
                                    <button type="button" id="btn-toggle-pills" class="buddy-settings-btn"
                                        onclick="toggleBuddyPills()" title="Pengaturan Pill Tags">
                                        <i class="fas fa-cog" id="buddy-settings-icon"></i>
                                    </button>
                                </div>
                            </h3>
                            <div id="buddy-chat-log" class="buddy-chat-log"></div>
                            <p id="buddy-typing-text"
                                style="color: var(--text-muted); font-size: 0.95rem; min-height: 44px; line-height: 1.4;">
                            </p>
                            <div id="buddy-prompt-wrapper" class="buddy-prompt-wrapper">
                                <div id="buddy-prompt-buttons" class="buddy-prompt-inner">
                                    <button id="btn-prompt-yes" class="buddy-action-btn primary"
                                        style="padding: 0.4rem 1rem; flex: none; width: auto; font-size: 0.85rem;">Iya</button>
                                    <button id="btn-prompt-no" class="buddy-action-btn"
                                        style="padding: 0.4rem 1rem; flex: none; width: auto; font-size: 0.85rem;">Tidak</button>
                                </div>
                            </div>
                            <!-- Free Text Chat Input Row -->
                            <div id="buddy-chat-input-wrapper" class="buddy-chat-input-row">
                                <input type="text" id="buddy-chat-input" placeholder="Tanya buddy sesuatu..."
                                    autocomplete="off">
                                <button type="button" id="btn-buddy-chat-send" class="btn-buddy-chat-send"
                                    title="Kirim">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                            <!-- "Tanya Buddy" Pill-Tags -->
                            <div id="buddy-pills-wrapper" class="buddy-pills-row">
                                <div class="buddy-pills-inner">
                                    <button class="buddy-pill-btn" onclick="askBuddy('tips', this)">💡 Tips
                                        Belajar</button>
                                    <button class="buddy-pill-btn" onclick="askBuddy('status', this)">🔥 Status</button>
                                    <button class="buddy-pill-btn" onclick="askBuddy('motivasi', this)">🚀
                                        Motivasi</button>
                                    <button class="buddy-pill-btn" onclick="askBuddy('humor', this)">☕ Lelucon</button>
                                    <button class="buddy-pill-btn buddy-pill-dismiss" onclick="dismissBuddyPills()"
                                        title="Sembunyikan">✕</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SEPARATE EXTENDED INFO CARD -->
            <div id="buddy-stats-card" class="buddy-card-container"
                style="display: none; opacity: 0; transform: translateY(-10px); transition: all 0.4s ease; margin-top: 15px; flex-direction: column; width: 100%;">
                <div
                    style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                    <h4 style="margin: 0; font-size: 1rem; color: var(--text-main);">Informasi Tambahan</h4>
                    <button id="btn-close-stats"
                        style="background: rgba(255,255,255,0.1); border: none; color: white; width: 30px; height: 30px; border-radius: 50%; cursor: pointer; display: flex; justify-content: center; align-items: center; transition: all 0.2s ease;"
                        onmouseover="this.style.background='rgba(255,255,255,0.2)'"
                        onmouseout="this.style.background='rgba(255,255,255,0.1)'">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="buddy-stats-row" style="margin-top: 0;">
                    <div class="buddy-stat-item">
                        <span class="stat-icon">🔥</span>
                        <div class="stat-info">
                            <span class="stat-value" id="buddy-stat-streak">- hr</span>
                            <span class="stat-label">Streak</span>
                        </div>
                    </div>
                    <div class="buddy-stat-item">
                        <span class="stat-icon">📚</span>
                        <div class="stat-info">
                            <span class="stat-value" id="buddy-stat-missions">-/-</span>
                            <span class="stat-label">Misi Selesai</span>
                        </div>
                    </div>
                    <div class="buddy-stat-item">
                        <span class="stat-icon">⚡</span>
                        <div class="stat-info">
                            <span class="stat-value" id="buddy-stat-level">Lv {{ auth()->user()->level ?? 1 }}</span>
                            <span class="stat-label"
                                id="buddy-stat-tier">{{ auth()->user()->tier ?? 'Initiate' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Rekomendasi Belajar -->
                <div id="buddy-recommendation-card" class="buddy-rec-card" style="display: none; margin-top: 15px;">
                    <div class="buddy-rec-header">
                        <span class="buddy-rec-icon">🎯</span>
                        <span class="buddy-rec-tag">Rekomendasi Belajar</span>
                    </div>
                    <div class="buddy-rec-body">
                        <div id="buddy-rec-course-title" class="buddy-rec-course">Memuat kelas...</div>
                        <div id="buddy-rec-lesson-title" class="buddy-rec-lesson">Mencari materi selanjutnya...</div>

                        <div class="buddy-rec-progress-wrapper">
                            <div class="buddy-rec-progress-track">
                                <div id="buddy-rec-progress-bar" class="buddy-rec-progress-bar" style="width: 0%;">
                                </div>
                            </div>
                            <span id="buddy-rec-progress-text" class="buddy-rec-progress-text">Progress: 0%</span>
                        </div>

                        <a id="buddy-rec-action-btn" href="#" class="buddy-rec-btn">
                            Mulai Belajar <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <div class="buddy-quick-actions">
                    <a href="#materi-section" class="buddy-action-btn primary">
                        <i class="fa-solid fa-play"></i> Lanjut Belajar
                    </a>
                    <a href="#misi-section" class="buddy-action-btn">
                        <i class="fa-solid fa-list-check"></i> Cek Misi
                    </a>
                </div>
            </div>
        </div>

        <!-- PROGRESS & MISI HARIAN -->
        <div>
            <div class="section-header">
                <h2 class="section-title">Progress dan misi harian</h2>
                <p class="section-subtitle">Gasss!!, mulai aja dulu ntar tau mau kemana selanjutnya</p>
            </div>

            <div class="missions-container">
                <div class="mission-list">
                    @foreach($dailyMissions as $mission)
                        <div
                            class="mission-item {{ $mission['is_completed'] ? 'done' : ($mission['progress'] > 0 ? 'active' : '') }}">
                            <div class="mission-icon">{{ $mission['is_completed'] ? '✓' : '' }}</div>
                            <div class="mission-text">{{ $mission['name'] }}</div>
                            <div class="mission-progress">{{ $mission['progress'] }}/{{ $mission['target'] }}</div>
                            <div class="mission-reward">+{{ $mission['reward_exp'] }} EXP</div>
                        </div>
                    @endforeach
                </div>

                @php
$totalLessonsCount = 0;
$completedLessonsCount = 0;
if ($userCourse) {
    foreach ($submateris as $sm) {
        foreach ($sm->chapters as $ch) {
            $totalLessonsCount += $ch->lessons->count();
            foreach ($ch->lessons as $les) {
                if (in_array($les->id, $completedLessons)) {
                    $completedLessonsCount++;
                }
            }
        }
    }
}
$courseProgressPct = $totalLessonsCount > 0 ? round(($completedLessonsCount / $totalLessonsCount) * 100) : 0;
                @endphp
                <div class="web-dev-card">
                    <div class="web-dev-pill">
                        <div class="web-dev-dot"></div>
                        Tujuanmu saat ini
                    </div>
                    <div class="web-dev-title">
                        {{ $userCourse ? $userCourse->title : 'Web Development' }}
                    </div>
                    <div class="web-dev-bar-container">
                        <div class="web-dev-bar-track"></div>
                        <div class="web-dev-bar-fill" style="width: {{ $courseProgressPct }}%">
                            {{ $courseProgressPct == 0 ? '%' : $courseProgressPct . ' %' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- UJIAN AKHIR / SERTIFIKAT FOKUS -->
        @if(isset($isCourseCompleted) && $isCourseCompleted)
            @php
    $activeDots = [
        '6,2' => true,
        '4,3' => true,
        '5,3' => true,
        '6,3' => true,
        '7,3' => true,
        '8,3' => true,
        '2,4' => true,
        '3,4' => true,
        '4,4' => true,
        '5,4' => true,
        '6,4' => true,
        '7,4' => true,
        '8,4' => true,
        '9,4' => true,
        '10,4' => true,
        '4,5' => true,
        '5,5' => true,
        '6,5' => true,
        '7,5' => true,
        '8,5' => true,
        '6,6' => true,
        '4,6' => true,
        '5,6' => true,
        '7,6' => true,
        '8,6' => true,
        '4,7' => true,
        '5,7' => true,
        '6,7' => true,
        '7,7' => true,
        '8,7' => true,
        '5,8' => true,
        '6,8' => true,
        '7,8' => true,
        '11,4' => true,
        '11,5' => true,
        '11,6' => true,
        '11,7' => true,
        '10,8' => true,
        '11,8' => true,
        '12,8' => true
    ];
            @endphp
            <div class="android-exam-wrapper">
                @php
    $achievements = auth()->user()->achievements ?? [];
    $passedExams = $achievements['passed_exams'] ?? [];
    $hasPassedExam = $userCourse && in_array($userCourse->id, $passedExams);
                @endphp
                <div class="section-header">
                    <h2 class="section-title">Selamat! Kamu telah menyelesaikan semua materi</h2>
                    <p class="section-subtitle">Buktikan keahlianmu dan dapatkan Sertifikat Fokus</p>
                </div>

                <div class="android-exam-card" style="--accent-rgb: {{ $rgbColor }};">
                    <div class="android-exam-content">
                        @if($hasPassedExam)
                            <h3 class="android-exam-title">Sertifikat Fokus Tersedia! 🎉</h3>
                            <p class="android-exam-desc">Selamat! Kamu telah lulus ujian akhir kelas
                                <strong>{{ $userCourse->title }}</strong>. Unduh Sertifikat Fokus resmimu sekarang sebagai bukti
                                pencapaianmu.
                            </p>

                            <a href="{{ route('certificates.focus', $userCourse->id) }}" class="btn-android-pill"
                                target="_blank"
                                style="background: var(--success); border-color: var(--success); color: white; box-shadow: 0 0 12px var(--success);">
                                <span>Unduh Sertifikat</span>
                            </a>
                        @else
                            <h3 class="android-exam-title">Ujian Akhir</h3>
                            <p class="android-exam-desc">Kamu telah menguasai seluruh submateri pada fokus ini. Ambil ujian
                                akhir sekarang untuk mengevaluasi pemahamanmu secara menyeluruh dan dapatkan <strong>Sertifikat
                                    Fokus</strong> resmi.</p>

                            <a href="{{ route('exam.agreement') }}" class="btn-android-pill">
                                <span>Mulai Ujian</span>
                            </a>
                        @endif
                    </div>

                    <div class="android-exam-widget">
                        <div class="android-exam-screen">
                            <svg width="100" height="100" viewBox="0 0 130 130">
                                @for ($y = 0; $y < 13; $y++)
                                    @for ($x = 0; $x < 13; $x++)
                                        @php
            $isActive = isset($activeDots["$x,$y"]);
            $cx = $x * 10 + 5;
            $cy = $y * 10 + 5;
                                        @endphp
                                        <circle cx="{{ $cx }}" cy="{{ $cy }}" r="3.5"
                                            class="android-exam-svg-dot {{ $isActive ? 'active' : '' }}" />
                                    @endfor
                                @endfor
                            </svg>
                            <div class="android-exam-screen-dot-matrix">STATUS: {{ $hasPassedExam ? 'PASSED' : 'ELIGIBLE' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($hasPassedExam)
                <!-- PILIH TUJUAN LAINNYA COMPONENT -->
                <div class="pilih-tujuan-wrapper" style="--accent-rgb: {{ $rgbColor }};">
                    <h3 class="pilih-tujuan-title">
                        <i class='bx bxs-compass'></i>
                        <span>Pilih Tujuan Belajar Lainnya</span>
                    </h3>
                    <p class="pilih-tujuan-subtitle">Selamat atas kelulusanmu! Mau belajar keahlian apa lagi selanjutnya? Pilih
                        minat dan fokus barumu di bawah ini.</p>

                    <!-- Tab Interest Buttons -->
                    <div class="interest-tabs">
                        @foreach($interests as $index => $int)
                            <button type="button" class="interest-tab-btn {{ $index === 0 ? 'active' : '' }}"
                                data-target="panel-{{ $int->val }}" onclick="switchInterestTab(this)">
                                {!! $int->icon !!}
                                <span>{{ $int->name }}</span>
                            </button>
                        @endforeach
                    </div>

                    <!-- Focus Panels Container -->
                    <form method="POST" action="{{ route('change-focus') }}" id="changeFocusForm">
                        @csrf
                        <input type="hidden" name="interest" id="selectedInterestVal"
                            value="{{ $interests->first()->val ?? '' }}">
                        <input type="hidden" name="focus" id="selectedFocusVal" value="">

                        <div class="focus-panels-container">
                            @foreach($interests as $index => $int)
                                <div class="focus-panel {{ $index === 0 ? 'active' : '' }}" id="panel-{{ $int->val }}">
                                    @forelse($int->focusItems as $f)
                                        @php
                $isCompleted = in_array($f->val, $completedFocuses ?? []);
                $isActiveFocus = $f->val === auth()->user()->focus;
                                        @endphp
                                        <div class="focus-option-card {{ $isActiveFocus ? 'is-active' : '' }} {{ $isCompleted ? 'is-completed' : '' }}"
                                            data-interest="{{ $int->val }}" data-focus="{{ $f->val }}" @if(!$isActiveFocus)
                                            onclick="selectFocusCard(this)" @endif>
                                            <div class="focus-card-header">
                                                <div class="focus-card-icon">
                                                    {!! $f->icon !!}
                                                </div>
                                                <div class="focus-card-title-wrap">
                                                    <div style="display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
                                                        <span class="focus-card-title">{{ $f->name }}</span>
                                                        @if($isActiveFocus)
                                                            <span class="focus-badge focus-badge-active"><i class='bx bx-play-circle'></i>
                                                                Aktif</span>
                                                        @elseif($isCompleted)
                                                            <span class="focus-badge focus-badge-completed"><i
                                                                    class='bx bx-check-circle'></i> Selesai</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="focus-card-desc">{{ $f->desc }}</p>

                                            @if($f->tags)
                                                <div class="focus-card-tags">
                                                    @foreach(explode(',', $f->tags) as $tag)
                                                        <span class="focus-card-tag">{{ trim($tag) }}</span>
                                                    @endforeach
                                                </div>
                                            @endif

                                            @if(!$isActiveFocus)
                                                <div class="focus-card-check">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                        <polyline points="20 6 9 17 4 12"></polyline>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                    @empty
                                        <p style="color: #b3aeb6; font-size: 0.9rem; grid-column: 1/-1; text-align: center;">Belum ada
                                            fokus untuk minat ini.</p>
                                    @endforelse
                                </div>
                            @endforeach
                        </div>

                        <div class="pilih-tujuan-actions">
                            <button type="submit" class="btn-pilih-tujuan-submit" id="btnChangeFocusSubmit" disabled>
                                <span>Pilih Fokus & Lanjut Belajar</span>
                                <i class="bx bx-right-arrow-alt" style="font-size: 1.2rem;"></i>
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        @endif

        <!-- MATERI BELAJAR -->
        <div>
            <div class="section-header">
                <h2 class="section-title">Materi Belajar</h2>
                <p class="section-subtitle">Gasss, yok mulai belajar pilih yang kamu tertarik</p>
            </div>

            <div class="materials-container">
                @forelse($submateris as $index => $submateri)
                    <div class="material-card {{ $index === 0 ? 'expanded' : 'collapsed' }}">
                        <div class="collapsed-content">
                            <div class="collapsed-text">{{ $submateri->title }}</div>
                            <button class="arrow-btn">→</button>
                        </div>
                        <div class="material-expanded-content">
                            <h3 class="material-title">{{ $submateri->title }}</h3>
                            <p class="material-desc">
                                {{ $submateri->description ?: 'Orang yang kerjaan nya bikin user interface, desain yang unik aneh t...' }}
                            </p>
                            <div class="material-actions">
                                <a href="{{ route('courses.show', [$userCourse->id, 'submateri_id' => $submateri->id]) }}"
                                    class="btn btn-white">Mulai Belajar</a>
                                <a href="javascript:void(0)" class="btn btn-white btn-jadwalkan-modal"
                                    data-materi="{{ $userCourse->title }}"
                                    data-submateri="{{ $submateri->title }}">Jadwalkan</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div
                        style="display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 2rem; background: rgba(255,255,255,0.02); border-radius: 34px; border: 1px dashed rgba(255,255,255,0.08); color: #9ca3af; width: 100%; height: 100%;">
                        <div style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.8;">📚</div>
                        <div style="font-size: 1.1rem; font-weight: 700; color: #fff; margin-bottom: 0.5rem;">Belum ada
                            materi pembelajaran</div>
                        <div style="font-size: 0.85rem; color: #8b8591; max-width: 320px; line-height: 1.4;">Kurikulum fokus
                            pilihanmu sedang kami persiapkan. Coba ubah fokus atau kembali nanti!</div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- JADWAL HARI INI -->
        <div>
            <div class="section-header-flex">
                <div class="section-header">
                    <h2 class="section-title">Jadwal mu hari ini</h2>
                    <p class="section-subtitle">Yok check hari ini kamu belajar apa...</p>
                </div>
                <a href="{{ route('jadwal') }}" class="kelola-jadwal-btn">
                    <i class='bx bx-calendar'></i>
                    <span>Kelola Jadwal</span>
                </a>
            </div>

            @php
$currentTime = date('H:i');
$classifiedTodaySchedules = [];
$nextScheduleFound = false;

foreach ($todaySchedules as $sch) {
    $start = date('H:i', strtotime($sch->start_time));
    $end = date('H:i', strtotime($sch->end_time));

    $status = 'nanti';
    $statusLabel = 'Nanti';
    $statusDotColor = '#3b82f6'; // blue

    if ($currentTime >= $start && $currentTime <= $end) {
        $status = 'saat_ini';
        $statusLabel = 'Saat ini';
        $statusDotColor = '#38b2ac'; // green
    } elseif ($currentTime > $end) {
        $status = 'selesai';
        $statusLabel = 'Selesai';
        $statusDotColor = '#a855f7'; // purple
    } else {
        if (!$nextScheduleFound) {
            $status = 'selanjutnya';
            $statusLabel = 'Selanjutnya';
            $statusDotColor = '#e5e7eb'; // white
            $nextScheduleFound = true;
        } else {
            $status = 'nanti';
            $statusLabel = 'Nanti';
            $statusDotColor = '#3b82f6'; // blue
        }
    }

    $config = $sch->routine_config;
    $schColor = $config['color'] ?? null;
    if ($schColor) {
        if ($schColor === 'green') {
            $statusDotColor = '#38b2ac';
        } elseif ($schColor === 'purple') {
            $statusDotColor = '#a855f7';
        } elseif ($schColor === 'blue') {
            $statusDotColor = '#3b82f6';
        } elseif ($schColor === 'white') {
            $statusDotColor = '#e5e7eb';
        }
    }

    $carbonStart = Carbon\Carbon::parse($start);
    $carbonEnd = Carbon\Carbon::parse($end);
    if ($carbonEnd->lt($carbonStart)) {
        $carbonEnd->addDay();
    }
    $diffMinutes = $carbonStart->diffInMinutes($carbonEnd);
    $hoursPart = floor($diffMinutes / 60);
    $minsPart = $diffMinutes % 60;

    $durationStr = '';
    if ($hoursPart > 0) {
        $durationStr .= $hoursPart . ' jam ';
    }
    if ($minsPart > 0 || $hoursPart == 0) {
        $durationStr .= $minsPart . ' menit';
    }
    $durationStr = trim($durationStr);

    $classifiedTodaySchedules[] = [
        'model' => $sch,
        'status' => $status,
        'status_label' => $statusLabel,
        'status_dot' => $statusDotColor,
        'duration_str' => $durationStr,
        'start' => $start,
        'end' => $end,
    ];
}
            @endphp

            <div class="calendar-row">
                @for ($i = 0; $i < 7; $i++)
                    @php
    $timestamp = strtotime("+$i days");
    $dayNameEn = date('l', $timestamp);
    $dayNameId = $dayMap[$dayNameEn] ?? 'Sen';
    $dateNum = date('d', $timestamp);
    $monthNameShort = $monthMap[date('M', $timestamp)] ?? 'Jan';
    $fullDateStr = date('Y-m-d', $timestamp);
    $isActiveCard = ($i === 0);

    $dayDots = [];
    foreach ($schedules as $sch) {
        $isActiveOnThisDay = false;
        $config = $sch->routine_config;
        $wOM = ceil(date('d', $timestamp) / 7);
        $wStr = 'Minggu ' . $wOM;
        $wStrShort = 'M' . $wOM;

        if ($sch->routine_type === 'Harian') {
            $days = $config['days'] ?? [];
            if (empty($days) || in_array($dayNameId, $days)) {
                $isActiveOnThisDay = true;
            }
        } elseif ($sch->routine_type === 'Mingguan') {
            $days = $config['days'] ?? [];
            $weeks = $config['weeks'] ?? [];
            if (in_array($dayNameId, $days) && (in_array($wStr, $weeks) || in_array('Tiap Minggu', $weeks))) {
                $isActiveOnThisDay = true;
            }
        } elseif ($sch->routine_type === 'Bulanan') {
            $months = $config['months'] ?? [];
            $weeks = $config['weeks'] ?? [];
            if (in_array($monthNameShort, $months) && in_array($wStrShort, $weeks)) {
                $isActiveOnThisDay = true;
            }
        } elseif ($sch->routine_type === 'Custom') {
            $customDate = $config['date'] ?? '';
            if ($customDate === $fullDateStr) {
                $isActiveOnThisDay = true;
            }
        }

        if ($isActiveOnThisDay) {
            $schColor = $config['color'] ?? null;
            if ($schColor) {
                $dayDots[] = $schColor;
            } else {
                if ($sch->routine_type === 'Harian') {
                    $dayDots[] = 'green';
                } elseif ($sch->routine_type === 'Mingguan') {
                    $dayDots[] = 'purple';
                } elseif ($sch->routine_type === 'Bulanan') {
                    $dayDots[] = 'blue';
                } else {
                    $dayDots[] = 'white';
                }
            }
        }
    }
    $dayDots = array_slice(array_unique($dayDots), 0, 4);
                    @endphp

                    @if ($isActiveCard)
                        <div class="date-card active" data-date="{{ $fullDateStr }}" data-day="{{ $dayNameId }}"
                            data-num="{{ $dateNum }}" data-month="{{ $monthNameShort }}">
                            <div class="date-active-top">
                                <div class="date-num">{{ $dateNum }}</div>
                                <div class="date-divider"></div>
                                <div style="text-align: left;">
                                    <div class="date-day">{{ $dayNameId }}</div>
                                    <div class="date-month">{{ $monthNameShort }}</div>
                                </div>
                            </div>
                            <div class="date-dots">
                                @foreach ($dayDots as $dot)
                                    <div class="dot {{ $dot }}"></div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="date-card" data-date="{{ $fullDateStr }}" data-day="{{ $dayNameId }}"
                            data-num="{{ $dateNum }}" data-month="{{ $monthNameShort }}">
                            <div class="date-day">{{ $dayNameId }}</div>
                            <div class="date-num">{{ $dateNum }}</div>
                            <div class="date-dots">
                                @foreach ($dayDots as $dot)
                                    <div class="dot {{ $dot }}"></div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endfor
            </div>

            <div class="schedule-details">
                <div class="schedule-column">
                    <div class="section-subtitle"
                        style="margin-bottom:1rem; color:white; font-weight:600; font-size: 1.1rem;">Jadwal saat ini
                    </div>
                    <div class="current-schedule">
                        @if (count($classifiedTodaySchedules) > 0)
                            <div class="slider-controls">
                                <button class="slider-btn" id="prevScheduleBtn">❮</button>
                                <button class="slider-btn" id="nextScheduleBtn">❯</button>
                            </div>
                        @endif

                        <div class="schedule-slider-container">
                            <div class="schedule-slider" id="scheduleSlider">
                                @forelse ($classifiedTodaySchedules as $cs)
                                    @php
    $sch = $cs['model'];
    $status = $cs['status'];
    $statusLabel = $cs['status_label'];
    $statusDot = $cs['status_dot'];
    $durationStr = $cs['duration_str'];
                                    @endphp
                                    <div class="schedule-slide" data-status="{{ $status }}" data-start="{{ $cs['start'] }}"
                                        data-end="{{ $cs['end'] }}">
                                        <div class="timer-tags">
                                            <div class="tag">
                                                <div class="tag-dot" style="background: {{ $statusDot }};"></div>
                                                {{ strtolower($statusLabel) }}
                                            </div>
                                            <div class="tag">{{ $durationStr }}</div>
                                        </div>
                                        <div class="timer-display">
                                            @if ($status === 'saat_ini')
                                                <span class="countdown-timer" data-end="{{ $cs['end'] }}">--:--:--</span>
                                                <div class="timer-sub">waktu<br>tersisa</div>
                                            @elseif ($status === 'selesai')
                                                <span>Selesai</span>
                                                <div class="timer-sub">telah<br>berakhir</div>
                                            @else
                                                <span>0:00:00</span>
                                                <div class="timer-sub">belum<br>dimulai</div>
                                            @endif
                                        </div>
                                        <div class="current-subject">{{ $sch->title }}</div>
                                    </div>
                                @empty
                                    <div class="schedule-slide empty-state-slide"
                                        style="display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 2rem; min-height: 180px; width: 100%;">
                                        <div style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.8;">📅</div>
                                        <div
                                            style="font-size: 1.1rem; font-weight: 700; color: #fff; margin-bottom: 0.5rem;">
                                            Tidak Ada Jadwal Hari Ini</div>
                                        <div
                                            style="font-size: 0.85rem; color: #9ca3af; max-width: 250px; margin: 0 auto; line-height: 1.4;">
                                            Santai dulu, atau buat jadwal belajar baru di <a href="{{ route('jadwal') }}"
                                                style="color: #38b2ac; text-decoration: none; font-weight: 600; transition: color 0.2s;"
                                                onmouseover="this.style.color='#4fd1c5'"
                                                onmouseout="this.style.color='#38b2ac'">menu Jadwal</a>!</div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <div class="schedule-column">
                    <div class="section-subtitle"
                        style="margin-bottom:1rem; color:white; font-weight:600; font-size: 1.1rem;">Detail jadwal hari
                        ini</div>
                    <div class="schedule-list" id="scheduleList">
                        @forelse ($classifiedTodaySchedules as $cs)
                            @php
    $sch = $cs['model'];
    $status = $cs['status'];
    $statusLabel = $cs['status_label'];
    $statusDot = $cs['status_dot'];
                            @endphp
                            <div class="schedule-item">
                                <div class="schedule-item-left">
                                    <div class="status-dot" style="background: {{ $statusDot }};"></div>
                                    {{ $sch->title }}
                                </div>
                                @if ($status === 'saat_ini')
                                    <div class="schedule-status-text">Sedang berlangsung...</div>
                                @elseif ($status === 'selesai')
                                    <div class="schedule-status-text" style="color: #a855f7;">Selesai</div>
                                @elseif ($status === 'selanjutnya')
                                    <div class="schedule-status-text" style="color: #9ca3af;">Berikutnya ({{ $cs['start'] }})
                                    </div>
                                @else
                                    <div class="schedule-status-text" style="color: #8b8591;">Nanti ({{ $cs['start'] }})</div>
                                @endif
                            </div>
                        @empty
                            <div
                                style="display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 2rem 1rem; background: rgba(255,255,255,0.02); border-radius: 20px; border: 1px dashed rgba(255,255,255,0.08); color: #9ca3af; min-height: 180px; height: 100%;">
                                <div style="font-size: 2rem; margin-bottom: 0.5rem; opacity: 0.8;">✨</div>
                                <div style="font-weight: 600; color: #fff; margin-bottom: 0.25rem;">Hari ini Bebas Belajar!
                                </div>
                                <div style="font-size: 0.8rem; color: #8b8591;">Kamu bebas membaca sub-materi apapun atau <a
                                        href="{{ route('jadwal') }}"
                                        style="color: #38b2ac; text-decoration: none; font-weight: 600; transition: color 0.2s;"
                                        onmouseover="this.style.color='#4fd1c5'"
                                        onmouseout="this.style.color='#38b2ac'">kelola jadwalmu</a>.</div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- PROGRESS EMPTY CARDS -->
        <div>
            <div class="section-header">
                <h2 class="section-title">Kejar Rank Quota mu!!</h2>
                <p class="section-subtitle">Gasss!!, ayo kejar rank quota mu biar makin jago coding</p>
            </div>
            <div class="empty-cards-row">
                @foreach ($displayTiers as $index => $t)
                    @if ($t['name'] === $userTier)
                        <div class="tier-progression-card current-tier" style="--tier-color: {{ $t['color'] }};">
                            <div class="card-glow"></div>
                            <div class="feather-texture">
                                <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10 90C35 70 70 35 90 10" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" opacity="0.8" />
                                    <path d="M22 78C12 70 8 60 10 50C15 62 25 70 30 71" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" opacity="0.6" />
                                    <path d="M33 67C20 58 15 48 18 38C23 50 35 58 41 59" stroke="currentColor"
                                        stroke-width="1.5" stroke-linecap="round" opacity="0.6" />
                                    <path d="M45 55C32 45 28 35 30 25C35 37 47 45 53 46" stroke="currentColor"
                                        stroke-width="1.5" stroke-linecap="round" opacity="0.6" />
                                    <path d="M58 42C45 32 40 22 42 12C47 24 59 32 65 33" stroke="currentColor"
                                        stroke-width="1.5" stroke-linecap="round" opacity="0.6" />
                                    <path d="M22 78C32 82 42 82 50 78C40 74 30 74 25 75" stroke="currentColor"
                                        stroke-width="1.5" stroke-linecap="round" opacity="0.6" />
                                    <path d="M33 67C43 71 53 71 61 67C51 63 41 63 36 64" stroke="currentColor"
                                        stroke-width="1.5" stroke-linecap="round" opacity="0.6" />
                                    <path d="M45 55C65 59 65 59 73 55C63 51 53 51 48 52" stroke="currentColor"
                                        stroke-width="1.5" stroke-linecap="round" opacity="0.6" />
                                    <path d="M58 42C68 46 78 46 86 42C76 38 66 38 61 39" stroke="currentColor"
                                        stroke-width="1.5" stroke-linecap="round" opacity="0.6" />
                                </svg>
                            </div>
                            <div class="card-content">
                                <div class="card-header">
                                    <span class="badge-lv">LV. {{ $t['level'] }}</span>
                                    <span class="label-tier">TIER</span>
                                </div>
                                <div class="tier-name">{{ $t['name'] }}</div>
                            </div>
                            <span class="current-badge">Saat Ini</span>
                        </div>
                    @else
                        <div class="tier-progression-card upcoming-tier" style="--tier-color: {{ $t['color'] }};">
                            <div class="feather-texture mini">
                                <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10 90C35 70 70 35 90 10" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" opacity="0.8" />
                                    <path d="M22 78C12 70 8 60 10 50C15 62 25 70 30 71" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" opacity="0.6" />
                                    <path d="M33 67C20 58 15 48 18 38C23 50 35 58 41 59" stroke="currentColor"
                                        stroke-width="1.5" stroke-linecap="round" opacity="0.6" />
                                    <path d="M45 55C32 45 28 35 30 25C35 37 47 45 53 46" stroke="currentColor"
                                        stroke-width="1.5" stroke-linecap="round" opacity="0.6" />
                                    <path d="M58 42C45 32 40 22 42 12C47 24 59 32 65 33" stroke="currentColor"
                                        stroke-width="1.5" stroke-linecap="round" opacity="0.6" />
                                    <path d="M22 78C32 82 42 82 50 78C40 74 30 74 25 75" stroke="currentColor"
                                        stroke-width="1.5" stroke-linecap="round" opacity="0.6" />
                                    <path d="M33 67C43 71 53 71 61 67C51 63 41 63 36 64" stroke="currentColor"
                                        stroke-width="1.5" stroke-linecap="round" opacity="0.6" />
                                    <path d="M45 55C65 59 65 59 73 55C63 51 53 51 48 52" stroke="currentColor"
                                        stroke-width="1.5" stroke-linecap="round" opacity="0.6" />
                                    <path d="M58 42C68 46 78 46 86 42C76 38 66 38 61 39" stroke="currentColor"
                                        stroke-width="1.5" stroke-linecap="round" opacity="0.6" />
                                </svg>
                            </div>
                            <div class="card-content mini">
                                <div class="card-header">
                                    <span class="badge-lv">LV. {{ $t['level'] }}</span>
                                    <span class="label-tier">TIER</span>
                                </div>
                                <div class="tier-name">{{ $t['name'] }}</div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- SEASON DYNAMIC -->
        <div>
            <div class="section-header">
                <h2 class="section-title">{{ $currentSeason->name }}</h2>
                <p class="section-subtitle">season kali ini akan berakhir sesuai timer nya yak!!</p>
            </div>

            <div class="season-timers" data-season-end="{{ $currentSeason->ends_at->toIso8601String() }}">
                <div class="season-row">
                    <div class="season-label">Hari</div>
                    <div class="season-bar-container">
                        <div class="season-bar-track"></div>
                        <div class="season-bar-fill" id="seasonDays" style="width: 0%;">0</div>
                    </div>
                </div>
                <div class="season-row">
                    <div class="season-label">Jam</div>
                    <div class="season-bar-container">
                        <div class="season-bar-track"></div>
                        <div class="season-bar-fill" id="seasonHours" style="width: 0%;">0</div>
                    </div>
                </div>
                <div class="season-row">
                    <div class="season-label">Menit</div>
                    <div class="season-bar-container">
                        <div class="season-bar-track"></div>
                        <div class="season-bar-fill" id="seasonMinutes" style="width: 0%;">0</div>
                    </div>
                </div>
                <div class="season-row">
                    <div class="season-label">Detik</div>
                    <div class="season-bar-container">
                        <div class="season-bar-track"></div>
                        <div class="season-bar-fill" id="seasonSeconds" style="width: 0%;">0</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- LEADERBOARD -->
        <div>
            <div class="section-header">
                <h2 class="section-title">Leaderboard Global</h2>
                <p class="section-subtitle">Leaderboard ini akan di reset di akhir season yak!!</p>
            </div>

            <div class="leaderboard-list">

                @foreach($leaderboard as $index => $lbUser)
                                    @php
                    $rankClass = '';
                    if ($index === 0)
                        $rankClass = 'rank-1';
                    elseif ($index === 1)
                        $rankClass = 'rank-2';
                    elseif ($index === 2)
                        $rankClass = 'rank-3';
                                    @endphp
                                    <!-- Rank {{ $index + 1 }} -->
                                    <div class="leaderboard-item {{ $rankClass }} {{ auth()->id() == $lbUser->id ? 'active-user' : '' }}">
                                        <div class="lb-rank">
                                            <span class="rank-num">{{ sprintf('%02d', $index + 1) }}</span>
                                        </div>
                                        <div class="lb-card">
                                            <div class="lb-left">
                                                <div class="lb-avatar"
                                                    style="background: url('https://ui-avatars.com/api/?name={{ urlencode($lbUser->name) }}&background=random') center/cover;">
                                                </div>
                                                <div class="lb-info">
                                                    <div class="lb-name">{{ $lbUser->name }}
                                                        {!! $lbUser->exp >= 1000000 ? '<span class="tag-max">MAX</span>' : '' !!}
                                                    </div>
                                                    <div class="lb-role"><span
                                                            class="{{ auth()->id() == $lbUser->id ? 'user-tier-display' : '' }}">{{ $lbUser->tier }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <x-user-achievements :user="$lbUser" />
                                            <div class="lb-exp"><span
                                                    class="{{ auth()->id() == $lbUser->id ? 'user-exp-display' : '' }}">{{ number_format($lbUser->exp, 0, ',', '.') }}</span>
                                                <span>EXP</span>
                                            </div>
                                            @if(auth()->id() == $lbUser->id)
                                                <div class="you-text">Me</div>
                                            @endif
                                        </div>
                                        @if(auth()->id() != $lbUser->id)
                                            @php
                        $friendStatus = auth()->user()->friendshipStatusWith($lbUser);
                        $btnClass = '';
                        $btnText = '+ Tambah';
                        if ($friendStatus === 'friends') {
                            $btnClass = 'friends';
                            $btnText = 'Teman';
                        } elseif ($friendStatus === 'pending_sent') {
                            $btnClass = 'pending-sent';
                            $btnText = 'Pending';
                        } elseif ($friendStatus === 'pending_received') {
                            $btnClass = 'pending-received';
                            $btnText = 'Terima';
                        }
                                            @endphp
                                            <div class="lb-action toggle-friend-btn {{ $btnClass }}" data-id="{{ $lbUser->id }}">
                                                {{ $btnText }}
                                            </div>
                                        @endif
                                    </div>
                @endforeach
            </div>

            <!-- Current User Card (Fixed below scroll area) -->
            @php
$currentUser = auth()->user();
$foundIndex = $leaderboard->search(function ($user) use ($currentUser) {
    return $user->id == $currentUser->id;
});

if ($foundIndex !== false) {
    $currentUserRank = $foundIndex + 1;
} else {
    $currentUserRank = \App\Models\User::where('exp', '>', $currentUser->exp)->count() + 1;
}
            @endphp
            <div class="leaderboard-item active-user current-user-lb-card" style="margin-top: 1rem;">
                <div class="lb-rank" style="background: #312b36;">
                    <span class="rank-num">{{ sprintf('%02d', $currentUserRank) }}</span>
                </div>
                <div class="lb-card" style="flex: 1; background: #3a3440; box-shadow: 0 -4px 20px rgba(0,0,0,0.3);">
                    <div class="lb-avatar"
                        style="background: url('https://ui-avatars.com/api/?name={{ urlencode($currentUser->name) }}&background=random') center/cover;">
                    </div>
                    <div class="lb-info">
                        <div class="lb-name">{{ $currentUser->name }}
                            {!! $currentUser->exp >= 1000000 ? '<span class="tag-max">MAX</span>' : '' !!}
                        </div>
                        <div class="lb-role"><span
                                class="user-tier-display">{{ $currentUser->tier ?? 'Visionary' }}</span>
                        </div>
                    </div>
                    <x-user-achievements :user="$currentUser" />
                    <div class="lb-exp"><span
                            class="user-exp-display">{{ number_format($currentUser->exp, 0, ',', '.') }}</span>
                        <span>EXP</span>
                    </div>
                    <div class="you-text">Me</div>
                </div>
            </div>
        </div>

    </div>

    <!-- Pindahkan buddy-sentences.js ke sini agar bisa digunakan oleh initBuddySystem -->
    <script src="{{ asset('js/buddy-sentences.js') }}"></script>

    <!-- FLOATING BUDDY WIDGET (Phase 9) dipindah ke atas agar DOM-nya terbaca sebelum JS berjalan -->
    <div id="buddy-floating-widget" role="button" aria-label="Kembali ke Buddy" title="Kembali ke Buddy">
        <img id="buddy-float-avatar" src="" alt="Buddy">
        <div class="buddy-float-info">
            <span class="buddy-float-name" id="buddy-float-name">Buddy</span>
            <span class="buddy-float-hint">Online</span>
        </div>
        <i class="fas fa-arrow-up buddy-float-scroll-icon"></i>
    </div>

    <script>
        const ALL_TIERS_DATA = @json($allTiers);

        window.syncTierProgressionUI = function (newTierName) {
            let currentIndex = 0;
            for (let i = 0; i < ALL_TIERS_DATA.length; i++) {
                if (ALL_TIERS_DATA[i].name === newTierName) {
                    currentIndex = i;
                    break;
                }
            }

            let startIdx = currentIndex;
            if (startIdx + 5 > ALL_TIERS_DATA.length) {
                startIdx = Math.max(0, ALL_TIERS_DATA.length - 5);
            }

            const displayTiers = ALL_TIERS_DATA.slice(startIdx, startIdx + 5);
            const container = document.querySelector('.empty-cards-row');
            if (container) {
                let html = '';
                displayTiers.forEach((t, index) => {
                    if (t.name === newTierName) {
                        html += `
                        <div class="tier-progression-card current-tier" style="--tier-color: ${t.color};">
                            <div class="card-glow"></div>
                            <div class="feather-texture">
                                <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10 90C35 70 70 35 90 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" opacity="0.8" />
                                    <path d="M22 78C12 70 8 60 10 50C15 62 25 70 30 71" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity="0.6" />
                                    <path d="M33 67C20 58 15 48 18 38C23 50 35 58 41 59" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity="0.6" />
                                    <path d="M45 55C32 45 28 35 30 25C35 37 47 45 53 46" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity="0.6" />
                                    <path d="M58 42C45 32 40 22 42 12C47 24 59 32 65 33" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity="0.6" />
                                    <path d="M22 78C32 82 42 82 50 78C40 74 30 74 25 75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity="0.6" />
                                    <path d="M33 67C43 71 53 71 61 67C51 63 41 63 36 64" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity="0.6" />
                                    <path d="M45 55C65 59 65 59 73 55C63 51 53 51 48 52" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity="0.6" />
                                    <path d="M58 42C68 46 78 46 86 42C76 38 66 38 61 39" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity="0.6" />
                                </svg>
                            </div>
                            <div class="card-content">
                                <div class="card-header">
                                    <span class="badge-lv">LV. ${t.level}</span>
                                    <span class="label-tier">TIER</span>
                                </div>
                                <div class="tier-name">${t.name}</div>
                            </div>
                            <span class="current-badge">Saat Ini</span>
                        </div>`;
                    } else {
                        html += `
                        <div class="tier-progression-card upcoming-tier" style="--tier-color: ${t.color};">
                            <div class="feather-texture mini">
                                <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10 90C35 70 70 35 90 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" opacity="0.8" />
                                    <path d="M22 78C12 70 8 60 10 50C15 62 25 70 30 71" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity="0.6" />
                                    <path d="M33 67C20 58 15 48 18 38C23 50 35 58 41 59" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity="0.6" />
                                    <path d="M45 55C32 45 28 35 30 25C35 37 47 45 53 46" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity="0.6" />
                                    <path d="M58 42C45 32 40 22 42 12C47 24 59 32 65 33" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity="0.6" />
                                    <path d="M22 78C32 82 42 82 50 78C40 74 30 74 25 75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity="0.6" />
                                    <path d="M33 67C43 71 53 71 61 67C51 63 41 63 36 64" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity="0.6" />
                                    <path d="M45 55C65 59 65 59 73 55C63 51 53 51 48 52" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity="0.6" />
                                    <path d="M58 42C68 46 78 46 86 42C76 38 66 38 61 39" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity="0.6" />
                                </svg>
                            </div>
                            <div class="card-content mini">
                                <div class="card-header">
                                    <span class="badge-lv">LV. ${t.level}</span>
                                    <span class="label-tier">TIER</span>
                                </div>
                                <div class="tier-name">${t.name}</div>
                            </div>
                        </div>`;
                    }
                });
                container.innerHTML = html;
            }

            // Sync other tier displays
            document.querySelectorAll('.user-tier-display').forEach(el => {
                el.textContent = newTierName;
            });

            // Sync new-tier-card color if exists
            const currentTierObj = ALL_TIERS_DATA[currentIndex];
            if (currentTierObj) {
                const newTierCard = document.querySelector('.new-tier-card');
                if (newTierCard) {
                    newTierCard.style.setProperty('--tier-color', currentTierObj.color);
                }
            }
        }
        function initDashboardJs() {
            // Hero Clock Update
            const updateHeroClock = () => {
                const now = new Date();
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');

                const hourEl = document.getElementById('hero-clock-hours');
                const minEl = document.getElementById('hero-clock-minutes');

                if (hourEl) hourEl.textContent = hours;
                if (minEl) minEl.textContent = minutes;
            };
            updateHeroClock();
            if (window.heroClockInterval) clearInterval(window.heroClockInterval);
            window.heroClockInterval = setInterval(updateHeroClock, 1000);

            // Interaktivitas untuk bagian Materi Belajar (Accordion effect) menggunakan Event Delegation
            if (!window.materialCardBound) {
                window.materialCardBound = true;
                document.addEventListener('click', function (e) {
                    const card = e.target.closest('.material-card');
                    if (!card) return;

                    // Jika klik terjadi pada tombol di dalam konten yang diperluas, jangan lakukan collapse
                    if (e.target.closest('.btn-white') || e.target.closest('.lesson-card') || e.target.closest('.dashboard-timeline-scroll')) return;

                    // Jika kartu yang diklik sudah aktif, kita tutup (toggle off)
                    if (card.classList.contains('expanded')) {
                        card.classList.remove('expanded');
                        card.classList.add('collapsed');
                        return;
                    }

                    // Tutup semua kartu (jadikan collapsed)
                    document.querySelectorAll('.material-card').forEach(c => {
                        c.classList.remove('expanded');
                        c.classList.add('collapsed');
                    });

                    // Buka kartu yang baru saja diklik
                    card.classList.remove('collapsed');
                    card.classList.add('expanded');
                });
            }

            // Export schedules collection to JS
            const allSchedules = @json($schedules);
            let selectedDate = new Date();

            // Date Formatting utility (YYYY-MM-DD)
            function formatDateYYYYMMDD(date) {
                const y = date.getFullYear();
                const m = String(date.getMonth() + 1).padStart(2, '0');
                const d = String(date.getDate()).padStart(2, '0');
                return `${y}-${m}-${d}`;
            }

            // Duration calculator utility
            function getDurationStr(startTime, endTime) {
                const [startH, startM] = startTime.split(':').map(Number);
                const [endH, endM] = endTime.split(':').map(Number);

                let diffMinutes = (endH * 60 + endM) - (startH * 60 + startM);
                if (diffMinutes < 0) {
                    diffMinutes += 24 * 60; // next day
                }

                const hours = Math.floor(diffMinutes / 60);
                const mins = diffMinutes % 60;

                let str = '';
                if (hours > 0) {
                    str += hours + ' jam ';
                }
                if (mins > 0 || hours === 0) {
                    str += mins + ' menit';
                }
                return str.trim();
            }

            // Client-side schedules recurrence evaluator
            function isScheduleActiveOnDate(sch, date) {
                const dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
                const dayName = dayNames[date.getDay()];

                const d = date.getDate();
                const w = Math.ceil(d / 7);
                const weekStr = 'Minggu ' + w;
                const weekStrShort = 'M' + w;

                const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
                const monthName = monthNames[date.getMonth()];

                let config = sch.routine_config;
                if (typeof config === 'string') {
                    try { config = JSON.parse(config); } catch (e) { config = {}; }
                }
                config = config || {};

                if (sch.routine_type === 'Harian') {
                    const days = config.days || [];
                    return days.length === 0 || days.includes(dayName);
                } else if (sch.routine_type === 'Mingguan') {
                    const days = config.days || [];
                    const weeks = config.weeks || [];
                    return days.includes(dayName) && (weeks.includes(weekStr) || weeks.includes('Tiap Minggu'));
                } else if (sch.routine_type === 'Bulanan') {
                    const months = config.months || [];
                    const weeks = config.weeks || [];
                    return months.includes(monthName) && weeks.includes(weekStrShort);
                } else if (sch.routine_type === 'Custom') {
                    const customDate = config.date || '';
                    const yyyymmdd = formatDateYYYYMMDD(date);
                    return customDate === yyyymmdd;
                }
                return false;
            }

            // Get active schedules for date helper
            function getActiveSchedulesForDate(date) {
                return allSchedules.filter(sch => isScheduleActiveOnDate(sch, date));
            }

            // Get schedules status classification helper
            function classifyScheduleStatusForDate(sch, date) {
                const today = new Date();
                const selectedDateStr = formatDateYYYYMMDD(date);
                const todayDateStr = formatDateYYYYMMDD(today);

                if (selectedDateStr < todayDateStr) {
                    return { label: 'Selesai' };
                } else if (selectedDateStr > todayDateStr) {
                    return { label: 'Nanti' };
                } else {
                    const currentH = today.getHours();
                    const currentM = today.getMinutes();
                    const currentTimeStr = String(currentH).padStart(2, '0') + ':' + String(currentM).padStart(2, '0');
                    const start = sch.start_time.substring(0, 5);
                    const end = sch.end_time.substring(0, 5);

                    if (currentTimeStr >= start && currentTimeStr <= end) {
                        return { label: 'Saat ini' };
                    } else if (currentTimeStr > end) {
                        return { label: 'Selesai' };
                    } else {
                        return { label: 'Nanti' };
                    }
                }
            }

            // Rebuild card helper for active/inactive HTML structure
            function rebuildCardHtml(card, isActive) {
                const day = card.getAttribute('data-day');
                const num = card.getAttribute('data-num');
                const month = card.getAttribute('data-month');
                const dotsEl = card.querySelector('.date-dots');
                const dotsHtml = dotsEl ? dotsEl.innerHTML : '';

                if (isActive) {
                    card.innerHTML = `
                        <div class="date-active-top">
                            <div class="date-num">${num}</div>
                            <div class="date-divider"></div>
                            <div style="text-align: left;">
                                <div class="date-day">${day}</div>
                                <div class="date-month">${month}</div>
                            </div>
                        </div>
                        <div class="date-dots">
                            ${dotsHtml}
                        </div>
                    `;
                } else {
                    card.innerHTML = `
                        <div class="date-day">${day}</div>
                        <div class="date-num">${num}</div>
                        <div class="date-dots">
                            ${dotsHtml}
                        </div>
                    `;
                }
            }

            // Interaktivitas untuk Slider & Detail Jadwal (Dynamic update)
            let currentScheduleIndex = 0;
            const slider = document.getElementById('scheduleSlider');
            const prevBtn = document.getElementById('prevScheduleBtn');
            const nextBtn = document.getElementById('nextScheduleBtn');
            const controls = document.querySelector('.slider-controls');

            function updateSliderNavigation() {
                if (!slider) return;
                const total = slider.children.length;

                if (controls) {
                    if (total > 1 && !slider.children[0].classList.contains('empty-state-slide')) {
                        controls.style.display = 'flex';
                    } else {
                        controls.style.display = 'none';
                    }
                }

                currentScheduleIndex = 0;
                for (let i = 0; i < total; i++) {
                    if (slider.children[i].getAttribute('data-status') === 'saat_ini') {
                        currentScheduleIndex = i;
                        break;
                    }
                }
                slider.style.transform = `translateX(-${currentScheduleIndex * 100}%)`;
            }

            if (prevBtn && nextBtn) {
                nextBtn.addEventListener('click', function () {
                    if (!slider) return;
                    const total = slider.children.length;
                    if (total <= 1) return;
                    currentScheduleIndex = (currentScheduleIndex + 1) % total;
                    slider.style.transform = `translateX(-${currentScheduleIndex * 100}%)`;
                });

                prevBtn.addEventListener('click', function () {
                    if (!slider) return;
                    const total = slider.children.length;
                    if (total <= 1) return;
                    currentScheduleIndex = (currentScheduleIndex - 1 + total) % total;
                    slider.style.transform = `translateX(-${currentScheduleIndex * 100}%)`;
                });
            }

            // Update main schedules HTML layouts dynamically
            function updateDashboardSchedules(date) {
                const activeSchedules = getActiveSchedulesForDate(date);
                activeSchedules.sort((a, b) => a.start_time.localeCompare(b.start_time));

                const today = new Date();
                const selectedDateStr = formatDateYYYYMMDD(date);
                const todayDateStr = formatDateYYYYMMDD(today);

                let classified = [];
                let nextScheduleFound = false;

                activeSchedules.forEach(sch => {
                    const start = sch.start_time.substring(0, 5);
                    const end = sch.end_time.substring(0, 5);

                    let status = 'nanti';
                    let statusLabel = 'Nanti';
                    let statusDotColor = '#3b82f6';

                    if (selectedDateStr < todayDateStr) {
                        status = 'selesai';
                        statusLabel = 'Selesai';
                        statusDotColor = '#a855f7';
                    } else if (selectedDateStr > todayDateStr) {
                        status = 'nanti';
                        statusLabel = 'Nanti';
                        statusDotColor = '#3b82f6';
                    } else {
                        const currentH = today.getHours();
                        const currentM = today.getMinutes();
                        const currentTimeStr = String(currentH).padStart(2, '0') + ':' + String(currentM).padStart(2, '0');

                        if (currentTimeStr >= start && currentTimeStr <= end) {
                            status = 'saat_ini';
                            statusLabel = 'Saat ini';
                            statusDotColor = '#38b2ac';
                        } else if (currentTimeStr > end) {
                            status = 'selesai';
                            statusLabel = 'Selesai';
                            statusDotColor = '#a855f7';
                        } else {
                            status = 'pending';
                        }
                    }

                    let config = sch.routine_config;
                    if (typeof config === 'string') {
                        try { config = JSON.parse(config); } catch (e) { config = {}; }
                    }
                    config = config || {};
                    const schColor = config.color || null;
                    if (schColor) {
                        if (schColor === 'green') statusDotColor = '#38b2ac';
                        else if (schColor === 'purple') statusDotColor = '#a855f7';
                        else if (schColor === 'blue') statusDotColor = '#3b82f6';
                        else if (schColor === 'white') statusDotColor = '#e5e7eb';
                    }

                    classified.push({
                        model: sch,
                        status: status,
                        statusLabel: statusLabel,
                        statusDotColor: statusDotColor,
                        start: start,
                        end: end,
                        durationStr: getDurationStr(sch.start_time, sch.end_time)
                    });
                });

                if (selectedDateStr === todayDateStr) {
                    classified.forEach(item => {
                        if (item.status === 'pending') {
                            if (!nextScheduleFound) {
                                item.status = 'selanjutnya';
                                item.statusLabel = 'Selanjutnya';
                                item.statusDotColor = '#e5e7eb';
                                nextScheduleFound = true;
                            } else {
                                item.status = 'nanti';
                                item.statusLabel = 'Nanti';
                                item.statusDotColor = '#3b82f6';
                            }
                        }
                    });
                } else {
                    classified.forEach(item => {
                        if (item.status === 'pending') {
                            item.status = 'nanti';
                            item.statusLabel = 'Nanti';
                            item.statusDotColor = '#3b82f6';
                        }
                    });
                }

                const sliderEl = document.getElementById('scheduleSlider');
                const listEl = document.getElementById('scheduleList');

                if (sliderEl) {
                    sliderEl.innerHTML = '';
                    if (classified.length > 0) {
                        classified.forEach(cs => {
                            const sch = cs.model;
                            let timerHtml = '';
                            if (cs.status === 'saat_ini') {
                                timerHtml = `<span class="countdown-timer" data-end="${cs.end}">--:--:--</span><div class="timer-sub">waktu<br>tersisa</div>`;
                            } else if (cs.status === 'selesai') {
                                timerHtml = `<span>Selesai</span><div class="timer-sub">telah<br>berakhir</div>`;
                            } else {
                                timerHtml = `<span>0:00:00</span><div class="timer-sub">belum<br>dimulai</div>`;
                            }

                            const slideEl = document.createElement('div');
                            slideEl.className = 'schedule-slide';
                            slideEl.setAttribute('data-status', cs.status);
                            slideEl.setAttribute('data-start', cs.start);
                            slideEl.setAttribute('data-end', cs.end);
                            slideEl.innerHTML = `
                                <div class="timer-tags">
                                    <div class="tag">
                                        <div class="tag-dot" style="background: ${cs.statusDotColor}; box-shadow: 0 0 10px ${cs.statusDotColor}90;"></div> ${cs.statusLabel.toLowerCase()}
                                    </div>
                                    <div class="tag">${cs.durationStr}</div>
                                </div>
                                <div class="timer-display">
                                    ${timerHtml}
                                </div>
                                <div class="current-subject">${sch.title}</div>
                            `;
                            sliderEl.appendChild(slideEl);
                        });
                    } else {
                        sliderEl.innerHTML = `
                            <div class="schedule-slide empty-state-slide" style="display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 2rem; min-height: 180px; width: 100%;">
                                <div style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.8;">📅</div>
                                <div style="font-size: 1.1rem; font-weight: 700; color: #fff; margin-bottom: 0.5rem;">Tidak Ada Jadwal</div>
                                <div style="font-size: 0.85rem; color: #9ca3af; max-width: 250px; margin: 0 auto; line-height: 1.4;">Santai dulu, atau buat jadwal belajar baru di <a href="/jadwal" style="color: #38b2ac; text-decoration: none; font-weight: 600; transition: color 0.2s;" onmouseover="this.style.color='#4fd1c5'" onmouseout="this.style.color='#38b2ac'">menu Jadwal</a>!</div>
                            </div>
                        `;
                    }
                }

                if (listEl) {
                    listEl.innerHTML = '';
                    if (classified.length > 0) {
                        classified.forEach(cs => {
                            const sch = cs.model;
                            let statusText = '';
                            if (cs.status === 'saat_ini') {
                                statusText = 'Sedang berlangsung...';
                            } else if (cs.status === 'selesai') {
                                statusText = '<span style="color: #a855f7;">Selesai</span>';
                            } else if (cs.status === 'selanjutnya') {
                                statusText = `<span style="color: #e5e7eb;">Berikutnya (${cs.start})</span>`;
                            } else {
                                statusText = `<span style="color: #8b8591;">Nanti (${cs.start})</span>`;
                            }

                            const itemEl = document.createElement('div');
                            itemEl.className = 'schedule-item';
                            itemEl.innerHTML = `
                                <div class="schedule-item-left" style="display: flex; align-items: center; gap: 1.25rem; font-size: 0.95rem; font-weight: 600; color: white; min-width: 0; flex: 1; margin-right: 1rem;">
                                    <div class="status-dot" style="background: ${cs.statusDotColor}; box-shadow: 0 0 10px ${cs.statusDotColor}90; flex-shrink: 0;"></div>
                                    <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block;">${sch.title}</span>
                                </div>
                                <div class="schedule-status-text" style="flex-shrink: 0;">${statusText}</div>
                            `;
                            listEl.appendChild(itemEl);
                        });
                    } else {
                        listEl.innerHTML = `
                            <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 2rem 1rem; background: rgba(255,255,255,0.02); border-radius: 20px; border: 1px dashed rgba(255,255,255,0.08); color: #9ca3af; min-height: 180px; width: 100%;">
                                <div style="font-size: 2rem; margin-bottom: 0.5rem; opacity: 0.8;">✨</div>
                                <div style="font-weight: 600; color: #fff; margin-bottom: 0.25rem;">Bebas Belajar!</div>
                                <div style="font-size: 0.8rem; color: #8b8591;">Kamu bebas membaca sub-materi apapun atau <a href="/jadwal" style="color: #38b2ac; text-decoration: none; font-weight: 600; transition: color 0.2s;" onmouseover="this.style.color='#4fd1c5'" onmouseout="this.style.color='#38b2ac'">kelola jadwalmu</a>.</div>
                            </div>
                        `;
                    }
                }

                updateSliderNavigation();
                updateCountdowns();
            }

            // Sync global date select action
            function selectDate(date) {
                selectedDate = date;
                const dateStr = formatDateYYYYMMDD(date);

                // 1. Sync horizontal cards
                const cards = document.querySelectorAll('.calendar-row .date-card');
                cards.forEach(card => {
                    const cardDate = card.getAttribute('data-date');
                    if (cardDate === dateStr) {
                        if (!card.classList.contains('active')) {
                            card.classList.add('active');
                            rebuildCardHtml(card, true);
                        }
                    } else {
                        if (card.classList.contains('active')) {
                            card.classList.remove('active');
                            rebuildCardHtml(card, false);
                        }
                    }
                });

                // 2. Sync main dashboard panels
                updateDashboardSchedules(date);

                // 3. Sync mini calendar popup visual elements
                updateMiniCalendarUI(date);
            }

            // Bind click listener to horizontal date cards
            document.querySelectorAll('.calendar-row .date-card').forEach(card => {
                card.addEventListener('click', function () {
                    const dateStr = this.getAttribute('data-date');
                    if (dateStr) {
                        const parts = dateStr.split('-');
                        const clickedDate = new Date(parseInt(parts[0]), parseInt(parts[1]) - 1, parseInt(parts[2]));
                        selectDate(clickedDate);
                    }
                });
            });

            // Live Ticking Countdown Timer for active schedule
            const updateCountdowns = () => {
                const now = new Date();
                const timers = document.querySelectorAll('.countdown-timer');

                timers.forEach(timer => {
                    const endStr = timer.getAttribute('data-end');
                    if (!endStr) return;

                    const [endHours, endMinutes] = endStr.split(':').map(Number);
                    const endTime = new Date();
                    endTime.setHours(endHours, endMinutes, 0, 0);

                    let diffMs = endTime.getTime() - now.getTime();

                    if (diffMs <= 0) {
                        timer.textContent = "0:00:00";
                        if (!timer.dataset.expired) {
                            timer.dataset.expired = "true";
                            setTimeout(() => {
                                window.location.reload();
                            }, 2000);
                        }
                    } else {
                        const totalSecs = Math.floor(diffMs / 1000);
                        const hours = Math.floor(totalSecs / 3600);
                        const minutes = Math.floor((totalSecs % 3600) / 60);
                        const seconds = totalSecs % 60;

                        const hStr = hours;
                        const mStr = String(minutes).padStart(2, '0');
                        const sStr = String(seconds).padStart(2, '0');

                        timer.textContent = `${hStr}:${mStr}:${sStr}`;
                    }
                });
            };

            updateCountdowns();
            setInterval(updateCountdowns, 1000);
            updateSliderNavigation();

            // Mini Calendar Popup
            const btn = document.getElementById('btnHeroCalendar');
            if (!btn) return;

            // Build popup HTML with elegant month selection navigators
            const popup = document.createElement('div');
            popup.className = 'mini-cal-popup';
            popup.id = 'miniCalPopup';
            popup.innerHTML = `
                <div class="mini-cal-main">
                    <div class="mini-cal-grid">
                        <div class="mini-cal-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.7rem;">
                            <button id="prevMonthBtn" style="background: none; border: none; color: #6b6570; cursor: pointer; font-size: 0.9rem; transition: color 0.15s; outline: none;" onmouseover="this.style.color='white'" onmouseout="this.style.color='#6b6570'">❮</button>
                            <span id="miniCalTitle" style="font-weight: 700; font-size: 0.95rem; color: white;"></span>
                            <button id="nextMonthBtn" style="background: none; border: none; color: #6b6570; cursor: pointer; font-size: 0.9rem; transition: color 0.15s; outline: none;" onmouseover="this.style.color='white'" onmouseout="this.style.color='#6b6570'">❯</button>
                        </div>
                        <div class="mini-cal-days-header">
                            <span>SUN</span><span>MON</span><span>TUE</span><span>WED</span><span>THU</span><span>FRI</span><span>SAT</span>
                        </div>
                        <div class="mini-cal-cells" id="miniCalCells"></div>
                    </div>
                    <div class="mini-cal-time">
                        <div class="mini-cal-time-label">Jadwal Belajar</div>
                        <div id="miniCalTimes"></div>
                    </div>
                </div>
                <div class="mini-cal-event" id="miniCalEvent" style="backdrop-filter: blur(10px); background: rgba(28, 26, 31, 0.95);">
                    <div class="mini-cal-event-date" style="background: rgba(56, 178, 172, 0.1); border: 1px solid rgba(56, 178, 172, 0.2); color: #38b2ac;">
                        <div class="mini-cal-event-month" id="miniCalEvtMonth" style="color: #38b2ac;"></div>
                        <div class="mini-cal-event-day" id="miniCalEvtDay"></div>
                    </div>
                    <div class="mini-cal-event-info" style="min-width: 0;">
                        <div class="mini-cal-event-title" style="font-size: 0.85rem; font-weight: 700; margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Bebas belajar</div>
                        <div class="mini-cal-event-time" id="miniCalEvtTime">-</div>
                    </div>
                </div>
            `;
            document.body.appendChild(popup);

            // Style definitions injected directly to dashboard page
            const style = document.createElement('style');
            style.textContent = `
                .mini-cal-cell {
                    position: relative !important;
                    flex-direction: column !important;
                    gap: 1px !important;
                }
                .mini-cal-cell-dots {
                    display: flex;
                    gap: 2px;
                    justify-content: center;
                    position: absolute;
                    bottom: 2px;
                    left: 50%;
                    transform: translateX(-50%);
                }
                .mini-cal-dot {
                    width: 3px;
                    height: 3px;
                    border-radius: 50%;
                }
                .mini-cal-dot.green { background: #38b2ac !important; }
                .mini-cal-dot.purple { background: #a855f7 !important; }
                .mini-cal-dot.blue { background: #3b82f6 !important; }
                .mini-cal-dot.white { background: #e5e7eb !important; }

                .mini-cal-cell.selected-day {
                    background: #38b2ac !important;
                    color: #0a0a0a !important;
                    font-weight: 800 !important;
                    box-shadow: 0 0 10px rgba(56, 178, 172, 0.4);
                }
            `;
            document.head.appendChild(style);

            let displayDate = new Date(selectedDate.getTime());
            const MONTHS = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            const MONTHS_ID = ['JAN', 'FEB', 'MAR', 'APR', 'MEI', 'JUN', 'JUL', 'AGT', 'SEP', 'OKT', 'NOV', 'DES'];

            // Render calendar grid
            function renderCal() {
                const year = displayDate.getFullYear();
                const month = displayDate.getMonth();
                document.getElementById('miniCalTitle').textContent = `${MONTHS[month]} ${year}`;

                const firstDay = new Date(year, month, 1).getDay();
                const daysInMonth = new Date(year, month + 1, 0).getDate();
                const cells = document.getElementById('miniCalCells');
                cells.innerHTML = '';

                for (let i = 0; i < firstDay; i++) {
                    const el = document.createElement('div');
                    el.className = 'mini-cal-cell empty';
                    cells.appendChild(el);
                }

                for (let d = 1; d <= daysInMonth; d++) {
                    const cellDate = new Date(year, month, d);
                    const cellDateStr = formatDateYYYYMMDD(cellDate);
                    const selectedDateStr = formatDateYYYYMMDD(selectedDate);

                    // Render visual dots for days with active schedules
                    const activeSchs = getActiveSchedulesForDate(cellDate);
                    let dotsHtml = '';
                    if (activeSchs.length > 0) {
                        const colors = [];
                        activeSchs.forEach(sch => {
                            let config = sch.routine_config;
                            if (typeof config === 'string') {
                                try { config = JSON.parse(config); } catch (e) { config = {}; }
                            }
                            config = config || {};
                            let schColor = config.color || null;
                            if (!schColor) {
                                if (sch.routine_type === 'Harian') schColor = 'green';
                                else if (sch.routine_type === 'Mingguan') schColor = 'purple';
                                else if (sch.routine_type === 'Bulanan') schColor = 'blue';
                                else schColor = 'white';
                            }
                            colors.push(schColor);
                        });
                        const uniqueColors = [...new Set(colors)].slice(0, 4);
                        dotsHtml = `<div class="mini-cal-cell-dots">` + uniqueColors.map(c => `<span class="mini-cal-dot ${c}"></span>`).join('') + `</div>`;
                    }

                    const el = document.createElement('div');
                    el.className = 'mini-cal-cell' + (cellDateStr === selectedDateStr ? ' selected-day' : '');
                    el.setAttribute('data-date', cellDateStr);
                    el.innerHTML = `${d}${dotsHtml}`;

                    el.addEventListener('click', (e) => {
                        e.stopPropagation();
                        selectedDate = new Date(year, month, d);
                        selectDate(selectedDate);
                    });

                    cells.appendChild(el);
                }

                document.getElementById('miniCalEvtMonth').textContent = MONTHS_ID[month];
                document.getElementById('miniCalEvtDay').textContent = selectedDate.getDate();
            }

            // Render schedule lists for selected day
            function renderTimesForDate(date) {
                const container = document.getElementById('miniCalTimes');
                if (!container) return;

                container.innerHTML = '';

                const activeSchs = getActiveSchedulesForDate(date);
                activeSchs.sort((a, b) => a.start_time.localeCompare(b.start_time));

                if (activeSchs.length > 0) {
                    activeSchs.forEach((sch, idx) => {
                        const start = sch.start_time.substring(0, 5);
                        const end = sch.end_time.substring(0, 5);

                        const el = document.createElement('div');
                        el.className = 'mini-cal-time-item';
                        el.textContent = `${start} - ${sch.title}`;
                        el.style.fontSize = '0.75rem';
                        el.style.padding = '0.45rem 0.6rem';
                        el.style.textAlign = 'left';
                        el.style.overflow = 'hidden';
                        el.style.textOverflow = 'ellipsis';
                        el.style.whiteSpace = 'nowrap';

                        el.addEventListener('click', () => {
                            container.querySelectorAll('.mini-cal-time-item').forEach(x => x.classList.remove('active'));
                            el.classList.add('active');

                            const statusInfo = classifyScheduleStatusForDate(sch, date);

                            document.getElementById('miniCalEvtTime').innerHTML = `
                                <div style="font-size:0.7rem; color:#8b8591;">${start} - ${end} (${statusInfo.label})</div>
                                <div style="font-size:0.72rem; color:rgba(255,255,255,0.7); margin-top:3px; line-height:1.3; word-break:break-word; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">
                                    ${sch.description || 'Tidak ada deskripsi'}
                                </div>
                            `;

                            const titleEl = document.querySelector('.mini-cal-event-title');
                            if (titleEl) titleEl.textContent = sch.title;
                        });

                        container.appendChild(el);

                        if (idx === 0) {
                            el.click();
                        }
                    });
                } else {
                    container.innerHTML = `
                        <div style="font-size: 0.72rem; color: #6b6570; text-align: center; padding: 2rem 0.5rem; line-height: 1.4;">
                            Bebas belajar<br>
                            <span style="font-size: 0.65rem; opacity: 0.8;">Tidak ada jadwal</span>
                        </div>
                    `;
                    document.getElementById('miniCalEvtTime').textContent = '-';
                    const titleEl = document.querySelector('.mini-cal-event-title');
                    if (titleEl) titleEl.textContent = 'Bebas belajar';
                }
            }

            // Sync calendar popup elements
            window.updateMiniCalendarUI = function (date) {
                displayDate = new Date(date.getTime());
                renderCal();
                renderTimesForDate(date);
            };

            // Month navigation bindings
            document.getElementById('prevMonthBtn').addEventListener('click', (e) => {
                e.stopPropagation();
                displayDate.setMonth(displayDate.getMonth() - 1);
                renderCal();
            });

            document.getElementById('nextMonthBtn').addEventListener('click', (e) => {
                e.stopPropagation();
                displayDate.setMonth(displayDate.getMonth() + 1);
                renderCal();
            });

            // Position popup near the button
            function positionPopup() {
                const rect = btn.getBoundingClientRect();
                const popupW = 340;
                let left = rect.right + 8;
                if (left + popupW > window.innerWidth) left = rect.left - popupW - 8;
                popup.style.left = left + 'px';
                popup.style.top = (rect.top + window.scrollY) + 'px';
            }

            renderCal();
            renderTimesForDate(selectedDate);

            let isOpen = false;
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                isOpen = !isOpen;
                positionPopup();
                if (isOpen) {
                    popup.style.display = 'flex';
                    requestAnimationFrame(() => popup.classList.add('open'));
                } else {
                    popup.classList.remove('open');
                    setTimeout(() => { popup.style.display = 'none'; }, 250);
                }
            });

            document.addEventListener('click', (e) => {
                if (isOpen && !popup.contains(e.target) && e.target !== btn) {
                    isOpen = false;
                    popup.classList.remove('open');
                    setTimeout(() => { popup.style.display = 'none'; }, 250);
                }
            });
        }

        // ----------------------------------------------------
        // BUDDY COMPONENT LOGIC
        // ----------------------------------------------------
        function initBuddySystem() {
            const state1 = document.getElementById('buddy-state-1');
            const state2 = document.getElementById('buddy-state-2');
            const state3 = document.getElementById('buddy-state-3');



            const avatarOptions = document.querySelectorAll('.buddy-avatar-option');
            const previewAvatar = document.getElementById('selected-buddy-preview');
            const nameInput = document.getElementById('buddy-name-input');
            const btnSave = document.getElementById('btn-save-buddy');
            const errorMsg = document.getElementById('buddy-error-msg');
            const silhouette = document.getElementById('buddy-silhouette');

            let selectedAvatarId = null;

            if (avatarOptions.length > 0) {
                // Personality color map for hover glow
                const buddyColors = {
                    '1': '#10b981', // Chill Mentor — emerald
                    '2': '#f59e0b', // Energetic Coach — amber
                    '3': '#818cf8', // Wise Sage — indigo
                    '4': '#ec4899'  // Hype Beast — pink
                };
                const buddyTitles = {
                    '1': 'Si Paling Kalem <i class="fas fa-leaf" style="margin-left: 6px; font-size: 0.9em; opacity: 0.85;"></i>',
                    '2': 'Si Paling Ambisius <i class="fas fa-fire" style="margin-left: 6px; font-size: 0.9em; opacity: 0.85;"></i>',
                    '3': 'Si Paling Bijak <i class="fas fa-book" style="margin-left: 6px; font-size: 0.9em; opacity: 0.85;"></i>',
                    '4': 'Si Paling Hype <i class="fas fa-bolt" style="margin-left: 6px; font-size: 0.9em; opacity: 0.85;"></i>'
                };
                const buddySubtitles = {
                    '1': '"Pelan-pelan aja, yang penting sampai..."',
                    '2': '"GASS!! Belajar terus sampai jago!!"',
                    '3': '"Ilmu adalah cahaya yang tak pernah padam"',
                    '4': '"Slay abis bestie, kita coding bareng~"'
                };
                let hoverTransitionTimer = null;

                // Smooth text transition helper
                function animateTextChange(el, newText, newColor, newShadow) {
                    if (!el) return;
                    // Phase 1: fade out
                    el.classList.remove('text-visible');
                    el.classList.add('text-transitioning-out');

                    setTimeout(() => {
                        // Phase 2: swap content while invisible, position for entry
                        el.innerHTML = newText;
                        if (newColor !== null) el.style.color = newColor;
                        if (newShadow !== null) el.style.textShadow = newShadow;
                        el.classList.remove('text-transitioning-out');
                        el.classList.add('text-transitioning-in');

                        // Force reflow
                        void el.offsetHeight;

                        // Phase 3: fade in from below
                        requestAnimationFrame(() => {
                            el.classList.remove('text-transitioning-in');
                            el.classList.add('text-visible');
                        });
                    }, 180); // matches CSS transition-out duration
                }

                avatarOptions.forEach(opt => {
                    // Hover effect for silhouette + animated text
                    opt.addEventListener('mouseenter', function () {
                        if (silhouette) {
                            silhouette.src = this.src;
                            silhouette.classList.add('show');
                        }
                        // Cancel any pending reset
                        if (hoverTransitionTimer) {
                            clearTimeout(hoverTransitionTimer);
                            hoverTransitionTimer = null;
                        }
                        const avatarId = this.dataset.id;
                        const selHeader = document.getElementById('buddy-selection-header');
                        const selSub = document.getElementById('buddy-selection-subtitle');
                        const color = buddyColors[avatarId] || '#818cf8';
                        const glowShadow = `0 0 20px ${color}44, 0 0 40px ${color}22`;

                        animateTextChange(selHeader, buddyTitles[avatarId] || 'Pilih teman belajar mu!', color, glowShadow);
                        animateTextChange(selSub, buddySubtitles[avatarId] || 'Teman yang akan menemani mu sampai lulus', color, 'none');
                    });

                    opt.addEventListener('mouseleave', function () {
                        if (silhouette) {
                            silhouette.classList.remove('show');
                        }
                        // Debounce: delay reset so quick hover between avatars doesn't flicker
                        hoverTransitionTimer = setTimeout(() => {
                            const selHeader = document.getElementById('buddy-selection-header');
                            const selSub = document.getElementById('buddy-selection-subtitle');
                            animateTextChange(selHeader, 'Pilih teman belajar mu!', '', 'none');
                            animateTextChange(selSub, 'Teman yang akan menemani mu sampai lulus', 'var(--text-muted)', 'none');
                        }, 100);
                    });

                    opt.addEventListener('click', function () {
                        avatarOptions.forEach(o => o.classList.remove('selected'));
                        this.classList.add('selected');

                        selectedAvatarId = this.dataset.id;
                        previewAvatar.src = this.src;

                        const container = document.querySelector('.buddy-card-container');

                        // 1. Get exact current height
                        const startHeight = container.getBoundingClientRect().height;

                        // 2. Temporarily swap states to measure new height
                        state1.style.display = 'none';
                        state2.style.display = 'flex';
                        const endHeight = container.getBoundingClientRect().height;

                        // 3. Revert back to prepare for animation
                        state1.style.display = 'flex';
                        state2.style.display = 'none';

                        // 4. Lock container height
                        container.style.boxSizing = 'border-box';
                        container.style.height = startHeight + 'px';
                        container.style.overflow = 'hidden';
                        container.style.position = 'relative';

                        // 5. Position state1 absolutely so it can crossfade out smoothly
                        const state1Rect = state1.getBoundingClientRect();
                        const containerRect = container.getBoundingClientRect();
                        state1.style.position = 'absolute';
                        state1.style.top = (state1Rect.top - containerRect.top) + 'px';
                        state1.style.left = (state1Rect.left - containerRect.left) + 'px';
                        state1.style.width = state1Rect.width + 'px';

                        // Force reflow
                        void container.offsetHeight;

                        // 6. Start transitions
                        container.style.transition = 'height 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
                        container.style.height = endHeight + 'px';

                        state1.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                        state1.style.opacity = '0';
                        state1.style.transform = 'scale(0.95)';

                        state2.style.display = 'flex';
                        state2.style.opacity = '0';
                        void state2.offsetWidth; // force reflow for state 2

                        state2.style.transition = 'opacity 0.4s ease 0.1s';
                        state2.style.opacity = '1';

                        // Trigger inner animations
                        previewAvatar.classList.remove('anim-enter');
                        document.querySelector('.buddy-form').classList.remove('anim-enter');
                        void previewAvatar.offsetWidth;
                        previewAvatar.classList.add('anim-enter');
                        document.querySelector('.buddy-form').classList.add('anim-enter');

                        setTimeout(() => {
                            state1.style.display = 'none';
                            state1.style.position = '';
                            state1.style.top = '';
                            state1.style.left = '';
                            state1.style.width = '';
                            state1.style.opacity = '';
                            state1.style.transform = '';

                            container.style.height = 'auto';
                            container.style.overflow = 'visible';
                            container.style.transition = '';
                            container.style.boxSizing = '';
                            nameInput.focus();
                        }, 500);
                    });
                });
            }

            if (btnSave) {
                btnSave.addEventListener('click', async function () {
                    const name = nameInput.value.trim();
                    if (!name) {
                        errorMsg.style.display = 'block';
                        nameInput.style.borderColor = '#ef4444';
                        return;
                    }

                    errorMsg.style.display = 'none';
                    nameInput.style.borderColor = 'rgba(255, 255, 255, 0.2)';
                    btnSave.textContent = 'Menyimpan...';

                    try {
                        const response = await fetch('/api/user/buddy', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                buddy_avatar: selectedAvatarId,
                                buddy_name: name
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            const container = document.querySelector('.buddy-card-container');

                            // 1. Get exact current height and original avatar rect (while state2 is visible)
                            const startHeight = container.getBoundingClientRect().height;
                            const selectedBuddyPreview = document.getElementById('selected-buddy-preview');
                            const rect2 = selectedBuddyPreview.getBoundingClientRect();

                            // 2. Temporarily swap states to measure new height & target positions
                            state2.style.display = 'none';
                            state3.style.display = 'flex';

                            // Inject name and thinking text so height is accurate before measurement
                            const activeAvatar = document.getElementById('active-buddy-avatar');
                            const activeName = document.getElementById('active-buddy-name');
                            const textContainer = document.getElementById('buddy-typing-text');

                            activeAvatar.src = previewAvatar.src;
                            const nameSpan = activeName.querySelector('span');
                            if (nameSpan) nameSpan.textContent = data.buddy_name;
                            if (textContainer) {
                                textContainer.innerHTML = '<i><span style="opacity:0.6">Sedang memikirkan...</span></i>';
                            }

                            const endHeight = container.getBoundingClientRect().height;

                            // Measure avatar target position
                            const containerRect = container.getBoundingClientRect();
                            const rect3 = activeAvatar.getBoundingClientRect();

                            const startTop = rect2.top - containerRect.top;
                            const startLeft = rect2.left - containerRect.left;
                            const startWidth = rect2.width;
                            const startHeightAvatar = rect2.height;

                            const endTop = rect3.top - containerRect.top;
                            const endLeft = rect3.left - containerRect.left;
                            const endWidth = rect3.width;
                            const endHeightAvatar = rect3.height;

                            // 3. Revert back to prepare for animation
                            state2.style.display = 'flex';
                            state3.style.display = 'none';

                            // Create the flying avatar clone
                            const clone = document.createElement('img');
                            clone.src = selectedBuddyPreview.src;
                            clone.className = 'buddy-avatar-preview';
                            clone.style.position = 'absolute';
                            clone.style.top = startTop + 'px';
                            clone.style.left = startLeft + 'px';
                            clone.style.width = startWidth + 'px';
                            clone.style.height = startHeightAvatar + 'px';
                            clone.style.zIndex = '999';
                            clone.style.pointerEvents = 'none';
                            clone.style.transition = 'all 0.6s cubic-bezier(0.16, 1, 0.3, 1)';
                            container.appendChild(clone);

                            // Force reflow on clone to guarantee starting position is painted
                            void clone.offsetHeight;

                            // Hide real avatars during transition
                            selectedBuddyPreview.style.opacity = '0';
                            activeAvatar.style.opacity = '0';

                            // 4. Lock container height
                            container.style.boxSizing = 'border-box';
                            container.style.height = startHeight + 'px';
                            container.style.overflow = 'hidden';
                            container.style.position = 'relative';

                            // 5. Position state2 absolutely
                            const state2Rect = state2.getBoundingClientRect();
                            state2.style.position = 'absolute';
                            state2.style.top = (state2Rect.top - containerRect.top) + 'px';
                            state2.style.left = (state2Rect.left - containerRect.left) + 'px';
                            state2.style.width = state2Rect.width + 'px';

                            // Force reflow
                            void container.offsetHeight;

                            // 6. Start transitions (Synchronized to 0.6s with premium easing)
                            container.style.transition = 'height 0.6s cubic-bezier(0.16, 1, 0.3, 1)';
                            container.style.height = endHeight + 'px';

                            state2.style.transition = 'opacity 0.4s cubic-bezier(0.16, 1, 0.3, 1), transform 0.4s cubic-bezier(0.16, 1, 0.3, 1)';
                            state2.style.opacity = '0';
                            state2.style.transform = 'scale(0.96)';

                            state3.style.display = 'flex';
                            state3.style.opacity = '0';
                            void state3.offsetWidth;

                            state3.style.transition = 'opacity 0.6s cubic-bezier(0.16, 1, 0.3, 1)';
                            state3.style.opacity = '1';

                            // Animate the flying clone
                            requestAnimationFrame(() => {
                                clone.style.top = endTop + 'px';
                                clone.style.left = endLeft + 'px';
                                clone.style.width = endWidth + 'px';
                                clone.style.height = endHeightAvatar + 'px';
                            });

                            setTimeout(() => {
                                state2.style.display = 'none';
                                state2.style.position = '';
                                state2.style.top = '';
                                state2.style.left = '';
                                state2.style.width = '';
                                state2.style.opacity = '';
                                state2.style.transform = '';

                                container.style.height = 'auto';
                                container.style.overflow = 'visible';
                                container.style.transition = '';
                                container.style.boxSizing = '';

                                // Cleanup the flying clone and restore original avatars
                                clone.remove();
                                activeAvatar.style.opacity = '1';
                                selectedBuddyPreview.style.opacity = '';

                                startTypingEffect();
                            }, 650);
                        }
                    } catch (err) {
                        console.error('Error saving buddy:', err);
                        btnSave.textContent = 'Selesai';
                    }
                });
            }

            if (state3 && state3.style.display !== 'none') {
                startTypingEffect();
            }

            function computeBuddyMood(ctx) {
                const hour = ctx.hour_of_day;
                // 1. Sleepy trigger: between 22:00 (10 PM) and 05:00 (5 AM)
                if (hour >= 22 || hour < 5) {
                    return 'sleepy';
                }
                // 2. Excited trigger: streak >= 5 and all daily missions done
                if (ctx.streak >= 5 && ctx.missions_total > 0 && ctx.missions_done === ctx.missions_total) {
                    return 'excited';
                }
                // 3. Happy trigger: streak >= 1 or missions > 50% completed
                if (ctx.streak >= 1 || (ctx.missions_total > 0 && (ctx.missions_done / ctx.missions_total) >= 0.5)) {
                    return 'happy';
                }
                // 4. Sad trigger: streak === 0 and no missions done (not happy)
                if (ctx.streak === 0 && ctx.missions_done === 0) {
                    return 'sad';
                }
                // 5. Default Neutral
                return 'neutral';
            }

            function applyBuddyMood(mood, ctx) {
                const activeStateEl = document.getElementById('buddy-state-3');
                if (!activeStateEl) return;

                // Remove previous mood classes
                activeStateEl.classList.remove('mood-excited', 'mood-happy', 'mood-neutral', 'mood-sleepy', 'mood-sad');
                // Add new mood class
                activeStateEl.classList.add('mood-' + mood);

                // Update status text
                const statusTextEl = document.getElementById('buddy-status-text');
                if (statusTextEl) {
                    const moodLabels = {
                        excited: '🔥 Sangat Bersemangat!',
                        happy: '😊 Online',
                        neutral: '😐 Siap menemanimu',
                        sleepy: '😴 Mengantuk...',
                        sad: '😢 Kangen belajar...'
                    };
                    statusTextEl.textContent = moodLabels[mood] || 'Online';
                }
            }

            async function startTypingEffect() {
                const textContainer = document.getElementById('buddy-typing-text');
                if (!textContainer) return;

                textContainer.innerHTML = '<i><span style="opacity:0.6">Sedang memikirkan...</span></i>';

                let messages = ["Halo! Salam kenal... hehehe"];

                try {
                    const response = await fetch('/api/user/buddy-context', {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (response.ok) {
                        const context = await response.json();

                        // Resolve personality first so greeting list can adapt
                        currentPersonality = getPersonalityName(context.buddy_avatar);

                        messages = generateBuddyMessages(context);

                        // Compute and Apply Mood
                        const mood = computeBuddyMood(context);
                        applyBuddyMood(mood, context);

                        // Update micro-stats visually
                        const elStreak = document.getElementById('buddy-stat-streak');
                        const elMissions = document.getElementById('buddy-stat-missions');
                        const elLevel = document.getElementById('buddy-stat-level');
                        const elTier = document.getElementById('buddy-stat-tier');

                        if (elStreak) elStreak.textContent = context.streak + ' hr';
                        if (elMissions) elMissions.textContent = context.missions_done + '/' + context.missions_total;
                        if (elLevel) elLevel.textContent = 'Lv ' + context.level;
                        if (elTier) {
                            if (elTier.dataset.currentTier && elTier.dataset.currentTier !== context.tier) {
                                syncTierProgressionUI(context.tier);
                            }
                            elTier.dataset.currentTier = context.tier;
                            elTier.textContent = context.tier;
                        }

                        // Populate learning recommendation
                        const recCard = document.getElementById('buddy-recommendation-card');
                        const recCourse = document.getElementById('buddy-rec-course-title');
                        const recLesson = document.getElementById('buddy-rec-lesson-title');
                        const recProgress = document.getElementById('buddy-rec-progress-bar');
                        const recProgressText = document.getElementById('buddy-rec-progress-text');
                        const recActionBtn = document.getElementById('buddy-rec-action-btn');

                        if (context.recommendation && context.recommendation.has_recommendation) {
                            if (recCourse) recCourse.textContent = context.recommendation.course_title;
                            if (recLesson) recLesson.textContent = 'Materi: ' + context.recommendation.lesson_title;
                            if (recProgress) recProgress.style.width = context.recommendation.progress_percent + '%';
                            if (recProgressText) recProgressText.textContent = 'Progress: ' + context.recommendation.progress_percent + '%';
                            if (recActionBtn) {
                                recActionBtn.href = '/lessons/' + context.recommendation.lesson_id;
                                recActionBtn.style.display = 'inline-flex';
                            }
                            if (recCard) recCard.style.display = 'block';
                        } else if (context.recommendation && context.recommendation.course_title) {
                            // Enrolled but completed course!
                            if (recCourse) recCourse.textContent = context.recommendation.course_title;
                            if (recLesson) recLesson.textContent = '🎉 Semua materi kelas selesai!';
                            if (recProgress) recProgress.style.width = '100%';
                            if (recProgressText) recProgressText.textContent = 'Progress: 100%';
                            if (recActionBtn) recActionBtn.style.display = 'none';
                            if (recCard) recCard.style.display = 'block';
                        } else {
                            if (recCard) recCard.style.display = 'none';
                        }
                    }
                } catch (e) {
                    console.error("Failed to fetch buddy context", e);
                }

                let msgIndex = 0;
                let charIndex = 0;
                let isDeleting = false;
                let typeSpeed = 50;

                function type() {
                    let currentObj = messages[msgIndex];
                    if (typeof currentObj === 'string') {
                        currentObj = { text: currentObj };
                    }
                    const currentMsg = currentObj.text;

                    // Hide pills container smoothly at start of typing or deleting
                    const pillsWrapper = document.getElementById('buddy-pills-wrapper');
                    if (pillsWrapper && (charIndex === 0 || isDeleting)) {
                        pillsWrapper.classList.remove('visible');
                    }

                    if (isDeleting) {
                        textContainer.innerHTML = currentMsg.substring(0, charIndex - 1) + '<span class="typed-cursor">|</span>';
                        charIndex--;
                        typeSpeed = 20; // faster deletion
                    } else {
                        textContainer.innerHTML = currentMsg.substring(0, charIndex + 1) + '<span class="typed-cursor">|</span>';
                        charIndex++;
                        typeSpeed = 40 + Math.random() * 30; // human like typing
                    }

                    if (!isDeleting && charIndex === currentMsg.length) {
                        if (currentObj.type === "mission_prompt") {
                            // Show buttons with smooth CSS grid animation
                            const wrapper = document.getElementById('buddy-prompt-wrapper');
                            requestAnimationFrame(() => {
                                wrapper.classList.add('visible');
                            });

                            function hideButtons(callback) {
                                wrapper.classList.remove('visible');
                                wrapper.addEventListener('transitionend', function handler(e) {
                                    if (e.propertyName === 'grid-template-rows') {
                                        wrapper.removeEventListener('transitionend', handler);
                                        callback();
                                    }
                                });
                                // Fallback if transitionend doesn't fire
                                setTimeout(callback, 500);
                            }

                            // Auto-select "Tidak" after 20 seconds of no interaction
                            if (window.buddyAutoNoTimeout) {
                                clearTimeout(window.buddyAutoNoTimeout);
                            }
                            window.buddyAutoNoTimeout = setTimeout(() => {
                                const noBtn = document.getElementById('btn-prompt-no');
                                if (noBtn) {
                                    noBtn.click();
                                }
                            }, 20000);

                            document.getElementById('btn-prompt-yes').onclick = function () {
                                if (window.buddyAutoNoTimeout) {
                                    clearTimeout(window.buddyAutoNoTimeout);
                                    window.buddyAutoNoTimeout = null;
                                }
                                this.onclick = null; // prevent double-click
                                hideButtons(() => {
                                    setTimeout(() => {
                                        const statsCard = document.getElementById('buddy-stats-card');
                                        statsCard.style.display = 'flex';
                                        void statsCard.offsetWidth;
                                        statsCard.style.opacity = '1';
                                        statsCard.style.transform = 'translateY(0)';

                                        messages.splice(msgIndex + 1, 0, { text: 'Ini dia! Semangat ya ngerjainnya! 🎯' });
                                        isDeleting = true;
                                        typeSpeed = 20;
                                        type();
                                    }, 600);
                                });
                            };

                            document.getElementById('btn-prompt-no').onclick = function () {
                                if (window.buddyAutoNoTimeout) {
                                    clearTimeout(window.buddyAutoNoTimeout);
                                    window.buddyAutoNoTimeout = null;
                                }
                                this.onclick = null; // prevent double-click
                                hideButtons(() => {
                                    setTimeout(() => {
                                        messages.splice(msgIndex + 1, 0, { text: 'Oke deh, kabari aja kalau udah siap! 😊' });
                                        isDeleting = true;
                                        typeSpeed = 20;
                                        type();
                                    }, 600);
                                });
                            };

                            return; // Wait for user interaction
                        }

                        // Show "Tanya Buddy" pills row smoothly
                        const pillsWrapper = document.getElementById('buddy-pills-wrapper');
                        if (pillsWrapper && localStorage.getItem('buddy_pills_disabled') !== 'true') {
                            pillsWrapper.classList.add('visible');
                        }

                        typeSpeed = 20000; // wait 20 seconds before deleting
                        isDeleting = true;
                    } else if (isDeleting && charIndex === 0) {
                        isDeleting = false;
                        msgIndex = (msgIndex + 1) % messages.length;
                        typeSpeed = 500; // wait half second before typing next
                    }

                    if (window.buddyTypingTimeout) {
                        clearTimeout(window.buddyTypingTimeout);
                    }
                    window.buddyTypingTimeout = setTimeout(type, typeSpeed);
                }

                // Clear any existing typing timeout to prevent overlaps
                if (window.buddyTypingTimeout) {
                    clearTimeout(window.buddyTypingTimeout);
                }
                if (window.buddyAutoNoTimeout) {
                    clearTimeout(window.buddyAutoNoTimeout);
                    window.buddyAutoNoTimeout = null;
                }

                // Clear the thinking text and start typing
                textContainer.innerHTML = '';
                type();
            }

            // Chatbot Personality, History Log & Input logic
            let currentPersonality = 'chill';
            let toastTimeout = null;
            let idleTimer = null;

            function showBuddyToast(message, type = '') {
                const toast = document.getElementById('buddy-toast');
                const toastText = document.getElementById('buddy-toast-text');
                if (!toast || !toastText) return;

                toast.className = 'buddy-toast';
                if (type) {
                    toast.classList.add('event-' + type);
                }

                toastText.textContent = message;
                toast.classList.add('visible');

                if (toastTimeout) {
                    clearTimeout(toastTimeout);
                }
                toastTimeout = setTimeout(() => {
                    toast.classList.remove('visible');
                }, 4000);
            }

            function resetIdleTimer() {
                if (idleTimer) {
                    clearTimeout(idleTimer);
                }
                idleTimer = setTimeout(triggerIdleReaction, 300000); // 5 minutes
            }

            function triggerIdleReaction() {
                const reminders = {
                    chill: "Masih di sini? Santai sejenak aja, terus lanjut lagi... 🌿☕",
                    energetic: "WOII!! LARI DARI KENYATAAN YA?? AYO LANJUT NGODING!!! 🔥💪🚀",
                    wise: "Ingatlah bahwa penundaan adalah pencuri waktu. Mari lanjutkan perjuanganmu. 📚🧠",
                    hype: "Bestie... masih hidup kan? Yuk lanjut belajar biar makin slay! 😎👑"
                };
                const msg = reminders[currentPersonality] || "Masih di sini? Yuk lanjut belajar~ 😊";
                showBuddyToast(msg, 'warning');
            }

            // Register global event listeners for idle tracking
            document.addEventListener('mousemove', resetIdleTimer);
            document.addEventListener('keypress', resetIdleTimer);
            document.addEventListener('scroll', resetIdleTimer);
            document.addEventListener('click', resetIdleTimer);
            resetIdleTimer();

            function getPersonalityName(avatarId) {
                const mapping = {
                    '1': 'chill',
                    '2': 'energetic',
                    '3': 'wise',
                    '4': 'hype'
                };
                return mapping[avatarId] || 'chill';
            }

            function addChatBubble(sender, text) {
                const logContainer = document.getElementById('buddy-chat-log');
                if (!logContainer) return;

                // Make logContainer visible
                logContainer.style.display = 'flex';

                const bubble = document.createElement('div');
                bubble.className = `buddy-msg-bubble ${sender}`;

                // Parse bold notation **text**
                bubble.innerHTML = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');

                logContainer.appendChild(bubble);
                logContainer.scrollTop = logContainer.scrollHeight;
            }

            function loadBuddyMemory() {
                const defaultMemory = {
                    total_interactions: 0,
                    last_topic: '',
                    favorite_topic: '',
                    topic_counts: { tips: 0, motivasi: 0, humor: 0, status: 0 },
                    days_active: 1,
                    last_visit_date: new Date().toDateString(),
                    chat_history: []
                };

                try {
                    const stored = localStorage.getItem('buddy_memory');
                    if (stored) {
                        return JSON.parse(stored);
                    }
                } catch (e) {
                    console.error("Failed to parse buddy memory", e);
                }

                return defaultMemory;
            }

            function saveBuddyMemory(memory) {
                try {
                    localStorage.setItem('buddy_memory', JSON.stringify(memory));
                } catch (e) {
                    console.error("Failed to save buddy memory", e);
                }
            }

            function trackBuddyInteraction(topic, userMsg, replyMsg) {
                const memory = loadBuddyMemory();

                memory.total_interactions += 1;
                memory.last_topic = topic;

                if (memory.topic_counts[topic] !== undefined) {
                    memory.topic_counts[topic] += 1;
                } else {
                    memory.topic_counts[topic] = 1;
                }

                // Recalculate favorite topic
                let fav = memory.favorite_topic || topic;
                let maxCount = memory.topic_counts[fav] || 0;
                for (const t in memory.topic_counts) {
                    if (memory.topic_counts[t] > maxCount) {
                        fav = t;
                        maxCount = memory.topic_counts[t];
                    }
                }
                memory.favorite_topic = fav;

                // Track active days
                const today = new Date().toDateString();
                if (memory.last_visit_date !== today) {
                    memory.days_active += 1;
                    memory.last_visit_date = today;
                }

                // Add to history (keep max 10 messages = 20 entries)
                memory.chat_history.push({
                    role: 'user',
                    text: userMsg,
                    ts: Date.now()
                });
                memory.chat_history.push({
                    role: 'buddy',
                    text: replyMsg,
                    ts: Date.now()
                });
                if (memory.chat_history.length > 20) {
                    memory.chat_history.shift();
                    memory.chat_history.shift();
                }

                saveBuddyMemory(memory);
            }

            // "Tanya Buddy" Interactive Chatbot Logic
            window.askBuddy = function (topic, btn) {
                if (window.buddyPillClicked) return;
                window.buddyPillClicked = true;

                // Hide pills container smoothly
                const pillsWrapper = document.getElementById('buddy-pills-wrapper');
                if (pillsWrapper) {
                    pillsWrapper.classList.remove('visible');
                }

                if (window.buddyTypingTimeout) {
                    clearTimeout(window.buddyTypingTimeout);
                }
                if (window.buddyAutoNoTimeout) {
                    clearTimeout(window.buddyAutoNoTimeout);
                    window.buddyAutoNoTimeout = null;
                }

                // Add User Chat Bubble
                let topicLabel = "💡 Tips Belajar";
                if (topic === 'status') topicLabel = "🔥 Status";
                if (topic === 'motivasi') topicLabel = "🚀 Motivasi";
                if (topic === 'humor') topicLabel = "☕ Lelucon";
                addChatBubble('user', topicLabel);

                // Get dynamic context data for responses
                const streak = document.getElementById('buddy-stat-streak')?.textContent || '0 hr';
                const missions = document.getElementById('buddy-stat-missions')?.textContent || '0/0';
                const level = document.getElementById('buddy-stat-level')?.textContent || 'Lv 1';
                const tier = document.getElementById('buddy-stat-tier')?.textContent || 'Initiate';

                let replyText = "";
                if (topic === 'status') {
                    replyText = `Status belajarmu saat ini: kamu ada di **${tier} (${level})** dengan streak **${streak}**. ${missions !== '0/0' ? `Hari ini kamu udah beresin ${missions} misi!` : 'Yuk selesaikan misimu hari ini!'} Semangat terus belajarnya ya! 🚀`;
                } else {
                    replyText = getRandomBuddySentence(topic, currentPersonality);
                }

                const textContainer = document.getElementById('buddy-typing-text');
                if (!textContainer) return;

                let currentText = textContainer.textContent.replace('|', '');
                let deleteIndex = currentText.length;

                function deleteAnimation() {
                    if (deleteIndex > 0) {
                        textContainer.innerHTML = currentText.substring(0, deleteIndex - 1) + '<span class="typed-cursor">|</span>';
                        deleteIndex--;
                        window.buddyTypingTimeout = setTimeout(deleteAnimation, 15);
                    } else {
                        let replyCharIndex = 0;
                        function typeReply() {
                            if (replyCharIndex <= replyText.length) {
                                textContainer.innerHTML = replyText.substring(0, replyCharIndex) + '<span class="typed-cursor">|</span>';
                                replyCharIndex++;
                                window.buddyTypingTimeout = setTimeout(typeReply, 30 + Math.random() * 20);
                            } else {
                                window.buddyPillClicked = false;

                                // Append Buddy Response Bubble to Log
                                addChatBubble('buddy', replyText);

                                // Track interaction to local storage memory
                                trackBuddyInteraction(topic, topicLabel, replyText);

                                const pillsWrapper = document.getElementById('buddy-pills-wrapper');
                                if (pillsWrapper && localStorage.getItem('buddy_pills_disabled') !== 'true') {
                                    pillsWrapper.classList.add('visible');
                                }

                                window.buddyTypingTimeout = setTimeout(() => {
                                    let cleanIndex = replyText.length;
                                    function cleanReply() {
                                        if (cleanIndex > 0) {
                                            textContainer.innerHTML = replyText.substring(0, cleanIndex - 1) + '<span class="typed-cursor">|</span>';
                                            cleanIndex--;
                                            window.buddyTypingTimeout = setTimeout(cleanReply, 15);
                                        } else {
                                            startTypingEffect();
                                        }
                                    }
                                    cleanReply();
                                }, 20000);
                            }
                        }
                        typeReply();
                    }
                }
                deleteAnimation();
            };

            // Custom Free-Text Response Processing
            window.processBuddyChat = function (userMessage) {
                if (!userMessage.trim()) return;
                if (window.buddyPillClicked) return;
                window.buddyPillClicked = true;

                // Add user bubble
                addChatBubble('user', userMessage);

                // Clear input field
                const chatInput = document.getElementById('buddy-chat-input');
                if (chatInput) chatInput.value = '';

                // Hide pills container smoothly
                const pillsWrapper = document.getElementById('buddy-pills-wrapper');
                if (pillsWrapper) {
                    pillsWrapper.classList.remove('visible');
                }

                if (window.buddyTypingTimeout) {
                    clearTimeout(window.buddyTypingTimeout);
                }
                if (window.buddyAutoNoTimeout) {
                    clearTimeout(window.buddyAutoNoTimeout);
                    window.buddyAutoNoTimeout = null;
                }

                // Match Intent Topic
                const topic = matchBuddyIntent(userMessage);

                // Get dynamic context data for responses
                const streak = document.getElementById('buddy-stat-streak')?.textContent || '0 hr';
                const missions = document.getElementById('buddy-stat-missions')?.textContent || '0/0';
                const level = document.getElementById('buddy-stat-level')?.textContent || 'Lv 1';
                const tier = document.getElementById('buddy-stat-tier')?.textContent || 'Initiate';

                let replyText = "";
                if (topic === 'status') {
                    replyText = `Status belajarmu saat ini: kamu ada di **${tier} (${level})** dengan streak **${streak}**. ${missions !== '0/0' ? `Hari ini kamu udah beresin ${missions} misi!` : 'Yuk selesaikan misimu hari ini!'} Semangat terus belajarnya ya! 🚀`;
                } else {
                    replyText = getRandomBuddySentence(topic, currentPersonality);
                }

                const textContainer = document.getElementById('buddy-typing-text');
                if (!textContainer) return;

                let currentText = textContainer.textContent.replace('|', '');
                let deleteIndex = currentText.length;

                function deleteAnimation() {
                    if (deleteIndex > 0) {
                        textContainer.innerHTML = currentText.substring(0, deleteIndex - 1) + '<span class="typed-cursor">|</span>';
                        deleteIndex--;
                        window.buddyTypingTimeout = setTimeout(deleteAnimation, 15);
                    } else {
                        let replyCharIndex = 0;
                        function typeReply() {
                            if (replyCharIndex <= replyText.length) {
                                textContainer.innerHTML = replyText.substring(0, replyCharIndex) + '<span class="typed-cursor">|</span>';
                                replyCharIndex++;
                                window.buddyTypingTimeout = setTimeout(typeReply, 30 + Math.random() * 20);
                            } else {
                                window.buddyPillClicked = false;

                                // Append Buddy Response Bubble to Log
                                addChatBubble('buddy', replyText);

                                // Track interaction to local storage memory
                                trackBuddyInteraction(topic, userMessage, replyText);

                                const pillsWrapper = document.getElementById('buddy-pills-wrapper');
                                if (pillsWrapper && localStorage.getItem('buddy_pills_disabled') !== 'true') {
                                    pillsWrapper.classList.add('visible');
                                }

                                window.buddyTypingTimeout = setTimeout(() => {
                                    let cleanIndex = replyText.length;
                                    function cleanReply() {
                                        if (cleanIndex > 0) {
                                            textContainer.innerHTML = replyText.substring(0, cleanIndex - 1) + '<span class="typed-cursor">|</span>';
                                            cleanIndex--;
                                            window.buddyTypingTimeout = setTimeout(cleanReply, 15);
                                        } else {
                                            startTypingEffect();
                                        }
                                    }
                                    cleanReply();
                                }, 20000);
                            }
                        }
                        typeReply();
                    }
                }
                deleteAnimation();
            };

            // Wire input events
            const chatInput = document.getElementById('buddy-chat-input');
            const chatSendBtn = document.getElementById('btn-buddy-chat-send');

            if (chatInput && chatSendBtn) {
                chatSendBtn.addEventListener('click', () => {
                    processBuddyChat(chatInput.value);
                });

                chatInput.addEventListener('keypress', (e) => {
                    if (e.key === 'Enter') {
                        processBuddyChat(chatInput.value);
                    }
                });
            }

            // Restore chat history from memory on initial load
            try {
                const storedMemory = loadBuddyMemory();
                if (storedMemory.chat_history && storedMemory.chat_history.length > 0) {
                    storedMemory.chat_history.forEach(msg => {
                        addChatBubble(msg.role, msg.text);
                    });
                }
            } catch (e) {
                console.error("Restoring buddy chat history failed", e);
            }

            // ─── Mode Switch Logic (Auto ↔ Chat) ───
            function switchBuddyMode(mode) {
                const chatContainer = document.querySelector('#buddy-state-3 .buddy-chat');
                const switchEl = document.getElementById('buddy-mode-switch');
                const btnAuto = document.getElementById('btn-mode-auto');
                const btnChat = document.getElementById('btn-mode-chat');
                const chatInput = document.getElementById('buddy-chat-input');

                if (!chatContainer || !switchEl) return;

                if (mode === 'chat') {
                    chatContainer.classList.add('mode-chat');
                    switchEl.setAttribute('data-active', 'chat');
                    if (btnAuto) btnAuto.classList.remove('active');
                    if (btnChat) btnChat.classList.add('active');
                    localStorage.setItem('buddy_chat_mode', 'chat');

                    // Auto-focus the chat input
                    if (chatInput) {
                        setTimeout(() => chatInput.focus(), 400);
                    }
                } else {
                    chatContainer.classList.remove('mode-chat');
                    switchEl.setAttribute('data-active', 'auto');
                    if (btnAuto) btnAuto.classList.add('active');
                    if (btnChat) btnChat.classList.remove('active');
                    localStorage.setItem('buddy_chat_mode', 'auto');

                    // Clear input and blur focus
                    if (chatInput) {
                        chatInput.value = '';
                        chatInput.blur();
                    }
                }
            }

            // Wire mode switch buttons
            const btnModeAuto = document.getElementById('btn-mode-auto');
            const btnModeChat = document.getElementById('btn-mode-chat');
            if (btnModeAuto) btnModeAuto.addEventListener('click', () => switchBuddyMode('auto'));
            if (btnModeChat) btnModeChat.addEventListener('click', () => switchBuddyMode('chat'));

            // Restore saved mode preference (default: auto)
            const savedMode = localStorage.getItem('buddy_chat_mode') || 'auto';
            switchBuddyMode(savedMode);

            // Keep dismissBuddyPills for the ✕ button in pills row (only relevant in auto mode)
            window.dismissBuddyPills = function () {
                const pillsWrapper = document.getElementById('buddy-pills-wrapper');
                if (pillsWrapper) {
                    pillsWrapper.classList.remove('visible');
                }
                localStorage.setItem('buddy_pills_disabled', 'true');
            };

            // Restore toggleBuddyPills to toggle visibility of pill tags
            window.toggleBuddyPills = function () {
                const pillsWrapper = document.getElementById('buddy-pills-wrapper');
                if (!pillsWrapper) return;

                const isCurrentlyVisible = pillsWrapper.classList.contains('visible');
                if (isCurrentlyVisible) {
                    pillsWrapper.classList.remove('visible');
                    localStorage.setItem('buddy_pills_disabled', 'true');
                } else {
                    pillsWrapper.classList.add('visible');
                    localStorage.setItem('buddy_pills_disabled', 'false');
                }
            };

            function generateBuddyMessages(ctx) {
                const msgs = [];

                // 1. Time & Day based dynamic greeting (Personality & Context Aware)
                let greeting = window.getDynamicBuddyGreeting(currentPersonality, ctx);
                msgs.push(greeting);

                // 2. Mission context
                if (ctx.missions_total > 0) {
                    if (ctx.missions_done === ctx.missions_total) {
                        msgs.push("Wah, semua misi hari ini udah beres! Keren banget! 🎉");
                    } else if (ctx.missions_done === 0) {
                        msgs.push({ text: "Misi harianmu belum ada yang dikerjain nih. Mau kulihatkan?", type: "mission_prompt" });
                    } else {
                        msgs.push({ text: `Misi harianmu baru selesai ${ctx.missions_done} dari ${ctx.missions_total}. Mau kulihatkan?`, type: "mission_prompt" });
                    }
                }

                // 3. Streak context
                if (ctx.streak > 3) {
                    msgs.push(`Gokil! Udah ${ctx.streak} hari berturut-turut kamu belajar. Pertahankan apinya! 🔥`);
                } else if (ctx.streak === 0) {
                    msgs.push("Udah lama nggak kelihatan... Jangan kendor belajarnya ya!");
                }

                // 4. Avatar personality flavor
                if (ctx.buddy_avatar === '1') {
                    msgs.push("Btw, aku suka banget ngoding sambil dengerin musik lofi~");
                } else if (ctx.buddy_avatar === '2') {
                    msgs.push("Jangan lupa minum air putih yang banyak ya biar tetap fokus!");
                } else if (ctx.buddy_avatar === '3') {
                    msgs.push("Kalau materinya susah, santai aja. Pelan-pelan pasti paham kok.");
                } else if (ctx.buddy_avatar === '4') {
                    msgs.push("Aku siap nemenin kamu sampai jadi pro!");
                }

                // 5. Level context
                if (ctx.exp_percentage > 80) {
                    msgs.push(`Dikit lagi naik level nih! Tinggal push dikit lagi. 📈`);
                } else {
                    msgs.push(`Sekarang kamu ada di tier ${ctx.tier}, terus kumpulkan EXP ya!`);
                }

                return msgs;
            }

            const btnCloseStats = document.getElementById('btn-close-stats');
            if (btnCloseStats) {
                btnCloseStats.addEventListener('click', () => {
                    const statsCard = document.getElementById('buddy-stats-card');
                    statsCard.style.opacity = '0';
                    statsCard.style.transform = 'translateY(-10px)';
                    setTimeout(() => {
                        statsCard.style.display = 'none';
                    }, 400); // match CSS transition duration
                });
            }

            // ============================================
            // PHASE 9: Floating Buddy Widget
            // Shows when main buddy card scrolls out of view
            // Uses scroll + getBoundingClientRect for max reliability
            // ============================================
            (function initFloatingBuddy() {
                const floatWidget = document.getElementById('buddy-floating-widget');
                const floatAvatar = document.getElementById('buddy-float-avatar');
                const floatName = document.getElementById('buddy-float-name');
                const buddySection = document.querySelector('.buddy-section');

                if (!floatWidget || !buddySection) return;

                // Sync avatar & name from the main buddy card
                function syncFloatWidget() {
                    const mainAvatar = document.getElementById('active-buddy-avatar');
                    const mainName = document.getElementById('active-buddy-name');

                    if (mainAvatar && mainAvatar.src && !mainAvatar.src.endsWith('/')) {
                        floatAvatar.src = mainAvatar.src;
                    }
                    if (mainName) {
                        const nameSpan = mainName.querySelector('span:first-child');
                        const name = nameSpan ? nameSpan.textContent.trim() : '';
                        if (name) floatName.textContent = name;
                    }
                }

                // Check if buddy is "active" (buddy has been set up)
                function isBuddyActive() {
                    const state3 = document.getElementById('buddy-state-3');
                    if (!state3) return false;
                    // Use getComputedStyle for maximum reliability
                    return window.getComputedStyle(state3).display !== 'none';
                }

                // Update floating widget visibility based on scroll position
                let ticking = false;
                function updateFloatVisibility() {
                    if (!ticking) {
                        requestAnimationFrame(function () {
                            const rect = buddySection.getBoundingClientRect();
                            // Card is "out of view" when its bottom has scrolled above the top of viewport
                            // OR when its top has scrolled below the bottom of viewport
                            const isOutOfView = rect.bottom < 0 || rect.top > window.innerHeight;

                            if (isOutOfView && isBuddyActive()) {
                                syncFloatWidget();
                                floatWidget.classList.add('visible');
                            } else {
                                floatWidget.classList.remove('visible');
                            }
                            ticking = false;
                        });
                        ticking = true;
                    }
                }

                // Listen to scroll on both window and any parent
                window.addEventListener('scroll', updateFloatVisibility, { passive: true });
                document.addEventListener('scroll', updateFloatVisibility, { passive: true, capture: true });

                // --- HOVER DESCRIPTION LOGIC ---
                let hoverTimer = null;
                let hideTimer = null;
                let currentHoverDesc = null;
                let currentHoverTarget = null;
                const defaultHintText = 'Online';
                let isExpandedDesc = false;

                // Shared FLIP helper: animates width+height from current to target
                const FLIP_DURATION = 500; // ms
                const FLIP_EASING = 'cubic-bezier(0.4, 0.0, 0.2, 1)';
                let flipInProgress = false;

                function flipAnimate(onMutate, onTextReady) {
                    if (flipInProgress) return;
                    flipInProgress = true;

                    const hintEl = floatWidget.querySelector('.buddy-float-hint');
                    const nameEl = floatWidget.querySelector('.buddy-float-name');

                    // 1. FIRST — capture current rendered size & styles
                    const firstComputed = window.getComputedStyle(floatWidget);
                    const firstW = floatWidget.offsetWidth;
                    const firstH = floatWidget.offsetHeight;
                    const firstBR = firstComputed.borderRadius;
                    const firstPad = firstComputed.padding;

                    // 2. Fade out text
                    if (hintEl) {
                        hintEl.style.transition = 'opacity 0.15s ease';
                        hintEl.style.opacity = '0';
                    }
                    if (nameEl) {
                        nameEl.style.transition = 'opacity 0.15s ease';
                        nameEl.style.opacity = '0';
                    }

                    // 3. Lock at current size + remove CSS constraints
                    floatWidget.style.transition = 'none';
                    floatWidget.style.width = firstW + 'px';
                    floatWidget.style.height = firstH + 'px';
                    floatWidget.style.borderRadius = firstBR;
                    floatWidget.style.padding = firstPad;
                    floatWidget.style.maxWidth = 'none';
                    floatWidget.style.maxHeight = 'none';
                    floatWidget.style.overflow = 'hidden';

                    // Small delay to let fade-out render
                    setTimeout(() => {
                        // 4. MUTATE — apply class/text changes while size is locked
                        onMutate(hintEl);

                        // 5. LAST — measure natural target size (temporarily unlock)
                        floatWidget.style.width = 'auto';
                        floatWidget.style.height = 'auto';
                        floatWidget.style.borderRadius = '';
                        floatWidget.style.padding = '';

                        const targetComputed = window.getComputedStyle(floatWidget);
                        const lastW = floatWidget.offsetWidth;
                        const lastH = floatWidget.offsetHeight;
                        const lastBR = targetComputed.borderRadius;
                        const lastPad = targetComputed.padding;

                        // 6. INVERT — snap back to first size
                        floatWidget.style.width = firstW + 'px';
                        floatWidget.style.height = firstH + 'px';
                        floatWidget.style.borderRadius = firstBR;
                        floatWidget.style.padding = firstPad;
                        floatWidget.offsetHeight; // force reflow

                        // 7. PLAY — animate to target with gentle easing
                        const dur = FLIP_DURATION;
                        const ease = 'cubic-bezier(0.25, 0.1, 0.25, 1)';
                        floatWidget.style.transition = [
                            `width ${dur}ms ${ease}`,
                            `height ${dur}ms ${ease}`,
                            `border-radius ${dur}ms ${ease}`,
                            `padding ${dur}ms ${ease}`,
                            `background ${dur}ms ease`
                        ].join(', ');

                        floatWidget.style.width = lastW + 'px';
                        floatWidget.style.height = lastH + 'px';
                        floatWidget.style.borderRadius = lastBR;
                        floatWidget.style.padding = lastPad;

                        // 8. Fade text back in after size has begun settling
                        setTimeout(() => {
                            if (hintEl) {
                                hintEl.style.transition = 'opacity 0.35s ease';
                                hintEl.style.opacity = '1';
                            }
                            if (nameEl) {
                                nameEl.style.transition = 'opacity 0.35s ease';
                                nameEl.style.opacity = '1';
                            }
                            if (onTextReady) onTextReady();
                        }, dur * 0.4);

                        // 9. Cleanup — remove ALL inline overrides so CSS takes over
                        setTimeout(() => {
                            floatWidget.style.width = '';
                            floatWidget.style.height = '';
                            floatWidget.style.borderRadius = '';
                            floatWidget.style.padding = '';
                            floatWidget.style.maxWidth = '';
                            floatWidget.style.maxHeight = '';
                            floatWidget.style.overflow = '';
                            floatWidget.style.transition = '';
                            if (hintEl) hintEl.style.transition = '';
                            if (nameEl) nameEl.style.transition = '';
                            flipInProgress = false;
                        }, dur + 80);
                    }, 140);
                }

                function resetWidgetToDefault() {
                    if (isExpandedDesc) return;

                    const wasExpanded = floatWidget.classList.contains('expanded-desc');
                    const hintEl = floatWidget.querySelector('.buddy-float-hint');

                    if (wasExpanded) {
                        // Use FLIP for smooth collapse
                        flipAnimate((hint) => {
                            floatWidget.classList.remove('expanded-desc');
                            if (currentHoverTarget && currentHoverDesc) {
                                floatWidget.classList.add('question-state');
                                if (hint) hint.textContent = 'Tentang ini?';
                            } else {
                                floatWidget.classList.remove('question-state');
                                if (hint) hint.innerHTML = defaultHintText;
                                currentHoverDesc = null;
                                currentHoverTarget = null;
                            }
                        });
                    } else if (hintEl) {
                        const nameEl = floatWidget.querySelector('.buddy-float-name');
                        // Simple text swap (e.g. Tentang ini? -> Online)
                        hintEl.style.transition = 'opacity 0.2s ease';
                        hintEl.style.opacity = '0';
                        if (nameEl) {
                            nameEl.style.transition = 'opacity 0.2s ease';
                            nameEl.style.opacity = '0';
                        }
                        setTimeout(() => {
                            floatWidget.classList.remove('expanded-desc');
                            if (currentHoverTarget && currentHoverDesc) {
                                floatWidget.classList.add('question-state');
                                hintEl.textContent = 'Tentang ini?';
                            } else {
                                floatWidget.classList.remove('question-state');
                                hintEl.innerHTML = defaultHintText;
                                currentHoverDesc = null;
                                currentHoverTarget = null;
                            }
                            hintEl.style.opacity = '1';
                            if (nameEl) nameEl.style.opacity = '1';
                            setTimeout(() => {
                                hintEl.style.transition = '';
                                if (nameEl) nameEl.style.transition = '';
                            }, 250);
                        }, 200);
                    } else {
                        floatWidget.classList.remove('expanded-desc', 'question-state');
                        currentHoverDesc = null;
                        currentHoverTarget = null;
                    }
                }

                const descMap = {
                    'material-card': 'Menampilkan materi berdasarkan interest dan fokus yang kamu pilih.',
                    'missions-container': 'Menampilkan misi harian yang bisa kamu selesaikan untuk mendapatkan EXP ekstra.',
                    'android-exam-card': 'Ujian akhir untuk mengevaluasi pemahamanmu secara menyeluruh dan mendapatkan Sertifikat Fokus.',
                    'schedule-column': 'Jadwal belajar kamu hari ini. Pastikan kamu tidak kelewatan ya!',
                    'season-timers': 'Waktu tersisa sebelum season ini berakhir, ayo kumpulkan EXP sebanyak mungkin!',
                    'leaderboard-list': 'Peringkat global berdasarkan total EXP. Ayo kejar peringkat pertama!',
                    'tier-progression-card': 'Tingkatan rank berdasarkan seberapa rajin kamu belajar dan mengumpulkan EXP.'
                };
                const selector = '[data-buddy-desc], ' + Object.keys(descMap).map(cls => '.' + cls).join(', ');

                document.addEventListener('mouseover', (e) => {
                    // Only run logic if widget is visible and not already expanded
                    if (!floatWidget.classList.contains('visible') || isExpandedDesc) return;

                    // If moving cursor to the widget itself, don't hide the "Tentang ini?" text
                    if (e.target.closest('#buddy-floating-widget')) {
                        clearTimeout(hideTimer);
                        return;
                    }

                    const target = e.target.closest(selector);

                    if (target) {
                        clearTimeout(hideTimer);
                        // If hovering a NEW target
                        if (target !== currentHoverTarget) {
                            currentHoverTarget = target;
                            clearTimeout(hoverTimer);

                            let desc = target.getAttribute('data-buddy-desc');
                            if (!desc) {
                                for (const [cls, txt] of Object.entries(descMap)) {
                                    if (target.classList.contains(cls)) {
                                        desc = txt;
                                        break;
                                    }
                                }
                            }

                            if (desc) {
                                // 100ms delay before showing "Tentang ini?" (super quick but prevents glitching when scrolling past)
                                hoverTimer = setTimeout(() => {
                                    if (!isExpandedDesc) {
                                        const hintEl = floatWidget.querySelector('.buddy-float-hint');
                                        if (hintEl) {
                                            hintEl.style.opacity = '0';
                                            setTimeout(() => {
                                                floatWidget.classList.add('question-state');
                                                hintEl.textContent = 'Tentang ini?';
                                                hintEl.style.opacity = '1';
                                            }, 150);
                                        }
                                        currentHoverDesc = desc;
                                    }
                                }, 100);
                            }
                        }
                    } else {
                        // Moving to empty space (not a container and not the widget)
                        if (currentHoverTarget) {
                            currentHoverTarget = null;
                            clearTimeout(hoverTimer);

                            // If it's already showing "Tentang ini?", give it 3s before hiding
                            // so the user has time to move their cursor to it
                            if (floatWidget.classList.contains('question-state')) {
                                hideTimer = setTimeout(() => {
                                    resetWidgetToDefault();
                                }, 3000);
                            } else {
                                currentHoverDesc = null;
                            }
                        }
                    }
                });

                // Click → expand description OR smooth scroll back to buddy section
                floatWidget.addEventListener('click', function (e) {
                    if (currentHoverDesc && !isExpandedDesc && !flipInProgress) {
                        // Expand to show description using FLIP
                        e.preventDefault();
                        e.stopPropagation();
                        isExpandedDesc = true;

                        flipAnimate((hint) => {
                            floatWidget.classList.remove('question-state');
                            floatWidget.classList.add('expanded-desc');
                            if (hint) hint.textContent = currentHoverDesc;
                        });

                        // Auto collapse after 10s
                        setTimeout(() => {
                            isExpandedDesc = false;
                            resetWidgetToDefault();
                        }, 10000);
                        return;
                    }

                    if (isExpandedDesc && !flipInProgress) {
                        // Collapse back
                        isExpandedDesc = false;
                        resetWidgetToDefault();
                        return;
                    }

                    if (!flipInProgress) {
                        buddySection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                });

                // Watch for buddy becoming active mid-session (state-3 display changes)
                const state3El = document.getElementById('buddy-state-3');
                if (state3El) {
                    new MutationObserver(updateFloatVisibility)
                        .observe(state3El, { attributes: true, attributeFilter: ['style'] });
                }

                // Initial check
                updateFloatVisibility();
            })();
        }

        initBuddySystem();

        // Pilih Tujuan Lainnya JS functions
        function switchInterestTab(button) {
            const tabs = button.parentElement.querySelectorAll('.interest-tab-btn');
            tabs.forEach(t => t.classList.remove('active'));
            button.classList.add('active');

            const panels = document.querySelectorAll('.focus-panel');
            panels.forEach(p => p.classList.remove('active'));

            const targetPanelId = button.getAttribute('data-target');
            const targetPanel = document.getElementById(targetPanelId);
            if (targetPanel) {
                targetPanel.classList.add('active');
            }

            const interestVal = targetPanelId.replace('panel-', '');
            const selectedInterestInput = document.getElementById('selectedInterestVal');
            if (selectedInterestInput) {
                selectedInterestInput.value = interestVal;
            }

            clearFocusSelection();
        }

        function selectFocusCard(card) {
            const cards = document.querySelectorAll('.focus-option-card');
            cards.forEach(c => c.classList.remove('selected'));
            card.classList.add('selected');

            const interestVal = card.getAttribute('data-interest');
            const focusVal = card.getAttribute('data-focus');

            const selectedInterestInput = document.getElementById('selectedInterestVal');
            const selectedFocusInput = document.getElementById('selectedFocusVal');
            const submitBtn = document.getElementById('btnChangeFocusSubmit');

            if (selectedInterestInput) selectedInterestInput.value = interestVal;
            if (selectedFocusInput) selectedFocusInput.value = focusVal;
            if (submitBtn) submitBtn.disabled = false;
        }

        function clearFocusSelection() {
            const cards = document.querySelectorAll('.focus-option-card');
            cards.forEach(c => c.classList.remove('selected'));

            const selectedFocusInput = document.getElementById('selectedFocusVal');
            const submitBtn = document.getElementById('btnChangeFocusSubmit');

            if (selectedFocusInput) selectedFocusInput.value = '';
            if (submitBtn) submitBtn.disabled = true;
        }

        // Alert auto-dismiss system
        function dismissAlert(el) {
            if (!el || el.classList.contains('dismissing')) return;
            el.classList.add('dismissing');
            setTimeout(() => el.remove(), 500);
        }

        function initAlerts() {
            document.querySelectorAll('.premium-alert-banner').forEach(banner => {
                // Auto-dismiss after 10 seconds
                const timerId = setTimeout(() => dismissAlert(banner), 10000);
                // Cancel timer if user manually closes
                banner.querySelector('.premium-alert-close')?.addEventListener('click', () => {
                    clearTimeout(timerId);
                });
                // Pause progress bar on hover
                banner.addEventListener('mouseenter', () => {
                    const bar = banner.querySelector('.premium-alert-progress-bar');
                    if (bar) bar.style.animationPlayState = 'paused';
                });
                banner.addEventListener('mouseleave', () => {
                    const bar = banner.querySelector('.premium-alert-progress-bar');
                    if (bar) bar.style.animationPlayState = 'running';
                });
            });
        }

        // Initialize alerts on page load
        initAlerts();

        if (!window.dashboardTurboBound) {
            window.dashboardTurboBound = true;
            document.addEventListener('turbo:load', function () {
                if (typeof initDashboardJs === 'function') initDashboardJs();
                initAlerts();
            });
        }
    </script>

    @include('partials.modal-jadwal')

    <!-- BOTTOM-LEFT PAGE NAV MENU -->
    <div class="page-nav" id="pageNav">
        <div class="page-nav-items" id="pageNavItems">
            <a href="{{ route('dashboard') }}" class="page-nav-item active">Dashboard</a>
            <a href="{{ route('jadwal') }}" class="page-nav-item">Jadwal</a>
            <a href="{{ route('history') }}" class="page-nav-item">History</a>
            <a href="#" class="page-nav-item"
                onclick="event.preventDefault(); document.getElementById('logoutFormNav').submit();"
                style="backdrop-filter: blur(15px); background-color: rgba(15, 23, 42, 0.9); color: #ef4444; border-color: rgba(239, 68, 68, 0.15); background: rgba(239, 68, 68, 0.03);">
                <i class='bx bx-log-out' style="margin-right: 6px; font-size: 1.15rem; vertical-align: middle;"></i>Log
                Out
            </a>
        </div>
        <form method="POST" action="{{ route('logout') }}" id="logoutFormNav" style="display: none;">
            @csrf
        </form>
        <button class="page-nav-btn" id="pageNavBtn" aria-label="Menu halaman">
            <span class="page-nav-line"></span>
            <span class="page-nav-line"></span>
            <span class="page-nav-line"></span>
        </button>
    </div>

    <script src="{{ asset('js/modal-jadwal.js') }}"></script>
    <script src="{{ asset('js/panel.js') }}"></script>
    <script src="{{ asset('js/layout.js') }}"></script>

</html>