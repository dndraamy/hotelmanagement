<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use App\Models\PengajuanCuti;
use Illuminate\Http\Request;

class ApprovalCutiController extends Controller
{
    // Daftar semua pengajuan cuti, bisa difilter per status
    public function index(Request $request)
    {
        $status = $request->query('status', 'Pending');

        $pengajuan = PengajuanCuti::with('pegawai')
            ->where('status_approval', $status)
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        $counts = [
            'Pending'  => PengajuanCuti::where('status_approval', 'Pending')->count(),
            'Approved' => PengajuanCuti::where('status_approval', 'Approved')->count(),
            'Rejected' => PengajuanCuti::where('status_approval', 'Rejected')->count(),
        ];

        return view('hrd.cuti.index', compact('pengajuan', 'status', 'counts'));
    }

    // Detail satu pengajuan
    public function show(PengajuanCuti $pengajuanCuti)
    {
        $pengajuanCuti->load(['pegawai', 'approver']);

        return view('hrd.cuti.show', compact('pengajuanCuti'));
    }

    // Setujui pengajuan
    public function approve(PengajuanCuti $pengajuanCuti)
    {
        if ($pengajuanCuti->status_approval !== 'Pending') {
            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $pengajuanCuti->update([
            'status_approval' => 'Approved',
            'id_approver'     => auth()->user()->id_pegawai,
        ]);

        return redirect()
            ->route('hrd.cuti.index', ['status' => 'Pending'])
            ->with('success', "Pengajuan cuti {$pengajuanCuti->pegawai->nama_lengkap} disetujui.");
    }

    // Tolak pengajuan
    public function reject(PengajuanCuti $pengajuanCuti)
    {
        if ($pengajuanCuti->status_approval !== 'Pending') {
            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $pengajuanCuti->update([
            'status_approval' => 'Rejected',
            'id_approver'     => auth()->user()->id_pegawai,
        ]);

        return redirect()
            ->route('hrd.cuti.index', ['status' => 'Pending'])
            ->with('success', "Pengajuan cuti {$pengajuanCuti->pegawai->nama_lengkap} ditolak.");
    }
}