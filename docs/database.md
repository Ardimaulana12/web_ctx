# Integrasi database company profile

## Skema dan batas perubahan

Lima tabel mengikuti atribut, primary key INT, foreign key INT, panjang VARCHAR,
TEXT, enum `aktif`/`nonaktif`, boolean dan timestamps pada ERD. Semua PK/FK memakai
INT unsigned yang konsisten. Tidak ada kolom atau tabel konten tambahan.

Tabel bawaan Laravel tidak dihapus atau diubah. Tabel pesan yang sebelumnya
bernama `inquiries` kini dinamai `hubungi_kami` melalui migration rename terpisah.
`hubungi_kami` tetap menyimpan pesan form kontak, dengan perilaku SMTP, validasi,
CSRF dan throttle yang sama. Tidak ada admin, autentikasi atau endpoint CRUD baru.

| Tabel | Data awal | Pemetaan |
| --- | ---: | --- |
| `bahasa` | 2 | English/en/en_US (default), Indonesia/id/id_ID |
| `halaman_website` | 6 | `nama_halaman` = nama route/view; `slug` = path asli termasuk `/` |
| `konten_website` | 14 | 6 bagian `page` × 2 bahasa, 1 bagian `shared` × 2 bahasa |
| `menu_navigasi` | 6 | Home, Services, Case Study, Blog, About, Contact us; urutan asli |
| `kontak` | 2 | Alamat kantor, email dan WhatsApp asli per bahasa |

`peta_lokasi`, `jam_operasional`, dan `ikon` bernilai NULL karena website asli
tidak menyediakan nilainya. Metadata bagian `shared` juga NULL; metadata halaman
tetap sama dengan website asli.

## Penggunaan kolom TEXT tanpa perubahan ERD

`konten_website.isi` menyimpan JSON dan dicast ke array oleh Eloquent. `judul`
menjadi identitas bagian (`page` atau `shared`) dalam kombinasi halaman/bahasa.
JSON berisi data saja; aplikasi tidak mengeksekusi Blade/PHP/HTML dari database.

Pada bagian `page`, `isi.sections` memuat:

| Halaman | Isi bagian |
| --- | --- |
| home | `list_1` topik; `list_2` angka dampak; `list_3` nilai; `list_4` quality assurance; `list_5` teknologi |
| about | `list_1` pendekatan, keahlian, komitmen |
| services | Bagian bersama `services`, teks melalui kamus terjemahan |
| cases | `list_1` penelitian; `list_2` dashboard, termasuk gambar dan subject permintaan |
| blog | `list_1` artikel asli, judul, tanggal, gambar, slug eksternal dan ringkasan |
| contact | `list_1` label field; aturan validasi dan struktur form tetap kode aplikasi |

Bagian `shared` ditautkan ke halaman home untuk setiap bahasa dan memuat
`translations`, `services`, `leaders`, `process`, identitas merek, media sosial,
pesan WhatsApp, label footer, dan versi singkat alamat kantor. Website asli
menampilkan alamat singkat di halaman kontak dan alamat lengkap di footer;
keduanya dipertahankan. Alamat lengkap berasal dari `kontak.alamat`, versi singkat
dari `shared.office_address`. Email dan nomor WhatsApp berasal dari `kontak`.

Karena ERD tidak memiliki `id_bahasa` di menu, `nama_menu` menjadi kunci label
dalam kamus `shared.translations`. Relasi menu tetap hanya ke halaman. Footer
memakai variasi label asli (misalnya About us), tetapi tujuan tautannya berasal
dari menu/halaman yang aktif di database.

Kamus menyertakan teks Inggris identitas, seluruh terjemahan EN/ID asli, label
aksesibilitas dan notifikasi form. `DatabaseLoader` memasok fungsi Laravel `__()`
dari database; tidak membaca file JSON bahasa sebagai fallback runtime.
Terjemahan validasi framework tetap memakai loader file Laravel.

Sumber seed mandiri: `database/seeders/data/website.json`, diekstrak dari Blade,
`config/ctx.php`, kamus bahasa dan pesan controller sebelum integrasi. File sumber
lama dipertahankan sebagai referensi; konfigurasi `ctx` dan JSON bahasa lama tidak
lagi menjadi sumber konten halaman saat request. Template, CSS, JavaScript, SVG,
asset frontend, URL dan struktur HTML asli dipertahankan.

## Integritas dan pembacaan

- Unique: kode bahasa, slug, nama halaman, kombinasi halaman/bahasa/judul konten,
  halaman/nama menu, dan bahasa/email kontak.
- FK menggunakan RESTRICT pada delete dan CASCADE pada update. Menghapus bahasa
  atau halaman yang masih direferensikan ditolak database.
- Indeks status/urutan serta bahasa/status mendukung filter konten aktif.
- Eloquent memvalidasi status, field wajib, panjang, email, URL peta, path halaman,
  nomor telepon, urutan nonnegatif, FK dan kapasitas TEXT 65.535 byte. Default bahasa
  harus aktif dan tunggal melalui validasi model. Update SQL/bulk Eloquent melewati
  event model; gunakan instance model untuk mempertahankan validasi aplikasi.
- Bahasa aktif dan default dibaca setiap request. Session bahasa tidak valid atau
  nonaktif kembali ke default. POST bahasa menolak pilihan nonaktif/tidak dikenal.
- Halaman/konten nonaktif menghasilkan 404, menu nonaktif disembunyikan. Konten
  wajib yang hilang tidak diam-diam diganti hardcoded. Tanpa default aktif: 503.
- `WebsiteController` hanya memakai nama route yang sudah terdaftar untuk view.
  Bagian bersama dicache sebatas request; perubahan database terlihat pada request
  berikutnya. Metadata SEO dibaca dari `meta_title` dan `meta_description`.

