@extends('layout')
@section('content')
<section class="hero research-hero">
    <img class="hero-background" src="/images/research-hero.jpg" alt="" fetchpriority="high">
    <div class="hero-content">
        <p class="hero-badge"><span aria-hidden="true">✦</span> {{ __('Social intelligence. Meaningful impact.') }}</p>
        <h1>{{ __('Understand your world.') }}<br><span>{{ __('Decide with confidence.') }}</span></h1>
        <p class="hero-description">{{ __('We turn research and complex data into clear, actionable insights. Understand society, markets, and people — and move forward with confidence.') }}</p>
        <div class="hero-actions"><a class="button button-blue" href="{{ route('contact') }}">{{ __('Start a conversation') }} <span aria-hidden="true">→</span></a><a class="button button-outline" href="{{ route('services') }}">{{ __('Explore our services') }}</a></div>
        <div class="hero-topics"><span>{{ __('Research') }}</span><i></i><span>{{ __('Data Analytics') }}</span><i></i><span>{{ __('Social Intelligence') }}</span></div>
    </div>
</section>
@include('partials.services')
<section id="about" class="section-shell section-space text-center home-profile">
    <h2 class="section-title">{{ __('AT') }}
        <span>CORTEX</span>
        {{ __(', WE PROVIDE TRUE') }}
        <br class="hidden md:block"> {{ __('UNDERSTANDING ABOUT') }}
    </h2>
    <p class="intro-copy">{{ __('Society, Market, People, to help our clients make smarter, faster, optimized and better decision, monitoring and evaluation.') }}</p>
    <a class="text-link inline-block mt-6" href="{{ route('about') }}">{{ __('Company Profile') }} ↗</a>
    <div class="grid gap-5 md:grid-cols-3 mt-16">
        @foreach(['Society', 'Market', 'People'] as $topic)
        <figure class="topic-card">
            <img src="/images/home1{{ ['a','b','c'][$loop->index] }}.jpg" alt="{{ __($topic) }}" loading="lazy">
            <!-- <figcaption>{{ __($topic) }}</figcaption> -->
        </figure>
        @endforeach
    </div>
</section>
<section class="figures">
    <div class="section-shell">
        <div class="figures-intro">
            <div>
                <p class="eyebrow">{{ __('Our impact') }}</p>
                <h2 class="section-title">{{ __('Key Figure') }}</h2>
            </div>
            <p class="figures-quote">{{ __('“We are augmenting Social Intelligence for smarter, faster, cheaper and optimized decision, monitoring and evaluation.”') }}</p>
        </div>
        <div class="figures-panel">@foreach(['30'=>'Province coverage','6'=>'Product core & service','12'=>'Clients served'] as $number=>$label)
            <div class="figure-cell"><strong class="stat"><span class="sr-only">{{ $number }}</span><span class="counter" data-count="{{ $number }}" aria-hidden="true">{{ $number }}</span></strong>
                <p class="stat-label">{{ __($label) }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
<section class="section-shell py-14 client-section">
    <p class="eyebrow text-center">{{ __('Organizations we have worked with') }}</p><img src="/images/clients.jpg" alt="{{ __('Organizations and clients served by Cortex') }}" class="client-logos" loading="lazy">
</section>
<section class="section-shell section-space">
    <h2 class="section-title text-center">{{ __('OUR') }} <span>{{ __('VALUES') }}</span></h2>
    <div class="grid gap-8 md:grid-cols-3 mt-14">
        @foreach([
        ['CURIOSITY','We are curious about the social interaction and world around us by asking the right questions. We seek the new and unexpected variables that greatly impact any decisions.'],
        ['ENTREPRENEURIAL','We are collectively and socially smarter than any of us individually. We work in a diverse culture and encourage individuals to try different and innovative approaches.'],
        ['CLIENT FIRST','We put clients first, always focusing on long-term partnerships. We are responsible for providing our clients with the optimum solution to solve problems and achieve objectives.']
        ] as $value)
        <article class="value-card">
            <img src="/images/mid{{ $loop->iteration }}.jpg" alt="" loading="lazy">
            <h3>{{ __($value[0]) }}</h3>
            <p>{{ __($value[1]) }}</p>
        </article>@endforeach
    </div>
</section>
@include('partials.process')
<section class="quality section-space">
    <div class="section-shell">
        <p class="eyebrow text-center">{{ __('Our dedication for quality assurance') }}</p>
        <h2 class="section-title text-center">{{ __('QUALITY') }} <span>{{ __('ASSURANCE') }}</span></h2>
        <div class="grid gap-10 md:grid-cols-3 mt-14">@foreach([
            ['SOCIAL RESEARCH','2 centres of excellence','Data and insight coordination and verification in Jakarta (Research) and Banten (Field Services & Engineering). Fulltime research scientists, data auditors and field operations.'],
            ['DIGITAL TECHNOLOGY','Latest digital technology','Supported by multi-cloud providers to accelerate delivery and support operational efficiency. Fulltime data engineering and business intelligence.'],
            ['EXPERT TEAM','More than 20 years of experience','Reputable experts in social intelligence research and activations, with dedicated expertise in social and market research, data science and engineering.']
            ] as $item)<article class="quality-card">
                <div class="quality-icon">{{ ['◎','⌘','◇'][$loop->index] }}</div>
                <h3>{{ __($item[0]) }}</h3>
                <h4 class="font-semibold my-3">{{ __($item[1]) }}</h4>
                <p>{{ __($item[2]) }}</p>
            </article>@endforeach</div>
    </div>
</section>
<section class="section-shell section-space technology-section">
    <div class="technology-heading">
        <p class="eyebrow">{{ __('Powered by insight') }}</p>
        <h2 class="section-title">{{ __('Better research. Smarter technology.') }}</h2>
    </div>
    <div class="technology-layout"><img src="/images/technology.jpg" alt="{{ __('Technology supporting social intelligence') }}" class="rounded-3xl w-full" loading="lazy">
        <div>@foreach(['MEASURE SOCIALLY'=>'Web and social listening, community observations, behavioural economics, ethnography, consumer & retail analytics, media sentiment monitoring, targeted audience analytics.','COLLECT REAL TIME'=>'Mobile, digital services overnight, qualitative feedback management (EFM).','LARGE PROCESSING'=>'Cloud-based data streamlining, data analytics and data science.'] as $title=>$description)<div class="technology-item">
                <h3>{{ __($title) }}</h3>
                <p>{{ __($description) }}</p>
            </div>@endforeach</div>
    </div>
</section>
<div class="quote-band">
    <p>{{ __('“Through a range of specialized services, our teams provide accurate and relevant information that will help organizations make smarter decisions, faster, optimized and targeted across specific market profiles.”') }}</p>
</div>

@include('partials.leadership')
<section class="cta section-shell">
    <div>
        <p class="eyebrow">{{ __('Think clearly. Decide confidently. Act precisely.') }}</p>
        <h2>{{ __('Turn your questions into insight.') }}</h2>
    </div><a href="/contact" class="button button-blue">{{ __('Let\'s talk') }} <span>↗</span></a>
</section>
@endsection