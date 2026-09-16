# PRD — JARA (Advanced Todo List)

**Versi Dokumen:** 1.0
**Tanggal:** 2026-09-16
**Status:** Draft
**Author:** Tim JARA

---

## 1. Ringkasan Produk

JARA adalah aplikasi web untuk mengelola tugas pribadi maupun tim. Pengguna dapat
membuat, mengelompokkan, dan mengatur tugas ke dalam beberapa daftar (*list/project*),
menetapkan prioritas dan tenggat waktu, serta menandai tugas sebagai selesai. Pemilik
daftar dapat menambahkan pengguna lain ke daftar tugasnya agar dapat dikerjakan bersama
dan memantau progres penyelesaian tugas. Admin bertanggung jawab menambah dan menghapus
akun pengguna dalam sistem.

## 2. Tujuan Produk

- Menyediakan pengelolaan tugas pribadi maupun kolaboratif dalam satu aplikasi.
- Memudahkan pemantauan progres penyelesaian tugas dalam sebuah daftar.
- Memberikan kontrol penuh kepada pemilik daftar atas daftar dan anggota yang dimilikinya.
- Menyediakan peran admin untuk mengelola akun pengguna.

## 3. Definisi / Istilah

| Istilah        | Definisi                                                              |
|----------------|-----------------------------------------------------------------------|
| Daftar (List)  | Wadah/kelompok tugas, juga disebut *project*. Dimiliki oleh satu orang.|
| Pemilik Daftar | Pengguna yang membuat daftar dan memiliki otoritas penuh atasnya.      |
| Anggota        | Pengguna lain yang ditambahkan pemilik ke dalam sebuah daftar.         |
| Tugas          | Item pekerjaan di dalam sebuah daftar dengan prioritas & tenggat waktu.|
| Prioritas      | Tingkat kepentingan tugas (rendah/sedang/tinggi/urgent).               |
| Progres        | Persentase tugas selesai di dalam sebuah daftar.                       |
| Admin          | Pengguna dengan peran admin yang mengelola akun pengguna.              |

## 4. Target Pengguna

- Individu yang ingin mengelola tugas pribadinya.
- Tim kecil yang ingin berkolaborasi dalam satu atau beberapa daftar tugas.
- Admin sistem yang dikelola oleh pemilik aplikasi.

## 5. Fitur Utama (User Story)

### F1. Autentikasi & Akun Pengguna
- Sebagai pengguna, saya dapat mendaftar, masuk, dan keluar akun.
- Sebagai pengguna, saya memiliki peran (`user` atau `admin`).
- Sebagai pengguna, saya dapat melihat dan mengubah profil sederhana saya.

### F2. Manajemen Daftar (List/Project)
- Sebagai pengguna, saya dapat membuat daftar baru dan otomatis menjadi pemiliknya.
- Sebagai pengguna, saya dapat melihat daftar yang saya miliki atau saya ikuti.
- Sebagai pengguna, saya dapat mengganti nama daftar yang saya miliki.
- Sebagai pengguna, saya dapat menghapus daftar yang saya miliki beserta **seluruh tugas
  dan keanggotaan** di dalamnya secara **atomik** (jika satu langkah gagal, seluruh
  perubahan dibatalkan).
- Permintaan dari pengguna yang **tidak berwenang** (bukan pemilik) wajib ditolak.

### F3. Manajemen Tugas
- Sebagai pengguna anggota daftar, saya dapat membuat, mengedit, dan menghapus tugas.
- Sebagai pengguna, saya dapat menetapkan prioritas dan tenggat waktu pada tugas.
- Sebagai pengguna, saya dapat menandai tugas sebagai selesai.

### F4. Kolaborasi & Keanggotaan
- Sebagai pemilik daftar, saya dapat menambahkan pengguna lain ke daftar saya.
- Sebagai pemilik daftar, saya dapat menghapus keanggotaan anggota.
- Sebagai anggota, saya dapat mengerjakan tugas bersama dan melihat progres daftar.

### F5. Admin & Pemantauan Progres
- Sebagai admin, saya dapat menambahkan akun pengguna baru.
- Sebagai admin, saya dapat menghapus akun pengguna.
- Sebagai anggota tim, saya dapat melihat progres penyelesaian tugas per daftar.

## 6. Kebutuhan Non-Fungsional

| ID    | Kebutuhan                                                                 |
|-------|---------------------------------------------------------------------------|
| NFR-1 | **Atomisitas:** Proses pembuatan/hapus daftar + hapus cascade tugas &
        keanggotaan berjalan dalam satu transaksi database (rollback jika gagal).|
