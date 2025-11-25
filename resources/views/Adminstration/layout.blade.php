<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="{{ asset('css/dashboard/base.css') }}" rel="stylesheet">

    @if(request()->routeIs('dashboard'))
    <link href="{{ asset('css/dashboard/dashboard.css') }}" rel="stylesheet">
@elseif(request()->routeIs('analyses*'))
    <link href="{{ asset('css/dashboard/analyses.css') }}" rel="stylesheet">
@elseif(request()->routeIs('reservations*'))
    <link href="{{ asset('css/dashboard/reservations.css') }}" rel="stylesheet">
@elseif(request()->routeIs('messages*'))
    <link href="{{ asset('css/dashboard/messages.css') }}" rel="stylesheet">
@endif

@yield('styles')


</head>
<body>
    @auth('administrator')
    <header class="admin-header">
        <h1>
            <i class="fas fa-tachometer-alt"></i>
            لوحة تحكم مخبر المنيعة
        </h1>
        <div class="admin-actions">
            <form action="{{ route('administrator.logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-sign-out-alt"></i> خروج
                </button>
            </form>
        </div>
    </header>

    <div class="admin-container">
        <nav class="admin-sidebar">
            <ul class="sidebar-menu">
                <li><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i> لوحة التحكم
                </a></li>
                <li><a href="{{ route('reservations') }}" class="{{ request()->routeIs('reservations') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check"></i> إدارة الحجوزات
                </a></li>
                <li><a href="{{ route('analyses') }}" class="{{ request()->routeIs('analyses') ? 'active' : '' }}">
                    <i class="fas fa-flask"></i> إدارة التحاليل
                </a></li>
                <li><a href="{{ route('messages') }}" class="{{ request()->routeIs('messages') ? 'active' : '' }}">
                    <i class="fas fa-envelope"></i> إرسال الرسائل
                </a></li>
            </ul>
        </nav>

        <main class="admin-main">
            @yield('content')
        </main>
    </div>
    @else
    <div class="login-redirect">
        <p>يجب تسجيل الدخول أولاً</p>
        <a href="/auth" class="btn btn-primary">تسجيل الدخول</a>  {{-- Fixed link --}}
    </div>
    @endauth

    {{-- Notifications --}}
    @if(session('success'))
    <div class="notification success show">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="notification error show">
        {{ session('error') }}
    </div>
    @endif

    <script src="{{ asset('js/dashboard/base.js') }}" type="module"></script>

    <!-- Page Specific JS -->
    @if(request()->routeIs('dashboard'))
        <script src="{{ asset('js/dashboard/dashboard.js') }}" type="module"></script>
    @elseif(request()->routeIs('analyses*'))
        <script src="{{ asset('js/dashboard/analyses.js') }}" type="module"></script>
    @elseif(request()->routeIs('reservations*'))
        <script src="{{ asset('js/dashboard/reservations.js') }}" type="module"></script>
    @elseif(request()->routeIs('messages*'))
        <script src="{{ asset('js/dashboard/messages.js') }}" type="module"></script>
    @endif

    @yield('scripts')
</body>
</html>
