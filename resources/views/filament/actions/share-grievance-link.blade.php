<div style="direction: rtl; font-family: inherit; color: #0f172a;">
    <!-- Link Copy Box -->
    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 0.875rem; padding: 1rem; margin-bottom: 0.85rem;">
        <label style="display: block; font-size: 0.8rem; font-weight: 800; color: #334155; margin-bottom: 0.35rem;">
            🔗 رابط صندوق الشكاوى المباشر للموظفين
        </label>
        
        <div style="display: flex; gap: 0.5rem; align-items: center; background: #f8fafc; border: 1.5px solid #cbd5e1; border-radius: 0.75rem; padding: 0.35rem;">
            <input type="text" id="complaints_share_input" readonly value="{{ url('/complaints') }}" 
                   style="flex: 1; border: none; background: transparent; font-family: monospace; font-size: 0.85rem; font-weight: 700; color: #0f172a; outline: none; padding: 0.5rem 0.75rem; direction: ltr; text-align: left;">
            <button type="button" 
                    onclick="navigator.clipboard.writeText(document.getElementById('complaints_share_input').value); const btn = this; btn.innerText = '✓ تم النسخ!'; btn.style.background = '#059669'; setTimeout(() => { btn.innerText = 'نسخ الرابط'; btn.style.background = '#0d9488'; }, 2500);" 
                    style="background: #0d9488; color: white; border: none; border-radius: 0.5rem; font-size: 0.8rem; font-weight: 800; padding: 0.6rem 1.25rem; cursor: pointer; transition: all 0.2s; white-space: nowrap;">
                نسخ الرابط
            </button>
        </div>
        <span style="display: block; font-size: 0.725rem; color: #64748b; margin-top: 0.45rem;">
            هذا الرابط يفتح صندوق الشكاوى والمقترحات الرقمي مباشرة لتقديم ومتابعة الشكاوى سواء مع تسجيل الدخول أو بدونه.
        </span>
    </div>

    <!-- Quick Action Buttons: WhatsApp & Email -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 0.85rem;">
        <a href="https://api.whatsapp.com/send?text={{ urlencode('زملائي الأعزاء، تفعيلاً للتحول الرقمي وتسهيل التواصل بالشركة، يمكنكم تقديم أي شكوى، ملاحظة أو مقترح تطويري إلكترونياً وبسرية تامة عبر الرابط التالي: ' . url('/complaints')) }}" 
           target="_blank" 
           style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.75rem 0.85rem; background: #ecfdf5; border: 1.5px solid #a7f3d0; border-radius: 0.75rem; color: #065f46; font-size: 0.8rem; font-weight: 800; text-decoration: none; cursor: pointer; transition: all 0.15s; text-align: center;">
            <span style="font-size: 1.1rem;">💬</span>
            <span>مشاركة عبر واتساب للموظفين</span>
        </a>

        <a href="mailto:?subject={{ urlencode('رابط تقديم الشكاوى والمقترحات الرقمي - قسم الموارد البشرية') }}&body={{ urlencode('زملائي الأعزاء،' . "\n\n" . 'تفعيلاً للتحول الرقمي وتسهيل التواصل بالشركة، يمكنكم تقديم أي شكوى، ملاحظة أو مقترح تطويري إلكترونياً وبسرية تامة عبر الرابط التالي:' . "\n" . url('/complaints') . "\n\n" . 'مع تحيات إدارة الموارد البشرية.') }}" 
           target="_blank" 
           style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.75rem 0.85rem; background: #eff6ff; border: 1.5px solid #bfdbfe; border-radius: 0.75rem; color: #1e40af; font-size: 0.8rem; font-weight: 800; text-decoration: none; cursor: pointer; transition: all 0.15s; text-align: center;">
            <span style="font-size: 1.1rem;">✉️</span>
            <span>إرسال تعميم عبر الإيميل</span>
        </a>
    </div>

    <!-- Printable QR Code Section -->
    <div style="display: flex; align-items: center; gap: 1.25rem; background: #ffffff; border: 1.5px dashed #cbd5e1; border-radius: 1rem; padding: 1.15rem;">
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode(url('/complaints')) }}" 
             alt="QR Code" 
             style="width: 110px; height: 110px; border-radius: 0.75rem; border: 1px solid #e2e8f0; padding: 6px; background: white; flex-shrink: 0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
        
        <div>
            <span style="display: block; font-size: 0.875rem; font-weight: 800; color: #0f172a; margin-bottom: 0.25rem;">
                رمز الاستجابة السريعة (QR Code)
            </span>
            <p style="font-size: 0.75rem; color: #64748b; line-height: 1.5; margin: 0 0 0.65rem 0;">
                يمكن للموظف مسح هذا الرمز بكاميرا الجوال للوصول المباشر والفوري دون كتابة الرابط.
            </p>
            <button type="button" 
                    onclick="const w = window.open(''); w.document.write('<div style=\'text-align:center;padding:50px;font-family:Cairo,sans-serif;direction:rtl;\'><h1 style=\'margin-bottom:10px;font-size:26px;color:#0f172a;\'>صندوق الشكاوى والمقترحات الرقمي</h1><p style=\'color:#64748b;margin-bottom:30px;font-size:16px;\'>امسح الرمز بكاميرا الجوال لتقديم أي شكوى أو مقترح رسمي وبسرية تامة</p><img src=\'https://api.qrserver.com/v1/create-qr-code/?size=350x350&data={{ urlencode(url('/complaints')) }}\' style=\'width:280px;height:280px;border-radius:16px;box-shadow:0 10px 25px rgba(0,0,0,0.1);padding:10px;background:white;border:1px solid #e2e8f0;\'><br><br><p style=\'font-weight:bold;font-size:18px;color:#0f172a;letter-spacing:1px;\'>{{ url('/complaints') }}</p><p style=\'color:#94a3b8;font-size:14px;margin-top:10px;\'>إدارة الموارد البشرية</p></div>'); w.print();"
                    style="display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.775rem; font-weight: 800; color: #0d9488; background: #f0fdfa; border: 1px solid #ccfbf1; padding: 0.4rem 0.85rem; border-radius: 0.5rem; cursor: pointer;">
                <span>🖨️</span>
                <span>طباعة ملصق الـ QR Code للتعليق بالشركة</span>
            </button>
        </div>
    </div>
</div>
