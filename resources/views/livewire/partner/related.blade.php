<div>
    <h2 class="text-center text-3xl font-bold my-10">Saviez-vous que ?</h2>
    <div class="mx-auto mt-12 grid max-w-lg gap-5 lg:max-w-none lg:grid-cols-3 sm:grid-cols-2">
    @forelse ($related as $row)
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
        <a href="{{route('article',['slug'=>$row->slug])}}">
            <span class="absolute inset-0"></span>
            {{$row->title}}
        </a>
        </h3>
    </article>
    @empty
       
    @endforelse
    </div>
</div>
