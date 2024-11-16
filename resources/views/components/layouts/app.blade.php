<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>{{ $title ?? 'Page Title' }}</title>
  @vite(['resources/css/app.css'])
</head>

<body class="mx-auto mt-4 max-w-lg">
  <ul class="flex items-center gap-2 underline">
    <li>
      <a href="/">Dashboard</a>
    </li>
    <li>
      <a href="/categories">Categories</a>
    </li>
    <li>
      <a href="/products">Products</a>
    </li>
  </ul>

  <main class="overflow-x-hidden">
    {{ $slot }}
  </main>
  @vite(['resources/js/app.js'])
</body>

</html>
