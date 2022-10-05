@if(isset($links) && isset($links->privacy))

    @include('components.cookie.cookieBar')

@else
    {{ var_dump("Cookies: Links class or Page with code_name 'privacy' not found") }}
@endif