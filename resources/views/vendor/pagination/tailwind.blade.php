@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex justify-center mt-4"
        x-init="lucide.createIcons()">
        <ul class="inline-flex items-center text-xs">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li>
                    <span
                        class="w-8 h-8 flex items-center justify-center text-gray-400 bg-slate-100 border border-slate-300 rounded-l-lg">
                        <i data-lucide="chevron-left" class="w-4 h-4"></i>
                    </span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                        class="w-8 h-8 flex items-center justify-center text-teal-800 bg-white border border-teal-300 hover:bg-teal-100 rounded-l-lg">
                        <i data-lucide="chevron-left" class="w-4 h-4"></i>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li>
                        <span
                            class="w-8 h-8 flex items-center justify-center text-gray-500 bg-white border border-slate-300">…</span>
                    </li>
                @endif

                {{-- Page Number Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li>
                                <span
                                    class="w-8 h-8 flex items-center justify-center text-white bg-teal-600 border border-teal-600 font-semibold">
                                    {{ $page }}
                                </span>
                            </li>
                        @else
                            <li>
                                <a href="{{ $url }}"
                                    class="w-8 h-8 flex items-center justify-center text-teal-800 bg-white border border-teal-300 hover:bg-teal-100">
                                    {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                        class="w-8 h-8 flex items-center justify-center text-teal-800 bg-white border border-teal-300 hover:bg-teal-100 rounded-r-lg">
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </a>
                </li>
            @else
                <li>
                    <span
                        class="w-8 h-8 flex items-center justify-center text-gray-400 bg-slate-100 border border-slate-300 rounded-r-lg">
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
