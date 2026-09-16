# Changelog — JARA (Advanced Todo List)

Semua perubahan penting pada project ini dicatat di file ini.

Format mengikuti [Keep a Changelog](https://keepachangelog.com/en/1.0.0/)
dan versi mengikuti [Semantic Versioning](https://semver.org/).

## [Unreleased]

### Added
- Setup project Laravel 13 (PHP 8.5) termasuk dependensi, `.env`, dan database SQLite.
- Instalasi Laravel Boost (guidelines, skills, MCP) untuk alur kerja tim.
- Instalasi dependensi frontend (npm) dan verifikasi build Vite.
- Dokumentasi awal: `PRD.md`, `Design.md`, `Changelog.md`, `Checklist PRD.md`,
  `Commit.md`, dan `README.md`.
- Folder `releases/` untuk catatan perilisan.
- Pembagian tugas per fitur untuk 5 developer (lihat `PRD.md` §10).

### In Progress (fitur sesuai milestone)
- F1 Kemampuan autentikasi & manajemen akun (role user/admin).
- F2 Manajemen daftar/list dengan penghapusan atomik & otorisasi pemilik.
- F3 Manajemen tugas (prioritas, tenggat, status selesai).
- F4 Kolaborasi & keanggotaan daftar.
- F5 Admin (tambah/hapus akun) & pemantauan progres.

## [0.1.0] - 2026-09-16
### Added
- Fondasi aplikasi: autentikasi bawaan Laravel, template Blade + Tailwind/Vite,
  migrasi default (`users`, `cache`, `jobs`), dan test bawaan lulus.