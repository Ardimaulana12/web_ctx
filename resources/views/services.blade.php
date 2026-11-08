@extends('layout')
@section('title', __('Products & Services — CTX Corp'))
@section('content')
<section class="page-heading page-heading-centered section-shell"><p class="eyebrow">{{ __('Research. Intelligence. Impact.') }}</p><h1>{{ __('Better insights.') }}<br><span>{{ __('Smarter decisions.') }}</span></h1><p>{{ __('Social intelligence and research solutions to understand your market, your community, and your next opportunity.') }}</p></section>
@include('partials.services')
<section class="cta section-shell"><h2>{{ __('Find the right solution for your organization.') }}</h2><a href="/contact" class="button button-blue">{{ __('Contact us ↗') }}</a></section>
@endsection
