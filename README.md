# CTX Corp — Laravel + Tailwind CSS

Rekonstruksi situs CTX dengan Laravel 12, Blade, Tailwind CSS 4, dan driver MySQL.

## Redesign UI

Tampilan mengikuti referensi CTX Research lokal: palet navy/teal, hero berlatar analisis data, font Inter lokal, kartu layanan, alur kerja, serta header halaman navy. Sistem visual berada di resources/css/redesign.css. Konten Cortex, bahasa EN/ID, dan kontak Get in touch tetap dipertahankan. Foto hero disimpan lokal di public/images/research-hero.jpg; sumbernya adalah aset Unsplash pada template referensi.

## Setup

Jalankan composer install dan npm install. Salin .env.example menjadi .env lalu jalankan php artisan key:generate. Buat database ctx_website pada MySQL dan sesuaikan DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD di .env.

Jalankan php artisan migrate, npm run build, kemudian php artisan serve. Buka http://127.0.0.1:8000. Saat mengembangkan styling, jalankan npm run dev di terminal lain.

## Database lokal

XAMPP lama mengalami error InnoDB. Data lama tidak diubah. Instance MariaDB bawaan XAMPP yang kompatibel dengan driver MySQL disiapkan terpisah pada 127.0.0.1:3307, dengan data di storage/mysql-local. .env lokal menggunakan port 3307, sedangkan .env.example memakai port standar MySQL 3306. Native asynchronous I/O dimatikan untuk mengatasi proses indeks yang macet pada mesin ini.

Jalankan ./start-local.ps1 dari PowerShell untuk menghidupkan database lokal dan server Laravel kembali. Instance awal yang gagal di storage/mysql tidak digunakan.

## Fitur

- Homepage, layanan, case study, blog, dan kontak.
- Halaman /about memuat profil perusahaan dan tim pimpinan.
- Dropdown ID/EN di kanan tombol kontak. Bahasa Inggris menjadi default; pilihan tersimpan dalam session. Terjemahan terdapat di lang/id.json, lang/en.json, dan lang/id/validation.php.
- Footer biru tua #061C3D. Instagram @ctx_corp; WhatsApp +62 812-1133-7721 dengan pesan awal Hello Cortex Data.
- Animasi masuk dan reveal saat scroll menghormati prefers-reduced-motion dan tidak memblokir konten ketika JavaScript tidak aktif.
- Menu hamburger pada lebar di bawah 1280px, termasuk ponsel dan tablet landscape. Mendukung keyboard dan Escape.
- Form kontak menggunakan validasi server, CSRF, pembatasan 5 permintaan/menit, dan penyimpanan pada tabel inquiries. Tidak mengirim email otomatis.
- Aset visual disimpan lokal di public/images dari situs referensi https://www.ctx.co.id/.
- Tautan blog membuka artikel asli. Permintaan case study membuka form dengan subject terisi.
- Leadership memakai inisial karena referensi memakai foto placeholder yang sama.

## Pengujian

Jalankan php artisan test dan npm run build. Feature test memeriksa halaman publik, penyimpanan kontak dan penolakan input invalid. Transaksi database pengujian di-rollback; migrasi harus sudah dijalankan.

### Contact email delivery

Requests are saved to MySQL, then emailed synchronously to CONTACT_MAIL_TO
(default ardimaulana265@gmail.com). Failed SMTP shows a saved-but-not-emailed
warning. Previously saved messages are not automatically retried.
Configure your sender provider's credentials privately in .env:

```dotenv
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_SCHEME=smtp
MAIL_USERNAME=your-sender-account
MAIL_PASSWORD=your-smtp-password
MAIL_FROM_ADDRESS=your-verified-sender-address
MAIL_FROM_NAME="CTX Corp"
CONTACT_MAIL_TO=ardimaulana265@gmail.com
```

For port 465 use MAIL_SCHEME=smtps. Visitor email is Reply-To only.
Never commit credentials. Run php artisan config:clear after configuration.
Submit a test and check the inbox/spam folder; SMTP acceptance does not guarantee
inbox placement. The default log mailer does not deliver external email.
