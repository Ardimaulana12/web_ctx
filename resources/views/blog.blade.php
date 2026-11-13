@extends('layout')
@section('title', __('Blog — CTX Corp'))
@section('content')
<section class="page-heading page-heading-centered section-shell"><p class="eyebrow">{{ __('Perspectives & research') }}</p><h1>{{ __('Explore our') }} <span>{{ __('latest insights.') }}</span></h1><p>{{ __('Stories behind the data. Perspectives on society, markets, and human behavior.') }}</p></section>
<section class="section-shell blog-section grid grid-cols-1 gap-10 pb-24">@foreach([
    ['Sibuk Scroll, Lupa Tidur: Dampak Live Shopping bagi Kesehatan Gen Z','October 28, 2025','sibuk-scroll-lupa-tidur-dampak-live-shopping-bagi-kesehatan-gen-z','sibuk-scroll-lupa-tidur-pdf-cover-c.jpg','How late-night live shopping on TikTok and Shopee influences Gen Z’s sleep quality, mental health, and impulsive spending habits.','2025-10-28'],
    ['Tren Pengeluaran Konsumen Indonesia 2025: Adaptasi di Tengah Dinamika Ekonomi dan Pergeseran Gaya Hidup','September 17, 2025','tren-pengeluaran-konsumen-indonesia-2025-adaptasi-di-tengah-dinamika-ekonomi-dan-pergeseran-gaya-hidup','tren-pengeluaran.png','This research aims to explore in depth the allocation of household expenditures on primary, secondary, and tertiary needs.','2025-09-17']
    ] as $post)
    <article class="case-card">
        <img src="/images/{{ $post[3] }}" alt="{{ __($post[0]) }}" loading="lazy" width="547" height="745">
        <div class="p-8">
            <p class="eyebrow"><time datetime="{{ $post[5] }}">{{ __($post[1]) }}</time></p>
            <h2><a href="https://www.ctx.co.id/{{ $post[2] }}" target="_blank" rel="noopener">{{ __($post[0]) }}</a></h2>
            <p class="my-5">{{ __($post[4]) }}</p>
            <a class="text-link" href="https://www.ctx.co.id/{{ $post[2] }}" target="_blank" rel="noopener">{{ __('Read full story on CTX ↗') }}</a>
        </div>
    </article>@endforeach
</section>
@endsection
