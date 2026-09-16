# Checklist PRD — JARA (Advanced Todo List)

Checklist ini menelusuri tiap poin di `PRD.md` agar mudah dilacak. Centang `[x]` saat selesai.

**Status keseluruhan:** F0 Selesai · F1–F5 Belum

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

- [ ] Register akun baru (role `user`).
- [ ] Login dan logout.
- [ ] Kolom `role` pada users (`user`/`admin`).
- [ ] Seeder akun admin & user contoh.
- [ ] Halaman profil sederhana + update profil.
- [ ] Feature test auth (register/login/logout).

## F2. Manajemen Daftar (List/Project)

- [ ] Membuat daftar baru → otomatis menjadi pemilik (`owner_id`).
- [ ] Melihat daftar milik & yang diikuti.
- [ ] Update nama/deskripsi daftar (khusus owner).
- [ ] Hapus daftar (khusus owner).
- [ ] Hapus daftar bersifat **atomik**: tugas + keanggotaan ikut terhapus; jikagagal, semua dibatalkan (rollback).
- [ ] Aksi bukan-owner **ditolak (403)**.
- [ ] Validasi input daftar.
- [ ] Feature test: `owner_can_delete_list_atomically`.
- [ ] Feature test: `deleting_list_removes_tasks_and_members`.
- [ ] Feature test: `non_owner_cannot_delete_list` (403).

## F3. Manajemen Tugas

- [ ] Membuat tugas di dalam daftar.
- [ ] Mengedit tugas (judul, deskripsi).
- [ ] Menghapus tugas.
- [ ] Menetapkan prioritas (low/medium/high/urgent).
- [ ] Menetapkan tenggat waktu (due_date).
- [ ] Menandai tugas selesai / batal selesai.
- [ ] Validasi input tugas.
- [ ] Feature test CRUD tugas & validasi.

## F4. Kolaborasi & Keanggotaan

- [ ] Pemilik dapat menambahkan pengguna lain ke daftar (pivot `list_user`).
- [ ] Pemilik dapat menghapus keanggotaan anggota.
- [ ] Anggota dapat melihat & mengerjakan tugas dalam daftar.
- [ ] Non-member/owner-hanya diuji (403 untuk non-owner).
- [ ] Feature test manajemen anggota.

## F5. Admin & Pemantauan Progres

- [ ] Middleware/policy admin hanya untuk role `admin`.
- [ ] Admin dapat menambahkan akun pengguna.
- [ ] Admin dapat menghapus akun pengguna.
- [ ] Perhitungan progres = tugas selesai / total × 100%.
- [ ] Tampilan progres per daftar.
- [ ] Feature test admin tambah/hapus akun.

## NFR — Non-Fungsional (lintas fitur)

- [ ] NFR-1: operasi hapus daftar dalam satu transaksi (`DB::transaction`).
- [ ] NFR-2: otorisasi via Policy → 403 untuk akses tidak sah.
- [ ] NFR-3: seluruh input melalui Form Request / validasi Laravel.
- [ ] NFR-4: semua query memakai Eloquent/parameterized (tanpa raw concatenation).
- [ ] NFR-5: Laravel 13 (PHP 8.5), SQLite development.
- [ ] NFR-6: seluruh fitur utama memiliki feature test yang lulus.

## QA Akhir

- [ ] Semua test lulus (`php artisan test`).
- [ ] `vendor/bin/pint --dirty` bersih.
- [ ] Build frontend OK (`npm run build`).
- [ ] Bertemu acceptance criteria PRD §8.
- [ ] Release draft dibuat di folder `releases/`.