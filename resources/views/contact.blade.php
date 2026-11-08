@extends('layout')
@section('title', __('Contact Us — CTX Corp'))
@section('content')
<section class="section-shell section-space grid gap-14 lg:grid-cols-2">
    <div class="page-heading !p-0">
        <p class="eyebrow">{{ __('Get in touch') }}</p>
        <h1>{{ __('Let’s make') }}<br><span>{{ __('better decisions.') }}</span></h1>
        <p>{{ __('Tell us about your research needs. Start a conversation with our team.') }}</p>
        <div class="mt-10">
            <h3>{{ __('Visit our office') }}</h3>
            <p>Graha Surveyor Indonesia, Lt 15 Unit 1503<br>Jl. Jend. Gatot Subroto, Kav 56<br>Jakarta Selatan, DKI Jakarta 12950</p><a class="text-link" href="mailto:cortexdataid@gmail.com">cortexdataid@gmail.com</a>
        </div>
    </div>
    <form method="POST" action="{{ route('contact.store', [], false) }}" class="contact-form" data-contact-form>@csrf
        @if(session('success'))<div role="status" class="success-message">{{ __(session('success')) }}</div>@endif
        @if(session('warning'))<div role="alert" class="mb-6 rounded-lg border border-amber-400 bg-amber-50 p-4 text-amber-950">{{ __(session('warning')) }}</div>@endif
        <div data-error-summary role="alert" tabindex="-1" class="error-message" @if(!$errors->any()) hidden @endif>
            <ul class="mt-2 list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
        <!-- <p class="mb-5 text-sm">{{ __('Fields marked * are required. Company / organization is optional.') }}</p> -->
        @foreach(['name'=>'Full name','email'=>'Email address','company'=>'Company / organization','subject'=>'Subject','message'=>'Message'] as $field=>$label)
        @php
        $minimum = ['name'=>2, 'subject'=>3, 'message'=>10][$field] ?? null;
        $maximum = ['subject'=>200, 'message'=>5000][$field] ?? 150;
        @endphp
        <div class="mb-5">
            <label for="{{ $field }}">{{ __($label) }} @if($field!=='company')<span aria-hidden="true">*</span>@endif</label>
            @if($field==='message')
            <textarea id="message" name="message" rows="5"
                @else
                <input id="{{ $field }}" name="{{ $field }}" type="{{ $field==='email' ? 'email' : 'text' }}" value="{{ old($field, $field==='subject' ? request('subject') : '') }}"
                @endif
                class="!mb-1" maxlength="{{ $maximum }}" @if($minimum) minlength="{{ $minimum }}" @endif
                @required($field!=='company' ) aria-describedby="{{ $field }}-error" aria-invalid="{{ $errors->has($field) ? 'true' : 'false' }}"
                data-required="{{ __(':field is required.', ['field'=>__($label)]) }}"
                data-format="{{ __('Enter a valid email address, for example name@example.com.') }}"
                data-min="{{ __(':field must contain at least :min characters.', ['field'=>__($label), 'min'=>$minimum]) }}"
                data-max="{{ __(':field must not exceed :max characters.', ['field'=>__($label), 'max'=>$maximum]) }}"
                @if($field==='message' )>{{ old('message') }}</textarea>@else>@endif
            <p id="{{ $field }}-error" class="text-sm !text-red-700" @if(!$errors->has($field)) hidden @endif>{{ $errors->first($field) }}</p>
        </div>
        @endforeach
        <button class="button button-blue w-full disabled:opacity-60" type="submit" data-sending="{{ __('Sending...') }}">{{ __('Send message') }} <span aria-hidden="true"></span></button>
    </form>
</section>
@endsection