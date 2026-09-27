<div style="text-align: center; padding: 20px;">
    <p style="margin-bottom: 20px; color: gray;">قم بمسح الرمز للذهاب مباشرة إلى صفحة التقديم، أو اضغط بزر الماوس الأيمن لحفظ الصورة ومشاركتها.</p>
    <div style="display: flex; justify-content: center;">
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ urlencode($url) }}" alt="QR Code" style="border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border: 1px solid #e5e7eb; padding: 10px; background: white;" width="250" height="250" />
    </div>
    <div style="margin-top: 20px;">
        <a href="{{ $url }}" target="_blank" style="color: #4f46e5; text-decoration: underline; word-break: break-all; font-size: 0.9em;">
            {{ $url }}
        </a>
    </div>
</div>
