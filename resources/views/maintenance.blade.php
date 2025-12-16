<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance - {{ $site_name }}</title>
    @vite(['resources/css/app.css'])
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg">
                    <div class="card-body text-center p-5">
                        <i class="bi bi-wrench text-warning" style="font-size: 5rem;"></i>
                        <h1 class="fw-bold mt-4 mb-3">Website Dalam Perbaikan</h1>
                        <p class="lead mb-4">{{ $message }}</p>
                        <small class="text-muted">{{ $site_name }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>