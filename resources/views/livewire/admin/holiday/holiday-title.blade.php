<div >
    <x-input.group  inline label="Title" for="name_{{$holidayTitle->lang}}" hidden="{{$lang!=$holidayTitle->lang?1:0}}" :error="$errors->first('holidayTitle.name')">
        <x-input.text 
            wire:model="holidayTitle.name"  
            id="name_{{$holidayTitle->lang}}" 
            wire:keyUp="$emitSelf('setTitle','{{$lang}}',$event.target.value)" 
            wire:change="$emitUp('setTitle','{{$lang}}',$event.target.value)" 
            x-ref="title">
            <x-slot:leadingIcon>
                <x-icon.edit class="flex-shrink-0 text-sky-500"/>
            </x-slot:leadingIcon>
        </x-input.text>
      </x-input.group>
      <x-input.group  inline label="Slug" for="slug_{{$holidayTitle->lang}}" hidden="{{$lang!=$holidayTitle->lang?1:0}}" :error="$errors->first('holidayTitle.slug')">
        <x-input.text 
            wire:model="holidayTitle.slug"  
            x-ref="slug"
            id="slug_{{$holidayTitle->lang}}"  >
            <x-slot:leadingIcon>
                <x-icon.edit class="flex-shrink-0 text-sky-500"/>
            </x-slot:leadingIcon>
        </x-input.text>
      </x-input.group>
      <x-input.group  inline label="Privilege" for="privilege_{{$holidayTitle->lang}}" hidden="{{$lang!=$holidayTitle->lang?1:0}}" :error="$errors->first('holidayTitle.privilege')">
        <x-input.text 
            wire:model="holidayTitle.privilege"  
            x-ref="privilege"
            id="privilege_{{$holidayTitle->lang}}"  >
            <x-slot:leadingIcon>
                <x-icon.edit class="flex-shrink-0 text-sky-500"/>
            </x-slot:leadingIcon>
        </x-input.text>
      </x-input.group>
</div>
