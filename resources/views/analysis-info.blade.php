<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.analysis_info_tips') }} - {{ __('messages.admin_panel') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>

<body>
    <!-- Global Dynamic Background -->
    <div class="global-bg">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <!-- Navigation -->
    <nav>
        <ul class="nav-links">
            <li><a href="/"><i class="fas fa-home"></i> {{ __('messages.home') }}</a></li>
            <li><a href="/#analysis"><i class="fas fa-flask"></i> {{ __('messages.analysis') }}</a></li>
            <li><a href="/#booking"><i class="fas fa-calendar-check"></i> {{ __('messages.booking') }}</a></li>
            <li><a href="/#contact"><i class="fas fa-envelope"></i> {{ __('messages.contact') }}</a></li>
        </ul>
    </nav>

    <!-- Analysis Info Section -->
    <section class="section">
        <div class="container">
            <h1><i class="fas fa-flask"></i> {{ __('messages.analysis_info_tips') }}</h1>
            <p>{{ __('messages.analysis_info_desc') }}</p>

            <div class="analysis-info-grid">
                @forelse($analyses as $analyse)
                <div class="info-card">
                    <h3><i class="fas fa-vial"></i> {{ $analyse->name }}</h3>
                    <div class="info-content">
                        <h4>{{ __('messages.analysis_status') }}:</h4>
                        <p>
                            @if($analyse->availability == 1)
                            <span class="status-badge available-status">{{ __('messages.available') }}</span>
                            @else
                            <span class="status-badge unavailable-status">{{ __('messages.unavailable') }}</span>
                            @endif
                        </p>
                        <h4>{{ __('messages.preparation_instructions') }}:</h4>
                        {{$analyse->preparation_instructions ?? __('messages.no_prep_tips')}}

                        <h4>{{ __('messages.additional_notes') }}:</h4>
                        <p>{{ $analyse->description ?? __('messages.no_extra_info') }}</p>

                        <h4><i class="fas fa-clock"></i> {{ __('messages.duration') }}:</h4>
                        <p>{{ $analyse->duration }}</p>

                        <h4><i class="fas fa-money-bill-wave"></i> {{ __('messages.price') }}:</h4>
                        <p class="price">{{ number_format($analyse->price, 2) }} {{ __('messages.price_unit') ?? 'دج' }}</p>
                    </div>
                </div>
                @empty
                <div class="info-card">
                    <h3><i class="fas fa-vial"></i> {{ __('messages.no_analyses_added') }}</h3>
                    <div class="info-content">
                        <p>{{ __('messages.no_analyses_registered') }}</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Back to Home Button -->
    <section class="section">
        <div class="container text-center">
            <a href="/" class="cta-button"><i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}"></i> {{ __('messages.return_to_home') }}</a>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>{{ __('messages.admin_panel') }}</h3>
                <p>{{ __('messages.footer_about') }}</p>
                <div class="social-icons">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>
            <div class="footer-section">
                <h3>{{ __('messages.quick_links') }}</h3>
                <a href="/">{{ __('messages.home') }}</a>
                <a href="/#analysis">{{ __('messages.analysis') }}</a>
                <a href="/#booking">{{ __('messages.booking') }}</a>
                <a href="/#contact">{{ __('messages.contact') }}</a>
            </div>
            <div class="footer-section">
                <h3>{{ __('messages.contact_info') }}</h3>
                <p><i class="fas fa-map-marker-alt"></i> {{ __('messages.address') }}</p>
                <p><i class="fas fa-phone"></i> {{ __('messages.phone_val') }}</p>
                <p><i class="fas fa-envelope"></i> {{ __('messages.email_val') }}</p>
                <p><i class="fas fa-clock"></i> {{ __('messages.work_hours') }}</p>
            </div>
        </div>
        <div class="copyright">
            <p>{!! __('messages.all_rights_reserved') !!}</p>
        </div>
    </footer>

    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>