# Skema Database — JARA (Advanced Todo List)

**Versi:** 1.0 | **Tanggal:** 2026-09-16 | **DB:** SQLite (development)

Dokumen ini menjelaskan seluruh tabel, kolom, tipe data, konstrain, dan relasi pada
database aplikasi ini. Bagian **A-D** adalah tabel yang **sudah ada** dari migrasi
default Laravel; bagian **E-G + perubahan users** adalah **rencana (belum dibuat
migrasinya)** untuk fitur F1–F5.

> Sumber aturan: `database/migrations/*.php` dan `Design.md §2`.

---

## Status Tabel

| Tabel                   | Status     | Migrasi                                |
| ----------------------- | ---------- | -------------------------------------- |
| `users`                 | ✅ Ada     | `0001_01_01_000000_create_users_table` |
| `password_reset_tokens` | ✅ Ada     | `(di migrate users)`.                  |
| `sessions`              | ✅ Ada     | `(di migrate users)`.                  |
| `cache`                 | ✅ Ada     | `0001_01_01_000001_create_cache_table` |
| `cache_locks`           | ✅ Ada     | `(di migrate cache)`.                  |
| `jobs`                  | ✅ Ada     | `0001_01_01_000002_create_jobs_table`  |
| `job_batches`           | ✅ Ada     | `(di migrate jobs)`.                   |
| `failed_jobs`           | ✅ Ada     | `(di migrate jobs)`.                   |
| `lists`                 | ⏳ Rencana | —                                      |
| `tasks`                 | ⏳ Rencana | —                                      |
| `list_user`             | ⏳ Rencana | —                                      |

---

## A. `users` — Pengguna sistem

✅ **Sudah ada** (akan ditambah kolom `role` untuk fitur F1).

| Kolom               | Tipe            | Null | Keterangan                  |
| ------------------- | --------------- | ---- | --------------------------- |
| `id`                | BIGINT unsigned | No   | Primary key, auto-increment |
| `name`              | VARCHAR(255)    | No   | Nama pengguna               |
| `email`             | VARCHAR(255)    | No   | Unique                      |
| `email_verified_at` | TIMESTAMP       | Yes  | Waktu verifikasi email      |
| `password`          | VARCHAR(255)    | No   | Hashed                      |
| `remember_token`    | VARCHAR(100)    | Yes  | Token "remember me"         |
| `created_at`        | TIMESTAMP       | Yes  |                             |
| `updated_at`        | TIMESTAMP       | Yes  |                             |

**Rencana penambahan (F1):**

| Kolom  | Tipe         | Null | Keterangan                 |
| ------ | ------------ | ---- | -------------------------- |
| `role` | VARCHAR enum | No   | `user` (default) / `admin` |

---

## B. Tabel pendukung autentikasi

### `password_reset_tokens` — Token reset password

| Kolom        | Tipe         | Null | Keterangan  |
| ------------ | ------------ | ---- | ----------- |
| `email`      | VARCHAR(255) | No   | Primary key |
| `token`      | VARCHAR(255) | No   |             |
| `created_at` | TIMESTAMP    | Yes  |             |

### `sessions` — Sesi pengguna

| Kolom           | Tipe            | Null | Keterangan              |
| --------------- | --------------- | ---- | ----------------------- |
| `id`            | VARCHAR(255)    | No   | Primary key             |
| `user_id`       | BIGINT unsigned | Yes  | FK → `users.id` (index) |
| `ip_address`    | VARCHAR(45)     | Yes  | Support IPv6            |
| `user_agent`    | TEXT            | Yes  |                         |
| `payload`       | LONGTEXT        | No   |                         |
| `last_activity` | INTEGER         | No   | Index; Epoch seconds    |

---

## C. `cache` & `cache_locks`

✅ **Sudah ada.**

### `cache`

| Kolom        | Tipe         | Null | Keterangan   |
| ------------ | ------------ | ---- | ------------ |
| `key`        | VARCHAR(255) | No   | Primary key  |
| `value`      | MEDIUMTEXT   | No   |              |
| `expiration` | BIGINT       | No   | Index; epoch |

### `cache_locks`

| Kolom        | Tipe         | Null | Keterangan   |
| ------------ | ------------ | ---- | ------------ |
| `key`        | VARCHAR(255) | No   | Primary key  |
| `owner`      | VARCHAR(255) | No   |              |
| `expiration` | BIGINT       | No   | Index; epoch |

---

## D. `jobs`, `job_batches`, `failed_jobs` (antrian)

✅ **Sudah ada.**

### `jobs`

| Kolom          | Tipe              | Null | Keterangan       |
| -------------- | ----------------- | ---- | ---------------- |
| `id`           | BIGINT unsigned   | No   | Primary key      |
| `queue`        | VARCHAR(255)      | No   | Index            |
| `payload`      | LONGTEXT          | No   | JSON payload job |
| `attempts`     | SMALLINT unsigned | No   |                  |
| `reserved_at`  | INTEGER unsigned  | Yes  | Epoch            |
| `available_at` | INTEGER unsigned  | No   | Epoch            |
| `created_at`   | INTEGER unsigned  | No   | Epoch            |

### `job_batches`

| Kolom            | Tipe         | Null | Keterangan  |
| ---------------- | ------------ | ---- | ----------- |
| `id`             | VARCHAR(255) | No   | Primary key |
| `name`           | VARCHAR(255) | No   |             |
| `total_jobs`     | INTEGER      | No   |             |
| `pending_jobs`   | INTEGER      | No   |             |
| `failed_jobs`    | INTEGER      | No   |             |
| `failed_job_ids` | LONGTEXT     | No   | JSON        |
| `options`        | MEDIUMTEXT   | Yes  |             |
| `cancelled_at`   | INTEGER      | Yes  | Epoch       |
| `created_at`     | INTEGER      | No   | Epoch       |
| `finished_at`    | INTEGER      | Yes  | Epoch       |

