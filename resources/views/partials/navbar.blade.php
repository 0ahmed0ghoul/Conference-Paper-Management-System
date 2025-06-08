<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar</title>
    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --text: #374151;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background-color: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .logo {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--primary);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-links {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9375rem;
            transition: 0.2s ease;
            cursor: pointer;
            border: none;
            background: none;
        }

        .btn-login {
            color: var(--text);
        }

        .btn-login:hover {
            color: var(--primary);
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

        .btn-contact {
            background-color: #ffffff;
            color: var(--primary);
            border: 1px solid var(--primary);
        }

        .btn-contact:hover {
            background-color: #f0f0ff;
            transform: translateY(-1px);
        }

        .dashboard-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2rem;
            height: 2rem;
            color: var(--primary);
        }

        form {
            display: inline;
        }

        button {
            background: none;
            border: none;
        }

        @media (max-width: 768px) {
            .nav-links {
                flex-direction: column;
                align-items: flex-end;
                gap: 0.75rem;
            }

            .navbar {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
<nav class="navbar">
    <a href="/" class="logo">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        </svg>
        Ai24
    </a>

    <div class="nav-links">
    <a href="{{ route('speakers') }}" class="btn btn-login">Speakers</a>
    <a href="{{ route('schedule') }}" class="btn btn-login">Schedule</a>
    <a href="{{ route('accepted-papers') }}" class="btn btn-info">Papers</a>
    <a href="{{ route('contact') }}" class="btn btn-contact">Contact</a>

    @guest
        <a href="{{ route('login') }}" class="btn btn-login">Login</a>
        <a href="{{ route('signup') }}" class="btn btn-signup">Signup</a>
    @endguest

    @auth
        @php
            $user = auth()->user();
            $dashboardRoute = $user->email === 'superchair@example.com'
                ? route('chair.index')
                : match ($user->role) {
                    'chair' => route('chair.index'),
                    'reviewer' => route('reviewer.dashboard'),
                    'author' => route('author.dashboard'),
                    default => '#',
                };
        @endphp

        <a href="{{ $dashboardRoute }}" title="Dashboard" class="dashboard-icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 12c2.21 0 4-1.79 4-4S14.21 4 12 4 8 5.79 8 8s1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
            </svg>
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-login">Logout</button>
        </form>
    @endauth
</div>



</nav>
</body>
</html>
