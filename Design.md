# Design — JARA (Advanced Todo List)

**Versi:** 1.0 | **Status:** Draft | **Tanggal:** 2026-09-16

Dokumen ini adalah panduan desain arsitektur, skema data, API/routes, dan strategi
keamanan yang dipakai bersama oleh tim 5 developer.

---

## 1. Arsitektur

- **Framework:** Laravel 13 (PHP 8.5) — architecture default MVC.
- **Database:** SQLite untuk pengembangan (default `database/database.sqlite`).
- **Autentikasi:** Laravel Breeze atau `laravel/ui`-less built-in auth, dengan tambahan
  kolom `role` pada tabel `users` (`user` / `admin`).
- **Pola:** Controller → Form Request (validasi) → Service/Model. Otorisasi memakai
  **Policy** (`can()`), transaksi memakai `DB::transaction()`.
- **Frontend:** Blade + Tailwind (template default Laravel 13), di-bundle via Vite.

## 2. Skema Database

### `users`

| Kolom          | Tipe                     | Keterangan                        |
|----------------|--------------------------|-----------------------------------|
| id             | bigint unsigned (PK)     |                                   |
| name           | string(255)              |                                   |
| email          | string(255) unique       |                                   |
| role           | enum('user','admin')     | default `user`                     |
| password       | string                   | hashed                            |
| timestamps     |                          |                                   |
| remember_token | string nullable          |                                   |

### `lists` (tabel untuk daftar/project)

| Kolom       | Tipe                     | Keterangan                          |
|-------------|--------------------------|-------------------------------------|
| id          | bigint unsigned (PK)     |                                     |
| owner_id    | bigint unsigned (FK users)| pemilik daftar (on delete cascade)  |
| name        | string(255)              | nama daftar                         |
| description | text nullable            | opsional                            |
| timestamps  |                          |                                     |

### `tasks`

| Kolom         | Tipe                       | Keterangan                          |
|---------------|----------------------------|-------------------------------------|
| id            | bigint unsigned (PK)       |                                     |
| list_id       | bigint unsigned (FK lists) | on delete cascade                   |
| title         | string(255)                |                                     |
| description   | text nullable              |                                     |
| priority      | enum('low','medium','high','urgent') | default `medium`         |
| due_date      | date/datetime nullable     | tenggat waktu                        |
| is_completed  | boolean                    | default false                        |
| completed_at  | timestamp nullable         | saat ditandai selesai                |
| timestamps    |                            |                                     |

### `list_user` (pivot keanggotaan)

| Kolom      | Tipe                | Keterangan                          |
|------------|---------------------|-------------------------------------|
| list_id    | bigint unsigned FK  |                                     |
| user_id    | bigint unsigned FK  |                                     |
| added_by   | bigint unsigned FK  | siapa yang menambahkan (opsional)   |
| created_at | timestamp           |                                     |
| PK         | (list_id, user_id)  |                                     |

### Relasi

```
User 1───* List (owner)            List 1───* Task
User *───* List (via list_user)    List *───* User (via list_user, anggota)
```

## 3. Otorisasi (Policies)

| Aksi                     | Policy                   | Aturan                              |
|--------------------------|--------------------------|-------------------------------------|
| Update/hapus list        | `ListPolicy`             | hanya `owner_id === auth()->id()`    |
| Tambah/hapus anggota     | `ListPolicy`             | hanya owner                         |
| CRUD tugas               | `TaskPolicy`             | anggota daftar atau owner           |
| Admin tambah/hapus user  | `UserPolicy` + middleware `admin` | hanya role `admin`        |

Tindakan yang tidak berwenang menghasilkan **HTTP 403 Forbidden**.

## 4. Transaksi Atomik (Hapus Daftar)

Penghapusan daftar menghapus tugas & keanggotaan sekaligus. Dilakukan **terparameterisasi
dan atomic**:

1. Cek otorisasi pemilik → jika bukan pemilik, tolak (403).
2. Validasi input (Form Request).
3. `DB::transaction(fn () => ...)`:
   - Hapus semua `tasks` milik list (Eloquent builder → prepared statement).
   - Hapus semua baris `list_user` milik list.
   - Hapus `lists` itu sendiri.
