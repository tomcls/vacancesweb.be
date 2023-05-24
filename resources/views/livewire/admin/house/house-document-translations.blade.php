<div >
    <x-input.group  inline label="Title" for="name_{{$houseDocument->lang}}" hidden="{{$lang!=$houseDocument->lang?1:0}}" :error="$errors->first('houseDocument.name')">
        <x-input.text 
            wire:model.lazy="houseDocument.name"  
            id="name_{{$houseDocument->lang}}"  
            wire:change="$emitUp('setDocumentName','{{$lang}}',$event.target.value)" 
            x-ref="title">
            <x-slot:leadingIcon>
                <x-icon.edit class="flex-shrink-0 text-indigo-600"/>
            </x-slot:leadingIcon>
        </x-input.text>
      </x-input.group>
</div>
