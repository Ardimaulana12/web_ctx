@extends('layout')
@section('title', __('Case Study — CTX Corp'))
@section('content')
<section class="page-heading page-heading-centered section-shell">
    <p class="eyebrow">{{ __('Our work') }}</p>
    <h1>{{ __('Data with') }} <span>{{ __('real impact.') }}</span></h1>
    <p>{{ __('At Cortex, we don’t just analyze, we create solutions. We uncover the stories behind the findings and show how data drives meaningful transformation.') }}</p>
</section>
<section class="section-shell grid md:grid-cols-2 gap-10 pb-24">@foreach([
    ['Consumer Spending Trends','This research aims to explore in depth the allocation of household expenditures on primary, secondary, and tertiary needs.','home1b.jpg'],
    ['The Impact of Live Shopping on Gen Z’s Sleep Patterns','How late-night live shopping on TikTok and Shopee influences Gen Z’s sleep quality, mental health, and impulsive spending habits.','home1c.jpg'],
    ] as $case)<article class="case-card"><img src="/images/{{ $case[2] }}" alt="" loading="lazy">
        <div class="p-8">
            <p class="eyebrow">{{ __('Internal research') }}</p>
            <h2>{{ __($case[0]) }}</h2>
            <p class="my-5">{{ __($case[1]) }}</p><a class="text-link" href="{{ route('contact', ['subject'=>$case[0]]) }}">{{ __('Request case study ↗') }}</a>
        </div>
    </article>@endforeach</section>
@endsection