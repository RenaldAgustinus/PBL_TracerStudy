<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class LingkupTempatKerjaModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Get all lingkup tempat kerja data grouped by graduation year
     */
    public function getAllLingkupData()
    {
        $sql = "SELECT 
                    YEAR(a.tgl_lulus) as year,
                    COUNT(DISTINCT a.nim) as total_graduates,
                    COUNT(DISTINCT CASE WHEN pl.id_pengguna_lulusan IS NOT NULL THEN a.nim END) as tracked_graduates,
                    COUNT(CASE WHEN a.kategori_profesi = 'Infokom' THEN 1 END) as infocom_field,
                    COUNT(CASE WHEN a.kategori_profesi = 'Non Infokom' THEN 1 END) as non_infocom_field,
                    COUNT(CASE WHEN pl.skala_instansi = 'International' THEN 1 END) as multinational,
                    COUNT(CASE WHEN pl.skala_instansi = 'National' THEN 1 END) as national,
                    COUNT(CASE WHEN pl.skala_instansi = 'Local' THEN 1 END) as entrepreneurship
                FROM alumni a
                LEFT JOIN pengguna_lulusan pl ON a.id_pengguna_lulusan = pl.id_pengguna_lulusan
                WHERE a.tgl_lulus IS NOT NULL
                GROUP BY YEAR(a.tgl_lulus)
                ORDER BY YEAR(a.tgl_lulus) ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get lingkup tempat kerja data by specific year
     */
    public function getByYear($year)
    {
        $sql = "SELECT 
                    YEAR(a.tgl_lulus) as year,
                    COUNT(DISTINCT a.nim) as total_graduates,
                    COUNT(DISTINCT CASE WHEN pl.id_pengguna_lulusan IS NOT NULL THEN a.nim END) as tracked_graduates,
                    COUNT(CASE WHEN a.kategori_profesi = 'Infokom' THEN 1 END) as infocom_field,
                    COUNT(CASE WHEN a.kategori_profesi = 'Non Infokom' THEN 1 END) as non_infocom_field,
                    COUNT(CASE WHEN pl.skala_instansi = 'International' THEN 1 END) as multinational,
                    COUNT(CASE WHEN pl.skala_instansi = 'National' THEN 1 END) as national,
                    COUNT(CASE WHEN pl.skala_instansi = 'Local' THEN 1 END) as entrepreneurship
                FROM alumni a
                LEFT JOIN pengguna_lulusan pl ON a.id_pengguna_lulusan = pl.id_pengguna_lulusan
                WHERE YEAR(a.tgl_lulus) = :year
                GROUP BY YEAR(a.tgl_lulus)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':year', $year);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Calculate totals for all years
     */
    public function getTotals()
    {
        $sql = "SELECT 
                    COUNT(DISTINCT a.nim) as total_graduates,
                    COUNT(DISTINCT CASE WHEN pl.id_pengguna_lulusan IS NOT NULL THEN a.nim END) as tracked_graduates,
                    COUNT(CASE WHEN a.kategori_profesi = 'Infokom' THEN 1 END) as infocom_field,
                    COUNT(CASE WHEN a.kategori_profesi = 'Non Infokom' THEN 1 END) as non_infocom_field,
                    COUNT(CASE WHEN pl.skala_instansi = 'International' THEN 1 END) as multinational,
                    COUNT(CASE WHEN pl.skala_instansi = 'National' THEN 1 END) as national,
                    COUNT(CASE WHEN pl.skala_instansi = 'Local' THEN 1 END) as entrepreneurship
                FROM alumni a
                LEFT JOIN pengguna_lulusan pl ON a.id_pengguna_lulusan = pl.id_pengguna_lulusan
                WHERE a.tgl_lulus IS NOT NULL";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get statistics for dashboard
     */
    public function getStatistics()
    {
        $totals = $this->getTotals();
        
        return [
            'total_graduates' => (int)$totals['total_graduates'],
            'tracked_graduates' => (int)$totals['tracked_graduates'],
            'tracking_percentage' => $totals['total_graduates'] > 0 ? 
                round(($totals['tracked_graduates'] / $totals['total_graduates']) * 100, 2) : 0,
            'infocom_percentage' => $totals['tracked_graduates'] > 0 ? 
                round(($totals['infocom_field'] / $totals['tracked_graduates']) * 100, 2) : 0,
            'multinational_percentage' => $totals['tracked_graduates'] > 0 ? 
                round(($totals['multinational'] / $totals['tracked_graduates']) * 100, 2) : 0,
            'national_percentage' => $totals['tracked_graduates'] > 0 ? 
                round(($totals['national'] / $totals['tracked_graduates']) * 100, 2) : 0,
            'entrepreneurship_percentage' => $totals['tracked_graduates'] > 0 ? 
                round(($totals['entrepreneurship'] / $totals['tracked_graduates']) * 100, 2) : 0
        ];
    }

    /**
     * Get detailed alumni data with employment info
     */
    public function getDetailedAlumniData()
    {
        $sql = "SELECT 
                    a.nim,
                    a.nama_alumni,
                    a.prodi,
                    a.tgl_lulus,
                    a.kategori_profesi,
                    a.profesi,
                    a.masa_tunggu,
                    pl.nama_instansi,
                    pl.jenis_instansi,
                    pl.skala_instansi,
                    pl.lokasi_instansi,
                    pl.nama_atasan,
                    pl.jabatan_atasan
                FROM alumni a
                LEFT JOIN pengguna_lulusan pl ON a.id_pengguna_lulusan = pl.id_pengguna_lulusan
                WHERE a.tgl_lulus IS NOT NULL
                ORDER BY a.tgl_lulus DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get data for charts
     */
    public function getChartData()
    {
        $data = $this->getAllLingkupData();
        
        $chartData = [
            'years' => [],
            'total_graduates' => [],
            'profesi_kerja' => [
                'infokom' => 0,
                'non_infokom' => 0
            ],
            'lingkup_tempat_kerja' => [
                'multinational' => 0,
                'national' => 0,
                'entrepreneurship' => 0
            ],
            'jenis_instansi' => []
        ];

        foreach ($data as $row) {
            $chartData['years'][] = $row['year'];
            $chartData['total_graduates'][] = (int)$row['total_graduates'];
            $chartData['profesi_kerja']['infokom'] += (int)$row['infocom_field'];
            $chartData['profesi_kerja']['non_infokom'] += (int)$row['non_infocom_field'];
            $chartData['lingkup_tempat_kerja']['multinational'] += (int)$row['multinational'];
            $chartData['lingkup_tempat_kerja']['national'] += (int)$row['national'];
            $chartData['lingkup_tempat_kerja']['entrepreneurship'] += (int)$row['entrepreneurship'];
        }

        // Get jenis instansi distribution
        $jenisInstansiData = $this->getJenisInstansiDistribution();
        $chartData['jenis_instansi'] = $jenisInstansiData;

        return $chartData;
    }

    /**
     * Get distribution of jenis instansi
     */
    public function getJenisInstansiDistribution()
    {
        $sql = "SELECT 
                    pl.jenis_instansi,
                    COUNT(*) as count
                FROM alumni a
                JOIN pengguna_lulusan pl ON a.id_pengguna_lulusan = pl.id_pengguna_lulusan
                WHERE pl.jenis_instansi IS NOT NULL AND pl.jenis_instansi != ''
                GROUP BY pl.jenis_instansi
                ORDER BY count DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get years range from alumni data
     */
    public function getYearsRange()
    {
        $sql = "SELECT 
                    MIN(YEAR(tgl_lulus)) as min_year, 
                    MAX(YEAR(tgl_lulus)) as max_year 
                FROM alumni 
                WHERE tgl_lulus IS NOT NULL";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get waiting period statistics (masa tunggu)
     */
    public function getWaitingPeriodStats()
    {
        $sql = "SELECT 
                    AVG(masa_tunggu) as avg_waiting_period,
                    MIN(masa_tunggu) as min_waiting_period,
                    MAX(masa_tunggu) as max_waiting_period,
                    COUNT(CASE WHEN masa_tunggu <= 3 THEN 1 END) as within_3_months,
                    COUNT(CASE WHEN masa_tunggu BETWEEN 4 AND 6 THEN 1 END) as within_4_6_months,
                    COUNT(CASE WHEN masa_tunggu > 6 THEN 1 END) as more_than_6_months
                FROM alumni 
                WHERE masa_tunggu IS NOT NULL";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get alumni by program studi (prodi)
     */
    public function getAlumniByProdi()
    {
        $sql = "SELECT 
                    a.prodi,
                    COUNT(*) as total_alumni,
                    COUNT(CASE WHEN pl.id_pengguna_lulusan IS NOT NULL THEN 1 END) as tracked_alumni,
                    COUNT(CASE WHEN a.kategori_profesi = 'Infokom' THEN 1 END) as infokom_count
                FROM alumni a
                LEFT JOIN pengguna_lulusan pl ON a.id_pengguna_lulusan = pl.id_pengguna_lulusan
                WHERE a.tgl_lulus IS NOT NULL
                GROUP BY a.prodi
                ORDER BY total_alumni DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Search alumni by various criteria
     */
    public function searchAlumni($criteria = [])
    {
        $where = ['a.tgl_lulus IS NOT NULL'];
        $params = [];

        if (!empty($criteria['year'])) {
            $where[] = 'YEAR(a.tgl_lulus) = :year';
            $params['year'] = $criteria['year'];
        }

        if (!empty($criteria['prodi'])) {
            $where[] = 'a.prodi LIKE :prodi';
            $params['prodi'] = '%' . $criteria['prodi'] . '%';
        }

        if (!empty($criteria['kategori_profesi'])) {
            $where[] = 'a.kategori_profesi = :kategori_profesi';
            $params['kategori_profesi'] = $criteria['kategori_profesi'];
        }

        if (!empty($criteria['skala_instansi'])) {
            $where[] = 'pl.skala_instansi = :skala_instansi';
            $params['skala_instansi'] = $criteria['skala_instansi'];
        }

        $whereClause = implode(' AND ', $where);

        $sql = "SELECT 
                    a.nim,
                    a.nama_alumni,
                    a.prodi,
                    a.tgl_lulus,
                    a.kategori_profesi,
                    a.profesi,
                    pl.nama_instansi,
                    pl.jenis_instansi,
                    pl.skala_instansi,
                    pl.lokasi_instansi
                FROM alumni a
                LEFT JOIN pengguna_lulusan pl ON a.id_pengguna_lulusan = pl.id_pengguna_lulusan
                WHERE {$whereClause}
                ORDER BY a.tgl_lulus DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}