4. Jika salah satu langkah gagal → seluruh transaksi **rollback**.

> Semua operasi memakai Eloquent/Query Builder yang menghasilkan **PDO prepared
> statements** sehingga aman dari SQL injection.

## 5. Progres Penyelesaian

Progres daftar dihitung dengan:

```
progres = (jumlah task is_completed=true) / (jumlah total task) × 100%
```

Bisa dihitung on-the-fly di controller/service, atau disimpan sebagai kolom turunan
(opsional) dan di-update pada event penyelesaian tugas.

## 6. Routes (Usulan)

```
# Auth (F1)
POST   /register                → create user (role user)
POST   /login                   → auth attempt
POST   /logout                  → logout
GET    /profile                 → tampil profil
PATCH  /profile                 → update profil

# Lists (F2)
GET    /lists                   → daftar milik & diikuti (index)
POST   /lists                   → create (owner otomatis)
GET    /lists/{list}            → show daftar + tugas + progres
PATCH  /lists/{list}            → update nama/deskripsi (owner)
DELETE /lists/{list}            → hapus atomik (owner)

# Tasks (F3)
POST    /lists/{list}/tasks     → store tugas
PATCH   /tasks/{task}           → update tugas
PATCH   /tasks/{task}/toggle    → tandai selesai / batal
DELETE  /tasks/{task}           → hapus tugas

# Members (F4)
POST    /lists/{list}/members   → tambah anggota (owner)
DELETE  /lists/{list}/members/{user} → hapus anggota (owner)
GET     /lists/{list}/members   → lihat anggota

# Admin (F5)
GET     /admin/users            → kelola akun (admin)
POST    /admin/users            → tambah akun (admin)
DELETE  /admin/users/{user}     → hapus akun (admin)
```

## 7. Validasi (Form Request)

| Field      | Rule contoh                                                        |
|------------|--------------------------------------------------------------------|
| `name`     | `required|string|max:255`                                          |
| `email`    | `required|email|unique:users,email`                                |
| `password` | `required|string|min:8|confirmed`                                  |
| `title`    | `required|string|max:255`                                          |
| `priority` | `required|in:low,medium,high,urgent`                               |
| `due_date` | `nullable|date|after_or_equal:today`                               |
| `user_id`  | `required|exists:users,id` (pivot tambah anggota)                  |

## 8. Keamanan

- **SQL Injection:** Wajib Eloquent / Query Builder (parameterized). Dilarang string
  concatenation pada query.
- **Validasi:** Semua input melalui Form Request/`ValidatedInput`.
- **Otorisasi:** Policy untuk setiap aksi sensitif.
- **Password:** di-hash (`Hash::make`) — automatis oleh Laravel.
- **CSRF:** Laravel CSRF default pada semua non-GET form.
- **XSS:** Blade auto-escapes output.

## 9. Testing

- **Feature test per fitur** (PHPUnit): auth, list CRUD + atomik + 403, task CRUD,
  membership, admin, progres.
- Contoh inti yang WAJIB ada:
  - `test_owner_can_delete_list_atomically`
  - `test_non_owner_cannot_delete_list` (403)
  - `test_deleting_list_removes_tasks_and_members`
  - `test_admin_can_delete_user_account`
  - `test_task_input_is_validated`

## 10. Struktur Folder Target

```
app/
  Http/Controllers/{Auth,List,Task,Member,Admin}Controller.php
  Http/Requests/{...}Request.php
  Models/{User,List,Task}.php
  Policies/{ListPolicy,TaskPolicy}.php
database/migrations/ 0001_users(role) / create_lists / create_tasks / create_list_user
database/factories/  {UserFactory, ListFactory, TaskFactory}
database/seeders/    DatabaseSeeder (admin + demo user)
routes/web.php
tests/Feature/       {AuthTest, ListTest, TaskTest, MemberTest, AdminTest}
```