### `failed_jobs`

| Kolom        | Tipe            | Null | Keterangan                       |
| ------------ | --------------- | ---- | -------------------------------- |
| `id`         | BIGINT unsigned | No   | Primary key                      |
| `uuid`       | VARCHAR(255)    | No   | Unique                           |
| `connection` | VARCHAR(255)    | No   |                                  |
| `queue`      | VARCHAR(255)    | No   |                                  |
| `payload`    | LONGTEXT        | No   |                                  |
| `exception`  | LONGTEXT        | No   |                                  |
| `failed_at`  | TIMESTAMP       | No   | Default `CURRENT_TIMESTAMP`      |
| — (index)    |                 |      | `(connection, queue, failed_at)` |

---

## E. `lists` — Daftar/Project (RENCANA, fitur F2)

| Kolom         | Tipe            | Null | Keterangan                           |
| ------------- | --------------- | ---- | ------------------------------------ |
| `id`          | BIGINT unsigned | No   | Primary key                          |
| `owner_id`    | BIGINT unsigned | No   | FK → `users.id`, `on delete cascade` |
| `name`        | VARCHAR(255)    | No   | Nama daftar                          |
| `description` | TEXT            | Yes  | Opsional                             |
| `created_at`  | TIMESTAMP       | Yes  |                                      |
| `updated_at`  | TIMESTAMP       | Yes  |                                      |

- **Unik/multi-index (pending):** `index` pada `owner_id`.

---

## F. `tasks` — Tugas (RENCANA, fitur F3)

| Kolom          | Tipe            | Null | Keterangan                                     |
| -------------- | --------------- | ---- | ---------------------------------------------- |
| `id`           | BIGINT unsigned | No   | Primary key                                    |
| `list_id`      | BIGINT unsigned | No   | FK → `lists.id`, **`on delete cascade`**       |
| `title`        | VARCHAR(255)    | No   | Judul tugas                                    |
| `description`  | TEXT            | Yes  | Opsional                                       |
| `priority`     | VARCHAR enum    | No   | `low` / `medium` (default) / `high` / `urgent` |
| `due_date`     | DATE / DATETIME | Yes  | Tenggat waktu                                  |
| `is_completed` | BOOLEAN         | No   | Default `false`                                |
| `completed_at` | TIMESTAMP       | Yes  | Saat ditandai selesai                          |
| `created_at`   | TIMESTAMP       | Yes  |                                                |
| `updated_at`   | TIMESTAMP       | Yes  |                                                |

---

## G. `list_user` — Pivot keanggotaan (RENCANA, fitur F4)

| Kolom        | Tipe            | Null | Keterangan                 |
| ------------ | --------------- | ---- | -------------------------- |
| `list_id`    | BIGINT unsigned | No   | FK → `lists.id`, `cascade` |
| `user_id`    | BIGINT unsigned | No   | FK → `users.id`, `cascade` |
| `added_by`   | BIGINT unsigned | No   | FK → `users.id` (opsional) |
| `created_at` | TIMESTAMP       | Yes  |                            |

- **Composite primary key:** `(list_id, user_id)` — satu pasangan unik.
- `added_by` mencatat siapa yang menambahkan anggota.

---

## Relasi Antar Tabel

```
┌─────────────┐  1        N ┌─────────────┐
│    users    │────────────►│    lists    │  (users.owner → lists.owner_id)
└─────────────┘             └──────┬──────┘
      ▲                            │ 1
      │ N                           │ N
      │                             ▼
┌─────┴──────────┐            ┌─────────────┐
│   list_user    │            │    tasks    │  (list_id → lists.id, cascade)
└───────────────┘            └─────────────┘

Legenda:
- users 1─N lists      = pemilik daftar
- lists 1─N tasks      = daftar punya banyak tugas (cascade hapus)
- users 1─N list_user  = keanggotaan
- lists 1─N list_user  = anggota daftar
- users ─┬─ lists (owner) ─┬─ tasks
- users ─┴─ list_user ─┴─┘  (anggota)
```

**Rangkuman relasi:**

| Relasi              | Tabel sumber         | Tabel tujuan | Mode hapus (FK)                            |
| ------------------- | -------------------- | ------------ | ------------------------------------------ |
| Pemilik daftar      | `lists.owner_id`     | `users.id`   | `cascade` (jika user dihapus, daftar ikut) |
| Tugas dalam daftar  | `tasks.list_id`      | `lists.id`   | `cascade`                                  |
| Keanggotaan (pivot) | `list_user.list_id`  | `lists.id`   | `cascade`                                  |
| Keanggotaan (pivot) | `list_user.user_id`  | `users.id`   | `cascade`                                  |
| Penambah anggota    | `list_user.added_by` | `users.id`   | — (opsional, `nullOnDelete`)               |
| Sesi pengguna       | `sessions.user_id`   | `users.id`   | `cascade` (default Laravel)                |

---

## Catatan

1. **SQLite saat ini:** Foreign key/SQLite memerlukan `PRAGMA foreign_keys = ON`
   (diaktifkan Laravel secara default). Untuk produksi disarankan PostgreSQL/MySQL.
2. **`role` pada users** belum ada di DB saat ini; ditambahkan pada migrasi fitur F1.
3. **Tabel rencana (E–G)** harus dibuat melalui `php artisan make:migration`, bukan
   manual, agar sesuai konvensi Laravel.
4. Perubahan skema harus disertai update dokumen ini + `Changelog.md`.
