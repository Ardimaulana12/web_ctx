@extends('layout')
@section('content')
<section class="page-heading page-heading-centered section-shell"><p class="eyebrow">{{ __('Perspectives & research') }}</p><h1>{{ __('Explore our') }} <span>{{ __('latest insights.') }}</span></h1><p>{{ __('Stories behind the data. Perspectives on society, markets, and human behavior.') }}</p></section>
<section class="section-shell blog-section grid grid-cols-1 gap-10 pb-24">@foreach($sections['list_1'] as $post)
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
