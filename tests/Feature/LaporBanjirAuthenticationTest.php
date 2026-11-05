<?php

namespace Tests\Feature;

use App\Models\Laporan;
use App\Models\User;
use Database\Seeders\PetugasSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LaporBanjirAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_residents_can_open_the_form_and_submit_a_report_without_logging_in(): void
    {
        Storage::fake('public');
        $this->get(route('lapor.form'))->assertOk();

        $this->post(route('lapor.store'), [
            'nama_pelapor' => 'Siti Rahma',
            'no_hp' => '08123456789',
            'lokasi' => 'Baleendah, Kabupaten Bandung',
            'kecamatan' => 'Baleendah',
            'tinggi_air' => 45,
            'keterangan' => 'Air masuk ke halaman rumah.',
            'foto' => $this->fakePng('banjir.png'),
        ])->assertRedirect(route('lapor.form'));

        $this->assertDatabaseHas('laporan', [
            'nama_pelapor' => 'Siti Rahma',
            'lokasi' => 'Baleendah, Kabupaten Bandung',
            'tinggi_genangan' => 45,
            'tinggi_air' => 45,
            'status' => 'Menunggu',
        ]);
        Storage::disk('public')->assertExists(Laporan::firstOrFail()->foto);
        $this->assertGuest();
    }

    public function test_guest_is_redirected_to_login_before_viewing_reports(): void
    {
        $this->get(route('laporan.index'))
            ->assertRedirect(route('login'));

        $this->get(route('laporan.create'))->assertRedirect(route('login'));
        $this->get(route('laporan.show', 1))->assertRedirect(route('login'));
    }

    public function test_report_photo_is_required_and_limited_to_image_files_up_to_two_megabytes(): void
    {
        $data = [
            'nama_pelapor' => 'Siti Rahma',
            'no_hp' => '08123456789',
            'lokasi' => 'Baleendah',
            'kecamatan' => 'Baleendah',
            'tinggi_air' => 45,
            'keterangan' => 'Air menggenangi jalan.',
        ];

        $this->from(route('lapor.form'))
            ->post(route('lapor.store'), $data)
            ->assertRedirect(route('lapor.form'))
            ->assertSessionHasErrors('foto');

        $data['foto'] = UploadedFile::fake()->create('bukti.pdf', 100, 'application/pdf');
        $this->from(route('lapor.form'))
            ->post(route('lapor.store'), $data)
            ->assertSessionHasErrors('foto');

        $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+jXn8AAAAASUVORK5CYII=');
        $data['foto'] = UploadedFile::fake()->createWithContent('besar.png', str_pad($png, 2049 * 1024, 'x'));
        $this->from(route('lapor.form'))
            ->post(route('lapor.store'), $data)
            ->assertSessionHasErrors('foto');
    }

    public function test_petugas_can_manage_reports_and_replaced_or_deleted_photos_are_removed(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $this->actingAs($user);

        $attributes = [
            'nama_pelapor' => 'Siti Rahma',
            'no_hp' => '08123456789',
            'lokasi' => 'Jalan Raya Baleendah',
            'kecamatan' => 'Baleendah',
            'tinggi_air' => 45,
            'keterangan' => 'Genangan di jalan.',
            'status' => 'Menunggu',
            'foto' => $this->fakePng('awal.png'),
        ];

        $this->post(route('laporan.store'), $attributes)
            ->assertRedirect(route('laporan.index'))
            ->assertSessionHas('success', 'Laporan berhasil ditambahkan.');
        $laporan = Laporan::firstOrFail();
        $oldPhoto = $laporan->foto;
        Storage::disk('public')->assertExists($oldPhoto);

        $this->get(route('laporan.show', $laporan))->assertOk()->assertSee('Siti Rahma');
        $this->get(route('laporan.edit', $laporan))->assertOk();

        $attributes['status'] = 'Diproses';
        $attributes['foto'] = $this->fakePng('baru.png');
        $this->put(route('laporan.update', $laporan), $attributes)
            ->assertRedirect(route('laporan.show', $laporan))
            ->assertSessionHas('success', 'Laporan berhasil diperbarui.');
        $laporan->refresh();
        $this->assertSame('Diproses', $laporan->status);
        $this->assertNotSame($oldPhoto, $laporan->foto);
        Storage::disk('public')->assertMissing($oldPhoto);
        Storage::disk('public')->assertExists($laporan->foto);

        $currentPhoto = $laporan->foto;
        $this->delete(route('laporan.destroy', $laporan))
            ->assertRedirect(route('laporan.index'))
            ->assertSessionHas('success', 'Laporan berhasil dihapus.');
        $this->assertDatabaseMissing('laporan', ['id' => $laporan->id]);
        Storage::disk('public')->assertMissing($currentPhoto);
    }

    public function test_registration_hashes_password_and_logs_petugas_in(): void
    {
        $this->post(route('register.store'), [
            'name' => 'Petugas Uji',
            'email' => 'petugas@example.test',
            'password' => 'petugas123',
            'password_confirmation' => 'petugas123',
        ])->assertRedirect(route('laporan.index'));

        $user = User::where('email', 'petugas@example.test')->firstOrFail();
        $this->assertNotSame('petugas123', $user->password);
        $this->assertTrue(Hash::check('petugas123', $user->password));
        $this->assertAuthenticatedAs($user);

        $this->get(route('profile'))
            ->assertOk()
            ->assertSee('Petugas Uji')
            ->assertSee('petugas@example.test');
    }

    public function test_login_shows_clear_error_and_logout_clears_authentication(): void
    {
        $user = User::factory()->create([
            'email' => 'petugas@example.test',
            'password' => Hash::make('petugas123'),
        ]);

        $this->from(route('login'))->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ])->assertRedirect(route('login'))
            ->assertSessionHasErrors(['email' => 'Email atau password yang Anda masukkan salah.']);

        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'petugas123',
        ])->assertRedirect(route('laporan.index'));

        $this->assertAuthenticatedAs($user);
        $this->post(route('logout'))->assertRedirect(route('lapor.form'));
        $this->assertGuest();
    }

    public function test_petugas_seeder_creates_five_hashed_accounts_and_fixed_admin(): void
    {
        $this->seed(PetugasSeeder::class);
        $this->seed(PetugasSeeder::class);

        $this->assertSame(6, User::count());
        $admin = User::where('email', 'admin@bpbd-bandung.go.id')->firstOrFail();
        $this->assertTrue(Hash::check('admin123', $admin->password));
        $this->assertSame(5, User::where('email', '!=', $admin->email)->count());
        User::where('email', '!=', $admin->email)->get()->each(
            fn (User $petugas) => $this->assertTrue(Hash::check('password', $petugas->password))
        );
    }

    private function fakePng(string $name): UploadedFile
    {
        return UploadedFile::fake()->createWithContent(
            $name,
            base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+jXn8AAAAASUVORK5CYII=')
        );
    }
}
