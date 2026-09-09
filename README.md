# 🌏 Travel Tour - Sistem Pemesanan Wisata

> Platform booking wisata online berbasis web dengan dashboard admin lengkap dan sistem keamanan modern

[![PHP Version](https://img.shields.io/badge/PHP-8.2.4-blue.svg)](https://php.net)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.0.2-purple.svg)](https://getbootstrap.com)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-orange.svg)](https://mysql.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

---

## 📋 Deskripsi Proyek

**Travel Tour** adalah aplikasi web untuk manajemen pemesanan paket wisata yang memungkinkan:
- 👤 **User** untuk browse destinasi, melakukan booking, dan kelola profil
- 👨‍💼 **Admin** untuk kelola destinasi, monitoring booking, dan lihat statistik real-time dengan charts

---

## ✨ Fitur Utama

### 👤 User Features
- 🔐 Autentikasi (Register, Login, Forgot Password)
- 🏖️ Browse & filter destinasi wisata (Bali, Malang, Yogyakarta, dll)
- 📝 Booking online dengan form lengkap
- 👨‍💼 Profile management (edit nama, email, foto, password)
- 📋 Riwayat booking

### 👨‍💼 Admin Features
- 📊 Dashboard statistik (Total Destinasi, Booking, User, Revenue)
- 📈 Chart analytics (Booking trend & Top destinations dengan Chart.js)
- ➕ **CRUD Destinasi** lengkap (Create, Read, Update, Delete)
- 📋 Kelola booking dari semua user
- 🗺️ Upload gambar destinasi (BLOB storage)

---

## 🛠️ Teknologi

| Layer | Teknologi |
|-------|-----------|
| **Frontend** | HTML5, CSS3, JavaScript, Bootstrap 5.0.2, Chart.js, Font Awesome |
| **Backend** | PHP 8.2.4 (Native) |
| **Database** | MySQL/MariaDB |
| **Security** | Bcrypt (password hashing), Prepared Statements, Input Sanitization |
| **Server** | Apache (XAMPP) / PHP Built-in Server |

---

## 📂 Struktur Proyek

```
Travel Tour Remake/
├── 🔐 Authentication
│   ├── register.php, login.php, loginAdmin.php
│   └── forgot_password.php, reset_password.php
│
├── 👤 User Area
│   ├── index.php, daftarDestinasi.php, detailDestinasi.php
│   ├── booking.php, listBooking.php
│   └── profile.php, edit_profile.php, change_password.php
│
├── 👨‍💼 Admin Area
│   ├── dashboardAdmin.php (Dashboard + Charts)
│   ├── kelolaDestinasi.php, tambahDestinasi.php, editDestinasi.php
│   └── kelolaBooking.php
│
├── ⚙️ Backend Logic
│   ├── proses.php (Login, Register, Booking, Delete)
│   ├── proses_profile.php (Update profil, foto, password)
│   └── config_security.php (Security functions)
│
└── 💾 Database
    └── travel_db.sql (Full schema + sample data)
```

---

## 🚀 Instalasi & Setup

### Metode 1: XAMPP (Recommended)

**1. Install XAMPP**
```bash
Download dari: https://www.apachefriends.org/
Install dengan PHP 8.x
```

**2. Setup Project**
```bash
# Copy project ke folder XAMPP
C:\xampp\htdocs\Travel Tour Remake\

# Import Database
1. Buka phpMyAdmin: http://localhost/phpmyadmin
2. Create database: travel_db
3. Import file: travel_db.sql
```

**3. Konfigurasi Database**
```php
// File: koneksi.php
$host = "localhost";
$user = "root";
$password = "";  // Default XAMPP: kosong
$database = "travel_db";
```

**4. Run Application**
```bash
1. Start Apache & MySQL di XAMPP Control Panel
2. Buka browser: http://localhost/Travel%20Tour%20Remake/
```

---

### Metode 2: PHP Built-in Server

```bash
# Terminal / CMD
cd "C:\xampp\htdocs\Travel Tour Remake"
php -S localhost:8000

# Buka browser
http://localhost:8000/index.php
```

---

## 🔑 Sample Login Credentials

### User Account
| Username | Password |
|----------|----------|
| `amba`   | `tukam`  |
| `Amba`   | `sing`   |
| `amba2`  | `123`    |

### Admin Account
| Username | Password |
|----------|----------|
| `admin`  | `admin`  |

---

## 🗄️ Database Schema

**6 Tabel Utama:**

1. **`akun`** - User accounts (id_akun, nama_lengkap, username, password, email, foto_profil)
2. **`akun_admin`** - Admin accounts
3. **`destinasi`** - Travel destinations (nama, harga, kategori, kota, gambar BLOB, deskripsi)
4. **`pemesanan`** - Bookings (id_akun FK, id_destinasi FK, tanggal, jumlah_orang)
5. **`remember_tokens`** - Auto-login tokens
6. **`activity_log`** - User activity logging

**Relasi:**
```
pemesanan.id_akun → akun.id_akun
pemesanan.id_destinasi → destinasi.id_destinasi
```

---

## 🔒 Fitur Keamanan

✅ **Password Hashing** - Bcrypt dengan cost 12 (4096 iterasi)  
✅ **SQL Injection Prevention** - 100% Prepared Statements  
✅ **Input Sanitization** - htmlspecialchars, trim, stripslashes  
✅ **Session Management** - 30 menit timeout, session regeneration  
✅ **CSRF Protection** - Token validation  
✅ **Validation** - Client-side & server-side validation

**Contoh Implementasi:**
```php
// Password hashing
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
}

// Prepared statement
$stmt = $conn->prepare("SELECT * FROM akun WHERE username = ?");
$stmt->bind_param("s", $username);
```

---

## 📊 Demo Screenshots

### User Dashboard
![Homepage](docs/screenshots/homepage.png)

### Admin Dashboard
![Dashboard Admin](docs/screenshots/dashboard-admin.png)

### Booking Flow
![Booking Form](docs/screenshots/booking.png)

---

## 🎯 CRUD Operations

| Modul | Create | Read | Update | Delete |
|-------|--------|------|--------|--------|
| **Destinasi** | ✅ | ✅ | ✅ | ✅ |
| **Booking** | ✅ | ✅ | ❌ | ❌ |
| **User Profile** | ✅ | ✅ | ✅ | ❌ |

**Status: CRUD Destinasi 100% Complete**

---

## 🧪 Testing

```bash
# Test Authentication
✅ Register user baru
✅ Login dengan kredensial valid/invalid
✅ Logout & session management

# Test CRUD
✅ Tambah destinasi
✅ Edit destinasi
✅ Hapus destinasi
✅ Lihat detail

# Test Booking
✅ Submit booking
✅ Validasi form
✅ Lihat riwayat
```

---

## ⚠️ Common Issues & Solutions

### Error: `ERR_CONNECTION_REFUSED`
**Solusi:** Start XAMPP Apache/MySQL atau jalankan `php -S localhost:8000`

### Error: Foreign Key Constraint
**Solusi:** Pastikan `travel_db.sql` memiliki:
```sql
SET FOREIGN_KEY_CHECKS=0;
-- table creation
SET FOREIGN_KEY_CHECKS=1;
```

### Error: Session Expired
**Solusi:** Logout dan login ulang dengan kredensial yang valid

---

## 📈 Future Improvements

- 📧 Email notification untuk booking confirmation
- 💳 Payment gateway integration (Midtrans)
- ⭐ Rating & review system
- 📱 Progressive Web App (PWA)
- 🔄 Migrate to framework (Laravel/CodeIgniter)
- 🐳 Docker containerization

---

## 📚 Dokumentasi Lengkap

📄 **[PRESENTASI_PROYEK.md](PRESENTASI_PROYEK.md)** - Dokumentasi lengkap untuk presentasi dengan:
- 15 Slide presentasi
- Demo flow lengkap (User & Admin)
- Architecture diagram
- Security implementation details
- SQL queries & metrics
- Quick demo script (5 menit)

---

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

---

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 👨‍💻 Developer

**Your Name**  
- GitHub: [@laksalks30](https://github.com/laksalks30)
- Email: your.email@example.com

---

## 🙏 Acknowledgments

- Bootstrap Team untuk UI framework
- Chart.js untuk data visualization
- Font Awesome untuk icon library
- XAMPP Team untuk development environment

---

## 📞 Support

Jika ada pertanyaan atau issue, silakan:
- Open an issue di GitHub
- Email ke: support@example.com
- Check dokumentasi di `PRESENTASI_PROYEK.md`

---

<div align="center">

**⭐ Star this repo if you find it helpful!**

Made with ❤️ by laksana atmaja putra

</div>
