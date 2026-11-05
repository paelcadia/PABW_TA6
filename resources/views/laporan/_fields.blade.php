<div class="field-grid">
    <div class="field">
        <label for="nama_pelapor">Nama pelapor</label>
        <input id="nama_pelapor" name="nama_pelapor" value="{{ old('nama_pelapor', $laporan->nama_pelapor ?? '') }}" autocomplete="name" required maxlength="255" placeholder="Nama lengkap">
        @error('nama_pelapor')<span class="field-error">{{ $message }}</span>@enderror
    </div>
    <div class="field">
        <label for="no_hp">Nomor HP</label>
        <input id="no_hp" name="no_hp" type="tel" value="{{ old('no_hp', $laporan->no_hp ?? '') }}" autocomplete="tel" required maxlength="20" placeholder="Contoh: 08123456789">
        @error('no_hp')<span class="field-error">{{ $message }}</span>@enderror
    </div>
    <div class="field field-wide">
        <label for="lokasi">Lokasi kejadian</label>
        <input id="lokasi" name="lokasi" value="{{ old('lokasi', $laporan->lokasi ?? '') }}" required maxlength="255" placeholder="Desa/kelurahan atau alamat yang mudah dikenali">
        @error('lokasi')<span class="field-error">{{ $message }}</span>@enderror
    </div>
    <div class="field">
        <label for="kecamatan">Kecamatan</label>
        <input id="kecamatan" name="kecamatan" value="{{ old('kecamatan', $laporan->kecamatan ?? '') }}" required maxlength="100" placeholder="Nama kecamatan">
        @error('kecamatan')<span class="field-error">{{ $message }}</span>@enderror
    </div>
    <div class="field">
        <label for="tinggi_air">Tinggi air <span>(cm)</span></label>
        <input id="tinggi_air" name="tinggi_air" type="number" min="1" max="1000" value="{{ old('tinggi_air', $laporan->tinggi_air ?? $laporan->tinggi_genangan ?? '') }}" required placeholder="Contoh: 45">
        @error('tinggi_air')<span class="field-error">{{ $message }}</span>@enderror
    </div>
    <div class="field field-wide">
        <label for="keterangan">Keterangan kondisi</label>
        <textarea id="keterangan" name="keterangan" rows="4" required maxlength="5000" placeholder="Jelaskan kondisi banjir dan dampak yang terlihat">{{ old('keterangan', $laporan->keterangan ?? '') }}</textarea>
        @error('keterangan')<span class="field-error">{{ $message }}</span>@enderror
    </div>
    <div class="field field-wide">
        <label for="foto">Foto bukti {{ isset($laporan) ? '(opsional jika tidak diganti)' : '' }}</label>
        <input id="foto" name="foto" type="file" accept=".jpg,.jpeg,.png,image/jpeg,image/png" {{ isset($laporan) ? '' : 'required' }}>
        @error('foto')<span class="field-error">{{ $message }}</span>@enderror
        @if (isset($laporan) && $laporan->foto)
            <img class="form-photo-preview" src="{{ asset('storage/'.$laporan->foto) }}" alt="Foto bukti laporan saat ini">
        @endif
    </div>
    @if ($management ?? false)
        <div class="field">
            <label for="status">Status penanganan</label>
            <select id="status" name="status" required>
                @foreach (['Menunggu', 'Diproses', 'Selesai'] as $status)
                    <option value="{{ $status }}" @selected(old('status', $laporan->status ?? 'Menunggu') === $status)>{{ $status }}</option>
                @endforeach
            </select>
            @error('status')<span class="field-error">{{ $message }}</span>@enderror
        </div>
    @endif
</div>
