<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\PetugasSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LaporBanjirAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_residents_can_open_the_form_and_submit_a_report_without_logging_in(): void
    {
        $this->get(route('lapor.form'))->assertOk();

        $this->post(route('lapor.store'), [
            'nama_pelapor' => 'Siti Rahma',
            'lokasi' => 'Baleendah, Kabupaten Bandung',
            'tinggi_genangan' => 45,
            'tanggal_kejadian' => now()->toDateString(),
        ])->assertRedirect(route('lapor.form'));

        $this->assertDatabaseHas('laporan', [
            'nama_pelapor' => 'Siti Rahma',
            'lokasi' => 'Baleendah, Kabupaten Bandung',
            'tinggi_genangan' => 45,
        ]);
        $this->assertGuest();
    }

    public function test_guest_is_redirected_to_login_before_viewing_reports(): void
    {
        $this->get(route('laporan.index'))
            ->assertRedirect(route('login'));
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
}