<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} - @yield('title')</title>

    <link rel="icon" href="/innova-food.ico" />

    <script src="{{ asset('js/tailwind-3_4_16.js') }}"></script>
    <script src="{{ asset('js/iconify.min.js') }}"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#4d4341',
                            hover: '#3a3230'
                        }
                    }
                }
            }
        }
    </script>
</head>

<body class="font-sans min-h-screen bg-[#f1f1f1] flex flex-col items-center justify-center p-5 text-gray-800">

    <main class="w-full max-w-5xl">
        @yield('content')
    </main>

</body>

</html>