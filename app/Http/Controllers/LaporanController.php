<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

class LaporanController extends Controller
{
    public function createPublic(): View
    {
        return view('laporan.create');
    }

    public function storePublic(Request $request): RedirectResponse
    {
        $validated = $this->validateReport($request);
        $this->createReport($request, $validated, 'Menunggu');

        return to_route('lapor.form')->with('success', 'Laporan banjir berhasil dikirim.');
    }

    public function index(): View
    {
        return view('laporan.index', [
            'laporan' => Laporan::query()->latest()->paginate(10),
            'jumlahLaporan' => Laporan::count(),
        ]);
    }

    public function create(): View
    {
        return view('laporan.create', ['management' => true]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateReport($request);
        $this->createReport($request, $validated, $validated['status'] ?? 'Menunggu');

        return to_route('laporan.index')->with('success', 'Laporan berhasil ditambahkan.');
    }

    public function show(Laporan $laporan): View
    {
        return view('laporan.show', compact('laporan'));
    }

    public function edit(Laporan $laporan): View
    {
        return view('laporan.edit', compact('laporan'));
    }

    public function update(Request $request, Laporan $laporan): RedirectResponse
    {
        $validated = $this->validateReport($request, updating: true);
        $attributes = $this->reportAttributes($validated);
        $oldPhoto = $laporan->foto;
        $newPhoto = null;

        if ($request->hasFile('foto')) {
            $newPhoto = $this->storePhoto($request);
            $attributes['foto'] = $newPhoto;
        }

        try {
            DB::transaction(fn () => $laporan->update($attributes));
        } catch (Throwable $exception) {
            if ($newPhoto !== null) {
                $this->deletePhoto($newPhoto);
            }

            throw $exception;
        }

        if ($newPhoto !== null && $oldPhoto) {
            $this->deletePhoto($oldPhoto);
        }

        return to_route('laporan.show', $laporan)->with('success', 'Laporan berhasil diperbarui.');
    }

    public function destroy(Laporan $laporan): RedirectResponse
    {
        $photo = $laporan->foto;
        $laporan->delete();

        if ($photo) {
            $this->deletePhoto($photo);
        }

        return to_route('laporan.index')->with('success', 'Laporan berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validateReport(Request $request, bool $updating = false): array
    {
        return $request->validate([
            'nama_pelapor' => ['required', 'string', 'max:255'],
            'no_hp' => ['required', 'string', 'max:20'],
            'lokasi' => ['required', 'string', 'max:255'],
            'kecamatan' => ['required', 'string', 'max:100'],
            'tinggi_air' => ['required', 'integer', 'min:1', 'max:1000'],
            'keterangan' => ['required', 'string', 'max:5000'],
            'foto' => [$updating ? 'sometimes' : 'required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'status' => ['sometimes', 'required', Rule::in(['Menunggu', 'Diproses', 'Selesai'])],
        ]);
    }

    /**
     * @param  array<string, mixed>  $validated
     */
    private function createReport(Request $request, array $validated, string $status): Laporan
    {
        $photo = $this->storePhoto($request);
        $attributes = $this->reportAttributes($validated);
        $attributes['foto'] = $photo;
        $attributes['status'] = $status;

        try {
            return Laporan::create($attributes);
        } catch (Throwable $exception) {
            $this->deletePhoto($photo);

            throw $exception;
        }
    }

    /**
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function reportAttributes(array $validated): array
    {
        unset($validated['foto']);
        $validated['tinggi_genangan'] = $validated['tinggi_air'];
        $validated['tanggal_kejadian'] = now()->toDateString();

        return $validated;
    }

    private function storePhoto(Request $request): string
    {
        $path = $request->file('foto')->store('laporan', 'public');

        if (! is_string($path)) {
            throw new \RuntimeException('Foto laporan gagal disimpan.');
        }

        return $path;
    }

    private function deletePhoto(string $path): void
    {
        $disk = Storage::disk('public');

        if ($disk->exists($path) && ! $disk->delete($path)) {
            throw new \RuntimeException('Foto laporan gagal dihapus dari storage.');
        }
    }
}
