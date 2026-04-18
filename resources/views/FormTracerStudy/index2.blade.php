<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sistem Tracer Study - Politeknik Negeri Malang</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-green: #13754C;
            --primary-yellow: #F7DC6F;
            --text-dark: #2c3e50;
            --text-light: #6c757d;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--text-dark);
        }

        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('{{ asset("landingpageimg/campus-aerial.jpg") }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            border-radius: 0 0 50px 50px;
            overflow: hidden;
        }

        .hero-logo {
            width: 120px;
            height: 120px;
            background: white;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            transition: transform 0.3s ease;
        }

        .hero-logo:hover {
            transform: translateY(-5px);
        }

        .hero-logo i {
            font-size: 3rem;
            color: var(--primary-green);
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            color: white;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
            margin-bottom: 2rem;
            line-height: 1.2;
        }

        .btn-primary-custom {
            background: var(--primary-yellow);
            color: #000;
            font-weight: 600;
            padding: 15px 40px;
            border-radius: 50px;
            border: none;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary-custom:hover {
            background: #f4d03f;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            color: #000;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 2rem;
        }

        .benefit-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border: none;
            margin-bottom: 2rem;
            position: relative;
        }

        .benefit-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .benefit-number {
            width: 60px;
            height: 60px;
            background: var(--primary-green);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            position: absolute;
            top: -30px;
            left: 2rem;
        }

        .benefit-card .card-body {
            padding-top: 2rem;
        }

        .mechanism-item {
            text-align: center;
            padding: 2rem;
            background: white;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            margin-bottom: 2rem;
        }

        .mechanism-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }

        .mechanism-icon {
            width: 100px;
            height: 100px;
            margin: 0 auto 1.5rem;
            background: var(--primary-green);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2.5rem;
        }

        .footer-custom {
            background: var(--primary-green);
            color: white;
            position: relative;
            overflow: hidden;
        }

        .footer-custom::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 100px;
            background: var(--primary-green);
            clip-path: polygon(0 0, 100% 50px, 100% 100%, 0 100%);
        }

        .footer-content {
            position: relative;
            z-index: 2;
            padding-top: 4rem;
        }

        .footer-cta {
            background: rgba(255,255,255,0.1);
            border-radius: 20px;
            padding: 3rem;
            margin-bottom: 3rem;
            backdrop-filter: blur(10px);
        }

        .copyright-bar {
            background: var(--primary-yellow);
            color: #000;
            padding: 1rem 0;
            margin: 0;
        }

        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .benefit-number {
                position: relative;
                top: 0;
                left: 0;
                margin: 0 auto 1.5rem;
            }
        }
    </style>
</head>

