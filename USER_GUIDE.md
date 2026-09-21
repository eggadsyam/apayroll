# 📖 Panduan Pengguna Sistem Penggajian (Payroll) untuk Pemula

Selamat datang di panduan sistem penggajian! Panduan ini disusun dengan bahasa yang sangat sederhana agar siapapun, meskipun tidak memiliki latar belakang IT, dapat memahami cara kerja sistem ini dari awal sampai akhir.

---

## 👥 Siapa Saja Pengguna (User) Sistem Ini?

Dalam sebuah perusahaan, tidak semua orang mengerjakan hal yang sama. Oleh karena itu, sistem ini membagi pengguna menjadi 6 peran (jabatan) dengan tugas yang berbeda-beda:

### 1. Super Admin (Admin Utama)
* **Siapa dia?** Biasanya tim IT atau pemilik perusahaan.
* **Apa tugasnya?** Dia ibarat dewa di sistem ini. Dia bisa melakukan apa saja, melihat apa saja, dan mengatur siapa saja yang boleh masuk ke dalam sistem. 

### 2. HRD (Human Resources Department)
* **Siapa dia?** Bagian personalia yang mengurus karyawan sehari-hari.
* **Apa tugasnya?** HRD adalah "jantung" dari aplikasi ini. Tugasnya paling banyak:
    * Memasukkan data karyawan baru.
    * Mencatat kehadiran (absen), cuti, dan sakit.
    * Memasukkan data lembur dan urusan koperasi (simpan pinjam).
    * **Tugas Utama:** Memulai proses perhitungan gaji di akhir bulan.

### 3. Supervisor (Atasan Langsung)
* **Siapa dia?** Atasan langsung (Level 1) yang memimpin tim kecil.
* **Apa tugasnya?** Tugas utamanya adalah melakukan persetujuan (Approval) Tahap 1. Jika anak buahnya di tim tersebut minta cuti atau lembur, Supervisor adalah orang pertama yang mengecek dan menyetujui.

### 4. Manager (Manajer Departemen)
* **Siapa dia?** Kepala divisi atau pemimpin departemen (atasan dari para Supervisor).
* **Apa tugasnya?** Melakukan persetujuan Tahap 2 untuk cuti dan lembur (setelah disetujui Supervisor). Nanti saat HRD selesai menghitung gaji, Manager juga ikut mengecek dan menyetujui draft gaji divisinya.

### 5. Finance (Keuangan)
* **Siapa dia?** Bagian keuangan atau bendahara perusahaan.
* **Apa tugasnya?** Pemegang kunci uang. Setelah HRD menghitung gaji, Finance bertugas mengecek ulang. Jika sudah benar, Finance akan mentransfer uang ke rekening karyawan, lalu menekan tombol **"Bayar (Pay)"** di sistem sebagai tanda lunas.

### 6. Employee (Karyawan Biasa)
* **Siapa dia?** Seluruh staf dan karyawan di perusahaan.
* **Apa tugasnya?** Karyawan hanya bisa melihat data milik dirinya sendiri. Mereka masuk ke sistem untuk melihat jatah cuti, mengajukan cuti, mengajukan pinjaman koperasi, dan melihat/mendownload **Slip Gaji** mereka.

---

## 🔄 Alur Kerja: Dari Awal Sampai Gajian!

Agar tidak bingung, bayangkan proses ini seperti cerita yang terbagi dalam 3 babak utama.

### TAHAP 1: Persiapan Awal (Setup Data)
*Tahap ini biasanya hanya dilakukan sekali di awal saat perusahaan baru menggunakan sistem, atau saat ada karyawan baru.*

1. **Membuat Struktur Kantor (Oleh HRD):** HRD membuat daftar Departemen (contoh: Pemasaran, IT) dan daftar Jabatan (contoh: Staf, Supervisor).
2. **Mengatur Rumus Gaji (Oleh HRD):** HRD menentukan apa saja isi amplop gaji. Misalnya ada Gaji Pokok, Tunjangan Makan, dan Potongan BPJS.
3. **Mendaftarkan Karyawan (Oleh HRD):** Jika ada orang baru, HRD membuatkan profilnya (Nama, Alamat, masuk divisi apa).
4. **Memasukkan Angka Gaji (Oleh HRD):** HRD memasukkan kesepakatan gaji ke sistem. (Misal: Karyawan A gaji pokoknya Rp 5.000.000).

### TAHAP 2: Operasional Harian (Berjalan Setiap Hari)
*Ini adalah hal-hal yang terjadi sehari-hari sepanjang bulan.*

1. **Absensi:** Setiap hari karyawan absen (bisa lewat mesin sidik jari). Di akhir minggu/bulan, data absen ini dimasukkan oleh HRD ke sistem.
2. **Lembur & Cuti:** 
   * Jika Karyawan A sakit/cuti, dia mengajukan di sistem. **Supervisor** dan **Manager** akan mengklik "Setuju" secara berjenjang.
   * Jika Karyawan B kerja lembur, dicatat di sistem dan **Supervisor** serta **Manager** juga harus mengklik "Setuju" agar lemburnya dibayar.
3. **Koperasi (Simpan Pinjam):** Jika karyawan mengajukan pinjaman ke koperasi kantor, HRD akan mencatatnya agar bulan depan gajinya otomatis dipotong untuk cicilan. Begitu juga dengan potongan simpanan wajib/sukarela.

### TAHAP 3: Hari Penggajian (Payday!)
*Ini adalah proses puncak di akhir bulan. Mari kita lihat urutannya:*

* **Langkah 1: Menghitung Gaji (Oleh HRD)**
  HRD menekan tombol *"Buat Penggajian Bulan Ini"*. Sistem akan sangat pintar dan otomatis menghitung: 
  *(Gaji Pokok + Uang Lembur) dikurangi (Potongan Cuti/Telat + Koperasi)*.
* **Langkah 2: Pengecekan Ulang (Oleh Manager)**
  Sebelum uang dikeluarkan, Manager akan melihat draf gaji dari HRD. Jika sudah pantas dan tidak ada yang salah, Manager klik **"Approve"**.
* **Langkah 3: Persetujuan Akhir (Oleh Finance)**
  Bagian Keuangan (Finance) melihat data yang sudah disetujui Manager. Finance kembali mengecek apakah uang di kas cukup. Jika iya, Finance juga klik **"Approve"**.
* **Langkah 4: Transfer & Lunas (Oleh Finance)**
  Finance secara fisik mentransfer uang dari bank perusahaan ke bank masing-masing karyawan. Setelah selesai transfer, Finance kembali ke sistem dan mengklik **"Pay (Bayar)"**. 
* **Langkah 5: Hore, Gajian! (Oleh Karyawan)**
  Seketika setelah Finance mengklik "Pay", semua karyawan akan mendapatkan notifikasi. Karyawan bisa login dari HP mereka dan melihat **Slip Gaji** bulan itu. Selesai!

---
*Panduan ini dibuat seringkas mungkin agar alur besar sistem mudah dipahami. Jika ada pertanyaan spesifik tentang cara menekan tombol-tombolnya, Anda dapat bertanya lebih lanjut.*
