<meta property="og:title" content="@yield('title',config('app.url'))" />
<meta property="og:type" content="website" />
<meta property="og:url" content="{{ request()->root() . request()->server("PATH_INFO", '') }}" />
<meta property="og:image" content="{{ url('/img/open-graph-image.jpg') }}" />