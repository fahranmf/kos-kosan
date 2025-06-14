# Sistem Informasi Kos-Kosan
---
[![forthebadge](https://forthebadge.com/images/badges/made-with-php.svg)](https://forthebadge.com) [![forthebadge](https://forthebadge.com/images/badges/uses-css.svg)](https://forthebadge.com) [![forthebadge](https://forthebadge.com/images/badges/uses-js.svg)](https://forthebadge.com)

## 🌟 Fitur 
- CRUD Daftar Kos
- Banyak dah, males jabarin 
## 🧰 Teknologi
- ⚙️ PHP OOP + MVC architecture (PHP v 8.2.0 or later)
- 🗄️ MySQL database
- 🎨 Vanilla CSS (tanpa framework)

## 🛠️ Instalasi



> Pastikan udah install: PHP, MySQL, Composer, dan web server seperti Laragon/XAMPP.

### 1. Clone Repository

```sh
git clone https://github.com/fahranmf/kos-kosan.git
cd kos-kosan
``` 
Jangan lupa pindahin foldernya ke htdocs (xampp) atau www (laragon).
```sh
C:\xampp\htdocs
```

atau
```sh
C:\laragon\www
```

### 2. Install Dependency 
```sh
composer install
```

### 3. Setup Database
Import file database/kos_kosan.sql ke phpMyAdmin atau langsung via MySQL CLI.

### 4. Konfigurasi .env
Buat file .env di root project:
```sh
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_email_app_password
```
> Emailnya harus pake gmail ya, pass nya juga harus aktifin app password dlu di gmailnya.

### 5. Run
Start apache & mysql di laragon atau xampp, lalu run di browser dengan url
```
http://localhost/index.php?page=home 
```
atau
```
http://localhost:8081/index.php?page=home
```


---
[![forthebadge](https://forthebadge.com/images/badges/it-works-dont-ask-me-how.svg)](https://forthebadge.com) [![forthebadge](https://forthebadge.com/images/badges/license-mit.svg)](https://forthebadge.com)
