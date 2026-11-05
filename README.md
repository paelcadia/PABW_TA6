# LaporBanjir | Praktikum Modul 7.5

Aplikasi laporan banjir BPBD Kabupaten Bandung. Warga dapat mengirim laporan beserta foto bukti tanpa login. Petugas yang sudah login dapat melihat, menambah, mengubah status/foto, dan menghapus laporan. Autentikasi dibuat manual menggunakan controller Laravel, `Auth`, dan `Hash` agar alurnya dapat dipelajari langsung.

## Persiapan

1. Buat database MySQL `db_laporbanjir` melalui phpMyAdmin.
2. Untuk instalasi baru, atur `DB_CONNECTION=mysql`, `DB_HOST`, `DB_PORT`, `DB_DATABASE=db_laporbanjir`, `DB_USERNAME`, dan `DB_PASSWORD` pada `.env`. Jika meneruskan database TA yang sudah ada, pertahankan nama database aktif agar data lama tetap digunakan.
3. Jalankan `php artisan migrate` dan `php artisan storage:link`.
4. Jalankan `php artisan db:seed` untuk membuat akun petugas dan contoh laporan.
5. Jalankan `npm run build`, lalu `php artisan serve`.

File foto disimpan pada `storage/app/public/laporan` dan dapat diakses melalui `public/storage`. Foto wajib berformat JPG/JPEG/PNG dengan ukuran maksimal 2 MB. Foto lama dihapus ketika diganti atau laporan dihapus.

## Bukti penyimpanan untuk praktikum

Kirim satu laporan dengan foto melalui formulir warga, lalu ambil tangkapan layar tabel `laporan` di phpMyAdmin yang memperlihatkan kolom `foto` berisi path file. Ambil juga tangkapan layar folder `storage/app/public/laporan` yang memperlihatkan file yang sama. Folder tersebut baru berisi file setelah ada laporan dengan foto yang berhasil dikirim.

## Fitur pengelolaan

Warga mengirim laporan melalui `/lapor`. Seluruh route resource `/laporan` dilindungi middleware `auth` dan menyediakan daftar berpaginasi, detail, tambah, ubah status, ganti foto, serta hapus laporan. Setiap input menampilkan pesan validasi; operasi tambah, ubah, dan hapus menampilkan pesan berhasil.

## Akun demo

- Email: `admin@bpbd-bandung.go.id`
- Kata sandi: `admin123`
- Akun petugas Faker lainnya memakai kata sandi `password`.

## Pengujian

Jalankan `php artisan test`. Pengujian mencakup pengiriman laporan, validasi foto, proteksi halaman petugas, CRUD, dan penghapusan foto dari storage.

## Alasan memilih autentikasi manual

Autentikasi manual dipilih agar proses validasi, hashing password, pengelolaan session, dan proteksi middleware dapat dipahami langsung, sesuai tujuan praktikum. Implementasi memakai `Auth::attempt()` untuk login serta menginvalidasi session saat logout.
