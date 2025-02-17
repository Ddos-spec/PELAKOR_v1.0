<?php
session_start();
include '../connect-db.php';
include '../functions/export.php';

header('Content-Type: application/json');

$type = $_POST['type'] ?? '';
$filters = $_POST['filters'] ?? [];
$template = $_POST['template'] ?? 'default';

try {
    $export = new ExportHandler($connect);
    $export->setFilters($filters);
    
    if($type === 'preview') {
        echo json_encode([
            'success' => true,
            'data' => $export->getPreviewData()
        ]);
        exit;
    }
    
    if($type === 'excel') {
        $spreadsheet = $export->exportExcel();
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="export.xlsx"');
        $writer->save('php://output');
        exit;
    }
    
    if($type === 'pdf') {
        $pdf = $export->exportPDF($template);
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment;filename="export.pdf"');
        echo $pdf->Output('', 'S');
        exit;
    }
    
    throw new Exception('Invalid export type');
    
} catch(Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
