<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Guest Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css') {{-- atau link ke CSS-mu --}}
    @livewireStyles
</head>
<body class="min-h-screen bg-gray-50 text-gray-900">

    <div class="container mx-auto py-10">
        {{ $slot }}
    </div>

    @livewireScripts
</body>
</html>
