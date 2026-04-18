<!-- Lingkup Tempat Kerja Data Table -->
<div class="table-responsive">
    <table id="lingkupTempatKerjaTable" class="table table-bordered table-striped table-hover">
        <thead style="background-color: #4472C4; color: white;">
            <tr>
                <th rowspan="2" class="text-center align-middle">Tahun Lulus</th>
                <th rowspan="2" class="text-center align-middle">Jumlah Lulusan</th>
                <th rowspan="2" class="text-center align-middle">Jumlah Lulusan yang terlacak</th>
                <th colspan="2" class="text-center">Profesi Kerja</th>
                <th colspan="3" class="text-center" style="background-color: #5B9BD5;">Lingkup Tempat Kerja</th>
                <th rowspan="2" class="text-center align-middle">Aksi</th>
            </tr>
            <tr>
                <th class="text-center">Bidang Infokom</th>
                <th class="text-center">Bidang Non Infokom</th>
                <th class="text-center" style="background-color: #5B9BD5;">Multinasional/ Internasional</th>
                <th class="text-center" style="background-color: #5B9BD5;">Nasional</th>
                <th class="text-center" style="background-color: #5B9BD5;">Wirausaha</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lingkupData as $index => $row): ?>
            <tr class="<?= $index % 2 == 0 ? 'table-light' : '' ?>">
                <td class="text-center font-weight-bold"><?= htmlspecialchars($row['year']) ?></td>
                <td class="text-center"><?= number_format($row['total_graduates']) ?></td>
                <td class="text-center"><?= number_format($row['tracked_graduates']) ?></td>
                <td class="text-center"><?= number_format($row['infocom_field']) ?></td>
                <td class="text-center"><?= number_format($row['non_infocom_field']) ?></td>
                <td class="text-center" style="background-color: #E7F3FF;"><?= number_format($row['multinational']) ?></td>
                <td class="text-center" style="background-color: #E7F3FF;"><?= number_format($row['national']) ?></td>
                <td class="text-center" style="background-color: #E7F3FF;"><?= number_format($row['entrepreneurship']) ?></td>
                <td class="text-center">
                    <div class="btn-group" role="group">
                        <a href="/lingkup-tempat-kerja/edit/<?= $row['id'] ?>" class="btn btn-warning btn-sm" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-danger btn-sm" onclick="deleteData(<?= $row['id'] ?>)" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot style="background-color: #D9E2F3;">
            <tr class="font-weight-bold">
                <td class="text-center">JUMLAH</td>
                <td class="text-center"><?= number_format($totals['total_graduates']) ?></td>
                <td class="text-center"><?= number_format($totals['tracked_graduates']) ?></td>
                <td class="text-center"><?= number_format($totals['infocom_field']) ?></td>
                <td class="text-center"><?= number_format($totals['non_infocom_field']) ?></td>
                <td class="text-center"><?= number_format($totals['multinational']) ?></td>
                <td class="text-center"><?= number_format($totals['national']) ?></td>
                <td class="text-center"><?= number_format($totals['entrepreneurship']) ?></td>
                <td class="text-center">-</td>
            </tr>
        </tfoot>
    </table>
</div>

<script>
function deleteData(id) {
    if (confirm('Apakah Anda yakin ingin menghapus data lingkup tempat kerja ini?')) {
        window.location.href = '/lingkup-tempat-kerja/delete/' + id;
    }
}

// Initialize DataTable
$(document).ready(function() {
    $('#lingkupTempatKerjaTable').DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "searching": true,
        "paging": true,
        "info": true,
        "ordering": true,
        "order": [[ 0, "asc" ]],
        "pageLength": 10,
        "language": {
            "search": "Cari:",
            "lengthMenu": "Tampilkan _MENU_ data per halaman",
            "zeroRecords": "Data tidak ditemukan",
            "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
            "infoEmpty": "Tidak ada data tersedia",
            "infoFiltered": "(difilter dari _MAX_ total data)",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "Selanjutnya",
                "previous": "Sebelumnya"
            }
        }
    });
});
</script>