<div>
    <x-input.group  inline label="Description" for="description_{{$houseDescription->lang}}" hidden="{{$lang!=$houseDescription->lang?1:0}}" help-text="Write the description of the house"  :error="$errors->first('description')">
        <x-input.suneditor wire:model.defer="houseDescription.description" id="description_{{$houseDescription->lang}}" />
    </x-input.group>
</div>
