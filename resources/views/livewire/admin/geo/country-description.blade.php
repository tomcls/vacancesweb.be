<div>
    <x-input.group  label="" for="description_{{$keyLang.$keyType}}" hidden="{{($lang==$keyLang && $type == $keyType )?0:1}}" help-text="Write the description of the Holiday"  :error="$errors->first('description')">
        <x-input.suneditor wire:model.defer="countryDescription.description" id="description_{{$keyLang.$keyType}}" />
    </x-input.group>
</div>