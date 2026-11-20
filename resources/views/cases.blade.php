@extends('layout')
@section('content')
<section class="page-heading page-heading-centered section-shell">
    <p class="eyebrow">{{ __('Our work') }}</p>
    <h1>{{ __('Data with') }}
        <span>{{ __('real impact.') }}</span>
    </h1>
    <p>{{ __('At Cortex, we don’t just analyze, we create solutions. We uncover the stories behind the findings and show how data drives meaningful transformation.') }}</p>
</section>
<section class="section-shell case-study-section pb-24" aria-labelledby="research-heading">
    <div class="section-heading">
        <h2 id="research-heading" class="section-title">{{ __('Research and Analysis') }}</h2>
    </div>
    <div class="grid grid-cols-1 gap-10">@foreach($sections['list_1'] as $case)
        <article class="case-card">
            <img src="/images/{{ $case[2] }}" alt="{{ __($case[0]) }}" loading="lazy">
            <div class="p-8">
                <p class="eyebrow">{{ __('Internal research') }}</p>
                <h3>{{ __($case[0]) }}</h3>
                <p class="my-5">{{ __($case[1]) }}</p><a class="text-link" href="{{ route('contact', ['subject'=>$case[0]]) }}">{{ __('Request case study ↗') }}</a>
            </div>
        </article>@endforeach
    </div>
</section>
<section class="section-shell case-study-section pb-24" aria-labelledby="dashboard-heading">
    <div class="section-heading">
        <h2 id="dashboard-heading" class="section-title">{{ __('Dashboard') }}</h2>
    </div>
    <div class="grid grid-cols-1 gap-10">@foreach($sections['list_2'] as $case)
        <article class="case-card">
            <img src="/images/{{ $case[2] }}" alt="{{ __($case[0]) }}" loading="lazy">
            <div class="p-8">
                <p class="eyebrow">{{ __('Dashboard') }}</p>
                <h3>{{ __($case[0]) }}</h3>
                <p class="my-5">{{ __($case[1]) }}</p><a class="text-link" href="{{ route('contact', ['subject'=>$case[0]]) }}">{{ __('Request case study ↗') }}</a>
            </div>
        </article>@endforeach
    </div>
</section>
@endsection
