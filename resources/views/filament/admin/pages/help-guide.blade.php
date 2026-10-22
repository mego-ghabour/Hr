<x-filament-panels::page>
    <style>
        .guide-flow-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1.25rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.03);
            transition: all 0.2s ease;
        }
        .dark .guide-flow-card {
            background: #0f172a;
            border-color: #1e293b;
        }
        .guide-flow-card:hover {
            box-shadow: 0 8px 24px rgba(0,0,0,0.06);
            transform: translateY(-2px);
        }
        .flow-step-number {
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 0.875rem;
            flex-shrink: 0;
        }
        .badge-system {
            font-size: 0.75rem;
            font-weight: 800;
            padding: 0.2rem 0.6rem;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }
    </style>

    <div style="display: flex; flex-direction: column; gap: 2rem;">

        {{-- Top Hero Banner --}}
        <div style="background: linear-gradient(135deg, #0d9488 0%, #0f766e 50%, #115e59 100%); border-radius: 1.5rem; padding: 2rem; color: #ffffff; box-shadow: 0 10px 25px rgba(13, 148, 136, 0.25); position: relative; overflow: hidden;">
            <div style="position: absolute; right: -2rem; top: -2rem; width: 12rem; height: 12rem; background: rgba(255,255,255,0.08); border-radius: 9999px; pointer-events: none;"></div>
            <div style="position: relative; z-index: 10;">
                <div style="display: flex; align-items: center; gap: 0.65rem; margin-bottom: 0.75rem;">
                    <span class="badge-system" style="background: rgba(255,255,255,0.2); color: #ffffff;">
                        <x-filament::icon icon="heroicon-o-sparkles" style="width: 1rem; height: 1rem;" />
                        دليل التشغيل والسياسات المعتمدة
                    </span>
                    <span class="badge-system" style="background: #ecfdf5; color: #047857;">
                        الإصدار المتكامل 2026
                    </span>
                </div>
                <h1 style="font-size: 1.85rem; font-weight: 900; margin: 0 0 0.5rem 0; line-height: 1.25;">
                    الدليل الشامل والمتكامل لإدارة الموارد البشرية والتوظيف
                </h1>
                <p style="font-size: 0.95rem; color: #ccfbf1; margin: 0; max-width: 48rem; line-height: 1.6;">
                    مرجعك البصري والتشغيلي الموحد لفهم مسارات النظام: من استقطاب المواهب وبناء النماذج، مروراً بالبصمة الجغرافية الذكية، ودورة الموافقات المتعددة، وحتى مسيرات الرواتب وبوابة الخدمة الذاتية للموظفين.
                </p>
            </div>
        </div>

        {{-- Quick Index Nav --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem;">
            <a href="#recruitment-ats" style="text-decoration: none;" class="guide-flow-card">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">👥</div>
                    <div>
                        <strong style="display: block; font-size: 0.9rem; color: #0f172a;" class="dark:text-white">1. التوظيف واستقطاب المواهب</strong>
                        <span style="font-size: 0.75rem; color: #64748b;">الوظائف، النماذج، والفرز الذكي</span>
                    </div>
                </div>
            </a>

            <a href="#attendance-gps" style="text-decoration: none;" class="guide-flow-card">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">📍</div>
                    <div>
                        <strong style="display: block; font-size: 0.9rem; color: #0f172a;" class="dark:text-white">2. الحضور والانصراف الجغرافي</strong>
                        <span style="font-size: 0.75rem; color: #64748b;">السياج الجغرافي وبصمة GPS</span>
                    </div>
                </div>
            </a>

            <a href="#multi-approvals" style="text-decoration: none;" class="guide-flow-card">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">✍️</div>
                    <div>
                        <strong style="display: block; font-size: 0.9rem; color: #0f172a;" class="dark:text-white">3. دورة الموافقات المتعددة</strong>
                        <span style="font-size: 0.75rem; color: #64748b;">المدير المباشر ثم الموارد البشرية</span>
                    </div>
                </div>
            </a>

            <a href="#payroll-wps" style="text-decoration: none;" class="guide-flow-card">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; background: #f3e8ff; color: #9333ea; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">💰</div>
                    <div>
                        <strong style="display: block; font-size: 0.9rem; color: #0f172a;" class="dark:text-white">4. مسيرات الأجور والرواتب</strong>
                        <span style="font-size: 0.75rem; color: #64748b;">حماية الأجور والبدلات وقسائم القبض</span>
                    </div>
                </div>
            </a>
        </div>

        {{-- ────────────────────────────────────────── --}}
        {{-- SECTION 1: ATS & RECRUITMENT FLOW          --}}
        {{-- ────────────────────────────────────────── --}}
        <div id="recruitment-ats" class="guide-flow-card">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.5rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 1rem;" class="dark:border-slate-800">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; background: #0d9488; color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                        <x-filament::icon icon="heroicon-o-briefcase" style="width: 1.5rem; height: 1.5rem;" />
                    </div>
                    <div>
                        <h2 style="font-size: 1.25rem; font-weight: 900; margin: 0; color: #0f172a;" class="dark:text-white">1. دورة التوظيف واستقطاب المواهب (ATS)</h2>
                        <span style="font-size: 0.8rem; color: #64748b;">من نشر الإعلان الوظيفي حتى التعيين النهائي وكشف التكرار الآلي</span>
                    </div>
                </div>
                <span class="badge-pill" style="background: #e0f2fe; color: #0369a1; font-weight: 800; font-size: 0.75rem; padding: 0.25rem 0.75rem; border-radius: 9999px;">نظام تتبع المرشحين الذكي</span>
            </div>

            {{-- Visual Diagram --}}
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 1rem; padding: 1.5rem; margin-bottom: 1.5rem;" class="dark:bg-slate-900/50 dark:border-slate-800">
                <h4 style="font-size: 0.85rem; font-weight: 800; color: #475569; margin: 0 0 1rem 0; text-transform: uppercase;" class="dark:text-slate-400">
                    رسم توضيحي: مسار تدفق طلبات التوظيف (Workflow)
                </h4>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; position: relative;">
                    <div style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 0.85rem; padding: 1rem; text-align: center;" class="dark:bg-slate-800 dark:border-slate-700">
                        <div class="flow-step-number" style="background: #ccfbf1; color: #0f766e; margin: 0 auto 0.75rem auto;">1</div>
                        <strong style="display: block; font-size: 0.85rem; color: #0f172a; margin-bottom: 0.25rem;" class="dark:text-white">نشر الوظيفة</strong>
                        <p style="font-size: 0.75rem; color: #64748b; margin: 0;">تحديد المتطلبات، تفعيل الحقول الأساسية وتعيينها إجباري/اختياري، وبناء الأسئلة المخصصة.</p>
                    </div>

                    <div style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 0.85rem; padding: 1rem; text-align: center;" class="dark:bg-slate-800 dark:border-slate-700">
                        <div class="flow-step-number" style="background: #e0f2fe; color: #0369a1; margin: 0 auto 0.75rem auto;">2</div>
                        <strong style="display: block; font-size: 0.85rem; color: #0f172a; margin-bottom: 0.25rem;" class="dark:text-white">تقديم المرشح</strong>
                        <p style="font-size: 0.75rem; color: #64748b; margin: 0;">نموذج متجاوب (صفحة واحدة أو Wizard)، صيغة هاتف مناسبة لسوق الدولة، وإشعار تأكيد فوري برقم مرجعي.</p>
                    </div>

                    <div style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 0.85rem; padding: 1rem; text-align: center;" class="dark:bg-slate-800 dark:border-slate-700">
                        <div class="flow-step-number" style="background: #fee2e2; color: #b91c1c; margin: 0 auto 0.75rem auto;">3</div>
                        <strong style="display: block; font-size: 0.85rem; color: #0f172a; margin-bottom: 0.25rem;" class="dark:text-white">كشف التكرار الآلي</strong>
                        <p style="font-size: 0.75rem; color: #64748b; margin: 0;">فحص الهاتف والإيميل تلقائياً لحماية قاعدة البيانات مع خيارات الدمج أو الرفض أو التأكيد.</p>
                    </div>

                    <div style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 0.85rem; padding: 1rem; text-align: center;" class="dark:bg-slate-800 dark:border-slate-700">
                        <div class="flow-step-number" style="background: #fef3c7; color: #b45309; margin: 0 auto 0.75rem auto;">4</div>
                        <strong style="display: block; font-size: 0.85rem; color: #0f172a; margin-bottom: 0.25rem;" class="dark:text-white">الفرز والتقييم</strong>
                        <p style="font-size: 0.75rem; color: #64748b; margin: 0;">تدقيق السيرة الذاتية، التقييم المهني والسلوكي بالنجوم، وجدولة المقابلات وإرسال إشعارات البريد.</p>
                    </div>

                    <div style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 0.85rem; padding: 1rem; text-align: center;" class="dark:bg-slate-800 dark:border-slate-700">
                        <div class="flow-step-number" style="background: #dcfce7; color: #15803d; margin: 0 auto 0.75rem auto;">5</div>
                        <strong style="display: block; font-size: 0.85rem; color: #0f172a; margin-bottom: 0.25rem;" class="dark:text-white">العرض والتعيين</strong>
                        <p style="font-size: 0.75rem; color: #64748b; margin: 0;">إصدار العرض الوظيفي وتحويل المرشح الناجح بضغطة زر إلى موظف رسمي في الهيكل التنظيمي.</p>
                    </div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.25rem;">
                <div style="border-right: 3px solid #0d9488; padding-right: 1rem;">
                    <strong style="display: block; font-size: 0.9rem; color: #0f172a; margin-bottom: 0.35rem;" class="dark:text-white">تخصيص نموذج التقديم لكل وظيفة:</strong>
                    <p style="font-size: 0.8rem; color: #64748b; line-height: 1.6; margin: 0;">
                        يمكنك التحكم في كل حقل على حدة: تفعيل ظهوره، وتحديد ما إذا كان إجبارياً أو اختيارياً (مثل رقم الهوية، السيرة الذاتية، الراتب المتوقع، والمدينة).
                    </p>
                </div>
                <div style="border-right: 3px solid #3b82f6; padding-right: 1rem;">
                    <strong style="display: block; font-size: 0.9rem; color: #0f172a; margin-bottom: 0.35rem;" class="dark:text-white">التأقلم مع أسواق العمل (مصر، السعودية، والخليج):</strong>
                    <p style="font-size: 0.8rem; color: #64748b; line-height: 1.6; margin: 0;">
                        يقوم النظام بتعديل حقل رقم الجوال تلقائياً حسب الدولة المحددة في الإعدادات العامة (مصر +20 مع 11 رقم، السعودية +966 مع 05، أو دول الخليج).
                    </p>
                </div>
            </div>
        </div>

        {{-- ────────────────────────────────────────── --}}
        {{-- SECTION 2: MULTI-LEVEL APPROVALS WORKFLOW  --}}
        {{-- ────────────────────────────────────────── --}}
        <div id="multi-approvals" class="guide-flow-card">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.5rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 1rem;" class="dark:border-slate-800">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; background: #16a34a; color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                        <x-filament::icon icon="heroicon-o-check-badge" style="width: 1.5rem; height: 1.5rem;" />
                    </div>
                    <div>
                        <h2 style="font-size: 1.25rem; font-weight: 900; margin: 0; color: #0f172a;" class="dark:text-white">2. دورة الموافقات المتعددة (الإجازات والاستئذان)</h2>
                        <span style="font-size: 0.8rem; color: #64748b;">مسار اعتماد تصاعدي وذكي يضمن الشفافية وعدم تخطي التسلسل الإداري</span>
                    </div>
                </div>
                <span class="badge-pill" style="background: #dcfce7; color: #15803d; font-weight: 800; font-size: 0.75rem; padding: 0.25rem 0.75rem; border-radius: 9999px;">مستويان للاعتماد: المدير + الـ HR</span>
            </div>

            {{-- Approval Sequence Flowchart --}}
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 1rem; padding: 1.5rem; margin-bottom: 1.5rem;" class="dark:bg-slate-900/50 dark:border-slate-800">
                <h4 style="font-size: 0.85rem; font-weight: 800; color: #475569; margin: 0 0 1rem 0; text-transform: uppercase;" class="dark:text-slate-400">
                    رسم توضيحي: مسار طلب الإجازة والاستئذان
                </h4>

                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <div style="display: flex; align-items: center; gap: 1rem; background: #ffffff; border: 1px solid #e2e8f0; padding: 0.85rem 1.25rem; border-radius: 0.85rem;" class="dark:bg-slate-800 dark:border-slate-700">
                        <span style="width: 2rem; height: 2rem; border-radius: 9999px; background: #e0f2fe; color: #0284c7; font-weight: 900; display: flex; align-items: center; justify-content: center; font-size: 0.8rem;">1</span>
                        <div style="flex-grow: 1;">
                            <strong style="display: block; font-size: 0.85rem; color: #0f172a;" class="dark:text-white">الموظف يقدم الطلب من بوابة الخدمة الذاتية (ESS)</strong>
                            <span style="font-size: 0.75rem; color: #64748b;">يختار نوع الإجازة وتاريخ البداية والنهاية، والنظام يفحص الرصيد المتاح تلقائياً ويمنع التجاوز.</span>
                        </div>
                        <span class="badge-pill" style="background: #fef3c7; color: #b45309; font-size: 0.7rem; font-weight: 700; padding: 0.15rem 0.5rem; border-radius: 0.35rem;">حالة الطلب: معلق</span>
                    </div>

                    <div style="display: flex; align-items: center; gap: 1rem; background: #ffffff; border: 1px solid #e2e8f0; padding: 0.85rem 1.25rem; border-radius: 0.85rem;" class="dark:bg-slate-800 dark:border-slate-700">
                        <span style="width: 2rem; height: 2rem; border-radius: 9999px; background: #fef3c7; color: #b45309; font-weight: 900; display: flex; align-items: center; justify-content: center; font-size: 0.8rem;">2</span>
                        <div style="flex-grow: 1;">
                            <strong style="display: block; font-size: 0.85rem; color: #0f172a;" class="dark:text-white">المستوى الأول: موافقة المدير المباشر (Direct Manager)</strong>
                            <span style="font-size: 0.75rem; color: #64748b;">يصل إشعار فوري للمدير المسؤول لمراجعة عبء العمل في القسم ثم اعتماد الطلب أو رفضه مع إبداء الأسباب.</span>
                        </div>
                        <span class="badge-pill" style="background: #e0f2fe; color: #0369a1; font-size: 0.7rem; font-weight: 700; padding: 0.15rem 0.5rem; border-radius: 0.35rem;">معتمد من المدير</span>
                    </div>

                    <div style="display: flex; align-items: center; gap: 1rem; background: #ffffff; border: 1px solid #e2e8f0; padding: 0.85rem 1.25rem; border-radius: 0.85rem;" class="dark:bg-slate-800 dark:border-slate-700">
                        <span style="width: 2rem; height: 2rem; border-radius: 9999px; background: #dcfce7; color: #15803d; font-weight: 900; display: flex; align-items: center; justify-content: center; font-size: 0.8rem;">3</span>
                        <div style="flex-grow: 1;">
                            <strong style="display: block; font-size: 0.85rem; color: #0f172a;" class="dark:text-white">المستوى الثاني: الاعتماد النهائي لإدارة الموارد البشرية (HR Final Approval)</strong>
                            <span style="font-size: 0.75rem; color: #64748b;">يقوم مسؤولو الموارد البشرية بالاعتماد الرسمي، مما يخصم الأيام تلقائياً من رصيد الإجازات السنوي ويرسل إشعاراً فورياً للموظف.</span>
                        </div>
                        <span class="badge-pill" style="background: #dcfce7; color: #15803d; font-size: 0.7rem; font-weight: 700; padding: 0.15rem 0.5rem; border-radius: 0.35rem;">معتمد نهائياً</span>
                    </div>
                </div>
            </div>

            <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 0.85rem; padding: 1rem; color: #1e40af; font-size: 0.825rem; line-height: 1.5;" class="dark:bg-blue-950/40 dark:border-blue-900 dark:text-blue-200">
                💡 <strong>ملاحظة هامة للمدير العام (Super Admin):</strong> يمتلك مدير النظام صلاحية "اعتماد مباشر وتجاوز" للطلبات العاجلة والطارئة دون الحاجة للمرور بمراحل التسلسل عند الضرورة.
            </div>
        </div>

        {{-- ────────────────────────────────────────── --}}
        {{-- SECTION 3: GPS ATTENDANCE & GEOFENCING     --}}
        {{-- ────────────────────────────────────────── --}}
        <div id="attendance-gps" class="guide-flow-card">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.5rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 1rem;" class="dark:border-slate-800">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; background: #d97706; color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                        <x-filament::icon icon="heroicon-o-map-pin" style="width: 1.5rem; height: 1.5rem;" />
                    </div>
                    <div>
                        <h2 style="font-size: 1.25rem; font-weight: 900; margin: 0; color: #0f172a;" class="dark:text-white">3. نظام الحضور والانصراف الجغرافي والذكي</h2>
                        <span style="font-size: 0.8rem; color: #64748b;">تسجيل الحضور عبر البوابة بالسياج الجغرافي (Geofencing) وفترة السماح</span>
                    </div>
                </div>
                <span class="badge-pill" style="background: #fef3c7; color: #92400e; font-weight: 800; font-size: 0.75rem; padding: 0.25rem 0.75rem; border-radius: 9999px;">دقة عالية بالـ GPS</span>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.25rem; margin-bottom: 1.5rem;">
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 1rem; padding: 1.25rem;" class="dark:bg-slate-900/50 dark:border-slate-800">
                    <strong style="display: block; font-size: 0.9rem; color: #0f172a; margin-bottom: 0.5rem;" class="dark:text-white">📍 السياج الجغرافي (Geofencing):</strong>
                    <p style="font-size: 0.8rem; color: #64748b; line-height: 1.6; margin: 0;">
                        يمكن تفعيل تحديد الموقع الجغرافي للشركة (خط الطول، دائرة العرض، ونصف القطر بالمتر) من الإعدادات العامة. لن يتمكن الموظف من تسجيل البصمة إلا إذا كان متواجداً فعلياً داخل حرم الشركة.
                    </p>
                </div>

                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 1rem; padding: 1.25rem;" class="dark:bg-slate-900/50 dark:border-slate-800">
                    <strong style="display: block; font-size: 0.9rem; color: #0f172a; margin-bottom: 0.5rem;" class="dark:text-white">⏱️ فترة السماح ورصد التأخير:</strong>
                    <p style="font-size: 0.8rem; color: #64748b; line-height: 1.6; margin: 0;">
                        يحدد النظام فترة سماح بالدقائق (مثال: 15 دقيقة بعد موعد بدء الوردية). يتم احتساب أي تأخير بعد ذلك بدقة بالدقائق ويظهر في سجلات الحضور الشهرية لتسوية الرواتب.
                    </p>
                </div>

                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 1rem; padding: 1.25rem;" class="dark:bg-slate-900/50 dark:border-slate-800">
                    <strong style="display: block; font-size: 0.9rem; color: #0f172a; margin-bottom: 0.5rem;" class="dark:text-white">🌐 العمل عن بُعد (Remote Clock-in):</strong>
                    <p style="font-size: 0.8rem; color: #64748b; line-height: 1.6; margin: 0;">
                        يمكن لمسؤول النظام السماح للموظفين ذوي طبيعة العمل المرنة أو عن بُعد بتسجيل الحضور دون التقيد بالنطاق الجغرافي للفرع.
                    </p>
                </div>
            </div>
        </div>

        {{-- ────────────────────────────────────────── --}}
        {{-- SECTION 4: PAYROLL & WPS                   --}}
        {{-- ────────────────────────────────────────── --}}
        <div id="payroll-wps" class="guide-flow-card">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.5rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 1rem;" class="dark:border-slate-800">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; background: #9333ea; color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                        <x-filament::icon icon="heroicon-o-banknotes" style="width: 1.5rem; height: 1.5rem;" />
                    </div>
                    <div>
                        <h2 style="font-size: 1.25rem; font-weight: 900; margin: 0; color: #0f172a;" class="dark:text-white">4. الرواتب، مسيرات الأجور وحماية الأجور (WPS)</h2>
                        <span style="font-size: 0.8rem; color: #64748b;">احتساب الرواتب التلقائي، ملفات البنوك، وقسائم الرواتب الفورية</span>
                    </div>
                </div>
                <span class="badge-pill" style="background: #f3e8ff; color: #7e22ce; font-weight: 800; font-size: 0.75rem; padding: 0.25rem 0.75rem; border-radius: 9999px;">تصدير معتمد للبنوك</span>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.25rem;">
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 1rem; padding: 1.25rem;" class="dark:bg-slate-900/50 dark:border-slate-800">
                    <strong style="display: block; font-size: 0.9rem; color: #0f172a; margin-bottom: 0.5rem;" class="dark:text-white">1. التوليد الآلي للمسير الشهري:</strong>
                    <p style="font-size: 0.8rem; color: #64748b; line-height: 1.6; margin: 0;">
                        بضغطة زر واحدة يقوم النظام بحساب رواتب جميع الموظفين بناءً على الراتب الأساسي، بدل السكن، بدل الانتقال، ومطابقة سجلات الغياب والتأخير غير المبرر.
                    </p>
                </div>

                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 1rem; padding: 1.25rem;" class="dark:bg-slate-900/50 dark:border-slate-800">
                    <strong style="display: block; font-size: 0.9rem; color: #0f172a; margin-bottom: 0.5rem;" class="dark:text-white">2. تصدير ملف حماية الأجور (WPS SIF File):</strong>
                    <p style="font-size: 0.8rem; color: #64748b; line-height: 1.6; margin: 0;">
                        تصدير ملف المسير بصيغة البنوك المعتمدة وحماية الأجور المتوافقة مع البنوك المصرية والسعودية والخليجية لرفعه مباشرة إلى منصة البنك.
                    </p>
                </div>

                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 1rem; padding: 1.25rem;" class="dark:bg-slate-900/50 dark:border-slate-800">
                    <strong style="display: block; font-size: 0.9rem; color: #0f172a; margin-bottom: 0.5rem;" class="dark:text-white">3. قسيمة الراتب الإلكترونية (Payslip):</strong>
                    <p style="font-size: 0.8rem; color: #64748b; line-height: 1.6; margin: 0;">
                        بمجرد اعتماد المسير، تظهر قسيمة الراتب فوراً في بوابة الموظف الذاتية مع تفاصيل كاملة للبدلات والاستقطاعات وإمكانية الطباعة والحفظ بصيغة PDF.
                    </p>
                </div>
            </div>
        </div>

        {{-- ────────────────────────────────────────── --}}
        {{-- SECTION 5: EMPLOYEE SELF-SERVICE PORTAL    --}}
        {{-- ────────────────────────────────────────── --}}
        <div id="ess-portal" class="guide-flow-card">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.5rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 1rem;" class="dark:border-slate-800">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; background: #0284c7; color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                        <x-filament::icon icon="heroicon-o-device-phone-mobile" style="width: 1.5rem; height: 1.5rem;" />
                    </div>
                    <div>
                        <h2 style="font-size: 1.25rem; font-weight: 900; margin: 0; color: #0f172a;" class="dark:text-white">5. بوابة الخدمة الذاتية للموظف (ESS Portal)</h2>
                        <span style="font-size: 0.8rem; color: #64748b;">واجهة عصرية صممت خصيصاً للجوال لتمكين الموظف وإراحته</span>
                    </div>
                </div>
                <span class="badge-pill" style="background: #e0f2fe; color: #0369a1; font-weight: 800; font-size: 0.75rem; padding: 0.25rem 0.75rem; border-radius: 9999px;">رابط مخصص: /ess</span>
            </div>

            <p style="font-size: 0.85rem; color: #64748b; line-height: 1.6; margin-bottom: 1rem;">
                يمكن للموظفين الدخول برقم الهوية أو البريد الإلكتروني وكلمة المرور من أي هاتف ذكي، والاستفادة من الخدمات التالية دون مراجعة إدارة الموارد البشرية يدوياً:
            </p>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem;">
                <div style="padding: 1rem; border: 1px dashed #cbd5e1; border-radius: 0.75rem;" class="dark:border-slate-700">
                    <strong style="display: block; font-size: 0.85rem; color: #0f172a; margin-bottom: 0.25rem;" class="dark:text-white">📱 البصمة الذكية</strong>
                    <span style="font-size: 0.75rem; color: #64748b;">تسجيل الحضور والانصراف بضغطة زر وتأكيد الموقع الجغرافي.</span>
                </div>
                <div style="padding: 1rem; border: 1px dashed #cbd5e1; border-radius: 0.75rem;" class="dark:border-slate-700">
                    <strong style="display: block; font-size: 0.85rem; color: #0f172a; margin-bottom: 0.25rem;" class="dark:text-white">🏖️ طلبات الإجازات والاستئذان</strong>
                    <span style="font-size: 0.75rem; color: #64748b;">متابعة حالة الطلبات لحظة بلحظة ومعرفة رصيد الإجازات المتبقي.</span>
                </div>
                <div style="padding: 1rem; border: 1px dashed #cbd5e1; border-radius: 0.75rem;" class="dark:border-slate-700">
                    <strong style="display: block; font-size: 0.85rem; color: #0f172a; margin-bottom: 0.25rem;" class="dark:text-white">💵 كشوف الرواتب الشهرية</strong>
                    <span style="font-size: 0.75rem; color: #64748b;">الاطلاع على قسائم الرواتب بالتفصيل وطباعتها بأي وقت.</span>
                </div>
                <div style="padding: 1rem; border: 1px dashed #cbd5e1; border-radius: 0.75rem;" class="dark:border-slate-700">
                    <strong style="display: block; font-size: 0.85rem; color: #0f172a; margin-bottom: 0.25rem;" class="dark:text-white">📢 إعلانات وتعاميم الشركة</strong>
                    <span style="font-size: 0.75rem; color: #64748b;">استقبال القرارات الإدارية والتنبيهات الرسمية فور نشرها.</span>
                </div>
            </div>
        </div>

        {{-- ────────────────────────────────────────── --}}
        {{-- SECTION 6: SYSTEM RESILIENCY & UPDATES     --}}
        {{-- ────────────────────────────────────────── --}}
        <div class="guide-flow-card" style="background: #f8fafc; border: 1px solid #cbd5e1;" class="dark:bg-slate-900 dark:border-slate-800">
            <div style="display: flex; align-items: flex-start; gap: 1rem;">
                <div style="width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; background: #334155; color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0;">
                    <x-filament::icon icon="heroicon-o-shield-check" style="width: 1.5rem; height: 1.5rem;" />
                </div>
                <div>
                    <h3 style="font-size: 1rem; font-weight: 800; color: #0f172a; margin: 0 0 0.5rem 0;" class="dark:text-white">
                        تحديثات قاعدة البيانات والاستضافات المشتركة (Hostinger / cPanel):
                    </h3>
                    <p style="font-size: 0.8rem; color: #64748b; line-height: 1.6; margin: 0 0 0.75rem 0;">
                        تم تزويد النظام بنظام تحقق ذاتي تلقائي لمنع أخطاء الجداول المفقودة (Base table or view not found). وإذا تعذر تشغيل سطر الأوامر (Terminal / SSH) على استضافتك، يمكنك تشغيل الهجرة بضغطة زر واحدة وآمنة عبر الرابط التالي:
                    </p>
                    <code style="background: #e2e8f0; color: #0f172a; padding: 0.35rem 0.75rem; border-radius: 0.5rem; font-size: 0.75rem; font-family: monospace; display: inline-block;" class="dark:bg-slate-800 dark:text-teal-400">
                        {{ url('/system/migrate?key=hr2026migrate') }}
                    </code>
                </div>
            </div>
        </div>

    </div>
</x-filament-panels::page>
