<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImportController extends Controller
{
    public function index()
    {
        return view('admin.import');
    }

    /**
     * Import data dosen dari file CSV.
     * Format kolom CSV: nip,nama,email,no_hp
     */
    public function importDosen(Request $request)
    {
        $request->validate([
            'file_dosen' => 'required|file|mimes:csv,txt',
        ]);

        [$imported, $skipped, $errors] = $this->processCsv($request->file('file_dosen'), function ($row) {
            [$nip, $nama, $email, $noHp] = array_pad($row, 4, null);

            if (!$nip || !$nama) {
                return false;
            }

            Dosen::updateOrCreate(
                ['nip' => trim($nip)],
                [
                    'nama' => trim($nama),
                    'email' => $email ? trim($email) : null,
                    'no_hp' => $noHp ? trim($noHp) : null,
                ]
            );

            return true;
        });

        return back()->with('success', "Import dosen selesai. Berhasil: {$imported}, Dilewati: {$skipped}.")
            ->with('import_errors', $errors);
    }

    /**
     * Import data mahasiswa dari file CSV.
     * Format kolom CSV: nim,nama,angkatan,program_studi,nip_dosen_wali,email,no_hp
     */
    public function importMahasiswa(Request $request)
    {
        $request->validate([
            'file_mahasiswa' => 'required|file|mimes:csv,txt',
        ]);

        [$imported, $skipped, $errors] = $this->processCsv($request->file('file_mahasiswa'), function ($row) {
            [$nim, $nama, $angkatan, $prodi, $nipDosen, $email, $noHp] = array_pad($row, 7, null);

            if (!$nim || !$nama) {
                return false;
            }

            $dosenId = null;
            if ($nipDosen) {
                $dosen = Dosen::where('nip', trim($nipDosen))->first();
                $dosenId = $dosen?->id;
            }

            Mahasiswa::updateOrCreate(
                ['nim' => trim($nim)],
                [
                    'nama' => trim($nama),
                    'angkatan' => $angkatan ? trim($angkatan) : null,
                    'program_studi' => $prodi ? trim($prodi) : null,
                    'dosen_id' => $dosenId,
                    'email' => $email ? trim($email) : null,
                    'no_hp' => $noHp ? trim($noHp) : null,
                ]
            );

            return true;
        });

        return back()->with('success', "Import mahasiswa selesai. Berhasil: {$imported}, Dilewati: {$skipped}.")
            ->with('import_errors', $errors);
    }

    private function processCsv($file, callable $rowHandler): array
    {
        $imported = 0;
        $skipped = 0;
        $errors = [];

        $handle = fopen($file->getRealPath(), 'r');
        $rowNumber = 0;

        DB::beginTransaction();
        try {
            while (($row = fgetcsv($handle)) !== false) {
                $rowNumber++;

                // Lewati baris header (baris pertama)
                if ($rowNumber === 1) {
                    continue;
                }

                if (count(array_filter($row)) === 0) {
                    continue;
                }

                $ok = $rowHandler($row);
                if ($ok) {
                    $imported++;
                } else {
                    $skipped++;
                    $errors[] = "Baris {$rowNumber} dilewati (data tidak lengkap).";
                }
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $errors[] = 'Import dibatalkan karena error: ' . $e->getMessage();
        } finally {
            fclose($handle);
        }

        return [$imported, $skipped, $errors];
    }
}
