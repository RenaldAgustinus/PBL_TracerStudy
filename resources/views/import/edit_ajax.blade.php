@empty($alumni)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data alumni tidak ditemukan
                </div>
                <a href="{{ url('/') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/import/' . $alumni->nim . '/update_ajax') }}" method="POST" id="form-edit-alumni">
        @csrf
        @method('PUT')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Alumni</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>NIM</label>
                        <input value="{{ $alumni->nim }}" type="text" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label>Nama Alumni</label>
                        <input value="{{ $alumni->nama_alumni }}" type="text" name="nama_alumni" id="nama_alumni"
                            class="form-control" required>
                        <small id="error-nama_alumni" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Program Studi</label>
                        <input value="{{ $alumni->prodi }}" type="text" name="prodi" id="prodi" class="form-control"
                            required>
                        <small id="error-prodi" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>No HP</label>
                        <input value="{{ $alumni->no_hp }}" type="text" name="no_hp" id="no_hp" class="form-control"
                            required>
                        <small id="error-no_hp" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input value="{{ $alumni->email }}" type="email" name="email" id="email" class="form-control"
                            required>
                        <small id="error-email" class="error-text form-text text-danger"></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </form>

    <script>
        $(document).ready(function () {
            $("#form-edit-alumni").validate({
                rules: {
                    nama_alumni: {
                        required: true,
                        minlength: 3,
                        maxlength: 100
                    },
                    prodi: {
                        required: true
                    },
                    no_hp: {
                        required: true,
                        digits: true
                    },
                    email: {
                        required: true,
                        email: true
                    },
                },
                submitHandler: function (form) {
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success: function (response) {
                            if (response.status) {
                                $('#myModal').modal('hide');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message
                                });
                                tableAlumni.ajax.reload();
                            } else {
                                $('.error-text').text('');
                                $.each(response.msgField, function (prefix, val) {
                                    $('#error-' + prefix).text(val[0]);
                                });
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Terjadi Kesalahan',
                                    text: response.message
                                });
                            }
                        }
                    });
                    return false;
                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
@endempty
