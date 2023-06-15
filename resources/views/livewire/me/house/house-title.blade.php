<div >
    <x-input.group  inline label="Title" for="name_{{$houseTitle->lang}}" hidden="{{$lang!=$houseTitle->lang?1:0}}" :error="$errors->first('houseTitle.name')">
        <x-input.text 
            wire:model="houseTitle.name"  
            id="name_{{$houseTitle->lang}}" 
            wire:keyUp="$emitSelf('setTitle','{{$lang}}',$event.target.value)" 
            wire:change="$emitUp('setTitle','{{$lang}}',$event.target.value)" 
            x-ref="title">
            <x-slot:leadingIcon>
                <x-icon.edit class="flex-shrink-0 text-sky-500"/>
            </x-slot:leadingIcon>
        </x-input.text>
      </x-input.group>
</div>
