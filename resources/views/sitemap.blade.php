<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
    <!-- created with Free Online Sitemap Generator www.xml-sitemaps.com -->


    <url>
        <loc>{{ route('home') }}</loc>
        <lastmod>2022-10-11T10:15:38+00:00</lastmod>
        <priority>1.00</priority>
    </url>
    <url>
        <loc>{{ route('site.about') }}</loc>
        <lastmod>2022-10-11T10:15:38+00:00</lastmod>
        <priority>0.80</priority>
    </url>
    <url>
        <loc>{{ route('site.contact') }}</loc>
        <lastmod>2022-10-11T10:15:38+00:00</lastmod>
        <priority>0.80</priority>
    </url>
    <url>
        <loc>{{ url('/') }}/category/latest</loc>
        <lastmod>2022-10-11T10:15:38+00:00</lastmod>
        <priority>0.80</priority>
    </url>
    <url>
        <loc>{{ url('/') }}/category/popular</loc>
        <lastmod>2022-10-11T10:15:38+00:00</lastmod>
        <priority>0.80</priority>
    </url>
    <url>
        <loc>{{ route('site.privacyPolicy') }}</loc>
        <lastmod>2022-10-11T10:15:38+00:00</lastmod>
        <priority>0.60</priority>
    </url>

    @if (isset($categories) && !empty($categories) && count($categories) > 0)
        @foreach ($categories as $category)
            <url>
                <loc>{{ route('site.getCategoryView', $category->slug) }}</loc>
                <lastmod>{{ gmdate('Y-m-d\TH:i:s\Z', strtotime($category->updated_at)) }}</lastmod>
                <priority>0.80</priority>
            </url>
        @endforeach
    @endif

    @if (isset($polls) && !empty($polls) && count($polls) > 0)
        @foreach ($polls as $poll)
            <url>
                <loc>{{ route('poll.view', $poll->slug) }}</loc>
                <lastmod>{{ gmdate('Y-m-d\TH:i:s\Z', strtotime($poll->updated_at)) }}</lastmod>
                <priority>0.80</priority>
            </url>

            <url>
                <loc>{{ route('poll.viewResults', $poll->slug) }}</loc>
                <lastmod>{{ gmdate('Y-m-d\TH:i:s\Z', strtotime($poll->updated_at)) }}</lastmod>
                <priority>0.64</priority>
            </url>
        @endforeach
    @endif

</urlset>
