<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pegawai;
use App\Models\Kehadiran;
use App\Models\KomponenGaji;
use App\Models\PengajuanCuti;
use App\Models\Penggajian;
use App\Models\DetailPenggajian;

class PenggajianController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        $riwayatGaji = Penggajian::with('pegawai.jabatan')
            ->where('periode_bulan', $bulan)
            ->where('periode_tahun', $tahun)
            ->get();

        return view('hrd.penggajian.index', compact(
            'riwayatGaji',
            'bulan',
            'tahun'
        ));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'bulan' => 'required|numeric',
            'tahun' => 'required|numeric',
        ]);

        $bulan = (int) $request->bulan;
        $tahun = (int) $request->tahun;


        // Mencegah generate gaji yang sudah pernah dibuat untuk periode yang sama
        if (
            Penggajian::where('periode_bulan', $bulan)
                ->where('periode_tahun', $tahun)
                ->exists()
        ) {
            return back()->with(
                'error',
                'Penggajian bulan ini sudah pernah dibuat.'
            );
        }

        $komponenLembur = KomponenGaji::where('nama_komponen', 'Lembur')->first();
        $komponenAlpha = KomponenGaji::where('nama_komponen', 'Alpha')->first();
        $komponenTelat = KomponenGaji::where('nama_komponen', 'Terlambat')->first();
        $komponenCuti = KomponenGaji::where('nama_komponen', 'Cuti')->first();

        $pegawais = Pegawai::with('jabatan')->get();

        DB::beginTransaction();

        try {

            foreach ($pegawais as $pegawai) {

                $gajiPokok = $pegawai->jabatan->gaji_pokok ?? 0;


                //LEMBUR
                $totalMenitLembur = Kehadiran::where('id_pegawai', $pegawai->id_pegawai)
                    ->whereMonth('tanggal', $bulan)
                    ->whereYear('tanggal', $tahun)
                    ->sum('menit_lembur');

                $jamLembur = floor($totalMenitLembur / 60);

                $uangLembur = $komponenLembur
                    ? $jamLembur * $komponenLembur->nominal
                    : 0;

              
                //ALPHA
                $jumlahAlpha = Kehadiran::where('id_pegawai', $pegawai->id_pegawai)
                    ->whereMonth('tanggal', $bulan)
                    ->whereYear('tanggal', $tahun)
                    ->where('status_kehadiran', 'Alpha')
                    ->count();

                $potonganAlpha = $komponenAlpha
                    ? $jumlahAlpha * $komponenAlpha->nominal
                    : 0;


                //TERLAMBAT
                $jumlahTelat = Kehadiran::where('id_pegawai', $pegawai->id_pegawai)
                    ->whereMonth('tanggal', $bulan)
                    ->whereYear('tanggal', $tahun)
                    ->where('status_kehadiran', 'Terlambat')
                    ->count();

                $potonganTelat = $komponenTelat
                    ? $jumlahTelat * $komponenTelat->nominal
                    : 0;

                //CUTI
                $jumlahCuti = PengajuanCuti::where('id_pegawai', $pegawai->id_pegawai)
                    ->where('status_approval', 'Approved')
                    ->whereMonth('tanggal_mulai', $bulan)
                    ->whereYear('tanggal_mulai', $tahun)
                    ->count();

                $potonganCuti = $komponenCuti
                    ? $jumlahCuti * $komponenCuti->nominal
                    : 0;

                
                //TOTAL POTONGAN DAN GAJI BERSIH
                $totalPotongan =
                    $potonganAlpha +
                    $potonganTelat +
                    $potonganCuti;

                $gajiBersih =
                    $gajiPokok +
                    $uangLembur -
                    $totalPotongan;
                
  
                $penggajian = Penggajian::create([
                    'id_pegawai' => $pegawai->id_pegawai,
                    'periode_bulan' => $bulan,
                    'periode_tahun' => $tahun,
                    'total_gaji_pokok' => $gajiPokok,
                    'total_uang_lembur' => $uangLembur,
                    'total_potongan' => $totalPotongan,
                    'gaji_bersih' => $gajiBersih
                ]);

                
                //SIMPAN DETAIL PENGGAJIAN
                if ($uangLembur > 0) {
                    DetailPenggajian::create([
                        'id_penggajian' => $penggajian->id_penggajian,
                        'id_komponen' => $komponenLembur->id_komponen,
                        'nominal_terapan' => $uangLembur
                    ]);
                }

                if ($potonganAlpha > 0) {
                    DetailPenggajian::create([
                        'id_penggajian' => $penggajian->id_penggajian,
                        'id_komponen' => $komponenAlpha->id_komponen,
                        'nominal_terapan' => $potonganAlpha
                    ]);
                }

                if ($potonganTelat > 0) {
                    DetailPenggajian::create([
                        'id_penggajian' => $penggajian->id_penggajian,
                        'id_komponen' => $komponenTelat->id_komponen,
                        'nominal_terapan' => $potonganTelat
                    ]);
                }

                if ($potonganCuti > 0) {
                    DetailPenggajian::create([
                        'id_penggajian' => $penggajian->id_penggajian,
                        'id_komponen' => $komponenCuti->id_komponen,
                        'nominal_terapan' => $potonganCuti
                    ]);
                }
            }

            DB::commit();

            return back()->with(
                'success',
                "Penggajian periode $bulan/$tahun berhasil dibuat."
            );

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with(
                'error',
                'Terjadi kesalahan : ' . $e->getMessage()
            );
        }
    }

    public function cetakSlip($id)
    {
        $penggajian = Penggajian::with([
            'pegawai.jabatan',
            'detail.komponen'
        ])->findOrFail($id);

        if (is_null($penggajian->tanggal_cetak_slip)) {
            $penggajian->update([
                'tanggal_cetak_slip' => now()
            ]);
        }

        return view(
            'hrd.penggajian.slip',
            compact('penggajian')
        );
    }
}