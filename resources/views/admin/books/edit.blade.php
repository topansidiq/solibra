@extends('admin.layouts.app')

@section('content')
    <div class="content flex flex-col flex-auto bg-gray-50 w-full" x-data="{ openAddBookModal: false }">

        {{-- Header/Page Title/Page Description --}}
        <div class="title p-4 flex flex-row items-center justify-between">
            <div>
                <h3 class="text-xl font-bold">Edit Buku <span class="text-amber-500">{{ $book->title }}</span></h3>
                <p class="text-sm">Penulis: {{ $book->author }} ({{ $book->year }})</p>
            </div>
        </div>

        <!-- Main modal -->
        <div id="defaultModal" tabindex="-1" aria-hidden="true" class="w-full justify-center items-center ">
            <div class="relative p-4 w-full h-full md:h-auto">
                <!-- Modal content -->
                <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">

                    <!-- Modal body -->
                    <form action="{{ route('books.update', $book->id) }}" method="POST">

                        @csrf
                        @method('PUT')
                        <div class="grid gap-4 mb-4 sm:grid-cols-2">
                            <div>
                                <label for="title"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Judul</label>
                                <input type="text" name="title" id="title"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Masukkan judul buku...." required="" value="{{ $book->title }}">
                            </div>
                            <div>
                                <label for="author"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Penulis</label>
                                <input type="text" name="author" id="author"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Masukkan nama lengkap penulis...." required=""
                                    value="{{ $book->author }}">
                            </div>
                            <div>
                                <label for="publisher"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Penerbit</label>
                                <input type="text" name="publisher" id="publisher"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Masukkan nama penerbit...." required="" value="{{ $book->publisher }}">
                            </div>
                            <div>
                                <label for="year"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tahun</label>
                                <input type="number" name="year" id="year"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Masukkan tahun terbit...." required="" value="{{ $book->year }}">
                            </div>
                            <div>
                                <label for="isbn"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ISBN</label>
                                <input type="number" name="isbn" id="isbn"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Masukkan nomor ISBN...." required="" value="{{ $book->isbn }}">
                            </div>
                            <div x-data="categorySearch({{ $categories->sortByDesc('books_count')->values()->toJson() }},
                                {{ $book->categories->values()->toJson() }})" x-init="window.categorySearchInstance = $data" class="relative">
                                <label for="keyword"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kategori</label>
                                <input type="text" x-model="search" @focus="show = true"
                                    @keydown.tab.prevent="selectFirst()" @keydown.enter.prevent="selectFirst()"
                                    @click.outside="show = false" id="keyword" name="keyword"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Ketikkan kategori...">

                                <!-- Dropdown kategori -->
                                <div x-show="show"
                                    class="absolute w-full bg-white shadow border mt-1 rounded z-50 max-h-60 overflow-y-auto">
                                    <template x-for="(cat, index) in filtered" :key="cat.id">
                                        <div @click="select(cat)" class="px-4 py-2 text-sm hover:bg-teal-100 cursor-pointer"
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
                            <div>
                                <label for="stock"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tahun</label>
                                <input type="number" name="stock" id="stock"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Masukkan tahun terbit...." required="" value="{{ $book->stock }}">
                            </div>
                            <div>
                                <label for="cover"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sampul</label>
                                <input type="file" name="cover" id="cover"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Masukkan tahun terbit...." value="/storage/public{{ $book->cover }}">
                            </div>
                            <div class="sm:col-span-2">
                                <label for="description"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Deskipsi</label>
                                <textarea id="description" rows="4"
                                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Write product description here">{{ $book->description }}</textarea>
                            </div>
                        </div>
                        <button type="submit" class="text-white flex bg-teal-600 p-2 rounded gap-2">
                            <i class="w-5 h-5" data-lucide="save"></i>
                            <span class="text-sm">Simpan Perubahan</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
