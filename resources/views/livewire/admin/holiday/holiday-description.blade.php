<div>
    <x-input.group  inline label="Description" for="description_{{$holidayDescription->lang}}" hidden="{{$lang!=$holidayDescription->lang?1:0}}" help-text="Write the description of the Holiday"  :error="$errors->first('description')">
        <x-input.suneditor wire:model.defer="holidayDescription.description" id="description_{{$holidayDescription->lang}}" />
    </x-input.group>
</div>
