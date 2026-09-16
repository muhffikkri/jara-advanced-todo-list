# Commit.md — Konvensi Commit

Panduan ini dipakai bersama oleh seluruh tim (5 developer) agar riwayat git rapi
dan mudah dirilis. Mengikuti **Conventional Commits**.

## 1. Format

```
<type>(<scope>): <deskripsi singkat>
```

- `<type>`: jenis perubahan (wajib).
- `<scope>`: fitur/modul yang disentuh (opsional).
- `<deskripsi>`: kalimat singkat, huruf kecil, **max ~72 karakter**.
- Body opsional untuk menjelaskan kenapa/mengapa + referensi issue.
- Tanda `!` setelah type/scope menandakan breaking change: `feat(auth)!`.

## 2. Tipe

| Type      | Kegunaan                                        |
|-----------|-------------------------------------------------|
| `feat`    | Fitur baru.                                      |
| `fix`     | Perbaikan bug.                                   |
| `docs`    | Perubahan dokumentasi (PRD, Design, README, dll).|
| `style`   | Format, whitespace; tanpa mengubah logika.       |
| `refactor`| Perubahan struktur tanpa mengubah perilaku.      |
| `test`    | Menambah/mengubah test.                          |
| `chore`   | Tugas kecil (deps, config, tooling).             |

## 3. Scope usulan (sesuai pembagian fitur)

`auth`, `list`, `task`, `member`, `admin` — plus `core` untuk setup/arsitektur.

## 4. Contoh

```
feat(auth): add register with default user role
fix(list): rollback delete when task removal fails
test(task): cover validasi prioritas & due_date
docs(prd): update acceptance criteria F2
chore(core): add releases folder
refactor(admin): extract user list service
```

## 5. Aturan kerja tim

1. **Satu commit = satu perubahan logis** (jangan campur 2 fitur).
2. Commit hanya file yang relevan; **jangan commit rahasia** (cek `.env`).
3. Sebelum commit: jalankan test terkait (`php artisan test`) dan
   `vendor/bin/pint --dirty` agar bersih.
4. Tulis commit message yang jelas; hindari "fix stuff", "update".
5. Branch kerja per fitur, misal `feat/auth-f1`, `feat/list-f2`, dst.
6. Merge ke branch utama setelah test lulus.
7. Saat siap rilis: buat versi (`releases/`) dan catat di `Changelog.md`.

## 6. Alur contoh (Developer 2 di fitur F2)

```sh
git checkout -b feat/list-f2
git add app/Http/Controllers/ListController.php routes/web.php
git commit -m "feat(list): hapus daftar beserta tugas & anggota secara atomik"
git push origin feat/list-f2
```