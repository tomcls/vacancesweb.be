<div class="mt-4">
    <form wire:submit.prevent="save">
        <div class="sm:border-b border-gray-200 sm:pb-5 sm:flex sm:items-center sm:justify-between">
            <h3 class="text-base font-semibold leading-6 text-gray-900">Extra costs</h3>
            <div class="mt-3 sm:ml-4 sm:mt-0">
              <div class="flex rounded-md shadow-sm">
                <button type="submit"  class=" rounded relative -ml-px sm:inline-flex items-center gap-x-1.5  px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                  Save
                  <x-icon.right class="text-gray-900 bg-gray-50" />
                </button>
              </div>
            </div>
        </div>
        <ul role="list" class="mt-3 grid grid-cols-1 gap-5 sm:grid-cols-1 sm:gap-6 md:grid-cols-1 lg:grid-cols-2 xl:grid-cols-2 2xl:grid-cols-3">
        @foreach ($costs as $index => $cost)
            <li class="col-span-1 flex rounded-md shadow-sm">
                <div class="flex flex-1 items-center justify-between truncate rounded-md border-b border border-t border-gray-200 bg-white">
                    <div class="flex-1 truncate px-2 py-2 text-sm">
                    <p class="font-medium text-gray-900 hover:text-gray-600">{{$cost->name}}</p>
                    <p class="text-gray-500"> 
                    @if ($cost->cost->cost_unit)
                        Per
                        {{$cost->cost->cost_unit}}
                    @else 
                        &nbsp;
                    @endif  
                    </p>
                    </div>
                    <div class="flex-shrink-0 pr-2 ">
                            @if ($cost->cost->cost_unit == 'cat_stay_unit' || !$cost->cost->cost_unit)
                            <x-input.select  wire:model.lazy="houseCosts.{{$cost->cost->id}}.cost_unit" style="max-width: 8.8rem;">
                                @if (isset($houseCosts[$cost->cost->id]->cost_unit))
                                    <option value="{{$houseCosts[$cost->cost->id]->cost_unit}}" >{{$houseCosts[$cost->cost->id]->cost_unit}}</option>
                                @endif
                                <option value="" ></option>
                                @foreach (\App\Data\Enums\CostUnitEnum::cases() as $unit)
                                    <option value="{{ $unit }}">{{$unit }}</option>
                                @endforeach
                            </x-input.select>
                            @elseif($cost->cost->cost_unit == 'cat_stay')
                            <x-input.select  wire:model.lazy="houseCosts.{{$cost->cost->id}}.cost_unit" style="max-width: 8.8rem;">
                                @if (isset($houseCosts[$cost->cost->id]->cost_unit))
                                    <option value="{{$houseCosts[$cost->cost->id]->cost_unit}}" >{{$houseCosts[$cost->cost->id]->cost_unit}}</option>
                                @endif
                                <option value="" ></option>
                                @foreach (\App\Data\Enums\CostPeriodUnitEnum::cases() as $unit)
                                    <option value="{{ $unit }}">{{$unit }}</option>
                                @endforeach
                            </x-input.select>
                            @elseif($cost->cost->cost_unit == 'cat_person')
                            <x-input.select  wire:model.lazy="houseCosts.{{$cost->cost->id}}.cost_unit" style="max-width: 8.8rem;">
                                @if (isset($houseCosts[$cost->cost->id]->cost_unit))
                                    <option value="{{$houseCosts[$cost->cost->id]->cost_unit}}" >cccc{{$houseCosts[$cost->cost->id]->cost_unit}}</option>
                                @endif
                                <option value="" ></option>
                                @foreach (\App\Data\Enums\CostPersonUnitEnum::cases() as $unit)
                                    <option value="{{ $unit }}">{{$unit }}</option>
                                @endforeach
                            </x-input.select>
                            @endif
                    </div>
                    <div class="flex-shrink-0 pr-2">
                        <x-input.text  wire:model.lazy="houseCosts.{{$cost->cost->id}}.price" value="{{$houseCosts[$cost->cost->id]->price??null}}" placeHolder="€" style="max-width: 4rem; "  />
                    </div>
                </div>
            </li>
        @endforeach
        </ul>
    </form>
</div>
