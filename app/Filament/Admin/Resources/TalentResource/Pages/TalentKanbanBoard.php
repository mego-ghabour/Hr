<?php

namespace App\Filament\Admin\Resources\TalentResource\Pages;

use App\Filament\Admin\Resources\TalentResource;
use App\Models\Talent;
use App\Models\TalentStatus;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Collection;

class TalentKanbanBoard extends Page
{
    protected static string $resource = TalentResource::class;
    protected string $view = 'filament.admin.resources.talent-resource.pages.talent-kanban-board';
    
    public function getTitle(): string | \Illuminate\Contracts\Support\Htmlable
    {
        return 'لوحة التوظيف (كانبان)';
    }
    
    public Collection $statuses;
    public Collection $talents;
    public Collection $departments;
    public Collection $jobs;

    public string $searchQuery = '';
    public $selectedDepartment = '';
    public $selectedJob = '';
    public array $visibleStatuses = [];

    protected $queryString = [
        'searchQuery' => ['except' => ''],
        'selectedDepartment' => ['except' => ''],
        'selectedJob' => ['except' => ''],
        'visibleStatuses' => ['except' => []],
    ];

    public function mount()
    {
        $this->departments = \App\Models\Department::orderBy('name')->get();
        $this->jobs = \App\Models\JobPosting::where('is_active', true)->orderBy('title')->get();
        $this->statuses = TalentStatus::orderBy('order_column')->get();
        
        // Restore from session if not in query string
        if (empty($this->selectedDepartment)) {
            $this->selectedDepartment = session()->get('kanban_selected_department', '');
        }
        
        if (empty($this->selectedJob)) {
            $this->selectedJob = session()->get('kanban_selected_job', '');
        }
        
        if (empty($this->visibleStatuses)) {
            $this->visibleStatuses = session()->get('kanban_visible_statuses', []);
        }

        if (empty($this->visibleStatuses)) {
            $this->visibleStatuses = $this->statuses->pluck('id')->map(fn($id) => (string)$id)->toArray();
        }
        
        $this->loadBoard();
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'visibleStatuses') {
            session()->put('kanban_visible_statuses', $this->visibleStatuses);
        } elseif ($propertyName === 'selectedDepartment') {
            session()->put('kanban_selected_department', $this->selectedDepartment);
        } elseif ($propertyName === 'selectedJob') {
            session()->put('kanban_selected_job', $this->selectedJob);
        }
        
        $this->loadBoard();
    }

    public function loadBoard()
    {
        $query = Talent::with(['status', 'department', 'documents', 'jobPosting']);

        if (!empty($this->searchQuery)) {
            $query->where(function ($q) {
                $q->where('full_name', 'like', '%' . $this->searchQuery . '%')
                  ->orWhere('talent_id', 'like', '%' . $this->searchQuery . '%');
            });
        }

        if (!empty($this->selectedDepartment)) {
            $query->where('department_id', $this->selectedDepartment);
        }

        if (!empty($this->selectedJob)) {
            $query->where('job_posting_id', $this->selectedJob);
        }

        $this->talents = $query->get();
    }

    public function updateTalentStatus($talentId, $statusId)
    {
        $talent = Talent::find($talentId);
        if ($talent && $talent->status_id != $statusId) {
            $talent->update(['status_id' => $statusId]);
        }
        $this->loadBoard();
    }
}
