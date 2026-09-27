<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;

class HelpGuide extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-question-mark-circle';

    protected string $view = 'filament.admin.pages.help-guide';

    protected static string|\UnitEnum|null $navigationGroup = 'التعليمات والمساعدة';

    protected static ?int $navigationSort = 10;

    public static function getNavigationLabel(): string
    {
        return 'دليل الاستخدام';
    }

    public function getTitle(): string
    {
        return 'دليل استخدام النظام';
    }
}