<x-filament-panels::page>
    <form wire:submit="saveTemplates" class="space-y-6">
        
        {{ $this->templatesForm }}

        <div class="flex items-center gap-4 justify-start mt-6">
            <x-filament::button type="submit" icon="heroicon-o-check-circle" color="primary">
                حفظ القوالب
            </x-filament::button>
            <span class="text-sm text-gray-500" wire:loading wire:target="saveTemplates">
                جاري الحفظ...
            </span>
        </div>

    </form>
</x-filament-panels::page>