## Migrasi dan seed

```sh
php artisan migrate --seed
php artisan db:seed
php artisan test
```

Seeder berjalan dalam transaksi. `firstOrCreate` dan pengecekan relasi mengisi
data yang belum ada, tanpa truncate/delete dan tanpa menimpa isi, status, metadata,
label menu atau email yang telah diedit. Seeder tidak membuat user dummy.
Identitas tetap seperti kode bahasa, nama halaman dan judul bagian sebaiknya tidak
diganti; identitas yang diganti dianggap sebagai data lain ketika seeding.

Jangan memakai `migrate:fresh` pada database website yang berisi data. Pengujian
rollback menggunakan database sementara terpisah, bukan database aplikasi.

## Verifikasi pada 20 November 2026

Pengujian menggunakan koneksi `mysql` lokal dengan server MariaDB 10.4.32
(kompatibel driver MySQL Laravel). Belum diuji pada server Oracle MySQL terpisah.

- 23 pengujian, 171 assertion lulus.
- Build produksi `npm.cmd run build` berhasil; pemeriksaan `git diff --check` bersih.
- Migrasi up → seed → down → up → seed berhasil di database sementara.
- Lima tabel baru menghasilkan 2 bahasa, 6 halaman, 14 konten, 6 menu, 2 kontak.
- Seed dua kali mempertahankan snapshot seluruh data, termasuk edit yang disengaja
  dan tabel lama. Semua edit pengujian dibatalkan melalui transaksi.
- Perubahan database terbukti memengaruhi teks, daftar, SEO, menu, email, alamat
  dan WhatsApp tanpa mengganti konten bahasa lain.
- FK, unique konten, batas TEXT, email, default bahasa, status aktif/nonaktif,
  redirect bahasa aman dan alur sukses/gagal SMTP diuji.
- Hash HTML enam halaman dalam EN/ID cocok dengan baseline sebelum integrasi.
  Normalisasi hanya mengabaikan CSRF, tahun copyright, injeksi asset dev/build,
  komentar dan whitespace format. Baseline tersimpan dalam
  `tests/Fixtures/website-baseline.json`; CSS/JS tidak diubah.

Uji fitur membutuhkan database yang telah dimigrasi dan diseed. Uji migrasi
memerlukan hak CREATE/DROP DATABASE untuk nama sementara `ctx_erd_test_<acak>`;
database tersebut selalu dibersihkan pada akhir pengujian.

## Rename tabel pesan menjadi hubungi_kami

Migration `2026_11_20_000002_rename_inquiries_to_hubungi_kami.php` mengganti:

| Lama | Baru |
| --- | --- |
| inquiries | hubungi_kami |
| id | id_hubungi |
| name | nama |
| company | perusahaan |
| subject | subjek |
| message | pesan |

`email`, `created_at`, dan `updated_at` tidak berubah. Migration menggunakan satu
`ALTER TABLE` dengan `CHANGE COLUMN` dan `RENAME TO`, kompatibel dengan MariaDB
10.4 dan MySQL. Definisi tiap kolom dibaca dari `SHOW CREATE TABLE` dan diterapkan
kembali persis, termasuk tipe, nullable, default, collation, komentar dan
auto increment. Primary key, indeks dan opsi tabel tetap dipertahankan. Migration
menolak kondisi tabel yang tidak sesuai sebelum menjalankan perubahan.

`down()` melakukan rename sebaliknya tanpa menghapus tabel atau pesan baru.
Migration pembuat tabel awal tetap dipertahankan sebagai riwayat. Jangan rollback
melewati migration rename jika ingin mempertahankan tabel pesan.

Model `HubungiKami` menetapkan `$table = 'hubungi_kami'`, primary key `id_hubungi`,
dan fillable Indonesia. Controller memetakan field form `name/company/subject/message`
ke `nama/perusahaan/subjek/pesan` setelah validasi. Field HTML, error validasi,
routing, isi email dan class pengirim `ContactInquiry` tidak berubah; class email
tersebut menerima payload formulir, bukan mengakses tabel lama. Log kegagalan
email kini mencatat `id_hubungi`.

Verifikasi langsung pada database `ctx_website`: 8 pesan lama dibandingkan satu
per satu sebelum rename, setelah rename, setelah rollback, dan setelah penerapan
ulang. Semua nilai dan definisi tabel yang dinormalisasi identik, termasuk nilai
auto increment saat pengujian migrasi. Tabel lama tidak tersisa sebagai duplikat.
Snapshot sebelum migrasi disimpan privat di `storage/app/private/` dan tidak
ditambahkan ke Git atau ditampilkan dalam log.

Skrip verifikasi sekali jalan: `php tests/Support/verify-contact-rename.php`, hanya
dapat dijalankan sebelum migration rename. Skrip ini tidak membuat/menghapus tabel
dan berhenti jika verifikasi gagal. Untuk pemasangan normal cukup `php artisan migrate`.

Pengujian regresi rename dijalankan dengan memilih WebsiteTest, WebsiteDatabaseTest,
ContentBaselineTest, ExampleTest, dan Unit/ExampleTest. WebsiteMigrationTest lama
tidak dijalankan pada perubahan rename ini karena pengujiannya membuat dan
menghapus database sementara. Verifikasi rollback rename memakai skrip di atas.

Hasil regresi rename: 23 pengujian / 168 assertion lulus, termasuk penyimpanan
semua field Indonesia, primary key Eloquent, timestamps, perusahaan NULL,
validasi, notifikasi email yang dimock, serta baseline HTML seluruh halaman EN/ID.
Pemeriksaan akhir setelah tes memastikan 8 pesan asli masih identik dan tabel
`inquiries` tidak ada. Pesan pengujian dibatalkan melalui transaksi.
