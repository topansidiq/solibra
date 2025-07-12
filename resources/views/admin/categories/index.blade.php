@extends('admin.layouts.app')

@section('content')
    <div class="content flex flex-col flex-auto bg-gray-50 w-full">
        <div class="title p-4 flex item-center justify-between">
            <div>
                <h3 class="text-xl font-bold">Daftar Kategori</h3>
                <p class="text-sm">Ini adalah daftar kategori buku yang tersedia di perpustakaan</p>
            </div>

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
                                class="form-input w-96 border-b border-slate-400 focus:outline-0 p-2 placeholder: text-sm"
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
        </div>

        {{-- Tabel Scrollable --}}
        <div class="mx-4">
            <table class="table font-sans w-1/2">
                <thead class="bg-teal-800 text-white text-sm sticky top-0 z-10">
                    <tr>
                        <th class="p-4 text-center w-5">No.</th>
                        <th class="p-4">Kategori</th>
                        <th class="p-4">Tanggal</th>
                        <th class="p-4">Total Buku</th>
                    </tr>
                </thead>
                <tbody class="text-xs">
                    @foreach ($categories as $category)
                        <tr>
                            <td class="px-4 py-1 border border-slate-300 text-center">
                                {{ $category->id }}.
                            </td>
                            <td class="px-4 py-1 border border-slate-300 text-left">{{ $category->name }}</td>
                            <td class="px-4 py-1 border border-slate-300 text-center">{{ $category->created_at }}</td>
                            <td class="px-4 py-1 border border-slate-300 text-center">{{ $category->books_count }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
