<!DOCTYPE html>
<html lang="id">

<head>
    @include('layouts.transition-head')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TurnCode - Belajar Jadi Programmer</title>
    <meta name="description"
        content="Platform belajar coding terbaik untuk pemula. Mulai perjalanan coding-mu sekarang bersama TurnCode.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Silkscreen&display=swap"
        rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            user-select: none;
        }

        body::-webkit-scrollbar {
            display: none;
        }

        body::-webkit-scrollbar-track {
            display: none;
        }

        :root {
            --bg-color: #181519;
            --card-bg: #1a171c;
            --card-bg-hover: #232025;
            --text-main: #ffffff;
            --text-muted: #a3a3a3;
            --btn-dark: #17151a;
            --btn-light: #ffffff;
            --accent-purple: #8b5cf6;
        }

        html {
            scroll-behavior: smooth;
            background-color: var(--bg-color);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            overflow-x: hidden;
            line-height: 1.5;
        }

        .container {
            max-width: 90em;
            margin: 0 auto;
            padding: 0 2rem;
        }

        /* NAVBAR */
        nav {
            padding: 2.5rem 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .nav-logo {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-main);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-logo i {
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        .nav-logo-img {
            height: 28px;
            width: auto;
            display: block;
        }

        .nav-actions {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        .nav-link {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .nav-link:hover {
            color: var(--text-main);
        }

        /* HERO SECTION */
        .hero-section {
            min-height: 42em;
            padding: 4rem 0;
        }

        .hero-title-container {
            margin-bottom: 3rem;
        }

        .hero-pre-title {
            font-size: 2.5rem;
            font-weight: 300;
            color: var(--text-muted);
            line-height: 1.1;
        }

        .hero-main-title {
            font-size: 4.5rem;
            line-height: 1.1;
            letter-spacing: -1.5px;
        }

        .hero-main-title span {
            color: var(--text-main);
            font-weight: 900;
        }

        .hero-grid {
            display: grid;
            grid-template-columns: 1.4fr 1fr;
            gap: 2.5rem;
            align-items: center;
            margin-bottom: 3.5rem;
        }

        .hero-visual-card {
            background: var(--card-bg);
            border-radius: 40px;
            overflow: hidden;
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-visual-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.85;
            transition: transform 0.5s ease;
        }

        .hero-visual-card:hover img {
            transform: scale(1.03);
        }

        .hero-desc {
            font-size: 1rem;
            color: var(--text-muted);
            line-height: 1.7;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
        }

        .btn-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.9rem 2.2rem;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            cursor: pointer;
        }

        .btn-pill.dark {
            background: var(--btn-dark);
            color: var(--text-main);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .btn-pill.dark:hover {
            background: #201e24;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
        }

        .btn-pill.light {
            background: var(--btn-light);
            color: var(--bg-color);
        }

        .btn-pill.light:hover {
            background: #e5e5e5;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 255, 255, 0.1);
        }

        /* SECTION COMMON LAYOUT */
        .info-section {
            padding: 6rem 0;
        }

        .split-grid {
            display: grid;
            min-height: 42em;
            grid-template-columns: 1fr 1.3fr;
            gap: 4rem;
            align-items: center;
        }

        .about-split {
            min-height: 10em;
        }

        .split-left-label {
            font-size: 0.9rem;
            color: var(--text-muted);
            margin-bottom: 0.5rem;
            text-transform: capitalize;
            letter-spacing: 0.5px;
        }

        .split-left-title {
            font-size: 2.2rem;
            font-weight: 800;
            line-height: 1.25;
            letter-spacing: -0.5px;
        }

        .split-right-desc {
            font-size: 1.05rem;
            color: var(--text-muted);
            line-height: 1.8;
        }

        .about-split-left.reveal.revealed.sync-active,
        .about-split-right.reveal.revealed.sync-active {
            transform: translateX(var(--about-x, 0px));
            opacity: var(--about-opacity, 1);
            transition: transform 0.08s ease-out, opacity 0.08s ease-out;
        }

        /* TUJUAN KARIR CARDS */
        .career-section {
            padding: 8rem 0;
        }

        .career-grid {
            display: grid;
            grid-template-columns: 1.45fr 1fr;
            gap: 4rem;
            align-items: center;
        }

        .career-cards-stack {
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
        }

        .career-card-row {
            display: grid;
            grid-template-columns: 1fr 150px;
            align-items: center;
            gap: 1rem;
            width: 100%;
        }

        .career-card-row.reverse {
            grid-template-columns: 150px 1fr;
        }

        .career-card-row .career-visual {
            flex: 0 0 150px;
        }

        .career-card {
            background: #110f12;
            border-radius: 40px;
            padding: 2rem 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 0.5rem;
            border: 1px solid rgba(139, 92, 246, 0.08);
            flex: 1;
            min-height: 210px;
            transform: translateX(var(--exit-x, 0px));
            opacity: var(--exit-opacity, 1);
            transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.6s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: transform, opacity;
        }

        .career-card-text {
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
        }

        .career-card-header {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .career-card-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-main);
            letter-spacing: -0.8px;
            line-height: 1.2;
        }

        .career-card-badge {
            background: rgba(139, 92, 246, 0.12);
            color: #c084fc;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 6px 18px;
            border-radius: 100px;
            border: 1px solid rgba(139, 92, 246, 0.25);
            text-transform: capitalize;
            letter-spacing: 0.3px;
        }

        .career-card-badge.purple {
            background: rgba(168, 85, 247, 0.12);
            color: #d8b4fe;
            border-color: rgba(168, 85, 247, 0.25);
        }

        .career-card-desc {
            font-size: 1.05rem;
            color: #a3a3a3;
            line-height: 1.6;
            font-weight: 400;
        }

        .career-visual {
            width: 150px;
            height: 210px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .career-visual img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 100px;
        }

        .career-title-block {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding-left: 1.5rem;
        }

        .career-title {
            font-size: 2.75rem;
            font-weight: 800;
            color: var(--text-main);
            line-height: 1.2;
            letter-spacing: -1px;
        }

        .career-desc {
            font-size: 1.15rem;
            color: var(--text-muted);
            line-height: 1.7;
            margin-top: 1.2rem;
        }

        @media (max-width: 992px) {
            .career-grid {
                grid-template-columns: 1.2fr 1fr;
                gap: 2.5rem;
            }

            .career-title {
                font-size: 2.2rem;
            }

            .career-desc {
                font-size: 1.05rem;
            }

            .career-card {
                padding: 2.5rem 3rem;
            }

            .career-card-title {
                font-size: 1.85rem;
            }
        }

        @media (max-width: 768px) {
            .career-grid {
                grid-template-columns: 1fr;
                gap: 3rem;
            }

            .career-title-block {
                order: -1;
                padding-left: 0;
                text-align: center;
                margin-bottom: 1.5rem;
            }

            .career-cards-stack {
                gap: 2.5rem;
            }

            .career-card-row {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .career-card-row.reverse {
                grid-template-columns: 1fr;
            }

            .career-card-row.reverse .career-visual {
                order: -1;
            }

            .career-card {
                padding: 2.5rem;
                min-height: auto;
                border-radius: 36px;
                width: 100%;
            }

            .career-card-title {
                font-size: 1.75rem;
            }

            .career-visual {
                width: 130px;
                height: 220px;
                margin: 0 auto;
            }
        }

        /* LIVE CODE EDITOR */
        .editor-screen-wrapper {
            background: #050405;
            border-radius: 48px;
            padding: 3.5rem 0 3.5rem 4.5rem;
            width: 100%;
            min-height: 42em;
            display: flex;
            align-items: center;
            justify-content: center;
            will-change: transform;
        }

        .editor-screen-wrapper.reveal {
            opacity: 0;
            transform: translateX(40px) scale(0.85);
            transition: opacity 1.2s cubic-bezier(0.16, 1, 0.3, 1), transform 1.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .editor-screen-wrapper.reveal.revealed {
            opacity: 1;
            transform: translateX(0px) scale(var(--scroll-scale, 0.95));
        }

        .editor-screen-wrapper.reveal.revealed.sync-active {
            transition: transform 0.3s cubic-bezier(0.25, 1, 0.5, 1), opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .editor-container {
            background: #0d0b0e;
            border-radius: 20px 0 0 20px;
            overflow: hidden;
            width: 100%;
            border: 1px solid rgba(255, 255, 255, 0.03);
        }

        .editor-header {
            background: #131115;
            padding: 1.2rem 1.8rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
        }

        .editor-dots {
            display: flex;
            gap: 10px;
        }

        .editor-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #46404a;
            /* Muted grey/silver dots matching mockup */
        }

        .editor-title {
            font-family: monospace;
            font-size: 0.95rem;
            color: #7b7580;
            margin-right: 44px;
            /* Offset for dots balance */
        }

        .editor-body {
            padding: 2.5rem 3rem;
            font-family: 'JetBrains Mono', 'Consolas', monospace;
            font-size: 0.9rem;
            line-height: 1.8;
            background: #0d0b0e;
            color: #ffffff;
        }

        /* Code syntax highlights */
        .code-line {
            display: flex;
            align-items: center;
            color: #ffffff;
        }

        .code-line.indent-1 {
            padding-left: 2rem;
        }

        .code-line.indent-2 {
            padding-left: 4rem;
        }

        .code-bracket {
            color: #8b858f;
            /* Muted gray-purple for brackets */
        }

        .code-tag {
            color: #38bdf8;
            /* Vibrant cyan/blue for tag names */
        }

        .code-attr {
            color: #34d399;
            /* Vibrant green/emerald for attribute names */
        }

        .code-val {
            color: #ffffff;
            /* Crisp white for strings */
        }

        .code-text {
            color: #ffffff;
            /* Crisp white for standard text */
        }

        /* standard inner text */

        /* GAMIFIKASI WIDGETS */
        .gamify-grid {
            grid-template-columns: 1.45fr 1fr;
        }

        .gamify-visual-col {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            width: 100%;
            margin: 0 auto;
        }

        .gold-capsule-card {
            position: relative;
            background: #201e24;
            border-radius: 50px 150px 150px 50px;
            /* padding: 3rem 5rem; */
            display: flex;
            align-items: center;
            color: #ffffff;
            min-height: 320px;
            width: 100%;
        }

        .capsule-content {
            display: flex;
            z-index: 3;
            position: relative;
            flex-direction: column;
            align-items: flex-start;
            text-align: left;
            margin: 0 5rem;
        }

        .capsule-gradient {
            position: absolute;
            width: 100%;
            height: 320px;
            border-radius: 50px 150px 150px 50px;
            background: linear-gradient(90deg, #ffd9003d, #daa137ff 40%);
            z-index: 1;
        }

        .capsule-img-wrapper {
            width: 100%;
            height: 320px;
            z-index: 1;
            display: flex;
            position: absolute;
        }

        .capsule-img {
            width: 50%;
            height: 100%;
            border-radius: 50px 0 0 50px;
            object-fit: cover;
        }

        .capsule-lbl {
            font-size: 1.2rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 2px;
        }

        .capsule-title {
            font-size: 4.5rem;
            font-weight: 700;
            line-height: 1.1;
            color: #ffffff;
            letter-spacing: -0.5px;
        }

        .capsule-sub {
            font-size: 0.95rem;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.85);
            margin-top: 12px;
            text-align: right;
            width: 22em;
        }

        .dial-row {
            display: flex;
            gap: 1.5rem;
            align-items: center;
            width: 100%;
        }

        .dial-card {
            background: #110f12;
            display: flex;
            min-height: 320px;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            flex-shrink: 0;
        }

        /* Achievements: horizontal oval */
        .dial-card.oval {
            flex: 1;
            height: 185px;
            border-radius: 300px;
        }

        /* Tier: perfect circle */
        .dial-card.circle {
            width: 320px;
            height: 185px;
            border-radius: 50%;
            background: transparent;
            border: 1.5px solid #d6ac58;
            box-shadow: none;
        }

        .dial-value {
            font-size: 4rem;
            font-weight: 700;
            color: #ffffff;
            line-height: 1.1;
            display: inline-block;
        }

        .dial-card.oval .dial-value {
            background: linear-gradient(135deg, #ab70ff 0%, #d8b4fe 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .dial-label {
            font-size: 1.95rem;
            color: #82789c;
            /* Soft purple-ish gray matching mockup label */
            margin-top: 4px;
            font-weight: 400;
        }

        .dial-card.circle .dial-label {
            color: #d6ac58;
            /* Gold label matching mockup */
        }

        /* STAND BY SCENERY BANNER */
        .scenery-section {
            /* background: red; */
            display: flex;
            justify-content: center;
            padding: 4rem 0 6rem 0;
        }

        .scenery-card {
            width: 100%;
            max-width: 70em;
            height: auto;
            aspect-ratio: 1914 / 1198;
            border-radius: 36px;
            overflow: hidden;
            position: relative;
            box-shadow: rgba(0, 0, 0, 0.4) 0px 10px 30px;
        }


        .scenery-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
        }

        .scenery-text {
            font-family: 'Silkscreen', monospace;
            font-size: 3rem;
            font-weight: 700;
            color: var(--text-main);
            letter-spacing: 2px;
            text-transform: uppercase;
            text-shadow: 0 0 15px rgba(255, 255, 255, 0.4),
                0 0 30px rgba(255, 255, 255, 0.2);
            animation: textFlicker 3s infinite alternate;
        }

        @keyframes textFlicker {

            0%,
            100% {
                opacity: 0.95;
            }

            50% {
                opacity: 1;
            }
        }

        /* FOOTER */
        footer {
            border-top: 1px solid rgba(255, 255, 255, 0.03);
            padding: 3rem 0;
            text-align: center;
            color: var(--text-muted);
            font-size: 0.8rem;
        }

        footer span {
            color: var(--text-main);
            font-weight: 700;
        }

        /* RESPONSIVE LAYOUTS */
        @media (max-width: 768px) {
            nav {
                padding: 1.5rem 0;
            }

            .hero-pre-title {
                font-size: 1.8rem;
            }

            .hero-main-title {
                font-size: 2.8rem;
            }

            .hero-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .hero-visual-card {
                height: 160px;
            }

            .split-grid,
            .career-grid {
                grid-template-columns: 1fr;
                gap: 2.5rem;
            }

            .split-left-title {
                font-size: 1.8rem;
            }

            .career-card {
                padding: 1.5rem;
            }

            .career-card:nth-child(2) {
                flex-direction: row-reverse;
            }

            .editor-body {
                padding: 1rem 1.25rem;
                font-size: 0.75rem;
            }


            .scenery-text {
                font-size: 2rem;
            }
        }

        /* SCROLL REVEAL ANIMATIONS */
        .reveal {
            opacity: 0;
            will-change: transform, opacity;
            transition: opacity 1.0s cubic-bezier(0.16, 1, 0.3, 1), transform 1.0s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .reveal-fade {
            transform: none;
        }

        .reveal-slide-up {
            transform: translateY(45px);
        }

        .reveal-slide-down {
            transform: translateY(-45px);
        }

        .reveal-slide-left {
            transform: translateX(-40px);
        }

        .reveal-slide-right {
            transform: translateX(40px);
        }

        .reveal-scale {
            transform: scale(0.94);
        }

        .reveal.revealed {
            opacity: 1;
            transform: translate(0) scale(1);
        }

        /* Stagger delays */
        .reveal.d-1 {
            transition-delay: 0.1s;
        }

        .reveal.d-2 {
            transition-delay: 0.2s;
        }

        .reveal.d-3 {
            transition-delay: 0.3s;
        }

        .reveal.d-4 {
            transition-delay: 0.4s;
        }

        /* PARALLAX ENHANCEMENTS */
        .hero-visual-card {
            position: relative;
            overflow: hidden;
        }

        .hero-visual-card img {
            width: 100%;
            height: 100% !important;
            object-fit: cover;
            position: absolute;
            top: 0;
            left: 0;
            --hero-scale: 1;
            transform: scale(var(--hero-scale));
            transition: transform 0.5s ease;
        }

        .hero-visual-card:hover img {
            --hero-scale: 1.03;
        }

        .career-visual {
            width: 150px;
            height: 210px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transform: translateY(var(--parallax-y, 0px)) rotate(var(--parallax-rotate, 0deg));
            transition: transform 0.08s ease-out;
            will-change: transform;
        }

        .career-visual img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 100px;
        }

        .capsule-img-wrapper {
            width: 100%;
            height: 320px;
            z-index: 1;
            display: flex;
            position: absolute;
            overflow: hidden;
            border-radius: 50px 0 0 50px;
        }

        .capsule-img {
            width: 50%;
            height: 140% !important;
            position: absolute;
            top: -20%;
            left: 0;
            border-radius: 0 !important;
            /* clipped by wrapper */
            object-fit: cover;
            transform: translateY(var(--parallax-y, 0px));
            transition: transform 0.08s ease-out;
            will-change: transform;
        }

        .scenery-card {
            width: 100%;
            height: auto;
            aspect-ratio: 1914 / 1198;
            border-radius: 36px;
            overflow: hidden;
            position: relative;
            box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px;
            will-change: transform;
        }

        .scenery-card.reveal {
            opacity: 0;
            transform: scale(0.85);
            transition: opacity 1.2s cubic-bezier(0.16, 1, 0.3, 1), transform 1.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .scenery-card.reveal.revealed {
            opacity: 1;
            transform: scale(var(--scroll-scale, 0.95));
        }

        .scenery-card.reveal.revealed.sync-active {
            transition: transform 0.3s cubic-bezier(0.25, 1, 0.5, 1), opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .scenery-video {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            object-fit: cover;
            background: #050405;
            --scenery-scale: 1;
            transform: scale(var(--scenery-scale));
            transition: transform 0.08s ease-out;
            will-change: transform;
            pointer-events: none;
        }

        .scenery-card:hover .scenery-video {
            --scenery-scale: 1.02;
        }

        .scenery-play-btn {
            position: absolute;
            bottom: 2rem;
            right: 2rem;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            opacity: 0;
            transform: translateY(6px);
            transition: opacity 0.35s ease, transform 0.35s ease, background 0.25s ease;
        }

        .scenery-card:hover .scenery-play-btn {
            opacity: 1;
            transform: translateY(0);
        }

        .scenery-play-btn:hover {
            background: rgba(255, 255, 255, 0.22);
        }

        .scenery-play-btn svg {
            width: 18px;
            height: 18px;
            fill: #fff;
        }

        .scenery-play-btn .icon-pause {
            display: none;
        }

        .scenery-play-btn.is-playing .icon-play {
            display: none;
        }

        .scenery-play-btn.is-playing .icon-pause {
            display: block;
        }

        /* PAGE TRANSITIONS & LENIS SMOOTH SCROLL INTEGRATION */
        html.lenis,
        html.lenis body {
            height: auto;
        }

        .lenis.lenis-smooth {
            scroll-behavior: auto !important;
        }

        .lenis.lenis-smooth [data-lenis-prevent] {
            overflow: clip;
        }

        .lenis.lenis-stopped {
            overflow: hidden;
        }

        .lenis.lenis-scrolling iframe {
            pointer-events: none;
        }

        .page-transition-wrapper {
            opacity: 0;
            transform: translateY(12px);
            transition: opacity 0.6s cubic-bezier(0.25, 1, 0.5, 1), transform 0.6s cubic-bezier(0.25, 1, 0.5, 1);
            will-change: opacity, transform;
        }

        .page-transition-wrapper.loaded {
            opacity: 1;
            transform: translateY(0);
        }

        .page-transition-wrapper.fade-out {
            opacity: 0;
            transform: translateY(-12px);
        }

        /* Hero Section Scroll Parallax */
        .hero-pre-title.reveal.revealed,
        .hero-main-title.reveal.revealed,
        .hero-buttons.reveal.revealed {
            transform: translateY(var(--parallax-y, 0px));
        }

        .hero-visual-card.reveal.revealed,
        .hero-desc.reveal.revealed {
            transform: translateX(var(--parallax-x, 0px));
        }

        .hero-pre-title.reveal.revealed.sync-active,
        .hero-main-title.reveal.revealed.sync-active,
        .hero-buttons.reveal.revealed.sync-active,
        .hero-visual-card.reveal.revealed.sync-active,
        .hero-desc.reveal.revealed.sync-active {
            transition: transform 0.08s ease-out, opacity 0.08s ease-out;
        }

        /* REVIEWS SECTION */
        .reviews-section {
            padding: 8rem 0;
            display: flex;
            flex-direction: column;
            gap: 4rem;
        }

        .reviews-header {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 4rem;
            align-items: end;
        }

        .reviews-header-title {
            font-size: 2.2rem;
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -1.5px;
            color: var(--text-main);
        }

        .reviews-header-desc {
            font-size: 1.05rem;
            color: var(--text-muted);
            line-height: 1.7;
            padding-bottom: 5px;
        }

        .reviews-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            width: 100%;
        }

        .review-card {
            background: rgba(26, 23, 28, 0.45);
            border: 1px solid rgba(255, 255, 255, 0.03);
            border-radius: 24px;
            padding: 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 2rem;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        .review-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(800px circle at var(--mouse-x, 0px) var(--mouse-y, 0px), rgba(139, 92, 246, 0.06), transparent 40%);
            z-index: 1;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.5s ease;
        }

        .review-card:hover::before {
            opacity: 1;
        }

        .review-card:hover {
            transform: translateY(-8px);
            border-color: rgba(139, 92, 246, 0.25);
            background: rgba(35, 32, 37, 0.6);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }

        .review-rating {
            display: flex;
            gap: 0.25rem;
            color: #fbbf24;
            z-index: 2;
        }

        .review-rating svg {
            width: 16px;
            height: 16px;
            fill: currentColor;
        }

        .review-content {
            font-size: 1rem;
            line-height: 1.7;
            color: var(--text-muted);
            font-weight: 400;
            z-index: 2;
            transition: color 0.3s ease;
        }

        .review-card:hover .review-content {
            color: var(--text-main);
        }

        .review-user {
            display: flex;
            align-items: center;
            gap: 1rem;
            z-index: 2;
        }

        .review-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.08);
            transition: border-color 0.3s ease;
        }

        .review-card:hover .review-avatar {
            border-color: var(--accent-purple);
        }

        .review-user-info {
            display: flex;
            flex-direction: column;
            gap: 0.15rem;
        }

        .review-user-name {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text-main);
        }

        .review-user-role {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .review-quote-icon {
            position: absolute;
            top: 2rem;
            right: 2rem;
            color: rgba(139, 92, 246, 0.08);
            width: 48px;
            height: 48px;
            pointer-events: none;
            z-index: 1;
            transition: color 0.3s ease;
        }

        .review-card:hover .review-quote-icon {
            color: rgba(139, 92, 246, 0.15);
        }

        .reviews-header-left.reveal.revealed.sync-active,
        .reviews-header-right.reveal.revealed.sync-active {
            transform: translateX(var(--reviews-header-x, 0px));
            opacity: var(--reviews-header-opacity, 1);
            transition: transform 0.08s ease-out, opacity 0.08s ease-out;
        }

        @media (max-width: 992px) {
            .reviews-header {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .reviews-header-title {
                font-size: 2rem;
            }

            .reviews-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .reviews-grid {
                grid-template-columns: 1fr;
            }

            .review-card {
                padding: 2rem;
            }
        }

        /* DEVELOPER SECTION */
        .dev-section {
            padding: 8rem 0;
            display: flex;
            flex-direction: column;
            gap: 4rem;
        }

        .dev-header {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 4rem;
            align-items: end;
        }

        .dev-header-title {
            font-size: 2.2rem;
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -1.5px;
            color: var(--text-main);
        }

        .dev-header-desc {
            font-size: 1.05rem;
            color: var(--text-muted);
            line-height: 1.7;
            padding-bottom: 5px;
        }

        .dev-header-left.reveal.revealed.sync-active,
        .dev-header-right.reveal.revealed.sync-active {
            transform: translateX(var(--dev-header-x, 0px));
            opacity: var(--dev-header-opacity, 1);
            transition: transform 0.08s ease-out, opacity 0.08s ease-out;
        }

        .dev-cards-wrapper {
            width: 100%;
            overflow: hidden;
        }

        .dev-cards-track {
            display: flex;
            gap: 1.5rem;
            transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
            width: 100%;
        }

        .dev-card {
            flex: 0 0 calc((100% - 2 * 1.5rem) / 3);
            cursor: pointer;
            transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
        }

        .dev-card-img-wrapper {
            position: relative;
            border-radius: 24px;
            overflow: hidden;
            aspect-ratio: 16 / 10;
            background: #1e1b20;
            border: 1px solid rgba(255, 255, 255, 0.03);
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .dev-card-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.55;
            transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
            filter: grayscale(100%);
        }

        .dev-card.active .dev-card-img {
            opacity: 1;
            filter: none;
            transform: scale(1.02);
        }

        .dev-card.active .dev-card-img-wrapper {
            border-color: rgba(255, 255, 255, 0.12);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
        }

        .dev-overlay-text {
            position: absolute;
            top: 2rem;
            left: 2rem;
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--text-main);
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
            line-height: 1.1;
            letter-spacing: -1px;
            pointer-events: none;
            text-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
        }

        .dev-card.active .dev-overlay-text {
            opacity: 1;
            transform: translateY(0);
        }

        .dev-play-btn {
            position: absolute;
            bottom: 1.5rem;
            right: 1.5rem;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.9);
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #000;
            cursor: pointer;
            opacity: 0.6;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .dev-card:hover .dev-play-btn {
            opacity: 1;
            transform: scale(1.1);
        }

        .dev-card.active .dev-play-btn {
            background: var(--text-main);
            color: #000;
            opacity: 1;
        }

        .dev-footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 4rem;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            padding-top: 2rem;
        }

        .dev-details {
            max-width: 500px;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .dev-details-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-main);
            text-transform: capitalize;
        }

        .dev-details-desc {
            font-size: 0.95rem;
            color: var(--text-muted);
            line-height: 1.6;
        }

        .dev-controls {
            display: flex;
            align-items: center;
            gap: 3rem;
        }

        .dev-arrows {
            display: flex;
            gap: 0.75rem;
        }

        .dev-arrow-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: transparent;
            color: var(--text-main);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.25s ease;
        }

        .dev-arrow-btn:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .dev-case-link {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-main);
            text-decoration: none;
            transition: color 0.2s ease;
            white-space: nowrap;
        }

        .dev-case-link:hover {
            color: var(--text-muted);
        }

        @media (max-width: 992px) {
            .dev-header {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .dev-header-title {
                font-size: 2.5rem;
            }

            .dev-overlay-text {
                font-size: 1.6rem;
                top: 1.5rem;
                left: 1.5rem;
            }

            .dev-footer {
                flex-direction: column;
                gap: 2rem;
            }

            .dev-controls {
                width: 100%;
                justify-content: space-between;
            }
        }

        @media (max-width: 768px) {
            .dev-card {
                flex: 0 0 100%;
            }

            .dev-card-img-wrapper {
                aspect-ratio: 16 / 9;
            }

            .dev-section {
                padding: 5rem 0;
            }
        }
    </style>
</head>

<body class="welcome-page">
    <noscript>
        <style>
            .page-transition-wrapper {
                opacity: 1 !important;
                transform: none !important;
            }

            .circle-transition-overlay {
                display: none !important;
            }
        </style>
    </noscript>

    <div class="page-transition-wrapper">
        <div class="container">
            <!-- NAVBAR -->
            <nav>
                <a href="/" class="nav-logo">
                    <img src="{{ asset('images/logo/Logo-TurnCode-white.png') }}" alt="TurnCode" class="nav-logo-img">
                    <h5>TurnCode</h5>
                </a>
                <div class="nav-actions">
                    @auth
                        <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="nav-link">Masuk</a>
                    @endauth
                </div>
            </nav>

            <!-- HERO SECTION -->
            <section class="hero-section">
                <div class="hero-title-container">
                    <div class="hero-pre-title reveal reveal-slide-down">Belajar jadi</div>
                    <h1 class="hero-main-title reveal reveal-slide-up d-1">Programmer From <span>Zero</span> to
                        <span>Pro</span>
                    </h1>
                </div>
                <div class="hero-grid">
                    <div class="hero-visual-card reveal reveal-slide-left d-2">
                        <img src="{{ asset('images/grayscale_wave.jpg') }}" alt="Wave visual">
                    </div>
                    <div class="hero-desc reveal reveal-slide-right d-2">
                        TurnCode membantumu belajar coding dengan kurikulum terstruktur, proyek nyata, dan komunitas
                        yang
                        supportif. Tidak perlu pengalaman sebelumnya.
                    </div>
                </div>
                <div class="hero-buttons reveal reveal-slide-up d-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn-pill light">🚀 Lanjut Belajar</a>
                    @else
                        <a href="{{ route('register') }}" class="btn-pill dark">Daftar Sekarang</a>
                        <a href="{{ route('login') }}" class="btn-pill light">Masuk</a>
                    @endauth
                </div>
            </section>

            <!-- ABOUT SECTION -->
            <section class="info-section">
                <div class="split-grid about-split">
                    <div class="reveal reveal-slide-left about-split-left">
                        <div class="split-left-label">Seputar informasi</div>
                        <h2 class="split-left-title">Tentang TurnCode</h2>
                    </div>
                    <div class="split-right-desc reveal reveal-slide-right d-1 about-split-right">
                        Pembuatan Aplikasi Pembelajaran mengandung tema Gamified Learning yang dimana didalam aplikasi
                        tersebut tersedia materi pembelajaran yang mengusung konsep Microlearning (materi singkat,
                        ringan,
                        dan konsisten setiap hari). Dengan menggunakan visual belajar, sehingga pembelajaran terasa
                        tidak
                        seperti kelas formal. Serta aplikasi berbasis progres harian yang dimana pengguna diharapkan
                        termotivasi lewat target harian dan pencapaian di aplikasi tersebut.
                    </div>
                </div>
            </section>

            <!-- CAREER GOALS SECTION -->
            <section class="career-section">
                <div class="career-grid">
                    <div class="career-cards-stack">
                        <!-- Card 1: App Dev -->
                        <div class="career-card-row reveal reveal-slide-up">
                            <div class="career-card">
                                <div class="career-card-text">
                                    <div class="career-card-header">
                                        <span class="career-card-title">App Development</span>
                                        <span class="career-card-badge">Coming soon</span>
                                    </div>
                                    <div class="career-card-desc">Menyediakan live coding di setiap materi yang
                                        dipelajari
                                    </div>
                                </div>
                            </div>
                            <div class="career-visual" data-parallax-speed="0.1">
                                <img src="{{ asset('images/orange_loop.jpg') }}" alt="Orange torus">
                            </div>
                        </div>

                        <!-- Card 2: Web Dev -->
                        <div class="career-card-row reverse reveal reveal-slide-up d-1">
                            <div class="career-visual" data-parallax-speed="-0.08">
                                <img src="{{ asset('images/grey_loop_one.jpg') }}" alt="Grey loop 1">
                            </div>
                            <div class="career-card">
                                <div class="career-card-text">
                                    <div class="career-card-header">
                                        <span class="career-card-title">Web Development</span>
                                    </div>
                                    <div class="career-card-desc">Menyediakan live coding di setiap materi yang
                                        dipelajari
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card 3: Game Dev -->
                        <div class="career-card-row reveal reveal-slide-up d-2">
                            <div class="career-card">
                                <div class="career-card-text">
                                    <div class="career-card-header">
                                        <span class="career-card-title">Game Development</span>
                                        <span class="career-card-badge purple">Coming soon</span>
                                    </div>
                                    <div class="career-card-desc">Menyediakan live coding di setiap materi yang
                                        dipelajari
                                    </div>
                                </div>
                            </div>
                            <div class="career-visual" data-parallax-speed="0.12">
                                <img src="{{ asset('images/grey_loop_two.jpg') }}" alt="Grey loop 2">
                            </div>
                        </div>
                    </div>

                    <div class="career-title-block reveal reveal-slide-right">
                        <h2 class="career-title">Tujuan Karir mu!</h2>
                        <div class="career-desc">
                            Kami menyediakan beberapa Jobdesk yang bisa kamu pelajari sesuai ketertarikanmu
                        </div>
                    </div>
                </div>
            </section>

            <!-- LIVE CODE SECTION -->
            <section class="info-section">
                <div class="split-grid">
                    <div class="reveal reveal-slide-left">
                        <h2 class="split-left-title" style="margin-bottom: 1.5rem;">Live Code</h2>
                        <div class="split-right-desc">
                            Menyediakan live coding di setiap materi yang dipelajari
                        </div>
                    </div>

                    <div class="editor-screen-wrapper reveal d-1" data-scroll-scale="true">
                        <div class="editor-container">
                            <div class="editor-header">
                                <div class="editor-dots">
                                    <div class="editor-dot"></div>
                                    <div class="editor-dot"></div>
                                    <div class="editor-dot"></div>
                                </div>
                                <div class="editor-title">index.html</div>
                            </div>
                            <div class="editor-body">
                                <div class="code-line">
                                    <span class="code-bracket">&lt;</span><span class="code-tag">!DOCTYPE</span> <span
                                        class="code-attr">html</span><span class="code-bracket">&gt;</span>
                                </div>
                                <div class="code-line">
                                    <span class="code-bracket">&lt;</span><span class="code-tag">html</span> <span
                                        class="code-attr">lang</span><span class="code-bracket">=</span><span
                                        class="code-val">"en"</span><span class="code-bracket">&gt;</span>
                                </div>
                                <div class="code-line indent-1">
                                    <span class="code-bracket">&lt;</span><span class="code-tag">head</span><span
                                        class="code-bracket">&gt;</span>
                                </div>
                                <div class="code-line indent-2">
                                    <span class="code-bracket">&lt;</span><span class="code-tag">meta</span> <span
                                        class="code-attr">charset</span><span class="code-bracket">=</span><span
                                        class="code-val">"UTS-8"</span> <span class="code-bracket">/&gt;</span>
                                </div>
                                <div class="code-line indent-2">
                                    <span class="code-bracket">&lt;</span><span class="code-tag">meta</span> <span
                                        class="code-attr">name</span><span class="code-bracket">=</span><span
                                        class="code-val">"viewport"</span> <span class="code-attr">content</span><span
                                        class="code-bracket">=</span><span class="code-val">"width=device"</span><span
                                        class="code-bracket">&gt;</span>
                                </div>
                                <div class="code-line indent-2">
                                    <span class="code-bracket">&lt;</span><span class="code-tag">link</span> <span
                                        class="code-attr">rel</span><span class="code-bracket">=</span><span
                                        class="code-val">"stylesheet"</span> <span class="code-attr">hef</span><span
                                        class="code-bracket">=</span><span class="code-val">"style.css"</span> <span
                                        class="code-bracket">/&gt;</span>
                                </div>
                                <div class="code-line indent-2">
                                    <span class="code-bracket">&lt;</span><span class="code-tag">title</span><span
                                        class="code-bracket">&gt;</span><span class="code-text">Image
                                        GrayScale</span><span class="code-bracket">&lt;/</span><span
                                        class="code-tag">title</span><span class="code-bracket">&gt;</span>
                                </div>
                                <div class="code-line indent-1">
                                    <span class="code-bracket">&lt;/</span><span class="code-tag">head</span><span
                                        class="code-bracket">&gt;</span>
                                </div>
                                <div class="code-line indent-1">
                                    <span class="code-bracket">&lt;</span><span class="code-tag">body</span><span
                                        class="code-bracket">&gt;</span>
                                </div>
                                <div class="code-line indent-2">
                                    <span class="code-bracket">&lt;</span><span class="code-tag">img</span> <span
                                        class="code-attr">src</span><span class="code-bracket">=</span><span
                                        class="code-val">"img.jpeg"</span> <span class="code-attr">alt</span><span
                                        class="code-bracket">=</span><span class="code-val">"Image not found"</span>
                                    <span class="code-bracket">/&gt;</span>
                                </div>
                                <div class="code-line indent-1">
                                    <span class="code-bracket">&lt;/</span><span class="code-tag">body</span><span
                                        class="code-bracket">&gt;</span>
                                </div>
                                <div class="code-line">
                                    <span class="code-bracket">&lt;/</span><span class="code-tag">html</span><span
                                        class="code-bracket">&gt;</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- GAMIFIKASI SECTION -->
            <section class="info-section">
                <div class="split-grid gamify-grid">
                    <div class="gamify-visual-col">
                        <div class="gold-capsule-card reveal reveal-scale">
                            <div class="capsule-img-wrapper">
                                <img class="capsule-img" src="{{ asset('images/feather_gold.png') }}" alt=""
                                    data-parallax-speed="-0.12">
                            </div>
                            <div class="capsule-gradient"></div>
                            <div class="capsule-content">
                                <span class="capsule-lbl">Tier tertinggi</span>
                                <h4 class="capsule-title">Visionary</h4>
                                <span class="capsule-sub">4.000.000 EXP</span>
                            </div>
                        </div>
                        <div class="dial-row reveal reveal-slide-up d-1">
                            <div class="dial-card oval">
                                <span class="dial-value">10+</span>
                                <span class="dial-label">Achivements</span>
                            </div>
                            <div class="dial-card circle">
                                <span class="dial-value">10+</span>
                                <span class="dial-label">Tier</span>
                            </div>
                        </div>
                    </div>

                    <div class="reveal reveal-slide-right">
                        <h2 class="split-left-title" style="margin-bottom: 1.5rem;">Sistem Gamifikasi</h2>
                        <div class="split-right-desc">
                            Raih tier tertinggi dan jadi jagoan programmer sepuh
                        </div>
                    </div>
                </div>
            </section>

            <!-- STAND BY SCENERY -->
            <section class="scenery-section">
                <div class="scenery-card reveal" data-scroll-scale="true" id="sceneryCard">
                    <video class="scenery-video" id="sceneryVideo" loop muted playsinline preload="metadata">
                        <source src="{{ asset('videos/scenery-video.mp4') }}" type="video/mp4">
                    </video>
                    <button class="scenery-play-btn" id="sceneryPlayBtn" type="button" aria-label="Play/Pause video">
                        <svg class="icon-play" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z" />
                        </svg>
                        <svg class="icon-pause" viewBox="0 0 24 24">
                            <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z" />
                        </svg>
                    </button>
            </section>

            <!-- REVIEWS SECTION -->
            <section class="reviews-section">
                <!-- Header: Two columns -->
                <div class="reviews-header">
                    <h2 class="reviews-header-title reveal reveal-slide-left reviews-header-left">Apa Kata
                        Mereka?<br>Ulasan dari komunitas TurnCode</h2>
                    <p class="reviews-header-desc reveal reveal-slide-right d-1 reviews-header-right">
                        Lebih dari 10.000+ developer, mahasiswa, dan kreator telah bergabung. Dengarkan langsung cerita
                        sukses mereka belajar coding interaktif di TurnCode.
                    </p>
                </div>

                <!-- Cards Grid -->
                <div class="reviews-grid reveal reveal-slide-up d-1">
                    <!-- Card 1 -->
                    <div class="review-card">
                        <svg class="review-quote-icon" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M6 17h3l2-4V7H5v6h3zm8 0h3l2-4V7h-6v6h3z" />
                        </svg>
                        <div class="review-rating">
                            <svg viewBox="0 0 24 24">
                                <path
                                    d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                            </svg>
                            <svg viewBox="0 0 24 24">
                                <path
                                    d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                            </svg>
                            <svg viewBox="0 0 24 24">
                                <path
                                    d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                            </svg>
                            <svg viewBox="0 0 24 24">
                                <path
                                    d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                            </svg>
                            <svg viewBox="0 0 24 24">
                                <path
                                    d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                            </svg>
                        </div>
                        <p class="review-content">"Materi di TurnCode sangat interaktif dan mudah dipahami. Saya
                            berhasil mendapatkan pekerjaan pertama saya sebagai Frontend Developer dalam 6 bulan berkat
                            kurikulum terstruktur di sini."</p>
                        <div class="review-user">
                            <img class="review-avatar" src="{{ asset('images/develop/dev001.jpeg') }}"
                                alt="Rian Kurniawan">
                            <div class="review-user-info">
                                <h4 class="review-user-name">Rian Kurniawan</h4>
                                <p class="review-user-role">Frontend Developer di TechCorp</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="review-card">
                        <svg class="review-quote-icon" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M6 17h3l2-4V7H5v6h3zm8 0h3l2-4V7h-6v6h3z" />
                        </svg>
                        <div class="review-rating">
                            <svg viewBox="0 0 24 24">
                                <path
                                    d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                            </svg>
                            <svg viewBox="0 0 24 24">
                                <path
                                    d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                            </svg>
                            <svg viewBox="0 0 24 24">
                                <path
                                    d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                            </svg>
                            <svg viewBox="0 0 24 24">
                                <path
                                    d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                            </svg>
                            <svg viewBox="0 0 24 24">
                                <path
                                    d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                            </svg>
                        </div>
                        <p class="review-content">"Paling suka dengan compiler langsung di browser dan feedback yang
                            instan! Belajar struktur data tidak lagi terasa membosankan atau membingungkan. Sangat
                            direkomendasikan untuk pemula."</p>
                        <div class="review-user">
                            <img class="review-avatar" src="{{ asset('images/develop/dev003.jpeg') }}"
                                alt="Sarah Amalia">
                            <div class="review-user-info">
                                <h4 class="review-user-name">Sarah Amalia</h4>
                                <p class="review-user-role">Mahasiswa Ilmu Komputer</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="review-card">
                        <svg class="review-quote-icon" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M6 17h3l2-4V7H5v6h3zm8 0h3l2-4V7h-6v6h3z" />
                        </svg>
                        <div class="review-rating">
                            <svg viewBox="0 0 24 24">
                                <path
                                    d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                            </svg>
                            <svg viewBox="0 0 24 24">
                                <path
                                    d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                            </svg>
                            <svg viewBox="0 0 24 24">
                                <path
                                    d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                            </svg>
                            <svg viewBox="0 0 24 24">
                                <path
                                    d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                            </svg>
                            <svg viewBox="0 0 24 24">
                                <path
                                    d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                            </svg>
                        </div>
                        <p class="review-content">"Transisi antar topik dikemas dengan sangat baik. Sistem gamifikasi
                            dan tantangan coding mingguan membuat saya tetap termotivasi untuk terus ngoding setiap
                            hari."</p>
                        <div class="review-user">
                            <img class="review-avatar" src="{{ asset('images/develop/dev002.jpeg') }}"
                                alt="David Wijaya">
                            <div class="review-user-info">
                                <h4 class="review-user-name">David Wijaya</h4>
                                <p class="review-user-role">Self-taught Full Stack Developer</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 4 -->
                    <div class="review-card">
                        <svg class="review-quote-icon" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M6 17h3l2-4V7H5v6h3zm8 0h3l2-4V7h-6v6h3z" />
                        </svg>
                        <div class="review-rating">
                            <svg viewBox="0 0 24 24">
                                <path
                                    d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                            </svg>
                            <svg viewBox="0 0 24 24">
                                <path
                                    d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                            </svg>
                            <svg viewBox="0 0 24 24">
                                <path
                                    d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                            </svg>
                            <svg viewBox="0 0 24 24">
                                <path
                                    d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                            </svg>
                            <svg viewBox="0 0 24 24">
                                <path
                                    d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                            </svg>
                        </div>
                        <p class="review-content">"Meskipun background saya design, TurnCode membantu saya memahami
                            logika kode di balik desain visual saya. Navigasinya mulus, interface-nya sangat premium!"
                        </p>
                        <div class="review-user">
                            <img class="review-avatar" src="{{ asset('images/develop/dev004.jpeg') }}" alt="Mega Putri">
                            <div class="review-user-info">
                                <h4 class="review-user-name">Mega Putri</h4>
                                <p class="review-user-role">UI/UX Designer di Startup</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 5 -->
                    <div class="review-card">
                        <svg class="review-quote-icon" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M6 17h3l2-4V7H5v6h3zm8 0h3l2-4V7h-6v6h3z" />
                        </svg>
                        <div class="review-rating">
                            <svg viewBox="0 0 24 24">
                                <path
                                    d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                            </svg>
                            <svg viewBox="0 0 24 24">
                                <path
                                    d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                            </svg>
                            <svg viewBox="0 0 24 24">
                                <path
                                    d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                            </svg>
                            <svg viewBox="0 0 24 24">
                                <path
                                    d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                            </svg>
                            <svg viewBox="0 0 24 24">
                                <path
                                    d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                            </svg>
                        </div>
                        <p class="review-content">"Materi database dan API design-nya luar biasa detail. Penjelasan
                            visual dan studi kasusnya benar-benar mirip dengan masalah riil yang saya temukan di
                            pekerjaan."</p>
                        <div class="review-user">
                            <img class="review-avatar" src="{{ asset('images/develop/dev005.jpeg') }}"
                                alt="Ahmad Fauzi">
                            <div class="review-user-info">
                                <h4 class="review-user-name">Ahmad Fauzi</h4>
                                <p class="review-user-role">Backend Engineer</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 6 -->
                    <div class="review-card">
                        <svg class="review-quote-icon" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M6 17h3l2-4V7H5v6h3zm8 0h3l2-4V7h-6v6h3z" />
                        </svg>
                        <div class="review-rating">
                            <svg viewBox="0 0 24 24">
                                <path
                                    d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                            </svg>
                            <svg viewBox="0 0 24 24">
                                <path
                                    d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                            </svg>
                            <svg viewBox="0 0 24 24">
                                <path
                                    d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                            </svg>
                            <svg viewBox="0 0 24 24">
                                <path
                                    d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                            </svg>
                            <svg viewBox="0 0 24 24">
                                <path
                                    d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                            </svg>
                        </div>
                        <p class="review-content">"TurnCode memberikan pondasi pemrograman (OOP & Algoritma) yang kuat
                            sebelum saya masuk ke game engine. Cara penyampaiannya seru dan interaktif."</p>
                        <div class="review-user">
                            <img class="review-avatar" src="{{ asset('images/developer_1.png') }}" alt="Clara Shinta">
                            <div class="review-user-info">
                                <h4 class="review-user-name">Clara Shinta</h4>
                                <p class="review-user-role">Game Developer</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- DEVELOPER SECTION -->
            <section class="dev-section">
                <!-- Header: Two columns -->
                <div class="dev-header">
                    <h2 class="dev-header-title reveal reveal-slide-left dev-header-left">Developer TurnCode<br>Generasi
                        pembangun era digital modern</h2>
                    <p class="dev-header-desc reveal reveal-slide-right d-1 dev-header-right">
                        TurnCode is built for developer growth, whether you're a professional developer working in a
                        large enterprise codebase, a student starting your coding journey, a hobbyist coding in your
                        spare time, or anyone in between.
                    </p>
                </div>

                <!-- Cards Carousel -->
                <div class="dev-cards-wrapper reveal reveal-slide-up d-1">
                    <div class="dev-cards-track" id="devCardsTrack">
                        <!-- Card 1 -->
                        <div class="dev-card active" data-index="0" onclick="selectDeveloper(0)">
                            <div class="dev-card-img-wrapper">
                                <img class="dev-card-img" src="{{ asset('images/develop/dev001.jpeg') }}"
                                    alt="Hobbyist Developer">
                                <div class="dev-overlay-text">Front End<br>developer</div>
                                <button class="dev-play-btn">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M8 5v14l11-7z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <!-- Card 2 -->
                        <div class="dev-card" data-index="1" onclick="selectDeveloper(1)">
                            <div class="dev-card-img-wrapper">
                                <img class="dev-card-img" src="{{ asset('images/develop/dev002.jpeg') }}"
                                    alt="Enterprise Developer">
                                <div class="dev-overlay-text">Full Stack<br>developer</div>
                                <button class="dev-play-btn">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M8 5v14l11-7z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <!-- Card 3 -->
                        <div class="dev-card" data-index="2" onclick="selectDeveloper(2)">
                            <div class="dev-card-img-wrapper">
                                <img class="dev-card-img" src="{{ asset('images/develop/dev003.jpeg') }}"
                                    alt="Student Developer">
                                <div class="dev-overlay-text">Front End<br>developer</div>
                                <button class="dev-play-btn">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M8 5v14l11-7z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <!-- Card 4 -->
                        <div class="dev-card" data-index="3" onclick="selectDeveloper(3)">
                            <div class="dev-card-img-wrapper">
                                <img class="dev-card-img" src="{{ asset('images/develop/dev004.jpeg') }}"
                                    alt="DevOps Engineer">
                                <div class="dev-overlay-text">Designer<br>UI/UX</div>
                                <button class="dev-play-btn">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M8 5v14l11-7z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <!-- Card 5 -->
                        <div class="dev-card" data-index="4" onclick="selectDeveloper(4)">
                            <div class="dev-card-img-wrapper">
                                <img class="dev-card-img" src="{{ asset('images/develop/dev005.jpeg') }}"
                                    alt="AI Researcher">
                                <div class="dev-overlay-text">Back End<br>developer</div>
                                <button class="dev-play-btn">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M8 5v14l11-7z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom details / controls -->
                <div class="dev-footer reveal reveal-slide-up d-2">
                    <div class="dev-details">
                        <h4 class="dev-details-title" id="devTitle">Hobbyist developer</h4>
                        <p class="dev-details-desc" id="devDesc">TurnCode memberdayakan kreator mandiri dan penikmat
                            coding untuk membangun ide dan mimpi mereka.</p>
                    </div>
                    <div class="dev-controls">
                        <div class="dev-arrows">
                            <button class="dev-arrow-btn" onclick="navigateDeveloper(-1)">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="15 18 9 12 15 6" />
                                </svg>
                            </button>
                            <button class="dev-arrow-btn" onclick="navigateDeveloper(1)">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="9 18 15 12 9 6" />
                                </svg>
                            </button>
                        </div>
                        <a href="{{ route('register') }}" class="dev-case-link" id="devLink">Mulai Belajar <svg
                                width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                                style="margin-left: 4px; display: inline-block; vertical-align: middle;">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19" />
                            </svg></a>
                    </div>
                </div>
            </section>

            <!-- FOOTER -->
            <footer class="reveal reveal-fade">
                <p>© 2026 <span>TurnCode</span>. All rights reserved.</p>
            </footer>
        </div>
    </div> <!-- page-transition-wrapper -->

    <!-- Lenis Smooth Scroll CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@studio-freight/lenis@1.0.42/dist/lenis.min.js"></script>

    <!-- PARALLAX SCROLL CONTROLLER -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // --- 0. Page Transition Wrapper (Fade and Slide entrance) ---
            const transitionWrapper = document.querySelector('.page-transition-wrapper');
            if (transitionWrapper) {
                window.requestAnimationFrame(() => {
                    transitionWrapper.classList.add('loaded');
                });
            }



            // --- 0.5. Lenis Smooth Scroll Initialization ---
            let lenis;
            if (typeof Lenis !== 'undefined') {
                lenis = new Lenis({
                    lerp: 0.12, // linear interpolation for quick snappy scroll response
                    smoothWheel: true,
                    smoothTouch: false
                });

                function raf(time) {
                    lenis.raf(time);
                    requestAnimationFrame(raf);
                }
                requestAnimationFrame(raf);
            }

            // --- 1. Intersection Observer for Scroll Reveal ---
            const revealElements = document.querySelectorAll('.reveal');

            const revealObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('revealed');

                        // Enable instant scroll synchronization once entrance reveal transition ends
                        const isHero = entry.target.classList.contains('hero-pre-title') ||
                            entry.target.classList.contains('hero-main-title') ||
                            entry.target.classList.contains('hero-visual-card') ||
                            entry.target.classList.contains('hero-desc') ||
                            entry.target.classList.contains('hero-buttons');



                        if (entry.target.hasAttribute('data-scroll-scale')) {
                            setTimeout(() => {
                                entry.target.classList.add('sync-active');
                            }, 1200);
                        } else if (isHero) {
                            setTimeout(() => {
                                entry.target.classList.add('sync-active');
                            }, 1000);
                        } else if (isAbout) {
                            setTimeout(() => {
                                entry.target.classList.add('sync-active');
                            }, 1000);
                        }

                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.08,
                rootMargin: '0px 0px -40px 0px'
            });

            revealElements.forEach(el => revealObserver.observe(el));

            // --- 2. Performance-Optimized Scroll Parallax System ---
            const parallaxElements = document.querySelectorAll('[data-parallax-speed]');
            const careerCards = document.querySelectorAll('.career-card');
            const scaleElements = document.querySelectorAll('[data-scroll-scale]');

            const heroPreTitle = document.querySelector('.hero-pre-title');
            const heroMainTitle = document.querySelector('.hero-main-title');
            const heroVisualCard = document.querySelector('.hero-visual-card');
            const heroDesc = document.querySelector('.hero-desc');
            const heroButtons = document.querySelector('.hero-buttons');

            const aboutSplitLeft = document.querySelector('.about-split-left');
            const aboutSplitRight = document.querySelector('.about-split-right');
            const devHeaderLeft = document.querySelector('.dev-header-left');
            const devHeaderRight = document.querySelector('.dev-header-right');

            let isTicking = false;

            function updateParallax() {
                const viewportHeight = window.innerHeight;
                const viewportCenter = viewportHeight / 2;

                // Update hero parallax based on scrollY (kondisi awal - akhir jadi akhir - awal)
                const scrollY = window.scrollY || window.pageYOffset;
                const heroOpacity = Math.max(1 - (scrollY / 350), 0);
                const maxDrift = 60; // Maximum displacement in pixels
                const heroShift = Math.min(scrollY * 0.15, maxDrift);

                if (heroPreTitle) {
                    heroPreTitle.style.setProperty('--parallax-y', `${-heroShift}px`);
                    if (heroPreTitle.classList.contains('sync-active')) {
                        heroPreTitle.style.opacity = heroOpacity;
                    }
                }
                if (heroMainTitle) {
                    heroMainTitle.style.setProperty('--parallax-y', `${heroShift}px`);
                    if (heroMainTitle.classList.contains('sync-active')) {
                        heroMainTitle.style.opacity = heroOpacity;
                    }
                }
                if (heroVisualCard) {
                    heroVisualCard.style.setProperty('--parallax-x', `${-heroShift}px`);
                    if (heroVisualCard.classList.contains('sync-active')) {
                        heroVisualCard.style.opacity = heroOpacity;
                    }
                }
                if (heroDesc) {
                    heroDesc.style.setProperty('--parallax-x', `${heroShift}px`);
                    if (heroDesc.classList.contains('sync-active')) {
                        heroDesc.style.opacity = heroOpacity;
                    }
                }
                if (heroButtons) {
                    heroButtons.style.setProperty('--parallax-y', `${heroShift}px`);
                    if (heroButtons.classList.contains('sync-active')) {
                        heroButtons.style.opacity = heroOpacity;
                    }
                }

                // Update about split parallax
                if (aboutSplitLeft && aboutSplitRight) {
                    const parent = aboutSplitLeft.parentElement;
                    if (parent) {
                        const rect = parent.getBoundingClientRect();
                        if (rect.top < viewportHeight && rect.bottom > 0) {
                            const parentHeight = rect.height;
                            const parentCenter = rect.top + parentHeight / 2;
                            const offsetFromCenter = parentCenter - viewportCenter;

                            const maxDistance = viewportCenter;
                            const normalizedDist = Math.min(Math.abs(offsetFromCenter) / maxDistance, 1);

                            // Opacity transitions from 1.0 (center) to 0.5 (exit edges)
                            const opacity = 1 - (Math.pow(normalizedDist, 2) * 0.5);

                            // Shift horizontal (left shifts left, right shifts right)
                            const maxShift = 80;
                            const shiftVal = Math.pow(normalizedDist, 3) * maxShift;

                            aboutSplitLeft.style.setProperty('--about-x', `${-shiftVal}px`);
                            aboutSplitLeft.style.setProperty('--about-opacity', opacity);

                            aboutSplitRight.style.setProperty('--about-x', `${shiftVal}px`);
                            aboutSplitRight.style.setProperty('--about-opacity', opacity);
                        } else {
                            aboutSplitLeft.style.setProperty('--about-x', `-80px`);
                            aboutSplitLeft.style.setProperty('--about-opacity', '0.5');

                            aboutSplitRight.style.setProperty('--about-x', `80px`);
                            aboutSplitRight.style.setProperty('--about-opacity', '0.5');
                        }
                    }
                }

                // Update dev header parallax
                if (devHeaderLeft && devHeaderRight) {
                    const parent = devHeaderLeft.parentElement;
                    if (parent) {
                        const rect = parent.getBoundingClientRect();
                        if (rect.top < viewportHeight && rect.bottom > 0) {
                            const parentHeight = rect.height;
                            const parentCenter = rect.top + parentHeight / 2;
                            const offsetFromCenter = parentCenter - viewportCenter;

                            const maxDistance = viewportCenter;
                            const normalizedDist = Math.min(Math.abs(offsetFromCenter) / maxDistance, 1);

                            // Opacity transitions from 1.0 (center) to 0.5 (exit edges)
                            const opacity = 1 - (Math.pow(normalizedDist, 2) * 0.5);

                            // Shift horizontal (left shifts left, right shifts right)
                            const maxShift = 80;
                            const shiftVal = Math.pow(normalizedDist, 3) * maxShift;

                            devHeaderLeft.style.setProperty('--dev-header-x', `${-shiftVal}px`);
                            devHeaderLeft.style.setProperty('--dev-header-opacity', opacity);

                            devHeaderRight.style.setProperty('--dev-header-x', `${shiftVal}px`);
                            devHeaderRight.style.setProperty('--dev-header-opacity', opacity);
                        } else {
                            devHeaderLeft.style.setProperty('--dev-header-x', `-80px`);
                            devHeaderLeft.style.setProperty('--dev-header-opacity', '0.5');

                            devHeaderRight.style.setProperty('--dev-header-x', `80px`);
                            devHeaderRight.style.setProperty('--dev-header-opacity', '0.5');
                        }
                    }
                }

                // Update standard parallax elements
                parallaxElements.forEach(el => {
                    const rect = el.getBoundingClientRect();
                    const parent = el.parentElement;
                    const parentRect = parent ? parent.getBoundingClientRect() : rect;

                    // Only compute transforms if parent card is currently visible in viewport
                    if (parentRect.top < viewportHeight && parentRect.bottom > 0) {
                        const parentHeight = parentRect.height;
                        const elementCenter = parentRect.top + parentHeight / 2;

                        // Distance from the center of the viewport
                        const offsetFromCenter = elementCenter - viewportCenter;

                        const speed = parseFloat(el.getAttribute('data-parallax-speed')) || 0.1;
                        const rotateSpeed = parseFloat(el.getAttribute('data-parallax-rotate')) || 0;

                        let translateY = offsetFromCenter * speed;

                        // Apply cubic easing to translateY for career-visual elements
                        // to keep them perfectly vertically aligned with the cards at the center of the viewport
                        if (el.classList.contains('career-visual')) {
                            const maxDistance = viewportCenter;
                            const normalizedDist = Math.min(Math.abs(offsetFromCenter) / maxDistance, 1);
                            translateY = translateY * Math.pow(normalizedDist, 3);
                        }

                        el.style.setProperty('--parallax-y', `${translateY}px`);

                        if (rotateSpeed !== 0) {
                            const rotate = offsetFromCenter * rotateSpeed;
                            el.style.setProperty('--parallax-rotate', `${rotate}deg`);
                        }
                    }
                });

                // Update career cards exit transitions (fade and shift on scroll exit)
                careerCards.forEach((card, index) => {
                    const rect = card.getBoundingClientRect();

                    if (rect.top < viewportHeight && rect.bottom > 0) {
                        const cardHeight = rect.height;
                        const cardCenter = rect.top + cardHeight / 2;
                        const offsetFromCenter = cardCenter - viewportCenter;

                        // Distance normalized: 0 at center, 1 at viewport edges
                        const maxDistance = viewportCenter;
                        const normalizedDist = Math.min(Math.abs(offsetFromCenter) / maxDistance, 1);

                        // Opacity transitions from 1.0 (center) to 0.6 (exit edges)
                        const opacity = 1 - (normalizedDist * 0.2);

                        // Shift X direction: Web Dev (index 1) shifts right, App/Game Dev (index 0, 2) shift left
                        const shiftDirection = (index === 1) ? 1 : -1;
                        const maxShift = 100; // Max shift displacement in pixels (increased from 45)
                        // Use cubic interpolation (normalizedDist^3) so the shift remains near 0 
                        // when cards are in the viewport center (aligned), only accelerating as they exit
                        const shiftX = shiftDirection * (Math.pow(normalizedDist, 3) * maxShift);

                        card.style.setProperty('--exit-opacity', opacity);
                        card.style.setProperty('--exit-x', `${shiftX}px`);
                    } else {
                        // Fully off-screen: hold target values
                        card.style.setProperty('--exit-opacity', '0.6');
                        const shiftDirection = (index === 1) ? 1 : -1;
                        card.style.setProperty('--exit-x', `${shiftDirection * 100}px`);
                    }
                });

                // Update scroll scale elements (scale in-out on scroll)
                scaleElements.forEach(el => {
                    const rect = el.getBoundingClientRect();

                    if (rect.top < viewportHeight && rect.bottom > 0) {
                        const elHeight = rect.height;
                        const elCenter = rect.top + elHeight / 2;
                        const offsetFromCenter = elCenter - viewportCenter;

                        // Distance normalized: 0 at center, 1 at viewport edges
                        const maxDistance = viewportCenter;
                        const normalizedDist = Math.min(Math.abs(offsetFromCenter) / maxDistance, 1);

                        // Scale transitions from 1.0 (center) to 0.70 (exit edges)
                        const scale = 1 - (normalizedDist * 0.30);
                        el.style.setProperty('--scroll-scale', scale);
                    } else {
                        // Fully off-screen: hold scale at 0.70
                        el.style.setProperty('--scroll-scale', '0.70');
                    }
                });

                isTicking = false;
            }

            // Hook scroll listener to Lenis if loaded, otherwise fallback to window scroll
            if (lenis) {
                lenis.on('scroll', updateParallax);
            } else {
                window.addEventListener('scroll', () => {
                    if (!isTicking) {
                        window.requestAnimationFrame(updateParallax);
                        isTicking = true;
                    }
                }, { passive: true });
            }

            // --- Developer Section Slider Logic ---
            const developersData = [
                {
                    title: "Frontend developer",
                    desc: "TurnCode meningkatkan kemampuan yang mampu bersaing ditingkat industri.",
                    linkText: "Gass Belajar"
                },
                {
                    title: "Fullstack developer",
                    desc: "Membawa TurnCode ke level yang lebih tinggi dengan membekali skill yang dibutuhkan oleh pasar.",
                    linkText: "Gass Belajar"
                },
                {
                    title: "Frontend developer",
                    desc: "TurnCode dapat membantu kita belajar lebih teratur agar kita tidak dongo",
                    linkText: "Gass Belajar"
                },
                {
                    title: "Desaigner UI/UX",
                    desc: "TurnCode mampu mengembangkan potensi dan kreatifitas menjadi seseorang yang lebih baik kedepannya",
                    linkText: "Gass Belajar"
                },
                {
                    title: "Backend developer",
                    desc: "TurnCode memandu Anda menjelajahi dunia kecerdasan buatan, machine learning, dan pengolahan data masa kini.",
                    linkText: "Gass Belajar"
                }
            ];

            let activeDevIndex = 0;
            const devCards = document.querySelectorAll('.dev-card');
            const devCardsTrack = document.getElementById('devCardsTrack');
            const devTitle = document.getElementById('devTitle');
            const devDesc = document.getElementById('devDesc');
            const devLink = document.getElementById('devLink');

            window.selectDeveloper = function (index) {
                activeDevIndex = index;
                devCards.forEach((card, idx) => {
                    if (idx === index) {
                        card.classList.add('active');
                    } else {
                        card.classList.remove('active');
                    }
                });

                if (devTitle && devDesc && developersData[index]) {
                    devTitle.textContent = developersData[index].title;
                    devDesc.textContent = developersData[index].desc;
                    const arrowSvg = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 4px; display: inline-block; vertical-align: middle;"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"/></svg>`;
                    devLink.innerHTML = `${developersData[index].linkText} ${arrowSvg}`;
                }

                if (devCardsTrack && devCards.length > 0) {
                    const cardWidth = devCards[0].offsetWidth;
                    const gap = 24; // 1.5rem gap matches CSS
                    const visibleCards = window.innerWidth <= 768 ? 1 : 3;

                    const maxShift = 0;
                    const minShift = - (developersData.length - visibleCards) * (cardWidth + gap);

                    let targetShift = - (activeDevIndex - Math.floor(visibleCards / 2)) * (cardWidth + gap);
                    targetShift = Math.min(maxShift, Math.max(targetShift, minShift));

                    devCardsTrack.style.transform = `translateX(${targetShift}px)`;
                }
            };

            window.navigateDeveloper = function (direction) {
                let newIndex = activeDevIndex + direction;
                if (newIndex < 0) {
                    newIndex = developersData.length - 1;
                } else if (newIndex >= developersData.length) {
                    newIndex = 0;
                }
                selectDeveloper(newIndex);
            };

            window.addEventListener('resize', () => {
                selectDeveloper(activeDevIndex);
            });

            // Initialize slider state
            selectDeveloper(0);

            // --- Scenery Video: Hover Play / Pause ---
            const sceneryCard = document.getElementById('sceneryCard');
            const sceneryVideo = document.getElementById('sceneryVideo');
            const sceneryPlayBtn = document.getElementById('sceneryPlayBtn');
            let userPaused = false;

            function syncPlayBtn() {
                if (!sceneryPlayBtn) return;
                if (sceneryVideo.paused) {
                    sceneryPlayBtn.classList.remove('is-playing');
                } else {
                    sceneryPlayBtn.classList.add('is-playing');
                }
            }

            if (sceneryCard && sceneryVideo) {
                sceneryCard.addEventListener('mouseenter', () => {
                    if (!userPaused) {
                        sceneryVideo.play();
                        syncPlayBtn();
                    }
                });
                sceneryCard.addEventListener('mouseleave', () => {
                    sceneryVideo.pause();
                    syncPlayBtn();
                    userPaused = false;
                });

                if (sceneryPlayBtn) {
                    sceneryPlayBtn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        if (sceneryVideo.paused) {
                            sceneryVideo.play();
                            userPaused = false;
                        } else {
                            sceneryVideo.pause();
                            userPaused = true;
                        }
                        syncPlayBtn();
                    });
                }
            }

            // Initial layout calculation
            updateParallax();
        });
    </script>
</body>

</html>