<body>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right" data-aos-duration="1000">
                    <div class="hero-logo">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h1 class="hero-title">
                        Selamat Datang di<br>
                        Sistem Tracer Study
                    </h1>
                    <a href="{{ route('form.opsi') }}" class="btn-primary-custom">
                        <i class="fas fa-edit me-2"></i>Isi Formulir
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- What is Tracer Study Section -->
    <section class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right" data-aos-duration="1000">
                    <h2 class="section-title">Apa itu Tracer Study?</h2>
                    <p class="lead text-muted">
                        Tracer Study adalah survei yang dilakukan oleh institusi pendidikan kepada alumni untuk 
                        melacak jejak karier dan penilaian terhadap pendidikan yang telah diterima.
                    </p>
                </div>
                <div class="col-lg-6" data-aos="fade-left" data-aos-duration="1000">
                    <!-- Benefits Cards -->
                    <div class="row">
                        <div class="col-12">
                            <div class="benefit-card">
                                <div class="benefit-number">1</div>
                                <div class="card-body">
                                    <h5 class="card-title fw-bold">Evaluasi</h5>
                                    <p class="card-text text-muted">
                                        Sebagai masukan jurusan dan prodi untuk perbaikan kurikulum
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="benefit-card">
                                <div class="benefit-number">2</div>
                                <div class="card-body">
                                    <h5 class="card-title fw-bold">Komunikasi</h5>
                                    <p class="card-text text-muted">
                                        Sebagai media penghubung antara alumni, kampus dan dunia kerja
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="benefit-card">
                                <div class="benefit-number">3</div>
                                <div class="card-body">
                                    <h5 class="card-title fw-bold">Informasi</h5>
                                    <p class="card-text text-muted">
                                        Sebagai sumber informasi yang dapat digunakan untuk membuat kebijakan strategis
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mechanism Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="section-title text-center mb-5" data-aos="fade-up" data-aos-duration="1000">
                Mekanisme Tracer Study
            </h2>
            <div class="row">
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="100">
                    <div class="mechanism-item">
                        <div class="mechanism-icon">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <p class="fw-semibold">Alumni mengisi form Tracer Study</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
                    <div class="mechanism-item">
                        <div class="mechanism-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <p class="fw-semibold">Pengguna lulusan mengisi survey penilaian kinerja alumni</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="300">
                    <div class="mechanism-item">
                        <div class="mechanism-icon">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <p class="fw-semibold">Data akan dikelola untuk berbagai keperluan seperti evaluasi, peningkatan kualitas pendidikan</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Description -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10" data-aos="fade-up" data-aos-duration="1000">
                    <h2 class="section-title text-center">Manfaat Tracer Study</h2>
                    <p class="lead text-center text-muted">
                        Tracer Study adalah jembatan antara kampus dan para alumninya. Melalui survei ini, kampus dapat 
                        melacak bagaimana perjalanan karier lulusan setelah menyelesaikan pendidikan. Lebih dari sekadar 
                        formalitas, Tracer Study menjadi alat penting untuk mengevaluasi kualitas pendidikan, memperbaiki 
                        kurikulum, dan memastikan bahwa apa yang diajarkan benar-benar relevan dengan kebutuhan dunia kerja.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer-custom">
        <div class="footer-content">
            <div class="container">
                <!-- CTA Section -->
                <div class="footer-cta text-center" data-aos="fade-up" data-aos-duration="1000">
                    <div class="row align-items-center justify-content-center">
                        <div class="col-lg-8">
                            <h3 class="fw-bold mb-4">
                                Partisipasi Anda penting untuk kemajuan pendidikan tinggi.
                            </h3>
                            <a href="{{ route('form.opsi') }}" class="btn-primary-custom">
                                <i class="fas fa-edit me-2"></i>Isi Formulir
                            </a>
                        </div>
                    </div>
                </div>

                <hr class="border-light opacity-25 my-5">

                <!-- Footer Info -->
                <div class="row" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
                    <div class="col-lg-4 mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ asset('landingpageimg/3186eafb-bdc8-461c-93ef-b3ac617a517c 3.png') }}" 
                                 alt="Logo Politeknik Negeri Malang" 
                                 style="width: 80px; height: 80px;" class="me-3">
                            <div>
                                <h5 class="fw-bold mb-1">Tracer Study</h5>
                                <p class="mb-0">Politeknik Negeri Malang</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-4">
                        <h5 class="fw-bold mb-3">Kontak Kami</h5>
                        <p class="mb-2"><i class="fas fa-map-marker-alt me-2"></i>Politeknik Negeri Malang, Jl. Soekarno Hatta</p>
                        <p class="mb-2">No.9, Jatimulyo, Kec. Lowokwaru, Kota Malang</p>
                        <p class="mb-2"><i class="fas fa-phone me-2"></i>(0341) 404424 / 404425</p>
                        <p class="mb-0"><i class="fas fa-envelope me-2"></i>humas@polinema.ac.id</p>
                    </div>
                    <div class="col-lg-4 mb-4">
                        <h5 class="fw-bold mb-3">Berita Terbaru</h5>
                        <p>
                            Anda dapat mengakses berita terbaru mengenai Polinema 
                            <a href="https://www.polinema.ac.id/" class="text-white text-decoration-underline">disini</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Copyright -->
        <div class="copyright-bar text-center">
            <div class="container">
                <p class="mb-0 fw-semibold">© 2025 Politeknik Negeri Malang. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AOS Animation -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Add scroll effect to hero background
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const hero = document.querySelector('.hero-section');
            if (hero) {
                hero.style.transform = `translateY(${scrolled * 0.5}px)`;
            }
        });
    </script>
</body>
</html>