# Changelog — JARA (Advanced Todo List)

Semua perubahan penting pada project ini dicatat di file ini.

Format mengikuti [Keep a Changelog](https://keepachangelog.com/en/1.0.0/)
dan versi mengikuti [Semantic Versioning](https://semver.org/).

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