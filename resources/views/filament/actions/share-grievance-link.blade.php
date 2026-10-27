<div class="space-y-4 text-right" dir="rtl">
    <!-- Link Copy Card -->
    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/90 space-y-2">
        <label class="text-xs font-bold text-slate-700 block">رابط التقديم المباشر للموظفين</label>
        <div class="flex items-center gap-2">
            <input type="text" id="complaints_share_input" readonly value="{{ url('/complaints') }}" 
                   class="flex-1 text-xs font-mono font-bold bg-white border border-slate-300 rounded-xl px-3 py-2.5 text-slate-800" dir="ltr">
            <button type="button" 
                    onclick="navigator.clipboard.writeText(document.getElementById('complaints_share_input').value); const btn = this; btn.innerText = '✓ تم النسخ!'; setTimeout(() => btn.innerText = 'نسخ الرابط', 2500);" 
                    class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white rounded-xl text-xs font-bold transition-all whitespace-nowrap shadow-sm">
                نسخ الرابط
            </button>
        </div>
        <span class="text-[11px] text-slate-500 block mt-1">هذا الرابط يفتح صندوق الشكاوى الرقمي مباشرة لتقديم ومتابعة الشكاوى والمقترحات (سواء مع تسجيل دخول أو بدونه بحسب إعدادات النظام).</span>
    </div>

    <!-- Quick Action Buttons: WhatsApp & Email -->
    <div class="grid grid-cols-2 gap-3">
        <a href="https://api.whatsapp.com/send?text={{ urlencode('زملائي الأعزاء، تفعيلاً للتحول الرقمي وتسهيل التواصل بالشركة، يمكنكم تقديم أي شكوى، ملاحظة أو مقترح تطويري إلكترونياً وبسرية تامة عبر الرابط التالي: ' . url('/complaints')) }}" 
           target="_blank" 
           class="p-3.5 rounded-2xl bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 text-emerald-800 flex items-center justify-center gap-2 text-xs font-bold transition-all shadow-sm">
            <span class="text-base">💬</span>
            <span>مشاركة عبر واتساب للموظفين</span>
        </a>

        <a href="mailto:?subject={{ urlencode('رابط تقديم الشكاوى والمقترحات الرقمي - قسم الموارد البشرية') }}&body={{ urlencode('زملائي الأعزاء،' . "\n\n" . 'تفعيلاً للتحول الرقمي وتسهيل التواصل بالشركة، يمكنكم تقديم أي شكوى، ملاحظة أو مقترح تطويري إلكترونياً وبسرية تامة عبر الرابط التالي:' . "\n" . url('/complaints') . "\n\n" . 'مع تحيات إدارة الموارد البشرية.') }}" 
           target="_blank" 
           class="p-3.5 rounded-2xl bg-blue-50 hover:bg-blue-100 border border-blue-200 text-blue-800 flex items-center justify-center gap-2 text-xs font-bold transition-all shadow-sm">
            <span class="text-base">✉️</span>
            <span>إرسال تعميم عبر الإيميل</span>
        </a>
    </div>

    <!-- Printable QR Code Section -->
    <div class="p-4 rounded-2xl border border-dashed border-slate-300 bg-white flex flex-col sm:flex-row items-center gap-4 text-center sm:text-right shadow-sm">
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode(url('/complaints')) }}" 
             alt="QR Code" 
             class="w-28 h-28 rounded-xl border border-slate-100 shadow-sm p-1.5 bg-white shrink-0">
        <div class="space-y-1.5">
            <span class="text-xs font-black text-slate-900 block">رمز الاستجابة السريعة (QR Code)</span>
            <p class="text-[11px] text-slate-500 leading-relaxed">
                يمكن للموظف مسح هذا الرمز بكاميرا الهاتف للوصول الفوري، كما يمكنك طباعة ملصق رسمي لتعليقه في لوحة إعلانات الشركة أو صالة الاستراحة.
            </p>
            <button type="button" 
                    onclick="const w = window.open(''); w.document.write('<div style=\'text-align:center;padding:50px;font-family:sans-serif;direction:rtl;\'><h1 style=\'margin-bottom:10px;font-size:26px;\'>صندوق الشكاوى والمقترحات الرقمي</h1><p style=\'color:#64748b;margin-bottom:30px;font-size:16px;\'>امسح الرمز بكاميرا الجوال لتقديم أي شكوى أو مقترح رسمي وبسرية تامة</p><img src=\'https://api.qrserver.com/v1/create-qr-code/?size=350x350&data={{ urlencode(url('/complaints')) }}\' style=\'width:300px;height:300px;border-radius:16px;box-shadow:0 10px 25px rgba(0,0,0,0.1);\'><br><br><p style=\'font-weight:bold;font-size:18px;color:#0f172a;\'>{{ url('/complaints') }}</p><p style=\'color:#94a3b8;font-size:13px;margin-top:10px;\'>إدارة الموارد البشرية</p></div>'); w.print();"
                    class="inline-flex items-center gap-1.5 text-xs text-indigo-600 hover:text-indigo-800 font-bold underline mt-1">
                🖨️ طباعة ملصق الـ QR Code للتعليق في الشركة
            </button>
        </div>
    </div>
</div>
