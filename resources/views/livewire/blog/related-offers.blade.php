@php
    $counter = 1;
@endphp
<div class="space-y-2 pt-12">
    {{-- The Master doesn't talk, he acts. --}}
    @forelse ($houses as $row)
        @if($counter < 3)
        <div class="group relative flex flex-col overflow-hidden rounded-lg border border-gray-200 bg-white">
            <div class="aspect-h-3 aspect-w-4 bg-gray-200 sm:aspect-none group-hover:opacity-75 h-52">
                <img src="@include('components.icon.preload-image')" data-src="{{ config('app.url').'/houses/images/'.$row->house_id.'/'. $row->cover }}" alt=""
                    class="h-full w-full object-cover object-center sm:h-full sm:w-full">
            </div>
            <div class="flex flex-1 flex-col space-y-2 p-4">
                <h2 class="text-sm font-medium text-gray-900">
                    <a href="#">
                        <span aria-hidden="true" class=" inset-0"></span>
                        {{ $row->id }} {{ $row->house_name }}
                    </a>
                </h2>
                {{-- <p class="text-sm text-gray-500">Get the full lineup of our Basic Tees. Have a fresh shirt all week, and an extra for laundry day.</p> --}}
                <div class="flex flex-1 flex-row justify-between w-full">
                    <span class="text-sm italic text-gray-500 px-0 mx-0">
                        <x-icon.map class="text-xs h-3 w-3 pr-1 mr-0 text-sky-500" /> {{ $row->region_name }}
                    </span>
                    <p class="text-sm italic text-gray-500"></p>
                    <p class="text-sm italic text-gray-500">{{ $row->house_type_name }}</p>
                </div>
                <div class="flex flex-1 flex-row justify-between w-full">
                    <p class="text-sm italic text-gray-500">{{ $row->rooms }} chambres</p>
                    <p class="text-sm italic text-gray-500">{{ $row->number_people }} personnes</p>
                    <p class="text-sm italic text-gray-500">{{ $row->acreage }} m2</p>
                </div>
                <div class="flex flex-1 flex-row justify-between w-full space-x-1">
                    <div class="flex flex-1 flex-row  text-left justify-between">
                        <p class="text-base font-medium text-gray-900">€{{ $row->week_price }} / semaine</p>
                        <p class="text-sm italic  text-gray-700">{{ $row->min_nights }} nuits min.</p>
                    </div>
                </div>
            </div>
        </div>
            @php
            $counter ++;
            @endphp
        @endif
    @empty

    @endforelse

    @if($counter < 5)
        @forelse ($holidays as $row)
        <div class="group relative flex flex-col overflow-hidden rounded-lg border border-gray-200 bg-white">
            <div class="aspect-h-3 aspect-w-4 bg-gray-200 sm:aspect-none group-hover:opacity-75 h-52">
                <img src="@include('components.icon.preload-image')" data-src="{{ config('app.url').'/holidays/images/'.$row->holiday_id.'/'. $row->cover }}" alt=""
                    class="h-full w-full object-cover object-center sm:h-full sm:w-full">
            </div>
            <div class="flex flex-1 flex-col space-y-2 p-4">
                <h2 class="text-sm font-medium text-gray-900">
                    <a href="#">
                        <span aria-hidden="true" class=" inset-0"></span>
                        {{ $row->id }} {{ $row->holiday_name }}
                    </a>
                </h2>
                {{-- <p class="text-sm text-gray-500">Get the full lineup of our Basic Tees. Have a fresh shirt all week, and an extra for laundry day.</p> --}}
                <div class="flex flex-1 flex-row justify-between w-full">
                    <span class="text-sm italic text-gray-500 px-0 mx-0">
                        <x-icon.map class="text-xs h-3 w-3 pr-1 mr-0 text-sky-500" /> {{ $row->region_name }}
                    </span>
                    <p class="text-sm italic text-gray-500"></p>
                    <p class="text-sm italic text-gray-500">{{ $row->holiday_type_name }}</p>
                </div>
                <div class="flex flex-1 flex-row justify-between w-full">
                    <p class="text-sm italic text-gray-500">{{ $row->departure_from }} </p>
                    <p class="text-sm italic text-gray-500">{{ $row->departure_date }} </p>
                </div>
                <div class="flex flex-1 flex-row justify-between w-full space-x-1">
                    <div class="flex flex-1 flex-row  text-left justify-between">
                        <span class="text-base font-medium text-gray-900">€{{ $row->price_customer }} €</p>
                        <span class="text-base font-medium text-gray-900">{{ $row->duration_days }} jours /</span>
                        <span class="text-sm italic  text-gray-700">{{ $row->duration_nights }} nuits</span>
                    </div>
                </div>
            </div>
        </div>
            @php
            $counter ++;
            @endphp
        
        @empty
        
        @endforelse
    @endif
</div>
