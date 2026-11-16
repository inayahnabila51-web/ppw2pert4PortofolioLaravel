<nav class="navbar navbar-expand-lg navbar-light bg-light mb-3">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">Portofolio Saya</a>
        <div class="navbar-nav align-items-center w-100">
            <a class="nav-link" href="{{ route('home') }}">Home</a>
            <a class="nav-link" href="{{ route('about') }}">About</a>
            <a class="nav-link" href="{{ route('education') }}">Education</a>
            <a class="nav-link" href="{{ route('projects.index') }}">Projects</a>

            @auth
                <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                <a class="nav-link" href="{{ route('skills.index') }}">Skills</a>
            @endauth

            <div class="ms-auto d-flex align-items-center gap-2">
                @auth
                    <a class="nav-link" href="{{ route('profile.saya') }}">Halo, {{ Auth::user()->name }}</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-sm btn-outline-danger">Logout</button>
                    </form>
                @else
                    <a class="btn btn-sm btn-primary" href="{{ route('login') }}">Login</a>
                @endauth
            </div>
        </div>
    </div>
</nav>