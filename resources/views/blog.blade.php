@extends('layout')
@section('title', __('Blog — CTX Corp'))
@section('content')
<section class="page-heading page-heading-centered section-shell"><p class="eyebrow">{{ __('Perspectives & research') }}</p><h1>{{ __('Explore our') }} <span>{{ __('latest insights.') }}</span></h1><p>{{ __('Stories behind the data. Perspectives on society, markets, and human behavior.') }}</p></section>
<section class="section-shell grid md:grid-cols-2 gap-10 pb-24">@foreach([
['Sibuk Scroll, Lupa Tidur: Dampak Live Shopping bagi Kesehatan Gen Z','October 28, 2025','sibuk-scroll-lupa-tidur-dampak-live-shopping-bagi-kesehatan-gen-z','home1c.jpg'],
['Tren Pengeluaran Konsumen Indonesia 2025: Adaptasi di Tengah Dinamika Ekonomi dan Pergeseran Gaya Hidup','September 17, 2025','tren-pengeluaran-konsumen-indonesia-2025-adaptasi-di-tengah-dinamika-ekonomi-dan-pergeseran-gaya-hidup','home1b.jpg']
] as $post)<article class="case-card"><img src="/images/{{ $post[3] }}" alt="" loading="lazy"><div class="p-8"><p class="eyebrow">{{ __($post[1]) }}</p><h2>{{ __($post[0]) }}</h2><a class="text-link mt-7 inline-block" href="https://www.ctx.co.id/{{ $post[2] }}" target="_blank" rel="noopener">{{ __('Read full story on CTX ↗') }}</a></div></article>@endforeach</section>
@endsection
