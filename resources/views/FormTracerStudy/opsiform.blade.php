<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sistem Tracer Study</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }
        
        main {
            flex: 1;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #fff;
        }

        .logo-title {
            display: flex;
            align-items: center;
            padding: 1rem;
        }

        .logo-title img {
            height: 64px;
            margin-right: 1rem;
        }

        .info-card,
        .btn-formulir,
        .card-wrapper {
            font-family: 'Poppins', sans-serif;
        }

        .card-wrapper {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            flex-wrap: wrap;
            margin-top: 2rem;
            margin-bottom: 5rem;
        }

        .info-card {
            width: 280px;
            /* sebelumnya mungkin 320px atau auto */
            padding: 1rem 1.2rem;
            border: 1px solid #ddd;
            border-radius: 12px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-align: center;
        }

        .info-card img {
            width: 60%;
            height: auto;
            margin-bottom: 1rem;
        }

        .info-card h5 {
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .info-card p {
            font-size: 0.85rem;
            line-height: 1.4;
            margin-bottom: 1rem;
        }


        .btn-formulir {
            background-color: #1e824c;
            color: white;
            border: none;
            padding: 0.5rem 1.2rem;
            border-radius: 6px;
            font-weight: 500;
        }

        .btn-formulir:hover {
            background-color: #166a3b;
        }

        .copyright {
            background-color: #166a3b;
            color: white;
            text-align: center;
            font-size: 0.8rem;
            font-weight: 500;
            padding: 0.8rem;
            margin-top: 3rem;
        }

        footer {
            text-align: center;
            background-color: #166a3b;
            padding: 10px 0;
            font-size: 12px;
            font-weight: bold;
            color: white;
            margin-top: 50px;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .info-card {
            opacity: 0;
            animation: fadeUp 0.8s ease forwards;
        }

        .info-card:nth-child(1) {
            animation-delay: 0.2s;
        }

        .info-card:nth-child(2) {
            animation-delay: 0.4s;
        }

        .info-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>

<body>

    <!-- Header -->
<a href="{{ url('/tracerstudy') }}" class="text-decoration-none text-dark">
    <div class="logo-title d-flex align-items-center">
        <img src="{{ asset('landingpageimg/Logo_Polinema 1.png') }}" alt="Logo Polinema" />
        <div class="ms-2">
            <h5 class="mb-0">TRACER STUDY</h5>
            <small>Politeknik Negeri Malang</small>
        </div>
    </div>
</a>


    <main>
        <!-- Card Section -->
        <div class="card-wrapper">
            <!-- Card 1: Alumni -->
            <div class="info-card">
                <img src="{{ asset('landingpageimg/happy students.png') }}" alt="Alumni Image" />
                <h5>Alumni</h5>
                <p style="margin-bottom: 35px; ">Pengguna yang telah lulus dari institusi dan mengisi form untuk
                    memberikan
                    informasi terkait pekerjaan,
                    studi lanjutan, dan pengalaman pasca-kelulusan.</p>
                <a class="btn-formulir btn btn-fill-form mb-5" href="{{ route('form.alumni') }}">Isi Formulir</a>
            </div>

            <!-- Card 2: Pengguna Lulusan -->
            <div class="info-card">
                <img src="{{ asset('landingpageimg/Office worker talking on phone.png') }}" alt="Employer Image" />
                <h5>Pengguna Lulusan</h5>
                <p>Pihak eksternal seperti perusahaan atau instansi yang mempekerjakan lulusan, bertujuan memberikan
                    penilaian terhadap kompetensi dan kinerja lulusan di tempat kerja.</p>
                <a class="btn-formulir btn btn-fill-form mb-5" href="{{ route('otp.check') }}">Isi
                    Formulir</a>
            </div>
        </div>
    </main>
    <!-- Footer -->
    <footer style="margin-bottom: 0">
        &copy; 2025 Politeknik Negeri Malang. All Rights Reserved.
    </footer>

</body>

</html>
