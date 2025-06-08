@extends('layouts.app')

@section('title', 'Conference Schedule | Ai24')

@push('styles')
<style>
        :root {
            --primary: #4f46e5;
            --primary-light: #6366f1;
            --primary-dark: #4338ca;
            --text: #1f2937;
            --text-light: #6b7280;
            --bg: #f9fafb;
            --border: #e5e7eb;
        }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--bg);
            color: var(--text);
            line-height: 1.6;
        }
        .navbar {
            background-color: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .logo {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .nav-links {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }
        .btn {
            padding: 0.625rem 1.25rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9375rem;
            transition: all 0.2s;
        }
        .btn-login {
            color: var(--text);
        }
        .btn-login:hover {
            color: var(--primary);
        }
        .btn-contact {
            background-color: #ffffff;
            color: var(--primary);
            border: 1px solid var(--primary);
        }
        .btn-contact:hover {
            background-color: #f5f3ff;
            transform: translateY(-1px);
        }
        .btn-signup {
            background-color: var(--primary);
            color: white;
        }
        .btn-signup:hover {
            background-color: var(--primary-dark);
            transform: translateY(-1px);
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 3rem 2rem;
        }
        .hero {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            padding: 4rem 2rem;
            text-align: center;
        }
        .hero h1 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
        }
        .hero p {
            font-size: 1.25rem;
            max-width: 700px;
            margin: 0 auto;
            opacity: 0.9;
        }
        .schedule-nav {
            display: flex;
            justify-content: center;
            margin: 2rem 0;
            gap: 1rem;
        }
        .schedule-nav-btn {
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            background: white;
            color: var(--text);
            border: 1px solid var(--border);
            cursor: pointer;
            font-weight: 600;
            transition: all 0.2s;
        }
        .schedule-nav-btn.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        .schedule-day {
            display: none;
        }
        .schedule-day.active {
            display: block;
        }
        .schedule-item {
            background: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            display: grid;
            grid-template-columns: 120px 1fr;
            gap: 1.5rem;
        }
        .schedule-time {
            font-weight: 700;
            color: var(--primary);
        }
        .schedule-content h3 {
            margin: 0 0 0.5rem 0;
            font-size: 1.25rem;
        }
        .schedule-speaker {
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: block;
        }
        .schedule-description {
            color: var(--text-light);
            margin-bottom: 0;
        }
        .schedule-track {
            display: inline-block;
            background: #e0e7ff;
            color: var(--primary);
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-top: 0.75rem;
        }
        @media (max-width: 768px) {
            .schedule-item {
                grid-template-columns: 1fr;
            }
            .schedule-time {
                margin-bottom: 0.5rem;
            }
            .navbar {
                flex-direction: column;
                gap: 1rem;
                padding: 1rem;
            }
            .hero h1 {
                font-size: 2rem;
            }
        }
    </style>
@endpush

@section('content')
<section class="hero">
        <h1>Conference Schedule</h1>
        <p>Plan your Ai24 experience with our detailed session timetable</p>
    </section>

    <div class="container">
        <div class="schedule-nav">
            <button class="schedule-nav-btn active" data-day="day1">Day 1</button>
            <button class="schedule-nav-btn" data-day="day2">Day 2</button>
            <button class="schedule-nav-btn" data-day="day3">Day 3</button>
        </div>

        <!-- Day 1 Schedule -->
        <div class="schedule-day active" id="day1">
            <div class="schedule-item">
                <div class="schedule-time">
                    09:00 - 10:00
                </div>
                <div class="schedule-content">
                    <h3>Registration & Welcome Coffee</h3>
                    <p class="schedule-description">Get your conference badge and network with attendees</p>
                </div>
            </div>

            <div class="schedule-item">
                <div class="schedule-time">
                    10:00 - 11:00
                </div>
                <div class="schedule-content">
                    <h3>Opening Keynote: The Future of AI</h3>
                    <span class="schedule-speaker">Dr. Samir Hallaci</span>
                    <p class="schedule-description">Exploring the next decade of artificial intelligence advancements and ethical considerations</p>
                    <span class="schedule-track">Main Stage</span>
                </div>
            </div>

            <div class="schedule-item">
                <div class="schedule-time">
                    11:30 - 12:30
                </div>
                <div class="schedule-content">
                    <h3>Machine Learning in Production</h3>
                    <span class="schedule-speaker">Zaid Farhat</span>
                    <p class="schedule-description">Best practices for deploying and maintaining ML models at scale</p>
                    <span class="schedule-track">Track A</span>
                </div>
            </div>

            <!-- More Day 1 items... -->
        </div>

        <!-- Day 2 Schedule -->
        <div class="schedule-day" id="day2">
            <div class="schedule-item">
                <div class="schedule-time">
                    09:00 - 10:00
                </div>
                <div class="schedule-content">
                    <h3>Breakfast & Networking</h3>
                    <p class="schedule-description">Start your day with food and new connections</p>
                </div>
            </div>

            <div class="schedule-item">
                <div class="schedule-time">
                    10:00 - 11:00
                </div>
                <div class="schedule-content">
                    <h3>Natural Language Processing Breakthroughs</h3>
                    <span class="schedule-speaker">Follan Follani</span>
                    <p class="schedule-description">Latest advancements in transformer models and language understanding</p>
                    <span class="schedule-track">Main Stage</span>
                </div>
            </div>

            <!-- More Day 2 items... -->
        </div>

        <!-- Day 3 Schedule -->
        <div class="schedule-day" id="day3">
            <div class="schedule-item">
                <div class="schedule-time">
                    09:00 - 10:00
                </div>
                <div class="schedule-content">
                    <h3>Morning Workshop: AI Ethics</h3>
                    <span class="schedule-speaker">Maria Gonzalez</span>
                    <p class="schedule-description">Interactive session on implementing ethical AI frameworks</p>
                    <span class="schedule-track">Workshop Room</span>
                </div>
            </div>

            <!-- More Day 3 items... -->
        </div>
    </div>        
    </div>
@endsection

@push('scripts')
<script>
        // Tab navigation for schedule days
        document.querySelectorAll('.schedule-nav-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                // Remove active class from all buttons and days
                document.querySelectorAll('.schedule-nav-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.schedule-day').forEach(day => day.classList.remove('active'));
                
                // Add active class to clicked button
                btn.classList.add('active');
                
                // Show corresponding day
                const dayId = btn.getAttribute('data-day');
                document.getElementById(dayId).classList.add('active');
            });
        });
    </script>
@endpush