| NFR-2 | **Otorisasi:** Semua aksi sensitif dicek kepemilikan (`Policy`); akses tidak
        sah ditolak (403).                                                    |
| NFR-3 | **Validasi Input:** Seluruh input pengguna divalidasi sebelum diproses.     |
| NFR-4 | **Keamanan SQL Injection:** Semua query menggunakan Eloquent Query Builder /
        parameter terparameterisasi (PDO prepared statement).                  |
| NFR-5 | **Framework:** Laravel 13 (PHP 8.5), database SQLite untuk pengembangan.    |
| NFR-6 | **Testing:** Setiap fitur utama dilengkapi feature test (PHPUnit).          |

## 7. Skope / Di Luar Cakupan (v1.0)

- Di luar cakupan: notifikasi real-time, lampiran file, UI drag & drop, API publik untuk pihak ketiga.

## 8. Kriteria Kelulusan (Acceptance Criteria)

1. Pengguna dapat membuat daftar dan otomatis menjadi pemiliknya.
2. Hanya pemilik yang dapat menghapus daftar; selainnya mendapat 403.
3. Penghapusan daftar menghapus semua tugas & anggota dalam satu transaksi atomik.
4. Pengguna dapat membuat/mengedit/menghapus tugas, menetapkan prioritas & tenggat, dan menandai selesai.
5. Pemilik dapat menambah/menghapus anggota daftar.
6. Progres daftar dihitung dari persentase tugas selesai.
7. Admin dapat menambah dan menghapus akun pengguna.
8. Semua input divalidasi; tidak ada celah SQL injection (Eloquent/parameterized).
9. Seluruh fitur inti tercakup feature test yang lulus.

## 9. Milestone

| Milestone | Isi                                              | Status  |
|-----------|--------------------------------------------------|---------|
| M0        | Setup project Laravel + dokumentasi                                              | ✅ Selesai |
| M1        | F1 Autentikasi & akun                              | ⏳ Belum  |
| M2        | F2 Manajemen daftar (atomik + otorisasi)          | ⏳ Belum  |
| M3        | F3 Manajemen tugas                                | ⏳ Belum  |
| M4        | F4 Kolaborasi & keanggotaan                       | ⏳ Belum  |
| M5        | F5 Admin & pemantauan progres                     | ⏳ Belum  |
| M6        | Integrasi, QA, pengujian end-to-end                | ⏳ Belum  |

## 10. Pembagian Tugas (5 Developer, 1 Fitur per Orang)

Setiap developer bertanggung jawab penuh atas satu fitur, termasuk skema database,
controller, policy (otorisasi), validasi, dan feature test-nya. Semboyan kompetensi
umum berlaku untuk semua: input wajib divalidasi dan semua query memakai Eloquent /
parameterized (prepared statement).

| Developer | Fitur | Ruang Lingkup |
|-----------|-------|---------------|
| **Dev 1 — Autentikasi & Akun** | F1 | Register, login, logout (Laravel Breeze/Built-in auth), field `role` pada users, profil, seeder akun admin & user. |
| **Dev 2 — Manajemen Daftar** | F2 | CRUD list/project; pembuatan otomatis `owner_id = auth user`; penghapusan daftar + cascade tugas & members secara **atomik** (`DB::transaction`); `ListPolicy` (owner-only); Validasi input. |
| **Dev 3 — Manajemen Tugas** | F3 | CRUD tugas (milik daftar): title, prioritas (enum rendah/sedang/tinggi/urgent), tenggat waktu, status selesai; validasi; test. |
| **Dev 4 — Kolaborasi & Keanggotaan** | F4 | Tambah/hapus anggota (`list_user` pivot) oleh owner saja; list daftar yang diikuti; relasi model; test keanggotaan. |
| **Dev 5 — Admin & Progres** | F5 | Dashboard admin (tambah/hapus akun user) + pemantauan progres (presentase tugas selesai per daftar); admin middleware; test. |

### Alur Integrasi & Ketergantungan

```
F1 (auth + role) ──► F2 (daftar, butuh user & owner) ──► F3 (tugas, butuh daftar)
                          │
F5 (admin & progres, butuh F2, F3) ◄── F4 (keanggotaan, butuh F1 & F2)
```

> **Catatan kolaborasi:** Dev 2 s.d. Dev 5 berangkat setelah F1 menyediakan auth.
> Dev 5 membutuhkan F2 dan F3 untuk menghitung progres. Koordinasi skema tabel
> dilakukan bersama di awal (lihat `Design.md`) agar per-Dev tidak bentrok.