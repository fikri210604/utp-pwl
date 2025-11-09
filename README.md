# 📬 Aplikasi Manajemen Surat

Aplikasi ini merupakan sistem **manajemen surat berbasis web** yang dibangun menggunakan framework **Laravel**.  
Tujuan utama dari aplikasi ini adalah membantu pengguna dalam **mengelola surat masuk dan surat keluar** secara digital dengan fitur pencatatan, penomoran otomatis, dan pengarsipan yang terstruktur.

---

## 🚀 Fitur Utama

### 📨 Manajemen Surat Masuk
Mencatat dan mengarsipkan surat yang diterima.  
<img width="1898" height="876" src="https://github.com/user-attachments/assets/1de6de31-07d8-4bfa-9587-62a655048be4" />
<img width="1884" height="1074" src="https://github.com/user-attachments/assets/9ee145a4-7cd7-4799-852c-5c480655e782" />
<img width="1901" height="867" src="https://github.com/user-attachments/assets/1778ad34-9e51-419a-9ea5-0c0721c9041c" />

---

### 📤 Manajemen Surat Keluar
Membuat, mencatat, dan menghasilkan file PDF surat yang akan dikirim.  
<img width="1896" height="867" src="https://github.com/user-attachments/assets/e70f80c6-c71e-4a6f-9f97-76fb8862e79b" />
<img width="1920" height="1209" src="https://github.com/user-attachments/assets/33200468-e301-4e81-9af0-c1763fbb5989" />
<img width="1920" height="1474" src="https://github.com/user-attachments/assets/9de9d80b-8e14-446c-9f3f-a6b1b4f2382f" />
<img width="1920" height="1044" src="https://github.com/user-attachments/assets/97a73c7a-c218-41a5-a540-2ed33cc0d3a8" />

---

### 🔢 Penomoran Surat Otomatis
Sistem otomatis untuk menghasilkan nomor surat berdasarkan klasifikasi.  
<img width="1920" height="1024" src="https://github.com/user-attachments/assets/a20c5151-e0eb-46a4-ad46-b4e05a59185d" />
<img width="1920" height="954" src="https://github.com/user-attachments/assets/1030ecd9-d881-4073-a85d-713ec2cbcb99" />
<img width="1895" height="870" src="https://github.com/user-attachments/assets/bb0922aa-29c7-40e3-9f74-369581aee5dd" />

---

### 🧾 Manajemen Master Data
**Data Penandatangan**  
<img width="1920" height="878" src="https://github.com/user-attachments/assets/f14224d1-2ec2-4ce8-a886-a1d3f2c49546" />
<img width="1920" height="878" src="https://github.com/user-attachments/assets/e94a643d-8a0a-4d84-8a17-67101beb3195" />
<img width="1920" height="878" src="https://github.com/user-attachments/assets/21d47479-c218-4c8f-9e99-d81866553184" />

**Data Perihal / Klasifikasi Surat**  
<img width="1920" height="1521" src="https://github.com/user-attachments/assets/f5a9fa80-cb88-4806-b7a4-8ddeb112858b" />
<img width="1920" height="878" src="https://github.com/user-attachments/assets/eb974b02-1e1a-4825-8df9-1634935028f9" />

---

### 🔐 Autentikasi Pengguna
Sistem login untuk membatasi akses pengguna.  
<img width="1902" height="756" src="https://github.com/user-attachments/assets/151ce901-79f3-4058-8d77-11d48f03cd59" />

---

### 👥 Manajemen Pengguna
Kelola akun yang memiliki akses terhadap sistem.  
<img width="1920" height="878" src="https://github.com/user-attachments/assets/49632b07-8f11-4677-ae30-dbfbd604ffa2" />
<img width="1920" height="878" src="https://github.com/user-attachments/assets/9851572a-0729-4656-a16e-6fb2fc0266ef" />
<img width="1920" height="878" src="https://github.com/user-attachments/assets/d0689399-ca6a-48ae-8583-a0827472160b" />

---

### 🔍 Pencarian Cepat
Fitur pencarian surat masuk dan keluar berdasarkan kata kunci tertentu.  
<img width="1557" height="316" src="https://github.com/user-attachments/assets/494e1c48-f4dd-46ab-accd-d9f50f6b0fa0" />

---

## 🧩 Teknologi yang Digunakan

| Komponen | Teknologi |
|-----------|------------|
| **Framework** | Laravel 10.x |
| **Backend** | PHP 8.x |
| **Frontend** | Vite, Bootstrap, Blade |
| **Database** | MySQL |
| **Testing** | PHPUnit |
| **Deployment** | Vercel / Server lokal (Laragon, XAMPP, dll) |

---

## 🗄️ Struktur & Daftar Tabel

| Nama Tabel | Keterangan |
|-------------|-------------|
| `users` | Menyimpan data pengguna sistem |
| `surat_masuks` | Menyimpan data surat masuk |
| `surat_keluars` | Menyimpan data surat keluar |
| `penandatangans` | Menyimpan data pejabat penandatangan |
| `surat_penandatangans` | Tabel pivot relasi antara surat keluar dan penandatangan |

---

## 👨‍💻 Informasi Proyek

| Informasi | Keterangan |
|------------|------------|
| **Nama Proyek** | Aplikasi Manajemen Surat |
| **Framework** | Laravel 10 |
| **Dibuat oleh** |  |
| • Ahmad Fikri Hanif | 2317051073 |
| • Intan Nur Laila | 2317051109 |
| • Muhammad Alvin | 2317051040 |
| • Rahayu Indah Lestari | 2317051073 |
| **Tujuan** | Implementasi konsep MVC dan CRUD dalam sistem manajemen surat digital |
| **Konteks** | Ujian Tengah Praktikum Pemrograman Web Lanjut |

---

## 📜 Lisensi

Proyek ini dibuat untuk **tujuan pembelajaran dan akademik**.  
Segala bentuk penyalinan kode tanpa izin pembuat tidak diperkenankan.

---
