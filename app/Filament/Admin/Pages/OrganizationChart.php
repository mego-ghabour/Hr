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
        return 'شؤون العاملين';
    }

    public static function getNavigationLabel(): string
    {
        return 'الهيكل التنظيمي';
    }

    public function getTitle(): string
    {
        return 'الهيكل التنظيمي للشركة';
    }
    


    protected string $view = 'filament.admin.pages.organization-chart';

    public function getEmployeesData(): array
    {
        $employees = Employee::with('department')->get();
        $data = [];
        
        // Ensure there is a root node (Company) if no employees have manager_id = null
        // Or we just rely on whoever has manager_id = null as roots.
        
        // We will create a virtual root node if needed, but let's assume CEO has null.
        // Actually, d3-org-chart requires a single root usually, or handles multiple if told so.
        // Let's create a single virtual root node just in case.
        
        $data[] = [
            'id' => 'root',
            'parentId' => '',
            'name' => 'إدارة الشركة',
            'positionName' => 'الإدارة العليا',
            'imageUrl' => url('https://ui-avatars.com/api/?name=Company&color=FFFFFF&background=111827'),
        ];
        
        foreach ($employees as $employee) {
            // If manager_id is null, attach to root
            $parentId = $employee->manager_id ? (string)$employee->manager_id : 'root';
            
            $imageUrl = $employee->avatar_url 
                ? Storage::url($employee->avatar_url) 
                : url('https://ui-avatars.com/api/?name=' . urlencode($employee->first_name . ' ' . $employee->last_name) . '&color=7F9CF5&background=EBF4FF');
                
            $data[] = [
                'id' => (string)$employee->id,
                'parentId' => $parentId,
                'name' => $employee->first_name . ' ' . $employee->last_name,
                'positionName' => $employee->job_title ?: 'موظف',
                'department' => $employee->department ? $employee->department->name : '',
                'imageUrl' => $imageUrl,
            ];
        }
        
        return $data;
    }
}
