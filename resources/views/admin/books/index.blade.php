@extends('admin.layouts.app')

@section('content')
    {{-- h-[calc(100vh-4rem)] --}}
    <div class="content flex flex-col flex-auto bg-gray-50">
        <div class="title p-4 ">
            <h3 class="text-xl font-bold">Daftar Koleksi</h3>
            <p class="text-sm">Ini adalah daftar koleksi buku yang tersedia di perpustakaan</p>
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
                            <td class="px-4 py-1 border border-slate-300">{{ $book->title }}</td>
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
