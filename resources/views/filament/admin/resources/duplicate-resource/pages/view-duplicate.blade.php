<x-filament-panels::page>
    @php
        $duplicate = $this->getRecord();
        $original = $duplicate->originalTalent;
        $newTalent = $duplicate->duplicateTalent;
        
        $fields = $duplicate->matching_fields ?? [];
        if (is_string($fields)) {
            $decoded = json_decode($fields, true);
            $fields = is_array($decoded) ? $decoded : [$fields];
        } elseif (!is_array($fields)) {
            $fields = [$fields];
        }
        
        $fields = collect($fields)->flatten()->toArray();

        $labels = [
            'email' => 'البريد الإلكتروني',
            'phone' => 'رقم الهاتف',
            'linkedin_url' => 'رابط لينكد إن',
            'full_name' => 'الاسم',
            'current_company' => 'الشركة الحالية',
        ];
        $matchingText = collect($fields)->map(fn ($f) => $labels[$f] ?? $f)->implode(' + ');
    @endphp

    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        
        {{-- Match Info --}}
        <x-filament::section icon="heroicon-o-information-circle" icon-color="primary">
            <x-slot name="heading">
                معلومات التطابق
            </x-slot>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
                <div>
                    <span class="text-sm text-gray-500 block" style="margin-bottom: 0.25rem;">نسبة التشابه</span>
                    <x-filament::badge color="{{ $duplicate->confidence >= 90 ? 'danger' : 'warning' }}" size="lg">
                        {{ $duplicate->confidence }}%
                    </x-filament::badge>
                </div>
                <div>
                    <span class="text-sm text-gray-500 block" style="margin-bottom: 0.25rem;">أوجه التطابق</span>
                    <x-filament::badge color="info" size="lg">
                        {{ $matchingText ?: 'غير محدد' }}
                    </x-filament::badge>
                </div>
                <div>
                    <span class="text-sm text-gray-500 block" style="margin-bottom: 0.25rem;">حالة المراجعة</span>
                    <x-filament::badge color="{{ $duplicate->status->getColor() }}" size="lg">
                        {{ $duplicate->status->getLabel() }}
                    </x-filament::badge>
                </div>
            </div>
        </x-filament::section>

        {{-- Comparison Grid --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
            
            {{-- Original --}}
            <x-filament::section icon="heroicon-o-user" icon-color="gray">
                <x-slot name="heading">
                    المرشح الأصلي (الموجود مسبقاً)
                </x-slot>

                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <div>
                        <span class="text-sm text-gray-500 block">الاسم</span>
                        <strong class="text-gray-900 dark:text-white">{{ $original->full_name }}</strong>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500 block">البريد الإلكتروني</span>
                        <span class="text-gray-900 dark:text-gray-300 {{ in_array('email', $fields) ? 'text-danger-600 dark:text-danger-400 font-bold' : '' }}">
                            {{ $original->email }}
                        </span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500 block">رقم الهاتف</span>
                        <span class="text-gray-900 dark:text-gray-300 {{ in_array('phone', $fields) ? 'text-danger-600 dark:text-danger-400 font-bold' : '' }}">
                            {{ $original->phone }}
                        </span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500 block">رابط لينكد إن</span>
                        @if($original->linkedin_url)
                            <a href="{{ $original->linkedin_url }}" target="_blank" class="text-primary-600 hover:underline">
                                {{ $original->linkedin_url }}
                            </a>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </div>
                    <div>
                        <span class="text-sm text-gray-500 block">تاريخ الإضافة</span>
                        <span class="text-gray-900 dark:text-gray-300">{{ $original->created_at->format('d/m/Y g:i A') }}</span>
                    </div>
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700" style="padding-top: 1rem; border-top: 1px solid var(--gray-200);">
                        <x-filament::button tag="a" href="{{ App\Filament\Admin\Resources\TalentResource::getUrl('view', ['record' => $original->id]) }}" target="_blank" color="gray" icon="heroicon-m-arrow-top-right-on-square">
                            عرض الملف الكامل للمرشح الأصلي
                        </x-filament::button>
                    </div>
                </div>
            </x-filament::section>

            {{-- Duplicate --}}
            <x-filament::section icon="heroicon-o-user-plus" icon-color="danger">
                <x-slot name="heading">
                    المرشح الجديد (المشتبه به)
                </x-slot>

                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <div>
                        <span class="text-sm text-gray-500 block">الاسم</span>
                        <strong class="text-gray-900 dark:text-white">{{ $newTalent->full_name }}</strong>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500 block">البريد الإلكتروني</span>
                        <span class="text-gray-900 dark:text-gray-300 {{ in_array('email', $fields) ? 'text-danger-600 dark:text-danger-400 font-bold' : '' }}">
                            {{ $newTalent->email }}
                        </span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500 block">رقم الهاتف</span>
                        <span class="text-gray-900 dark:text-gray-300 {{ in_array('phone', $fields) ? 'text-danger-600 dark:text-danger-400 font-bold' : '' }}">
                            {{ $newTalent->phone }}
                        </span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500 block">رابط لينكد إن</span>
                        @if($newTalent->linkedin_url)
                            <a href="{{ $newTalent->linkedin_url }}" target="_blank" class="text-primary-600 hover:underline">
                                {{ $newTalent->linkedin_url }}
                            </a>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </div>
                    <div>
                        <span class="text-sm text-gray-500 block">تاريخ الإضافة</span>
                        <span class="text-gray-900 dark:text-gray-300">{{ $newTalent->created_at->format('d/m/Y g:i A') }}</span>
                    </div>
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700" style="padding-top: 1rem; border-top: 1px solid var(--gray-200);">
                        <x-filament::button tag="a" href="{{ App\Filament\Admin\Resources\TalentResource::getUrl('view', ['record' => $newTalent->id]) }}" target="_blank" color="gray" icon="heroicon-m-arrow-top-right-on-square">
                            عرض الملف الكامل للمرشح الجديد
                        </x-filament::button>
                    </div>
                </div>
            </x-filament::section>

        </div>
    </div>
</x-filament-panels::page>
