<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kode OTP Administrator - TurningCode</title>
    <style>
        body {
            background-color: #0d0e12;
            color: #e2e8f0;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 40px 20px;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            background: rgba(30, 32, 43, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 32px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #ffffff;
            margin-bottom: 24px;
            letter-spacing: 1px;
        }
        .logo span {
            color: #d4af37; /* gold */
        }
        h2 {
            font-size: 20px;
            margin-bottom: 8px;
            color: #ffffff;
        }
        p {
            font-size: 14px;
            line-height: 1.6;
            color: #94a3b8;
            margin-bottom: 24px;
        }
        .otp-code {
            display: inline-block;
            font-size: 36px;
            font-weight: 800;
            letter-spacing: 8px;
            color: #d4af37;
            background: rgba(212, 175, 55, 0.1);
            border: 1px dashed rgba(212, 175, 55, 0.5);
            padding: 12px 32px;
            border-radius: 12px;
            margin: 16px 0;
            text-shadow: 0 0 10px rgba(212, 175, 55, 0.2);
        }
        .footer {
            margin-top: 32px;
            font-size: 11px;
            color: #475569;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">Turning<span>Code</span></div>
        <h2>Autentikasi Administrator</h2>
        <p>Anda menerima email ini karena ada permintaan login untuk akses panel administrator <strong>{{ $email }}</strong>. Gunakan kode keamanan di bawah ini untuk melanjutkan:</p>
        
        <div class="otp-code">{{ $otp }}</div>
        
        <p style="font-size: 12px; color: #ef4444; margin-top: 16px;">Kode ini hanya berlaku selama 10 menit. Jangan membagikan kode ini kepada siapa pun termasuk tim TurningCode.</p>
        
        <div class="footer">
            Sistem Keamanan Otomatis &copy; {{ date('Y') }} TurningCode. All rights reserved.
        </div>
    </div>
</body>
</html>
