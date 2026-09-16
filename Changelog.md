# Changelog — JARA (Advanced Todo List)

Semua perubahan penting pada project ini dicatat di file ini.

Format mengikuti [Keep a Changelog](https://keepachangelog.com/en/1.0.0/)
dan versi mengikuti [Semantic Versioning](https://semver.org/).

## [1.2.0] - 2026-09-16

### Added
- **Manajemen akun di area admin (F5 parsial):** `AdminController` dengan
  dashboard daftar akun (`GET /admin`, paginate), tambah akun
  (`POST /admin/users` — nama, email, password, role), dan hapus akun
  (`DELETE /admin/users/{user}`); admin tidak dapat menghapus akun sendiri (403);
  khusus role `admin`.
- **UI Blade:** halaman `lists/index`, `lists/joined`, `lists/show`,
  `lists/members`, dan `admin/index`; menu area admin pada layout.
- **Named routes:** seluruh route web diberi nama (`lists.*`,
  `lists.members.*`, `tasks.*`, `admin.*`, dsb.); parameter `/lists/{list}`
  dibatasi `whereNumber`; `GET /lists/joined` diprioritaskan di atas
  `/lists/{list}`.
- **Relasi & otorisasi baru:** `TodoList::tasks`, `Task::list`;
  `ListPolicy::view` kini mengizinkan member melihat daftar.
- **Feature test admin:** `AdminUserTest` (tambah & hapus akun oleh admin).

### Changed
- `ListController` & `ListMemberController` menjawab dengan JSON atau Blade
  (dual response via `expectsJson`).
- Update profil kini dilayani `PUT/PATCH /profile`; user terautentikasi di
  halaman utama dialihkan ke `/lists`.
- `phpunit.xml` memakai SQLite `:memory:`; `TaskTest` memakai `RefreshDatabase`.

### In Progress (milestone berikutnya, sesuai PRD §9)
- F5 Admin: tampilan progres per daftar.

## [1.1.0] - 2026-09-16

### Added
- **Hapus daftar atomik (F2):** endpoint `DELETE /lists/{list}` menghapus daftar
  beserta tugas & anggota dalam satu transaksi (`DB::transaction`); otorisasi
  via `ListPolicy::delete` (owner only).
- **Kolaborasi & keanggotaan (F4):** relasi model (`TodoList::members()`,
  `User::joinedLists()`), `ListMemberController` dengan endpoint tambah anggota
  (`POST /lists/{list}/members`), hapus anggota
  (`DELETE /lists/{list}/members/{member}`), dan daftar yang diikuti
  (`GET /lists/joined`); otorisasi `manageMembers` (owner only).
- **Pengujian tugas (F3):** feature test CRUD, validasi, dan otorisasi tugas.
- **Perbaikan UI autentikasi (F1):** halaman login/register/profil dengan layout
  bersama (`layouts/app`), homepage redesain, font Figtree.
- Pengaktifan otorisasi di kontroler (`Controller::authorize` resolution).

### Changed
- Database test PHPUnit berpindah ke MySQL (`jara`) mengikuti lingkungan
  tim; pengembangan juga memakai MySQL (`jara-todolist`).
- Assertion smoke test & E2E disesuaikan dengan teks homepage baru.

### In Progress (milestone berikutnya, sesuai PRD §9)
- F5 Admin: tambah/hapus akun & tampilan progres per daftar.

## [1.0.0] - 2026-09-16

### Added
- Autentikasi & akun pengguna (F1): register, login, logout, profil (edit/update),
  kolom `role` (`user`/`admin`), middleware admin, seeder admin & user contoh.
- Manajemen daftar (F2 — CRUD sementara delete): membuat daftar (otomatis jadi
  pemilik), melihat daftar milik & diikuti, update nama/deskripsi khusus owner,
  otorisasi via `ListPolicy` (403 untuk non-owner), validasi via Form Request.
- Manajemen tugas (F3): membuat, mengedit, menghapus, menandai selesai/toggle,
  prioritas (`low`/`medium`/`high`/`urgent`), tenggat waktu; otorisasi via
  `TaskPolicy` (owner/member list), validasi via Form Request.
- Kelas bantu `TaskPriority` (enum) dan `ListProgress` (kalkulasi progres).
- Skema database: tabel `lists`, `tasks`, `list_user`, dan kolom `role` pada users.
- Pengujian terotomasi: unit test (enum, support, konfigurasi model), feature test
  (skema DB, policy, form request, autentikasi, CRUD list, smoke test), dan E2E
  Playwright (`npm run test:e2e`).

### Changed
- `TodoList` kini mengisi `owner_id` pada saat pembuatan agar daftar langsung
  tercatat sebagai milik user (sebelumnya `owner_id` tidak masuk `fillable`).

### In Progress (milestone berikutnya, sesuai PRD §9)
- F2 Hapus daftar secara atomik (tugas + keanggotaan ikut terhapus).
- F4 Kolaborasi & keanggotaan (pivot `list_user`).
- F5 Admin: tambah/hapus akun & tampilan progres per daftar.
- Feature test CRUD tugas.

## [0.1.0] - 2026-09-16
### Added
- Fondasi aplikasi: autentikasi bawaan Laravel, template Blade + Tailwind/Vite,
  migrasi default (`users`, `cache`, `jobs`), dan test bawaan lulus.