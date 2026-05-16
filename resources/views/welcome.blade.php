<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-100 flex justify-center items-center h-screen m-0 font-sans">
        <div class="text-center">
            <h1 class="text-5xl font-bold text-gray-800 mb-4">Laravel + Tailwind CSS v4</h1>
            <p class="text-gray-600 text-lg">Your fresh installation is ready with Tailwind CSS v4 successfully installed!</p>
        </div>
    </body>
</html>

