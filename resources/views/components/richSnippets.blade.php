<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "BreadcrumbList",
        "itemListElement": [
        @foreach($breadcrumbList as $breadcrumbItem)
            {
                "@type": "ListItem",
                "position": {{$loop->iteration}},
                "name": "{{ $breadcrumbItem->name }}",
                "item": "{{ $breadcrumbItem->route }}"
            }@if(!$loop->last),@endif
        @endforeach
        ]
    }
</script>
<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "name" : "{{ config('site.company.name') }}",
        "url": "{{ request()->root() }}"
    }
</script>