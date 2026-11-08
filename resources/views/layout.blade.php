<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ __('Cortex Data Indonesia — social intelligence, market research, and data insight solutions.') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>@yield('title', __('CTX Corp — Social Intelligence Analytics'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <a href="#main" class="skip-link">{{ __('Skip to content') }}</a>
    <header class="site-header">
        <div class="header-inner section-shell flex min-h-18 items-center justify-between gap-6 md:min-h-19 xl:min-h-20">
            <a class="shrink-0 xl:flex-1" href="{{ route('home') }}" aria-label="{{ __('CTX Corp home') }}"><img src="/images/logo.png" alt="CTX CORP" class="logo h-auto w-37 md:w-42.5" width="220" height="55"></a>
            <button id="menu-toggle" class="menu-toggle xl:hidden" aria-expanded="false" aria-controls="navigation" aria-label="{{ __('Open navigation') }}" data-open-label="{{ __('Open navigation') }}" data-close-label="{{ __('Close navigation') }}"><span></span><span></span><span></span></button>
            <nav class="xl:contents" id="navigation" aria-label="{{ __('Main navigation') }}">
                <div class="nav-links xl:flex xl:items-center xl:justify-center xl:gap-6">
                    @foreach(['home' => 'Home', 'services' => 'Services', 'cases' => 'Case Study', 'blog' => 'Blog'] as $route => $label)
                    <a href="{{ route($route) }}" @if(request()->routeIs($route)) aria-current="page" @endif>{{ __($label) }}</a>
                    @endforeach
                    <a href="{{ route('about') }}" @if(request()->routeIs('about')) aria-current="page" @endif>{{ __('About') }}</a>
                </div>
                <div class="nav-actions mt-4 flex items-center gap-3 xl:mt-0 xl:flex-1 xl:justify-end">
                    <a class="button button-blue nav-contact" href="{{ route('contact') }}">{{ __('Contact us') }}</a>
                    <form action="{{ route('language.update') }}" method="POST" class="language-form">@csrf
                        <input type="hidden" name="return_to" value="{{ request()->getRequestUri() }}">
                        <details class="language-dropdown">
                            <summary aria-label="{{ __('Language') }}">
                                <img src="/images/flag-{{ app()->getLocale() }}.svg" alt="">
                                <span>{{ strtoupper(app()->getLocale()) }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" height="14" width="10.5" viewBox="0 0 384 512">
                                    <path d="M32 288c-12.9 0-24.6 7.8-29.6 19.8S.2 333.5 9.4 342.6l160 160c12.5 12.5 32.8 12.5 45.3 0l160-160c9.2-9.2 11.9-22.9 6.9-34.9S364.9 288 352 288L32 288z" />
                                </svg>
                                <!-- <span class="" aria-hidden="true">⌄</span> -->
                            </summary>
                            <div class="language-options">
                                <button type="submit" name="locale" value="en" @if(app()->getLocale() === 'en') aria-current="true" @endif><img src="/images/flag-en.svg" alt="">English <span>EN</span></button>
                                <button type="submit" name="locale" value="id" @if(app()->getLocale() === 'id') aria-current="true" @endif><img src="/images/flag-id.svg" alt="">Indonesia <span>ID</span></button>
                            </div>
                        </details>
                    </form>
                </div>
            </nav>
        </div>
    </header>
    <main id="main">@yield('content')</main>
    <footer>
        <div class="section-shell grid gap-10 md:grid-cols-2 xl:grid-cols-4">
            <div><img src="/images/logo.png" alt="CTX CORP" class="w-48 mb-7">
                <p class="text-white">Graha Surveyor Indonesia<br>Lt 15 Unit 1503<br>Jl. Jend. Gatot Subroto, Kav 56<br>Kuningan Timur, Setia Budi<br>Jakarta Selatan, DKI Jakarta 12950</p>
            </div>
            <div>
                <h3>{{ __('Explore') }}</h3>
                <a href="/">{{ __('Home') }}</a>
                <a href="/product-and-services">{{ __('Services') }}</a>
                <a href="/about">{{ __('About us') }}</a>
            </div>
            <div>
                <h3>{{ __('Discover') }}</h3><a href="/case-study">{{ __('Case study') }}</a><a href="/blog">Blog</a><a href="/contact">{{ __('Contact') }}</a>
            </div>
            <div>
                <h3>{{ __('Get in touch') }}</h3>
                <a class="flex gap-2" href="mailto:cortexdataid@gmail.com">
                    <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" viewBox="0 0 512 512"><!--!Font Awesome Free v7.3.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                        <path fill="rgb(255, 255, 255)" d="M61.4 64C27.5 64 0 91.5 0 125.4 0 126.3 0 127.1 .1 128L0 128 0 384c0 35.3 28.7 64 64 64l384 0c35.3 0 64-28.7 64-64l0-256-.1 0c0-.9 .1-1.7 .1-2.6 0-33.9-27.5-61.4-61.4-61.4L61.4 64zM464 192.3L464 384c0 8.8-7.2 16-16 16L64 400c-8.8 0-16-7.2-16-16l0-191.7 154.8 117.4c31.4 23.9 74.9 23.9 106.4 0L464 192.3zM48 125.4C48 118 54 112 61.4 112l389.2 0c7.4 0 13.4 6 13.4 13.4 0 4.2-2 8.2-5.3 10.7L280.2 271.5c-14.3 10.8-34.1 10.8-48.4 0L53.3 136.1c-3.3-2.5-5.3-6.5-5.3-10.7z" />
                    </svg>
                    cortexdataid@gmail.com
                </a>
                <a class="flex gap-2" href="https://www.instagram.com/ctx_corp/" target="_blank" rel="noopener noreferrer">
                    <svg xmlns="http://www.w3.org/2000/svg" height="20" width="17.5" viewBox="0 0 448 512"><!--!Font Awesome Free v7.3.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                        <path fill="rgb(255, 255, 255)" d="M224.3 141a115 115 0 1 0 -.6 230 115 115 0 1 0 .6-230zm-.6 40.4a74.6 74.6 0 1 1 .6 149.2 74.6 74.6 0 1 1 -.6-149.2zm93.4-45.1a26.8 26.8 0 1 1 53.6 0 26.8 26.8 0 1 1 -53.6 0zm129.7 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM399 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z" />
                    </svg>
                    @ctx_corp ↗
                </a>
                <a class="flex gap-2" href="https://wa.me/6281211337721?text=Hello%20Cortex%20Data" target="_blank" rel="noopener noreferrer">
                    <svg xmlns="http://www.w3.org/2000/svg" height="20" width="17.5" viewBox="0 0 448 512"><!--!Font Awesome Free v7.3.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                        <path fill="rgb(255, 255, 255)" d="M380.9 97.1c-41.9-42-97.7-65.1-157-65.1-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480 117.7 449.1c32.4 17.7 68.9 27 106.1 27l.1 0c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3 18.6-68.1-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1s56.2 81.2 56.1 130.5c0 101.8-84.9 184.6-186.6 184.6zM325.1 300.5c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8s-14.3 18-17.6 21.8c-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7 .9-6.9-.5-9.7s-12.5-30.1-17.1-41.2c-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2s-9.7 1.4-14.8 6.9c-5.1 5.6-19.4 19-19.4 46.3s19.9 53.7 22.6 57.4c2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4s4.6-24.1 3.2-26.4c-1.3-2.5-5-3.9-10.5-6.6z" />
                    </svg>
                    WhatsApp ↗
                </a>
            </div>
        </div>
        <div class="copyright flex justify-center">Copyright © {{ date('Y') }} Cortex Data Indonesia.</div>
    </footer>
    <a class="floating-contact" href="https://wa.me/6281211337721?text=Hello%20Cortex%20Data" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp Cortex">
        <svg xmlns="http://www.w3.org/2000/svg" height="20" width="17.5" viewBox="0 0 448 512"><!--!Font Awesome Free v7.3.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
            <path fill="rgb(255, 255, 255)" d="M380.9 97.1c-41.9-42-97.7-65.1-157-65.1-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480 117.7 449.1c32.4 17.7 68.9 27 106.1 27l.1 0c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3 18.6-68.1-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1s56.2 81.2 56.1 130.5c0 101.8-84.9 184.6-186.6 184.6zM325.1 300.5c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8s-14.3 18-17.6 21.8c-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7 .9-6.9-.5-9.7s-12.5-30.1-17.1-41.2c-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2s-9.7 1.4-14.8 6.9c-5.1 5.6-19.4 19-19.4 46.3s19.9 53.7 22.6 57.4c2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4s4.6-24.1 3.2-26.4c-1.3-2.5-5-3.9-10.5-6.6z" />
        </svg>
    </a>
</body>

</html>