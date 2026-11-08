<section id="services" class="services-section section-space"><div class="section-shell">
    <div class="section-heading"><p class="eyebrow">{{ __('What we do') }}</p><h2 class="section-title">{{ __('Research solutions, built around you.') }}</h2><p>{{ __('From understanding your market to turning complex data into clear decisions.') }}</p></div>
    <div class="services-grid">
        @foreach(config('ctx.services') as $service)
        <article class="service-item" id="service-{{ $loop->iteration }}"><div class="service-icon">@include('partials.service-icon', ['index' => $loop->index])</div>
            <div>
                <h3>{{ __($service[0]) }}</h3>
                <p class="service-subtitle">({{ __($service[1]) }})</p>
                <p>{{ __($service[2]) }}</p>
                <a class="text-link service-link" href="{{ route('contact', ['subject' => __($service[0])]) }}">{{ __('Discuss your project') }} <span aria-hidden="true">→</span></a>
            </div>
        </article>
        @endforeach
    </div>
</div></section>
