<section class="process-section section-space"><div class="section-shell">
    <div class="section-heading"><p class="eyebrow">{{ __('A clear path forward') }}</p><h2 class="section-title">{{ __('From questions to confident decisions.') }}</h2><p>{{ __('A collaborative approach, with clarity at every step.') }}</p></div>
    <div class="process-grid">@foreach($shared['process'] as $step)<article class="process-step"><span class="process-number">{{ sprintf('%02d', $loop->iteration) }}</span><h3>{{ __($step[0]) }}</h3><p>{{ __($step[1]) }}</p></article>@endforeach</div>
</div></section>
