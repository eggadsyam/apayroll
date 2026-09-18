# 💼 APayroll - Human Resource & Payroll Management System

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12">
  <img src="https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.2+">
  <img src="https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge" alt="License">
</p>

---

## 📌 Tentang Aplikasi

**APayroll** adalah sistem informasi manajemen sumber daya manusia (HRIS) dan penggajian (*Payroll System*) berbasis web yang dirancang khusus untuk mempermudah pengelolaan data karyawan, absensi, pengajuan lembur/cuti dengan alur persetujuan bertingkat, hingga kalkulasi penggajian otomatis (termasuk BPJS dan PPh 21 TER) serta penerbitan slip gaji PDF.

Aplikasi ini dilengkapi dengan **Employee Self-Service (Portal Karyawan)** dan manajemen hak akses berbasis peran (**Role-Based Access Control / RBAC**).

---

## ✨ Fitur Utama

### 1. 🏢 Manajemen Data Master & Kepegawaian
- **Data Karyawan Lengkap**: Informasi personal, jabatan, departemen, status ketenagakerjaan (PKWT, PKWTT, Freelance), shift kerja, data bank, serta NPWP/BPJS.
- **Import & Export Data**: Dukungan import data karyawan massal via Excel.
- **Departemen & Jabatan**: Struktur organisasi perusahaan yang rapi dan fleksibel.
- **Manajemen Shift Kerja**: Pengaturan jam masuk, pulang, serta toleransi keterlambatan.

### 2. ⏱️ Presensi & Absensi (Attendance)
- Pencatatan kehadiran harian karyawan.
- Perhitungan keterlambatan dan pulang cepat secara otomatis.
- Fitur import absensi harian dari file spreadsheet/mesin finger.
- Rekapitulasi absensi bulanan untuk integrasi ke payroll.

### 3. 📝 Pengajuan Cuti & Lembur (Leave & Overtime)
- **Multi-level Approval Workflow**:
  - Pengajuan oleh Karyawan.
  - Persetujuan Tahap 1 oleh **Manager / Atasan Langsung** per departemen.
  - Persetujuan Tahap Akhir oleh **HRD**.
- Saldo dan kuota cuti tahunan terpantau secara real-time.
- Perhitungan upah lembur otomatis sesuai ketentuan jam kerja.

### 4. 💳 Pinjaman / Kasbon Karyawan (Employee Loans)
- Pencatatan pengajuan pinjaman karyawan dan tenor cicilan.
- Pemotongan cicilan pinjaman otomatis setiap periode payroll diproses.
- Riwayat pembayaran dan sisa saldo pinjaman tercatat transparan.

### 5. 💰 Penggajian Otomatis (Payroll Engine)
- **Komponen Gaji Fleksibel**: Komponen tunjangan tetap, tunjangan tidak tetap, premi, insentif, dan potongan.
- **Kalkulasi Pajak PPh 21 TER**: Mengikuti regulasi Tarif Efektif Rata-Rata (Kategori A, B, C).
- **Perhitungan BPJS**: BPJS Ketenagakerjaan (JKK, JKM, JHT, JP) dan BPJS Kesehatan (perusahaan & karyawan).
- **Siklus Penggajian Terstruktur**:
  1. *Draft Payroll* dibuat oleh HRD.
  2. *Approval* oleh Manager.
  3. *Approval & Pembayaran* oleh Finance.
- **Cetak Slip Gaji**: Unduh slip gaji berformat PDF baik secara individual maupun *bulk download* (zip/semua).

### 6. 📱 Employee Self-Service (Portal Karyawan)
- Dashboard personal untuk staf:
  - Ringkasan kehadiran dan jatah cuti.
  - Formulir pengajuan cuti dan lembur langsung dari HP/desktop.
  - Riwayat gaji dan unduh slip gaji PDF mandiri.
  - Notifikasi persetujuan secara instan.

### 7. 📊 Laporan & Audit
- Rekapitulasi Penggajian (Payroll Summary) & Rincian Komponen (Payroll Detail).
- Rekapitulasi Absensi & Lembur Karyawan.
- Daftar transfer bank untuk kebutuhan divisi Finance.
- **Activity Log / Audit Trail** menggunakan Spatie Activitylog untuk merekam seluruh perubahan data penting.

