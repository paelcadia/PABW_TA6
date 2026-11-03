<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function create(): View
    {
        return view('laporan.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_pelapor' => ['required', 'string', 'max:255'],
            'lokasi' => ['required', 'string', 'max:255'],
            'tinggi_genangan' => ['required', 'integer', 'min:1', 'max:1000'],
            'tanggal_kejadian' => ['required', 'date', 'before_or_equal:today'],
        ]);

        Laporan::create($validated);

        return to_route('lapor.form')->with('success', 'Laporan banjir berhasil dikirim.');
    }

    public function index(): View
    {
        return view('laporan.index', [
            'laporan' => Laporan::query()->latest('tanggal_kejadian')->paginate(10),
            'jumlahLaporan' => Laporan::count(),
        ]);
    }
}