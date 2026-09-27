<x-filament-panels::page>
    <style>
        /* Essential Tailwind fallbacks because JIT compiler skips custom pages sometimes */
        .flex { display: flex !important; }
        .items-center { align-items: center !important; }
        .justify-center { justify-content: center !important; }
        .justify-between { justify-content: space-between !important; }
        .gap-1 { gap: 0.25rem !important; }
        .gap-2 { gap: 0.5rem !important; }
        .mt-2 { margin-top: 0.5rem !important; }
        .mt-3 { margin-top: 0.75rem !important; }
        .w-8 { width: 2rem !important; }
        .h-8 { height: 2rem !important; }
        .rounded-full { border-radius: 9999px !important; }
        .text-xs { font-size: 0.75rem !important; line-height: 1rem !important; }
        
        svg.w-3 { width: 0.75rem !important; height: 0.75rem !important; display: inline-block; }
        svg.w-4 { width: 1rem !important; height: 1rem !important; display: inline-block; }
        svg.w-5 { width: 1.25rem !important; height: 1.25rem !important; display: inline-block; }
        svg.w-6 { width: 1.5rem !important; height: 1.5rem !important; display: inline-block; }
        .kanban-icon { width: 1.25rem !important; height: 1.25rem !important; display: inline-block; }
        
        .kanban-board-container {
            display: flex !important;
            gap: 1.5rem;
            overflow-x: auto;
            padding-bottom: 1.5rem;
            min-height: 75vh;
            align-items: flex-start;
        }
        
        /* Custom Scrollbar for a premium look */
        .kanban-board-container::-webkit-scrollbar { height: 8px; }
        .kanban-board-container::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
        .kanban-board-container::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .kanban-board-container::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        .kanban-cards-container::-webkit-scrollbar { width: 6px; }
        .kanban-cards-container::-webkit-scrollbar-track { background: transparent; }
        .kanban-cards-container::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 3px; }
        .kanban-cards-container::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }

        .kanban-column {
            flex-shrink: 0;
            width: 22rem; /* Slightly wider */
            background-color: #f8fafc; /* Slate 50 */
            border-radius: 0.75rem;
            display: flex !important;
            flex-direction: column;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border: 1px solid #e2e8f0;
            max-height: 80vh;
            transition: background-color 0.2s ease;
        }
        
        .kanban-column.drag-over {
            background-color: #eff6ff; /* Blue 50 */
            border: 2px dashed #3b82f6;
        }

        .kanban-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: white;
            border-top-left-radius: 0.75rem;
            border-top-right-radius: 0.75rem;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .kanban-header-title {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-weight: 700;
            color: #0f172a; /* Slate 900 */
            font-size: 1rem;
        }
        .kanban-badge {
            background-color: #f1f5f9;
            color: #475569;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            box-shadow: inset 0 1px 2px rgba(0,0,0,0.05);
        }
        .kanban-cards-container {
            padding: 1rem;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            overflow-y: auto;
        }
        
        .kanban-card {
            background-color: white;
            padding: 1.25rem;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            border: 1px solid #e2e8f0;
            border-right: 4px solid #e2e8f0; /* Accent border default */
            cursor: grab;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            text-align: right;
        }
        .kanban-card:active {
            cursor: grabbing;
        }
        .kanban-card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            transform: translateY(-2px);
            border-color: #cbd5e1;
            border-right-color: #3b82f6; /* Accent color on hover */
        }
        
        .kanban-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.75rem;
        }
        .kanban-card-title {
            font-weight: 700;
            font-size: 0.95rem;
            color: #1e293b;
            line-height: 1.3;
        }
        .kanban-card-id {
            font-size: 0.75rem;
            color: #64748b;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            background: #f8fafc;
            padding: 2px 6px;
            border-radius: 4px;
            border: 1px solid #e2e8f0;
            margin-top: 4px;
            display: inline-block;
        }
        .kanban-card-job {
            font-size: 0.8rem;
            color: #475569;
            margin-bottom: 0.75rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .kanban-card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap; /* Fixes overlap */
            gap: 0.5rem;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px dashed #e2e8f0;
        }
        .kanban-dept {
            font-size: 0.7rem;
            font-weight: 700;
            color: #4338ca; /* Indigo 700 */
            background-color: #e0e7ff; /* Indigo 100 */
            padding: 0.35rem 0.6rem;
            border-radius: 9999px;
            text-transform: uppercase;
            letter-spacing: 0.025em;
            white-space: normal;
            line-height: 1.3;
            max-width: 100%;
            text-align: center;
        }
        .kanban-empty {
            padding: 2rem 1rem;
            text-align: center;
            color: #94a3b8;
            font-size: 0.875rem;
            font-weight: 500;
            border: 2px dashed #cbd5e1;
            border-radius: 0.5rem;
            background-color: rgba(255, 255, 255, 0.5);
        }
        .kanban-drop-placeholder {
            border: 2px dashed #93c5fd;
            background-color: #eff6ff;
            border-radius: 0.75rem;
            min-height: 8rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #3b82f6;
            font-weight: 700;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            animation: pulse-border 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        @keyframes pulse-border {
            0%, 100% { border-color: #93c5fd; background-color: #eff6ff; }
            50% { border-color: #60a5fa; background-color: #dbeafe; }
        }
        .opacity-50 { opacity: 0.5 !important; }
        .scale-95 { transform: scale(0.95) !important; }
        .ring-2 { box-shadow: 0 0 0 2px #3b82f6 !important; }

        /* Action Buttons Styling */
        .btn-action {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 2.25rem !important;
            height: 2.25rem !important;
            border-radius: 50% !important;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }
        .btn-action:hover {
            transform: scale(1.15) translateY(-2px) !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
        }
        .btn-whatsapp {
            background-color: #dcfce7 !important;
            color: #16a34a !important;
            border: 1px solid #bbf7d0 !important;
        }
        .btn-whatsapp:hover {
            background-color: #bbf7d0 !important;
            color: #15803d !important;
        }
        .btn-view {
            background-color: #eff6ff !important;
            color: #2563eb !important;
            border: 1px solid #bfdbfe !important;
        }
        .btn-view:hover {
            background-color: #dbeafe !important;
            color: #1d4ed8 !important;
        }

        /* Filter Bar Premium Styles */
        .kanban-filters-bar {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            margin-bottom: 2rem;
            background: linear-gradient(to right, #ffffff, #f8fafc);
            padding: 1.25rem 1.5rem;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
        }
        @media (min-width: 768px) {
            .kanban-filters-bar {
                flex-direction: row;
            }
        }
        .filter-group {
            display: flex;
            flex-direction: column;
            flex: 1;
        }
        .filter-group.fixed-width {
            flex: 0 0 auto;
            width: 100%;
        }
        @media (min-width: 768px) {
            .filter-group.fixed-width {
                width: 16rem; /* 64 */
            }
            .filter-group.fixed-width-lg {
                width: 20rem; /* 80 */
            }
        }
        .filter-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.25rem;
        }
        .filter-input {
            width: 100%;
            border-radius: 0.5rem;
            border: 1px solid #d1d5db;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
        }
        .filter-input:focus {
            border-color: #3b82f6;
            outline: none;
            box-shadow: 0 0 0 1px #3b82f6;
        }
        
        .dark .kanban-filters-bar {
            background-color: #111827;
            border-color: #1f2937;
        }
        .dark .filter-label {
            color: #d1d5db;
        }
        .dark .filter-input {
            background-color: #1f2937;
            border-color: #374151;
            color: white;
        }
    </style>

    <!-- Filters Bar -->
    <div class="kanban-filters-bar">
        <div class="filter-group relative">
            <label class="filter-label" style="font-weight: 700; color: #334155;">بحث عن مرشح</label>
            <div style="position: relative;">
                <div style="position: absolute; right: 0.85rem; top: 0.65rem; color: #94a3b8; pointer-events: none;">
                    <svg style="width: 1.25rem; height: 1.25rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
                <input type="text" wire:model.live.debounce.300ms="searchQuery" placeholder="اكتب اسم المرشح أو المسمى الوظيفي..." class="filter-input" style="padding-right: 2.75rem; border-color: #cbd5e1; transition: all 0.2s ease;">
            </div>
        </div>
        
        <div class="filter-group fixed-width">
            <label class="filter-label" style="font-weight: 700; color: #334155;">تصفية بالقسم</label>
            <div style="position: relative;">
                <select wire:model.live="selectedDepartment" class="filter-input" style="appearance: none; padding-left: 2.5rem; border-color: #cbd5e1; cursor: pointer; transition: all 0.2s ease;">
                    <option value="">جميع الأقسام</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                    @endforeach
                </select>
                <div style="position: absolute; left: 0.85rem; top: 0.75rem; color: #94a3b8; pointer-events: none;">
                    <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>
        </div>

        <div class="filter-group fixed-width">
            <label class="filter-label" style="font-weight: 700; color: #334155;">تصفية بالوظيفة</label>
            <div style="position: relative;">
                <select wire:model.live="selectedJob" class="filter-input" style="appearance: none; padding-left: 2.5rem; border-color: #cbd5e1; cursor: pointer; transition: all 0.2s ease;">
                    <option value="">جميع الوظائف</option>
                    @foreach($jobs as $job)
                        <option value="{{ $job->id }}">{{ Str::limit($job->title, 30) }}</option>
                    @endforeach
                </select>
                <div style="position: absolute; left: 0.85rem; top: 0.75rem; color: #94a3b8; pointer-events: none;">
                    <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>
        </div>

        <div class="filter-group fixed-width-lg">
            <label class="filter-label" style="font-weight: 700; color: #334155;">الأعمدة المعروضة</label>
            <div class="relative" style="position: relative;" x-data="{ open: false }">
                <button @click="open = !open" type="button" class="filter-input" style="text-align: right; cursor: pointer; display: flex; justify-content: space-between; align-items: center; border-color: #cbd5e1; background-color: white;">
                    <span class="block truncate" style="font-weight: 600; color: #475569;">
                        @if(count($visibleStatuses) === count($statuses))
                            جميع الأعمدة ({{ count($statuses) }})
                        @elseif(count($visibleStatuses) === 0)
                            اختر الأعمدة...
                        @else
                            {{ count($visibleStatuses) }} أعمدة معروضة
                        @endif
                    </span>
                    <svg style="width: 1rem; height: 1rem; color: #94a3b8;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="open" @click.away="open = false" style="position: absolute; top: 100%; right: 0; z-index: 50; margin-top: 0.5rem; width: 100%; background-color: white; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1); border-radius: 0.75rem; padding: 0.5rem; border: 1px solid #e2e8f0; max-height: 18rem; overflow-y: auto;">
                    <div style="padding: 0.25rem 0.5rem 0.5rem 0.5rem; margin-bottom: 0.5rem; border-bottom: 1px solid #e2e8f0; font-size: 0.75rem; color: #64748b; font-weight: 700;">اختر ما تريد عرضه:</div>
                    @foreach($statuses as $status)
                        <label style="display: flex; align-items: center; padding: 0.6rem 0.5rem; cursor: pointer; font-size: 0.875rem; border-radius: 0.375rem; transition: background-color 0.2s ease;" onmouseover="this.style.backgroundColor='#f8fafc'" onmouseout="this.style.backgroundColor='transparent'">
                            <input type="checkbox" wire:model.live="visibleStatuses" value="{{ $status->id }}" style="margin-left: 0.75rem; border-radius: 0.25rem; border-color: #cbd5e1; color: #3b82f6; width: 1.1rem; height: 1.1rem; cursor: pointer;">
                            <span style="font-weight: 500; color: #334155;">{{ $status->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="kanban-board-container">
        @foreach($statuses as $status)
            @if(in_array((string)$status->id, $visibleStatuses))
                <div 
                    class="kanban-column"
                    style="border-top: 4px solid {{ match($status->color) { 'primary' => '#3b82f6', 'success' => '#22c55e', 'danger' => '#ef4444', 'warning' => '#eab308', 'info' => '#0ea5e9', default => '#6b7280' } }};"
                    x-data="{ 
                        statusId: {{ $status->id }},
                        isDragOver: false 
                    }"
                    @dragover.prevent="isDragOver = true"
                    @dragleave.prevent="isDragOver = false"
                    @drop.prevent="
                        isDragOver = false;
                        const talentId = event.dataTransfer.getData('text/plain');
                        if(talentId) {
                            $wire.updateTalentStatus(talentId, statusId);
                        }
                    "
                    :style="isDragOver ? 'box-shadow: 0 0 0 2px #3b82f6;' : ''"
                >
                    <!-- Column Header -->
                    <div class="kanban-header">
                        <div class="kanban-header-title">
                            <div style="width: 10px; height: 10px; border-radius: 50%; background-color: {{ match($status->color) { 'primary' => '#3b82f6', 'success' => '#22c55e', 'danger' => '#ef4444', 'warning' => '#eab308', 'info' => '#0ea5e9', default => '#6b7280' } }}"></div>
                            <h3>{{ $status->name }}</h3>
                        </div>
                        <span class="kanban-badge" style="color: {{ match($status->color) { 'primary' => '#1d4ed8', 'success' => '#15803d', 'danger' => '#b91c1c', 'warning' => '#a16207', 'info' => '#0369a1', default => '#4b5563' } }}; background-color: {{ match($status->color) { 'primary' => '#eff6ff', 'success' => '#f0fdf4', 'danger' => '#fef2f2', 'warning' => '#fefce8', 'info' => '#e0f2fe', default => '#f3f4f6' } }}; border: 1px solid {{ match($status->color) { 'primary' => '#bfdbfe', 'success' => '#bbf7d0', 'danger' => '#fecaca', 'warning' => '#fef08a', 'info' => '#bae6fd', default => '#e5e7eb' } }};">
                            {{ $talents->where('status_id', $status->id)->count() }}
                        </span>
                    </div>
                    
                    <!-- Cards Container -->
                    <div class="kanban-cards-container" :style="isDragOver ? 'background-color: #f8fafc; border-radius: 0.5rem;' : ''">
                        
                        <!-- Ghost Drop Placeholder -->
                        <div x-show="isDragOver" style="display: none;" class="kanban-drop-placeholder">
                            إفلات البطاقة هنا
                        </div>

                        @foreach($talents->where('status_id', $status->id) as $talent)
                            <div 
                                draggable="true"
                                x-data="{ isDragging: false }"
                                @dragstart="
                                    isDragging = true;
                                    event.dataTransfer.setData('text/plain', {{ $talent->id }});
                                    event.dataTransfer.effectAllowed = 'move';
                                    setTimeout(() => $el.classList.add('opacity-50', 'scale-95', 'ring-2', 'ring-primary-500'), 0);
                                "
                                @dragend="
                                    isDragging = false;
                                    $el.classList.remove('opacity-50', 'scale-95', 'ring-2', 'ring-primary-500');
                                "
                                class="kanban-card"
                                :class="isDragging ? 'opacity-50 scale-95 ring-2 ring-primary-500' : ''"
                            >
                                <div class="kanban-card-header">
                                    <div class="flex items-center gap-2">
                                        <div class="flex items-center justify-center w-8 h-8 rounded-full bg-primary-100 dark:bg-primary-900/50 text-primary-600 dark:text-primary-400 font-bold text-xs" style="background: linear-gradient(135deg, #eff6ff, #dbeafe); border: 1px solid #bfdbfe; color: #1e40af;">
                                            {{ mb_substr($talent->full_name, 0, 2) }}
                                        </div>
                                        <div>
                                            <h4 class="kanban-card-title">{{ $talent->full_name }}</h4>
                                            <span class="kanban-card-id">{{ $talent->talent_id }}</span>
                                        </div>
                                    </div>
                                    <div style="color: #cbd5e1; cursor: grab;" title="اسحب البطاقة">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                                    </div>
                                </div>
                                
                                @if($talent->jobPosting)
                                    <div class="mt-2 text-xs font-semibold" style="color: #059669; background-color: #d1fae5; padding: 3px 8px; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px; border: 1px solid #a7f3d0;">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        {{ \Illuminate\Support\Str::limit($talent->jobPosting->title, 25) }}
                                    </div>
                                @endif

                                @if($talent->current_job_title)
                                    <p class="kanban-card-job mt-2">{{ $talent->current_job_title }}</p>
                                @endif
                                
                                <div class="flex items-center gap-1 text-xs text-gray-500 dark:text-gray-400 mt-2">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    @if($talent->date_added)
                                    {{ \Carbon\Carbon::parse($talent->date_added)->locale('ar')->diffForHumans(['parts' => 1]) }}
                                @else
                                        جديد
                                    @endif
                                </div>
                                
                                <div class="kanban-card-footer mt-3">
                                    <span class="kanban-dept">
                                        {{ $talent->department?->name ?? 'بدون قسم' }}
                                    </span>
                                    
                                    <div class="flex items-center gap-2">
                                        @if($talent->phone)
                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $talent->phone) }}" target="_blank" class="btn-action btn-whatsapp" title="مراسلة واتساب">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                            </a>
                                        @endif
                                        
                                        <a href="{{ \App\Filament\Admin\Resources\TalentResource::getUrl('view', ['record' => $talent->id]) }}" class="btn-action btn-view" title="عرض التفاصيل">
                                            <svg class="kanban-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        
                        @if($talents->where('status_id', $status->id)->isEmpty())
                            <div class="kanban-empty">
                                <svg style="margin: 0 auto 0.5rem auto; width: 2.5rem; height: 2.5rem; color: #cbd5e1;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                <span>اسحب المرشحين هنا</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</x-filament-panels::page>
