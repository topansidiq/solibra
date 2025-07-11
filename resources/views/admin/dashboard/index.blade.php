@extends('admin.layouts.app')

@section('content')
    <div class="content flex flex-col flex-auto bg-gray-50 w-full">

        {{-- Page Header --}}
        <div class="title p-4 flex items-center justify-between">

            {{-- Title --}}
            <div class="">
                <h3 class="text-xl font-bold">Beranda</h3>
                <p class="text-sm">Menu ini menampilkan aktifitas di dalam aplikasi.</p>
            </div>

            {{-- Action --}}
            <div class="flex flex-row gap-3">

                {{-- Add new book button --}}
                <div x-data="{ open: false }">
                    <div @click="open = true"
                        class="flex flex-row items-center justify-around rounded-md cursor-pointer px-2 py-1 bg-teal-950 text-slate-200">
                        <i data-lucide="plus" class="block w-5 h-5"></i>
                        <p class="text-sm">Tambah Buku</p>
                    </div>

                    <div x-cloak x-transition class="modal-add bg-white shadow-2xl rounded-lg fixed top-32 left-52 w-1/2"
                        x-show="open">
                        <div class="bg-teal-950 w-full p-4 rounded-t-lg cursor-move modal-add-header">
                            <h2 class="text-xl font-bold flex align-middle justify-between">
                                <span class="block text-white">Tambah Buku Baru</span>
                                <button @click="open=false"><i class="block w-6 h-6 text-white text-sm cursor-pointer"
                                        data-lucide="x"></i></button>
                            </h2>
                        </div>

                        <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data"
                            class="grid grid-cols-2 gap-4 p-5 w-full">
                            @csrf

                            <div>
                                <label for="title" class="block font-semibold">Judul Buku</label>
                                <input type="text" name="title" id="title"
                                    class="form-input w-full border-b border-slate-400 focus: outline-0 p-2 placeholder: text-sm"
                                    placeholder="Contoh: Pemrograman Web" required>
                            </div>

                            <div>
                                <label for="author" class="block font-semibold">Penulis</label>
                                <input type="text" name="author" id="author"
                                    class="form-input w-full border-b border-slate-400 focus: outline-0 p-2 placeholder: text-sm"
                                    required placeholder="Contoh: Rio, Andi, Rahmat">
                            </div>

                            <div>
                                <label for="publisher" class="block font-semibold">Penerbit</label>
                                <input type="text" name="publisher" id="publisher"
                                    class="form-input w-full border-b border-slate-400 focus: outline-0 p-2 placeholder: text-sm"
                                    placeholder="Contoh: Solok Publisher">
                            </div>

                            <div>
                                <label for="year" class="block font-semibold">Tahun</label>
                                <input type="number" name="year" id="year"
                                    class="form-input w-full border-b border-slate-400 focus: outline-0 p-2 placeholder: text-sm"
                                    min="1900" max="{{ date('Y') }}" placeholder="Contoh: 1998">
                            </div>

                            <div>
                                <label for="isbn" class="block font-semibold">ISBN</label>
                                <input type="text" name="isbn" id="isbn"
                                    class="form-input w-full border-b border-slate-400 focus: outline-0 p-2 placeholder: text-sm"
                                    placeholder="Contoh: 987654321">
                            </div>

                            <div>
                                <div x-data="categorySearch({{ $categories->sortByDesc('books_count')->values()->toJson() }})" class="relative">
                                    <label for="keyword" class="block font-semibold">Kategori</label>
                                    <input type="text" x-model="search" @focus="show = true"
                                        @keydown.tab.prevent="selectFirst()" @keydown.enter.prevent="selectFirst()"
                                        @click.outside="show = false" id="keyword" name="keyword"
                                        class="form-input w-full border-b border-slate-400 focus: outline-0 p-2 placeholder: text-sm"
                                        placeholder="Ketikkan kategori...">

                                    <!-- Dropdown kategori -->
                                    <div x-show="show"
                                        class="absolute w-full bg-white shadow border mt-1 rounded z-50 max-h-60 overflow-y-auto">
                                        <template x-for="(cat, index) in filtered" :key="cat.id">
                                            <div @click="select(cat)"
                                                class="px-4 py-2 text-sm hover:bg-teal-100 cursor-pointer"
                                                :class="index === 0 ? 'bg-teal-50' : ''">
                                                <span x-text="cat.name"></span>
                                                <span class="text-xs text-gray-400"
                                                    x-text="'(' + cat.books_count + ' buku)'"></span>
                                            </div>
                                        </template>

                                        <template x-if="filtered.length === 0">
                                            <div class="px-4 py-2 text-sm text-gray-400">Tidak ditemukan</div>
                                        </template>
                                    </div>

                                    <!-- Tampilkan kategori yang dipilih -->
                                    <div class="mt-2 flex flex-wrap gap-1">
                                        <template x-for="(cat, i) in selected" :key="cat.id">
                                            <div
                                                class="bg-teal-100 text-teal-800 px-2 py-1 rounded text-sm flex items-center">
                                                <span x-text="cat.name"></span>
                                                <button type="button" class="ml-2" @click="remove(cat.id)">
                                                    x
                                                </button>
                                                <input type="hidden" name="categories[]" :value="cat.id">
                                            </div>
                                        </template>
                                    </div>
                                </div>

                            </div>

                            <div>
                                <label for="stock" class="block font-semibold">Stok</label>
                                <input type="number" name="stock" id="stock"
                                    class="form-input w-full border-b border-slate-400 focus: outline-0 p-2 placeholder: text-sm"
                                    min="0" placeholder="Contoh: 23">
                            </div>

                            <div>
                                <label for="cover" class="block font-semibold">Cover Buku</label>
                                <input type="file" name="cover" id="cover"
                                    class="form-input w-full border-b border-slate-400 focus: outline-0 p-2 placeholder: text-sm">
                            </div>

                            <div class="col-span-2">
                                <label for="description" class="block font-semibold">Deskripsi</label>
                                <textarea name="description" id="description" rows="5"
                                    placeholder="Bagian ini bisa di isi dengan sinopsis atau abstrak"
                                    class="form-textarea w-full border-b border-slate-400 focus: outline-0 placeholder:text-sm placeholder:text-center resize-y"></textarea>
                            </div>

                            <div class="col-span-2 pt-4 flex flex-row content-end gap-4 justify-end-safe">
                                <button @click="open = false"
                                    class="block rounded-sm font-bold bg-red-500 px-3 py-1 w-28 text-white hover:scale-105 transition-all">Batal</button>
                                <button type="submit"
                                    class="bg-emerald-700 px-3 py-1 rounded-sm font-bold text-white block w-28 hover:scale-105 transition-all">
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>

                </div>

                {{-- Add new category button --}}
                <div x-data="{ open: false }">
                    <div @click="open = true"
                        class="flex flex-row items-center justify-around cursor-pointer rounded-md px-2 py-1 bg-teal-950 text-slate-200">
                        <i data-lucide="plus" class="block w-5 h-5"></i>
                        <p class="text-sm">Tambah Kategori</p>
                    </div>

                    <div x-cloak x-transition class="modal-add bg-white shadow-2xl rounded-lg fixed top-32 left-52 w-fit"
                        x-show="open">
                        <div class="bg-teal-950 w-full p-4 rounded-t-lg cursor-move modal-add-header">
                            <h2 class="text-xl font-bold flex align-middle justify-between">
                                <span class="block text-white">Tambah Kategori Baru</span>
                                <button @click="open=false"><i class="block w-6 h-6 text-white text-sm cursor-pointer"
                                        data-lucide="x"></i></button>
                            </h2>
                        </div>

                        <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data"
                            class="grid grid-cols-2 gap-4 p-5 w-full">
                            @csrf

                            <div>
                                <label for="name" class="block font-semibold">Kategori</label>
                                <input type="text" name="name" id="name"
                                    class="form-input w-full border-b border-slate-400 focus: outline-0 p-2 placeholder: text-sm"
                                    placeholder="Contoh: Pemrograman Web" required>
                            </div>

                            <div class="col-span-2 pt-4 flex flex-row content-end gap-4 justify-end-safe">
                                <button @click="open = false"
                                    class="block rounded-sm font-bold bg-red-500 px-3 py-1 w-28 text-white hover:scale-105 transition-all">Batal</button>
                                <button type="submit"
                                    class="bg-emerald-700 px-3 py-1 rounded-sm font-bold text-white block w-28 hover:scale-105 transition-all">
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>

                </div>

                {{-- Add new borrow button --}}
                <div x-data="{ open: false }">
                    <div @click="open = true"
                        class="flex flex-row items-center justify-around cursor-pointer rounded-md px-2 py-1 bg-teal-950 text-slate-200">
                        <i data-lucide="plus" class="block w-5 h-5"></i>
                        <p class="text-sm">Tambah Peminjaman</p>
                    </div>

                    <div x-cloak x-transition class="modal-add bg-white shadow-2xl rounded-lg fixed top-32 left-52 w-fit"
                        x-show="open">
                        <div class="bg-teal-950 w-full p-4 rounded-t-lg cursor-move modal-add-header">
                            <h2 class="text-xl font-bold flex align-middle justify-between">
                                <span class="block text-white">Tambah Peminjaman Baru</span>
                                <button @click="open=false"><i class="block w-6 h-6 text-white text-sm cursor-pointer"
                                        data-lucide="x"></i></button>
                            </h2>
                        </div>

                        <form action="{{ route('borrows.store') }}" method="POST"
                            class="grid grid-cols-2 gap-4 p-5 w-full">
                            @csrf

                            {{-- Select Book --}}
                            <div x-data="bookSearch({{ $books->sortByDesc('stock')->values()->toJson() }})" x-init="window.bookSearchInstance = $data" class="relative">
                                <label for keyword class="block font-semibold">Pilih Buku</label>
                                <input type="text" x-model="search" @focus="show = true"
                                    @keydown.tab.prevent="selectFirst()" @keydown.enter.prevent="selectFirst()"
                                    @click.outside="show = false" id="keyword" name="keyword"
                                    placeholder="Cari buku..."
                                    class="form-input w-full border-b border-slate-400 focus:outline-0 p-2 placeholder:text-sm">

                                {{-- Dropdown --}}
                                <div x-show="show && filtered.length > 0"
                                    class="absolute w-full bg-white shadow border mt-1 rounded z-50 max-h-60 overflow-y-auto">
                                    <template x-for="(book, index) in filtered" :key="book.id">
                                        <div @click="select(book)"
                                            class="px-4 py-2 text-sm hover:bg-teal-100 cursor-pointer">
                                            <span x-text="book.title"></span>
                                            <span class="text-xs text-gray-500"
                                                x-text="'(' + book.stock + ' stock)'"></span>
                                        </div>
                                    </template>
                                </div>

                                <input type="hidden" name="book_id" :value="book_id">
                            </div>

                            {{-- Select User --}}
                            <div x-data="userSearch({{ $users->values()->toJson() }})" x-init="window.userSearchInstance = $data" class="relative">
                                <label for keyword class="block font-semibold">Pilih Pengguna</label>
                                <input type="text" x-model="search" @focus="show = true"
                                    @keydown.tab.prevent="selectFirst()" @keydown.enter.prevent="selectFirst()"
                                    @click.outside="show = false" id="keyword" name="keyword"
                                    placeholder="Cari buku..."
                                    class="form-input w-full border-b border-slate-400 focus:outline-0 p-2 placeholder:text-sm">

                                {{-- Dropdown --}}
                                <div x-show="show"
                                    class="absolute w-full bg-white shadow border mt-1 rounded z-50 max-h-60 overflow-y-auto">
                                    <template x-for="(user, index) in filtered" :key="user.id">
                                        <div @click="select(user)"
                                            class="px-4 py-2 text-sm hover:bg-teal-100 cursor-pointer">
                                            <span x-text="user.name"></span>
                                            <span class="text-xs text-gray-500" x-text="'(' + user.email + ')'"></span>
                                        </div>
                                    </template>
                                </div>

                                <input class="text-xs" type="hidden" name="user_id" :value="user_id">
                            </div>

                            <!-- Tanggal Pengembalian -->
                            @php
                                $dueDate = now()->addDays(7)->locale('id')->translatedFormat('d F Y');
                            @endphp

                            <div>
                                <label class="block font-semibold">Harus dikembalikan pada</label>
                                <input type="date" readonly name="due_date"
                                    value="{{ now()->addDays(7)->toDateString() }}">
                                <p class="text-sm text-gray-700 mt-1">
                                    (otomatis 7 hari setelah tanggal peminjaman)
                                </p>
                            </div>

                            <input type="hidden" name="borrowed_at" value="{{ now()->toDateString() }}">

                            <!-- Tombol -->
                            <div class="col-span-2 pt-4 flex justify-end gap-4">
                                <button type="reset"
                                    class="block rounded-sm font-bold bg-red-500 px-3 py-1 w-28 text-white hover:scale-105 transition-all">
                                    Reset
                                </button>
                                <button type="button" @click="openAddborrowModal = false"
                                    class="block rounded-sm font-bold bg-red-500 px-3 py-1 w-28 text-white hover:scale-105 transition-all">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="bg-emerald-700 px-3 py-1 rounded-sm font-bold text-white block w-28 hover:scale-105 transition-all">
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>

                </div>

                {{-- Add new user button --}}
                {{-- Add new event button --}}
                {{-- Add new post button --}}

            </div>

        </div>

        {{-- New Book --}}
        <div class="mx-4 p-4 shadow rounded-xl bg-white text-sm text-slate-800 w-auto h-fit">
            <h2 class="text-lg font-bold">Buku Terbaru</h2>

            <div class="py-2 grid xl:grid-cols-6 gap-2 sm:grid-cols-3" x-data="{ open: false, book: {} }" x-ref="modal">
                @forelse ($latestBooks as $book)
                    <div class="border border-slate-200 p-4 book cursor-pointer hover:shadow-md transition"
                        @click="open = true; book = {{ json_encode([
                            'title' => $book->title,
                            'author' => $book->author,
                            'publisher' => $book->publisher,
                            'year' => $book->year,
                            'isbn' => $book->isbn,
                            'stock' => $book->stock,
                            'description' => $book->description,
                            'cover' => $book->cover ? asset('storage/covers/' . $book->cover) : '',
                            'initial' => $book->initial,
                            'categories' => $book->categories->pluck('name')->join(', '),
                            'created_at' => $book->created_at->format('d M Y'),
                        ]) }}">
                        <div
                            class="xl:h-70 sm:h-40 bg-teal-600 text-white flex items-center justify-center rounded shadow text-3xl font-bold">
                            @if ($book->cover)
                                <img src="{{ asset('storage/covers/' . $book->cover) }}" alt="{{ $book->title }}"
                                    class="w-full h-full object-cover rounded-xl">
                            @else
                                {{ $book->initial }}
                            @endif
                        </div>
                        <div class="pt-2">
                            <strong>{{ $book->title }}</strong><br>
                            <p class="text-xs"> {{ $book->author }} </p>
                            <p class="text-xs">
                                @foreach ($book->categories as $category)
                                    {{ $category->name }},
                                @endforeach
                            </p>
                            <span class="text-xs text-slate-500">Ditambahkan pada
                                {{ $book->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                @empty
                    <li>Tidak ada buku terbaru.</li>
                @endforelse

                {{-- Modal --}}
                <div x-show="open" x-transition class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
                    style="display: none;">
                    <div class="bg-white p-6 rounded-lg w-11/12 md:w-1/2 relative" @click.away="open = false">
                        <!-- Tombol Tutup -->
                        <button class="absolute top-4 right-4 text-slate-500 hover:text-red-500" @click="open = false">
                            <i data-lucide="x"></i>
                        </button>

                        <!-- Judul dan Penulis -->
                        <div class="flex flex-col mb-4">
                            <h2 class="text-xl font-semibold" x-text="book.title"></h2>
                            <span class="text-xs text-slate-600" x-text="book.author"></span>
                        </div>

                        <!-- Cover atau Inisial -->
                        <template x-if="book.cover">
                            <img :src="book.cover" alt="Book Cover" class="w-full h-64 object-cover rounded mb-4" />
                        </template>
                        <template x-if="!book.cover">
                            <div class="w-full h-64 bg-teal-600 text-white flex items-center justify-center rounded text-5xl font-bold mb-4"
                                x-text="book.initial"></div>
                        </template>

                        <!-- Info Buku -->
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div>
                                <strong>Penerbit</strong>
                                <span class="block text-xs text-slate-700" x-text="book.publisher"></span>
                            </div>
                            <div>
                                <strong>Tahun</strong>
                                <span class="block text-xs text-slate-700" x-text="book.year"></span>
                            </div>
                            <div>
                                <strong>ISBN</strong>
                                <span class="block text-xs text-slate-700" x-text="book.isbn"></span>
                            </div>
                            <div>
                                <strong>Stok</strong>
                                <span class="block text-xs text-slate-700" x-text="book.stock"></span>
                            </div>
                            <div>
                                <strong>Kategori</strong>
                                <span class="block text-xs text-slate-700" x-text="book.categories"></span>
                            </div>
                            <div>
                                <strong>Ditambahkan</strong>
                                <span class="block text-xs text-slate-700" x-text="book.created_at"></span>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="mt-4 text-sm">
                            <h3 class="font-bold mb-1">Deskripsi</h3>
                            <p class="text-xs text-slate-700" x-text="book.description"></p>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        {{-- Activity --}}
        <div class="ml-4 p-4 shadow rounded-xl bg-white text-sm text-slate-800 w-fit h-fit mt-2">
            <h2 class="text-lg font-bold">Aktivitas</h2>
            <div class="py-2 grid grid-cols-6 gap-2">
                <div class="border border-slate-200 w-60 h-60 content-center text-center p-10 rounded-xl">
                    <h3>Total Buku</h3>
                    <p class="text-6xl font-bold">{{ $books_count }}</p>
                </div>
                <div class="border border-slate-200 w-60 h-60 content-center text-center p-10 rounded-xl">
                    <h3>Total Kategori</h3>
                    <p class="text-6xl font-bold">{{ $categories_count }}</p>
                </div>
                <div class="border border-slate-200 w-60 h-60 content-center text-center p-10 rounded-xl">
                    <h3>Total Buku Dipinjam</h3>
                    <p class="text-6xl font-bold">{{ $borrows_count }}</p>
                </div>
                <div class="border border-slate-200 w-60 h-60 content-center text-center p-10 rounded-xl">
                    <h3>Total Pengguna Terdaftar</h3>
                    <p class="text-6xl font-bold">{{ $users_count }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
