<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ env('APP_NAME', 'Laravel') }}</title>

  <!-- FONTS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

  <!-- Vite -->
  @vite(['resources/js/app.js'])
  @yield('scripts')
</head>

<body>
  <header>
    <!-- NAVBAR -->
    @include('layouts/partials/navbar')
  </header>
  
  <main>
    <!-- JUMBOTRON -->
    <div class="container">
      @if (session('danger'))
        <div class="alert alert-danger my-3">
              {{ session('danger') }}
        </div>
      @elseif (session('message'))
        <div class="alert alert-success my-3">
          {{ session('message') }}
        </div>
      @endif
    <div class="container">
      <div>
        <h3 class="mt-3">
            @yield('title')
        </h3>
    </div>
    </div>

    <div class="container">
      @yield('card')
    </div>

    <div class="py-3">
      @yield('content')
    </div>
  </main>

  @yield('modals')
</body>

</html>
