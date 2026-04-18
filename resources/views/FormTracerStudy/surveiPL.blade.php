<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Pengguna Lulusan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            font-family: Arial, sans-serif;
            background-color: #fff;
            font-size: 14px;
        }

        main {
            flex: 1;
        }

        header {
            background-color: #fff;
            border-bottom: 1px solid #fff;
            padding: 20px;
            display: flex;
            align-items: center;
        }

        header img {
            height: 60px;
            margin-right: 15px;
        }

        header h3 {
            font-size: 22px;
            margin: 0;
            font-weight: 700;
        }

        header p {
            margin: 0;
            font-size: 14px;
        }

        .form-container {
            max-width: 800px;
            margin: 30px auto;
            padding: 0 20px;
        }

        h1 {
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 25px;
        }

        h2 {
            font-size: 18px;
            font-weight: 600;
            margin: 30px 0 15px;
        }

        .form-section {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        label {
            font-weight: 600;
            font-size: 14px;
            margin-top: 10px;
        }

        input,
        textarea {
            width: 100%;
            padding: 8px 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #999;
            border-radius: 5px;
            font-size: 14px;
        }

        textarea {
            min-height: 100px;
        }

        .form-row {
            display: flex;
            gap: 20px;
        }

        .form-row>div {
            flex: 1;
        }

        .rating-group {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-top: 10px;
        }

        .rating-label {
            font-size: 13px;
            font-weight: 600;
            white-space: nowrap;
        }

        .rating-options {
            display: flex;
            gap: 10px;
            flex: 1;
            justify-content: center;
        }

        .rating-options input[type="radio"] {
            accent-color: #007649;
            transform: scale(1.2);
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

        footer {
            text-align: center;
            background-color: #f4cf58;
            padding: 12px 0;
            font-size: 13px;
            font-weight: 600;
            color: #000;
            margin-top: 50px;
        }
    </style>
</head>

<body>

    <script>
        $(document).ready(function() {
            // Auto isi data alumni
            $('#nama_alumni').on('input', function() {
                var nama = $(this).val();
                if (nama.length >= 3) {
                    $.ajax({
                        url: '/tracerstudy/get-alumni-data/' + encodeURIComponent(nama),
                        method: 'GET',
                        success: function(data) {
                            if (data) {
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

            // Auto isi data pengguna lulusan (atasan)
            $('#nama_atasan').on('input', function() {
                var nama = $(this).val();
                if (nama.length >= 3) {
                    $.ajax({
                        url: '/tracerstudy/get-pl-data/' + encodeURIComponent(nama),
                        method: 'GET',
                        success: function(data) {
                            if (data) {
                                $('#jabatan_atasan').val(data.jabatan_atasan);
                                $('#email_atasan').val(data.email_atasan);
                            } else {
                                $('#jabatan_atasan').val('');
                                $('#email_atasan').val('');
                            }
                        },
                        error: function() {
                            alert('Gagal mengambil data berdasarkan nama atasan.');
                        }
                    });
                }
            });

            // Submit form dengan AJAX
            $('form').on('submit', function(e) {
                e.preventDefault(); // Mencegah reload

                let form = $(this);
                let formData = form.serialize();

                $.ajax({
                    type: 'POST',
                    url: form.attr('action'),
                    data: formData,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            confirmButtonColor: '#007649',
                        }).then(() => {
                            window.location.href = response.redirect;
                        });
                    },
                    error: function(xhr) {
                        let msg = 'Terjadi kesalahan.';
                        if (xhr.status === 409 && xhr.responseJSON?.error) {
                            msg = xhr.responseJSON.error;
                        } else if (xhr.responseJSON?.error) {
                            msg = xhr.responseJSON.error;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: msg,
                            confirmButtonColor: '#d33',
                        });
                    }
                });
            });
        });
    </script>

    <header class="container d-flex align-items-center py-2 border-bottom">
        <img src="{{ asset('landingpageimg/Logo_Polinema 1.png') }}">
        <div style="line-height: 1.2;">
            <h3 class="mb-0" style="font-size: 20px;">TRACER STUDY</h3>
            <p class="mb-0" style="font-size: 14px;">Politeknik Negeri Malang</p>
        </div>
    </header>

    <main>
        <form class="form-container" action="{{ route('survey.store') }}" method="POST">
            @csrf
            <h1>Survey</h1>

            <!-- Pengguna Lulusan -->
            <div class="form-section">
                <h2>Pengguna Lulusan</h2>
                <div class="form-group">
                    <label for="nama_atasan" class="form-label">Nama</label>
                    <input list="daftar_nama" class="form-control" id="nama_atasan" name="nama_atasan" required>
                    <datalist id="daftar_nama">
                        @foreach ($namaAtasan as $pl)
                            <option value="{{ $pl->nama_atasan }}">
                        @endforeach
                    </datalist>
                </div>

                <div class="form-group">
                    <label for="instansi">Instansi</label>
                    <input type="text" id="instansi" name="instansi" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="jabatan_atasan">Jabatan</label>
                    <input type="text" id="jabatan_atasan" name="jabatan_atasan" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="email_atasan">Email</label>
                    <input type="email" id="email_atasan" name="email_atasan" class="form-control" required>
                </div>
            </div>

            <!-- Alumni -->
            <div class="form-section">
                <h2>Alumni</h2>
                <div class="form-group">
                    <label for="nama_alumni">Nama</label>
                    <input type="text" id="nama_alumni" name="nama_alumni" class="form-control" required>
                </div>

                <div class="form-row">
                    <div>
                        <label for="prodi">Program Studi</label>
                        <input type="text" id="prodi" name="prodi" class="form-control" required>
                    </div>
                    <div>
                        <label for="tgl_lulus">Tanggal Lulus</label>
                        <input type="date" id="tgl_lulus" name="tgl_lulus" class="form-control" required>
                    </div>
                </div>
            </div>

            <!-- Penilaian -->
            <!-- Penilaian (Radio Button) -->
            <div class="form-section">
                <h2>Penilaian</h2>

                @foreach ($pertanyaan->where('metodejawaban', 1) as $question)
                    <div class="mb-3">
                        <label class="d-block">{{ $question->isi_pertanyaan }}</label>
                        <div class="rating-group">
                            <span class="rating-label">Sangat Kurang</span>
                            <div class="rating-options">
                                @for ($i = 1; $i <= 5; $i++)
                                    <input type="radio" name="jawaban[{{ $question->id_pertanyaan }}]"
                                        value="{{ $i }}">
                                @endfor
                            </div>
                            <span class="rating-label">Sangat Baik</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Komentar atau Masukan (Textarea) -->
            <div class="form-section">
                <h2>Masukan</h2>

                @foreach ($pertanyaan->where('metodejawaban', 2) as $question)
                    <div class="mb-3">
                        <label for="pertanyaan_{{ $question->id_pertanyaan }}">{{ $question->isi_pertanyaan }}</label>
                        <textarea name="jawaban[{{ $question->id_pertanyaan }}]" id="pertanyaan_{{ $question->id_pertanyaan }}"
                            class="form-control" rows="3"></textarea>
                    </div>
                @endforeach
            </div>

            <!-- Tombol -->
            <div class="button-group">
                <button type="button" class="kembali"
                    onclick="window.location.href='{{ route('form.opsi') }}'">Kembali</button>
                <button type="submit">Simpan</button>
            </div>
        </form>
    </main>

    <footer>
        © 2025 Politeknik Negeri Malang. All Rights Reserved.
    </footer>
</body>

</html>