---

## 👥 Hirarki Peran & Hak Akses (RBAC)

Aplikasi memiliki 5 tingkatan peran dengan wewenang yang terpisah:

| Peran | Deskripsi Singkat | Ruang Lingkup Akses |
|---|---|---|
| **Super Admin** | Administrator Sistem | Akses penuh ke seluruh menu, pengaturan perusahaan, manajemen user, dan log sistem. |
| **HRD** | Bagian Personalia | Mengelola data karyawan, master data, absensi, pinjaman, ACC cuti/lembur, dan draft payroll. |
| **Manager** | Atasan Departemen | Menyetujui (Approve) cuti/lembur bawahan satu departemen & review draft gaji divisi. |
| **Finance** | Bagian Keuangan | Review payroll, otorisasi transfer gaji, status lunas (Paid), dan laporan keuangan payroll. |
| **Employee** | Karyawan Staf | Mengakses **Portal Karyawan**: absensi pribadi, ajukan cuti/lembur, dan unduh slip gaji. |

> 📘 Untuk penjelasan detail mengenai alur operasional dan pembagian peran, silakan baca:
> - [Panduan Pengguna Pemula (USER_GUIDE.md)](USER_GUIDE.md)
> - [Saran Hirarki Peran & Hak Akses (hierarchy_roles.md)](hierarchy_roles.md)

---

## 🛠️ Tech Stack & Dependencies

- **Framework**: [Laravel 12.x](https://laravel.com) (PHP 8.2+)
- **Authentication**: Laravel Breeze
- **Styling & UI**: [Tailwind CSS 3.x](https://tailwindcss.com), Alpine.js
- **PDF Engine**: [barryvdh/laravel-dompdf](https://github.com/barryvdh/laravel-dompdf)
- **Excel Import/Export**: [maatwebsite/excel](https://maatwebsite.nl/)
- **Permissions**: [spatie/laravel-permission](https://spatie.be/docs/laravel-permission)
- **Activity Logging**: [spatie/laravel-activitylog](https://spatie.be/docs/laravel-activitylog)
- **Database**: MySQL / PostgreSQL / SQLite

---

## 🚀 Panduan Instalasi Lokal

Ikuti langkah-langkah berikut untuk menjalankan project di komputer lokal:

### 1. Prasyarat Sistem
- PHP >= 8.2
- Composer >= 2.x
- Node.js & NPM >= 18.x
- MySQL / MariaDB (atau SQLite)

### 2. Clone Repository
```bash
git clone https://github.com/eggadsyam/apayroll.git
cd apayroll
```

### 3. Install Dependencies
```bash
# Install PHP packages
composer install

# Install Javascript packages
npm install
```

### 4. Konfigurasi Environment (`.env`)
Salin file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
Sesuaikan konfigurasi database pada file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=apayroll
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Generate Application Key & Storage Link
```bash
php artisan key:generate
php artisan storage:link
```

### 6. Migrasi & Seeder Database
Jalankan migrasi tabel dan pengisian data awal (role, akun default, komponen gaji, tarif pajak TER):
```bash
php artisan migrate:fresh --seed
```

### 7. Jalankan Server Pengembangan
Buka 2 terminal:

**Terminal 1 (Backend Laravel):**
```bash
php artisan serve
```

**Terminal 2 (Vite Asset Bundler):**
```bash
npm run dev
```

Aplikasi dapat diakses melalui browser di: `http://127.0.0.1:8000`

---

## 🔑 Akun Default (Hasil Seeder)

Setelah menjalankan `php artisan migrate:fresh --seed`, Anda dapat masuk menggunakan akun demo berikut (Password default: `password`):

| Role | Email | Password |
|---|---|---|
| **Super Admin** | `admin@payroll.com` | `password` |
| **HRD Staff** | `hrd@payroll.com` | `password` |
| **Finance Staff** | `finance@payroll.com` | `password` |
| **Manager** | `manager@payroll.com` | `password` |
| **Employee (Karyawan)** | `budi@payroll.com` | `password` |

---

## 🧪 Menjalankan Pengujian (Testing)

Untuk memastikan seluruh fungsi berjalan dengan baik:
```bash
php artisan test
```

---

## 📄 Lisensi

Project ini dilisensikan di bawah [MIT License](LICENSE).
