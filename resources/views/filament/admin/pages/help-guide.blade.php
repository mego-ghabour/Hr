<x-filament-panels::page>
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        
        {{-- Header Section --}}
        <x-filament::section icon="heroicon-o-book-open" icon-color="primary">
            <x-slot name="heading">
                <span class="text-xl font-bold">الدليل الشامل لنظام إدارة المرشحين (ATS)</span>
            </x-slot>
            
            <p class="text-gray-600 dark:text-gray-400 text-lg leading-relaxed">
                هذا الدليل مصمم ليكون مرجعك الأساسي لفهم كل جزء في النظام وكيفية استخدامه بأقصى كفاءة لإدارة عمليات التوظيف داخل شركتك.
            </p>
        </x-filament::section>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-top: 1.5rem;">
            
            {{-- Dashboard --}}
            <x-filament::section icon="heroicon-o-chart-pie" icon-color="info">
                <x-slot name="heading">لوحة التحكم (Dashboard)</x-slot>
                
                <p class="text-gray-600 dark:text-gray-400" style="margin-bottom: 1rem;">
                    أول شاشة تقابلك، وهي تعطيك نظرة عامة فورية عن حالة التوظيف.
                </p>
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <div style="display: flex; gap: 0.75rem;">
                        <x-filament::icon icon="heroicon-m-funnel" class="h-6 w-6 text-primary-500" style="min-width: 1.5rem;" />
                        <div><strong class="text-gray-900 dark:text-white">فلاتر سريعة:</strong> تغيير الإحصائيات حسب القسم أو التاريخ.</div>
                    </div>
                    <div style="display: flex; gap: 0.75rem;">
                        <x-filament::icon icon="heroicon-m-presentation-chart-line" class="h-6 w-6 text-success-500" style="min-width: 1.5rem;" />
                        <div><strong class="text-gray-900 dark:text-white">الإحصائيات السريعة:</strong> أعداد المرشحين الإجمالية وحالاتهم.</div>
                    </div>
                    <div style="display: flex; gap: 0.75rem;">
                        <x-filament::icon icon="heroicon-m-chart-bar-square" class="h-6 w-6 text-warning-500" style="min-width: 1.5rem;" />
                        <div><strong class="text-gray-900 dark:text-white">توزيع المرشحين:</strong> رسوم بيانية توضح إقبال المرشحين على الأقسام.</div>
                    </div>
                </div>
            </x-filament::section>

            {{-- Talents --}}
            <x-filament::section icon="heroicon-o-users" icon-color="primary">
                <x-slot name="heading">إدارة المرشحين (Talents)</x-slot>
                
                <p class="text-gray-600 dark:text-gray-400" style="margin-bottom: 1rem;">
                    قلب النظام النابض. هنا تجد قائمة بكل من تقدم لوظيفة أو تم إضافته يدوياً.
                </p>
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <div style="display: flex; gap: 0.75rem;">
                        <x-filament::icon icon="heroicon-m-document-text" class="h-6 w-6 text-gray-500" style="min-width: 1.5rem;" />
                        <div><strong class="text-gray-900 dark:text-white">الملاحظات:</strong> إضافة تعليق سريع يقرأه باقي فريق الموارد البشرية.</div>
                    </div>
                    <div style="display: flex; gap: 0.75rem;">
                        <x-filament::icon icon="heroicon-m-star" class="h-6 w-6 text-warning-500" style="min-width: 1.5rem;" />
                        <div><strong class="text-gray-900 dark:text-white">التقييمات:</strong> تقييم المرشح بعد المقابلة (فني، سلوكي، الخ).</div>
                    </div>
                    <div style="display: flex; gap: 0.75rem;">
                        <x-filament::icon icon="heroicon-m-calendar-days" class="h-6 w-6 text-info-500" style="min-width: 1.5rem;" />
                        <div><strong class="text-gray-900 dark:text-white">المتابعات:</strong> جدولة مواعيد المقابلات أو المكالمات القادمة معه.</div>
                    </div>
                    <div style="display: flex; gap: 0.75rem;">
                        <x-filament::icon icon="heroicon-m-paper-clip" class="h-6 w-6 text-danger-500" style="min-width: 1.5rem;" />
                        <div><strong class="text-gray-900 dark:text-white">المستندات:</strong> السيرة الذاتية (CV) والشهادات المرفوعة.</div>
                    </div>
                </div>
            </x-filament::section>

            {{-- Duplicates --}}
            <x-filament::section icon="heroicon-o-document-duplicate" icon-color="danger">
                <x-slot name="heading">الملفات المكررة (Duplicates)</x-slot>
                
                <p class="text-gray-600 dark:text-gray-400" style="margin-bottom: 1rem;">
                    النظام ذكي جداً. يقوم آلياً بالبحث عن أي مرشح تقدم مرتين باستخدام (رقم الجوال أو الإيميل).
                </p>
                
                <div class="bg-danger-50 dark:bg-danger-900/30 border border-danger-200 dark:border-danger-800" style="padding: 1rem; border-radius: 0.75rem; display: flex; gap: 0.75rem;">
                    <x-filament::icon icon="heroicon-m-exclamation-triangle" class="h-6 w-6 text-danger-600 dark:text-danger-400" style="min-width: 1.5rem; flex-shrink: 0;" />
                    <p class="text-danger-800 dark:text-danger-400 text-sm font-medium leading-relaxed">
                        إذا وُجد تشابه، سيظهر المرشح هنا لتتخذ قرارك: (دمج البيانات، رفض الجديد، أو تأكيد بأنهما شخصان مختلفان).
                    </p>
                </div>
            </x-filament::section>

            {{-- External Form --}}
            <x-filament::section icon="heroicon-o-globe-alt" icon-color="success">
                <x-slot name="heading">استمارة التقديم الخارجية</x-slot>
                
                <p class="text-gray-600 dark:text-gray-400" style="margin-bottom: 1rem;">
                    يوجد في القائمة الجانبية رابط اسمه <strong class="text-gray-900 dark:text-white">"استمارة التقديم الخارجي"</strong>.
                </p>
                <div class="bg-gray-50 dark:bg-gray-800" style="padding: 1rem; border-radius: 0.75rem; display: flex; gap: 0.75rem;">
                    <x-filament::icon icon="heroicon-m-share" class="h-6 w-6 text-success-600 dark:text-success-400" style="min-width: 1.5rem; flex-shrink: 0;" />
                    <p class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed">
                        يمكنك نشر هذا الرابط في (لينكد إن، فيسبوك). أي شخص يملأ هذه الاستمارة سيتم تسجيله فوراً كـ "مرشح جديد" وستصلك إشعار.
                    </p>
                </div>
            </x-filament::section>

        </div>

        {{-- Settings --}}
        <div style="margin-top: 1.5rem;">
            <x-filament::section icon="heroicon-o-cog-6-tooth" icon-color="gray">
                <x-slot name="heading">الإعدادات العامة وإعدادات النظام</x-slot>
                
                <p class="text-gray-600 dark:text-gray-400" style="margin-bottom: 1.5rem;">
                    يحتوي قسم الإعدادات (في أسفل القائمة الجانبية) على كل ما تحتاجه للتحكم في ثوابت النظام:
                </p>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                    <div class="bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700" style="padding: 1.25rem; border-radius: 1rem;">
                        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                            <x-filament::icon icon="heroicon-m-building-office" class="h-6 w-6 text-primary-500" style="min-width: 1.5rem;" />
                            <strong class="text-gray-900 dark:text-white text-lg">إعدادات النظام</strong>
                        </div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">لتغيير لوجو الشركة وتغيير كلمة مرور حسابك الشخصي.</span>
                    </div>
                    
                    <div class="bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700" style="padding: 1.25rem; border-radius: 1rem;">
                        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                            <x-filament::icon icon="heroicon-m-chat-bubble-left-ellipsis" class="h-6 w-6 text-success-500" style="min-width: 1.5rem;" />
                            <strong class="text-gray-900 dark:text-white text-lg">إعدادات التواصل</strong>
                        </div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">لإعداد رسائل البريد الإلكتروني (الإيميل) والـ SMS.</span>
                    </div>
                    
                    <div class="bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700" style="padding: 1.25rem; border-radius: 1rem;">
                        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                            <x-filament::icon icon="heroicon-m-tag" class="h-6 w-6 text-warning-500" style="min-width: 1.5rem;" />
                            <strong class="text-gray-900 dark:text-white text-lg">ثوابت التوظيف</strong>
                        </div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">لإضافة (الأقسام، مصادر التوظيف، حالات المرشحين).</span>
                    </div>
                </div>
            </x-filament::section>
        </div>

    </div>
</x-filament-panels::page>