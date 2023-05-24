<div x-init="Livewire.on('nameChanged{{$regionName->lang}}', translation => {
     @this.set('regionName.name', translation.name);
     @this.set('regionName.slug', translation.slug);
     @this.set('regionName.path', translation.path);
 });"  class="sm:grid sm:grid-cols-5   sm:gap-4 sm:items-start ">
    <div>
        
    </div>
    <div class="flex flex-col sm:flex-row justify-between sm:space-x-2 col-span-4 sm:col-span-4 lg:col-span-3">
        <div class="justify-self-start basis-4/12  ">
            <x-input.group   label="Title" for="name_{{$regionName->lang}}" hidden="{{$lang!=$regionName->lang?1:0}}" :error="$errors->first('regionName.name')">
                <x-input.text 
                    wire:model="regionName.name"  
                    id="name_{{$regionName->lang}}" 
                    wire:keyUp="$emitSelf('setName','{{$lang}}',$event.target.value)" 
                    wire:change="$emitUp('setName','{{$lang}}',$event.target.value)" 
                    x-ref="name_{{$regionName->lang}}">
                    <x-slot:leadingIcon>
                        <x-icon.edit class="flex-shrink-0 text-sky-500"/>
                    </x-slot:leadingIcon>
                </x-input.text>
            </x-input.group>
        </div>
        <div class="justify-self-start basis-4/12  ">
            <x-input.group   label="Slug" for="slug_{{$regionName->lang}}" hidden="{{$lang!=$regionName->lang?1:0}}" :error="$errors->first('regionName.slug')">
                <x-input.text 
                    wire:model="regionName.slug"  
                    x-ref="slug_{{$regionName->lang}}"
                    id="slug_{{$regionName->lang}}"  >
                    <x-slot:leadingIcon>
                        <x-icon.edit class="flex-shrink-0 text-sky-500"/>
                    </x-slot:leadingIcon>
                </x-input.text>
            </x-input.group>
        </div>
        <div class="justify-self-start basis-4/12  ">
            <x-input.group   label="Path" for="path_{{$regionName->lang}}" hidden="{{$lang!=$regionName->lang?1:0}}" :error="$errors->first('regionName.path')">
                <x-input.text 
                    wire:model="regionName.path"  
                    x-ref="path_{{$regionName->lang}}"
                    id="path_{{$regionName->lang}}"  >
                    <x-slot:leadingIcon>
                        <x-icon.edit class="flex-shrink-0 text-sky-500"/>
                    </x-slot:leadingIcon>
                </x-input.text>
            </x-input.group>
        </div>
    </div>
</div>