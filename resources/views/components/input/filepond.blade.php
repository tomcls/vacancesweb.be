<div
        wire:ignore
        x-data={pond:null}
        x-init="
        window.FilePond.setOptions({
            allowMultiple:   'true',
            allowImageExifOrientation: true,
            server: {
                process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                    @this.upload('{{ $attributes['wire:model'] }}', file, load, error, progress) },
                    revert: (filename, load) => {
                        @this.removeUpload('{{ $attributes['wire:model'] }}', filename, load)
                    },
                },
                onprocessfiles: () => {
                    Livewire.emit('saveImages');
                    pond.removeFiles();
                }
            });
            pond = window.FilePond.create($refs.input);"

        >
    <input type="file" x-ref="input" />
</div>