@extends('master')

@section('title', 'Sitemap | '. config('site.company_name'))

@section('content')

    @include('components.pageTitle', [
        'heading' => 'Sitemap'
    ])

    <nav class="l-contain">
        <ul class="c-sitemap  o-block">

            @php
                $pages = new \Illuminate\Database\Eloquent\Collection($page->findChildren());
                $pages = $pages->load('translation', 'translation.route');
            @endphp

            {{-- Generate all sitemap loop for find children except home --}}
            @foreach($pages as $child)
                {{--@unless($child->code_name == 'home')--}}
                @include('components.sitemapLoop', ['sitemapItem' => $child])
                {{--@endunless--}}
            @endforeach

        </ul>
    </nav>

    @include('organisms.calloutBar', [
        'heading' => config('site.global_CTA_heading'),
        'button' => App\Buttons\Models\Button::where('id', config('site.global_CTA_button_id'))->with('translations')->first(),
        'servicePoint' => App\Servicepoints\Models\Servicepoint::where('id', config('site.global_servicePoint_id'))->with('translations', 'documents')->first()
    ])


@endsection