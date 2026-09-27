<x-filament-panels::page>
    <div class="space-y-6">

        {{-- System Form --}}
        <x-filament::section icon="heroicon-o-building-office" icon-color="primary">
            <x-slot name="heading">الهوية البصرية</x-slot>
            <x-slot name="description">اسم النظام واللوجو الذي يظهر في الأعلى</x-slot>

            <form wire:submit="saveSystem">
                {{ $this->systemForm }}

                <div style="margin-top: 2rem; display: flex; justify-content: flex-start;">
                    <x-filament::button type="submit" icon="heroicon-o-check" color="primary" size="lg">
                        حفظ التعديلات
                    </x-filament::button>
                </div>
            </form>
        </x-filament::section>

        <div style="height: 2rem;"></div>

        {{-- Password Form --}}
        <x-filament::section icon="heroicon-o-lock-closed" icon-color="warning" collapsible collapsed>
            <x-slot name="heading">تغيير كلمة المرور</x-slot>
            <x-slot name="description">قم بتحديث كلمة مرور حسابك</x-slot>

            <form wire:submit="savePassword">
                {{ $this->passwordForm }}

                <div style="margin-top: 2rem; display: flex; justify-content: flex-start;">
                    <x-filament::button type="submit" icon="heroicon-o-lock-closed" color="warning" size="lg">
                        تحديث كلمة المرور
                    </x-filament::button>
                </div>
            </form>
        </x-filament::section>

    </div>
</x-filament-panels::page>
