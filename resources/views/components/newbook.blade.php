@section('new-book-section')
    <div x-data="{ open: true }">
        <button @click="open = true">Tambah Buku</button>

        <div id="modal-add-book" class="bg-white shadow-2xl rounded-lg fixed top-32 lef1-1/2 w-1/2" x-show="open">
            <div class="bg-teal-950 w-full p-4 rounded-t-lg cursor-move" id="modal-add-book-header">
                <h2 class="text-xl font-bold flex align-middle justify-between">
                    <span class="block text-white">Tambah Buku Baru</span>
                    <button @click="open=false"><i class="block w-6 h-6 text-white text-sm cursor-pointer"
                            data-lucide="x"></i></button>
                </h2>
            </div>

            <form action="" method="POST" enctype="multipart/form-data" class="grid grid-cols-2 gap-4 p-5 w-full">
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
                    {{-- <label for="categories" class="block font-semibold">Kategori</label>
                                <select name="categories[]" id="categories" multiple class="form-select w-full">
                                    @foreach ($categories as $category)
                                        <option class="text-sm" value="{{ $category->id }}">
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-gray-500">Tekan Ctrl (atau Cmd) untuk memilih lebih dari satu.</small> --}}


                    <div x-data="categorySearch({{ $categories->sortByDesc('books_count')->values()->toJson() }})" class="relative">
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
                                    :class="index === 0 ? 'bg-teal-50' : ''">
                                    <span x-text="cat.name"></span>
                                    <span class="text-xs text-gray-400" x-text="'(' + cat.books_count + ' buku)'"></span>
                                </div>
                            </template>

                            <template x-if="filtered.length === 0">
                                <div class="px-4 py-2 text-sm text-gray-400">Tidak ditemukan</div>
                            </template>
                        </div>

                        <!-- Tampilkan kategori yang dipilih -->
                        <div class="mt-2 flex flex-wrap gap-1">
                            <template x-for="(cat, i) in selected" :key="cat.id">
                                <div class="bg-teal-100 text-teal-800 px-2 py-1 rounded text-sm flex items-center">
                                    <span x-text="cat.name"></span>
                                    <button type="button" class="ml-2" @click="remove(cat.id)">
                                        <i data-lucide="x" class="w-4 h-4"></i>
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
@endsection
