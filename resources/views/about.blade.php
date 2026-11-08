@extends('layout')
@section('title', __('Company Profile').' — CTX Corp')
@section('content')
<section class="page-heading page-heading-centered section-shell">
    <p class="eyebrow">{{ __('Company Profile') }}</p>
    <h1>{{ __('Understanding people.') }}<br><span>{{ __('Empowering decisions.') }}</span></h1>
    <p class="">{{ __('Cortex Data Indonesia is a social intelligence analytics company helping organizations understand society, markets, and human behavior.') }}</p>
</section>
<section class="section-shell grid gap-12 lg:grid-cols-2 items-center pb-20"><img src="/images/home1a.jpg" alt="{{ __('Society') }}" class="about-image">
    <div>
        <p class="eyebrow">{{ __('Who we are') }}</p>
        <h2 class="section-title">{{ __('Insight that makes a difference.') }}</h2>
        <p class="mt-6">{{ __('We connect social research, field expertise, and data analytics to provide accurate and relevant information for better decisions, monitoring, and evaluation.') }}</p>
        <p class="mt-4">{{ __('Our work spans market research, community impact studies, data insight solutions, field surveys, focus group discussions, and training programs.') }}</p><a href="{{ route('services') }}" class="text-link inline-block mt-6">{{ __('Explore our services') }} ↗</a>
    </div>
</section>
<section class="quality section-space">
    <div class="section-shell grid gap-12 md:grid-cols-3">@foreach([
        ['Our approach', 'We start with the right questions, listen to people, and turn evidence into insights that organizations can act on.'],
        ['Our expertise', 'Our team brings together social and market research, data science, engineering, and field operations.'],
        ['Our commitment', 'We put clients first, uphold research quality, and build long-term partnerships through responsible and relevant solutions.']
        ] as $item)<article>
            <p class="eyebrow">{{ sprintf('%02d', $loop->iteration) }}</p>
            <h3>{{ __($item[0]) }}</h3>
            <p class="mt-4">{{ __($item[1]) }}</p>
        </article>@endforeach</div>
</section>
@include('partials.leadership')
<section class="cta section-shell">
    <div>
        <p class="eyebrow">{{ __('Think clearly. Decide confidently. Act precisely.') }}</p>
        <h2>{{ __('Let’s build your next insight together.') }}</h2>
    </div><a href="{{ route('contact') }}" class="button button-blue">{{ __('Contact us') }} ↗</a>
</section>
@endsection