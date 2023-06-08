<div>
      <!-- Send contact -->
      <form wire:submit.prevent="send">
        <x-modal.dialog wire:model.defer="showContactModal" class="px-2"> 
            <x-slot name="title">Contactez le propriétaire ou le gestionaire</x-slot>
            <x-slot name="content">
                <div class="flex  flex-row space-x-1 sm:space-x-2 w-full">
                  <x-input.group for="firstname" label="Firstname" :error="$errors->first('contact.firstname')" class="w-1/2">
                      <x-input.text wire:model.lazy="contact.firstname" id="firstname" placeholder="Firstname" />
                  </x-input.group>
  
                  <x-input.group for="lastname" label="Lastname" :error="$errors->first('contact.lastname')" class="w-1/2">
                      <x-input.text wire:model.lazy="contact.lastname" id="lastname" placeholder="Lastname" />
                  </x-input.group>
                </div>
                <div class="flex flex-row space-x-1 sm:space-x-2 pt-2" >
                    <x-input.group for="email" label="email" :error="$errors->first('contact.email')" class="w-1/2">
                        <x-input.text wire:model.lazy="contact.email" id="email" placeholder="email" />
                    </x-input.group>

                    <x-input.group for="phone" label="Phone" :error="$errors->first('contact.phone')" class="w-1/2">
                        <x-input.text wire:model.lazy="contact.phone" id="phone" placeholder="Phone" />
                    </x-input.group>
                </div>
                <div class="flex flex-row space-x-1 sm:space-x-2 pt-2">
                    <x-input.group for="date_from" label="Date d'arrivée" :error="$errors->first('contact.date_from')" class="w-1/2">
                        <x-input.date wire:model.lazy="contact.date_from" id="date_from" placeholder="Date d'arrivée" />
                    </x-input.group>

                    <x-input.group for="stay" label="Nombre de jour" :error="$errors->first('contact.stay')" class="w-1/2">
                        <x-input.text wire:model.lazy="contact.stay" id="stay" placeholder="Nombre de jour" />
                    </x-input.group>
                </div>
                <x-input.group for="numberPeople" label="Nombre de personne" :error="$errors->first('contact.number_people')" class="w-1/2 pt-2">
                    <x-input.text wire:model.lazy="contact.number_people" id="numberPeople" placeholder="Nombre de personne" />
                </x-input.group>

                <x-input.group for="message" label="Votre message" :error="$errors->first('contact.message')" class="w-full pt-2">
                    <x-input.textarea wire:model.lazy="contact.message" id="message" placeholder="Votre message" class="h-36 w-full rounded border-gray-300"/>
                </x-input.group>
            </x-slot>
            <x-slot name="footer">
                <x-button.secondary wire:click="$set('showContactModal', false)">Annuler</x-button.secondary>

                <x-button.primary type="submit">Envoyer</x-button.primary>
            </x-slot>
        </x-modal.dialog>
</div>
