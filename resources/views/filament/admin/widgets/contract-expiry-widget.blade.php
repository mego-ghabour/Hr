<x-filament-widgets::widget>
    <x-filament::section>
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <div style="width: 2rem; height: 2rem; border-radius: 0.375rem; background-color: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center;">
                    <x-filament::icon icon="heroicon-o-document-text" style="width: 1.25rem; height: 1.25rem;" />
                </div>
                <div>
                    <h2 style="font-size: 1.1rem; font-weight: 700; color: #1f2937; margin: 0;">متابعة انتهاء عقود العمل</h2>
                    <p style="font-size: 0.75rem; color: #6b7280; margin: 0;">تنبيهات فورية بالعقود المنتهية والتي تقترب من الانتهاء (خلال 60 يوماً)</p>
                </div>
            </div>
            <a href="{{ \App\Filament\Admin\Resources\Employees\EmployeeResource::getUrl('index') }}" style="font-size: 0.8rem; font-weight: 600; color: #0d9488; text-decoration: none;">
                استعراض كافة الموظفين &larr;
            </a>
        </div>

        @if(count($expiringEmployees) > 0)
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                @foreach($expiringEmployees as $emp)
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.75rem 1rem; border-radius: 0.5rem; border: 1px solid {{ $emp['is_expired'] ? '#fecaca' : '#fed7aa' }}; background-color: {{ $emp['is_expired'] ? '#fff5f5' : '#fffaf0' }};">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div style="width: 2.25rem; height: 2.25rem; border-radius: 50%; background-color: {{ $emp['is_expired'] ? '#fee2e2' : '#ffedd5' }}; color: {{ $emp['is_expired'] ? '#dc2626' : '#ea580c' }}; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.875rem;">
                                {{ mb_substr($emp['name'], 0, 1) }}
                            </div>
                            <div>
                                <div style="font-weight: 700; color: #111827; font-size: 0.9rem;">{{ $emp['name'] }}</div>
                                <div style="font-size: 0.75rem; color: #6b7280;">{{ $emp['job_title'] }} • {{ $emp['department'] }}</div>
                            </div>
                        </div>

                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="text-align: left;">
                                <div style="font-size: 0.75rem; color: #6b7280;">تاريخ الانتهاء: <strong dir="ltr">{{ $emp['contract_end_date'] }}</strong></div>
                                <div>
                                    @if($emp['is_expired'])
                                        <span style="display: inline-block; padding: 0.15rem 0.6rem; border-radius: 9999px; background-color: #fee2e2; color: #b91c1c; font-size: 0.75rem; font-weight: 700;">
                                            منتهي منذ {{ abs($emp['days_remaining']) }} يوم!
                                        </span>
                                    @else
                                        <span style="display: inline-block; padding: 0.15rem 0.6rem; border-radius: 9999px; background-color: #ffedd5; color: #c2410c; font-size: 0.75rem; font-weight: 700;">
                                            متبقي {{ $emp['days_remaining'] }} يوم
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <a href="{{ \App\Filament\Admin\Resources\Employees\EmployeeResource::getUrl('edit', ['record' => $emp['id']]) }}" style="padding: 0.4rem 0.8rem; border-radius: 0.375rem; background-color: white; border: 1px solid #d1d5db; font-size: 0.8rem; font-weight: 600; color: #374151; text-decoration: none; transition: all 0.2s;">
                                تجديد العقد
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; padding: 1.5rem 0; color: #059669; font-size: 0.9rem; font-weight: 600; background-color: #ecfdf5; border-radius: 0.5rem; border: 1px solid #a7f3d0;">
                جميع عقود العمل سارية المفعول ولا توجد عقود تقترب من الانتهاء حالياً.
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
