<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Response;

class PendidikanTemplateController extends Controller
{
    /**
     * Download template Excel untuk import data Pendidikan
     */
    public function download()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set title
        $sheet->setTitle('Template Import Pendidikan');

        // Header row
        $headers = [
            'A1' => 'nama_sekolah',
            'B1' => 'jenjang_instansi',
            'C1' => 'nama_kota',
            'D1' => 'nama_kecamatan',
            'E1' => 'alamat',
            'F1' => 'nama_kepsek',
            'G1' => 'nama_operator',
            'H1' => 'no_hp_kepsek',
            'I1' => 'no_hp_operator',
            'J1' => 'jumlah_siswa',
            'K1' => 'jumlah_pip_aspirasi',
        ];

        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }

        // Label row (for user guidance)
        $labels = [
            'A2' => 'Nama Sekolah (WAJIB)',
            'B2' => 'Jenjang: SD/SMP/SMA/SMK/SLB/PERGURUAN TINGGI',
            'C2' => 'Nama Kota/Kabupaten (WAJIB)',
            'D2' => 'Nama Kecamatan (WAJIB)',
            'E2' => 'Alamat lengkap sekolah',
            'F2' => 'Nama Kepala Sekolah',
            'G2' => 'Nama Operator Sekolah',
            'H2' => 'No HP Kepala Sekolah',
            'I2' => 'No HP Operator',
            'J2' => 'Jumlah Siswa (angka)',
            'K2' => 'Jumlah PIP Aspirasi (angka)',
        ];

        foreach ($labels as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }

        // Example data
        $examples = [
            'A3' => 'SD NEGERI 1 SUKAMANAH',
            'B3' => 'SD',
            'C3' => 'KOTA BANDUNG',
            'D3' => 'BANDUNG WETAN',
            'E3' => 'Jl. Sukamnah No. 1',
            'F3' => 'Drs. Ahmad Yani',
            'G3' => 'Siti Aminah',
            'H3' => '081234567890',
            'I3' => '089876543210',
            'J3' => '500',
            'K3' => '100',
        ];

        foreach ($examples as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }

        // Style the header row
        $sheet->getStyle('A1:K1')->getFont()->setBold(true);
        $sheet->getStyle('A1:K1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFCCE5FF');

        // Auto-size columns
        for ($col = 'A'; $col <= 'K'; $col++) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Create the Excel file
        $writer = new Xlsx($spreadsheet);
        
        // Prepare response
        $filename = 'template_import_pendidikan_' . date('Ymd') . '.xlsx';
        
        ob_start();
        $writer->save('php://output');
        $content = ob_get_clean();

        return Response::make($content, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}

