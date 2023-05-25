<div class="pt-10"
    x-data="{
        initDragAndDrop: function() {
            let root = document.querySelector('[drag-root]');
            root.querySelectorAll('[drag-item]').forEach(el => {
            el.addEventListener('dragstart', e => {
                e.target.setAttribute('dragging', true);
            });
            el.addEventListener('drop', e => {
                let draggingEl = root.querySelector('[dragging]')

                if(e.target.nodeName === 'DIV' && e.target.classList.contains('w-full')) {
                    e.target.parentElement.parentElement.parentElement.before(draggingEl)
                } else if(e.target.nodeName === 'DIV' && e.target.classList.contains('text-container')) {
                    e.target.parentElement.parentElement.before(draggingEl)
                } else if(e.target.nodeName === 'DIV' ) {
                    e.target.parentElement.before(draggingEl)
                } else {
                    e.target.before(draggingEl)
                }
                let component = window.Livewire.find(
                    e.target.closest('[wire\\:id]').getAttribute('wire:id')
                );
                let orderIds = Array.from(root.querySelectorAll('[drag-item]'))
                    .map(itemEl => itemEl.getAttribute('drag-item'));
                window.Livewire.emit('reorder',orderIds);
            });

            el.addEventListener('dragenter', e => {
                if(e.target.nodeName === 'DIV' && e.target.classList.contains('text-container')) {
                    e.target.parentElement.getElementsByTagName('IMG')[0].classList.add('opacity-50')
                } else {
                    let el = e.target.getElementsByTagName('img')[0];
                    if(el && !el.classList.contains('opacity-50')) {
                    el.classList.add('opacity-50');
                    }
                }
                e.preventDefault()
            });

            el.addEventListener('dragover', e => e.preventDefault());

            el.addEventListener('dragleave', e => {});

            el.addEventListener('dragend', e => { e.target.removeAttribute('dragging')});
            });
        }
    }"
    x-init="initDragAndDrop();">
    <x-input.filepond wire:model='files' />

    <div class="mx-auto max-w-2xl px-4  sm:px-6  lg:max-w-7xl lg:px-8">
      <ul drag-root="reorder" class="overflow-hidden ">
        @forelse($images as $image)
            <li drag-item="{{ $image->id }}" draggable="true" wire:key="{{ $image->id }}" 
              class="w-64 p-4  inline-block group relative">
                <div class="aspect-h-3 aspect-w-4 overflow-hidden rounded-lg bg-gray-100">
                    <img src="{{$image->url('small')}}" class="object-cover object-center">
                    <div class="text-container flex items-end p-4 opacity-0 group-hover:opacity-100" aria-hidden="true">
                      <div class="w-full rounded-md bg-white bg-opacity-75 px-4 py-2 text-center text-sm font-medium text-gray-900 backdrop-blur backdrop-filter">
                        <x-button.round wire:click.prevent="delete({{$image->id}})"> 
                          <x-icon.trash />
                        </x-button.round>
                        <x-button.round wire:click.prevent="rotate({{$image->id}})"> 
                          <x-icon.rotate />
                        </x-button.round>
                      </div>
                    </div>
                </div>
            </li>
            @empty
                <x-table.row>
                    <x-table.cell colspan="7">
                        <div class="flex justify-center items-center space-x-2">
                            <x-icon.image class="h-8 w-8 text-cool-gray-400" />
                            <span class="font-medium py-8 text-cool-gray-400 text-xl">No image found...</span>
                        </div>
                    </x-table.cell>
                </x-table.row>
            @endforelse
      </ul>
    </div>
</div>