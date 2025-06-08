@extends('layouts.app')

@section('title', 'Speakers | Ai24')

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
        .btn-signup {
            background-color: var(--primary);
            color: white;
        }
        .btn-login {
            color: var(--text);
        }
        .btn-login:hover {
            color: var(--primary);
        }
        .btn-signup {
            background-color: var(--primary);
            color: white;
        }
        .btn-signup:hover {
            background-color: var(--primary-dark);
            transform: translateY(-1px);
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
        .btn-contact {
            background-color: #ffffff;
            color: #4f46e5;
            border: 1px solid #4f46e5;
            padding: 0.625rem 1.25rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9375rem;
            transition: all 0.2s;
        }

        .btn-contact:hover {
            background-color: #f5f3ff;
            transform: translateY(-1px);
        }
        .btn-signup:hover {
            background-color: var(--primary-dark);
            transform: translateY(-1px);
        }
        .hero {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
            padding: 4rem 2rem;
            text-align: center;
        }
        .hero h1 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 3rem 2rem;
        }
        .speakers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        .speaker-card {
            background: white;
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .speaker-card:hover {
            transform: translateY(-5px);
        }
        .speaker-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }
        .speaker-info {
            padding: 1.5rem;
        }
        .speaker-name {
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0 0 0.5rem 0;
            color: var(--text);
        }
        .speaker-title {
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 0.75rem;
            font-size: 0.9375rem;
        }
        .speaker-topic {
            color: var(--text-light);
            margin-bottom: 1rem;
            font-size: 0.9375rem;
        }
        .speaker-bio {
            color: var(--text-light);
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
        }
        .tag {
            display: inline-block;
            background-color: #e0e7ff;
            color: var(--primary);
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
        }
        .section-title {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            color: var(--text);
            text-align: center;
        }
        .section-subtitle {
            color: var(--text-light);
            text-align: center;
            max-width: 700px;
            margin: 0 auto 3rem;
        }
        footer {
            background-color: var(--text);
            color: white;
            padding: 3rem 2rem;
            text-align: center;
        }
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2rem;
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
        <h1>Our Distinguished Speakers</h1>
        <p>Learn from the brightest minds in technology</p>
    </section>

    <div class="container">
        <h2 class="section-title">Featured Speakers</h2>
        <p class="section-subtitle">Meet the industry leaders and innovators who will be sharing their knowledge at TechConf 2023</p>
        
        <div class="speakers-grid">
            <!-- Speaker 1 -->
            <div class="speaker-card">
            <img src="{{ asset('assets/hallaci.jfif') }}" alt="Dr. Samir Hallaci" class="speaker-image">
            <div class="speaker-info">
                    <h3 class="speaker-name">Dr. Samir Hallaci</h3>
                    <p class="speaker-title">Chief AI Scientist, NeuroTech</p>
                    <p class="speaker-topic">Keynote: The Future of Neural Interfaces</p>
                    <p class="speaker-bio">Pioneer in brain-computer interfaces with 15+ years of research experience at MIT and Stanford.</p>
                    <div>
                        <span class="tag">Artificial Intelligence</span>
                        <span class="tag">Neuroscience</span>
                        <span class="tag">Machine Learning</span>
                    </div>
                </div>
            </div>

            <!-- Speaker 2 -->
            <div class="speaker-card">
                <img src="{{ asset('assets/zaid.jfif') }}" alt="Zaid Farhat " class="speaker-image">
                <div class="speaker-info">
                    <h3 class="speaker-name">Farhat Zaid</h3>
                    <p class="speaker-title">CTO, BlockChain Solutions</p>
                    <p class="speaker-topic">Workshop: Building Secure DApps</p>
                    <p class="speaker-bio">Blockchain expert and author of "Decentralized Future", leading developer of several open-source protocols.</p>
                    <div>
                        <span class="tag">Blockchain</span>
                        <span class="tag">Cryptography</span>
                        <span class="tag">Web3</span>
                    </div>
                </div>
            </div>

            <!-- Speaker 3 -->
            <div class="speaker-card">
                <img src="{{ asset('assets/follan.jfif') }}" alt="Follan follani" class="speaker-image">
                <div class="speaker-info">
                    <h3 class="speaker-name">Folan Folani</h3>
                    <p class="speaker-title">Director of Cloud Architecture, Azure</p>
                    <p class="speaker-topic">Panel: Multi-Cloud Strategies</p>
                    <p class="speaker-bio">Leading cloud architect helping Fortune 500 companies transition to hybrid cloud environments.</p>
                    <div>
                        <span class="tag">Cloud Computing</span>
                        <span class="tag">DevOps</span>
                        <span class="tag">Security</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <p>&copy; Ai24. All rights reserved.</p>
    </footer>
@endsection
