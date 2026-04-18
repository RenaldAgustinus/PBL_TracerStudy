<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sistem Tracer Study - Politeknik Negeri Malang</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-green: #13754C;
            --secondary-green: #1a8f5f;
            --accent-yellow: #F7DC6F;
            --dark-yellow: #e6c659;
            --light-green: #f0f8f5;
            --text-dark: #2c3e50;
            --text-light: #6c757d;
            --white: #ffffff;
            --shadow: 0 8px 25px rgba(0,0,0,0.08);
            --shadow-hover: 0 15px 35px rgba(0,0,0,0.12);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: var(--text-dark);
            overflow-x: hidden;
        }

        html {
            scroll-behavior: smooth;
        }

        /* Header Section */
        .header-section {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--secondary-green) 100%);
            color: white;
            padding: 3rem 0 2rem;
            position: relative;
            overflow: hidden;
            min-height: 90vh;
            display: flex;
            align-items: center;
        }

        .header-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><defs><radialGradient id="a" cx="50%" cy="50%"><stop offset="0%" stop-color="%23ffffff" stop-opacity="0.08"/><stop offset="100%" stop-color="%23ffffff" stop-opacity="0"/></radialGradient></defs><circle cx="200" cy="200" r="100" fill="url(%23a)"/><circle cx="800" cy="300" r="150" fill="url(%23a)"/><circle cx="400" cy="700" r="120" fill="url(%23a)"/></svg>');
            opacity: 0.6;
        }

        .welcome-text {
            font-size: 3.2rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1.2rem;
            background: linear-gradient(45deg, #ffffff, #f0f8ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .welcome-subtitle {
            font-size: 1.2rem;
            font-weight: 400;
            margin-bottom: 2rem;
            opacity: 0.9;
            line-height: 1.6;
        }

        .btn-fill-form {
            background: linear-gradient(45deg, var(--accent-yellow), var(--dark-yellow));
            color: var(--text-dark);
            font-weight: 600;
            padding: 0.9rem 2.2rem;
            border: none;
            border-radius: 50px;
            font-size: 1.05rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
        }

        .btn-fill-form::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }

        .btn-fill-form:hover::before {
            left: 100%;
        }

        .btn-fill-form:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
            color: var(--text-dark);
        }

        .hero-image {
            position: relative;
            z-index: 2;
        }

        .hero-image img {
            max-width: 100%;
            height: auto;
            filter: drop-shadow(0 15px 30px rgba(0,0,0,0.15));
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
        }

        /* Section Styling */
        .section {
            padding: 3rem 0;
            position: relative;
        }

        .section-compact {
            padding: 2.5rem 0;
        }

        .section-title {
            font-size: 2.4rem;
            font-weight: 700;
            margin-bottom: 0.8rem;
            color: var(--text-dark);
            position: relative;
        }

        .section-subtitle {
            font-size: 1.1rem;
            color: var(--text-light);
            margin-bottom: 2rem;
            line-height: 1.7;
        }

        /* What is Tracer Study Section */
        .tracer-study-section {
            background: linear-gradient(135deg, #fafbfc 0%, #ffffff 100%);
            margin-top: -1rem;
            padding-top: 4rem;
        }

        .feature-card {
            background: var(--white);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            border: 1px solid rgba(19, 117, 76, 0.08);
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(45deg, var(--primary-green), var(--secondary-green));
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.2rem;
            color: white;
            font-size: 1.8rem;
        }

        /* Mechanism Section */
        .mechanism-section {
            background: var(--white);
            position: relative;
            padding: 2.5rem 0;
        }

        .mechanism-card {
            background: var(--white);
            border-radius: 16px;
            padding: 2rem 1.5rem;
            text-align: center;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            border: 1px solid rgba(19, 117, 76, 0.08);
            position: relative;
            overflow: hidden;
            height: 100%;
        }

        .mechanism-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(45deg, var(--primary-green), var(--accent-yellow));
        }

        .mechanism-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-hover);
        }

        .mechanism-number {
            position: absolute;
            top: 15px;
            right: 15px;
            width: 35px;
            height: 35px;
            background: linear-gradient(45deg, var(--primary-green), var(--secondary-green));
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .mechanism-icon {
            width: 85px;
            height: 85px;
            margin: 0 auto 1.2rem;
            background: var(--light-green);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .mechanism-card:hover .mechanism-icon {
            background: var(--primary-green);
            color: white;
        }

        .mechanism-icon img {
            width: 55px;
            height: 55px;
            object-fit: contain;
        }

        .mechanism-text {
            font-size: 1rem;
            font-weight: 500;
            color: var(--text-dark);
            line-height: 1.5;
        }

        /* Benefits Section */
        .benefits-section {
            background: linear-gradient(135deg, var(--light-green) 0%, #ffffff 100%);
            position: relative;
            padding: 2.5rem 0;
        }

        .benefits-content {
            background: var(--white);
            border-radius: 16px;
            padding: 2.5rem;
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
        }

        .benefits-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(45deg, var(--primary-green), var(--accent-yellow));
        }

        /* CTA Footer */
        .cta-footer {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--secondary-green) 100%);
            color: white;
            padding: 3rem 0 0;
            position: relative;
            overflow: hidden;
            clip-path: polygon(0 8%, 100% 0%, 100% 100%, 0 100%);
            margin-top: 2rem;
        }

        .cta-footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><defs><radialGradient id="b" cx="50%" cy="50%"><stop offset="0%" stop-color="%23ffffff" stop-opacity="0.04"/><stop offset="100%" stop-color="%23ffffff" stop-opacity="0"/></radialGradient></defs><circle cx="100" cy="100" r="80" fill="url(%23b)"/><circle cx="900" cy="200" r="120" fill="url(%23b)"/><circle cx="300" cy="800" r="100" fill="url(%23b)"/></svg>');
        }

        .cta-content {
            position: relative;
            z-index: 2;
            padding-top: 1.5rem;
        }

        .cta-title {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            line-height: 1.3;
        }

        .footer-info {
            background: rgba(0,0,0,0.08);
            padding: 2rem 0;
            margin-top: 2.5rem;
        }

        .footer-logo {
            width: 70px;
            height: 70px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .footer-title {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 0.3rem;
        }

        .footer-subtitle {
            font-size: 0.95rem;
            opacity: 0.9;
        }

        .contact-info {
            line-height: 1.7;
        }

        .contact-info i {
            width: 18px;
            color: var(--accent-yellow);
        }

        .copyright {
            background: var(--accent-yellow);
            color: var(--text-dark);
            padding: 0.8rem 0;
            text-align: center;
            font-weight: 500;
        }

        /* Natural Transitions */
        .section-transition {
            position: relative;
        }

        .section-transition::before {
            content: '';
            position: absolute;
            top: -50px;
            left: 0;
            right: 0;
            height: 100px;
            background: linear-gradient(to bottom, transparent, rgba(255,255,255,0.5), transparent);
            pointer-events: none;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .welcome-text {
                font-size: 2.2rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .cta-title {
                font-size: 1.8rem;
            }
            
            .header-section {
                padding: 2rem 0 1.5rem;
                min-height: auto;
            }
            
            .section {
                padding: 2rem 0;
            }

            .section-compact {
                padding: 1.5rem 0;
            }

            .mechanism-card {
                margin-bottom: 1rem;
            }
        }

        @media (max-width: 576px) {
            .welcome-text {
                font-size: 1.9rem;
            }
            
            .section-title {
                font-size: 1.7rem;
            }
            
            .btn-fill-form {
                padding: 0.7rem 1.8rem;
                font-size: 0.95rem;
            }

            .feature-card, .mechanism-card, .benefits-content {
                padding: 1.5rem;
            }
        }

        /* Loading Animation */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--primary-green);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            transition: opacity 0.5s ease;
        }

        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 3px solid rgba(255,255,255,0.3);
            border-top: 3px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Smooth section connections */
        .section-connector {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(19, 117, 76, 0.1), transparent);
            margin: 0 auto;
            width: 80%;
        }
    </style>
