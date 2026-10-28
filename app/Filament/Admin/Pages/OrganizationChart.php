<?php

namespace App\Filament\Admin\Pages;

use App\Models\Employee;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Storage;

class OrganizationChart extends Page
{
    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-users';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'شؤون العاملين والموظفين';
    }

    protected static ?int $navigationSort = 6;

    public static function getNavigationLabel(): string
    {
        return 'الهيكل التنظيمي';
    }

    public function getTitle(): string
    {
        return 'الهيكل التنظيمي للشركة';
    }

    protected string $view = 'filament.admin.pages.organization-chart';

    protected function getViewData(): array
    {
        return [
            'employeesData' => $this->getEmployeesData(),
        ];
    }

    public function getEmployeesData(): array
    {
        $employees = Employee::with('department')->get();
        $data = [];
        
        $data[] = [
            'id' => 'root',
            'parentId' => '',
            'name' => 'إدارة الشركة',
            'positionName' => 'الإدارة العليا',
            'imageUrl' => 'https://ui-avatars.com/api/?name=Company&color=FFFFFF&background=111827',
        ];
        
        $validIds = $employees->pluck('id')->map(fn($id) => (string)$id)->flip()->toArray();

        foreach ($employees as $employee) {
            $empId = (string)$employee->id;
            $mgrId = $employee->manager_id ? (string)$employee->manager_id : null;

            // Ensure parent exists and avoid self-referencing loops
            if ($mgrId && isset($validIds[$mgrId]) && $mgrId !== $empId) {
                $parentId = $mgrId;
            } else {
                $parentId = 'root';
            }
            
            $imageUrl = $employee->avatar_url 
                ? Storage::disk('public')->url($employee->avatar_url) 
                : 'https://ui-avatars.com/api/?name=' . urlencode($employee->first_name . ' ' . $employee->last_name) . '&color=7F9CF5&background=EBF4FF';
                
            $data[] = [
                'id' => $empId,
                'parentId' => $parentId,
                'name' => trim($employee->first_name . ' ' . $employee->last_name),
                'positionName' => $employee->job_title ?: 'موظف',
                'department' => $employee->department ? $employee->department->name : '',
                'imageUrl' => $imageUrl,
            ];
        }
        
        return $data;
    }
}
