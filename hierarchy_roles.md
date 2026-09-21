# Saran Hirarki Peran (Role) dan Hak Akses (Permissions)

Dalam sistem HRIS (Human Resource Information System) / Payroll, pengaturan hak akses yang jelas sangat penting untuk menjaga kerahasiaan data dan kelancaran operasional. Berdasarkan praktik terbaik, berikut adalah saran pemisahan peran dan hak akses yang ideal untuk sistem Anda:

## 1. Karyawan (Employee)
Peran dasar untuk semua staf yang bekerja di perusahaan. Karyawan hanya memiliki akses ke **Portal Mandiri (Employee Portal)**.
- **Akses yang diberikan:**
  - Melihat profil diri sendiri.
  - Melihat riwayat absen sendiri.
  - Mengajukan dan melihat riwayat cuti & izin sendiri.
  - Mengajukan lembur sendiri.
  - Mengunduh/melihat slip gaji sendiri.
- **Akses yang DILARANG:** Tidak boleh masuk ke halaman Dashboard Admin (Data karyawan lain, penggajian, laporan, dll).

## 2. Supervisor (Atasan Langsung / Level 1)
Supervisor bertanggung jawab atas sekelompok tim dalam satu departemen.
- **Akses yang diberikan:**
  - Sama dengan Karyawan (memiliki Portal Mandiri untuk dirinya sendiri).
  - Bisa masuk ke Admin Dashboard dengan akses **terbatas**.
  - **Melihat Karyawan:** Hanya bisa melihat daftar dan detail karyawan yang menjadi **bawahannya langsung** (berdasarkan relasi `supervisor_id`).
  - **Persetujuan (Approval) Level 1:** Menyetujui (ACC) atau menolak pengajuan **Cuti** dan **Lembur** untuk bawahan langsungnya (status menjadi `pending_manager`).
  - **Laporan (Opsional):** Melihat laporan absensi untuk timnya saja.
- **Akses yang DILARANG:** Tidak bisa melihat gaji bawahan, tidak bisa menambah karyawan baru, dan tidak bisa melihat data di luar timnya.

## 3. Manager (Atasan Departemen / Level 2)
Manager adalah atasan dari satu departemen tertentu (misalnya Manager IT, Manager Marketing).
- **Akses yang diberikan:**
  - Sama dengan Karyawan (memiliki Portal Mandiri untuk dirinya sendiri).
  - Bisa masuk ke Admin Dashboard dengan akses **terbatas**.
  - **Melihat Karyawan:** Hanya bisa melihat daftar dan detail karyawan yang berada **di satu departemen** dengannya.
  - **Persetujuan (Approval) Level 2:** Menyetujui (ACC) atau menolak pengajuan **Cuti** dan **Lembur** untuk departemennya (setelah di-ACC oleh Supervisor, status menjadi `pending_hrd`).
  - **Laporan (Opsional):** Melihat laporan absensi untuk departemennya.
- **Akses yang DILARANG:** Tidak bisa melihat gaji bawahan, tidak bisa menambah karyawan baru, dan tidak bisa melihat data dari departemen lain.

## 4. HRD (Human Resources Department)
HRD adalah pusat pengelolaan sumber daya manusia dan operasional harian perusahaan.
- **Akses yang diberikan:**
  - Mengelola (CRUD) seluruh master data: Karyawan, Departemen, Jabatan, dll.
  - Melihat seluruh data Absensi Karyawan.
  - **Persetujuan (Approval) Final:** Menyetujui ACC akhir untuk Cuti dan Lembur dari semua departemen (setelah di-ACC Manager).
  - Mengelola data Pinjaman Karyawan.
  - Mencetak laporan absensi dan cuti seluruh perusahaan.
- **Akses yang DILARANG (Opsional/Tergantung Perusahaan):** Tidak memiliki akses untuk melihat nominal gaji atau menjalankan proses Payroll bulanan (jika dipisah dengan Finance).

## 5. Finance / Payroll
Staf keuangan yang khusus mengurus penggajian dan pajak (terkadang digabung dengan HRD, tetapi idealnya dipisah).
- **Akses yang diberikan:**
  - Mengelola komponen gaji (Tunjangan, Potongan).
  - Mengelola tarif PPh 21 (TER), BPJS, dan PTKP.
  - **Memproses Payroll (Penggajian bulanan).**
  - Mencetak dan mengekspor Laporan Gaji (Bank Transfer List, dsb).
- **Akses yang DILARANG:** Tidak perlu repot mengurus persetujuan cuti atau input absensi (karena itu ranah HRD).

## 6. Super Admin / Direktur
Pemilik sistem yang memiliki kekuasaan penuh (God Mode).
- **Akses yang diberikan:**
  - Memiliki **SEMUA** hak akses dari semua role.
  - Menambah/mengedit User dan memodifikasi Pengaturan Sistem (Company Settings).
  - Bisa mem-bypass alur approval (langsung ACC cuti/lembur jika dibutuhkan).
  - Melihat dashboard rekapitulasi keuangan dan SDM secara menyeluruh.

---

### Kondisi Akun `Manager` Anda Saat Ini
Saat ini, akun **Manager** yang Anda gunakan saat login ternyata **belum dikaitkan dengan profil Karyawan (Employee) manapun** di sistem (Data `employee_id`-nya kosong).

Karena sistem tidak tahu Manager tersebut berada di departemen apa, maka sistem menganggapnya bisa melihat semua data. Agar pembatasannya berfungsi, Anda harus:
1. Login sebagai **Super Admin/HRD**.
2. Pergi ke menu Karyawan, buat profil Karyawan untuk Manager tersebut (jangan lupa pilih **Departemennya**).
3. Pergi ke menu Pengguna (Users), edit akun Manager, lalu kaitkan **Karyawan**-nya.