</head>

<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>

    <div class="container-fluid p-0">
        <!-- Header Section -->
        <section class="header-section">
            <div class="container">
                <div class="row align-items-center">
                    <!-- Content Column -->
                    <div class="col-lg-6 order-2 order-lg-1" data-aos="fade-right" data-aos-duration="1000">
                        <h1 class="welcome-text">
                            Selamat Datang di<br>
                            <span style="color: var(--accent-yellow);">Sistem Tracer Study</span>
                        </h1>
                        <p class="welcome-subtitle">
                            Bergabunglah dengan ribuan alumni Politeknik Negeri Malang dalam membangun masa depan pendidikan yang lebih baik melalui jejak karier Anda.
                        </p>
                        <a href="{{ route('form.opsi') }}" class="btn-fill-form">
                            <i class="fas fa-edit"></i>
                            Isi Formulir Sekarang
                        </a>
                    </div>

                    <!-- Image Column -->
                    <div class="col-lg-6 order-1 order-lg-2 hero-image" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="200">
                        <img src="{{ asset('landingpageimg/IMG_6875.png') }}" alt="Tracer Study Illustration" class="img-fluid">
                    </div>
                </div>
            </div>
        </section>

        <!-- What is Tracer Study Section -->
        <section class="section tracer-study-section">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6" data-aos="fade-right" data-aos-duration="800">
                        <h2 class="section-title">Apa itu Tracer Study?</h2>
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-search"></i>
                            </div>
                            <p class="section-subtitle mb-0">
                                Tracer Study adalah survei komprehensif yang dilakukan oleh institusi pendidikan kepada para alumni untuk mengetahui jejak karier mereka, aktivitas pekerjaan setelah lulus, serta mengukur sejauh mana pendidikan yang telah diterima selaras dengan tuntutan dunia kerja modern.
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-6" data-aos="fade-left" data-aos-duration="800" data-aos-delay="200">
                        <div class="text-center">
                            <img src="{{ asset('landingpageimg/Workflow visualization with kanban board.png') }}" 
                                 alt="Tracer Study Illustration" 
                                 class="img-fluid"
                                 style="max-width: 85%; filter: drop-shadow(0 10px 25px rgba(0,0,0,0.08));">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section Connector -->
        <div class="section-connector"></div>

        <!-- Mechanism Section -->
        <section class="section-compact mechanism-section">
            <div class="container">
                <div class="text-center mb-4" data-aos="fade-up" data-aos-duration="800">
                    <h2 class="section-title">Mekanisme Tracer Study</h2>
                    <p class="section-subtitle">Proses sederhana dalam 4 langkah </p>
                </div>
                
                <div class="row g-3">
                    <!-- Step 1 -->
                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
                        <div class="mechanism-card">
                            <div class="mechanism-number">1</div>
                            <div class="mechanism-icon">
                                <img src="{{ asset('landingpageimg/Online survey on tablet screen.png') }}" alt="Form Icon">
                            </div>
                            <p class="mechanism-text">Alumni mengisi formulir Tracer Study dengan data terkini</p>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
                        <div class="mechanism-card">
                            <div class="mechanism-number">2</div>
                            <div class="mechanism-icon">
                                <img src="{{ asset('landingpageimg/spam email.png') }}" alt="Email Icon">
                            </div>
                            <p class="mechanism-text">Sistem mengirimkan email kepada pengguna lulusan berupa kode OTP</p>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-duration="800" data-aos-delay="300">
                        <div class="mechanism-card">
                            <div class="mechanism-number">3</div>
                            <div class="mechanism-icon">
                                <img src="{{ asset('landingpageimg/documents.png') }}" alt="Survey Icon">
                            </div>
                            <p class="mechanism-text">Pengguna lulusan menilai kinerja alumni dan feedback</p>
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-duration="800" data-aos-delay="400">
                        <div class="mechanism-card">
                            <div class="mechanism-number">4</div>
                            <div class="mechanism-icon">
                                <img src="{{ asset('landingpageimg/pie chart.png') }}" alt="Data Icon">
                            </div>
                            <p class="mechanism-text">Data dianalisis untuk evaluasi dan peningkatan kualitas pendidikan</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section Connector -->
        <div class="section-connector"></div>

        <!-- Benefits Section -->
        <section class="section-compact benefits-section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="text-center mb-4" data-aos="fade-up" data-aos-duration="800">
                            <h2 class="section-title">Manfaat Tracer Study</h2>
                        </div>
                        
                        <div class="benefits-content" data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
                            <div class="row align-items-center">
                                <div class="col-lg-8">
                                    <p class="section-subtitle mb-0">
                                        Tracer Study adalah jembatan strategis antara kampus dan para alumninya. Melalui survei komprehensif ini, kampus dapat melacak perjalanan karier lulusan, mengidentifikasi tren industri, dan memahami kebutuhan pasar kerja. 
                                        <br><br>
                                        Lebih dari sekadar formalitas, Tracer Study menjadi instrumen vital untuk mengevaluasi efektivitas kurikulum, memperbaiki metode pembelajaran, dan memastikan relevansi pendidikan dengan dinamika dunia kerja yang terus berkembang.
                                    </p>
                                </div>
                                <div class="col-lg-4 text-center">
                                        <img src="{{ asset('landingpageimg/Employee doing risk management for company.png') }}" alt="Data Icon" style="width: 250px; height: 250px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Footer -->
        <footer class="cta-footer">
            <div class="cta-content">
                <div class="container text-center">
                    <div class="row align-items-center justify-content-center mb-4" data-aos="fade-up" data-aos-duration="800">
                        <div class="col-lg-8">
                            <h2 class="cta-title">
                                Partisipasi Anda Sangat Penting untuk<br>
                                <span style="color: var(--accent-yellow);">Kemajuan Pendidikan Tinggi</span>
                            </h2>
                            <p class="mb-3" style="font-size: 1.1rem; opacity: 0.9;">
                                Bergabunglah dengan ribuan alumni lainnya dalam membangun masa depan pendidikan yang lebih baik
                            </p>
                            <a href="{{ route('form.opsi') }}" class="btn-fill-form">
                                <i class="fas fa-edit"></i>
                                Mulai Isi Formulir
                            </a>
                        </div>
                    </div>
                    
                    <hr style="border-color: rgba(255,255,255,0.2); margin: 2rem 0;">
                </div>

                <div class="footer-info">
                    <div class="container">
                        <div class="row">
                            <!-- Logo dan Judul -->
                            <div class="col-lg-4 mb-3" data-aos="fade-up" data-aos-duration="800">
                                <div class="d-flex align-items-center mb-2">
                                    <img src="{{ asset('landingpageimg/3186eafb-bdc8-461c-93ef-b3ac617a517c 3.png') }}" 
                                         alt="Politeknik Negeri Malang Logo" 
                                         class="footer-logo me-3">
                                    <div>
                                        <div class="footer-title">Tracer Study</div>
                                        <div class="footer-subtitle">Politeknik Negeri Malang</div>
                                    </div>
                                </div>
                                <p style="opacity: 0.8; line-height: 1.5; font-size: 0.9rem;">
                                    Membangun jembatan antara pendidikan dan dunia kerja melalui data dan insight yang akurat.
                                </p>
                            </div>

                            <!-- Kontak -->
                            <div class="col-lg-4 mb-3" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
                                <h5 class="footer-title mb-2">Kontak Kami</h5>
                                <div class="contact-info">
                                    <p class="mb-1" style="font-size: 0.9rem;">
                                        <i class="fas fa-map-marker-alt me-2"></i>
                                        Jl. Soekarno Hatta No.9, Jatimulyo<br>
                                        <span class="ms-4">Kec. Lowokwaru, Kota Malang</span>
                                    </p>
                                    <p class="mb-1" style="font-size: 0.9rem;">
                                        <i class="fas fa-phone me-2"></i>
                                        (0341) 404424 / 404425
                                    </p>
                                    <p class="mb-0" style="font-size: 0.9rem;">
                                        <i class="fas fa-envelope me-2"></i>
                                        humas@polinema.ac.id
                                    </p>
                                </div>
                            </div>

                            <!-- Berita -->
                            <div class="col-lg-4 mb-3" data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
                                <h5 class="footer-title mb-2">Informasi Terbaru</h5>
                                <p class="mb-2" style="opacity: 0.9; font-size: 0.9rem;">
                                    Dapatkan informasi terbaru mengenai Politeknik Negeri Malang dan perkembangan dunia pendidikan.
                                </p>
                                <a href="https://www.polinema.ac.id/" 
                                   class="btn btn-outline-light btn-sm"
                                   style="border-radius: 20px; padding: 0.4rem 1.2rem; font-size: 0.85rem;">
                                    <i class="fas fa-external-link-alt me-1"></i>
                                    Kunjungi Website
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

        <!-- Copyright -->
        <div class="copyright">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start">
                        <small>© 2025 Politeknik Negeri Malang. All Rights Reserved.</small>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <small>Developed with <i class="fas fa-heart text-danger"></i> for better education</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize AOS
            AOS.init({
                duration: 600,
                easing: 'ease-in-out',
                once: true,
                offset: 80
            });

            // Hide loading overlay
            setTimeout(function() {
                $('#loadingOverlay').fadeOut(400);
            }, 800);

            // Smooth scrolling for anchor links
            $('a[href^="#"]').on('click', function(event) {
                event.preventDefault();
                const target = $($.attr(this, 'href'));
                if (target.length) {
                    $('html, body').animate({
                        scrollTop: target.offset().top - 60
                    }, 600);
                }
            });

            // Add scroll effect
            $(window).scroll(function() {
                if ($(this).scrollTop() > 100) {
                    $('.navbar').addClass('scrolled');
                } else {
                    $('.navbar').removeClass('scrolled');
                }
            });

            // Enhanced hover effects
            $('.mechanism-card, .feature-card').hover(
                function() {
                    $(this).addClass('shadow-lg');
                },
                function() {
                    $(this).removeClass('shadow-lg');
                }
            );

            // Reduced parallax effect for smoother performance
            $(window).scroll(function() {
                const scrolled = $(this).scrollTop();
                const parallax = $('.header-section');
                const speed = scrolled * 0.3;
                parallax.css('transform', 'translateY(' + speed + 'px)');
            });

            // Form enhancement
            $('form').on('submit', function(e) {
                const form = $(this);
                const submitBtn = form.find('button[type="submit"]');
                
                submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Mengirim...');
                submitBtn.prop('disabled', true);
                
                setTimeout(function() {
                    submitBtn.html('Kirim Formulir');
                    submitBtn.prop('disabled', false);
                }, 2500);
            });
        });

        // Optimized animations
        document.addEventListener('DOMContentLoaded', function() {
            // Reduced floating animation
            const heroImage = document.querySelector('.hero-image img');
            if (heroImage) {
                let floatDirection = 1;
                setInterval(() => {
                    floatDirection *= -1;
                    heroImage.style.transform = `translateY(${floatDirection * 8}px)`;
                }, 4000);
            }
        });
    </script>
</body>

</html>