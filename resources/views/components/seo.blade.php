{{-- SEO Meta Tags Component --}}
@props([
    'title' => 'Mechanic Africa - Professional Car Maintenance & Oil Change Services in Nigeria',
    'description' => 'Expert car maintenance, oil change, and vehicle servicing across Nigeria. Certified mechanics, genuine parts, transparent pricing. Book your 4, 6, or 8-cylinder engine service today from ₦60,000.',
    'keywords' => 'car maintenance Nigeria, oil change Lagos, vehicle servicing Abuja, auto mechanic Nigeria, car repair services, engine oil change, certified mechanics, genuine auto parts, car service package, mobile mechanic, auto workshop Nigeria',
    'image' => asset('images/mechanic-working-on-vehicle.jpg'),
    'type' => 'website',
    'author' => 'Mechanic Africa',
    'url' => url()->current(),
    'siteName' => 'Mechanic Africa',
    'locale' => 'en_NG',
    'twitterHandle' => '@MechanicAfrica',
    'publishedTime' => null,
    'modifiedTime' => null,
    'schema' => null
])

{{-- Primary Meta Tags --}}
<title>{{ $title }}</title>
<meta name="title" content="{{ $title }}">
<meta name="description" content="{{ $description }}">
<meta name="keywords" content="{{ $keywords }}">
<meta name="author" content="{{ $author }}">
<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
<meta name="googlebot" content="index, follow">
<meta name="bingbot" content="index, follow">

{{-- Canonical URL --}}
<link rel="canonical" href="{{ $url }}">

{{-- Alternate/Hreflang Tags for International SEO --}}
<link rel="alternate" href="{{ $url }}" hreflang="en-ng" />
<link rel="alternate" href="{{ $url }}" hreflang="en" />
<link rel="alternate" href="{{ $url }}" hreflang="x-default" />

{{-- Open Graph / Facebook --}}
<meta property="og:type" content="{{ $type }}">
<meta property="og:url" content="{{ $url }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:image" content="{{ $image }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:locale" content="{{ $locale }}">
@if($publishedTime)
<meta property="article:published_time" content="{{ $publishedTime }}">
@endif
@if($modifiedTime)
<meta property="article:modified_time" content="{{ $modifiedTime }}">
@endif

{{-- Twitter Card --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="{{ $url }}">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $image }}">
<meta name="twitter:site" content="{{ $twitterHandle }}">
<meta name="twitter:creator" content="{{ $twitterHandle }}">

{{-- Additional SEO Meta --}}
<meta name="rating" content="General">
<meta name="distribution" content="global">
<meta name="revisit-after" content="7 days">
<meta name="language" content="English">
<meta name="geo.region" content="NG">
<meta name="geo.placename" content="Nigeria">
<meta name="format-detection" content="telephone=yes">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="{{ $siteName }}">

{{-- Security Headers --}}
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

{{-- Preload Critical Resources --}}
<link rel="preload" as="image" href="{{ $image }}">

{{-- Schema.org Structured Data --}}
@if($schema)
{!! $schema !!}
@endif
