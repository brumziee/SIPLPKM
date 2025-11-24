<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>SIPLPKM</title>

    <!-- Tailwind CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Script atau style tambahan -->
    @include('includes.style')
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    @yield('content')

    @include('includes.script')
</body>

</html>
