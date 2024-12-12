<header>
    <nav class="navbar">
        <div class="logo">
            <a href="{{ route('front.index') }}"><img src="{{ asset('template\images\new\DC LOGO.png') }}" alt="Logo" width="240px" ></a>
        </div>

        <ul class="nav-links">
            <li><a href="{{ route('front.index') }}">Home</a></li>
            @if(auth()->check())
                <li>
                    <a href="{{ route('admin.dashboard') }}">{{ ucfirst(auth()->user()->roles()->first()->name) }} Dashboard</a>
                </li>
            @else
                <li><a href="{{ route('register', ['role' => 'government']) }}">Government</a></li>
                <li><a href="{{ route('register', ['role' => 'candidate']) }}">Candidates</a></li>
                <li><a href="{{ route('register', ['role' => 'institution']) }}">Institutions</a></li>
                <li><a href="{{ route('register', ['role' => 'company']) }}">Companies</a></li>
            @endif
        </ul>

        <div class="login-btn">
            <a href="{{ route('login') }}" class="btn">Login</a>
        </div>

        <div class="mobile-menu-toggle">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </div>
    </nav>
</header>
