<!-- Modal Import Alumni -->
<div class="modal fade" id="modalImport" tabindex="-1" aria-labelledby="modalImportLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalImportLabel">Import Data Alumni</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <h6><i class="fas fa-info-circle"></i> Petunjuk Import:</h6>
                    <ul class="mb-0">
                        <li>Download template Excel terlebih dahulu</li>
                        <li>Isi data sesuai format yang disediakan</li>
                        <li>File yang diizinkan: .xlsx, .xls, .csv (maksimal 2MB)</li>
                        <li>Pastikan Program Studi sesuai dengan yang tersedia di sistem</li>
                    </ul>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <a href="{{ route('alumni.template') }}" class="btn btn-success">
                            <i class="fas fa-download"></i> Download Template Excel
                        </a>
                    </div>
                </div>

                <form action="{{ route('alumni.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                    @csrf
                    <div class="mb-3">
                        <label for="file" class="form-label">Pilih File Excel</label>
                        <input type="file" class="form-control @error('file') is-invalid @enderror" 
                               id="file" name="file" accept=".xlsx,.xls,.csv" required>
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Format yang didukung: Excel (.xlsx, .xls) dan CSV (.csv)</div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="confirmImport" required>
                            <label class="form-check-label" for="confirmImport">
                                Saya sudah memeriksa data dan siap untuk mengimport
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" form="importForm" class="btn btn-success" id="importBtn" disabled>
                    <i class="fas fa-upload"></i> Import Data
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const confirmCheckbox = document.getElementById('confirmImport');
    const importBtn = document.getElementById('importBtn');

    // Enable/disable import button based on checkbox
    confirmCheckbox.addEventListener('change', function() {
        importBtn.disabled = !this.checked;
    });

    // Reset when modal closes
    document.getElementById('modalImport').addEventListener('hidden.bs.modal', function() {
        document.getElementById('importForm').reset();
        importBtn.disabled = true;
        confirmCheckbox.checked = false;
    });
});
</script>