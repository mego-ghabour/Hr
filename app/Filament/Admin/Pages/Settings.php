<?php

namespace App\Filament\Admin\Pages;

use App\Models\Setting;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.admin.pages.settings';

    protected static ?string $cluster = \App\Filament\Clusters\Settings\SettingsCluster::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string|\UnitEnum|null $navigationGroup = 'الإعدادات العامة';

    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return 'إعدادات النظام';
    }

    public function getTitle(): string
    {
        return 'إعدادات النظام العامة';
    }

    public function getSubheading(): ?string
    {
        return 'تتحكم في الهوية البصرية للنظام واسم الشركة، بالإضافة إلى إعدادات حسابك الشخصي.';
    }

    public ?array $systemData = [];
    public ?array $passwordData = [];

    public function mount(): void
    {
        $logoPath = Setting::get('logo');
        if ($logoPath && ! \Illuminate\Support\Facades\Storage::disk('public')->exists($logoPath)) {
            $logoPath = null;
        }

        $this->systemForm->fill([
            'brand_name' => Setting::get('brand_name', 'نظام إدارة المرشحين'),
            'logo'       => $logoPath,
        ]);

        $this->passwordForm->fill();
    }

    public function systemForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                        TextInput::make('brand_name')
                            ->label('اسم النظام')
                            ->placeholder('نظام إدارة المرشحين')
                            ->required()
                            ->maxLength(100),
                        FileUpload::make('logo')
                            ->label('اللوجو')
                            ->image()
                            ->disk('public')
                            ->directory('logo')
                            ->maxSize(2048)
                            ->columnSpanFull(),
            ])
            ->statePath('systemData');
    }

    public function passwordForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                        TextInput::make('current_password')
                            ->label('كلمة المرور الحالية')
                            ->password()
                            ->revealable()
                            ->required(fn () => filled($this->passwordData['new_password'] ?? null))
                            ->currentPassword()
                            ->autocomplete('current-password'),

                        TextInput::make('new_password')
                            ->label('كلمة المرور الجديدة')
                            ->password()
                            ->revealable()
                            ->rule(Password::min(8))
                            ->autocomplete('new-password'),

                        TextInput::make('new_password_confirmation')
                            ->label('تأكيد كلمة المرور')
                            ->password()
                            ->revealable()
                            ->same('new_password')
                            ->requiredWith('new_password')
                            ->autocomplete('new-password'),
            ])
            ->statePath('passwordData');
    }

    protected function getForms(): array
    {
        return [
            'systemForm',
            'passwordForm',
        ];
    }

    public function saveSystem(): void
    {
        $data = $this->systemForm->getState();

        Setting::set('brand_name', $data['brand_name'] ?? 'نظام إدارة المرشحين');
        Setting::set('logo', $data['logo'] ?? null);

        Notification::make()
            ->title('تم حفظ إعدادات النظام بنجاح')
            ->success()
            ->send();
    }

    public function savePassword(): void
    {
        $data = $this->passwordForm->getState();

        if (blank($data['new_password'] ?? null)) {
            return;
        }

        Auth::user()->update([
            'password' => Hash::make($data['new_password']),
        ]);

        $this->passwordForm->fill();

        Notification::make()
            ->title('تم تحديث كلمة المرور بنجاح')
            ->success()
            ->send();
    }
}
