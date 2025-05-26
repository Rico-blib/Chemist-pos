<form wire:submit.prevent="save" class="grid grid-cols-1 md:grid-cols-2 gap-4">

    <div class="space-y-1">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Medicine Name</label>
        <x-input wire:model.defer="name" required />
    </div>

    <div class="space-y-1">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Category</label>
        <x-input wire:model.defer="category" />
    </div>

    <div class="space-y-1">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Manufacturer</label>
        <x-input wire:model.defer="manufacturer" />
    </div>

    <div class="space-y-1">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Price</label>
        <x-input wire:model.defer="price" type="number" step="0.01" />
    </div>

    <div class="space-y-1">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Quantity</label>
        <x-input wire:model.defer="quantity" type="number" />
    </div>

    <div class="space-y-1">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Expiry Date</label>
        <x-input wire:model.defer="expiry_date" type="date" />
    </div>

    <div class="sm:col-span-2 flex justify-end gap-2 pt-4">
        <x-button type="button" secondary wire:click="$dispatch('formSubmitted')">
            Cancel
        </x-button>
        <x-button type="submit" primary>
            Save
        </x-button>
    </div>
</form>
