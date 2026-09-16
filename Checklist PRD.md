# Checklist PRD — JARA (Advanced Todo List)

Checklist ini menelusuri tiap poin di `PRD.md` agar mudah dilacak. Centang `[x]` saat selesai.

**Status keseluruhan:** F0–F4 Selesai (fitur inti) · F5 Parsial

---

## F0. Fondasi & Dokumentasi

- [x] Setup project Laravel 13 (PHP 8.5) + SQLite + dependensi.
- [x] `PRD.md` — kebutuhan produk & pembagian tugas 5 developer.
- [x] `Design.md` — arsitektur, skema, otorisasi, route.
- [x] `Database-Schema.md` — tabel, kolom, tipe data, dan relasi lengkap.
- [x] `Changelog.md`, `Checklist PRD.md`, `Commit.md`, `README.md`, `releases/`.
- [x] Build Vite OK & test bawaan Laravel lulus.
- [x] Unit test (`TaskPriority`, `ListProgress`, konfigurasi model User & TodoList).
- [x] Integration/feature test (skema DB, `ListPolicy`, `StoreListRequest`, smoke test).
- [x] E2E test via Playwright (`tests/e2e`) dengan web server `.env.playwright`.

## F1. Autentikasi & Akun Pengguna

- [x] Register akun baru (role `user`).
- [x] Login dan logout.
- [x] Kolom `role` pada users (`user`/`admin`).
- [x] Seeder akun admin & user contoh.
- [x] Halaman profil sederhana + update profil.
- [x] Feature test auth (register/login/logout).

## F2. Manajemen Daftar (List/Project)

- [x] Membuat daftar baru → otomatis menjadi pemilik (`owner_id`).
- [x] Melihat daftar milik & yang diikuti.
- [x] Update nama/deskripsi daftar (khusus owner).
- [x] Hapus daftar (khusus owner).
- [x] Hapus daftar bersifat **atomik**: tugas + keanggotaan ikut terhapus; jikagagal, semua dibatalkan (rollback).
- [x] Aksi bukan-owner **ditolak (403)**.
- [x] Validasi input daftar.
- [x] Feature test: `owner_can_delete_list_atomically`.
- [x] Feature test: `deleting_list_removes_tasks_and_members`.
- [x] Feature test: `non_owner_cannot_delete_list` (403).

## F3. Manajemen Tugas

- [x] Membuat tugas di dalam daftar.
- [x] Mengedit tugas (judul, deskripsi).
- [x] Menghapus tugas.
- [x] Menetapkan prioritas (low/medium/high/urgent).
- [x] Menetapkan tenggat waktu (due_date).
- [x] Menandai tugas selesai / batal selesai.
- [x] Validasi input tugas.
- [x] Feature test CRUD tugas & validasi.

## F4. Kolaborasi & Keanggotaan

- [x] Pemilik dapat menambahkan pengguna lain ke daftar (pivot `list_user`).
- [x] Pemilik dapat menghapus keanggotaan anggota.
- [x] Anggota dapat melihat & mengerjakan tugas dalam daftar.
- [x] Non-member/owner-hanya diuji (403 untuk non-owner).
- [x] Feature test manajemen anggota.

## F5. Admin & Pemantauan Progres

- [x] Middleware/policy admin hanya untuk role `admin`.
- [x] Admin dapat menambahkan akun pengguna.
- [x] Admin dapat menghapus akun pengguna.
- [x] Perhitungan progres = tugas selesai / total × 100%.
- [ ] Tampilan progres per daftar.
- [x] Feature test admin tambah/hapus akun.

## NFR — Non-Fungsional (lintas fitur)

- [x] NFR-1: operasi hapus daftar dalam satu transaksi (`DB::transaction`).
- [x] NFR-2: otorisasi via Policy → 403 untuk akses tidak sah.
- [x] NFR-3: seluruh input melalui Form Request / validasi Laravel.
- [x] NFR-4: semua query memakai Eloquent/parameterized (tanpa raw concatenation).
- [x] NFR-5: Laravel 13 (PHP 8.5), MySQL development.
- [x] NFR-6: seluruh fitur utama memiliki feature test yang lulus.

## QA Akhir

- [x] Semua test lulus (`php artisan test`).
- [x] `vendor/bin/pint --dirty` bersih.
- [x] Build frontend OK (`npm run build`).
- [ ] Bertemu acceptance criteria PRD §8.
- [x] Release draft dibuat di folder `releases/`.