<?php

namespace App\Filament\Admin\Resources\LeaveTypes\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;

class LeaveTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('إعدادات نوع الإجازة')
                    ->description('تحديد اسم الإجازة والرصيد الافتراضي للموظف')
                    ->schema([
                        TextInput::make('name')
                            ->label('اسم نوع الإجازة')
                            ->placeholder('مثال: إجازة سنوية، إجازة مرضية...')
                            ->required(),
                        TextInput::make('default_days_per_year')
                            ->label('الرصيد الافتراضي (بالأيام)')
                            ->helperText('عدد الأيام المخصصة لهذا النوع في السنة الواحدة')
                            ->numeric()
                            ->default(21)
                            ->required(),
                        Toggle::make('requires_approval')
                            ->label('تتطلب موافقة المدير؟')
                            ->helperText('إذا كانت مفعلة، فلن تُعتمد الإجازة إلا بموافقة إدارية')
                            ->default(true),
                    ]),
            ]);
    }
}
