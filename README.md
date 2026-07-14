# 🍽️ DigitalMenu

![CodeIgniter](https://img.shields.io/badge/CodeIgniter-4-red)
![PHP](https://img.shields.io/badge/PHP-8.2-blue)
![MySQL](https://img.shields.io/badge/MySQL-8.0-orange)
![Docker](https://img.shields.io/badge/Docker-Ready-2496ED?logo=docker&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green)

## 📖 Tentang Project

**DigitalMenu** merupakan aplikasi berbasis **CodeIgniter 4** yang digunakan untuk mengelola menu makanan dan minuman secara digital.

Aplikasi menyediakan Dashboard Admin untuk mengelola kategori menu, data makanan, gambar menu, serta halaman pelanggan untuk melihat menu dan melakukan pemesanan.

Project ini juga telah dikonfigurasi menggunakan **Docker** sehingga mudah dijalankan pada berbagai sistem operasi.

---

# ✨ Fitur

- 🔐 Login Admin
- 📊 Dashboard Admin
- 🍔 Manajemen Menu
- 📂 Manajemen Kategori
- 🖼 Upload Gambar Menu
- 🛒 Pemesanan Menu
- 👥 Halaman Customer
- 📱 Responsive Design
- 🐳 Support Docker

---

# 🛠 Teknologi

| Teknologi | Digunakan |
|-----------|-----------|
| Framework | CodeIgniter 4 |
| Bahasa | PHP 8 |
| Database | MySQL 8 |
| Frontend | HTML, CSS, Bootstrap |
| Container | Docker |
| Version Control | Git & GitHub |

---

# 📂 Struktur Project

```
DigitalMenu
│
├── app
├── public
├── tests
├── vendor
├── writable
├── Dockerfile
├── docker-compose.yml
├── composer.json
├── spark
└── README.md
```

---

# 🚀 Cara Menjalankan

## 1 Clone Repository

```bash
git clone https://github.com/miftahudin86/DigitalMenu.git
```

## 2 Masuk Folder

```bash
cd DigitalMenu
```

## 3 Jalankan Docker

```bash
docker compose up -d --build
```

## 4 Buka Browser

```
http://localhost:8080
```

---

# ⚙️ Konfigurasi Database

Project menggunakan MySQL yang berjalan pada Docker.

Default konfigurasi:

```
Host     : db
Database : digital_menu
Username : root
Password : rootpassword
```

Jika database masih kosong, lakukan:

- Import file SQL
- atau jalankan Migration CodeIgniter

---

# 📸 Screenshot

## Dashboard

> Tambahkan gambar dashboard di sini.

![Dashboard](docs/images/dashboard.png)

---

## Halaman Menu

![Menu](docs/images/menu.png)

---

## Docker Container

![Docker](docs/images/docker.png)

---

# 📦 Docker

Project dijalankan menggunakan dua container.

| Container | Fungsi |
|-----------|--------|
| digitalmenu_web | Web Server (PHP + Apache) |
| digitalmenu_db | Database MySQL |

Melihat container aktif:

```bash
docker ps
```

Menghentikan container:

```bash
docker compose down
```

Menjalankan kembali:

```bash
docker compose up -d
```

---

# 👨‍💻 Developer

**Miftah Udin**

Mahasiswa Teknik Informatika

Universitas Islam Sultan Agung (UNISSULA)

GitHub

https://github.com/miftahudin86

---

# 📄 Lisensi

Project ini dibuat untuk keperluan pembelajaran, praktikum, dan pengembangan aplikasi berbasis CodeIgniter 4 menggunakan Docker.