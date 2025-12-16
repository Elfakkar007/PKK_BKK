<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: #0d6efd;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border: 1px solid #dee2e6;
        }
        .info-row {
            margin-bottom: 15px;
        }
        .label {
            font-weight: bold;
            color: #495057;
        }
        .message-box {
            background: white;
            padding: 20px;
            border-left: 4px solid #0d6efd;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #6c757d;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>📧 Pesan Baru dari Form Kontak</h2>
        </div>
        
        <div class="content">
            <div class="info-row">
                <span class="label">Dari:</span> {{ $contactRequest->name }}
            </div>
            <div class="info-row">
                <span class="label">Email:</span> 
                <a href="mailto:{{ $contactRequest->email }}">{{ $contactRequest->email }}</a>
            </div>
            <div class="info-row">
                <span class="label">Subjek:</span> {{ $contactRequest->subject }}
            </div>
            <div class="info-row">
                <span class="label">Waktu:</span> {{ $contactRequest->created_at->format('d/m/Y H:i') }} WIB
            </div>
            
            <div class="message-box">
                <div class="label" style="margin-bottom: 10px;">Pesan:</div>
                <div>{{ $contactRequest->message }}</div>
            </div>
        </div>
        
        <div class="footer">
            <p>Email ini dikirim otomatis dari sistem BKK SMKN 1 Purwosari</p>
            <p>Anda dapat membalas langsung ke email pengirim</p>
        </div>
    </div>
</body>
</html>