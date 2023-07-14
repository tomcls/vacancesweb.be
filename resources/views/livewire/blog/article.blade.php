<div x-init="loadImages();" class="flex min-h-full flex-col">
    <div class="relative isolate overflow-hidden pt-14 h-[60vh] ">
        <img src="@include('components.icon.preload-image')" data-src="{{ $article->cover }}" alt="{{ $article->title }}"
            class="absolute inset-0 -z-10 sm:h-full w-full object-cover" />
            
        <div class=" flex-row absolute bottom-0 w-full  h-24 justify-center">
            <div class="absolute inset-0 -z-10 bg-black opacity-30"></div>
            <div class="max-w-6xl text-left mx-auto">
                <div class="flex flex-row justify-start space-x-2 content-center">
                    <h1 class="text-3xl font-bold tracking-tight text-white sm:text-3xl p-3 py-6">{!! $article->title !!}</h1>
                    <x-icon.guide class="text-sky-500 h-20 w-16 pt-5" />
                    <label class="w-56 text-xl text-white pt-9"> Suivez le guide</label>
                </div>
            </div>
        </div>
    </div>
    <!-- 3 column wrapper -->
    <div class="mx-auto w-full max-w-7xl grow  lg:flex xl:px-2">
        <!-- Left sidebar & main wrapper -->
        <div class="flex-1 xl:flex">
            <div class="px-4 py-6 sm:px-6 lg:pl-8 xl:flex-1 xl:pl-6">
                <!-- Main area -->
                <div class="mx-auto mt-4 max-w-lg gap-5 lg:max-w-none font-light text-lg">
                    <div class="flex flex-row justify-between">
                        <div class="text-sm font-bold justify-center content-center pt-5">
                           <span class="text-gray-400">Dernière modifiation:</span> <span class="text-gray-600"> {{date('d-m-Y', strtotime($article->dateUpdated))}}</span>
                        </div>
                        <div >
                            <div class="flex flex-col">
                                <span class="relative inline-block">
                                    <img class="h-16 w-16 rounded-full ring-2 ring-gray-300" data-src="{{$article->author['avatar']}}" alt="{{$article->author['name']}}">
                              </span>
                              <label class="text-xs">{{$article->author['name']}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-12">
                        {!! $article->subtitle !!}
                    </div>
                    <div class="font-light text-lg">
                        {!! $content !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="shrink-0  px-4 py-6 sm:px-6 lg:w-96  lg:pr-8 xl:pr-6">
            <!-- -->
            @livewire('blog.related-offers', ['tags' => count($tagSlugs) ? $tagSlugs : null], key('relatedOffers'))
        </div>
    </div>

  <div class="container mx-auto">
      <!-- related articles -->
      <h2 class="text-blue-950 text-3xl inline-block my-4 font-bold">Lisez aussi</h2>
      <div class="mx-auto mt-12 grid max-w-lg gap-5 lg:max-w-none lg:grid-cols-3 sm:grid-cols-2">
        @forelse ($related as $row)
        <article class="relative isolate flex flex-col justify-end overflow-hidden rounded-2xl bg-gray-900 px-8 pb-8 pt-80 sm:pt-48 lg:pt-80">
          <img src="@include('components.icon.preload-image')" data-src="{{ $row->image }}" alt="" class="absolute inset-0 -z-10 h-full w-full object-cover">
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
            <a href="{{route('article',['slug'=>$row->slug])}}">
              <span class="absolute inset-0"></span>
              {{$row->title}}
            </a>
          </h3>
        </article>
        @empty
            <p>nothing found </p>
        @endforelse
      </div>
  </div>
</div>
