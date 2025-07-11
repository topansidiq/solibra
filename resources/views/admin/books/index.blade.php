@extends('admin.layouts.app')

@section('content')
    <div class="content flex flex-col flex-auto bg-gray-50 w-full" x-data="{ openAddBookModal: false }">
        <div class="title p-4 flex flex-row items-center justify-between">

            {{-- Header/Page Title/Page Description --}}
            <div>
                <h3 class="text-xl font-bold">Daftar Koleksi</h3>
                <p class="text-sm">Ini adalah daftar koleksi buku yang tersedia di perpustakaan</p>
            </div>

            {{-- Action --}}
            <div class="flex gap-4">
                {{-- Filter --}}

                {{-- Search Filter --}}
                <div class="flex items-center gap-2" x-data="filterSearch()">

                    {{-- Dropdown and Input Filter Search --}}
                    <div class="flex justify-center">
                        <div x-on:keydown.escape.prevent.stop="close($refs.button)"
                            x-on:focusin.window="! $refs.panel.contains($event.target) && close()"
                            x-id="['dropdown-button']" class="relative">
                            <!-- Button -->
                            <button x-ref="button" x-on:click="toggle()" :aria-expanded="open"
                                :aria-controls="$id('dropdown-button')" type="button"
                                class="relative flex items-center whitespace-nowrap justify-center gap-2 py-2 rounded-lg shadow-sm bg-white hover:bg-gray-50 text-gray-800 border border-gray-200 hover:border-gray-200 px-4">
                                <span>Filter</span>

                                <i data-lucide="chevron-down" class="w-5 h-5"></i>
                            </button>

                            <!-- Panel -->
                            <div x-ref="panel" x-show="open" x-transition.origin.top.left
                                x-on:click.outside="close($refs.button)" :id="$id('dropdown-button')" x-cloak
                                class="absolute left-0 min-w-48 rounded-lg shadow-sm mt-2 z-50 origin-top-left bg-white p-1.5 outline-none border border-gray-200">

                                <template x-for="filter in filters">
                                    <a href="#new"
                                        class="px-2 lg:py-1.5 py-2 w-full flex items-center rounded-md transition-colors text-left text-gray-800 hover:bg-gray-50 focus-visible:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                                        x-text="filter">
                                    </a>
                                </template>

                                {{-- <a href="#new"
                                    class="px-2 lg:py-1.5 py-2 w-full flex items-center rounded-md transition-colors text-left text-gray-800 hover:bg-gray-50 focus-visible:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                    New Task
                                </a>

                                <a href="#edit"
                                    class="px-2 lg:py-1.5 py-2 w-full flex items-center rounded-md transition-colors text-left text-gray-800 hover:bg-gray-50 focus-visible:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                    Edit Task
                                </a>

                                <a href="#delete"
                                    class="px-2 lg:py-1.5 py-2 w-full flex items-center rounded-md transition-colors text-left text-gray-800 hover:bg-red-50 hover:text-red-600 focus-visible:bg-red-50 focus-visible:text-red-600 disabled:opacity-50 disabled:cursor-not-allowed">
                                    Delete Task
                                </a> --}}
                            </div>
                        </div>
                    </div>
                    {{-- Input Search --}}
                    <div class="relative w-96">
                        <input type="text" placeholder="Cari buku...."
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">

                        <ul x-show="showDropdown && books.length > 0"
                            class="absolute z-10 w-full bg-white shadown-lg rounded-b-lg border border-t-0 border-gray-300"
                            x-transition>

                            <template x-for="book in books" :key="book.id">
                                <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer" x-text="book.title"
                                    @click="selectBook(book)"></li>
                            </template>

                        </ul>
                    </div>
                </div>

                {{-- Category Filter --}}
                <form method="GET" class="flex items-center gap-2">
                    <label for="category" class="text-sm font-medium text-gray-700">Filter:</label>

                    <div class="relative">
                        <select name="category" id="category" onchange="this.form.submit()"
                            class="appearance-none bg-teal-950 text-slate-200 text-sm px-3 py-1 pr-8 rounded-md cursor-pointer shadow-sm">
                            <option value="">Semua Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                            <i data-lucide="chevron-down" class="w-4 h-4 text-slate-300"></i>
                        </div>
                    </div>
                </form>
                <button type="button" @click="openAddBookModal = true"
                    class="flex flex-row items-center justify-around rounded-md cursor-pointer px-2 py-1 bg-teal-950 text-slate-200">
                    <i data-lucide="plus" class="block w-5 h-5"></i>
                    <p class="text-sm">Tambah Buku</p>
                </button>
                {{-- Modal Tambah Buku --}}
                <div class="modal-add bg-white shadow-2xl rounded-lg fixed top-32 left-52 w-1/2 z-50" x-cloak
                    x-show="openAddBookModal">
                    <div class="bg-teal-950 w-full p-4 rounded-t-lg cursor-move modal-add-header">
                        <h2 class="text-xl font-bold flex align-middle justify-between">
                            <span class="block text-white">Tambah Buku Baru</span>
                            <button type="button" @click="openAddBookModal=false"><i
                                    class="block w-6 h-6 text-white text-sm cursor-pointer" data-lucide="x"></i></button>
                        </h2>
                    </div>

                    <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data"
                        class="grid grid-cols-2 gap-4 p-5 w-full">
                        @csrf

                        <input type="hidden" name="_method" id="formMethod" value="POST">


                        <div>
                            <label for="title-edit" class="block font-semibold">Judul Buku</label>
                            <input type="text" name="title" id="title-edit"
                                class="form-input w-full border-b border-slate-400 focus: outline-0 p-2 placeholder: text-sm"
                                placeholder="Contoh: Pemrograman Web" required>
                        </div>

                        <div>
                            <label for="author-edit" class="block font-semibold">Penulis</label>
                            <input type="text" name="author" id="author-edit"
                                class="form-input w-full border-b border-slate-400 focus: outline-0 p-2 placeholder: text-sm"
                                required placeholder="Contoh: Rio, Andi, Rahmat">
                        </div>

                        <div>
                            <label for="publisher-edit" class="block font-semibold">Penerbit</label>
                            <input type="text" name="publisher" id="publisher-edit"
                                class="form-input w-full border-b border-slate-400 focus: outline-0 p-2 placeholder: text-sm"
                                placeholder="Contoh: Solok Publisher">
                        </div>

                        <div>
                            <label for="year-edit" class="block font-semibold">Tahun</label>
                            <input type="number" name="year" id="year-edit"
                                class="form-input w-full border-b border-slate-400 focus: outline-0 p-2 placeholder: text-sm"
                                min="1900" max="{{ date('Y') }}" placeholder="Contoh: 1998">
                        </div>

                        <div>
                            <label for="isbn-edit" class="block font-semibold">ISBN</label>
                            <input type="text" name="isbn" id="isbn-edit"
                                class="form-input w-full border-b border-slate-400 focus: outline-0 p-2 placeholder: text-sm"
                                placeholder="Contoh: 987654321">
                        </div>

                        <div id="selectedCategories">
                            <div x-data="categorySearch({{ $categories->sortByDesc('books_count')->values()->toJson() }})" x-init="window.categorySearchInstance = $data" class="relative">
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
                                            :class="index === 0 ? 'bg-teal-50' :
                                                ''">
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
                                <div class="mt-2 flex flex-wrap gap-1" id="categories">
                                    <template x-for="(cat, i) in selected" :key="cat.id">
                                        <div class="bg-teal-100 text-teal-800 px-2 py-1 rounded text-sm flex items-center">
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
                            <label for="stock-edit" class="block font-semibold">Stok</label>
                            <input type="number" name="stock" id="stock-edit"
                                class="form-input w-full border-b border-slate-400 focus: outline-0 p-2 placeholder: text-sm"
                                min="0" placeholder="Contoh: 23">
                        </div>

                        <div>
                            <label for="cover-edit" class="block font-semibold">Cover Buku</label>
                            <input type="file" name="cover" id="cover-edit"
                                class="form-input w-full border-b border-slate-400 focus: outline-0 p-2 placeholder: text-sm">
                        </div>

                        <div class="col-span-2">
                            <label for="description" class="block font-semibold">Deskripsi</label>
                            <textarea name="description" id="description-edit" rows="5"
                                placeholder="Bagian ini bisa di isi dengan sinopsis atau abstrak"
                                class="form-textarea text-sm w-full border-b border-slate-400 focus: outline-0 placeholder:text-sm placeholder:text-center resize-y"></textarea>
                        </div>

                        <div class="col-span-2 pt-4 flex flex-row content-end gap-4 justify-end-safe">
                            <button type="reset" id="resetBtn"
                                class="block rounded-sm font-bold bg-red-500 px-3 py-1 w-28 text-white hover:scale-105 transition-all">Reset</button>
                            <button type="button" @click="openAddBookModal=false"
                                class="block rounded-sm font-bold bg-red-500 px-3 py-1 w-28 text-white hover:scale-105 transition-all">Batal</button>
                            <button type="submit"
                                class="bg-emerald-700 px-3 py-1 rounded-sm font-bold text-white block w-28 hover:scale-105 transition-all">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Tabel Scrollable --}}
        <div class="mx-4">
            <table class="table font-sans w-full">
                <thead class="bg-teal-800 text-white text-sm sticky top-0 z-10">
                    <tr>

                        @php
                            $isAsc = request('sort_dir', 'asc') === 'asc';
                            $nextDir = $isAsc ? 'desc' : 'asc';
                        @endphp

                        <th class="p-4 text-center">
                            No.
                        </th>
                        <th class="p-4">
                            <a href="{{ route('books.index', ['sort_by' => 'title', 'sort_dir' => $sortBy === 'title' && $sortDir === 'asc' ? 'desc' : 'asc']) }}"
                                class="hover:underline m-auto">
                                Judul
                                @if ($sortBy === 'title')
                                    <i data-lucide="{{ $sortDir === 'asc' ? 'arrow-up' : 'arrow-down' }}"
                                        class="w-4 h-4 inline"></i>
                                @endif
                            </a>
                        </th>
                        <th class="p-4">
                            <a href="{{ route('books.index', ['sort_by' => 'author', 'sort_dir' => $sortBy === 'author' && $sortDir === 'asc' ? 'desc' : 'asc']) }}"
                                class="m-auto hover:underline">
                                Penulis
                                @if ($sortBy === 'author')
                                    <i data-lucide="{{ $sortDir === 'asc' ? 'arrow-up' : 'arrow-down' }}"
                                        class="w-4 h-4 inline"></i>
                                @endif
                            </a>
                        </th>
                        <th class="p-4">
                            <a href="{{ route('books.index', ['sort_by' => 'publisher', 'sort_dir' => $sortBy === 'publisher' && $sortDir === 'asc' ? 'desc' : 'asc']) }}"
                                class="m-auto hover:underline">
                                Penerbit
                                @if ($sortBy === 'publisher')
                                    <i data-lucide="{{ $sortDir === 'asc' ? 'arrow-up' : 'arrow-down' }}"
                                        class="w-4 h-4 inline"></i>
                                @endif
                            </a>
                        </th>
                        <th class="p-4">
                            <a href="{{ route('books.index', ['sort_by' => 'year', 'sort_dir' => $sortBy === 'year' && $sortDir === 'asc' ? 'desc' : 'asc']) }}"
                                class="m-auto hover:underline">
                                Tahun
                                @if ($sortBy === 'year')
                                    <i data-lucide="{{ $sortDir === 'asc' ? 'arrow-up' : 'arrow-down' }}"
                                        class="w-4 h-4 inline"></i>
                                @endif
                            </a>
                        </th>
                        <th class="p-4">
                            <a href="{{ route('books.index', ['sort_by' => 'isbn', 'sort_dir' => $sortBy === 'isbn' && $sortDir === 'asc' ? 'desc' : 'asc']) }}"
                                class="m-auto hover:underline">
                                ISBN
                                @if ($sortBy === 'isbn')
                                    <i data-lucide="{{ $sortDir === 'asc' ? 'arrow-up' : 'arrow-down' }}"
                                        class="w-4 h-4 inline"></i>
                                @endif
                            </a>
                        </th>
                        <th class="p-4">Kategori</th>
                        <th class="p-4">
                            <a href="{{ route('books.index', ['sort_by' => 'stock', 'sort_dir' => $sortBy === 'stock' && $sortDir === 'asc' ? 'desc' : 'asc']) }}"
                                class="m-auto hover:underline">
                                Stok
                                @if ($sortBy === 'stock')
                                    <i data-lucide="{{ $sortDir === 'asc' ? 'arrow-up' : 'arrow-down' }}"
                                        class="w-4 h-4 inline"></i>
                                @endif
                            </a>
                        </th>
                    </tr>
                </thead>
                <tbody class="text-xs">
                    @foreach ($books as $book)
                        <tr>
                            <td class="px-4 py-1 border border-slate-300 text-center">
                                {{ $loop->iteration + ($books->currentPage() - 1) * $books->perPage() }}
                            </td>

                            <td class="px-4 py-1 border border-slate-300">

                                <div class="flex flex-row gap-2 justify-between items-center" x-data="{ openEditModal: false }">
                                    <div class="flex items-center gap-2">
                                        {{-- Edit Book Button --}}
                                        @php $bookData = $book->only(['id', 'title', 'author', 'publisher', 'year', 'isbn', 'stock', 'categories', 'description', 'cover']); @endphp
                                        <button x-data @click.prevent="$dispatch(@js($bookData))"
                                            @click="openEditModal = true">
                                            <i data-lucide="edit" class="w-4 h-4 text-blue-500"></i>
                                        </button>

                                        {{-- Book Title --}}
                                        <p class="text-left">{{ $book->title }}</p>
                                    </div>

                                    {{-- Edit Modal (should be outside the loop) --}}
                                    <div x-data="{
                                        form: {
                                            id: '',
                                            title: '',
                                            author: '',
                                            publisher: '',
                                            year: '',
                                            isbn: '',
                                            stock: '',
                                            description: '',
                                            categories: [],
                                            cover: ''
                                        },
                                        openModal(bookData) {
                                            this.form = { ...bookData };
                                            this.open = true;
                                        },
                                    }" x-show="openEditModal"
                                        class="modal-add bg-slate-50 border border-slate-300 rounded-md fixed top-32 left-1/2 w-1/2 z-50"
                                        x-cloak @open-edit-modal.window="openModal($event.detail)">

                                        <!-- Modal Header -->
                                        <div class="modal-add-header bg-teal-950 w-full p-4 rounded-t-lg cursor-move">
                                            <h2 class="text-xl font-bold flex align-middle justify-between">
                                                <span class="block text-white">Edit Buku</span>
                                                <button type="button" @click="openEditModal=false">
                                                    <i class="block w-6 h-6 text-white text-sm cursor-pointer"
                                                        data-lucide="x"></i>
                                                </button>
                                            </h2>
                                        </div>

                                        <!-- Modal Body -->
                                        <form :action="`/books/${form.id}`" method="POST" enctype="multipart/form-data"
                                            class="grid grid-cols-2 gap-4 p-5 w-full">
                                            @csrf
                                            @method('PUT')

                                            <!-- Hidden input for method -->
                                            <input type="hidden" name="_method" value="PUT">

                                            <!-- Judul Buku -->
                                            <div>
                                                <label for="title-edit" class="block font-semibold">Judul Buku</label>
                                                <input type="text" name="title" id="title-edit"
                                                    x-model="form.title"
                                                    class="form-input w-full border-b border-slate-400 focus: outline-0 p-2 placeholder: text-sm"
                                                    placeholder="Contoh: Pemrograman Web" required>
                                            </div>

                                            <!-- Penulis -->
                                            <div>
                                                <label for="author-edit" class="block font-semibold">Penulis</label>
                                                <input type="text" name="author" id="author-edit"
                                                    x-model="form.author"
                                                    class="form-input w-full border-b border-slate-400 focus: outline-0 p-2 placeholder: text-sm"
                                                    required placeholder="Contoh: Rio, Andi, Rahmat">
                                            </div>

                                            <!-- Penerbit -->
                                            <div>
                                                <label for="publisher-edit" class="block font-semibold">Penerbit</label>
                                                <input type="text" name="publisher" id="publisher-edit"
                                                    x-model="form.publisher"
                                                    class="form-input w-full border-b border-slate-400 focus: outline-0 p-2 placeholder: text-sm"
                                                    placeholder="Contoh: Solok Publisher">
                                            </div>

                                            <!-- Tahun -->
                                            <div>
                                                <label for="year-edit" class="block font-semibold">Tahun</label>
                                                <input type="number" name="year" id="year-edit" x-model="form.year"
                                                    class="form-input w-full border-b border-slate-400 focus: outline-0 p-2 placeholder: text-sm"
                                                    min="1900" max="{{ date('Y') }}" placeholder="Contoh: 1998">
                                            </div>

                                            <!-- ISBN -->
                                            <div>
                                                <label for="isbn-edit" class="block font-semibold">ISBN</label>
                                                <input type="text" name="isbn" id="isbn-edit" x-model="form.isbn"
                                                    class="form-input w-full border-b border-slate-400 focus: outline-0 p-2 placeholder: text-sm"
                                                    placeholder="Contoh: 987654321">
                                            </div>

                                            <!-- Kategori -->
                                            <div id="selectedCategories">
                                                <div x-data="categorySearch({{ $categories->sortByDesc('books_count')->values()->toJson() }})" x-init="window.categorySearchInstance = $data" class="relative">
                                                    <label for="keyword" class="block font-semibold">Kategori</label>
                                                    <input type="text" x-model="search" @focus="show = true"
                                                        @keydown.tab.prevent="selectFirst()"
                                                        @keydown.enter.prevent="selectFirst()"
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
                                                            <div class="px-4 py-2 text-sm text-gray-400">Tidak ditemukan
                                                            </div>
                                                        </template>
                                                    </div>

                                                    <!-- Tampilkan kategori yang dipilih -->
                                                    <div class="mt-2 flex flex-wrap gap-1" id="categories">
                                                        <template x-for="(cat, i) in selected" :key="cat.id">
                                                            <div
                                                                class="bg-teal-100 text-teal-800 px-2 py-1 rounded text-sm flex items-center">
                                                                <span x-text="cat.name"></span>
                                                                <button type="button" class="ml-2"
                                                                    @click="remove(cat.id)">
                                                                    x
                                                                </button>
                                                                <input type="hidden" name="categories[]"
                                                                    :value="cat.id">
                                                            </div>
                                                        </template>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Stok -->
                                            <div>
                                                <label for="stock-edit" class="block font-semibold">Stok</label>
                                                <input type="number" name="stock" id="stock-edit"
                                                    x-model="form.stock"
                                                    class="form-input w-full border-b border-slate-400 focus: outline-0 p-2 placeholder: text-sm"
                                                    min="0" placeholder="Contoh: 23">
                                            </div>

                                            <!-- Cover Buku -->
                                            <div>
                                                <label for="cover-edit" class="block font-semibold">Cover Buku</label>
                                                <input type="file" name="cover" id="cover-edit"
                                                    class="form-input w-full border-b border-slate-400 focus: outline-0 p-2 placeholder: text-sm">
                                            </div>

                                            <!-- Deskripsi -->
                                            <div class="col-span-2">
                                                <label for="description" class="block font-semibold">Deskripsi</label>
                                                <textarea name="description" id="description-edit" rows="5" x-model="form.description"
                                                    placeholder="Bagian ini bisa di isi dengan sinopsis atau abstrak"
                                                    class="form-textarea text-sm w-full border-b border-slate-400 focus: outline-0 placeholder:text-sm placeholder:text-center resize-y"></textarea>
                                            </div>

                                            <!-- Modal Footer -->
                                            <div class="col-span-2 pt-4 flex flex-row content-end gap-4 justify-end-safe">
                                                <button type="reset"
                                                    class="block rounded-sm font-bold bg-red-500 px-3 py-1 w-28 text-white hover:scale-105 transition-all">Reset</button>
                                                <button type="button" @click="openEditModal=false"
                                                    class="block rounded-sm font-bold bg-red-500 px-3 py-1 w-28 text-white hover:scale-105 transition-all">Batal</button>
                                                <button type="submit"
                                                    class="bg-emerald-700 px-3 py-1 rounded-sm font-bold text-white block w-28 hover:scale-105 transition-all">
                                                    Simpan
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                    {{-- Delete Book Button --}}
                                    <form action="{{ route('books.destroy', $book->id) }}" method="POST"
                                        class="ml-2">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-book-btn">
                                            <i data-lucide="trash-2" class="w-4 h-4 text-red-500"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>

                            <td class="px-4 py-1 border border-slate-300">{{ $book->author }}</td>
                            <td class="px-4 py-1 border border-slate-300">{{ $book->publisher }}</td>
                            <td class="px-4 py-1 border border-slate-300 text-center">{{ $book->year }}</td>
                            <td class="px-4 py-1 border border-slate-300 text-center">{{ $book->isbn }}</td>
                            <td class="px-4 py-1 border border-slate-300">
                                @foreach ($book->categories->take(3) as $category)
                                    <span class="badge">{{ $category->name }},</span>
                                @endforeach
                            </td>
                            <td class="px-4 py-2 border border-slate-300 text-center">{{ $book->stock }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $books->links() }}
        </div>
    </div>
@endsection
