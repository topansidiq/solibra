@extends('admin.layouts.app')

@section('content')
    <div class="content flex flex-col flex-auto bg-gray-50 w-full" x-data="{ openAddBookModal: false }">
        <div class="title p-4 flex flex-row items-center justify-between">
            <div>
                <h3 class="text-xl font-bold">Daftar Koleksi</h3>
                <p class="text-sm">Ini adalah daftar koleksi buku yang tersedia di perpustakaan</p>
            </div>

            {{-- Add Book --}}
            <div>
                <button id="openAddBookModalBtn"
                    class="flex flex-row items-center justify-around rounded-md cursor-pointer px-2 py-1 bg-teal-950 text-slate-200">
                    <i data-lucide="plus" class="block w-5 h-5"></i>
                    <p class="text-sm">Tambah Buku</p>
                </button>
            </div>
        </div>

        {{-- Tabel Scrollable --}}
        <div class="mx-4">
            <table class="table font-sans w-full">
                <thead class="bg-teal-800 text-white text-sm sticky top-0 z-10">
                    <tr>
                        <th class="p-4 text-center w-5">No.</th>
                        <th class="p-4">Judul</th>
                        <th class="p-4">Penulis</th>
                        <th class="p-4">Penerbit</th>
                        <th class="p-4">Tahun</th>
                        <th class="p-4">ISBN</th>
                        <th class="p-4">Kategori</th>
                        <th class="p-4">Stok</th>
                    </tr>
                </thead>
                <tbody class="text-xs">
                    @foreach ($books as $book)
                        <tr>
                            <td class="px-4 py-1 border border-slate-300 text-center">
                                {{ $loop->iteration + ($books->currentPage() - 1) * $books->perPage() }}
                            </td>
                            <td class="px-4 py-1 border border-slate-300">
                                <div class="flex justify-between" x-cloak x-transition x-data="{
                                    showDeleteModal: false,
                                    deleteUrl: '',
                                    openDeleteModal(id) {
                                        this.deleteUrl = `/books/${id}`;
                                        this.showDeleteModal = true;
                                    }
                                }">
                                    <button class="editBookBtn" data-id="{{ $book->id }}"
                                        data-title="{{ $book->title }}" data-author="{{ $book->author }}"
                                        data-publisher="{{ $book->publisher }}" data-year="{{ $book->year }}"
                                        data-isbn="{{ $book->isbn }}" data-stock="{{ $book->stock }}"
                                        data-description="{{ $book->description }}"
                                        data-categories='@json($book->categories->map(fn($c) => ['id' => $c->id, 'name' => $c->name]))'>
                                        <i data-lucide="edit" class="w-4 h-4 text-blue-600"></i>
                                    </button>
                                    <p class="text-left pl-2 w-full text-sm">{{ $book->title }}</p>
                                    <button type="button" @click="openDeleteModal({{ $book->id }})">
                                        <i data-lucide="trash" class="text-red-500 w-4 h-4"></i>
                                    </button>

                                    {{-- Modal Delete --}}
                                    <div x-show="showDeleteModal" x-cloak
                                        class="fixed inset-0 flex items-center justify-center bg-black/50 z-50">
                                        <div class="bg-white w-[400px] p-6 rounded-lg shadow-xl">
                                            <h2 class="text-lg font-bold text-red-600 mb-4 text-center">Konfirmasi Hapus
                                            </h2>
                                            <p class="text-sm text-center mb-6 text-gray-700">Yakin ingin menghapus buku
                                                ini? Tindakan ini tidak bisa
                                                dibatalkan.</p>

                                            <div class="flex justify-end gap-3">
                                                <button @click="showDeleteModal = false"
                                                    class="bg-gray-300 hover:bg-gray-400 px-4 py-1 rounded text-sm">
                                                    Batal
                                                </button>

                                                <form :action="deleteUrl" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-1 rounded text-sm">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
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

        {{-- Modal --}}
        <div id="modalAdd" class="modal-add bg-white shadow-2xl rounded-lg fixed top-32 left-52 w-1/2 hidden z-50">
            <div class="bg-teal-950 w-full p-4 rounded-t-lg cursor-move modal-add-header">
                <h2 class="text-xl font-bold flex align-middle justify-between">
                    <span class="block text-white">Tambah Buku Baru</span>
                    <button id="modalCloseBtn"><i class="block w-6 h-6 text-white text-sm cursor-pointer"
                            data-lucide="x"></i></button>
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
                        <input type="text" x-model="search" @focus="show = true" @keydown.tab.prevent="selectFirst()"
                            @keydown.enter.prevent="selectFirst()" @click.outside="show = false" id="keyword"
                            name="keyword"
                            class="form-input w-full border-b border-slate-400 focus: outline-0 p-2 placeholder: text-sm"
                            placeholder="Ketikkan kategori...">

                        <!-- Dropdown kategori -->
                        <div x-show="show"
                            class="absolute w-full bg-white shadow border mt-1 rounded z-50 max-h-60 overflow-y-auto">
                            <template x-for="(cat, index) in filtered" :key="cat.id">
                                <div @click="select(cat)" class="px-4 py-2 text-sm hover:bg-teal-100 cursor-pointer"
                                    :class="index === 0 ? 'bg-teal-50' :
                                        ''">
                                    <span x-text="cat.name"></span>
                                    <span class="text-xs text-gray-400" x-text="'(' + cat.books_count + ' buku)'"></span>
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
                    <button id="closeBtnModal"
                        class="block rounded-sm font-bold bg-red-500 px-3 py-1 w-28 text-white hover:scale-105 transition-all">Batal</button>
                    <button type="submit"
                        class="bg-emerald-700 px-3 py-1 rounded-sm font-bold text-white block w-28 hover:scale-105 transition-all">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
