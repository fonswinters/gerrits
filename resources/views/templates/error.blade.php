@inject( 'response', 'Illuminate\Http\Response' )

@extends('master')

@section('title', $exception->getStatusCode() . ' | '. $page->translation->meta_title .' | '. config('site.company_name'))

@section('content')

    <section class="error-page-message">
        <div class="l-contain">
            <div class="kms-content">

                @if(!empty($exception->getMessage()))

                    <h1>Foutcode {{ $exception->getStatusCode() }}</h1>
                    {{ $exception->getMessage() }}

                @elseif(\Lang::has('errors.'.$exception->getStatusCode()))

                    <h1>Foutcode {{ $exception->getStatusCode() }}</h1>
                    <p>@lang('errors.'.$exception->getStatusCode())</p>

                @else

                    <h1>Foutcode {{ $exception->getStatusCode() }}</h1>
                    <p>@lang('errors.default')</p>

                @endif

            </div>
        </div>
    </section>

@endsection