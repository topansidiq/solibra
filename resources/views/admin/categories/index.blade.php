@extends('admin.layouts.app')

@section('content')
    <div class="content flex flex-col flex-auto bg-gray-50">
        <div class="title p-4">
            <h3 class="text-xl font-bold">Daftar Kategori</h3>
            <p class="text-sm">Ini adalah daftar kategori buku yang tersedia di perpustakaan</p>
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
