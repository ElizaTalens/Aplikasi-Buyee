<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Buyee Shop</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
  @vite(['resources/css/app.css','resources/js/app.js'])
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-50 font-[Inter] text-gray-800 antialiased pt-25">

  @include('layouts.navbar')

  <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-12 py-6">
    @include('layouts.hero')                 {{-- banner iPhone 14 Pro --}}
    @include('layouts.category-row')         {{-- Browse by Category --}}
    @include('layouts.product-grid-tabs')    {{-- New Arrival grid + tabs --}}
  </main>

  @include('layouts.footer')

</body>
</html>
