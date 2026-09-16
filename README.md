# JARA — Advanced Todo List

Aplikasi web untuk mengelola tugas pribadi maupun tim. Pengguna dapat membuat,
mengelompokkan, dan mengatur tugas ke dalam beberapa daftar (*list/project*),
menetapkan prioritas dan tenggat waktu, menandai tugas selesai, berkolaborasi dengan
anggota lain, memantau progres, dan dikelola oleh admin.

## Fitur

- **F1 — Autentikasi & Akun:** register, login/logout, role `user`/`admin`, profil,
  middleware admin, seeder akun contoh.
- **F2 — Manajemen Daftar (List/Project):** CRUD daftar; pemilik otomatis; hapus
  daftar beserta tugas & anggota secara **atomik** (`DB::transaction`); otorisasi
  owner via `ListPolicy` (403 untuk yang lain).
- **F3 — Manajemen Tugas:** buat/edit/hapus/toggle selesai; prioritas
  (low/medium/high/urgent); tenggat waktu; validasi via Form Request; otorisasi
  via `TaskPolicy`.
- **F4 — Kolaborasi & Keanggotaan:** pemilik menambah/menghapus anggota (pivot
  `list_user`), daftar yang diikuti (`/lists/joined`), otorisasi `manageMembers`.
- **F5 — Admin & Progres:** middleware admin (`/admin`); kalkulasi progres
  `ListProgress`; pengelolaan akun & tampilan progres menyusul.

Dokumen lengkap: **[PRD.md](PRD.md)** · [Design.md](Design.md) · [Commit.md](Commit.md) ·
[Checklist PRD.md](Checklist%20PRD.md) · [Changelog.md](Changelog.md) · `releases/`

## Stack

- Laravel 13 (PHP 8.5)
- Database MySQL (development & test, via `pdo_mysql`)
- Blade + Tailwind CSS, Vite
- PHPUnit (unit & feature test), Playwright (E2E), Laravel Pint

## Memulai (Local Development)

```sh
# Prasyarat: PHP 8.5+, Composer 2.x, Node 20+, MySQL aktif
composer install
npm install
cp .env.example .env          # Windows: copy .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run build                 # atau npm run dev / composer run dev
php artisan serve
```

Buka di browser: <http://localhost:8000>

Akun default (seeder): lihat `database/seeders/DatabaseSeeder.php`.

## Testing & Kode Bersih

```sh
php artisan test                      # unit + feature test (via PHPUnit)
npm run test:e2e                      # E2E test (Playwright + Chromium)
npx playwright install chromium       # sekali saja: unduh browser E2E
vendor/bin/pint --dirty               # format kode PHP
npm run build                         # build aset frontend
```

## Pembagian Tugas Tim (5 Developer)

| Dev | Fitur | Fokus |
|-----|-------|-------|
| Dev 1 | F1 Autentikasi & Akun | auth, role, profil, seeder |
| Dev 2 | F2 Manajemen Daftar | CRUD list, owner, hapus atomik, list policy |
| Dev 3 | F3 Manajemen Tugas | CRUD tugas, prioritas, tenggat, selesai |
| Dev 4 | F4 Kolaborasi & Keanggotaan | pivot list_user, tambah/hapus anggota |
| Dev 5 | F5 Admin & Progres | kelola akun by admin, monitor progres |

Detail lengkap: **PRD.md §10**.

## Lisensi

Proyek studi/kuliah — silakan digunakan untuk pembelajaran.