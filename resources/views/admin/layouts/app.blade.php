<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Solibra - Perpustakaan Umum Kota Solok</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>

<body class="bg-slate-200">
    <div class="flex flex-col">
        <x-middle />
    </div>

    {{-- JS --}}
    <script src="{{ asset('js/dashboard.js') }}"></script>
    <script src="{{ asset('js/book.js') }}"></script>
</body>

</html>
