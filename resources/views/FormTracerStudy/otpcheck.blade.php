<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card mx-auto" style="max-width: 400px;">
            <div class="card-body">
                <h4 class="card-title text-center mb-4">Verifikasi OTP</h4>
                <form method="POST" action="{{ route('otp.validation') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Anda</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="otp" class="form-label">Masukkan Kode OTP</label>
                        <input type="text" name="otp" id="otp" class="form-control" required>
                        @if(session('error'))
                            <small class="text-danger">{{ session('error') }}</small>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-success w-100">Verifikasi</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
