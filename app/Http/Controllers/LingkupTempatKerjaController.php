<?php

namespace App\Http\Controllers;

use App\Models\LingkupTempatKerjaModel;
use App\Core\Controller;

class LingkupTempatKerjaController extends Controller 
{
    private $lingkupTempatKerjaModel;

    public function __construct()
    {
        $this->lingkupTempatKerjaModel = new LingkupTempatKerjaModel();
    }

    /**
     * Display lingkup tempat kerja dashboard
     */
    public function index()
    {
        $data = [
            'title' => 'Lingkup Tempat Kerja Lulusan',
            'lingkupData' => $this->lingkupTempatKerjaModel->getAllLingkupData(),
            'totals' => $this->lingkupTempatKerjaModel->getTotals(),
            'statistics' => $this->lingkupTempatKerjaModel->getStatistics(),
            'waitingPeriodStats' => $this->lingkupTempatKerjaModel->getWaitingPeriodStats(),
            'alumniByProdi' => $this->lingkupTempatKerjaModel->getAlumniByProdi(),
            'yearsRange' => $this->lingkupTempatKerjaModel->getYearsRange()
        ];

        $this->view('lingkup_tempat_kerja/index', $data);
    }

    /**
     * Display detailed alumni data
     */
    public function detailAlumni()
    {
        $data = [
            'title' => 'Detail Data Alumni',
            'alumniData' => $this->lingkupTempatKerjaModel->getDetailedAlumniData()
        ];

        $this->view('lingkup_tempat_kerja/detail_alumni', $data);
    }

    /**
     * Search alumni with filters
     */
    public function search()
    {
        $criteria = [];
        
        if (isset($_GET['year']) && !empty($_GET['year'])) {
            $criteria['year'] = $_GET['year'];
        }
        
        if (isset($_GET['prodi']) && !empty($_GET['prodi'])) {
            $criteria['prodi'] = $_GET['prodi'];
        }
        
        if (isset($_GET['kategori_profesi']) && !empty($_GET['kategori_profesi'])) {
            $criteria['kategori_profesi'] = $_GET['kategori_profesi'];
        }
        
        if (isset($_GET['skala_instansi']) && !empty($_GET['skala_instansi'])) {
            $criteria['skala_instansi'] = $_GET['skala_instansi'];
        }

        $data = [
            'title' => 'Pencarian Alumni',
            'alumniData' => $this->lingkupTempatKerjaModel->searchAlumni($criteria),
            'criteria' => $criteria
        ];

        $this->view('lingkup_tempat_kerja/search_results', $data);
    }

    /**
     * Get chart data as JSON
     */
    public function getChartData()
    {
        header('Content-Type: application/json');
        echo json_encode($this->lingkupTempatKerjaModel->getChartData());
    }

    /**
     * Get statistics as JSON
     */
    public function getStatistics()
    {
        header('Content-Type: application/json');
        echo json_encode($this->lingkupTempatKerjaModel->getStatistics());
    }

    /**
     * Export lingkup tempat kerja data to Excel
     */
    public function exportExcel()
    {
        $data = $this->lingkupTempatKerjaModel->getAllLingkupData();
        
        // Set headers for Excel download
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="lingkup_tempat_kerja_' . date('Y-m-d') . '.xls"');
        header('Cache-Control: max-age=0');

        echo '<table border="1">';
        echo '<tr>';
        echo '<th>Tahun Lulus</th>';
        echo '<th>Jumlah Lulusan</th>';
        echo '<th>Lulusan Terlacak</th>';
        echo '<th>Bidang Infokom</th>';
        echo '<th>Bidang Non Infokom</th>';
        echo '<th>Multinasional</th>';
        echo '<th>Nasional</th>';
        echo '<th>Wirausaha/Local</th>';
        echo '</tr>';

        foreach ($data as $row) {
            echo '<tr>';
            echo '<td>' . $row['year'] . '</td>';
            echo '<td>' . $row['total_graduates'] . '</td>';
            echo '<td>' . $row['tracked_graduates'] . '</td>';
            echo '<td>' . $row['infocom_field'] . '</td>';
            echo '<td>' . $row['non_infocom_field'] . '</td>';
            echo '<td>' . $row['multinational'] . '</td>';
            echo '<td>' . $row['national'] . '</td>';
            echo '<td>' . $row['entrepreneurship'] . '</td>';
            echo '</tr>';
        }

        echo '</table>';
        exit;
    }

    /**
     * Export detailed alumni data to Excel
     */
    public function exportDetailedExcel()
    {
        $data = $this->lingkupTempatKerjaModel->getDetailedAlumniData();
        
        // Set headers for Excel download
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="detail_alumni_' . date('Y-m-d') . '.xls"');
        header('Cache-Control: max-age=0');

        echo '<table border="1">';
        echo '<tr>';
        echo '<th>NIM</th>';
        echo '<th>Nama Alumni</th>';
        echo '<th>Program Studi</th>';
        echo '<th>Tanggal Lulus</th>';
        echo '<th>Kategori Profesi</th>';
        echo '<th>Profesi</th>';
        echo '<th>Masa Tunggu (bulan)</th>';
        echo '<th>Nama Instansi</th>';
        echo '<th>Jenis Instansi</th>';
        echo '<th>Skala Instansi</th>';
        echo '<th>Lokasi Instansi</th>';
        echo '</tr>';

        foreach ($data as $row) {
            echo '<tr>';
            echo '<td>' . ($row['nim'] ?? '-') . '</td>';
            echo '<td>' . ($row['nama_alumni'] ?? '-') . '</td>';
            echo '<td>' . ($row['prodi'] ?? '-') . '</td>';
            echo '<td>' . ($row['tgl_lulus'] ?? '-') . '</td>';
            echo '<td>' . ($row['kategori_profesi'] ?? '-') . '</td>';
            echo '<td>' . ($row['profesi'] ?? '-') . '</td>';
            echo '<td>' . ($row['masa_tunggu'] ?? '-') . '</td>';
            echo '<td>' . ($row['nama_instansi'] ?? '-') . '</td>';
            echo '<td>' . ($row['jenis_instansi'] ?? '-') . '</td>';
            echo '<td>' . ($row['skala_instansi'] ?? '-') . '</td>';
            echo '<td>' . ($row['lokasi_instansi'] ?? '-') . '</td>';
            echo '</tr>';
        }

        echo '</table>';
        exit;
    }

    /**
     * Show analytics dashboard
     */
    public function analytics()
    {
        $data = [
            'title' => 'Analytics Lingkup Tempat Kerja',
            'statistics' => $this->lingkupTempatKerjaModel->getStatistics(),
            'chartData' => $this->lingkupTempatKerjaModel->getChartData(),
            'waitingPeriodStats' => $this->lingkupTempatKerjaModel->getWaitingPeriodStats(),
            'jenisInstansiData' => $this->lingkupTempatKerjaModel->getJenisInstansiDistribution()
        ];

        $this->view('lingkup_tempat_kerja/analytics', $data);
    }
}