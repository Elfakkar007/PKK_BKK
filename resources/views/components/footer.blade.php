<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4 mb-lg-0">
                <h5 class="mb-3">{{ settings('site_name', 'BKK SMKN 1 Purwosari') }}</h5>
                <p class="text-light">
                    {{ settings('site_description', 'Bursa Kerja Khusus yang menghubungkan siswa dan alumni dengan perusahaan mitra untuk peluang karir terbaik.') }}
                </p>
                <div class="social-links">
                    @if(settings('social_facebook'))
                        <a href="{{ settings('social_facebook') }}" target="_blank" class="text-white me-3">
                            <i class="bi bi-facebook fs-4"></i>
                        </a>
                    @endif
                    @if(settings('social_instagram'))
                        <a href="{{ settings('social_instagram') }}" target="_blank" class="text-white me-3">
                            <i class="bi bi-instagram fs-4"></i>
                        </a>
                    @endif
                    @if(settings('social_twitter'))
                        <a href="{{ settings('social_twitter') }}" target="_blank" class="text-white me-3">
                            <i class="bi bi-twitter fs-4"></i>
                        </a>
                    @endif
                    @if(settings('social_youtube'))
                        <a href="{{ settings('social_youtube') }}" target="_blank" class="text-white">
                            <i class="bi bi-youtube fs-4"></i>
                        </a>
                    @endif
                </div>
            </div>
            
            <div class="col-lg-2 col-md-4 mb-4 mb-lg-0">
                <h5 class="mb-3">Menu</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ route('home') }}" class="text-light text-decoration-none">Beranda</a></li>
                    <li class="mb-2"><a href="{{ route('information') }}" class="text-light text-decoration-none">Informasi</a></li>
                    <li class="mb-2"><a href="{{ route('companies') }}" class="text-light text-decoration-none">Perusahaan</a></li>
                    <li class="mb-2"><a href="{{ route('vacancies') }}" class="text-light text-decoration-none">Lowongan</a></li>
                    <li class="mb-2"><a href="{{ route('about') }}" class="text-light text-decoration-none">Tentang</a></li>
                </ul>
            </div>
            
            <div class="col-lg-3 col-md-4 mb-4 mb-lg-0">
                <h5 class="mb-3">Kontak</h5>
                <ul class="list-unstyled text-light">
                    @if(settings('contact_address'))
                        <li class="mb-2">
                            <i class="bi bi-geo-alt-fill me-2"></i>
                            {{ settings('contact_address') }}
                        </li>
                    @endif
                    @if(settings('contact_phone'))
                        <li class="mb-2">
                            <i class="bi bi-telephone-fill me-2"></i>
                            {{ settings('contact_phone') }}
                        </li>
                    @endif
                    @if(settings('contact_email'))
                        <li class="mb-2">
                            <i class="bi bi-envelope-fill me-2"></i>
                            {{ settings('contact_email') }}
                        </li>
                    @endif
                </ul>
            </div>
            
            <div class="col-lg-3 col-md-4">
                <h5 class="mb-3">Jam Operasional</h5>
                <ul class="list-unstyled text-light">
                    <li class="mb-2">Senin - Jumat</li>
                    <li class="mb-2">06:45 - 14:50 WIB</li>
                </ul>
            </div>
        </div>
        
        <hr class="bg-light my-4">
        
        <div class="row">
            <div class="col-md-6 text-center text-md-start">
                <p class="mb-0 text-light">
                    &copy; {{ date('Y') }} {{ settings('site_name', 'BKK SMKN 1 Purwosari') }}. All rights reserved.
                </p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <p class="mb-0 text-light">
                    Developed by Alfakkar
                </p>
            </div>
        </div>
    </div>
</footer>