<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-300">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Role Base Auth - {{ $title ?? 'website' }}</title>
        <!-- OR -->
        <!-- <title>Flick - @yield('title')</title> -->

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </head>
    <body class="h-full font-sans antialiase bg-slate-300">
      <x-nav/>

      <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold text-center p-4 text-gray-800">{{ $heading }}</h1>

      {{ $slot }}
      {{ $script ?? '' }}

    </body>
</html>
