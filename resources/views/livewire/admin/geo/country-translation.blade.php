<div x-init="Livewire.on('nameChanged{{$countryName->lang}}', translation => {
    @this.set('countryName.name', translation.name);
    @this.set('countryName.slug', translation.slug);
});" class="sm:grid sm:grid-cols-5   sm:gap-4 sm:items-start ">
    <div>
        
    </div>
    <div class="flex flex-col sm:flex-row justify-between sm:space-x-2 col-span-4 sm:col-span-4 lg:col-span-3">
        <div class="justify-self-start basis-6/12  ">
            <x-input.group label="Title" for="name_{{$countryName->lang}}" hidden="{{$lang!=$countryName->lang?1:0}}" :error="$errors->first('countryName.name')">
                <x-input.text 
                    wire:model="countryName.name"  
                    id="name_{{$countryName->lang}}" 
                    wire:keyUp="$emitSelf('setName','{{$lang}}',$event.target.value)" 
                    wire:change="$emitUp('setName','{{$lang}}',$event.target.value)" 
                    x-ref="name_{{$countryName->lang}}">
                    <x-slot:leadingIcon>
                        <x-icon.edit class="flex-shrink-0 text-sky-500"/>
                    </x-slot:leadingIcon>
                </x-input.text>
            </x-input.group>
        </div>
        <div class="justify-self-start basis-6/12  ">
            <x-input.group label="Slug" for="slug_{{$countryName->lang}}" hidden="{{$lang!=$countryName->lang?1:0}}" :error="$errors->first('countryName.slug')">
                <x-input.text 
                    wire:model="countryName.slug"  
                    x-ref="slug_{{$countryName->lang}}"
                    id="slug_{{$countryName->lang}}"  >
                    <x-slot:leadingIcon>
                        <x-icon.edit class="flex-shrink-0 text-sky-500"/>
                    </x-slot:leadingIcon>
                </x-input.text>
            </x-input.group>
        </div>
    </div>
</div>
