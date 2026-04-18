<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Tracer Study</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }

        header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            padding: 20px;
            display: flex;
            align-items: center;
        }

        header img {
            height: 60px;
            margin-right: 20px;
        }

        footer {
            text-align: center;
            background-color: #f4cf58;
            padding: 10px 0;
            font-size: 12px;
            font-weight: bold;
            color: #000;
            margin-top: 50px;
        }

        .form-section {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 4px 4px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .form-section h2 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .button-group {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }

        button {
            padding: 10px 25px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        button[type="submit"] {
            background-color: #007649;
            color: white;
        }

        button.kembali {
            background-color: #ccc;
            color: black;
        }
    </style>
</head>

<body>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js"></script>
    <script type="text/javascript">
        (function() {
            emailjs.init({
                publicKey: "pTkI6NIc56E_HvOFb",
            });
        })();
    </script>

    <script>
        $(document).ready(function() {
            // Update form action saat NIM berubah
            $('#nim').on('change', function() {
                var nim = $(this).val();
                if (nim) {
                    $('#form-tracer').attr('action', '/tracerstudy/formulir/' + nim);
                    $.ajax({
                        url: '/tracerstudy/get-alumni-data/' + nim,
                        method: 'GET',
                        success: function(data) {
                            if (data) {
                                $('#nama_alumni').val(data.nama_alumni);
                                $('#prodi').val(data.prodi);
                                $('#tgl_lulus').val(data.tgl_lulus);
                            } else {
                                alert('Data alumni tidak ditemukan.');
                            }
                        },
                        error: function() {
                            alert('Gagal mengambil data alumni.');
                        }
                    });
                }
            });

            // Input nama_alumni untuk autocomplete (optional)
            $('#nama_alumni').on('input', function() {
                var nama = $(this).val();
                if (nama.length >= 3) {
                    $.ajax({
                        url: '/tracerstudy/get-alumni-data/' + encodeURIComponent(nama),
                        method: 'GET',
                        success: function(data) {
                            if (data) {
                                $('#nim').val(data.nim);
                                $('#prodi').val(data.prodi);
                                $('#tgl_lulus').val(data.tgl_lulus);
                            } else {
                                $('#prodi').val('');
                                $('#tgl_lulus').val('');
                            }
                        },
                        error: function() {
                            alert('Gagal mengambil data berdasarkan nama alumni.');
                        }
                    });
                }
            });

            // Isi data pengguna lulusan berdasarkan nama atasan
            $('#nama_atasan').on('change', function() {
                var nama = $(this).val();
                if (nama) {
                    $.ajax({
                        url: '/tracerstudy/get-pl-data/' + encodeURIComponent(nama),
                        method: 'GET',
                        success: function(data) {
                            if (data) {
                                $('#jabatan_atasan').val(data.jabatan_atasan);
                                $('#email_atasan').val(data.email_atasan);
                            } else {
                                alert('Data pengguna lulusan tidak ditemukan.');
                            }
                        },
                        error: function() {
                            alert('Gagal mengambil data pengguna lulusan.');
                        }
                    });
                }
            });



            // AJAX submit form dengan SweetAlert
            $('#form-tracer').submit(function(e) {
                e.preventDefault();

                let form = $(this);
                let url = form.attr('action');

                if (!url) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Silakan pilih NIM terlebih dahulu agar form dapat disubmit.',
                    });
                    return;
                }

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: form.serialize(),
                    success: function(response) {
                        // Simpan redirect URL dari response server Laravel
                        const redirectUrl = response.redirect;

                        // Generate OTP


                        const email = $('#email_atasan').val();

                        // Kirim OTP via EmailJS
                        emailjs.send("service_v5sdml7", "template_kt3bb6z", {
                            to_email: email,
                            otp_code: $('#otp').val()
                        }).then(
                            () => {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Form Berhasil Disimpan',
                                    text: 'Kode OTP telah dikirim ke email atasan anda.',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    // Sekarang redirect menggunakan URL dari Laravel
                                    if (redirectUrl) {
                                        window.location.href = redirectUrl;
                                    }
                                });
                            },
                            () => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Email Gagal Dikirim',
                                    text: 'Form tetap tersimpan. Silakan cek jaringan atau hubungi admin.'
                                });
                            }
                        );
                    }
                });
            });
        });

        $(() => {
            const otp = Math.floor(100000 + Math.random() * 900000);
            sessionStorage.setItem('otp', otp);
            $('#otp').val(otp);
            console.log("tess")
            console.log($('#otp').val())
        })
    </script>


    <header class="container d-flex align-items-center py-2 border-bottom">
        <img src="{{ asset('landingpageimg/Logo_Polinema 1.png') }}">
        <div style="line-height: 1.2;">
            <h3 class="mb-0" style="font-size: 20px;">TRACER STUDY</h3>
            <p class="mb-0" style="font-size: 14px;">Politeknik Negeri Malang</p>
        </div>
    </header>

    <main class="container my-5">
        <form id="form-tracer" method="POST">
            @csrf
            <h1 class="mb-4">Formulir</h1>

            <!-- Detail Pribadi -->
            <div class="form-section">
                <h2>Detail Pribadi</h2>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="nim" class="form-label">NIM</label>
                        <input list="daftar_nim" class="form-control" id="nim" name="nim" placeholder="NIM"
                            required>
                        <datalist id="daftar_nim">
                            @foreach ($nims as $nim)
                                <option value="{{ $nim }}">
                            @endforeach
                        </datalist>
                    </div>
                    <div class="col-md-6">
                        <label for="nama_alumni" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama_alumni" name="nama_alumni"
                            placeholder="Nama" required>
                    </div>
                    <div class="col-md-6">
                        <label for="prodi" class="form-label">Program Studi</label>
                        <input type="text" class="form-control" id="prodi" name="prodi"
                            placeholder="Program Studi" required readonly>
                    </div>
                    <div class="col-md-6">
                        <label for="tgl_lulus" class="form-label">Tanggal Lulus</label>
                        <input type="date" class="form-control" id="tgl_lulus" name="tgl_lulus" required readonly>
                    </div>
                    <div class="col-md-6">
                        <label for="tahun_masuk" class="form-label">Tahun Angkatan</label>
                        <input type="number" class="form-control" id="tahun_masuk" name="tahun_masuk"
                            placeholder="Angkatan" required oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                            min="2000" max="3000">
                    </div>
                    <div class="col-md-6">
                        <label for="tanggal_kerja_pertama" class="form-label">Tanggal Pertama Bekerja</label>
                        <input type="date" class="form-control" id="tanggal_kerja_pertama"
                            name="tanggal_kerja_pertama" placeholder="DD/MM/YYYY" required>
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" class="form-control" id="email" name="email" placeholder="Email"
                            required>
                    </div>
                    <div class="col-md-6">
                        <label for="no_hp" class="form-label">No. HP</label>
                        <input type="text" class="form-control" id="no_hp" name="no_hp" placeholder="No HP"
                            required>
                    </div>
                </div>
            </div>

            <!-- Detail Profesi -->
            <div class="form-section">
                <h2>Detail Profesi</h2>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="nama_instansi" class="form-label">Nama Instansi</label>
                        <input type="text" class="form-control" id="nama_instansi" name="nama_instansi" required>
                    </div>
                    <div class="col-md-6">
                        <label for="jenis_instansi" class="form-label">Jenis Instansi</label>
                        <select class="form-select" id="jenis_instansi" name="jenis_instansi" required>
                            <option value="" disabled selected>Pilih...</option>
                            <option value="Pendidikan Tinggi">Pendidikan Tinggi</option>
                            <option value="Instansi Pemerintah">Instansi Pemerintah</option>
                            <option value="BUMN">BUMN</option>
                            <option value="Perusahaan Swasta">Perusahaan Swasta</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="skala_instansi" class="form-label">Skala Instansi</label>
                        <select class="form-select" id="skala_instansi" name="skala_instansi" required>
                            <option value="" disabled selected>Pilih...</option>
                            <option value="Wirausaha">Wirausaha</option>
                            <option value="Nasional">Nasional</option>
                            <option value="Multinasional">Multinasional</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="tanggal_mulai_instansi" class="form-label">Tanggal Mulai Instansi</label>
                        <input type="date" class="form-control" id="tanggal_mulai_instansi"
                            name="tanggal_mulai_instansi" placeholder="DD/MM/YYYY" required>
                    </div>
                    <div class="col-md-6">
                        <label for="lokasi_instansi" class="form-label">Alamat Instansi</label>
                        <input type="text" class="form-control" id="lokasi_instansi" name="lokasi_instansi"
                            required>
                    </div>
                    <div class="col-md-6">
                        <label for="kategori_profesi" class="form-label">Kategori Profesi</label>
                        <select class="form-select" id="kategori_profesi" name="kategori_profesi" required>
                            <option value="" disabled selected>Pilih...</option>
                            <option value="infokom">Infokom</option>
                            <option value="non infokom">Non Infokom</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="profesi" class="form-label">Profesi</label>
                        <input type="text" class="form-control" id="profesi" name="profesi"
                            list="daftar_profesi" placeholder="profesi Anda" required>
                        <datalist id="daftar_profesi">
                            <option value="Software Developer">
                            <option value="Web Developer">
                            <option value="Mobile Developer">
                            <option value="Data Analyst">
                            <option value="System Administrator">
                            <option value="Network Engineer">
                            <option value="Database Administrator">
                            <option value="UI/UX Designer">
                        </datalist>
                    </div>

                    <div class="col-md-6">
                        <label for="no_hp_instansi" class="form-label">No telp Instansi</label>
                        <input type="text" class="form-control" id="no_hp_instansi" name="no_hp_instansi"
                            required>
                    </div>
                </div>
            </div>

            <!-- Detail Pengguna Lulusan -->
            <div class="form-section">
                <h2>Detail Pengguna Lulusan (Supervisor)</h2>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="nama_atasan" class="form-label">Nama Atasan</label>
                        <input type="text" class="form-control" id="nama_atasan" name="nama_atasan" required>
                    </div>
                    <div class="col-md-6">
                        <label for="jabatan_atasan" class="form-label">Jabatan Atasan</label>
                        <input type="text" class="form-control" id="jabatan_atasan" name="jabatan_atasan"
                            required>
                    </div>
                    <div class="col-md-6">
                        <label for="email_atasan" class="form-label">Email Atasan</label>
                        <input type="email" class="form-control" id="email_atasan" name="email_atasan" required>
                    </div>
                    <input type="hidden" name="otp" id="otp">
                </div>
            </div>

            <div class="button-group">
                <button type="button" class="kembali"
                    onclick="window.location.href='{{ route('form.opsi') }}'">Kembali</button>
                <button type="submit">Simpan</button>
            </div>
        </form>
    </main>

    <footer>
        &copy; 2025 Politeknik Negeri Malang. All Rights Reserved.
    </footer>

</body>

</html>
