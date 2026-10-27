<div class="grievance-page-wrapper">
    <style>
        .grievance-page-wrapper {
            min-height: 100vh;
            background: radial-gradient(circle at 50% 0%, #f0fdfa 0%, #f8fafc 50%, #f1f5f9 100%);
            font-family: 'Cairo', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: #0f172a;
            direction: rtl;
            padding: 2.5rem 1rem 4rem 1rem;
        }

        .grievance-container {
            max-width: 760px;
            margin: 0 auto;
        }

        /* Glassmorphism Main Card */
        .glass-card {
            background: #ffffff;
            border-radius: 1.5rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 20px 40px -15px rgba(15, 23, 42, 0.06), 0 0 0 1px rgba(226, 232, 240, 0.6);
            overflow: hidden;
            transition: all 0.2s ease;
        }

        /* Segmented Tabs */
        .tab-segmented-container {
            display: flex;
            background: #e2e8f0;
            padding: 4px;
            border-radius: 1rem;
            max-width: 420px;
            margin: 0 auto 2rem auto;
        }

        .tab-segmented-btn {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.65rem 1rem;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            font-weight: 800;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            color: #64748b;
            background: transparent;
        }

        .tab-segmented-btn.active {
            background: #ffffff;
            color: #0d9488;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        /* Category Selection Cards */
        .category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 0.75rem;
        }

        .category-card {
            border: 1.5px solid #e2e8f0;
            border-radius: 1rem;
            padding: 1rem 0.85rem;
            text-align: right;
            cursor: pointer;
            background: #ffffff;
            transition: all 0.2s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .category-card:hover {
            border-color: #cbd5e1;
            transform: translateY(-1px);
        }

        .category-card.selected {
            border-color: #0d9488;
            background: #f0fdfa;
            box-shadow: 0 0 0 2px rgba(13, 148, 136, 0.2);
        }

        .category-icon-box {
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 0.65rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.65rem;
            background: #f1f5f9;
            color: #475569;
            font-size: 1.1rem;
            transition: all 0.2s ease;
        }

        .category-card.selected .category-icon-box {
            background: #0d9488;
            color: #ffffff;
        }

        /* Form Inputs & Controls */
        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 0.4rem;
        }

        .input-text, .input-select, .input-textarea {
            width: 100%;
            border: 1.5px solid #cbd5e1;
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
            font-family: inherit;
            color: #0f172a;
            background: #ffffff;
            transition: all 0.15s ease;
            box-sizing: border-box;
            outline: none;
        }

        .input-text:focus, .input-select:focus, .input-textarea:focus {
            border-color: #0d9488;
            box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.15);
        }

        /* Priority Chips */
        .priority-chip-container {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .priority-chip {
            padding: 0.45rem 1rem;
            border-radius: 0.65rem;
            font-size: 0.8rem;
            font-weight: 800;
            cursor: pointer;
            border: 1.5px solid transparent;
            background: #f1f5f9;
            color: #475569;
            transition: all 0.15s ease;
        }

        .priority-chip.selected {
            background: #0f172a;
            color: #ffffff;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.12);
        }

        /* Privacy Cards */
        .privacy-box {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
        }

        @media (max-width: 600px) {
            .privacy-box {
                grid-template-columns: 1fr;
            }
        }

        .privacy-option {
            border: 1.5px solid #e2e8f0;
            border-radius: 1rem;
            padding: 0.85rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
            background: #ffffff;
            transition: all 0.2s ease;
        }

        .privacy-option.selected {
            border-color: #059669;
            background: #ecfdf5;
            box-shadow: 0 0 0 2px rgba(5, 150, 105, 0.2);
        }

        /* Primary Action Button */
        .btn-submit-main {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.95rem 1.5rem;
            border-radius: 1rem;
            background: linear-gradient(135deg, #0d9488 0%, #059669 100%);
            color: #ffffff;
            font-size: 1rem;
            font-weight: 800;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 8px 20px -4px rgba(13, 148, 136, 0.35);
        }

        .btn-submit-main:hover {
            background: linear-gradient(135deg, #0f766e 0%, #047857 100%);
            transform: translateY(-1px);
        }

        .btn-submit-main:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Ticket Voucher Card */
        .ticket-voucher {
            background: #f8fafc;
            border: 2px dashed #0d9488;
            border-radius: 1.25rem;
            padding: 1.5rem;
            text-align: center;
            margin: 1.5rem auto;
            max-width: 440px;
        }

        .ticket-code-badge {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            font-size: 1.85rem;
            font-weight: 900;
            color: #0d9488;
            letter-spacing: 2px;
            margin: 0.5rem 0;
            direction: ltr;
        }
    </style>

    <div class="grievance-container">

        <!-- Top Header / Trust Identity -->
        <div style="text-align: center; margin-bottom: 2rem;">
            <!-- Brand Logo -->
            <div style="display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
                @if($brandLogo)
                    <img src="{{ asset('storage/' . $brandLogo) }}" alt="{{ $brandName }}" style="max-height: 60px; max-width: 180px; object-fit: contain; padding: 6px; background: #ffffff; border-radius: 1rem; border: 1px solid #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                @else
                    <div style="width: 60px; height: 60px; border-radius: 1.25rem; background: linear-gradient(135deg, #0d9488 0%, #059669 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 1.75rem; box-shadow: 0 8px 16px rgba(13, 148, 136, 0.25);">
                        🛡️
                    </div>
                @endif
            </div>

            <!-- Title & Subtitle -->
            <h1 style="font-size: 1.75rem; font-weight: 900; color: #0f172a; margin: 0 0 0.5rem 0; letter-spacing: -0.5px;">
                صندوق الشكاوى والمقترحات الرقمي
            </h1>
            <p style="font-size: 0.95rem; color: #475569; margin: 0; font-weight: 600;">
                {{ $brandName }} — منصة آمنة، محايدة وسرية 100% لتعزيز بيئة عمل عادلة وتطوير مستمر
            </p>

            <!-- Trust Shield Pill -->
            <div style="margin-top: 0.85rem; display: inline-flex; align-items: center; gap: 0.5rem; background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; font-size: 0.775rem; font-weight: 800; padding: 0.35rem 1rem; border-radius: 9999px;">
                <span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #059669; box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.2);"></span>
                <span>بياناتك وحيادية التحقيق مضمونة ومحمية وفق أعلى معايير الحوكمة والسرية</span>
            </div>
        </div>

        <!-- Mode Switcher (Segmented Control) -->
        @if($viewMode !== 'success')
            <div class="tab-segmented-container">
                <button type="button"
                        wire:click="switchMode('submit')"
                        class="tab-segmented-btn {{ $viewMode === 'submit' ? 'active' : '' }}">
                    <span>✍️</span>
                    <span>تقديم جديد</span>
                </button>

                <button type="button"
                        wire:click="switchMode('track')"
                        class="tab-segmented-btn {{ $viewMode === 'track' ? 'active' : '' }}">
                    <span>🔍</span>
                    <span>متابعة تذكرة سابقة</span>
                </button>
            </div>
        @endif

        {{-- ════════════════════════════════════════════════════════════════ --}}
        {{-- MODE 1: SUBMIT NEW COMPLAINT / SUGGESTION                        --}}
        {{-- ════════════════════════════════════════════════════════════════ --}}
        @if($viewMode === 'submit')
            <div class="glass-card">
                <form wire:submit="submit" style="padding: 1.75rem; display: flex; flex-direction: column; gap: 1.5rem;">

                    <!-- Step 1: Category Selection Cards -->
                    <div>
                        <label class="form-label">
                            1. تصنيف الموضوع أو الطلب <span style="color: #ef4444;">*</span>
                        </label>
                        <div class="category-grid">
                            <!-- Category 1: Administrative -->
                            <div wire:click="$set('type', 'administrative')"
                                 class="category-card {{ $type === 'administrative' ? 'selected' : '' }}">
                                <div class="category-icon-box">🏛️</div>
                                <div>
                                    <h4 style="font-size: 0.825rem; font-weight: 800; margin: 0 0 0.25rem 0; color: #0f172a;">إدارية أو مالية</h4>
                                    <p style="font-size: 0.725rem; color: #64748b; margin: 0; line-height: 1.4;">رواتب، خصومات، مستحقات، إجراءات</p>
                                </div>
                            </div>

                            <!-- Category 2: Workplace -->
                            <div wire:click="$set('type', 'workplace')"
                                 class="category-card {{ $type === 'workplace' ? 'selected' : '' }}">
                                <div class="category-icon-box">🏢</div>
                                <div>
                                    <h4 style="font-size: 0.825rem; font-weight: 800; margin: 0 0 0.25rem 0; color: #0f172a;">بيئة العمل والأدوات</h4>
                                    <p style="font-size: 0.725rem; color: #64748b; margin: 0; line-height: 1.4;">مكاتب، أجهزة، تكييف، مستلزمات</p>
                                </div>
                            </div>

                            <!-- Category 3: Behavioral -->
                            <div wire:click="$set('type', 'behavioral')"
                                 class="category-card {{ $type === 'behavioral' ? 'selected' : '' }}">
                                <div class="category-icon-box">🤝</div>
                                <div>
                                    <h4 style="font-size: 0.825rem; font-weight: 800; margin: 0 0 0.25rem 0; color: #0f172a;">سلوكية أو نزاع مهني</h4>
                                    <p style="font-size: 0.725rem; color: #64748b; margin: 0; line-height: 1.4;">معاملة، سوء تفاهم، بيئة الفريق</p>
                                </div>
                            </div>

                            <!-- Category 4: Suggestion -->
                            <div wire:click="$set('type', 'suggestion')"
                                 class="category-card {{ $type === 'suggestion' ? 'selected' : '' }}">
                                <div class="category-icon-box">💡</div>
                                <div>
                                    <h4 style="font-size: 0.825rem; font-weight: 800; margin: 0 0 0.25rem 0; color: #0f172a;">فكرة أو مقترح تطويري</h4>
                                    <p style="font-size: 0.725rem; color: #64748b; margin: 0; line-height: 1.4;">مبادرة، تحسين مسار، زيادة كفاءة</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Privacy / Confidentiality Selection -->
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 1.15rem; padding: 1.15rem;">
                        <label class="form-label" style="margin-bottom: 0.65rem;">
                            2. وضع الخصوصية وهوية المُرسِل <span style="color: #ef4444;">*</span>
                        </label>
                        
                        <div class="privacy-box">
                            <div wire:click="$set('is_anonymous', true)"
                                 class="privacy-option {{ $is_anonymous ? 'selected' : '' }}">
                                <span style="font-size: 1.5rem;">🔒</span>
                                <div>
                                    <div style="display: flex; align-items: center; gap: 0.4rem;">
                                        <strong style="font-size: 0.85rem; color: #0f172a;">سري ومجهول الهوية</strong>
                                        <span style="font-size: 0.65rem; background: #dcfce7; color: #166534; padding: 0.1rem 0.4rem; border-radius: 0.35rem; font-weight: 800;">مُوصى به</span>
                                    </div>
                                    <p style="font-size: 0.725rem; color: #64748b; margin: 0.2rem 0 0 0; line-height: 1.4;">
                                        لن يتم تسجيل أو كشف أي بيانات شخصية، وتحصل على كود تذكرة خاص للمتابعة.
                                    </p>
                                </div>
                            </div>

                            <div wire:click="$set('is_anonymous', false)"
                                 class="privacy-option {{ ! $is_anonymous ? 'selected' : '' }}">
                                <span style="font-size: 1.5rem;">👤</span>
                                <div>
                                    <strong style="font-size: 0.85rem; color: #0f172a;">تقديم بالهوية الشخصية</strong>
                                    <p style="font-size: 0.725rem; color: #64748b; margin: 0.2rem 0 0 0; line-height: 1.4;">
                                        يسمح لفريق الموارد البشرية بالتواصل المباشر معك لسماع التفاصيل وحل الموضوع.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Submitter Contact Inputs (when NOT anonymous) -->
                        @if(! $is_anonymous)
                            <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px dashed #cbd5e1; display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 0.75rem;">
                                <div>
                                    <label class="form-label" style="font-size: 0.8rem;">الاسم الكريم <span style="color: #ef4444;">*</span></label>
                                    <input type="text"
                                           wire:model="submitter_name"
                                           placeholder="اسمك الكامل أو كودك الوظيفي"
                                           class="input-text">
                                    @error('submitter_name') <span style="color: #dc2626; font-size: 0.75rem; display: block; margin-top: 0.25rem;">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="form-label" style="font-size: 0.8rem;">رقم الهاتف أو البريد (اختياري)</label>
                                    <input type="text"
                                           wire:model="submitter_contact"
                                           placeholder="للتواصل معك وموافاتك بالمستجدات"
                                           class="input-text">
                                    @error('submitter_contact') <span style="color: #dc2626; font-size: 0.75rem; display: block; margin-top: 0.25rem;">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Step 3: Priority Chips -->
                    <div>
                        <label class="form-label">
                            3. درجة الأهمية / الاستعجال <span style="color: #ef4444;">*</span>
                        </label>
                        <div class="priority-chip-container">
                            @php
                                $priorities = [
                                    'low' => 'منخفضة (استفسار عادي)',
                                    'normal' => 'عادية (طبيعية)',
                                    'high' => 'عالية (تؤثر على العمل)',
                                    'urgent' => 'عاجلة جداً (أمر طارئ/حرج ⚠️)',
                                ];
                            @endphp
                            @foreach($priorities as $pk => $pLabel)
                                <div wire:click="$set('priority', '{{ $pk }}')"
                                     class="priority-chip {{ $priority === $pk ? 'selected' : '' }}">
                                    {{ $pLabel }}
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Step 4: Subject Title -->
                    <div>
                        <label class="form-label">
                            4. موضوع أو عنوان الشكوى / المقترح <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text"
                               wire:model="title"
                               placeholder="اكتب عنواناً موجزاً يوضح فحوى الموضوع..."
                               class="input-text">
                        @error('title') <span style="color: #dc2626; font-size: 0.75rem; display: block; margin-top: 0.25rem;">{{ $message }}</span> @enderror
                    </div>

                    <!-- Department & Manager Direct Bypass -->
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; align-items: center;">
                        @if($departments->count() > 0)
                            <div>
                                <label class="form-label" style="font-size: 0.8rem;">القسم المعني (اختياري)</label>
                                <select wire:model="department_id" class="input-select">
                                    <option value="">— غير محدد / يخص الإدارة العامة —</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div style="background: #fff1f2; border: 1px solid #fecdd3; border-radius: 0.75rem; padding: 0.75rem 1rem;">
                            <label style="display: flex; align-items: flex-start; gap: 0.5rem; cursor: pointer; margin: 0;">
                                <input type="checkbox" wire:model="against_manager" style="accent-color: #e11d48; width: 1.15rem; height: 1.15rem; margin-top: 0.15rem;">
                                <div>
                                    <strong style="font-size: 0.775rem; color: #9f1239; display: block;">
                                        رفع مباشر للإدارة العليا والتنفيذية
                                    </strong>
                                    <span style="font-size: 0.7rem; color: #be123c;">
                                        تخطي الإدارة المباشرة لحفظ النزاهة والحيادية التامة
                                    </span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Step 5: Full Content -->
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
                            <label class="form-label" style="margin: 0;">
                                5. التفاصيل والوقائع الكاملة <span style="color: #ef4444;">*</span>
                            </label>
                            <span style="font-size: 0.725rem; color: #94a3b8;">كن واضحاً ومحدداً للمساعدة في سرعة المعالجة</span>
                        </div>
                        <textarea wire:model="content"
                                  rows="5"
                                  placeholder="يرجى ذكر الوقائع والتواريخ وأي تفاصيل داعمة للموضوع بوضوح وأمانة..."
                                  class="input-textarea"></textarea>
                        @error('content') <span style="color: #dc2626; font-size: 0.75rem; display: block; margin-top: 0.25rem;">{{ $message }}</span> @enderror
                    </div>

                    <!-- Step 6: Attachment Upload -->
                    <div>
                        <label class="form-label" style="font-size: 0.8rem;">
                            مستند أو صورة داعمة (اختياري)
                        </label>
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <input type="file"
                                   wire:model="attachment"
                                   accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                                   style="font-size: 0.8rem; color: #64748b; cursor: pointer;">
                            <span wire:loading wire:target="attachment" style="font-size: 0.75rem; color: #0d9488; font-weight: 800;">
                                جارٍ رفع الملف...
                            </span>
                        </div>
                        <span style="display: block; font-size: 0.7rem; color: #94a3b8; margin-top: 0.35rem;">
                            الملفات المدعومة: PDF, Word, صور بحد أقصى 5 ميجابايت.
                        </span>
                        @error('attachment') <span style="color: #dc2626; font-size: 0.75rem; display: block; margin-top: 0.25rem;">{{ $message }}</span> @enderror
                    </div>

                    <!-- Submit Button -->
                    <div style="padding-top: 0.5rem; border-top: 1px solid #f1f5f9;">
                        <button type="submit"
                                wire:loading.attr="disabled"
                                class="btn-submit-main">
                            <span wire:loading.remove wire:target="submit">🚀 إرسال وتوثيق الشكوى رسمياً</span>
                            <span wire:loading wire:target="submit">⏳ جارٍ التوثيق وتوليد التذكرة...</span>
                        </button>
                    </div>

                </form>
            </div>
        @endif

        {{-- ════════════════════════════════════════════════════════════════ --}}
        {{-- MODE 2: SUBMISSION SUCCESS SCREEN                                --}}
        {{-- ════════════════════════════════════════════════════════════════ --}}
        @if($viewMode === 'success')
            <div class="glass-card" style="padding: 2.5rem 1.5rem; text-align: center;">
                <!-- Animated Checkmark Icon -->
                <div style="width: 70px; height: 70px; border-radius: 50%; background: #dcfce7; color: #16a34a; font-size: 2.5rem; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem auto; box-shadow: 0 0 0 8px #f0fdf4;">
                    ✓
                </div>

                <h2 style="font-size: 1.5rem; font-weight: 900; color: #0f172a; margin: 0 0 0.5rem 0;">
                    تم استلام وتوثيق طلبك بنجاح!
                </h2>
                <p style="font-size: 0.9rem; color: #64748b; margin: 0 auto; max-width: 440px; line-height: 1.6;">
                    شكراً لحرصك على بيئة العمل. تم تسجيل الموضوع في النظام الداخلي بنجاح وإحالته للجهة المختصة للتحقيق والحل.
                </p>

                <!-- Ticket Code Voucher -->
                <div class="ticket-voucher">
                    <span style="display: block; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">
                        رقم تذكرة المتابعة السرية الخاصة بك:
                    </span>
                    <div class="ticket-code-badge" id="ticketNumberDisplay">
                        {{ $submittedTicketNumber }}
                    </div>
                    <p style="font-size: 0.75rem; color: #92400e; background: #fef3c7; border-radius: 0.5rem; padding: 0.45rem; margin: 0.5rem 0 1rem 0; font-weight: 700;">
                        ⚠️ احتفظ بهذا الرقم في مكان آمن، فهو وسيلتك الوحيدة لمتابعة نتيجة التحقيق وقرار الإدارة لاحقاً.
                    </p>

                    <!-- Copy Button -->
                    <button type="button"
                            onclick="navigator.clipboard.writeText('{{ $submittedTicketNumber }}'); alert('تم نسخ رقم التذكرة بنجاح: {{ $submittedTicketNumber }}');"
                            style="display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.5rem 1.25rem; background: #0f172a; color: white; border: none; border-radius: 0.5rem; font-size: 0.8rem; font-weight: 800; cursor: pointer; transition: all 0.2s;">
                        <span>📋</span>
                        <span>نسخ رقم التذكرة</span>
                    </button>
                </div>

                <!-- Action Buttons -->
                <div style="display: flex; gap: 0.75rem; justify-content: center; flex-wrap: wrap;">
                    <button type="button"
                            wire:click="trackSubmitted"
                            style="padding: 0.75rem 1.5rem; border-radius: 0.75rem; background: #0d9488; color: white; font-weight: 800; font-size: 0.85rem; border: none; cursor: pointer;">
                        متابعة حالة التذكرة الآن ↗
                    </button>

                    <button type="button"
                            wire:click="resetForm"
                            style="padding: 0.75rem 1.5rem; border-radius: 0.75rem; background: #f1f5f9; color: #334155; font-weight: 800; font-size: 0.85rem; border: 1px solid #e2e8f0; cursor: pointer;">
                        تقديم موضوع آخر
                    </button>
                </div>
            </div>
        @endif

        {{-- ════════════════════════════════════════════════════════════════ --}}
        {{-- MODE 3: TRACK EXISTING TICKET                                    --}}
        {{-- ════════════════════════════════════════════════════════════════ --}}
        @if($viewMode === 'track')
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                <!-- Search Box Card -->
                <div class="glass-card" style="padding: 1.75rem;">
                    <h3 style="font-size: 1.1rem; font-weight: 900; color: #0f172a; margin: 0 0 0.35rem 0;">
                        استعلام ومتابعة حالة التذكرة
                    </h3>
                    <p style="font-size: 0.85rem; color: #64748b; margin: 0 0 1.25rem 0;">
                        أدخل رقم التذكرة المستلم عند تقديم الشكوى (مثال: GRV-2610-0001) للاطلاع على المستجدات والرد الإداري:
                    </p>

                    <form wire:submit="lookupTicket" style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                        <input type="text"
                               wire:model="searchTicket"
                               placeholder="GRV-2610-0001"
                               style="flex: 1; min-width: 200px; font-family: monospace; font-size: 1rem; font-weight: 800; text-transform: uppercase; direction: ltr; text-align: left;"
                               class="input-text">

                        <button type="submit"
                                wire:loading.attr="disabled"
                                style="padding: 0.75rem 1.5rem; border-radius: 0.75rem; background: #0d9488; color: white; font-weight: 800; font-size: 0.85rem; border: none; cursor: pointer; white-space: nowrap;">
                            <span wire:loading.remove wire:target="lookupTicket">🔍 بحث واستعلام</span>
                            <span wire:loading wire:target="lookupTicket">⏳ جارٍ البحث...</span>
                        </button>
                    </form>

                    <!-- Error Alert -->
                    @if($trackError)
                        <div style="margin-top: 1rem; padding: 0.75rem 1rem; border-radius: 0.75rem; background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; font-size: 0.825rem; font-weight: 700;">
                            ⚠️ {{ $trackError }}
                        </div>
                    @endif
                </div>

                <!-- Search Results Card -->
                @if($trackedGrievance)
                    <div class="glass-card">
                        <!-- Result Header -->
                        <div style="padding: 1.5rem; border-bottom: 1px solid #e2e8f0; background: #f8fafc;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem; flex-wrap: wrap;">
                                <div>
                                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.35rem;">
                                        <span style="font-family: monospace; font-size: 0.85rem; font-weight: 900; color: #0d9488; background: #ccfbf1; padding: 0.2rem 0.6rem; border-radius: 0.4rem; direction: ltr;">
                                            {{ $trackedGrievance->ticket_number }}
                                        </span>
                                        <span style="font-size: 0.75rem; color: #64748b;">
                                            {{ $trackedGrievance->created_at->format('Y-m-d H:i') }}
                                        </span>
                                    </div>
                                    <h3 style="font-size: 1.15rem; font-weight: 900; color: #0f172a; margin: 0;">
                                        {{ $trackedGrievance->title }}
                                    </h3>
                                </div>

                                <!-- Status Badge -->
                                <div>
                                    @php
                                        $statusBadge = match($trackedGrievance->status) {
                                            'resolved' => ['bg' => '#dcfce7', 'text' => '#15803d', 'label' => 'تم الحل والمعالجة ✓'],
                                            'in_progress' => ['bg' => '#dbeafe', 'text' => '#1d4ed8', 'label' => 'قيد المراجعة والتحقيق'],
                                            'rejected' => ['bg' => '#f1f5f9', 'text' => '#64748b', 'label' => 'مغلقة / مؤرشفة'],
                                            default => ['bg' => '#fef3c7', 'text' => '#b45309', 'label' => 'جديدة مسجلة'],
                                        };
                                    @endphp
                                    <span style="display: inline-block; padding: 0.4rem 0.85rem; border-radius: 9999px; font-size: 0.8rem; font-weight: 800; background: {{ $statusBadge['bg'] }}; color: {{ $statusBadge['text'] }};">
                                        {{ $statusBadge['label'] }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- 3-Step Visual Progress Tracker -->
                        <div style="padding: 1.5rem; background: #ffffff; border-bottom: 1px solid #e2e8f0;">
                            <span style="display: block; font-size: 0.8rem; font-weight: 800; color: #475569; margin-bottom: 1rem;">
                                مسار معالجة التذكرة:
                            </span>

                            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0.5rem; text-align: center;">
                                <!-- Step 1: Received -->
                                <div>
                                    <div style="width: 32px; height: 32px; border-radius: 50%; background: #059669; color: white; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.4rem auto; font-weight: 900; font-size: 0.85rem;">
                                        ✓
                                    </div>
                                    <strong style="display: block; font-size: 0.775rem; color: #0f172a;">تم الاستلام والتوثيق</strong>
                                    <span style="font-size: 0.7rem; color: #059669; font-weight: 700;">مكتمل</span>
                                </div>

                                <!-- Step 2: In Investigation -->
                                <div>
                                    <div style="width: 32px; height: 32px; border-radius: 50%; background: {{ in_array($trackedGrievance->status, ['in_progress', 'resolved']) ? '#059669' : '#f59e0b' }}; color: white; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.4rem auto; font-weight: 900; font-size: 0.85rem;">
                                        {{ in_array($trackedGrievance->status, ['in_progress', 'resolved']) ? '✓' : '2' }}
                                    </div>
                                    <strong style="display: block; font-size: 0.775rem; color: #0f172a;">قيد الفحص والمراجعة</strong>
                                    <span style="font-size: 0.7rem; color: {{ in_array($trackedGrievance->status, ['in_progress', 'resolved']) ? '#059669' : '#b45309' }}; font-weight: 700;">
                                        {{ $trackedGrievance->status === 'new' ? 'في انتظار البدء' : ($trackedGrievance->status === 'in_progress' ? 'جارٍ حالياً' : 'مكتمل') }}
                                    </span>
                                </div>

                                <!-- Step 3: Resolved -->
                                <div>
                                    <div style="width: 32px; height: 32px; border-radius: 50%; background: {{ $trackedGrievance->status === 'resolved' ? '#059669' : '#e2e8f0' }}; color: {{ $trackedGrievance->status === 'resolved' ? 'white' : '#94a3b8' }}; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.4rem auto; font-weight: 900; font-size: 0.85rem;">
                                        {{ $trackedGrievance->status === 'resolved' ? '✓' : '3' }}
                                    </div>
                                    <strong style="display: block; font-size: 0.775rem; color: {{ $trackedGrievance->status === 'resolved' ? '#0f172a' : '#94a3b8' }};">تم اتخاذ الإجراء والحل</strong>
                                    <span style="font-size: 0.7rem; color: {{ $trackedGrievance->status === 'resolved' ? '#059669' : '#94a3b8' }}; font-weight: 700;">
                                        {{ $trackedGrievance->status === 'resolved' ? 'تم الانتهاء' : 'بانتظار القرار' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Content & Official HR Response -->
                        <div style="padding: 1.5rem; display: flex; flex-direction: column; gap: 1rem;">
                            <!-- Grievance Content -->
                            <div>
                                <span style="display: block; font-size: 0.75rem; font-weight: 800; color: #64748b; margin-bottom: 0.25rem;">
                                    تفاصيل الشكوى المسجلة:
                                </span>
                                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.75rem; padding: 0.85rem 1rem; font-size: 0.85rem; color: #334155; line-height: 1.6; white-space: pre-line;">
                                    {{ $trackedGrievance->content }}
                                </div>
                            </div>

                            <!-- Official Response Section -->
                            @if($trackedGrievance->hr_response)
                                <div style="background: #ecfdf5; border: 1.5px solid #a7f3d0; border-radius: 1rem; padding: 1.15rem;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                        <strong style="font-size: 0.9rem; color: #065f46; display: flex; align-items: center; gap: 0.4rem;">
                                            <span>🛡️</span>
                                            <span>رد وقرار إدارة الموارد البشرية الرسمي</span>
                                        </strong>
                                        @if($trackedGrievance->resolved_at)
                                            <span style="font-size: 0.725rem; color: #047857; font-weight: 700;">
                                                بتاريخ: {{ $trackedGrievance->resolved_at->format('Y-m-d') }}
                                            </span>
                                        @endif
                                    </div>
                                    <p style="font-size: 0.875rem; color: #064e3b; margin: 0; line-height: 1.6; font-weight: 600; white-space: pre-line;">
                                        {{ $trackedGrievance->hr_response }}
                                    </p>
                                </div>
                            @else
                                <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 0.75rem; padding: 0.85rem 1rem; color: #1e40af; font-size: 0.825rem; font-weight: 700; display: flex; align-items: center; gap: 0.5rem;">
                                    <span>⏳</span>
                                    <span>تذكرتك قيد الدراسة والمتابعة من قِبل فريق الموارد البشرية والإدارة المختصة. سيتم تحديث الرد والقرار هنا فور الانتهاء.</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- Footer Help Link -->
        <div style="text-align: center; margin-top: 2.5rem; padding-top: 1.5rem; border-top: 1px solid #e2e8f0;">
            <p style="font-size: 0.8rem; color: #64748b; margin: 0;">
                هل أنت موظف بالشركة ولديك حساب؟
                <a href="{{ route('portal.dashboard') }}" style="color: #0d9488; font-weight: 800; text-decoration: underline; margin-right: 0.35rem;">
                    الدخول إلى بوابة الموظف الذاتية (ESS)
                </a>
            </p>
        </div>

    </div>
</div>
