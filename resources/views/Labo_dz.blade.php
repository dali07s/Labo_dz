<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('messages.admin_panel') }} - Labo_dz</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <style>
        .btn-lang {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 8px 16px;
            border-radius: 50px;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-lang:hover {
            background: white;
            color: var(--primary-color);
            transform: translateY(-2px);
        }
        .lang-switcher-public {
            pointer-events: auto;
        }
        /* Fix for direction-dependent icons */
        [dir="ltr"] .fa-arrow-left { transform: rotate(180deg); }
        [dir="rtl"] .fa-arrow-right { transform: rotate(180deg); }
    </style>
</head>
<script src="{{ asset('js/app.js') }}"></script>

<body>
    <!-- Global Dynamic Background -->
    <div class="global-bg">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <!-- Notification -->
    <div id="notification" class="notification"></div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-error">
        {{ session('error') }}
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-error">
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Navigation -->
    <nav>
        <div class="lang-switcher-public" style="position: absolute; left: 20px; top: 20px; z-index: 1000;">
            @if(app()->getLocale() == 'ar')
                <a href="{{ route('lang.switch', 'fr') }}" class="btn-lang">Français</a>
            @else
                <a href="{{ route('lang.switch', 'ar') }}" class="btn-lang">العربية</a>
            @endif
        </div>
        <ul class="nav-links">
            <li><a href="#home"><i class="fas fa-home"></i> {{ __('messages.home') }}</a></li>
            <li><a href="#features"><i class="fas fa-star"></i> {{ __('messages.features') }}</a></li>
            <li><a href="#analysis"><i class="fas fa-flask"></i> {{ __('messages.analysis') }}</a></li>
            <li><a href="#tips"><i class="fas fa-lightbulb"></i> {{ __('messages.tips') }}</a></li>
            <li><a href="#booking"><i class="fas fa-calendar-check"></i> {{ __('messages.booking') }}</a></li>
            <li><a href="#contact"><i class="fas fa-envelope"></i> {{ __('messages.contact') }}</a></li>
        </ul>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <h1>{{ __('messages.hero_title') }}</h1>
        <p>{{ __('messages.hero_subtitle') }}</p>
        <a href="#booking" class="cta-button">{{ __('messages.book_now') }} <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}"></i></a>
    </section>

    <!-- Features Section -->
    <section id="features" class="section">
        <div class="container">
            <h2><i class="fas fa-star"></i> {{ __('messages.our_features') }}</h2>
            <p>{{ __('messages.features_desc') }}</p>

            <div class="features-grid">
                <div class="feature-card">
                    <i class="fas fa-microscope"></i>
                    <h3>{{ __('messages.modern_equipment') }}</h3>
                    <p>{{ __('messages.modern_equipment_desc') }}</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-user-md"></i>
                    <h3>{{ __('messages.medical_expertise') }}</h3>
                    <p>{{ __('messages.medical_expertise_desc') }}</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-bolt"></i>
                    <h3>{{ __('messages.fast_results') }}</h3>
                    <p>{{ __('messages.fast_results_desc') }}</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-headset"></i>
                    <h3>{{ __('messages.continuous_support') }}</h3>
                    <p>{{ __('messages.continuous_support_desc') }}</p>
                </div>
            </div>
        </div>
    </section>


    <!-- Analysis Section -->
    <section id="analysis" class="section">
        <div class="container">
            <h2><i class="fas fa-flask"></i> {{ __('messages.available_analyses_list') }}</h2>
            <p>{{ __('messages.available_analyses_desc') }}</p>
            <div class="analysis-list">
                @foreach($analyses as $analysis)
                <div class="analysis-item">
                    <span>{{ $analysis->name }}</span>
                    @if($analysis->availability == 1)
                    <button class='status-btn available'>{{ __('messages.available') }}</button>
                    @else
                    <button class='status-btn unavailable'>{{ __('messages.unavailable') }}</button>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Analysis Info Button Section -->
    <section id="tips" class="section">
        <div class="container">
            <h2><i class="fas fa-lightbulb"></i> {{ __('messages.tips_title') }}</h2>
            <p>{{ __('messages.tips_desc') }}</p>
            <a href="{{ route('analysis.info') }}" class="cta-button">{{ __('messages.view_analysis_info') }} <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}"></i></a>
        </div>
    </section>

    <!-- Booking Section -->
    <section id="booking" class="section">
        <div class="container">
            <h2><i class="fas fa-calendar-check"></i> {{ __('messages.booking') }}</h2>
            <p>{{ __('messages.booking_desc') }}</p>
            <form id="bookingForm" action="{{ route('booking') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">{{ __('messages.full_name') }}</label>
                    <input type="text" id="name" name="name" placeholder="{{ __('messages.full_name') }}" required>
                </div>
                <div class="form-group">
                    <label for="phone">{{ __('messages.phone_number') }}</label>
                    <input type="tel" id="phone" name="phone" placeholder="{{ __('messages.phone_number') }}" required>
                </div>
                <div class="form-group">
                    <label for="email">{{ __('messages.email') }}</label>
                    <input type="email" id="email" name="email" placeholder="{{ __('messages.email') }}">
                </div>
                <div class="form-group">
                    <label for="gender">{{ __('messages.gender') }}</label>
                    <select id="gender" name="gender" required>
                        <option value="">{{ __('messages.select_gender') }}</option>
                        <option value="male">{{ __('messages.male') }}</option>
                        <option value="female">{{ __('messages.female') }}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="birth_date">{{ __('messages.birth_date') }}</label>
                    <input type="date" id="birth_date" name="birth_date" required>
                </div>
                <div class="form-group">
                    <label>{{ __('messages.analysis_types') }} <span class="required">*</span></label>

                    <div class="checkbox-grid">
                      @foreach ($analyses as $analysis)
                        @if($analysis->availability == 1)
                       <div class="checkbox-item">
                  <input type="checkbox" name="analysisTypes[]" value="{{ $analysis->id }}" id="analysis_{{ $analysis->id }}">
                 <label for="analysis_{{ $analysis->id }}">{{ $analysis->name }} ({{ $analysis->code }})</label>
                  </div>
                       @endif
                           @endforeach
                           </div>
                            <small class="form-text text-muted">{{ __('messages.can_choose_multiple') }}</small>
                </div>
                <div class="form-group">
                    <label for="date">{{ __('messages.date') }}</label>
                    <input type="date" id="date" name="date" required>
                </div>
                <div class="form-group">
                    <label for="time">{{ __('messages.time') }}</label>
                    <input type="time" id="time" name="time" required>
                </div>
                <button type="submit"><i class="fas fa-paper-plane"></i> {{ __('messages.confirm_booking') }}</button>
            </form>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="section">
        <div class="container">
            <h2><i class="fas fa-envelope"></i> {{ __('messages.contact') }}</h2>
            <p>{{ __('messages.contact_desc') }}</p>
            <form id="contactForm" action={{route('message')}} method="POST">
                @csrf
                <div class="form-group">
                    <label for="contact_name">{{ __('messages.name') }}</label>
                    <input type="text" name="name" id="contact_name" placeholder="{{ __('messages.name') }}" required>
                </div>
                <div class="form-group">
                    <label for="contact_email">{{ __('messages.email') }}</label>
                    <input type="email" id="contact_email" name="email" placeholder="{{ __('messages.email') }}" required>
                </div>
                <div class="form-group">
                    <label for="message">{{ __('messages.message') }}</label>
                    <textarea id="message" name="message" rows="5" placeholder="{{ __('messages.message') }}" required></textarea>
                </div>
                <button type="submit"><i class="fas fa-paper-plane"></i> {{ __('messages.send_message') }}</button>
            </form>
        </div>
    </section>

    <!-- Map Section -->
    <section class="section">
        <div class="container">
            <h2><i class="fas fa-map-marker-alt"></i> {{ __('messages.our_location') }}</h2>
            <p>{{ __('messages.location_desc') }}</p>
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d5693.052425601013!2d5.262623025838582!3d31.957933404038677!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x125d69d1688915f9%3A0xc65def288f0e9a57!2sLaboratoire%20Bela%C3%AFd%20d&#39;analyse%20m%C3%A9dical!5e0!3m2!1sen!2sdz!4v1761573361428!5m2!1sen!2sdz" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
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
                <a href="#home">{{ __('messages.home') }}</a>
                <a href="#features">{{ __('messages.features') }}</a>
                <a href="#analysis">{{ __('messages.analysis') }}</a>
                <a href="#booking">{{ __('messages.booking') }}</a>
                <a href="#contact">{{ __('messages.contact') }}</a>
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

    {{-- Auto-trigger PDF download and form validation --}}
    <script>
        window.addEventListener('load', function() {
            // 1. Auto-download PDF if session exists
            @if(session('download_pdf'))
                var reservationId = {{ session('download_pdf') }};
                var downloadUrl = '{{ url("/reservation") }}/' + reservationId + '/pdf';
                
                var link = document.createElement('a');
                link.href = downloadUrl;
                link.download = 'reservation_confirmation.pdf';
                link.style.display = 'none';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            @endif

            // 2. Client-side validation for analysis selection
            const bookingForm = document.getElementById('bookingForm');
            if (bookingForm) {
                bookingForm.addEventListener('submit', function(e) {
                    const checkboxes = this.querySelectorAll('input[name="analysisTypes[]"]:checked');
                    if (checkboxes.length === 0) {
                        e.preventDefault();
                        alert('{{ __('messages.at_least_one_analysis') }}');
                    }
                });
            }
        });
    </script>
</body>

</html>