<div class="mt-4">
    <form wire:submit.prevent="save">
        <div class="sm:border-b border-gray-200 sm:pb-5 sm:flex sm:items-center sm:justify-between">
            <h3 class="text-base font-semibold leading-6 text-gray-900">Amenities / Attributes</h3>
            <div class="mt-3 sm:ml-4 sm:mt-0">
              <div class="flex rounded-md shadow-sm">
                <button type="submit" wire:click="create" class=" rounded relative -ml-px sm:inline-flex items-center gap-x-1.5  px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                  Save
                  <x-icon.right class="text-gray-900 bg-gray-50" />
                </button>
              </div>
            </div>
        </div>
        <div class="flex justify-between  ">
            <div class="justify-self-start">
                <h2 class="text-base font-semibold leading-7 text-gray-900 pt-10">Comforts</h2>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-2 sm:grid-cols-4 sm:gap-4 mt-4">
            @foreach ($comforts as $amenity)
                <div class="basis-1/4">
                    <x-input.checkbox wire:model="houseAmenities"  value="{{$amenity->amenity->id}}" label="{{$amenity->name}}" id="{{$amenity->amenity->code}}" />
                </div>
            @endforeach
        </div>
        <div class="flex justify-between  ">
            <div class="justify-self-start">
            <h2 class="text-base font-semibold leading-7 text-gray-900 pt-10">Classifications</h2>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-2 sm:grid-cols-4 sm:gap-4 mt-4">
            @foreach ($classifications as $index => $amenity)
                <div class="basis-1/4">
                    <x-input.checkbox wire:model="houseAmenities"  value="{{$amenity->amenity->id}}" label="{{$amenity->amenity->id.'  '.$amenity->name}}" id="{{$amenity->amenity->code}}" />
                    <div class="max-w-fit">
                        <x-input.group   label="" for="value" >
                            <x-input.text wire:model.lazy="houseClassifications.{{$amenity->amenity->id}}"  value="{{$houseClassifications[$amenity->amenity->id]??null}}" placeHolder="value" />
                          </x-input.group>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="flex justify-between  ">
            <div class="justify-self-start">
            <h2 class="text-base font-semibold leading-7 text-gray-900 pt-10">Services</h2>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-2 sm:grid-cols-4 sm:gap-4 mt-4">
            @foreach ($services as $amenity)
                <div class="basis-1/4">
                    <x-input.checkbox wire:model="houseAmenities"  value="{{$amenity->amenity->id}}" label="{{$amenity->name}}" id="{{$amenity->amenity->code}}" />
                </div>
            @endforeach
        </div>
        <div class="flex justify-between  ">
            <div class="justify-self-start">
            <h2 class="text-base font-semibold leading-7 text-gray-900 pt-10">Options</h2>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-2 sm:grid-cols-4 sm:gap-4 mt-4">
            @foreach ($options as $amenity)
                <div class="basis-1/4">
                    <x-input.checkbox wire:model="houseAmenities"  value="{{$amenity->amenity->id}}" label="{{$amenity->name}}" id="{{$amenity->amenity->code}}" />
                </div>
            @endforeach
        </div>
        <div class="flex justify-between  ">
            <div class="justify-self-start">
            <h2 class="text-base font-semibold leading-7 text-gray-900 pt-10">Around</h2>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-2 sm:grid-cols-4 sm:gap-4 mt-4">
            @foreach ($arounds as $amenity)
                <div class="basis-1/4">
                    <x-input.checkbox wire:model="houseAmenities"  value="{{$amenity->amenity->id}}" label="{{$amenity->name}}" id="{{$amenity->amenity->code}}" />
                </div>
            @endforeach
        </div>
    </form>
</div>
