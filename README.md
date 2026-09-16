# JARA — Advanced Todo List

JARA adalah aplikasi web *advanced todo list* untuk mengelola tugas pribadi maupun tim.
Pengguna dapat membuat daftar (*list/project*), menetapkan prioritas dan tenggat waktu,
menandai tugas selesai, berkolaborasi dengan anggota tim, serta memantau progres.
Fitur dikelola berbasis peran (`user`/`admin`) dengan otorisasi per-fiturnya.

## Fitur

- **F1 — Autentikasi & Akun:** register, login/logout, role `user`/`admin`, halaman
  profil (edit/update), middleware admin, seeder akun contoh.
- **F2 — Manajemen Daftar (List/Project):** CRUD daftar; pembuat otomatis menjadi
  pemilik (`owner_id`); hapus daftar beserta tugas & anggota secara **atomik**
  (`DB::transaction`); otorisasi owner via `ListPolicy` (403 untuk yang lain).
- **F3 — Manajemen Tugas:** buat, edit, hapus, toggle selesai; prioritas
  (`low`/`medium`/`high`/`urgent`); tenggat waktu; validasi via Form Request;
  otorisasi via `TaskPolicy` (owner/member list).
- **F4 — Kolaborasi & Keanggotaan:** pemilik menambah/menghapus anggota (pivot
  `list_user`), melihat daftar yang diikuti (`/lists/joined`), otorisasi
  `manageMembers` (owner only).
- **F5 — Admin & Progres:** area admin (`/admin`) khusus role admin; kalkulasi
  progres `ListProgress` (tugas selesai / total × 100%).

## Struktur Repo

```
app/
  Http/Controllers/   # Auth, Profile, List, ListMember, Task
  Http/Requests/      # Form Request validasi input (Store*/Update*)
  Models/             # User, TodoList (lists), Task
  Policies/           # ListPolicy, ListMemberPolicy, TaskPolicy
  Providers/          # registrasi Gate/policy
  Support/            # ListProgress (kalkulasi progres)
database/
  factories/          # UserFactory, TodoListFactory, TaskFactory
  migrations/         # users, cache, jobs, lists, tasks, list_user
  seeders/            # akun admin & user contoh
resources/views/      # Blade: welcome, auth, profile, layouts
routes/web.php        # seluruh endpoint aplikasi
tests/
  Unit/               # unit test (enum, support, konfigurasi model)
  Feature/            # integration test (auth, list, task, member, policy)
  e2e/                # E2E test (Playwright)
```

## Tech Stack

- Laravel 13 (PHP 8.5)
- Database MySQL (development & test, via `pdo_mysql`)
- Blade + Tailwind CSS, Vite
- PHPUnit (unit & feature test), Playwright (E2E), Laravel Pint

## Menjalankan (Local Development)

```sh
# Prasyarat: PHP 8.5+, Composer 2.x, Node 20+, MySQL aktif
composer install
npm install
cp .env.example .env          # Windows: copy .env.example .env
php artisan key:generate
php artisan migrate --seed
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

## Rilis

- **v1.1.0** — kolaborasi anggota (F4), hapus daftar atomik (F2), test tugas.
- **v1.0.0** — autentikasi (F1), CRUD list & tugas (F2–F3), suite test otomasi.

Catatan lengkap per versi ada di folder `releases/` dan `Changelog.md`.

## Dokumen Terkait

[PRD.md](PRD.md) · [Design.md](Design.md) · [Database-Schema.md](Database-Schema.md) ·
[Commit.md](Commit.md) · [Checklist PRD.md](Checklist%20PRD.md) · [Changelog.md](Changelog.md)

## Lisensi

Proyek studi/kuliah — silakan digunakan untuk pembelajaran.