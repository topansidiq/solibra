<div class="flex flex-col bg-teal-950 w-80 h-screen" x-data="{ active: '{{ Route::currentRouteName() }}' }">

    <div class="flex items-center space-x-3 p-4">
        <i data-lucide="library" class="w-8 h-8 text-teal-400"></i>

        <div class="logo leading-tight">
            <p class="font-extrabold font-serif text-teal-400 text-lg">SOLIBRA</p>
            <p class="text-xs text-slate-300">Perpustakaan Umum Kota Solok</p>
        </div>
    </div>

    @php
        $menu = [
            ['label' => 'Beranda', 'name' => 'dashboard.index', 'icon' => 'home'],
            ['label' => 'Buku', 'name' => 'books.index', 'icon' => 'book-open'],
            ['label' => 'Kategori', 'name' => 'categories.index', 'icon' => 'layers'],
            ['label' => 'Peminjaman', 'name' => 'borrows.index', 'icon' => 'list'],
            ['label' => 'Pengguna', 'name' => 'users.index', 'icon' => 'user'],
        ];
    @endphp

    @foreach ($menu as $item)
        <a href="{{ route($item['name']) }}" @click="active = '{{ $item['name'] }}'"
            class="flex items-center gap-2 px-4 py-2 hover:bg-teal-400 hover:text-teal-950 transition-all text-xs"
            :class="active === '{{ $item['name'] }}' ? 'bg-teal-400 text-teal-950' : 'bg-teal-950 text-slate-200'">

            <i data-lucide="{{ $item['icon'] }}" class="w-4 h-4"></i>
            <span>{{ $item['label'] }}</span>
        </a>
    @endforeach
</div>
