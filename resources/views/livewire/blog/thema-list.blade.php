<div>
    <ul role="list" class="divide-y divide-white/5">
        @foreach ($rows as $category)
            <li class="relative flex items-center space-x-4 py-1">
                <div class="min-w-0 flex-auto">
                    <div class="flex items-center gap-x-3">
                        <div class="flex-none rounded-full p-1 text-gray-500 bg-gray-200">
                            <div class="h-2 w-2 rounded-full bg-current"></div>
                        </div>
                        <h2 class="min-w-0 text-sm font-semibold ">
                            <a href="{{ route('themas',['slug' => $category->slug]) }}" class="flex gap-x-2 text-slate-800">
                                <span class="truncate">{{ $category->name }}</span>
                            </a>
                        </h2>
                    </div>
                    <div class="mt-3 flex items-center gap-x-2.5 text-xs leading-3 text-gray-400">
                        <p class="truncate pl-7">{{ $category->total }} articles</p>
                        <svg viewBox="0 0 2 2" class="h-0.5 w-0.5 flex-none fill-gray-300">
                            <circle cx="1" cy="1" r="1" />
                        </svg>
                    </div>
                </div>
                <a href="{{ $category->slug }}" class="flex gap-x-2">
                    <div
                        class="rounded-full flex-none py-1 px-2 text-xs font-medium ring-1 ring-inset text-gray-400 bg-blue-400/10 ring-gray-400/20">
                        Consulter</div>

                    <svg class="h-5 w-5 flex-none text-blue-400" viewBox="0 0 20 20" fill="currentColor"
                        aria-hidden="true">
                        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                            clip-rule="evenodd" />
                    </svg>
                </a>
            </li>
        @endforeach
    </ul>
</div>
