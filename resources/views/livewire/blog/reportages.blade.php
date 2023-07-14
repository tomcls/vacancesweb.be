<div x-init="loadImages();"  class="flex min-h-full flex-col">
    <div class="relative isolate overflow-hidden pt-14 h-[60vh] ">
        <img src="{{ $rows[0]->cover }}" data-src="{{ $rows[0]->cover }}" alt="{{ $rows[0]->title }}"
            class="absolute inset-0 -z-10 sm:h-full w-full object-cover" />
        <div class=" flex-row absolute bottom-0 w-full  h-24 justify-center">
            <div class="absolute inset-0 -z-10 bg-black opacity-30"></div>
            <div class="max-w-6xl text-left mx-auto">
                <div class="flex flex-row justify-start space-x-2 content-center">
                    <h2 class="text-3xl font-bold tracking-tight text-white sm:text-3xl p-3">{{ $rows[0]->title }}</h2>
                    <x-icon.guide class="text-sky-500 h-20 w-16 pt-5" />
                    <label class="w-56 text-xl text-white pt-9"> Suivez le guide</label>
                </div>
            </div>
        </div>
    </div>
    <!-- 3 column wrapper -->
    <div class="mx-auto w-full max-w-7xl grow lg:flex xl:px-2">
        <!-- Left sidebar & main wrapper -->
        <div class="flex-1 xl:flex">
            <div class="px-4 py-6 sm:px-6 lg:pl-8 xl:flex-1 xl:pl-6">
                <!-- Main area -->
                <div class="mx-auto mt-12 grid max-w-lg gap-5 lg:max-w-none lg:grid-cols-3 sm:grid-cols-2">
                    @forelse ($rows as $row)
                    <article class="relative isolate flex flex-col justify-end overflow-hidden rounded-2xl bg-gray-900 px-8 pb-8 pt-80 sm:pt-48 lg:pt-80">
                        <img src="@include('components.icon.preload-image')" data-src="{{ $row->cover }}" alt="" class="absolute inset-0 -z-10 h-full w-full object-cover">
                        <div class="absolute inset-0 -z-10 bg-gradient-to-t from-gray-900 via-gray-900/5"></div>
                        <div class="absolute inset-0 -z-10 rounded-2xl ring-1 ring-inset ring-gray-900/10"></div>
                
                        <div class="flex flex-wrap items-center gap-y-1 overflow-hidden text-sm leading-6 text-gray-300">
                          <time datetime="2020-03-16" class="mr-8">{{date('d-m-Y', strtotime($row->dateUpdated));}}</time>
                          <div class="-ml-4 flex items-center gap-x-4">
                            <svg viewBox="0 0 2 2" class="-ml-0.5 h-0.5 w-0.5 flex-none fill-white/50">
                              <circle cx="1" cy="1" r="1" />
                            </svg>
                            <div class="flex gap-x-2.5">
                              <img src="@include('components.icon.preload-image')" data-src="{{$row->author['avatar'] ?? ''}}" alt="" class="h-6 w-6 flex-none rounded-full bg-white/10">
                              {{$row->author['name']??''}}
                            </div>
                          </div>
                        </div>
                        <h3 class="mt-3 text-lg font-semibold leading-6 text-white">
                          <a href="{{route('reportage',['slug'=>$row->slug])}}">
                            <span class="absolute inset-0"></span>
                            {{$row->title}}
                          </a>
                        </h3>
                      </article>
                    @empty
                        <p>nothing found </p>
                    @endforelse
                </div>
                <div class="pt-10 flex text-center w-full justify-center items-center content-center">
                    {{ $rows->links('pagination') }}
                </div>
            </div>
        </div>
    </div>
</div>