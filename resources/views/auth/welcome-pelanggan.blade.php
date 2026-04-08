<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang - PDAM Magetan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .welcome-container {
            background: white;
            border-radius: 20px;
            padding: 50px;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            text-align: center;
            animation: slideUp 0.5s ease;
        }

        .success-icon {
            font-size: 80px;
            color: #28a745;
            margin-bottom: 20px;
            animation: bounce 1s ease;
        }

        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 32px;
        }

        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 18px;
        }

        .info-card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 30px;
            margin: 30px 0;
        }

        .info-label {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }

        .nomor-pelanggan {
            font-size: 36px;
            font-weight: bold;
            color: #0d6efd;
            letter-spacing: 3px;
            font-family: 'Courier New', monospace;
            margin: 15px 0;
        }

        .copy-btn {
            background: #6c757d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 10px;
            transition: all 0.3s ease;
        }

        .copy-btn:hover {
            background: #5c636a;
            transform: translateY(-2px);
        }

        .warning-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 20px;
            border-radius: 8px;
            text-align: left;
            margin: 20px 0;
        }

        .warning-box i {
            color: #856404;
            margin-right: 10px;
        }

        .warning-box p {
            color: #856404;
            margin: 0;
            font-size: 14px;
        }

        .info-list {
            text-align: left;
            margin-top: 20px;
        }

        .info-list li {
            color: #666;
            margin-bottom: 10px;
            padding-left: 25px;
            position: relative;
        }

        .info-list li:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: #28a745;
            font-weight: bold;
        }

        .btn-continue {
            background: #0d6efd;
            color: white;
            border: none;
            padding: 15px 40px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 30px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-continue:hover {
            background: #0b5ed7;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(13, 110, 253, 0.3);
        }

        @keyframes slideUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
    </style>
</head>
<body>
    <div class="welcome-container">
        <i class="fas fa-check-circle success-icon"></i>
        <h1>Selamat Datang!</h1>
        <p class="subtitle">Registrasi Anda berhasil, {{ $pelanggan->nama_pelanggan }}</p>

        <div class="info-card">
            <div class="info-label">Nomor Pelanggan Anda</div>
            <div class="nomor-pelanggan" id="nomorPelanggan">{{ $pelanggan->no_pelanggan }}</div>
            <button class="copy-btn" onclick="copyNomor()">
                <i class="fas fa-copy"></i> Salin Nomor
            </button>
        </div>

        <div class="warning-box">
            <p>
                <i class="fas fa-exclamation-triangle"></i>
                <strong>PENTING:</strong> Simpan nomor pelanggan ini dengan baik. Anda akan memerlukan nomor ini untuk:
            </p>
            <ul class="info-list">
                <li>Pembayaran tagihan bulanan</li>
                <li>Pengajuan pengaduan</li>
                <li>Permintaan informasi layanan</li>
                <li>Akses ke portal pelanggan</li>
            </ul>
        </div>

        <a href="{{ route('homepage') }}" class="btn-continue">
            <i class="fas fa-arrow-right"></i> Lanjutkan ke Beranda
        </a>
    </div>

    <script>
        function copyNomor() {
            const nomor = document.getElementById('nomorPelanggan').textContent;
            
            navigator.clipboard.writeText(nomor).then(() => {
                const btn = event.target.closest('button');
                const originalHTML = btn.innerHTML;
                
                btn.innerHTML = '<i class="fas fa-check"></i> Tersalin!';
                btn.style.background = '#28a745';
                
                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                    btn.style.background = '#6c757d';
                }, 2000);
            }).catch(err => {
                alert('Gagal menyalin. Nomor Anda: ' + nomor);
            });
        }
    </script>
</body>
</html>