<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use TCPDF;

class ExportHandler {
    private $connect;
    private $filters;
    
    public function __construct($connect) {
        $this->connect = $connect;
        $this->filters = [];
    }
    
    public function setFilters($filters) {
        $this->filters = $filters;
    }
    
    private function getFilteredData() {
        $query = "SELECT t.*, c.tipe_layanan, c.berat, a.nama_laundry, p.nama as nama_pelanggan 
                 FROM v_transaksi_detail t 
                 WHERE 1=1";
                 
        if(!empty($this->filters['date_start'])) {
            $query .= " AND t.tgl_mulai >= '{$this->filters['date_start']}'";
        }
        if(!empty($this->filters['date_end'])) {
            $query .= " AND t.tgl_mulai <= '{$this->filters['date_end']}'";
        }
        if(!empty($this->filters['tipe'])) {
            $query .= " AND t.tipe_layanan = '{$this->filters['tipe']}'";
        }
        
        return mysqli_query($this->connect, $query);
    }
    
    public function exportExcel() {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set headers
        $headers = ['No', 'Tanggal', 'Pelanggan', 'Agen', 'Tipe', 'Total'];
        $col = 'A';
        foreach($headers as $header) {
            $sheet->setCellValue($col.'1', $header);
            $col++;
        }
        
        // Fill data
        $row = 2;
        $data = $this->getFilteredData();
        while($item = mysqli_fetch_assoc($data)) {
            $sheet->setCellValue('A'.$row, $row-1);
            $sheet->setCellValue('B'.$row, $item['tgl_mulai']);
            $sheet->setCellValue('C'.$row, $item['nama_pelanggan']);
            $sheet->setCellValue('D'.$row, $item['nama_laundry']);
            $sheet->setCellValue('E'.$row, $item['tipe_layanan']);
            $sheet->setCellValue('F'.$row, $item['total_bayar']);
            $row++;
        }
        
        return $spreadsheet;
    }
    
    public function exportPDF($template = 'default') {
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        // Load template
        require_once "templates/pdf/{$template}.php";
        
        $data = $this->getFilteredData();
        $content = '';
        
        while($item = mysqli_fetch_assoc($data)) {
            $content .= $this->generatePDFRow($item);
        }
        
        $pdf->writeHTML($content);
        return $pdf;
    }
    
    public function getPreviewData() {
        $data = $this->getFilteredData();
        $preview = [];
        
        while($row = mysqli_fetch_assoc($data)) {
            $preview[] = [
                'tanggal' => $row['tgl_mulai'],
                'pelanggan' => $row['nama_pelanggan'],
                'agen' => $row['nama_laundry'],
                'total' => $row['total_bayar']
            ];
        }
        
        return $preview;
    }
}
