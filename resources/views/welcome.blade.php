@extends('layouts.app')

@section('title', 'Conference Schedule | Ai24')

@push('styles')
<style>

        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --text: #1f2937;
            --text-light: #6b7280;
            --bg: #f9fafb;
        }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--bg);
            color: var(--text);
            line-height: 1.6;
        }

        .hero {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
            padding: 6rem 2rem;
            text-align: center;
        }
        .hero-content {
            max-width: 800px;
            margin: 0 auto;
        }
       
        .hero h1 {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }
        .hero p {
            font-size: 1.25rem;
            max-width: 600px;
            margin: 0 auto 2.5rem;
            opacity: 0.95;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 4rem 2rem;
        }
        .about-conference {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 3rem;
            align-items: center;
        }
        .about-text h2 {
            font-size: 2.25rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            color: var(--text);
        }
        .about-text p {
            color: var(--text-light);
            margin-bottom: 1.5rem;
            font-size: 1.125rem;
        }
        .highlight-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 4rem;
        }
        .card {
            background: white;
            border-radius: 0.75rem;
            padding: 2rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card h3 {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--text);
        }
        .card p {
            color: var(--text-light);
        }
        footer {
            background-color: var(--text);
            color: white;
            padding: 3rem 2rem;
            text-align: center;
        }
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.25rem;
            }
            .navbar {
                flex-direction: column;
                gap: 1rem;
                padding: 1rem;
            }
        }
    </style>
@endpush

@section('content')
<section class="hero">
        <div class="hero-content">
            <h1>The Premier ai Conference of 2024</h1>
            <p>Join industry leaders and innovators for three days of inspiring talks, hands-on workshops, and unparalleled networking opportunities.</p>
            <a href="{{ route('signup') }}" class="btn btn-signup">Register Now</a>
        </div>
    </section>

    <div class="container">
        <section class="about-conference">
            <div class="about-text">
                <h2>About Ai24 </h2>
                <p>Ai24 brings together the brightest minds in technology for an unforgettable experience. Our 2024 edition features cutting-edge topics in AI, blockchain, cloud computing, and more.</p>
                <p>Held in the heart of Guelma from april 16-17-18, this year's conference promises to be our biggest yet with over 100 speakers and 5,000 attendees from around the globe.</p>
                <a href="{{ route('schedule') }}" class="btn btn-signup">Learn More</a>
            </div>
            <div>
                <img src="{{ asset('assets/ai.jpg') }}"alt="Conference attendees" style="width:100%; border-radius:0.5rem; box-shadow:0 10px 15px -3px rgba(0,0,0,0.1);">
            </div>
        </section>

        <section class="highlight-cards">
            <div class="card">
                <h3>World-Class Speakers</h3>
                <p>Learn from industry pioneers and visionary thinkers shaping the future of technology.</p>
            </div>
            <div class="card">
                <h3>Interactive Workshops</h3>
                <p>Get hands-on experience with the latest tools and technologies in small-group sessions.</p>
            </div>
            <div class="card">
                <h3>Networking Opportunities</h3>
                <p>Connect with peers, potential collaborators, and employers at our social events.</p>
            </div>
        </section>
    </div>

    <footer>
        <p>&copy; 2023 Ai24. All rights reserved.</p>
    </footer>
@endsection




